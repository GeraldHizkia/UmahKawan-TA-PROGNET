<?php
// cart.php - Halaman Keranjang Belanja
session_start();
include 'config.php';
include 'functions.php';

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get cart count
$cart_count = count($_SESSION['cart']);

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'update') {
        // Update quantity
        foreach ($_POST['quantity'] as $cart_item_id => $qty) {
            $qty = intval($qty);
            if ($qty > 0 && isset($_SESSION['cart'][$cart_item_id])) {
                $_SESSION['cart'][$cart_item_id]['quantity'] = $qty;
            }
        }
        $_SESSION['success_message'] = 'Keranjang berhasil diupdate!';
        header('Location: cart.php');
        exit();
    }
    
    if ($action == 'remove') {
        // Remove item
        $cart_item_id = $_POST['cart_item_id'] ?? '';
        if (isset($_SESSION['cart'][$cart_item_id])) {
            unset($_SESSION['cart'][$cart_item_id]);
            $_SESSION['success_message'] = 'Produk berhasil dihapus dari keranjang!';
        }
        header('Location: cart.php');
        exit();
    }
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = $subtotal > 0 ? 5000 : 0;
$discount = 0; // Bisa ditambahkan logika diskon
$total = $subtotal + $delivery_fee - $discount;

// Get success message
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// Query untuk related products
$query_related = "SELECT id, name, description, price, image_url 
                  FROM products 
                  WHERE is_available = 1 
                  ORDER BY RAND() 
                  LIMIT 4";
$result_related = mysqli_query($conn, $query_related);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cart - UmahKawan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">Umah<small>Kawan</small></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="menu.php" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="services.php" class="nav-link">Review</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="shop.php" id="dropdown04" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Shop</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="shop.php">Shop</a>
                            <a class="dropdown-item" href="product-detail.php">Single Product</a>
                            <a class="dropdown-item" href="checkout.php">Checkout</a>
                        </div>
                    </li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item cart active">
                        <a href="cart.php" class="nav-link">
                            <span class="icon icon-shopping_cart"></span>
                            <?php if ($cart_count > 0): ?>
                            <span class="badge badge-danger"><?php echo $cart_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(./img/bg-rujak2.png)" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Cart</h1>
                        <p class="breadcrumbs">
                            <span class="mr-2"><a href="index.php">Home</a></span>
                            <span>Cart</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($success_message)): ?>
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success_message; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Cart Section -->
    <section class="ftco-section ftco-cart">
        <div class="container">
            <?php if (empty($_SESSION['cart'])): ?>
            <!-- Empty Cart -->
            <div class="row justify-content-center">
                <div class="col-md-6 text-center ftco-animate">
                    <p style="font-size: 80px; color: #ccc;">🛒</p>
                    <h3>Keranjang Anda Kosong</h3>
                    <p class="text-muted">Belum ada produk di keranjang. Yuk mulai belanja!</p>
                    <a href="menu.php" class="btn btn-primary btn-lg mt-3">Lihat Menu</a>
                </div>
            </div>
            <?php else: ?>
            <form method="POST" action="cart.php">
                <input type="hidden" name="action" value="update">
                <div class="row">
                    <div class="col-md-12 ftco-animate">
                        <div class="cart-list">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr class="text-center">
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $cart_item_id => $item): ?>
                                    <tr class="text-center">
                                        <td class="product-remove">
                                            <button type="button" class="btn btn-link text-danger" 
                                                    onclick="removeItem('<?php echo $cart_item_id; ?>')">
                                                <span class="icon-close"></span>
                                            </button>
                                        </td>

                                        <td class="image-prod">
                                            <div class="img" style="background-image: url(<?php echo htmlspecialchars(getImagePath($item['image_url'])); ?>);"></div>
                                        </td>

                                        <td class="product-name">
                                            <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                            <p><?php echo htmlspecialchars($item['spicy_level']); ?></p>
                                            <?php if (!empty($item['notes'])): ?>
                                            <p class="text-muted"><small><?php echo htmlspecialchars($item['notes']); ?></small></p>
                                            <?php endif; ?>
                                        </td>

                                        <td class="price"><?php echo formatRupiah($item['price']); ?></td>

                                        <td class="quantity">
                                            <div class="input-group mb-3">
                                                <input type="number" 
                                                       name="quantity[<?php echo $cart_item_id; ?>]" 
                                                       class="quantity form-control input-number" 
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       min="1" 
                                                       max="100">
                                            </div>
                                        </td>

                                        <td class="total"><?php echo formatRupiah($item['price'] * $item['quantity']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <a href="menu.php" class="btn btn-outline-primary">
                                    <span class="icon-arrow-left"></span> Continue Shopping
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <span class="icon-refresh"></span> Update Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <div class="row justify-content-end">
                <div class="col col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
                    <div class="cart-total mb-3">
                        <h3>Cart Totals</h3>
                        <p class="d-flex">
                            <span>Subtotal</span>
                            <span><?php echo formatRupiah($subtotal); ?></span>
                        </p>
                        <p class="d-flex">
                            <span>Delivery</span>
                            <span><?php echo formatRupiah($delivery_fee); ?></span>
                        </p>
                        <?php if ($discount > 0): ?>
                        <p class="d-flex">
                            <span>Discount</span>
                            <span><?php echo formatRupiah($discount); ?></span>
                        </p>
                        <?php endif; ?>
                        <hr>
                        <p class="d-flex total-price">
                            <span>Total</span>
                            <span><?php echo formatRupiah($total); ?></span>
                        </p>
                    </div>
                    <p class="text-center">
                        <a href="checkout.php" class="btn btn-primary py-3 px-4">Proceed to Checkout</a>
                    </p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Related Products -->
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 heading-section ftco-animate text-center">
                    <span class="subheading">Discover</span>
                    <h2 class="mb-4">Related products</h2>
                    <p>Rujak first, worries later. Somewhere in the heart of Bali, behind the bustling streets and the warm smiles, lives a flavor that speaks without words — sweet, spicy, and unforgettable.</p>
                </div>
            </div>
            <div class="row">
                <?php while ($related = mysqli_fetch_assoc($result_related)): ?>
                <div class="col-md-3">
                    <div class="menu-entry">
                        <a href="product-detail.php?id=<?php echo $related['id']; ?>" 
                           class="img" 
                           style="background-image: url(<?php echo htmlspecialchars(getImagePath($related['image_url'])); ?>);"></a>
                        <div class="text text-center pt-4">
                            <h3><a href="product-detail.php?id=<?php echo $related['id']; ?>"><?php echo htmlspecialchars($related['name']); ?></a></h3>
                            <p><?php echo htmlspecialchars($related['description']); ?></p>
                            <p class="price"><span><?php echo formatRupiah($related['price']); ?></span></p>
                            <p>
                                <a href="product-detail.php?id=<?php echo $related['id']; ?>" 
                                   class="btn btn-primary btn-outline-primary">View Detail</a>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="ftco-footer ftco-section img">
        <div class="overlay"></div>
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Toko Rujak Umah Kawan</h2>
                        <p>Kami menyajikan rujak tradisional Bali dengan bahan-bahan segar dan sambal khas rumah.</p>
                        <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                            <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                            <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4 ml-md-4">
                        <h2 class="ftco-heading-2">Services</h2>
                        <ul class="list-unstyled">
                            <li><a href="#" class="py-2 d-block">Cooked</a></li>
                            <li><a href="#" class="py-2 d-block">Deliver</a></li>
                            <li><a href="#" class="py-2 d-block">Quality Foods</a></li>
                            <li><a href="#" class="py-2 d-block">Mixed</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                    <div class="ftco-footer-widget mb-4">
                        <h2 class="ftco-heading-2">Have a Questions?</h2>
                        <div class="block-23 mb-3">
                            <ul>
                                <li><span class="icon icon-map-marker"></span><span class="text">Jl. P. Bungin I No.14, Pedungan, Denpasar Selatan, Bali 80222</span></li>
                                <li><a href="#"><span class="icon icon-phone"></span><span class="text">+62895-3381-81468</span></a></li>
                                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">umahkawan@gmail.com</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | UmahKawan</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Loader -->
    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>

    <!-- Hidden form untuk remove item -->
    <form id="removeForm" method="POST" action="cart.php" style="display:none;">
        <input type="hidden" name="action" value="remove">
        <input type="hidden" name="cart_item_id" id="removeItemId">
    </form>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-migrate-3.0.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/scrollax.min.js"></script>
    <script src="js/main.js"></script>

    <script>
    function removeItem(cartItemId) {
        if (confirm('Hapus produk ini dari keranjang?')) {
            document.getElementById('removeItemId').value = cartItemId;
            document.getElementById('removeForm').submit();
        }
    }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>