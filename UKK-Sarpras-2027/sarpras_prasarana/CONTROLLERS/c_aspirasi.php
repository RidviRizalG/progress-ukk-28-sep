<?php
session_start();
require_once '../MODELS/m_koneksi.php';
require_once '../MODELS/m_aspirasi.php';

$modelAspirasi = new M_Aspirasi($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['aksi'] ?? '') === 'tambah') {
    $nis = $_SESSION['nis'] ?? '';
    $id_kategori = trim($_POST['id_kategori'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    $ket = trim($_POST['ket'] ?? '');

    if ($nis === '' || $id_kategori === '' || $lokasi === '' || $ket === '') {
        header('Location: c_beranda.php?page=form&status=data_tidak_lengkap');
        exit();
    }

    if ($modelAspirasi->tambahAspirasi($nis, $id_kategori, $lokasi, $ket)) {
        header('Location: c_beranda.php?page=dashboard&status=berhasil');
        exit();
    }

    header('Location: c_beranda.php?page=form&status=gagal');
    exit();
}

header('Location: c_beranda.php?page=form');
exit();
?>
