#include <bits/stdc++.h>    // header tunggal berisi semua pustaka standar
#include "Film.cpp"         // muat definisi class Film (level 1)
#include "FilmBioskop.cpp"  // muat class FilmBioskop (level 2)
#include "FilmAnimasi.cpp"  // muat class FilmAnimasi (level 3)

using namespace std;        // pakai namespace std (tanpa prefiks std::)

// ====== Kode warna terminal ======
#define WARNA_BIRU   "\033[34m"  // kode warna biru (border tabel)
#define WARNA_HIJAU  "\033[32m"  // kode warna hijau (pesan sukses)
#define WARNA_MERAH  "\033[31m"  // kode warna merah (pesan gagal)
#define WARNA_KUNING "\033[33m"  // kode warna kuning (judul / opsi menu)
#define WARNA_RESET  "\033[0m"   // reset warna ke default terminal

// ====== Nama kolom tabel (gabungan semua atribut dari 3 class) ======
vector<string> kolom = {                                    // header kolom tabel (gabungan semua atribut)
    "ID", "Judul", "Harga", "Durasi", "Genre",              
    "Sutradara", "Studio", "Teknik Animasi", "Rating Usia"  
};

// ====== Fungsi teks berwarna ======
// teksHijau: Membungkus teks dengan warna hijau 
string teksHijau(string teks) {
    return string(WARNA_HIJAU) + teks + WARNA_RESET;  // bungkus teks warna hijau lalu reset
}

// teksMerah: Membungkus teks dengan warna merah 
string teksMerah(string teks) {
    return string(WARNA_MERAH) + teks + WARNA_RESET;  // bungkus teks warna merah lalu reset
}

// berhenti: Mencetak pesan berhenti SEKALI lalu mengakhiri program (dipakai EOF & Ctrl+C).
void berhenti() {
    static bool sudahCetak = false;                   // penanda pesan sudah pernah dicetak
    if (!sudahCetak) {                                // cegah pesan tercetak ganda (double)
        sudahCetak = true;                            // tandai pesan sudah dicetak
        cout << teksMerah(" Program dihentikan. ") << endl;  // kabari bahwa program berhenti
        cout.flush();                                 // paksa pesan tampil seketika
    }
    exit(0);                                          // akhiri program
}

// tanganiCtrlC: Menangani Ctrl+C lewat prosedur berhenti yang sama.
void tanganiCtrlC(int) {
    berhenti();                                       // panggil prosedur berhenti (pesan sekali saja)
}

const int LebarKata = 35;  // lebar label prompt agar tanda titik dua selalu rata

// cetakPrompt: Mencetak label prompt rata-kiri ke lebar tetap lalu tanda ':'.
void cetakPrompt(string label) {
    cout << label;                            // cetak label tanpa pindah baris
    int spasi = LebarKata - (int)label.size();  // sisa ruang agar label rata kiri
    if (spasi > 0) cout << string(spasi, ' ');    // isi sisa ruang dengan spasi
    cout << ": ";                             // tutup label dengan tanda titik dua
    cout.flush();                             // paksa output tampil seketika
}

// bacaTeks: Membaca teks dengan menampilkan label prompt yang sesuai.
string bacaTeks(string label) {
    cetakPrompt(label);                                    // tampilkan label prompt
    string baris;                                          // penampung satu baris input
    if (!getline(cin, baris)) {                            // EOF (Ctrl+C): tidak ada input lagi
        berhenti();                                        // kabari program berhenti lalu akhiri
    }
    return baris;                                          // kembalikan teks yang dibaca
}

// inputAngka: Membaca angka dengan validasi angka dan batas minimum.
int inputAngka(string label, int minimum, string pesanKurang) {
    while (true) {
        cetakPrompt(label);                                       // tampilkan label prompt
        string baris;                                             // penampung satu baris input
        if (!getline(cin, baris)) {                               // EOF (Ctrl+C): tidak ada input lagi
            berhenti();                                           // kabari program berhenti lalu akhiri
        }

        bool   valid = !baris.empty();                            // tandai input valid (tidak kosong)
        size_t mulai = 0;                                         // posisi digit pertama (lewati tanda +/-)
        if (valid && (baris[0] == '-' || baris[0] == '+')) {      // ada tanda bilangan di depan?
            mulai = 1;                                            // lewati karakter tanda
            if (mulai >= baris.size()) valid = false;             // hanya tanda tanpa digit: tidak valid
        }
        for (size_t i = mulai; valid && i < baris.size(); i++) {  // telusuri semua karakter sisa
            if (!isdigit((unsigned char)baris[i])) {              // ada karakter non-angka?
                valid = false;                                    // tandai input tidak valid
            }
        }
        if (!valid) {                                             // format salah -> minta ulang
        // pesan merah: input harus angka
            cout << teksMerah("Input harus berupa angka! Silakan coba lagi.") << endl << endl;  // pesan: harus angka
            continue;                                             // ulangi iterasi meminta input
        }

        int angka = 0;                                            // hasil konversi string ke angka
        for (size_t i = mulai; i < baris.size(); i++) {           // telusuri digit dari posisi paling kiri
            angka = angka * 10 + (baris[i] - '0');                // tumpuk digit (puluhan, ratusan, dst)
        }
        if (baris[0] == '-') angka = -angka;                      // terapkan tanda negatif

        if (angka < minimum) {                                    // cek batas nilai minimum
            cout << teksMerah(pesanKurang) << endl << endl;       // minta ulang dengan pesan batas
            continue;                                             // ulangi iterasi meminta input
        }
        return angka;                                             // input valid: kembalikan nilainya
    }
}

// bacaId: Membaca ID film dengan validasi format dan keunikan ID.
int bacaId(vector<Film>& biasa, vector<FilmBioskop>& bioskop, vector<FilmAnimasi>& animasi) {
    while (true) {
        cetakPrompt("ID film (angka)");                            // tampilkan label prompt ID
        string baris;                                              // penampung satu baris input
        if (!getline(cin, baris)) {                                // EOF (Ctrl+C): tidak ada input lagi
            berhenti();                                            // kabari program berhenti lalu akhiri
        }

        bool   valid = !baris.empty();                             // tandai input valid (tidak kosong)
        size_t mulai = 0;                                          // posisi digit pertama (lewati tanda +/-)
        if (valid && (baris[0] == '-' || baris[0] == '+')) {       // ada tanda bilangan di depan?
            mulai = 1;                                             // lewati karakter tanda
            if (mulai >= baris.size()) valid = false;              // hanya tanda tanpa digit: tidak valid
        }
        for (size_t i = mulai; valid && i < baris.size(); i++) {   // telusuri semua karakter sisa
            if (!isdigit((unsigned char)baris[i])) valid = false;  // karakter non-angka: tidak valid
        }

        int id = 0;                                                // hasil konversi string
        if (valid) {                                               // konversi hanya bila format benar
            try {
                id = stoi(baris);                                  // ubah string menjadi int
            } catch (const exception&) {                           // angka melebihi jangkauan int
                valid = false;                                     // tandai tidak valid
            }
        }
        if (!valid) {                                              // format salah -> minta ulang
        // pesan merah: input harus berupa angka
            cout << teksMerah("Input harus berupa angka! Silakan coba lagi.") << endl << endl;  // pesan: harus angka
            continue;                                              // ulangi iterasi meminta input
        }

        if (id < 0) {                                              // ID negatif ditolak
        // pesan merah: ID tidak boleh negatif
            cout << teksMerah("ID tidak boleh negatif!") << endl << endl;  // pesan: ID negatif ditolak
            continue;                                              // ulangi iterasi meminta input
        }
        if (id == 0) {                                             // ID nol ditolak
        // pesan merah: ID harus mulai dari angka 1
            cout << teksMerah("ID harus angka mulai dari 1!") << endl << endl;  // pesan: ID nol ditolak
            continue;                                              // ulangi iterasi meminta input
        }

        bool dipakai = false;                                      // penanda ID sudah terpakai
        for (Film& o : biasa) {                                    // telusuri semua film biasa
            if (o.getId() == id) dipakai = true;                   // cocok dengan film lain?
        }
        if (!dipakai) {                                            // belum dipakai di film biasa?
            for (FilmBioskop& o : bioskop) {                       // telusuri semua film bioskop
                if (o.getId() == id) dipakai = true;               // cocok dengan film lain?
            }
        }
        if (!dipakai) {                                            // belum dipakai juga di film bioskop?
            for (FilmAnimasi& o : animasi) {                       // telusuri semua film animasi
                if (o.getId() == id) dipakai = true;               // cocok dengan film lain?
            }
        }
        if (dipakai) {                                             // ID duplikat tidak diizinkan
        // pesan merah: ID harus unik
            cout << teksMerah("ID sudah dipakai! Gunakan ID lain.") << endl << endl;  // pesan: ID duplikat
            continue;                                              // ulangi iterasi meminta input
        }
        return id;                                                 // ID valid dan unik, kembalikan nilainya
    }
}

// cetakGaris: Mencetak satu garis kotak dengan karakter pengisi.
void cetakGaris(string kiri, string tengah, string kanan, int panjang) {
    cout << WARNA_BIRU << kiri << string(panjang, tengah[0]) << kanan << WARNA_RESET << endl;  // gambar satu garis kotak (kiri, pengisi, kanan)
}

// cetakJudul: Mencetak judul di tengah kotak dengan padding seimbang.
void cetakJudul(string judul, int panjang) {
    int kiri = (panjang - (int)judul.size()) / 2;      // spasi kiri agar judul di tengah
    cout << WARNA_BIRU << "|" << WARNA_RESET;          // border kiri kotak
    // judul kuning di tengah, kanan diisi spasi
    cout << WARNA_KUNING << string(kiri, ' ') << judul
         << string(panjang - kiri - (int)judul.size(), ' ') << WARNA_RESET;
    cout << WARNA_BIRU << "|" << WARNA_RESET << endl;  // border kanan + pindah baris
}

// cetakBaris: Mencetak satu baris isi kotak dengan border kiri-kanan.
void cetakBaris(string isi, int panjang, string warna = WARNA_RESET) {
    cout << WARNA_BIRU << "|" << WARNA_RESET << " ";                  // batas kiri + spasi awal isi
    if (!warna.empty() && warna != WARNA_RESET) cout << warna;        // aktifkan warna teks
    cout << isi;                                                      // cetak teks isi
    if (!warna.empty() && warna != WARNA_RESET) cout << WARNA_RESET;  // matikan warna kembali
    cout << string(max(0, panjang - 1 - (int)isi.size()), ' ')        // spasi pengisi sisa lebar
         << WARNA_BIRU << "|" << WARNA_RESET << endl;
}

// cetakKotakPilihan: Mencetak kotak berisi daftar pilihan "[n] ..." (kuning).
void cetakKotakPilihan(string caption, vector<string> opsiBaris) {
    int panjang = 2;                                  // lebar awal kotak
    for (string isi : opsiBaris) {                    // cari lebar opsi terpanjang
        panjang = max(panjang, 1 + (int)isi.size());  // perlebar jika opsi lebih panjang
    }
    panjang += 2;                                     // sedikit ruang ekstra
    cout << caption << endl;                          // caption di atas kotak
    cetakGaris("+", "-", "+", panjang);               // garis atas kotak
    // cetak tiap opsi dengan warna kuning
    for (string isi : opsiBaris) cetakBaris(isi, panjang, WARNA_KUNING);
    cetakGaris("+", "-", "+", panjang);               // garis bawah kotak
    cout << endl;                                     // spasi setelah kotak
}

// ===== tabel kolom dinamis =====
// cetakGarisTabel: Mencetak garis pemisah tabel dengan lebar kolom dinamis.
void cetakGarisTabel(vector<int>& lebar) {
    cout << WARNA_BIRU << "+";              // ujung kiri garis
    for (int w : lebar) {                   // telusuri tiap kolom
        cout << string(w + 2, '-') << "+";  // ruas garis tiap kolom + pemisah
    }
    cout << WARNA_RESET << endl;            // reset warna + pindah baris
}

// cetakBannerTabel: Mencetak judul menyatu dengan tabel (lebarnya selebar grid kolom).
void cetakBannerTabel(string judul, vector<int>& lebar) {
    int total = 1;                         // total lebar satu garis tabel
    for (int w : lebar) total += w + 3;    // jumlahkan lebar tiap kolom + 3
    cetakGaris("+", "=", "+", total - 2);  // batas atas banner (selebar tabel)
    cetakJudul(judul, total - 2);          // judul kuning di tengah, selebar tabel
    cetakGaris("+", "=", "+", total - 2);  // pemisah judul vs tabel (gaya TAMBAH)
    cetakGarisTabel(lebar);                // grid atas kolom tabel
}

// cetakBarisTabel: Mencetak satu baris tabel dengan sel sesuai lebar kolom.
void cetakBarisTabel(vector<string> isi, vector<int>& lebar, bool judulKolom) {
    cout << WARNA_BIRU << "|" << WARNA_RESET;                      // batas awal baris (biru)
    for (size_t i = 0; i < isi.size(); i++) {                      // telusuri semua sel
        string teks = isi[i];                                      // isi sel saat ini
        // pad kanan agar selebar kolom
        if ((int)teks.size() < lebar[i]) teks.append(lebar[i] - (int)teks.size(), ' ');
        if (judulKolom) teks = WARNA_KUNING + teks + WARNA_RESET;  // judul kolom diberi warna kuning
        cout << " " << teks << " ";                                // sel dibungkus spasi kiri-kanan
        cout << WARNA_BIRU << "|" << WARNA_RESET;                  // sisi "|" berwarna biru
    }
    cout << endl;                                                  // pindah baris
}

// cetakKotakJudulOpsi: Mencetak kotak gabungan judul dan daftar opsi.
void cetakKotakJudulOpsi(string judul, vector<string> opsiBaris) {
    int panjang = max(40, (int)judul.size() + 4);     // lebar minimal 40 atau selebar judul
    for (string isi : opsiBaris) {                    // cari lebar opsi terpanjang
        panjang = max(panjang, (int)isi.size() + 4);  // perlebar jika opsi lebih panjang
    }
    cetakGaris("+", "=", "+", panjang);               // garis atas kotak
    cetakJudul(judul, panjang);                       // judul di tengah
    cetakGaris("+", "=", "+", panjang);               // pemisah judul dan opsi
    // cetak tiap opsi dengan warna kuning
    for (string isi : opsiBaris) cetakBaris(isi, panjang, WARNA_KUNING);
    cetakGaris("+", "=", "+", panjang);               // garis bawah kotak
    cout << endl;                                     // spasi setelah kotak
}

// tampilkanTabel: Menampilkan seluruh data film dari 3 wadah berjenis dalam satu tabel dinamis.
void tampilkanTabel(string judul, vector<Film>& biasa,vector<FilmBioskop>& bioskop, vector<FilmAnimasi>& animasi) {
    size_t jumlah = biasa.size() + bioskop.size() + animasi.size();  // jumlah seluruh film
    if (jumlah == 0) {                                               // belum ada data sama sekali?
        cout << "Belum ada data film." << endl;                      // kabari bahwa data kosong
        return;                                                      // langsung keluar dari fungsi
    }

    // karena tiap kelas tinggal di wadah berjenisnya sendiri (tanpa virtual),
    // seluruh data diratakan dulu jadi pasangan (id, baris) supaya bisa diurutkan bersama
    vector<pair<int, vector<string>>> urut;                    // penampung (id, baris) untuk diurutkan
    for (Film& o : biasa)                                      // telusuri wadah film biasa
        urut.push_back({o.getId(), o.getData()});              // ambil id + baris data film biasa
    for (FilmBioskop& o : bioskop)                             // telusuri wadah film bioskop
        urut.push_back({o.getId(), o.getData()});              // ambil id + baris data film bioskop
    for (FilmAnimasi& o : animasi)                             // telusuri wadah film animasi
        urut.push_back({o.getId(), o.getData()});              // ambil id + baris data film animasi

    // urutkan pasangan (id, baris) berdasarkan ID menaik memakai std::sort 
    sort(urut.begin(), urut.end(),                             // urutkan seluruh pasangan yang dikumpulkan
         [](const auto& a, const auto& b) { return a.first < b.first; });  // a lebih dulu bila ID-nya lebih kecil

    // siapkan baris-baris tabel: isi kolom milik class-nya, sisanya "-"
    vector<vector<string>> barisData;                          // penampung baris-baris tabel
    for (auto& pasangan : urut) {                              // telusuri pasangan yang sudah urut
        vector<string> data = pasangan.second;                 // baris data milik objek
        while ((int)data.size() < (int)kolom.size()) data.push_back("-");  // pad kolom kosong dengan "-"
        barisData.push_back(data);                             // simpan baris ke daftar
    }

    // hitung lebar tiap kolom = teks terpanjang antara header dan isi
    vector<int> lebar(kolom.size());                           // penampung lebar tiap kolom
    for (size_t i = 0; i < kolom.size(); i++) {                // telusuri tiap kolom
        lebar[i] = (int)kolom[i].size();                       // awali dari lebar nama kolom
        for (auto& baris : barisData) {                        // cari isi terpanjang di kolom itu
        // perlebar jika isi lebih panjang
            if ((int)baris[i].size() > lebar[i]) lebar[i] = (int)baris[i].size();  // perlebar jika isi sel lebih panjang
        }
    }

    // ===== cetak tabel =====
    cout << endl;                                              // spasi sebelum tabel
    cetakBannerTabel(judul, lebar);                            // judul + batas atas tabel
    cetakBarisTabel(kolom, lebar, true);                       // baris judul kolom (kuning)
    cetakGarisTabel(lebar);                                    // pemisah judul dan isi
    // cetak satu baris per data
    for (auto& baris : barisData) cetakBarisTabel(baris, lebar, false);  // cetak tiap baris data (tanpa warna)
    cetakGarisTabel(lebar);                                    // garis bawah tabel
    // jumlah data total (hijau)
    cout << WARNA_HIJAU << "Total data : " << jumlah << WARNA_RESET << endl;  // jumlah seluruh film (hijau)
    cout << endl;                                              // spasi setelah tabel
}

// pilihTeknik: Memilih teknik animasi dari daftar opsi yang tersedia.
string pilihTeknik() {
    vector<string> opsi = {"2D", "3D", "Stop Motion", "CGI", "Motion Capture",
                           "Animatronik", "GoMotion", "Cut Out", "Rotoscope",
                           "Plastinasi", "Pixilasi", "Grafik Gerak"};
    vector<string> barisOpsi;                   // daftar baris untuk kotak
    for (size_t i = 0; i < opsi.size(); i++) {  // susun tiap teknik jadi baris
        // isi label "[nomor] nama teknik"
        barisOpsi.push_back("  [" + to_string(i + 1) + "] " + opsi[i]);
    }
    // tampilkan kotak pilihan teknik
    cetakKotakPilihan("Pilih teknik animasi yang tersedia:", barisOpsi);

    int pilihan;                                // penampung nomor pilihan
    do {                                        // minta ulang sampai pilihan valid
        // minta nomor pilihan dalam rentang 1..n
        pilihan = inputAngka("Pilih teknik animasi (1-" + to_string(opsi.size()) + ")",
                             1, "Pilihan harus 1-" + to_string(opsi.size()) + "!");
        if ((int)pilihan > (int)opsi.size()) {  // nomor di luar daftar?
        // pesan merah: pilihan tidak valid
            cout << teksMerah("Pilihan harus 1-" + to_string(opsi.size()) + "!") << endl << endl;  // pesan: pilihan tidak valid
        }
    } while ((int)pilihan > (int)opsi.size());  // ulangi selama di luar daftar
    switch (pilihan) {                          // petakan nomor pilihan ke teknik
        case 1:  return "2D";             // 1 = teknik 2D
        case 2:  return "3D";             // 2 = teknik 3D
        case 3:  return "Stop Motion";    // 3 = stop motion
        case 4:  return "CGI";            // 4 = CGI
        case 5:  return "Motion Capture"; // 5 = motion capture
        case 6:  return "Animatronik";    // 6 = animatronik
        case 7:  return "GoMotion";       // 7 = go motion
        case 8:  return "Cut Out";        // 8 = cut out
        case 9:  return "Rotoscope";      // 9 = rotoscope
        case 10: return "Plastinasi";     // 10 = plastinasi
        case 11: return "Pixilasi";       // 11 = pixilasi
        case 12: return "Grafik Gerak";   // 12 = grafik gerak
    }
    return "";                            // tidak akan tercapai (pilihan sudah divalidasi)
}

// pilihRating: Memilih rating usia penonton dari daftar opsi yang tersedia.
string pilihRating() {
    // daftar rating usia penonton
    vector<string> opsi = {"SU", "13+", "17+", "21+"};
    vector<string> barisOpsi;                   // daftar baris untuk kotak
    for (size_t i = 0; i < opsi.size(); i++) {  // susun tiap rating jadi baris
        // isi label "[nomor] nama rating"
        barisOpsi.push_back("  [" + to_string(i + 1) + "] " + opsi[i]);
    }
    // tampilkan kotak pilihan rating
    cetakKotakPilihan("Pilih rating usia penonton yang tersedia:", barisOpsi);

    int pilihan;                                // penampung nomor pilihan
    do {                                        // minta ulang sampai pilihan valid
        // minta nomor pilihan dalam rentang 1..n
        pilihan = inputAngka("Pilih rating usia (1-" + to_string(opsi.size()) + ")",
                             1, "Pilihan harus 1-" + to_string(opsi.size()) + "!");
        if ((int)pilihan > (int)opsi.size()) {  // nomor di luar daftar?
        // pesan merah: pilihan tidak valid
            cout << teksMerah("Pilihan harus 1-" + to_string(opsi.size()) + "!") << endl << endl;  // pesan: pilihan tidak valid
        }
    } while ((int)pilihan > (int)opsi.size());  // ulangi selama di luar daftar
    switch (pilihan) {                // petakan nomor pilihan ke rating
        case 1:  return "SU";         // 1 = SU (semua umur)
        case 2:  return "13+";        // 2 = 13+
        case 3:  return "17+";        // 3 = 17+
        case 4:  return "21+";        // 4 = 21+
    }
    return "";                        // tidak akan tercapai (pilihan sudah divalidasi)
}

// bacaGenre: Membaca daftar genre dengan validasi huruf awal besar.
vector<string> bacaGenre() {
    int jumlah = inputAngka("Jumlah genre (lebih dari 0)", 1,         // tanya jumlah genre
                            "Input harus lebih dari 0!");
    vector<string> genre;                                             // penampung daftar genre
    for (int i = 1; i <= jumlah; i++) {                               // telusuri tiap nomor genre
        bool validGenre = false;                                      // tandai genre belum valid
        while (!validGenre) {                                         // minta ulang sampai valid
            // baca satu genre dengan label nomor urut
            string g = bacaTeks("Genre ke-" + to_string(i) + " (awali huruf besar)");
            validGenre = !g.empty() && isupper((unsigned char)g[0]);  // valid jika non-kosong & huruf besar
            if (validGenre) {                                         // genre benar?
                genre.push_back(g);                                   // simpan genre
            } else {                                                  // genre salah format
                // pesan merah: awali dengan huruf besar
                cout << teksMerah("Huruf awal genre harus huruf besar!") << endl << endl;  // pesan: huruf awal harus besar
            }
        }
    }
    return genre;                                                     // kembalikan daftar genre
}

// tambahData: Menerima input user untuk menambahkan satu objek film.
void tambahData(vector<Film>& biasa, vector<FilmBioskop>& bioskop, vector<FilmAnimasi>& animasi) {
    cetakKotakJudulOpsi("TAMBAH DATA FILM",                           // menu pilih tipe film
                        {"  [1] Film biasa   (butuh id, judul, harga)",
                         "  [2] Film bioskop (butuh durasi, genre, sutradara)",
                         "  [3] Film animasi (butuh studio, teknik animasi, rating)"});

    int tipe;                                                         // penampung pilihan tipe
    do {                                                              // minta ulang sampai tipe valid
        // tanya tipe (1 biasa, 2 bioskop, 3 animasi)
        tipe = inputAngka("Pilih tipe film (1/2/3)", 1, "Tipe harus 1, 2, atau 3!");
        if (tipe > 3) {                                               // tipe di luar 1..3?
            // pesan merah: tipe tidak valid
            cout << teksMerah("Tipe harus 1, 2, atau 3!") << endl << endl;  // pesan: tipe tidak valid
        }
    } while (tipe > 3);                                               // ulangi selama tipe di luar 1..3

    // kolom milik class Film (selalu ada)
    int    id    = bacaId(biasa, bioskop, animasi);                       // baca ID yang unik
    string judul = bacaTeks("Judul film");                            // baca judul film
    // baca harga tiket (tidak boleh negatif)
    int    harga = inputAngka("Harga tiket", 0, "Harga tidak boleh negatif!");

    if (tipe == 1) {                                                  // tipe 1: hanya data dasar Film
        // Film            : <id> <judul> <harga>
        biasa.push_back(Film(id, judul, harga));                  // simpan objek Film ke wadah berjenis
    } else {                                                          // tipe 2/3: pakai atribut bioskop
        // kolom milik class FilmBioskop
        // baca durasi film dalam menit
        int            durasi     = inputAngka("Durasi film (menit)", 0, "Durasi tidak boleh negatif!");
        vector<string> genre      = bacaGenre();                      // baca daftar genre
        string         sutradara  = bacaTeks("Nama sutradara");       // baca nama sutradara

        if (tipe == 2) {                                              // tipe 2: cukup sampai kelas bioskop
            // FilmBioskop   : <id> <judul> <harga> <durasi> <genre...> <sutradara>
            // simpan objek FilmBioskop ke wadah berjenis
            bioskop.push_back(FilmBioskop(id, judul, harga, durasi, genre, sutradara));  // kirim objek ke wadah bioskop
        } else {                                                      // tipe 3: lanjut atribut animasi
            // kolom milik class FilmAnimasi
            string studio      = bacaTeks("Studio animasi");          // baca studio pembuat
            string teknik      = pilihTeknik();                       // pilih teknik animasi
            string ratingUsia  = pilihRating();                       // pilih rating usia

            // FilmAnimasi    : <id> <judul> <harga> <durasi> <genre...> <sutradara>
            //                   <studio> <teknik> <rating>
            // simpan objek FilmAnimasi ke wadah berjenis
            animasi.push_back(FilmAnimasi(id, judul, harga, durasi, genre, sutradara,
                                          studio, teknik, ratingUsia));  // kirim objek ke wadah animasi
        }
    }
    cout << teksHijau("Data berhasil ditambahkan!") << endl << endl;  // pesan sukses hijau
}

int main() {
    signal(SIGINT, tanganiCtrlC);      // tangani Ctrl+C dengan pesan berhenti 

    vector<Film>        daftarBiasa;   // wadah film biasa (level 1)
    vector<FilmBioskop> daftarBioskop; // wadah film bioskop (level 2)
    vector<FilmAnimasi> daftarAnimasi; // wadah film animasi (level 3)

    daftarBiasa.push_back(Film(1, "Sejarah", 25000));              // film biasa: data awal 1
    // bioskop: Kimi No Nawa
    daftarBioskop.push_back(FilmBioskop(2, "Kimi No Nawa", 45000, 115, {"Drama", "Romance"}, "Sari"));  // data awal 2 (bioskop)
    // bioskop: Ghost In The Cell
    daftarBioskop.push_back(FilmBioskop(3, "Ghost In The Cell", 40000, 95, {"Horor"}, "Rina"));  // data awal 3 (bioskop)
    // animasi: Konosuba (StudioBiru, 3D)
    daftarAnimasi.push_back(FilmAnimasi(4, "Konosuba", 50000, 105, {"Aksi", "Petualangan"}, "Andi", "StudioBiru", "3D", "SU"));  // data awal 4 (animasi)
    // animasi: Dragon Slayer (StudioUngu, 2D)
    daftarAnimasi.push_back(FilmAnimasi(5, "Dragon Slayer", 52000, 98, {"Fantasi"}, "Dewi", "StudioUngu", "2D", "SU"));  // data awal 5 (animasi)

    // tampilkan data awal dalam satu tabel
    tampilkanTabel("DAFTAR FILM AWAL", daftarBiasa, daftarBioskop, daftarAnimasi);  // tampilkan 5 data awal

    // ===== TERIMA INPUT USER UNTUK MENAMBAH DATA =====
    // minta jumlah data yang akan ditambahkan
    int n = inputAngka("Masukkan jumlah data yang ingin ditambahkan", 1,
                       "Input harus lebih dari 0!");
    for (int i = 1; i <= n; i++) {                                // tambahkan data sebanyak n
        // penanda nomor urut data
        cout << WARNA_KUNING << "--- Data ke-" << i << " ---" << WARNA_RESET << endl;  // penanda nomor data ke-i
        tambahData(daftarBiasa, daftarBioskop, daftarAnimasi);    // tambah satu objek film
    }

    // ===== TAMPILKAN SELURUH DATA SETELAH PENAMBAHAN =====
    tampilkanTabel("DAFTAR FILM SETELAH PENAMBAHAN", daftarBiasa, daftarBioskop, daftarAnimasi);  // tampilkan seluruh data terbaru
    cout << teksHijau("Program selesai. Terima kasih!") << endl;  // salam penutup program

    return 0;  // program sukses selesai
}