<?php

session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $login = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_user WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($login) > 0) {

        $data = mysqli_fetch_array($login);

      $_SESSION['id'] = $data['id'];
$_SESSION['id_user'] = $data['id'];
$_SESSION['username'] = $data['username'];
$_SESSION['role'] = $data['role'];
$_SESSION['nama'] = !empty($data['nama']) ? $data['nama'] : $data['username'];
        if ($data['role'] == 'admin') {

            $_SESSION['admin'] = $data['username'];

            header("Location: dashboard.php?status=login");
            exit;

        } elseif ($data['role'] == 'pelanggan') {

            header("Location: index.php?status=login");
            exit;
        }

    } else {

        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Username atau Password salah.',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'Coba Lagi'
                });
            });
        </script>";
    }
}

?>

<!doctype html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - GreenLeaf Garden</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

    /* =========================================================
       GREENLEAF GARDEN - LOGIN
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

    .login-wrapper {
        width: 100%;
        min-height: 100vh;

        display: flex;

        position: relative;

        z-index: 2;
    }


    /* =========================================================
       LEFT SIDE
    ========================================================= */

    .login-illustration {
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
     
    

    .login-illustration::before {
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
        width: 68px;
        height: 68px;

        object-fit: contain;

        border-radius: 9px;
    }

    .left-logo-wrap span {
        font-size: 16px;

        font-weight: 700;

        color: #6da169;
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
       JUDUL
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
       KANAN / LOGIN AREA
    ========================================================= */

    .login-area {
        width: 45%;

        min-height: 100vh;

        display: flex;

        align-items: center;

        justify-content: center;

        padding: 40px 5vw;

        background: rgba(255, 255, 255, 0.92);

        box-shadow:
            -12px 0 40px rgba(0, 0, 0, 0.045);

        position: relative;

        z-index: 5;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .form-signin {
        width: 100%;

        max-width: 420px;

        position: relative;
    }


    /* =========================================================
       LOGO
    ========================================================= */

    .card-logo {
    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 24px;
}

.card-logo img {
    width: 48px;
    height: 48px;

    object-fit: contain;

    border-radius: 10px;
}

.card-logo-text {
    font-size: 20px;

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

    .input-icon-left {
        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #9eafa5;

        display: flex;

        align-items: center;

        z-index: 5;

        pointer-events: none;
    }

    .input-icon-left svg {
        width: 17px;
        height: 17px;
    }

    .form-floating > .form-control {
        height: 50px;

        min-height: 50px;

        padding: 13px 42px;

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
        padding: 13px 13px 13px 42px;

        color: #89958e;

        font-size: 13px;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #198754;
    }



    /* =========================================================
       REMEMBER
    ========================================================= */

    .login-options {
        display: flex;

        align-items: center;

        justify-content: space-between;

        margin: 4px 0 18px;
    }

    .form-check {
        padding-left: 1.6em;
    }

    .form-check-input {
        width: 15px;
        height: 15px;

        margin-left: -1.6em;

        margin-top: 0.18em;

        border: 1.5px solid #cbd8cf;

        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #198754;

        border-color: #198754;
    }

    .form-check-label {
        color: #4d5e54;

        font-size: 12.5px;

        font-weight: 500;

        cursor: pointer;
    }

    .forgot-link {
        color: #198754;

        font-size: 12.5px;

        font-weight: 600;

        text-decoration: none;
    }

    .forgot-link:hover {
        color: #0f5132;
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


    /* =========================================================
       REGISTER
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
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .login-illustration {
            padding-left: 45px;
            padding-right: 45px;
        }

        .login-area {
            padding-left: 35px;
            padding-right: 35px;
        }

        .left-title {
            font-size: 48px;
        }
    }


    @media (max-width: 850px) {

        .login-wrapper {
            flex-direction: column;
        }

        .login-illustration {
            width: 100%;
            min-height: 430px;

            padding: 32px 30px;
        }

        .login-area {
            width: 100%;
            min-height: auto;

            padding: 45px 30px 50px;

            box-shadow:
                0 -8px 30px rgba(0, 0, 0, 0.04);
        }

        .form-signin {
            max-width: 400px;
        }

        .left-title {
            font-size: 42px;
        }
    }


    @media (max-width: 500px) {

        .login-illustration {
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

        .login-area {
            padding: 35px 20px 45px;
        }
    }

    </style>

</head>


<body class="login-bg">

<main class="login-wrapper">


    <!-- =====================================================
         KIRI
    ====================================================== -->

    <section class="login-illustration">

        <div class="left-header">

            <a href="index.php" class="left-logo-wrap">

              
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

                Tumbuhkan

                <span class="green">
                    Kebahagiaan
                </span>

                di Setiap Sudut Rumahmu

            </h1>

            <p class="left-desc">

                Kurasi tanaman hias terbaik, pot artisan,
                dan perlengkapan berkebun untuk rumah
                yang lebih hidup dan bernapas.

            </p>

        </div>

    </section>


    <!-- =====================================================
         KANAN
    ====================================================== -->

    <section class="login-area">

        <div class="form-signin">


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
                Selamat datang kembali!
            </div>

            <div class="card-subheading">

                Masuk ke akun Anda untuk melanjutkan
                perjalanan berkebun bersama kami.

            </div>


            <!-- FORM -->

            <form method="post">


                <!-- USERNAME -->

                <div class="input-wrap">

                    <span class="input-icon-left">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                            />

                            <circle
                                cx="12"
                                cy="7"
                                r="4"
                            />

                        </svg>

                    </span>


                    <div class="form-floating">

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            id="floatingInput"
                            placeholder="Username"
                            required
                        >

                        <label for="floatingInput">
                            Username
                        </label>

                    </div>

                </div>


                <!-- PASSWORD -->

                <div class="input-wrap">

                    <span class="input-icon-left">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <rect
                                x="3"
                                y="11"
                                width="18"
                                height="11"
                                rx="2"
                            />

                            <path
                                d="M7 11V7a5 5 0 0 1 10 0v4"
                            />

                        </svg>

                    </span>


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


                    <!-- EYE -->

                    <button
                        type="button"
                        class="input-eye"
                        onclick="togglePassword()"
                    >

                        <svg
                            id="eyeIcon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                            />

                            <circle
                                cx="12"
                                cy="12"
                                r="3"
                            />

                        </svg>

                    </button>

                </div>


                <!-- REMEMBER -->

                <div class="login-options">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="checkDefault"
                        >

                        <label
                            class="form-check-label"
                            for="checkDefault"
                        >
                            Ingat saya
                        </label>

                    </div>


                    <a
                        href="#"
                        class="forgot-link"
                    >
                        Lupa password?
                    </a>

                </div>


                <!-- BUTTON -->

                <button
                    class="btn btn-primary"
                    type="submit"
                    name="login"
                >
                    Masuk ke Akun →
                </button>


                <!-- REGISTER -->

                <div class="register-text">

                    Belum punya akun?

                    <a href="register.php">
                        Daftar sekarang
                    </a>

                </div>


            </form>

        </div>

    </section>

</main>


<!-- SWEETALERT SATU KALI SAJA -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

function togglePassword() {

    const input =
        document.getElementById('floatingPassword');

    const icon =
        document.getElementById('eyeIcon');


    if (input.type === 'password') {

        input.type = 'text';

        icon.innerHTML = `
            <path
                d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"
            />

            <path
                d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"
            />

            <line
                x1="1"
                y1="1"
                x2="23"
                y2="23"
            />
        `;

    } else {

        input.type = 'password';

        icon.innerHTML = `
            <path
                d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
            />

            <circle
                cx="12"
                cy="12"
                r="3"
            />
        `;

    }

}

</script>


<?php if (isset($_GET['status']) && $_GET['status'] == 'register') { ?>

<script>

Swal.fire({
    icon: 'success',
    title: 'Registrasi Berhasil!',
    text: 'Akun berhasil dibuat. Silakan login.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'Login'
});

</script>

<?php } ?>


<?php if (isset($_GET['status']) && $_GET['status'] == 'logout') { ?>

<script>

Swal.fire({
    icon: 'success',
    title: 'Logout Berhasil!',
    text: 'Anda telah keluar dari akun.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});

</script>

<?php } ?>


</body>

</html>