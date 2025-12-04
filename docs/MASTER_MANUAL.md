# 📘 MASTER MANUAL: ITLG Notification System

**Last Updated**: 4 Desember 2025  
**Version**: 2.0 (Premium UI & Smart Logic)

---

## 🚀 1. Quick Start (Testing)

Cara tercepat untuk mengetes sistem notifikasi tanpa menunggu waktu asli.

### **Step 1: Buat Jadwal Test**
Membuat jadwal dummy yang akan mulai dalam 30 menit (trigger notifikasi).
```bash
php artisan test:create-schedule 30
```

### **Step 2: Trigger Notifikasi**

#### **✅ Opsi Rekomendasi: Manual (Instan)**
Cara paling cepat dan pasti untuk testing di local.
```bash
php artisan schedule:send-reminders
```

#### **Opsi Alternatif: Otomatis (Advanced)**
Hanya gunakan jika ingin simulasi production (Cron Job).
```bash
php artisan schedule:run-loop
```
*Script ini akan menunggu sampai detik ke-00, lalu menjalankan scheduler otomatis setiap menit.*

### **Step 3: Cek Hasil**
Buka browser dan lihat:
1.  **Badge Navbar**: Bertambah.
2.  **Toast Pop-up**: Muncul dari atas (tunggu max 5 detik).
3.  **Halaman Notifikasi**: List notifikasi baru muncul.

---

## ✨ 2. Fitur Utama (New Features)

### **A. Premium Toast Notification ("Ngejreng")**
Pop-up notifikasi yang muncul otomatis di kanan atas layar.
-   **Trigger**: Muncul saat badge notifikasi bertambah (Polling setiap 5 detik).
-   **Desain**: Gradient border, glowing icon, animasi bouncy.
-   **Lokasi**: Muncul di **SEMUA** halaman.

### **B. Click-to-Mark Logic**
Mekanisme membaca notifikasi yang lebih user-friendly.
-   **Before**: Auto-read semua setelah 1 detik (membingungkan).
-   **After**: User harus **klik** notifikasi untuk menandainya sebagai "sudah dibaca".
-   **Visual**: Hijau (Belum dibaca) → Putih (Sudah dibaca).
-   **Badge**: Berkurang 1 setiap kali klik.

### **C. Smart Profile Tabs**
Tab jadwal di halaman profil otomatis terbuka sesuai hari ini.
-   **Senin - Jumat**: Buka tab hari ini.
-   **Sabtu - Minggu**: Buka tab **Jumat** (fallback).

---

## 🛠️ 3. Essential Commands

Kumpulan command penting untuk developer/admin.

| Command | Fungsi | Kapan Dipakai? |
| :--- | :--- | :--- |
| `php artisan test:create-schedule 30` | Buat jadwal H-30 menit | Testing notifikasi |
| `php artisan schedule:send-reminders` | Kirim notifikasi manual | Testing / Debugging |
| `php artisan notifications:view --today` | Lihat list notifikasi di DB | Verifikasi data |
| `php artisan notifications:send-missed` | Kirim notifikasi yang terlewat | Server mati/restart |
| `php artisan schedule:run` | Jalanin scheduler 1x | Production (Cron) |

---

## ⚙️ 4. Production Setup

Untuk server production (Live), jangan gunakan command manual. Gunakan **Task Scheduler**.

### **Windows Task Scheduler**
1.  Create Basic Task.
2.  Trigger: **Daily**, Repeat task every **1 minute**.
3.  Action: Start a program.
    *   **Program**: `php.exe` path
    *   **Arguments**: `artisan schedule:run`
    *   **Start in**: Project folder path

### **Linux (Cron)**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## ❓ 5. Troubleshooting Common Issues

### **Q: Toast tidak muncul saat test manual?**
**A**: Tunggu maksimal **5 detik**. Browser melakukan polling setiap 5 detik untuk cek notifikasi baru. Pastikan Anda stay di halaman dashboard.

### **Q: Command `schedule:run` tidak mengirim notifikasi?**
**A**: Laravel scheduler hanya jalan di detik ke-00 (`02:00:00`). Jika Anda run di `02:00:15`, command akan skip.
**Solusi**: Gunakan `php artisan schedule:send-reminders` untuk testing instan.

### **Q: Notifikasi double?**
**A**: Sistem punya proteksi `withoutOverlapping()` dan cek database. Jika terjadi, cek apakah ada 2 instance scheduler yang berjalan bersamaan.

---

## 📂 6. File Struktur Penting

-   **Logic Notifikasi**: `app/Console/Commands/SendScheduleReminders.php`
-   **Tampilan Toast**: `resources/views/layouts/main.blade.php`
-   **Tampilan Halaman Notif**: `resources/views/notifikasi.blade.php`
-   **Logic Profil**: `resources/views/profile.blade.php`
-   **Scheduler Config**: `app/Console/Kernel.php`

---

**Dokumen ini merangkum 18 file dokumentasi menjadi 1 panduan praktis.**
