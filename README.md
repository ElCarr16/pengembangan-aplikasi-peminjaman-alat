# 📦 Sistem Peminjaman Alat

Aplikasi berbasis web untuk mengelola peminjaman alat secara terstruktur dengan fitur multi-role (Admin, Petugas, dan Peminjam). Dibangun menggunakan **Laravel**.

---

## 🚀 Fitur Utama

### 👤 Peminjam

* Melihat daftar alat tersedia
* Melihat detail alat (seperti marketplace)
* Mengajukan peminjaman
* Melihat riwayat peminjaman

### 🛠️ Admin

* CRUD data alat
* CRUD kategori alat
* Manajemen user
* Melihat data peminjaman & pengembalian

### 🧾 Petugas

* Menyetujui / menolak pengajuan
* Memproses pengembalian alat
* Melihat laporan peminjaman

---

## 🧱 Teknologi yang Digunakan

* **Laravel 12**
* **PHP 8.2**
* **MySQL**
* **Bootstrap (UI)**

---

## 📂 Struktur Folder (Ringkas)

```
app/
 ├── Http/Controllers/
 ├── Models/

resources/
 ├── views/
 │   ├── admin/
 │   ├── petugas/
 │   └── peminjam/

routes/
 └── web.php
```

---

## ⚙️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/ElCarr16/pengembangan-aplikasi-peminjaman-alat.git
cd pengembangan-aplikasi-peminjaman-alat
```

### 2. Install Dependency

```bash
composer install
```

### 3. Copy Environment

```bash
cp .env.example .env
```

### 4. Generate Key

```bash
php artisan key:generate
```

### 5. Setting Database (.env)

```env
DB_DATABASE=sistem_peminjaman_alat
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Migrasi Database

```bash
php artisan migrate
```

### 7. Jalankan Server

```bash
php artisan serve
```

---

## 🔐 Role User

| Role     | Akses             |
| -------- | ----------------- |
| Admin    | Full akses sistem |
| Petugas  | Approval & return |
| Peminjam | Ajukan pinjaman   |

---

## 🔄 Alur Sistem

1. Peminjam memilih alat
2. Mengajukan peminjaman
3. Status: **Pending**
4. Petugas:

   * Approve → alat dipinjam
   * verifikasi alat diambil peminjam
   * Reject → ditolak
5. Peminjam: Mengajukan pengembalian
6. Status: **Pending**
7. Petugas:
   *verifikasi alat
   *kalkulasi denda (jika ada)
   *Pengembalian dikembalikan

## 📸 Tampilan

* Dashboard peminjam (daftar alat)
* Detail alat (mirip e-commerce)
* Form peminjaman
* Panel admin & petugas

---

## 🧠 Catatan Pengembangan

* Validasi stok dilakukan sebelum peminjaman
* Sistem menggunakan middleware role-based
* Activity log untuk mencatat aktivitas user

---

## 📌 TODO / Pengembangan Lanjutan

* [ ] Fix fitur kategori di dashboard peminjam
* [ ] Menambah fitur edit data profil di peminjam
* [ ] update denda

---

## 👨‍💻 Developer

**Fajar Sidik**

---

## 📜 Lisensi

Project ini dibuat untuk keperluan pembelajaran dan pengembangan sistem informasi.

---
