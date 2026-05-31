<?php
session_start();
require "koneksi.php";

// cek login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// penanda hal aktif
$currentPage = "add";

// array waktu
$waktu = ["08:00", "10:30", "13:00", "15:30"];

// ambil data laboratorium
$queryLab = mysqli_query($konek, "SELECT * FROM laboratorium");

// cek tombol submit ditekan
if (isset($_POST['submit'])) {
    // ambil data dari form
    $laboratorium_id = $_POST['laboratorium_id'];
    $tanggal = $_POST['tanggal'];
    $waktu_pilih = $_POST['waktu'];

    // cek apakah waktu sudah dipakai
    $cek = mysqli_query(
        $konek,
        "SELECT * FROM peminjaman
        WHERE laboratorium_id = '$laboratorium_id'
        AND tanggal = '$tanggal'
        AND waktu = '$waktu_pilih'"
    );

    // mengecek hasil query
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Lab sudah dipinjam di hari dan jam tersebut!";
        header("Location: add.php");
        exit;

        // kalo masi kosong
    } else {
        // ambil user login
        $user_id = $_SESSION['id'];
        // cek data kosong, kalo kosong error, harus isi semua
        if (
            empty($user_id) ||
            empty($laboratorium_id) ||
            empty($tanggal) ||
            empty($waktu_pilih)
        ) {
            $_SESSION['error'] = "Data belum lengkap!";
            header("Location: add.php");
            exit;
        }

        // simpan ke database
        mysqli_query(
            $konek,
            "INSERT INTO peminjaman
            (user_id, laboratorium_id, tanggal, waktu, created_at)
            VALUES ('$user_id', '$laboratorium_id', '$tanggal', '$waktu_pilih', NOW())"
        );

        // succes message, lanjut ke dashboard
        $_SESSION['success'] = "Peminjaman berhasil!";
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Peminjaman</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4a169f;
            --secondary-color: #725aca;
            --link-color: #ffb99c;
            --text-color: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--text-color);
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-custom {
            width: 100%;
            padding: 30px 70px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 55px;
            height: 55px;
            border-radius: 50%;

            display: flex;
            justify-content: center;
            align-items: center;

            background-color: var(--secondary-color);
            border: 3px solid var(--link-color);

            color: var(--link-color);
            font-size: 28px;
            font-weight: bold;
        }

        .menu {
            display: flex;
            gap: 40px;
        }

        .menu a {
            text-decoration: none;
            font-weight: bold;
            color: var(--link-color);
            font-size: 20px;
        }

        .menu a:hover {
            color: var(--primary-color);
        }

        .active-menu {
            color: var(--primary-color) !important;
        }

        .form-section {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;

            margin-top: 40px;
        }

        .form-box {
            width: 500px;
            background-color: white;

            border: 4px solid var(--secondary-color);
            border-radius: 25px;

            padding: 40px;
        }

        .form-title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: bold;
        }

        .form-label {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-control,
        .form-select {
            height: 50px;
            border-radius: 12px;
            border: 2px solid var(--secondary-color);
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .radio-item {
            padding: 10px 18px;
            border: 2px solid var(--secondary-color);
            border-radius: 12px;
        }

        .button-group {
            display: flex;
            gap: 15px;

            margin-top: 25px;
        }

        .btn-submit,
        .btn-cancel {
            flex: 1;
            height: 50px;

            border-radius: 12px;

            font-weight: bold;
            font-size: 18px;

            display: flex;
            justify-content: center;
            align-items: center;

            text-decoration: none;
        }

        .btn-submit {
            background-color: var(--secondary-color);
            border: none;
            color: white;
        }

        .btn-submit:hover {
            background-color: var(--primary-color);
        }

        .btn-cancel {
            background-color: transparent;
            border: 3px solid var(--secondary-color);
            color: var(--primary-color);
        }

        .btn-cancel:hover {
            background-color: #ece7ff;
        }

        @media (max-width: 768px) {

            .navbar-custom {
                flex-direction: column;
                gap: 20px;
                padding: 20px;
            }

            .menu {
                gap: 20px;
            }

            .form-box {
                width: 90%;
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    <!-- navbar -->
    <div class="navbar-custom">
        <!-- logo -->
        <div class="logo">C</div>
        <!-- menu -->
        <div class="menu">
            <a href="dashboard.php" class="<?= ($currentPage == 'home') ? 'active-menu' : ''; ?>">Home</a>
            <a href="riwayat.php" class="<?= ($currentPage == 'riwayat') ? 'active-menu' : ''; ?>">Riwayat</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- form -->
    <div class="form-section">

        <div class="form-box">
            <h1 class="form-title">Tambah Peminjaman </h1>

            <!-- alert, kalo ada error di session -->
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>
                <!-- menghapus session error setelah ditampilkan biar ga muncul terus -->
                <?php unset($_SESSION['error']); ?>
            <?php } ?>

            <!-- data form di kirim pake method post -->
            <form method="POST">

                <!-- laboratorium -->
                <div class="mb-4">
                    <label class="form-label"> Pilih Laboratorium </label>

                    <!-- dropdown -->
                    <select name="laboratorium_id" class="form-select" required>
                        <option value="">-- Pilih Laboratorium --</option>
                        <!-- ngambil data satu persatu dr query -->
                        <?php while ($lab = mysqli_fetch_assoc($queryLab)) { ?>
                            <!-- yang dikirim ke database adalah id lab -->
                            <option value="<?= $lab['id']; ?>">
                                <?= htmlspecialchars($lab['nama_lab']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- tanggal -->
                <div class="mb-4">
                    <label class="form-label"> Tanggal</label>
                    <!-- input tanggal -->
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <!-- waktu -->
                <dviv class="mb-4">
                    <label class="form-label"> Pilih Waktu </label>
                    <div class="radio-group">
                        <!-- loop array waktu -->
                        <?php foreach ($waktu as $jam) { ?>
                            <label class="radio-item">
                                <input type="radio" name="waktu" value="<?= $jam; ?>" required>
                                <?= $jam; ?>
                            </label>
                        <?php } ?>
                    </div>
                </dviv>

                <!-- tombol -->
                <div class="button-group">
                    <a href="dashboard.php" class="btn-cancel"> Batalkan</a>
                    <button type="submit" name="submit" class="btn-submit">
                        Tambah Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>