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

    // Jika Guest mencoba menambahkan produk
    if (!$is_login) {
        $id_produk = (int)($_GET["id"] ?? 0);

        header("Location: detail.php?id=" . $id_produk . "&status=login_required");
        exit;
    }

    $id_produk = (int)$_GET["id"];
    $jumlah = (int)$_POST["jumlah"];

    if ($jumlah < 1) {
        $jumlah = 1;
    }

    if (isset($_SESSION["cart"][$id_produk])) {

        $_SESSION["cart"][$id_produk]['jumlah'] += $jumlah;

    } else {

        $_SESSION["cart"][$id_produk] = array(
            'id' => $id_produk,
            'nama' => $_POST["hidden_nama"],
            'harga' => $_POST["hidden_harga"],
            'foto' => $_POST["hidden_foto"],
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

    /* HAPUS PRODUK */
    if ($_GET["aksi"] == "hapus") {

        $id_produk = (int)$_GET["id"];

        if (isset($_SESSION["cart"][$id_produk])) {

            unset($_SESSION["cart"][$id_produk]);

            header("Location: keranjang.php?status=deleted");
            exit;
        }
    }


    /* BELI */
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

        foreach ($_SESSION["cart"] as $value) {
            $total += ($value["jumlah"] * $value["harga"]);
        }


        /* Simpan transaksi utama */
        $query = mysqli_query(
            $koneksi,
            "INSERT INTO tb_transaksi(tanggal, id_pelanggan, total_harga)
             VALUES ('" . date("Y-m-d") . "', '$id_user', '$total')"
        ) or die("Error insert transaksi: " . mysqli_error($koneksi));

        $id_transaksi = mysqli_insert_id($koneksi);


        /* Simpan detail produk */
        foreach ($_SESSION["cart"] as $value) {

            $id_produk = (int)$value['id'];
            $jumlah = (int)$value['jumlah'];

            $sql = "INSERT INTO tb_detail(id_transaksi, id_produk, jumlah)
                    VALUES ('$id_transaksi', '$id_produk', '$jumlah')";

            mysqli_query($koneksi, $sql);
        }


        /* Kosongkan keranjang */
        unset($_SESSION["cart"]);

        header("Location: cetak.php?id=" . $id_transaksi);
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

        $subtotal = $value["jumlah"] * $value["harga"];

        $total += $subtotal;
        $total_item += $value["jumlah"];
    }
}

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Keranjang | GreenLeaf Garden</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>

        /* =========================
           ROOT
        ========================= */

        :root {
            --green: #198754;
            --green-dark: #11653d;
            --green-light: #eaf7ef;
            --text: #1d2b24;
            --muted: #748078;
            --border: #e6ece8;
            --white: #ffffff;
            --bg: #f5f8f6;
        }


        /* =========================
           BODY
        ========================= */

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 5% 10%, rgba(25, 135, 84, .08), transparent 25%),
                radial-gradient(circle at 95% 90%, rgba(25, 135, 84, .06), transparent 25%),
                var(--bg);
        }


        /* =========================
           NAVBAR
        ========================= */

        .navbar-custom {
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            min-height: 78px;
        }

        .logo {
            width: 145px;
            height: auto;
            object-fit: contain;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .nav-menu a {
            color: #536058;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 14px;
            border-radius: 10px;
            transition: .25s ease;
        }

        .nav-menu a:hover {
            color: var(--green);
            background: var(--green-light);
        }

        .nav-icon {
            width: 43px;
            height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #445049;
            background: #f4f7f5;
            border: 1px solid var(--border);
            border-radius: 13px;
            margin-left: 6px;
            transition: .25s ease;
        }

        .nav-icon:hover {
            color: white;
            background: var(--green);
            border-color: var(--green);
            transform: translateY(-2px);
        }

        .cart-wrapper {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -2px;
            min-width: 19px;
            height: 19px;
            padding: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dc3545;
            color: white;
            font-size: 10px;
            font-weight: 700;
            border-radius: 50px;
            border: 2px solid white;
        }


        /* =========================
           PAGE
        ========================= */

        .cart-page {
            max-width: 1180px;
            margin: auto;
            padding: 55px 20px 70px;
        }

        .page-heading {
            margin-bottom: 35px;
        }

        .page-heading small {
            display: inline-block;
            color: var(--green);
            background: var(--green-light);
            padding: 7px 13px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .5px;
            margin-bottom: 12px;
        }

        .page-heading h1 {
            font-size: clamp(30px, 5vw, 44px);
            font-weight: 800;
            letter-spacing: -1.5px;
            margin: 0;
        }

        .page-heading p {
            color: var(--muted);
            margin-top: 10px;
            font-size: 15px;
        }


        /* =========================
           CART CARD
        ========================= */

        .cart-card {
            background: rgba(255, 255, 255, .94);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 50px rgba(30, 60, 45, .08);
        }


        /* =========================
           CART HEADER
        ========================= */

        .cart-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cart-header h5 {
            margin: 0;
            font-weight: 750;
        }

        .item-count {
            color: var(--green);
            background: var(--green-light);
            padding: 7px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
        }


        /* =========================
           PRODUCT ITEM
        ========================= */

        .cart-item {
            padding: 22px 25px;
            border-bottom: 1px solid var(--border);
            transition: .25s ease;
        }

        .cart-item:hover {
            background: #fbfdfc;
        }

        .product-box {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .product-image {
            width: 92px;
            height: 92px;
            border-radius: 18px;
            object-fit: cover;
            background: #f1f5f2;
            padding: 7px;
            border: 1px solid var(--border);
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .product-code {
            font-size: 12px;
            color: var(--muted);
        }

        .price-label {
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 3px;
        }

        .price {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
        }

        .quantity {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 45px;
            height: 38px;
            padding: 0 12px;
            background: var(--green-light);
            color: var(--green-dark);
            border-radius: 10px;
            font-weight: 700;
        }

        .subtotal {
            font-size: 16px;
            font-weight: 800;
            color: var(--green);
        }


        /* =========================
           DELETE BUTTON
        ========================= */

        .delete-btn {
            width: 39px;
            height: 39px;
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


        /* =========================
           SUMMARY
        ========================= */

        .summary-card {
            background: linear-gradient(145deg, #ffffff, #f7fbf8);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 27px;
            box-shadow: 0 18px 50px rgba(30, 60, 45, .08);
            position: sticky;
            top: 105px;
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
            font-size: 14px;
            color: var(--muted);
        }

        .summary-line strong {
            color: var(--text);
        }

        .summary-total {
            border-top: 1px dashed #d7dfda;
            padding-top: 18px;
            margin-top: 18px;
        }

        .summary-total span {
            font-size: 14px;
            color: var(--muted);
        }

        .summary-total strong {
            display: block;
            font-size: 25px;
            color: var(--green);
            margin-top: 4px;
        }


        /* =========================
           BUTTON
        ========================= */

        .btn-buy {
            width: 100%;
            border: none;
            padding: 14px 20px;
            margin-top: 22px;
            background: linear-gradient(135deg, #198754, #11653d);
            color: white;
            border-radius: 14px;
            font-weight: 700;
            transition: .3s ease;
            box-shadow: 0 10px 25px rgba(25, 135, 84, .22);
        }

        .btn-buy:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(25, 135, 84, .30);
        }

        .btn-continue {
            width: 100%;
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            padding: 13px 20px;
            border: 1px solid #dce5df;
            color: #536058;
            border-radius: 14px;
            font-weight: 600;
            transition: .25s ease;
        }

        .btn-continue:hover {
            color: var(--green);
            background: var(--green-light);
            border-color: #bfe0cc;
        }


        /* =========================
           EMPTY CART
        ========================= */

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
            max-width: 430px;
            margin: 0 auto 25px;
            line-height: 1.7;
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


        /* =========================
           FOOTER
        ========================= */

        footer {
            border-top: 1px solid var(--border);
            padding: 25px 20px;
            color: var(--muted);
            font-size: 13px;
            margin-top: 20px;
        }

        footer a {
            color: var(--green);
            text-decoration: none;
            font-weight: 600;
        }


        /* =========================
           RESPONSIVE
        ========================= */

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
                width: 72px;
                height: 72px;
            }

            .product-name {
                font-size: 14px;
            }

            .desktop-label {
                display: none;
            }

            .mobile-price {
                display: block;
                margin-top: 10px;
            }
        }

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<header class="navbar-custom">

    <div class="container">

        <div class="navbar-inner d-flex align-items-center justify-content-between">

            <!-- LOGO -->
            <a href="index.php" class="text-decoration-none">
                <img src="img/logotoko.png" class="logo" alt="GreenLeaf Garden">
            </a>


            <!-- MENU -->
            <nav class="nav-menu">

                <a href="index.php">Beranda</a>
                <a href="index.php#produk">Produk</a>

            </nav>


            <!-- ICON -->
            <div class="d-flex align-items-center">

                <a href="profile.php" class="nav-icon" title="Profile">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="19"
                         height="19"
                         fill="currentColor"
                         viewBox="0 0 16 16">

                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>

                        <path fill-rule="evenodd"
                              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.226 5.468 2.37A7 7 0 0 0 8 1"/>

                    </svg>

                </a>


                <div class="cart-wrapper">

                    <a href="keranjang.php"
                       class="nav-icon"
                       title="Keranjang">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="19"
                             height="19"
                             fill="currentColor"
                             viewBox="0 0 16 16">

                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>

                        </svg>

                    </a>

                    <?php if ($total_item > 0) { ?>

                        <span class="cart-badge">
                            <?= $total_item ?>
                        </span>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</header>



<!-- =========================
     MAIN
========================= -->

<main>

<div class="cart-page">


    <!-- TITLE -->

    <div class="page-heading">

        <small>GREENLEAF GARDEN</small>

        <h1>Keranjang Belanja</h1>

        <p>
            Periksa kembali produk pilihanmu sebelum melakukan pembelian.
        </p>

    </div>



    <?php if (!empty($_SESSION["cart"])) { ?>


    <div class="row g-4">


        <!-- =========================
             PRODUCT LIST
        ========================= -->

        <div class="col-lg-8">

            <div class="cart-card">

                <div class="cart-header">

                    <h5>Produk Pilihanmu</h5>

                    <span class="item-count">
                        <?= $total_item ?> Item
                    </span>

                </div>


                <?php

                foreach ($_SESSION["cart"] as $key => $value) {

                    $subtotal = $value["jumlah"] * $value["harga"];

                ?>

                <div class="cart-item">

                    <div class="row align-items-center g-3">


                        <!-- PRODUK -->

                        <div class="col-md-5">

                            <div class="product-box">

                                <img
                                    src="img/<?= htmlspecialchars($value["foto"]) ?>"
                                    class="product-image"
                                    alt="<?= htmlspecialchars($value["nama"]) ?>"
                                    onerror="this.src='img/logotoko.png';"
                                >

                                <div>

                                    <div class="product-name">
                                        <?= htmlspecialchars($value["nama"]) ?>
                                    </div>

                                    <div class="product-code">
                                        GreenLeaf Garden
                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- HARGA -->

                        <div class="col-6 col-md-2">

                            <div class="price-label">
                                Harga
                            </div>

                            <div class="price">
                                Rp <?= number_format($value["harga"], 0, ',', '.') ?>
                            </div>

                        </div>


                        <!-- JUMLAH -->

                        <div class="col-6 col-md-2 text-md-center">

                            <div class="price-label">
                                Jumlah
                            </div>

                            <span class="quantity">
                                <?= $value["jumlah"] ?>
                            </span>

                        </div>


                        <!-- SUBTOTAL -->

                        <div class="col-md-2">

                            <div class="price-label">
                                Subtotal
                            </div>

                            <div class="subtotal">
                                Rp <?= number_format($subtotal, 0, ',', '.') ?>
                            </div>

                        </div>


                        <!-- HAPUS -->

                        <div class="col-md-1 text-md-end">

                            <button
                                type="button"
                                class="delete-btn"
                                onclick="hapusProduk('keranjang.php?aksi=hapus&id=<?= $value["id"] ?>')"
                                title="Hapus produk">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     fill="currentColor"
                                     viewBox="0 0 16 16">

                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>

                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L10.882 4zM2.5 3V2h11v1z"/>

                                </svg>

                            </button>

                        </div>

                    </div>

                </div>

                <?php } ?>

            </div>

        </div>



        <!-- =========================
             SUMMARY
        ========================= -->

        <div class="col-lg-4">

            <div class="summary-card">

                <div class="summary-title">
                    Ringkasan Pesanan
                </div>


                <div class="summary-line">

                    <span>Total Produk</span>

                    <strong>
                        <?= $total_item ?> item
                    </strong>

                </div>


                <div class="summary-line">

                    <span>Biaya Produk</span>

                    <strong>
                        Rp <?= number_format($total, 0, ',', '.') ?>
                    </strong>

                </div>


                <div class="summary-line">

                    <span>Biaya Pengiriman</span>

                    <strong>
                        Gratis
                    </strong>

                </div>


                <div class="summary-total">

                    <span>
                        Total Pembayaran
                    </span>

                    <strong>
                        Rp <?= number_format($total, 0, ',', '.') ?>
                    </strong>

                </div>


               <button
    type="button"
    class="btn-buy"
    onclick="konfirmasiBeli('keranjang.php?aksi=beli')">

    Beli Sekarang

    <svg xmlns="http://www.w3.org/2000/svg"
         width="17"
         height="17"
         fill="currentColor"
         viewBox="0 0 16 16"
         class="ms-1">

        <path fill-rule="evenodd"
              d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
    </svg>

</button> 

                <a href="index.php" class="btn-continue">
                    Lanjut Belanja
                </a>

            </div>

        </div>

    </div>


    <?php } else { ?>


    <!-- =========================
         EMPTY CART
    ========================= -->

    <div class="cart-card">

        <div class="empty-cart">

            <div class="empty-icon">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="48"
                     height="48"
                     fill="currentColor"
                     viewBox="0 0 16 16">

                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5"/>

                    <path d="M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>

                </svg>

            </div>


            <h3>Keranjang Masih Kosong</h3>

            <p>
                Belum ada tanaman atau produk yang kamu pilih.
                Yuk, temukan produk favoritmu dan mulai belanja di GreenLeaf Garden.
            </p>


            <a href="index.php" class="btn-shop">

                Mulai Belanja

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="17"
                     height="17"
                     fill="currentColor"
                     viewBox="0 0 16 16">

                    <path fill-rule="evenodd"
                          d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>

                </svg>

            </a>

        </div>

    </div>


    <?php } ?>


</div>

</main>



<!-- =========================
     FOOTER
========================= -->

<footer>

    <div class="container d-flex flex-wrap justify-content-between align-items-center">

        <span>
            © 2026 <strong>GreenLeaf Garden</strong>
        </span>

        <a href="#">
            Kembali ke atas ↑
        </a>

    </div>

</footer>



<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

/* =========================
   HAPUS PRODUK
========================= */

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


/* =========================
   KONFIRMASI BELI
========================= */

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


/* =========================
   STATUS NOTIFICATION
========================= */

<?php if (isset($_GET['status']) && $_GET['status'] === 'added') { ?>

Swal.fire({

    icon: 'success',
    title: 'Produk Ditambahkan',
    text: 'Produk berhasil ditambahkan ke keranjang.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'

});

<?php } ?>


<?php if (isset($_GET['status']) && $_GET['status'] === 'deleted') { ?>

Swal.fire({

    icon: 'success',
    title: 'Produk Dihapus',
    text: 'Produk berhasil dihapus dari keranjang.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'

});

<?php } ?>


<?php if (isset($_GET['status']) && $_GET['status'] === 'empty') { ?>

Swal.fire({

    icon: 'info',
    title: 'Keranjang Kosong',
    text: 'Belum ada produk yang dapat dibeli.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'

});

<?php } ?>

</script>


</body>

</html>