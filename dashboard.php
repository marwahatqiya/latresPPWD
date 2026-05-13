<?php
session_start();
require "koneksi.php";

// mengecek user sudh login blm
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

// menentukan halaman aktif
$currentPage = "home";

// variabel filter lab
$filterLab = "";

// mengecek filter dr url
if (isset($_GET['lab'])) {
    $filterLab = $_GET['lab'];
}

// array waktu
$waktu = [
    "08:00",
    "10:30",
    "13:00",
    "15:30",
];

//search
$search = "";
// mengambil keyword search
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// ambil data laboratorium berdasarkan pencarian
$querySQL = "SELECT * FROM laboratorium WHERE nama_lab LIKE '%$search%'";

// filter lab, .= artinya menambahkan string ke var sebelumnya
if ($filterLab != "") {
    $querySQL .= " AND id = '$filterLab'";
}

// menjalankan query lab
$queryLab = mysqli_query($konek, $querySQL);

// ambil peminjaman hari ini
$queryPinjam = mysqli_query($konek, "SELECT laboratorium_id, LEFT(waktu, 5) as waktu FROM peminjaman WHERE tanggal = CURDATE()");

// simpan data peminjaman ke array
$dataPinjam = [];

// loop data peminjaman
while ($pinjam = mysqli_fetch_assoc($queryPinjam)) {
    $dataPinjam[$pinjam['laboratorium_id']][] = $pinjam['waktu'];
}

// ambil 5 ajuan baru
$queryAjuan = mysqli_query(
    $konek,
    "SELECT peminjaman.*, laboratorium.nama_lab 
FROM peminjaman 
JOIN laboratorium 
ON peminjaman.laboratorium_id = laboratorium.id 
ORDER BY created_at DESC LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #4a169f;
            --secondary-color: #725aca;
            --link-color: #ffb99c;
            --text-color: #f5f5f5;
            --card-color: #7a6bd6;
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

        .container-custom {
            width: 100%;
            padding: 30px 70px;
        }

        /* navbar */

        .navbar-custom {
            width: 100%;

            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 30px;
        }

        .logo {
            width: 55px;
            height: 55px;
            border-radius: 50%;

            display: flex;
            justify-content: center;
            align-items: center;

            color: var(--link-color);
            font-size: 28px;
            font-weight: bold;

            background-color: var(--secondary-color);
            border: 3px solid var(--link-color);
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

            transition: 0.3s;
        }

        .menu a:hover {
            color: var(--primary-color);
        }

        .active-menu {
            color: var(--primary-color) !important;
        }

        /* search */

        .search-section {
            width: 100%;

            display: flex;
            gap: 15px;

            margin-bottom: 40px;
        }

        .search-input {
            flex: 1;
            height: 55px;

            border: 4px solid var(--secondary-color);
            border-radius: 15px;

            padding: 0 20px;

            font-size: 20px;
            font-weight: 600;

            outline: none;

            background-color: #f3c9c0;
            color: var(--primary-color);
        }

        .search-input::placeholder {
            color: #6d5d5d;
        }

        .filter-btn {
            height: 55px;

            padding: 0 25px;

            border: 4px solid var(--secondary-color);
            border-radius: 15px;

            background-color: #f3c9c0;

            color: var(--primary-color);
            font-size: 20px;
            font-weight: bold;
        }

        .filter-btn:hover {
            background-color: #efb7aa;
        }

        .dropdown-menu {
            border-radius: 15px;
            border: 3px solid var(--secondary-color);
            overflow: hidden;
        }

        .dropdown-item {
            font-weight: 600;
            color: var(--primary-color);
        }

        .dropdown-item:hover {
            background-color: #f3c9c0;
        }

        /* title */

        .section-title {
            text-align: center;
            color: var(--primary-color);
            font-size: 42px;
            font-weight: bold;

            margin-bottom: 40px;
        }

        .ajuan-title {
            color: var(--primary-color);
            font-size: 36px;
            font-weight: bold;
            border-radius: 12px;

            margin-top: 50px;
            margin-bottom: 30px;
        }

        /* card lab */

        .lab-container {
            width: 100%;

            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .lab-card {
            width: 320px;

            background-color: var(--card-color);

            border: 4px solid var(--primary-color);
            border-radius: 18px;

            padding: 25px;

            color: var(--link-color);
        }

        .lab-header {
            display: flex;
            align-items: center;
            gap: 12px;

            margin-bottom: 20px;
        }

        .lab-header i {
            font-size: 24px;
        }

        .lab-header h5 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .time-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .time-box {
            padding: 6px 12px;

            border: 2px solid var(--link-color);
            border-radius: 8px;

            font-size: 14px;
            font-weight: bold;

            color: white;
            background-color: transparent;
        }

        /* ajuan */

        .ajuan-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            border-radius: 12px;
            
        }

        .ajuan-card {
            width: 320px;

            background-color: var(--card-color);

            border: 4px solid var(--primary-color);
            border-radius: 18px;

            padding: 25px;

            color: var(--link-color);
        }

        .ajuan-card h5 {
            font-size: 28px;
            font-weight: bold;

            margin-bottom: 15px;
        }

        .ajuan-card p {
            font-size: 18px;
            font-weight: bold;

            margin-bottom: 10px;
        }

        .button-group {
            display: flex;
            gap: 15px;

            margin-top: 20px;
        }

        .btn-hapus,
        .btn-edit {
            flex: 1;

            height: 50px;

            border-radius: 30px;

            font-size: 20px;
            font-weight: bold;

            display: flex;
            justify-content: center;
            align-items: center;

            text-decoration: none;
        }

        .btn-hapus {
            border: 2px solid white;
            color: white;
            background-color: transparent;
        }

        .btn-edit {
            background-color: #f3b4ad;
            color: var(--primary-color);
            border: none;
        }

        /* tombol tambah */

        .btn-add {
            width: 75px;
            height: 75px;

            border-radius: 50%;

            background-color: #f3c9c0;

            border: 4px solid var(--primary-color);

            position: fixed;
            bottom: 30px;
            right: 30px;

            display: flex;
            justify-content: center;
            align-items: center;

            text-decoration: none;

            color: var(--primary-color);
            font-size: 42px;
            font-weight: bold;
        }

        /* responsive */

        @media (max-width: 768px) {

            .container-custom {
                padding: 20px;
            }

            .navbar-custom {
                flex-direction: column;
                gap: 20px;
            }

            .menu {
                gap: 20px;
            }

            .search-section {
                flex-direction: column;
            }

            .section-title {
                font-size: 30px;
            }

            .ajuan-title {
                font-size: 28px;
            }

            .lab-card,
            .ajuan-card {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="container-custom">

        <!-- navbar -->
        <div class="navbar-custom">

            <!-- logo -->
            <div class="logo">
                C
            </div>

            <!-- menu -->
            <div class="menu">
                <!-- menu aktif -->
                <a href="dashboard.php" class="<?= ($currentPage == 'home') ? 'active-menu' : ''; ?>">Home</a>
                <a href="riwayat.php" class="<?= ($currentPage == 'riwayat') ? 'active-menu' : ''; ?>">Riwayat</a>
                <a href="logout.php">Logout</a>
            </div>

        </div>

        <!-- form search -->
        <form method="GET" class="search-section">

            <!-- tempat user ngetik -->
            <input type="text" name="search" class="search-input" placeholder="Cari laboratorium"
                value="<?= $search ?>">

            <!-- dropdown filter -->
            <div class="dropdown">

                <!-- tombol filter -->
                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Filter
                </button>

                <!-- isi kalo tombol filter dipencet -->
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="dashboard.php">
                            Semua Lab
                        </a>
                    </li>

                    <!-- mengambil data nama lab -->
                    <?php
                    $queryFilter = mysqli_query($konek, "SELECT * FROM laboratorium");
                    // ngeloop nama lab di database jadi ga ditulis satu-satu
                    while ($filter = mysqli_fetch_assoc($queryFilter)) {
                        ?>
                        <!-- nampilin nama lab buat di filter -->
                        <li>
                            <a class="dropdown-item" href="dashboard.php?lab= <?= $filter['id']; ?> ">
                                <?= htmlspecialchars($filter['nama_lab']); ?>
                            </a>
                        </li>
                    <?php } ?>

                </ul>

            </div>

        </form>

        <!-- lab tersedia -->
        <h3 class="section-title">
            Laboratorium Tersedia Hari Ini
        </h3>

        <div class="lab-container">
            <!-- menampilkan data lab hasil filter -->
            <?php while ($lab = mysqli_fetch_assoc($queryLab)) { ?>

                <?php
                // mengecek jadwal dipinjam, berdasarkan jam
                $dipinjam = $dataPinjam[$lab['id']] ?? [];

                // mengecek jadwal tersedia, dgn cara membandingkan
                $tersedia = array_diff($waktu, $dipinjam);

                // jika jadwal penuh semua, ga ada yg di tampilin, lanjut ke loop berikutnya
                if (count($tersedia) == 0) {
                    continue;
                }
                ?>

                <div class="lab-card">

                    <div class="lab-header">
                        <i class="bi bi-display"></i>
                        <h5>
                            <!-- menampilkan nama lab -->
                            <?= htmlspecialchars($lab['nama_lab']) ?>
                        </h5>
                    </div>

                    <div class="time-group">

                        <!-- menampilkan jam tersedia -->
                        <?php foreach ($tersedia as $jam) { ?>

                            <div class="time-box">
                                <?= $jam ?>
                            </div>

                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        </div>

        <!-- ajuan terbaru -->
        <h3 class="ajuan-title">
            Ajuan Terbaru
        </h3>

        <div class="ajuan-container">
            <?php while ($ajuan = mysqli_fetch_assoc($queryAjuan)) { ?>

                <div class="ajuan-card">

                    <div class="lab-header">

                        <i class="bi bi-display"></i>

                        <h5>
                            <?= htmlspecialchars($ajuan['nama_lab']) ?>
                        </h5>

                    </div>

                    <p>
                        <?= $ajuan['tanggal'] ?>
                    </p>

                    <div class="time-group">

                        <div class="time-box">
                            <?= $ajuan['waktu'] ?>
                        </div>

                    </div>

                    <div class="button-group">

                        <!-- tombol hapus -->
                        <a href="delete.php?id=<?= $ajuan['id'] ?>" class="btn-hapus">
                            HAPUS
                        </a>

                        <!-- tombol edit -->
                        <a href="edit.php?id=<?= $ajuan['id'] ?>" class="btn-edit">
                            EDIT
                        </a>

                    </div>

                </div>

            <?php } ?>


            <!-- tombol tambah -->
            <a href="add.php" class="btn-add">
                +
            </a>

        </div>

    </div>

</body>

</html>