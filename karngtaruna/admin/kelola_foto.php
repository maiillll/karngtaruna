<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

$pesan = '';
// Proses form jika ada data yang dikirim untuk menambah foto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["gambar"])) {
    $judul = $_POST['judul'];
    $gambar = $_FILES['gambar'];

    $target_dir = "../uploads/";
    // Beri nama unik untuk mencegah file tertimpa
    $nama_file = time() . '_' . basename($gambar["name"]);
    $target_file = $target_dir . $nama_file;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek validasi gambar
    $check = getimagesize($gambar["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($gambar["tmp_name"], $target_file)) {
            // Simpan ke database
            $sql = "INSERT INTO galeri (judul, nama_file_gambar) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $judul, $nama_file);
            if(mysqli_stmt_execute($stmt)){
                $pesan = "Foto berhasil diupload.";
                $pesan_tipe = "sukses";
            } else {
                $pesan = "Gagal menyimpan data ke database.";
                $pesan_tipe = "error";
            }
        } else {
            $pesan = "Maaf, terjadi error saat mengupload file.";
            $pesan_tipe = "error";
        }
    } else {
        $pesan = "File yang diupload bukan gambar.";
        $pesan_tipe = "error";
    }
}

// Ambil pesan dari URL (setelah edit/hapus)
if(isset($_GET['status'])){
    if($_GET['status'] == 'deleted'){
        $pesan = "Foto berhasil dihapus.";
        $pesan_tipe = "sukses";
    }
    if($_GET['status'] == 'edited'){
        $pesan = "Judul foto berhasil diperbarui.";
        $pesan_tipe = "sukses";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri Foto</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-images"></i> Kelola Galeri Foto</h1>
        <a href="index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <?php if(!empty($pesan)): ?>
        <div class="pesan-feedback <?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <div class="content-grid">
        <div class="form-card">
            <h3><i class="fa-solid fa-plus"></i> Tambah Foto Baru</h3>
            <form action="kelola_foto.php" method="post" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="judul">Judul Foto</label>
                    <input type="text" id="judul" name="judul" required>
                </div>
                <div class="form-group">
                    <label for="gambar">Pilih File Gambar</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*" required>
                </div>
                <button type="submit" class="btn-primary-admin">Upload Foto</button>
            </form>
        </div>

        <div class="list-card">
            <h3><i class="fa-solid fa-list"></i> Daftar Foto Saat Ini</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_select = "SELECT * FROM galeri ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql_select);
                        if(mysqli_num_rows($result) > 0){
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td><img src="../uploads/' . htmlspecialchars($row['nama_file_gambar']) . '" alt="preview" class="table-preview"></td>';
                                echo '<td>' . htmlspecialchars($row['judul']) . '</td>';
                                echo '<td>';
                                echo '<a href="edit_foto.php?id=' . $row['id'] . '" class="btn-aksi btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                                echo '<a href="hapus_foto.php?id=' . $row['id'] . '" class="btn-aksi btn-hapus" onclick="return confirm(\'Anda yakin ingin menghapus foto ini?\');"><i class="fa-solid fa-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">Belum ada foto yang diupload.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>