<?php
// order-complete.php
session_start();
include 'config.php';
include 'functions.php';

// Get order number
$order_number = $_GET['order_number'] ?? '';

if (empty($order_number)) {
    header('Location: index.php');
    exit();
}

// ================= GET ORDER =================
$query = "SELECT * FROM orders WHERE order_number = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $order_number);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header('Location: index.php');
    exit();
}

$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// ================= GET ITEMS =================
$query_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt_items = mysqli_prepare($conn, $query_items);
mysqli_stmt_bind_param($stmt_items, "i", $order['id']);
mysqli_stmt_execute($stmt_items);
$result_items = mysqli_stmt_get_result($stmt_items);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Pesanan Berhasil - UmahKawan</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>


<body>
    <?php require "navbar.php"; ?>

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <!-- SUCCESS -->
                    <div class="text-center mb-5">
                        <div style="font-size:80px;color:#28a745;">✓</div>
                        <h2>Pesanan Berhasil Dibuat!</h2>
                        <p class="lead">Terima kasih telah berbelanja di UmahKawan</p>
                    </div>

                    <!-- ORDER INFO -->
                    <div class="ftco-bg-dark p-4 mb-4">
                        <h3>Informasi Pesanan</h3>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Nomor Pesanan</strong></div>
                            <div class="col-6 text-right">
                                <span class="badge badge-primary">
                                    <?= htmlspecialchars($order['order_number']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Tanggal</strong></div>
                            <div class="col-6 text-right">
                                <?= date('d F Y, H:i', strtotime($order['created_at'])) ?>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6"><strong>Status</strong></div>
                            <div class="col-6 text-right">
                                <span class="badge badge-warning" style="text-transform: uppercase;"><?= $order["order_status"] ?></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6"><strong>Metode Pembayaran</strong></div>
                            <div class="col-6 text-right">
                                <?= ucfirst($order['payment_method']) ?>
                            </div>
                        </div>

                        <hr>

                        <h5>Alamat Pengiriman</h5>
                        <p class="mb-1"><strong><?= htmlspecialchars($order['customer_name']) ?></strong></p>
                        <p class="mb-1"><?= htmlspecialchars($order['customer_phone']) ?></p>
                        <p class="mb-0"><?= htmlspecialchars($order['customer_address']) ?></p>
                        <p class="mb-0">
                            <?= htmlspecialchars($order['customer_city']) ?>
                            <?= $order['postal_code'] ? ' - ' . htmlspecialchars($order['postal_code']) : '' ?>
                        </p>
                    </div>

                    <!-- ITEMS -->
                    <div class="ftco-bg-dark p-4 mb-4">
                        <h3 class="mb-4 text-white">Detail Pesanan</h3>

                        <?php while ($item = mysqli_fetch_assoc($result_items)): ?>
                            <div class="border-bottom pb-3 mb-3">

                                <!-- Nama Produk -->
                                <strong class="text-white d-block">
                                    <?= htmlspecialchars($item['product_name']) ?>
                                </strong>

                                <!-- Catatan -->
                                <?php if (!empty($item['notes'])): ?>
                                    <small class="text-muted d-block mb-1">
                                        <?= htmlspecialchars($item['notes']) ?>
                                    </small>
                                <?php endif; ?>

                                <!-- Qty & Harga -->
                                <div class="d-flex justify-content-between small">
                                    <span>Qty: <?= $item['quantity'] ?></span>
                                    <span><?= formatRupiah($item['price']) ?></span>
                                </div>

                                <!-- Subtotal -->
                                <div class="text-right mt-1">
                                    <strong><?= formatRupiah($item['subtotal']) ?></strong>
                                </div>

                            </div>
                        <?php endwhile; ?>

                        <!-- RINGKASAN TOTAL -->
                        <div class="pt-3 text-right">
                            <div>Subtotal: <?= formatRupiah($order['subtotal']) ?></div>
                            <div>Ongkir: <?= formatRupiah($order['delivery_fee']) ?></div>
                            <div class="mt-2 text-white" style="font-size: 18px;">
                                <strong>Total: <?= formatRupiah($order['total']) ?></strong>
                            </div>
                        </div>
                    </div>



                    <!-- PAYMENT INFO -->
                    <?php if ($order['payment_method'] !== 'cash'): ?>
                        <div class="alert alert-info">
                            <h5>Instruksi Pembayaran</h5>
                            <?php if ($order['payment_method'] === 'transfer'): ?>
                                <p>Transfer ke BCA 1234567890 a.n UmahKawan</p>
                            <?php else: ?>
                                <p>Silakan scan QRIS yang diberikan admin</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-outline-primary">Beranda</a>
                        <a href="menu.php" class="btn btn-primary">Belanja Lagi</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if (!empty($_SESSION['order_success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pesanan Berhasil!',
                html: 'Nomor Pesanan: <strong><?= $order['order_number'] ?></strong><br>Total: <strong><?= formatRupiah($order['total']) ?></strong>',
                confirmButtonColor: '#28a745'
            });
        </script>
    <?php unset($_SESSION['order_success']);
    endif; ?>

</body>

</html>
<?php
mysqli_stmt_close($stmt_items);
mysqli_close($conn);
?>