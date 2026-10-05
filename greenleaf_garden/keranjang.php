<?php
session_start();
include 'koneksi.php';

/* =========================
   CEK LOGIN
========================= */
$is_login = (
    isset($_SESSION['id_user']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'pelanggan'
);

$id_user = $is_login ? (int) $_SESSION['id_user'] : null;


/* =========================
   TAMBAH PRODUK
========================= */
if (isset($_POST["add"])) {

    if (!$is_login) {

        $id_produk = (int)($_GET["id"] ?? 0);

        header(
            "Location: detail.php?id=" .
            $id_produk .
            "&status=login_required"
        );

        exit;
    }

    $id_produk = (int)$_GET["id"];
    $jumlah = (int)$_POST["jumlah"];

    if ($jumlah < 1) {
        $jumlah = 1;
    }


    /* =========================
       CEK PRODUK
    ========================= */

    $cek_produk = mysqli_query(
        $koneksi,
        "SELECT *
         FROM tb_produk
         WHERE id = '$id_produk'
         AND status = 'aktif'
         LIMIT 1"
    );

    $produk_db = mysqli_fetch_assoc($cek_produk);

    if (!$produk_db) {

        header("Location: index.php?status=produk_tidak_ditemukan");
        exit;
    }


    /* =========================
       CEK STOK
    ========================= */

    $stok = (int)$produk_db['stok'];

    if ($stok <= 0) {

        header("Location: detail.php?id=" . $id_produk . "&status=stok_habis");
        exit;
    }


    /* Jika produk sudah ada */
    if (isset($_SESSION["cart"][$id_produk])) {

        $jumlah_lama = (int)$_SESSION["cart"][$id_produk]['jumlah'];
        $jumlah_baru = $jumlah_lama + $jumlah;

        if ($jumlah_baru > $stok) {
            $jumlah_baru = $stok;
        }

        $_SESSION["cart"][$id_produk]['jumlah'] = $jumlah_baru;

    } else {

        if ($jumlah > $stok) {
            $jumlah = $stok;
        }

        $_SESSION["cart"][$id_produk] = array(

            'id' => $id_produk,

            'nama' => $produk_db['nama'],

            'harga' => $produk_db['harga'],

            'foto' => $produk_db['poto'],

            'jumlah' => $jumlah

        );
    }

    header("Location: keranjang.php?status=added");
    exit;
}


/* =========================
   AKSI KERANJANG
========================= */
if (isset($_GET["aksi"])) {


    /* =========================
       HAPUS PRODUK
    ========================= */

    if ($_GET["aksi"] == "hapus") {

        $id_produk = (int)($_GET["id"] ?? 0);

        if (isset($_SESSION["cart"][$id_produk])) {

            unset($_SESSION["cart"][$id_produk]);

            header("Location: keranjang.php?status=deleted");
            exit;
        }
    }


    /* =========================
       BELI
    ========================= */

    elseif ($_GET["aksi"] == "beli") {

        if (!$is_login) {

            header("Location: login.php");
            exit;
        }

        if (empty($_SESSION["cart"])) {

            header("Location: keranjang.php?status=empty");
            exit;
        }


        $total = 0;


        /* =========================
           CEK SEMUA PRODUK
        ========================= */

        foreach ($_SESSION["cart"] as $value) {

            $id_produk = (int)$value['id'];
            $jumlah = (int)$value['jumlah'];


            $cek = mysqli_query(
                $koneksi,
                "SELECT id, nama, harga, stok, status
                 FROM tb_produk
                 WHERE id = '$id_produk'
                 LIMIT 1"
            );

            $produk = mysqli_fetch_assoc($cek);


            if (!$produk || $produk['status'] !== 'aktif') {

                header(
                    "Location: keranjang.php?status=produk_nonaktif"
                );

                exit;
            }


            if ((int)$produk['stok'] < $jumlah) {

                header(
                    "Location: keranjang.php?status=stok_kurang"
                );

                exit;
            }


            $total +=
                ((int)$produk['harga'] * $jumlah);
        }


        /* =========================
           SIMPAN TRANSAKSI
        ========================= */

        $tanggal = date("Y-m-d");

        $query = mysqli_query(
            $koneksi,
            "INSERT INTO tb_transaksi
            (tanggal, id_pelanggan, total_harga)
            VALUES
            ('$tanggal', '$id_user', '$total')"
        );


        if (!$query) {

            die(
                "Error insert transaksi: " .
                mysqli_error($koneksi)
            );
        }


        /*
         * tb_transaksi menggunakan
         * id_transaksi AUTO_INCREMENT
         */
        $id_transaksi = mysqli_insert_id($koneksi);


        /* =========================
           SIMPAN DETAIL + KURANGI STOK
        ========================= */

        foreach ($_SESSION["cart"] as $value) {

            $id_produk = (int)$value['id'];
            $jumlah = (int)$value['jumlah'];


            /* Simpan detail */

            $sql_detail = mysqli_query(
                $koneksi,
                "INSERT INTO tb_detail
                (id_transaksi, id_produk, jumlah)
                VALUES
                ('$id_transaksi', '$id_produk', '$jumlah')"
            );


            if (!$sql_detail) {

                die(
                    "Error insert detail: " .
                    mysqli_error($koneksi)
                );
            }


            /* Kurangi stok */

            mysqli_query(
                $koneksi,
                "UPDATE tb_produk
                 SET stok = stok - '$jumlah'
                 WHERE id = '$id_produk'"
            );
        }


        /* =========================
           KOSONGKAN KERANJANG
        ========================= */

        unset($_SESSION["cart"]);


        /* =========================
           FLASH SUCCESS
        ========================= */

        $_SESSION["purchase_success"] = true;


        /* =========================
           KE INVOICE
        ========================= */

        header(
            "Location: invoice.php?id=" .
            $id_transaksi
        );

        exit;
    }
}


/* =========================
   HITUNG TOTAL
========================= */

$total = 0;
$total_item = 0;

if (!empty($_SESSION["cart"])) {

    foreach ($_SESSION["cart"] as $value) {

        $subtotal =
            (int)$value["jumlah"] *
            (int)$value["harga"];

        $total += $subtotal;

        $total_item +=
            (int)$value["jumlah"];
    }
}

?>

<!doctype html>

<html lang="id">

<head>

<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>
    Keranjang | GreenLeaf Garden
</title>


<link
    href="assets/dist/css/bootstrap.min.css"
    rel="stylesheet"
>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>


<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>


<script
    src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
</script>


<style>

/* =====================================================
   ROOT
===================================================== */

:root {

    --green: #198754;
    --green-dark: #11653d;
    --green-deep: #0e5132;

    --green-light: #eaf7ef;

    --text: #1d2b24;
    --muted: #748078;

    --border: #e5ece8;

    --white: #ffffff;

    --bg: #f6f9f7;

}


/* =====================================================
   BODY
===================================================== */

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    font-family: 'Inter', sans-serif;

    color: var(--text);

    background:

        radial-gradient(
            circle at 0% 0%,
            rgba(25,135,84,.08),
            transparent 27%
        ),

        radial-gradient(
            circle at 100% 100%,
            rgba(25,135,84,.06),
            transparent 25%
        ),

        var(--bg);
}


/* =====================================================
   NAVBAR
===================================================== */

.navbar-custom {

    position: sticky;

    top: 0;

    z-index: 999;

    background:
        rgba(255,255,255,.94);

    backdrop-filter:
        blur(18px);

    border-bottom:
        1px solid var(--border);

}


.navbar-inner {

    min-height: 78px;

}


.logo {

    width: 145px;

    height: auto;

    display: block;

    object-fit: contain;

}


.nav-menu {

    display: flex;

    align-items: center;

    gap: 7px;

}


.nav-menu a {

    color: #536058;

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    padding: 10px 14px;

    border-radius: 11px;

    transition: .25s ease;

}


.nav-menu a:hover {

    color: var(--green);

    background: var(--green-light);

}


.nav-menu a.active {

    color: var(--green);

    background: var(--green-light);

}


.nav-icon {

    width: 43px;
    height: 43px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    color: #198754;

    background: #edf8f2;

    border: none;

    border-radius: 50%;

    margin-left: 6px;

    transition: all .3s ease;

    text-decoration: none;

    font-size: 19px;

}


.nav-icon:hover {

    color: #ffffff;

    background: #198754;

    transform: translateY(-2px);

}


.nav-icon i {

    font-size: 19px;

    line-height: 1;

}


.cart-wrapper {

    position: relative;

    display: inline-block;

}


.cart-badge {

    position: absolute;

    top: -3px;
    right: -3px;

    min-width: 19px;
    height: 19px;

    padding: 0 5px;

    display: flex;

    align-items: center;
    justify-content: center;

    background: #dc3545;

    color: white;

    font-size: 9px;
    font-weight: 800;

    border-radius: 50px;

    border: 2px solid white;

}

/* =====================================================
   PAGE
===================================================== */

.cart-page {

    max-width: 1200px;

    margin: auto;

    padding: 50px 20px 70px;

}


.page-heading {

    margin-bottom: 30px;

}


.page-heading small {

    display: inline-flex;

    align-items: center;

    color: var(--green);

    background: var(--green-light);

    padding: 7px 13px;

    border-radius: 50px;

    font-size: 11px;

    font-weight: 800;

    letter-spacing: .7px;

    margin-bottom: 12px;

}


.page-heading h1 {

    font-size: clamp(30px, 5vw, 42px);

    font-weight: 800;

    letter-spacing: -1.5px;

    margin: 0;

}


.page-heading p {

    color: var(--muted);

    margin: 9px 0 0;

    font-size: 14px;

}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-button {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    text-decoration: none;

    color: #536058;

    background: white;

    border: 1px solid var(--border);

    padding: 10px 15px;

    border-radius: 12px;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 24px;

    transition: .25s ease;

}


.back-button:hover {

    color: var(--green);

    border-color: #b9dcc8;

    background: var(--green-light);

    transform: translateX(-2px);

}


/* =====================================================
   CART CARD
===================================================== */

.cart-card {

    background:
        rgba(255,255,255,.96);

    border:
        1px solid var(--border);

    border-radius:
        22px;

    overflow:
        hidden;

    box-shadow:
        0 15px 45px rgba(30,60,45,.07);

}


/* =====================================================
   CART HEADER
===================================================== */

.cart-header {

    padding: 20px 24px;

    border-bottom:
        1px solid var(--border);

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.cart-header h5 {

    margin: 0;

    font-size: 17px;

    font-weight: 800;

}


.item-count {

    color: var(--green);

    background: var(--green-light);

    padding: 7px 12px;

    border-radius: 50px;

    font-size: 11px;

    font-weight: 800;

}


/* =====================================================
   PRODUCT ITEM
===================================================== */

.cart-item {

    padding: 24px;

    border-bottom:
        1px solid var(--border);

    transition: .25s ease;

}


.cart-item:last-child {

    border-bottom: none;

}


.cart-item:hover {

    background: #fcfefd;

}


.product-box {

    display: flex;

    align-items: flex-start;

    gap: 17px;

}


.product-image {

    width: 105px;

    height: 105px;

    flex-shrink: 0;

    border-radius: 17px;

    object-fit: cover;

    background: #f1f5f2;

    border: 1px solid var(--border);

    padding: 5px;

}


.product-info {

    min-width: 0;

}


.product-name {

    font-size: 16px;

    font-weight: 800;

    margin-bottom: 7px;

    line-height: 1.4;

}


.product-description {

    color: var(--muted);

    font-size: 12px;

    line-height: 1.6;

    margin-bottom: 10px;

    display: -webkit-box;

    -webkit-line-clamp: 2;

    -webkit-box-orient: vertical;

    overflow: hidden;

}


.product-category {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    color: var(--green-dark);

    background: var(--green-light);

    padding: 5px 9px;

    border-radius: 8px;

    font-size: 10px;

    font-weight: 700;

}


/* =====================================================
   DETAIL
===================================================== */

.detail-label {

    color: var(--muted);

    font-size: 10px;

    font-weight: 600;

    margin-bottom: 5px;

}


.detail-value {

    color: var(--text);

    font-size: 14px;

    font-weight: 700;

}


.price {

    color: var(--text);

    font-size: 15px;

    font-weight: 800;

}


.stock-text {

    color: var(--green);

    font-size: 11px;

    font-weight: 600;

    margin-top: 4px;

}


.quantity {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 45px;

    height: 38px;

    padding: 0 13px;

    background: var(--green-light);

    color: var(--green-dark);

    border-radius: 10px;

    font-weight: 800;

}


.subtotal {

    font-size: 16px;

    font-weight: 800;

    color: var(--green);

}


/* =====================================================
   DELETE
===================================================== */

.delete-btn {

    width: 40px;

    height: 40px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border: none;

    border-radius: 11px;

    background: #fff0f1;

    color: #dc3545;

    transition: .25s ease;

}


.delete-btn:hover {

    background: #dc3545;

    color: white;

    transform: scale(1.06);

}


/* =====================================================
   SUMMARY
===================================================== */

.summary-card {

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #f6fbf8
        );

    border:
        1px solid var(--border);

    border-radius:
        22px;

    padding:
        27px;

    box-shadow:
        0 15px 45px rgba(30,60,45,.07);

    position:
        sticky;

    top:
        100px;

}


.summary-title {

    font-size: 18px;

    font-weight: 800;

    margin-bottom: 22px;

}


.summary-line {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 14px;

    font-size: 13px;

    color: var(--muted);

}


.summary-line strong {

    color: var(--text);

}


.summary-total {

    border-top:
        1px dashed #d7dfda;

    padding-top:
        18px;

    margin-top:
        18px;

}


.summary-total span {

    font-size: 12px;

    color: var(--muted);

}


.summary-total strong {

    display: block;

    font-size: 27px;

    color: var(--green);

    margin-top: 5px;

}


/* =====================================================
   BUY BUTTON
===================================================== */

.btn-buy {

    width: 100%;

    border: none;

    padding: 14px 20px;

    margin-top: 22px;

    background:
        linear-gradient(
            135deg,
            #198754,
            #11653d
        );

    color: white;

    border-radius: 13px;

    font-weight: 800;

    transition: .3s ease;

    box-shadow:
        0 10px 25px rgba(25,135,84,.20);

}


.btn-buy:hover {

    color: white;

    transform:
        translateY(-2px);

    box-shadow:
        0 15px 30px rgba(25,135,84,.28);

}


/* =====================================================
   CONTINUE BUTTON
===================================================== */

.btn-continue {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    text-align: center;

    text-decoration: none;

    margin-top: 10px;

    padding: 12px 20px;

    border: 1px solid #dce5df;

    color: #536058;

    border-radius: 13px;

    font-size: 13px;

    font-weight: 600;

    transition: .25s ease;

}


.btn-continue:hover {

    color: var(--green);

    background: var(--green-light);

    border-color: #bfe0cc;

}


/* =====================================================
   EMPTY CART
===================================================== */

.empty-cart {

    padding: 80px 20px;

    text-align: center;

}


.empty-icon {

    width: 100px;

    height: 100px;

    margin: 0 auto 22px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 30px;

    background: var(--green-light);

    color: var(--green);

}


.empty-cart h3 {

    font-weight: 800;

    margin-bottom: 8px;

}


.empty-cart p {

    color: var(--muted);

    max-width: 450px;

    margin: 0 auto 25px;

    line-height: 1.7;

    font-size: 14px;

}


.btn-shop {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 13px 22px;

    border-radius: 13px;

    background: var(--green);

    color: white;

    text-decoration: none;

    font-weight: 700;

    transition: .25s ease;

}


.btn-shop:hover {

    color: white;

    background: var(--green-dark);

    transform: translateY(-2px);

}


/* =====================================================
   FOOTER
===================================================== */

footer {

    border-top:
        1px solid var(--border);

    padding:
        25px 20px;

    color:
        var(--muted);

    font-size:
        13px;

}


footer strong {

    color:
        var(--text);

}


footer a {

    color:
        var(--green);

    text-decoration:
        none;

    font-weight:
        600;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991px) {

    .nav-menu {
        display: none;
    }

    .summary-card {
        position: static;
        margin-top: 20px;
    }

}


@media (max-width: 767px) {

    .cart-page {
        padding: 35px 15px 50px;
    }

    .cart-item {
        padding: 18px 15px;
    }

    .cart-header {
        padding: 18px 15px;
    }

    .product-image {
        width: 80px;
        height: 80px;
    }

    .product-name {
        font-size: 14px;
    }

    .product-description {
        font-size: 11px;
    }

    .product-box {
        gap: 12px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<header class="navbar-custom">

<div class="container">

<div
    class="navbar-inner d-flex align-items-center justify-content-between"
>


    <!-- LOGO -->

    <a
        href="index.php"
        class="text-decoration-none"
    >

        GREENLEAF GARDEN

    </a>


    <!-- MENU -->

    <nav class="nav-menu">

        <a href="index.php">
            
            
            <span>
                GREENLEAF GARDEN
            </span>

        </a>

        <a href="index.php#produk">
            Produk
        </a>

        <a
            href="keranjang.php"
            class="active"
        >
            Keranjang
        </a>

    </nav>


  
<!-- ICON -->

<div class="d-flex align-items-center gap-2">

    <!-- PROFILE -->

    <a
        href="profile.php"
        class="nav-icon"
        title="Profile"
    >

        <i class="bi bi-person-fill"></i>

    </a>


    <!-- KERANJANG -->

    <div class="cart-wrapper">

        <a
            href="keranjang.php"
            class="nav-icon"
            title="Keranjang"
        >

            <i class="bi bi-cart3"></i>

        </a>


        <?php if ($total_item > 0) { ?>

            <span class="cart-badge">
                <?= $total_item ?>
            </span>

        <?php } ?>

    </div>

</div>

</header>


<!-- =====================================================
     MAIN
===================================================== -->

<main>

<div class="cart-page">


    <!-- KEMBALI -->

    <a
        href="index.php"
        class="back-button"
    >

        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="15"
            height="15"
            fill="currentColor"
            viewBox="0 0 16 16"
        >

            <path
                fill-rule="evenodd"
                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"
            />

        </svg>

        Kembali ke Produk

    </a>


    <!-- JUDUL -->

    <div class="page-heading">

        <small>
            GREENLEAF GARDEN
        </small>

        <h1>
            Keranjang Belanja
        </h1>

        <p>
            Periksa kembali tanaman pilihanmu sebelum melakukan pembelian.
        </p>

    </div>


    <?php if (!empty($_SESSION["cart"])) { ?>


    <div class="row g-4">


        <!-- =================================================
             LIST PRODUK
        ================================================= -->

        <div class="col-lg-8">

            <div class="cart-card">


                <div class="cart-header">

                    <h5>
                        Produk Pilihanmu
                    </h5>

                    <span class="item-count">

                        <?= $total_item ?>

                        Item

                    </span>

                </div>


                <?php

                foreach (
                    $_SESSION["cart"]
                    as $key => $value
                ) {

                    $id_produk =
                        (int)$value["id"];


                    /*
                     * Ambil informasi terbaru
                     * langsung dari database.
                     */

                    $detail_query = mysqli_query(
                        $koneksi,
                        "SELECT
                            p.*,
                            k.nama_kategori
                         FROM tb_produk p
                         LEFT JOIN tb_kategori k
                            ON p.id_kategori = k.id_kategori
                         WHERE p.id = '$id_produk'
                         LIMIT 1"
                    );


                    $detail_produk =
                        mysqli_fetch_assoc($detail_query);


                    /*
                     * Jika produk tidak ditemukan,
                     * gunakan data session.
                     */

                    if (!$detail_produk) {

                        $detail_produk = [

                            'nama' =>
                                $value['nama'],

                            'harga' =>
                                $value['harga'],

                            'poto' =>
                                $value['foto'],

                            'deskripsi' =>
                                'Informasi produk tidak tersedia.',

                            'stok' =>
                                0,

                            'nama_kategori' =>
                                'Produk'

                        ];
                    }


                    $subtotal =
                        (int)$value["jumlah"] *
                        (int)$value["harga"];

                ?>


                <div class="cart-item">


                    <div class="row align-items-center g-4">


                        <!-- PRODUK -->

                        <div class="col-lg-6">

                            <div class="product-box">


                                <img
                                    src="img/<?= htmlspecialchars($detail_produk["poto"]) ?>"
                                    class="product-image"
                                    alt="<?= htmlspecialchars($detail_produk["nama"]) ?>"
                                    onerror="this.src='img/logotoko.png';"
                                >


                                <div class="product-info">


                                    <div class="product-name">

                                        <?= htmlspecialchars(
                                            $detail_produk["nama"]
                                        ) ?>

                                    </div>


                                    <div class="product-description">

                                        <?= htmlspecialchars(
                                            $detail_produk["deskripsi"]
                                        ) ?>

                                    </div>


                                    <div class="product-category">

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="11"
                                            height="11"
                                            fill="currentColor"
                                            viewBox="0 0 16 16"
                                        >

                                            <path
                                                d="M8 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4m0-5a3 3 0 1 0 0-6 3 3 0 0 0 0 6"
                                            />

                                            <path
                                                d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8"
                                            />

                                        </svg>

                                        <?= htmlspecialchars(
                                            $detail_produk["nama_kategori"]
                                        ) ?>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- HARGA -->

                        <div class="col-6 col-lg-2">

                            <div class="detail-label">
                                Harga
                            </div>

                            <div class="price">

                                Rp
                                <?= number_format(
                                    $detail_produk["harga"],
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </div>

                        </div>


                        <!-- JUMLAH -->

                        <div class="col-6 col-lg-1">

                            <div class="detail-label">
                                Jumlah
                            </div>

                            <span class="quantity">

                                <?= (int)$value["jumlah"] ?>

                            </span>

                        </div>


                        <!-- SUBTOTAL -->

                        <div class="col-6 col-lg-2">

                            <div class="detail-label">
                                Subtotal
                            </div>

                            <div class="subtotal">

                                Rp
                                <?= number_format(
                                    $subtotal,
                                    0,
                                    ',',
                                    '.'
                                ) ?>

                            </div>

                        </div>


                        <!-- HAPUS -->

                        <div class="col-6 col-lg-1 text-end">

                            <button
                                type="button"
                                class="delete-btn"
                                onclick="hapusProduk(
                                    'keranjang.php?aksi=hapus&id=<?= $id_produk ?>'
                                )"
                                title="Hapus produk"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    fill="currentColor"
                                    viewBox="0 0 16 16"
                                >

                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"
                                    />

                                    <path
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L10.882 4zM2.5 3V2h11v1z"
                                    />

                                </svg>

                            </button>

                        </div>


                    </div>


                    <!-- STOK -->

                    <div class="mt-3">

                        <span class="stock-text">

                            Stok tersedia:
                            <?= (int)$detail_produk["stok"] ?>

                            unit

                        </span>

                    </div>


                </div>


                <?php } ?>


            </div>

        </div>


        <!-- =================================================
             RINGKASAN
        ================================================= -->

        <div class="col-lg-4">

            <div class="summary-card">


                <div class="summary-title">

                    Ringkasan Pesanan

                </div>


                <div class="summary-line">

                    <span>
                        Total Produk
                    </span>

                    <strong>
                        <?= $total_item ?> item
                    </strong>

                </div>


                <div class="summary-line">

                    <span>
                        Biaya Produk
                    </span>

                    <strong>

                        Rp
                        <?= number_format(
                            $total,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


                <div class="summary-line">

                    <span>
                        Pengiriman
                    </span>

                    <strong>
                        Gratis
                    </strong>

                </div>


                <div class="summary-total">

                    <span>
                        Total Pembayaran
                    </span>

                    <strong>

                        Rp
                        <?= number_format(
                            $total,
                            0,
                            ',',
                            '.'
                        ) ?>

                    </strong>

                </div>


                <!-- BELI -->

                <button
                    type="button"
                    class="btn-buy"
                    onclick="konfirmasiBeli(
                        'keranjang.php?aksi=beli'
                    )"
                >

                    Beli Sekarang

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="17"
                        height="17"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                        class="ms-1"
                    >

                        <path
                            fill-rule="evenodd"
                            d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"
                        />

                    </svg>

                </button>


                <!-- KEMBALI -->

                <a
                    href="index.php"
                    class="btn-continue"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="14"
                        height="14"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                    >

                        <path
                            fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"
                        />

                    </svg>

                    Kembali Belanja

                </a>


            </div>

        </div>


    </div>


    <?php } else { ?>


    <!-- =================================================
         EMPTY CART
    ================================================= -->

    <div class="cart-card">

        <div class="empty-cart">


            <div class="empty-icon">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="48"
                    height="48"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >

                    <path
                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5"
                    />

                    <path
                        d="M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4"
                    />

                </svg>

            </div>


            <h3>
                Keranjang Masih Kosong
            </h3>


            <p>

                Belum ada tanaman atau produk
                yang kamu pilih.

                Yuk, temukan produk favoritmu
                dan mulai belanja di
                GreenLeaf Garden.

            </p>


            <a
                href="index.php"
                class="btn-shop"
            >

                Mulai Belanja

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="17"
                    height="17"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                >

                    <path
                        fill-rule="evenodd"
                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"
                    />

                </svg>

            </a>


        </div>

    </div>


    <?php } ?>


</div>

</main>


<!-- =====================================================
     FOOTER
===================================================== -->

<footer>

<div class="container d-flex flex-wrap justify-content-between align-items-center">

    <span>
        © 2026
        <strong>GreenLeaf Garden</strong>
    </span>

    <a href="#">
        Kembali ke atas ↑
    </a>

</div>

</footer>


<script
    src="assets/dist/js/bootstrap.bundle.min.js">
</script>


<script>

/* =====================================================
   HAPUS PRODUK
===================================================== */

function hapusProduk(url) {

    Swal.fire({

        title: 'Hapus Produk?',

        text: 'Produk ini akan dihapus dari keranjang.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc3545',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Hapus',

        cancelButtonText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

}


/* =====================================================
   KONFIRMASI BELI
===================================================== */

function konfirmasiBeli(url) {

    Swal.fire({

        title: 'Konfirmasi Pembelian',

        text: 'Apakah kamu yakin ingin membeli semua produk di keranjang?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonColor: '#198754',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Beli Sekarang',

        cancelButtonText: 'Periksa Lagi'

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

}


/* =====================================================
   STATUS
===================================================== */

<?php if (
    isset($_GET['status']) &&
    $_GET['status'] === 'added'
) { ?>

Swal.fire({

    icon: 'success',

    title: 'Produk Ditambahkan',

    text: 'Produk berhasil ditambahkan ke keranjang.',

    confirmButtonColor: '#198754',

    confirmButtonText: 'OK'

});

<?php } ?>


<?php if (
    isset($_GET['status']) &&
    $_GET['status'] === 'deleted'
) { ?>

Swal.fire({

    icon: 'success',

    title: 'Produk Dihapus',

    text: 'Produk berhasil dihapus dari keranjang.',

    confirmButtonColor: '#198754',

    confirmButtonText: 'OK'

});

<?php } ?>


<?php if (
    isset($_GET['status']) &&
    $_GET['status'] === 'empty'
) { ?>

Swal.fire({

    icon: 'info',

    title: 'Keranjang Kosong',

    text: 'Belum ada produk yang dapat dibeli.',

    confirmButtonColor: '#198754',

    confirmButtonText: 'OK'

});

<?php } ?>


<?php if (
    isset($_GET['status']) &&
    $_GET['status'] === 'produk_nonaktif'
) { ?>

Swal.fire({

    icon: 'warning',

    title: 'Produk Tidak Tersedia',

    text: 'Salah satu produk di keranjang sudah tidak tersedia.',

    confirmButtonColor: '#198754',

    confirmButtonText: 'OK'

});

<?php } ?>


<?php if (
    isset($_GET['status']) &&
    $_GET['status'] === 'stok_kurang'
) { ?>

Swal.fire({

    icon: 'warning',

    title: 'Stok Tidak Mencukupi',

    text: 'Jumlah produk di keranjang melebihi stok yang tersedia.',

    confirmButtonColor: '#198754',

    confirmButtonText: 'OK'

});

<?php } ?>


</script>


</body>

</html>