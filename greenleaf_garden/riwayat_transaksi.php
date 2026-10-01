<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: login.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];

/*
|--------------------------------------------------------------------------
| Ambil riwayat transaksi pelanggan
| tb_transaksi = data transaksi
| tb_detail    = detail produk yang dibeli
| tb_produk    = informasi produk
|--------------------------------------------------------------------------
*/
$query = mysqli_query(
    $koneksi,
    "SELECT 
        t.id_transaksi,
        t.id_pelanggan,
        t.tanggal,
        t.total_harga,
        GROUP_CONCAT(
            CONCAT(p.nama, ' (', d.jumlah, 'x)')
            SEPARATOR ', '
        ) AS produk_dibeli
     FROM tb_transaksi AS t
     LEFT JOIN tb_detail AS d
        ON t.id_transaksi = d.id_transaksi
     LEFT JOIN tb_produk AS p
        ON d.id_produk = p.id
     WHERE t.id_pelanggan = '$id_user'
     GROUP BY
        t.id_transaksi,
        t.id_pelanggan,
        t.tanggal,
        t.total_harga
     ORDER BY t.id_transaksi DESC"
);

if (!$query) {
    die("Query riwayat gagal: " . mysqli_error($koneksi));
}
?>

<!doctype html>
<html lang="id">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>Riwayat Transaksi - GreenLeaf Garden</title>

<link href="assets/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet">

<style>

body {
    margin: 0;
    min-height: 100vh;

    font-family: "Poppins", Arial, sans-serif;

    color: #304238;

    background:
        radial-gradient(
            circle at 10% 10%,
            rgba(25,135,84,.10),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #f7fbf8,
            #edf7f1
        );
}

.history-container {
    padding: 55px 15px;
}


/* =========================
   TITLE
========================= */

.history-title {
    margin-bottom: 28px;
}

.history-title span {
    display: inline-block;

    padding: 6px 13px;

    border-radius: 20px;

    background: #e8f6ee;

    color: #198754;

    font-size: 11px;

    font-weight: 700;

    letter-spacing: 2px;
}

.history-title h2 {
    margin-top: 12px;

    color: #163e2a;

    font-size: 30px;

    font-weight: 600;
}

.history-title p {
    color: #738078;

    font-size: 13px;
}


/* =========================
   CARD
========================= */

.history-card {

    border: 1px solid #dceae1;

    border-radius: 18px;

    background: white;

    box-shadow:
        0 18px 45px rgba(23,78,48,.08);

    overflow: hidden;
}



/* =========================
   TABLE
========================= */

.table {
    margin-bottom: 0;
}

.table thead th {

    padding: 16px;

    background: #f1f8f4;

    color: #24523a;

    font-size: 12px;

    font-weight: 600;

    border-bottom: 1px solid #dceae1;

    white-space: nowrap;
}

.table tbody td {

    padding: 16px;

    vertical-align: middle;

    color: #526158;

    font-size: 13px;

    border-color: #edf3ef;
}

.table tbody tr:hover {
    background: #fbfefc;
}


/* =========================
   ID TRANSAKSI
========================= */

.transaction-id {

    color: #176d43;

    font-weight: 600;
}

.transaction-number {

    display: block;

    margin-top: 3px;

    color: #8a968f;

    font-size: 11px;
}


/* =========================
   DATE
========================= */

.date-main {

    color: #304238;

    font-weight: 500;
}


/* =========================
   TOTAL
========================= */

.total-price {

    color: #176d43;

    font-weight: 600;
}


/* =========================
   STATUS
========================= */

.status-badge {

    display: inline-block;

    padding: 5px 10px;

    border-radius: 20px;

    background: #e8f6ee;

    color: #198754;

    font-size: 11px;

    font-weight: 600;
}


/* =========================
   BUTTON
========================= */

.btn-detail {

    border: none;

    border-radius: 8px;

    padding: 8px 13px;

    background: #198754;

    color: white;

    font-size: 12px;
}

.btn-detail:hover {

    background: #157347;

    color: white;
}


/* =========================
   EMPTY
========================= */

.empty-history {

    padding: 55px 20px;

    text-align: center;

    color: #7b8982;

    font-size: 14px;
}


/* =========================
   BACK BUTTON
========================= */

.btn-back {

    display: inline-block;

    margin-top: 20px;

    padding: 10px 20px;

    border: 1px solid #c8ded0;

    border-radius: 10px;

    color: #198754;

    background: white;

    text-decoration: none;

    font-size: 13px;
}

.btn-back:hover {

    background: #eaf7ef;

    color: #146c43;
}


/* =========================
   MOBILE
========================= */

@media(max-width:767px) {

    .history-container {

        padding: 30px 10px;

    }

    .history-card {

        overflow-x: auto;

    }

    .table {

        min-width: 950px;

    }

}

</style>

</head>

<body>

<div class="container history-container">


    <!-- =========================
         TITLE
    ========================= -->

    <div class="history-title">

        <span>
            GREENLEAF GARDEN
        </span>

        <h2>
            Riwayat Transaksi
        </h2>

        <p>
            Lihat seluruh riwayat pembelian dan invoice transaksi Anda.
        </p>

    </div>


    <!-- =========================
         HISTORY CARD
    ========================= -->

    <div class="history-card">

        <?php if (mysqli_num_rows($query) > 0) { ?>

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th width="55">
                            No
                        </th>

                        <th>
                            Invoice
                        </th>

                        <th>
                            Tanggal Transaksi
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php

                $no = 1;

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

                while ($data = mysqli_fetch_assoc($query)) {

                    /* =========================
                       TANGGAL TRANSAKSI
                    ========================= */

                    $tanggal_raw = $data['tanggal'] ?? '';

                    $tanggal_tampil = '-';

                    if (!empty($tanggal_raw)) {

                        $timestamp = strtotime($tanggal_raw);

                        if ($timestamp !== false) {

                            $hari = date('d', $timestamp);

                            $bulan_num = (int) date('m', $timestamp);

                            $tahun = date('Y', $timestamp);

                            $tanggal_tampil =
                                $hari . ' ' .
                                $bulan[$bulan_num] . ' ' .
                                $tahun;
                        }
                    }


                    /* =========================
                       STATUS
                    ========================= */

                    $status = 'Berhasil';

                    if (
                        isset($data['status']) &&
                        !empty($data['status'])
                    ) {

                        $status = $data['status'];

                    } elseif (
                        isset($data['status_transaksi']) &&
                        !empty($data['status_transaksi'])
                    ) {

                        $status =
                            $data['status_transaksi'];

                    }


                    /* =========================
                       TOTAL
                    ========================= */

                    $total_harga =
                        (float) ($data['total_harga'] ?? 0);

                ?>

                    <tr>


                        <!-- NO -->

                        <td>

                            <?= $no++ ?>

                        </td>


                        <!-- INVOICE -->

                        <td>

                            <div class="transaction-id">

                                INV-<?= str_pad(
                                    $data['id_transaksi'],
                                    6,
                                    '0',
                                    STR_PAD_LEFT
                                ) ?>

                            </div>

                            <span class="transaction-number">

                                ID Transaksi:
                                #<?= htmlspecialchars(
                                    $data['id_transaksi']
                                ) ?>

                            </span>

                        </td>


                        <!-- TANGGAL -->

                        <td>

                            <div class="date-main">

                                <?= $tanggal_tampil ?>

                            </div>

                        </td>


                        <!-- TOTAL -->

                        <td>

                            <div class="total-price">

                                Rp <?= number_format(
                                    $total_harga,
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </div>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <span class="status-badge">

                                <?= htmlspecialchars(
                                    ucfirst($status)
                                ) ?>

                            </span>

                        </td>


                        <!-- AKSI -->

                        <td>

                            <a
                                href="invoice.php?id=<?= $data['id_transaksi']; ?>"
                                class="btn btn-detail"
                                onclick="return konfirmasiInvoice(event, this.href);">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="15"
                                    height="15"
                                    fill="currentColor"
                                    viewBox="0 0 16 16"
                                    style="margin-right:5px;vertical-align:-2px;">

                                    <path d="M2 7a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v2h10v-2h1a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1h-1V2H3v5H2zm2-4h8v4H4V3zm7 9H5v-2h6v2zm3-2H2V8h12v2z"/>

                                </svg>

                                Invoice

                            </a>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

        <?php } else { ?>

            <div class="empty-history">

                Belum ada transaksi.

            </div>

        <?php } ?>

    </div>


    <!-- =========================
         BACK
    ========================= -->

    <a
        href="profile.php"
        class="btn-back">

        ← Kembali ke Profile

    </a>

</div>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

/* =========================
   KONFIRMASI INVOICE
========================= */

function konfirmasiInvoice(event, url) {

    event.preventDefault();

    Swal.fire({

        title: 'Buka Invoice?',

        html:
            'Invoice transaksi akan dibuka' +
            '<br>' +
            'dan dapat dicetak.',

        icon: 'question',

        showCancelButton: true,

        confirmButtonColor: '#198754',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Buka Invoice',

        cancelButtonText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

    return false;

}

</script>

</body>

</html>