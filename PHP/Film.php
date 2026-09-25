<?php

class Film {         // class dasar (level 1)
    private $id;     // id unik film
    private $judul;  // judul film
    private $harga;  // harga tiket

    // __construct: Konstruktor class Film dengan nilai awal opsional.
    public function __construct($id = 0, $judul = "", $harga = 0) {  // konstruktor (nilai awal)
        $this->id = $id;                                             // simpan id
        $this->judul = $judul;                                       // simpan judul
        $this->harga = $harga;                                       // simpan harga
    }

    // ===== getter =====
    // getId: Mengembalikan nilai id film.
    public function getId()    { return $this->id; }     // ambil id
    // getJudul: Mengembalikan nilai judul film.
    public function getJudul() { return $this->judul; }  // ambil judul
    // getHarga: Mengembalikan nilai harga film.
    public function getHarga() { return $this->harga; }  // ambil harga

    // ===== setter =====
    // setId: Mengubah nilai id film.
    public function setId($id)         { $this->id = $id; }        // ubah id
    // setJudul: Mengubah nilai judul film.
    public function setJudul($judul)   { $this->judul = $judul; }  // ubah judul
    // setHarga: Mengubah nilai harga film.
    public function setHarga($harga)   { $this->harga = $harga; }  // ubah harga

    // getData: Mengembalikan data milik class Film dalam 3 kolom.
    public function getData() {                                        // kembalikan data inti Film
        return [$this->id, $this->judul, formatRupiah($this->harga)];  // harga dibungkus format Rupiah
    }
}
