<?php
session_start();
include 'koneksi.php';

$id = $_GET['id'];

$is_login = (
    isset($_SESSION['id_user']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'pelanggan'
);
$hasil = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id = '$id' ");
while ($data = mysqli_fetch_array($hasil)) {
  $nama = $data['nama'];
  $harga = $data['harga'];
  $deskripsi = $data['deskripsi'];
  $poto = $data['poto'];
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta
      name="author"
      content="Mark Otto, Jacob Thornton, and Bootstrap contributors" />
    <meta name="generator" content="Astro v5.13.2" />
    <title>GREENLEAF GARDEN</title>
    <link
      rel="canonical"
      href="https://getbootstrap.com/docs/5.3/examples/album/"/>
    <script src="assets/js/color-modes.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet" />
   <style>
  /* =========================
     GREENLEAF GARDEN - DETAIL
     ========================= */

  body {
    background: #f5faf7;
    color: #212529;
  }

  /* Navbar */
  .navbar-greenleaf {
    background: linear-gradient(135deg, #0f5132, #198754, #2aa66a) !important;
  }

  .navbar-greenleaf .navbar-brand {
    color: #ffffff !important;
    font-weight: 600;
  }

  .navbar-greenleaf .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.35);
  }

  /* Detail container */
  main {
    min-height: calc(100vh - 70px);
  }

  .detail-card {
    background: #ffffff;
    border: 1px solid #dcefe4 !important;
    box-shadow: 0 12px 35px rgba(25, 135, 84, 0.12) !important;
  }

  /* Foto produk */
  .product-image-wrapper {
    padding: 25px;
  }

  .product-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(25, 135, 84, 0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .product-image:hover {
    transform: scale(1.02);
    box-shadow: 0 12px 30px rgba(25, 135, 84, 0.18);
  }

  /* Nama produk */
  .product-title {
    color: #0f5132;
    font-size: 2rem;
    line-height: 1.25;
  }

  /* Harga */
  .product-price {
    color: #198754 !important;
    font-weight: 700;
    font-size: 1.7rem;
  }

  /* Deskripsi */
  .product-description {
    color: #66756d !important;
    line-height: 1.8;
  }

  /* Garis pemisah */
  .detail-divider {
    width: 60px;
    height: 4px;
    background: #198754;
    border-radius: 10px;
    margin-bottom: 20px;
  }

  /* Input jumlah */
  .quantity-input {
    width: 100px;
    border: 1px solid #b9ddc8;
    border-radius: 8px;
    padding: 9px 10px;
  }

  .quantity-input:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
  }

  /* Tombol keranjang */
  .btn-cart {
    background: #198754;
    border: 1px solid #198754;
    color: white;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.25s ease;
  }

  .btn-cart:hover {
    background: #157347;
    border-color: #157347;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(25, 135, 84, 0.2);
  }

  /* Theme toggle tetap kecil */
  .bd-mode-toggle {
    z-index: 1500;
  }

  .bd-mode-toggle .btn-bd-primary {
    --bs-btn-color: #ffffff;
    --bs-btn-bg: #198754;
    --bs-btn-border-color: #198754;
    --bs-btn-hover-color: #ffffff;
    --bs-btn-hover-bg: #157347;
    --bs-btn-hover-border-color: #157347;

    padding: 7px 9px !important;
    width: auto !important;
    height: auto !important;
    font-size: 14px;
    border-radius: 8px;
  }

  .bd-mode-toggle .bi {
    width: 1em;
    height: 1em;
  }

  /* Responsive */
  @media (max-width: 767px) {
    .product-image {
      height: 350px !important;
    }

    .product-title {
      font-size: 1.6rem;
    }

    .product-price {
      font-size: 1.4rem;
    }

    .product-image-wrapper {
      padding: 18px;
    }
  }
</style>
  </head>
  <body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="check2" viewBox="0 0 16 16">
        <path
          d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"
        ></path>
      </symbol>
      <symbol id="circle-half" viewBox="0 0 16 16">
        <path
          d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"
        ></path>
      </symbol>
      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path
          d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"
        ></path>
        <path
          d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"
        ></path>
      </symbol>
      <symbol id="sun-fill" viewBox="0 0 16 16">
        <path
          d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"
        ></path>
      </symbol>
    </svg>
    <div
      class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle"
    >
      <button
        class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
        id="bd-theme"
        type="button"
        aria-expanded="false"
        data-bs-toggle="dropdown"
        aria-label="Toggle theme (auto)"
      >
        <svg class="bi my-1 theme-icon-active" aria-hidden="true">
          <use href="#circle-half"></use>
        </svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>
      <ul
        class="dropdown-menu dropdown-menu-end shadow"
        aria-labelledby="bd-theme-text"
      >
        <li>
          <button
            type="button"
            class="dropdown-item d-flex align-items-center"
            data-bs-theme-value="light"
            aria-pressed="false"
          >
            <svg class="bi me-2 opacity-50" aria-hidden="true">
              <use href="#sun-fill"></use>
            </svg>
            Light
            <svg class="bi ms-auto d-none" aria-hidden="true">
              <use href="#check2"></use>
            </svg>
          </button>
        </li>
        <li>
          <button
            type="button"
            class="dropdown-item d-flex align-items-center"
            data-bs-theme-value="dark"
            aria-pressed="false"
          >
            <svg class="bi me-2 opacity-50" aria-hidden="true">
              <use href="#moon-stars-fill"></use>
            </svg>
            Dark
            <svg class="bi ms-auto d-none" aria-hidden="true">
              <use href="#check2"></use>
            </svg>
          </button>
        </li>
        <li>
          <button
            type="button"
            class="dropdown-item d-flex align-items-center active"
            data-bs-theme-value="auto"
            aria-pressed="true"
          >
            <svg class="bi me-2 opacity-50" aria-hidden="true">
              <use href="#circle-half"></use>
            </svg>
            Auto
            <svg class="bi ms-auto d-none" aria-hidden="true">
              <use href="#check2"></use>
            </svg>
          </button>
        </li>
      </ul>
    </div>
    <header data-bs-theme="dark">
      <div class="collapse text-bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4>About</h4>
              <p class="text-body-secondary">
                Add some information about the album below, the author, or any
                other background context. Make it a few sentences long so folks
                can pick up some informative tidbits. Then, link them off to
                some social networking sites or contact information.
              </p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4>Contact</h4>
              <ul class="list-unstyled">
                <li><a href="#" class="text-white">Follow on X</a></li>
                <li><a href="#" class="text-white">Like on Facebook</a></li>
                <li><a href="#" class="text-white">Email me</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="20"
              height="20"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              aria-hidden="true"
              class="me-2"
              viewBox="0 0 24 24"
            >
              <path
                d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"
              ></path>
              <circle cx="12" cy="13" r="4"></circle>
            </svg>
            <strong>Album</strong>
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarHeader"
            aria-controls="navbarHeader"
            aria-expanded="false"
            aria-label="Toggle navigation" >
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>
   <main>
    <div class="container py-5">
      <div class="card detail-card rounded-4 overflow-hidden">
        <div class="row g-0 align-items-center">
          <!-- FOTO PRODUK -->
          <div class="col-md-5">
           <div class="product-image-wrapper">
  <img src="img/<?= $poto ?>" alt="<?= $nama ?>" class="img-fluid product-image">
        </div>
          </div>
          <!-- DETAIL PRODUK -->
          <div class="col-md-7">
            <div class="p-4 p-md-5">
             <h1 class="product-title fw-bold mb-3">
             <?= $nama ?>
            </h1>
             <div class="detail-divider"></div>
             <h3 class="product-price mb-4">
              <p class="product-description fs-5 mb-4">
             <form method="post" action="keranjang.php?id=<?= $id ?>" onsubmit="return cekLogin(event, this)">
    <input type="hidden" name="hidden_foto" value="<?= $poto ?>">
    <input type="hidden" name="hidden_nama" value="<?= $nama ?>">
    <input type="hidden" name="hidden_harga" value="<?= $harga ?>">

    <input type="number"
           name="jumlah"
           value="1"
           min="1"
           class="form-control mb-3"
           style="width: 100px;">

    <input type="submit"
           name="add"
           class="btn btn-cart"
           value="Tambah ke Keranjang">
</form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
    <script
      src="../assets/dist/js/bootstrap.bundle.min.js"class="astro-vvvwv3sm"
    ></script>
  <script>
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
            cancelButtonText: 'Batal'
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