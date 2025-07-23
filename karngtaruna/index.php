<?php
// Hubungkan ke database. File ini harus ada di folder /includes
require 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karang Taruna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/css/lightgallery.min.css">
</head>
<body>

    <header class="header">
        <div class="container navbar">
            <a href="#" class="logo">KarangTaruna</a>
            <ul class="nav-links">
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#informasi">Informasi</a></li>
                <li><a href="#organisasi">Struktur Organisasi</a></li>
                <li><a href="#acara">Acara</a></li>
                <li><a href="#galeri">Galeri</a></li>
                <li><a href="admin/login.php">Admin</a></li>
            </ul>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-content animated">
                <div class="hero-text">
                    <h1>Berkarya dan Berdampak untuk Masyarakat.</h1>
                    <p>Temukan berbagai informasi, acara menarik, dan galeri kegiatan kami. Bergabunglah dengan komunitas kami sekarang!</p>
                    <div class="hero-buttons">
                        <a href="#informasi" class="btn btn-primary">Selengkapnya</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://i.imgur.com/G5GYJ3J.png" alt="Kegiatan Karang Taruna">
                </div>
            </div>
        </section>

        <section id="informasi">
            <div class="container info-content animated">
                <div class="info-image">
                    <img src="https://i.imgur.com/8aA2OaI.png" alt="Tentang Kami">
                </div>
                <div class="info-text">
                    <?php
                    $sql_info = "SELECT judul_halaman, konten FROM informasi WHERE judul_halaman = 'Tentang Kami' LIMIT 1";
                    $result_info = mysqli_query($conn, $sql_info);
                    if ($info = mysqli_fetch_assoc($result_info)) {
                        echo '<h2>' . htmlspecialchars($info['judul_halaman']) . '</h2>';
                        echo '<p>' . nl2br(htmlspecialchars($info['konten'])) . '</p>';
                    } else {
                        echo '<h2>Informasi</h2>';
                        echo '<p>Konten untuk halaman ini belum diisi. Silakan tambahkan melalui halaman admin.</p>';
                    }
                    ?>
                    <a href="" class="btn btn-primary">Kelola Informasi</a>
                </div>
            </div>
        </section>

        <section id="organisasi">
            <div class="container">
                <h2 class="section-title animated">Struktur Organisasi</h2>
                <div class="org-chart">
                    <?php
                    // Ambil data Pimpinan Tertinggi (CEO/Ketua Umum)
                    $sql_ceo = "SELECT nama, jabatan, foto_profil FROM profil WHERE jabatan = 'CEO' OR jabatan = 'Ketua Umum' LIMIT 1";
                    $result_ceo = mysqli_query($conn, $sql_ceo);
                    
                    if ($ceo = mysqli_fetch_assoc($result_ceo)) {
                    ?>
                        <div class="org-level level-ceo">
                            <div class="org-node animated">
                                <div class="org-card">
                                    <img src="uploads/<?php echo htmlspecialchars($ceo['foto_profil']); ?>" alt="<?php echo htmlspecialchars($ceo['nama']); ?>">
                                    <p class="name"><?php echo htmlspecialchars($ceo['nama']); ?></p>
                                    <p class="title"><?php echo htmlspecialchars($ceo['jabatan']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }

                    // Ambil data Kepala Divisi/Manajer
                    $sql_managers = "SELECT nama, jabatan, foto_profil FROM profil WHERE jabatan != 'CEO' AND jabatan != 'Ketua Umum' ORDER BY id ASC";
                    $result_managers = mysqli_query($conn, $sql_managers);
                    
                    if (mysqli_num_rows($result_managers) > 0) {
                    ?>
                        <div class="org-level level-managers">
                            <?php
                            while ($manager = mysqli_fetch_assoc($result_managers)) {
                            ?>
                                <div class="org-node animated">
                                    <div class="org-card">
                                        <img src="uploads/<?php echo htmlspecialchars($manager['foto_profil']); ?>" alt="<?php echo htmlspecialchars($manager['nama']); ?>">
                                        <p class="name"><?php echo htmlspecialchars($manager['nama']); ?></p>
                                        <p class="title"><?php echo htmlspecialchars($manager['jabatan']); ?></p>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </section>

        <section id="acara">
            <div class="container">
                <h2 class="section-title animated">Acara Terbaru</h2>
                <div class="event-grid">
                    <?php
                    $sql_acara = "SELECT nama_acara, tanggal_acara, deskripsi FROM acara ORDER BY tanggal_acara DESC LIMIT 3";
                    $result_acara = mysqli_query($conn, $sql_acara);
                    if (mysqli_num_rows($result_acara) > 0) {
                        while ($acara = mysqli_fetch_assoc($result_acara)) {
                           echo '<div class="event-card animated" data-tilt>';
                            echo '<h3>' . htmlspecialchars($acara['nama_acara']) . '</h3>';
                            echo '<p class="date">' . date('d F Y', strtotime($acara['tanggal_acara'])) . '</p>';
                            echo '<p>' . htmlspecialchars(substr($acara['deskripsi'], 0, 100)) . '...</p>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="text-align:center;">Belum ada acara yang dijadwalkan.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>

        <section id="galeri">
            <div class="container">
                <h2 class="section-title animated">Galeri Foto</h2>
                <div class="gallery-grid">
                     <?php
                    $sql_galeri = "SELECT judul, nama_file_gambar FROM galeri ORDER BY id DESC LIMIT 8";
                    $result_galeri = mysqli_query($conn, $sql_galeri);
                    
                    if (mysqli_num_rows($result_galeri) > 0) {
                        while ($foto = mysqli_fetch_assoc($result_galeri)) {
                            echo '<div class="gallery-item animated" data-tilt>';
                            echo '  <img src="uploads/' . htmlspecialchars($foto['nama_file_gambar']) . '" alt="' . htmlspecialchars($foto['judul']) . '">';
                            echo '  <h4>' . htmlspecialchars($foto['judul']) . '</h4>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="text-align:center;">Belum ada foto di galeri.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> By Mail Gledek.</p>
        </div>
    </footer>

    <script>
        const animatedElements = document.querySelectorAll('.animated');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });
        animatedElements.forEach(el => {
            observer.observe(el);
        });
        
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.2/lightgallery.min.js"></script>
<script>
    // Inisialisasi lightGallery pada galeri kita
    lightGallery(document.getElementById('galeri-grid'), {
        selector: '.gallery-item-link',
        download: false // Menonaktifkan tombol download
    });
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
  
        
        VanillaTilt.init(document.querySelectorAll(".event-card, .gallery-item"), {
            max: 15,    
            speed: 400, 
            glare: true, 
            "max-glare": 0.5
        });
  
</script>
</body>
</html>