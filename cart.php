<?php
// cart.php - Halaman Keranjang Belanja
session_start();
include 'config.php';
include 'functions.php';

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = $subtotal > 0 ? 5000 : 0;
$discount = 0;
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

    <style>
        /* Animasi untuk update */
        .table-success {
            transition: background-color 0.3s ease;
            background-color: #d4edda !important;
        }

        tr {
            transition: opacity 0.3s ease;
        }

        .quantity {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php require "navbar.php"; ?>

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
                        </div>
                    </div>
                </div>

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
    <?php include 'footer.php'; ?>
    <!-- Loader -->
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
        $(document).ready(function() {
            // Auto update saat quantity berubah
            $('.quantity').on('change', function() {
                var $input = $(this);
                var cartItemId = $input.attr('name').match(/\[(.*?)\]/)[1];
                var quantity = parseInt($input.val());

                // Validasi
                if (quantity < 1) {
                    quantity = 1;
                    $input.val(1);
                }

                // Update cart via AJAX
                updateCart(cartItemId, quantity, $input);
            });

            // Update dengan debounce untuk performa lebih baik
            var updateTimeout;
            $('.quantity').on('input', function() {
                var $input = $(this);
                clearTimeout(updateTimeout);

                updateTimeout = setTimeout(function() {
                    $input.trigger('change');
                }, 800); // Update setelah 800ms user berhenti mengetik
            });
        });

        function updateCart(cartItemId, quantity, $input) {
            // Show loading indicator
            var $row = $input.closest('tr');
            $row.css('opacity', '0.6');

            $.ajax({
                url: 'update-cart-ajax.php',
                method: 'POST',
                data: {
                    cart_item_id: cartItemId,
                    quantity: quantity
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Update item total
                        $row.find('.total').text(response.item_total);

                        // Update cart totals
                        $('.cart-total .d-flex:eq(0) span:last').text(response.subtotal);
                        $('.cart-total .d-flex:eq(1) span:last').text(response.delivery);
                        $('.cart-total .total-price span:last').text(response.total);

                        // Update cart counter di navbar
                        if (response.cart_count > 0) {
                            $('#cart-counter').text(response.cart_count).removeClass('empty');
                        } else {
                            $('#cart-counter').addClass('empty');
                        }

                        // Show success animation
                        $row.css('opacity', '1');


                    } else {
                        alert('Gagal update keranjang: ' + (response.message || 'Unknown error'));
                        $row.css('opacity', '1');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengupdate keranjang');
                    $row.css('opacity', '1');
                }
            });
        }

        function removeItem(cartItemId) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Produk akan dihapus dari keranjang",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'update-cart-ajax.php',
                        method: 'POST',
                        data: {
                            action: 'remove',
                            cart_item_id: cartItemId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'Produk berhasil dihapus dari keranjang',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Gagal menghapus produk'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus produk'
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>
<?php mysqli_close($conn); ?>