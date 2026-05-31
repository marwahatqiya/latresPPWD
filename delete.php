<?php
session_start();
require "koneksi.php";

// cek login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// cek id
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];

// ambil data peminjaman
$query = mysqli_query($konek,
    "SELECT peminjaman.*, laboratorium.nama_lab
    FROM peminjaman
    JOIN laboratorium
    ON peminjaman.laboratorium_id = laboratorium.id
    WHERE peminjaman.id = '$id'"
);

$data = mysqli_fetch_assoc($query);

// kalau data tidak ada
if (!$data) {
    header("Location: dashboard.php");
    exit;
}

// proses hapus
if (isset($_POST['hapus'])) {
    mysqli_query($konek,"DELETE FROM peminjaman
        WHERE id = '$id'");

    $_SESSION['success'] = "Data berhasil dihapus";
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Peminjaman</title>

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

        .delete-section {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;

            margin-top: 50px;
        }

        .delete-box {
            width: 500px;
            background-color: white;

            border: 4px solid var(--secondary-color);
            border-radius: 25px;

            padding: 40px;
            text-align: center;
        }

        .delete-title {
            color: var(--danger-color);
            font-weight: bold;
            margin-bottom: 25px;
        }

        .delete-info {
            margin-bottom: 25px;
        }

        .delete-info p {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--primary-color);
            font-weight: 600;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-delete {
            flex: 1;
            height: 50px;

            background-color: var(--link-color);
            border-color: var(--primary-color);
            border-radius: 12px;

            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        .btn-delete:hover {
            background-color: var(--link-color);
        }

        .btn-cancel {
            flex: 1;
            height: 50px;

            background-color: var(--secondary-color);
            border-color: var(--primary-color);
            border-radius: 12px;

            color: white;
            font-weight: bold;
            font-size: 18px;

            text-decoration: none;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-cancel:hover {
            background-color: var(--primary-color);
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

            .delete-box {
                width: 90%;
                padding: 25px;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- navbar -->
    <div class="navbar-custom">
        <div class="logo">C</div>
        <div class="menu">
            <a href="dashboard.php">Home</a>
            <a href="riwayat.php">Riwayat</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- delete section -->
    <div class="delete-section">

        <div class="delete-box">

            <h1 class="delete-title">Hapus Peminjaman</h1>

            <div class="delete-info">
                <p>Laboratorium:
                    <?= htmlspecialchars($data['nama_lab']); ?>
                </p>

                <p>Tanggal:
                    <?= $data['tanggal']; ?>
                </p>

                <p>Waktu:
                    <?= $data['waktu']; ?>
                </p>
            </div>

            <h5>
                Apakah kamu yakin ingin menghapus data ini?
            </h5>

            <form method="POST">
                <div class="button-group">
                    <a href="dashboard.php" class="btn-cancel">
                        Batal</a>

                    <button type="submit"name="hapus" class="btn-delete">
                        Hapus</button>

                </div>

            </form>
        </div>
    </div>
</body>

</html>