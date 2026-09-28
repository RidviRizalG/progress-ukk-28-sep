<?php

class Koneksi
{
    private $host = "127.0.0.1";
    private $username = "root";
    private $pass = "";
    private $db = "sarana_prasaranarpl4";
    private $ports = [3306, 3307];

    public $koneksi;

    public function __construct()
    {
        $lastError = null;

        foreach ($this->ports as $port) {
            $this->koneksi = mysqli_connect(
                $this->host,
                $this->username,
                $this->pass,
                $this->db,
                $port
            );

            if ($this->koneksi) {
                break;
            }

            $lastError = mysqli_connect_error();
        }

        if (!$this->koneksi) {
            die("Koneksi ke database gagal. Pastikan MySQL XAMPP aktif pada port 3306/3307. Error: " . $lastError);
        }

        mysqli_set_charset($this->koneksi, "utf8mb4");
    }
}

$koneksi = new Koneksi();

$conn = $koneksi->koneksi;
?>