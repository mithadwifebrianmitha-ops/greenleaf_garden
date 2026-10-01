<?php

include 'koneksi.php';

if (isset($_POST['register'])) {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hp = $_POST['hp'];
    $alamat = $_POST['alamat'];

    $register = mysqli_query(
        $koneksi,
        "INSERT INTO tb_user (nama, email, username, password, hp, alamat, role)
        VALUES ('$nama', '$email', '$username', '$password', '$hp', '$alamat', 'pelanggan')"
    );

    if ($register) {

        header('Location: login.php?status=register');
        exit;

    } else {

        header('Location: register.php?status=gagal');
        exit;

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

    <title>Register - GreenLeaf Garden</title>

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

    /* =========================================================
       GREENLEAF GARDEN - REGISTER
    ========================================================= */

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', Arial, sans-serif;
    }

    body {
        overflow-x: hidden;
    }


    /* =========================================================
       BACKGROUND
    ========================================================= */

    .login-bg {
        min-height: 100vh;

        position: relative;

        overflow: hidden;

        background:
            linear-gradient(
                135deg,
                #e8f5ec 0%,
                #f5fbf6 48%,
                #e1f3e7 100%
            );
    }


    .login-bg::before {
        content: "";

        position: absolute;

        width: 500px;
        height: 500px;

        top: -250px;
        left: -180px;

        background: rgba(44, 150, 88, 0.12);

        border-radius: 50%;

        filter: blur(90px);

        pointer-events: none;
    }


    .login-bg::after {
        content: "";

        position: absolute;

        width: 430px;
        height: 430px;

        right: -200px;
        bottom: -200px;

        background: rgba(106, 193, 137, 0.13);

        border-radius: 50%;

        filter: blur(90px);

        pointer-events: none;
    }


    /* =========================================================
       MAIN LAYOUT
       DESKTOP = 55% KIRI + 45% KANAN
    ========================================================= */

    .register-wrapper {
        width: 100%;

        min-height: 100vh;

        display: flex;

        position: relative;

        z-index: 2;
    }


    /* =========================================================
       LEFT SIDE
    ========================================================= */

    .register-illustration {
        width: 55%;

        min-height: 100vh;

        position: relative;

        overflow: hidden;

        display: flex;

        flex-direction: column;

        padding: 42px 6vw 45px;
       
        background: 
  linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7)),
  url('img/sombras 2.jpg') no-repeat center center / cover;
}
     
    


    .register-illustration::before {
        content: "";

        position: absolute;

        width: 550px;
        height: 550px;

        right: -250px;
        top: 100px;

        background: rgba(47, 150, 87, 0.09);

        border-radius: 50%;

        filter: blur(70px);

        pointer-events: none;
    }


    /* =========================================================
       HEADER KIRI
    ========================================================= */

    .left-header {
        display: flex;

        align-items: center;

        gap: 14px;

        position: relative;

        z-index: 3;

        margin-bottom: 40px;
    }


    .left-logo-wrap {
        display: flex;

        align-items: center;

        gap: 10px;

        text-decoration: none;
    }


    .left-logo-wrap img {
        width: 38px;
        height: 38px;

        object-fit: contain;

        border-radius: 9px;
    }


    .left-logo-wrap span {
        font-size: 16px;

        font-weight: 700;

        color: #1a5c32;
    }


    .left-header-divider {
        width: 1px;
        height: 25px;

        background: rgba(25, 135, 84, 0.25);
    }


    .left-tagline {
        font-size: 11px;

        color: #4a7a5a;

        line-height: 1.4;
    }


    /* =========================================================
       CONTENT KIRI
    ========================================================= */

    .left-content {
        flex: 1;

        display: flex;

        flex-direction: column;

        justify-content: center;

        position: relative;

        z-index: 3;

        padding-bottom: 30px;
    }


    .left-badge {
        display: inline-flex;

        align-items: center;

        width: fit-content;

        gap: 7px;

        padding: 8px 15px;

        margin-bottom: 20px;

        border-radius: 30px;

        background: rgba(255, 255, 255, 0.75);

        border: 1px solid rgba(25, 135, 84, 0.15);

        box-shadow:
            0 5px 16px rgba(25, 135, 84, 0.06);

        color: #1e6b3a;

        font-size: 11px;

        font-weight: 700;

        letter-spacing: 1.2px;

        text-transform: uppercase;
    }


    /* =========================================================
       JUDUL KIRI
    ========================================================= */

    .left-title {
        margin: 0;

        max-width: 700px;

        font-size: clamp(40px, 4vw, 62px);

        line-height: 1.04;

        letter-spacing: -2.5px;

        font-weight: 800;

        color: #102d1c;
    }


    .left-title .green {
        display: block;

        color: #1e8c4a;
    }


    /* =========================================================
       DESKRIPSI
    ========================================================= */

    .left-desc {
        max-width: 470px;

        margin: 20px 0 0;

        font-size: 15px;

        line-height: 1.7;

        color: #4d6b57;
    }


    /* =========================================================
       KANAN / REGISTER AREA
    ========================================================= */

    .register-area {
        width: 45%;

        min-height: 100vh;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 45px 5vw;

        background: rgba(255, 255, 255, 0.92);

        box-shadow:
            -12px 0 40px rgba(0, 0, 0, 0.045);

        position: relative;

        z-index: 5;

        overflow-y: auto;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .form-register {
        width: 100%;

        max-width: 400px;

        position: relative;
    }


    /* =========================================================
       LOGO
    ========================================================= */

    .card-logo {
        display: flex;

        align-items: center;

        gap: 10px;

        margin-bottom: 18px;
    }


    .card-logo img {
        width: 68px;
        height: 68px;

        object-fit: contain;

        border-radius: 9px;
    }


    .card-logo-text {
        font-size: 17px;

        font-weight: 700;

        color: #1a5c32;
    }


    .card-logo-text span {
        font-weight: 400;

        color: #333;
    }


    /* =========================================================
       HEADING
    ========================================================= */

    .card-heading {
        margin-bottom: 5px;

        color: #1e8c4a;

        font-size: 22px;

        font-weight: 700;
    }


    .card-subheading {
        margin-bottom: 22px;

        color: #7a8a80;

        font-size: 12.5px;

        line-height: 1.6;
    }


    /* =========================================================
       INPUT
    ========================================================= */

    .input-wrap {
        position: relative;

        margin-bottom: 13px;
    }


    .form-floating > .form-control {
        height: 50px;

        min-height: 50px;

        padding: 13px 15px;

        background: #f8fbf9;

        border: 1.5px solid #e2ebe5;

        border-radius: 13px;

        color: #222;

        font-size: 13.5px;

        font-family: inherit;

        box-shadow: none;

        transition: all 0.22s ease;
    }


    .form-floating > .form-control::placeholder {
        color: transparent;
    }


    .form-floating > .form-control:hover {
        background: #ffffff;

        border-color: #b8d4c2;
    }


    .form-floating > .form-control:focus {
        background: #ffffff;

        border-color: #198754;

        box-shadow:
            0 0 0 4px rgba(25, 135, 84, 0.07);
    }


    .form-floating > label {
        padding: 13px 15px;

        color: #89958e;

        font-size: 13px;
    }


    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #198754;
    }


    /* =========================================================
       ALAMAT
    ========================================================= */

    .form-floating textarea.form-control {
        height: 75px;

        min-height: 75px;

        resize: none;

        padding-top: 16px;
    }


    /* =========================================================
       BUTTON
    ========================================================= */

    .btn-primary {
        width: 100%;

        height: 50px;

        display: flex;

        align-items: center;

        justify-content: center;

        border: none;

        border-radius: 13px;

        background:
            linear-gradient(
                135deg,
                #1e8c4a,
                #157347
            );

        color: #ffffff;

        font-family: inherit;

        font-size: 14px;

        font-weight: 700;

        box-shadow:
            0 8px 20px rgba(25, 135, 84, 0.20);

        transition: all 0.25s ease;
    }


    .btn-primary:hover {
        background:
            linear-gradient(
                135deg,
                #157347,
                #0f5132
            );

        transform: translateY(-2px);

        box-shadow:
            0 11px 24px rgba(25, 135, 84, 0.26);
    }


    .btn-primary:active {
        transform: translateY(0);
    }


    /* =========================================================
       LOGIN LINK
    ========================================================= */

    .register-text {
        margin-top: 18px;

        text-align: center;

        color: #7a867f;

        font-size: 12.5px;
    }


    .register-text a {
        color: #198754;

        text-decoration: none;

        font-weight: 700;
    }


    .register-text a:hover {
        color: #0f5132;
    }


    /* =========================================================
       COPYRIGHT
    ========================================================= */

    .copyright {
        margin-top: 22px;

        text-align: center;

        color: #a0aaa4;

        font-size: 11px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .register-illustration {
            padding-left: 45px;

            padding-right: 45px;
        }


        .register-area {
            padding-left: 35px;

            padding-right: 35px;
        }


        .left-title {
            font-size: 48px;
        }

    }


    @media (max-width: 850px) {

        .register-wrapper {
            flex-direction: column;
        }


        .register-illustration {
            width: 100%;

            min-height: 430px;

            padding: 32px 30px;
        }


        .register-area {
            width: 100%;

            min-height: auto;

            padding: 45px 30px 50px;

            box-shadow:
                0 -8px 30px rgba(0, 0, 0, 0.04);
        }


        .form-register {
            max-width: 400px;
        }


        .left-title {
            font-size: 42px;
        }

    }


    @media (max-width: 500px) {

        .register-illustration {
            min-height: 360px;

            padding: 25px 20px;
        }


        .left-header {
            margin-bottom: 30px;
        }


        .left-logo-wrap img {
            width: 35px;

            height: 35px;
        }


        .left-logo-wrap span {
            font-size: 14px;
        }


        .left-header-divider,
        .left-tagline {
            display: none;
        }


        .left-badge {
            font-size: 9px;

            padding: 7px 11px;

            letter-spacing: 0.8px;
        }


        .left-title {
            font-size: 32px;

            letter-spacing: -1.5px;
        }


        .left-desc {
            font-size: 13px;

            line-height: 1.6;
        }


        .register-area {
            padding: 35px 20px 45px;
        }

    }

    </style>

</head>


<body class="login-bg">


<main class="register-wrapper">


    <!-- =====================================================
         KIRI
    ====================================================== -->

    <section class="register-illustration">


        <div class="left-header">


            <a
                href="index.php"
                class="left-logo-wrap"
            >

                <img
                    src="logotoko.png"
                    alt="GreenLeaf Garden"
                >

                <span>
                    GreenLeaf Garden
                </span>

            </a>


            <div class="left-header-divider"></div>


            <div class="left-tagline">

                Tanam Hari Ini,<br>
                Hidup Lebih Baik Nanti

            </div>


        </div>


        <div class="left-content">


            <div class="left-badge">

                🌿 Plant Make Life Better

            </div>


            <h1 class="left-title">

                Mulai

                <span class="green">
                    Perjalanan
                </span>

                Hijau Bersama Kami

            </h1>


            <p class="left-desc">

                Buat akun GreenLeaf Garden dan temukan
                berbagai tanaman hias, pot artisan, serta
                perlengkapan berkebun untuk membuat
                rumah menjadi lebih hidup.

            </p>


        </div>


    </section>


    <!-- =====================================================
         KANAN
    ====================================================== -->

    <section class="register-area">


        <div class="form-register">


            <!-- LOGO -->

            <div class="card-logo">

                <img
                    src="logotoko.png"
                    alt="GreenLeaf Garden"
                >


                <div class="card-logo-text">

                    GreenLeaf
                    <span>Garden</span>

                </div>

            </div>


            <!-- HEADING -->

            <div class="card-heading">

                Buat akun baru

            </div>


            <div class="card-subheading">

                Daftarkan diri Anda untuk mulai
                berbelanja dan menikmati layanan
                GreenLeaf Garden.

            </div>


            <!-- FORM -->

            <form method="post">


                <!-- NAMA -->

                <div class="input-wrap">

                    <div class="form-floating">

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            id="floatingNama"
                            placeholder="Nama Lengkap"
                            required
                        >

                        <label for="floatingNama">

                            Nama Lengkap

                        </label>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="input-wrap">

                    <div class="form-floating">

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            id="floatingEmail"
                            placeholder="Email"
                            required
                        >

                        <label for="floatingEmail">

                            Email

                        </label>

                    </div>

                </div>


                <!-- USERNAME -->

                <div class="input-wrap">

                    <div class="form-floating">

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            id="floatingUsername"
                            placeholder="Username"
                            required
                        >

                        <label for="floatingUsername">

                            Username

                        </label>

                    </div>

                </div>


                <!-- NO HP -->

                <div class="input-wrap">

                    <div class="form-floating">

                        <input
                            type="text"
                            name="hp"
                            class="form-control"
                            id="floatingHp"
                            placeholder="No. HP"
                            required
                        >

                        <label for="floatingHp">

                            No. HP

                        </label>

                    </div>

                </div>


                <!-- ALAMAT -->

                <div class="input-wrap">

                    <div class="form-floating">

                        <textarea
                            name="alamat"
                            class="form-control"
                            id="floatingAlamat"
                            placeholder="Alamat"
                            required
                        ></textarea>

                        <label for="floatingAlamat">

                            Alamat

                        </label>

                    </div>

                </div>


                <!-- PASSWORD -->

                <div class="input-wrap">

                    <div class="form-floating">

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            id="floatingPassword"
                            placeholder="Password"
                            required
                        >

                        <label for="floatingPassword">

                            Password

                        </label>

                    </div>

                </div>


                <!-- BUTTON -->

                <button
                    class="btn btn-primary"
                    type="submit"
                    name="register"
                >

                    Daftar Sekarang →

                </button>


                <!-- LOGIN -->

                <div class="register-text">

                    Sudah punya akun?

                    <a href="login.php">

                        Masuk sekarang

                    </a>

                </div>


                <!-- COPYRIGHT -->

                <div class="copyright">

                    &copy; 2025-2026 GreenLeaf Garden

                </div>


            </form>


        </div>


    </section>


</main>


<!-- BOOTSTRAP -->

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>


<!-- SWEETALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php if (isset($_GET['status']) && $_GET['status'] == 'gagal') { ?>

<script>

Swal.fire({

    icon: 'error',

    title: 'Registrasi Gagal',

    text: 'Data tidak berhasil didaftarkan.',

    confirmButtonColor: '#198754',

    confirmButtonText: 'Coba Lagi'

});

</script>

<?php } ?>


</body>

</html>