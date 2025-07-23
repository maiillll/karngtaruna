<?php
require '../includes/db_connect.php';
// Cek jika admin belum login, tendang ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("location: login.php");
    exit;
}

// Ambil data statistik dari database
$result_foto = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM galeri");
$jumlah_foto = mysqli_fetch_assoc($result_foto)['jumlah'];

$result_acara = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM acara");
$jumlah_acara = mysqli_fetch_assoc($result_acara)['jumlah'];

$result_profil = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM profil");
$jumlah_profil = mysqli_fetch_assoc($result_profil)['jumlah'];

$result_info = mysqli_query($conn, "SELECT COUNT(id) AS jumlah FROM informasi");
$jumlah_info = mysqli_fetch_assoc($result_info)['jumlah'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">

    <div class="admin-dashboard">
        <div class="dashboard-header">
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
        <p>Kelola konten website Anda melalui kartu di bawah ini.</p>

        <div class="dashboard-grid">
            <a href="kelola_foto.php" class="dashboard-card">
                <i class="fa-solid fa-images card-icon" style="background-color: #e0f7fa;"></i>
                <div class="card-content">
                    <h3><?php echo $jumlah_foto; ?></h3>
                    <p>Total Foto</p>
                </div>
            </a>

            <a href="kelola_acara.php" class="dashboard-card">
                <i class="fa-solid fa-calendar-days card-icon" style="background-color: #fff3e0;"></i>
                <div class="card-content">
                    <h3><?php echo $jumlah_acara; ?></h3>
                    <p>Total Acara</p>
                </div>
            </a>

            <a href="kelola_profil.php" class="dashboard-card">
                <i class="fa-solid fa-users card-icon" style="background-color: #e8f5e9;"></i>
                <div class="card-content">
                    <h3><?php echo $jumlah_profil; ?></h3>
                    <p>Anggota Tim</p>
                </div>
            </a>
            
            <a href="kelola_informasi.php" class="dashboard-card">
                <i class="fa-solid fa-file-alt card-icon" style="background-color: #f3e5f5;"></i>
                <div class="card-content">
                    <h3><?php echo $jumlah_info; ?></h3>
                    <p>Halaman Info</p>
                </div>
            </a>
        </div>
    </div>

</body>
</html>