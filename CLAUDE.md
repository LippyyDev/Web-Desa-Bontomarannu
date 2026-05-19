# CLAUDE.md — Website Desa Padang Loang

---

## 1. Project Overview

- **Name**        : Website Desa Padang Loang
- **Description** : Official village website for Desa Padang Loang, Kecamatan Ujung Loe, Kabupaten Bulukumba. A full-stack web platform for village administration, public information, and citizen services.
- **Goal**        : Provide a centralized digital platform for the village to publish public information (news, gallery, village profile, inventory), manage official correspondence (surat), handle citizen complaints (pengaduan), and manage village content — all with a role-based access system.
- **Target Users**: Village staff (Admin, Staff/Perangkat Desa) and citizens (User)
- **Version**     : v1.0.0
- **Status**      : Active Development

---

## 2. Tech Stack

- **Language**       : PHP 8.1+
- **Framework**      : CodeIgniter 4 (CI4)
- **Styling**        : Vanilla CSS + Bootstrap (via Views)
- **UI Library**     : Custom HTML/CSS/JS per view
- **Database**       : MySQL (MySQLi driver via CI4)
- **ORM**            : CodeIgniter 4 built-in Query Builder / Model
- **Auth**           : Custom JWT-based session auth (manual, stored in `auth_tokens` table)
- **Email**          : PHPMailer (via SMTP Gmail) + custom async EmailQueue system
- **Document Gen**   : PHPWord (`.docx` generation) + TCPDF (`.pdf` generation)
- **Package Manager**: Composer
- **Deployment**     : Local dev via `php spark serve` or Apache (XAMPP/Laragon). Production: VPS / shared hosting.

---

## 3. Commands

```bash
# Development
php spark serve                  # Run CI4 dev server (default: localhost:8080)
php spark serve --port=8000      # Run on specific port

# Routing & Cache
php spark routes                 # List all registered routes
php spark cache:clear            # Clear application cache

# Database
php spark migrate                # Run pending migrations
php spark migrate:rollback       # Rollback last migration batch
php spark db:seed [SeederName]   # Run a specific database seeder

# Code Generation
php spark make:controller [Name] # Generate a new controller
php spark make:model [Name]      # Generate a new model
php spark make:migration [Name]  # Generate a new migration

# Testing
composer test                    # Run all PHPUnit tests (alias for vendor/bin/phpunit)
vendor/bin/phpunit               # Run PHPUnit directly

# Dependencies
composer install                 # Install all dependencies from composer.lock
composer require [package]       # Add a new Composer package
```

> **Never** use `npm` or `yarn` — this project has no Node.js dependencies.
> Always use `composer` for PHP package management.

---

## 4. Project Structure

**Architecture**: MVC (Model-View-Controller), organized by role/feature within the CI4 convention.

```
Web Desa Bontomarannu/
  app/
    Commands/          # Custom Spark CLI commands
    Config/            # CI4 config files (Routes.php, Filters.php, Database.php, Email.php, etc.)
    Controllers/
      Admin/           # Controllers for Admin role (AccountController, DashboardController, ProfileController)
      Staff/           # Controllers for Staff role (ContentController, LetterController, PdfWordController, etc.)
      User/            # Controllers for User/citizen role (LetterController, PdfWordController, ProfileController, etc.)
      Guest/           # Public-facing controllers (AuthController, LandingController)
      Api/             # Internal API endpoints (EmailQueueController)
    Database/
      Migrations/      # Database migration files
      Seeds/           # Database seed files
    Filters/           # CI4 HTTP Filters (EmailQueuePumpFilter)
    Helpers/           # Custom helper functions
    Language/          # Localization strings
    Libraries/         # Custom library classes (EmailService, EmailQueueProcessor)
    Models/            # CI4 Model classes (one model per database table)
    ThirdParty/        # Third-party integrations
    Views/
      Admin/           # View templates for Admin role
      Staff/           # View templates for Staff role
      User/            # View templates for User role
      Guest/           # Public-facing view templates (landing, auth)
      Components/      # Reusable view partials/components
      errors/          # CI4 error page templates
  public/              # Web root — only this folder is exposed to the browser
    uploads/           # User-uploaded files (profile photos, letter attachments, gallery media, etc.)
  tests/               # PHPUnit test files
  vendor/              # Composer dependencies (do NOT edit)
  writable/            # CI4 writable directory (cache, logs, sessions, temp files)
  .env                 # Local environment configuration (NEVER commit this)
  composer.json        # PHP dependency definitions
  spark               # CI4 CLI entry point
```

**File placement rules:**
- New Controllers → `app/Controllers/[Role]/` matching the role (Admin/Staff/User/Guest/Api)
- New Models → `app/Models/` — one file per table, named `[TableName]Model.php`
- New Views → `app/Views/[Role]/` matching the controller's role
- Reusable view partials → `app/Views/Components/`
- Custom helper functions → `app/Helpers/`
- Custom library classes → `app/Libraries/`
- Config changes → `app/Config/`
- **Do not create new top-level folders without confirmation**

---

## 5. Naming Conventions

```
# Files & Folders
- Controllers   : PascalCase    e.g. LetterController.php, ContentController.php
- Models        : PascalCase    e.g. LetterModel.php, UserProfileModel.php
- Views         : snake_case    e.g. index.php, create_letter.php
- Migrations    : CI4 timestamp format: YYYY-MM-DD-HHMMSS_DescriptionInPascalCase.php
- Helpers       : snake_case    e.g. file_helper.php

# Inside Code (PHP)
- Variables     : camelCase     e.g. $userData, $isVerified
- Constants     : UPPER_SNAKE   e.g. MAX_FILE_SIZE, BASE_URL
- Methods       : camelCase     e.g. getUserById(), generateWord()
- Classes       : PascalCase    e.g. EmailService, LetterController
- Database cols : snake_case    e.g. created_at, user_id, tipe_surat

# Routes
- URL segments  : kebab-case    e.g. /perangkat-desa, /forgot-password, /ubah-password
- Role groups   : lowercase     e.g. /user/..., /staff/..., /admin/...

# Git Branches
- New feature   : feat/[nama-fitur]
- Bug fix       : fix/[nama-bug]
- Hotfix        : hotfix/[nama]
- Refactor      : refactor/[nama]
```

---

## 6. Code Conventions

```
# General Approach
- Follow CI4 conventions and PSR-4 autoloading
- Keep controllers thin — business logic belongs in Models or Libraries
- DRY: extract shared logic into Helpers or Libraries
- Always validate and sanitize input before processing

# PHP Style
- PHP 8.1+ features are available (match expressions, enums, named args, etc.)
- Always use strict comparison (===) instead of loose (==)
- Use try-catch for operations that can throw exceptions
- Never suppress errors with @

# Controller Pattern
- Always extend BaseController or ProtectedController
- ProtectedController handles JWT/session auth checks — use it for all protected routes
- Return JSON responses for API/AJAX endpoints, redirect/view for web routes

# Model Pattern
- Use CI4's built-in Model class with $table, $primaryKey, $allowedFields defined
- Use $useSoftDeletes = true where applicable to preserve data integrity
- Use Query Builder methods; avoid raw SQL unless necessary

# Import / Use Order (PHP)
1. namespace declaration
2. use statements (CI4 classes first, then App\\ classes)
3. class definition

# Error Handling
- Always catch exceptions in try-catch blocks
- Return meaningful error messages (not raw exception messages to the user)
- Log errors with CI4's log_message() for server-side visibility
```

---

## 7. Controller Rules

```
# All Protected Routes
- Must extend ProtectedController (app/Controllers/ProtectedController.php)
- ProtectedController validates JWT token and sets $this->currentUser
- Access $this->currentUser to get the authenticated user's data

# Role-Based Access
- Admin   → app/Controllers/Admin/
- Staff   → app/Controllers/Staff/
- User    → app/Controllers/User/
- Guest   → app/Controllers/Guest/ (no auth required)
- API     → app/Controllers/Api/

# Response Pattern
- Web views  : return view('ViewPath/filename', $data)
- Redirects  : return redirect()->to('/path')->with('message', '...')
- JSON/AJAX  : return $this->response->setJSON(['success' => true, 'data' => ...])

# File Upload Handling
- ALWAYS validate: magic bytes, MIME type, file extension, file size, and content scanning
- Convert profile photos to WebP format after validation
- Store uploads in public/uploads/[category]/ (e.g., public/uploads/profiles/, public/uploads/letters/)
- NEVER store uploads inside app/ directory
```

---

## 8. Styling Rules

```
# Approach
- Views use server-rendered PHP templates (no frontend framework)
- Bootstrap is used for grid/layout; custom CSS for overrides
- Inline styles are acceptable for small dynamic values (e.g., background-image URLs)
- Do not use !important unless overriding third-party styles

# Responsive Design
- Use Bootstrap breakpoints: sm, md, lg, xl
- Mobile-first approach

# Assets
- CSS/JS assets go in public/assets/ or public/css/, public/js/
- Images used as UI assets go in public/images/
- User-uploaded content goes in public/uploads/

# UI Components
- SweetAlert2 is used for global alerts, confirmations, and CI4 flash messages.
- Global SweetAlert helpers are located in `public/assets/js/components/sweetalert.js`.
- Use `showSuccess()`, `showError()`, `showInfo()`, and `showConfirm()` instead of native browser `alert()` or `confirm()`.
```

---

## 8.1 AJAX Pattern (Standard)

Semua endpoint data yang diakses via AJAX **WAJIB** menggunakan pola berikut. Referensi implementasi: `Staff/NotificationController::data()` dan `Staff/ContentController::galleryApi()`.

```
# Route
- Gunakan POST, bukan GET, untuk semua AJAX data endpoint
- Contoh: $routes->post('fitur/api', 'Staff\Controller::method');

# Controller
- Selalu validasi: if (!$this->request->isAJAX()) → return 400
- Baca params via getPost(), bukan getGet()
- Selalu sertakan 'success' => true/false di JSON response
- Guard role sebelum proses data

# View / JavaScript
- Gunakan Fetch API native — JANGAN tambah jQuery hanya untuk AJAX
- Selalu sertakan CSRF token di setiap request:
    const csrfHeaderName = document.querySelector('meta[name="csrf-header"]')?.content || 'X-CSRF-TOKEN';
    const getCsrfHash    = () => document.querySelector(`meta[name="${csrfHeaderName}"]`)?.content || '';
    formData.append(csrfHeaderName, getCsrfHash());
- Selalu sertakan header: { 'X-Requested-With': 'XMLHttpRequest' }
- Gunakan showError() bukan alert(), showConfirm() bukan confirm()
- Gunakan escapeHtml() untuk semua data dari server sebelum di-render ke DOM

# Pola Fetch POST lengkap
    const formData = new URLSearchParams();
    formData.append('page', page);
    formData.append(csrfHeaderName, getCsrfHash());

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => { if (data.success) { ... } })
    .catch(() => showError('Terjadi kesalahan.'))
    .finally(() => { /* hide loading */ });

# Implementasi yang sudah menggunakan pola ini
- Staff Notifikasi   → POST /staff/notifikasi/data
- Staff Gallery      → POST /staff/galeri/api       (limit: 12 fixed)
- Staff Berita       → POST /staff/berita/api       (limit: 12 fixed)
- Staff Pengumuman   → POST /staff/pengumuman/api   (limit: 12 fixed)
- Staff Surat        → POST /staff/surat/api        (limit: dari filter Tampilkan)
- Staff Pengaduan    → POST /staff/pengaduan/api    (limit: dari filter Tampilkan)
- Staff UMKM         → POST /staff/umkm/api         (limit: dari filter Tampilkan, filter status & tanggal)
- Staff UMKM Produk  → POST /staff/umkm/{id}/produk-api  (limit: 8 fixed, infinite scroll di tab edit)
- Staff Pariwisata   → POST /staff/pariwisata/api   (limit: 12 fixed)
- User Surat         → POST /user/surat/api         (limit: dari filter Tampilkan)
- User UMKM          → POST /user/umkm/api          (limit: dari filter Tampilkan, filter status & tanggal)
- User UMKM Produk   → POST /user/umkm/{id}/produk-api   (limit: 8 fixed, infinite scroll di tab edit)
- Admin Akun         → POST /admin/akun/api         (limit: dari filter Tampilkan, filter role & status)
```

---

## 9. Database & Model Rules

```
# Database
- Driver   : MySQLi
- Host     : localhost (configured in .env)
- Database : desa_bontomarannu
- Port     : 3306

# Model Conventions
- Each table has exactly one Model class in app/Models/
- Always define: $table, $primaryKey, $useTimestamps, $allowedFields
- Use CI4 validation rules inside models where applicable

# Current Tables (from Models)
- users                  → UserModel
- user_profiles          → UserProfileModel   (kolom: nama_lengkap, jenis_kelamin ENUM('Laki-laki','Perempuan'), tempat_lahir, tanggal_lahir, agama, pekerjaan, nik, alamat, foto_profil)
- auth_tokens            → AuthTokenModel
- letters                → LetterModel
- letter_attachments     → LetterAttachmentModel
- letter_replies         → LetterReplyModel
- reply_attachments      → ReplyAttachmentModel
- desa_profiles          → DesaProfileModel
- geografi_desa          → GeografiDesaModel
- perangkat_desa         → PerangkatDesaModel  (Kepala Desa: TIDAK BISA DIHAPUS — tombol hapus disembunyikan di UI & diblok di controller)
- gallery_albums         → GalleryAlbumModel
- gallery_media          → GalleryMediaModel
- news                   → NewsModel
- news_media             → NewsMediaModel
- inventaris_desa        → InventarisDesaModel
- pengumuman             → PengumumanModel
- pengaduan              → PengaduanModel
- notifications          → NotificationModel
- email_queue            → EmailQueueModel
- umkm                   → UmkmModel          (+ kolom foto_toko: VARCHAR(255) nullable)
- umkm_ecommerce         → UmkmEcommerceModel
- umkm_produk            → UmkmProdukModel     (kolom: nama_produk, harga, deskripsi)
- umkm_produk_gambar     → UmkmProdukGambarModel
- pariwisata             → PariwisataModel
- pariwisata_gambar      → PariwisataGambarModel

# Migrations
- Always create a new migration file; do NOT manually alter tables in production
- Run migrations with: php spark migrate
- Rollback with: php spark migrate:rollback
- NEVER run destructive queries directly against the production database
```

---

## 10. Authentication & Session Rules

```
# Auth System
- Custom JWT-based authentication (no third-party auth library)
- Tokens stored in the auth_tokens table
- ProtectedController validates the token on every protected request
- Session data is used alongside JWT for convenience

# Role System
- Three roles: admin, staff, user
- Role is stored in the users table
- Routes are separated by role prefix (/admin/*, /staff/*, /user/*)
- Accessing the wrong role prefix is blocked by ProtectedController

# Login Flow
1. POST /login → Guest\AuthController::doLogin
2. Validate credentials
3. Issue JWT token → store in auth_tokens
4. Set session → redirect based on role

# Registration Flow
1. POST /register → Guest\AuthController::doRegister
2. Create user (status: pending)
3. Send OTP/verification link via email (EmailService / EmailQueue)
4. POST /verify → activate account
```

---

## 11. Email System Rules

```
# Architecture
- Async email queue system to avoid blocking HTTP responses
- Emails are inserted into the email_queue table first
- EmailQueueProcessor processes the queue asynchronously
- EmailQueuePumpFilter triggers queue processing on requests (or via /api/email-queue/process endpoint)

# EmailService (app/Libraries/EmailService.php)
- Use EmailService to queue emails; NEVER send emails directly inline in controllers
- Method: $emailService->queue($to, $subject, $body, $data)

# SMTP Config (from .env)
- Protocol : smtp (Gmail)
- Host     : smtp.gmail.com
- Port     : 587 (TLS)
- Credentials are set in .env — NEVER hardcode them in source files

# Transactional Emails Used
- Account verification OTP
- Password reset OTP
- Letter status notification (to user when staff replies)
```

---

## 12. Document Generation Rules

```
# Libraries Used
- PHPWord  (phpoffice/phpword ^1.4) → generates .docx (Word) files
- TCPDF    (tecnickcom/tcpdf ^6.10) → generates .pdf files

# Controllers
- User\PdfWordController  → User can generate their own letter as Word/PDF
- Staff\PdfWordController → Staff generates documents from letter data + official templates

# Templates
- Official letter templates use the kop surat (letterhead) of:
  - Pemerintah Desa Bonto Marannu, Kecamatan Ulu Ere, Kabupaten Bantaeng
- Templates are generated programmatically (not from static .docx files) using PHPWord

# Penandatangan (Signature Section)
- Nama dan Jabatan penandatangan diambil OTOMATIS dari tabel `perangkat_desa`
  dimana `LOWER(jabatan) = 'kepala desa'` (case-insensitive)
- Alamat penandatangan selalu: "Desa Bonto Marannu Kec.Ulu Ere Kab. Bantaeng" (hardcoded)
- Implementasi: `Staff\PdfWordController::getKepalaDesa()` private method

# Jenis Kelamin di Surat
- Field Jenis Kelamin pada surat diambil dari `user_profiles.jenis_kelamin` (ENUM: Laki-laki/Perempuan)
- Jika belum diisi di profil, tampil sebagai 'Belum Diisi'

# File Handling
- Generated files are streamed directly to the browser (download prompt)
- Do NOT store generated Word/PDF files permanently on disk unless explicitly required
```

---

## 13. Security Rules

```
# File Upload Security (Critical)
- MANDATORY 8-layer validation for every file upload:
  1. File exists and is uploaded via HTTP
  2. File size limit enforced
  3. Extension whitelist check
  4. MIME type check (finfo / getimagesize)
  5. Magic byte verification (read raw file header bytes)
  6. Content scanning (detect embedded scripts, PHP tags, etc.)
  7. Path traversal protection (validate filenames)
  8. WebP conversion for image uploads (profile photos)
- This security layer has already been implemented for profile photo uploads across User, Staff, and Admin controllers
- Apply the same pattern when adding any new file upload endpoint

# General Security
- Never expose raw exception/error messages to users in production
- Never hardcode secrets, credentials, or API keys in source files
- Always use parameterized queries (CI4 Query Builder handles this)
- Validate and sanitize ALL user input before processing
- CSRF protection should be enabled for all state-changing POST requests
- Never commit .env to version control
```

---

## 13.1 File Upload Validation Helper

Validasi upload file menggunakan **dua komponen reusable** — satu untuk backend (PHP), satu untuk frontend (JS). Keduanya harus selalu digunakan bersama. Ada dua jenis fungsi validasi sesuai konteks penggunaannya.

### Backend (PHP)

```
# File
- app/Helpers/upload_helper.php

# Fungsi 1 — Gambar (Galeri, Foto Profil, Thumbnail)
- validate_image_upload(UploadedFile $file, int $maxBytes = 1048576): ?string
  → Return null jika valid
  → Return string pesan error jika tidak valid
  → Aturan: ext jpg/jpeg/png, MIME image/jpeg|image/png, ≤1MB, magic bytes

# Fungsi 2 — Lampiran Surat (PDF, Word, Gambar)
- validate_letter_attachment(UploadedFile $file, int $maxBytes = 1048576): ?string
  → Return null jika valid
  → Return string pesan error jika tidak valid
  → Aturan: ext pdf/doc/docx/jpg/jpeg/png, MIME sesuai ekstensi, ≤1MB
  → Tidak ada magic bytes (dokumen lebih kompleks), MIME server-side sudah cukup

# Load di Controller
- Tambahkan di method yang relevan: helper('upload');

# Contoh — validate_image_upload
    $thumb = $this->request->getFile('thumbnail');
    if ($thumb && $thumb->isValid()) {
        $error = validate_image_upload($thumb);
        if ($error !== null) {
            return redirect()->back()->with('error', 'Thumbnail: ' . $error);
        }
    }

# Contoh — validate_letter_attachment (loop multi-file)
    helper('upload');
    foreach ($files as $file) {
        if (!$file->isValid() || $file->hasMoved()) continue;
        $error = validate_letter_attachment($file);
        if ($error !== null) {
            // skip file ini, catat ke $errors[]
            $errors[] = $file->getClientName() . ': ' . $error;
            continue;
        }
        // proses upload ...
    }
    if (!empty($errors)) {
        session()->setFlashdata('attachment_errors', $errors);
    }
```

### Frontend (JavaScript)

```
# File
- public/assets/js/components/upload_validator.js
- Di-load global via Staff/layout.php DAN User/layout.php

# Fungsi Global — Gambar
- validateImageFile(file: File): boolean
  → Validasi satu file gambar (ext, MIME, ukuran)

- initMediaUploader(inputId: string, previewId: string): void
  → Setup multi-file picker gambar akumulatif dengan preview visual 16:9
  → File invalid di-skip, tiap preview punya tombol hapus

# Fungsi Global — Lampiran Surat
- validateLetterAttachment(file: File): { ok: boolean, error: string|null }
  → Validasi satu file lampiran surat (ext, MIME, ukuran)
  → Ekstensi: pdf, doc, docx, jpg, jpeg, png | Maks: 1MB

- initLetterAttachmentUploader(inputId: string, previewId: string): void
  → Setup multi-file picker lampiran surat dengan validasi frontend
  → Tampilkan list file (ikon tipe + nama dipotong) + tombol hapus per file
  → File invalid di-skip dengan pesan error via showError()
  → Cek duplikat otomatis

# Fungsi Inline (per view) — Link Video YouTube
- isValidYoutubeUrl(url: string): boolean
  → Validasi satu URL YouTube (youtube.com/watch, youtu.be, /shorts, /embed)
  → Dideklarasikan langsung di masing-masing view (bukan file global)

- applyYoutubeValidation(input: HTMLInputElement): boolean
  → Terapkan Bootstrap is-valid/is-invalid + tampilkan pesan error di .yt-feedback
  → Dipanggil on blur, on input (jika sudah pernah divalidasi), dan on submit

# Contoh Penggunaan (di view)
    // Uploader gambar (galeri)
    initMediaUploader('mediaInput', 'mediaPreview');

    // Uploader lampiran surat
    initLetterAttachmentUploader('letterAttachments', 'attachmentPreviewList');

# View yang sudah menggunakan
- Staff/gallery/create.php     → initMediaUploader('mediaInput', 'mediaPreview') + isValidYoutubeUrl()
- Staff/gallery/edit.php       → initMediaUploader('mediaInput', 'mediaPreview') + isValidYoutubeUrl()
- Staff/news/create.php        → initMediaUploader('mediaInput', 'mediaPreview') + isValidYoutubeUrl()
- Staff/news/edit.php          → initMediaUploader('mediaInput', 'mediaPreview') + isValidYoutubeUrl()
- User/letters/form.php        → initLetterAttachmentUploader('letterAttachments', 'attachmentPreviewList')
- Staff/letters/detail.php     → initLetterAttachmentUploader('replyAttachments', 'attachmentPreviewList')
```

### Controller yang sudah menggunakan upload_helper.php

```
# validate_image_upload
- Staff\ContentController  → storeGallery(), updateGallery()
- Staff\ContentController  → storeNews(), updateNews()
- Staff\ContentController  → storePengumuman(), updatePengumuman()

# validate_letter_attachment
- User\LetterController    → handleAttachments()      (lampiran surat baru dari user)
- Staff\LetterController   → handleReplyAttachments() (lampiran balasan dari staff)

# validate_youtube_url
- Staff\ContentController  → saveGalleryMedia()  (backend gate: skip jika bukan YouTube)
- Staff\ContentController  → saveNewsMedia()     (backend gate: skip jika bukan YouTube)
```

### Shared Helper: `uploadToWebp()` di ProtectedController

Method `protected function uploadToWebp(UploadedFile $file, string $subPath, int $quality = 85): ?string`
tersedia di **semua controller** (via inheritance dari `ProtectedController`).
Melakukan: upload sementara → konversi WebP → hapus file temp → return path relatif.
Return `null` jika konversi gagal.

```php
// Contoh penggunaan
$path = $this->uploadToWebp($file, 'uploads/pengumuman'); // → 'uploads/pengumuman/abc.webp'
if ($path === null) {
    return redirect()->back()->with('error', 'Gagal memproses gambar.');
}
$data['thumbnail'] = $path;
```

---

## 13.2 Security Headers (HTTP Response Headers)

Semua HTTP security headers diinjeksikan secara otomatis via CI4 after-filter ke **setiap response**.

```
# Filter
- File   : app/Filters/SecurityHeadersFilter.php
- Aktif  : app/Config/Filters.php → $globals['after'] → 'securityheaders'
- Scope  : Berlaku untuk SEMUA route (global after-filter)

# Headers yang Diterapkan
- X-Frame-Options          : SAMEORIGIN
- X-Content-Type-Options   : nosniff
- Referrer-Policy          : strict-origin-when-cross-origin
- Content-Security-Policy  : (lihat daftar allow-list di bawah)
- Permissions-Policy       : semua sensor/API browser sensitif dinonaktifkan
- Strict-Transport-Security: max-age=31536000; includeSubDomains (hanya aktif jika HTTPS)
```

### ⚠️ WAJIB: Update CSP saat menambah Library / CDN / API Eksternal

**SETIAP KALI** kamu menambahkan library JS/CSS baru, CDN baru, atau memanggil API eksternal dari frontend (fetch/XHR), kamu **HARUS** memperbarui direktif CSP yang relevan di `SecurityHeadersFilter.php`.

Jika tidak diupdate → browser akan memblokir resource tersebut dengan error CSP di console.

```
# Direktif CSP yang harus diupdate sesuai jenis resource baru:

Jenis resource baru         Direktif CSP yang diupdate
--------------------------  ----------------------------
Script JS dari CDN          script-src
CSS dari CDN                style-src
Font dari CDN / Google      font-src
Gambar dari domain lain     img-src
Fetch/XHR ke API eksternal  connect-src
Embed iframe/map eksternal  frame-src
```

### Daftar Domain yang Sudah Di-allow (CSP saat ini)

```
# script-src
- 'self'
- 'unsafe-inline'          (dibutuhkan untuk script inline di view)
- https://cdn.jsdelivr.net (Bootstrap JS, ApexCharts, SweetAlert2, **Quill Editor**)

# style-src
- 'self'
- 'unsafe-inline'          (dibutuhkan untuk inline style di view)
- https://cdn.jsdelivr.net (Bootstrap CSS, Bootstrap Icons, **Quill Editor CSS**)
- https://fonts.googleapis.com (Google Fonts stylesheet)

# font-src
- 'self'
- https://cdn.jsdelivr.net (Bootstrap Icons font files)
- https://fonts.gstatic.com (Google Fonts files)

# img-src
- 'self'
- data:                    (Base64 / data URI untuk foto profil WebP)
- blob:                    (Blob URL)

# connect-src  ← update ini saat menambah fetch/XHR ke domain luar
- 'self'
- https://cdn.jsdelivr.net (Bootstrap source maps - devtools)
- https://api.open-meteo.com (Weather API untuk widget cuaca di dashboard)

# frame-src
- 'self'
- https://www.openstreetmap.org (Embed peta desa)
- https://maps.google.com       (Embed Google Maps)
- https://www.youtube.com       (Embed video YouTube di galeri & berita)

# object-src
- 'none'  (Flash & plugin lama diblokir total)
```

### Contoh: Cara Menambah Domain Baru ke CSP

```php
// Di app/Filters/SecurityHeadersFilter.php, metode after():

// Sebelum (hanya self):
"connect-src 'self'",

// Sesudah (tambah API baru):
"connect-src 'self' https://cdn.jsdelivr.net https://api.open-meteo.com https://api.domainbaru.com",
```

---

## 14. Git Rules

Commit after every meaningful change. This ensures you can always compare before/after and rollback if needed.

```
# Commit Message Format
feat     : [description of new feature]
fix      : [description of bug fixed]
refactor : [description of refactor change]
style    : [styling or formatting changes]
docs     : [documentation changes]
test     : [test additions or changes]
chore    : [config or tooling changes]
security : [security hardening changes]

# Examples
feat: add gallery album CRUD for staff
fix: resolve letter attachment false-positive content scan rejection
security: harden profile photo upload with 8-layer validation
refactor: extract email queue processing into EmailQueueProcessor library

# Rules
- NEVER commit .env or any file containing secrets/credentials
- One commit per specific, focused change
- Do not mix unrelated changes in a single commit
```

---

## 15. Features

```
# Completed & Working — DO NOT change without explicit instruction
- [x] Public landing page (hero, about, navigation, map, org structure, gallery/news preview)
- [x] Public profile page (village vision/mission, population stats, contact, location)
- [x] Public gallery page (album cards with photo/video)
- [x] Public news page (news cards + detail)
- [x] Public geographic info page (geografis)
- [x] Public village apparatus page (perangkat desa)
- [x] Public village inventory page (inventaris)
- [x] Public announcements page (pengumuman)
- [x] Public complaint submission page (pengaduan)
- [x] Authentication (login, register, email OTP verification, forgot/reset password)
- [x] JWT-based auth with role redirect (admin/staff/user)
- [x] User dashboard (letter summary, notifications)
- [x] User profile management (photo, personal data incl. jenis_kelamin, password change)
- [x] User letter system CRUD (create, view, edit, delete, send to staff)
- [x] User letter export (Word .docx + PDF via official letterhead template)
- [x] User letter status tracking (sent, read, replied)
- [x] User notifications (letter status, staff reply, UMKM approval)
- [x] User UMKM management (submit toko + foto toko, edit info/produk langsung jika approved, resubmit setelah ditolak)
- [x] Staff dashboard (incoming letter summary)
- [x] Staff profile management (incl. jenis_kelamin dropdown)
- [x] Staff letter inbox (view, reply with attachment, delete)
- [x] Staff letter Word generation from letter data (penandatangan & jenis kelamin dari DB)
- [x] Staff village profile management (visi, misi, population stats, contact, location)
- [x] Staff geographic data management
- [x] Staff gallery CRUD (album + multi-photo/video) — AJAX listing via POST /staff/galeri/api
- [x] Staff news CRUD (multi-photo)
- [x] Staff village apparatus CRUD (perangkat desa) — Kepala Desa tidak bisa dihapus (UI + controller)
- [x] Staff inventory CRUD (inventaris desa)
- [x] Staff announcements CRUD (pengumuman)
- [x] Staff complaint management (pengaduan)
- [x] Staff notifications (incoming letters from users)
- [x] Staff UMKM management (CRUD + approve/reject user submissions + edit produk existing + foto toko)
- [x] Staff Pariwisata management (CRUD + gallery photos)
- [x] Admin dashboard
- [x] Admin account management CRUD (admin/staff/user accounts)
- [x] Admin profile management
- [x] Async email queue system (EmailQueue + EmailService + EmailQueueProcessor)
- [x] File upload security hardening (8-layer validation, WebP conversion for profile photos)
- [x] Reusable image upload validation helper (app/Helpers/upload_helper.php — validate_image_upload())
- [x] Gallery photo upload validation: max 1MB, JPG/JPEG/PNG only, frontend + backend
- [x] Jenis Kelamin field di profil User & Staff (ENUM: Laki-laki/Perempuan, tersimpan di DB)
- [x] Word export surat: jenis_kelamin dari DB pengaju, penandatangan dari Kepala Desa (perangkat_desa)
- [x] Kepala Desa lock: tidak bisa dihapus dari kelola perangkat desa (UI & controller)

# In Progress — DO NOT modify without confirmation
- [ ] (none currently identified — confirm with user before adding here)

# Planned / Not Started
- [ ] (confirm with user for any new features to add here)
```

---

## 16. Testing

```
# Framework
- PHPUnit ^10.5 (via composer require-dev)
- CI4's built-in test support (CIUnitTestCase)
- Config: phpunit.xml.dist

# Run Tests
composer test
# or
vendor/bin/phpunit

# Test Location
- tests/ directory at project root
- tests/_support/ for test support files

# What to Test
- Model query logic and validation rules
- Controller responses (status codes, redirect paths)
- File upload validation logic (security-critical)
- Email queue processing logic

# What NOT to Test
- Third-party library internals (PHPWord, TCPDF, PHPMailer)
- Simple view rendering with no logic
- Database migrations (test by running them)
```

---

## 17. Do Not

If a prompt or instruction is ambiguous, **ASK FIRST** before writing code.
Do not assume and proceed without confirmation.

```
# Structure & Files
- Do NOT create new folders without confirmation
- Do NOT delete files without confirmation
- Do NOT move files without confirmation
- Do NOT rename controllers or models without updating all references

# Code
- Do NOT hardcode database credentials, SMTP passwords, or any secret values in source files
- Do NOT commit .env or any file containing secrets
- Do NOT install new Composer packages without confirmation
- Do NOT remove or modify existing working features without a clear instruction
- Do NOT bypass or weaken the file upload security validation layers

# Patterns to Avoid
- Do NOT use raw SQL when CI4 Query Builder can handle it
- Do NOT send emails synchronously inside a controller — always use EmailService to queue
- Do NOT store user-uploaded files inside the app/ directory (use public/uploads/)
- Do NOT expose detailed server errors or stack traces to end users in production

# Database
- Do NOT run destructive SQL (DROP, TRUNCATE, DELETE without WHERE) on production
- Do NOT create or alter tables manually — always use migrations
- Do NOT expose database credentials to the client side

# Security
- Do NOT weaken or bypass the 8-layer file upload validation
- Do NOT expose API keys, SMTP passwords, or JWT secrets to the client
- Do NOT skip input validation on any form submission
- Do NOT allow unauthenticated access to routes under /user/, /staff/, or /admin/
```

---

## 18. Environment Variables

```
# Setup
- Copy env (the example file) to .env for local development
- NEVER commit .env to the repository (it is in .gitignore)
- All sensitive values MUST come from .env — not hardcoded in source

# Application
CI_ENVIRONMENT = development          # Set to 'production' on live server
app.baseURL    = 'https://...'        # Full base URL including trailing slash

# Database
database.default.hostname = localhost
database.default.database = desa_bontomarannu
database.default.username = [db_user]
database.default.password = [db_pass]   # SERVER ONLY — never expose to client
database.default.DBDriver = MySQLi
database.default.port     = 3306

# Email / SMTP (SERVER ONLY — never expose to client)
email.fromEmail   = [sender_email]
email.fromName    = '[Sender Display Name]'
email.protocol    = smtp
email.SMTPHost    = smtp.gmail.com
email.SMTPUser    = [gmail_address]
email.SMTPPass    = '[gmail_app_password]'  # Use Gmail App Password, not account password
email.SMTPPort    = 587
email.SMTPTimeout = 5
email.SMTPCrypto  = tls
```
