<?php
session_start();
require 'koneksi.php';

// mengecek tombol register dipencet
if (isset($_POST['register'])) {
    // mengambil data form
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // validasi username, strlen() hitung jumlah karakter
    if(strlen($username) > 20) {
        $_SESSION['error'] = "Username max 20 karakter!";
        header("Location: register.php");
        exit;
    } 

    // validasi password
    if(strlen($password) < 6) {
        $_SESSION['error'] = "Password min 6 karakter";
        header("Location: register.php");
        exit;
    }

    // cek apakah email sdh pernah digunakan
    $cek = mysqli_query($konek, "SELECT * FROM users 
            WHERE email = '$email'");
    // kalo email sudah pernah dipake
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = "Email sudah digunakan!";
        header("Location: register.php");
        exit;
    } else {
        // mengacak password sebelum dimasukan ke database
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // menyimpan data baru setelah dilakukan pengecekan
        $query = "INSERT INTO users(email, name, password) 
                VALUES('$email', '$username', '$hash')";
        // menjalankan $query
        if (mysqli_query($konek, $query)) {
            $_SESSION['success'] = "Registrasi berhasil! Silahkan login.";
            header("Location: login.php");
            exit;
        } else {
            // kalo Gagal
            $_SESSION['error'] = "Terjadi kesalahan!";
            header("Location: register.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary-color: #4a169f;
            --secondary-color: #725aca;
            --link-color: #ffb99c;
            --text-color: #f5f5f5;
            --highligh-color: #3d3d3d;
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

        .container-login {
            width: 100%;
            height: 100vh;
            display: flex;
        }

        .left-side {
            width: 50%;
            height: 100vh;

            background-image: url(img/bg.png);
            background-position: center;
            background-size: 80%;
            background-repeat: no-repeat;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            text-align: center;
            color: white;
        }

        .right-side {
            width: 50%;
            height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background-color: var(--text-color);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .title h1 {
            font-size: 42px;
            font-weight: bold;
            color: var(--secondary-color);

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .title p {
            color: var(--secondary-color);
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 24px;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-control {
            height: 55px;
            border-radius: 12px;
            border-color: var(--secondary-color);
            border-width: 3px;
        }

        .form-floating label {
            color: var(--highligh-color);
        }

        .btn-primary {
            height: 48px;
            border-radius: 12px;
            background-color: var(--secondary-color);
            border-color:var(--primary-color) ;
            border-width: 3px;
            color: var(--link-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--link-color);
        }

        .register {
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--highligh-color);
        }

        .register a {
            text-decoration: none;
            color: var(--link-color);
        }

    </style>
</head>

<body>
    <!-- yang membungkus seluruh halaman register -->
    <div class="container-login">

        <!-- LEFT seluruh bagian kiri -->
        <div class="left-side">

            <!-- lapisan transaparan diatas background -->
            <div class="overlay"></div>

        </div>

        <!-- RIGHT seluruh bagian kanan -->
        <div class="right-side">

            <!-- yang membungkus isi form -->
            <div class="form-container">

                <div class="title">
                    <!-- mengecek apakah ada pesan error dr session -->
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        // supaya pesan hilang begitu di refresh
                        unset($_SESSION['error']);
                    }
                    //  untuk yang berhasil
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    ?>

                    <!-- untuk judul -->
                    <h1>REGISTER</h1>
                    <p>Mulai ajukan peminjaman lab</p>
                </div>

                <!-- form registrasi -->
                <form action="" method="POST">

                    <!-- yang buat kotak input melayang -->
                    <div class="form-floating mb-3">
                        <!-- input email, wajib diisi (require), for sama id harus sam -->
                        <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email"
                            required>
                        <label for="floatingEmail"><b>Email</b></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="floatingUsername"
                            placeholder="Username" maxlength="20" required>
                        <label for="floatingUsername"><b>Username</b></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPassword"
                            placeholder="Password" minlength="6" required>
                        <label for="floatingPassword"><b>Password</b></label>
                    </div>

                    <!-- tombol regist -->
                    <div class="register">
                        <button type="submit" name="register" class="btn btn-primary w-100">
                            <b>Buat Akun</b>
                        </button>
                    </div>

                </form>

                <div class="register">
                    <!-- tombol login -->
                    <p><b>Sudah punya akun? <a href="login.php">Login</a></b></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>