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

#### **✅ Opsi Rekomendasi: Otomatis (Advanced)**
Hanya gunakan jika ingin simulasi production (Cron Job).
```bash
php artisan schedule:run-slots
```
*Script ini akan menunggu sampai detik ke-00, lalu menjalankan scheduler otomatis setiap menit.*

### **Step 3: Cek Hasil**
Buka browser dan lihat:
1.  **Badge Navbar**: Bertambah.
2.  **Toast Pop-up**: Muncul dari atas (tunggu max 5 detik).
3.  **Halaman Notifikasi**: List notifikasi baru muncul.

## 📂 2. File Struktur Penting

-   **Logic Notifikasi**: `app\Console\Commands\RunSlotScheduler.php`
-   **Logic Notifikasi**: `app\Console\Commands\SendScheduleReminders.php`
-   **Tampilan Toast**: `resources/views/layouts/main.blade.php`
-   **Tampilan Halaman Notif**: `resources/views/notifikasi.blade.php`
-   **Logic Profil**: `resources/views/profile.blade.php`
-   **Scheduler Config**: `app/Console/Kernel.php`

---

**Dokumen ini merangkum 18 file dokumentasi menjadi 1 panduan praktis.**
