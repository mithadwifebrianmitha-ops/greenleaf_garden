<?php

include 'koneksi.php';

$id = (int) $_GET['id'];


/* CEK APAKAH PRODUK SUDAH PERNAH MASUK TRANSAKSI */

$cek = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS jumlah
     FROM tb_detail
     WHERE id_produk='$id'"
);

$data = mysqli_fetch_assoc($cek);

if ($data['jumlah'] > 0) {

    echo "
    <script>
        alert('Produk tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        window.location='produk.php';
    </script>
    ";

    exit;
}


/* HAPUS PRODUK */

$hapus = mysqli_query(
    $koneksi,
    "DELETE FROM tb_produk
     WHERE id='$id'"
);

if ($hapus) {

    echo "
    <script>
        alert('Produk berhasil dihapus.');
        window.location='produk.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Produk gagal dihapus.');
        window.location='produk.php';
    </script>
    ";
}

exit;

?>