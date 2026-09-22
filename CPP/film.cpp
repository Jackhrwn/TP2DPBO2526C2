using namespace std;    // pakai namespace std (tanpa prefiks std::)

class Film { // deklarasi class (level 1), induk 
private:
    int id;             // atribut 1: nomor id film
    string judul;       // atribut 2: judul film
    int harga;          // atribut 3: harga tiket (ditampilkan Rp)

public:
    // Film: Konstruktor untuk mengisi atribut awal id, judul, dan harga.
    Film(int id, string judul, int harga) {  // konstruktor untuk mengisi atribut awal
        this->id    = id;                    // simpan nilai id ke atribut id
        this->judul = judul;                 // simpan nilai judul ke atribut judul
        this->harga = harga;                 // simpan nilai harga ke atribut harga
    }

    // ~Film: Destruktor class dasar 
    ~Film() {}  // destruktor class dasar 

    // ===== getter =====
    // getId: Getter untuk mengambil atribut id.
    int getId()       { return id; }     // getter: amankan atribut id
    // getJudul: Getter untuk mengambil atribut judul.
    string getJudul() { return judul; }  // getter: ambil atribut judul
    // getHarga: Getter untuk mengambil atribut harga.
    int getHarga()    { return harga; }  // getter: ambil atribut harga

    // ===== setter =====
    // setId: Setter untuk mengubah atribut id.
    void setId(int id)          { this->id    = id; }     // setter atribut id
    // setJudul: Setter untuk mengubah atribut judul.
    void setJudul(string judul) { this->judul = judul; }  // setter atribut judul
    // setHarga: Setter untuk mengubah atribut harga.
    void setHarga(int harga)    { this->harga = harga; }  // setter atribut harga

    // getData: Mengambil data film dalam bentuk baris tabel (ID, Judul, Harga).
    vector<string> getData() {                           // ambil data film dalam bentuk baris tabel
        string s = to_string(harga), hasil;              // s = harga jadi string; hasil = string terformat
        for (int i = 0; i < (int)s.size(); i++) {        // telusuri digit dari posisi paling belakang
            hasil = s[s.size() - 1 - i] + hasil;         // tulis digit ke posisi depan hasil
            // sisipkan titik pemisah ribuan setiap kelipatan 3 digit (selain digit terakhir)
            if ((i + 1) % 3 == 0 && i < (int)s.size() - 1) hasil = "." + hasil;
        }
        return { to_string(id), judul, "Rp " + hasil };  // kumpulan 3 kolom milik Film
    }
};