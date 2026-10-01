<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php?status=hapus");
    exit;
}


/* =========================
   HAPUS TRANSAKSI
========================= */

if (isset($_GET['hapus'])) {

    $id_transaksi = (int) $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM tb_detail
        WHERE id_transaksi = '$id_transaksi'
    ");

    mysqli_query($koneksi, "
        DELETE FROM tb_transaksi
        WHERE id_transaksi = '$id_transaksi'
    ");

    header("Location: transaksi.php");
    exit;
}


/* =========================
   DATA TRANSAKSI
========================= */

$hasil = mysqli_query($koneksi, "
    SELECT
        tb_transaksi.id_transaksi,
        tb_transaksi.tanggal,
        tb_transaksi.total_harga,
        tb_user.nama
    FROM tb_transaksi
    INNER JOIN tb_user
        ON tb_transaksi.id_pelanggan = tb_user.id
    ORDER BY tb_transaksi.id_transaksi DESC
");


/* =========================
   DETAIL TRANSAKSI
========================= */

$detail = null;

if (isset($_GET['detail'])) {

    $id_transaksi = (int) $_GET['detail'];

    $query_detail = mysqli_query($koneksi, "
        SELECT
            tb_transaksi.id_transaksi,
            tb_transaksi.tanggal,
            tb_transaksi.total_harga,
            tb_user.nama
        FROM tb_transaksi
        INNER JOIN tb_user
            ON tb_transaksi.id_pelanggan = tb_user.id
        WHERE tb_transaksi.id_transaksi = '$id_transaksi'
    ");

    $detail = mysqli_fetch_assoc($query_detail);

    if ($detail) {

        $produk_detail = mysqli_query($koneksi, "
            SELECT
                tb_detail.id_detail,
                tb_detail.id_produk,
                tb_detail.jumlah,
                tb_produk.nama,
                tb_produk.harga,
                tb_produk.poto
            FROM tb_detail
            INNER JOIN tb_produk
                ON tb_detail.id_produk = tb_produk.id
            WHERE tb_detail.id_transaksi = '$id_transaksi'
        ");

    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="description" content="" />
    <meta name="author" content="GreenLeaf Garden" />

    <title>Transaksi - GreenLeaf Garden</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" />

 <style>

    :root {
        --green-main: #198754;
        --green-dark: #145c3a;
        --green-soft: #eaf6ef;
        --green-border: #dcefe4;
        --green-light: #f5faf7;
        --text-dark: #26382f;
        --text-muted: #6c7d74;
    }

    body {
        background: var(--green-light);
        color: var(--text-dark);
        font-family: "Segoe UI", sans-serif;
    }


    /* =========================
       NAVBAR
    ========================== */

    .navbar {
        height: 64px;
        background: #ffffff !important;
        border-bottom: 1px solid var(--green-border);
        box-shadow: 0 2px 10px rgba(25, 135, 84, 0.06) !important;
    }

    .navbar-brand {
        color: var(--green-dark) !important;
        font-weight: 700;
        letter-spacing: .5px;
    }


    /* =========================
       SIDEBAR
    ========================== */

    .sidebar {
        background: #ffffff !important;
        border-color: var(--green-border) !important;
        min-height: calc(100vh - 64px);
    }

    .offcanvas {
        background: #ffffff !important;
    }

    .offcanvas-body {
        padding-top: 18px !important;
    }

    .sidebar .nav-link {
        color: #60736a;
        font-weight: 500;
        font-size: 14px;
        padding: 11px 16px;
        margin: 4px 10px;
        border-radius: 10px;
        transition: all .2s ease;
    }

    .sidebar .nav-link:hover {
        color: var(--green-main);
        background: var(--green-soft);
    }

    .sidebar .nav-link.active {
        color: #ffffff !important;
        background: linear-gradient(
            135deg,
            #5b7669,
            #198754,
            #2aa66a
        );
        box-shadow: 0 5px 12px rgba(25, 135, 84, .15);
    }

    .sidebar .nav-link svg {
        width: 18px !important;
        height: 18px !important;
        flex-shrink: 0;
    }


    /* =========================
       MAIN CONTENT
    ========================== */

    main {
        padding-top: 24px;
        padding-bottom: 40px;
    }

    main > .d-flex {
        border-bottom: 1px solid var(--green-border) !important;
    }

    h1,
    h4 {
        color: var(--green-dark);
        font-weight: 700;
    }

    h1 {
        font-size: 25px;
    }

    h4 {
        font-size: 20px;
    }


    /* =========================
       TABLE
    ========================== */

    .table-responsive {
        background: #ffffff;
        border: 1px solid var(--green-border);
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(25, 135, 84, .07);
        overflow: hidden;
    }

    .table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .table thead th {
        background: var(--green-soft);
        color: var(--green-dark);
        font-weight: 600;
        border-bottom: 1px solid var(--green-border);
        padding: 14px 12px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 13px 12px;
        border-color: #edf4ef;
        color: #53655c;
    }

    .table tbody tr {
        transition: background .2s ease;
    }

    .table tbody tr:hover {
        background: #f7fcf9;
    }


    /* =========================
       DETAIL PELANGGAN
    ========================== */

    .mt-4 > .d-flex {
        border-bottom-color: var(--green-border) !important;
    }

    .mt-4 .table-responsive {
        margin-top: 5px;
    }

    .mt-4 .table th {
        width: 200px;
        background: var(--green-soft);
        color: var(--green-dark);
        font-weight: 600;
    }

    .mt-4 .table td {
        background: #ffffff;
        color: var(--text-muted);
    }


    /* =========================
       ROLE BADGE
    ========================== */

    .badge.text-bg-secondary {
        background: var(--green-soft) !important;
        color: var(--green-dark) !important;
        border: 1px solid var(--green-border);
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 7px;
    }


    /* =========================
       BUTTON
    ========================== */

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all .2s ease;
    }

    .btn-sm {
        font-size: 13px;
        padding: 6px 11px;
    }

    .btn-primary {
        background: var(--green-main);
        border-color: var(--green-main);
    }

    .btn-primary:hover {
        background: #157347;
        border-color: #157347;
    }

    .btn-danger {
        background: #fff0f0;
        color: #dc3545;
        border: 1px solid #f3c7cc;
    }

    .btn-danger:hover {
        background: #dc3545;
        color: #ffffff;
        border-color: #dc3545;
    }

    .btn-secondary {
        background: #ffffff;
        color: #5f7168;
        border: 1px solid #cfded5;
    }

    .btn-secondary:hover {
        background: var(--green-soft);
        color: var(--green-dark);
        border-color: #a9cdb7;
    }


    /* =========================
       EMPTY DATA
    ========================== */

    .table tbody td.text-center {
        padding: 35px 15px;
        color: #819189;
        font-style: italic;
    }


    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 767.98px) {

        .navbar {
            height: 58px;
        }

        main {
            padding: 18px 12px 30px;
        }

        h1 {
            font-size: 21px;
        }

        h4 {
            font-size: 18px;
        }

        .table-responsive {
            border-radius: 12px;
        }

        .table {
            min-width: 750px;
        }

        .mt-4 .table {
            min-width: 0;
        }

    }

</style>

</head>


<body>

<svg xmlns="http://www.w3.org/2000/svg" class="d-none">

    <symbol id="house-fill" viewBox="0 0 16 16">
        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"></path>

        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"></path>
    </symbol>


    <symbol id="file-earmark" viewBox="0 0 16 16">
        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"></path>

        <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"></path>
    </symbol>


    <symbol id="people" viewBox="0 0 16 16">
        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"></path>
    </symbol>

</svg>


<!-- NAVBAR -->

<header
    class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow"
    data-bs-theme="dark"
>

    <a
        class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white"
        href="dashboard.php"
    >
        GREENLEAF GARDEN
    </a>

</header>


<div class="container-fluid">

    <div class="row">


        <!-- SIDEBAR -->

        <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">

            <div
                class="offcanvas-md offcanvas-end bg-body-tertiary"
                tabindex="-1"
                id="sidebarMenu"
                aria-labelledby="sidebarMenuLabel"
            >

                <div class="offcanvas-header">

                    <h5 class="offcanvas-title" id="sidebarMenuLabel">
                        GREENLEAF GARDEN
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        data-bs-target="#sidebarMenu"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">

                    <ul class="nav flex-column">

                        <li class="nav-item">

                            <a
                                class="nav-link d-flex align-items-center gap-2"
                                href="dashboard.php"
                            >

                                <svg class="bi" aria-hidden="true">
                                    <use href="#house-fill"></use>
                                </svg>

                                Dashboard

                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                class="nav-link d-flex align-items-center gap-2 active"
                                aria-current="page"
                                href="transaksi.php"
                            >

                                <svg class="bi" aria-hidden="true">
                                    <use href="#file-earmark"></use>
                                </svg>

                                Orders

                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                class="nav-link d-flex align-items-center gap-2"
                                href="pelanggan.php"
                            >

                                <svg class="bi" aria-hidden="true">
                                    <use href="#people"></use>
                                </svg>

                                Customers

                            </a>

                        </li>

                    </ul>

                </div>

            </div>

        </div>


        <!-- KONTEN -->

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">


            <?php if ($detail) { ?>

                <!-- =========================
                     DETAIL TRANSAKSI
                ========================== -->

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
                >

                    <h1 class="h2">
                        Detail Transaksi
                    </h1>

                    <a
                        href="transaksi.php"
                        class="btn btn-sm btn-outline-secondary"
                    >
                        Kembali
                    </a>

                </div>


                <div class="card mb-4">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <p>
                                    <strong>ID Transaksi:</strong>
                                    #<?= $detail['id_transaksi']; ?>
                                </p>

                                <p>
                                    <strong>Nama Pelanggan:</strong>
                                    <?= htmlspecialchars($detail['nama']); ?>
                                </p>

                            </div>


                            <div class="col-md-6">

                                <p>
                                    <strong>Tanggal:</strong>
                                    <?= date(
                                        'd-m-Y',
                                        strtotime($detail['tanggal'])
                                    ); ?>
                                </p>

                                <p>

                                    <strong>Total:</strong>

                                    Rp
                                    <?= number_format(
                                        $detail['total_harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <h2>Produk yang Dibeli</h2>

                <div class="table-responsive small">

                    <table class="table table-striped table-sm">

                        <thead>

                            <tr>

                                <th>No</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php

                        $no = 1;

                        while ($data = mysqli_fetch_assoc($produk_detail)) {

                            $subtotal =
                                $data['harga'] *
                                $data['jumlah'];

                        ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <?php if (!empty($data['poto'])) { ?>

                                            <img
                                                src="img/<?= htmlspecialchars($data['poto']); ?>"
                                                width="50"
                                                height="50"
                                                style="object-fit:cover;"
                                                class="rounded"
                                            >

                                        <?php } ?>

                                        <?= htmlspecialchars($data['nama']); ?>

                                    </div>

                                </td>

                                <td>
                                    Rp
                                    <?= number_format(
                                        $data['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>
                                </td>

                                <td>
                                    <?= $data['jumlah']; ?>
                                </td>

                                <td>
                                    Rp
                                    <?= number_format(
                                        $subtotal,
                                        0,
                                        ',',
                                        '.'
                                    ); ?>
                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>


                        <tfoot>

                            <tr>

                                <th colspan="4" class="text-end">
                                    Total
                                </th>

                                <th>

                                    Rp
                                    <?= number_format(
                                        $detail['total_harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>


            <?php } else { ?>


                <!-- =========================
                     DATA TRANSAKSI
                ========================== -->

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
                >

                    <h1 class="h2">
                        Data Transaksi
                    </h1>

                </div>


                <div class="table-responsive small">

                    <table class="table table-striped table-sm">

                        <thead>

                            <tr>

                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php

                        $no = 1;

                        if (mysqli_num_rows($hasil) > 0) {

                            while ($data = mysqli_fetch_assoc($hasil)) {

                        ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>
                                        #<?= $data['id_transaksi']; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['nama']); ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            'd-m-Y',
                                            strtotime($data['tanggal'])
                                        ); ?>
                                    </td>

                                    <td>

                                        Rp
                                        <?= number_format(
                                            $data['total_harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </td>

                                    <td>

                                        <a
                                            href="transaksi.php?detail=<?= $data['id_transaksi']; ?>"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Detail
                                        </a>

                                       <a
    href="transaksi.php?hapus=<?= $data['id_transaksi']; ?>"
    class="btn btn-sm btn-danger"
    onclick="return konfirmasiHapus(event, this.href);"
>
    Hapus
</a>
<a href="invoice.php?id=<?= $data['id_transaksi']; ?>"
   class="btn btn-sm btn-success">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="15"
         height="15"
         fill="currentColor"
         viewBox="0 0 16 16"
         style="margin-right: 4px; vertical-align: -2px;">

        <path d="M2 7a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1v2h10v-2h1a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1h-1V2H3v5H2zm2-4h8v4H4V3zm7 9H5v-2h6v2zm3-2H2V8h12v2z"/>

    </svg>

    Invoice

</a>
                                    </td>

                                </tr>

                        <?php

                            }

                        } else {

                        ?>

                            <tr>

                                <td colspan="6" class="text-center">

                                    Belum ada data transaksi.

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>


            <?php } ?>


        </main>

    </div>

</div>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// =========================
// KONFIRMASI HAPUS
// =========================

function konfirmasiHapus(event, url) {

    event.preventDefault();

    Swal.fire({
        title: 'Hapus transaksi?',
        text: 'Transaksi dan detail produk di dalamnya akan dihapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = url;
        }

    });

    return false;
}


// =========================
// ALERT BERHASIL DIHAPUS
// =========================

<?php if (isset($_GET['status']) && $_GET['status'] == 'hapus') { ?>

Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Transaksi berhasil dihapus.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});

<?php } ?>

</script>

</body>

</body>

</html>