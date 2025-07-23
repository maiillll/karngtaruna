<?php
// includes/footer.php
?>
    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Nama Website Anda. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php
// Menutup koneksi database jika ada
if (isset($conn)) {
    mysqli_close($conn);
}
?>