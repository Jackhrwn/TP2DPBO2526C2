import java.io.BufferedReader;     // pembaca input baris demi baris 
import java.io.InputStreamReader;  // sumber aliran masukan sistem
import java.text.DecimalFormat;    // pola format angka ribuan
import java.text.NumberFormat;     // objek format angka standar
import java.util.ArrayList;        // daftar dinamis berisi objek film
import java.util.Arrays;           // ubah array menjadi List untuk data awal
import java.util.Collections;      // penyedia Collections.sort untuk pengurutan daftar
import java.util.Locale;           // pengaturan lokal Inggris untuk format ribuan

public class Main {                // deklarasi class utama program

    // ====== Kode warna terminal ======
    static final String BIRU   = "\033[34m";  // warna biru untuk garis dan border tabel
    static final String HIJAU  = "\033[32m";  // warna hijau untuk pesan berhasil
    static final String MERAH  = "\033[31m";  // warna merah untuk pesan error
    static final String KUNING = "\033[33m";  // warna kuning untuk judul dan opsi pilihan
    static final String RESET  = "\033[0m";   // teks reset agar warna kembali normal

    // ====== Nama kolom tabel (semua atribut dari 3 class) ======
    static final String[] KOLOM = {                             // daftar nama kolom tabel gabungan semua class
        "ID", "Judul", "Harga", "Durasi", "Genre",              // kolom milik class Film dan FilmBioskop
        "Sutradara", "Studio", "Teknik Animasi", "Rating Usia"  // kolom milik class FilmBioskop dan FilmAnimasi
    };

    static BufferedReader br;  // pembaca input baris demi baris 

    // BarisData: Pasangan id dan isi baris untuk keperluan pengurutan tabel.
    static class BarisData {                          // wadah pasangan (id, baris data)
        int id;                                       // id film (dipakai untuk mengurutkan)
        ArrayList<String> kolom;                      // isi baris tabel milik objek
        BarisData(int id, ArrayList<String> kolom) {  // konstruktor pasangan
            this.id = id;                             // simpan id film
            this.kolom = kolom;                       // simpan isi baris tabel
        }
    }

    // ====== Fungsi teks berwarna ======
    // teksHijau: Membungkus teks dengan kode warna hijau.
    static String teksHijau(String teks) {  // bungkus teks dengan kode warna hijau
        return HIJAU + teks + RESET;        // gabungkan warna hijau + teks + reset
    }

    // teksMerah: Membungkus teks dengan kode warna merah.
    static String teksMerah(String teks) {  // bungkus teks dengan kode warna merah
        return MERAH + teks + RESET;        // gabungkan warna merah + teks + reset
    }

    // formatRupiah: Memformat angka menjadi teks Rupiah (contoh: Rp 25.000).
    static String formatRupiah(int nilai) {                      // format angka keras menjadi teks Rupiah
        NumberFormat fmt = NumberFormat.getInstance(Locale.US);  // ambil format angka standar untuk pemisah ribuan
        DecimalFormat df = (DecimalFormat) fmt;                  // ubah objek format menjadi DecimalFormat
        df.applyPattern("#,###");                                // terapkan pola ribuan tanpa angka desimal
        return "Rp " + df.format(nilai).replace(',', '.');       // format angka lalu ubah koma menjadi titik
    }

    // formatDurasi: Memformat durasi menit menjadi teks jam dan menit.
    static String formatDurasi(int menit) {                // format durasi menit menjadi teks jam-menit
        int jam  = menit / 60;                             // hitung jumlah jam dari total menit
        int sisa = menit % 60;                             // simpan sisa menit yang tidak genap satu jam
        if (jam > 0) {                                     // jika durasi sudah satu jam atau lebih
            String hasil = jam + " jam";                   // mulai teks hasil dari bagian jam
            if (sisa > 0) hasil += " " + sisa + " menit";  // tambahkan sisa menit jika masih tersisa
            return hasil;                                  // kembalikan hasil berformat jam-menit
        }
        return menit + " menit";                           // durasi di bawah satu jam: cukup menit saja
    }

    static final int LEBAR_PROMPT = 35;                    // lebar label prompt agar tanda titik dua sejajar

    // cetakPrompt: Mencetak label prompt rata kiri selebar LEBAR_PROMPT.
    static void cetakPrompt(String label) {  // cetak label rata kiri selebar LEBAR_PROMPT
        // keluarkan label lalu tanda ':' dan spasi
        System.out.print(String.format("%-" + LEBAR_PROMPT + "s", label) + ": ");  // label rata kiri lalu tanda titik dua
    }

    // ====== Membaca teks dengan prompt sejajar ======
    // bacaTeks: Membaca teks dari user memakai prompt yang sejajar.
    static String bacaTeks(String label) throws Exception {  // baca teks memakai label prompt yang sejajar
        cetakPrompt(label);                                  // tampilkan label sesuai kolom yang diminta
        String baris = br.readLine();                        // baca satu baris mentah dari konsol
        if (baris == null) {                                 // jika input sudah habis (end of file)
            // input habis: tampilkan pesan peringatan merah
            System.out.println(teksMerah(" Program dihentikan. "));  // kabari bahwa program berhenti
            System.exit(0);                                  // hentikan seluruh program karena input kosong
        }
        return baris;                                        // kembalikan hasil bacaan baris dari user
    }

    // inputAngka: Membaca angka dengan validasi nilai minimal dan penanganan error.
    static int inputAngka(String label, int minimum, String pesanKurang) {  // baca angka dengan validasi batas minimum
        while (true) {                                                      // ulangi sampai nomor yang dimasukkan valid
            cetakPrompt(label);                                             // tampilkan label sesuai kolom yang diminta
            try {                                                           // cegah program berhenti jika input bukan angka
                String baris = br.readLine();                   // baca satu baris mentah dari konsol
                if (baris == null) {                            // jika input sudah habis (end of file)
                    // input habis: tampilkan pesan peringatan merah
                    System.out.println(teksMerah(" Program dihentikan. "));  // kabari bahwa program berhenti
                    System.exit(0);                             // hentikan seluruh program karena input kosong
                }
                int angka = Integer.parseInt(baris);            // ubah teks input menjadi bilangan bulat
                if (angka < minimum) {                                      // jika angka lebih kecil dari batas minimum
                    System.out.println(teksMerah(pesanKurang) + "\n");      // tampilkan pesan peringatan nilai terlalu kecil
                    continue;                                               // minta input ulang kepada user
                }
                return angka;                                               // angka valid: langsung kembalikan nilainya
            } catch (NumberFormatException e) {                             // tangkap kesalahan ketika input bukan angka
                // cetak pesan error saat input bukan angka
                System.out.println(teksMerah("Input harus berupa angka! Silakan coba lagi.\n"));  // pesan: input harus angka
            } catch (Exception e) {                                         // tangkap error lain di luar kendali program
                System.exit(0);                                             // hentikan program jika terjadi error fatal
            }
        }
    }

    // bacaId: Membaca ID film dengan validasi berlapis (negatif, nol, atau sudah dipakai).
    static int bacaId(ArrayList<Film> biasa, ArrayList<FilmBioskop> bioskop,
                      ArrayList<FilmAnimasi> animasi) throws Exception {     // baca ID film disertai validasi berlapis
        while (true) {                                                       // ulangi sampai ID yang dimasukkan benar-benar valid
            cetakPrompt("ID film (angka)");                                  // tampilkan label khusus kolom ID film
            int id;                                                          // wadah nilai ID yang sedang diuji
            try {                                                            // cegah program berhenti jika input bukan angka
                String baris = br.readLine();                    // baca satu baris mentah dari konsol
                if (baris == null) {                             // jika input sudah habis (end of file)
                    // input habis: tampilkan pesan peringatan merah
                    System.out.println(teksMerah(" Program dihentikan. "));  // kabari bahwa program berhenti
                    System.exit(0);                              // hentikan seluruh program karena input kosong
                }
                id = Integer.parseInt(baris);                    // ubah teks input ID menjadi bilangan bulat
            } catch (NumberFormatException e) {                  // tangkap kesalahan ketika input bukan angka
                // tampilkan pesan error lalu ulangi input
                System.out.println(teksMerah("Input harus berupa angka! Silakan coba lagi.\n"));  // pesan: input harus angka
                continue;                                                    // lanjutkan ke iterasi berikutnya
            }

            if (id < 0) {                                                    // jika ID bernilai negatif
                System.out.println(teksMerah("ID tidak boleh negatif!\n"));  // tampilkan pesan bahwa ID tidak boleh negatif
                continue;                                                    // ulangi input ID dari awal
            }
            if (id == 0) {                                                   // jika ID bernilai nol
                // tampilkan pesan bahwa ID harus mulai dari 1
                System.out.println(teksMerah("ID harus angka mulai dari 1!\n"));  // pesan: ID nol ditolak
                continue;                                                    // ulangi input ID dari awal
            }

            boolean dipakai = false;                                         // penanda apakah ID sudah terpakai
            for (Film f : biasa) {                                           // periksa wadah film biasa
                if (f.getId() == id) dipakai = true;                         // tandai bahwa ID telah dipakai
            }
            if (!dipakai) {                                                  // belum dipakai di wadah biasa?
                for (FilmBioskop f : bioskop) {                              // periksa wadah film bioskop
                    if (f.getId() == id) dipakai = true;                     // tandai bahwa ID telah dipakai
                }
            }
            if (!dipakai) {                                                  // belum dipakai di wadah bioskop?
                for (FilmAnimasi f : animasi) {                              // periksa wadah film animasi
                    if (f.getId() == id) dipakai = true;                     // tandai bahwa ID telah dipakai
                }
            }
            if (dipakai) {                                                   // jika ID ternyata sudah dipakai
                // tampilkan pesan bahwa ID sudah dipakai
                System.out.println(teksMerah("ID sudah dipakai! Gunakan ID lain.\n"));  // pesan: ID duplikat
                continue;                                                    // minta user memasukkan ID yang lain
            }
            return id;                                                       // ID valid: langsung kembalikan nilainya
        }
    }

    // cetakGaris: Mencetak satu garis kotak dengan karakter pengisi tertentu.
    static void cetakGaris(String tengah, int panjang) {                      // cetak satu garis kotak dengan karakter pengisi
        System.out.print(BIRU + "+" + tengah.repeat(panjang) + "+" + RESET);  // gabungkan border kiri, pengisi, dan border kanan
        System.out.println();                                                 // pindah ke baris berikutnya
    }

    // cetakJudul: Mencetak judul di tengah baris kotak.
    static void cetakJudul(String judul, int panjang) {     // cetak judul di tengah baris kotak
        int kiri = (panjang - judul.length()) / 2;          // spasi kiri agar seimbang
        System.out.print(BIRU + "|" + RESET);               // border kiri kotak berwarna biru
        System.out.print(KUNING + " ".repeat(kiri) + judul  // spasi kiri lalu judul dengan warna kuning
                         // lanjutan spasi di kanan hingga selebar kotak
                         + " ".repeat(panjang - kiri - judul.length()) + RESET);
        System.out.println(BIRU + "|" + RESET);             // border kanan kotak berwarna biru
    }

    // cetakBaris: Mencetak satu baris isi dengan border kiri dan kanan kotak.
    static void cetakBaris(String isi, int panjang, String warna) {             // cetak satu baris isi dengan border kiri dan kanan
        System.out.print(BIRU + "|" + RESET + " ");                             // border kiri diikuti satu spasi
        if (!warna.isEmpty()) System.out.print(warna);                          // aktifkan warna jika parameter warna diisi user
        System.out.print(isi);                                                  // cetak teks isi baris kotak
        if (!warna.isEmpty()) System.out.print(RESET);                          // matikan warna kembali agar tidak mengganggu
        System.out.print(" ".repeat(Math.max(0, panjang - 1 - isi.length())));  // pad dengan spasi agar lebar baris pas di kotak
        System.out.println(BIRU + "|" + RESET);                                 // border kanan lalu pindah ke baris baru
    }

    // cetakKotakPilihan: Mencetak kotak berisi daftar pilihan bernomor.
    static void cetakKotakPilihan(String caption, String[] opsiBaris) {  // kotak berisi daftar pilihan bernomor
        int panjang = 2;                                                 // lebar awal kotak paling kecil
        for (String isi : opsiBaris) {                                   // ukur lebar pada setiap baris opsi
            panjang = Math.max(panjang, 1 + isi.length());               // simpan lebar terpanjang ditambah satu spasi
        }
        panjang += 2;                                                    // sedikit ruang ekstra
        System.out.println(caption);                                     // cetak keterangan di atas kotak
        cetakGaris("-", panjang);                                        // garis atas kotak memakai karakter '-'
        for (String isi : opsiBaris) {                                   // tampilkan seluruh baris opsi pilihan
            cetakBaris(isi, panjang, KUNING);                            // setiap opsi dicetak dengan warna kuning
        }
        cetakGaris("-", panjang);                                        // garis bawah penutup kotak
        System.out.println();                                            // sisakan satu baris kosong sebelum konten
    }

    // ===== tabel kolom dinamis =====
    // cetakGarisTabel: Mencetak garis pemisah tabel kolom dinamis.
    static void cetakGarisTabel(int[] lebar) {          // cetak garis pemisah tabel kolom dinamis
        System.out.print(BIRU + "+");                   // border kiri berupa tanda '+'
        for (int w : lebar) {                           // tampilkan tiap lebar kolom yang terhitung
            System.out.print("-".repeat(w + 2) + "+");  // isi tiap kolom dengan tanda '-' selebar w+2
        }
        System.out.println(RESET);                      // reset warna lalu pindah ke baris baru
    }

    // cetakBannerTabel: Mencetak banner judul menyatu selebar grid tabel.
    static void cetakBannerTabel(String judul, int[] lebar) {  // banner judul menyatu selebar grid tabel
        int total = 1;                                         // total lebar satu garis tabel
        for (int w : lebar) {                                  // jumlahkan semua lebar kolom
            total += w + 3;                                    // tambahkan lebar spasi dan border tiap kolom
        }
        cetakGaris("=", total - 2);                            // batas atas banner (selebar tabel)
        cetakJudul(judul, total - 2);                          // judul kuning di tengah, selebar tabel
        cetakGaris("=", total - 2);                            // pemisah judul vs tabel (gaya TAMBAH)
        cetakGarisTabel(lebar);                                // grid atas tabel
    }

    // cetakBarisTabel: Mencetak satu baris tabel sesuai lebar kolom.
    static void cetakBarisTabel(String[] isi, int[] lebar, boolean judulKolom) {  // cetak satu baris tabel sesuai lebar kolom
        System.out.print(BIRU + "|" + RESET);                            // border kiri baris tabel berwarna biru
        for (int i = 0; i < isi.length; i++) {                           // tampilkan semua kolom pada baris ini
            String teks = String.format("%-" + lebar[i] + "s", isi[i]);  // pad isi kolom menjadi selebar kolomnya
            if (judulKolom) {                                            // jika ini baris judul kolom
                teks = KUNING + teks + RESET;                            // beri warna kuning pada teks judul kolom
            }
            System.out.print(" " + teks + " ");                          // cetak isi kolom diapit satu spasi
            System.out.print(BIRU + "|" + RESET);                        // sisi "|" berwarna biru
        }
        System.out.println();                                            // tutup baris tabel
    }

    // cetakKotakJudulOpsi: Mencetak kotak gabungan judul di atas dan daftar opsi.
    static void cetakKotakJudulOpsi(String judul, String[] opsiBaris) {  // kotak gabungan judul di atas dan daftar opsi
        int panjang = Math.max(40, judul.length() + 4);                  // lebar awal: minimal 40 karakter
        for (String isi : opsiBaris) {                                   // tinjau lebar setiap baris opsi
            panjang = Math.max(panjang, isi.length() + 4);               // sesuaikan bila ada opsi lebih panjang
        }
        cetakGaris("=", panjang);                                        // garis atas kotak memakai karakter '='
        cetakJudul(judul, panjang);                                      // judul dicetak di tengah kotak
        cetakGaris("=", panjang);                                        // pemisah judul vs opsi
        for (String isi : opsiBaris) {                                   // tampilkan seluruh baris opsi pilihan
            cetakBaris(isi, panjang, KUNING);                            // setiap opsi dicetak dengan warna kuning
        }
        cetakGaris("=", panjang);                                        // garis bawah penutup kotak
        System.out.println();                                            // sisakan satu baris kosong setelah kotak
    }

    // pilihTeknik: Memilih teknik animasi lewat nomor opsi.
    static String pilihTeknik() throws Exception {                            // pilih teknik animasi lewat nomor opsi
        String[] opsi = {"2D", "3D", "Stop Motion", "CGI", "Motion Capture",  // daftar teknik animasi bagian pertama
                         "Animatronik", "GoMotion", "Cut Out", "Rotoscope",   // daftar teknik animasi bagian kedua
                         "Plastinasi", "Pixilasi", "Grafik Gerak"};           // daftar teknik animasi bagian ketiga
        String[] barisOpsi = new String[opsi.length];                         // wadah baris opsi bernomor untuk kotak pilihan
        for (int i = 0; i < opsi.length; i++) {                               // buat nomor urut untuk setiap teknik
            barisOpsi[i] = "  [" + (i + 1) + "] " + opsi[i];                  // susun teks opsi "[nomor] nama teknik"
        }
        cetakKotakPilihan("Pilih teknik animasi yang tersedia:", barisOpsi);  // tampilkan kotak berisi semua opsi teknik

        int pilihan;                                                          // wadah nomor pilihan dari user
        do {                                                                  // ulangi sampai pilihan berada dalam rentang
            // minta nomor pilihan dalam rentang opsi
            pilihan = inputAngka("Pilih teknik animasi (1-" + opsi.length + ")", 1,
                                 "Pilihan harus 1-" + opsi.length + "!");     // lanjutan argumen: pesan jika di luar rentang
            if (pilihan > opsi.length) {                                      // jika pilihan melewati jumlah opsi
                // tampilkan pesan pilihan tidak valid
                System.out.println(teksMerah("Pilihan harus 1-" + opsi.length + "!\n"));  // pesan: pilihan tidak valid
            }
        } while (pilihan > opsi.length);                                      // hentikan perulangan setelah pilihan valid
        switch (pilihan) {                                                    // petakan nomor pilihan ke nama teknik
            case 1:  return "2D";                                             // opsi 1 : teknik animasi 2D
            case 2:  return "3D";                                             // opsi 2 : teknik animasi 3D
            case 3:  return "Stop Motion";                                    // opsi 3 : teknik animasi Stop Motion
            case 4:  return "CGI";                                            // opsi 4 : teknik animasi CGI
            case 5:  return "Motion Capture";                                 // opsi 5 : teknik animasi Motion Capture
            case 6:  return "Animatronik";                                    // opsi 6 : teknik animasi Animatronik
            case 7:  return "GoMotion";                                       // opsi 7 : teknik animasi GoMotion
            case 8:  return "Cut Out";                                        // opsi 8 : teknik animasi Cut Out
            case 9:  return "Rotoscope";                                      // opsi 9 : teknik animasi Rotoscope
            case 10: return "Plastinasi";                                     // opsi 10 : teknik animasi Plastinasi
            case 11: return "Pixilasi";                                       // opsi 11 : teknik animasi Pixilasi
            case 12: return "Grafik Gerak";                                   // opsi 12 : teknik animasi Grafik Gerak
        }
        return null;                                                          // tidak akan tercapai (pilihan sudah divalidasi)
    }

    // pilihRating: Memilih rating usia lewat nomor opsi.
    static String pilihRating() throws Exception {                         // pilih rating usia lewat nomor opsi
        String[] opsi = {"SU", "13+", "17+", "21+"};                       // daftar rating usia penonton yang tersedia
        String[] barisOpsi = new String[opsi.length];                      // wadah baris opsi bernomor untuk kotak pilihan
        for (int i = 0; i < opsi.length; i++) {                            // buat nomor urut untuk setiap rating
            barisOpsi[i] = "  [" + (i + 1) + "] " + opsi[i];               // susun teks opsi "[nomor] nama rating"
        }
        // tampilkan kotak berisi semua opsi rating
        cetakKotakPilihan("Pilih rating usia penonton yang tersedia:", barisOpsi);  // tampilkan kotak berisi semua opsi rating

        int pilihan;                                                       // wadah nomor pilihan dari user
        do {                                                               // ulangi sampai pilihan berada dalam rentang
            // minta nomor pilihan dalam rentang opsi
            pilihan = inputAngka("Pilih rating usia (1-" + opsi.length + ")", 1,
                                 "Pilihan harus 1-" + opsi.length + "!"); 
            if (pilihan > opsi.length) {                                   // jika pilihan melewati jumlah opsi
                // tampilkan pesan pilihan tidak valid
                System.out.println(teksMerah("Pilihan harus 1-" + opsi.length + "!\n"));  // pesan: pilihan tidak valid
            }
        } while (pilihan > opsi.length);                                   // hentikan perulangan setelah pilihan valid
        switch (pilihan) {                                                 // petakan nomor pilihan ke rating usia
            case 1:  return "SU";                                          // opsi 1 : rating usia SU (semua umur)
            case 2:  return "13+";                                         // opsi 2 : rating usia 13+
            case 3:  return "17+";                                         // opsi 3 : rating usia 17+
            case 4:  return "21+";                                         // opsi 4 : rating usia 21+
        }
        return null;                                                       // tidak akan tercapai (pilihan sudah divalidasi)
    }

    // bacaGenre: Membaca jumlah dan daftar genre dengan validasi huruf besar.
    static ArrayList<String> bacaGenre() throws Exception {                     // tanya jumlah genre lalu validasi tiap genre
        int jumlah = inputAngka("Jumlah genre (lebih dari 0)", 1,               // minta jumlah genre yang akan dimasukkan
                                "Input harus lebih dari 0!");                   // lanjutan argumen: pesan jika bernilai nol
        ArrayList<String> genre = new ArrayList<>();                            // wadah daftar genre yang sudah valid
        for (int i = 1; i <= jumlah; i++) {                                     // isi genre satu per satu sesuai jumlah
            boolean validGenre = false;                                         // penanda apakah genre yang diuji valid
            while (!validGenre) {                                               // ulangi sampai genre yang dimasukkan valid
                String g = bacaTeks("Genre ke-" + i + " (awali huruf besar)");  // baca genre dengan label berisi nomor urut
                // syarat valid: tidak kosong dan diawali huruf besar
                validGenre = !g.isEmpty() && Character.isUpperCase(g.charAt(0));  // syarat valid: non-kosong & huruf besar
                if (validGenre) {                                               // jika genre sudah memenuhi syarat
                    genre.add(g);                                               // tambahkan genre ke dalam daftar
                } else {                                                        // jika genre belum memenuhi syarat
                    // tampilkan pesan agar huruf awal memakai huruf besar
                    System.out.println(teksMerah("Huruf awal genre harus huruf besar!\n"));  // pesan: huruf awal harus besar
                }
            }
        }
        return genre;                                                           // kembalikan daftar genre yang sudah valid
    }

    // tampilkanTabel: Menampilkan seluruh data dari 3 wadah berjenis dalam satu tabel dinamis.
    static void tampilkanTabel(String judul, ArrayList<Film> biasa,
                               ArrayList<FilmBioskop> bioskop, ArrayList<FilmAnimasi> animasi) {   // tampilkan seluruh data
        int jumlah = biasa.size() + bioskop.size() + animasi.size();   // jumlah seluruh film
        if (jumlah == 0) {                                             // jika tidak ada data sama sekali
            System.out.println("Belum ada data film.");                // cetak pesan bahwa belum ada data
            return;                                                    // langsung akhiri fungsi tanpa mencetak tabel
        }

        // kumpulkan pasangan (id, baris) dari wadah berjenis masing-masing
        ArrayList<BarisData> urut = new ArrayList<>();                 // wadah pasangan (id, baris)
        for (Film f : biasa)                                           // telusuri wadah film biasa
            urut.add(new BarisData(f.getId(), f.getData()));           // ambil id + baris data film biasa
        for (FilmBioskop f : bioskop)                                  // telusuri wadah film bioskop
            urut.add(new BarisData(f.getId(), f.getData()));           // ambil id + baris data film bioskop
        for (FilmAnimasi f : animasi)                                  // telusuri wadah film animasi
            urut.add(new BarisData(f.getId(), f.getData()));           // ambil id + baris data film animasi

        // karena tiap class tinggal di wadah berjenisnya sendiri
        // seluruh data diratakan dulu jadi pasangan (id, baris) supaya bisa diurutkan bersama
        // urutkan pasangan (id, baris) berdasarkan ID menaik memakai Collections.sort 
        Collections.sort(urut, (a, b) -> a.id - b.id);                 // a lebih dulu bila ID-nya lebih kecil

        // siapkan baris-baris tabel: isi kolom milik class-nya, sisanya "-"
        ArrayList<ArrayList<String>> barisData = new ArrayList<>();    // wadah baris data untuk tiap film
        for (BarisData p : urut) {                                     // olah setiap pasangan yang sudah terurut
            ArrayList<String> data = p.kolom;                          // baris data milik objek
            while (data.size() < KOLOM.length) {                       // isi kolom kosong selama kurang dari 9 kolom
                data.add("-");                                         // kolom kosong diisi tanda '-'
            }
            barisData.add(data);                                       // simpan baris data film tersebut
        }

        // hitung lebar tiap kolom = teks terpanjang antara header dan isi
        int[] lebar = new int[KOLOM.length];                                  // wadah lebar setiap kolom tabel
        for (int i = 0; i < KOLOM.length; i++) {                              // hitung lebar pada setiap nama kolom
            lebar[i] = KOLOM[i].length();                                     // lebar awal mengikuti panjang nama kolom
            for (ArrayList<String> baris : barisData) {                       // bandingkan dengan isi pada semua baris data
                if (baris.get(i).length() > lebar[i]) {                       // jika ada isi yang lebih panjang dari lebar
                    lebar[i] = baris.get(i).length();                         // perbarui lebar menjadi panjang isi tersebut
                }
            }
        }

        // ===== cetak tabel =====
        System.out.println();                                                 // sisakan satu baris kosong di atas tabel
        cetakBannerTabel(judul, lebar);                                       // cetak banner judul beserta grid atas
        cetakBarisTabel(KOLOM, lebar, true);                                  // baris judul kolom (kuning)
        cetakGarisTabel(lebar);                                               // garis pemisah antara judul dan isi kolom
        for (ArrayList<String> baris : barisData) {                           // cetak seluruh baris data film
            cetakBarisTabel(baris.toArray(new String[0]), lebar, false);      // ubah baris data menjadi array lalu cetak
        }
        cetakGarisTabel(lebar);                                               // garis penutup bagian bawah tabel
        System.out.println(HIJAU + "Total data : " + jumlah + RESET);         // cetak total data memakai warna hijau
        System.out.println();                                                 // sisakan satu baris kosong di bawah tabel
    }

    // tambahData: Menerima input user untuk menambahkan satu objek film.
    static void tambahData(ArrayList<Film> biasa, ArrayList<FilmBioskop> bioskop,
                           ArrayList<FilmAnimasi> animasi) throws Exception {   // terima input user untuk menambah satu film
        cetakKotakJudulOpsi("TAMBAH DATA FILM",                                 // cetak kotak berisi judul dan tipe film
                new String[]{"  [1] Film biasa   (butuh id, judul, harga)",     // opsi pertama: film biasa
                             // opsi kedua: film bioskop beserta keterangannya
                             "  [2] Film bioskop (butuh durasi, genre, sutradara)",
                             // opsi ketiga: film animasi beserta keterangannya
                             "  [3] Film animasi (butuh studio, teknik animasi, rating)"});

        int tipe;                                                             // wadah tipe film yang dipilih user
        do {                                                                  // ulangi sampai tipe yang dipilih valid
            // minta nomor tipe film 1, 2, atau 3
            tipe = inputAngka("Pilih tipe film (1/2/3)", 1, "Tipe harus 1, 2, atau 3!");  // minta nomor tipe film
            if (tipe > 3) {                                                   // jika nomor tipe melewati angka tiga
                System.out.println(teksMerah("Tipe harus 1, 2, atau 3!\n"));  // tampilkan pesan tipe tidak valid
            }
        } while (tipe > 3);                                                   // hentikan perulangan setelah tipe valid

        // kolom milik class Film 
        int    id    = bacaId(biasa, bioskop, animasi);                       // baca ID yang belum dipakai sebelumnya
        String judul = bacaTeks("Judul film");                                // baca judul film dari user
        // baca harga tiket dalam Rupiah
        int    harga = inputAngka("Harga tiket", 0, "Harga tidak boleh negatif!");  // baca harga tiket dalam Rupiah

        if (tipe == 1) {                                                      // jika tipe satu: hanya atribut dasar Film
            // Film            : <id> <judul> <harga>
            biasa.add(new Film(id, judul, harga));                            // simpan objek Film ke wadah berjenis
        } else {                                                              // jika tipe dua atau tiga: butuh data bioskop
            // kolom milik class FilmBioskop
            // baca durasi film dalam satuan menit
            int durasi = inputAngka("Durasi film (menit)", 0, "Durasi tidak boleh negatif!");  // baca durasi dalam menit
            ArrayList<String> genre = bacaGenre();                            // baca daftar genre dengan validasi huruf besar
            String sutradara = bacaTeks("Nama sutradara");                    // baca nama sutradara dari user

            if (tipe == 2) {                                                  // jika tipe dua: cukup data bioskop
                // FilmBioskop   : <id> <judul> <harga> <durasi> <genre...> <sutradara>
                // simpan objek FilmBioskop ke wadah berjenis
                bioskop.add(new FilmBioskop(id, judul, harga, durasi, genre, sutradara));  // kirim objek ke wadah bioskop
            } else {                                                          // jika tipe tiga: butuh data animasi tambahan
                // kolom milik class FilmAnimasi
                String studio   = bacaTeks("Studio animasi");                 // baca nama studio pembuat film animasi
                String teknik   = pilihTeknik();                              // baca teknik animasi lewat menu pilihan
                String rating   = pilihRating();                              // baca rating usia lewat menu pilihan

                // FilmAnimasi    : <id> <judul> <harga> <durasi> <genre...> <sutradara>
                //                   <studio> <teknik> <rating>
                // simpan objek FilmAnimasi ke wadah berjenis
                animasi.add(new FilmAnimasi(id, judul, harga, durasi, genre, sutradara,
                                            studio, teknik, rating));  // kirim objek ke wadah animasi         
            }
        }
        System.out.println(teksHijau("Data berhasil ditambahkan!\n"));        // cetak pesan data berhasil ditambahkan
    }

    // main: Fungsi utama program daftar film bioskop.
    public static void main(String[] args) throws Exception {                 // titik masuk utama eksekusi program
        br = new BufferedReader(new InputStreamReader(System.in));            // hubungkan pembaca input ke aliran masukan sistem

        ArrayList<Film> daftarBiasa = new ArrayList<>();                      // wadah film biasa (level 1)
        ArrayList<FilmBioskop> daftarBioskop = new ArrayList<>();             // wadah film bioskop (level 2)
        ArrayList<FilmAnimasi> daftarAnimasi = new ArrayList<>();             // wadah film animasi (level 3)

        daftarBiasa.add(new Film(1, "Sejarah", 25000));                       // data awal 1 : film biasa "Sejarah"
        daftarBioskop.add(new FilmBioskop(2, "Kimi No Nawa", 45000, 115,      // data awal 2 : film bioskop "Kimi No Nawa"
                          new ArrayList<>(Arrays.asList("Drama", "Romance")), "Sari"));
        daftarBioskop.add(new FilmBioskop(3, "Ghost In The Cell", 40000, 95,  // data awal 3 : film bioskop "Ghost In The Cell"
                          new ArrayList<>(Arrays.asList("Horor")), "Rina"));
        daftarAnimasi.add(new FilmAnimasi(4, "Konosuba", 50000, 105,          // data awal 4 : film animasi "Konosuba"
                          new ArrayList<>(Arrays.asList("Aksi", "Petualangan")), "Andi","StudioBiru", "3D", "SU"));                 
        daftarAnimasi.add(new FilmAnimasi(5, "Dragon Slayer", 52000, 98,       // data awal 5 : film animasi "Dragon Slayer"
                          new ArrayList<>(Arrays.asList("Fantasi")), "Dewi","StudioUngu", "2D", "SU"));                 

        // tampilkan data awal dalam satu tabel
        tampilkanTabel("DAFTAR FILM AWAL", daftarBiasa, daftarBioskop, daftarAnimasi);  // tampilkan 5 data awal

        // ===== TERIMA INPUT USER UNTUK MENAMBAH DATA  =====
        int n = inputAngka("Masukkan jumlah data yang ingin ditambahkan", 1,   // minta jumlah data tambahan dari user
                           "Input harus lebih dari 0!");                       // lanjutan argumen: pesan jika jumlah nol
        for (int i = 1; i <= n; i++) {                                         // tambahkan data sebanyak n kali
            System.out.println(KUNING + "--- Data ke-" + i + " ---" + RESET);  // cetak penanda nomor data yang sedang diisi
            tambahData(daftarBiasa, daftarBioskop, daftarAnimasi);             // panggil proses penambahan satu film
        }

        // ===== TAMPILKAN SELURUH DATA SETELAH PENAMBAHAN =====
        tampilkanTabel("DAFTAR FILM SETELAH PENAMBAHAN", daftarBiasa, daftarBioskop, daftarAnimasi);  // tampilkan seluruh data terbaru
        System.out.println(teksHijau("Program selesai. Terima kasih!"));       // cetak pesan program selesai memakai warna hijau
    }
}