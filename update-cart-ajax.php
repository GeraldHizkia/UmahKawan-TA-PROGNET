<?php
// update-cart-ajax.php - AJAX handler untuk update cart
session_start();
include 'functions.php';

header('Content-Type: application/json');

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? 'update';

    if ($action == 'remove') {
        // Remove item
        $cart_item_id = $_POST['cart_item_id'] ?? '';

        if (isset($_SESSION['cart'][$cart_item_id])) {
            unset($_SESSION['cart'][$cart_item_id]);
            $response['success'] = true;
            $response['message'] = 'Produk berhasil dihapus';
        } else {
            $response['message'] = 'Item tidak ditemukan';
        }
    } else {
        // Update quantity
        $cart_item_id = $_POST['cart_item_id'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 1);

        if ($quantity < 1) {
            $response['message'] = 'Quantity minimal 1';
            echo json_encode($response);
            exit;
        }

        if (isset($_SESSION['cart'][$cart_item_id])) {
            // Update quantity
            $_SESSION['cart'][$cart_item_id]['quantity'] = $quantity;

            // Calculate item total
            $item = $_SESSION['cart'][$cart_item_id];
            $item_total = $item['price'] * $item['quantity'];

            // Calculate cart totals
            $subtotal = 0;
            foreach ($_SESSION['cart'] as $cart_item) {
                $subtotal += $cart_item['price'] * $cart_item['quantity'];
            }

            // Hitung jumlah unique items (bukan total quantity)
            $cart_count = count($_SESSION['cart']);

            $delivery_fee = $subtotal > 0 ? 5000 : 0;
            $discount = 0;
            $total = $subtotal + $delivery_fee - $discount;

            $response['success'] = true;
            $response['item_total'] = formatRupiah($item_total);
            $response['subtotal'] = formatRupiah($subtotal);
            $response['delivery'] = formatRupiah($delivery_fee);
            $response['total'] = formatRupiah($total);
            $response['cart_count'] = $cart_count;
            $response['message'] = 'Keranjang berhasil diupdate';
        } else {
            $response['message'] = 'Item tidak ditemukan di keranjang';
        }
    }
}

echo json_encode($response);
exit;
