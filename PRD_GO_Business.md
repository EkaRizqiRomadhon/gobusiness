# Product Requirement Document (PRD): GO Business

## 1. Informasi Proyek
* **Nama Aplikasi:** GO Business
* **Platform:** Berbasis Website (Responsive)
* **Tujuan:** Membantu UMKM mengelola transaksi, stok, dan laporan keuangan melalui sistem digital yang terintegrasi untuk pengambilan keputusan berbasis data.

## 2. Alur Aplikasi (App Flow)
Sistem berjalan dalam 5 lapisan utama:
1.  **Entry Point — Login/Registrasi:** Pengguna masuk ke akun masing-masing. Sistem menggunakan isolasi data per akun untuk menjamin keamanan.
2.  **Dashboard Utama:** Pusat kendali yang menampilkan ringkasan penjualan harian, stok rendah, dan grafik tren mingguan.
3.  **Empat Modul Inti (Paralel):**
    * **Transaksi:** Input penjualan harian yang tersinkronisasi otomatis dengan stok.
    * **Stok:** Manajemen produk, harga, dan pengaturan ambang batas minimum.
    * **Laporan:** Rekapitulasi penjualan harian/bulanan serta ranking produk terlaris.
    * **Grafik:** Visualisasi tren penjualan (Line, Bar, Pie chart).
4.  **Output Sistem (3 Tingkat):**
    * **Operasional:** Fokus pada transaksi dan pembaruan stok harian.
    * **Taktis:** Evaluasi performa produk dan pencapaian bulanan.
    * **Strategis:** Analisis tren jangka panjang untuk pertumbuhan usaha.
5.  **Pengambilan Keputusan:** Hasil olah data menjadi dasar strategi promosi, stok, dan pengembangan bisnis.

## 3. Kebutuhan Fungsional (Fitur Utama)

| Fitur | Fungsi | Level Manajemen |
| :--- | :--- | :--- |
| **Input Transaksi** | Mencatat penjualan produk, jumlah, dan harga secara real-time. | Operasional |
| **Update Stok Otomatis** | Mengurangi jumlah stok secara otomatis setelah transaksi berhasil. | Operasional |
| **Alert Stok Rendah** | Memberikan notifikasi jika produk mencapai batas minimum stok. | Operasional |
| **Manajemen Produk** | Fitur CRUD (Tambah, Edit, Hapus) produk dan kategori. | Operasional |
| **Laporan Penjualan** | Menghasilkan ringkasan omzet harian dan bulanan. | Taktis |
| **Ranking Produk** | Menampilkan daftar produk terlaris (Best Seller). | Taktis |
| **Visualisasi Tren** | Menampilkan grafik tren penjualan (Line/Bar) dan performa (Pie). | Strategis |

## 4. Kebutuhan Non-Fungsional
* **Keamanan:** Enkripsi password dan isolasi database antar pengguna.
* **Responsivitas:** Antarmuka harus optimal saat diakses melalui smartphone, tablet, maupun desktop.
* **Skalabilitas:** Sistem mampu menangani pertumbuhan data transaksi dalam jumlah besar.
* **User Experience (UX):** Desain yang intuitif agar mudah digunakan oleh pelaku UMKM tanpa latar belakang IT yang mendalam.

## 5. Output Sistem & Hierarki Keputusan
1.  **Level Operasional:** Fokus pada kelancaran arus barang dan uang harian (Input & Update Stok).
2.  **Level Taktis:** Fokus pada efisiensi bulanan (Evaluasi performa & Ranking produk).
3.  **Level Strategis:** Fokus pada arah bisnis ke depan (Analisis tren usaha berbasis data).

---
*Dokumen ini dibuat sebagai panduan pengembangan aplikasi GO Business versi 1.0.*