// =====================================================================
// Class dasar (level 1) dari multilevel inheritance:
// =====================================================================
public class Film {        // deklarasi class dasar (level 1) 
    private int id;        // atribut 1: nomor unik identitas film
    private String judul;  // atribut 2: judul film
    private int harga;     // atribut 3: harga tiket dalam Rupiah

    // Film: Konstruktor untuk menginisialisasi atribut dasar film (id, judul, harga).
    public Film(int id, String judul, int harga) {  // konstruktor: inisialisasi atribut dasar film
        this.id = id;                               // isi atribut id dari parameter
        this.judul = judul;                         // isi atribut judul dari parameter
        this.harga = harga;                         // isi atribut harga dari parameter
    }

    // ===== getter =====
    // getId: Mengembalikan nilai id film.
    public int getId() {  // getter: mengembalikan nilai id
        return id;        // kembalikan id film
    }

    // getJudul: Mengembalikan judul film.
    public String getJudul() {  // getter: mengembalikan nilai judul
        return judul;           // kembalikan judul film
    }

    // getHarga: Mengembalikan harga tiket film.
    public int getHarga() {  // getter: mengembalikan nilai harga
        return harga;        // kembalikan harga tiket
    }

    // ===== setter =====
    // setId: Mengubah nilai id film.
    public void setId(int id) {  // setter: mengubah nilai id
        this.id = id;            // simpan id baru ke atribut
    }

    // setJudul: Mengubah judul film.
    public void setJudul(String judul) {  // setter: mengubah nilai judul
        this.judul = judul;               // simpan judul baru ke atribut
    }

    // setHarga: Mengubah harga tiket film.
    public void setHarga(int harga) {  // setter: mengubah nilai harga
        this.harga = harga;            // simpan harga baru ke atribut
    }

    // getData: Mengembalikan data milik class Film (tiga kolom).
    public java.util.ArrayList<String> getData() {                       // method polimorfik: data milik class Film
        java.util.ArrayList<String> data = new java.util.ArrayList<>();  // wadah daftar kolom data film
        data.add(String.valueOf(id));                                    // kolom id diubah menjadi teks
        data.add(judul);                                                 // kolom judul film
        data.add(Main.formatRupiah(harga));                              // kolom harga dengan format Rupiah (dari Main)
        return data;                                                     // kembalikan tiga kolom dasar film
    }
}