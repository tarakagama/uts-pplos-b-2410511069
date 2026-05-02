"# UTS Sistem Booking Lapangan - Microservices" 
    
    Nama        : Taraka Zubair Gama
    NIM         : 2410511069

# Field Service - Microservices Project (UTS)

Proyek ini adalah sistem manajemen penyewaan lapangan olahraga menggunakan arsitektur **Microservices**. Dibangun menggunakan **Laravel 11** dan menggunakan **API Gateway** sebagai titik masuk tunggal.

## 🚀 Arsitektur Sistem

Sistem ini terdiri dari beberapa layanan:
- **API Gateway (Port 5000):** Proxy utama, Rate Limiting, dan routing.
- **Auth Service (Port 8000):** Manajemen User, JWT Authentication, & OAuth 2.0.
- **Field Service (Port 8001):** Pengelolaan data lapangan, fasilitas, dan jadwal.
- **Booking Service (Port 8002):** Pengelolaan transaksi penyewaan.

## 🛠️ Tech Stack
- **Framework:** Laravel 11
- **Database:** MySQL
- **Auth:** JWT (Tymon/jwt-auth)
- **Gateway:** Laravel HTTP Client (Proxying)

## 📋 Fitur Utama
1. **Authentication:** Login, Me, Logout, dan Refresh Token via JWT.
2. **Pagination:** Menampilkan data lapangan secara bertahap (10 data per halaman).
3. **Filtering:** Mencari lapangan berdasarkan `category_id`.
4. **Relasi Database:** Data lapangan terelasi dengan tabel Kategori, Fasilitas, dan Jadwal.
5. **Security:** Rate Limiting pada Gateway (60 request/menit).

## ⚙️ Cara Instalasi

1. **Clone Repository:**
   ```bash
   git clone <url-repository-kamu>
   
2. Setup Database:

   Import file database.sql ke MySQL kamu.
   Buat 3 database berbeda (atau 1 database bersama sesuai konfigurasi .env).

3. Konfigurasi .env:
   Pastikan setiap service memiliki file .env dan JWT_SECRET yang sinkron.
   
   Contoh .env Gateway:
   AUTH_SERVICE_URL=[http://127.0.0.1:8000](http://127.0.0.1:8000)
   FIELD_SERVICE_URL=[http://127.0.0.1:8001](http://127.0.0.1:8001)
   BOOKING_SERVICE_URL=[http://127.0.0.1:8002](http://127.0.0.1:8002)

4. Jalankan Service:
   Buka terminal di setiap folder service dan jalankan:
   php artisan serve --port=5000 (untuk gateway)
   php artisan serve --port=8000 (untuk auth)
   php artisan serve --port=8001 (untuk booking)
   php artisan serve --port=8002 (untuk fields)

5. Daftar Endpoint

    Authentication Service
    Login: POST /api/login = Digunakan untuk mendapatkan JWT Token.
    Get Profile: GET /api/auth/me = Mengambil data profil user yang sedang login (Membutuhkan Token).
    Refresh Token: POST /api/refresh = Memperbarui masa berlaku token JWT.
    Logout: POST /api/logout = Menghapus/me-nonaktifkan token saat ini.
    GitHub Login: GET /api/auth/github = Redirect ke autentikasi pihak ketiga via OAuth 2.0.

    Field Service
    Daftar Lapangan: GET /api/fields = Mendukung fitur Pagination (contoh: ?page=1).
    Filter Lapangan: GET /api/fields?category_id={id} = Mencari lapangan berdasarkan ID kategori tertentu.
    Detail Lapangan: GET /api/fields/{id} = Menampilkan detail lapangan lengkap dengan relasi Fasilitas dan Jadwal.

    Booking Service
    Buat Pesanan: POST /api/bookings = Membuat reservasi lapangan baru. Sistem akan otomatis menghitung harga dengan memanggil data dari Field Service secara internal.
    Riwayat Pesanan: GET /api/bookings = Menampilkan semua daftar booking yang pernah dibuat oleh user.
    Batalkan Pesanan: DELETE /api/bookings/{id} = Menghapus atau membatalkan reservasi berdasarkan ID Booking.