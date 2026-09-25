
from FilmBioskop import FilmBioskop  # import class FilmBioskop sebagai induk

class FilmAnimasi(FilmBioskop):
    """Class level 3 (turunan FilmBioskop)"""

    # __init__: Inisialisasi objek FilmAnimasi plus studio, teknik animasi, dan rating usia.
    def __init__(self, id:int, judul:str, harga:int, durasi_menit:int, genre:list, sutradara:str,
                 studio:str, teknik_animasi:str, rating_usia:str):
        super().__init__(id, judul, harga, durasi_menit, genre, sutradara)  # panggil konstruktor class FilmBioskop
        self.__studio = studio                                              # simpan nama studio animasi
        self.__teknik_animasi = teknik_animasi                              # simpan teknik animasi (2D/3D/dll.)
        self.__rating_usia = rating_usia                                    # simpan rating usia (SU/13+/17+/21+)

    # ===== getter =====
    # get_studio: Mengembalikan nama studio animasi.
    def get_studio(self)->str:
        return self.__studio                                                # kembalikan nama studio

    # get_teknik_animasi: Mengembalikan teknik animasi film.
    def get_teknik_animasi(self)->str:
        return self.__teknik_animasi                                        # kembalikan teknik animasi

    # get_rating_usia: Mengembalikan rating usia penonton film.
    def get_rating_usia(self)->str:
        return self.__rating_usia                                           # kembalikan rating usia

    # ===== setter =====
    # set_studio: Mengisi ulang nama studio animasi.
    def set_studio(self, studio:str)->None:
        self.__studio = studio                                              # isi ulang nama studio

    # set_teknik_animasi: Mengisi ulang teknik animasi film.
    def set_teknik_animasi(self, teknik_animasi:str)->None:
        self.__teknik_animasi = teknik_animasi                              # isi ulang teknik animasi

    # set_rating_usia: Mengisi ulang rating usia penonton film.
    def set_rating_usia(self, rating_usia:str)->None:
        self.__rating_usia = rating_usia                                    # isi ulang rating usia

    # get_data: Mengembalikan seluruh data (9 kolom): Film + bioskop + animasi.
    def get_data(self)->list:
        """Mengembalikan seluruh data (9 kolom): Film + bioskop + animasi."""
        data = super().get_data()                                           # ambil data milik class FilmBioskop
        # sambungkan data bioskop dengan atribut khusus animasi
        data.extend([self.__studio, self.__teknik_animasi, self.__rating_usia])
        return data                                                         # kembalikan 9 kolom data film animasi