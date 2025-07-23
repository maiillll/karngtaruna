<?php
require '../includes/db_connect.php';
if (!isset($_SESSION['admin_logged_in'])) { header("location: login.php"); exit; }

if(!isset($_GET['id'])){
    header("location: kelola_acara.php");
    exit;
}

$id = intval($_GET['id']);

// Hapus record dari database
$sql_delete = "DELETE FROM acara WHERE id = ?";
$stmt_delete = mysqli_prepare($conn, $sql_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $id);
mysqli_stmt_execute($stmt_delete);

header("location: kelola_acara.php?status=deleted");
exit;
?>