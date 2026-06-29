# Deployment Guide

Berikut adalah langkah-langkah *best practice* untuk meluncurkan Backend API HealthSelf AI ke server *Production* (seperti Ubuntu Server, AWS EC2, atau DigitalOcean Droplet).

## 1. Persiapan Server
1. Instal Nginx / Apache.
2. Instal PHP 8.2 (berserta ekstensi `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`).
3. Instal Composer.
4. Instal MySQL 8 / MariaDB.
5. Instal Python 3.11 dan `pip`.

## 2. Kloning dan Setup Aplikasi
```bash
cd /var/www
git clone <url-repo-anda> healthself
cd healthself

# Instal dependensi PHP
composer install --optimize-autoloader --no-dev

# Atur hak akses agar bisa ditulis oleh Nginx/Apache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

## 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env` dan perhatikan hal berikut:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.healthself.com # Ubah sesuai domain

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=healthself_db
DB_USERNAME=root
DB_PASSWORD=rahasia

GROQ_API_KEY=groq_key_anda
```
Jalankan: `php artisan key:generate`

## 4. Setup Database
```bash
php artisan migrate --force
php artisan db:seed --force
```

## 5. Setup Python AI Environment
```bash
cd python
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
```

## 6. Optimalisasi (Sangat Penting di Production)
```bash
# Cache konfigurasi
php artisan config:cache

# Cache routing
php artisan route:cache

# Cache views
php artisan view:cache

# N+1 Prevention & Caching otomatis aktif di Production
```

## 7. Konfigurasi Nginx (Contoh)
Arahkan root direktori Nginx ke folder `public`:
```nginx
server {
    listen 80;
    server_name api.healthself.com;
    root /var/www/healthself/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Jangan lupa memasang sertifikat SSL (HTTPS) menggunakan *Certbot* / *Let's Encrypt*.
