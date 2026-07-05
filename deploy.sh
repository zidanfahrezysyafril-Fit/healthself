#!/bin/bash

# ==========================================
# Script Upload (Deploy) ke Server Ubuntu
# ==========================================

# Silakan ubah variabel di bawah ini sesuai dengan server Anda
SERVER_IP="172.16.62.193"
SERVER_USER="root" # Ubah jika Anda menggunakan user lain, misal 'ubuntu' atau 'www-data'
SERVER_DIR="/var/www/healthself" # Ubah sesuai lokasi folder project di Ubuntu Anda

echo "🚀 Memulai sinkronisasi perubahan kode ke $SERVER_IP..."

# Menggunakan 'rsync' jauh lebih baik daripada 'scp' biasa untuk update,
# karena rsync hanya akan mengupload file yang "berubah" saja (lebih cepat)
# dan kita bisa mengecualikan folder yang tidak perlu diupload (seperti vendor).

rsync -avz --exclude 'node_modules' \
           --exclude 'vendor' \
           --exclude '.env' \
           --exclude '.git' \
           --exclude 'storage/logs/*' \
           --exclude 'storage/framework/views/*' \
           --exclude 'storage/framework/cache/*' \
           --exclude 'storage/framework/sessions/*' \
           --exclude 'deploy.sh' \
           -e ssh ./ $SERVER_USER@$SERVER_IP:$SERVER_DIR

echo "✅ Sinkronisasi selesai!"

# (Opsional) Jika Anda ingin server otomatis membersihkan cache Laravel setelah update, hapus tanda pagar di bawah ini:
# ssh $SERVER_USER@$SERVER_IP "cd $SERVER_DIR && php artisan optimize:clear"
