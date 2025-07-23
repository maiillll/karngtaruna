<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

$pesan = '';
$pesan_tipe = '';

// Proses form tambah acara
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_acara'])) {
    $nama_acara = $_POST['nama_acara'];
    $tanggal_acara = $_POST['tanggal_acara'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "INSERT INTO acara (nama_acara, tanggal_acara, deskripsi) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nama_acara, $tanggal_acara, $deskripsi);
    
    if(mysqli_stmt_execute($stmt)){
        $pesan = "Acara berhasil ditambahkan.";
        $pesan_tipe = "sukses";
    } else {
        $pesan = "Gagal menambahkan acara.";
        $pesan_tipe = "error";
    }
}

// Ambil pesan status dari URL
if(isset($_GET['status'])){
    if($_GET['status'] == 'deleted'){
        $pesan = "Acara berhasil dihapus.";
        $pesan_tipe = "sukses";
    }
    if($_GET['status'] == 'edited'){
        $pesan = "Acara berhasil diperbarui.";
        $pesan_tipe = "sukses";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Acara</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-content-container">
    <div class="page-header">
        <h1><i class="fa-solid fa-calendar-days"></i> Kelola Acara</h1>
        <a href="index.php" class="btn-secondary-admin"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <?php if(!empty($pesan)): ?>
        <div class="pesan-feedback <?php echo $pesan_tipe; ?>"><?php echo $pesan; ?></div>
    <?php endif; ?>

    <div class="content-grid">
        <div class="form-card">
            <h3><i class="fa-solid fa-plus"></i> Tambah Acara Baru</h3>
            <form action="kelola_acara.php" method="post" class="admin-form">
                <input type="hidden" name="tambah_acara" value="1">
                <div class="form-group">
                    <label for="nama_acara">Nama Acara</label>
                    <input type="text" id="nama_acara" name="nama_acara" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_acara">Tanggal Acara</label>
                    <input type="date" id="tanggal_acara" name="tanggal_acara" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-primary-admin">Tambah Acara</button>
            </form>
        </div>

        <div class="list-card">
            <h3><i class="fa-solid fa-list"></i> Daftar Acara Saat Ini</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Acara</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_select = "SELECT * FROM acara ORDER BY tanggal_acara DESC";
                        $result = mysqli_query($conn, $sql_select);
                        if(mysqli_num_rows($result) > 0){
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['nama_acara']) . '</td>';
                                echo '<td>' . date('d M Y', strtotime($row['tanggal_acara'])) . '</td>';
                                echo '<td>';
                                echo '<a href="edit_acara.php?id=' . $row['id'] . '" class="btn-aksi btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                                echo '<a href="hapus_acara.php?id=' . $row['id'] . '" class="btn-aksi btn-hapus" onclick="return confirm(\'Anda yakin ingin menghapus acara ini?\');"><i class="fa-solid fa-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">Belum ada acara yang ditambahkan.</td></tr>';
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