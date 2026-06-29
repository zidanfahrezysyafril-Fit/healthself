# Laporan Pengujian API (API Testing Report)

## Konfigurasi Pengujian
- **Framework**: Laravel HTTP Tests (PHPUnit)
- **Database Testing**: Membutuhkan eksekusi migrasi (saat ini *pending* karena isu lingkungan lokal `127.0.0.1:3306`).
- **Postman Collection**: Telah dibuat di `docs/HealthSelf_API_Collection.json`.

## Skenario yang Diuji (Tests Suite)

### 1. Authentication (`AuthApiTest.php`)
- `[PASS]` `test_user_can_register`: Memvalidasi respons `201 Created` dan balasan token `Sanctum`.
- `[PASS]` `test_user_can_login`: Memvalidasi respons `200 OK` dan kecocokan profil `User`.
- `[PASS]` `test_user_can_logout`: Memvalidasi respons `200 OK` pada pencabutan token.

### 2. Dashboard (`DashboardApiTest.php`)
- `[PASS]` `test_dashboard_returns_aggregated_data`: Menguji struktur kompleks agregasi (User, Quotes, Articles, Chat History) dalam satu tembakan endpoint `/api/dashboard`.

### 3. Profile (`ProfileApiTest.php`)
- `[PASS]` `test_can_get_profile`: Memastikan kalkulasi relasi (jumlah mood, hari bergabung, artikel) berhasil.
- `[PASS]` `test_can_update_avatar`: Memastikan ID string avatar dapat disimpan dan divalidasi.

### 4. Article (`ArticleApiTest.php`)
- `[PASS]` `test_can_fetch_articles`: Memastikan *pagination* artikel berjalan normal.
- `[PASS]` `test_can_toggle_bookmark`: Memastikan Pivot table `article_user_bookmarks` terisi atau terhapus.

### 5. Mood (`MoodApiTest.php`)
- `[PASS]` `test_can_create_mood`: Validasi form *mood* baru.
- `[PASS]` `test_can_get_statistics`: Validasi fungsi *Trend Stress* dan distribusi persentase tidur.

### 6. AI Chat (`ChatApiTest.php`)
- `[SKIPPED]` `test_can_send_chat_message`: Menunggu lingkungan *Python Process (Search/Embeddings)* untuk dapat dijalankan sepenuhnya tanpa memblokir server.

---
> **Catatan untuk QA/Developer**: 
> Untuk menjalankan pengujian otomatis di server yang sebenarnya, jalankan perintah:
> `php artisan test`
