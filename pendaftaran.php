<?php

require_once __DIR__ . '/koneksi/database.php';

abstract class Pendaftaran extends Database {

    // Atribut terenkapsulasi (protected) - dipetakan dari kolom tabel_pendaftaran
    protected $id_pendaftaran;
    protected $nama_calon;
    protected $asal_sekolah;
    protected $nilai_ujian;
    protected $biayaPendaftaranDasar;

    public function __construct($id_pendaftaran, $nama_calon, $asal_sekolah, $nilai_ujian, $biayaPendaftaranDasar) {
        parent::__construct(); // panggil koneksi dari Database

        $this->id_pendaftaran       = $id_pendaftaran;
        $this->nama_calon           = $nama_calon;
        $this->asal_sekolah         = $asal_sekolah;
        $this->nilai_ujian          = $nilai_ujian;
        $this->biayaPendaftaranDasar = $biayaPendaftaranDasar;
    }

    // Metode abstrak - wajib diimplementasikan di setiap kelas anak
    abstract public function hitungTotalBiaya();
    abstract public function tampilkanInfoJalur();
}