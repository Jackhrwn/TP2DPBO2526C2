
import sys                           # sys.exit untuk menghentikan program saat input habis

from Film import Film                # class Film (level 1)
from FilmBioskop import FilmBioskop  # class FilmBioskop (level 2)
from FilmAnimasi import FilmAnimasi  # class FilmAnimasi (level 3)

# ====== Kode warna terminal ======
BIRU   = "\033[34m"                  # warna teks biru
HIJAU  = "\033[32m"                  # warna teks hijau
MERAH  = "\033[31m"                  # warna teks merah
KUNING = "\033[33m"                  # warna teks kuning
RESET  = "\033[0m"                   # kembalikan warna ke bawaan

# Lebar tetap untuk label prompt, agar semua titik dua ':' sejajar
LEBAR_PROMPT = 35                    # lebar label prompt agar titik dua ':' sejajar

SUDAH_CETAK_BERHENTI = False         # penanda pesan "Program dihentikan." sudah dicetak

# ====== Nama kolom tabel (semua atribut dari 3 class) ======
KOLOM = [
    "ID",
    "Judul",
    "Harga",
    "Durasi",
    "Genre",
    "Sutradara",
    "Studio",
    "Teknik Animasi",
    "Rating Usia",
]


# teks_hijau: Membungkus teks dengan warna hijau (pesan berhasil).
def teks_hijau(teks):
    """Membungkus teks dengan warna hijau (pesan berhasil)."""
    return HIJAU + teks + RESET  # bungkus teks dengan warna hijau


# teks_merah: Membungkus teks dengan warna merah (pesan gagal).
def teks_merah(teks):
    """Membungkus teks dengan warna merah (pesan gagal)."""
    return MERAH + teks + RESET  # bungkus teks dengan warna merah


# berhenti: Mencetak pesan berhenti SEKALI lalu mengakhiri program (dipakai EOF & Ctrl+C).
def berhenti():
    """Mencetak pesan berhenti sekali lalu keluar (EOF / Ctrl+C)."""
    global SUDAH_CETAK_BERHENTI               # penanda pesan sudah pernah dicetak
    if not SUDAH_CETAK_BERHENTI:              # cegah pesan tercetak ganda (double)
        SUDAH_CETAK_BERHENTI = True           # tandai pesan sudah dicetak
        print(teks_merah(" Program dihentikan. "))  # kabari bahwa program berhenti
        sys.stdout.flush()                    # paksa pesan tampil seketika
    sys.exit(0)                               # akhiri program dengan kode sukses


# cetak_prompt: Mencetak label prompt rata-kiri ke lebar tetap lalu tanda ':'.
def cetak_prompt(label):
    """Cetak label prompt rata-kiri ke lebar tetap lalu tanda ':' (semua sejajar)."""
    print(f"{label:<{LEBAR_PROMPT}}: ", end="")  # cetak label rata kiri lalu tanda titik dua
    sys.stdout.flush()                            # paksa output tampil sebelum user mengetik


# baca_teks: Membaca satu baris teks dengan label prompt yang sejajar.
def baca_teks(label):
    """Membaca satu baris teks dengan label prompt yang sejajar."""
    cetak_prompt(label)                                  # tampilkan label prompt terlebih dahulu
    try:                                                 # coba baca satu baris input
        return input()                                   # kembalikan baris bacaan
    except (EOFError, KeyboardInterrupt):                # input habis (EOF) / ditekan Ctrl+C
        berhenti()                                       # tampilkan pesan berhenti lalu akhiri program


# input_angka: Membaca angka dengan error handling dan memastikan hasil >= minimum.
def input_angka(label, minimum, pesan_kurang):
    """Membaca angka dengan error handling."""
    while True:                                     # ulangi sampai angka yang dimasukkan valid
        cetak_prompt(label)                         # tampilkan label prompt
        try:                                        # coba baca baris input
            baris = input()                         # baca baris input dari konsol
        except (EOFError, KeyboardInterrupt):            # input habis (EOF) / ditekan Ctrl+C
            berhenti()                                  # tampilkan pesan berhenti lalu akhiri program
        try:                                        # coba konversi input menjadi bilangan bulat
            nilai = int(baris)                      # simpan hasil konversi angka
        except ValueError:                          # input bukan angka?
            # bukan angka: tampilkan pesan error lalu ulangi
            print(teks_merah("Input harus berupa angka! Silakan coba lagi.\n"))  # tampilkan pesan input bukan angka
            continue                                # minta ulang input
        if nilai < minimum:                         # angka masih di bawah batas minimum
            print(teks_merah(pesan_kurang) + "\n")  # tampilkan pesan "kurang dari minimum"
            continue                                # minta ulang input
        return nilai                                # angka valid: kembalikan nilainya


# baca_id: Membaca ID film dengan validasi angka, negatif, nol, dan duplikat.
def baca_id(biasa, bioskop, animasi):
    """Baca ID film dengan validasi:"""
    while True:                                                        # ulangi sampai ID yang dimasukkan valid
        cetak_prompt("ID film (angka)")                                # tampilkan label prompt "ID film"
        try:                                                           # coba baca baris input
            baris = input()                                            # baca baris input dari konsol
        except (EOFError, KeyboardInterrupt):                       # input habis (EOF) / ditekan Ctrl+C
            berhenti()                                             # tampilkan pesan berhenti lalu akhiri program
        try:                                                           # coba konversi input ID menjadi angka
            id_film = int(baris)                                       # simpan ID hasil konversi
        except ValueError:                                             # input ID bukan angka?
            # bukan angka: tampilkan pesan error lalu ulangi
            print(teks_merah("Input harus berupa angka! Silakan coba lagi.\n"))  # tampilkan pesan input bukan angka
            continue                                                 # minta ulang input
        if id_film < 0:                                              # ID negatif tidak diperbolehkan
            print(teks_merah("ID tidak boleh negatif!\n"))           # tampilkan pesan ID negatif
            continue                                                 # minta ulang input
        if id_film == 0:                                             # ID nol tidak diperbolehkan
            print(teks_merah("ID harus angka mulai dari 1!\n"))      # tampilkan pesan ID nol
            continue                                                 # minta ulang input
        dipakai = False                                              # penanda ID sudah dipakai
        for o in biasa:                                              # periksa wadah film biasa
            if o.get_id() == id_film:                                # ID cocok dengan objek lain?
                dipakai = True                                       # tandai ID sudah dipakai
        if not dipakai:                                              # belum dipakai di wadah biasa?
            for o in bioskop:                                        # periksa wadah film bioskop
                if o.get_id() == id_film:                            # ID cocok dengan objek lain?
                    dipakai = True                                   # tandai ID sudah dipakai
        if not dipakai:                                              # belum dipakai di wadah bioskop?
            for o in animasi:                                        # periksa wadah film animasi
                if o.get_id() == id_film:                            # ID cocok dengan objek lain?
                    dipakai = True                                   # tandai ID sudah dipakai
        if dipakai:                                                  # ID sudah dipakai objek lain?
            print(teks_merah("ID sudah dipakai! Gunakan ID lain.\n"))# tampilkan pesan ID duplikat
            continue                                                 # minta ulang input
        return id_film                                               # ID valid: kembalikan id film


# cetak_garis: Mencetak satu garis border kotak, misal +---+.
def cetak_garis(tengah, panjang):
    """Cetak satu garis border kotak, misal +---+."""
    print(BIRU + "+" + tengah * panjang + "+" + RESET)  # cetak garis dari karakter tengah yang digandakan


# cetak_judul: Mencetak judul di tengah baris (isi kotak judul).
def cetak_judul(judul, panjang):
    """Cetak judul di tengah baris (isi kotak judul)."""
    kiri = (panjang - len(judul)) // 2  # spasi kiri agar seimbang
    print(BIRU + "|" + RESET, end="")   # tepi kiri kotak
    # teks judul kuning di tengah, kiri-kanan diisi spasi
    print(KUNING + " " * kiri + judul + " " * (panjang - kiri - len(judul)) + RESET, end="")
    print(BIRU + "|" + RESET)           # tepi kanan kotak


# cetak_baris: Mencetak satu baris isi dengan border kiri-kanan.
def cetak_baris(isi, panjang, warna=""):
    """Cetak satu baris isi dengan border kiri-kanan."""
    print(BIRU + "|" + RESET + " ", end="")              # border kiri diikuti satu spasi
    if warna:                                            # ada warna yang diminta?
        print(warna, end="")                             # aktifkan warna teks
    print(isi, end="")                                   # cetak isi baris
    if warna:                                            # warna masih aktif?
        print(RESET, end="")                             # matikan warna kembali
    print(" " * max(0, panjang - 1 - len(isi)), end="")  # sisipkan spasi pengisi (minimal 0)
    print(BIRU + "|" + RESET)                            # border kanan kotak


# cetak_kotak_pilihan: Mencetak kotak berisi daftar pilihan '[n] ...' (kuning).
def cetak_kotak_pilihan(caption, opsi_baris):
    """Kotak berisi daftar pilihan '[n] ...' (kuning)."""
    panjang = 2                               # lebar dasar kotak
    for isi in opsi_baris:                    # telusuri setiap baris opsi
        panjang = max(panjang, 1 + len(isi))  # lebar mengikuti opsi terpanjang
    panjang += 2                              # sedikit ruang ekstra
    print(caption)                            # cetak caption di atas kotak
    cetak_garis("-", panjang)                 # garis batas atas kotak
    for isi in opsi_baris:                    # telusuri kembali untuk mencetak opsi
        cetak_baris(isi, panjang, KUNING)     # cetak isi opsi (kuning)
    cetak_garis("-", panjang)                 # garis batas bawah kotak
    print()                                   # baris kosong setelah kotak


# cetak_garis_tabel: Mencetak garis pemisah tabel kolom dinamis.
def cetak_garis_tabel(lebar):
    """Cetak garis pemisah tabel kolom dinamis."""
    print(BIRU + "+", end="")               # tanda '+' di sambungan kolom pertama
    for w in lebar:                         # setiap lebar kolom
        print("-" * (w + 2) + "+", end="")  # cetak garis '-' dan tanda '+' per kolom
    print(RESET)                            # reset warna setelah garis selesai


# cetak_banner_tabel: Mencetak banner judul menyatu dengan tabel selebar grid kolom.
def cetak_banner_tabel(judul, lebar):
    """Banner judul menyatu dengan tabel (lebarnya mengikuti grid kolom)."""
    total = 1                      # total lebar satu garis tabel
    for w in lebar:                # jumlahkan lebar semua kolom
        total += w + 3             # tiap kolom menyumbang lebar + 3
    cetak_garis("=", total - 2)    # batas atas banner (selebar tabel)
    cetak_judul(judul, total - 2)  # judul kuning di tengah, selebar tabel
    cetak_garis("=", total - 2)    # pemisah judul vs tabel (gaya TAMBAH)
    cetak_garis_tabel(lebar)       # grid atas tabel


# cetak_baris_tabel: Mencetak satu baris tabel kolom dinamis.
def cetak_baris_tabel(isi, lebar, warna=""):
    """Cetak satu baris tabel kolom dinamis."""
    print(BIRU + "|" + RESET, end="")      # border kiri sel
    for i, nilai in enumerate(isi):        # telusuri tiap sel beserta indeksnya
        teks = str(nilai).ljust(lebar[i])  # rata kiri nilai sesuai lebar kolom
        if warna:                          # sel memakai warna?
            teks = warna + teks + RESET    # bungkus sel dengan warna
        print(" " + teks + " ", end="")    # cetak sel dengan satu spasi kiri-kanan
        print(BIRU + "|" + RESET, end="")  # border kanan sel
    print()                                # baris selesai


# cetak_kotak_judul_opsi: Mencetak kotak gabungan judul di atas dan daftar opsi.
def cetak_kotak_judul_opsi(judul, opsi_baris):
    """Kotak gabungan: judul di atas, lalu daftar opsi (satu border '=')."""
    panjang = max(40, len(judul) + 4)         # lebar dasar: minimal 40 atau judul+4
    for isi in opsi_baris:                    # telusuri setiap baris opsi
        panjang = max(panjang, len(isi) + 4)  # lebar menyesuaikan opsi terpanjang
    cetak_garis("=", panjang)                 # batas atas kotak
    cetak_judul(judul, panjang)               # judul kuning di tengah
    cetak_garis("=", panjang)                 # pemisah judul vs opsi
    for isi in opsi_baris:                    # telusuri kembali untuk mencetak opsi
        cetak_baris(isi, panjang, KUNING)     # cetak opsi baris (kuning)
    cetak_garis("=", panjang)                 # batas bawah kotak
    print()                                   # baris kosong setelah kotak


# pilih_teknik: Memilih teknik animasi secara angka lalu memetakannya via match-case.
def pilih_teknik():
    """Pilih teknik animasi secara ANGKA lalu petakan lewat match-case,
    mengikuti pola pemilihan tipe film. Input di luar daftar opsi akan
    terus diminta ulang sampai valid."""
    # daftar teknik animasi yang dapat dipilih
    opsi = ["2D", "3D", "Stop Motion", "CGI", "Motion Capture",
            "Animatronik", "GoMotion", "Cut Out", "Rotoscope",
            "Plastinasi", "Pixilasi", "Grafik Gerak"]
    baris_opsi = [f"  [{i}] {o}" for i, o in enumerate(opsi, 1)]            # ubah daftar menjadi opsi "[n] nama"
    cetak_kotak_pilihan("Pilih teknik animasi yang tersedia:", baris_opsi)  # tampilkan kotak pilihan teknik
    pilihan = 0                                                             # nilai awal pilihan (belum valid)
    while not (1 <= pilihan <= len(opsi)):                                  # ulangi sampai pilihan dalam rentang 1..n
        pilihan = input_angka(f"Pilih teknik animasi (1-{len(opsi)})", 1,   # minta pilihan teknik berupa angka
                              f"Pilihan harus 1-{len(opsi)}!")
        if pilihan > len(opsi):                                             # pilihan melebihi jumlah daftar?
            print(teks_merah(f"Pilihan harus 1-{len(opsi)}!\n"))            # tampilkan pesan pilihan di luar daftar
    match pilihan:                         # petakan nomor pilihan ke teknik animasi
        case 1:  return "2D"               # nomor 1 = teknik 2D
        case 2:  return "3D"               # nomor 2 = teknik 3D
        case 3:  return "Stop Motion"      # nomor 3 = stop motion
        case 4:  return "CGI"              # nomor 4 = CGI
        case 5:  return "Motion Capture"   # nomor 5 = motion capture
        case 6:  return "Animatronik"      # nomor 6 = animatronik
        case 7:  return "GoMotion"         # nomor 7 = go motion
        case 8:  return "Cut Out"          # nomor 8 = cut out
        case 9:  return "Rotoscope"        # nomor 9 = rotoscope
        case 10: return "Plastinasi"       # nomor 10 = plastinasi
        case 11: return "Pixilasi"         # nomor 11 = pixilasi
        case 12: return "Grafik Gerak"     # nomor 12 = grafik gerak


# pilih_rating: Memilih rating usia secara angka lalu memetakannya via match-case.
def pilih_rating():
    """Pilih rating usia secara ANGKA lalu petakan lewat match-case,
    mengikuti pola pemilihan tipe film. Input di luar daftar opsi akan
    terus diminta ulang sampai valid."""
    opsi = ["SU", "13+", "17+", "21+"]                                  # daftar rating usia penonton
    baris_opsi = [f"  [{i}] {o}" for i, o in enumerate(opsi, 1)]        # ubah daftar menjadi opsi "[n] nama"
    # tampilkan kotak pilihan rating usia
    cetak_kotak_pilihan("Pilih rating usia penonton yang tersedia:", baris_opsi)
    pilihan = 0                                                         # nilai awal pilihan (belum valid)
    while not (1 <= pilihan <= len(opsi)):                              # ulangi sampai pilihan dalam rentang 1..n
        pilihan = input_angka(f"Pilih rating usia (1-{len(opsi)})", 1,  # minta pilihan rating berupa angka
                              f"Pilihan harus 1-{len(opsi)}!")
        if pilihan > len(opsi):                                         # pilihan melebihi jumlah daftar?
            print(teks_merah(f"Pilihan harus 1-{len(opsi)}!\n"))        # tampilkan pesan pilihan di luar daftar
    match pilihan:                     # petakan nomor pilihan ke rating usia
        case 1: return "SU"            # nomor 1 = rating SU
        case 2: return "13+"           # nomor 2 = rating 13+
        case 3: return "17+"           # nomor 3 = rating 17+
        case 4: return "21+"           # nomor 4 = rating 21+


# baca_genre: Membaca jumlah genre lalu memvalidasi tiap genre berawalan huruf besar.
def baca_genre():
    """Baca daftar genre: tanya jumlah genre lalu validasi tiap genre
    (harus diawali huruf besar)."""
    jumlah = input_angka("Jumlah genre (lebih dari 0)", 1,                  # minta jumlah genre terlebih dahulu
                         "Input harus lebih dari 0!")
    genre = []                                                              # penampung daftar genre
    for i in range(1, jumlah + 1):                                          # ulangi untuk genre ke-1 sampai ke-jumlah
        valid_genre = False                                                 # asumsi genre belum valid
        while not valid_genre:                                              # ulangi sampai genre valid
            g = baca_teks(f"Genre ke-{i} (awali huruf besar)")              # baca genre ke-i dengan prompt
            valid_genre = bool(g) and g[0].isupper()                        # valid bila tidak kosong dan berawalan kapital
            if valid_genre:                                                 # genre sudah valid?
                genre.append(g)                                             # simpan genre ke daftar
            else:                                                           # genre belum valid
                print(teks_merah("Huruf awal genre harus huruf besar!\n"))  # tampilkan pesan huruf awal harus besar
    return genre                                                            # kembalikan daftar genre


# tampilkan_tabel: Menampilkan seluruh data dari 3 wadah berjenis dalam satu tabel dinamis.
def tampilkan_tabel(judul, biasa, bioskop, animasi):
    """Menampilkan seluruh data dalam SATU TABEL DINAMIS.
    Objek class level 1/2 hanya mengisi kolom miliknya, sisanya diisi '-'."""
    if not (biasa or bioskop or animasi):                 # tidak ada data sama sekali?
        print("Belum ada data film.")                     # tampilkan pesan tabel kosong
        return                                            # hentikan fungsi

    # karena tiap class tinggal di wadah berjenisnya sendiri 
    # seluruh data diratakan dulu jadi pasangan (id, baris) supaya bisa diurutkan bersama
    pasangan = []                                         # penampung pasangan (id, baris)
    for o in biasa:                                       # telusuri wadah film biasa
        pasangan.append((o.get_id(), o.get_data()))       # ambil id + baris data film biasa
    for o in bioskop:                                     # telusuri wadah film bioskop
        pasangan.append((o.get_id(), o.get_data()))       # ambil id + baris data film bioskop
    for o in animasi:                                     # telusuri wadah film animasi
        pasangan.append((o.get_id(), o.get_data()))       # ambil id + baris data film animasi
    pasangan.sort(key=lambda p: p[0])                     # urutkan pasangan berdasarkan ID (key-nya id)

    # siapkan baris-baris tabel: isi kolom milik class-nya, sisanya "-"
    baris_data = []                                       # penampung baris-baris tabel
    for _, data in pasangan:                              # telusuri setiap baris yang sudah terurut
        data += ["-"] * (len(KOLOM) - len(data))          # pad dengan "-"
        baris_data.append(data)                           # simpan baris ke penampung

    # hitung lebar tiap kolom = teks terpanjang antara header dan isi
    lebar = []                                            # penampung lebar tiap kolom
    for i, nama_kolom in enumerate(KOLOM):                # telusuri tiap kolom beserta indeksnya
        panjang = len(str(nama_kolom))                    # awali dari panjang nama kolom
        for baris in baris_data:                          # bandingkan dengan isi tiap baris
            if len(str(baris[i])) > panjang:              # isi sel lebih panjang?
                panjang = len(str(baris[i]))              # ambil panjang isi sel terpanjang
        lebar.append(panjang)                             # simpan lebar kolom ke-i

    # ===== cetak tabel =====
    print()                                               # baris kosong sebelum tabel
    cetak_banner_tabel(judul, lebar)                      # banner judul selebar grid tabel
    cetak_baris_tabel(KOLOM, lebar, KUNING)               # baris judul kolom (kuning)
    cetak_garis_tabel(lebar)                              # garis pemisah antara header dan isi
    for baris in baris_data:                              # cetak setiap baris data
        cetak_baris_tabel(baris, lebar)                   # baris data tanpa warna
    cetak_garis_tabel(lebar)                              # garis penutup tabel
    print(HIJAU + f"Total data : {len(biasa) + len(bioskop) + len(animasi)}" + RESET)  # tampilkan jumlah total data (hijau)
    print()                                               # baris kosong setelah tabel


# tambah_data: Menerima input user untuk menambahkan satu objek film.
def tambah_data(biasa, bioskop, animasi):
    """Menerima input user untuk menambahkan satu objek film.
    Setiap prompt sudah menjelaskan data apa yang harus diinput."""
    cetak_kotak_judul_opsi("TAMBAH DATA FILM",               # tampilkan kotak pilihan tipe film
                           ["  [1] Film biasa   (butuh id, judul, harga)",
                            "  [2] Film bioskop (butuh durasi, genre, sutradara)",
                            "  [3] Film animasi (butuh studio, teknik animasi, rating)"])

    tipe = 0                                                 # nilai awal tipe (belum valid)
    while not (1 <= tipe <= 3):                              # ulangi sampai tipe bernilai 1/2/3
        # minta tipe film berupa angka
        tipe = input_angka("Pilih tipe film (1/2/3)", 1, "Tipe harus 1, 2, atau 3!")  # minta tipe film berupa angka
        if tipe > 3:                                         # tipe yang dipilih melebihi 3?
            print(teks_merah("Tipe harus 1, 2, atau 3!\n"))  # tampilkan pesan tipe tidak valid

    # kolom milik class Film (selalu ada)
    id_film = baca_id(biasa, bioskop, animasi)             # baca ID film dengan validasi unik
    judul   = baca_teks("Judul film")                      # baca judul film
    # baca harga tiket (tidak boleh negatif)
    harga   = input_angka("Harga tiket", 0, "Harga tidak boleh negatif!")  # baca harga tiket

    if tipe == 1:                                            # tipe 1: film biasa tanpa kolom tambahan
        # Film             : <id> <judul> <harga>
        biasa.append(Film(id_film, judul, harga))            # tambahkan objek Film ke wadah berjenis
    else:                                                    # tipe lain: butuh properti tambahan
        # kolom milik class FilmBioskop
        # baca durasi film dalam menit
        durasi = input_angka("Durasi film (menit)", 0, "Durasi tidak boleh negatif!")  # baca durasi film dalam menit
        genre  = baca_genre()                                # baca daftar genre
        sutradara = baca_teks("Nama sutradara")              # baca nama sutradara

        if tipe == 2:                                        # tipe 2: film bioskop tanpa properti animasi
            # FilmBioskop      : <id> <judul> <harga> <durasi> <genre...> <sutradara>
            # tambahkan objek FilmBioskop ke wadah berjenis
            bioskop.append(FilmBioskop(id_film, judul, harga, durasi, genre, sutradara))  # kirim objek ke wadah bioskop
        else:                                                # tipe 3: film animasi (turunan paling bawah)
            # kolom milik class FilmAnimasi
            studio = baca_teks("Studio animasi")             # baca nama studio animasi
            teknik = pilih_teknik()                          # baca pilihan teknik animasi
            rating = pilih_rating()                          # baca pilihan rating usia

            # FilmAnimasi      : <id> <judul> <harga> <durasi> <genre...> <sutradara>
            #                    <studio> <teknik> <rating>
            # tambahkan objek FilmAnimasi ke wadah berjenis
            animasi.append(FilmAnimasi(id_film, judul, harga, durasi, genre, sutradara,
                                       studio, teknik, rating))  # kirim objek ke wadah animasi
    print(teks_hijau("Data berhasil ditambahkan!\n"))        # tampilkan pesan sukses (hijau)


def main():
    daftar_biasa = []       # wadah film biasa (level 1)
    daftar_bioskop = []     # wadah film bioskop (level 2)
    daftar_animasi = []     # wadah film animasi (level 3)

    daftar_biasa.append(Film(1, "Sejarah", 25000))                             # film dasar (level 1)
    # film bioskop: film dasar + durasi, genre, sutradara
    daftar_bioskop.append(FilmBioskop(2, "Kimi No Nawa", 45000, 115, ["Drama", "Romance"], "Sari"))  # data awal 2 (bioskop)
    daftar_bioskop.append(FilmBioskop(3, "Ghost In The Cell", 40000, 95, ["Horor"], "Rina"))  # data awal 3 (bioskop)
    # film animasi: film bioskop + studio, teknik, rating usia
    daftar_animasi.append(FilmAnimasi(4, "Konosuba", 50000, 105, ["Aksi", "Petualangan"], "Andi",
                                      "StudioBiru", "3D", "SU"))  # data awal 4 (animasi)
    # film animasi kedua (teknik 2D)
    daftar_animasi.append(FilmAnimasi(5, "Dragon Slayer", 52000, 98, ["Fantasi"], "Dewi", "StudioUngu", "2D", "SU"))

    # tampilkan data awal dalam satu tabel
    tampilkan_tabel("DAFTAR FILM AWAL", daftar_biasa, daftar_bioskop, daftar_animasi)  # tampilkan 5 data awal

    # ===== TERIMA INPUT USER UNTUK MENAMBAH DATA =====
    n = input_angka("Masukkan jumlah data yang ingin ditambahkan", 1,       # minta jumlah data yang akan ditambahkan
                    "Input harus lebih dari 0!")
    for i in range(1, n + 1):                                               # ulangi sebanyak jumlah data yang diminta
        print(KUNING + f"--- Data ke-{i} ---" + RESET)                      # judul kecil "Data ke-i" (kuning)
        tambah_data(daftar_biasa, daftar_bioskop, daftar_animasi)           # panggil proses tambah data

    # ===== TAMPILKAN SELURUH DATA SETELAH PENAMBAHAN =====
    tampilkan_tabel("DAFTAR FILM SETELAH PENAMBAHAN", daftar_biasa, daftar_bioskop, daftar_animasi)  # tampilkan seluruhnya
    print(teks_hijau("Program selesai. Terima kasih!"))  # ucapan penutup

if __name__ == "__main__":
    try:                                        # jalankan program utama
        main()                                  # eksekusi alur program
    except (EOFError, KeyboardInterrupt):       # Ctrl+C / EOF di luar fungsi baca input
        berhenti()                              # tampilkan pesan berhenti sekali lalu keluar