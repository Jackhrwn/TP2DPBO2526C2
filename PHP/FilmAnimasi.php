<?php

class FilmAnimasi extends FilmBioskop {  // turunan dari FilmBioskop
    private $studio;                     // studio pembuat
    private $teknikAnimasi;              // teknik animasi
    private $ratingUsia;                 // rating usia penonton
    private $fotoProduk;                 // path gambar poster (khusus PHP)

    // __construct: Konstruktor class FilmAnimasi dengan nilai awal opsional.
    public function __construct($id = 0, $judul = "", $harga = 0, $durasiMenit = 0, $genre = [],
                                $sutradara = "", $studio = "", $teknikAnimasi = "",
                                $ratingUsia = "", $fotoProduk = "") {
        // panggil konstruktor induk
        parent::__construct($id, $judul, $harga, $durasiMenit, $genre, $sutradara);
        $this->studio = $studio;                // simpan studio
        $this->teknikAnimasi = $teknikAnimasi;  // simpan teknik animasi
        $this->ratingUsia = $ratingUsia;        // simpan rating usia
        $this->fotoProduk = $fotoProduk;        // simpan path foto produk
    }

    // ===== getter =====
    // getStudio: Mengembalikan nama studio pembuat film animasi.
    public function getStudio()        { return $this->studio; }         // ambil studio
    // getTeknikAnimasi: Mengembalikan teknik animasi yang digunakan.
    public function getTeknikAnimasi() { return $this->teknikAnimasi; }  // ambil teknik animasi
    // getRatingUsia: Mengembalikan rating usia penonton.
    public function getRatingUsia()    { return $this->ratingUsia; }     // ambil rating usia
    // getFotoProduk: Mengembalikan path gambar poster film.
    public function getFotoProduk()    { return $this->fotoProduk; }     // ambil path foto produk

    // ===== setter =====
    // setStudio: Mengubah nama studio pembuat film animasi.
    public function setStudio($studio)             { $this->studio = $studio; }          // ubah studio
    // setTeknikAnimasi: Mengubah teknik animasi yang digunakan.
    public function setTeknikAnimasi($teknik)      { $this->teknikAnimasi = $teknik; }   // ubah teknik animasi
    // setRatingUsia: Mengubah rating usia penonton.
    public function setRatingUsia($ratingUsia)     { $this->ratingUsia = $ratingUsia; }  // ubah rating usia
    // setFotoProduk: Mengubah path gambar poster film.
    public function setFotoProduk($fotoProduk)     { $this->fotoProduk = $fotoProduk; }  // ubah foto produk

    // getData: Mengembalikan seluruh data film animasi (10 kolom).
    public function getData() {          // seluruh data film (10 kolom)
        $data = parent::getData();       // mulai dari data induk
        $data[] = $this->studio;         // tambahkan studio
        $data[] = $this->teknikAnimasi;  // tambahkan teknik animasi
        $data[] = $this->ratingUsia;     // tambahkan rating usia
        $data[] = $this->fotoProduk;     // tambahkan path foto produk
        return $data;                    // kembalikan hasil
    }
}
