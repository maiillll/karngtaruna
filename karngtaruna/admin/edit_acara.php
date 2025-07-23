<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

if(!isset($_GET['id'])){ header("location: kelola_acara.php"); exit; }
$id = intval($_GET['id']);

// Proses form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_acara = $_POST['nama_acara'];
    $tanggal_acara = $_POST['tanggal_acara'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "UPDATE acara SET nama_acara = ?, tanggal_acara = ?, deskripsi = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $nama_acara, $tanggal_acara, $deskripsi, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("location: kelola_acara.php?status=edited");
        exit;
    }
}

// Ambil data acara saat ini
$sql_select = "SELECT * FROM acara WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$acara = mysqli_fetch_assoc($result);
if(!$acara){ header("location: kelola_acara.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Acara</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">
<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-calendar-pen"></i> Edit Acara</h1>
        <a href="kelola_acara.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Batal</a>
    </div>
    <div class="form-card" style="max-width: 700px; margin: 20px auto;">
        <form action="edit_acara.php?id=<?php echo $id; ?>" method="post" class="admin-form">
            <div class="form-group">
                <label for="nama_acara">Nama Acara</label>
                <input type="text" id="nama_acara" name="nama_acara" value="<?php echo htmlspecialchars($acara['nama_acara']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_acara">Tanggal Acara</label>
                <input type="date" id="tanggal_acara" name="tanggal_acara" value="<?php echo htmlspecialchars($acara['tanggal_acara']); ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" required><?php echo htmlspecialchars($acara['deskripsi']); ?></textarea>
            </div>
            <button type="submit" class="btn-primary-admin">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>