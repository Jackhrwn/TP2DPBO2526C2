using namespace std;    // pakai namespace std (tanpa prefiks std::)

class FilmAnimasi : public FilmBioskop {  // deklarasi class (level 3), turunan dari FilmBioskop
private:
    string studio;                        // atribut 1: studio animasi pembuat film
    string teknikAnimasi;                 // atribut 2: teknik animasi (2D / 3D / stop motion)
    string ratingUsia;                    // atribut 3: rating usia penonton (SU / 13+ / 17+)

public:
    // FilmAnimasi: Konstruktor untuk menyimpan data lengkap film animasi.
    FilmAnimasi(int id, string judul, int harga, int durasiMenit,vector<string> genre, string sutradara,
                string studio, string teknikAnimasi, string ratingUsia)
                : FilmBioskop(id, judul, harga, durasiMenit, genre, sutradara) {
        this->studio        = studio;         // simpan nama studio pembuat
        this->teknikAnimasi = teknikAnimasi;  // simpan teknik animasi yang dipakai
        this->ratingUsia    = ratingUsia;     // simpan rating usia penonton
    }

    // ===== getter =====
    // getStudio: Getter untuk mengambil atribut studio.
    string getStudio()        { return studio; }         // getter studio animasi
    // getTeknikAnimasi: Getter untuk mengambil atribut teknikAnimasi.
    string getTeknikAnimasi() { return teknikAnimasi; }  // getter teknik animasi
    // getRatingUsia: Getter untuk mengambil atribut ratingUsia.
    string getRatingUsia()    { return ratingUsia; }     // getter rating usia

    // ===== setter =====
    // setStudio: Setter untuk mengubah atribut studio.
    void setStudio(string studio)              { this->studio = studio; }                 // setter studio animasi
    // setTeknikAnimasi: Setter untuk mengubah atribut teknikAnimasi.
    void setTeknikAnimasi(string teknikAnimasi) { this->teknikAnimasi = teknikAnimasi; }  // setter teknik animasi
    // setRatingUsia: Setter untuk mengubah atribut ratingUsia.
    void setRatingUsia(string ratingUsia)      { this->ratingUsia = ratingUsia; }         // setter rating usia

    // getData: Mengambil seluruh data Film, bioskop, dan animasi (9 kolom).
    vector<string> getData() {                         // method getData milik FilmAnimasi (menutup milik FilmBioskop)
        vector<string> data = FilmBioskop::getData();  // ambil 6 kolom dari class induk
        data.push_back(studio);                        // tambah kolom studio animasi
        data.push_back(teknikAnimasi);                 // tambah kolom teknik animasi
        data.push_back(ratingUsia);                    // tambah kolom rating usia
        return data;                                   // kembalikan 9 kolom gabungan
    }
};