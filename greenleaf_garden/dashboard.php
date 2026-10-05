<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

if (
    !isset($_SESSION['username']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: login.php");
    exit;
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : "";

/* =========================
   DATA EDIT
========================= */

if ($aksi == "edit") {

    $id = $_GET['id'];

    $hasil = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk p
        INNER JOIN tb_kategori k
        ON p.id_kategori = k.id_kategori
        WHERE p.id = '$id'"
    );

    while ($data = mysqli_fetch_array($hasil)) {
        $nama = $data['nama'];
        $harga = $data['harga'];
        $stok = $data['stok'];
        $id_kategori = $data['id_kategori'];
        $poto = $data['poto'];
        $deskripsi = $data['deskripsi'];
    }
}

/* =========================
   PROSES TAMBAH / EDIT
========================= */

if (isset($_POST['tambah'])) {

    $nama_produk = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['id_kategori'];
    $deskripsi = $_POST['deskripsi'];

    /* =========================
       PROSES EDIT
    ========================= */

    if ($aksi == 'edit') {

        $id = isset($_POST['id_produk'])
            ? $_POST['id_produk']
            : $_GET['id'];

        /* =========================
           CEK GAMBAR
        ========================= */

        if (
            isset($_FILES['poto']) &&
            $_FILES['poto']['error'] == 0 &&
            !empty($_FILES['poto']['name'])
        ) {

            $poto = $_FILES['poto']['name'];
            $file_tmp = $_FILES['poto']['tmp_name'];

            move_uploaded_file($file_tmp, "img/" . $poto);

        } else {

            $poto = $_POST['foto_lama'];

        }

        /* =========================
           UPDATE PRODUK
        ========================= */

        $sql = "UPDATE tb_produk SET
                    nama='$nama_produk',
                    harga='$harga',
                    stok='$stok',
                    poto='$poto',
                    id_kategori='$kategori',
                    deskripsi='$deskripsi'
                WHERE id='$id'";

        $simpan = mysqli_query($koneksi, $sql);

        if (!$simpan) {
            die("Gagal mengedit produk: " . mysqli_error($koneksi));
        }

        header("Location: dashboard.php?status=edit");
        exit;
    }

    /* =========================
       PROSES TAMBAH
    ========================= */

    else {

        if (
            isset($_FILES['poto']) &&
            $_FILES['poto']['error'] == 0 &&
            !empty($_FILES['poto']['name'])
        ) {

            $poto = $_FILES['poto']['name'];
            $file_tmp = $_FILES['poto']['tmp_name'];

            move_uploaded_file($file_tmp, "img/" . $poto);

        } else {

            $poto = "";

        }

        $sql = "INSERT INTO tb_produk
                (nama, harga, stok, poto, id_kategori, deskripsi, status)
                VALUES
                ('$nama_produk', '$harga', '$stok', '$poto', '$kategori', '$deskripsi', 'aktif')";

        $simpan = mysqli_query($koneksi, $sql);

        if (!$simpan) {
            die("Gagal menambahkan produk: " . mysqli_error($koneksi));
        }

        header("Location: dashboard.php?status=tambah");
        exit;
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>

    <meta charset="utf-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    />

    <meta name="description" content="" />

    <meta
        name="author"
        content="Mark Otto, Jacob Thornton, and Bootstrap contributors"
    />

    <meta
        name="generator"
        content="Astro v5.13.2"
    />

    <title>Dashboard GreenLeaf Garden</title>

    <link
        rel="canonical"
        href="https://getbootstrap.com/docs/5.3/examples/dashboard/"
    />

    <script src="assets/js/color-modes.js"></script>

    <link
        href="assets/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />

    <meta
        name="theme-color"
        content="#712cf9"
    />

    <link
        href="dashboard.css"
        rel="stylesheet"
    />

<style>

:root {
    --green-main: #198754;
    --green-dark: #145c3a;
    --green-soft: #eaf6ef;
    --green-border: #dcefe4;
    --bg: #f5faf7;
    --text: #26332d;
    --muted: #718078;
}

* {
    box-sizing: border-box;
}

body {
    background: var(--bg);
    color: var(--text);
    font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

/* =========================
   NAVBAR
========================= */

.navbar {
    height: 64px;
    background: #ffffff !important;
    border-bottom: 1px solid var(--green-border);
    box-shadow: 0 3px 15px rgba(25, 135, 84, 0.06) !important;
    z-index: 1030;
}

.navbar-brand {
    color: var(--green-dark) !important;
    font-weight: 700;
    font-size: 16px;
    letter-spacing: 0.4px;
}

.navbar .nav-link {
    color: var(--green-dark) !important;
}

/* =========================
   SIDEBAR
========================= */

.sidebar {
    min-height: calc(100vh - 64px);
    background: #ffffff !important;
    border-right: 1px solid var(--green-border) !important;
}

.sidebar .offcanvas {
    background: #ffffff !important;
}

.sidebar .nav {
    padding: 8px 12px;
}

.sidebar .nav-item {
    margin-bottom: 5px;
}

.sidebar .nav-link {
    color: #617067 !important;
    font-size: 14px;
    font-weight: 500;
    padding: 11px 14px;
    border-radius: 10px;
    transition: all 0.25s ease;
}

.sidebar .nav-link .bi,
.sidebar .nav-link svg {
    width: 18px;
    height: 18px;
    color: #718078;
}

.sidebar .nav-link:hover {
    background: var(--green-soft);
    color: var(--green-main) !important;
    transform: translateX(2px);
}

.sidebar .nav-link:hover svg {
    color: var(--green-main);
}

.sidebar .nav-link.active {
    background: #198754 !important;
    color: #ffffff !important;
    box-shadow: 0 5px 14px rgba(25, 135, 84, 0.18);
}

.sidebar .nav-link.active svg {
    color: #ffffff;
}

/* =========================
   MAIN CONTENT
========================= */

main {
    padding-top: 8px;
    padding-bottom: 50px;
}

main > .d-flex {
    border-bottom: 1px solid var(--green-border) !important;
    padding-top: 20px !important;
    padding-bottom: 12px !important;
    margin-bottom: 12px !important;
}

main h1 {
    color: var(--green-dark);
    font-size: 26px;
    font-weight: 700;
    margin: 0;
}

main h2 {
    color: var(--green-dark);
    font-size: 20px;
    font-weight: 650;
    margin-top: 35px;
    margin-bottom: 18px;
}

/* =========================
   TOOLBAR
========================= */

.btn-toolbar .btn {
    border-color: #cfe5d8;
    color: #4e6258;
    background: #ffffff;
    border-radius: 8px;
    transition: all 0.25s ease;
}

.btn-toolbar .btn:hover {
    background: var(--green-soft);
    color: var(--green-main);
    border-color: #abd7bc;
}

/* =========================
   FORM CARD
========================= */

form.row {
    background: #ffffff;
    border: 1px solid var(--green-border);
    border-radius: 16px;
    padding: 24px;
    margin: 0;
    box-shadow: 0 8px 25px rgba(25, 135, 84, 0.07);
}

form,
form input,
form select,
form textarea,
form button {
    font-family: "Segoe UI", system-ui, sans-serif;
}

/* =========================
   INPUT
========================= */

.form-control,
.form-select {
    border: 1px solid #cfe5d8 !important;
    border-radius: 9px;
    padding: 10px 13px;
    background-color: #f9fcfa !important;
    color: #26332d !important;
    font-size: 14px;
}

.form-control::placeholder {
    color: #8a9690 !important;
    opacity: 1;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--green-main) !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.12) !important;
    background: #ffffff !important;
    color: #26332d !important;
}

.form-select {
    color: #26332d !important;
}

.form-select option {
    color: #26332d !important;
    background: #ffffff;
}

/* =========================
   HARGA RP
========================= */

.input-group-text {
    background: var(--green-soft) !important;
    border-color: #cfe5d8 !important;
    color: var(--green-dark) !important;
    font-weight: 600;
}

.input-group .form-control {
    border-radius: 0;
    color: #26332d !important;
}

/* =========================
   FILE
========================= */

input[type="file"] {
    cursor: pointer;
    color: #26332d !important;
}

input[type="file"]::file-selector-button {
    background: var(--green-soft);
    color: var(--green-dark);
    border: 0;
    border-right: 1px solid #cfe5d8;
    padding: 10px 15px;
    margin-right: 12px;
    font-weight: 500;
}

/* =========================
   GAMBAR
========================= */

form img {
    margin-top: 10px;
    border-radius: 9px;
    border: 1px solid var(--green-border);
    padding: 3px;
    background: #ffffff;
    object-fit: cover;
}

/* =========================
   BUTTON TAMBAH
========================= */

form .btn-primary {
    background: var(--green-main) !important;
    border-color: var(--green-main) !important;
    color: #ffffff !important;
    border-radius: 9px;
    padding: 9px 22px;
    font-weight: 600;
    transition: all 0.25s ease;
}

form .btn-primary:hover {
    background: #157347 !important;
    border-color: #157347 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(25, 135, 84, 0.18);
}

/* =========================
   TABLE
========================= */

.table-responsive {
    background: #ffffff;
    border: 1px solid var(--green-border);
    border-radius: 14px;
    padding: 5px;
    box-shadow: 0 8px 25px rgba(25, 135, 84, 0.06);
}

.table {
    margin-bottom: 0;
    vertical-align: middle;
}

.table thead th {
    background: #eef8f2 !important;
    color: var(--green-dark) !important;
    font-size: 13px;
    font-weight: 650;
    padding: 13px 12px;
    border-bottom: 1px solid var(--green-border);
    white-space: nowrap;
}

.table tbody td {
    color: #526159 !important;
    font-size: 13px;
    padding: 12px;
    border-color: #edf3ef;
}

.table tbody tr {
    transition: background 0.2s ease;
}

.table tbody tr:hover {
    background: #f7fcf9 !important;
}

.table tbody img {
    width: 65px !important;
    height: 55px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--green-border);
}

/* =========================
   ACTION BUTTON
========================= */

.btn-warning {
    background: #fff3cd !important;
    border-color: #ffe69c !important;
    color: #856404 !important;
    border-radius: 7px;
    font-weight: 500;
}

.btn-warning:hover {
    background: #ffdf7e !important;
    border-color: #ffdf7e !important;
    color: #664d03 !important;
}

.btn-danger {
    background: #fcebea !important;
    border-color: #f3c5c1 !important;
    color: #b02a37 !important;
    border-radius: 7px;
    font-weight: 500;
}

.btn-danger:hover {
    background: #dc3545 !important;
    border-color: #dc3545 !important;
    color: #ffffff !important;
}

/* =========================
   MOBILE
========================= */

@media (max-width: 767px) {

    .navbar {
        height: 58px;
    }

    .sidebar {
        min-height: 100vh;
    }

    main {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }

    main h1 {
        font-size: 23px;
    }

    .btn-toolbar {
        display: none;
    }

    form.row {
        padding: 18px;
        border-radius: 13px;
    }

    .table-responsive {
        border-radius: 11px;
    }
}

</style>

</head>

<body>

<!-- =========================
     SVG ICON
========================= -->

<svg xmlns="http://www.w3.org/2000/svg" class="d-none">

    <symbol id="check2" viewBox="0 0 16 16">
        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"></path>
    </symbol>

    <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
    </symbol>

    <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
        <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.528-.386-.617l-.732-.732z"></path>
    </symbol>

    <symbol id="sun-fill" viewBox="0 0 16 16">
        <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8z"></path>
    </symbol>

</svg>

<!-- =========================
     THEME BUTTON
========================= -->

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

        <span
            class="visually-hidden"
            id="bd-theme-text"
        >
            Toggle theme
        </span>

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

<!-- =========================
     MORE SVG ICONS
========================= -->

<svg xmlns="http://www.w3.org/2000/svg" class="d-none">

    <symbol id="calendar3" viewBox="0 0 16 16">
        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"></path>
        <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
    </symbol>

    <symbol id="list" viewBox="0 0 16 16">
        <path
            fill-rule="evenodd"
            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"
        ></path>
    </symbol>

    <symbol id="people" viewBox="0 0 16 16">
        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Z"></path>
        <path d="M11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
    </symbol>

    <symbol id="house-fill" viewBox="0 0 16 16">
        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"></path>
        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6Z"></path>
    </symbol>

    <symbol id="file-earmark" viewBox="0 0 16 16">
        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5z"></path>
    </symbol>

    <symbol id="door-closed" viewBox="0 0 16 16">
        <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2zm1 13h8V2H4v13z"></path>
        <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0z"></path>
    </symbol>

    <symbol id="search" viewBox="0 0 16 16">
        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
    </symbol>

</svg>

<!-- =========================
     NAVBAR
========================= -->

<header
    class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow"
    data-bs-theme="dark"
>

    <a
        class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white"
        href="#"
    >
        GREENLEAF GARDEN
    </a>

    <ul class="navbar-nav flex-row d-md-none">

        <li class="nav-item text-nowrap">

            <button
                class="nav-link px-3 text-white"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSearch"
                aria-controls="navbarSearch"
                aria-expanded="false"
                aria-label="Toggle search"
            >

                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#search"></use>
                </svg>

            </button>

        </li>

        <li class="nav-item text-nowrap">

            <button
                class="nav-link px-3 text-white"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >

                <svg class="bi" aria-hidden="true">
                    <use xlink:href="#list"></use>
                </svg>

            </button>

        </li>

    </ul>

    <div
        id="navbarSearch"
        class="navbar-search w-100 collapse"
    >

        <input
            class="form-control w-100 rounded-0 border-0"
            type="text"
            placeholder="Search"
            aria-label="Search"
        />

    </div>

</header>

<!-- =========================
     CONTENT
========================= -->

<div class="container-fluid">

    <div class="row">

        <!-- =========================
             SIDEBAR
        ========================= -->

        <div
            class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary"
        >

            <div
                class="offcanvas-md offcanvas-end bg-body-tertiary"
                tabindex="-1"
                id="sidebarMenu"
                aria-labelledby="sidebarMenuLabel"
            >

                <div class="offcanvas-header">

                    <h5
                        class="offcanvas-title"
                        id="sidebarMenuLabel"
                    >
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

                <div
                    class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto"
                >

                    <ul class="nav flex-column">

                        <li class="nav-item">

                            <a
                                class="nav-link d-flex align-items-center gap-2 active"
                                aria-current="page"
                                href="dashboard.php"
                            >

                                <svg class="bi" aria-hidden="true">
                                    <use xlink:href="#house-fill"></use>
                                </svg>

                                Dashboard

                            </a>

                        </li>

                        <li class="nav-item">

                            <a
                                class="nav-link d-flex align-items-center gap-2"
                                href="transaksi.php"
                            >

                                <svg class="bi" aria-hidden="true">
                                    <use xlink:href="#file-earmark"></use>
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
                                    <use xlink:href="#people"></use>
                                </svg>

                                Customers

                            </a>

                        </li>

                        <li class="nav-item mt-2">

                            <a
                                class="nav-link d-flex align-items-center gap-2"
                                href="logout.php"
                                onclick="return konfirmasiLogout(event, this.href);"
                            >

                                <svg class="bi" aria-hidden="true">
                                    <use xlink:href="#door-closed"></use>
                                </svg>

                                Logout

                            </a>

                        </li>

                    </ul>

                </div>

            </div>

        </div>

        <!-- =========================
             MAIN
        ========================= -->

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

            <!-- DASHBOARD HEADER -->

            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
            >

                <h1 class="h2">
                    Dashboard
                </h1>

                <div class="btn-toolbar mb-2 mb-md-0">

                    <div class="btn-group me-2">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            Share
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                        >
                            Export
                        </button>

                    </div>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1"
                    >

                        <svg class="bi" aria-hidden="true">
                            <use xlink:href="#calendar3"></use>
                        </svg>

                        This week

                    </button>

                </div>

            </div>

            <!-- =========================
                 FORM TAMBAH / EDIT
            ========================= -->

            <form
                class="row g-3"
                method="post"
                enctype="multipart/form-data"
            >

                <?php if ($aksi == "edit") { ?>

                    <input
                        type="hidden"
                        name="id_produk"
                        value="<?= $id ?>"
                    >

                <?php } ?>

                <div class="col-12">

                    <input
                        type="text"
                        class="form-control"
                        name="nama"
                        value="<?= htmlspecialchars(@$nama ?? '') ?>"
                        placeholder="Nama produk"
                    >

                </div>

                <div class="input-group mb-3">

                    <span class="input-group-text">
                        Rp
                    </span>

                    <input
                        type="text"
                        class="form-control"
                        name="harga"
                        value="<?= htmlspecialchars(@$harga ?? '') ?>"
                        aria-label="Harga"
                    >

                    <span class="input-group-text">
                        .000
                    </span>

                </div>

                <div class="col-12">

                    <input
                        type="text"
                        class="form-control"
                        name="stok"
                        value="<?= htmlspecialchars(@$stok ?? '') ?>"
                        placeholder="Stok"
                    >

                </div>

                <div class="input-group">

                    <input
                        type="file"
                        class="form-control"
                        name="poto"
                        id="inputGroupFile04"
                        aria-describedby="inputGroupFileAddon04"
                        aria-label="Upload"
                    >

                    <input
                        type="hidden"
                        name="foto_lama"
                        value="<?= htmlspecialchars(@$poto ?? '') ?>"
                    >

                    <?php if (!empty($poto)) { ?>

                        <img
                            src="img/<?= htmlspecialchars($poto) ?>"
                            height="100px"
                            alt="Foto produk"
                        >

                    <?php } ?>

                </div>

                <div class="col-md-4">

                    <select
                        id="inputState"
                        class="form-select"
                        name="id_kategori"
                        required
                    >

                        <?php

                        $result = mysqli_query(
                            $koneksi,
                            "SELECT * FROM tb_kategori"
                        );

                        while ($list = mysqli_fetch_array($result)) {

                            $selected = '';

                            if (
                                $aksi == "edit" &&
                                isset($id_kategori) &&
                                $id_kategori == $list['id_kategori']
                            ) {
                                $selected = 'selected';
                            }

                        ?>

                            <option
                                value="<?= $list['id_kategori']; ?>"
                                <?= $selected; ?>
                            >
                                <?= htmlspecialchars($list['nama_kategori']); ?>
                            </option>

                        <?php } ?>

                        <?php if ($aksi != "edit") { ?>

                            <option
                                selected
                                disabled
                                value=""
                            >
                                Pilih kategori
                            </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="col-12">

                    <input
                        type="text"
                        class="form-control"
                        name="deskripsi"
                        value="<?= htmlspecialchars(@$deskripsi ?? '') ?>"
                        placeholder="Deskripsi"
                    >

                </div>

                <div class="col-12">

                    <button
                        type="submit"
                        name="tambah"
                        class="btn btn-primary"
                    >
                        <?= ($aksi == "edit")
                            ? "Simpan Perubahan"
                            : "Tambah"
                        ?>
                    </button>

                </div>

            </form>

            <!-- =========================
                 DATA PRODUK
            ========================= -->

            <h2>
                Data Produk
            </h2>

            <div class="table-responsive small">

                <table class="table table-striped table-sm">

                    <thead>

                        <tr>

                            <th scope="col">
                                Id
                            </th>

                            <th scope="col">
                                Nama
                            </th>

                            <th scope="col">
                                Harga
                            </th>

                            <th scope="col">
                                Stock
                            </th>

                            <th scope="col">
                                Poto
                            </th>

                            <th scope="col">
                                Kategori
                            </th>

                            <th scope="col">
                                Deskripsi
                            </th>

                            <th scope="col">
                                Status
                            </th>

                            <th scope="col">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        $hasil = mysqli_query(
                            $koneksi,
                            "SELECT *
                             FROM tb_produk p
                             INNER JOIN tb_kategori k
                             ON p.id_kategori = k.id_kategori
                             ORDER BY p.id DESC"
                        );

                        while ($data = mysqli_fetch_array($hasil)) {

                        ?>

                            <tr>

                                <td>
                                    <?= $no++; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['nama']); ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        $data['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['stok']); ?>
                                </td>

                                <td>

                                    <?php if (!empty($data['poto'])) { ?>

                                        <img
                                            src="img/<?= htmlspecialchars($data['poto']); ?>"
                                            width="100px"
                                            alt="<?= htmlspecialchars($data['nama']); ?>"
                                        >

                                    <?php } else { ?>

                                        <span class="text-muted">
                                            Tidak ada foto
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($data['nama_kategori']); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($data['deskripsi']); ?>
                                </td>

                                <td>

                                    <?php if ($data['status'] === 'aktif') { ?>

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge bg-secondary">
                                            Nonaktif
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>

                                    <div class="d-flex gap-1">

                                        <a
                                            href="dashboard.php?aksi=edit&id=<?= $data['id']; ?>"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Edit
                                        </a>

                                        <a
                                            href="hapus.php?id=<?= $data['id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return konfirmasiHapus(event, this.href);"
                                        >
                                            Hapus
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </main>

    </div>

</div>

<!-- =========================
     JAVASCRIPT
========================= -->

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script
    src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.js"
    integrity="sha384-eI7PSr3L1XLISH8JdDII5YN/njoSsxfbrkCTnJrzXt+ENP5MOVBxD+l6sEG4zoLp"
    crossorigin="anonymous">
</script>

<script src="dashboard.js"></script>

<!-- SweetAlert2 -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const status = <?= json_encode($_GET['status'] ?? '') ?>;

    const nama = <?= json_encode($_SESSION['username'] ?? 'Admin') ?>;

    /* =========================
       LOGIN BERHASIL
    ========================= */

    if (status === 'login') {

        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: 'Selamat datang, ' + nama + '!',
            confirmButtonColor: '#198754',
            confirmButtonText: 'OK'
        });

    }

    /* =========================
       PRODUK BERHASIL DITAMBAHKAN
    ========================= */

    if (status === 'tambah') {

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data produk berhasil ditambahkan.',
            confirmButtonColor: '#198754',
            confirmButtonText: 'OK'
        });

    }

    /* =========================
       PRODUK BERHASIL DIUPDATE
    ========================= */

    if (status === 'edit') {

        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data produk berhasil diperbarui.',
            confirmButtonColor: '#198754',
            confirmButtonText: 'OK'
        });

    }

});


/* =========================
   KONFIRMASI HAPUS
========================= */

function konfirmasiHapus(event, url)
{
    event.preventDefault();

    Swal.fire({

        title: 'Apakah Anda yakin?',

        text: 'Produk akan disembunyikan dari katalog pelanggan, tetapi datanya tetap tersimpan.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#198754',

        cancelButtonColor: '#b02a37',

        confirmButtonText: 'Ya, Hapus',

        cancelButtonText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {

            window.location.href = url;

        }

    });

    return false;
}


/* =========================
   KONFIRMASI LOGOUT
========================= */

function konfirmasiLogout(event, url)
{
    event.preventDefault();

    Swal.fire({

        title: 'Logout?',

        text: 'Apakah Anda yakin ingin keluar dari dashboard?',

        icon: 'question',

        showCancelButton: true,

        confirmButtonColor: '#198754',

        cancelButtonColor: '#b02a37',

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