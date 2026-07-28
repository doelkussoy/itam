# IT Asset Management (ITAM)

Aplikasi Sistem Manajemen Aset IT dan Helpdesk yang komprehensif, dibangun menggunakan framework Laravel. Aplikasi ini membantu perusahaan dan departemen IT dalam melacak, mengelola, dan mengoptimalkan aset IT, sumber daya jaringan, serta operasional layanan dukungan teknis (helpdesk).

## Fitur Utama

- **Manajemen Hak Akses (RBAC):** Keamanan akses berbasis peran pengguna (Super Admin, Admin, dan User).
- **Manajemen Data Master:** Kemudahan dalam mengelola data Departemen, Vendor, Merk (Brand), Lokasi, Kategori Aset, Karyawan, serta Master Data PIC IT.
- **Manajemen Siklus Hidup Aset:** Melacak aset mulai dari pengadaan hingga pembuangan (disposal). Proses peminjaman (checkout) dan pengembalian (checkin) aset kepada karyawan, serta pembuatan label aset (QR Code).
- **Manajemen Jaringan:** Mengelola VLAN dan Alamat IP secara terstruktur, dilengkapi dengan fitur *ping* otomatis untuk memonitor status IP address perangkat secara *real-time*.
- **Lisensi & Kredensial:** Menyimpan dan mengelola Lisensi Software serta *Password Vault* (Brankas Password) dengan aman.
- **Helpdesk & Ticketing:** Sistem tiket pelaporan gangguan IT yang dilengkapi dengan kategori sesuai *jobdesk*, PIC penanggung jawab, template otomatis untuk judul pelaporan, serta pelacakan *timeline* (waktu pembuatan dan waktu penyelesaian tiket).
- **Import & Export Data:** Mendukung Import dan Export data secara massal menggunakan file Excel untuk memudahkan migrasi data dan pembuatan laporan.
- **Multi-Bahasa & Tema Modern:** Mendukung peralihan bahasa (Inggris/Indonesia) dan tema modern dengan fitur Mode Gelap (*Dark Mode*) / Mode Terang (*Light Mode*) menggunakan desain *glassmorphism* dan adaptasi komponen UI.

## Teknologi yang Digunakan

- **Backend Framework:** Laravel 12
- **Autentikasi & Otorisasi:** Laravel Breeze, Spatie Laravel Permission
- **Export/Import Excel:** Maatwebsite Excel
- **Frontend & UI:** Bootstrap, AdminLTE 3 (Customisasi modern), Vanilla CSS, Flatpickr

## Instalasi

1. Clone repositori ini ke komputer Anda.
2. Install dependensi PHP:
   ```bash
   composer install
   ```
3. Install dependensi Frontend:
   ```bash
   npm install
   ```
4. Salin file konfigurasi `.env.example` menjadi `.env` dan sesuaikan pengaturan database serta environment lainnya:
   ```bash
   cp .env.example .env
   ```
5. Generate *application key*:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi database (beserta seeder jika diperlukan):
   ```bash
   php artisan migrate
   ```
7. Build aset frontend:
   ```bash
   npm run build
   ```
8. Jalankan *development server*:
   ```bash
   php artisan serve
   ```
   *Catatan: Anda juga dapat menggunakan perintah `composer run dev` yang akan menjalankan `php artisan serve`, queue worker, dan `npm run dev` secara bersamaan.*

## Lisensi

Proyek ini adalah perangkat lunak *open-source* di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
