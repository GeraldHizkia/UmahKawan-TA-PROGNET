<?php
include 'config.php';
// include 'functions.php';

// menu.php - Halaman Display Menu
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Warung Rujak Bali</title>
    <!-- Masukkan CSS Anda di sini -->
</head>
<body>
    <div class="container">
        <?php
        // Query untuk mengambil semua kategori yang aktif
        $query_categories = "SELECT id, name, description 
                            FROM categories 
                            WHERE is_active = 1 
                            ORDER BY display_order ASC";
        $result_categories = mysqli_query($conn, $query_categories);
        
        // Cek jika query berhasil
        if (!$result_categories) {
            die("Query gagal: " . mysqli_error($conn));
        }
        
        // Loop setiap kategori
        while ($category = mysqli_fetch_assoc($result_categories)):
            // Query untuk mengambil produk per kategori
            $category_id = mysqli_real_escape_string($conn, $category['id']);
            $query_products = "SELECT id, name, description, price, image_url 
                              FROM products 
                              WHERE category_id = '$category_id' 
                              AND is_available = 1 
                              ORDER BY display_order ASC";
            $result_products = mysqli_query($conn, $query_products);
            
            // Cek jika kategori tidak punya produk
            if (mysqli_num_rows($result_products) == 0) continue;
        ?>
        
        <!-- Tampilkan Kategori -->
        <h3 class="mb-5 heading-pricing ftco-animate"><?php echo htmlspecialchars($category['name']); ?></h3>
        
        <?php while ($product = mysqli_fetch_assoc($result_products)): ?>
            <!-- Loop Produk -->
            <!-- <div class="pricing-entry d-flex ftco-animate"> -->
                <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="pricing-entry d-flex ftco-animate">

                    <div class="img" style="background-image: url(<?php echo htmlspecialchars(getImagePath($product['image_url'])); ?>);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span><?php echo htmlspecialchars($product['name']); ?></span></h3>
                            <span class="price"><?php echo formatRupiah($product['price']); ?></span>
                        </div>
                        <div class="d-block">
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                        </div>
                    </div>
                </a>
            <!-- </div> -->
        <?php endwhile; ?>
        
        <?php 
            // Free result
            mysqli_free_result($result_products);
        endwhile; 
        
        // Free result dan tutup koneksi
        mysqli_free_result($result_categories);
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>