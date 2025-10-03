# 🚀 Catatan: Konfigurasi PostgreSQL agar Bisa Diakses Remote dari Laravel

## 1. Edit `postgresql.conf`
Lokasi file:
- Ubuntu/Debian: `/etc/postgresql/<versi>/main/postgresql.conf`
- CentOS/RHEL: `/var/lib/pgsql/<versi>/data/postgresql.conf`

Cari baris:
```conf
listen_addresses = 'localhost'
Ubah menjadi:

conf
Copy code
listen_addresses = '*'
atau lebih aman:

conf
Copy code
listen_addresses = '192.168.1.100'
2. Edit pg_hba.conf
Tambahkan rule baru tanpa menghapus rule default (127.0.0.1 & ::1).

Contoh:

conf
Copy code
# IPv4 local connections
host    all             all             127.0.0.1/32            scram-sha-256

# IPv6 local connections
host    all             all             ::1/128                 scram-sha-256

# Izinkan Laravel server (ubah IP sesuai server Laravel)
host    all             all             103.x.x.x/32            scram-sha-256

# Opsional (untuk testing, tidak aman di production)
# host    all             all             0.0.0.0/0                scram-sha-256
3. Restart PostgreSQL
bash
Copy code
sudo systemctl restart postgresql
4. Buka Port 5432 di Firewall
🔹 Compute Engine (VM)
GCP Console → VPC Network → Firewall rules

Tambahkan rule:

Name: allow-postgres

Source IP ranges: 103.x.x.x/32 (IP Laravel server)

Protocols/ports: tcp:5432

🔹 Cloud SQL (Managed PostgreSQL)
GCP Console → Cloud SQL → Instances → Connections

Tambahkan Authorized networks → 103.x.x.x/32

5. Test Koneksi
Dari server Laravel:

bash
Copy code
psql -h <IP_POSTGRES> -U <USER> -d <DBNAME>
6. Konfigurasi Laravel .env
Sesuaikan konfigurasi database:

env
Copy code
DB_CONNECTION=pgsql
DB_HOST=34.30.244.43
DB_PORT=5432
DB_DATABASE=myapp_db
DB_USERNAME=myapp_user
DB_PASSWORD=secret123
7. Test Laravel
Jalankan migrate:

bash
Copy code
php artisan migrate
Jika berhasil tanpa error → PostgreSQL sudah bisa diakses remote ✅

🔑 Kesimpulan
postgresql.conf → listen_addresses = '*' atau IP server.

pg_hba.conf → tambahkan baris baru untuk IP Laravel server.

Firewall / VPC → buka port 5432.

.env Laravel → isi host, user, db, dan password sesuai PostgreSQL.
