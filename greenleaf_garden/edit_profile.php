<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: login.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];

/* =========================
   UPDATE PROFILE
========================= */

if (isset($_POST['update_profile'])) {

    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $hp       = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $alamat   = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $update = mysqli_query(
        $koneksi,
        "UPDATE tb_user SET
            nama='$nama',
            email='$email',
            username='$username',
            hp='$hp',
            alamat='$alamat'
         WHERE id='$id_user'
         AND role='pelanggan'"
    );

    if ($update) {
        $_SESSION['username'] = $username;

        header("Location: profile.php?status=edit");
        exit;
    } else {
        header("Location: edit_profile.php?status=gagal");
        exit;
    }
}


/* =========================
   AMBIL DATA USER
========================= */

$query = mysqli_query(
    $koneksi,
    "SELECT id, nama, email, username, hp, alamat
     FROM tb_user
     WHERE id='$id_user'
     AND role='pelanggan'"
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: login.php");
    exit;
}
?>

<!doctype html>
<html lang="id">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>Edit Profile - GreenLeaf Garden</title>

<link href="assets/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link rel="preconnect"
      href="https://fonts.googleapis.com">

<link rel="preconnect"
      href="https://fonts.gstatic.com"
      crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
      rel="stylesheet">
      <style>
body {
    margin: 0;
    min-height: 100vh;

    font-family: "Poppins", Arial, sans-serif;

    background:
        radial-gradient(
            circle at 10% 10%,
            rgba(25,135,84,.10),
            transparent 30%
        ),
        radial-gradient(
            circle at 90% 90%,
            rgba(46,125,50,.08),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #f7fbf8,
            #edf7f1
        );

    color: #304238;
}

.edit-container {
    padding: 55px 15px;
}

.edit-card {
    max-width: 720px;
    margin: auto;

    border: 1px solid #dceae1;
    border-radius: 20px;

    background: rgba(255,255,255,.97);

    box-shadow:
        0 20px 50px rgba(23,78,48,.10);

    overflow: hidden;
}

.edit-header {
    padding: 30px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #126b40,
            #198754,
            #42ad75
        );
}

.edit-header h3 {
    margin: 0;

    font-size: 24px;
    font-weight: 600;
}

.edit-header p {
    margin: 7px 0 0;

    font-size: 13px;

    color: rgba(255,255,255,.85);
}

.edit-body {
    padding: 32px;
}

.form-label {
    color: #214c35;

    font-size: 13px;

    font-weight: 600;
}

.form-control {
    border: 1px solid #d5e5db;

    border-radius: 10px;

    padding: 11px 14px;

    background: #f8fbf9;

    font-size: 14px;

    color: #304238;

    transition: .25s;
}

.form-control:focus {
    background: white;

    border-color: #198754;

    box-shadow:
        0 0 0 3px rgba(25,135,84,.10);
}

textarea.form-control {
    resize: none;
}

.btn-green {
    border: none;

    border-radius: 10px;

    padding: 10px 20px;

    background:
        linear-gradient(
            135deg,
            #198754,
            #27a965
        );

    color: white;

    font-size: 13px;

    font-weight: 500;

    transition: .3s;
}

.btn-green:hover {
    color: white;

    transform: translateY(-2px);
}

.btn-back {
    border: 1px solid #c8ded0;

    border-radius: 10px;

    padding: 10px 20px;

    background: white;

    color: #198754;

    font-size: 13px;
}

.btn-back:hover {
    background: #eaf7ef;

    color: #146c43;
}

@media(max-width:767px) {

    .edit-container {
        padding: 30px 15px;
    }

    .edit-header {
        padding: 25px;
    }

    .edit-body {
        padding: 22px;
    }

}

</style>

</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

<?php if (isset($_GET['status']) && $_GET['status'] == 'edit') { ?>

Swal.fire({
    icon: 'success',
    title: 'Profil Berhasil Diperbarui!',
    text: 'Informasi akun Anda telah berhasil disimpan.',
    confirmButtonColor: '#198754',
    confirmButtonText: 'OK'
});

<?php } ?>

</script>

<body>

<div class="container edit-container">

    <div class="edit-card">

        <div class="edit-header">

            <h3>
                Edit Profile
            </h3>

            <p>
                Perbarui informasi akun GreenLeaf Garden Anda.
            </p>

        </div>


        <div class="edit-body">

            <form method="post">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="<?= htmlspecialchars($data['nama'] ?? '') ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            value="<?= htmlspecialchars($data['username'] ?? '') ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            No. HP
                        </label>

                        <input
                            type="text"
                            name="hp"
                            class="form-control"
                            value="<?= htmlspecialchars($data['hp'] ?? '') ?>"
                            required
                        >

                    </div>


                    <div class="col-12 mb-4">

                        <label class="form-label">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            class="form-control"
                            rows="4"
                            required
                        ><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>

                    </div>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    <button
                        type="submit"
                        name="update_profile"
                        class="btn btn-green">

                        Simpan Perubahan

                    </button>


                    <a
                        href="profile.php"
                        class="btn btn-back">

                        Kembali

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

<?php if (isset($_GET['status']) && $_GET['status'] == 'gagal') { ?>

Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: 'Profil tidak berhasil diperbarui.',
    confirmButtonColor: '#198754'
});

<?php } ?>

</script>

</body>

</html>