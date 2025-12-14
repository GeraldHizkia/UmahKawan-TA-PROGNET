<?php
// process-checkout.php
ob_start();
session_start();
include 'config.php';

// ================= CEK CART =================
if (empty($_SESSION['cart'])) {
    $_SESSION['error_message'] = 'Keranjang Anda kosong';
    header('Location: cart.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit();
}

// ================= DATA FORM =================
$customer_name    = trim($_POST['name'] ?? '');
$customer_phone   = trim($_POST['phone'] ?? '');
$customer_email   = trim($_POST['email'] ?? '');
$customer_address = trim($_POST['address'] ?? '');
$customer_city    = trim($_POST['city'] ?? 'Denpasar');
$postal_code      = trim($_POST['postal_code'] ?? '');
$notes            = trim($_POST['notes'] ?? '');

// Payment
$payment_method = $_POST['payment_method'] ?? 'cash';
$valid_payment_methods = ['cash', 'transfer', 'qris'];
if (!in_array($payment_method, $valid_payment_methods)) {
    $payment_method = 'cash';
}

// ================= VALIDASI =================
$errors = [];
if ($customer_name === '')    $errors[] = 'Nama harus diisi';
if ($customer_phone === '')   $errors[] = 'Nomor HP harus diisi';
if ($customer_address === '') $errors[] = 'Alamat harus diisi';

if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: checkout.php');
    exit();
}

// ================= HITUNG TOTAL =================
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$delivery_fee = 5000;
$total = $subtotal + $delivery_fee;

// ================= TRANSACTION =================
mysqli_begin_transaction($conn);

try {
    // ================= ORDER NUMBER =================
    $order_number = 'UK-' . date('Ymd') . '-' . rand(1000, 9999);

    // ================= INSERT ORDERS =================
    $qOrder = "
        INSERT INTO orders (
            order_number,
            customer_name,
            customer_phone,
            customer_email,
            customer_address,
            customer_city,
            postal_code,
            payment_method,
            subtotal,
            delivery_fee,
            total,
            notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmtOrder = mysqli_prepare($conn, $qOrder);
    mysqli_stmt_bind_param(
        $stmtOrder,
        "ssssssssddds",
        $order_number,
        $customer_name,
        $customer_phone,
        $customer_email,
        $customer_address,
        $customer_city,
        $postal_code,
        $payment_method,
        $subtotal,
        $delivery_fee,
        $total,
        $notes
    );

    if (!mysqli_stmt_execute($stmtOrder)) {
        throw new Exception('Gagal menyimpan pesanan');
    }

    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmtOrder);

    // ================= INSERT ORDER ITEMS =================
    $qItem = "
        INSERT INTO order_items (
            order_id,
            product_id,
            product_name,
            quantity,
            price,
            subtotal,
            notes
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmtItem = mysqli_prepare($conn, $qItem);

    foreach ($_SESSION['cart'] as $item) {
        $item_subtotal = $item['price'] * $item['quantity'];
        $item_notes = '';

        if (!empty($item['spicy_level'])) {
            $item_notes = 'Level Pedas: ' . $item['spicy_level'];
        }
        if (!empty($item['notes'])) {
            $item_notes .= ' | ' . $item['notes'];
        }

        mysqli_stmt_bind_param(
            $stmtItem,
            "iisidds",
            $order_id,
            $item['product_id'],
            $item['name'],
            $item['quantity'],
            $item['price'],
            $item_subtotal,
            $item_notes
        );

        if (!mysqli_stmt_execute($stmtItem)) {
            throw new Exception('Gagal menyimpan item pesanan');
        }
    }

    mysqli_stmt_close($stmtItem);

    // ================= INSERT PAYMENTS =================
    $qPay = "
        INSERT INTO payments (
            order_id,
            amount,
            payment_method,
            notes
        ) VALUES (?, ?, ?, ?)
    ";

    $stmtPay = mysqli_prepare($conn, $qPay);
    mysqli_stmt_bind_param(
        $stmtPay,
        "idss",
        $order_id,
        $total,
        $payment_method,
        $notes
    );
    mysqli_stmt_execute($stmtPay);
    mysqli_stmt_close($stmtPay);

    // ================= COMMIT =================
    mysqli_commit($conn);

    // Clear cart
    $_SESSION['cart'] = [];

    // Success info
    $_SESSION['order_success'] = true;
    $_SESSION['order_number'] = $order_number;
    $_SESSION['order_total'] = $total;

    ob_end_clean();
    header("Location: order-complete.php?order_number=$order_number");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['error_message'] = 'Checkout gagal: ' . $e->getMessage();
    ob_end_clean();
    header('Location: checkout.php');
    exit();
}
