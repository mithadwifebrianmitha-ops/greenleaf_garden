```php
<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id"> 
<head> 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GREENLEAF GARDEN</title>
  <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
html {
  scroll-behavior: smooth;
}

body {
  margin: 0;
  font-family: "Poppins", Arial, sans-serif;
  background: #f7faf8;
  color: #26382e;
}

/* =========================================================
   NAVBAR 
========================================================= */

/* =========================================================
   NAVBAR — GAYA SAMA DENGAN PROFILE
========================================================= */

.navbar-greenleaf {
  background: rgba(255, 255, 255, 0.94);

  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);

  box-shadow:
    0 4px 20px rgba(0, 0, 0, 0.06);

  border-bottom:
    1px solid rgba(25, 135, 84, 0.08);

  padding: 10px 0;

  z-index: 1050;
}

.navbar-greenleaf .container {
  max-width: 1200px;
}


/* =========================================================
   LOGO
========================================================= */

.navbar-greenleaf .greenleaf-logo {

  display: flex;
  align-items: center;

  color: #198754;

  font-size: 21px;
  font-weight: 800;

  letter-spacing: 0.3px;

  text-decoration: none;

  transition: 0.25s ease;
}

.navbar-greenleaf .greenleaf-logo:hover {
  color: #146c43;
}


/* =========================================================
   MENU
========================================================= */

.navbar-greenleaf .nav {
  gap: 8px;
}

.navbar-greenleaf .nav-link {

  display: flex;
  align-items: center;

  color: #53655c !important;

  font-size: 14px;
  font-weight: 600;

  padding: 10px 15px !important;

  border-radius: 10px;

  text-decoration: none;

  transition: all 0.25s ease;
}


/* IKON MENU */

.navbar-greenleaf .nav-link i {

  margin-right: 6px;

  font-size: 15px;

  line-height: 1;
}


/* HOVER */

.navbar-greenleaf .nav-link:hover {

  color: #198754 !important;

  background: #edf8f2;
}


/* =========================================================
   ICON PROFILE & KERANJANG
========================================================= */

.navbar-greenleaf .navbar-icon {

  width: 43px;
  height: 43px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  text-decoration: none;

  color: #198754;

  background: #edf8f2;

  border: none;

  transition: all 0.3s ease;

  font-size: 19px;

  padding: 0;
}

.navbar-greenleaf .navbar-icon:hover {

  background: #198754;

  color: #ffffff;

  transform: translateY(-2px);
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 991px) {

  .navbar-greenleaf {
    padding: 10px 0;
  }

  .navbar-greenleaf .container {
    max-width: 100%;
  }

  .navbar-greenleaf .greenleaf-logo {
    font-size: 18px;
  }

  .navbar-greenleaf .nav {

    width: 100%;

    justify-content: flex-start !important;

    gap: 3px;

    margin-top: 10px;

    padding: 5px 0;
  }

  .navbar-greenleaf .nav-link {

    padding: 11px 13px !important;

    font-size: 13px;
  }

  .navbar-greenleaf .navbar-icon {

    width: 40px;
    height: 40px;

    font-size: 17px;
  }

  .navbar-greenleaf .d-flex {

    margin-top: 8px;
  }

}


/* =========================================================
   EXTRA SMALL
========================================================= */

@media (max-width: 480px) {

  .navbar-greenleaf .greenleaf-logo {

    font-size: 15px;

    letter-spacing: 0.5px;
  }

  .navbar-greenleaf .nav-link {

    font-size: 11px;

    padding: 8px 9px !important;
  }

  .navbar-greenleaf .nav-link i {

    font-size: 13px;

    margin-right: 4px;
  }

  .navbar-greenleaf .navbar-icon {

    width: 36px;
    height: 36px;

    font-size: 16px;
  }

}

/* =========================================================
   HERO / CAROUSEL
========================================================= */

.carousel {
  margin-top: 72px;
  background: #173d2a;
}

.carousel-item {
  height: 650px;
  position: relative;
  overflow: hidden;

  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* IMAGE 1 */

.carousel-item:nth-child(1) {
  background:
    linear-gradient(
      90deg,
      rgba(10, 35, 23, 0.78) 0%,
      rgba(10, 35, 23, 0.42) 42%,
      rgba(10, 35, 23, 0.10) 75%
    ),
    url('img/tanaman hias.jpg') center / cover no-repeat;
}

/* IMAGE 2 */

.carousel-item:nth-child(2) {
  background:
    linear-gradient(
      90deg,
      rgba(10, 35, 23, 0.15) 0%,
      rgba(10, 35, 23, 0.40) 55%,
      rgba(10, 35, 23, 0.78) 100%
    ),
    url('img/tanaman bunga.jpg') center / cover no-repeat;
}

/* HIDE BOOTSTRAP PLACEHOLDER */

.carousel-item > svg {
  display: none;
}

.carousel-caption {
  z-index: 2;
  bottom: 17%;
  max-width: 720px;
}

.carousel-caption h1 {
  margin-bottom: 18px;

  font-family: "Poppins", Arial, sans-serif;
  font-size: clamp(48px, 6vw, 82px);
  font-weight: 600;
  line-height: 1.02;
  letter-spacing: -3px;
  text-transform: capitalize;
}

.carousel-caption p {
  max-width: 530px;
  margin-bottom: 22px;

  font-family: "Poppins", Arial, sans-serif;
  font-size: 15px;
  line-height: 1.8;
  color: rgba(255,255,255,0.88);
}

/* HERO BUTTON */

.carousel-caption .btn {
  border: 1px solid rgba(255,255,255,0.65);

  background: rgba(255,255,255,0.96);
  color: #145c3a;

  border-radius: 5px;

  padding: 11px 27px;

  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.5px;

  box-shadow: none;

  transition: all 0.3s ease;
}

.carousel-caption .btn:hover {
  background: #198754;
  color: #ffffff;
  border-color: #198754;

  transform: translateY(-3px);

  box-shadow:
    0 12px 30px rgba(0,0,0,0.18);
}


/* =========================================================
   CAROUSEL INDICATOR
========================================================= */

.carousel-indicators {
  bottom: 28px;
  z-index: 3;
}

.carousel-indicators button {
  width: 28px;
  height: 3px;

  border: 0;
  border-radius: 10px;

  margin: 0 4px;

  opacity: 0.55;
}

.carousel-indicators button.active {
  opacity: 1;
}


/* =========================================================
   PRODUCT SECTION
========================================================= */

.product-section {
  padding: 115px 0;
  background: #f7faf8;
}

.product-section:nth-of-type(even) {
  background: #eef6f1;
}

/* SECTION HEADER */

.section-label {
  display: inline-block;

  color: #198754;

  font-size: 10px;
  font-weight: 700;

  letter-spacing: 3px;
  text-transform: uppercase;

  margin-bottom: 14px;
}

.section-title {
  margin: 0;

  color: #173d2a;

  font-family: "Poppins", Arial, sans-serif;
  font-size: clamp(35px, 4vw, 52px);
  font-weight: 500;

  letter-spacing: -2px;
}

.section-line {
  width: 45px;
  height: 2px;

  margin: 22px auto 58px;

  background: #198754;
  border-radius: 10px;
}


/* =========================================================
   PRODUCT CARD — MINIMAL BEHANCE
========================================================= */

.product-card {
  position: relative;

  border: none !important;
  border-radius: 6px !important;

  overflow: hidden;

  background: #ffffff;

  box-shadow: none !important;

  transition:
    transform 0.4s ease,
    box-shadow 0.4s ease;
}

.product-card:hover {
  transform: translateY(-8px);

  box-shadow:
    0 20px 45px rgba(25, 80, 52, 0.12) !important;
}


/* =========================================================
   PRODUCT IMAGE
========================================================= */

.product-image-wrapper {
  height: 330px;

  overflow: hidden;

  position: relative;

  background: #e8f2ec;
}

.product-image {
  width: 100%;
  height: 100%;

  object-fit: cover;

  transition:
    transform 0.7s cubic-bezier(.2,.7,.2,1);
}

.product-card:hover .product-image {
  transform: scale(1.06);
}


/* IMAGE OVERLAY */

.product-image-wrapper::after {
  content: "";

  position: absolute;

  inset: 0;

  background:
    linear-gradient(
      to top,
      rgba(15, 50, 32, 0.10),
      transparent 40%
    );

  pointer-events: none;
}


/* =========================================================
   PRODUCT BODY
========================================================= */

.product-body {
  padding: 23px 21px 25px;
}

.product-name {
  margin-bottom: 7px;

  color: #183e2c;

  font-size: 17px;
  font-weight: 600;

  letter-spacing: -0.2px;
}

.product-price {
  display: block;

  margin-bottom: 10px;

  color: #198754;

  font-size: 14px;
  font-weight: 700;
}

.product-description {
  min-height: 42px;

  margin-bottom: 0;

  color: #77857e;

  font-size: 12px;
  line-height: 1.7;
}


/* =========================================================
   BUTTON
========================================================= */

.btn-greenleaf {
  border: 1px solid #198754;

  background: #198754;
  color: #ffffff;

  border-radius: 4px;

  padding: 10px 20px;

  font-size: 11px;
  font-weight: 600;

  letter-spacing: 0.5px;

  box-shadow: none;

  transition: all 0.3s ease;
}

.btn-greenleaf:hover {
  background: #145c3a;
  border-color: #145c3a;

  color: #ffffff;

  transform: translateY(-2px);

  box-shadow:
    0 8px 20px rgba(25,135,84,0.16);
}


/* =========================================================
   FOOTER
========================================================= */

.footer-greenleaf {
  padding: 70px 0;

  background:
    radial-gradient(
      circle at 20% 20%,
      rgba(25,135,84,0.08),
      transparent 30%
    ),
    #eaf4ee;

  border-top: 1px solid #d7e6dc;
}

.footer-greenleaf h5 {
  color: #145c3a !important;

  font-size: 16px;
  font-weight: 700;

  letter-spacing: 1.5px;
}

.footer-greenleaf p {
  color: #718078;

  font-size: 12px;

  margin-top: 10px;
}

.back-top {
  display: inline-block;

  color: #198754;

  font-size: 12px;
  font-weight: 600;

  text-decoration: none;

  margin-top: 8px;

  transition: 0.3s;
}

.back-top:hover {
  color: #145c3a;
  transform: translateY(-3px);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

  .navbar-greenleaf header {
    min-height: 65px;
  }

  .greenleaf-logo {
    font-size: 14px;
    letter-spacing: 1px;
  }

  .navbar-greenleaf .nav {
    order: 3;

    width: 100%;

    justify-content: center !important;

    margin-top: 8px;

    padding-bottom: 4px;
  }

  .navbar-greenleaf .nav-link {
    padding: 6px 8px !important;

    font-size: 10px;
  }

  .carousel {
    margin-top: 105px;
  }

  .carousel-item {
    height: 500px;
  }

  .carousel-caption {
    bottom: 13%;
    left: 8%;
    right: 8%;
  }

  .carousel-caption h1 {
    font-size: 42px;
    letter-spacing: -1.5px;
  }

  .carousel-caption p {
    font-size: 12px;
    line-height: 1.7;
  }

  .carousel-caption .btn {
    padding: 9px 21px;
    font-size: 11px;
  }

  .product-section {
    padding: 80px 0;
  }

  .section-title {
    font-size: 35px;
  }

  .section-line {
    margin-bottom: 40px;
  }

  .product-image-wrapper {
    height: 280px;
  }

  .product-body {
    padding: 19px;
  }
}


/* =========================================================
   EXTRA SMALL
========================================================= */

@media (max-width: 480px) {

  .greenleaf-logo {
    font-size: 12px;
  }

  .navbar-icon {
    width: 35px;
    height: 35px;
  }

  .navbar-icon svg {
    width: 17px;
    height: 17px;
  }

  .carousel-item {
    height: 460px;
  }

  .carousel-caption h1 {
    font-size: 35px;
  }

  .product-image-wrapper {
    height: 250px;
  }

}
</style>
</head>


<body>

<!-- =========================
     NAVBAR
========================= -->

<div class="navbar-greenleaf fixed-top">

  <div class="container">

    <header class="d-flex align-items-center justify-content-between">

      <!-- LOGO -->

      <a href="index.php"
         class="greenleaf-logo">

        <span>GREENLEAF GARDEN</span>

      </a>


      <!-- MENU -->

      <ul class="nav justify-content-center">

        <!-- SEMUA PRODUK -->

        <li>
          <a href="#produk"
             class="nav-link">

            <i class="bi bi-grid"></i>

            Semua Produk

          </a>
        </li>


        <!-- TANAMAN HIAS -->

        <li>
          <a href="#tanaman-hias"
             class="nav-link">

            <i class="bi bi-flower1"></i>

            Tanaman Hias

          </a>
        </li>


        <!-- TANAMAN BUNGA -->

        <li>
          <a href="#tanaman-bunga"
             class="nav-link">

            <i class="bi bi-flower2"></i>

            Tanaman Bunga

          </a>
        </li>

      </ul>


      <!-- PROFILE & KERANJANG -->

      <div class="d-flex align-items-center gap-2">


        <!-- PROFILE -->

        <a href="profile.php"
           class="navbar-icon"
           title="Profile">

          <i class="bi bi-person-fill"></i>

        </a>


        <!-- KERANJANG -->

        <a href="keranjang.php"
           class="navbar-icon"
           title="Keranjang">

          <i class="bi bi-cart3"></i>

        </a>

      </div>

    </header>

  </div>

</div>

<!-- =========================
     CAROUSEL
========================= -->

<div id="myCarousel"
     class="carousel slide mb-6"
     data-bs-ride="carousel">

  <div class="carousel-indicators">

    <button
      type="button"
      data-bs-target="#myCarousel"
      data-bs-slide-to="0"
      class="active"
      aria-current="true"
      aria-label="Slide 1">
    </button>

    <button
      type="button"
      data-bs-target="#myCarousel"
      data-bs-slide-to="1"
      aria-label="Slide 2">
    </button>

  </div>


  <div class="carousel-inner">

    <div class="carousel-item active">

      <div class="container">

        <div class="carousel-caption text-start">

          <h1>tanaman hias</h1>

          <p class="opacity-75">
            sedang mencari tanaman hias disini tempatnya
          </p>

          <p>
            <a class="btn btn-lg btn-primary"
               href="#tanaman-hias">
              lihat
            </a>
          </p>

        </div>

      </div>

    </div>


    <div class="carousel-item">

      <div class="container">

        <div class="carousel-caption text-end">

          <h1>tanaman bunga</h1>

          <p>
            gak perlu ribet kemana mana tinggl pilih dan klik, barang sampai ke rumahmu
          </p>

          <p>
            <a class="btn btn-lg btn-primary"
               href="#tanaman-bunga">
              lihat
            </a>
          </p>

        </div>

      </div>

    </div>

  </div>


  <button
    class="carousel-control-prev"
    type="button"
    data-bs-target="#myCarousel"
    data-bs-slide="prev">

    <span class="carousel-control-prev-icon"
          aria-hidden="true">
    </span>

    <span class="visually-hidden">
      Previous
    </span>

  </button>


  <button
    class="carousel-control-next"
    type="button"
    data-bs-target="#myCarousel"
    data-bs-slide="next">

    <span class="carousel-control-next-icon"
          aria-hidden="true">
    </span>

    <span class="visually-hidden">
      Next
    </span>

  </button>

</div>


<!-- =========================
     SEMUA PRODUK
========================= -->

<div class="product-section"
     id="produk">

  <div class="container">

    <div class="text-center">

      <span class="section-label">
        GreenLeaf Garden
      </span>

      <h1 class="section-title">
        Semua Produk
      </h1>

      <div class="section-line"></div>

    </div>


    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

      <?php

      $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk WHERE status = 'aktif'"
      );

      while ($data = mysqli_fetch_array($query)) {

      ?>

      <div class="col">

        <div class="card product-card shadow-sm">

          <div class="product-image-wrapper">

            <img src="img/<?= $data['poto'] ?>"
                 class="product-image"
                 alt="<?= htmlspecialchars($data['nama']) ?>">

          </div>


          <div class="product-body">

            <h5 class="product-name">

              <?= htmlspecialchars($data['nama']) ?>

            </h5>


            <p class="product-price">

              Rp <?= number_format($data['harga'], 0, ',', '.') ?>

            </p>


            <p class="product-description">

              <?= htmlspecialchars($data['deskripsi']) ?>

            </p>


            <a href="detail.php?id=<?= $data['id'] ?>"
               class="btn btn-greenleaf w-100 mt-3">

              Lihat Detail

            </a>

          </div>

        </div>

      </div>

      <?php } ?>

    </div>

  </div>

</div>


<!-- =========================
     TANAMAN HIAS
========================= -->

<div class="product-section"
     id="tanaman-hias">

  <div class="container">

    <div class="text-center">

      <span class="section-label">
        Tanaman Hias
      </span>

      <h1 class="section-title">
        Tanaman Hias
      </h1>

      <div class="section-line"></div>

    </div>


    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

      <?php

      $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk
         WHERE id_kategori = 1
         AND status = 'aktif'"
      );

      while ($data = mysqli_fetch_array($query)) {

      ?>

      <div class="col">

        <div class="card product-card shadow-sm">

          <div class="product-image-wrapper">

            <img src="img/<?= $data['poto'] ?>"
                 class="product-image"
                 alt="<?= htmlspecialchars($data['nama']) ?>">

          </div>


          <div class="product-body">

            <h5 class="product-name">

              <?= htmlspecialchars($data['nama']) ?>

            </h5>


            <p class="product-price">

              Rp <?= number_format($data['harga'], 0, ',', '.') ?>

            </p>


            <p class="product-description">

              <?= htmlspecialchars($data['deskripsi']) ?>

            </p>


            <a href="detail.php?id=<?= $data['id'] ?>"
               class="btn btn-greenleaf w-100 mt-3">

              Lihat Detail

            </a>

          </div>

        </div>

      </div>

      <?php } ?>

    </div>

  </div>

</div>


<!-- =========================
     TANAMAN BUNGA
========================= -->

<div class="product-section"
     id="tanaman-bunga">

  <div class="container">

    <div class="text-center">

      <span class="section-label">
        Tanaman Bunga
      </span>

      <h1 class="section-title">
        Tanaman Bunga
      </h1>

      <div class="section-line"></div>

    </div>


    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

      <?php

      $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk
         WHERE id_kategori = 2
         AND status = 'aktif'"
      );

      while ($data = mysqli_fetch_array($query)) {

      ?>

      <div class="col">

        <div class="card product-card shadow-sm">

          <div class="product-image-wrapper">

            <img src="img/<?= $data['poto'] ?>"
                 class="product-image"
                 alt="<?= htmlspecialchars($data['nama']) ?>">

          </div>


          <div class="product-body">

            <h5 class="product-name">

              <?= htmlspecialchars($data['nama']) ?>

            </h5>


            <p class="product-price">

              Rp <?= number_format($data['harga'], 0, ',', '.') ?>

            </p>


            <p class="product-description">

              <?= htmlspecialchars($data['deskripsi']) ?>

            </p>


            <a href="detail.php?id=<?= $data['id'] ?>"
               class="btn btn-greenleaf w-100 mt-3">

              Lihat Detail

            </a>

          </div>

        </div>

      </div>

      <?php } ?>

    </div>

  </div>

</div>


<!-- =========================
     FOOTER
========================= -->

<footer class="footer-greenleaf">

  <div class="container text-center">

    <h5 style="color:#0f5132; font-weight:700;">
      GREENLEAF GARDEN
    </h5>

    <p>
      GreenLeaf Garden — Tanaman dan kebutuhan berkebun.
    </p>

    <a href="#"
       class="back-top">
      Back to top ↑
    </a>

  </div>

</footer>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

<?php if (isset($_GET['status']) && $_GET['status'] == 'login') { ?>

Swal.fire({
    icon: 'success',
    title: 'Login Berhasil!',
    text: 'Selamat datang di GreenLeaf Garden.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});

<?php } ?>


<?php if (isset($_GET['status']) && $_GET['status'] == 'logout') { ?>

Swal.fire({
    icon: 'success',
    title: 'Logout Berhasil!',
    text: 'Anda telah keluar dari akun.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});

<?php } ?>

</script>


</body>
</html>
```