<?php
// product-detail.php
session_start();
include 'config.php';
include 'functions.php';

// Get cart count
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Ambil ID produk dari URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: menu.php');
    exit();
}

// Query untuk mengambil detail produk
$query = "SELECT p.*, c.name as category_name, c.id as category_id
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ? AND p.is_available = 1";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header('Location: menu.php');
    exit();
}

$product = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Query untuk related products (produk dari kategori yang sama)
$query_related = "SELECT id, name, description, price, image_url 
                  FROM products 
                  WHERE category_id = ? AND id != ? AND is_available = 1 
                  ORDER BY RAND() 
                  LIMIT 4";
$stmt_related = mysqli_prepare($conn, $query_related);
mysqli_stmt_bind_param($stmt_related, "ii", $product['category_id'], $product_id);
mysqli_stmt_execute($stmt_related);
$result_related = mysqli_stmt_get_result($stmt_related);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title><?php echo htmlspecialchars($product['name']); ?> - UmahKawan</title>
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
    <style>
        .product-image { width:100%; height:560px; object-fit:contain; display:block; }
        @media (max-width:767px){ .product-image{ height:auto; } }
    </style>
</head>
<body>
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
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="shop.php" id="dropdown04" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Shop</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="shop.php">Shop</a>
                            <a class="dropdown-item" href="product-detail.php">Single Product</a>
                            <a class="dropdown-item" href="checkout.php">Checkout</a>
                        </div>
                    </li>
                    <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                    <li class="nav-item cart">
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

    <section class="home-slider owl-carousel">
        <div class="slider-item" style="background-image: url(img/Landing\ Page.jpg);" data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center">
                    <div class="col-md-7 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-3 mt-5 bread">Product Detail</h1>
                        <p class="breadcrumbs">
                            <span class="mr-2"><a href="index.php">Home</a></span> 
                            <span>Product Detail</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mb-5 ftco-animate">
                    <a href="<?php echo htmlspecialchars(getImagePath($product['image_url'])); ?>" class="image-popup">
                        <img src="<?php echo htmlspecialchars(getImagePath($product['image_url'])); ?>" 
                             class="img-fluid product-image" 
                             alt="<?php echo htmlspecialchars($product['NAME']); ?>">
                    </a>
                </div>
                <div class="col-lg-5 product-details pl-md-5 ftco-animate">
                    <h3><?php echo htmlspecialchars($product['NAME']); ?></h3>
                    <p class="price"><span><?php echo formatRupiah($product['price']); ?></span></p>
                    <p><?php echo nl2br(htmlspecialchars($product['DESCRIPTION'])); ?></p>
                    
                    <form action="add-to-cart.php" method="POST" id="orderForm">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['NAME']); ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group d-flex">
                                    <div class="select-wrap">
                                        <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                        <select name="spicy_level" class="form-control">
                                            <option value="No Spicy">No Spicy</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Hot">Hot</option>
                                            <option value="Extra Spicy">Extra Spicy</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100"></div>
                            <div class="input-group col-md-6 d-flex mb-3">
                                <span class="input-group-btn mr-2">
                                    <button type="button" class="quantity-left-minus btn" data-type="minus">
                                        <i class="icon-minus"></i>
                                    </button>
                                </span>
                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
                                <span class="input-group-btn ml-2">
                                    <button type="button" class="quantity-right-plus btn" data-type="plus">
                                        <i class="icon-plus"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <p>
                            <button type="submit" name="action" value="add_to_cart" class="btn btn-primary py-3 px-5">
                                <i class="icon-shopping_cart"></i> Add to Cart
                            </button>
                            <button type="submit" name="action" value="buy_now" class="btn btn-success py-3 px-5 ml-2">
                                <i class="icon-credit-card"></i> Beli Sekarang
                            </button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
                            <p><a href="product-detail.php?id=<?php echo $related['id']; ?>" class="btn btn-primary btn-outline-primary">View Detail</a></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

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

    <div id="ftco-loader" class="show fullscreen">
        <svg class="circular" width="48px" height="48px">
            <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
            <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
        </svg>
    </div>

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
    $(document).ready(function(){
        var quantitiy=0;
        $('.quantity-right-plus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#quantity').val());
            $('#quantity').val(quantity + 1);
        });

        $('.quantity-left-minus').click(function(e){
            e.preventDefault();
            var quantity = parseInt($('#quantity').val());
            if(quantity>1){
                $('#quantity').val(quantity - 1);
            }
        });
    });
    </script>
</body>
</html>
<?php
mysqli_stmt_close($stmt_related);
mysqli_close($conn);
?>