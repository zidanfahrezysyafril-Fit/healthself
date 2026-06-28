#!/bin/bash
# ============================================================
# Setup Cloudflare Tunnel untuk HealthSelf
# ============================================================

set -e

PROJECT_DIR="/var/www/healthself"
TUNNEL_NAME="healthself-tunnel"

# Warna
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'
BOLD='\033[1m'

print_header() {
    echo ""
    echo -e "${CYAN}╔══════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║${NC} ${BOLD}$1${NC}"
    echo -e "${CYAN}╚══════════════════════════════════════════════════════════╝${NC}"
    echo ""
}
print_info() { echo -e "${CYAN}[i]${NC} $1"; }
print_step() { echo -e "${GREEN}[✓]${NC} $1"; }

if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}[✗] Script ini harus dijalankan sebagai root (sudo)!${NC}"
    exit 1
fi

print_header "Setup Cloudflare Tunnel - HealthSelf"

read -p "$(echo -e ${YELLOW}Masukkan domain Cloudflare Anda \(contoh: redsim.my.id\): ${NC})" DOMAIN

if [ -z "$DOMAIN" ]; then
    echo -e "${RED}[✗] Domain tidak boleh kosong!${NC}"
    exit 1
fi

print_step "Domain target: $DOMAIN"

# ===================== STEP 1: INSTALL CLOUDFLARED =====================
print_header "STEP 1/5 — Install Cloudflared"
if ! command -v cloudflared &> /dev/null; then
    print_info "Mendownload cloudflared..."
    wget -q "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb" -O /tmp/cloudflared.deb
    dpkg -i /tmp/cloudflared.deb
    rm /tmp/cloudflared.deb
    print_step "Cloudflared berhasil diinstal."
else
    print_step "Cloudflared sudah terinstal."
fi

# ===================== STEP 2: LOGIN CLOUDFLARE =====================
print_header "STEP 2/5 — Login ke Cloudflare"
echo -e "${YELLOW}PERHATIAN:${NC}"
echo -e "Silakan copy link yang muncul di bawah ini dan buka di browser komputer Anda."
echo -e "Login menggunakan akun Google / Cloudflare Anda, lalu pilih domain ${BOLD}${DOMAIN}${NC}."
echo -e "Script akan otomatis lanjut setelah Anda berhasil memilih domain di browser."
echo ""
cloudflared tunnel login

# ===================== STEP 3: SETUP TUNNEL =====================
print_header "STEP 3/5 — Membuat dan Konfigurasi Tunnel"
print_info "Membuat tunnel dengan nama: $TUNNEL_NAME..."
cloudflared tunnel delete "${TUNNEL_NAME}" 2>/dev/null || true
cloudflared tunnel create "${TUNNEL_NAME}"

TUNNEL_ID=$(cloudflared tunnel list | grep "${TUNNEL_NAME}" | awk '{print $1}')
print_step "Tunnel ID: $TUNNEL_ID"

print_info "Membuat file konfigurasi..."
mkdir -p /root/.cloudflared
cat > /root/.cloudflared/config.yml <<TUNNEL_CONF
tunnel: ${TUNNEL_ID}
credentials-file: /root/.cloudflared/${TUNNEL_ID}.json
ingress:
  - hostname: ${DOMAIN}
    service: http://localhost:80
  - hostname: www.${DOMAIN}
    service: http://localhost:80
  - service: http_status:404
TUNNEL_CONF

# ===================== STEP 4: ROUTING DNS & SERVICE =====================
print_header "STEP 4/5 — Routing DNS & Jalankan Service"
print_info "Mendaftarkan domain ke DNS Cloudflare..."
cloudflared tunnel route dns -f "${TUNNEL_NAME}" "${DOMAIN}" || true
cloudflared tunnel route dns -f "${TUNNEL_NAME}" "www.${DOMAIN}" || true

print_info "Menginstall cloudflared sebagai service agar jalan 24/7..."
# Stop service jika sebelumnya sudah ada
systemctl stop cloudflared 2>/dev/null || true
cloudflared service uninstall 2>/dev/null || true

cloudflared service install
systemctl daemon-reload
systemctl enable cloudflared
systemctl start cloudflared
print_step "Service cloudflared berhasil dijalankan!"

# ===================== STEP 5: UPDATE ENV LARAVEL =====================
print_header "STEP 5/5 — Update Konfigurasi Laravel (.env)"
if [ -d "${PROJECT_DIR}" ] && [ -f "${PROJECT_DIR}/.env" ]; then
    cd "${PROJECT_DIR}"
    
    # Update APP_URL
    sed -i "s|^APP_URL=.*|APP_URL=https://${DOMAIN}|" .env
    
    # Update Google Redirect URI
    sed -i "s|^GOOGLE_REDIRECT_URI=.*|GOOGLE_REDIRECT_URI=https://${DOMAIN}/auth/google/callback|" .env

    print_info "Membersihkan cache Laravel..."
    sudo -u www-data php artisan optimize:clear || true
    sudo -u www-data php artisan config:cache || true
    sudo -u www-data php artisan route:cache || true
    sudo -u www-data php artisan view:cache || true
    
    print_step "Konfigurasi Laravel berhasil diupdate menggunakan https://${DOMAIN}"
else
    echo -e "${RED}[✗] Direktori project atau file .env tidak ditemukan di ${PROJECT_DIR}${NC}"
fi

print_header "🎉 SETUP TUNNEL SELESAI! 🎉"
echo -e "${GREEN}Web HealthSelf Anda sekarang bisa diakses dari internet via:${NC}"
echo -e "👉 ${BOLD}https://${DOMAIN}${NC}"
echo ""
