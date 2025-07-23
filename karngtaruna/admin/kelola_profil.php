<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

$pesan = '';
$pesan_tipe = '';
// Proses form jika ada data yang dikirim untuk menambah profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_profil'])) {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $bio = $_POST['bio'];
    $foto = $_FILES['foto'];

    $target_dir = "../uploads/";
    $nama_file = time() . '_' . basename($foto["name"]);
    $target_file = $target_dir . $nama_file;
    
    $check = getimagesize($foto["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($foto["tmp_name"], $target_file)) {
            $sql = "INSERT INTO profil (nama, jabatan, bio, foto_profil) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $nama, $jabatan, $bio, $nama_file);
            if(mysqli_stmt_execute($stmt)){
                $pesan = "Profil baru berhasil ditambahkan.";
                $pesan_tipe = "sukses";
            } else {
                $pesan = "Gagal menyimpan data ke database.";
                $pesan_tipe = "error";
            }
        } else {
            $pesan = "Error saat mengupload file.";
            $pesan_tipe = "error";
        }
    } else {
        $pesan = "File yang diupload bukan gambar.";
        $pesan_tipe = "error";
    }
}

// Ambil pesan status dari URL
if(isset($_GET['status'])){
    if($_GET['status'] == 'deleted'){
        $pesan = "Profil berhasil dihapus.";
        $pesan_tipe = "sukses";
    }
    if($_GET['status'] == 'edited'){
        $pesan = "Profil berhasil diperbarui.";
        $pesan_tipe = "sukses";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil Tim</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-users"></i> Kelola Profil Tim</h1>
        <a href="index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <?php if(!empty($pesan)): ?>
        <div class="pesan-feedback <?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <div class="content-grid">
        <div class="form-card">
            <h3><i class="fa-solid fa-user-plus"></i> Tambah Profil Baru</h3>
            <form action="kelola_profil.php" method="post" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="tambah_profil" value="1">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan (cth: Ketua, Sekretaris)</label>
                    <input type="text" id="jabatan" name="jabatan" required>
                </div>
                <div class="form-group">
                    <label for="bio">Bio Singkat</label>
                    <textarea id="bio" name="bio" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="foto">Foto Profil</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>
                </div>
                <button type="submit" class="btn-primary-admin">Tambah Profil</button>
            </form>
        </div>

        <div class="list-card">
            <h3><i class="fa-solid fa-list"></i> Daftar Profil Saat Ini</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_select = "SELECT * FROM profil ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql_select);
                        if(mysqli_num_rows($result) > 0){
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td><img src="../uploads/' . htmlspecialchars($row['foto_profil']) . '" class="table-preview profile-preview"></td>';
                                echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['jabatan']) . '</td>';
                                echo '<td>';
                                echo '<a href="edit_profil.php?id=' . $row['id'] . '" class="btn-aksi btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                                echo '<a href="hapus_profil.php?id=' . $row['id'] . '" class="btn-aksi btn-hapus" onclick="return confirm(\'Anda yakin ingin menghapus profil ini?\');"><i class="fa-solid fa-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">Belum ada profil yang ditambahkan.</td></tr>';
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