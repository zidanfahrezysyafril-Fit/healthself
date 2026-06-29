# Dokumentasi API HealthSelf AI

Semua endpoint API diawali dengan `/api`.
Semua respons mematuhi kerangka kerja JSON berikut:
```json
{
    "success": true,
    "message": "Pesan deskriptif",
    "data": { ... } // Array atau Object
}
```

## Daftar Endpoint

### Authentication (`/api/auth`)
| Method | Endpoint | Headers | Body | Deskripsi |
|---|---|---|---|---|
| POST | `/login` | - | `email`, `password`, `remember_me` | Masuk ke aplikasi |
| POST | `/register` | - | `name`, `email`, `password`, `password_confirmation` | Mendaftar akun baru |
| POST | `/logout` | `Bearer Token` | - | Keluar dari perangkat saat ini |
| POST | `/logout-all` | `Bearer Token` | - | Cabut semua token pengguna |

### Dashboard (`/api/dashboard`)
| Method | Endpoint | Headers | Body | Deskripsi |
|---|---|---|---|---|
| GET | `/` | `Bearer Token` | - | Dapatkan Quotes, Mood hari ini, Artikel rekomendasi, dan Histori chat. |

### Mood (`/api/moods`)
| Method | Endpoint | Headers | Body | Deskripsi |
|---|---|---|---|---|
| GET | `/` | `Bearer Token` | - | Ambil riwayat mood |
| GET | `/statistics` | `Bearer Token` | - | Kalkulasi mingguan, bulanan, dan jam tidur |
| POST | `/` | `Bearer Token` | `mood`, `note`, `sleep_hours`, `stress_level` | Simpan mood harian |
| PUT | `/{id}` | `Bearer Token` | `mood`, `note`, `sleep_hours`, `stress_level` | Edit mood |
| DELETE | `/{id}` | `Bearer Token` | - | Hapus mood |

### Article (`/api/articles`)
| Method | Endpoint | Headers | Body | Deskripsi |
|---|---|---|---|---|
| GET | `/` | `Bearer Token` | - | Daftar seluruh artikel (Paginated) |
| GET | `/popular` | `Bearer Token` | - | 5 Artikel terpopuler (Cached) |
| GET | `/recommended` | `Bearer Token` | - | 5 Artikel rekomendasi (Cached) |
| GET | `/{slug}` | `Bearer Token` | - | Baca artikel (menyertakan related_articles) |
| POST | `/{id}/bookmark` | `Bearer Token` | - | Toggle simpan/hapus artikel ke Bookmark |

### Profile (`/api/profile`)
| Method | Endpoint | Headers | Body | Deskripsi |
|---|---|---|---|---|
| GET | `/` | `Bearer Token` | - | Info profil beserta total chat dan mood |
| PUT | `/` | `Bearer Token` | `name`, `email` | Ubah profil |
| POST | `/avatar` | `Bearer Token` | `avatar_id` (cth: 'avatar-1') | Pilih avatar dari daftar lokal |
| DELETE | `/avatar` | `Bearer Token` | - | Hapus avatar (kembali ke default) |
| POST | `/change-password` | `Bearer Token` | `current_password`, `password`, `password_confirmation` | Ganti password |

### AI Chat (`/api/chat`)
| Method | Endpoint | Headers | Body | Deskripsi |
|---|---|---|---|---|
| GET | `/history` | `Bearer Token` | - | Ambil 20 riwayat chat terakhir |
| POST | `/` | `Bearer Token` | `message` | Kirim pesan ke Bot RAG Konselor |
