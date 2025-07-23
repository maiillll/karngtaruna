<?php
// includes/header.php
require 'db_connect.php'; // Memastikan session dimulai dari sini
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Dinamis</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <nav class="container">
            <a href="index.php">Home</a>
            <a href="galeri.php">Galeri</a>
            <a href="acara.php">Acara</a>
            </nav>
    </header>