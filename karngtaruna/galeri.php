<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Galeri Foto</h1>
    <div class="gallery-grid">
        <?php
        require 'includes/db_connect.php';
        $sql = "SELECT * FROM galeri ORDER BY tgl_upload DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="gallery-item">';
                echo '<img src="uploads/' . htmlspecialchars($row['nama_file_gambar']) . '" alt="' . htmlspecialchars($row['judul']) . '">';
                echo '<h3>' . htmlspecialchars($row['judul']) . '</h3>';
                echo '</div>';
            }
        } else {
            echo '<p>Belum ada foto yang diupload.</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>