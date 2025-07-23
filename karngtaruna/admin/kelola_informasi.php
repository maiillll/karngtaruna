<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

$pesan = '';
$pesan_tipe = '';
// Proses form tambah halaman
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_info'])) {
    $judul_halaman = $_POST['judul_halaman'];
    $konten = $_POST['konten'];

    $sql = "INSERT INTO informasi (judul_halaman, konten) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $judul_halaman, $konten);
    
    if(mysqli_stmt_execute($stmt)){
        $pesan = "Halaman informasi baru berhasil ditambahkan.";
        $pesan_tipe = "sukses";
    } else {
        $pesan = "Gagal menambahkan halaman.";
        $pesan_tipe = "error";
    }
}

// Ambil pesan status dari URL
if(isset($_GET['status'])){
    if($_GET['status'] == 'deleted'){
        $pesan = "Halaman berhasil dihapus.";
        $pesan_tipe = "sukses";
    }
    if($_GET['status'] == 'edited'){
        $pesan = "Halaman berhasil diperbarui.";
        $pesan_tipe = "sukses";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Halaman Informasi</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-file-alt"></i> Kelola Halaman Informasi</h1>
        <a href="index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <?php if(!empty($pesan)): ?>
        <div class="pesan-feedback <?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <div class="content-grid">
        <div class="form-card">
            <h3><i class="fa-solid fa-plus"></i> Tambah Halaman Baru</h3>
            <form action="kelola_informasi.php" method="post" class="admin-form">
                <input type="hidden" name="tambah_info" value="1">
                <div class="form-group">
                    <label for="judul_halaman">Judul Halaman (cth: Tentang Kami, Visi Misi)</label>
                    <input type="text" id="judul_halaman" name="judul_halaman" required>
                </div>
                <div class="form-group">
                    <label for="konten">Isi Konten</label>
                    <textarea id="konten" name="konten" rows="8" required></textarea>
                </div>
                <button type="submit" class="btn-primary-admin">Tambah Halaman</button>
            </form>
        </div>

        <div class="list-card">
            <h3><i class="fa-solid fa-list"></i> Daftar Halaman Saat Ini</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Judul Halaman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_select = "SELECT * FROM informasi ORDER BY id DESC";
                        $result = mysqli_query($conn, $sql_select);
                        if(mysqli_num_rows($result) > 0){
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['judul_halaman']) . '</td>';
                                echo '<td>';
                                echo '<a href="edit_informasi.php?id=' . $row['id'] . '" class="btn-aksi btn-edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>';
                                echo '<a href="hapus_informasi.php?id=' . $row['id'] . '" class="btn-aksi btn-hapus" onclick="return confirm(\'Anda yakin ingin menghapus halaman ini?\');"><i class="fa-solid fa-trash"></i> Hapus</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="2">Belum ada halaman yang ditambahkan.</td></tr>';
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