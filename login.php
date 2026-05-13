<?php
session_start();
require "koneksi.php";

// mengecek tombol login ditekan
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // mencari username di database
    $query = mysqli_query($konek, "SELECT * FROM users WHERE name = '$username'");
    if (mysqli_num_rows($query) > 0) {
        // mengambil data user
        $data = mysqli_fetch_assoc($query);
        // mengecek apakah password sama
        if (password_verify($password, $data['password'])) {
            // jika password benar
            $_SESSION['id'] = $data['id'];
            $_SESSION['username'] = $data['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            // jika password salah
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .right-side {
            width: 50%;
            height: 100vh;

            background-image: url(img/bg.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: 80%;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .overlay {
            text-align: center;
            color: white;
        }

        .left-side {
            width: 50%;
            height: 100vh;

            background-color: var(--text-color);

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .title h1 {
            display: flex;
            justify-content: center;
            align-items: center;

            color: var(--secondary-color);
            font-size: 42px;
            font-weight: bold;
        }

        .title p {
            display: flex;
            justify-content: center;
            align-items: center;

            color: var(--secondary-color);
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-control {
            height: 55px;
            border-radius: 12px;
            border-color: var(--primary-color);
            border-width: 3px;
        }

        .form-floating label {
            color: var(--highligh-color);
        }

        .btn-primary {
            height: 48px;
            border-radius: 12px;
            border-color: var(--primary-color);
            border-width: 3px;
            background-color: var(--secondary-color);
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
            color: var(--link-color);
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- membungkus semua halaman login -->
    <div class="container-login">

        <!-- membungkus semua halaman kiri -->
        <div class="left-side">

            <!-- membungkus bagian form -->
            <div class="form-container">
                <div class="title">
                    <?php
                    //cek apakah ada pesan error dr session
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        // supaya pesan hilang setelah direset
                        unset($_SESSION['error']);
                    }
                    // untuk pesan berhasil
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    ?>
                    <!-- judul -->
                    <h1><b>Login</b></h1>
                    <p><b>Selamat datang kembali</b></p>
                </div>

                <!-- form -->
                <form action="" method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" name="username" class="form-control" id="floatingUsername"
                            placeholder="Username" required>
                        <label for="floatingUsername"><b>Username</b></label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPassword"
                            placeholder="Password" required>
                        <label for="floatingPassword"><b>Password</b></label>
                    </div>

                    <!-- tombol login -->
                    <button type="submit" name="login" class="btn btn-primary w-100"><b>Masuk</b></button>
                </form>

                <div class="register">
                    <p><b>Belum punya akun <a href="register.php">Register</a></b></p>
                </div>
            </div>
        </div>

        <!-- membungkus semua halaman kanan -->
        <div class="right-side">
            <div class="overlay"></div>
        </div>


    </div>
</body>

</html>