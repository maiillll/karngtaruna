informasi<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Daftar Acara</h1>
    <div class="event-list">
        <?php
        require 'includes/db_connect.php';
        $sql = "SELECT * FROM acara ORDER BY tanggal_acara DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="event-item">';
                echo '<h3>' . htmlspecialchars($row['nama_acara']) . '</h3>';
                echo '<p><strong>Tanggal:</strong> ' . date('d F Y', strtotime($row['tanggal_acara'])) . '</p>';
                echo '<p>' . nl2br(htmlspecialchars($row['deskripsi'])) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>Belum ada acara yang dijadwalkan.</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>