<?php
session_start();
require "koneksi.php";

// cek login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$currentPage = "riwayat";

// ambil data riwayat
$query = mysqli_query($konek,
    "SELECT peminjaman.id,
            laboratorium.nama_lab,
            peminjaman.tanggal,
            peminjaman.waktu
    FROM peminjaman
    JOIN laboratorium
    ON peminjaman.laboratorium_id = laboratorium.id
    ORDER BY peminjaman.created_at DESC"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat</title>

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

        .menu .active {
            color: var(--primary-color);
        }

        .history-section {
            width: 85%;
            margin: auto;
            margin-top: 30px;
        }

        .history-title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: bold;
        }

        .history-box {
            border: 4px solid var(--secondary-color);
            border-radius: 20px;
            padding: 30px;
            background-color: white;
        }

        .history-header,
        .history-item {
            display: grid;
            grid-template-columns: 1fr 2fr 2fr;
            align-items: center;
            padding: 15px 10px;
        }

        .history-header {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: bold;
        }

        .history-item {
            color: var(--secondary-color);
            font-size: 22px;
            font-weight: 600;
        }

        .history-item:not(:last-child) {
            border-bottom: 2px solid #ddd;
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

            .history-header,
            .history-item {
                font-size: 16px;
            }

            .history-section {
                width: 95%;
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
            <a href="dashboard.php">Home</a>
            <!-- menandai halaman yang sedang dibuka -->
            <a href="riwayat.php" class="active">Riwayat</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- history -->
    <div class="history-section">
        <!-- judul -->
        <h1 class="history-title">
            Cek riwayat peminjamanmu disini
        </h1>

        <div class="history-box">

            <!-- header -->
            <div class="history-header">
                <div>ID</div>
                <div>Laboratorium</div>
                <div>Timestamp</div>
            </div>

            <!-- data -->
            <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                <div class="history-item">
                    <!-- menampilkan isi data -->
                    <div><?= $data['id']; ?></div>

                    <div>
                        <?= htmlspecialchars($data['nama_lab']); ?>
                    </div>

                    <div>
                        <?= date('d/m/Y', strtotime($data['tanggal'])); ?>
                        <?= substr($data['waktu'], 0, 5); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>