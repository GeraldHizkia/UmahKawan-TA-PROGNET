<link rel="stylesheet" href="./css/bootstrap.min.css" />
<link rel="stylesheet" href="./css/animate.css" />

<link rel="stylesheet" href="./css/owl.carousel.min.css" />
<link rel="stylesheet" href="./css/owl.theme.default.min.css" />
<link rel="stylesheet" href="./css/magnific-popup.css" />

<link rel="stylesheet" href="./css/aos.css" />

<link rel="stylesheet" href="./css/ionicons.min.css" />

<link rel="stylesheet" href="./css/flaticon.css" />
<link rel="stylesheet" href="./css/icomoon.css" />
<link rel="stylesheet" href="./css/style.css" />
<link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">

<style>
    .cart-badge-wrapper {
        position: relative;
        display: inline-block;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
        line-height: 1;
    }

    .cart-badge.empty {
        display: none;
    }
</style>

<?php
include("active.php");

// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hitung jumlah jenis produk di cart (bukan total quantity)
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']); // Hitung jumlah unique items
}
?>

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Umah<small>Kawan</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?= active('index.php'); ?>">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item <?= active('menu.php') ?>">
                    <a href="menu.php" class="nav-link">Menu</a>
                </li>
                <li class="nav-item <?= active('services.php'); ?>">
                    <a href="services.php" class="nav-link">Review</a>
                </li>

                <li class="nav-item <?= active('about.php'); ?>">
                    <a href="about.php" class="nav-link">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="./cart.php" id="dropdown04" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">Shop</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="shop.php">Shop</a>
                        <a class="dropdown-item" href="checkout.php">Checkout</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="contact.php" class="nav-link">Contact</a>
                </li>
                <li class="nav-item cart">
                    <a href="cart.php" class="nav-link">
                        <span class="cart-badge-wrapper">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart-badge <?= $cart_count == 0 ? 'empty' : '' ?>" id="cart-counter">
                                <?= $cart_count ?>
                            </span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://kit.fontawesome.com/66f1b6c083.js" crossorigin="anonymous"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
// Cek apakah ada pesan cart
if (isset($_SESSION['cart_action'])):
    $action = $_SESSION['cart_action'];

    if ($action == 'add_to_cart' && isset($_SESSION['cart_message'])):
        $message = $_SESSION['cart_message'];
        $product_name = $_SESSION['cart_product_name'] ?? '';
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    timer: 1500,
                    timerProgressBar: true,
                })
            });
        </script>
        <?php
    endif;

    // Clear session messages
    unset($_SESSION['cart_action']);
    unset($_SESSION['cart_message']);
    unset($_SESSION['cart_product_name']);
endif;

// Success message untuk halaman lain
if (isset($_SESSION['success_message'])):
    $success_msg = $_SESSION['success_message'];
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo htmlspecialchars($success_msg); ?>',
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
    <?php
    unset($_SESSION['success_message']);
endif;

// Error message
if (isset($_SESSION['error_message'])):
    $error_msg = $_SESSION['error_message'];
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo htmlspecialchars($error_msg); ?>',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
    <?php
    unset($_SESSION['error_message']);
endif;
?>

<!-- END nav -->