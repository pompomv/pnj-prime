-- ============================================================
--  PNJ PRIME - Sistem E-Commerce & Booking Jasa (Hilirisasi Riset)
--  Politeknik Negeri Jakarta
--  Database Schema DDL - PostgreSQL
--  Versi: 1.0
--  Dibuat: 2026-11-02
-- ============================================================
-- CATATAN EKSEKUSI:
--   Pastikan ekstensi 'pgcrypto' aktif untuk gen_random_uuid().
--   Jalankan: CREATE EXTENSION IF NOT EXISTS "pgcrypto";
--   Atau gunakan: CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
-- ============================================================

-- Aktifkan ekstensi UUID
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================================
-- LANGKAH 1: BUAT SEMUA TIPE ENUM TERLEBIH DAHULU
-- ============================================================

-- ENUM Role pengguna sistem
CREATE TYPE role_enum AS ENUM (
    'dosen',
    'mahasiswa',
    'p3m',
    'rtpu',
    'pimpinan',
    'eksternal'
);

-- ENUM Status verifikasi akun pengguna eksternal
CREATE TYPE status_verifikasi_enum AS ENUM (
    'menunggu',    -- Baru mendaftar, menunggu review RTPU
    'disetujui',   -- Akun telah diverifikasi oleh RTPU
    'ditolak'      -- Akun ditolak oleh RTPU
);

-- ENUM Tipe item yang diajukan (produk fisik atau jasa)
CREATE TYPE tipe_item_enum AS ENUM (
    'produk_fisik',  -- Produk yang dapat dibeli/dikirim
    'jasa'           -- Jasa pengujian atau layanan (berbasis jadwal)
);

-- ENUM Status kurasi inovasi oleh P3M / RTPU
CREATE TYPE status_kurasi_enum AS ENUM (
    'menunggu_validasi', -- Baru diajukan, menunggu review P3M
    'revisi',            -- P3M meminta revisi dari pengaju
    'ditolak',           -- Inovasi ditolak oleh P3M/RTPU
    'lolos_p3m'          -- Inovasi disetujui P3M, siap penetapan harga
);

-- ENUM Status pembayaran transaksi
CREATE TYPE status_pembayaran_enum AS ENUM (
    'menunggu_pembayaran', -- Order dibuat, menunggu konfirmasi bayar
    'lunas',               -- Pembayaran telah dikonfirmasi
    'gagal',               -- Pembayaran gagal / expired
    'dibatalkan'           -- Order dibatalkan oleh pembeli atau sistem
);

-- ENUM Status penjadwalan jasa
CREATE TYPE status_jadwal_enum AS ENUM (
    'menunggu_jadwal', -- Transaksi lunas, menunggu RTPU membuat jadwal
    'terjadwal',       -- Jadwal telah ditetapkan oleh RTPU
    'selesai'          -- Jasa telah selesai dilaksanakan
);

-- ENUM Tipe aksi untuk audit log
CREATE TYPE aksi_audit_enum AS ENUM (
    'INSERT',
    'UPDATE',
    'DELETE',
    'APPROVE',
    'REJECT',
    'REVISI',
    'SET_HARGA',
    'PUBLISH',
    'UNPUBLISH',
    'VERIFIKASI_AKUN'
);


-- ============================================================
-- TABEL 1: USERS (Manajemen Akun & Pengguna)
-- Menyimpan semua pengguna sistem, baik Internal maupun Eksternal.
-- Internal login via SSO; Eksternal mendaftar manual.
-- ============================================================

CREATE TABLE users (
    -- Identitas Utama
    id                  UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name                VARCHAR(255)        NOT NULL,
    email               VARCHAR(255)        NOT NULL UNIQUE,
    role                role_enum           NOT NULL,

    -- Autentikasi
    -- password_hash: NULL untuk pengguna internal (login via SSO)
    password_hash       VARCHAR(255)        NULL,
    -- sso_id: Identifier unik dari provider SSO (misal: NIM/NIP dari LDAP)
    sso_id              VARCHAR(100)        NULL UNIQUE,

    -- Data khusus Pengguna EKSTERNAL
    -- Field berikut HANYA diisi jika role = 'eksternal'
    nik                 CHAR(16)            NULL,       -- Nomor Induk Kependudukan (16 digit)
    npwp                VARCHAR(20)         NULL,       -- Nomor Pokok Wajib Pajak
    url_foto_ktp        TEXT                NULL,       -- URL ke storage (e.g., S3/MinIO)
    url_foto_npwp       TEXT                NULL,       -- URL ke storage (e.g., S3/MinIO)
    nomor_telepon       VARCHAR(20)         NULL,
    alamat              TEXT                NULL,

    -- Status Verifikasi (relevan untuk pengguna Eksternal)
    status_verifikasi   status_verifikasi_enum NOT NULL DEFAULT 'menunggu',
    -- Referensi ke RTPU yang melakukan verifikasi
    diverifikasi_oleh   UUID                NULL,
    diverifikasi_pada   TIMESTAMP WITH TIME ZONE NULL,
    catatan_verifikasi  TEXT                NULL,       -- Alasan ditolak / catatan RTPU

    -- Informasi Akun
    is_active           BOOLEAN             NOT NULL DEFAULT TRUE,
    email_verified_at   TIMESTAMP WITH TIME ZONE NULL,
    last_login_at       TIMESTAMP WITH TIME ZONE NULL,

    -- Audit Timestamps
    created_at          TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    -- Constraints
    CONSTRAINT chk_nik_format CHECK (nik IS NULL OR LENGTH(nik) = 16),
    CONSTRAINT chk_eksternal_fields CHECK (
        role != 'eksternal' OR (nik IS NOT NULL AND url_foto_ktp IS NOT NULL)
    ),
    CONSTRAINT chk_internal_sso CHECK (
        role = 'eksternal' OR sso_id IS NOT NULL
    )
);

-- Foreign Key self-referencing untuk verifikasi
ALTER TABLE users
    ADD CONSTRAINT fk_users_diverifikasi_oleh
    FOREIGN KEY (diverifikasi_oleh) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Index untuk pencarian yang sering dilakukan
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_status_verifikasi ON users(status_verifikasi);
CREATE INDEX idx_users_sso_id ON users(sso_id) WHERE sso_id IS NOT NULL;

COMMENT ON TABLE users IS 'Tabel utama pengguna sistem PNJ PRIME. Menampung Sivitas Akademika (login SSO) dan Pengguna Eksternal (registrasi manual dengan verifikasi RTPU).';
COMMENT ON COLUMN users.sso_id IS 'Identifier dari sistem SSO kampus (LDAP/ActiveDirectory). Diisi untuk role internal, NULL untuk eksternal.';
COMMENT ON COLUMN users.status_verifikasi IS 'Status verifikasi akun eksternal. Default menunggu saat pertama mendaftar.';


-- ============================================================
-- TABEL 2: INOVASI (Pengajuan Produk & Jasa)
-- Mahasiswa/Dosen mengajukan inovasi berupa produk fisik atau jasa.
-- Melalui alur kurasi: P3M → Pimpinan (penetapan harga) → RTPU (publikasi).
-- ============================================================

CREATE TABLE inovasi (
    id                      UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    -- Pengaju Inovasi (harus Dosen atau Mahasiswa)
    pengaju_id              UUID                NOT NULL,

    -- Informasi Inovasi
    judul                   VARCHAR(500)        NOT NULL,
    slug                    VARCHAR(550)        NULL UNIQUE, -- URL-friendly version dari judul
    deskripsi               TEXT                NOT NULL,
    tipe_item               tipe_item_enum      NOT NULL,

    -- Dokumen & Media
    url_dokumen_pendukung   TEXT                NULL,       -- URL ke file proposal/laporan (PDF)
    url_foto_prototipe      TEXT                NULL,       -- URL ke gambar utama prototipe

    -- === ALUR KURASI P3M ===
    status_kurasi           status_kurasi_enum  NOT NULL DEFAULT 'menunggu_validasi',
    -- P3M yang melakukan kurasi
    dikurasi_oleh           UUID                NULL,
    dikurasi_pada           TIMESTAMP WITH TIME ZONE NULL,
    catatan_revisi          TEXT                NULL,       -- Catatan/instruksi revisi dari P3M atau RTPU

    -- === PENETAPAN HARGA oleh PIMPINAN ===
    -- Diisi setelah status_kurasi = 'lolos_p3m'
    hpp                     NUMERIC(15, 2)      NULL,       -- Harga Pokok Penjualan
    harga_jual              NUMERIC(15, 2)      NULL,       -- Harga Pasar / Harga Jual
    -- Pimpinan yang menetapkan harga
    harga_ditetapkan_oleh   UUID                NULL,
    harga_ditetapkan_pada   TIMESTAMP WITH TIME ZONE NULL,

    -- === PENGELOLAAN PUBLIKASI oleh RTPU ===
    -- Diisi setelah harga ditetapkan
    is_published            BOOLEAN             NOT NULL DEFAULT FALSE,
    deskripsi_promosi       TEXT                NULL,       -- Deskripsi marketing yang dibuat RTPU
    -- RTPU yang mengelola publikasi
    dipublikasi_oleh        UUID                NULL,
    dipublikasi_pada        TIMESTAMP WITH TIME ZONE NULL,

    -- Stok (untuk produk_fisik; NULL untuk jasa)
    stok                    INTEGER             NULL DEFAULT 0,
    -- Satuan item (e.g., 'pcs', 'unit', 'sesi', 'jam')
    satuan                  VARCHAR(50)         NULL DEFAULT 'pcs',

    -- Audit Timestamps
    created_at              TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    updated_at              TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    -- Constraints
    CONSTRAINT chk_harga_positif CHECK (
        (hpp IS NULL OR hpp >= 0) AND (harga_jual IS NULL OR harga_jual >= 0)
    ),
    CONSTRAINT chk_harga_jual_gte_hpp CHECK (
        harga_jual IS NULL OR hpp IS NULL OR harga_jual >= hpp
    ),
    CONSTRAINT chk_stok_non_negatif CHECK (stok IS NULL OR stok >= 0),
    CONSTRAINT chk_stok_untuk_produk CHECK (
        tipe_item != 'produk_fisik' OR stok IS NOT NULL
    )
);

-- Foreign Keys
ALTER TABLE inovasi
    ADD CONSTRAINT fk_inovasi_pengaju
    FOREIGN KEY (pengaju_id) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE inovasi
    ADD CONSTRAINT fk_inovasi_dikurasi_oleh
    FOREIGN KEY (dikurasi_oleh) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE inovasi
    ADD CONSTRAINT fk_inovasi_harga_ditetapkan_oleh
    FOREIGN KEY (harga_ditetapkan_oleh) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE inovasi
    ADD CONSTRAINT fk_inovasi_dipublikasi_oleh
    FOREIGN KEY (dipublikasi_oleh) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Index
CREATE INDEX idx_inovasi_pengaju_id ON inovasi(pengaju_id);
CREATE INDEX idx_inovasi_status_kurasi ON inovasi(status_kurasi);
CREATE INDEX idx_inovasi_tipe_item ON inovasi(tipe_item);
CREATE INDEX idx_inovasi_is_published ON inovasi(is_published);
CREATE INDEX idx_inovasi_published_tipe ON inovasi(is_published, tipe_item) WHERE is_published = TRUE;

COMMENT ON TABLE inovasi IS 'Tabel produk dan jasa yang diajukan oleh Sivitas Akademika (Dosen/Mahasiswa). Melewati alur kurasi P3M, penetapan harga Pimpinan, dan publikasi RTPU.';
COMMENT ON COLUMN inovasi.hpp IS 'Harga Pokok Penjualan - ditetapkan oleh Pimpinan setelah inovasi lolos kurasi P3M.';
COMMENT ON COLUMN inovasi.harga_jual IS 'Harga Pasar / Harga Jual ke konsumen eksternal - ditetapkan oleh Pimpinan.';
COMMENT ON COLUMN inovasi.is_published IS 'Status publikasi di marketplace. Dikelola oleh RTPU. FALSE = draft/tidak tampil di publik.';


-- ============================================================
-- TABEL 3: MEDIA_INOVASI (Galeri Foto Inovasi - Opsional Tambahan)
-- Menyimpan multiple foto untuk satu inovasi.
-- ============================================================

CREATE TABLE media_inovasi (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    inovasi_id  UUID    NOT NULL,
    url_media   TEXT    NOT NULL,
    tipe_media  VARCHAR(50) NOT NULL DEFAULT 'foto',  -- 'foto', 'video', 'dokumen'
    keterangan  VARCHAR(255) NULL,
    urutan      SMALLINT NOT NULL DEFAULT 1,           -- Urutan tampil di galeri
    created_at  TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    CONSTRAINT chk_urutan_positif CHECK (urutan > 0)
);

ALTER TABLE media_inovasi
    ADD CONSTRAINT fk_media_inovasi_inovasi
    FOREIGN KEY (inovasi_id) REFERENCES inovasi(id)
    ON DELETE CASCADE ON UPDATE CASCADE;

CREATE INDEX idx_media_inovasi_inovasi_id ON media_inovasi(inovasi_id);

COMMENT ON TABLE media_inovasi IS 'Galeri media (foto/dokumen) tambahan untuk setiap inovasi, mendukung multiple gambar.';


-- ============================================================
-- TABEL 4: TRANSAKSI / ORDERS
-- Header transaksi pembelian produk/jasa oleh pengguna Eksternal.
-- Pembayaran terpusat ke satu rekening BLU kampus.
-- ============================================================

CREATE TABLE transaksi (
    id                  UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    -- Pembeli (harus pengguna dengan role 'eksternal')
    pembeli_id          UUID                NOT NULL,

    -- Nomor order yang ditampilkan ke pengguna (human-readable)
    nomor_order         VARCHAR(50)         NOT NULL UNIQUE, -- e.g., 'ORD-20261102-0001'

    -- Ringkasan Harga
    subtotal            NUMERIC(15, 2)      NOT NULL DEFAULT 0,
    biaya_admin         NUMERIC(15, 2)      NOT NULL DEFAULT 0,
    total_harga         NUMERIC(15, 2)      NOT NULL,           -- subtotal + biaya_admin

    -- Informasi Pembayaran
    metode_pembayaran   VARCHAR(100)        NULL,   -- e.g., 'Transfer Bank BLU', 'Virtual Account'
    status_pembayaran   status_pembayaran_enum NOT NULL DEFAULT 'menunggu_pembayaran',

    -- Rekening BLU Tujuan Pembayaran
    -- Disimpan di tabel terpisah (rekening_blu), direferensikan di sini
    rekening_blu_id     UUID                NULL,

    -- Bukti & Konfirmasi Pembayaran
    url_bukti_pembayaran TEXT               NULL,   -- Upload bukti transfer
    dikonfirmasi_oleh   UUID                NULL,   -- RTPU/Admin yang konfirmasi
    dikonfirmasi_pada   TIMESTAMP WITH TIME ZONE NULL,

    -- Catatan
    catatan_pembeli     TEXT                NULL,
    catatan_admin       TEXT                NULL,

    -- Batas waktu pembayaran
    batas_waktu_bayar   TIMESTAMP WITH TIME ZONE NULL, -- Expired jika lewat waktu ini

    -- Audit Timestamps
    created_at          TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    -- Constraints
    CONSTRAINT chk_total_harga_positif CHECK (total_harga > 0),
    CONSTRAINT chk_subtotal_non_negatif CHECK (subtotal >= 0),
    CONSTRAINT chk_biaya_admin_non_negatif CHECK (biaya_admin >= 0)
);

-- Foreign Keys
ALTER TABLE transaksi
    ADD CONSTRAINT fk_transaksi_pembeli
    FOREIGN KEY (pembeli_id) REFERENCES users(id)
    ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE transaksi
    ADD CONSTRAINT fk_transaksi_dikonfirmasi_oleh
    FOREIGN KEY (dikonfirmasi_oleh) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Index
CREATE INDEX idx_transaksi_pembeli_id ON transaksi(pembeli_id);
CREATE INDEX idx_transaksi_status_pembayaran ON transaksi(status_pembayaran);
CREATE INDEX idx_transaksi_nomor_order ON transaksi(nomor_order);
CREATE INDEX idx_transaksi_created_at ON transaksi(created_at DESC);

COMMENT ON TABLE transaksi IS 'Header transaksi (Order) pembelian produk/jasa. Satu transaksi bisa berisi banyak item. Pembayaran ditujukan ke rekening BLU kampus yang terpusat.';
COMMENT ON COLUMN transaksi.nomor_order IS 'Nomor order yang ditampilkan ke pengguna, format: ORD-YYYYMMDD-XXXX.';


-- ============================================================
-- TABEL 5: REKENING_BLU (Referensi Rekening Pembayaran Terpusat)
-- Menyimpan data rekening BLU kampus sebagai tujuan pembayaran.
-- ============================================================

CREATE TABLE rekening_blu (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nama_bank       VARCHAR(100)    NOT NULL,
    nama_rekening   VARCHAR(255)    NOT NULL,
    nomor_rekening  VARCHAR(50)     NOT NULL UNIQUE,
    keterangan      TEXT            NULL,
    is_active       BOOLEAN         NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW()
);

-- Tambahkan FK rekening setelah tabel dibuat
ALTER TABLE transaksi
    ADD CONSTRAINT fk_transaksi_rekening_blu
    FOREIGN KEY (rekening_blu_id) REFERENCES rekening_blu(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

COMMENT ON TABLE rekening_blu IS 'Data rekening BLU (Badan Layanan Umum) kampus sebagai tujuan terpusat semua pembayaran transaksi.';


-- ============================================================
-- TABEL 6: DETAIL_TRANSAKSI / ORDER_ITEMS
-- Rincian item (produk/jasa) dalam satu transaksi.
-- Harga disimpan saat transaksi untuk mencegah perubahan harga mempengaruhi histori.
-- ============================================================

CREATE TABLE detail_transaksi (
    id                      UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    transaksi_id            UUID            NOT NULL,
    inovasi_id              UUID            NOT NULL,

    -- Snapshot data saat transaksi terjadi (immutable history)
    judul_saat_transaksi    VARCHAR(500)    NOT NULL, -- Judul inovasi pada saat beli
    tipe_item               tipe_item_enum  NOT NULL, -- Snapshot tipe item

    -- Kalkulasi Harga
    kuantitas               INTEGER         NOT NULL DEFAULT 1,
    harga_saat_transaksi    NUMERIC(15, 2)  NOT NULL, -- Harga per unit saat beli
    subtotal                NUMERIC(15, 2)  NOT NULL, -- kuantitas * harga_saat_transaksi

    -- Catatan per item
    catatan                 TEXT            NULL,

    created_at              TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    -- Constraints
    CONSTRAINT chk_kuantitas_positif CHECK (kuantitas > 0),
    CONSTRAINT chk_harga_saat_transaksi_positif CHECK (harga_saat_transaksi > 0),
    CONSTRAINT chk_subtotal_konsisten CHECK (
        subtotal = kuantitas * harga_saat_transaksi
    )
);

-- Foreign Keys
ALTER TABLE detail_transaksi
    ADD CONSTRAINT fk_detail_transaksi_transaksi
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE detail_transaksi
    ADD CONSTRAINT fk_detail_transaksi_inovasi
    FOREIGN KEY (inovasi_id) REFERENCES inovasi(id)
    ON DELETE RESTRICT ON UPDATE CASCADE;

-- Index
CREATE INDEX idx_detail_transaksi_transaksi_id ON detail_transaksi(transaksi_id);
CREATE INDEX idx_detail_transaksi_inovasi_id ON detail_transaksi(inovasi_id);

COMMENT ON TABLE detail_transaksi IS 'Line item dari setiap transaksi. Harga dan judul inovasi disimpan sebagai snapshot saat transaksi, agar histori tidak berubah jika harga inovasi diupdate.';
COMMENT ON COLUMN detail_transaksi.harga_saat_transaksi IS 'Harga per unit pada saat transaksi terjadi. Merupakan snapshot, tidak berubah meskipun harga inovasi diperbarui.';


-- ============================================================
-- TABEL 7: PENJADWALAN_JASA
-- Jadwal pelaksanaan untuk item bertipe 'jasa'.
-- Dibuat oleh RTPU setelah transaksi berstatus 'lunas'.
-- ============================================================

CREATE TABLE penjadwalan_jasa (
    id                      UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    -- Relasi ke line item dalam transaksi (harus tipe 'jasa')
    detail_transaksi_id     UUID            NOT NULL UNIQUE, -- 1 detail → 1 jadwal (one-to-one)

    -- Waktu Pelaksanaan
    waktu_mulai             TIMESTAMP WITH TIME ZONE NOT NULL,
    waktu_selesai           TIMESTAMP WITH TIME ZONE NOT NULL,

    -- Lokasi / Ruangan
    lokasi                  VARCHAR(255)    NULL,   -- e.g., 'Lab Uji Material Gedung H'
    keterangan_jadwal       TEXT            NULL,

    -- Status Pelaksanaan
    status_jadwal           status_jadwal_enum NOT NULL DEFAULT 'menunggu_jadwal',

    -- Bukti Pelaksanaan (diisi setelah jasa selesai)
    url_bukti_pelaksanaan   TEXT            NULL,   -- URL foto/dokumen bukti
    catatan_pelaksanaan     TEXT            NULL,   -- Catatan dari pelaksana

    -- RTPU yang membuat jadwal
    dijadwalkan_oleh        UUID            NULL,
    dijadwalkan_pada        TIMESTAMP WITH TIME ZONE NULL,

    -- Audit Timestamps
    created_at              TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    updated_at              TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),

    -- Constraints
    CONSTRAINT chk_waktu_valid CHECK (waktu_selesai > waktu_mulai)
);

-- Foreign Keys
ALTER TABLE penjadwalan_jasa
    ADD CONSTRAINT fk_penjadwalan_detail_transaksi
    FOREIGN KEY (detail_transaksi_id) REFERENCES detail_transaksi(id)
    ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE penjadwalan_jasa
    ADD CONSTRAINT fk_penjadwalan_dijadwalkan_oleh
    FOREIGN KEY (dijadwalkan_oleh) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Index
CREATE INDEX idx_penjadwalan_jasa_detail_transaksi_id ON penjadwalan_jasa(detail_transaksi_id);
CREATE INDEX idx_penjadwalan_jasa_status ON penjadwalan_jasa(status_jadwal);
CREATE INDEX idx_penjadwalan_jasa_waktu ON penjadwalan_jasa(waktu_mulai, waktu_selesai);

COMMENT ON TABLE penjadwalan_jasa IS 'Jadwal pelaksanaan untuk item jasa. Dibuat oleh RTPU setelah transaksi berstatus lunas. Relasi one-to-one dengan detail_transaksi (satu line item jasa = satu jadwal).';
COMMENT ON COLUMN penjadwalan_jasa.url_bukti_pelaksanaan IS 'URL ke file bukti pelaksanaan (foto, laporan). Diisi oleh RTPU setelah jasa selesai dilaksanakan.';


-- ============================================================
-- TABEL 8: AUDIT_LOGS (Jejak Rekam Aksi Krusial)
-- Merekam semua aksi penting di sistem: penetapan harga,
-- kurasi, verifikasi akun, perubahan publikasi, dll.
-- ============================================================

CREATE TABLE audit_logs (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),

    -- Pelaku aksi
    user_id         UUID                NULL, -- NULL jika aksi dilakukan oleh sistem
    user_name       VARCHAR(255)        NULL, -- Snapshot nama saat aksi (denormalized)
    user_role       role_enum           NULL, -- Snapshot role saat aksi

    -- Detail Aksi
    aksi            aksi_audit_enum     NOT NULL,
    nama_tabel      VARCHAR(100)        NOT NULL,   -- Tabel yang terpengaruh
    record_id       UUID                NULL,       -- ID record yang diubah

    -- Data Perubahan (JSON untuk fleksibilitas)
    -- Menyimpan nilai sebelum dan sesudah perubahan
    nilai_lama      JSONB               NULL,   -- Snapshot data sebelum diubah
    nilai_baru      JSONB               NULL,   -- Snapshot data setelah diubah

    -- Deskripsi aksi yang dapat dibaca manusia
    deskripsi       TEXT                NULL,   -- e.g., 'Menetapkan HPP = 500000, Harga Jual = 750000'

    -- Metadata Teknis
    ip_address      INET                NULL,
    user_agent      TEXT                NULL,

    -- Timestamp (immutable - tidak ada updated_at untuk log)
    created_at      TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW()
);

-- Foreign Key (SET NULL agar log tidak hilang jika user dihapus)
ALTER TABLE audit_logs
    ADD CONSTRAINT fk_audit_logs_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- Index untuk query audit yang umum
CREATE INDEX idx_audit_logs_user_id ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_nama_tabel ON audit_logs(nama_tabel);
CREATE INDEX idx_audit_logs_record_id ON audit_logs(record_id);
CREATE INDEX idx_audit_logs_aksi ON audit_logs(aksi);
CREATE INDEX idx_audit_logs_created_at ON audit_logs(created_at DESC);
-- Composite index untuk filter umum: tabel + record + waktu
CREATE INDEX idx_audit_logs_tabel_record ON audit_logs(nama_tabel, record_id, created_at DESC);

COMMENT ON TABLE audit_logs IS 'Tabel jejak rekam (audit trail) untuk semua aksi krusial: penetapan harga (Pimpinan), kurasi inovasi (P3M), verifikasi akun eksternal (RTPU), dan perubahan publikasi. Data bersifat immutable (hanya INSERT, tidak pernah UPDATE/DELETE).';
COMMENT ON COLUMN audit_logs.nilai_lama IS 'Snapshot data dalam format JSONB sebelum perubahan dilakukan.';
COMMENT ON COLUMN audit_logs.nilai_baru IS 'Snapshot data dalam format JSONB setelah perubahan dilakukan.';


-- ============================================================
-- TABEL 9: NOTIFIKASI (Opsional - Mendukung Alur Kerja)
-- Notifikasi in-app untuk pengguna terkait status pengajuan, dll.
-- ============================================================

CREATE TABLE notifikasi (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id     UUID            NOT NULL,
    judul       VARCHAR(255)    NOT NULL,
    pesan       TEXT            NOT NULL,
    tipe        VARCHAR(50)     NOT NULL DEFAULT 'info', -- 'info', 'sukses', 'peringatan', 'error'
    -- Referensi opsional ke entitas terkait
    ref_tabel   VARCHAR(100)    NULL,   -- e.g., 'inovasi', 'transaksi'
    ref_id      UUID            NULL,
    is_read     BOOLEAN         NOT NULL DEFAULT FALSE,
    dibaca_pada TIMESTAMP WITH TIME ZONE NULL,
    created_at  TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW()
);

ALTER TABLE notifikasi
    ADD CONSTRAINT fk_notifikasi_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE;

CREATE INDEX idx_notifikasi_user_id ON notifikasi(user_id);
CREATE INDEX idx_notifikasi_is_read ON notifikasi(user_id, is_read) WHERE is_read = FALSE;

COMMENT ON TABLE notifikasi IS 'Notifikasi in-app untuk pengguna. Digunakan untuk memberi tahu pengaju tentang status kurasi, pembeli tentang status pesanan, dan RTPU tentang tugas penjadwalan.';


-- ============================================================
-- FUNGSI TRIGGER: auto_update_updated_at
-- Fungsi generik untuk memperbarui kolom updated_at secara otomatis
-- ============================================================

CREATE OR REPLACE FUNCTION fn_auto_update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Terapkan trigger ke semua tabel yang memiliki updated_at
CREATE TRIGGER trg_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION fn_auto_update_updated_at();

CREATE TRIGGER trg_inovasi_updated_at
    BEFORE UPDATE ON inovasi
    FOR EACH ROW EXECUTE FUNCTION fn_auto_update_updated_at();

CREATE TRIGGER trg_transaksi_updated_at
    BEFORE UPDATE ON transaksi
    FOR EACH ROW EXECUTE FUNCTION fn_auto_update_updated_at();

CREATE TRIGGER trg_rekening_blu_updated_at
    BEFORE UPDATE ON rekening_blu
    FOR EACH ROW EXECUTE FUNCTION fn_auto_update_updated_at();

CREATE TRIGGER trg_penjadwalan_jasa_updated_at
    BEFORE UPDATE ON penjadwalan_jasa
    FOR EACH ROW EXECUTE FUNCTION fn_auto_update_updated_at();


-- ============================================================
-- VIEWS: Tampilan Data yang Sering Dibutuhkan
-- ============================================================

-- View: Inovasi yang sudah dipublikasi (untuk marketplace publik)
CREATE OR REPLACE VIEW v_marketplace AS
SELECT
    i.id,
    i.judul,
    i.slug,
    i.deskripsi_promosi,
    i.tipe_item,
    i.harga_jual,
    i.stok,
    i.satuan,
    i.url_foto_prototipe,
    u.name AS nama_pengaju,
    i.dipublikasi_pada
FROM inovasi i
JOIN users u ON u.id = i.pengaju_id
WHERE i.is_published = TRUE
  AND i.status_kurasi = 'lolos_p3m'
  AND i.harga_jual IS NOT NULL;

COMMENT ON VIEW v_marketplace IS 'View marketplace publik: hanya menampilkan inovasi yang sudah lolos kurasi P3M, sudah ditetapkan harganya, dan sudah dipublikasi oleh RTPU.';

-- View: Dashboard Kurasi P3M (inovasi yang perlu ditindaklanjuti)
CREATE OR REPLACE VIEW v_antrian_kurasi AS
SELECT
    i.id,
    i.judul,
    i.tipe_item,
    i.status_kurasi,
    u.name AS nama_pengaju,
    u.role AS role_pengaju,
    i.created_at AS tanggal_pengajuan,
    i.catatan_revisi
FROM inovasi i
JOIN users u ON u.id = i.pengaju_id
WHERE i.status_kurasi IN ('menunggu_validasi', 'revisi')
ORDER BY i.created_at ASC;

COMMENT ON VIEW v_antrian_kurasi IS 'View antrian kurasi P3M: menampilkan inovasi yang menunggu review atau menunggu revisi dari pengaju.';

-- View: Pesanan aktif yang perlu dijadwalkan oleh RTPU
CREATE OR REPLACE VIEW v_antrian_penjadwalan AS
SELECT
    dt.id AS detail_transaksi_id,
    t.nomor_order,
    i.judul AS nama_jasa,
    dt.kuantitas,
    t.pembeli_id,
    u.name AS nama_pembeli,
    t.dikonfirmasi_pada AS tanggal_lunas,
    pj.status_jadwal
FROM detail_transaksi dt
JOIN transaksi t ON t.id = dt.transaksi_id
JOIN inovasi i ON i.id = dt.inovasi_id
JOIN users u ON u.id = t.pembeli_id
LEFT JOIN penjadwalan_jasa pj ON pj.detail_transaksi_id = dt.id
WHERE dt.tipe_item = 'jasa'
  AND t.status_pembayaran = 'lunas'
ORDER BY t.dikonfirmasi_pada ASC;

COMMENT ON VIEW v_antrian_penjadwalan IS 'View antrian penjadwalan RTPU: menampilkan semua item jasa dari transaksi yang sudah lunas, dengan status jadwalnya.';


-- ============================================================
-- DATA AWAL (SEED DATA)
-- ============================================================

-- Rekening BLU Kampus (data awal)
INSERT INTO rekening_blu (nama_bank, nama_rekening, nomor_rekening, keterangan) VALUES
    ('Bank Mandiri', 'Politeknik Negeri Jakarta - BLU', '1234567890123', 'Rekening utama BLU PNJ untuk semua transaksi PNJ PRIME'),
    ('BNI', 'Politeknik Negeri Jakarta - BLU', '0987654321098', 'Rekening alternatif BLU PNJ');


-- ============================================================
-- RINGKASAN SKEMA
-- ============================================================
-- Tabel Utama    : users, inovasi, transaksi, detail_transaksi,
--                  penjadwalan_jasa, rekening_blu
-- Tabel Pendukung: media_inovasi, notifikasi, audit_logs
-- Views          : v_marketplace, v_antrian_kurasi, v_antrian_penjadwalan
-- Triggers       : Auto-update updated_at pada semua tabel utama
-- ENUM Types     : role_enum, status_verifikasi_enum, tipe_item_enum,
--                  status_kurasi_enum, status_pembayaran_enum,
--                  status_jadwal_enum, aksi_audit_enum
-- ============================================================

-- END OF SCHEMA DDL

