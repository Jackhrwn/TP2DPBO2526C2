using namespace std;    // pakai namespace std (tanpa prefiks std::)

class FilmBioskop : public Film {  // deklarasi class (level 2), turunan dari Film
private:
    int durasiMenit;      // atribut 1: durasi film (menit)
    vector<string> genre; // atribut 2: daftar genre (bisa lebih dari 1)
    string sutradara;     // atribut 3: sutradara film

public:
    // FilmBioskop: Konstruktor untuk menyimpan durasi, genre, dan sutradara.
    FilmBioskop(int id, string judul, int harga, int durasiMenit,vector<string> genre, string sutradara) : Film(id, judul, harga) {
        this->durasiMenit = durasiMenit;  // simpan durasi film (menit)
        this->genre       = genre;        // simpan daftar genre
        this->sutradara   = sutradara;    // simpan nama sutradara
    }

    // ===== getter =====
    // getDurasiMenit: Getter untuk mengambil atribut durasiMenit.
    int getDurasiMenit()      { return durasiMenit; }  // getter durasi film (menit)
    // getGenre: Getter untuk mengambil daftar genre.
    vector<string> getGenre() { return genre; }        // getter daftar genre
    // getSutradara: Getter untuk mengambil atribut sutradara.
    string getSutradara()     { return sutradara; }    // getter nama sutradara

    // ===== setter =====
    // setDurasiMenit: Setter untuk mengubah atribut durasiMenit.
    void setDurasiMenit(int durasiMenit) { this->durasiMenit = durasiMenit; }  // setter durasi film (menit)
    // setGenre: Setter untuk mengubah daftar genre.
    void setGenre(vector<string> genre)  { this->genre = genre; }              // setter daftar genre
    // setSutradara: Setter untuk mengubah atribut sutradara.
    void setSutradara(string sutradara)  { this->sutradara = sutradara; }      // setter nama sutradara

    // getGenreText: Gabungkan daftar genre menjadi satu string dipisah koma.
    string getGenreText() {
        string hasil;                                // penampung genre yang sudah digabung
        for (size_t i = 0; i < genre.size(); i++) {  // telusuri semua genre pada daftar
            if (i > 0) hasil += ", ";                // gunakan koma sebagai pemisah antar genre
            hasil += genre[i];                       // tambahkan genre saat ini ke hasil
        }
        return hasil;                                // kembalikan semua genre dalam satu string
    }

    // getData: Mengambil data Film ditambah atribut bioskop (6 kolom).
    vector<string> getData() {                        // method getData milik FilmBioskop (menutup milik Film)
        vector<string> data = Film::getData();        // ambil 3 kolom dasar dari class Film
        int jam  = durasiMenit / 60;                  // hitung jumlah jam (pembagian integer)
        int sisa = durasiMenit % 60;                  // hitung sisa menit sesudah dibagi jam
        string durasi;
        if (jam > 0) {                                   // jika durasi minimal satu jam
            durasi = to_string(jam) + " jam";            // awali dengan jumlah jam
            if (sisa > 0) durasi += " " + to_string(sisa) + " menit";  // tambahkan sisa menit
        } else {
            durasi = to_string(durasiMenit) + " menit";  // di bawah 1 jam: tampilkan menit saja
        }
        data.push_back(durasi);                          // tambah kolom durasi (format jam/menit)
        data.push_back(getGenreText());                  // tambah kolom genre (digabung koma)
        data.push_back(sutradara);                       // tambah kolom sutradara
        return data;                                     // kembalikan 6 kolom gabungan
    }
};