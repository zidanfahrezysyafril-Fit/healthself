# Arsitektur Aplikasi (Architecture & Patterns)

Proyek ini dibangun di atas Laravel 12 dengan menerapkan prinsip **Clean Architecture** berlapis. Hal ini memisahkan logika lalu lintas HTTP dengan logika bisnis aplikasi, sehingga kode jauh lebih mudah diuji, diperluas (*scalable*), dan di-*maintain*.

## 1. Flow Permintaan (Request Lifecycle)
`Flutter Mobile` ➔ `Routing (api.php)` ➔ `Middleware (Security, Rate Limiter)` ➔ `FormRequest (Validation)` ➔ `Controller` ➔ `Service` ➔ `Repository` ➔ `Model / Database`.

## 2. Lapisan-Lapisan Utama
- **Controllers**: Hanya bertanggung jawab mengarahkan lalu lintas. Menerima *request*, memanggil *Service*, dan memformat *response* melalui `ApiResponse::success()`. Tidak ada logika bisnis di sini.
- **FormRequests**: Semua aturan *validation* (seperti `required`, `email`, pengecekan token) dipisahkan ke sini (misal: `LoginRequest`, `UpdateProfileRequest`).
- **Services**: Otak utama dari sistem. Di sinilah logika *AI RAG*, *Caching*, *Hashing*, pembuatan token *Sanctum*, dan kalkulasi statistik berada (misal: `DashboardService`, `MoodService`).
- **Repositories**: Lapisan abstraksi Database. Semua perintah *Eloquent* (`create`, `find`, `where`) ditempatkan di sini. *Service* tidak perlu tahu tabel atau kolom apa yang dipakai, ia hanya memanggil *Repository* (misal: `ArticleRepository`).
- **API Resources**: Menyatukan dan menstandarisasi kerangka JSON keluaran (misal: `ArticleResource` akan otomatis menambahkan field `is_bookmarked` dengan mengecek hubungan relasi user).

## 3. Sistem AI RAG (Retrieval-Augmented Generation)
1. User memasukkan pertanyaan Bahasa Indonesia.
2. `ChatService` memanggil `Groq API` (Llama 3.1) untuk menerjemahkannya ke Bahasa Inggris (*query expansion*).
3. `ChatService` memanggil skrip Python eksternal (`python/search.py`) melalui `Symfony/Process`.
4. Python mengubah teks menjadi *Embeddings* dan mencari kemiripan dengan vektor yang ada di Database *ChromaDB* (Data Pengetahuan Medis/Psikologi).
5. Teks teratas dikembalikan ke PHP sebagai *Context*.
6. `ChatService` memasukkan *Context*, *Chat History*, dan pertanyaan ke `Groq API` lagi untuk membuat jawaban Bahasa Indonesia yang bersimpati.
7. Disimpan ke tabel `riwayat_chats` dan di-*return* ke pengguna.
