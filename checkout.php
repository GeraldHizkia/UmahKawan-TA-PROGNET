<?php
// checkout.php - Halaman Checkout
session_start();
include 'config.php';
include 'functions.php';

// Redirect jika cart kosong
if (empty($_SESSION['cart'])) {
  header('Location: cart.php');
  exit();
}

// Check if user logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

// Get user addresses if logged in
$addresses = [];
if ($is_logged_in) {
  $query = "SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($result)) {
    $addresses[] = $row;
  }
  mysqli_stmt_close($stmt);
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
  $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = 5000;
$total = $subtotal + $delivery_fee;

// Handle form submission
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $payment_method = $_POST['payment_method'] ?? 'cash';
  $order_notes = trim($_POST['order_notes'] ?? '');

  if ($is_logged_in) {
    // For logged in users
    $address_id = intval($_POST['address_id'] ?? 0);

    if ($address_id == 0) {
      $errors[] = "Silakan pilih alamat pengiriman";
    }

    if (empty($errors)) {
      // Create order for logged in user
      $order_number = 'RJK' . date('Ymd') . sprintf('%04d', rand(1, 9999));

      $query = "INSERT INTO orders (order_number, user_id, address_id, order_status, 
                     payment_status, payment_method, subtotal, delivery_fee, total, notes)
                     VALUES (?, ?, ?, 'pending', 'unpaid', ?, ?, ?, ?, ?)";

      $stmt = mysqli_prepare($conn, $query);
      mysqli_stmt_bind_param(
        $stmt,
        "siisddds",
        $order_number,
        $user_id,
        $address_id,
        $payment_method,
        $subtotal,
        $delivery_fee,
        $total,
        $order_notes
      );

      if (mysqli_stmt_execute($stmt)) {
        $order_id = mysqli_insert_id($conn);

        // Insert order items
        foreach ($_SESSION['cart'] as $item) {
          $item_notes = "Pedas: " . $item['spicy_level'];
          if (!empty($item['notes'])) {
            $item_notes .= " | " . $item['notes'];
          }

          $item_subtotal = $item['price'] * $item['quantity'];

          $query_item = "INSERT INTO order_items 
                                  (order_id, product_id, product_name, quantity, price, subtotal, notes)
                                  VALUES (?, ?, ?, ?, ?, ?, ?)";
          $stmt_item = mysqli_prepare($conn, $query_item);
          mysqli_stmt_bind_param(
            $stmt_item,
            "iisidds",
            $order_id,
            $item['product_id'],
            $item['name'],
            $item['quantity'],
            $item['price'],
            $item_subtotal,
            $item_notes
          );
          mysqli_stmt_execute($stmt_item);
          mysqli_stmt_close($stmt_item);
        }

        // Clear cart
        $_SESSION['cart'] = [];

        // Redirect to success page
        header("Location: order-complete.php?order_number=$order_number");
        exit();
      }

      mysqli_stmt_close($stmt);
    }
  } else {
    // For guest users - redirect to login/register
    $_SESSION['checkout_redirect'] = true;
    header('Location: login.php');
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>UmahKawan</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet" />

  <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css" />
  <link rel="stylesheet" href="css/animate.css" />

  <link rel="stylesheet" href="css/owl.carousel.min.css" />
  <link rel="stylesheet" href="css/owl.theme.default.min.css" />
  <link rel="stylesheet" href="css/magnific-popup.css" />

  <link rel="stylesheet" href="css/aos.css" />

  <link rel="stylesheet" href="css/ionicons.min.css" />

  <link rel="stylesheet" href="css/flaticon.css" />
  <link rel="stylesheet" href="css/icomoon.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <?php require "navbar.php"; ?>


  <section class="home-hero">
    <div class="hero-bg d-flex align-items-center" style="background-image: url(img/bg_packaging.png);">
      <div class="overlay"></div>

      <div class="container text-center">
        <h1 class="mb-3 mt-5 bread">Checkout</h1>

        <p class="breadcrumbs mb-0">
          <a href="index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Checkout</span>
        </p>
      </div>
    </div>
  </section>

  <section class="ftco-section">
    <div class="container">
      <form action="process-checkout.php" method="POST" id="checkoutForm">
        <div class="row">
          <!-- Informasi Pengiriman -->
          <div class="col-md-7 mb-4">
            <div class="ftco-bg-dark p-4 p-md-5">
              <h3 class="mb-4">Informasi Pengiriman</h3>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="phone">No. Telepon *</label>
                    <input type="tel" name="phone" id="phone" class="form-control" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="address">Alamat Lengkap *</label>
                    <textarea name="address" id="address" rows="3" class="form-control" required></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="city">Kota *</label>
                    <input type="text" name="city" id="city" class="form-control" value="Denpasar" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="postal_code">Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code" class="form-control">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="notes">Catatan Pesanan (Opsional)</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control"
                      placeholder="Contoh: Mohon hubungi sebelum antar"></textarea>
                  </div>
                </div>
              </div>

              <?php if (!empty($addresses)): ?>
                <hr class="my-4">
                <h5 class="mb-3">Atau Pilih Alamat Tersimpan:</h5>
                <?php foreach ($addresses as $address): ?>
                  <div class="custom-control custom-radio mb-3">
                    <input type="radio" id="addr_<?php echo $address['id']; ?>" name="saved_address_id"
                      value="<?php echo $address['id']; ?>" class="custom-control-input" <?php echo $address['is_default'] ? 'checked' : ''; ?>>
                    <label class="custom-control-label" for="addr_<?php echo $address['id']; ?>">
                      <strong><?php echo htmlspecialchars($address['label']); ?></strong><br>
                      <small>
                        <?php echo htmlspecialchars($address['recipient_name']); ?> -
                        <?php echo htmlspecialchars($address['phone']); ?><br>
                        <?php echo htmlspecialchars($address['address_line']); ?>
                      </small>
                    </label>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>

          <!-- Order Summary & Payment -->
          <div class="col-md-5">
            <!-- Order Summary -->
            <div class="cart-detail ftco-bg-dark p-4 mb-4">
              <h3 class="mb-4">Ringkasan Pesanan</h3>

              <p class="d-flex justify-content-between">
                <span>Subtotal</span>
                <span><?php echo formatRupiah($subtotal); ?></span>
              </p>
              <p class="d-flex justify-content-between">
                <span>Ongkos Kirim</span>
                <span><?php echo formatRupiah($delivery_fee); ?></span>
              </p>
              <hr>
              <p class="d-flex justify-content-between total-price">
                <span><strong>Total</strong></span>
                <span><strong><?php echo formatRupiah($total); ?></strong></span>
              </p>
            </div>

            <!-- Payment Method -->
            <div class="cart-detail ftco-bg-dark p-4">
              <h3 class="mb-4">Metode Pembayaran</h3>

              <div class="custom-control custom-radio mb-3">
                <input type="radio" id="payment_cash" name="payment_method" value="cash" class="custom-control-input"
                  checked>
                <label class="custom-control-label" for="payment_cash">
                  💵 Cash on Delivery (COD)
                </label>
              </div>

              <div class="custom-control custom-radio mb-3">
                <input type="radio" id="payment_transfer" name="payment_method" value="transfer"
                  class="custom-control-input">
                <label class="custom-control-label" for="payment_transfer">
                  🏦 Transfer Bank
                </label>
              </div>

              <div class="custom-control custom-radio mb-4">
                <input type="radio" id="payment_qris" name="payment_method" value="qris" class="custom-control-input">
                <label class="custom-control-label" for="payment_qris">
                  📱 QRIS
                </label>
              </div>

              <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input" id="agree_terms" required>
                <label class="custom-control-label" for="agree_terms">
                  Saya setuju dengan <a class="text-primary" onclick="showTerms()">syarat dan ketentuan</a>
                </label>
              </div>

              <button type="submit" class="btn btn-primary btn-block py-3">
                <i class="icon-credit-card"></i> Buat Pesanan
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>



  <?php include 'footer.php'; ?>

  <!-- loader -->
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



  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/main.js"></script>

  <script>
    function showTerms() {
      Swal.fire({
        title: 'Syarat & Ketentuan',
        html: `
      <div style="text-align:left; font-size:14px;">
        <p><strong>1. Ketentuan Umum</strong></p>
        <p>
          Dengan menggunakan layanan ini, pengguna dianggap telah membaca,
          memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.
        </p>

        <p><strong>2. Penggunaan Layanan</strong></p>
        <ul>
          <li>Layanan hanya boleh digunakan untuk tujuan yang sah.</li>
          <li>Pengguna bertanggung jawab atas data yang diberikan.</li>
          <li>Dilarang menyalahgunakan sistem untuk kepentingan pribadi.</li>
        </ul>

        <p><strong>3. Data dan Privasi</strong></p>
        <p>
          Data pengguna akan disimpan dan digunakan sesuai kebijakan privasi
          dan tidak akan disebarluaskan tanpa izin.
        </p>

        <p><strong>4. Perubahan Layanan</strong></p>
        <p>
          Pengelola berhak mengubah, menambah, atau menghapus layanan
          tanpa pemberitahuan terlebih dahulu.
        </p>

        <p><strong>5. Penutup</strong></p>
        <p>
          Dengan melanjutkan penggunaan layanan, pengguna menyetujui
          seluruh ketentuan yang telah ditetapkan.
        </p>
      </div>
    `,
        icon: 'info',
        width: 700,
        showCancelButton: true,
        confirmButtonText: 'Saya Setuju',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      })
    }


    // Auto-fill dari alamat tersimpan
    document.querySelectorAll('input[name="saved_address_id"]').forEach(radio => {
      radio.addEventListener('change', function () {
        if (this.checked) {
          // Ambil data dari label
          const label = this.parentElement.querySelector('label');
          const text = label.textContent;

          // Disable manual input
          document.getElementById('name').disabled = true;
          document.getElementById('phone').disabled = true;
          document.getElementById('address').disabled = true;

          // Optional: Clear manual input
          // document.getElementById('name').value = '';
        }
      });
    });

    // Re-enable manual input jika user mulai mengetik
    ['name', 'phone', 'address'].forEach(id => {
      document.getElementById(id).addEventListener('focus', function () {
        document.querySelectorAll('input[name="saved_address_id"]').forEach(r => r.checked = false);
        this.disabled = false;
      });
    });



    $(document).ready(function () {
      var quantitiy = 0;
      $(".quantity-right-plus").click(function (e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($("#quantity").val());

        // If is not undefined

        $("#quantity").val(quantity + 1);

        // Increment
      });

      $(".quantity-left-minus").click(function (e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($("#quantity").val());

        // If is not undefined

        // Increment
        if (quantity > 0) {
          $("#quantity").val(quantity - 1);
        }
      });
    });
  </script>
</body>

</html>