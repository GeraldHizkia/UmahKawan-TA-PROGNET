<?php
require_once 'auth.php';
require_once '../config.php';

// Get counts
$pending_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'");
$pending_orders = mysqli_fetch_assoc($pending_query)['count'];

$confirmed_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE order_status = 'confirmed'");
$confirmed_orders = mysqli_fetch_assoc($confirmed_query)['count'];

date_default_timezone_set('Asia/Makassar');
$today = date('Y-m-d');
$income_query = mysqli_query($conn, "SELECT SUM(total) as total FROM orders WHERE DATE(order_date) = '$today' AND order_status = 'completed'");
$today_income = mysqli_fetch_assoc($income_query)['total'] ?? 0;

// Get recent orders
$recent_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UmahKawan</title>
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
        <a href="index.php" class="active"><i class="fas fa-home mr-2"></i> Dashboard</a>
        <a href="orders.php"><i class="fas fa-shopping-bag mr-2"></i> Pesanan</a>
        <a href="products.php"><i class="fas fa-utensils mr-2"></i> Menu</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
    </div>

    <div class="content">
        <h2 class="mb-4">Dashboard Overview</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card-box bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3><?php echo $pending_orders; ?></h3>
                            <p class="mb-0">Pesanan Pending</p>
                        </div>
                        <i class="fas fa-clock stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3><?php echo $confirmed_orders; ?></h3>
                            <p class="mb-0">Pesanan Dikonfirmasi</p>
                        </div>
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-box bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3>Rp <?php echo number_format($today_income, 0, ',', '.'); ?></h3>
                            <p class="mb-0">Pendapatan Hari Ini</p>
                        </div>
                        <i class="fas fa-money-bill-wave stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box mt-4">
            <h4>Pesanan Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($recent_orders)): ?>
                            <tr>

                                <td>#<?php echo $row['order_number']; ?></td>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge badge-<?php
                                    echo $row['order_status'] == 'pending' ? 'warning' :
                                        ($row['order_status'] == 'confirmed' ? 'info' :
                                            ($row['order_status'] == 'completed' ? 'success' : 'secondary'));
                                    ?>">
                                        <?php echo ucfirst($row['order_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($row['order_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
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
    </script>
</body>