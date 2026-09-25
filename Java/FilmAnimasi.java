// =====================================================================
// Class level 3 (turunan dari FilmBioskop). 
// =====================================================================
public class FilmAnimasi extends FilmBioskop {  // deklarasi class level 3: turunan dari FilmBioskop
    private String studio;                      // atribut nama studio pembuat animasi
    private String teknikAnimasi;               // atribut teknik animasi yang dipakai
    private String ratingUsia;                  // atribut rating usia penonton

    // FilmAnimasi: Konstruktor untuk menginisialisasi seluruh atribut berantai tiga level class.
    public FilmAnimasi(int id, String judul, int harga, int durasiMenit,     // konstruktor: terima seluruh atribut 
                       java.util.ArrayList<String> genre, String sutradara,  // lanjutan parameter: genre, sutradara, studio
                       // parameter lanjutan: teknik animasi dan rating usia
                       String studio, String teknikAnimasi, String ratingUsia) {
        super(id, judul, harga, durasiMenit, genre, sutradara);           // panggil konstruktor class induk (FilmBioskop)
        this.studio = studio;                                             // simpan studio animasi ke atribut
        this.teknikAnimasi = teknikAnimasi;                               // simpan teknik animasi ke atribut
        this.ratingUsia = ratingUsia;                                     // simpan rating usia ke atribut
    }

    // ===== getter =====
    // getStudio: Mengembalikan nama studio animasi.
    public String getStudio() {  // getter: mengembalikan nama studio
        return studio;           // kembalikan nama studio animasi
    }

    // getTeknikAnimasi: Mengembalikan teknik animasi yang dipakai.
    public String getTeknikAnimasi() {  // getter: mengembalikan teknik animasi
        return teknikAnimasi;           // kembalikan teknik animasi yang dipakai
    }

    // getRatingUsia: Mengembalikan rating usia penonton.
    public String getRatingUsia() {  // getter: mengembalikan rating usia
        return ratingUsia;           // kembalikan rating usia penonton
    }

    // ===== setter =====
    // setStudio: Mengubah nama studio animasi.
    public void setStudio(String studio) {  // setter: mengubah nama studio
        this.studio = studio;               // simpan nama studio baru ke atribut
    }

    // setTeknikAnimasi: Mengubah teknik animasi.
    public void setTeknikAnimasi(String teknikAnimasi) {  // setter: mengubah teknik animasi
        this.teknikAnimasi = teknikAnimasi;               // simpan teknik animasi baru ke atribut
    }

    // setRatingUsia: Mengubah rating usia penonton.
    public void setRatingUsia(String ratingUsia) {  // setter: mengubah rating usia
        this.ratingUsia = ratingUsia;               // simpan rating usia baru ke atribut
    }

    // getData: Mengembalikan seluruh data gabungan tiga level class (sembilan kolom).
    public java.util.ArrayList<String> getData() {           // method milik FilmAnimasi (menutup milik FilmBioskop)
        java.util.ArrayList<String> data = super.getData();  // ambil dulu data milik class induk (6 kolom)
        data.add(studio);                                    // kolom studio animasi
        data.add(teknikAnimasi);                             // kolom teknik animasi
        data.add(ratingUsia);                                // kolom rating usia
        return data;                                         // kembalikan sembilan kolom data lengkap
    }
}