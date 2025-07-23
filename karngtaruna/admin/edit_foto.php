<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

if(!isset($_GET['id'])){
    header("location: kelola_foto.php");
    exit;
}

$id = intval($_GET['id']);

// Proses form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_baru = $_POST['judul'];
    $sql_update = "UPDATE galeri SET judul = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt, "si", $judul_baru, $id);
    if(mysqli_stmt_execute($stmt)){
        header("location: kelola_foto.php?status=edited");
        exit;
    }
}

// Ambil data foto saat ini
$sql_select = "SELECT * FROM galeri WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$foto = mysqli_fetch_assoc($result);
if(!$foto){
    header("location: kelola_foto.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Judul Foto</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">
<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square"></i> Edit Judul Foto</h1>
        <a href="kelola_foto.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Batal & Kembali</a>
    </div>
    <div class="form-card" style="max-width: 600px; margin: 20px auto;">
        <form action="edit_foto.php?id=<?php echo $id; ?>" method="post" class="admin-form">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="../uploads/<?php echo htmlspecialchars($foto['nama_file_gambar']); ?>" alt="preview" style="max-width: 100%; border-radius: 8px;">
            </div>
            <div class="form-group">
                <label for="judul">Judul Foto</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($foto['judul']); ?>" required>
            </div>
            <button type="submit" class="btn-primary-admin">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>