# Changelog

Seluruh perubahan penting pada aplikasi ini akan dicatat dalam berkas ini.

## [v1.0.0] - Production Ready (Sprint A10)

### Added
- **API Optimization (Sprint A8)**: Cache layer, Gzip compression, pencegahan `N+1 Query`, `Database Indexing`.
- **API Testing (Sprint A9)**: Pembuatan 6 modul *Feature Tests*, *Postman Collection*, dan Laporan Uji Coba.
- **Security Audit (Sprint A7)**: Global Exception Handler JSON, Middleware Security Headers, dan *Rate Limiter* API.
- **Authentication Refinement (Sprint A6)**: `AuthService`, pencatatan `login_attempts`, pembatasan Brute-force.
- **Profile Module (Sprint A5)**: `ProfileService`, penggantian sistem Avatar (tanpa upload), dan kalkulasi statistik personal.
- **Article Module (Sprint A4)**: `ArticleRepository`, fitur baca *bookmarked articles*, rekomendasi bersarang.
- **Dashboard Module (Sprint A3)**: `DashboardService` yang menggabungkan seluruh informasi krusial menjadi satu endpoint untuk mengurangi pemanggilan jaringan.
- **Mood Module (Sprint A2)**: Kalkulasi tren stres dan rata-rata tidur mingguan.
- **AI Chat RAG (Sprint A1)**: Integrasi Groq dan Python (Sentence Transformers), terjemahan Bahasa, penggabungan riwayat memori percakapan.

### Changed
- Refaktor semua API *Controllers* menjadi "Thin Controller" dengan memindahkan logika berat ke dalam *Service*.
- Refaktor seluruh respons HTTP menggunakan pembungkus konsisten `ApiResponse`.

### Fixed
- Menyembunyikan *stack trace* HTML saat terjadi kesalahan pada proses API (otomatis diubah ke JSON 500/404).

## [v0.9.0] - Awal Proyek
- Sistem Website Utama, Model Database Awal, Integrasi Python Dasar.
