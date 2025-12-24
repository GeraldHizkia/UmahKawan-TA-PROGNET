<?php
require_once 'auth.php';
require_once '../config.php';

// Redirect back if no id
if (empty($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$id = intval($_GET['id']);

// Fetch product
$res = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id' LIMIT 1");
if (!$res || mysqli_num_rows($res) == 0) {
    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Produk tidak ditemukan.'];
    header('Location: products.php');
    exit();
}

$product = mysqli_fetch_assoc($res);

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY display_order");

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    // If image uploaded, handle it
    $image_sql = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../img/";
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $db_image_url = 'img/' . $filename;
            $image_sql = ", image_url = '" . mysqli_real_escape_string($conn, $db_image_url) . "'";
        }
    }

    $query = "UPDATE products SET category_id = $category_id, name = '" . $name . "', description = '" . $description . "', price = $price, is_available = $is_available $image_sql WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Produk berhasil diperbarui.'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Gagal menyimpan perubahan.'];
    }

    header('Location: products.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Menu - Admin</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="sidebar" id="sidebar" style="display:none;"></div>

    <div class="container">
        <a href="products.php" class="btn btn-link mb-3"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Menu</a>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Menu: <?php echo htmlspecialchars($product['NAME']); ?></h4>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nama Menu</label>
                            <input type="text" name="name" class="form-control" required
                                value="<?php echo htmlspecialchars($product['NAME']); ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Harga</label>
                            <input type="number" step="0.01" name="price" class="form-control" required
                                value="<?php echo $product['price']; ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Kategori</label>
                            <select name="category_id" class="form-control">
                                <?php mysqli_data_seek($categories, 0);
                                while ($cat = mysqli_fetch_assoc($categories)): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $product['category_id'])
                                           echo 'selected'; ?>><?php echo htmlspecialchars($cat['NAME']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control"
                            rows="4"><?php echo htmlspecialchars($product['DESCRIPTION']); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Gambar (opsional)</label>
                            <input type="file" name="image" class="form-control-file" accept="image/*">
                            <?php if (!empty($product['image_url'])): ?>
                                <div class="mt-2">
                                    <img src="../<?php echo $product['image_url']; ?>" class="img-preview" alt="preview">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Availability</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available"
                                    <?php if ($product['is_available'])
                                        echo 'checked'; ?>>
                                <label class="form-check-label" for="is_available">Tersedia</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" name="save_product" class="btn btn-primary"><i class="fas fa-save"></i>
                            Simpan Perubahan</button>
                        <a href="products.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>
        // Simple image preview
        $('input[name="image"]').on('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                var img = $('<img/>', { src: ev.target.result, class: 'img-preview mt-2' });
                $('input[name="image"]').closest('.form-group').find('.img-preview').remove();
                $('input[name="image"]').closest('.form-group').append(img);
            };
            reader.readAsDataURL(file);
        });
    </script>
</body>

</html>