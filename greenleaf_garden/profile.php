<?php
session_start();
include 'koneksi.php';

$data = null;
$total_transaksi = 0;
$total_belanja = 0;

/* =========================
   CEK LOGIN PELANGGAN
========================= */
if (
    isset($_SESSION['id_user']) &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'pelanggan'
) {

    $id_user = (int) $_SESSION['id_user'];

    /* =========================
       AMBIL DATA USER
    ========================= */
    $query_user = mysqli_query(
        $koneksi,
        "SELECT id, nama, email, username, hp, alamat, role
         FROM tb_user
         WHERE id='$id_user' AND role='pelanggan'"
    );

    $data = mysqli_fetch_assoc($query_user);

    /* =========================
       JIKA DATA USER TIDAK ADA
    ========================= */
    if (!$data) {

        session_unset();
        session_destroy();

        $data = null;

    } else {

        /* =========================
           TOTAL TRANSAKSI
        ========================= */
        $query_jumlah = mysqli_query(
            $koneksi,
            "SELECT COUNT(*) AS jumlah
             FROM tb_transaksi
             WHERE id_pelanggan='$id_user'"
        );

        $jumlah_transaksi = mysqli_fetch_assoc($query_jumlah);

        $total_transaksi = $jumlah_transaksi['jumlah'] ?? 0;


        /* =========================
           TOTAL BELANJA
        ========================= */
        $query_total = mysqli_query(
            $koneksi,
            "SELECT SUM(total_harga) AS total
             FROM tb_transaksi
             WHERE id_pelanggan='$id_user'"
        );

        $total_belanja_data = mysqli_fetch_assoc($query_total);

        $total_belanja = $total_belanja_data['total'] ?? 0;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile - GreenLeaf Garden</title>

    <link rel="stylesheet"
          href="assets/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(25,135,84,.15), transparent 35%),
                radial-gradient(circle at bottom right, rgba(32,201,151,.12), transparent 35%),
                #f4faf7;
            min-height: 100vh;
            color: #26352d;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar-green {
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 20px rgba(0,0,0,.06);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            color: #198754 !important;
            font-size: 22px;
        }

        .navbar-brand img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            margin-right: 8px;
        }

        .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #198754;
            background: #edf8f2;
            transition: .3s;
            font-size: 20px;
        }

        .nav-icon:hover {
            background: #198754;
            color: white;
            transform: translateY(-2px);
        }


        /* =========================
           MAIN
        ========================= */

        .profile-wrapper {
            max-width: 1000px;
            margin: 55px auto;
            padding: 0 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title small {
            color: #198754;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .page-title h1 {
            font-size: 38px;
            font-weight: 800;
            margin-top: 7px;
        }


        /* =========================
           PROFILE CARD
        ========================= */

        .profile-card {
            background: rgba(255,255,255,.92);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(25,135,84,.12);
            border: 1px solid rgba(25,135,84,.08);
        }


        /* =========================
           PROFILE HEADER
        ========================= */

        .profile-header {
            position: relative;
            padding: 45px 35px;
            color: white;
            background:
                linear-gradient(
                    135deg,
                    #198754,
                    #20a464,
                    #157347
                );
            overflow: hidden;
        }

        .profile-header::before {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
            right: -70px;
            top: -100px;
        }

        .profile-header::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
            left: -70px;
            bottom: -100px;
        }

        .profile-header-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .avatar {
            width: 105px;
            height: 105px;
            border-radius: 50%;
            background: rgba(255,255,255,.18);
            border: 4px solid rgba(255,255,255,.75);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 46px;
            flex-shrink: 0;
        }

        .profile-header h2 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
        }

        .profile-header p {
            margin: 5px 0 0;
            opacity: .9;
        }


        /* =========================
           INFO
        ========================= */

        .profile-content {
            padding: 35px;
        }

        .section-title {
            font-weight: 800;
            font-size: 21px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 17px;
            border-radius: 15px;
            background: #f7fbf9;
            margin-bottom: 12px;
            transition: .3s;
            border: 1px solid transparent;
        }

        .info-item:hover {
            transform: translateX(5px);
            border-color: rgba(25,135,84,.15);
            background: #f1faf5;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e5f6ed;
            color: #198754;
            font-size: 20px;
            flex-shrink: 0;
        }

        .info-label {
            font-size: 13px;
            color: #777;
            margin-bottom: 2px;
        }

        .info-value {
            font-weight: 700;
            color: #27352e;
            word-break: break-word;
        }


        /* =========================
           STATISTIC
        ========================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-top: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f0faf5, #ffffff);
            border-radius: 18px;
            padding: 25px;
            text-align: center;
            border: 1px solid rgba(25,135,84,.08);
            transition: .3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(25,135,84,.10);
        }

        .stat-icon {
            font-size: 27px;
            color: #198754;
            margin-bottom: 7px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #198754;
        }

        .stat-label {
            color: #777;
            font-size: 14px;
        }


        /* =========================
           BUTTON
        ========================= */

        .profile-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn-green {
            background: #198754;
            color: white;
            border: none;
            border-radius: 13px;
            padding: 13px 20px;
            font-weight: 700;
            text-decoration: none;
            transition: .3s;
        }

        .btn-green:hover {
            background: #157347;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25,135,84,.20);
        }

        .btn-outline-green {
            border: 2px solid #198754;
            color: #198754;
            background: white;
            border-radius: 13px;
            padding: 11px 20px;
            font-weight: 700;
            text-decoration: none;
            transition: .3s;
        }

        .btn-outline-green:hover {
            background: #198754;
            color: white;
        }

        .btn-danger-custom {
            background: #fff1f1;
            color: #dc3545;
            border: 1px solid #ffd4d4;
            border-radius: 13px;
            padding: 11px 20px;
            font-weight: 700;
            text-decoration: none;
            transition: .3s;
        }

        .btn-danger-custom:hover {
            background: #dc3545;
            color: white;
        }


        /* =========================
           GUEST
        ========================= */

        .guest-area {
            text-align: center;
            padding: 70px 30px;
        }

        .guest-icon {
            width: 110px;
            height: 110px;
            margin: auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8f7ef;
            color: #198754;
            font-size: 55px;
            margin-bottom: 25px;
        }

        .guest-area h2 {
            font-weight: 800;
            margin-bottom: 10px;
        }

        .guest-area p {
            color: #777;
            margin-bottom: 28px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .profile-wrapper {
                margin: 30px auto;
            }

            .profile-header-content {
                flex-direction: column;
                text-align: center;
            }

            .profile-header h2 {
                font-size: 25px;
            }

            .profile-content {
                padding: 25px 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                flex-direction: column;
            }

            .profile-actions a {
                text-align: center;
            }

        }
        /* =========================
   NAVBAR
========================= */

.navbar-green {
    background: rgba(255,255,255,.94);
    backdrop-filter: blur(15px);
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    border-bottom: 1px solid rgba(25,135,84,.08);
    padding: 10px 0;
}

.navbar-brand {
    font-weight: 800;
    color: #198754 !important;
    font-size: 21px;
    letter-spacing: .3px;
    text-decoration: none;
}

.navbar-brand img {
    width: 42px;
    height: 42px;
    object-fit: contain;
    margin-right: 9px;
}

.navbar-nav {
    gap: 8px;
}

.navbar-nav .nav-link {
    color: #53655c;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 15px !important;
    border-radius: 10px;
    transition: .25s;
}

.navbar-nav .nav-link i {
    margin-right: 5px;
}

.navbar-nav .nav-link:hover {
    color: #198754;
    background: #edf8f2;
}

.nav-icon {
    width: 43px;
    height: 43px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: #198754;
    background: #edf8f2;
    transition: .3s;
    font-size: 19px;
}

.nav-icon:hover {
    background: #198754;
    color: white;
    transform: translateY(-2px);
}

.nav-icon.active {
    background: #198754;
    color: white;
}

.navbar-toggler {
    border: 1px solid #cfe5d8;
    border-radius: 9px;
}

.navbar-toggler:focus {
    box-shadow: 0 0 0 3px rgba(25,135,84,.12);
}


/* =========================
   MOBILE NAVBAR
========================= */

@media (max-width: 991px) {

    .navbar-collapse {
        margin-top: 15px;
        padding: 12px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(25,135,84,.08);
    }

    .navbar-nav {
        gap: 3px;
    }

    .navbar-nav .nav-link {
        padding: 11px 13px !important;
    }

    .navbar-collapse .d-flex {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #edf3ef;
    }

}

    </style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar navbar-expand-lg navbar-green sticky-top">

    <div class="container">

        <!-- LOGO -->
        <a href="index.php" class="navbar-brand d-flex align-items-center">

          

            <span>
                GREENLEAF GARDEN
            </span>

        </a>


        <!-- TOGGLE MOBILE -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarGreenLeaf"
            aria-controls="navbarGreenLeaf"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- MENU -->
        <div
            class="collapse navbar-collapse"
            id="navbarGreenLeaf"
        >

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <!-- BERANDA -->
                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php"
                    >

                        <i class="bi bi-house-door"></i>

                        Beranda

                    </a>

                </li>


                <!-- PRODUK -->
                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="index.php#produk"
                    >

                        <i class="bi bi-grid"></i>

                        Produk

                    </a>

                </li>

            </ul>


            <!-- ICON KANAN -->
            <div class="d-flex align-items-center gap-2">

                <!-- KERANJANG -->
                <a
                    href="keranjang.php"
                    class="nav-icon"
                    title="Keranjang"
                >

                    <i class="bi bi-cart3"></i>

                </a>


                <!-- PROFILE -->
                <a
                    href="profile.php"
                    class="nav-icon active"
                    title="Profile"
                >

                    <i class="bi bi-person-fill"></i>

                </a>

            </div>

        </div>

    </div>

</nav>



<!-- =========================
     PROFILE
========================= -->

<div class="profile-wrapper">


    <div class="page-title">

        <small>GreenLeaf Garden</small>

        <h1>Profile Saya</h1>

    </div>



    <div class="profile-card">


        <?php if ($data) { ?>


        <!-- =========================
             USER SUDAH LOGIN
        ========================= -->

        <div class="profile-header">

            <div class="profile-header-content">

                <div class="avatar">

                    <i class="bi bi-person-fill"></i>

                </div>


                <div>

                    <h2>
                        <?= htmlspecialchars($data['nama']) ?>
                    </h2>

                    <p>

                        <i class="bi bi-person-check-fill"></i>

                        Pelanggan GreenLeaf Garden

                    </p>

                </div>

            </div>

        </div>



        <div class="profile-content">


            <div class="section-title">

                <i class="bi bi-person-lines-fill text-success"></i>

                Informasi Akun

            </div>



            <div class="info-item">

                <div class="info-icon">

                    <i class="bi bi-person"></i>

                </div>

                <div>

                    <div class="info-label">Nama Lengkap</div>

                    <div class="info-value">

                        <?= htmlspecialchars($data['nama']) ?>

                    </div>

                </div>

            </div>



            <div class="info-item">

                <div class="info-icon">

                    <i class="bi bi-envelope"></i>

                </div>

                <div>

                    <div class="info-label">Email</div>

                    <div class="info-value">

                        <?= htmlspecialchars($data['email']) ?>

                    </div>

                </div>

            </div>



            <div class="info-item">

                <div class="info-icon">

                    <i class="bi bi-at"></i>

                </div>

                <div>

                    <div class="info-label">Username</div>

                    <div class="info-value">

                        <?= htmlspecialchars($data['username']) ?>

                    </div>

                </div>

            </div>



            <div class="info-item">

                <div class="info-icon">

                    <i class="bi bi-phone"></i>

                </div>

                <div>

                    <div class="info-label">No. HP</div>

                    <div class="info-value">

                        <?= htmlspecialchars($data['hp'] ?: '-') ?>

                    </div>

                </div>

            </div>



            <div class="info-item">

                <div class="info-icon">

                    <i class="bi bi-geo-alt"></i>

                </div>

                <div>

                    <div class="info-label">Alamat</div>

                    <div class="info-value">

                        <?= htmlspecialchars($data['alamat'] ?: '-') ?>

                    </div>

                </div>

            </div>



            <!-- =========================
                 STATISTIK
            ========================= -->

            <div class="stats">

                <div class="stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-receipt"></i>

                    </div>

                    <div class="stat-number">

                        <?= $total_transaksi ?>

                    </div>

                    <div class="stat-label">

                        Total Transaksi

                    </div>

                </div>



                <div class="stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-wallet2"></i>

                    </div>

                    <div class="stat-number">

                        Rp <?= number_format($total_belanja, 0, ',', '.') ?>

                    </div>

                    <div class="stat-label">

                        Total Belanja

                    </div>

                </div>

            </div>



            <!-- =========================
                 ACTION
            ========================= -->

            <div class="profile-actions">

                <a href="edit_profile.php"
                   class="btn-green">

                    <i class="bi bi-pencil-square"></i>

                    Edit Profile

                </a>


                <a href="riwayat_transaksi.php"
                   class="btn-outline-green">

                    <i class="bi bi-clock-history"></i>

                    Riwayat Transaksi

                </a>


                <a href="index.php"
                   class="btn-outline-green">

                    <i class="bi bi-house"></i>

                    Kembali

                </a>


                <a href="logout.php"
                   class="btn-danger-custom"
                   onclick="return konfirmasiLogout(event, this.href)">

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </a>

            </div>


        </div>



        <?php } else { ?>


        <!-- =========================
             GUEST
        ========================= -->

        <div class="guest-area">

            <div class="guest-icon">

                <i class="bi bi-person"></i>

            </div>


            <h2>

                Hai, Guest!

            </h2>


            <p>

                Anda belum login ke akun GreenLeaf Garden.<br>

                Login untuk melihat profil dan riwayat transaksi Anda.

            </p>


            <div class="d-flex justify-content-center gap-2 flex-wrap">


                <a href="login.php"
                   class="btn-green">

                    <i class="bi bi-box-arrow-in-right"></i>

                    Login Sekarang

                </a>


                <a href="index.php"
                   class="btn-outline-green">

                    <i class="bi bi-house"></i>

                    Kembali ke Beranda

                </a>


            </div>

        </div>


        <?php } ?>


    </div>

</div>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script>

function konfirmasiLogout(event, url) {

    event.preventDefault();

    Swal.fire({

        title: 'Logout?',
        text: 'Anda akan keluar dari akun pelanggan.',
        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, Logout',
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