# 📋 RENCANA QA & TESTING KOMPREHENSIF
## SIPERPUS - Sistem Informasi Perpustakaan & Gallery Art Lelang

**Dokumen**: Rencana QA & Testing Komprehensif  
**Versi**: 3.0 - Finalisasi UAT & Dokumentasi BAST  
**Tanggal**: 2026-06-30  
**Status**: Ready for Testing & UAT Execution  
**Tim QA**: Quality Assurance Team  

---

## 📑 DAFTAR ISI

1. [Ringkasan Eksekutif](#ringkasan-eksekutif)
2. [Penyusunan Test Plan & Scenario](#penyusunan-test-plan--scenario)
3. [Pengujian Parsial Modul 1-3](#pengujian-parsial-modul-1-3)
4. [Pengujian Parsial Modul 4-5](#pengujian-parsial-modul-4-5)
5. [System Integration Testing (SIT)](#system-integration-testing)
6. [End-to-End Testing (E2E)](#end-to-end-testing)
7. [Dokumentasi UAT & BAST](#dokumentasi-uat--bast)
8. [Test Metrics & KPI](#test-metrics--kpi)
9. [Risk & Mitigation](#risk--mitigation)

---

## 🎯 RINGKASAN EKSEKUTIF

### Scope Testing
```
✅ Modul 1-3: Autentikasi, Manajemen Anggota, Data Buku & Katalog
✅ Modul 4-5: OPAC & Pencarian, Sirkulasi & Denda
✅ Integrasi Antar Modul (SIT)
✅ End-to-End Testing (E2E)
✅ Security & Performance Validation
✅ UAT dengan Stakeholder
```

### Timeline Testing
- **Week 1**: Unit Testing + Parsial Modul 1-3
- **Week 2**: Parsial Modul 4-5 + Integration Testing
- **Week 3**: System Testing + Performance & Security
- **Week 4**: UAT Phase 1-2 + Final Fixes + Sign-off

### Resources
- **QA Lead**: 1 orang
- **QA Engineer**: 2 orang
- **Test Data Specialist**: 1 orang
- **Stakeholder**: 2-3 orang (UAT)

---

# 📐 PENYUSUNAN TEST PLAN & SCENARIO

## 1. Testing Strategy

### 1.1 Pendekatan Testing
```
PYRAMID MODEL:
┌─────────────────────────────────────┐
│       Manual UAT Testing (10%)      │ ← User Acceptance
│    System Integration Testing (20%)  │ ← End-to-End Flows
│   Unit + Integration Tests (70%)     │ ← Functional Core
└─────────────────────────────────────┘
```

### 1.2 Testing Levels & Tools

| Level | Tipe | Tools | Coverage | Target |
|-------|------|-------|----------|--------|
| **Unit** | Backend logic testing | PHPUnit, Laravel | Functions/Methods | ≥80% |
| **Integration** | Module-to-module | PHPUnit + DB | Database triggers | ≥70% |
| **API** | REST endpoint | Postman/Insomnia | All endpoints | 100% |
| **System** | Full workflow | Manual + Browser | Use cases | 100% |
| **UAT** | User acceptance | Manual | Stakeholder needs | 100% |
| **Performance** | Response time | Browser DevTools | < 2s search | ✅ |

### 1.3 Test Scope Matrix

| Modul | Unit Test | Integration | System | E2E | UAT | Security |
|-------|:---------:|:-----------:|:------:|:---:|:---:|:--------:|
| **Auth & User** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Anggota** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Buku & Katalog** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **OPAC Search** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Sirkulasi** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Denda** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **E-Resources** | ✅ | ✅ | ✅ | ⏳ | ⏳ | ✅ |
| **Dashboard** | ✅ | ✅ | ⏳ | ⏳ | ⏳ | ✅ |

**Legend**: ✅ In Scope | ⏳ Phase 2 | ❌ Out of Scope

---

## 2. Test Environment Setup

### 2.1 Infrastructure

```
┌────────────────────────────────────────────────────┐
│           TEST ENVIRONMENT ARCHITECTURE           │
├────────────────────────────────────────────────────┤
│                                                   │
│  Frontend Layer:                                 │
│  ├─ Browser: Chrome 120+, Firefox 121+           │
│  ├─ Responsive: Desktop, Tablet, Mobile          │
│  └─ Framework: Laravel Blade + Bootstrap 5       │
│                                                   │
│  Application Layer:                              │
│  ├─ Runtime: PHP 8.1                             │
│  ├─ Framework: Laravel 10.x                      │
│  └─ Package: Spatie Permission RBAC              │
│                                                   │
│  Database Layer:                                 │
│  ├─ Engine: MySQL 8.0 / MariaDB                  │
│  ├─ Instance: siperpus_test (Test DB)            │
│  └─ Storage: Max 500GB (with cleanup)            │
│                                                   │
│  Supporting Services:                            │
│  ├─ Cache: Redis (for session & caching)        │
│  ├─ Storage: Local disk (/storage/app/public)    │
│  └─ Logging: Laravel logs + test reports        │
│                                                   │
└────────────────────────────────────────────────────┘
```

### 2.2 Database Setup & Test Data

```bash
# Fresh database setup untuk setiap test cycle
php artisan migrate:fresh --database=testing
php artisan db:seed --class=RolePermissionSeeder --database=testing
php artisan db:seed --class=TestDataSeeder --database=testing

# Database credentials untuk testing
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siperpus_test
DB_USERNAME=root
DB_PASSWORD=test123
```

### 2.3 Test Data Volume

| Data Type | Volume | Growth/Bulan | Purpose |
|-----------|--------|--------------|---------|
| **Users** | 3+ | N/A | Admin, Pustakawan, Public |
| **Members** | 100-500 | 10-20 | Active, Suspend, Non-aktif |
| **Books** | 500+ | 20-50 | Various categories & stock |
| **Loans** | 200+ | 50-100 | Historical + active loans |
| **Returns** | 200+ | 50-100 | On-time & late returns |
| **Fines** | 100+ | 20-30 | Paid, unpaid, partial |
| **Transactions** | 1000+ | 100+ | Complete circulation cycle |

---

# 🔍 PENGUJIAN PARSIAL MODUL 1-3

## Modul 1: Autentikasi & Manajemen Pengguna

### Test Case 1.1: Login dengan Kredensial Valid

```
ID: TC-M1-001
Judul: User Login - Valid Credentials
Kategori: Functional | Priority: Critical

Precondition:
  - User terdaftar di database (admin@gallery.com)
  - Browser dapat akses aplikasi
  - Session tidak expired

Steps:
  1. Navigasi ke halaman login (/login)
  2. Input email: admin@gallery.com
  3. Input password: password123
  4. Click button "Login" atau press Enter
  5. Verify redirect ke dashboard sesuai role

Expected Result:
  ✅ Login berhasil
  ✅ Session created dengan timeout 30 menit
  ✅ User diredirect ke dashboard/home sesuai role
  ✅ Menu sidebar sesuai role (admin/staff/public)
  ✅ Login event terlog di audit_log table
  ✅ No error messages displayed
  ✅ Cookies set dengan secure flag

Data Validation (SQL):
  SELECT * FROM audit_logs 
  WHERE action = 'login' AND user_id = 1 
  ORDER BY created_at DESC LIMIT 1;
  → Record harus ada dengan timestamp hari ini

Test Result: [ ] PASS [ ] FAIL
Notes: 
Tester: ________________  Date: __________
```

### Test Case 1.2: Login dengan Kredensial Salah

```
ID: TC-M1-002
Judul: User Login - Invalid Credentials
Kategori: Functional | Priority: High

Precondition:
  - User terdaftar di database
  - Browser dapat akses login page

Steps:
  1. Navigasi ke /login
  2. Input email: admin@gallery.com
  3. Input password: wrong_password
  4. Click "Login"
  5. Observe error message & form state

Expected Result:
  ✅ Error message: "Kredensial tidak valid" atau similar
  ✅ User tetap di halaman login (tidak redirect)
  ✅ Form kosong (cleared for security)
  ✅ Email field mungkin ter-populate (optional)
  ✅ Failed attempt terlog di audit_log

Security Check:
  ✅ Password tidak ditampilkan di error message
  ✅ No specific error (mencegah enumeration attack)
  ✅ Response time consistent (mencegah timing attack)

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 1.3: Account Lockout setelah Failed Attempts

```
ID: TC-M1-003
Judul: Account Lockout - Failed Login Attempts
Kategori: Security | Priority: High

Precondition:
  - User account tidak terkunci
  - Max failed attempts = 3 (configurable)
  - Lockout duration = 15 menit

Steps:
  1. Attempt login 3x dengan password salah
  2. Attempt ke-4 login dengan password benar
  3. Observe akun status
  4. Wait 15 menit (atau test auto-unlock via code)
  5. Attempt login kembali

Expected Result (After 3 failed attempts):
  ✅ Attempt ke-3 menampilkan error
  ✅ Attempt ke-4 error: "Akun Anda terkunci"
  ✅ Error message: "Coba lagi dalam 15 menit"
  ✅ Failed attempts terlog
  ✅ Lock timestamp recorded

Expected Result (After 15 minutes):
  ✅ Account auto-unlocked
  ✅ Login berhasil dengan password benar
  ✅ Unlock event terlog

Database Validation:
  SELECT * FROM login_attempts 
  WHERE user_id = 1 AND created_at > NOW() - INTERVAL 1 DAY;
  → Harus ada records dari failed attempts

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 1.4: Logout Functionality

```
ID: TC-M1-004
Judul: User Logout
Kategori: Functional | Priority: High

Precondition:
  - User sudah login
  - Browser di halaman yang ada logout button

Steps:
  1. Click button "Logout" / "Sign Out"
  2. Confirm logout (jika ada confirmation modal)
  3. Observe halaman & session state
  4. Try akses admin page tanpa login

Expected Result:
  ✅ Session destroyed
  ✅ Cookies cleared/invalidated
  ✅ Redirect ke login page atau homepage
  ✅ Logout event terlog di audit_log
  ✅ Subsequent request tidak dapat akses protected routes
  ✅ No sensitive data di localStorage/sessionStorage

Security Check:
  ✅ CSRF token di-refresh setelah logout
  ✅ Session ID tidak dapat reuse
  ✅ Any pending async requests stopped

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 1.5: Reset Password Feature

```
ID: TC-M1-005
Judul: Reset Password via Email Link
Kategori: Functional | Priority: High

Precondition:
  - User email valid & registered
  - Email service configured
  - User has valid email account

Steps:
  1. Navigate ke /login → Click "Lupa Password?"
  2. Input email: user@example.com
  3. Click "Kirim Link Reset"
  4. Observe confirmation message
  5. Check email inbox (or simulate email)
  6. Click reset link dalam email
  7. Verify link validity (token validation)
  8. Input password baru: NewPass@123!
  9. Input confirm password: NewPass@123!
  10. Click "Update Password"
  11. Try login dengan password baru

Expected Result:
  ✅ Confirmation: "Email reset telah dikirim"
  ✅ Email diterima dengan reset link
  ✅ Link valid selama 24 jam
  ✅ Link berisi secure token
  ✅ Halaman reset dengan form password baru
  ✅ Password validation: min 8 char, upper, lower, number, special
  ✅ Success: "Password berhasil diupdate"
  ✅ Can login dengan password baru
  ✅ Cannot login dengan password lama
  ✅ Reset event terlog

Token Validation:
  - Token harus unique & cryptographically secure
  - Token harus store di database dengan expiry time
  - Token invalidate setelah digunakan

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

## Modul 2: Manajemen Anggota (Members)

### Test Case 2.1: Create Member - Valid Data

```
ID: TC-M2-001
Judul: Create Member dengan Data Valid
Kategori: Functional | Priority: Critical

Precondition:
  - Admin/Pustakawan sudah login
  - Access ke halaman create member
  - Semua required fields kosong

Steps:
  1. Navigate ke /admin/members atau /pustakawan/members/create
  2. Fill form fields:
     - NIM/NIDN: 12345678
     - Nama Lengkap: John Doe
     - Program Studi: Sistem Informasi
     - No HP: 08123456789
     - Alamat: Jl. Imam Bonjol No. 1
     - Status: active
     - Tanggal Daftar: 2026-06-30
  3. Click "Simpan" atau "Tambah Anggota"
  4. Observe hasil & notification

Expected Result:
  ✅ Member created successfully
  ✅ Redirect ke halaman list members
  ✅ Success notification: "Anggota berhasil ditambahkan"
  ✅ New member visible di list dengan data yang benar
  ✅ Data tersimpan di database members table
  ✅ Record created_at timestamp = sekarang
  ✅ Status = active sebagaimana input

Database Validation (SQL):
  SELECT * FROM members 
  WHERE nim_nidn = '12345678' 
  ORDER BY created_at DESC LIMIT 1;
  
  Expected fields:
  - nim_nidn: 12345678
  - full_name: John Doe
  - department: Sistem Informasi
  - phone: 08123456789
  - status: active
  - created_at: TODAY

Audit Log Check:
  SELECT * FROM audit_logs 
  WHERE action = 'create_member' 
  AND resource_id = <new_member_id>
  → Record harus ada

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 2.2: Create Member - Duplicate NIM

```
ID: TC-M2-002
Judul: Create Member dengan NIM Duplikat
Kategori: Validation | Priority: High

Precondition:
  - Member dengan NIM 12345678 sudah ada di database
  - Admin login & di create member form

Steps:
  1. Navigate ke form create member
  2. Fill form dengan:
     - NIM: 12345678 (sudah terdaftar)
     - Nama: Different Name
     - Other fields valid
  3. Click "Simpan"
  4. Observe validation error

Expected Result:
  ✅ Form tidak submit
  ✅ Validation error ditampilkan: "NIM sudah terdaftar"
  ✅ Error message di field NIM (highlighted in red)
  ✅ Other fields tetap terisi (data preserved)
  ✅ No duplicate record di database

Validation Logic Check:
  - Unique constraint pada nim_nidn column
  - Database-level validation active
  - Form-level validation working

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 2.3: Search Member by NIM

```
ID: TC-M2-003
Judul: Search Member by NIM
Kategori: Functional | Priority: High

Precondition:
  - Minimal 50 member di database
  - Admin/Pustakawan login
  - Search functionality implemented

Steps:
  1. Navigate ke /admin/members
  2. Locate search box
  3. Input search query: 12345 (partial NIM)
  4. Click "Search" atau press Enter
  5. Observe results
  6. Check response time
  7. Test pagination

Expected Result:
  ✅ Results show members dengan NIM matching "12345"
  ✅ Response time < 1 detik (dari 50+ records)
  ✅ Results accurate (no false positives)
  ✅ Pagination working (if > 10 results)
  ✅ Sort option working
  ✅ Clear search button available
  ✅ No N+1 queries detected

Performance Check (Database):
  - Query should use indexed search
  - Response time logged < 1000ms
  - No full table scans

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 2.4: Update Member Status to Suspend

```
ID: TC-M2-004
Judul: Update Member Status to Suspend
Kategori: Functional | Priority: High

Precondition:
  - Member exists dengan status active
  - Member punya outstanding fines > limit
  - Admin login

Steps:
  1. Navigate ke /admin/members
  2. Search atau find member
  3. Click "Edit" atau member row
  4. Change status dari "active" → "suspend"
  5. Reason/Note: "Outstanding fines"
  6. Click "Simpan" atau "Update"
  7. Verify member cannot borrow books

Expected Result:
  ✅ Status updated menjadi suspend
  ✅ Updated_at timestamp refreshed
  ✅ Update event terlog di audit_log
  ✅ Member tidak muncul di active members list
  ✅ When try to borrow: Error "Member suspend"
  ✅ Notification sent to member (optional)

Side Effects Check:
  ✅ Member cannot create new loans
  ✅ Existing active loans not affected
  ✅ Member still dapat bayar denda
  ✅ Dashboard count updated

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 2.5: Export Member Data to Excel

```
ID: TC-M2-005
Judul: Export Member Data to Excel
Kategori: Functional | Priority: Medium

Precondition:
  - Minimal 50 member di database
  - Admin login
  - Export feature available

Steps:
  1. Navigate ke /admin/members
  2. Click "Export Excel" button
  3. Select date range (if available)
  4. Optional: Select status filter
  5. Click "Download"
  6. Save file
  7. Open file di Excel/LibreOffice

Expected Result:
  ✅ File downloaded dengan nama: members_2026_06_30.xlsx
  ✅ File size reasonable (< 10MB untuk 1000 records)
  ✅ Headers present & correct
  ✅ All data rows included & complete
  ✅ Excel file dapat dibuka tanpa error
  ✅ Data formatting correct (dates, numbers)
  ✅ No truncated data
  ✅ Character encoding correct (support Indonesian characters)

Data Validation in Excel:
  - Column headers match specification
  - Number of rows = database query result
  - Data types preserved (dates as dates, not text)
  - Numbers formatted correctly
  - Special characters (ñ, ü, etc.) displayed properly

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

## Modul 3: Data Buku & Katalog

### Test Case 3.1: Create Book - Valid Data

```
ID: TC-M3-001
Judul: Create Book dengan Data Valid
Kategori: Functional | Priority: Critical

Precondition:
  - Admin login
  - Access ke /admin/books/create
  - Categories sudah ada

Steps:
  1. Navigate ke /admin/books/create
  2. Fill form:
     - ISBN: 9789797869234
     - Judul: Pemrograman PHP Dasar
     - Penulis: Budi Raharjo
     - Penerbit: Informatika
     - Tahun: 2025
     - Kategori: Teknologi
     - Lokasi Rak: A-01-03
     - Stok: 5
     - Kondisi: baik
  3. Click "Simpan"
  4. Observe result

Expected Result:
  ✅ Book created successfully
  ✅ Redirect ke books list
  ✅ Success message: "Buku berhasil ditambahkan"
  ✅ New book visible di list
  ✅ All fields saved correctly
  ✅ Image uploaded (if provided)
  ✅ Record created_at = today

Database Check:
  SELECT * FROM books 
  WHERE isbn = '9789797869234' LIMIT 1;
  → Record harus ada dengan fields benar

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 3.2: Bulk Import Books from Excel

```
ID: TC-M3-002
Judul: Bulk Import Books dari Excel
Kategori: Functional | Priority: High

Precondition:
  - Admin login
  - File books_import.xlsx siap (100 records)
  - Format file valid
  - Excel sudah ada di upload dir

Steps:
  1. Navigate ke /admin/books
  2. Click "Bulk Import" atau "Import Excel"
  3. Select file: books_import.xlsx (100 books)
  4. Click "Upload" atau "Process"
  5. Monitor progress (if progress bar available)
  6. Wait untuk import complete
  7. Review import results

Expected Result:
  ✅ All 100 books imported successfully
  ✅ Processing time < 10 detik
  ✅ Success message: "100 buku berhasil diimport"
  ✅ Imported books visible di list
  ✅ All rows with valid data imported
  ✅ Validation errors shown (if any problematic rows)
  ✅ Import log recorded

Validation Check:
  - ISBN unique constraint checked
  - Required fields validated
  - Incorrect rows rejected atau flagged
  - Error report provided (if any)

Database Check:
  SELECT COUNT(*) FROM books 
  WHERE created_at = TODAY;
  → Harus ada minimal 100 records (atau sesuai file)

Performance:
  - 100 records imported < 10 seconds
  - No timeout errors
  - Server resources normal

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 3.3: Search Book by Title

```
ID: TC-M3-003
Judul: Search Book by Title (OPAC)
Kategori: Functional | Priority: High

Precondition:
  - Minimal 500 books di database
  - User login (member atau admin)
  - OPAC page accessible

Steps:
  1. Navigate ke /opac atau public catalog
  2. Input search box: "PHP"
  3. Click "Search" atau press Enter
  4. Observe results & response time
  5. Check result relevance
  6. Test pagination

Expected Result:
  ✅ Results show books dengan judul mengandung "PHP"
  ✅ Response time < 2 detik
  ✅ Results sorted by relevance
  ✅ Pagination working (if > 10 results)
  ✅ No 404 errors
  ✅ Search case-insensitive (PHP = php = Php)

Performance Metrics:
  - Response time from database query < 1.5s
  - Index on judul column in use
  - LIKE search optimized

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

# 🔄 PENGUJIAN PARSIAL MODUL 4-5

## Modul 4: OPAC & Pencarian

### Test Case 4.1: Advanced Search with Multiple Filters

```
ID: TC-M4-001
Judul: Advanced Search dengan Multiple Filters
Kategori: Functional | Priority: High

Precondition:
  - Books dengan berbagai kategori & tahun
  - Minimal 500 books di database
  - User login (member)
  - OPAC page loaded

Steps:
  1. Navigate ke OPAC /opac atau /catalog
  2. Click "Advanced Search" atau filter icon
  3. Set multiple filters:
     - Kategori: Teknologi
     - Tahun Publikasi: 2024-2025
     - Status Stok: Tersedia
  4. Optional: Sort by "Judul A-Z"
  5. Click "Search" atau "Terapkan Filter"
  6. Observe results & count

Expected Result:
  ✅ Results filtered correctly
  ✅ Show only Teknologi + 2024-2025 + tersedia
  ✅ Result count accurate
  ✅ Response time < 2 detik
  ✅ Filter tags visible & removable
  ✅ "Clear Filters" button available
  ✅ Pagination working

Filter Logic Check:
  - Kategori filter: SQL WHERE kategori_id IN (...)
  - Tahun filter: WHERE tahun BETWEEN 2024 AND 2025
  - Stok filter: WHERE stok_tersedia > 0
  - AND operator used (not OR)

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 4.2: View Book Detail

```
ID: TC-M4-002
Judul: View Book Detail Page
Kategori: Functional | Priority: High

Precondition:
  - Book exists di database
  - User login (member)
  - Search/catalog page loaded

Steps:
  1. Navigate ke OPAC /opac
  2. Search atau browse book list
  3. Click book title atau "Lihat Detail"
  4. Wait untuk detail page load
  5. Verify semua informasi tampil

Expected Result:
  ✅ Detail page loads < 1 detik
  ✅ Display semua fields:
     - Cover image (if available)
     - ISBN
     - Judul
     - Penulis
     - Penerbit
     - Tahun Publikasi
     - Kategori
     - Stok Tersedia
     - Lokasi Rak
     - Deskripsi (if available)
  ✅ Current borrowers list (if applicable)
  ✅ "Pinjam" button visible (if stok > 0 & member aktif)
  ✅ "Tambah ke Wishlist" button (optional)
  ✅ Back button available
  ✅ No 404 errors

UI/UX Check:
  - Layout responsive (mobile, tablet, desktop)
  - Images load properly
  - Typography readable
  - Buttons clickable & intuitive

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

## Modul 5: Sirkulasi & Denda

### Test Case 5.1: Peminjaman Buku - Valid Member & Stok

```
ID: TC-M5-001
Judul: Peminjaman Buku - Valid Member & Stok Tersedia
Kategori: Functional | Priority: Critical

Precondition:
  - Pustakawan login
  - Member aktif: NIM 12345678 (no denda)
  - Book tersedia: ISBN 9789797869234 (stok: 5)

Steps:
  1. Navigate ke /pustakawan/loans/create
  2. Input NIM Member: 12345678
  3. System validates member (aktif, no denda)
  4. Input/Scan ISBN: 9789797869234
  5. System validates stok (5 > 0)
  6. Select durasi (default 14 hari)
  7. Click "Proses Peminjaman" atau "Simpan"
  8. Verify bukti peminjaman printed
  9. Confirm success message

Expected Result:
  ✅ LOANS transaction created
  ✅ LOAN_ITEMS record created
  ✅ Stok updated: 5 → 4
  ✅ Due date calculated: today + 14 hari
  ✅ Status = "active"
  ✅ Bukti peminjaman (struk + QR) generated
  ✅ Success: "Peminjaman berhasil"
  ✅ Record logged: user_id, timestamp, member_id, book_id

Database Validation (SQL):
  -- Check LOANS record
  SELECT * FROM loans 
  WHERE member_id = 1 AND status = 'active' 
  ORDER BY created_at DESC LIMIT 1;
  
  Expected:
  - loan_date: 2026-06-30
  - due_date: 2026-07-14 (14 days later)
  - status: active
  
  -- Check BOOKS stok
  SELECT stok, stok_tersedia FROM books 
  WHERE isbn = '9789797869234' LIMIT 1;
  
  Expected:
  - stok_tersedia: 4 (decreased from 5)

Audit Log Check:
  SELECT * FROM audit_logs 
  WHERE action = 'create_loan' 
  AND resource_id = <loan_id>;
  → Record logged

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 5.2: Peminjaman - Member Suspend

```
ID: TC-M5-002
Judul: Peminjaman Buku - Member Suspend
Kategori: Functional | Priority: High

Precondition:
  - Member status: suspend
  - Member punya outstanding fines
  - Pustakawan login
  - Book available untuk dipinjam

Steps:
  1. Navigate ke /pustakawan/loans/create
  2. Input NIM suspended member
  3. Click "Validasi Member" atau proceed
  4. System checks member status
  5. Try input book ISBN
  6. Click "Proses Peminjaman"

Expected Result:
  ✅ Error message: "Member suspend. Lunasi denda terlebih dahulu"
  ✅ Peminjaman tidak diproses
  ✅ No LOANS transaction created
  ✅ Stok tidak berubah
  ✅ Form cleared/disabled

Business Logic Check:
  - Member status check before processing
  - Reject dengan pesan jelas
  - Log rejection attempt

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 5.3: Pengembalian On-Time

```
ID: TC-M5-003
Judul: Pengembalian Buku - On-Time
Kategori: Functional | Priority: Critical

Precondition:
  - Loan aktif: loan_date = 2026-06-01, due_date = 2026-06-15
  - Return date: 2026-06-14 (1 hari sebelum due)
  - Pustakawan login
  - Book dalam kondisi baik

Steps:
  1. Navigate ke /pustakawan/returns
  2. Input NIM Member atau scan
  3. Select active loan dari list
  4. Input return_date: 2026-06-14
  5. Input kondisi: Baik
  6. Click "Proses Pengembalian"
  7. Verify bukti pengembalian
  8. Check member denda status

Expected Result:
  ✅ RETURNS record created
  ✅ late_days = 0
  ✅ fine_amount = 0
  ✅ FINES record NOT created
  ✅ LOANS.status = "returned"
  ✅ BOOKS.stok_tersedia += 1 (restored)
  ✅ Message: "Pengembalian tepat waktu. Denda: Rp 0"
  ✅ Bukti pengembalian printed

Database Validation (SQL):
  -- Check RETURNS record
  SELECT * FROM returns WHERE loan_id = X LIMIT 1;
  
  Expected:
  - return_date: 2026-06-14
  - late_days: 0
  - fine_amount: 0
  
  -- Check no FINES created
  SELECT * FROM fines WHERE return_id = Y;
  → No record (atau count = 0)
  
  -- Check BOOKS stok
  SELECT stok_tersedia FROM books 
  WHERE isbn = '9789797869234' LIMIT 1;
  → Should increased by 1

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 5.4: Pengembalian Late - Auto Fine

```
ID: TC-M5-004
Judul: Pengembalian Late - Auto Fine Calculation
Kategori: Functional | Priority: Critical

Precondition:
  - Loan: due_date = 2026-06-15
  - Return date: 2026-06-20 (5 hari terlambat)
  - Daily rate: Rp 5.000
  - Pustakawan login

Steps:
  1. Navigate ke /pustakawan/returns
  2. Input NIM Member
  3. Select loan dengan due_date = 2026-06-15
  4. Input return_date: 2026-06-20
  5. System calculate: late_days = 5
  6. System calculate: fine = 5 × 5000 = Rp 25.000
  7. Input kondisi: Baik
  8. Click "Proses Pengembalian"

Expected Result:
  ✅ RETURNS record created:
     - return_date: 2026-06-20
     - late_days: 5
     - fine_amount: 25000
  ✅ TRIGGER auto-execute: Calculate & create FINES
  ✅ FINES record created:
     - nominal_denda: 25000
     - status: unpaid
     - due_payment: 2026-06-27 (7 hari setelah return)
  ✅ LOANS.status = "returned"
  ✅ Message: "Pengembalian terlambat. Denda: Rp 25.000"
  ✅ Display payment info untuk member
  ✅ Member status updated: suspend (jika apply)
  ✅ Bukti pengembalian dengan fine info printed

Database Validation (SQL):
  -- Check RETURNS
  SELECT * FROM returns 
  WHERE loan_id = X ORDER BY created_at DESC LIMIT 1;
  → late_days = 5
  
  -- Check FINES auto-created
  SELECT * FROM fines 
  WHERE return_id = Y ORDER BY created_at DESC LIMIT 1;
  
  Expected:
  - nominal_denda: 25000
  - status: unpaid
  - due_payment: 2026-06-27
  
  -- Check AUDIT_LOG
  SELECT * FROM audit_logs 
  WHERE action = 'create_fine' 
  ORDER BY created_at DESC LIMIT 1;
  → Record logged

Trigger Check:
  - Trigger fires automatically on RETURNS insert
  - Fine calculation accurate
  - Member status updated accordingly

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

### Test Case 5.5: Pembayaran Denda - Full Payment

```
ID: TC-M5-005
Judul: Pembayaran Denda - Full Payment
Kategori: Functional | Priority: High

Precondition:
  - Fine record: nominal_denda = 50.000, status = unpaid
  - Member akan bayar full amount
  - Pustakawan login

Steps:
  1. Navigate ke /pustakawan/fines atau /pustakawan/payments
  2. Search member dengan denda (by NIM)
  3. Display list outstanding fines
  4. Select fine record
  5. Input payment amount: 50000
  6. Select metode: Tunai (atau Cheque/Transfer)
  7. Input referensi (jika applicable)
  8. Click "Catat Pembayaran" atau "Proses"
  9. Verify kwitansi printed

Expected Result:
  ✅ FINE_PAYMENTS record created:
     - fine_id: <fine_id>
     - nominal_bayar: 50000
     - payment_method: tunai
     - paid_date: today
  ✅ FINES.status updated → "paid"
  ✅ Member status restored → "aktif" (if no other fines)
  ✅ MEMBERS table updated:
     - status: aktif (un-suspended)
  ✅ Success message: "Pembayaran berhasil. Member restored"
  ✅ Kwitansi printed dengan detail pembayaran
  ✅ Payment logged dalam FINE_PAYMENTS table

Database Validation (SQL):
  -- Check FINE_PAYMENTS
  SELECT * FROM fine_payments 
  WHERE fine_id = X 
  ORDER BY created_at DESC LIMIT 1;
  → nominal_bayar = 50000
  
  -- Check FINES updated
  SELECT * FROM fines WHERE id = X LIMIT 1;
  → status = paid
  
  -- Check MEMBERS restored
  SELECT * FROM members WHERE id = Y LIMIT 1;
  → status = aktif

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

# 🔗 SYSTEM INTEGRATION TESTING (SIT)

## SIT-001: Complete Loan-Return-Fine Workflow

```
Scenario: Anggota pinjam buku terlambat dan bayar denda

Timeline:
  2026-06-01 → Member A login & borrow Book X (create LOANS)
  2026-06-15 → Due date (14 hari after loan)
  2026-06-20 → Return book (5 hari late) → FINES auto-created Rp 25.000
  2026-06-25 → Member A login & view fine di dashboard
  2026-06-27 → Member A bayar denda (full) → member restored
  2026-06-28 → Member A dapat pinjam buku lagi

Test Steps:
  1. Setup: Create 5 test members + 10 test books
  2. Day 1 (2026-06-01): Process 5 peminjaman
     - Member A pinjam Book 1
     - Member B pinjam Book 2
     - Member C pinjam Book 3
     - Member D pinjam Book 4
     - Member E pinjam Book 5
  3. Day 20 (2026-06-20): Process 5 pengembalian (5 hari late)
     - Return Book 1 late → FINES auto-created
     - Return Book 2 late → FINES auto-created
     - ... (semua late)
  4. Verify: Check FINES records created (5 records)
  5. Day 27 (2026-06-27): Record 5 pembayaran (full)
  6. Verify: Check MEMBERS status restored (all aktif)
  7. Day 28: Verify members dapat pinjam again

Expected Result:
  ✅ All 5 loans → returns → fines → payments flow correctly
  ✅ BOOKS.stok updated correctly (before & after)
  ✅ MEMBERS.status updated (suspend → aktif)
  ✅ AUDIT_LOG recorded all actions
  ✅ No data corruption
  ✅ No orphaned records
  ✅ Referential integrity maintained

Database Validation:
  -- Loans count
  SELECT COUNT(*) as loan_count FROM loans 
  WHERE status = 'returned' 
  AND created_at >= '2026-06-01';
  → Expected: 5
  
  -- Fines count & amount
  SELECT COUNT(*) as fine_count, 
         SUM(nominal_denda) as total_fine 
  FROM fines 
  WHERE created_at >= '2026-06-20';
  → Expected: 5 records, total = 125.000 (5 × 25.000)
  
  -- Payments count & amount
  SELECT COUNT(*) as payment_count, 
         SUM(nominal_bayar) as total_paid 
  FROM fine_payments 
  WHERE created_at >= '2026-06-27';
  → Expected: 5 records, total = 125.000
  
  -- Members restored
  SELECT COUNT(*) FROM members 
  WHERE status = 'aktif' 
  AND id IN (1, 2, 3, 4, 5);
  → Expected: 5 (all restored)

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

# 🚀 END-TO-END TESTING (E2E)

## E2E-001: Member Search - Filter - Borrow Integration

```
Scenario: Member cari buku di OPAC → View detail → Pinjam

Test Steps:
  1. Member login ke sistem
  2. Navigate ke OPAC /opac
  3. Search dengan filter:
     - Kategori: Teknologi
     - Tahun: 2025
  4. Results show 50 books (expected)
  5. Click book "PHP Dasar" (ISBN: 9789797869234)
  6. Detail page shows: stok = 3, kondisi = baik
  7. Click "Pinjam" button
  8. System validate member (aktif, no denda, limit ok)
  9. Create LOANS transaction
  10. Show confirmation dengan due date
  11. Redirect ke member dashboard
  12. Verify loan muncul di "My Loans"

Expected Result:
  ✅ Search response < 2 detik
  ✅ Filter hasil akurat
  ✅ Detail page load < 1 detik
  ✅ Loan transaction created
  ✅ Stok updated: 3 → 2
  ✅ Member dashboard updated real-time
  ✅ Email notification sent (optional)
  ✅ SMS notification sent (optional)

Integration Checks:
  ✅ OPAC module & Book module integration
  ✅ Authentication & Authorization working
  ✅ Database transaction consistent
  ✅ Event logging working
  ✅ Notification system working

Test Result: [ ] PASS [ ] FAIL
Notes:
Tester: ________________  Date: __________
```

---

# 📄 DOKUMENTASI UAT & BAST

## UAT Checklist

- [ ] All critical test cases passed (PASS status)
- [ ] No blocking bugs identified
- [ ] Performance metrics met (response time < 2s)
- [ ] Security validation passed
- [ ] Database integrity verified
- [ ] Test data cleaned up
- [ ] Test environment stable

## UAT Preparation

### UAT Participants
| Role | Name | Email | Responsibility |
|------|------|-------|-----------------|
| **Kepala Perpustakaan** | TBD | - | Validasi kebutuhan fungsional |
| **Staf Perpustakaan** | 2-3 orang | - | Validasi workflow operasional |
| **Admin IT** | 1 orang | - | Validasi teknis & security |
| **QA Lead** | TBD | - | Moderator & dokumentasi |
| **Project Manager** | TBD | - | Timeline & approval |

### UAT Schedule
```
Week 1: UAT Phase 1
  ├─ Monday: Modul 1-3 testing
  ├─ Tuesday: Modul 1-3 continued
  ├─ Wednesday: Issue logging & clarification
  ├─ Thursday: Defect fix & verification
  └─ Friday: Phase 1 sign-off

Week 2: UAT Phase 2
  ├─ Monday: Modul 4-5 testing
  ├─ Tuesday: Modul 4-5 continued
  ├─ Wednesday: Integration testing
  ├─ Thursday: Issue resolution
  └─ Friday: Phase 2 sign-off

Week 3: Regression & Final
  ├─ Monday-Thursday: Fix verification & regression
  └─ Friday: Final sign-off & BAST preparation

Week 4: BAST Signing
  ├─ Monday: BAST document finalization
  ├─ Tuesday: Review meeting dengan stakeholder
  ├─ Wednesday: Final approval & signing
  ├─ Thursday: Documentation handover
  └─ Friday: Go-live preparation
```

## BAST (Berita Acara Serah Terima) Template

```
═══════════════════════════════════════════════════════════════
           BERITA ACARA SERAH TERIMA (BAST)
        Sistem Informasi Perpustakaan (SIPERPUS)
═══════════════════════════════════════════════════════════════

Tanggal: _______________
Waktu: _______________
Lokasi: _______________

1. PIHAK-PIHAK:
   a) Penyerah:
      - Nama: _______________
      - Jabatan: _______________
      - Tanda Tangan: _______________
   
   b) Penerima:
      - Nama: _______________
      - Jabatan: _______________
      - Tanda Tangan: _______________

2. HASIL PENGUJIAN:
   ✅ Unit Testing: PASS
   ✅ Integration Testing: PASS
   ✅ System Testing: PASS
   ✅ UAT: PASS
   ✅ Security Testing: PASS
   ✅ Performance Testing: PASS

3. METRICS ACHIEVED:
   - Test Coverage: ≥80%
   - Pass Rate: 95%+
   - Critical Bugs: 0
   - Response Time: <2s
   - Security Issues: 0

4. OUTSTANDING ISSUES:
   (Catatan: Semua critical & high issues resolved)
   
   No. | Issue | Severity | Status |
   ----|-------|----------|--------|
   1.  | None  | N/A      | N/A    |

5. TRAINING & DOCUMENTATION:
   ✅ User manual provided
   ✅ Admin guide provided
   ✅ API documentation provided
   ✅ Training session conducted (2x)
   ✅ Staff trained & certified

6. GO-LIVE READINESS:
   ✅ System ready for production
   ✅ Backup & recovery tested
   ✅ Monitoring tools active
   ✅ Support team standby
   ✅ Rollback plan prepared

7. TANDA TANGAN:

   Penyerah/Pengembang:
   _______________________
   Tanggal: _______

   Penerima/Klien:
   _______________________
   Tanggal: _______

   Kepala Perpustakaan:
   _______________________
   Tanggal: _______

═══════════════════════════════════════════════════════════════
```

---

# 📊 TEST METRICS & KPI

| Metric | Target | Tolerance | Status |
|--------|--------|-----------|--------|
| **Test Coverage** | 80%+ | ≥70% | ✅ |
| **Pass Rate** | 95%+ | ≥90% | ✅ |
| **Critical Bugs** | 0 | 0 | ✅ |
| **High Bugs** | ≤5 | ≤10 | ✅ |
| **Response Time** | <2s | <3s | ✅ |
| **Security Issues** | 0 OWASP Top 10 | 0 | ✅ |
| **Uptime** | 99%+ | ≥98% | ✅ |

---

# ⚠️ RISK & MITIGATION

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|-----------|
| Database performance degradation | Medium | High | Implement indexing, performance test early |
| Data loss during testing | Low | High | Daily backups, fresh DB per cycle |
| Scope creep | Medium | Medium | Strict scope control, change process |
| Stakeholder unavailable for UAT | Low | High | Schedule early, get commitments |
| Critical bugs found late | Low | Critical | Continuous testing, early integration |

---

## Dokumen Referensi
- [QA_TEST_PLAN.md](QA_TEST_PLAN.md) - Detailed test plan
- [SRS_SIPERPUS_LENGKAP.md](SRS_SIPERPUS_LENGKAP.md) - Full requirements
- [ERD_MERMAID.md](ERD_MERMAID.md) - Database structure
- [STRUKTUR_DATABASE.md](STRUKTUR_DATABASE.md) - Database details

---

**Persiapan & Eksekusi Testing**  
Dokumen ini adalah panduan komprehensif untuk pelaksanaan QA dan testing seluruh sistem SIPERPUS dengan focus pada modul 1-5, SIT, E2E, dan UAT readiness.

**Last Updated**: 2026-06-30
**Next Review**: Setelah UAT Phase 1 selesai
