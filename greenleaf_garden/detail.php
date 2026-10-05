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

$id_user = $is_login ? (int)$_SESSION['id_user'] : null;


/* =========================
   AMBIL ID PRODUK
========================= */
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}


/* =========================
   AMBIL DATA PRODUK
========================= */
$hasil = mysqli_query(
    $koneksi,
    "SELECT 
        p.*,
        k.nama_kategori
     FROM tb_produk p
     LEFT JOIN tb_kategori k 
        ON p.id_kategori = k.id_kategori
     WHERE p.id = $id
     LIMIT 1"
);

if (!$hasil || mysqli_num_rows($hasil) == 0) {
    echo "<script>
        alert('Produk tidak ditemukan.');
        window.location='index.php';
    </script>";
    exit;
}

$data = mysqli_fetch_assoc($hasil);


/* =========================
   CEK STATUS PRODUK
========================= */
if ($data['status'] !== 'aktif') {
    echo "<script>
        alert('Produk ini sudah tidak tersedia.');
        window.location='index.php';
    </script>";
    exit;
}


/* =========================
   DATA PRODUK
========================= */
$nama       = $data['nama'];
$harga      = (int)$data['harga'];
$stok       = (int)$data['stok'];
$deskripsi  = $data['deskripsi'];
$poto       = $data['poto'];
$kategori   = $data['nama_kategori'] ?? 'Produk Tanaman';


/* =========================
   JUMLAH ITEM DI KERANJANG
========================= */
$total_item = 0;

if (!empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $item) {
        $total_item += (int)$item['jumlah'];
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

    <title><?= htmlspecialchars($nama) ?> | GreenLeaf Garden</title>

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>

/* =========================================================
   GREENLEAF GARDEN - DETAIL PRODUK
========================================================= */

:root {

    --green: #198754;
    --green-dark: #11653d;
    --green-light: #eaf7ef;

    --text: #1d2b24;
    --muted: #748078;

    --border: #e3ebe6;

    --white: #ffffff;

    --bg: #f5f8f6;
}


/* =========================================================
   BODY
========================================================= */

body {

    margin: 0;

    font-family: 'Inter', sans-serif;

    color: var(--text);

    background:

        radial-gradient(
            circle at 5% 10%,
            rgba(25, 135, 84, .08),
            transparent 25%
        ),

        radial-gradient(
            circle at 95% 90%,
            rgba(25, 135, 84, .06),
            transparent 25%
        ),

        var(--bg);

}


/* =========================================================
   NAVBAR
========================================================= */

.navbar-custom {

    background: rgba(255,255,255,.94);

    backdrop-filter: blur(16px);

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


/* =========================================================
   MAIN
========================================================= */

.detail-page {

    max-width: 1180px;

    margin: auto;

    padding: 38px 20px 75px;

}


/* =========================================================
   BACK BUTTON
========================================================= */

.back-button {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    color: #59665f;

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    margin-bottom: 25px;

    padding: 10px 15px;

    background: white;

    border: 1px solid var(--border);

    border-radius: 12px;

    transition: .25s ease;

}


.back-button:hover {

    color: var(--green);

    background: var(--green-light);

    border-color: #c5e3d0;

    transform: translateX(-3px);

}


/* =========================================================
   PRODUCT CARD
========================================================= */

.product-detail-card {

    background: rgba(255,255,255,.96);

    border: 1px solid var(--border);

    border-radius: 28px;

    overflow: hidden;

    box-shadow:
        0 20px 60px rgba(30,60,45,.09);

}


/* =========================================================
   IMAGE AREA
========================================================= */

.image-area {

    padding: 30px;

}


.image-container {

    position: relative;

    background: #f4f8f5;

    border-radius: 23px;

    overflow: hidden;

    min-height: 540px;

    display: flex;

    align-items: center;

    justify-content: center;

}


.product-image {

    width: 100%;

    height: 540px;

    object-fit: cover;

    transition: transform .45s ease;

}


.image-container:hover .product-image {

    transform: scale(1.025);

}


.product-badge {

    position: absolute;

    top: 18px;

    left: 18px;

    background: var(--green);

    color: white;

    padding: 8px 14px;

    border-radius: 50px;

    font-size: 11px;

    font-weight: 700;

    box-shadow: 0 6px 18px rgba(25,135,84,.25);

}


/* =========================================================
   DETAIL AREA
========================================================= */

.detail-content {

    padding: 45px 45px 45px 15px;

}


.category-label {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    color: var(--green);

    background: var(--green-light);

    padding: 8px 13px;

    border-radius: 50px;

    font-size: 11px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .4px;

    margin-bottom: 18px;

}


.product-title {

    font-size: clamp(30px, 4vw, 43px);

    line-height: 1.15;

    letter-spacing: -1.5px;

    font-weight: 800;

    margin-bottom: 18px;

    color: var(--text);

}


.product-price {

    font-size: 29px;

    color: var(--green);

    font-weight: 800;

    margin-bottom: 25px;

}


.detail-divider {

    height: 1px;

    background: var(--border);

    margin: 25px 0;

}


/* =========================================================
   INFO
========================================================= */

.info-grid {

    display: grid;

    grid-template-columns: repeat(2, 1fr);

    gap: 12px;

    margin-bottom: 25px;

}


.info-box {

    padding: 15px;

    background: #f7faf8;

    border: 1px solid #e7eee9;

    border-radius: 14px;

}


.info-label {

    display: block;

    color: var(--muted);

    font-size: 11px;

    font-weight: 600;

    margin-bottom: 5px;

}


.info-value {

    color: var(--text);

    font-size: 14px;

    font-weight: 700;

}


.stock-available {

    color: var(--green);

}


.stock-empty {

    color: #dc3545;

}


/* =========================================================
   DESCRIPTION
========================================================= */

.description-title {

    font-size: 16px;

    font-weight: 800;

    margin-bottom: 10px;

}


.product-description {

    color: var(--muted);

    line-height: 1.8;

    font-size: 14px;

    margin-bottom: 25px;

}


/* =========================================================
   QUANTITY
========================================================= */

.buy-label {

    display: block;

    font-size: 13px;

    font-weight: 700;

    margin-bottom: 9px;

}


.quantity-wrapper {

    display: flex;

    align-items: center;

    width: fit-content;

    border: 1px solid #d7e3dc;

    border-radius: 13px;

    overflow: hidden;

    background: white;

    margin-bottom: 17px;

}


.quantity-btn {

    width: 42px;

    height: 43px;

    border: none;

    background: #f4f8f5;

    color: var(--green-dark);

    font-size: 20px;

    font-weight: 600;

    transition: .2s ease;

}


.quantity-btn:hover {

    background: var(--green-light);

}


.quantity-input {

    width: 55px;

    height: 43px;

    border: none;

    outline: none;

    text-align: center;

    font-size: 14px;

    font-weight: 700;

    color: var(--text);

}


.quantity-input::-webkit-inner-spin-button,
.quantity-input::-webkit-outer-spin-button {

    -webkit-appearance: none;

    margin: 0;

}


.quantity-input[type=number] {

    appearance: textfield;

}


/* =========================================================
   ADD CART BUTTON
========================================================= */

.btn-cart {

    width: 100%;

    border: none;

    padding: 15px 20px;

    background:
        linear-gradient(
            135deg,
            #198754,
            #11653d
        );

    color: white;

    border-radius: 14px;

    font-size: 14px;

    font-weight: 700;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 9px;

    transition: .3s ease;

    box-shadow:
        0 10px 25px rgba(25,135,84,.22);

}


.btn-cart:hover {

    color: white;

    transform: translateY(-3px);

    box-shadow:
        0 15px 32px rgba(25,135,84,.3);

}


.btn-cart:disabled {

    background: #aab5ae;

    box-shadow: none;

    cursor: not-allowed;

    transform: none;

}


/* =========================================================
   EMPTY STOCK
========================================================= */

.out-of-stock {

    padding: 14px;

    background: #fff3f3;

    border: 1px solid #ffd6d6;

    color: #dc3545;

    border-radius: 13px;

    text-align: center;

    font-size: 13px;

    font-weight: 700;

}


/* =========================================================
   FOOTER
========================================================= */

footer {

    border-top: 1px solid var(--border);

    padding: 25px 20px;

    color: var(--muted);

    font-size: 13px;

}


footer strong {

    color: var(--text);

}


footer a {

    color: var(--green);

    text-decoration: none;

    font-weight: 600;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991px) {

    .nav-menu {

        display: none;

    }

    .detail-content {

        padding: 10px 30px 35px;

    }

}


@media (max-width: 767px) {

    .detail-page {

        padding: 25px 15px 50px;

    }

    .image-area {

        padding: 15px;

    }

    .image-container {

        min-height: 350px;

    }

    .product-image {

        height: 350px;

    }

    .detail-content {

        padding: 20px;

    }

    .product-title {

        font-size: 30px;

    }

    .product-price {

        font-size: 25px;

    }

    .info-grid {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<header class="navbar-custom">

    <div class="container">

        <div class="navbar-inner d-flex align-items-center justify-content-between">


            <!-- LOGO -->

            <a href="index.php" class="text-decoration-none">

               <span>GREENLEAF GARDEN</span>  

            </a>


            <!-- MENU -->

            <nav class="nav-menu">

                <a href="index.php">
                    Beranda
                </a>

                <a href="index.php#produk">
                    Produk
                </a>

            </nav>


            <!-- ICON -->

            <div class="d-flex align-items-center">


                <!-- PROFILE -->

                <a
                    href="profile.php"
                    class="nav-icon"
                    title="Profil"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="19"
                        height="19"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                    >

                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>

                        <path
                            fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.226 5.468 2.37A7 7 0 0 0 8 1"
                        />

                    </svg>

                </a>


                <!-- CART -->

                <div class="cart-wrapper">

                    <a
                        href="keranjang.php"
                        class="nav-icon"
                        title="Keranjang"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >

                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0-4 0"/>

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



<!-- =========================================================
     MAIN
========================================================= -->

<main>

<div class="detail-page">


    <!-- KEMBALI -->

    <a
        href="index.php#produk"
        class="back-button"
    >

        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="17"
            height="17"
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



    <!-- PRODUCT CARD -->

    <div class="product-detail-card">

        <div class="row g-0">


            <!-- =================================================
                 FOTO
            ================================================= -->

            <div class="col-lg-6">

                <div class="image-area">

                    <div class="image-container">


                        <?php if (!empty($kategori)) { ?>

                            <span class="product-badge">

                                <?= htmlspecialchars($kategori) ?>

                            </span>

                        <?php } ?>


                        <img
                            src="img/<?= htmlspecialchars($poto) ?>"
                            alt="<?= htmlspecialchars($nama) ?>"
                            class="product-image"
                            onerror="this.src='img/logotoko.png';"
                        >

                    </div>

                </div>

            </div>



            <!-- =================================================
                 INFORMASI PRODUK
            ================================================= -->

            <div class="col-lg-6">

                <div class="detail-content">


                    <!-- KATEGORI -->

                    <div class="category-label">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="13"
                            height="13"
                            fill="currentColor"
                            viewBox="0 0 16 16"
                        >

                            <path d="M8 16a2 2 0 1 0 0-4 2 2 0 0 0 0 4M3.5 1a.5.5 0 0 0-.5.5v3.379l6.707 6.707 2.829-2.829L5.828 2.05A.5.5 0 0 0 5.5 2H4v-.5a.5.5 0 0 0-.5-.5z"/>

                        </svg>

                        <?= htmlspecialchars($kategori) ?>

                    </div>


                    <!-- NAMA -->

                    <h1 class="product-title">

                        <?= htmlspecialchars($nama) ?>

                    </h1>


                    <!-- HARGA -->

                    <div class="product-price">

                        Rp <?= number_format($harga, 0, ',', '.') ?>

                    </div>


                    <div class="detail-divider"></div>


                    <!-- INFO -->

                    <div class="info-grid">


                        <!-- STOK -->

                        <div class="info-box">

                            <span class="info-label">
                                Ketersediaan
                            </span>

                            <?php if ($stok > 0) { ?>

                                <span class="info-value stock-available">

                                    Tersedia

                                </span>

                            <?php } else { ?>

                                <span class="info-value stock-empty">

                                    Stok Habis

                                </span>

                            <?php } ?>

                        </div>


                        <!-- JUMLAH STOK -->

                        <div class="info-box">

                            <span class="info-label">
                                Stok
                            </span>

                            <span class="info-value">

                                <?= $stok ?> unit

                            </span>

                        </div>


                        <!-- STATUS -->

                        <div class="info-box">

                            <span class="info-label">
                                Status
                            </span>

                            <span class="info-value stock-available">

                                Produk Aktif

                            </span>

                        </div>


                        <!-- TOKO -->

                        <div class="info-box">

                            <span class="info-label">
                                Penjual
                            </span>

                            <span class="info-value">

                                GreenLeaf Garden

                            </span>

                        </div>


                    </div>


                    <!-- DESKRIPSI -->

                    <div class="description-title">

                        Deskripsi Produk

                    </div>

                    <div class="product-description">

                        <?= nl2br(htmlspecialchars($deskripsi)) ?>

                    </div>


                    <!-- FORM KERANJANG -->

                    <?php if ($stok > 0) { ?>

                    <form
                        method="post"
                        action="keranjang.php?id=<?= $id ?>"
                        onsubmit="return cekLogin(event, this)"
                    >


                        <input
                            type="hidden"
                            name="hidden_foto"
                            value="<?= htmlspecialchars($poto) ?>"
                        >


                        <input
                            type="hidden"
                            name="hidden_nama"
                            value="<?= htmlspecialchars($nama) ?>"
                        >


                        <input
                            type="hidden"
                            name="hidden_harga"
                            value="<?= $harga ?>"
                        >


                        <label class="buy-label">
                            Jumlah
                        </label>


                        <div class="quantity-wrapper">


                            <button
                                type="button"
                                class="quantity-btn"
                                onclick="kurangiJumlah()"
                            >
                                −
                            </button>


                            <input
                                type="number"
                                id="jumlah"
                                name="jumlah"
                                value="1"
                                min="1"
                                max="<?= $stok ?>"
                                class="quantity-input"
                                readonly
                            >


                            <button
                                type="button"
                                class="quantity-btn"
                                onclick="tambahJumlah()"
                            >
                                +
                            </button>


                        </div>


                        <button
                            type="submit"
                            name="add"
                            class="btn-cart"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >

                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>

                            </svg>

                            Tambah ke Keranjang

                        </button>


                    </form>

                    <?php } else { ?>

                        <div class="out-of-stock">

                            Produk sedang habis dan belum dapat dibeli.

                        </div>

                    <?php } ?>


                </div>

            </div>

        </div>

    </div>

</div>

</main>



<!-- =========================================================
     FOOTER
========================================================= -->

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


<script>

/* =========================================================
   JUMLAH PRODUK
========================================================= */

function tambahJumlah() {

    const input = document.getElementById('jumlah');

    const max = parseInt(input.max);

    let jumlah = parseInt(input.value);

    if (jumlah < max) {

        input.value = jumlah + 1;

    }

}


function kurangiJumlah() {

    const input = document.getElementById('jumlah');

    let jumlah = parseInt(input.value);

    if (jumlah > 1) {

        input.value = jumlah - 1;

    }

}


/* =========================================================
   CEK LOGIN
========================================================= */

function cekLogin(event, form) {

    const isLogin = <?= $is_login ? 'true' : 'false' ?>;


    if (!isLogin) {

        event.preventDefault();


        Swal.fire({

            icon: 'info',

            title: 'Login Terlebih Dahulu',

            text: 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.',

            showCancelButton: true,

            confirmButtonColor: '#198754',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Login Sekarang',

            cancelButtonText: 'Batal',

            borderRadius: '18px'

        }).then((result) => {

            if (result.isConfirmed) {

                window.location.href = 'login.php';

            }

        });


        return false;

    }


    return true;

}

</script>


</body>

</html>