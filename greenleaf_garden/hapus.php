<?php
session_start();
include 'koneksi.php';

if (
    !isset($_SESSION['username']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: login.php");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    echo "<script>
        alert('ID produk tidak valid.');
        window.location='dashboard.php';
    </script>";
    exit;
}

/* Cek apakah produk ada */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM tb_produk WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);

    echo "<script>
        alert('Produk tidak ditemukan.');
        window.location='dashboard.php';
    </script>";
    exit;
}

mysqli_stmt_close($stmt);

/* Nonaktifkan produk, BUKAN menghapus data */
$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE tb_produk SET status = 'nonaktif' WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

$berhasil = mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

if ($berhasil) {
    echo "<script>
        alert('Produk berhasil dinonaktifkan.');
        window.location='dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Produk gagal dinonaktifkan.');
        window.location='dashboard.php';
    </script>";
}

exit;
?>