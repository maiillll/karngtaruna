<?php
// includes/db_connect.php

$host = 'localhost';
$user = 'root';
$pass = ''; // Kosongkan jika default XAMPP
$db_name = 'db_website';

$conn = mysqli_connect($host, $user, $pass, $db_name);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memulai session untuk login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>