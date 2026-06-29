# HealthSelf AI Backend

HealthSelf AI adalah platform cerdas untuk mahasiswa dalam memantau kesehatan mental, membaca artikel psikologi, dan berinteraksi dengan AI Konselor berbasis *Retrieval-Augmented Generation* (RAG).

Repositori ini memuat *source code* Backend berbasis Laravel 12 yang melayani aplikasi Web Admin dan Aplikasi Mobile (Flutter) melalui antarmuka REST API.

## Fitur Utama API
- **AI Chat RAG**: Terintegrasi dengan Groq (Llama 3.1) dan *Python Sentence Transformers* untuk pencarian semantik (Vector Search).
- **Mood Tracking**: Pemantauan *mood*, waktu tidur, dan tingkat stres harian.
- **Article & Bookmarks**: Rekomendasi bacaan cerdas berdasarkan preferensi pengguna.
- **Aggregated Dashboard**: Satu *endpoint* untuk merangkum seluruh aktivitas harian.
- **Enterprise Security**: Otentikasi Sanctum, proteksi *Brute-Force*, *Rate Limiter*, *Security Headers*, dan *Gzip Compression*.

## Struktur Direktori API
- `app/Http/Controllers/Api`: Seluruh *entry point* API (sangat tipis).
- `app/Services`: Tempat seluruh logika bisnis (*Fat Service*).
- `app/Repositories`: Lapisan abstraksi *Database* (*Repository Pattern*).
- `app/Http/Resources/Api`: Penyeragaman struktur *Response* JSON (`ApiResponse`).
- `python/`: Skrip Python untuk pemrosesan NLP (RAG Engine).

## Persyaratan
- PHP >= 8.2
- Composer
- Python 3.11+
- MySQL / MariaDB
- Node.js & NPM

## Setup Proyek
1. Clone repositori ini.
2. Jalankan `composer install` dan `npm install`.
3. Buat file `.env` dari `.env.example` dan konfigurasikan koneksi `DB_*`.
4. Jalankan `php artisan key:generate`.
5. Siapkan AI Environment:
   ```bash
   cd python
   python -m venv venv
   source venv/bin/activate
   pip install -r requirements.txt
   ```
6. Jalankan Migrasi: `php artisan migrate --seed`
7. Mulai server lokal: `php artisan serve`

## Dokumentasi API
Silakan baca [API DOCUMENTATION](docs/API_DOCUMENTATION.md) atau impor [Postman Collection](docs/HealthSelf_API_Collection.json).
