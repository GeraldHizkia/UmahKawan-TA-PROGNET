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
// Set delivery fee in session if not already set
if (!isset($_SESSION['delivery_fee'])) {
    $_SESSION['delivery_fee'] = random_int(5000, 100000);
}
$delivery_fee = $_SESSION['delivery_fee'];
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

        /* Dark Gold Luxury Theme - Cart Items Only */
        .cart-item-wrapper {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            margin-bottom: 15px;
            border-radius: 12px;
            border: 1px solid rgba(196, 155, 99, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .cart-item-wrapper:hover {
            border-color: rgba(196, 155, 99, 0.4);
            box-shadow: 0 8px 24px rgba(196, 155, 99, 0.15);
            transform: translateY(-2px);
        }

        .cart-item {
            display: flex;
            padding: 20px;
            gap: 18px;
            position: relative;
        }

        .cart-item.updating {
            opacity: 0.6;
        }

        /* Product Image */
        .item-image {
            flex-shrink: 0;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            border: 2px solid rgba(196, 155, 99, 0.3);
            position: relative;
            overflow: hidden;
        }

        .item-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(196, 155, 99, 0.1) 100%);
        }

        /* Product Info */
        .item-info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .item-name {
            font-size: 16px;
            color: #ffffff;
            line-height: 1.4;
            margin-bottom: 8px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .item-variant {
            font-size: 13px;
            color: #c49b63;
            margin-bottom: 6px;
            font-weight: 400;
        }

        .item-notes {
            font-size: 12px;
            color: #888;
            font-style: italic;
            margin-top: 4px;
            padding: 6px 10px;
            background: rgba(196, 155, 99, 0.05);
            border-radius: 4px;
            border-left: 2px solid #c49b63;
        }

        /* Price and Quantity Row */
        .item-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(196, 155, 99, 0.15);
        }

        .item-price {
            font-size: 18px;
            font-weight: 600;
            color: #c49b63;
            letter-spacing: 0.5px;
        }

        /* Quantity Control - Luxury Minimalist */
        .quantity-control {
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(196, 155, 99, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }

        .quantity-control button {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            color: #c49b63;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 300;
        }

        .quantity-control button:hover:not(:disabled) {
            background: rgba(196, 155, 99, 0.15);
            color: #d4af6a;
        }

        .quantity-control button:disabled {
            color: #555;
            cursor: not-allowed;
            opacity: 0.4;
        }

        .quantity-control input {
            width: 50px;
            height: 36px;
            border: none;
            border-left: 1px solid rgba(196, 155, 99, 0.2);
            border-right: 1px solid rgba(196, 155, 99, 0.2);
            background: transparent;
            text-align: center;
            font-size: 15px;
            color: #ffffff;
            font-weight: 500;
        }

        /* Delete Button */
        .item-delete {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(196, 155, 99, 0.2);
            color: #888;
            font-size: 16px;
            cursor: pointer;
            padding: 8px;
            line-height: 1;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .item-delete:hover {
            background: rgba(220, 53, 69, 0.15);
            border-color: rgba(220, 53, 69, 0.4);
            color: #dc3545;
            transform: rotate(90deg);
        }

        /* Mobile Optimization */
        @media (max-width: 576px) {
            .item-image {
                width: 80px;
                height: 80px;
            }

            .item-name {
                font-size: 14px;
            }

            .item-price {
                font-size: 16px;
            }

            .quantity-control button {
                width: 32px;
                height: 32px;
            }

            .quantity-control input {
                width: 45px;
                height: 32px;
            }

            .cart-item {
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .item-bottom {
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .quantity-control {
                justify-content: center;
            }

            .item-price {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <?php require "navbar.php"; ?>

    <!-- Hero Section -->

    <section class="home-hero">
        <div class="hero-bg d-flex align-items-center" style="background-image: url(img/bg-rujak2.png);">
            <div class="overlay"></div>

            <div class="container text-center">
                <h1 class="mb-3 mt-5 bread">Cart</h1>

                <p class="breadcrumbs mb-0">
                    <a href="index.php">Home</a>
                    <span class="mx-2">/</span>
                    <span>Cart</span>
                </p>
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


    <!-- Cart Content -->
    <div class="container mt-3 cart-content">
        <?php if (empty($_SESSION['cart'])): ?>
            <!-- Empty Cart -->
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h3>Keranjang Anda Kosong</h3>
                <p>Yuk, isi dengan produk pilihan Anda!</p>
                <a href="menu.php" class="btn btn-primary">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <!-- Continue Shopping Button -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="menu.php" class="btn btn-outline-primary">
                        <span class="icon-arrow-left"></span> Continue Shopping
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8 col-md-12">
                    <?php foreach ($_SESSION['cart'] as $cart_item_id => $item): ?>
                        <div class="cart-item-wrapper ftco-animate" data-cart-id="<?php echo $cart_item_id; ?>">
                            <div class="cart-item">
                                <!-- Delete Button -->
                                <button type="button" class="item-delete" onclick="removeItem('<?php echo $cart_item_id; ?>')">
                                    <span class="icon-close"></span>
                                </button>

                                <!-- Product Image -->
                                <div class="item-image"
                                    style="background-image: url(<?php echo htmlspecialchars(getImagePath($item['image_url'])); ?>);">
                                </div>

                                <!-- Product Info -->
                                <div class="item-info">
                                    <div>
                                        <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                        <div class="item-variant"><?php echo htmlspecialchars($item['spicy_level']); ?>
                                        </div>
                                        <?php if (!empty($item['notes'])): ?>
                                            <div class="item-notes"><?php echo htmlspecialchars($item['notes']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="item-bottom">
                                        <div class="item-price"><?php echo formatRupiah($item['price']); ?></div>

                                        <div class="quantity-control">
                                            <button type="button" onclick="updateQuantity('<?php echo $cart_item_id; ?>', -1)"
                                                <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                                −
                                            </button>
                                            <input type="number" value="<?php echo $item['quantity']; ?>" readonly>
                                            <button type="button" onclick="updateQuantity('<?php echo $cart_item_id; ?>', 1)"
                                                <?php echo $item['quantity'] >= 100 ? 'disabled' : ''; ?>>
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary (Original) -->
                <div class="col-lg-4 col-md-12 mt-5 cart-wrap ftco-animate">
                    <div class="cart-total mb-3">
                        <h3>Cart Totals</h3>
                        <p class="d-flex">
                            <span>Subtotal</span>
                            <span id="summary-subtotal"><?php echo formatRupiah($subtotal); ?></span>
                        </p>
                        <p class="d-flex">
                            <span>Delivery</span>
                            <span id="summary-delivery"><?php echo formatRupiah($delivery_fee); ?></span>
                        </p>
                        <?php if ($discount > 0): ?>
                            <p class="d-flex">
                                <span>Discount</span>
                                <span id="summary-discount"><?php echo formatRupiah($discount); ?></span>
                            </p>
                        <?php endif; ?>
                        <hr>
                        <p class="d-flex total-price">
                            <span>Total</span>
                            <span id="summary-total"><?php echo formatRupiah($total); ?></span>
                        </p>
                    </div>
                    <p class="text-center">
                        <a href="checkout.php" class="btn btn-primary py-3 px-4">Proceed to Checkout</a>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <!-- Related Products -->
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
        function updateQuantity(cartItemId, change) {
            var $wrapper = $('.cart-item-wrapper[data-cart-id="' + cartItemId + '"]');
            var $input = $wrapper.find('input[type="number"]');
            var currentQty = parseInt($input.val());
            var newQty = currentQty + change;

            if (newQty < 1 || newQty > 100) return;

            $input.val(newQty);
            updateCart(cartItemId, newQty, $wrapper);
        }

        function updateButtonStates(cartItemId, quantity) {
            var $wrapper = $('.cart-item-wrapper[data-cart-id="' + cartItemId + '"]');
            var $buttons = $wrapper.find('.quantity-control button');
            var $minusBtn = $buttons.eq(0);
            var $plusBtn = $buttons.eq(1);

            // Update minus button
            if (quantity <= 1) {
                $minusBtn.prop('disabled', true);
            } else {
                $minusBtn.prop('disabled', false);
            }

            // Update plus button
            if (quantity >= 100) {
                $plusBtn.prop('disabled', true);
            } else {
                $plusBtn.prop('disabled', false);
            }
        }

        $(document).ready(function () {
            // Auto update saat quantity berubah
            $('.quantity').on('change', function () {
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
            $('.quantity').on('input', function () {
                var $input = $(this);
                clearTimeout(updateTimeout);

                updateTimeout = setTimeout(function () {
                    $input.trigger('change');
                }, 800); // Update setelah 800ms user berhenti mengetik
            });
        });

        function updateCart(cartItemId, quantity, $input) {
            // Show loading indicator
            var $wrapper = $input.closest('.cart-item-wrapper');
            $wrapper.css('opacity', '0.6');

            $.ajax({
                url: 'update-cart-ajax.php',
                method: 'POST',
                data: {
                    cart_item_id: cartItemId,
                    quantity: quantity
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Update cart totals
                        $('#summary-subtotal').text(response.subtotal);
                        $('#summary-delivery').text(response.delivery);
                        $('#summary-total').text(response.total);

                        // Update button states berdasarkan quantity baru
                        updateButtonStates(cartItemId, quantity);

                        // Update cart counter di navbar
                        if (response.cart_count > 0) {
                            $('#cart-counter').text(response.cart_count).removeClass('empty');
                        } else {
                            $('#cart-counter').addClass('empty');
                        }

                        // Show success animation
                        $wrapper.css('opacity', '1');

                    } else {
                        alert('Gagal update keranjang: ' + (response.message || 'Unknown error'));
                        $wrapper.css('opacity', '1');
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan saat mengupdate keranjang');
                    $wrapper.css('opacity', '1');
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
                        success: function (response) {
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
                        error: function () {
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