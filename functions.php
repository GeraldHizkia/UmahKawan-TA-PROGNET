<?php
function formatRupiah($angka) {
    return "Rp" . number_format($angka, 0, ',', '.');
}

function getImagePath($image_url) {
    // Jika image_url kosong, gunakan gambar default
    if (empty($image_url)) {
        return 'img/no-image.jpg';
    }
    return $image_url;
}
?>