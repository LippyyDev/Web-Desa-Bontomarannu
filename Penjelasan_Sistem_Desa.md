# Analisis Mendalam: Fitur dan Keamanan Website Desa Bontomarannu

Dokumen ini membedah fitur-fitur yang ada di dalam Website Desa Bontomarannu secara rinci (satu per satu per tingkatan pengguna) serta menjabarkan mekanisme keamanan sistem yang diterapkan.

---

## 1. Rincian Fitur Berdasarkan Peran (Role)

Sistem informasi desa ini menggunakan arsitektur *Multi-Role* yang memecah hak akses dan *dashboard* menjadi 4 kategori terpisah (Guest, User, Staff, Admin) demi distribusi tugas yang efisien.

### A. GUEST (Pengunjung Publik / Tanpa Login)
Ini adalah antarmuka publik yang dapat diakses oleh siapa saja. Tujuannya adalah untuk transparansi dan promosi.
1.  **Beranda:** Menampilkan *hero section*, ringkasan berita terbaru, sorotan UMKM dan Pariwisata, serta statistik singkat tentang desa.
2.  **Profil Desa:** Terbagi menjadi tiga sub-fitur:
    *   **Profil:** Menampilkan sejarah terbentuknya desa, visi, misi, dan struktur pemerintahan.
    *   **Geografi:** Memberikan informasi letak geografis, demografi, batas wilayah, dan peta interaktif.
    *   **Perangkat Desa:** Menampilkan daftar anggota aparatur desa yang sedang menjabat lengkap dengan foto dan jabatan.
3.  **Informasi:**
    *   **Berita:** Portal artikel informatif seputar kegiatan desa.
    *   **Pengumuman:** Edaran resmi dan *notice* dari pemerintah desa kepada masyarakat luas.
    *   **Inventaris Desa:** Daftar aset desa yang dipublikasikan sebagai bentuk transparansi (misal: kendaraan dinas, gedung).
4.  **Galeri:** Menampilkan album dokumentasi visual (foto/video) terkait kegiatan dan keindahan desa.
5.  **Potensi Desa:**
    *   **UMKM:** Katalog produk-produk usaha milik warga desa. Publik dapat melihat detail produk, harga, dan kontak penjual.
    *   **Pariwisata:** Daftar destinasi wisata yang ada di desa beserta deskripsi lokasi dan tiket.
6.  **Laporan (Pengaduan):** Halaman yang menjelaskan tata cara pelaporan keluhan atau menampilkan statistik keluhan yang telah diselesaikan.
7.  **Autentikasi (Masuk/Daftar):** Pintu masuk ke dalam sistem. Di sini Guest bisa mendaftar menjadi User, atau mereset *password* jika lupa (dengan Pertanyaan Keamanan).

### B. USER (Warga Desa Terdaftar)
User adalah warga desa yang telah mendaftarkan diri, memiliki akun, dan login ke dalam sistem. Warga memiliki hak akses mandiri.
1.  **Dashboard Warga:** Antarmuka utama setelah warga login. Menampilkan rangkuman ringkas: berapa surat yang sedang diproses, berapa laporan yang ditanggapi, dan notifikasi terbaru.
2.  **Manajemen Profil:** Warga dapat mengubah data pribadi (Nama, NIK, dll), mengganti foto profil, serta mengatur ulang kata sandi dan **Pertanyaan Keamanan**.
3.  **Layanan Warga - Surat Online:** **(Fitur Inti)** Warga dapat mengajukan berbagai jenis permohonan administrasi (seperti Surat Keterangan Usaha, Domisili, dll) langsung dari perangkat mereka dengan mengisi *form* digital. Warga juga dapat memantau status suratnya (*Menunggu*, *Diproses*, *Selesai*, atau *Ditolak*).
4.  **Layanan Warga - Pengaduan:** Fasilitas untuk mengirimkan laporan (misal: jalan rusak, masalah keamanan) lengkap dengan judul, deskripsi, dan lampiran foto bukti. Laporan ini bisa dilacak status penyelesaiannya.
5.  **Usaha & Ekonomi - UMKM Saya:** Fitur pendaftaran produk jualan secara mandiri. Warga yang memiliki usaha dapat mengunggah foto produk, memberi harga, dan deskripsi dari *dashboard* mereka sendiri. Setelah disetujui staf, produk otomatis tayang di halaman Guest.

### C. STAFF (Perangkat Desa / Operator)
Staf adalah perangkat desa yang bertugas memproses operasional administratif desa sehari-hari.
1.  **Dashboard Staff:** Panel statistik operasional, menampilkan indikator seperti "Surat Menunggu Verifikasi", "Aduan Baru", dan "UMKM Baru".
2.  **Layanan Publik - Surat Masuk:** Staf melihat seluruh pengajuan surat dari warga. Staf memverifikasi dokumen warga, memperbarui status (menolak/menyetujui), dan **mengonversi/mencetak** form yang disetujui menjadi dokumen resmi berformat Word/PDF untuk ditandatangani Kepala Desa.
3.  **Layanan Publik - Pengaduan:** Staf membaca laporan masuk dari warga, memberikan tanggapan balik secara sistem, dan mengubah status laporan (misal: *Dalam Pengerjaan*, *Selesai*).
4.  **Manajemen Konten:** Staf memegang kendali (CRUD: Create, Read, Update, Delete) atas artikel **Berita**, **Pengumuman**, dan foto/album **Galeri**.
5.  **UMKM & Pariwisata:** Staf bertindak sebagai *Verifikator* produk UMKM yang diunggah warga. Staf berhak menyetujui atau menolak *listing* produk tersebut. Selain itu, staf menambah dan mengedit data **Pariwisata** desa.
6.  **Manajemen Desa:** Staf dapat menambah/mengedit susunan aparatur **Perangkat Desa**, profil **Geografi**, serta mencatat **Inventaris Aset Desa**.

### D. ADMIN (Administrator Sistem)
Admin memegang kendali atas pondasi pengaturan akun dan *maintenance* akses, tanpa ikut campur urusan surat-menyurat harian.
1.  **Dashboard Admin:** Menampilkan *Overview* seluruh sistem: metrik kinerja, jumlah pengguna per Role, dan sebagainya.
2.  **Manajemen Pengguna (Kelola Akun):** Admin adalah satu-satunya role yang bisa melakukan manajemen UAC (*User Access Control*). Fitur ini mengizinkan Admin untuk membuat akun Staf baru, mereset *password* akun apa saja secara paksa, mengubah role pengguna, dan melakukan *banned* atau menonaktifkan akun yang mencurigakan.

---

## 2. Detail Implementasi Mekanisme Keamanan

Sistem web ini dibangun bukan sekadar "asal jalan", melainkan mengikuti standar *Security by Design* tingkat lanjut.

1.  **8-Layer File Upload Validation (Validasi Upload Ekstrem)**
    Sistem web ini memblokir celah serangan *Upload* dengan 8 lapis keamanan yang di-enforce di backend:
    *   **HTTP Verification**: Menolak upload yang tidak berasal dari form *multipart* browser murni.
    *   **Size Limit & Extension Whitelist**: Pengecekan standar ukuran dan whitelist ekstensi file.
    *   **MIME Server Validation**: Pengecekan berbasis server (`finfo`) yang tak bisa ditipu oleh manipulasi di browser.
    *   **Magic Byte Checking**: Sistem membaca header *binary* langsung dari file (Contoh: file `.php` yang di-*rename* menjadi `.jpg` tetap akan ketahuan dari struktur *byte* aslinya).
    *   **Content Scanning**: Memindai isi file teks/gambar untuk mencari tag `<?php` atau `<script>` yang diselundupkan peretas.
    *   **Path Traversal Prevention**: Mengacak penamaan dan memvalidasi path, memblokir teknik *Directory Traversal* (`../../`).
    *   **Auto WebP Conversion**: Gambar akan dihancurkan struktur data aslinya dan dirender ulang menjadi `.webp`, mematikan *malware payload* yang ditanam peretas dalam *pixel* gambar.

2.  **Security Headers & CSP (Proteksi Eksekusi Browser)**
    Middleware `SecurityHeadersFilter.php` menyuntikkan perintah proteksi secara paksa ke browser pengunjung yang mengakses situs:
    *   **Content-Security-Policy (CSP):** Mencegah *Cross Site Scripting* (XSS) dengan memblokir pemanggilan *script* dari situs asing tak dikenal.
    *   **X-Frame-Options (SAMEORIGIN):** Mencegah web disematkan (*embed*) ke dalam situs *phishing/clickjacking*.
    *   **X-Content-Type-Options (nosniff):** Memaksa browser mematuhi tipe dokumen yang dikirim *server*.

3.  **Pertanyaan Keamanan (Security Questions)**
    Menerapkan verifikasi *2-Factor* sederhana saat pendaftaran, lupa *password*, atau ketika verifikasi identitas, untuk mereduksi risiko pengambilalihan akun (*Account Takeover*).

4.  **Standarisasi Kode Bawaan**
    *   **Anti SQL-Injection:** Tidak ada query manual. Semua database layer dieksekusi dengan *Parameterized Query Builder*.
    *   **CSRF Tokens:** Memerlukan token unik `Cross-Site Request Forgery` di setiap tombol form agar tidak bisa dibajak oleh situs asing.
