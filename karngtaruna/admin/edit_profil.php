<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

if(!isset($_GET['id'])){ header("location: kelola_profil.php"); exit; }
$id = intval($_GET['id']);

// Proses form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $bio = $_POST['bio'];
    $foto_lama = $_POST['foto_lama'];
    $nama_file = $foto_lama;

    // Jika ada file foto baru diupload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_baru = $_FILES['foto'];
        $target_dir = "../uploads/";
        $nama_file = time() . '_' . basename($foto_baru["name"]);
        
        // Hapus foto lama
        if(file_exists($target_dir . $foto_lama)) { unlink($target_dir . $foto_lama); }
        
        // Pindahkan foto baru
        move_uploaded_file($foto_baru["tmp_name"], $target_dir . $nama_file);
    }

    $sql = "UPDATE profil SET nama=?, jabatan=?, bio=?, foto_profil=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $jabatan, $bio, $nama_file, $id);
    if(mysqli_stmt_execute($stmt)){
        header("location: kelola_profil.php?status=edited");
        exit;
    }
}

// Ambil data profil saat ini
$sql_select = "SELECT * FROM profil WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$profil = mysqli_fetch_assoc($result);
if(!$profil){ header("location: kelola_profil.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Profil</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">
<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-user-pen"></i> Edit Profil</h1>
        <a href="kelola_profil.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    </div>
    <div class="form-card" style="max-width: 600px; margin: 20px auto;">
        <form action="edit_profil.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" class="admin-form">
            <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($profil['foto_profil']); ?>">
            <div class="form-group">
                <label>Foto Saat Ini</label>
                <img src="../uploads/<?php echo htmlspecialchars($profil['foto_profil']); ?>" class="table-preview profile-preview">
            </div>
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($profil['nama']); ?>" required>
            </div>
             <div class="form-group">
                <label for="jabatan">Jabatan</label>
                <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($profil['jabatan']); ?>" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio Singkat</label>
                <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($profil['bio']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="foto">Ganti Foto (Opsional)</label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>
            <button type="submit" class="btn-primary-admin">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>