<?php

class FilmBioskop extends Film {  // turunan langsung dari Film
    private $durasiMenit;         // durasi tayang dalam menit
    private $genre;               // daftar genre
    private $sutradara;           // nama sutradara

    // __construct: Konstruktor class FilmBioskop dengan nilai awal opsional.
    public function __construct($id = 0, $judul = "", $harga = 0, $durasiMenit = 0,
                                $genre = [], $sutradara = "") {
        parent::__construct($id, $judul, $harga);  // panggil konstruktor induk
        $this->durasiMenit = $durasiMenit;         // simpan durasi
        $this->genre = $genre;                     // simpan daftar genre
        $this->sutradara = $sutradara;             // simpan sutradara
    }

    // ===== getter =====
    // getDurasiMenit: Mengembalikan nilai durasi tayang dalam menit.
    public function getDurasiMenit() { return $this->durasiMenit; }  // ambil durasi
    // getGenre: Mengembalikan daftar genre film.
    public function getGenre()       { return $this->genre; }        // ambil daftar genre
    // getSutradara: Mengembalikan nama sutradara film.
    public function getSutradara()   { return $this->sutradara; }    // ambil sutradara

    // ===== setter =====
    // setDurasiMenit: Mengubah nilai durasi tayang dalam menit.
    public function setDurasiMenit($durasiMenit) { $this->durasiMenit = $durasiMenit; }  // ubah durasi
    // setGenre: Mengubah daftar genre film.
    public function setGenre($genre)             { $this->genre = $genre; }              // ubah daftar genre
    // setSutradara: Mengubah nama sutradara film.
    public function setSutradara($sutradara)     { $this->sutradara = $sutradara; }      // ubah sutradara

    /** Menggabungkan daftar genre menjadi satu string (dipisah koma). */
    // getGenreText: Menggabungkan daftar genre menjadi satu string.
    public function getGenreText() {         // gabungkan genre menjadi satu teks
        return implode(", ", $this->genre);  // pisahkan dengan koma
    }

    /**
     * Mengembalikan data Film + atribut bioskop (6 kolom).
     */
    // getData: Mengembalikan data Film dan atribut bioskop dalam 6 kolom.
    public function getData() {                      // data film + atribut bioskop
        $data = parent::getData();                   // mulai dari data induk
        $data[] = formatDurasi($this->durasiMenit);  // tambahkan durasi terformat
        $data[] = $this->getGenreText();             // tambahkan daftar genre
        $data[] = $this->sutradara;                  // tambahkan sutradara
        return $data;                                // kembalikan hasil
    }
}
