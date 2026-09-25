<?php

require_once "Film.php";         // muat class Film
require_once "FilmBioskop.php";  // muat class FilmBioskop
require_once "FilmAnimasi.php";  // muat class FilmAnimasi

// Sesi dipakai agar data film bertahan antar permintaan.
session_start();


// formatRupiah: Mengubah format angka menjadi Rupiah.
function formatRupiah($harga) {                        // format angka menjadi Rupiah
    return "Rp " . number_format($harga, 0, ",", "."); // ribuan dipisah titik, tanpa desimal
}

// formatDurasi: Mengubah durasi menit menjadi format jam dan menit.
function formatDurasi($menit) {                        // ubah durasi menit jadi teks jam & menit
    $jam  = intdiv($menit, 60);                        // bagian jam (pembulatan ke bawah)
    $sisa = $menit % 60;                               // sisa menit
    if ($jam > 0) {                                    // jika sudah 1 jam atau lebih
        $hasil = "$jam jam";                           // buat teks bagian jam
        if ($sisa > 0) $hasil .= " $sisa menit";       // tambahkan sisa menit
        return $hasil;                                 // kembalikan hasil gabungan
    }
    return "$menit menit";                             // kurang dari satu jam
}

// e: Mengaman-kan teks sebelum dicetak ke HTML (cegah XSS).
function e($teks) {                                                    // amankan output
    return htmlspecialchars((string)$teks, ENT_QUOTES, "UTF-8");       // ubah karakter berbahaya
}

// ====== Nama kolom tabel (semua atribut dari 3 class + fotoProduk) ======
$KOLOM = [
    "ID", "Judul", "Harga", "Durasi", "Genre",
    "Sutradara", "Studio", "Teknik Animasi", "Rating Usia", "Foto Produk"
];

// ====== Daftar opsi teknik & rating ======
$OPSI_TEKNIK = ["2D", "3D", "Stop Motion", "CGI", "Motion Capture",
                "Animatronik", "GoMotion", "Cut Out", "Rotoscope",
                "Plastinasi", "Pixilasi", "Grafik Gerak"];
$OPSI_RATING = ["SU", "13+", "17+", "21+"];

// ====== Inisialisasi data ======
if (!isset($_SESSION["biasa"]) || isset($_GET["reset"])) {
    $_SESSION["biasa"]   = [];
    $_SESSION["bioskop"] = [];
    $_SESSION["animasi"] = [];
    $_SESSION["biasa"][]   = new Film(1, "Sejarah", 25000);                                                  // film biasa: data awal 1
    $_SESSION["bioskop"][] = new FilmBioskop(2, "Kimi No Nawa", 45000, 115, ["Drama", "Romance"], "Sari");   // data awal 2 (bioskop)
    $_SESSION["bioskop"][] = new FilmBioskop(3, "Ghost In The Cell", 40000, 95, ["Horor"], "Rina");          // data awal 3 (bioskop)
    $_SESSION["animasi"][] = new FilmAnimasi(4, "Kimetsu No Yaiba", 50000, 105, ["Aksi", "Petualangan"], "Andi",
                                             "StudioBiru", "3D", "SU", "foto_produk/Kimetsu No Yaiba.png");     // data awal 4 (animasi)
    $_SESSION["animasi"][] = new FilmAnimasi(5, "Dragon Slayer", 52000, 98, ["Fantasi"], "Dewi",
                                             "StudioUngu", "2D", "SU", "foto_produk/Dragon Slayer.png");         // data awal 5 (animasi)

    if (isset($_GET["reset"])) {                                 // user menekan "Muat ulang data awal"?
        unset($_SESSION["flash"], $_SESSION["nilai"]);           // buang pesan/form tersimpan
        header("Location: index.php");                           // bersihkan URL ?reset=1
        exit;                                                    // hentikan agar tidak dua kali reset
    }
}

// referensi wadah data pada sesi
$daftarBiasa   = &$_SESSION["biasa"];    // wadah film biasa   (level 1)
$daftarBioskop = &$_SESSION["bioskop"];  // wadah film bioskop (level 2)
$daftarAnimasi = &$_SESSION["animasi"];  // wadah film animasi (level 3)

// idTerpakai: Mengecek apakah suatu ID sudah dipakai di salah satu wadah.
function idTerpakai($id, $biasa, $bioskop, $animasi) {   // cek ketersediaan ID
    foreach ($biasa as $o)   if ($o->getId() == $id) return true;   // cocok di wadah biasa?
    foreach ($bioskop as $o) if ($o->getId() == $id) return true;   // cocok di wadah bioskop?
    foreach ($animasi as $o) if ($o->getId() == $id) return true;   // cocok di wadah animasi?
    return false;                                                   // ID belum dipakai
}

// ====== Nilai input terakhir (untuk mengisi ulang form saat error) ======
$nilai = [
    "tipe"      => 1,
    "id"        => "",
    "judul"     => "",
    "harga"     => "",
    "durasi"    => "",
    "genre"     => "",
    "sutradara" => "",
    "studio"    => "",
    "teknik"    => "2D",
    "rating"    => "SU",
    "foto"      => "",
];

// ====== Pesan sekali tampil (flash) ======
// Pesan hasil dipindah lewat sesi + redirect (pola PRG) agar pesan TIDAK muncul lagi
// saat halaman di-refresh, dan agar refresh juga tidak menyimpan data dua kali.
$pesan = "";     // pesan hasil (sukses / error)
$gagal = false;  // penanda ada error
$errorsFlash = [];                                                // daftar error utk ditampilkan satu per satu
if (isset($_SESSION["flash"])) {                                  // ada pesan tersimpan?
    $gagal       = $_SESSION["flash"]["gagal"];                   // ambil status gagal
    $pesan       = $gagal ? "" : $_SESSION["flash"]["pesan"];     // ambil pesan sukses
    $errorsFlash = $gagal ? (array)($_SESSION["flash"]["errors"] ?? []) : [];  // ambil daftar error
    $nilai       = isset($_SESSION["nilai"]) ? $_SESSION["nilai"] : $nilai;       // isi ulang form bila ada
    unset($_SESSION["flash"], $_SESSION["nilai"]);                // hapus agar sekali tampil
}

// ====== Proses form tambah data ======
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nilai["tipe"]      = isset($_POST["tipe"]) ? (int)$_POST["tipe"] : 1;
    $nilai["id"]        = trim($_POST["id"] ?? "");
    $nilai["judul"]     = trim($_POST["judul"] ?? "");
    $nilai["harga"]     = trim($_POST["harga"] ?? "");
    $nilai["durasi"]    = trim($_POST["durasi"] ?? "");
    $nilai["genre"]     = trim($_POST["genre"] ?? "");
    $nilai["sutradara"] = trim($_POST["sutradara"] ?? "");
    $nilai["studio"]    = trim($_POST["studio"] ?? "");
    $nilai["teknik"]    = trim($_POST["teknik"] ?? "");
    $nilai["rating"]    = trim($_POST["rating"] ?? "");
    $nilai["foto"]      = trim($_POST["foto"] ?? "");

    $errors   = [];   // daftar pesan kesalahan
    $idBaru   = 0;    // penampung id hasil validasi
    $hargaBaru = 0;   // penampung harga hasil validasi

    // ===== validasi ID =====
    if (!preg_match('/^[+-]?[0-9]+$/', $nilai["id"])) {                 // bukan angka?
        $errors[] = "ID — Input harus berupa angka! Silakan coba lagi.";
    } else {
        $idBaru = (int)$nilai["id"];                                   // ubah ke bilangan bulat
        if ($idBaru < 0) {                                             // id negatif ditolak
            $errors[] = "ID — ID tidak boleh negatif!";
        } elseif ($idBaru == 0) {                                      // id nol ditolak
            $errors[] = "ID — ID harus angka mulai dari 1!";
        } elseif (idTerpakai($idBaru, $daftarBiasa, $daftarBioskop, $daftarAnimasi)) {  // id duplikat
            $errors[] = "ID — ID sudah dipakai! Gunakan ID lain.";
        }
    }

    // ===== validasi judul & harga =====
    if ($nilai["judul"] === "") $errors[] = "Judul — Judul tidak boleh kosong!";
    if (!preg_match('/^[+-]?[0-9]+$/', $nilai["harga"])) {             // harga bukan angka?
        $errors[] = "Harga — harga harus berupa angka!";
    } else {
        $hargaBaru = (int)$nilai["harga"];
        if ($hargaBaru < 0) $errors[] = "Harga — harga tidak boleh negatif!";
    }

    // ===== validasi atribut bioskop (tipe 2 / 3) =====
    $durasiBaru = 0;   // durasi hasil validasi
    $genreList  = [];  // daftar genre hasil validasi
    if ($nilai["tipe"] >= 2) {
        if (!preg_match('/^[+-]?[0-9]+$/', $nilai["durasi"])) {        // durasi bukan angka?
            $errors[] = "Durasi — durasi harus berupa angka!";
        } else {
            $durasiBaru = (int)$nilai["durasi"];
            if ($durasiBaru < 0) $errors[] = "Durasi — durasi tidak boleh negatif!";
        }

        $genreGagal = false;                                           // penanda ada genre salah
        foreach (explode(",", $nilai["genre"]) as $g) {                // pisahkan antar koma
            $g = trim($g);                                             // buang spasi awal/akhir
            if ($g === "") continue;                                   // abaikan kosong
            if (!preg_match('/^[A-Z]/', $g)) {                         // harus diawali huruf besar?
                if (!$genreGagal) $errors[] = "Genre — huruf awal genre harus huruf besar!";
                $genreGagal = true;                                    // tandai ada yang salah
                continue;                                              // loncati genre bermasalah
            }
            $genreList[] = $g;                                         // simpan genre valid
        }
        if ($genreList === []) $errors[] = "Genre — minimal satu genre!";
    }

    // ===== validasi path foto produk (khusus tipe 3): tolak bila berisi link =====
    if ($nilai["tipe"] == 3) {
        $fotoLink = $nilai["foto"];
        $adaLink = strpos($fotoLink, "://") !== false                  // http:// https:// ftp:// data: dll
                || stripos($fotoLink, "www.") === 0                    // link yang diawali www.
                || stripos($fotoLink, "//") === 0;                     // jalur absolut di istem net
        if ($fotoLink !== "" && $adaLink) {
            $errors[] = "Foto produk — path tidak boleh berupa link/URL! (isi jalur file lokal)";
        }
    }

    // ===== simpan bila tidak ada kesalahan =====
    if ($errors === []) {                                              // semua valid?
        if ($nilai["tipe"] == 1) {                                     // tipe 1: film biasa
            // Film            : <id> <judul> <harga>
            $daftarBiasa[] = new Film($idBaru, $nilai["judul"], $hargaBaru);  // simpan objek Film
        } elseif ($nilai["tipe"] == 2) {                               // tipe 2: film bioskop
            // FilmBioskop   : <id> <judul> <harga> <durasi> <genre...> <sutradara>
            $daftarBioskop[] = new FilmBioskop($idBaru, $nilai["judul"], $hargaBaru,
                                               $durasiBaru, $genreList, $nilai["sutradara"]);  // simpan objek FilmBioskop
        } else {                                                       // tipe 3: film animasi
            // FilmAnimasi   : <id> <judul> <harga> <durasi> <genre...> <sutradara>
            //                  <studio> <teknik> <rating> <fotoProduk>
            $daftarAnimasi[] = new FilmAnimasi($idBaru, $nilai["judul"], $hargaBaru,
                                               $durasiBaru, $genreList, $nilai["sutradara"],
                                               $nilai["studio"], $nilai["teknik"],
                                               $nilai["rating"], $nilai["foto"]);  // simpan objek FilmAnimasi
        }
        $pesan = "Data berhasil ditambahkan!";                         // umpan balik sukses (hijau)
        $nilai = array_merge($nilai, ["id" => "", "judul" => "", "harga" => "",   // kosongkan form
                                      "durasi" => "", "genre" => "", "sutradara" => "",
                                      "studio" => "", "foto" => ""]);
    } else {
        $gagal = true;                                                 // tandai gagal
        $errors[] = "Periksa kembali isian form Anda.";                // penutup daftar error
    }

    // simpan pesan + isi form ke sesi, lalu alihkan ke halaman bersih (PRG)
    $_SESSION["flash"] = $gagal                                        // simpan tiap error terpisah
        ? ["gagal" => true, "errors" => $errors]
        : ["gagal" => false, "pesan" => $pesan];                       // atau simpan pesan sukses
    $_SESSION["nilai"] = $nilai;                                       // simpan ulang isi form
    header("Location: index.php");                                     // alihkan ke halaman bersih
    exit;                                                              // hentikan aksi selanjutnya
}

// ====== Siapkan data tabel gabungan (diratakan, urutkan per ID) ======
$jumlah   = count($daftarBiasa) + count($daftarBioskop) + count($daftarAnimasi);  // jumlah seluruh film
$barisData = [];                                                      // wadah baris-baris tabel
foreach ($daftarBiasa   as $o) {                                      // ambil baris film biasa
    $d = $o->getData();                                               // baris data film biasa
    $d = array_pad($d, count($KOLOM), "-");                           // isi kolom milik class lain dengan "-"
    $barisData[] = $d;                                                // simpan baris
}
foreach ($daftarBioskop as $o) {                                      // ambil baris film bioskop
    $d = $o->getData();                                               // baris data film bioskop
    $d = array_pad($d, count($KOLOM), "-");                           // isi kolom milik class lain dengan "-"
    $barisData[] = $d;                                                // simpan baris
}
foreach ($daftarAnimasi as $o) {                                      // ambil baris film animasi
    $d = $o->getData();                                               // baris data film animasi
    $d[9] = $o->getFotoProduk();                                      // kolom terakhir = path foto produk
    $d = array_pad($d, count($KOLOM), "-");                           // pastikan seluruh kolom terisi
    $barisData[] = $d;                                                // simpan baris
}
usort($barisData, function ($a, $b) { return $a[0] - $b[0]; });       // urutkan per ID menaik

$tipeTerpilih = $nilai["tipe"];  // tipe yang dipilih user (untuk form)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Film Bioskop &mdash; Multilevel Inheritance</title>
    <style>
        /* ====== gaya halaman web (menggantikan warna ANSI terminal) ====== */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", "Inter", Arial, sans-serif;
            background:
                radial-gradient(circle at 15% 0%, rgba(59,130,246,.18), transparent 45%),
                radial-gradient(circle at 85% 10%, rgba(139,92,246,.14), transparent 40%),
                linear-gradient(180deg, #f8fafc, #eef2f7);
            min-height: 100vh;
            color: #1f2937;
            padding: 30px 20px 60px;
        }
        .container { max-width: 1180px; margin: 0 auto; }

        /* ====== header ====== */
        .hero {
            background: linear-gradient(135deg, #1e3a8a, #2563eb 55%, #7c3aed);
            color: #fff;
            border-radius: 20px;
            padding: 30px 34px;
            box-shadow: 0 12px 30px rgba(37, 99, 235, .35);
            margin-bottom: 22px;
        }
        .hero h1 {
            font-size: 30px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 0 2px 6px rgba(0,0,0,.25);
        }
        .hero p {
            margin-top: 6px;
            font-size: 14px;
            color: #dbeafe;
        }
        .hero .jalur { margin-top: 14px; display: flex; flex-wrap: wrap; gap: 8px; }
        .hero .jalur span {
            background: rgba(255,255,255,.16);
            border: 1px solid rgba(255,255,255,.25);
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ====== kartu statistik ====== */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,.06);
            border-left: 5px solid #2563eb;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .stat:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,.1); }
        .stat.biru   { border-left-color: #2563eb; }
        .stat.hijau  { border-left-color: #16a34a; }
        .stat.ungu   { border-left-color: #8b5cf6; }
        .stat.merah  { border-left-color: #ef4444; }
        .stat .label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; font-weight: 700; }
        .stat .angka { font-size: 32px; font-weight: 800; color: #1f2937; margin-top: 4px; }

        /* ====== pesan sukses / error ====== */
        .pesan {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 22px;
            font-weight: 600;
            font-size: 14px;
            border-left: 5px solid transparent;
            animation: masuk .2s ease;
        }
        .pesan.sukses { background: #ecfdf5; color: #15803d; border-left-color: #16a34a; }
        .pesan.error  { background: #fef2f2; color: #b91c1c; border-left-color: #ef4444; }
        .pesan .pembuka { font-weight: 800; margin-bottom: 6px; }
        .pesan .baris-error { font-weight: 500; margin-top: 3px; }
        @keyframes masuk { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: none; } }

        /* ====== tabel ====== */
        .table-wrapper {
            background: #fff;
            border-radius: 16px;
            padding: 10px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
            overflow: auto;
            max-height: 540px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
            min-width: 920px;
        }
        thead th {          /* header menempel saat tabel di-scroll */
            position: sticky;
            top: 0;
            z-index: 2;
            background: linear-gradient(180deg, #1d4ed8, #1e40af);
            color: #fff;
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: .8px;
            padding: 12px 14px;
            text-align: left;
            white-space: nowrap;
            box-shadow: inset 0 -2px 0 rgba(255,255,255,.18);
        }
        thead th:first-child { border-radius: 10px 0 0 0; }
        thead th:last-child  { border-radius: 0 10px 0 0; }
        thead th:nth-child(1),                           /* ID   : tengah */
        thead th:nth-child(8),                           /* Rating : tengah */
        thead th:nth-child(9),                           /* Teknik : tengah */
        thead th:nth-child(10) { text-align: center; }   /* Foto : tengah */
        thead th:nth-child(3),                           /* Harga : kanan */
        thead th:nth-child(4) { text-align: right; }     /* Durasi : kanan */
        thead th:nth-child(1),
        tbody td:nth-child(1) { width: 44px; }           /* kolom ID selebar chip */
        tbody td {
            padding: 9px 14px;
            border-bottom: 1px solid #eef1f7;
            vertical-align: middle;
            white-space: nowrap;
            line-height: 1.5;
        }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:last-child td:first-child { border-radius: 0 0 0 10px; }
        tbody tr:last-child td:last-child  { border-radius: 0 0 10px 0; }
        tbody tr { transition: background .12s ease; }
        tbody tr:nth-child(even) { background: #f6f9ff; }
        tbody tr:hover { background: #e6eeff; }
        tbody td:nth-child(1),              /* ID   : tengah */
        tbody td:nth-child(8),              /* Rating : tengah */
        tbody td:nth-child(9),              /* Teknik : tengah */
        tbody td:nth-child(10) { text-align: center; }   /* Foto : tengah */
        tbody td:nth-child(3),              /* Harga : kanan */
        tbody td:nth-child(4) {             /* Durasi : kanan */
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        .id-sel { text-align: center; }
        .id-sel span {
            display: inline-block;
            min-width: 30px;
            padding: 3px 10px;
            border-radius: 999px;
            background: #eaf1ff;
            color: #1d4ed8;
            font-weight: 800;
            font-size: 13px;
            box-shadow: inset 0 0 0 1px rgba(29,78,216,.2);
        }
        .judul-tebal { font-weight: 600; }
        .badges { display: flex; flex-wrap: wrap; gap: 5px; min-height: 22px; align-items: center; }

        /* ====== badge ====== */
        .badges { display: flex; flex-wrap: wrap; gap: 5px; }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
            line-height: 1.4;
        }
        .badge.genre   { background: #dbeafe; color: #1d4ed8; }
        .badge.teknik  { background: #ede9fe; color: #6d28d9; }
        .badge.rating  { background: #fef3c7; color: #92400e; }
        .tanda { color: #c0c8d4; font-weight: 600; }
        .poster {
            width: 58px;
            height: 84px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,.18);
            display: inline-block;
            vertical-align: middle;
            margin: 2px 0;
        }
        .poster-pal {            /* kotak kosong agar kolom foto tetap rapi/sejajar */
            width: 58px;
            height: 84px;
            border: 1.5px dashed #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            color: #b3bcc9;
            font-size: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
        }
        .total {
            text-align: center;
            padding: 14px 0 8px;
            font-weight: 800;
            color: #15803d;
            font-size: 15px;
        }

        /* ====== kartu form ====== */
        .card {
            background: #fff;
            border-radius: 16px;
            margin-top: 26px;
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .card .judul-card {
            background: linear-gradient(135deg, #15803d, #16a34a);
            color: #fff;
            padding: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card .judul-card .ikon {
            width: 30px; height: 30px;
            background: rgba(255,255,255,.2);
            border-radius: 8px;
            display: grid; place-items: center;
            font-size: 16px;
        }
        .card form { padding: 26px 28px 28px; }
        .grup {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px 20px;
            margin-bottom: 16px;
            background: #fbfcfe;
        }
        .grup legend {
            padding: 0 10px;
            font-weight: 800;
            color: #1d4ed8;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .baris { display: flex; flex-wrap: wrap; gap: 16px; }
        .kolom { flex: 1 1 210px; min-width: 200px; }
        label { display: block; font-weight: 600; font-size: 13px; margin: 0 0 5px; color: #374151; }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.18);
        }
        ::placeholder { color: #b3bcc9; }

        /* ====== pilihan tipe (segment) ====== */
        .segmen { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 4px; }
        .pil { position: relative; }
        .pil input { display: none; }
        .pil .pil-teks {
            display: inline-block;
            padding: 10px 18px;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #6b7280;
            background: #fff;
            cursor: pointer;
            transition: all .15s ease;
        }
        .pil .pil-teks:hover { border-color: #93c5fd; color: #1d4ed8; }
        .pil input:checked + .pil-teks {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(37,99,235,.35);
        }

        /* ====== tombol simpan ====== */
        .tombol-wrap { text-align: center; margin-top: 6px; }
        .tombol {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
            border: 0;
            padding: 13px 40px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .5px;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(22,163,74,.35);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .tombol:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(22,163,74,.4); }
        .tombol:active { transform: translateY(0); }

        /* ====== footer / tautan reset ====== */
        .kaki { text-align: center; margin-top: 30px; }
        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
            font-size: 13.5px;
            border: 1.5px solid #d1d5db;
            border-radius: 999px;
            padding: 10px 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,.06);
            transition: all .15s ease;
        }
        .btn-reset:hover {
            border-color: transparent;
            color: #fff;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            box-shadow: 0 6px 16px rgba(37,99,235,.3);
            transform: translateY(-1px);
        }
        .kaki .ket { display: block; margin-top: 12px; font-size: 12.5px; color: #6b7280; }

        @media (max-width: 760px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .hero h1 { font-size: 22px; }
            .card form { padding: 18px; }
        }
    </style>
</head>
<body>
<div class="container">

    <header class="hero">
        <h1>&#127916; Data Film Bioskop</h1>
        <p>Manajemen data film dengan OOP Multilevel Inheritance.</p>
        <div class="jalur">
            <span>&#128249; Film (level 1)</span>
            <span>&#10148; FilmBioskop (level 2)</span>
            <span>&#10148; FilmAnimasi (level 3)</span>
        </div>
    </header>

    <div class="stats">
        <div class="stat biru">
            <div class="label">Total Data</div>
            <div class="angka"><?= $jumlah ?></div>
        </div>
        <div class="stat hijau">
            <div class="label">Film Biasa</div>
            <div class="angka"><?= count($daftarBiasa) ?></div>
        </div>
        <div class="stat ungu">
            <div class="label">Film Bioskop</div>
            <div class="angka"><?= count($daftarBioskop) ?></div>
        </div>
        <div class="stat merah">
            <div class="label">Film Animasi</div>
            <div class="angka"><?= count($daftarAnimasi) ?></div>
        </div>
    </div>

    <?php if ($gagal || $pesan !== "") : ?>
        <div class="pesan <?= $gagal ? "error" : "sukses" ?>">
            <?php if ($gagal) : ?>
                <div class="pembuka">&#9888; Penyimpanan gagal:</div>
                <?php foreach ($errorsFlash as $err) : ?>
                    <div class="baris-error">&#8226; <?= e($err) ?></div>
                <?php endforeach; ?>
            <?php else : ?>
                &#9989; <?= e($pesan) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <?php if ($jumlah === 0) : ?>
            <p>Belum ada data film.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($KOLOM as $kolom) : ?>
                            <th><?= e($kolom) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barisData as $baris) : ?>
                        <tr>
                            <?php foreach ($baris as $i => $sel) : ?>
                                <?php if ($i === 9 && $sel !== "" && $sel !== "-" && file_exists(__DIR__ . "/" . $sel)) : ?>
                                    <?php $srcFoto = rtrim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/") . "/" . $sel; ?>
                                    <td><img class="poster" src="<?= e($srcFoto) ?>" alt="Poster <?= e($baris[1]) ?>"></td>
                                <?php elseif ($i === 9) : ?>
                                    <td><span class="poster-pal">&#127916;</span></td>
                                <?php elseif ($i === 0) : ?>
                                    <td class="id-sel"><span><?= e($sel) ?></span></td>
                                <?php elseif ($i === 1) : ?>
                                    <td class="judul-tebal"><?= e($sel) ?></td>
                                <?php elseif ($i === 4) : ?>
                                    <td>
                                        <div class="badges">
                                            <?php if ($sel === "-") : ?>
                                                <span class="tanda">-</span>
                                            <?php else : foreach (explode(", ", $sel) as $g) : if ($g === "") continue; ?>
                                                <span class="badge genre"><?= e($g) ?></span>
                                            <?php endforeach; endif; ?>
                                        </div>
                                    </td>
                                <?php elseif ($i === 7) : ?>
                                    <td><?= $sel === "-" ? '<span class="tanda">-</span>' : '<span class="badge teknik">' . e($sel) . '</span>' ?></td>
                                <?php elseif ($i === 8) : ?>
                                    <td><?= $sel === "-" ? '<span class="tanda">-</span>' : '<span class="badge rating">' . e($sel) . '</span>' ?></td>
                                <?php else : ?>
                                    <td><?= e($sel) ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total">&#128202; Total data : <?= $jumlah ?></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="judul-card"><span class="ikon">&#10133;</span> Tambah Data Film</div>
        <form method="post" action="">
            <fieldset class="grup">
                <legend>Jenis film</legend>
                <div class="segmen">
                    <label class="pil">
                        <input type="radio" name="tipe" value="1" onchange="ubahTipe(1)" <?= $tipeTerpilih == 1 ? "checked" : "" ?>>
                        <span class="pil-teks">1 &#183; Film biasa</span>
                    </label>
                    <label class="pil">
                        <input type="radio" name="tipe" value="2" onchange="ubahTipe(2)" <?= $tipeTerpilih == 2 ? "checked" : "" ?>>
                        <span class="pil-teks">2 &#183; Film bioskop</span>
                    </label>
                    <label class="pil">
                        <input type="radio" name="tipe" value="3" onchange="ubahTipe(3)" <?= $tipeTerpilih == 3 ? "checked" : "" ?>>
                        <span class="pil-teks">3 &#183; Film animasi</span>
                    </label>
                </div>
            </fieldset>

            <fieldset class="grup">
                <legend>Atribut Film (Semua Jenis)</legend>
                <div class="baris">
                    <div class="kolom">
                        <label for="id">ID film (angka)</label>
                        <input type="text" id="id" name="id" value="<?= e($nilai["id"]) ?>" placeholder="contoh: 6">
                    </div>
                    <div class="kolom">
                        <label for="judul">Judul film</label>
                        <input type="text" id="judul" name="judul" value="<?= e($nilai["judul"]) ?>" placeholder="contoh: Suhu">
                    </div>
                    <div class="kolom">
                        <label for="harga">Harga tiket</label>
                        <input type="text" id="harga" name="harga" value="<?= e($nilai["harga"]) ?>" placeholder="contoh: 42000">
                    </div>
                </div>
            </fieldset>

            <fieldset class="grup" id="grup-bioskop"
                <?= $tipeTerpilih >= 2 ? "" : "style=\"display:none\"" ?>>
                <legend>Atribut bioskop (Khusus jenis 2)</legend>
                <div class="baris">
                    <div class="kolom">
                        <label for="durasi">Durasi film (menit)</label>
                        <input type="text" id="durasi" name="durasi" value="<?= e($nilai["durasi"]) ?>" placeholder="contoh: 120">
                    </div>
                    <div class="kolom">
                        <label for="genre">Genre (pisahkan dengan koma)</label>
                        <input type="text" id="genre" name="genre" value="<?= e($nilai["genre"]) ?>" placeholder="contoh: Thriller, Action">
                    </div>
                    <div class="kolom">
                        <label for="sutradara">Nama sutradara</label>
                        <input type="text" id="sutradara" name="sutradara" value="<?= e($nilai["sutradara"]) ?>" placeholder="contoh: Budi">
                    </div>
                </div>
            </fieldset>

            <fieldset class="grup" id="grup-animasi"
                <?= $tipeTerpilih == 3 ? "" : "style=\"display:none\"" ?>>
                <legend>Atribut animasi (khusus jenis 3)</legend>
                <div class="baris">
                    <div class="kolom">
                        <label for="studio">Studio animasi</label>
                        <input type="text" id="studio" name="studio" value="<?= e($nilai["studio"]) ?>" placeholder="contoh: StudioMeka">
                    </div>
                    <div class="kolom">
                        <label for="teknik">Teknik animasi</label>
                        <select id="teknik" name="teknik">
                            <?php foreach ($OPSI_TEKNIK as $t) : ?>
                                <option value="<?= e($t) ?>" <?= $nilai["teknik"] === $t ? "selected" : "" ?>><?= e($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="kolom">
                        <label for="rating">Rating usia</label>
                        <select id="rating" name="rating">
                            <?php foreach ($OPSI_RATING as $r) : ?>
                                <option value="<?= e($r) ?>" <?= $nilai["rating"] === $r ? "selected" : "" ?>><?= e($r) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="kolom">
                        <label for="foto">Path foto produk (png/svg/jpg)</label>
                        <input type="text" id="foto" name="foto" value="<?= e($nilai["foto"]) ?>" placeholder="contoh: foto_produk/One Piece.png">
                    </div>
                </div>
            </fieldset>

            <div class="tombol-wrap">
                <button class="tombol" type="submit">&#128190; Simpan Data</button>
            </div>
        </form>
    </div>

    <div class="kaki">
        <a class="btn-reset" href="?reset=1">&#8635; Muat Ulang Data Awal</a>
        <span class="ket">Jaka Permana Herawan &mdash; TP1</span>
    </div>
</div>

<script>
// ubahTipe: Menampilkan/menyembunyikan grup field sesuai jenis film yang dipilih.
function ubahTipe(tipe) {
    document.getElementById("grup-bioskop").style.display = tipe >= 2 ? "" : "none";
    document.getElementById("grup-animasi").style.display = tipe === 3 ? "" : "none";
}
</script>
</body>
</html>