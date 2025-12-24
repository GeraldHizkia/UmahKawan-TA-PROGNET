<?php
require_once 'auth.php';
require_once '../config.php';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $update_query = "UPDATE orders SET order_status = '$new_status' WHERE id = '$order_id'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Status pesanan berhasil diperbarui!'];
    }

    header("Location: orders.php");
    exit();
}

$query = "SELECT * FROM orders ORDER BY order_date DESC";
$orders = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin UmahKawan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <button class="hamburger" id="menuToggle"><i class="fas fa-bars"></i></button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <h4 class="text-center mb-4">UmahKawan</h4>
        <a href="index.php"><i class="fas fa-home mr-2"></i> Dashboard</a>
        <a href="orders.php" class="active"><i class="fas fa-shopping-bag mr-2"></i> Pesanan</a>
        <a href="products.php"><i class="fas fa-utensils mr-2"></i> Menu</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
    </div>

    <div class="content">
        <h2 class="mb-4">Kelola Pesanan</h2>

        <div class="card-box">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 15%">Order Info</th>
                            <th style="width: 20%">Pelanggan</th>
                            <th style="width: 30%">Menu Dipesan</th>
                            <th style="width: 15%">Total & Status</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($orders)):
                            $order_id = $row['id'];
                            $items_query = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = '$order_id'");
                            ?>
                            <tr class="order-row clickable" data-order-id="<?php echo $order_id; ?>">
                                <td>
                                    <strong>#<?php echo $row['order_number']; ?></strong><br>
                                    <small
                                        class="text-muted"><?php echo date('d M Y H:i', strtotime($row['order_date'])); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong><br>
                                    <small><i class="fas fa-phone"></i>
                                        <?php echo htmlspecialchars($row['customer_phone']); ?></small><br>
                                    <small
                                        class="text-muted"><?php echo htmlspecialchars($row['customer_address']); ?></small>
                                </td>
                                <td>
                                    <div class="items-list">
                                        <?php while ($item = mysqli_fetch_assoc($items_query)): ?>
                                            <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><?php echo $item['quantity']; ?>x
                                                    <?php echo htmlspecialchars($item['product_name']); ?></span>
                                                <span><?php echo number_format($item['subtotal'], 0, ',', '.'); ?></span>
                                            </div>
                                            <?php if (!empty($item['notes'])): ?>
                                                <small class="text-danger d-block mb-1">- Note:
                                                    <?php echo htmlspecialchars($item['notes']); ?></small>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    </div>
                                    <?php if (!empty($row['notes'])): ?>
                                        <div class="mt-2 text-danger">
                                            <strong>Catatan Order:</strong> <?php echo htmlspecialchars($row['notes']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong>Total: Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></strong><br>
                                    <?php if (isset($row['payment_method'])): ?>
                                        <small>Via: <?php echo ucfirst($row['payment_method']); ?></small><br>
                                    <?php endif; ?>
                                    <span class="badge badge-<?php
                                    echo $row['order_status'] == 'pending' ? 'warning' :
                                        ($row['order_status'] == 'confirmed' ? 'info' :
                                            ($row['order_status'] == 'completed' ? 'success' : 'secondary'));
                                    ?> mt-1">
                                        <?php echo ucfirst($row['order_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                        <div class="form-group mb-2">
                                            <select name="status" class="form-control form-control-sm">
                                                <option value="pending" <?php if ($row['order_status'] == 'pending')
                                                    echo 'selected'; ?>>Pending</option>
                                                <option value="confirmed" <?php if ($row['order_status'] == 'confirmed')
                                                    echo 'selected'; ?>>Konfirmasi</option>
                                                <option value="processing" <?php if ($row['order_status'] == 'processing')
                                                    echo 'selected'; ?>>Proses</option>
                                                <option value="delivering" <?php if ($row['order_status'] == 'delivering')
                                                    echo 'selected'; ?>>Antar</option>
                                                <option value="completed" <?php if ($row['order_status'] == 'completed')
                                                    echo 'selected'; ?>>Selesai</option>
                                                <option value="cancelled" <?php if ($row['order_status'] == 'cancelled')
                                                    echo 'selected'; ?>>Batal</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm btn-block">
                                            Update Status
                                        </button>
                                        <a href="https://wa.me/<?php echo preg_replace('/^0/', '62', $row['customer_phone']); ?>"
                                            target="_blank" class="btn btn-success btn-sm btn-block mt-2">
                                            <i class="fab fa-whatsapp"></i> Hubungi
                                        </a>
                                    </form>
                                </td>
                            </tr>

                            <!-- Hidden detail block for modal (used when clicking row) -->
                            <div id="order-detail-<?php echo $order_id; ?>" class="d-none order-detail-block">
                                <h5>Order #<?php echo $row['order_number']; ?></h5>
                                <p><small
                                        class="text-muted"><?php echo date('d M Y H:i', strtotime($row['order_date'])); ?></small>
                                </p>
                                <p><strong>Pelanggan:</strong> <?php echo htmlspecialchars($row['customer_name']); ?> <br>
                                    <small><i class="fas fa-phone"></i>
                                        <?php echo htmlspecialchars($row['customer_phone']); ?></small><br>
                                    <small
                                        class="text-muted"><?php echo htmlspecialchars($row['customer_address']); ?></small>
                                </p>

                                <div class="items-list">
                                    <?php
                                    $items_q2 = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = '$order_id'");
                                    while ($it = mysqli_fetch_assoc($items_q2)): ?>
                                        <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                            <span><?php echo $it['quantity']; ?>x
                                                <?php echo htmlspecialchars($it['product_name']); ?></span>
                                            <span>Rp <?php echo number_format($it['subtotal'], 0, ',', '.'); ?></span>
                                        </div>
                                        <?php if (!empty($it['notes'])): ?>
                                            <small class="text-danger d-block mb-1">- Note:
                                                <?php echo htmlspecialchars($it['notes']); ?></small>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>

                                <?php if (!empty($row['notes'])): ?>
                                    <div class="mt-2 text-danger"><strong>Catatan Order:</strong>
                                        <?php echo htmlspecialchars($row['notes']); ?></div>
                                <?php endif; ?>

                                <p class="mt-3"><strong>Total:</strong> Rp
                                    <?php echo number_format($row['total'], 0, ',', '.'); ?>
                                </p>
                                <p><strong>Status:</strong> <?php echo ucfirst($row['order_status']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Flash Message
        <?php if (isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['alert']['type']; ?>',
                title: '<?php echo $_SESSION['alert']['message']; ?>',
                showConfirmButton: false,
                timer: 1500
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>

        // Open order detail modal when row clicked (ignore clicks on form controls)
        $(document).ready(function () {
            $('.order-row.clickable').on('click', function (e) {
                if ($(e.target).closest('form, button, a, select, input, .btn').length) return;
                var id = $(this).data('order-id');
                var html = $('#order-detail-' + id).html();
                if (html) {
                    $('#orderDetailModal .modal-body').html(html);
                    $('#orderDetailModal').modal('show');
                }
            });

            // Hamburger toggle
            $('#menuToggle').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
            });

            // Close sidebar when overlay clicked
            $('#sidebarOverlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
            });

            // Close sidebar when link clicked
            $('#sidebar a').on('click', function () {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
            });
        });
    </script>

</body>

</html>