<?php
// add-to-cart.php - Handler untuk menambahkan produk ke keranjang
session_start();
include 'config.php';
include 'functions.php';

// Initialize cart jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);
    $spicy_level = $_POST['spicy_level'] ?? 'No Spicy';
    $notes = trim($_POST['notes'] ?? '');
    $action = $_POST['action'] ?? 'add_to_cart';

    if ($product_id > 0 && $quantity > 0) {
        // Query product info
        $query = "SELECT id, name, price, image_url FROM products WHERE id = ? AND is_available = 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($product = mysqli_fetch_assoc($result)) {
            // Generate unique cart item ID (product_id + spicy_level)
            $cart_item_id = $product_id . '_' . md5($spicy_level);

            $is_update = false;
            // Cek apakah item sudah ada di cart dengan spicy level yang sama
            if (isset($_SESSION['cart'][$cart_item_id])) {
                // Update quantity
                $_SESSION['cart'][$cart_item_id]['quantity'] += $quantity;
                $is_update = true;
            } else {
                // Tambah item baru
                $_SESSION['cart'][$cart_item_id] = [
                    'product_id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image_url' => $product['image_url'],
                    'quantity' => $quantity,
                    'spicy_level' => $spicy_level,
                    'notes' => $notes
                ];
            }

            // Set session untuk SweetAlert
            if ($action == 'buy_now') {
                $_SESSION['cart_action'] = 'buy_now';
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header('Location: cart.php');
                exit();
            } else {
                $_SESSION['cart_action'] = 'add_to_cart';
                $_SESSION['cart_message'] = $is_update ?
                    'Quantity produk berhasil ditambahkan!' :
                    'Produk berhasil ditambahkan ke keranjang!';
                $_SESSION['cart_product_name'] = $product['name'];

                mysqli_stmt_close($stmt);
                mysqli_close($conn);



                header('Location: menu.php');
                exit();
            }
        }

        mysqli_stmt_close($stmt);
    }
}

// Jika sampai sini, berarti ada error atau request tidak valid
mysqli_close($conn);
$_SESSION['error_message'] = 'Gagal menambahkan produk ke keranjang';
header('Location: menu.php');
exit();
