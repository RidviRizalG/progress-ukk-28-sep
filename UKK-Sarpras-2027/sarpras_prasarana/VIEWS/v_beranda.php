<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Siswa - Pengaduan Sarana Prasarana</title>
    <link rel="stylesheet" href="../CSS/01-warna-dan-font.css">
    <link rel="stylesheet" href="../CSS/02-navbar-atas.css">
    <link rel="stylesheet" href="../CSS/03-form-dan-tombol.css">
    <link rel="stylesheet" href="../CSS/04-tabel-dan-badge.css">
    <link rel="stylesheet" href="../CSS/05-admin-dan-dashboard.css">
</head>
<body class="dashboard-page">
    <div class="navbar">
        <div class="brand">
            <img class="logo" src="../ASSETS/logo_sekolah_temagelap.png" alt="Logo Sekolah">
            <span>Aplikasi Pengaduan Sarpras</span>
        </div>
        <div class="user-info">
            <span class="role-name">Siswa: <?= htmlspecialchars($nama_siswa ?? 'Siswa') ?></span>
            <small>(<?= htmlspecialchars($kelas ?? '-') ?>)</small>
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Ganti tema"></button>
            <a class="btn-logout" href="../CONTROLLERS/c_logout.php" title="Logout">
                <img src="../ASSETS/logout.png" alt="Logout">
            </a>
        </div>
    </div>

    <main class="container">
        <section class="dashboard-hero student-dashboard-hero">
            <div class="dashboard-hero-content">
                <img class="dashboard-hero-logo" src="../ASSETS/logo_sekolah.png" alt="Logo Sekolah">
                <h2>Selamat datang di Aplikasi Pengaduan Sarana Prasarana</h2>
                <h2>SMK Hunter x Hunter</h2>
                <div class="dashboard-name"><?= htmlspecialchars($nama_siswa ?? 'Siswa') ?></div>
                <p class="student-welcome-text">
                    Sampaikan aspirasi dan pengaduanmu untuk membantu menciptakan lingkungan sekolah yang lebih nyaman dan baik.
                </p>
            </div>
        </section>

        <section class="dashboard-nav-grid student-dashboard-nav" aria-label="Navigasi siswa">
            <a class="dashboard-link student-dashboard-link" href="../CONTROLLERS/c_beranda.php?page=form">
                <img src="../ASSETS/lakukan_pengaduan.png" alt="">
                <div class="student-link-text">
                    <span class="student-link-title">Lakukan Pengaduan</span>
                    <small>Sampaikan laporan mengenai kerusakan atau kebutuhan sarana dan prasarana sekolah.</small>
                </div>
            </a>
            <a class="dashboard-link student-dashboard-link" href="../CONTROLLERS/c_beranda.php?page=histori">
                <img src="../ASSETS/histori_pengaduan.png" alt="">
                <div class="student-link-text">
                    <span class="student-link-title">Histori Pengaduan</span>
                    <small>Lihat kembali pengaduan yang telah kamu kirim, status, dan tanggapan dari petugas.</small>
                </div>
            </a>
        </section>
    </main>
    <script src="../JS/theme.js"></script>
</body>
</html>
