class Film:
    """Class dasar (level 1)"""

    # __init__: Inisialisasi objek Film dengan ID, judul, dan harga lewat setter.
    def __init__(self, id:int, judul:str, harga:int):
        self.set_id(id)                                                     # set nilai ID lewat setter
        self.set_judul(judul)                                               # set nilai judul lewat setter
        self.set_harga(harga)                                               # set nilai harga lewat setter

    # ===== getter =====
    # get_id: Mengembalikan ID film.
    def get_id(self)->int:
        return self.__id                                                    # kembalikan ID film

    # get_judul: Mengembalikan judul film.
    def get_judul(self)->str:
        return self.__judul                                                 # kembalikan judul film

    # get_harga: Mengembalikan harga film.
    def get_harga(self)->int:
        return self.__harga                                                 # kembalikan harga film

    # ===== setter =====
    # set_id: Mengisi nilai ID film.
    def set_id(self, id:int)->None:
        self.__id = id                                                      # isi nilai ID film

    # set_judul: Mengisi nilai judul film.
    def set_judul(self, judul:str)->None:
        self.__judul = judul                                                # isi nilai judul film

    # set_harga: Mengisi nilai harga film.
    def set_harga(self, harga:int)->None:
        self.__harga = harga                                                # isi nilai harga film

    # get_data: Mengembalikan data milik class Film (3 kolom).
    def get_data(self)->list:
        """Mengembalikan data milik class Film (3 kolom)."""
        # harga diformat Rupiah langsung di method milik class (tanpa fungsi bantu)
        rupiah = "Rp " + f"{self.__harga:,}".replace(",", ".")  # koma ribuan diganti titik
        return [str(self.__id), self.__judul, rupiah]           # data 3 kolom milik class Film