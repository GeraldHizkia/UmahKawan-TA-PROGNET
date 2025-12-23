<?php
// Pastikan sudah include config.php dan functions.php
include 'config.php';

// Query untuk mengambil semua kategori yang aktif
$query_categories = "SELECT id, name, description 
                    FROM categories 
                    WHERE is_active = 1 
                    ORDER BY display_order ASC";
$result_categories = mysqli_query($conn, $query_categories);

if (!$result_categories) {
    die("Query gagal: " . mysqli_error($conn));
}

// Simpan data kategori ke array untuk digunakan 2x (tab navigation & content)
$categories_data = [];
while ($category = mysqli_fetch_assoc($result_categories)) {
    $categories_data[] = $category;
}
mysqli_free_result($result_categories);
?>

<div class="row">
    <!-- Tab Navigation -->
    <div class="col-md-12 nav-link-wrap mb-5">
        <div class="nav ftco-animate nav-pills justify-content-center" id="v-pills-tab" role="tablist"
            aria-orientation="vertical">
            <?php
            $tab_counter = 1;
            foreach ($categories_data as $category):
                $active_class = ($tab_counter == 1) ? 'active' : '';
                $selected = ($tab_counter == 1) ? 'true' : 'false';
                ?>
                <a class="nav-link <?php echo $active_class; ?>" id="v-pills-<?php echo $tab_counter; ?>-tab"
                    data-toggle="pill" href="#v-pills-<?php echo $tab_counter; ?>" role="tab"
                    aria-controls="v-pills-<?php echo $tab_counter; ?>" aria-selected="<?php echo $selected; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
                <?php
                $tab_counter++;
            endforeach;
            ?>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="col-md-12 d-flex align-items-center">
        <div class="tab-content ftco-animate" id="v-pills-tabContent">
            <?php
            $content_counter = 1;
            foreach ($categories_data as $category):
                $active_show = ($content_counter == 1) ? 'show active' : '';

                // Query produk untuk kategori ini
                $category_id = mysqli_real_escape_string($conn, $category['id']);
                $query_products = "SELECT id, name, description, price, image_url 
                                  FROM products 
                                  WHERE category_id = '$category_id' 
                                  AND is_available = 1 
                                  ORDER BY display_order ASC";
                $result_products = mysqli_query($conn, $query_products);
                ?>

                <div class="tab-pane fade <?php echo $active_show; ?>" id="v-pills-<?php echo $content_counter; ?>"
                    role="tabpanel" aria-labelledby="v-pills-<?php echo $content_counter; ?>-tab">
                    <div class="row">
                        <?php while ($product = mysqli_fetch_assoc($result_products)): ?>
                            <div class="col-md-4 text-center">
                                <div class="menu-wrap">
                                    <a href="product-detail.php?id=<?php echo $product['id']; ?>" class="menu-img img mb-4"
                                        style="background-image: url(<?php echo htmlspecialchars(getImagePath($product['image_url'])); ?>);"></a>
                                    <div class="text">
                                        <h3><a href="#"><?php echo htmlspecialchars($product['name']); ?></a></h3>
                                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                                        <p class="price"><span><?php echo formatRupiah($product['price']); ?></span></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <?php
                mysqli_free_result($result_products);
                $content_counter++;
            endforeach;
            ?>
        </div>
    </div>
</div>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>