<?php

class Database {
    protected $conn;

    public function __construct() {
        $host     = 'localhost';
        $user     = 'root';
        $password = '';
        $dbname   = 'DB_SIMULASI_PBO_KELAS_NamaLengkap';

        $this->conn = new mysqli($host, $user, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }
}