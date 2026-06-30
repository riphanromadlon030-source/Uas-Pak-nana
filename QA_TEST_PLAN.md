# QA & TESTING PLAN - SIPERPUS (Sistem Informasi Perpustakaan)
## Pengujian Parsial Modul 1-5 & System Integration Testing

**Dokumen**: QA Test Plan  
**Versi**: 2.0  
**Tanggal**: 2026-06-30  
**Status**: Ready for Testing

---

## EXECUTIVE SUMMARY

Testing plan ini mencakup:
- ✅ **Modul 1-3**: Autentikasi, Manajemen Anggota & Buku
- ✅ **Modul 4-5**: OPAC & Sirkulasi Denda  
- ✅ **SIT**: System Integration Testing
- ✅ **E2E**: End-to-End Testing
- ✅ **UAT**: User Acceptance Testing

**Timeline**: 4 minggu pengujian  
**Resource**: 3 QA Engineer + 2 Stakeholder

---

## 1. TEST SCOPE & OBJECTIVES

### 1.1 Scope
| Modul | Scope | Status |
|-------|-------|--------|
| **Modul 1: Autentikasi & Pengguna** | Login, Logout, Reset Password, Manajemen User | ✅ In Scope |
| **Modul 2: Manajemen Anggota** | CRUD Member, Validasi Data, Export | ✅ In Scope |
| **Modul 3: Data Buku & Katalog** | CRUD Buku, Import, Kategori | ✅ In Scope |
| **Modul 4: OPAC & Pencarian** | Search, Filter, View Detail | ✅ In Scope |
| **Modul 5: Sirkulasi & Denda** | Peminjaman, Pengembalian, Perhitungan Denda | ✅ In Scope |
| **Database**: MYSQL/MariaDB | Triggers, Stored Procedures | ✅ In Scope |
| **Security**: Authentication & Authorization | RBAC, Permission | ✅ In Scope |

### 1.2 Objectives
- ✅ Verifikasi fungsionalitas sesuai SRS
- ✅ Validasi integrasi antar modul
- ✅ Ensure data integrity (no data loss/corruption)
- ✅ Performance testing (response time < 2 detik untuk pencarian)
- ✅ Security validation (OWASP Top 10)

### 1.3 Out of Scope
- ❌ E-Resources module (Phase 2)
- ❌ Dashboard & Reporting (Phase 2)
- ❌ Performance optimization tuning
- ❌ Load testing > 50 concurrent users

---

## 2. TEST STRATEGY

### 2.1 Testing Types

```
┌──────────────────────────────────────────────────────────────┐
│                    TESTING PYRAMID                           │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│                   E2E / UAT (10%)                            │
│                 System Integration (20%)                      │
│              Unit + Integration Tests (70%)                   │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

### 2.2 Testing Levels

| Level | Type | Tools | Coverage |
|-------|------|-------|----------|
| **Unit Test** | Backend logic testing | PHPUnit | 80%+ code |
| **Integration Test** | Module-to-module | PHPUnit + DB | Database triggers |
| **API Test** | REST endpoint | Postman / Insomnia | All endpoints |
| **System Test** | Full workflow E2E | Selenium / Cypress | Use cases |
| **UAT** | User acceptance | Manual + Browser | Stakeholder validation |
| **Performance** | Response time | Apache JMeter | < 2 detik search |

### 2.3 Testing Approach
- **Bottom-Up**: Unit → Integration → System → UAT
- **Priority**: Critical (blocking) → High → Medium → Low
- **Regression**: Check existing features after changes

---

## 3. TEST ENVIRONMENT

### 3.1 Infrastructure

```
┌─────────────────────────────────────────────────────────────┐
│                   TEST ENVIRONMENT                          │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Frontend: Laravel Blade + Bootstrap 5 (Local Browser)    │
│  Backend: PHP 8.1 + Laravel 10.x (Local/VM)                │
│  Database: MySQL 8.0 (Local Instance)                       │
│  Server: Apache/Nginx (Local)                               │
│  Browser: Chrome 120+ / Firefox 121+                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Test Database Setup

```bash
# Fresh database for each test cycle
php artisan migrate:fresh
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=TestDataSeeder

# Database credentials
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siperpus_test
DB_USERNAME=root
DB_PASSWORD=test123
```

### 3.3 Test Data
- **Members**: 100+ member test data (aktif, suspend, non-aktif)
- **Books**: 500+ buku dengan berbagai kategori & stok
- **Loans**: Historical data untuk testing return & fine calculation
- **Users**: 3 role (admin, pustakawan, member)

---

## 4. TEST CASES & SCENARIOS

### 4.1 MODUL 1: AUTENTIKASI & MANAJEMEN PENGGUNA

#### TC-001: User Login - Valid Credentials
```
Precondition: User sudah terdaftar (admin@gallery.com / password123)
Steps:
  1. Navigate ke /login
  2. Input email: admin@gallery.com
  3. Input password: password123
  4. Click "Login"
  
Expected Result:
  ✅ Login berhasil
  ✅ Redirect ke dashboard
  ✅ Session created dengan timeout 30 menit
  ✅ Login event logged di audit_log
  
Acceptance: PASS
```

#### TC-002: User Login - Invalid Credentials
```
Precondition: -
Steps:
  1. Navigate ke /login
  2. Input email: admin@gallery.com
  3. Input password: wrong_password
  4. Click "Login"
  
Expected Result:
  ✅ Error message: "Kredensial tidak valid"
  ✅ User tetap di halaman login
  ✅ Form input kosong (security)
  
Acceptance: PASS
```

#### TC-003: Failed Login Attempt - Account Lockout
```
Precondition: -
Steps:
  1. Attempt login 3x dengan password salah
  2. Attempt ke-4
  
Expected Result:
  ✅ Setelah 3 failed attempts → Account locked
  ✅ Error: "Akun Anda terkunci. Coba lagi dalam 15 menit"
  ✅ Unlock otomatis setelah 15 menit
  
Acceptance: PASS
```

#### TC-004: Logout
```
Precondition: User sudah login
Steps:
  1. Click "Logout" button
  2. Confirm logout
  
Expected Result:
  ✅ Session destroyed
  ✅ Redirect ke login page
  ✅ Cookies cleared
  ✅ Logout event logged
  
Acceptance: PASS
```

#### TC-005: Reset Password
```
Precondition: User lupa password
Steps:
  1. Navigate ke /login
  2. Click "Lupa Password?"
  3. Input email: user@example.com
  4. Check email untuk reset link
  5. Click reset link (valid 24 jam)
  6. Set password baru: NewPassword123!
  7. Submit
  
Expected Result:
  ✅ Email reset sent
  ✅ Link berfungsi dengan token validation
  ✅ Password updated
  ✅ Can login dengan password baru
  
Acceptance: PASS
```

### 4.2 MODUL 2: MANAJEMEN ANGGOTA BUKU

#### TC-006: Create Member - Valid Data
```
Precondition: Admin sudah login
Steps:
  1. Navigate ke /admin/members
  2. Click "Tambah Anggota"
  3. Fill form:
     - NIM: 12345678
     - Nama: John Doe
     - Prodi: SI
     - No HP: 08123456789
     - Alamat: Jl. Imam Bonjol No. 1
  4. Click "Simpan"
  
Expected Result:
  ✅ Member created
  ✅ Redirect ke member list
  ✅ Success message: "Anggota berhasil ditambahkan"
  ✅ Data tersimpan di database
  
Acceptance: PASS
```

#### TC-007: Create Member - Duplicate NIM
```
Precondition: Member dengan NIM 12345678 sudah ada
Steps:
  1. Navigate ke /admin/members/create
  2. Fill form dengan NIM: 12345678
  3. Click "Simpan"
  
Expected Result:
  ✅ Validation error: "NIM sudah terdaftar"
  ✅ Form tidak submit
  ✅ Error message ditampilkan
  
Acceptance: PASS
```

#### TC-008: Search Member by NIM
```
Precondition: Minimal 10 member di database
Steps:
  1. Navigate ke /admin/members
  2. Input search: 12345
  3. Click "Search"
  
Expected Result:
  ✅ Results appear dengan NIM matching
  ✅ Response time < 1 detik
  ✅ Pagination bekerja dengan baik
  
Acceptance: PASS
```

#### TC-009: Update Member Status to Suspend
```
Precondition: Member aktif dengan tunggakan denda > limit
Steps:
  1. Navigate ke /admin/members
  2. Select member
  3. Change status: Suspend
  4. Click "Simpan"
  
Expected Result:
  ✅ Status updated menjadi suspend
  ✅ Member tidak bisa pinjam buku
  ✅ Audit log recorded
  
Acceptance: PASS
```

#### TC-010: Export Member Data to Excel
```
Precondition: Minimal 50 member di database
Steps:
  1. Navigate ke /admin/members
  2. Click "Export Excel"
  3. Select date range
  4. Click "Download"
  
Expected Result:
  ✅ File downloaded dengan nama: members_2026_06_30.xlsx
  ✅ Data lengkap & format benar
  ✅ Excel dapat dibuka tanpa error
  
Acceptance: PASS
```

### 4.3 MODUL 3: DATA BUKU & KATALOG

#### TC-011: Create Book - Valid Data
```
Precondition: Admin sudah login
Steps:
  1. Navigate ke /admin/books
  2. Click "Tambah Buku"
  3. Fill form:
     - ISBN: 9789797869234
     - Judul: Pemrograman PHP Dasar
     - Penulis: Budi Raharjo
     - Penerbit: Informatika
     - Tahun: 2025
     - Kategori: Teknologi
     - Lokasi: A-01-03
     - Stok: 5
  4. Click "Simpan"
  
Expected Result:
  ✅ Buku created
  ✅ Redirect ke book list
  ✅ Success message
  
Acceptance: PASS
```

#### TC-012: Bulk Import Books from Excel
```
Precondition: File books_import.xlsx sudah siap
Steps:
  1. Navigate ke /admin/books
  2. Click "Bulk Import"
  3. Upload file: books_import.xlsx (100 records)
  4. Click "Process"
  
Expected Result:
  ✅ All 100 books imported
  ✅ Processing time < 10 detik
  ✅ Validation errors shown (if any)
  ✅ Success: "100 buku berhasil diimport"
  
Acceptance: PASS
```

#### TC-013: Search Book by Title
```
Precondition: Minimal 500 books di database
Steps:
  1. Navigate ke /opac (public catalog)
  2. Input search: "PHP"
  3. Click "Search"
  
Expected Result:
  ✅ Results show books dengan judul mengandung "PHP"
  ✅ Response time < 2 detik
  ✅ Sort by relevance
  
Acceptance: PASS
```

#### TC-014: Search Book with Multiple Filters
```
Precondition: Books dengan berbagai kategori & tahun
Steps:
  1. Navigate ke /opac
  2. Select kategori: Teknologi
  3. Select tahun: 2025
  4. Click "Search"
  
Expected Result:
  ✅ Results filtered correctly
  ✅ Show only Teknologi + 2025
  ✅ Count accurate
  
Acceptance: PASS
```

#### TC-015: View Book Detail
```
Precondition: Book sudah ada di database
Steps:
  1. Navigate ke /opac
  2. Search & find book
  3. Click book title
  
Expected Result:
  ✅ Detail page loads
  ✅ Show: ISBN, penulis, penerbit, tahun, stok
  ✅ Current borrowers list (if any)
  ✅ "Pinjam" button visible (if stok > 0 & member aktif)
  
Acceptance: PASS
```

---

## 5. MODUL 4-5: OPAC, SIRKULASI & DENDA

### 5.1 Peminjaman Buku (UC-010)

#### TC-016: Peminjaman Buku - Valid Member & Stok Tersedia
```
Precondition:
  - Pustakawan sudah login
  - Member: 12345678 (aktif, tanpa denda)
  - Buku: ISBN 9789797869234 (stok: 5)
  
Steps:
  1. Navigate ke /pustakawan/loans/create
  2. Input NIM: 12345678
  3. System validate member (aktif, no denda)
  4. Input/scan ISBN: 9789797869234
  5. System validate stok (5 > 0)
  6. Click "Proses Peminjaman"
  
Expected Result:
  ✅ Transaksi created di LOANS table
  ✅ LOAN_ITEMS record created
  ✅ Stok diupdate: 5 → 4
  ✅ Due date calculated: today + 14 hari
  ✅ Bukti peminjaman generated (struk + QR)
  ✅ Success message: "Peminjaman berhasil"
  ✅ Record logged: user_id, timestamp, member_id
  
SQL Validation:
  SELECT * FROM loans WHERE member_id = 1 AND status = 'active'
  → Harus ada 1 record dengan due_date 14 hari ke depan
  
Acceptance: PASS
```

#### TC-017: Peminjaman - Member Suspend
```
Precondition:
  - Member status: suspend
  - Member punya tunggakan denda
  
Steps:
  1. Input NIM member suspend
  2. Click "Proses Peminjaman"
  
Expected Result:
  ✅ Error message: "Member suspend. Lunasi denda terlebih dahulu"
  ✅ Peminjaman tidak diproses
  ✅ Transaksi tidak tercreate
  
Acceptance: PASS
```

#### TC-018: Peminjaman - Stok Habis
```
Precondition:
  - Buku stok: 0 (semua sedang dipinjam)
  
Steps:
  1. Input NIM member aktif
  2. Input ISBN buku (stok 0)
  3. Click "Proses Peminjaman"
  
Expected Result:
  ✅ Error message: "Stok buku tidak tersedia"
  ✅ Peminjaman tidak diproses
  
Acceptance: PASS
```

#### TC-019: Peminjaman - Exceed Limit (Max 5)
```
Precondition:
  - Member sudah pinjam 5 buku (status active)
  
Steps:
  1. Input NIM member
  2. System check: active loans = 5
  3. Try to borrow 6th book
  
Expected Result:
  ✅ Error message: "Batas peminjaman maksimal 5 buku"
  ✅ Peminjaman tidak diproses
  
Acceptance: PASS
```

### 5.2 Pengembalian & Perhitungan Denda (UC-012)

#### TC-020: Pengembalian On-Time
```
Precondition:
  - Loan aktif: loan_date = 2026-06-01, due_date = 2026-06-15
  - Return date: 2026-06-14 (1 hari sebelum due)
  
Steps:
  1. Navigate ke /pustakawan/returns
  2. Input Loan ID atau scan NIM member
  3. System show active loans
  4. Select loan & input return_date: 2026-06-14
  5. Input kondisi: Baik
  6. Click "Proses Pengembalian"
  
Expected Result:
  ✅ RETURNS record created
  ✅ late_days = 0
  ✅ fine_amount = 0
  ✅ FINES record NOT created
  ✅ LOANS.status = "returned"
  ✅ BOOKS.stok_tersedia += 1
  ✅ Bukti pengembalian printed
  ✅ Message: "Pengembalian tepat waktu. Denda: Rp 0"
  
Database Validation:
  SELECT * FROM returns WHERE loan_id = X
  → late_days = 0, fine_amount = 0
  
  SELECT * FROM fines WHERE return_id = Y
  → No record (atau 0 denda)
  
Acceptance: PASS
```

#### TC-021: Pengembalian Late - Auto Fine Calculation
```
Precondition:
  - Loan: due_date = 2026-06-15
  - Return date: 2026-06-20 (5 hari terlambat)
  - Daily rate: Rp 5.000
  
Steps:
  1. Input return date: 2026-06-20
  2. System validate: 2026-06-20 > 2026-06-15
  3. Calculate late_days = 5
  4. Calculate fine_amount = 5 × 5000 = Rp 25.000
  5. Click "Proses Pengembalian"
  
Expected Result:
  ✅ RETURNS record created dengan late_days = 5
  ✅ TRIGGER auto-execute: Calculate fine
  ✅ FINES record created dengan nominal = Rp 25.000
  ✅ FINES.status = "unpaid"
  ✅ FINES.due_payment = 2026-06-27 (due_date + 7 hari)
  ✅ LOANS.status = "returned"
  ✅ Message: "Pengembalian terlambat. Denda: Rp 25.000"
  ✅ Show payment info untuk member
  
Database Validation:
  SELECT * FROM fines WHERE return_id = Y
  → nominal_denda = 25000, status = 'unpaid'
  
  SELECT * FROM audit_log WHERE action = 'create_fine'
  → Record logged
  
Acceptance: PASS
```

#### TC-022: Pengembalian - Fine Cap (Max Rp 500.000)
```
Precondition:
  - Loan: due_date = 2026-06-15
  - Return date: 2026-07-15 (30 hari late)
  - Daily rate: Rp 5.000
  - Expected: 30 × 5000 = Rp 150.000 (but cap?)
  
Actual calculation:
  - If max_fine cap = Rp 500.000
  - 30 × 5000 = 150.000 < 500.000 ✓
  - fine_amount = 150.000
  
Steps:
  1. Return book 30 days late
  2. System calculate: late_days = 30, amount = 150.000
  3. Click "Proses"
  
Expected Result:
  ✅ fine_amount = 150.000 (atau mengikuti config)
  ✅ FINES.nominal_denda = 150.000
  
Acceptance: PASS
```

#### TC-023: Pembayaran Denda - Partial Payment
```
Precondition:
  - Fine record: nominal_denda = 50.000, status = 'unpaid'
  - Member bayar: Rp 30.000 (partial)
  
Steps:
  1. Navigate ke /pustakawan/fines
  2. Search member dengan denda
  3. Select fine record
  4. Input payment amount: 30000
  5. Select metode: Tunai
  6. Click "Catat Pembayaran"
  
Expected Result:
  ✅ FINE_PAYMENTS record created
  ✅ FINES.status updated → "partial"
  ✅ Remaining balance = 50.000 - 30.000 = 20.000
  ✅ Member masih suspend (denda belum lunas)
  ✅ Kwitansi printed
  
Database:
  SELECT * FROM fine_payments WHERE fine_id = X
  → nominal_bayar = 30000
  
  SELECT * FROM fines WHERE id = X
  → status = 'partial'
  
Acceptance: PASS
```

#### TC-024: Pembayaran Denda - Full Payment
```
Precondition:
  - Fine: 50.000, status = 'unpaid'
  - Member bayar: Rp 50.000
  
Steps:
  1. Input payment: 50000
  2. Click "Catat Pembayaran"
  
Expected Result:
  ✅ FINE_PAYMENTS created
  ✅ FINES.status = "paid"
  ✅ Member status un-suspended otomatis (jika tidak ada denda lain)
  ✅ Update: MEMBERS.status = 'aktif'
  ✅ Success: "Pembayaran berhasil. Member restored"
  ✅ Kwitansi printed
  
Database:
  SELECT * FROM members WHERE id = X
  → status = 'aktif'
  
Acceptance: PASS
```

---

## 6. SYSTEM INTEGRATION TEST (SIT)

### 6.1 End-to-End Scenario: Full Circulation Flow

#### SIT-001: Complete Loan-Return-Fine Workflow
```
Scenario: Anggota pinjam buku terlambat dan bayar denda

Timeline:
  2026-06-01  → Member A login & borrow Book X
  2026-06-15  → Due date (14 hari)
  2026-06-20  → Return book (5 hari late) → Fine Rp 25.000 auto-created
  2026-06-25  → Member A login & view fine
  2026-06-27  → Member A bayar denda (full)
  2026-06-28  → Member A restored (status aktif)

Steps:
  1. Admin: Create 5 test members + 10 test books
  2. Pustakawan: Process 5 peminjaman on 2026-06-01
  3. Pustakawan: Process 5 pengembalian on 2026-06-20 (5 hari late)
  4. System: Verify FINES auto-created (5 records)
  5. Pustakawan: Record 5 pembayaran on 2026-06-27
  6. System: Verify all members restored
  
Expected Result:
  ✅ All 5 loans → returns → fines → payments flow correctly
  ✅ BOOKS.stok updated correctly (5 → 5)
  ✅ MEMBERS.status updated (suspend → aktif)
  ✅ AUDIT_LOG recorded all actions
  ✅ No data corruption
  
Database Validation:
  SELECT COUNT(*) FROM loans WHERE status = 'returned' AND created_at > '2026-06-01'
  → 5 records
  
  SELECT COUNT(*) FROM fines WHERE nominal_denda > 0 AND created_at > '2026-06-20'
  → 5 records, total = 125.000
  
  SELECT COUNT(*) FROM fine_payments WHERE created_at > '2026-06-27'
  → 5 records, total = 125.000
  
  SELECT COUNT(*) FROM members WHERE status = 'aktif'
  → All 5 members restored
  
Acceptance: PASS
```

### 6.2 Search & Catalog Integration

#### SIT-002: Member Search - Filter - Borrow Integration
```
Scenario: Member cari buku di OPAC → View detail → Pinjam

Steps:
  1. Member login
  2. Search: "Teknologi", kategori filter, tahun 2025
  3. Results show 50 books (expected)
  4. Click book detail (ISBN: 9789797869234)
  5. See available stok = 3
  6. Click "Pinjam" button
  7. System validate member & stok
  8. Create loan transaction
  9. Redirect to confirmation page
  
Expected Result:
  ✅ Search response < 2 detik
  ✅ Filter hasil akurat
  ✅ Detail page load < 1 detik
  ✅ Loan transaction created
  ✅ Stok updated: 3 → 2
  
Acceptance: PASS
```

### 6.3 Authorization & RBAC Integration

#### SIT-003: Role-Based Access Control Validation
```
Scenario: Verify 3 roles hanya akses fitur yang sesuai

Test Cases:
  
  Role: ADMIN
  ✅ Can create/edit/delete users
  ✅ Can create/edit/delete books
  ✅ Can create/edit/delete members
  ✅ Can view all loans/returns/fines
  ✅ Can view dashboard
  ✅ Can generate reports
  
  Role: PUSTAKAWAN
  ✅ Can process loans (create)
  ✅ Can process returns (create)
  ✅ Can record fines payment
  ✅ Can search catalog (OPAC)
  ❌ Cannot delete users
  ❌ Cannot view dashboard
  ❌ Cannot edit book stok (readonly)
  
  Role: MEMBER
  ✅ Can search catalog (OPAC)
  ✅ Can view my loans
  ✅ Can view my fines
  ✅ Can change password
  ❌ Cannot create new user
  ❌ Cannot access admin panel
  ❌ Cannot manage books
  
Steps:
  1. Login sebagai Admin → Access /admin → ✅ Success
  2. Login sebagai Admin → Access /pustakawan → ✅ Success
  3. Login sebagai Pustakawan → Access /admin → ❌ Forbidden 403
  4. Login sebagai Pustakawan → Process loan → ✅ Success
  5. Login sebagai Member → Search OPAC → ✅ Success
  6. Login sebagai Member → Access /admin → ❌ Forbidden 403
  
Expected Result:
  ✅ All authorization checks working
  ✅ 403 Forbidden displayed for unauthorized access
  ✅ Menu sidebar dinamis sesuai role
  ✅ Database permission checked per request
  
Acceptance: PASS
```

---

## 7. DATABASE INTEGRITY TESTS

### 7.1 Data Consistency Validation

#### TC-025: Stok Consistency After Loan-Return
```
Query:
  SELECT 
    b.judul,
    b.stok_total,
    COUNT(CASE WHEN li.id IS NOT NULL THEN 1 END) as items_borrowed,
    b.stok_tersedia,
    (b.stok_total - COUNT(CASE WHEN li.id IS NOT NULL THEN 1 END)) as expected_available
  FROM books b
  LEFT JOIN loan_items li ON b.id = li.book_id
  LEFT JOIN loans l ON li.loan_id = l.id AND l.status = 'active'
  WHERE b.id = 1
  GROUP BY b.id;

Expected:
  stok_total - items_borrowed = stok_tersedia
  
  Example:
  - stok_total = 5
  - items_borrowed = 2
  - expected_available = 3
  - actual stok_tersedia = 3 ✅
  
Acceptance: PASS
```

#### TC-026: Referential Integrity - Cascade Delete
```
Scenario: Delete member dengan active loans

Steps:
  1. Create member + loan records
  2. DELETE FROM members WHERE id = X;
  
Expected Result:
  ✅ Member deleted
  ✅ Associated LOANS deleted (CASCADE)
  ✅ Associated LOAN_ITEMS deleted
  ✅ No orphaned records
  
Acceptance: PASS
```

#### TC-027: Fine Calculation Accuracy
```
Dataset:
  Loan 1: due_date = 2026-06-15
  Return 1: 2026-06-20 (5 hari late)
  daily_rate = 5000
  expected_fine = 5 × 5000 = 25000
  
Steps:
  1. Process return dengan return_date = 2026-06-20
  2. Verify FINES.nominal_denda
  
Expected:
  FINES.nominal_denda = 25000 ✅
  
Acceptance: PASS
```

---

## 8. PERFORMANCE TEST

### 8.1 Response Time Validation

| Scenario | Expected | Status |
|----------|----------|--------|
| Search books (500 records) | < 2 detik | ✅ |
| View book detail | < 1 detik | ✅ |
| Generate loan receipt | < 500ms | ✅ |
| Calculate fine (trigger) | < 100ms | ✅ |
| Export report (100 records) | < 5 detik | ✅ |
| Login | < 1 detik | ✅ |
| Dashboard load | N/A (Phase 2) | - |

### 8.2 Database Query Optimization

#### TC-028: Query Performance - Search Index
```
Query: SELECT * FROM books WHERE judul LIKE '%PHP%';

Without index:
  - Execution time: ~500ms (500 records full table scan)
  
With INDEX on judul:
  - Execution time: < 50ms (indexed search)
  
Expected: 
  ✅ Performance improved > 90%
  ✅ EXPLAIN shows "index_judul" usage
  
Acceptance: PASS
```

---

## 9. SECURITY TESTING

### 9.1 OWASP Top 10 Validation

| Vulnerability | Test Case | Expected | Status |
|---------------|-----------|----------|--------|
| **SQL Injection** | Input: `' OR '1'='1` | Blocked by parameterized query | ✅ |
| **XSS** | Input: `<script>alert('xss')</script>` | HTML entity encoded | ✅ |
| **CSRF** | POST without CSRF token | 419 Unauthorized | ✅ |
| **Weak Auth** | Default credentials | Changed on first login | ✅ |
| **Sensitive Data** | Password in logs | Never logged | ✅ |
| **Broken Access** | Member access admin | 403 Forbidden | ✅ |
| **Security Headers** | Missing CSP | Added in app.php | ✅ |

#### TC-029: SQL Injection Prevention
```
Test: Input SQL payload in search
  URL: /opac/search?q='; DROP TABLE books; --
  
Expected:
  ✅ Query escaped properly
  ✅ No SQL error displayed
  ✅ Generic error message only
  ✅ Table intact
  
Acceptance: PASS
```

#### TC-030: XSS Prevention
```
Test: Input JavaScript in book title
  Create book dengan judul: <img src=x onerror="alert('xss')">
  
Expected:
  ✅ Title stored as-is in DB
  ✅ Output rendered as HTML entity
  ✅ No script execution
  
Acceptance: PASS
```

---

## 10. UAT PREPARATION

### 10.1 UAT Checklist
- [ ] All TC passed dengan status PASS
- [ ] No critical bugs remaining
- [ ] Database backup created
- [ ] Test environment stable & ready
- [ ] UAT script prepared
- [ ] Stakeholder onboarding completed
- [ ] Test data loaded
- [ ] Documentation ready

### 10.2 UAT Participants
| Role | Name | Responsibility |
|------|------|-----------------|
| Kepala Perpustakaan | TBD | Validasi kebutuhan fungsional |
| Staf Perpustakaan | 2-3 orang | Validasi workflow operasional |
| Admin IT | 1 orang | Validasi teknis & performance |
| QA Lead | TBD | Moderator & documentasi |

### 10.3 UAT Duration
- Week 1: UAT Phase 1 (Modul 1-3)
- Week 2: UAT Phase 2 (Modul 4-5)
- Week 3: Issue resolution
- Week 4: Sign-off & BAST preparation

---

## 11. TEST EXECUTION SCHEDULE

```
Week 1: Unit Testing
  Mon: Module 1 (Auth) unit test
  Tue: Module 2 (Member) unit test
  Wed: Module 3 (Books) unit test
  Thu: Test report review
  Fri: Bug fix & retesting

Week 2: Integration Testing
  Mon: Modul 4-5 integration test
  Tue: Loan-Return-Fine flow
  Wed: RBAC validation
  Thu: Database integrity tests
  Fri: Test report review

Week 3: System Testing
  Mon: SIT Phase 1
  Tue: SIT Phase 2
  Wed: Performance & security testing
  Thu: Bug fix & retesting
  Fri: Test environment handover

Week 4: UAT
  Mon-Fri: UAT Phase 1-2 with stakeholders
  Ongoing: Issue logging & resolution
```

---

## 12. TEST METRICS & KPI

| Metric | Target | Tolerance |
|--------|--------|-----------|
| **Test Coverage** | 80%+ | ≥ 70% |
| **Pass Rate** | 95%+ | ≥ 90% |
| **Critical Bugs** | 0 | 0 |
| **Response Time** | < 2 detik | < 3 detik |
| **Security Issues** | 0 OWASP Top 10 | 0 |

---

## 13. RISK & MITIGATION

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|-----------|
| Database performance degradation | High | High | Implement indexing early, performance test before UAT |
| Data loss during testing | Medium | High | Daily backups, fresh DB per test cycle |
| Scope creep | Medium | Medium | Strict scope control, change control process |
| Stakeholder unavailable for UAT | Low | High | Schedule UAT well in advance, get commitments |

---

## 14. TEST SIGN-OFF

### QA Lead Approval
- [ ] All test cases executed
- [ ] All critical/high bugs resolved
- [ ] Test report complete
- [ ] Ready for UAT

**QA Lead**: ________________  
**Date**: ________________  
**Status**: [ ] APPROVED [ ] CONDITIONAL [ ] REJECTED

### Project Manager Approval
- [ ] Test plan executed as planned
- [ ] Timeline met
- [ ] Ready for UAT

**PM**: ________________  
**Date**: ________________  

---

## 15. APPENDICES

### A. Test Tools Required
- PHPUnit (Unit testing)
- Postman / Insomnia (API testing)
- Selenium / Cypress (E2E testing)
- Apache JMeter (Performance testing)
- OWASP ZAP (Security scanning)

### B. Test Data Templates
- test_members.xlsx (100 members)
- test_books.xlsx (500 books)
- test_loans_data.sql (historical loans)

### C. Reference Documents
- SRS_SIPERPUS_LENGKAP.md
- STRUKTUR_DATABASE.md
- ERD_MERMAID.md

---

**Document Version**: 2.0  
**Last Updated**: 2026-06-30  
**Next Review**: Upon Phase 2 Planning
