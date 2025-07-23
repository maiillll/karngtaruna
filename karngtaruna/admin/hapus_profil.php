<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

if(!isset($_GET['id'])){ header("location: kelola_profil.php"); exit; }
$id = intval($_GET['id']);

// Ambil nama file untuk dihapus dari folder
$sql_get_file = "SELECT foto_profil FROM profil WHERE id = ?";
$stmt_get_file = mysqli_prepare($conn, $sql_get_file);
mysqli_stmt_bind_param($stmt_get_file, "i", $id);
mysqli_stmt_execute($stmt_get_file);
$result = mysqli_stmt_get_result($stmt_get_file);
if ($row = mysqli_fetch_assoc($result)) {
    $file_path = '../uploads/' . $row['foto_profil'];
    if (file_exists($file_path)) { unlink($file_path); }
}

// Hapus record dari database
$sql_delete = "DELETE FROM profil WHERE id = ?";
$stmt_delete = mysqli_prepare($conn, $sql_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $id);
mysqli_stmt_execute($stmt_delete);

header("location: kelola_profil.php?status=deleted");
exit;
?>