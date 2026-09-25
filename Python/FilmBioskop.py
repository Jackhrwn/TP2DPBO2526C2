
from Film import Film  # import class Film sebagai induk

class FilmBioskop(Film):
    """Class level 2 (turunan Film)"""

    # __init__: Inisialisasi objek FilmBioskop plus durasi, genre, dan sutradara.
    def __init__(self, id:int, judul:str, harga:int, durasi_menit:int, genre:list, sutradara:str):
        super().__init__(id, judul, harga)                 # panggil konstruktor class Film
        self.__durasi_menit = durasi_menit                 # simpan durasi tayang film (menit)
        self.__genre = genre if genre is not None else []  # simpan genre; kosong bila None
        self.__sutradara = sutradara                       # simpan nama sutradara

    # ===== getter =====
    # get_durasi_menit: Mengembalikan durasi film (menit).
    def get_durasi_menit(self)->int:
        return self.__durasi_menit                         # kembalikan durasi film (menit)

    # get_genre: Mengembalikan daftar genre film.
    def get_genre(self)->list:
        return self.__genre                                # kembalikan daftar genre

    # get_sutradara: Mengembalikan nama sutradara film.
    def get_sutradara(self)->str:
        return self.__sutradara                            # kembalikan nama sutradara

    # ===== setter =====
    # set_durasi_menit: Mengisi ulang durasi film (menit).
    def set_durasi_menit(self, durasi_menit:int)->None:
        self.__durasi_menit = durasi_menit                 # isi ulang durasi film (menit)

    # set_genre: Mengisi ulang daftar genre film.
    def set_genre(self, genre:list)->None:
        self.__genre = genre                               # isi ulang daftar genre

    # set_sutradara: Mengisi ulang nama sutradara film.
    def set_sutradara(self, sutradara:str)->None:
        self.__sutradara = sutradara                       # isi ulang nama sutradara

    # get_genre_text: Menggabungkan daftar genre menjadi satu string (dipisah koma).
    def get_genre_text(self)->str:
        """Menggabungkan daftar genre menjadi satu string (dipisah koma)."""
        return ", ".join(self.__genre)                     # gabungkan genre dengan pemisah koma

    # get_data: Mengembalikan data Film + atribut bioskop (6 kolom).
    def get_data(self)->list:
        """Mengembalikan data Film + atribut bioskop (6 kolom)."""
        data = super().get_data()                          # ambil data milik class Film
        # durasi diformat jam-menit langsung di method milik class (tanpa fungsi bantu)
        jam, sisa = divmod(self.__durasi_menit, 60)        # menit dibagi 60 menjadi jam dan sisa
        if jam > 0:                                        # sudah satu jam atau lebih?
            durasi = f"{jam} jam"                          # tampilkan dalam satuan jam
            if sisa > 0:                                   # masih ada sisa menit?
                durasi += f" {sisa} menit"                 # tambahkan sisa menit ke hasil
        else:                                              # belum genap satu jam
            durasi = f"{self.__durasi_menit} menit"        # di bawah 60 menit: tampilkan menit
        # sambungkan data Film dengan atribut khusus bioskop
        data.extend([durasi, self.get_genre_text(), self.__sutradara])
        return data                                        # kembalikan 6 kolom data film bioskop