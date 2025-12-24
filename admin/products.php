<?php
require_once 'auth.php';
require_once '../config.php';

// Handle Product Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['toggle_availability'])) {
        $id = $_POST['id'];
        $current_status = $_POST['current_status'];
        $new_status = $current_status ? 0 : 1;
        $query = "UPDATE products SET is_available = $new_status WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Status menu berhasil diperbarui!'];
        }
    } elseif (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = $_POST['price'];
        $query = "UPDATE products SET name = '$name', price = '$price' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Menu berhasil diperbarui!'];
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM products WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Menu berhasil dihapus!'];
        }
    } elseif (isset($_POST['add_product'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];
        $description = mysqli_real_escape_string($conn, $_POST['description']); // Optional

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../img/";
            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $filename = time() . "_" . uniqid() . "." . $file_extension;
            $target_file = $target_dir . $filename;
            $db_image_url = "img/" . $filename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $query = "INSERT INTO products (category_id, name, description, price, image_url, is_available) 
                          VALUES ('$category_id', '$name', '$description', '$price', '$db_image_url', 1)";
                if (mysqli_query($conn, $query)) {
                    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Menu baru berhasil ditambahkan!'];
                }
            }
        }
    }

    header("Location: products.php");
    exit();
}

// Fetch Products
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          ORDER BY c.display_order, p.display_order";
$products = mysqli_query($conn, $query);

// Fetch Categories for Dropdown
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY display_order");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Admin UmahKawan</title>
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
        <a href="orders.php"><i class="fas fa-shopping-bag mr-2"></i> Pesanan</a>
        <a href="products.php" class="active"><i class="fas fa-utensils mr-2"></i> Menu</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kelola Menu</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPayloadModal">
                <i class="fas fa-plus"></i> Tambah Menu
            </button>
        </div>

        <div class="card-box">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Kategori</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($products)): ?>
                            <tr class="product-row" data-id="<?php echo $row['id']; ?>"
                                data-name="<?php echo htmlspecialchars($row['NAME']); ?>"
                                data-description="<?php echo htmlspecialchars($row['DESCRIPTION']); ?>"
                                data-category="<?php echo htmlspecialchars($row['category_name']); ?>"
                                data-price="<?php echo $row['price']; ?>"
                                data-image="<?php echo '../' . $row['image_url']; ?>"
                                data-available="<?php echo $row['is_available']; ?>">
                                <td>
                                    <img src="../<?php echo $row['image_url']; ?>" class="product-img" alt="img">
                                </td>
                                <td><span class="badge badge-secondary"><?php echo $row['category_name']; ?></span></td>

                                <!-- Form for editing -->
                                <form method="POST" id="form-<?php echo $row['id']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                                    <td>
                                        <input type="text" name="name" class="form-control form-control-sm"
                                            value="<?php echo htmlspecialchars($row['NAME']); ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="price" class="form-control form-control-sm"
                                            value="<?php echo $row['price']; ?>">
                                    </td>
                                    <td>
                                        <button type="submit" name="toggle_availability"
                                            class="btn btn-sm btn-<?php echo $row['is_available'] ? 'success' : 'danger'; ?>">
                                            <input type="hidden" name="current_status"
                                                value="<?php echo $row['is_available']; ?>">
                                            <?php echo $row['is_available'] ? 'Tersedia' : 'Habis'; ?>
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary view-btn" title="Lihat Detail"
                                            data-id="<?php echo $row['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="product-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info"
                                            title="Edit" data-id="<?php echo $row['id']; ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button type="submit" name="update_product" class="btn btn-sm btn-primary"
                                            title="Simpan Perubahan">
                                            <i class="fas fa-save"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                            data-id="<?php echo $row['id']; ?>" title="Hapus Menu">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Menu -->
    <div class="modal fade" id="addPayloadModal" tabindex="-1" role="dialog" aria-labelledby="addPayloadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPayloadModalLabel">Tambah Menu Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Menu</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control" required>
                                <?php
                                // Reset pointer for reuse if needed, or just iterate once as it's only used here
                                mysqli_data_seek($categories, 0);
                                while ($cat = mysqli_fetch_assoc($categories)):
                                    ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['NAME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" name="image" class="form-control-file" required accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Detail / Preview Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 text-center">
                            <img id="modal-product-image" src="" alt="img" class="img-fluid rounded">
                        </div>
                        <div class="col-md-7">
                            <h4 id="modal-product-name"></h4>
                            <p class="text-muted" id="modal-product-category"></p>
                            <h5 id="modal-product-price" class="text-primary"></h5>
                            <p id="modal-product-description"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="modal-frontend-link" class="btn btn-outline-primary" target="_blank">Lihat di
                        Frontend</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" id="modal-focus-edit" class="btn btn-primary">Edit Inline</button>
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

        // Delete Confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = function (e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const form = document.getElementById('form-' + id);

                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Menu yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4757',
                    cancelButtonColor: '#b2bec3',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create a hidden input to simulate the button click for delete
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'delete_product';
                        hiddenInput.value = 'true';
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            }
        });
    </script>

    <script>
        // Product detail / view and edit helpers
        $(document).ready(function () {
            // View button - open modal with details
            $('.view-btn').on('click', function () {
                var id = $(this).data('id');
                var $row = $('.product-row[data-id="' + id + '"]');
                var name = $row.data('name');
                var desc = $row.data('description');
                var category = $row.data('category');
                var price = $row.data('price');
                var image = $row.data('image');

                $('#modal-product-name').text(name);
                $('#modal-product-description').text(desc);
                $('#modal-product-category').text(category);
                $('#modal-product-price').text('Rp ' + Number(price).toLocaleString('id-ID'));
                $('#modal-product-image').attr('src', image);
                $('#modal-frontend-link').attr('href', '../product-detail.php?id=' + id);

                // store current id on modal for edit action
                $('#productDetailModal').data('current-id', id).modal('show');
            });

            // Edit (pencil) button - focus inline form
            $('.edit-btn').on('click', function () {
                var id = $(this).data('id');
                var $form = $('#form-' + id);
                if ($form.length) {
                    // scroll and highlight
                    $('html, body').animate({ scrollTop: $form.offset().top - 80 }, 300, function () {
                        var $tr = $form.closest('tr');
                        $tr.addClass('highlight-row');
                        setTimeout(function () { $tr.removeClass('highlight-row'); }, 2000);
                        // focus first input
                        $form.find('input[name="name"]').focus();
                    });
                }
            });

            // Modal 'Edit Inline' button
            $('#modal-focus-edit').on('click', function () {
                var id = $('#productDetailModal').data('current-id');
                if (id) {
                    $('#productDetailModal').modal('hide');
                    var $form = $('#form-' + id);
                    if ($form.length) {
                        $('html, body').animate({ scrollTop: $form.offset().top - 80 }, 300, function () {
                            var $tr = $form.closest('tr');
                            $tr.addClass('highlight-row');
                            setTimeout(function () { $tr.removeClass('highlight-row'); }, 2000);
                            $form.find('input[name="name"]').focus();
                        });
                    }
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