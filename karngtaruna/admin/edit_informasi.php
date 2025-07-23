<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

if(!isset($_GET['id'])){ header("location: kelola_informasi.php"); exit; }
$id = intval($_GET['id']);

// Proses form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_halaman = $_POST['judul_halaman'];
    $konten = $_POST['konten'];
    $sql = "UPDATE informasi SET judul_halaman = ?, konten = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $judul_halaman, $konten, $id);
    if(mysqli_stmt_execute($stmt)){
        header("location: kelola_informasi.php?status=edited");
        exit;
    }
}

// Ambil data halaman saat ini
$sql_select = "SELECT * FROM informasi WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$info = mysqli_fetch_assoc($result);
if(!$info){ header("location: kelola_informasi.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Halaman Informasi</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">
<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-file-pen"></i> Edit Halaman Informasi</h1>
        <a href="kelola_informasi.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    </div>
    <div class="form-card" style="max-width: 800px; margin: 20px auto;">
        <form action="edit_informasi.php?id=<?php echo $id; ?>" method="post" class="admin-form">
            <div class="form-group">
                <label for="judul_halaman">Judul Halaman</label>
                <input type="text" id="judul_halaman" name="judul_halaman" value="<?php echo htmlspecialchars($info['judul_halaman']); ?>" required>
            </div>
            <div class="form-group">
                <label for="konten">Isi Konten</label>
                <textarea id="konten" name="konten" rows="12" required><?php echo htmlspecialchars($info['konten']); ?></textarea>
            </div>
            <button type="submit" class="btn-primary-admin">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>