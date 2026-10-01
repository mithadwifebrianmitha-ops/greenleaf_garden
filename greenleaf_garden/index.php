<?php
session_start();
include 'koneksi.php';
?>
       <!DOCTYPE html>
      <html lang="id"> 
        <head> 
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title>GREENLEAF GARDEN</title> <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
          <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
          <style>
html {
  scroll-behavior: smooth;
}

body {
  margin: 0;
  font-family: Arial, sans-serif;
  background-color: #f8fbf9;
  color: #304238;
}
body {
  font-family: "Poppins", Arial, sans-serif;
}


/* =========================
   NAVBAR
========================= */

.navbar-greenleaf {
  background-color: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border-bottom: 1px solid #dfeae3;
  box-shadow: 0 4px 18px rgba(24, 73, 45, 0.07);
  z-index: 1050;
}

.navbar-greenleaf .container {
  max-width: 1200px;
}

.navbar-greenleaf .nav-link {
  color: #59665f !important;
  font-size: 13px;
  font-weight: 500;
  border-radius: 7px;
  margin: 0 2px;
  transition: all 0.25s ease;
}

.navbar-greenleaf .nav-link:hover {
  color: #198754 !important;
  background-color: #eef8f2;
}


/* =========================
   LOGO
========================= */

.greenleaf-logo {
  color: #16824e;
  font-size: 18px;
  font-weight: 700;
  letter-spacing: 0.7px;
  text-decoration: none;
  transition: 0.3s;
}

.greenleaf-logo:hover {
  color: #0f633b;
  transform: translateY(-1px);
}


/* =========================
   ICON
========================= */

.navbar-icon {
  color: #3e4b44;
  font-size: 19px;
  text-decoration: none;
  border-radius: 9px;
  padding: 7px 9px !important;
  transition: all 0.3s ease;
}

.navbar-icon:hover {
  color: #198754;
  background-color: #eaf7ef;
  transform: translateY(-2px);
}

/* =========================
   CAROUSEL / HERO
========================= */

.carousel {
  margin-top: 65px;
}

.carousel-item {
  height: 580px;
  position: relative;
  overflow: hidden;

  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

/* GAMBAR SLIDE 1 */
.carousel-item:nth-child(1) {
  background-image: url("https://images.unsplash.com/photo-1497250681960-ef046c08a56e?auto=format&fit=crop&w=1600&q=85");
}

/* GAMBAR SLIDE 2 */
.carousel-item:nth-child(2) {
  background-image: url("https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1600&q=85");
}

/* GAMBAR SLIDE 3 */
.carousel-item:nth-child(3) {
  background-image: url("https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=1600&q=85");
}
.carousel-caption {
  z-index: 2;
  bottom: 18%;
  max-width: 700px;
}

.carousel-caption h1 {
  font-family: Georgia, serif;
  font-size: 52px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.carousel-caption p,
.carousel-caption .btn {
  font-family: Arial, sans-serif;
}

.carousel-caption h1,
.carousel-caption p,
.carousel-caption .btn {
  font-family: "Trebuchet MS", Arial, sans-serif;
}
.carousel-caption p:last-child {
  margin-top: 10px;
  margin-bottom: 0;
}

.carousel-caption .btn {
  border: none;
  background-color: #ffffff;
  color: #146b42;
  border-radius: 25px;
  padding: 10px 25px;
  font-size: 14px;
  font-weight: 600;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
  transition: all 0.3s ease;
}

.carousel-caption .btn:hover {
  background-color: #146b42;
  color: #ffffff;
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
}

/* =========================
   CAROUSEL INDICATOR
========================= */

.carousel-indicators {
  z-index: 3;
}

.carousel-indicators button {
  width: 25px;
  height: 4px;
  border: 0;
  border-radius: 20px;
  margin: 0 4px;
}


/* =========================
   PRODUCT SECTION
========================= */

.product-section {
  padding: 85px 0;
  background-color: #f8fbf9;
}

.product-section:nth-of-type(even) {
  background-color: #899b8a;
}

.section-label {
  display: inline-block;
  letter-spacing: 3px;
  font-size: 10px;
  font-weight: 700;
  color: #198754;
  text-transform: uppercase;
  padding: 6px 12px;
  background-color: #eaf7ef;
  border-radius: 20px;
}

.section-title {
  font-size: 38px;
  font-weight: 500;
  margin-top: 12px;
  color: #153e2a;
}

.section-line {
  width: 55px;
  height: 3px;
  border-radius: 10px;
  background-color: #198754;
  margin: 17px auto 48px;
}

.product-section {
  font-family: "Trebuchet MS", Arial, sans-serif;
}

.section-label,
.section-title,
.product-name,
.product-price,
.product-description,
.btn-greenleaf {
  font-family: "Trebuchet MS", Arial, sans-serif;
}

/* =========================
   PRODUCT CARD
========================= */

.product-card {
  border: 1px solid #dfebe4;
  border-radius: 16px;
  overflow: hidden;
  background-color: #ffffff;
  transition: all 0.4s ease;
  height: 100%;
}

.product-card:hover {
  transform: translateY(-9px);
  border-color: #bcdac8;
  box-shadow:
    0 18px 40px rgba(22, 111, 68, 0.13) !important;
}


/* =========================
   PRODUCT IMAGE
========================= */

.product-image-wrapper {
  height: 305px;
  overflow: hidden;
  position: relative;
  background-color: #edf6f0;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.product-card:hover .product-image {
  transform: scale(1.07);
}


/* =========================
   PRODUCT BODY
========================= */

.product-body {
  padding: 22px;
}

.product-name {
  color: #1c5135;
  font-size: 19px;
  font-weight: 600;
  margin-bottom: 7px;
}

.product-price {
  display: inline-block;
  color: #198754;
  font-size: 15px;
  font-weight: 700;
  margin-bottom: 10px;
}

.product-description {
  color: #748078;
  font-size: 13px;
  line-height: 1.65;
  min-height: 42px;
}


/* =========================
   BUTTON
========================= */

.btn-greenleaf {
  background:
    linear-gradient(
      135deg,
      #1d9c5f,
      #13a25f
    );
  color: #ffffff;
  border-radius: 25px;
  padding: 10px 20px;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.3px;
  border: none;
  transition: all 0.3s ease;
  box-shadow: 0 5px 12px rgba(25, 135, 84, 0.12);
}

.btn-greenleaf:hover {
  background:
    linear-gradient(
      135deg,
      #1d9c5f,
      #13a25f
    );
  color: #ffffff;
  transform: translateY(-2px);
  box-shadow: 0 8px 18px rgba(25, 135, 84, 0.20);
}


/* =========================
   FOOTER
========================= */

.footer-greenleaf {
  background:
    radial-gradient(
      circle at 15% 20%,
      rgba(25, 135, 84, 0.08),
      transparent 30%
    ),
    linear-gradient(
      135deg,
      #e6f5eb,
      #f7fbf8
    );

  border-top: 1px solid #d5e7dc;
  padding: 55px 0;
}

.footer-greenleaf h5 {
  color: #12452c !important;
  font-weight: 700;
  letter-spacing: 0.8px;
}

.footer-greenleaf p {
  color: #687970;
  font-size: 13px;
  margin-top: 9px;
}

.back-top {
  display: inline-block;
  color: #198754;
  text-decoration: none;
  font-size: 13px;
  margin-top: 5px;
  transition: 0.3s;
}

.back-top:hover {
  color: #0f5132;
  transform: translateY(-3px);
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

  .navbar-greenleaf header {
    justify-content: center !important;
  }

  .navbar-greenleaf .nav {
    order: 3;
    width: 100%;
    justify-content: center !important;
    margin-top: 8px;
  }

  .navbar-greenleaf .nav-link {
    padding: 6px 7px !important;
    font-size: 11px;
    margin: 0;
  }

  .carousel {
    margin-top: 105px;
  }

  .carousel-item {
    height: 450px;
  }

  .carousel-caption {
    bottom: 15%;
  }

  .carousel-caption h1 {
    font-size: 35px;
  }

  .carousel-caption p {
    font-size: 13px;
  }

  .carousel-caption .btn {
    padding: 9px 21px;
    font-size: 13px;
  }

  .product-section {
    padding: 65px 0;
  }

  .section-title {
    font-size: 31px;
  }

  .product-image-wrapper {
    height: 275px;
  }

  .product-body {
    padding: 19px;
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

    <header class="d-flex flex-wrap align-items-center justify-content-between py-2">


      <!-- LOGO -->

      <a href="index.php"
         class="greenleaf-logo">

        GREENLEAF GARDEN

      </a>


      <!-- MENU -->

      <ul class="nav col-auto justify-content-center">

        <li>
          <a href="#produk"
             class="nav-link px-3 fw-medium">

            Semua Produk

          </a>
        </li>

        <li>
          <a href="#tanaman-hias"
             class="nav-link px-3">

            Tanaman Hias

          </a>
        </li>

        <li>
          <a href="#tanaman-bunga"
             class="nav-link px-3">

            Tanaman Bunga

          </a>
        </li>

      </ul>


      <!-- PROFILE & KERANJANG -->

      <div class="d-flex gap-1">


       <!-- PROFILE -->

<a href="profile.php"
   class="btn navbar-icon"
   title="Profile">

    <svg xmlns="http://www.w3.org/2000/svg"
         width="25"
         height="25"
         fill="currentColor"
         viewBox="0 0 16 16">

        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>

        <path fill-rule="evenodd"
              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>

    </svg>

</a>


        <!-- KERANJANG -->

        <a href="keranjang.php"
           class="btn navbar-icon"
           title="Keranjang">

          <svg xmlns="http://www.w3.org/2000/svg"
               width="25"
               height="25"
               fill="currentColor"
               viewBox="0 0 16 16">

            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1-1 1 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1-1 1 1 1 0 0 1 0-2"/>

          </svg>

        </a>

      </div>

    </header>

  </div>

</div>



<!-- =========================
     CAROUSEL
========================= -->

 <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button
          type="button"
          data-bs-target="#myCarousel"
          data-bs-slide-to="0"
          class="active"
          aria-current="true"
          aria-label="Slide 1"
        ></button>

        <button
          type="button"
          data-bs-target="#myCarousel"
          data-bs-slide-to="1"
          aria-label="Slide 2"
        ></button>

      </div>

      <div class="carousel-inner">

        <div class="carousel-item active">
          <svg
            aria-hidden="true"
            class="bd-placeholder-img"
            height="100%"
            preserveAspectRatio="xMidYMid slice"
            width="100%"
            xmlns="http://www.w3.org/2000/svg"
          >
            <rect
              width="100%"
              height="100%"
              fill="var(--bs-secondary-color)"
            ></rect>
          </svg>

          <div class="container">
            <div class="carousel-caption text-start">
              <h1>tanaman hias </h1>
              <p class="opacity-75">
                sedang mencari tanaman hias disini tempatnya
              </p>
              <p>
                <a class="btn btn-lg btn-primary" href="#tanaman-hias">lihat</a>
              </p>
            </div>
          </div>
        </div>


        <div class="carousel-item">
          <svg
            aria-hidden="true"
            class="bd-placeholder-img"
            height="100%"
            preserveAspectRatio="xMidYMid slice"
            width="100%"
            xmlns="http://www.w3.org/2000/svg" 
          > 
            <rect 
              width="100%" 
              height="100%" 
              fill="var(--bs-secondary-color)" 
            ></rect> 
          </svg> 

          <div class="container"> 
            <div class="carousel-caption text-end"> 
              <h1>tanaman bunga</h1> 
              <p> 
                gak perlu ribet kemana mana tinggl pilih dan klik, barang sampai ke rumahmu 
              </p> 
              <p> 
                <a class="btn btn-lg btn-primary" href="#tanaman-bunga">lihat</a> 
              </p> 
            </div> 
          </div> 
        </div> 

      </div> 

      <button 
        class="carousel-control-prev" 
        type="button" 
        data-bs-target="#myCarousel" 
        data-bs-slide="prev" 
      > 
        <span class="carousel-control-prev-icon" aria-hidden="true"></span> 
        <span class="visually-hidden">Previous</span> 
      </button> 

      <button 
        class="carousel-control-next" 
        type="button" 
        data-bs-target="#myCarousel" 
        data-bs-slide="next" 
      > 
        <span class="carousel-control-next-icon" aria-hidden="true"></span> 
        <span class="visually-hidden">Next</span> 
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
        "SELECT * FROM tb_produk"
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
        "SELECT * FROM tb_produk WHERE id_kategori = 1"
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
        "SELECT * FROM tb_produk WHERE id_kategori = 2"
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

<?php if (isset($_GET['status']) && $_GET['status'] == 'login') { ?>

<script>
Swal.fire({
    icon: 'success',
    title: 'Login Berhasil!',
    text: 'Selamat datang di GreenLeaf Garden.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});
</script>

<?php } ?>

</body>
</body>
</html>