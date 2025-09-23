# Duweet
Aplikasi personal finance berbasis gamifikasi

## Proses
- Registrasi **user** baru (CRUD)
- User mengelola **account** (CRUD)
- User mengelola **transaction** (CRUD)

## Requirements
- **User**
  - Bisa registrasi
  - Bisa mencatat transaksi keuangan

## Entitas
- **User**. User adalah pengguna aplikasi Duweet.
- **Account Type**. Jenis account untuk transaksi.
  - Data
    - Income    (IN)
    - Expenses  (EX)
    - Spending  (SP)
    - Liability (LI)
    - Asset     (AS)
  - Note: Di buat sebagai enum bukan tabel. Check app/Enums/AccountType.php
- **Account**. Nama account untuk transaksi keuangan (mendukung nested/hierarchical structure)
  - Atribut
    - id (Primary Key)
    - parent_id (nullable, foreign key to accounts.id) - Parent account untuk nested structure
    - name (string) - Nama account (contoh: "Bank", "BCA Tabungan", "Dompet")
    - type (enum AccountType) - Jenis account (IN/EX/SP/LI/AS)
    - balance (big integer) - Saldo saat ini (hanya untuk leaf accounts)
    - initial_balance (big integer) - Saldo awal
    - is_group (boolean) - Apakah account ini adalah group/parent (default: false)
    - description (text, nullable) - Deskripsi account
    - is_active (boolean) - Status aktif/nonaktif account
    - color (string, nullable) - Warna untuk UI (hex code)
    - icon (string, nullable) - Icon untuk UI
    - sort_order (integer) - Urutan tampilan
    - level (integer) - Level kedalaman (0 = root, 1 = child, dst)
    - created_at (timestamp)
    - updated_at (timestamp)
  - Validasi
    - name: required, max 100 characters
    - type: required, valid AccountType
    - balance: required if is_group=false, numeric
    - initial_balance: required if is_group=false, numeric
    - color: nullable, valid hex color
    - level: max 2 (maksimal 3 level: 0,1,2)
    - parent_id: nullable, must exist in accounts table
  - Relasi
    - belongsTo: Account (parent_id) - Parent account
    - hasMany: Account (parent_id) - Child accounts
    - hasMany: Transaction (account_id) - Hanya untuk leaf accounts (is_group=false)
  - Business Rules
    - Parent account (is_group=true) tidak bisa memiliki transaksi langsung
    - Balance parent account = sum balance dari children accounts
    - Parent dan child harus memiliki type yang sama
    - Maximum depth = 3 levels (0, 1, 2)
    - Group account tidak bisa dihapus jika masih ada children
    - Saldo tidak boleh negatif untuk type ASSET (kecuali group account)
    - Liability account bisa memiliki saldo negatif
    - Leaf account tidak bisa dihapus jika masih ada transaksi
    - Name harus unique per level dalam parent yang sama
- **UserAccount**. Adalah entitas/tabel relasi entitas **User** dengan **Account**.
- **Transaction**: adalah entitas yang mendata tiap transaksi keuangan yang terjadi