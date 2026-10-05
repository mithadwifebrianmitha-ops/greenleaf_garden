<?php
session_start();
include 'koneksi.php';

/* =========================
   CEK LOGIN
========================= */

if (
    !isset($_SESSION['id_user']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'pelanggan'
) {
    header("Location: login.php");
    exit;
}

$id_user = (int)$_SESSION['id_user'];


/* =========================
   CEK ID TRANSAKSI
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_transaksi = (int)$_GET['id'];


/* =========================
   AMBIL DATA TRANSAKSI
========================= */

$query_transaksi = mysqli_query(
    $koneksi,
    "SELECT
        t.id_transaksi,
        t.tanggal,
        t.id_pelanggan,
        t.total_harga,
        u.nama,
        u.email,
        u.hp,
        u.alamat
    FROM tb_transaksi t
    INNER JOIN tb_user u
        ON t.id_pelanggan = u.id
    WHERE t.id_transaksi = '$id_transaksi'
      AND t.id_pelanggan = '$id_user'
    LIMIT 1"
);

if (!$query_transaksi) {
    die("Error transaksi: " . mysqli_error($koneksi));
}

$transaksi = mysqli_fetch_assoc($query_transaksi);

if (!$transaksi) {
    header("Location: index.php");
    exit;
}


/* =========================
   AMBIL DETAIL TRANSAKSI
========================= */

$query_detail = mysqli_query(
    $koneksi,
    "SELECT
        d.id_detail,
        d.id_transaksi,
        d.id_produk,
        d.jumlah,
        p.nama,
        p.harga,
        p.poto
    FROM tb_detail d
    INNER JOIN tb_produk p
        ON d.id_produk = p.id
    WHERE d.id_transaksi = '$id_transaksi'
    ORDER BY d.id_detail ASC"
);

if (!$query_detail) {
    die("Error detail: " . mysqli_error($koneksi));
}


/* =========================
   PESAN SUKSES
========================= */

$purchase_success = false;

if (
    isset($_SESSION['purchase_success']) &&
    $_SESSION['purchase_success'] === true
) {
    $purchase_success = true;
    unset($_SESSION['purchase_success']);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Invoice #<?= htmlspecialchars($transaksi['id_transaksi']) ?> - GreenLeaf Garden
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    </script>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            background: #f4f7f5;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .invoice-container {
            max-width: 950px;
            margin: 40px auto;
            padding: 0 15px;
        }

        .invoice-card {
            background: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        /* =========================
           HEADER
        ========================= */

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #ddd;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

        .brand {
            font-size: 27px;
            font-weight: bold;
            color: #198754;
            letter-spacing: .5px;
        }

        .brand-subtitle {
            color: #777;
            margin-top: 5px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            margin: 0;
            font-weight: bold;
            color: #222;
        }

        .invoice-number {
            color: #198754;
            font-weight: 600;
            margin-top: 5px;
        }

        /* =========================
           CUSTOMER
        ========================= */

        .customer-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .customer-box h5 {
            color: #198754;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .customer-item {
            line-height: 1.6;
        }

        /* =========================
           TABLE
        ========================= */

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #198754;
            color: white;
            border: none;
            padding: 13px;
        }

        .table thead th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .table thead th:last-child {
            border-radius: 0 8px 8px 0;
        }

        .table tbody td {
            padding: 15px 13px;
            vertical-align: middle;
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        /* =========================
           TOTAL
        ========================= */

        .total-box {
            background: #f1f8f4;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            border: 1px solid #dcefe4;
        }

        .total-label {
            font-size: 17px;
            color: #555;
        }

        .total-price {
            font-size: 27px;
            font-weight: bold;
            color: #198754;
        }

        /* =========================
           BUTTON
        ========================= */

        .button-area {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-green,
        .btn-print {
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: .2s;
            cursor: pointer;
        }

        .btn-green {
            background: #198754;
            color: white;
        }

        .btn-green:hover {
            background: #157347;
            color: white;
        }

        .btn-print {
            background: #212529;
            color: white;
        }

        .btn-print:hover {
            background: #000;
            color: white;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .invoice-container {
                margin: 20px auto;
            }

            .invoice-card {
                padding: 20px;
            }

            .invoice-header {
                flex-direction: column;
                gap: 20px;
            }

            .invoice-title {
                text-align: left;
            }

            .button-area {
                flex-direction: column;
            }

            .btn-green,
            .btn-print {
                width: 100%;
            }

        }


        /* =========================
           CETAK
        ========================= */

        @media print {

            body {
                background: white;
            }

            .invoice-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            .invoice-card {
                box-shadow: none;
                border-radius: 0;
                padding: 10px;
            }

            .no-print {
                display: none !important;
            }

            .table thead th {
                background: #198754 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .total-box {
                background: #f1f8f4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .product-img {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

        }

    </style>

</head>

<body>

<div class="invoice-container">

    <div class="invoice-card">

        <!-- =========================
             HEADER INVOICE
        ========================= -->

        <div class="invoice-header">

            <div>

                <div class="brand">
                    GREENLEAF GARDEN
                </div>

                <div class="brand-subtitle">
                    Tanaman untuk rumah yang lebih hijau
                </div>

            </div>


            <div class="invoice-title">

                <h2>INVOICE</h2>

                <div class="invoice-number">
                    #<?= htmlspecialchars($transaksi['id_transaksi']) ?>
                </div>

                <div class="text-muted">
                    <?= htmlspecialchars($transaksi['tanggal']) ?>
                </div>

            </div>

        </div>


        <!-- =========================
             DATA PELANGGAN
        ========================= -->

        <div class="customer-box">

            <h5>
                Data Pelanggan
            </h5>

            <div class="row">

                <div class="col-md-6 mb-3 customer-item">

                    <strong>Nama:</strong><br>

                    <?= htmlspecialchars($transaksi['nama']) ?>

                </div>


                <div class="col-md-6 mb-3 customer-item">

                    <strong>Email:</strong><br>

                    <?= htmlspecialchars($transaksi['email']) ?>

                </div>


                <div class="col-md-6 mb-2 customer-item">

                    <strong>No. HP:</strong><br>

                    <?= htmlspecialchars($transaksi['hp']) ?>

                </div>


                <div class="col-md-6 mb-2 customer-item">

                    <strong>Alamat:</strong><br>

                    <?= htmlspecialchars($transaksi['alamat']) ?>

                </div>

            </div>

        </div>


        <!-- =========================
             DETAIL PRODUK
        ========================= -->

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>Produk</th>

                        <th>Harga</th>

                        <th>Jumlah</th>

                        <th>Subtotal</th>

                    </tr>

                </thead>


                <tbody>

                <?php while ($detail = mysqli_fetch_assoc($query_detail)): ?>

                    <?php

                    $subtotal =
                        (int)$detail['harga'] *
                        (int)$detail['jumlah'];

                    ?>

                    <tr>

                        <td>

                            <div class="d-flex align-items-center gap-3">

                                <img
                                    src="img/<?= htmlspecialchars($detail['poto']) ?>"
                                    class="product-img"
                                    onerror="this.style.display='none';"
                                >

                                <div>

                                    <?= htmlspecialchars($detail['nama']) ?>

                                </div>

                            </div>

                        </td>


                        <td>

                            Rp <?= number_format(
                                $detail['harga'],
                                0,
                                ',',
                                '.'
                            ) ?>

                        </td>


                        <td>

                            <?= (int)$detail['jumlah'] ?>

                        </td>


                        <td>

                            <strong>

                                Rp <?= number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </strong>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>


        <!-- =========================
             TOTAL
        ========================= -->

        <div class="total-box">

            <div class="d-flex justify-content-between align-items-center">

                <div class="total-label">

                    Total Pembayaran

                </div>


                <div class="total-price">

                    Rp <?= number_format(
                        $transaksi['total_harga'],
                        0,
                        ',',
                        '.'
                    ) ?>

                </div>

            </div>

        </div>


        <!-- =========================
             BUTTON
        ========================= -->

        <div class="button-area no-print">

            <a
                href="index.php"
                class="btn-green"
            >
                ← Kembali Belanja
            </a>


            <button
                type="button"
                class="btn-print"
                onclick="window.print()"
            >
                🖨 Cetak Invoice
            </button>

        </div>

    </div>

</div>


<!-- =========================
     SWEETALERT PEMBELIAN
========================= -->

<?php if ($purchase_success): ?>

<script>

Swal.fire({
    icon: 'success',
    title: 'Pembelian Berhasil!',
    text: 'Terima kasih sudah membeli di GreenLeaf Garden.',
    confirmButtonText: 'Lihat Invoice',
    confirmButtonColor: '#198754',
    allowOutsideClick: false
});

</script>

<?php endif; ?>


</body>

</html>