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
            
            // Cek apakah item sudah ada di cart dengan spicy level yang sama
            if (isset($_SESSION['cart'][$cart_item_id])) {
                // Update quantity
                $_SESSION['cart'][$cart_item_id]['quantity'] += $quantity;
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
            
            $_SESSION['success_message'] = 'Produk berhasil ditambahkan ke keranjang!';
            
            // Redirect based on action
            if (isset($_POST['action']) && $_POST['action'] == 'buy_now') {
                header('Location: cart.php');
            } else {
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'menu.php'));
            }
            exit();
        }
        
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
header('Location: menu.php');
exit();
?>