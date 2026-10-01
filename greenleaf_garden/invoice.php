<?php
session_start();
include 'koneksi.php';

/* =========================
   CEK LOGIN
========================= */

if (!isset($_SESSION['id_user'], $_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$role    = $_SESSION['role'];


/* =========================
   CEK ID TRANSAKSI
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_transaksi = (int) $_GET['id'];


/* =========================
   AMBIL DATA TRANSAKSI
========================= */

if ($role === 'admin') {

    $query_transaksi = mysqli_query(
        $koneksi,
        "SELECT *
         FROM tb_transaksi
         WHERE id_transaksi='$id_transaksi'"
    );

} elseif ($role === 'pelanggan') {

    $query_transaksi = mysqli_query(
        $koneksi,
        "SELECT *
         FROM tb_transaksi
         WHERE id_transaksi='$id_transaksi'
         AND id_pelanggan='$id_user'"
    );

} else {

    header("Location: login.php");
    exit;
}

$transaksi = mysqli_fetch_assoc($query_transaksi);

if (!$transaksi) {

    echo "<script>
        alert('Transaksi tidak ditemukan.');
        window.location.href='index.php';
    </script>";

    exit;
}


/* =========================
   DATA TRANSAKSI
========================= */

$id_pelanggan = (int) $transaksi['id_pelanggan'];

$tanggal_transaksi =
    $transaksi['tanggal'] ?? '-';

$total_database =
    (float) ($transaksi['total_harga'] ?? 0);


/* =========================
   FORMAT TANGGAL INDONESIA
========================= */

$bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

function formatTanggalIndonesia($tanggal, $bulan)
{
    if (!$tanggal || $tanggal === '-') {
        return '-';
    }

    $timestamp = strtotime($tanggal);

    if (!$timestamp) {
        return $tanggal;
    }

    $hari = date('d', $timestamp);

    $bulan_num =
        (int) date('m', $timestamp);

    $tahun =
        date('Y', $timestamp);

    return
        $hari . ' ' .
        $bulan[$bulan_num] . ' ' .
        $tahun;
}


/* =========================
   DATA PELANGGAN
========================= */

$query_user = mysqli_query(
    $koneksi,
    "SELECT nama, email, hp, alamat
     FROM tb_user
     WHERE id='$id_pelanggan'"
);

$user = mysqli_fetch_assoc($query_user);


/* =========================
   DETAIL TRANSAKSI
========================= */

$query_detail = mysqli_query(
    $koneksi,
    "SELECT
        d.*,
        p.nama AS nama_produk,
        p.harga,
        p.poto
     FROM tb_detail d
     LEFT JOIN tb_produk p
        ON d.id_produk = p.id
     WHERE d.id_transaksi='$id_transaksi'"
);


/* =========================
   HITUNG TOTAL ITEM
========================= */

$total_item = 0;

$total_hitung = 0;

$data_detail = [];

while ($detail = mysqli_fetch_assoc($query_detail)) {

    $harga =
        (float) ($detail['harga'] ?? 0);

    $jumlah =
        (int) ($detail['jumlah'] ?? 0);

    $subtotal =
        $harga * $jumlah;

    $total_item += $jumlah;

    $total_hitung += $subtotal;

    $detail['harga_hitung'] =
        $harga;

    $detail['jumlah_hitung'] =
        $jumlah;

    $detail['subtotal_hitung'] =
        $subtotal;

    $data_detail[] =
        $detail;
}


/* =========================
   STATUS TRANSAKSI
========================= */

$status_transaksi = "Berhasil";


/* =========================
   NOMOR INVOICE
========================= */

$nomor_invoice =
    'INV-' .
    str_pad(
        $id_transaksi,
        6,
        '0',
        STR_PAD_LEFT
    );

?>

<!doctype html>

<html lang="id">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        <?= $nomor_invoice ?> - GreenLeaf Garden
    </title>

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <style>

        body {

            margin: 0;

            background: #eef6f1;

            font-family:
                "Poppins",
                Arial,
                sans-serif;

            color: #304238;
        }


        .invoice-wrapper {

            padding: 45px 15px;
        }


        .invoice-card {

            max-width: 950px;

            margin: auto;

            background: white;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 15px 45px
                rgba(23, 78, 48, 0.10);
        }


        /* =========================
           HEADER
        ========================= */

        .invoice-header {

            padding: 32px 38px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #126b40,
                    #198754,
                    #42ad75
                );
        }


        .brand-title {

            margin: 0;

            font-size: 25px;

            font-weight: 700;
        }


        .brand-subtitle {

            margin: 6px 0 0;

            font-size: 13px;

            opacity: .88;
        }


        .invoice-number {

            text-align: right;
        }


        .invoice-label {

            font-size: 12px;

            letter-spacing: 1px;

            opacity: .85;
        }


        .invoice-number strong {

            display: block;

            margin-top: 3px;

            font-size: 21px;
        }


        /* =========================
           BODY
        ========================= */

        .invoice-body {

            padding: 35px 38px;
        }


        .section-title {

            margin-bottom: 12px;

            color: #176d43;

            font-size: 14px;

            font-weight: 600;
        }


        /* =========================
           INFO BOX
        ========================= */

        .info-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 15px;

            margin-bottom: 28px;
        }


        .info-box {

            padding: 17px;

            border: 1px solid #dceae1;

            border-radius: 12px;

            background: #f8fcf9;
        }


        .info-label {

            margin-bottom: 5px;

            color: #789184;

            font-size: 11px;

            font-weight: 500;
        }


        .info-value {

            color: #263d31;

            font-size: 13px;

            font-weight: 600;
        }


        /* =========================
           CUSTOMER
        ========================= */

        .customer-box {

            padding: 20px;

            margin-bottom: 30px;

            border: 1px solid #dceae1;

            border-radius: 12px;

            background: #f8fcf9;
        }


        .customer-row {

            display: grid;

            grid-template-columns:
                130px 1fr;

            padding: 7px 0;

            border-bottom:
                1px dashed #dceae1;

            font-size: 13px;
        }


        .customer-row:last-child {

            border-bottom: none;
        }


        .customer-label {

            color: #789184;
        }


        .customer-value {

            color: #263d31;

            font-weight: 500;
        }


        /* =========================
           TABLE
        ========================= */

        .table {

            margin-bottom: 0;

            vertical-align: middle;
        }


        .table thead th {

            padding: 13px;

            border: none;

            background: #eaf7ef;

            color: #176d43;

            font-size: 12px;

            white-space: nowrap;
        }


        .table tbody td {

            padding: 14px 13px;

            font-size: 13px;

            border-color: #e7eee9;
        }


        .product-name {

            color: #263d31;

            font-weight: 600;
        }


        /* =========================
           SUMMARY
        ========================= */

        .summary-wrapper {

            display: flex;

            justify-content: flex-end;

            margin-top: 25px;
        }


        .summary-box {

            width: 320px;

            padding: 20px;

            border-radius: 14px;

            background: #f1f9f4;
        }


        .summary-row {

            display: flex;

            justify-content:
                space-between;

            gap: 20px;

            padding: 7px 0;

            font-size: 13px;
        }


        .summary-total {

            display: flex;

            justify-content:
                space-between;

            margin-top: 10px;

            padding-top: 13px;

            border-top:
                1px solid #cfe3d5;

            font-size: 15px;

            font-weight: 700;

            color: #176d43;
        }


        .summary-total strong {

            font-size: 18px;
        }


        /* =========================
           NOTE
        ========================= */

        .invoice-note {

            margin-top: 30px;

            padding: 17px 20px;

            border-left:
                4px solid #198754;

            border-radius: 8px;

            background: #f7fbf8;
        }


        .invoice-note strong {

            display: block;

            margin-bottom: 5px;

            color: #176d43;

            font-size: 13px;
        }


        .invoice-note p {

            margin: 0;

            color: #718078;

            font-size: 12px;

            line-height: 1.7;
        }


        /* =========================
           FOOTER
        ========================= */

        .invoice-footer {

            padding: 22px 38px;

            border-top:
                1px solid #e5eee8;

            background: #fbfdfc;
        }


        .footer-text {

            margin: 0;

            color: #7c8981;

            text-align: center;

            font-size: 11px;
        }


        /* =========================
           ACTION
        ========================= */

        .invoice-actions {

            display: flex;

            justify-content: center;

            gap: 10px;

            padding:
                25px 35px 35px;
        }


        .btn-green {

            border: none;

            border-radius: 10px;

            padding: 10px 18px;

            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #27a965
                );

            color: white;

            font-size: 13px;

            font-weight: 500;
        }


        .btn-green:hover {

            color: white;

            background: #157347;
        }


        .btn-outline-green {

            border:
                1px solid #c8ded0;

            border-radius: 10px;

            padding: 10px 18px;

            background: white;

            color: #198754;

            font-size: 13px;

            font-weight: 500;
        }


        .btn-outline-green:hover {

            background: #eaf7ef;

            color: #146c43;
        }


        .print-icon {

            margin-right: 6px;

            vertical-align: -2px;
        }


        /* =========================
           PRINT
        ========================= */

        @media print {

            body {

                background: white;
            }


            .invoice-wrapper {

                padding: 0;
            }


            .invoice-card {

                max-width: none;

                box-shadow: none;

                border-radius: 0;
            }


            .invoice-actions {

                display: none !important;
            }


            .invoice-footer {

                background: white;
            }

        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 600px) {

            .invoice-header {

                padding: 25px;
            }


            .invoice-body {

                padding: 22px;
            }


            .invoice-number {

                margin-top: 18px;

                text-align: left;
            }


            .info-grid {

                grid-template-columns: 1fr;
            }


            .customer-row {

                grid-template-columns: 1fr;

                gap: 3px;
            }


            .summary-wrapper {

                justify-content: stretch;
            }


            .summary-box {

                width: 100%;
            }


            .invoice-actions {

                flex-direction: column;

                padding: 20px;
            }


            .invoice-actions a,
            .invoice-actions button {

                width: 100%;
            }

        }

    </style>

</head>


<body>

<div class="invoice-wrapper">

    <div class="invoice-card">


        <!-- =========================
             HEADER
        ========================= -->

        <div class="invoice-header">

            <div class="row">

                <div class="col-md-8">

                    <h2 class="brand-title">
                        GreenLeaf Garden
                    </h2>

                    <p class="brand-subtitle">
                        Invoice Pembelian Tanaman & Perlengkapan
                    </p>

                </div>


                <div class="col-md-4 invoice-number">

                    <div class="invoice-label">
                        NOMOR INVOICE
                    </div>

                    <strong>
                        <?= $nomor_invoice ?>
                    </strong>

                </div>

            </div>

        </div>


        <!-- =========================
             BODY
        ========================= -->

        <div class="invoice-body">


            <!-- INFORMASI TRANSAKSI -->

            <div class="section-title">
                Informasi Transaksi
            </div>


            <div class="info-grid">


                <div class="info-box">

                    <div class="info-label">
                        ID TRANSAKSI
                    </div>

                    <div class="info-value">
                        #<?= $id_transaksi ?>
                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        TANGGAL TRANSAKSI
                    </div>

                    <div class="info-value">

                        <?= formatTanggalIndonesia(
                            $tanggal_transaksi,
                            $bulan
                        ) ?>

                    </div>

                </div>


                <div class="info-box">

                    <div class="info-label">
                        STATUS TRANSAKSI
                    </div>

                    <div class="info-value">

                        <span
                            style="
                            display:inline-block;
                            padding:5px 10px;
                            border-radius:20px;
                            background:#dff5e7;
                            color:#198754;
                            font-size:11px;
                            ">

                            <?= htmlspecialchars(
                                $status_transaksi
                            ) ?>

                        </span>

                    </div>

                </div>

            </div>


            <!-- DATA PELANGGAN -->

            <div class="section-title">
                Informasi Pelanggan
            </div>


            <div class="customer-box">


                <div class="customer-row">

                    <div class="customer-label">
                        Nama
                    </div>

                    <div class="customer-value">

                        <?= htmlspecialchars(
                            $user['nama'] ?? '-'
                        ) ?>

                    </div>

                </div>


                <div class="customer-row">

                    <div class="customer-label">
                        Email
                    </div>

                    <div class="customer-value">

                        <?= htmlspecialchars(
                            $user['email'] ?? '-'
                        ) ?>

                    </div>

                </div>


                <div class="customer-row">

                    <div class="customer-label">
                        No. HP
                    </div>

                    <div class="customer-value">

                        <?= htmlspecialchars(
                            $user['hp'] ?? '-'
                        ) ?>

                    </div>

                </div>


                <div class="customer-row">

                    <div class="customer-label">
                        Alamat
                    </div>

                    <div class="customer-value">

                        <?= nl2br(
                            htmlspecialchars(
                                $user['alamat'] ?? '-'
                            )
                        ) ?>

                    </div>

                </div>

            </div>


            <!-- DETAIL PEMBELIAN -->

            <div class="section-title">
                Rincian Pembelian
            </div>


            <div class="table-responsive">

                <table class="table">

                    <thead>

                        <tr>

                            <th width="45">
                                No
                            </th>

                            <th>
                                Produk
                            </th>

                            <th width="130">
                                Harga Satuan
                            </th>

                            <th width="80">
                                Jumlah
                            </th>

                            <th width="145">
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (count($data_detail) > 0): ?>

                        <?php $no = 1; ?>


                        <?php foreach (
                            $data_detail
                            as $detail
                        ): ?>

                            <tr>

                                <td>
                                    <?= $no++ ?>
                                </td>


                                <td class="product-name">

                                    <?= htmlspecialchars(
                                        $detail['nama_produk']
                                        ?? '-'
                                    ) ?>

                                </td>


                                <td>

                                    Rp <?= number_format(
                                        $detail['harga_hitung'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </td>


                                <td>

                                    <?= $detail[
                                        'jumlah_hitung'
                                    ] ?>

                                </td>


                                <td>

                                    <strong>

                                        Rp <?= number_format(
                                            $detail[
                                                'subtotal_hitung'
                                            ],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                    </strong>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center">

                                Tidak ada detail produk.

                            </td>

                        </tr>

                    <?php endif; ?>


                    </tbody>

                </table>

            </div>


            <!-- RINGKASAN -->

            <div class="summary-wrapper">

                <div class="summary-box">


                    <div class="summary-row">

                        <span>
                            Jumlah Produk
                        </span>

                        <strong>
                            <?= count(
                                $data_detail
                            ) ?>
                            jenis
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Total Item
                        </span>

                        <strong>
                            <?= $total_item ?>
                            item
                        </strong>

                    </div>


                    <div class="summary-total">

                        <span>
                            TOTAL PEMBAYARAN
                        </span>

                        <strong>

                            Rp <?= number_format(
                                $total_hitung,
                                0,
                                ',',
                                '.'
                            ) ?>

                        </strong>

                    </div>

                </div>

            </div>


            <!-- CATATAN -->

            <div class="invoice-note">

                <strong>
                    Catatan Invoice
                </strong>

                <p>

                    Invoice ini merupakan bukti transaksi
                    pembelian pada GreenLeaf Garden.
                    Simpan invoice ini sebagai bukti
                    transaksi Anda.

                </p>

            </div>

        </div>


        <!-- =========================
             FOOTER
        ========================= -->

        <div class="invoice-footer">

            <p class="footer-text">

                GreenLeaf Garden &bull;
                Invoice <?= $nomor_invoice ?>

            </p>

        </div>


        <!-- =========================
             ACTION
        ========================= -->

        <div class="invoice-actions">


            <button
                type="button"
                class="btn btn-green"
                onclick="konfirmasiCetak();">

                <svg
                    class="print-icon"
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16">

                    <path d="M2 7a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v2h10v-2h1a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1h-1V2H3v5H2zm2-4h8v4H4V3zm7 9H5v-2H5v2h6v2zm3-2H2V8h12v2z"/>

                </svg>

                Cetak Invoice

            </button>


            <?php if ($role === 'admin'): ?>

                <a
                    href="transaksi.php"
                    class="btn btn-outline-green">

                    Kembali

                </a>

            <?php else: ?>

                <a
                    href="riwayat_transaksi.php"
                    class="btn btn-outline-green">

                    Kembali

                </a>

            <?php endif; ?>


        </div>

    </div>

</div>


<script
    src="assets/dist/js/bootstrap.bundle.min.js">
</script>

<script
    src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
</script>


<script>

/* =========================
   KONFIRMASI CETAK
========================= */

function konfirmasiCetak() {

    Swal.fire({

        title: 'Cetak Invoice?',

        html:
            'Invoice <strong><?= $nomor_invoice ?></strong>' +
            '<br>' +
            'akan dibuka dalam tampilan cetak.',

        icon: 'question',

        showCancelButton: true,

        confirmButtonColor: '#198754',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Cetak',

        cancelButtonText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {

            window.print();

        }

    });

}

</script>


</body>

</html>