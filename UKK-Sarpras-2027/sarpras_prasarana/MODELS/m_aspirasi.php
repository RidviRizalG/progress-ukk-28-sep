<?php
class M_Aspirasi {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function countAllAspirasi() {
        $query = "SELECT COUNT(*) AS total FROM input_aspirasi";
        $result = mysqli_query($this->db, $query);
        $row = mysqli_fetch_assoc($result);
        return (int)($row['total'] ?? 0);
    }

    public function getAspirasiByNis($nis) {
        // Ikat NIS sebagai string agar konsisten dengan nilai sesi/login.
        $query = "SELECT i.id_input, i.lokasi, i.ket, i.tgl_pelaporan,
                         k.ket_kategori,
                         COALESCE(a.status, 'Menunggu') AS status,
                         a.feedback
                  FROM input_aspirasi i
                  LEFT JOIN kategori k ON i.id_kategori = k.id_kategori
                  LEFT JOIN aspirasi a ON i.id_input = a.id_input
                  WHERE CAST(i.nis AS CHAR) = ?
                  ORDER BY i.tgl_pelaporan DESC, i.id_input DESC";
        $stmt = mysqli_prepare($this->db, $query);
        if (!$stmt) {
            return [];
        }
        $nis = (string)$nis;
        mysqli_stmt_bind_param($stmt, 's', $nis);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return [];
        }
        $result = mysqli_stmt_get_result($stmt);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $data;
    }

    /**
     * Detail satu pengaduan milik siswa tertentu (khusus halaman detail siswa).
     * NIS ikut di WHERE sehingga siswa tidak bisa membuka pengaduan siswa lain
     * hanya dengan mengganti id di URL.
     */
    public function getAspirasiByIdForNis($id_input, $nis) {
        $query = "SELECT i.id_input, i.lokasi, i.ket, i.tgl_pelaporan,
                         k.ket_kategori,
                         COALESCE(a.status, 'Menunggu') AS status,
                         a.feedback,
                         a.updated_at AS tgl_feedback
                  FROM input_aspirasi i
                  LEFT JOIN kategori k ON i.id_kategori = k.id_kategori
                  LEFT JOIN aspirasi a ON i.id_input = a.id_input
                  WHERE i.id_input = ? AND CAST(i.nis AS CHAR) = ?
                  LIMIT 1";
        $stmt = mysqli_prepare($this->db, $query);
        if (!$stmt) {
            return null;
        }
        $id_input = (int)$id_input;
        $nis = (string)$nis;
        mysqli_stmt_bind_param($stmt, 'is', $id_input, $nis);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return null;
        }
        $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
        mysqli_stmt_close($stmt);
        return $row;
    }

    public function getAllAspirasi($limit = null, $offset = 0) {
        $query = "SELECT i.id_input, i.nis, i.lokasi, i.ket, i.tgl_pelaporan,
                         k.ket_kategori,
                         COALESCE(a.status, 'Menunggu') AS status,
                         a.feedback,
                         s.nama_siswa, s.kelas
                  FROM input_aspirasi i
                  JOIN kategori k ON i.id_kategori = k.id_kategori
                  JOIN siswa s ON i.nis = s.nis
                  LEFT JOIN aspirasi a ON i.id_input = a.id_input
                  ORDER BY CASE COALESCE(a.status, 'Menunggu')
                                WHEN 'Menunggu' THEN 1
                                WHEN 'Proses' THEN 2
                                WHEN 'Selesai' THEN 3
                                ELSE 4
                           END ASC,
                           i.tgl_pelaporan DESC";

        if ($limit !== null) {
            $query .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $result = mysqli_query($this->db, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function updateAspirasi($id_input, $status, $feedback, $id_admin) {
        $id_input = (int)$id_input;
        $status = mysqli_real_escape_string($this->db, $status);
        $feedback = mysqli_real_escape_string($this->db, $feedback);
        $id_admin = (int)$id_admin;

        $cek = mysqli_query($this->db, "SELECT id_aspirasi FROM aspirasi WHERE id_input = '$id_input' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE aspirasi SET status = '$status', feedback = '$feedback', id_admin = '$id_admin', updated_at = NOW() WHERE id_input = '$id_input'";
            return mysqli_query($this->db, $query);
        }

        $row = mysqli_fetch_assoc(mysqli_query($this->db, "SELECT id_kategori FROM input_aspirasi WHERE id_input = '$id_input' LIMIT 1"));
        $id_kategori = $row['id_kategori'] ?? 0;

        $query = "INSERT INTO aspirasi (id_input, status, id_kategori, feedback, id_admin, updated_at)
                  VALUES ('$id_input', '$status', '$id_kategori', '$feedback', '$id_admin', NOW())";
        return mysqli_query($this->db, $query);
    }

    public function tambahAspirasi($nis, $id_kategori, $lokasi, $ket) {
        $stmt = mysqli_prepare(
            $this->db,
            'INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket, tgl_pelaporan) VALUES (?, ?, ?, ?, NOW())'
        );
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'iiss', $nis, $id_kategori, $lokasi, $ket);
        $berhasil = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $berhasil;
    }

    public function getKategori() {
        $query = "SELECT * FROM kategori";
        $result = mysqli_query($this->db, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
}
?>