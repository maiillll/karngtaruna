<?php
require 'includes/db_connect.php';

// Jika tidak ada ID di URL, kembalikan ke halaman utama
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data profil berdasarkan ID
$sql = "SELECT * FROM profil WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$profil = mysqli_fetch_assoc($result);

// Jika profil dengan ID tersebut tidak ditemukan, kembalikan ke halaman utama
if (!$profil) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil <?php echo htmlspecialchars($profil['nama']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body"> <div class="profile-detail-card">
        <a href="index.php#organisasi" class="btn-back">Kembali</a>
        <img src="uploads/<?php echo htmlspecialchars($profil['foto_profil']); ?>" alt="Foto <?php echo htmlspecialchars($profil['nama']); ?>">
        <h1><?php echo htmlspecialchars($profil['nama']); ?></h1>
        <h2><?php echo htmlspecialchars($profil['jabatan']); ?></h2>
        <p class="bio">
            <?php 
            // Menampilkan bio atau teks default jika bio kosong
            echo !empty($profil['bio']) ? nl2br(htmlspecialchars($profil['bio'])) : 'Informasi biografi untuk anggota ini belum tersedia.'; 
            ?>
        </p>
    </div>

</body>
</html>