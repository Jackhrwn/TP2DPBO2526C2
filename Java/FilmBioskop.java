// =====================================================================
// Class level 2 (turunan dari Film). 
// =====================================================================
public class FilmBioskop extends Film {         // deklarasi class level 2: turunan langsung dari Film
    private int durasiMenit;                    // atribut durasi film dalam satuan menit
    private java.util.ArrayList<String> genre;  // daftar genre yang dimiliki film
    private String sutradara;                   // nama sutradara yang menggarap film

    // FilmBioskop: Konstruktor untuk menginisialisasi atribut dasar plus atribut bioskop.
    public FilmBioskop(int id, String judul, int harga, int durasiMenit,  // konstruktor: terima atribut dasar + atribut bioskop
                       java.util.ArrayList<String> genre, String sutradara) {  // lanjutan parameter: daftar genre dan nama sutradara
        super(id, judul, harga);                                          // panggil konstruktor class induk (Film)
        this.durasiMenit = durasiMenit;                                   // simpan durasi film ke atribut
        this.genre = genre;                                               // simpan daftar genre ke atribut
        this.sutradara = sutradara;                                       // simpan nama sutradara ke atribut
    }

    // ===== getter =====
    // getDurasiMenit: Mengembalikan durasi film dalam menit.
    public int getDurasiMenit() {  // getter: mengembalikan durasi film
        return durasiMenit;        // kembalikan durasi dalam menit
    }

    // getGenre: Mengembalikan daftar genre film.
    public java.util.ArrayList<String> getGenre() {  // getter: mengembalikan daftar genre
        return genre;                                // kembalikan daftar genre film
    }

    // getSutradara: Mengembalikan nama sutradara film.
    public String getSutradara() {  // getter: mengembalikan nama sutradara
        return sutradara;           // kembalikan nama sutradara film
    }

    // ===== setter =====
    // setDurasiMenit: Mengubah durasi film dalam menit.
    public void setDurasiMenit(int durasiMenit) {  // setter: mengubah nilai durasi film
        this.durasiMenit = durasiMenit;            // simpan durasi baru ke atribut
    }

    // setGenre: Mengubah daftar genre film.
    public void setGenre(java.util.ArrayList<String> genre) {  // setter: mengubah daftar genre
        this.genre = genre;                                    // simpan daftar genre baru ke atribut
    }

    // setSutradara: Mengubah nama sutradara film.
    public void setSutradara(String sutradara) {  // setter: mengubah nama sutradara
        this.sutradara = sutradara;               // simpan nama sutradara baru ke atribut
    }

    // getGenreText: Menggabungkan daftar genre menjadi satu string dipisah koma.
    public String getGenreText() {        // gabungkan seluruh genre menjadi satu teks
        return String.join(", ", genre);  // pisahkan tiap genre dengan koma dan spasi
    }

    // getData: Mengembalikan data Film ditambah atribut bioskop (enam kolom).
    public java.util.ArrayList<String> getData() {          // method milik FilmBioskop (menutup milik Film)
        java.util.ArrayList<String> data = super.getData(); // ambil dulu data milik class induk (3 kolom)
        data.add(Main.formatDurasi(durasiMenit));           // kolom durasi film berformat jam/menit (dari Main)
        data.add(getGenreText());                           // kolom genre yang sudah digabung
        data.add(sutradara);                                // kolom nama sutradara
        return data;                                        // kembalikan enam kolom data bioskop
    }
}