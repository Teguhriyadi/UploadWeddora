# Buku Tamu Digital Pernikahan

<p align="center">
  <strong>Sistem Buku Tamu Digital untuk membantu proses pencatatan dan pengelolaan kehadiran tamu pada acara pernikahan.</strong>
</p>

<p align="center">
  Dibangun menggunakan Laravel 13, MySQL, Bootstrap SB Admin, NEO Object Storage, dan ZXing.
</p>

---

## Tentang Aplikasi

**Buku Tamu Digital Pernikahan** adalah aplikasi berbasis web yang dirancang untuk mempermudah proses registrasi, pencatatan, dan pengelolaan kehadiran tamu dalam acara pernikahan.

Aplikasi ini menyediakan beberapa metode check-in untuk memudahkan proses registrasi tamu, mulai dari **Scan QR Code**, **Selfie**, hingga **pencarian data tamu secara manual**. Selain itu, sistem juga mendukung pencatatan **tamu luar undangan** yang hadir dalam acara.

Selain proses check-in, sistem juga menyediakan fitur untuk **Titip Kehadiran**, **Titip Kado**, **Manajemen Users**, **Kelola Tamu Undangan**, dan **Kelola Laporan**.

---

## Fitur Utama

### Check-In Tamu

Sistem menyediakan beberapa metode untuk melakukan check-in tamu:

- **Scan QR Code**  
  Tamu dapat melakukan check-in menggunakan QR Code yang terdapat pada undangan.

- **Selfie**  
  Tamu dapat melakukan registrasi kehadiran menggunakan foto selfie sebagai dokumentasi kehadiran.

- **Pencarian Manual**  
  Petugas dapat mencari data tamu secara manual apabila QR Code tidak tersedia atau mengalami kendala.

- **Input Tamu Luar**  
  Sistem dapat mencatat tamu yang hadir namun tidak terdaftar dalam daftar tamu undangan.

---

### Titip Kehadiran

Fitur yang memungkinkan tamu untuk memberikan informasi atau konfirmasi kehadiran apabila tidak dapat menghadiri acara secara langsung.

---

### Titip Kado

Fitur untuk membantu mencatat informasi titipan kado dari tamu.

---

### Kelola Tamu Undangan

Admin dapat melakukan pengelolaan data tamu undangan, seperti:

- Menambahkan data tamu.
- Mengubah data tamu.
- Menghapus data tamu.
- Melihat daftar tamu.
- Melakukan pencarian data tamu.

---

### Manajemen Users

Fitur untuk mengelola pengguna yang memiliki akses ke dalam sistem, seperti:

- Menambahkan pengguna.
- Mengubah data pengguna.
- Menghapus pengguna.
- Mengelola akses pengguna.

---

### Kelola Laporan

Sistem menyediakan informasi dan laporan terkait:

- Data kehadiran tamu.
- Total tamu yang hadir.
- Data tamu undangan.
- Data titip kehadiran.
- Data titip kado.
- Dokumentasi kehadiran tamu.

---

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| Laravel 13 | Framework utama aplikasi |
| PHP | Bahasa pemrograman backend |
| MySQL | Database Management System |
| Bootstrap | Framework antarmuka pengguna |
| SB Admin | Template dashboard administrasi |
| NEO Object Storage | Penyimpanan file dan media |
| ZXing | Pembacaan dan pemindaian QR Code |

---

## Metode Check-In

Aplikasi menyediakan beberapa pilihan metode check-in agar proses registrasi tamu dapat dilakukan dengan lebih fleksibel.

```text
                    TAMU DATANG
                         │
                         ▼
                PILIH METODE CHECK-IN
                         │
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
    SCAN QR CODE       SELFIE       SEARCH MANUAL
        │                │                │
        └────────────────┼────────────────┘
                         │
                         ▼
                DATA TAMU DITEMUKAN
                         │
                         ▼
               KEHADIRAN TERCATAT
```

## QR Code Scanner

Sistem menggunakan **ZXing** untuk membantu proses pembacaan dan pemindaian QR Code.

QR Code digunakan sebagai salah satu metode untuk mengidentifikasi tamu undangan secara lebih cepat sehingga proses check-in dapat dilakukan tanpa perlu mencari data tamu secara manual.

---

## Penyimpanan File

Aplikasi menggunakan **NEO Object Storage** untuk menyimpan file dan media yang berkaitan dengan sistem.

Beberapa file yang dapat disimpan meliputi:

- Foto selfie tamu.
- Dokumentasi kehadiran.
- Media atau file pendukung lainnya.

---

## Dashboard Administrasi

Dashboard administrasi digunakan untuk membantu pengelolaan seluruh data dalam sistem.

Admin dapat melakukan:

- Manajemen users.
- Kelola tamu undangan.
- Monitoring kehadiran tamu.
- Kelola titip kehadiran.
- Kelola titip kado.
- Melihat dan mengelola laporan.

---

## Tujuan Sistem

Sistem Buku Tamu Digital Pernikahan ini dikembangkan untuk membantu proses pengelolaan tamu agar menjadi lebih:

- Cepat.
- Praktis.
- Terorganisir.
- Digital.
- Mudah dikelola.

Selain itu, sistem diharapkan dapat mengurangi proses pencatatan kehadiran secara manual dan membantu panitia dalam memantau jumlah tamu yang hadir.

---

## Developer

**Mohammad Ilham Teguhriyadi**

Fullstack Developer

---

## License

Project ini dikembangkan untuk kebutuhan sistem **Buku Tamu Digital Pernikahan**.