<?php

session_start();

include 'koneksi.php';



if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {

    header("Location: login.php?status=hapus");

    exit;

}



if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    // Nonaktifkan akun pelanggan agar relasi transaksi tetap aman.
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE tb_user SET status = 'nonaktif' WHERE id = ? AND role = 'pelanggan'"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    $berhasil = mysqli_stmt_execute($stmt);
    $jumlah = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($berhasil && $jumlah > 0) {
        header("Location: pelanggan.php?status=hapus");
    } else {
        header("Location: pelanggan.php?status=gagal_hapus");
    }
    exit;

}

/* RESET PASSWORD PELANGGAN */

if (isset($_GET['reset_password'])) {



    $id = (int) $_GET['reset_password'];



    $password_baru = '12345678';



    mysqli_query(

        $koneksi,

        "UPDATE tb_user

         SET password='$password_baru'

         WHERE id='$id'

         AND role='pelanggan'"

    );



    header("Location: pelanggan.php?status=reset");

    exit;

}



/* DETAIL PELANGGAN */

$detail = null;



if (isset($_GET['detail'])) {

    $id = $_GET['detail'];



    $query_detail = mysqli_query($koneksi, "SELECT id, nama, email, hp, alamat, role FROM tb_user WHERE id='$id' AND role='pelanggan' AND status='aktif'");

    $detail = mysqli_fetch_assoc($query_detail);

}



/* DATA PELANGGAN */

$query = mysqli_query($koneksi, "SELECT id, nama, email, hp, alamat, role FROM tb_user WHERE role='pelanggan' AND status='aktif' ORDER BY id DESC");

?>



<!doctype html>

<html lang="en" data-bs-theme="auto">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <title>Data Pelanggan - GreenLeaf Garden</title>



    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

<style>



    :root {

        --green-main: #198754;

        --green-dark: #145c3a;

        --green-soft: #eaf6ef;

        --green-border: #dcefe4;

        --green-light: #f5faf7;

        --text-dark: #26382f;

        --text-muted: #6c7d74;

    }



    body {

        background: var(--green-light);

        color: var(--text-dark);

        font-family: "Segoe UI", sans-serif;

    }





    /* =========================

       NAVBAR

    ========================== */



    .navbar {

        height: 64px;

        background: #ffffff !important;

        border-bottom: 1px solid var(--green-border);

        box-shadow: 0 2px 10px rgba(25, 135, 84, 0.06) !important;

    }



    .navbar-brand {

        color: var(--green-dark) !important;

        font-weight: 700;

        letter-spacing: .5px;

    }





    /* =========================

       SIDEBAR

    ========================== */



    .sidebar {

        background: #ffffff !important;

        border-color: var(--green-border) !important;

        min-height: calc(100vh - 64px);

    }



    .offcanvas {

        background: #ffffff !important;

    }



    .offcanvas-body {

        padding-top: 18px !important;

    }



    .sidebar .nav-link {

        color: #60736a;

        font-weight: 500;

        font-size: 14px;

        padding: 11px 16px;

        margin: 4px 10px;

        border-radius: 10px;

        transition: all .2s ease;

    }



    .sidebar .nav-link:hover {

        color: var(--green-main);

        background: var(--green-soft);

    }



    .sidebar .nav-link.active {

        color: #ffffff !important;

        background: linear-gradient(

            135deg,

            #5b7669,

            #198754,

            #2aa66a

        );

        box-shadow: 0 5px 12px rgba(25, 135, 84, .15);

    }



    .sidebar .nav-link svg {

        width: 18px !important;

        height: 18px !important;

        flex-shrink: 0;

    }





    /* =========================

       MAIN CONTENT

    ========================== */



    main {

        padding-top: 24px;

        padding-bottom: 40px;

    }



    main > .d-flex {

        border-bottom: 1px solid var(--green-border) !important;

    }



    h1,

    h4 {

        color: var(--green-dark);

        font-weight: 700;

    }



    h1 {

        font-size: 25px;

    }



    h4 {

        font-size: 20px;

    }





    /* =========================

       TABLE

    ========================== */



    .table-responsive {

        background: #ffffff;

        border: 1px solid var(--green-border);

        border-radius: 16px;

        box-shadow: 0 6px 20px rgba(25, 135, 84, .07);

        overflow: hidden;

    }



    .table {

        margin-bottom: 0;

        vertical-align: middle;

    }



    .table thead th {

        background: var(--green-soft);

        color: var(--green-dark);

        font-weight: 600;

        border-bottom: 1px solid var(--green-border);

        padding: 14px 12px;

        white-space: nowrap;

    }



    .table tbody td {

        padding: 13px 12px;

        border-color: #edf4ef;

        color: #53655c;

    }



    .table tbody tr {

        transition: background .2s ease;

    }



    .table tbody tr:hover {

        background: #f7fcf9;

    }





    /* =========================

       DETAIL PELANGGAN

    ========================== */



    .mt-4 > .d-flex {

        border-bottom-color: var(--green-border) !important;

    }



    .mt-4 .table-responsive {

        margin-top: 5px;

    }



    .mt-4 .table th {

        width: 200px;

        background: var(--green-soft);

        color: var(--green-dark);

        font-weight: 600;

    }



    .mt-4 .table td {

        background: #ffffff;

        color: var(--text-muted);

    }





    /* =========================

       ROLE BADGE

    ========================== */



    .badge.text-bg-secondary {

        background: var(--green-soft) !important;

        color: var(--green-dark) !important;

        border: 1px solid var(--green-border);

        font-weight: 500;

        padding: 6px 10px;

        border-radius: 7px;

    }





    /* =========================

       BUTTON

    ========================== */



    .btn {

        border-radius: 8px;

        font-weight: 500;

        transition: all .2s ease;

    }



    .btn-sm {

        font-size: 13px;

        padding: 6px 11px;

    }



    .btn-primary {

        background: var(--green-main);

        border-color: var(--green-main);

    }



    .btn-primary:hover {

        background: #157347;

        border-color: #157347;

    }



    .btn-danger {

        background: #fff0f0;

        color: #dc3545;

        border: 1px solid #f3c7cc;

    }



    .btn-danger:hover {

        background: #dc3545;

        color: #ffffff;

        border-color: #dc3545;

    }



    .btn-secondary {

        background: #ffffff;

        color: #5f7168;

        border: 1px solid #cfded5;

    }



    .btn-secondary:hover {

        background: var(--green-soft);

        color: var(--green-dark);

        border-color: #a9cdb7;

    }





    /* =========================

       EMPTY DATA

    ========================== */



    .table tbody td.text-center {

        padding: 35px 15px;

        color: #819189;

        font-style: italic;

    }





    /* =========================

       MOBILE

    ========================== */



    @media (max-width: 767.98px) {



        .navbar {

            height: 58px;

        }



        main {

            padding: 18px 12px 30px;

        }



        h1 {

            font-size: 21px;

        }



        h4 {

            font-size: 18px;

        }



        .table-responsive {

            border-radius: 12px;

        }



        .table {

            min-width: 750px;

        }



        .mt-4 .table {

            min-width: 0;

        }



    }



</style>

</head>



<body>



<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">



    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white"

       href="dashboard.php">

        GREENLEAF GARDEN

    </a>



</header>



<div class="container-fluid">

    <div class="row">



        <!-- SIDEBAR -->

        <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">



            <div class="offcanvas-md offcanvas-end bg-body-tertiary"

                 tabindex="-1"

                 id="sidebarMenu"

                 aria-labelledby="sidebarMenuLabel">



                <div class="offcanvas-header">

                    <h5 class="offcanvas-title" id="sidebarMenuLabel">

                        GREENLEAF GARDEN

                    </h5>



                    <button type="button"

                            class="btn-close"

                            data-bs-dismiss="offcanvas"

                            data-bs-target="#sidebarMenu"

                            aria-label="Close">

                    </button>

                </div>



                <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">



                    <ul class="nav flex-column">



                        <!-- DASHBOARD -->

                        <li class="nav-item">

                            <a class="nav-link d-flex align-items-center gap-2"

                               href="dashboard.php">



                                <svg xmlns="http://www.w3.org/2000/svg"

                                     width="18"

                                     height="18"

                                     fill="currentColor"

                                     class="bi bi-house"

                                     viewBox="0 0 16 16">



                                    <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 2 7.5V14a1 1 0 0 0 1 1h3.5a.5.5 0 0 0 1-1v-3.5h1V14a1 1 0 0 0 1 1H12a1 1 0 0 0 1-1V7.5a.5.5 0 0 0 .354-.854zM2.5 7.293 8 1.793l5.5 5.5V14a.5.5 0 0 1-.5.5H9.5V10h-3v4.5H3a.5.5 0 0 1-.5-.5z"/>

                                </svg>



                                Dashboard

                            </a>

                        </li>



                        <!-- ORDERS -->

                        <li class="nav-item">

                            <a class="nav-link d-flex align-items-center gap-2"

                               href="transaksi.php">



                                <svg xmlns="http://www.w3.org/2000/svg"

                                     width="18"

                                     height="18"

                                     fill="currentColor"

                                     class="bi bi-cart"

                                     viewBox="0 0 16 16">



                                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 7A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.14 4l1.25 6h8.22l1.286-6z"/>

                                    <path d="M5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>

                                </svg>



                                Orders

                            </a>

                        </li>



                        <!-- CUSTOMERS -->

                        <li class="nav-item">

                            <a class="nav-link d-flex align-items-center gap-2 active"

                               aria-current="page"

                               href="pelanggan.php">



                                <svg xmlns="http://www.w3.org/2000/svg"

                                     width="18"

                                     height="18"

                                     fill="currentColor"

                                     class="bi bi-people"

                                     viewBox="0 0 16 16">



                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zM7 9s1 0 1-1-1-3-4-3-4 2-4 3 1 1 1 1z"/>

                                    <path fill-rule="evenodd" d="M7.022 9.268A5.5 5.5 0 0 1 8 11c0 .6-.1 1.16-.29 1.68C7.36 12.94 6.72 13 6 13H1s-1 0-1-1 1-4 5-4c.76 0 1.41.1 2.02.268M11 5a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-4 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>

                                </svg>



                                Customers

                            </a>

                        </li>



                    </ul>



                </div>

            </div>

        </div>



        <!-- MAIN CONTENT -->

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">



            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">



                <h1 class="h2">Data Pelanggan</h1>



            </div>



            <!-- TABEL PELANGGAN -->

            <div class="table-responsive small">



                <table class="table table-striped table-sm">



                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Nama</th>

                            <th>Email</th>

                            <th>No. HP</th>

                            <th>Alamat</th>

                            <th>Role</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>



                    <tbody>



                        <?php

                        $no = 1;



                        if (mysqli_num_rows($query) > 0) {



                            while ($data = mysqli_fetch_assoc($query)) {

                        ?>



                      <tr>



    <td><?= $no++; ?></td>



    <td><?= htmlspecialchars($data['nama'] ?? ''); ?></td>



    <td><?= htmlspecialchars($data['email'] ?? ''); ?></td>



    <td><?= htmlspecialchars($data['hp'] ?? ''); ?></td>



    <td><?= htmlspecialchars($data['alamat'] ?? ''); ?></td>



    <td>

        <span class="badge text-bg-secondary">

            <?= htmlspecialchars($data['role'] ?? ''); ?>

        </span>

    </td>



    <td>





      <a href="pelanggan.php?reset_password=<?= $data['id']; ?>"

   class="btn btn-secondary btn-sm"

   onclick="return konfirmasiReset(event, this.href);">

    Reset Password

</a>



<a href="pelanggan.php?hapus=<?= $data['id']; ?>"

   class="btn btn-danger btn-sm"

   onclick="return konfirmasiHapus(event, this.href);">

    Hapus

</a>

    </td>



</tr>



                        <?php

                            }



                        } else {

                        ?>



                        <tr>

                            <td colspan="7" class="text-center">

                                Belum ada data pelanggan.

                            </td>

                        </tr>



                        <?php } ?>



                    </tbody>



                </table>



            </div>





        </main>



    </div>

</div>



<script src="assets/dist/js/bootstrap.bundle.min.js"></script>



<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiHapus(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'Nonaktifkan pelanggan?',
        text: 'Akun pelanggan akan dinonaktifkan. Riwayat transaksinya tetap tersimpan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Nonaktifkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    return false;
}

function konfirmasiReset(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'Reset password?',
        text: 'Password pelanggan akan diubah menjadi 12345678.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Reset',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
    return false;
}

<?php if (isset($_GET['status']) && $_GET['status'] == 'hapus') { ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: 'Akun pelanggan berhasil dinonaktifkan.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});
<?php } ?>

<?php if (isset($_GET['status']) && $_GET['status'] == 'gagal_hapus') { ?>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: 'Pelanggan tidak ditemukan atau gagal dinonaktifkan.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});
<?php } ?>
</script>
</body>
</html>
