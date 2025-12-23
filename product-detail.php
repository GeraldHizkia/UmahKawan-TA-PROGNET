<?php
// product-detail.php
session_start();
include 'config.php';
include 'functions.php';

// Get cart count

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
    <title><?php echo htmlspecialchars($product['NAME']); ?> - UmahKawan</title>
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
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <style>
        .product-image {
            width: 100%;
            height: 560px;
            object-fit: contain;
            display: block;
        }

        @media (max-width:767px) {
            .product-image {
                height: auto;
            }
        }
    </style>
</head>

<body>
    <?php require "navbar.php"; ?>



    <section class="home-hero">
        <div class="hero-bg d-flex align-items-center" style="background-image: url(img/Landing\ Page.jpg);">
            <div class="overlay"></div>

            <div class="container text-center">
                <h1 class="mb-3 mt-5 bread">Detail Product</h1>

                <p class="breadcrumbs mb-0">
                    <a href="index.php">Home</a>
                    <span class="mx-2">/</span>
                    <span>Product Detail</span>
                </p>
            </div>
        </div>
    </section>



    <section class="ftco-section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mb-5 ftco-animate">
                    <a href="<?php echo htmlspecialchars(getImagePath($product['image_url'])); ?>" class="image-popup">
                        <img src="<?php echo htmlspecialchars(getImagePath($product['image_url'])); ?>"
                            class="img-fluid product-image" alt="<?php echo htmlspecialchars($product['NAME']); ?>">
                    </a>
                </div>
                <div class="col-lg-5 product-details pl-md-5 ftco-animate">
                    <h3><?php echo htmlspecialchars($product['NAME']); ?></h3>
                    <p class="price"><span><?php echo formatRupiah($product['price']); ?></span></p>
                    <p><?php echo nl2br(htmlspecialchars($product['DESCRIPTION'])); ?></p>

                    <form action="add-to-cart.php" method="POST" id="orderForm">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name"
                            value="<?php echo htmlspecialchars($product['NAME']); ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group d-flex">
                                    <div class="select-wrap" <?= $product['category_id'] == 4 ? 'style="display:none;"' : '' ?>>
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
                                <input type="text" id="quantity" name="quantity" class="form-control input-number"
                                    value="1" min="1" max="100">
                                <span class="input-group-btn ml-2">
                                    <button type="button" class="quantity-right-plus btn" data-type="plus">
                                        <i class="icon-plus"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <p>
                            <button type="submit" name="action" value="add_to_cart" class="btn btn-cart py-3 px-5">
                                <i class="icon-shopping_cart"></i> Add to Cart
                            </button>
                            <button type="submit" name="action" value="buy_now" class="btn btn-buy py-3 px-5 ml-2">
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
                    <p>Rujak first, worries later. Somewhere in the heart of Bali, behind the bustling streets and the
                        warm smiles, lives a flavor that speaks without words — sweet, spicy, and unforgettable.</p>
                </div>
            </div>
            <div class="row">
                <?php while ($related = mysqli_fetch_assoc($result_related)): ?>
                    <div class="col-md-3">
                        <div class="menu-entry">
                            <a href="product-detail.php?id=<?php echo $related['id']; ?>" class="img"
                                style="background-image: url(<?php echo htmlspecialchars(getImagePath($related['image_url'])); ?>);"></a>
                            <div class="text text-center pt-4">
                                <h3><a
                                        href="product-detail.php?id=<?php echo $related['id']; ?>"><?php echo htmlspecialchars($related['name']); ?></a>
                                </h3>
                                <p><?php echo htmlspecialchars($related['description']); ?></p>
                                <p class="price"><span><?php echo formatRupiah($related['price']); ?></span></p>
                                <p><a href="product-detail.php?id=<?php echo $related['id']; ?>"
                                        class="btn btn-primary btn-outline-primary">View Detail</a></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <?php include 'loader.php'; ?>

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

        $(document).ready(function () {
            // Auto scroll ke section product setelah 1 detik
            setTimeout(function () {
                $('html, body').animate({
                    scrollTop: $('#product').offset().top - 50 // -80 untuk offset navbar
                }, 300); // 800ms durasi animasi scroll
            }, 700); // 1000ms = 1 detik delay

            // Quantity buttons handler
            var quantitiy = 0;
            $('.quantity-right-plus').click(function (e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                $('#quantity').val(quantity + 1);
                // console.log($('#quantity').val())
            });

            $('.quantity-left-minus').click(function (e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                if (quantity > 1) {
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