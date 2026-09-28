<?php
// Khusus fitur Admin: memperbarui status dan feedback aspirasi.
session_start();
require_once '../MODELS/m_koneksi.php';
require_once '../MODELS/m_aspirasi.php';

if (($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../VIEWS/v_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: c_admin.php?page=histori');
    exit();
}

$modelAspirasi = new M_Aspirasi($conn);
$id_input = (int)($_POST['id_input'] ?? 0);
$status = trim($_POST['status'] ?? 'Menunggu');
$feedback = trim($_POST['feedback'] ?? '');
$id_admin = (int)($_SESSION['id_admin'] ?? 0);

if ($id_input > 0 && in_array($status, ['Menunggu', 'Proses', 'Selesai'], true)) {
    $modelAspirasi->updateAspirasi($id_input, $status, $feedback, $id_admin);
}

header('Location: c_admin.php?page=histori');
exit();
?>
