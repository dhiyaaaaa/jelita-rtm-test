<?php

use App\Http\Controllers\admin\akademik\FakultasController;
use App\Http\Controllers\admin\akademik\JenjangController;
use App\Http\Controllers\admin\akademik\ProdiController;
use App\Http\Controllers\admin\akademik\UnitController;
use App\Http\Controllers\admin\assessment\HasilAssessmentController;
use App\Http\Controllers\admin\assessment\PertanyaanController;
use App\Http\Controllers\admin\audit\AssessmentController;
use App\Http\Controllers\admin\audit\HasilAuditController;
use App\Http\Controllers\admin\audit\InstrumenController;
use App\Http\Controllers\admin\audit\JadwalAuditController;
use App\Http\Controllers\admin\dokumen\AyatController;
use App\Http\Controllers\admin\dokumen\JenisPertanyaanController;
use App\Http\Controllers\admin\dokumen\KategoriController;
use App\Http\Controllers\admin\dokumen\KriteriaController;
use App\Http\Controllers\admin\dokumen\LevelController;
use App\Http\Controllers\admin\dokumen\PeraturanController;
use App\Http\Controllers\admin\dokumen\PasalController;
use App\Http\Controllers\admin\dokumen\StandarController;
use App\Http\Controllers\admin\menu\MenuController;
use App\Http\Controllers\admin\user\AuditeeController;
use App\Http\Controllers\admin\user\AuditorController;
use App\Http\Controllers\admin\user\JabatanController;
use App\Http\Controllers\admin\user\RoleController;
use App\Http\Controllers\admin\user\UserController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\auditee\dokumen\DokumenController as AuditeeDokumenController;
use App\Http\Controllers\auditee\lapangan\LapanganController as AuditeeLapanganController;
use App\Http\Controllers\auditee\lapangan\PtkController as AuditeePtkController;
use App\Http\Controllers\auditor\dokumen\DokumenController as AuditorDokumenController;
use App\Http\Controllers\auditor\lapangan\LapanganController as AuditorLapanganController;
use App\Http\Controllers\auditor\lapangan\BeritaAcaraController as AuditorBeritaAcaraController;
use App\Http\Controllers\auditor\lapangan\PtkController as AuditorPtkController;
use App\Http\Controllers\auditor\lapangan\LaporanController as AuditorLaporanController;
use App\Http\Controllers\auditor\AuditorUnitController as AuditorUnitController;
use App\Http\Controllers\auditor\PeerAssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\gpm\AuditorController as GpmAuditorController;
use App\Http\Controllers\HasilAuditProdiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    // Login Pusjamu
    Route::get('pusjamu', [AuthController::class, 'pusjamu'])->name('login.pusjamu');

    // Login
    Route::get('', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');

    // Google Auth
    Route::get('oauth/google', [AuthController::class, 'redirect'])->name('login.google');
    Route::get('oauth/google/callback', [AuthController::class, 'callback'])->name('google.callback');
});

Route::group(['middleware' => 'auth'], function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('edit_profile', [AuthController::class, 'edit_profile'])->name('profile.edit');

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Notifikasi
    Route::get('notifikasi', [DashboardController::class, 'notifikasi'])->name('notifikasi');

    // Middleware Role Pusjamu/Admin
    Route::group(['middleware' => 'role:pusjamu'], function () {
        // Audit
        Route::prefix('audit')->group(function () {
            // Instrumen
            Route::prefix('instrumen')->group(function () {
                // Ajax Request
                Route::get('get_instrumens', [InstrumenController::class, 'get_instrumens'])->name('instrumen.get_instrumen');
                Route::get('get_pasal_by_peraturan/{peraturan}', [InstrumenController::class, 'get_pasal_by_peraturan'])->name('instrumen.get_pasal_by_peraturan');
                Route::get('get_ayat_by_pasal/{pasal}', [InstrumenController::class, 'get_ayat_by_pasal'])->name('instrumen.get_ayat_by_pasal');
                Route::get('get_standar_by_peraturan/{peraturan}', [InstrumenController::class, 'get_standar_by_peraturan'])->name('instrumen.get_standar_by_peraturan');
                Route::get('get_kategori_by_standar/{standar}', [InstrumenController::class, 'get_kategori_by_standar'])->name('instrumen.get_kategori_by_standar');
                Route::get('get_jabatan_by_level/{level}', [InstrumenController::class, 'get_jabatan_by_level'])->name('instrumen.get_jabatan_by_level');
                Route::get('{standar}/get_kode/{kategori}', [InstrumenController::class, 'get_kode'])->name('instrumen.get_kode');
                // Lihat
                Route::get('', [InstrumenController::class, 'index'])->name('instrumen');
                // Create
                Route::get('create', [InstrumenController::class, 'create'])->name('instrumen.create');
                Route::post('store', [InstrumenController::class, 'store'])->name('instrumen.store');
                // Update
                Route::get('{instrumen}/edit', [InstrumenController::class, 'edit'])->name('instrumen.edit');
                Route::put('{instrumen}/update', [InstrumenController::class, 'update'])->name('instrumen.update');
                // Delete
                Route::delete('{instrumen}/destroy', [InstrumenController::class, 'destroy'])->name('instrumen.delete');
            });

            // Jadwal Audit
            Route::prefix('jadwal-audit')->group(function () {
                // Jadwal Audit
                // Ajax Request
                Route::get('get_instrumen', [JadwalAuditController::class, 'get_instrumen'])->name('jadwal_audit.get_instrumen');
                Route::get('get_instrumen_by_jadwal/{jadwalAudit}', [JadwalAuditController::class, 'get_instrumen_by_jadwal'])->name('jadwal_audit.get_instrumen_by_jadwal');
                // Lihat
                Route::get('', [JadwalAuditController::class, 'index'])->name('jadwal_audit');
                Route::get('{jadwalAudit}/show', [JadwalAuditController::class, 'show'])->name('jadwal_audit.show');
                // Create
                Route::get('create', [JadwalAuditController::class, 'create'])->name('jadwal_audit.create');
                Route::post('store', [JadwalAuditController::class, 'store'])->name('jadwal_audit.store');
                // Update
                Route::get('{jadwalAudit}/edit', [JadwalAuditController::class, 'edit'])->name('jadwal_audit.edit');
                Route::put('{jadwalAudit}/update', [JadwalAuditController::class, 'update'])->name('jadwal_audit.update');
                // Delete
                Route::delete('{jadwalAudit}/destroy', [JadwalAuditController::class, 'destroy'])->name('jadwal_audit.delete');
                // Delete Instrumen dari Jadwal
                Route::delete('{form}/destroy_form', [JadwalAuditController::class, 'destroy_form'])->name('jadwal_audit.delete_form');
                // Import
                Route::put('{jadwalAudit}/import', [JadwalAuditController::class, 'import'])->name('jadwal_audit.import');
                // Auditor
                Route::put('{jadwalAudit}/fitur_auditor', [JadwalAuditController::class, 'fitur_auditor'])->name('jadwal_audit.fitur_auditor');
                // Setting
                Route::get('setting', [JadwalAuditController::class, 'edit_setting'])->name('jadwal_audit.edit_setting');
                Route::post('update_setting', [JadwalAuditController::class, 'update_setting'])->name('jadwal_audit.update_setting');

                // Assessment
                // Ajax Request
                Route::get('get_assessment_pertanyaan', [AssessmentController::class, 'get_assessment_pertanyaan'])->name('assessment.get_assessment_pertanyaan');
                // Create
                Route::get('assessment/{jadwalAudit}/create', [AssessmentController::class, 'create'])->name('assessment.create');
                Route::post('assessment/{jadwalAudit}/store', [AssessmentController::class, 'store'])->name('assessment.store');
                // Update
                Route::get('assessment/{jadwalAudit}/edit', [AssessmentController::class, 'edit'])->name('assessment.edit');
                Route::put('assessment/{jadwalAudit}/update', [AssessmentController::class, 'update'])->name('assessment.update');
            });
        });

        // User
        Route::prefix('user')->group(function () {
            // User
            Route::prefix('user')->group(function () {
                // Ajax Request
                Route::get('get_users', [UserController::class, 'get_users'])->name('user.get_users');
                Route::get('get_prodi_by_fakultas/{fakultas}', [UserController::class, 'get_prodi_by_fakultas'])->name('user.get_prodi_by_fakultas');
                Route::get('get_unit_by_jabatan/{jabatan}', [UserController::class, 'get_unit_by_jabatan'])->name('user.get_unit_by_jabatan');
                // Lihat
                Route::get('', [UserController::class, 'index'])->name('user');
                // Create
                Route::get('create', [UserController::class, 'create'])->name('user.create');
                Route::post('store', [UserController::class, 'store'])->name('user.store');
                Route::get('create_user_role', [UserController::class, 'create_user_role'])->name('user.create_user_role');
                Route::post('store_user_role', [UserController::class, 'store_user_role'])->name('user.store_user_role');
                // Update
                Route::get('{user}/edit', [UserController::class, 'edit'])->name('user.edit');
                Route::put('{user}/update', [UserController::class, 'update'])->name('user.update');
                // Delete
                Route::delete('{user}/destroy', [UserController::class, 'destroy'])->name('user.delete');
            });

            // Role
            Route::prefix('role')->group(function () {
                // Ajax Request Get Users By Role
                Route::get('get_users_by_role/{role}', [RoleController::class, 'get_users_by_role'])->name('role.get_users_by_role');
                // Lihat
                Route::get('', [RoleController::class, 'index'])->name('role');
                Route::get('{role}/show', [RoleController::class, 'show'])->name('role.show');
            });

            // Jabatan
            Route::prefix('jabatan')->group(function () {
                // Ajax Request Get Unit By Type
                Route::get('get_unit_by_type/{type}', [JabatanController::class, 'get_unit_by_type'])->name('jabatan.get_unit_by_type');
                // Lihat
                Route::get('', [JabatanController::class, 'index'])->name('jabatan');
                Route::get('{jabatan}/show', [JabatanController::class, 'show'])->name('jabatan.show');
                // Create
                Route::get('create', [JabatanController::class, 'create'])->name('jabatan.create');
                Route::post('store', [JabatanController::class, 'store'])->name('jabatan.store');
                // Update
                Route::get('{jabatan}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit');
                Route::put('{jabatan}/update', [JabatanController::class, 'update'])->name('jabatan.update');
                // Delete
                Route::delete('{jabatan}/destroy', [JabatanController::class, 'destroy'])->name('jabatan.delete');
            });

            // Auditor
            Route::prefix('auditor')->group(function () {
                // Ajax Request
                Route::get('{jadwalAudit}/get_users', [AuditorController::class, 'get_users'])->name('auditor.get_users');
                // Lihat
                Route::get('', [AuditorController::class, 'index'])->name('auditor');
                Route::get('{jadwalAudit}/show', [AuditorController::class, 'show'])->name('auditor.show');
                // Create
                Route::get('{jadwalAudit}/create', [AuditorController::class, 'create'])->name('auditor.create');
                Route::post('{jadwalAudit}/store', [AuditorController::class, 'store'])->name('auditor.store');
                // Delete
                Route::delete('{auditor}/destroy', [AuditorController::class, 'destroy'])->name('auditor.delete');
            });

            // Auditee
            Route::prefix('auditan')->group(function () {
                // Ajax Request
                // Lihat
                Route::get('', [AuditeeController::class, 'index'])->name('auditee');
                Route::get('{jadwalAudit}/show/{type}', [AuditeeController::class, 'show'])->name('auditee.show');
                // Create
                Route::get('{jadwalAudit}/create/{unit}/{type}', [AuditeeController::class, 'create'])->name('auditee.create');
                Route::post('{jadwalAudit}/store/{unit}/{type}', [AuditeeController::class, 'store'])->name('auditee.store');
                // Update
                Route::get('{jadwalAudit}/edit/{unit}/{type}', [AuditeeController::class, 'edit'])->name('auditee.edit');
                Route::put('{jadwalAudit}/update/{unit}/{type}', [AuditeeController::class, 'update'])->name('auditee.update');
                // Delete
                Route::delete('{jadwalAudit}/destroy/{unit}/{type}', [AuditeeController::class, 'destroy'])->name('auditee.delete');
            });

            // Auditee Auditor
            Route::prefix('auditan-auditor')->group(function () {
                // Attach Auditor to Auditee
                Route::get('{jadwalAudit}/create/{unit}/{type}', [AuditorController::class, 'create_auditee_auditor'])->name('auditee_auditor.create');
                Route::post('{jadwalAudit}/store/{unit}/{type}', [AuditorController::class, 'store_auditee_auditor'])->name('auditee_auditor.store');
                // Edit Auditor Auditee
                Route::get('{jadwalAudit}/edit/{unit}/{type}', [AuditorController::class, 'edit_auditee_auditor'])->name('auditee_auditor.edit');
                Route::put('{jadwalAudit}/update/{unit}/{type}', [AuditorController::class, 'update_auditee_auditor'])->name('auditee_auditor.update');
            });
        });

        // Dokumen
        Route::prefix('dokumen')->group(function () {
            // Peraturan
            Route::prefix('peraturan')->group(function () {
                // Peraturan
                // Lihat
                Route::get('', [PeraturanController::class, 'index'])->name('peraturan');
                Route::get('{peraturan}/show', [PeraturanController::class, 'show'])->name('peraturan.show');
                // Create
                Route::get('create', [PeraturanController::class, 'create'])->name('peraturan.create');
                Route::post('store', [PeraturanController::class, 'store'])->name('peraturan.store');
                // Update
                Route::get('{peraturan}/edit', [PeraturanController::class, 'edit'])->name('peraturan.edit');
                Route::put('{peraturan}/update', [PeraturanController::class, 'update'])->name('peraturan.update');
                // Delete
                Route::delete('{peraturan}/destroy', [PeraturanController::class, 'destroy'])->name('peraturan.delete');

                // Pasal
                // Lihat
                Route::get('pasal/{pasal}/show', [PasalController::class, 'show'])->name('pasal.show');
                // Create
                Route::get('pasal/create', [PasalController::class, 'create'])->name('pasal.create');
                Route::post('pasal/store', [PasalController::class, 'store'])->name('pasal.store');
                // Update
                Route::get('pasal/{pasal}/edit', [PasalController::class, 'edit'])->name('pasal.edit');
                Route::put('pasal/{pasal}/update', [PasalController::class, 'update'])->name('pasal.update');
                // Delete
                Route::delete('pasal/{pasal}/destroy', [PasalController::class, 'destroy'])->name('pasal.delete');

                // Ayat
                Route::get('ayat/get-pasal/{peraturan}', [AyatController::class, 'get_pasal_by_peraturan'])->name('ayat.get_pasal');
                // Create
                Route::get('ayat/create', [AyatController::class, 'create'])->name('ayat.create');
                Route::post('ayat/store', [AyatController::class, 'store'])->name('ayat.store');
                // Update
                Route::get('ayat/{ayat}/edit', [AyatController::class, 'edit'])->name('ayat.edit');
                Route::put('ayat/{ayat}/update', [AyatController::class, 'update'])->name('ayat.update');
                // Delete
                Route::delete('ayat/{ayat}/destroy', [AyatController::class, 'destroy'])->name('ayat.delete');
            });

            // Standar
            Route::prefix('standar')->group(function () {
                // Standar
                // Lihat
                Route::get('', [StandarController::class, 'index'])->name('standar');
                Route::get('{standar}/show', [StandarController::class, 'show'])->name('standar.show');
                // Create
                Route::get('create', [StandarController::class, 'create'])->name('standar.create');
                Route::post('store', [StandarController::class, 'store'])->name('standar.store');
                // Update
                Route::get('{standar}/edit', [StandarController::class, 'edit'])->name('standar.edit');
                Route::put('{standar}/update', [StandarController::class, 'update'])->name('standar.update');
                // Delete
                Route::delete('{standar}/destroy', [StandarController::class, 'destroy'])->name('standar.delete');

                // Kategori
                // Create
                Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
                Route::post('kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
                // Update
                Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
                Route::put('kategori/{kategori}/update', [KategoriController::class, 'update'])->name('kategori.update');
                // Delete
                Route::delete('kategori/{kategori}/destroy', [KategoriController::class, 'destroy'])->name('kategori.delete');
            });

            // Level
            Route::prefix('level')->group(function () {
                // Lihat
                Route::get('', [LevelController::class, 'index'])->name('level');
                // Create
                Route::get('create', [LevelController::class, 'create'])->name('level.create');
                Route::post('store', [LevelController::class, 'store'])->name('level.store');
                // Update
                Route::get('{level}/edit', [LevelController::class, 'edit'])->name('level.edit');
                Route::put('{level}/update', [LevelController::class, 'update'])->name('level.update');
                // Delete
                Route::delete('{level}/destroy', [LevelController::class, 'destroy'])->name('level.delete');
            });

            // Kriteria
            Route::prefix('kriteria')->group(function () {
                // Lihat
                Route::get('', [KriteriaController::class, 'index'])->name('kriteria');
                // Create
                Route::get('create', [KriteriaController::class, 'create'])->name('kriteria.create');
                Route::post('store', [KriteriaController::class, 'store'])->name('kriteria.store');
                // Update
                Route::get('{kriteria}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
                Route::put('{kriteria}/update', [KriteriaController::class, 'update'])->name('kriteria.update');
                // Delete
                Route::delete('{kriteria}/destroy', [KriteriaController::class, 'destroy'])->name('kriteria.delete');
            });

            // Jenis Pertanyaan
            Route::prefix('jenis-pertanyaan')->group(function () {
                // Lihat
                Route::get('', [JenisPertanyaanController::class, 'index'])->name('jenis_pertanyaan');
                // Create
                Route::get('create', [JenisPertanyaanController::class, 'create'])->name('jenis_pertanyaan.create');
                Route::post('store', [JenisPertanyaanController::class, 'store'])->name('jenis_pertanyaan.store');
                // Update
                Route::get('{jenisPertanyaan}/edit', [JenisPertanyaanController::class, 'edit'])->name('jenis_pertanyaan.edit');
                Route::put('{jenisPertanyaan}/update', [JenisPertanyaanController::class, 'update'])->name('jenis_pertanyaan.update');
                // Delete
                Route::delete('{jenisPertanyaan}/destroy', [JenisPertanyaanController::class, 'destroy'])->name('jenis_pertanyaan.delete');
            });
        });

        // Assessment
        Route::prefix('assessment')->group(function () {
            // Pertanyaan
            Route::prefix('pertanyaan')->group(function () {
                // Lihat
                Route::get('', [PertanyaanController::class, 'index'])->name('pertanyaan');
                // Create
                Route::get('create', [PertanyaanController::class, 'create'])->name('pertanyaan.create');
                Route::post('store', [PertanyaanController::class, 'store'])->name('pertanyaan.store');
                // Update
                Route::get('{pertanyaan}/edit', [PertanyaanController::class, 'edit'])->name('pertanyaan.edit');
                Route::put('{pertanyaan}/update', [PertanyaanController::class, 'update'])->name('pertanyaan.update');
                // Delete
                Route::delete('{pertanyaan}/destroy', [PertanyaanController::class, 'destroy'])->name('pertanyaan.delete');
            });

            // Hasil
            Route::prefix('hasil')->group(function () {
                // Ajax Response
                Route::get('get_auditors', [HasilAssessmentController::class, 'get_auditors'])->name('hasil_assessment.get_auditors');

                // Lihat
                Route::get('', [HasilAssessmentController::class, 'index'])->name('hasil_assessment');
                Route::get('{auditor}/show', [HasilAssessmentController::class, 'show'])->name('hasil_assessment.show');
                Route::get('{auditorDinilai}/assessment/{auditorPenilai}/{unit}/{type}', [HasilAssessmentController::class, 'assessment'])->name('hasil_assessment.show_assessment');
            });
        });

        // Akademik
        Route::prefix('akademik')->group(function () {
            // Jenjang
            Route::prefix('jenjang')->group(function () {
                // Lihat
                Route::get('', [JenjangController::class, 'index'])->name('jenjang');
                Route::get('{jenjang}/show', [JenjangController::class, 'show'])->name('jenjang.show');
                // Create
                Route::get('create', [JenjangController::class, 'create'])->name('jenjang.create');
                Route::post('store', [JenjangController::class, 'store'])->name('jenjang.store');
                // Update
                Route::get('{jenjang}/edit', [JenjangController::class, 'edit'])->name('jenjang.edit');
                Route::put('{jenjang}/update', [JenjangController::class, 'update'])->name('jenjang.update');
                // Delete
                Route::delete('{jenjang}/destroy', [JenjangController::class, 'destroy'])->name('jenjang.delete');
            });

            // Fakultas
            Route::prefix('fakultas')->group(function () {
                // Lihat
                Route::get('', [FakultasController::class, 'index'])->name('fakultas');
                Route::get('{fakultas}/show', [FakultasController::class, 'show'])->name('fakultas.show');
                // Create
                Route::get('create', [FakultasController::class, 'create'])->name('fakultas.create');
                Route::post('store', [FakultasController::class, 'store'])->name('fakultas.store');
                // Update
                Route::get('{fakultas}/edit', [FakultasController::class, 'edit'])->name('fakultas.edit');
                Route::put('{fakultas}/update', [FakultasController::class, 'update'])->name('fakultas.update');
                // Delete
                Route::delete('{fakultas}/destroy', [FakultasController::class, 'destroy'])->name('fakultas.delete');
            });

            // Prodi
            Route::prefix('prodi')->group(function () {
                // Lihat
                Route::get('', [ProdiController::class, 'index'])->name('prodi');
                Route::get('get-prodi', [ProdiController::class, 'get_prodi'])->name('prodi.get_prodi');
                // Create
                Route::get('create', [ProdiController::class, 'create'])->name('prodi.create');
                Route::post('store', [ProdiController::class, 'store'])->name('prodi.store');
                // Update
                Route::get('{prodi}/edit', [ProdiController::class, 'edit'])->name('prodi.edit');
                Route::put('{prodi}/update', [ProdiController::class, 'update'])->name('prodi.update');
                // Delete
                Route::delete('{prodi}/destroy', [ProdiController::class, 'destroy'])->name('prodi.delete');
            });

            // Unit
            Route::prefix('unit')->group(function () {
                // Lihat
                Route::get('', [UnitController::class, 'index'])->name('unit');
                // Create
                Route::get('create', [UnitController::class, 'create'])->name('unit.create');
                Route::post('store', [UnitController::class, 'store'])->name('unit.store');
                // Update
                Route::get('{unit}/edit', [UnitController::class, 'edit'])->name('unit.edit');
                Route::put('{unit}/update', [UnitController::class, 'update'])->name('unit.update');
                // Delete
                Route::delete('{unit}/destroy', [UnitController::class, 'destroy'])->name('unit.delete');
            });
        });

        // Menu
        Route::prefix('menu')->group(function () {
            // Menu
            // Lihat
            Route::get('', [MenuController::class, 'index'])->name('menu');
            Route::get('{menu}/show', [MenuController::class, 'show'])->name('menu.show');
        });
    });

    // Hasil Audit untuk Rektor dan Pusjamu
    Route::group(['middleware' => 'check_is_admin_or_rektor'], function () {
        Route::prefix('audit/hasil-audit')->group(function () {
            // Lihat
            Route::get('', [HasilAuditController::class, 'index'])->name('hasil_audit');
            Route::get('{jadwalAudit}/show/{type}', [HasilAuditController::class, 'show'])->name('hasil_audit.show');

            // Audit Dokumen
            Route::get('{jadwalAudit}/audit_dokumen/{unit}/{type}', [HasilAuditController::class, 'audit_dokumen'])->name('hasil_audit.audit_dokumen');

            // Daftar Tilik
            Route::get('{jadwalAudit}/daftar_tilik/{unit}/{type}', [HasilAuditController::class, 'daftar_tilik'])->name('hasil_audit.daftar_tilik');

            // PTK
            Route::get('{ptk}/ptk', [HasilAuditController::class, 'ptk'])->name('hasil_audit.ptk');

            // Laporan
            Route::get('{laporan}/laporan', [HasilAuditController::class, 'laporan'])->name('hasil_audit.laporan');
        });
    });

    // Middlware Auditan
    Route::group(['middleware' => 'check_auditee'], function () {
        // Audit Dokumen
        Route::prefix('auditan/dokumen')->group(function () {
            // Index
            Route::get('', [AuditeeDokumenController::class, 'index'])->name('auditee.dokumen');

            // Isi Audit Dokumen
            // Ubah Status Auditee
            Route::post('{jadwalAudit}/isi-audit/{unit}/{type}', [AuditeeDokumenController::class, 'isi_audit'])->name('auditee.dokumen.isi_audit');

            // Create
            Route::get('{jadwalAudit}/create/{unit}/{type}', [AuditeeDokumenController::class, 'create'])->name('auditee.dokumen.create');

            // Store
            Route::post('{jadwalAudit}/store/{unit}/{type}', [AuditeeDokumenController::class, 'store'])->name('auditee.dokumen.store');

            // Save Jawaban Sementara
            Route::post('{jadwalAudit}/save/{unit}/{type}', [AuditeeDokumenController::class, 'save'])->name('auditee.dokumen.save');
            // Save Jawaban Per Nomor
            Route::post('{jadwalAudit}/save/{unit}/{type}/{formId}', [AuditeeDokumenController::class, 'save_per_nomor'])->name('auditee.dokumen.save_per_nomor');

            // Session
            Route::post('{jadwalAudit}/isi-audit-session/{unit}/{auditee}', [SessionController::class, 'session_auditee'])->name('auditee.dokumen.session');
            // Session Per Nomor
            Route::post('{jadwalAudit}/isi-audit-session/{unit}/{auditee}/{formId}', [SessionController::class, 'session_auditee_per_nomor'])->name('auditee.dokumen.session_per_nomor');

            // Import Jawaban
            Route::get('{jadwalAudit}/import/{unit}/{type}', [AuditeeDokumenController::class, 'import'])->name('auditee.dokumen.import');
            Route::post('{jadwalAudit}/import/{unit}/{type}', [AuditeeDokumenController::class, 'import_store'])->name('auditee.dokumen.import_store');

            // Notifikasi
            Route::post('{notifikasi}/notifikasi/{auditee}', [NotifikasiController::class, 'kirim_notifikasi_auditee'])->name('auditee.notifikasi');
        });

        // Audit Lapangan
        Route::prefix('auditan/lapangan')->group(function () {
            // Index
            Route::get('', [AuditeeLapanganController::class, 'index'])->name('auditee.lapangan');

            // Berita Acara
            // Approve Berita Acara
            Route::post('beritaacara/{beritaAcara}/approve/{auditee}', [ApproveController::class, 'approve_berita_acara_auditee'])->name('auditee.lapangan.berita_acara.approve');

            // Approve PTK
            Route::post('ptk/{ptk}/approve/{auditee}', [ApproveController::class, 'approve_ptk_auditee'])->name('auditee.lapangan.approve_ptk');

            // Approve Laporan
            Route::post('laporan/{laporan}/approve/{auditee}', [ApproveController::class, 'approve_laporan_auditee'])->name('auditee.lapangan.laporan.approve');

            // Isi PTK
            // Ubah Status PTK Auditee
            Route::post('ptk/{ptk}/isi_audit', [AuditeePtkController::class, 'isi_ptk'])->name('auditee.lapangan.isi_ptk');

            Route::get('ptk/{ptk}', [AuditeePtkController::class, 'create'])->name('auditee.lapangan.create_ptk');

            // Store PTK
            Route::post('ptk/{ptk}/store/{auditee}', [AuditeePtkController::class, 'store'])->name('auditee.lapangan.store_ptk');

            // Save Jawaban Sementara PTK
            Route::post('ptk/{ptk}/save/{auditee}', [AuditeePtkController::class, 'save'])->name('auditee.lapangan.save_ptk');
            // Save Jawaban per Nomor
            Route::post('ptk/{ptk}/save/{auditee}/{formId}', [AuditeePtkController::class, 'save_per_nomor'])->name('auditee.lapangan.save_ptk_per_nomor');

            // Session PTK Form
            Route::post('ptk/{ptk}/session_ptk_auditee/{auditee}', [SessionController::class, 'session_ptk_auditee'])->name('auditee.lapangan.session_ptk');
            // Session PTK per nomor
            Route::post('ptk/{ptk}/session_ptk_auditee/{auditee}/{formId}', [SessionController::class, 'session_ptk_auditee_per_nomor'])->name('auditee.lapangan.session_ptk_per_nomor');
        });
    });

    // Middlware Auditor
    Route::group(['middleware' => 'check_auditor'], function () {
        // Pilih Prodi/Fakultas/Unit yg diaudit
        Route::get('{jadwalAudit}/show/{type}', [AuditorUnitController::class, 'show'])->name('auditor.unit.show');
        Route::post('{auditor}/pilih/{unit}/{type}', [AuditorUnitController::class, 'pilih'])->name('auditor.unit.pilih');

        // Audit Dokumen
        Route::prefix('auditor/dokumen')->group(function () {
            // Index
            Route::get('', [AuditorDokumenController::class, 'index'])->name('auditor.dokumen');
            // Show
            Route::get('{jadwalAudit}/show', [AuditorDokumenController::class, 'show'])->name('auditor.dokumen.show');

            // Isi Audit
            // Status
            Route::post('{jadwalAudit}/isi_audit/{unit}/{type}', [AuditorDokumenController::class, 'isi_audit'])->name('auditor.dokumen.isi_audit');

            // Create
            Route::get('{jadwalAudit}/create/{unit}/{type}', [AuditorDokumenController::class, 'create'])->name('auditor.dokumen.create');

            // Store
            Route::post('{jadwalAudit}/store/{unit}/{type}', [AuditorDokumenController::class, 'store'])->name('auditor.dokumen.store');

            // Save Jawaban Sementara
            Route::post('{jadwalAudit}/save/{unit}/{type}', [AuditorDokumenController::class, 'save'])->name('auditor.dokumen.save');
            // Save Jawaban per Nomor
            Route::post('{jadwalAudit}/save/{unit}/{type}/{formId}', [AuditorDokumenController::class, 'save_per_nomor'])->name('auditor.dokumen.save_per_nomor');

            // Session
            Route::post('{jadwalAudit}/isi_audit_session/{unit}/{auditorId}', [SessionController::class, 'session_auditor'])->name('auditor.dokumen.session');
            // Session per Nomor
            Route::post('{jadwalAudit}/isi_audit_session/{unit}/{auditorId}/{formId}', [SessionController::class, 'session_auditor_per_nomor'])->name('auditor.dokumen.session_per_nomor');

            // Kirim Notifikasi
            Route::post('{jadwalAudit}/notifikasi/{unit}/{type}/{instrumenId}/{auditorId}', [NotifikasiController::class, 'kirim_notifikasi_auditor'])->name('auditor.notifikasi');

            // Notifikasi Selesai
            Route::post('{notifikasi}/notifikasi_selesai', [NotifikasiController::class, 'notifikasi_selesai'])->name('auditor.notifikasi.selesai');

            // Daftar Tilik
            Route::get('{jadwalAudit}/daftar_tilik/{unit}/{type}', [AuditorDokumenController::class, 'show_daftar_tilik'])->name('auditor.dokumen.daftar_tilik');

            // Edit Hapus Daftar Tilik PTK
            Route::post('{jawabanId}/edit_hapus_ptk', [AuditorDokumenController::class, 'edit_hapus_ptk'])->name('auditor.dokumen.edit_hapus_ptk');
        });

        // Audit Lapangan
        Route::prefix('auditor/lapangan')->group(function () {
            // Index
            Route::get('', [AuditorLapanganController::class, 'index'])->name('auditor.lapangan');

            // Show
            Route::get('{jadwalAudit}/show', [AuditorLapanganController::class, 'show'])->name('auditor.lapangan.show');

            // Berita Acara
            // Create
            Route::get('{jadwalAudit}/berita_acara/{unit}/{type}', [AuditorBeritaAcaraController::class, 'create'])->name('auditor.lapangan.berita_acara.create');

            // Store
            Route::post('{jadwalAudit}/berita_acara/{unit}/{type}', [AuditorBeritaAcaraController::class, 'store'])->name('auditor.lapangan.berita_acara.store');

            // Edit
            Route::get('berita-acara/{beritaAcara}/edit', [AuditorBeritaAcaraController::class, 'edit'])->name('auditor.lapangan.berita_acara.edit');

            // Update
            Route::put('berita-acara/{beritaAcara}/update', [AuditorBeritaAcaraController::class, 'update'])->name('auditor.lapangan.berita_acara.update');

            // Delete
            Route::delete('berita-acara/{beritaAcara}/destroy', [AuditorBeritaAcaraController::class, 'destroy'])->name('auditor.lapangan.berita_acara.delete');

            // Approve Berita Acara
            Route::post('berita_acara/{beritaAcara}/approve/{auditor}', [ApproveController::class, 'approve_berita_acara_auditor'])->name('auditor.lapangan.berita_acara.approve');

            // PTK
            // Create
            Route::get('{jadwalAudit}/ptk/{unit}/{type}', [AuditorPtkController::class, 'create'])->name('auditor.lapangan.ptk.create');

            // Store
            Route::post('{jadwalAudit}/ptk/{unit}/{type}/store', [AuditorPtkController::class, 'store'])->name('auditor.lapangan.ptk.store');

            // Edit
            Route::get('ptk/{ptk}/edit', [AuditorPtkController::class, 'edit'])->name('auditor.lapangan.ptk.edit');

            // Update
            Route::put('ptk/{ptk}/update', [AuditorPtkController::class, 'update'])->name('auditor.lapangan.ptk.update');

            // Delete
            Route::delete('ptk/{ptk}/destroy', [AuditorPtkController::class, 'destroy'])->name('auditor.lapangan.ptk.delete');

            // Approve PTK
            Route::post('ptk/{ptk}/approve/{auditor}', [ApproveController::class, 'approve_ptk_auditor'])->name('auditor.lapangan.ptk.approve');

            // Isi PTK
            Route::post('isi_ptk/{ptk}', [AuditorPtkController::class, 'isi_ptk'])->name('auditor.lapangan.ptk.isi_ptk');

            // Form PTK
            Route::get('ptk/{ptk}/form', [AuditorPtkController::class, 'form'])->name('auditor.lapangan.ptk.form');

            // Store Form PTK
            Route::post('ptk/{ptk}/store/{auditor}', [AuditorPtkController::class, 'store_form'])->name('auditor.lapangan.ptk.store_form');

            // Save Form PTK
            Route::post('ptk/{ptk}/save/{auditor}', [AuditorPtkController::class, 'save_form'])->name('auditor.lapangan.ptk.save_form');
            // Save Form PTK per nomor
            Route::post('ptk/{ptk}/save/{auditor}/{formId}', [AuditorPtkController::class, 'save_form_per_nomor'])->name('auditor.lapangan.ptk.save_form_per_nomor');

            // Save Session Form PTK 
            Route::post('{ptk}/auditor/{auditor}', [SessionController::class, 'session_ptk_auditor'])->name('auditor.lapangan.ptk.session');
            // Save Session PTK per nomor
            Route::post('{ptk}/auditor/{auditor}/{formId}', [SessionController::class, 'session_ptk_auditor_per_nomor'])->name('auditor.lapangan.ptk.session_per_nomor');

            // Hapus Instrumen dari PTK
            Route::post('{ptk}/ptk/{form}', [AuditorPtkController::class, 'hapus_instrumen'])->name('auditor.lapangan.ptk.delete_instrumen');

            // Laporan
            // Create Laporan
            Route::get('laporan/{jadwalAudit}/create/{unit}/{type}', [AuditorLaporanController::class, 'create'])->name('auditor.lapangan.laporan.create');

            // Store
            Route::post('laporan/{jadwalAudit}/store/{unit}/{type}', [AuditorLaporanController::class, 'store'])->name('auditor.lapangan.laporan.store');

            // Edit
            Route::get('laporan/{laporan}/edit', [AuditorLaporanController::class, 'edit'])->name('auditor.lapangan.laporan.edit');

            // Update
            Route::put('laporan/{laporan}/update', [AuditorLaporanController::class, 'update'])->name('auditor.lapangan.laporan.update');

            // Delete
            Route::delete('laporan/{laporan}/destroy', [AuditorLaporanController::class, 'destroy'])->name('auditor.lapangan.laporan.delete');

            // Approve Laporan
            Route::post('laporan/{laporan}/approve/{auditor}', [ApproveController::class, 'approve_laporan_auditor'])->name('auditor.lapangan.laporan.approve');

            // Status Laporan
            Route::post('laporan/{laporan}/isi_laporan', [AuditorLaporanController::class, 'isi_laporan'])->name('auditor.lapangan.laporan.isi_laporan');

            // Form
            Route::get('laporan/{laporan}/create_form', [AuditorLaporanController::class, 'form'])->name('auditor.lapangan.laporan.form');

            // Store Form
            Route::post('laporan/{laporan}/store_form/{auditor}', [AuditorLaporanController::class, 'store_form'])->name('auditor.lapangan.laporan.store_form');

            // Save Form
            Route::post('laporan/{laporan}/save_form/{auditor}', [AuditorLaporanController::class, 'save_form'])->name('auditor.lapangan.laporan.save_form');
            // Save Form per Nomor
            Route::post('laporan/{laporan}/save_form/{auditor}/{formId}', [AuditorLaporanController::class, 'save_form_per_nomor'])->name('auditor.lapangan.laporan.save_form_per_nomor');

            // Save Session Laporan Form
            Route::post('laporan/{laporan}/session/{auditor}', [SessionController::class, 'session_laporan_auditor'])->name('auditor.lapangan.laporan.session');
            // Save Session Laporan Form per nomor
            Route::post('laporan/{laporan}/session/{auditor}/{formId}', [SessionController::class, 'session_laporan_auditor_per_nomor'])->name('auditor.lapangan.laporan.session_per_nomor');
        });

        // Peer Assessment
        Route::prefix('peer-assessment')->group(function () {
            // Index
            Route::get('', [PeerAssessmentController::class, 'index'])->name('auditor.peer-assessment');

            // Create
            Route::get('{auditor}/create/{jadwalAudit}/{unit}/{type}', [PeerAssessmentController::class, 'create'])->name('auditor.peer-assessment.create');

            // Store
            Route::post('{auditor}/store/{jadwalAudit}/{unit}/{type}', [PeerAssessmentController::class, 'store'])->name('auditor.peer-assessment.store');

            // Session
            Route::post('{auditor}/session/{jadwalAudit}/{unit}', [SessionController::class, 'session_peer_assessment'])->name('auditor.peer-assessment.session');
        });
    });

    // Download Berita Acara, PTK, Laporan, Daftar Tilik, Isian Audit
    Route::prefix('download')->group(function () {
        // Download Berita Acara
        Route::post('beritaacara/{beritaAcara}', [DownloadController::class, 'download_berita_acara'])->name('download.berita-acara');

        // Download PTK
        Route::post('ptk/{ptk}', [DownloadController::class, 'download_ptk'])->name('download.ptk');

        // Download Laporan
        Route::post('laporan/{laporan}', [DownloadController::class, 'download_laporan'])->name('download.laporan');

        Route::post('instrumen', [DownloadController::class, 'download_instrumen'])->name('download.instrumen');

        // Daftar Tilik
        Route::post('{jadwalAudit}/daftar_tilik/{unit}/{type}', [DownloadController::class, 'download_daftar_tilik'])->name('download.daftar_tilik');

        // Isi Audit Auditee
        Route::post('{jadwalAudit}/isi_audit_auditee/{unit}/{type}', [DownloadController::class, 'download_isi_audit_auditee'])->name('download.isi_audit_auditee');

        // Daftar Auditee Auditor
        Route::post('{jadwalAudit}/auditee_auditor', [DownloadController::class, 'download_auditee_auditor'])->name('download.auditee_auditor');
    });

    // GPM
    Route::group(['middleware' => 'role:gpm'], function () {
        Route::prefix('gpm')->group(function () {
            // Auditor
            Route::prefix('auditor')->group(function () {
                Route::get('', [GpmAuditorController::class, 'index'])->name('gpm.auditor');
                Route::get('{jadwalAudit}/show', [GpmAuditorController::class, 'show'])->name('gpm.auditor.show');

                // Create
                Route::get('{jadwalAudit}/create/{unit}', [GpmAuditorController::class, 'create'])->name('gpm.auditor.create');
                Route::post('{jadwalAudit}/store/{unit}', [GpmAuditorController::class, 'store'])->name('gpm.auditor.store');

                // Edit
                Route::get('{jadwalAudit}/edit/{unit}', [GpmAuditorController::class, 'edit'])->name('gpm.auditor.edit');
                Route::put('{jadwalAudit}/update/{unit}', [GpmAuditorController::class, 'update'])->name('gpm.auditor.update');
            });
        });
    });

    // Hasil Audit Prodi untuk Dekan dan GPM
    Route::group(['middleware' => 'check_is_gpm_or_dekan'], function () {
        Route::prefix('hasil-audit-prodi')->group(function () {
            Route::get('', [HasilAuditProdiController::class, 'index'])->name('hasil_audit_prodi');
            Route::get('{jadwalAudit}/show', [HasilAuditProdiController::class, 'show'])->name('hasil_audit_prodi.show');

            // Audit Dokumen
            Route::get('{jadwalAudit}/audit_dokumen/{unit}', [HasilAuditProdiController::class, 'audit_dokumen'])->name('hasil_audit_prodi.audit_dokumen');

            // Daftar Tilik
            Route::get('{jadwalAudit}/daftar_tilik/{unit}', [HasilAuditProdiController::class, 'daftar_tilik'])->name('hasil_audit_prodi.daftar_tilik');

            // PTK
            Route::get('{ptk}/ptk', [HasilAuditProdiController::class, 'ptk'])->name('hasil_audit_prodi.ptk');

            // Laporan
            Route::get('{laporan}/laporan', [HasilAuditProdiController::class, 'laporan'])->name('hasil_audit_prodi.laporan');
        });
    });

    // Hasil Audit untuk Rektor
    // Route::group(['middleware' => 'check_is_rektor'], function () {
    //     Route::prefix('dashboard/hasil-audit')->group(function () {
    //         // Lihat
    //         Route::get('', [HasilAuditController::class, 'index'])->name('dashboard.hasil_audit');
    //         Route::get('{jadwalAudit}/show/{type}', [HasilAuditController::class, 'show_rektor'])->name('dashboard.hasil_audit.show');

    //         // Audit Dokumen
    //         Route::get('{jadwalAudit}/audit_dokumen/{unit}/{type}', [HasilAuditController::class, 'audit_dokumen'])->name('dashboard.hasil_audit.audit_dokumen');

    //         // Daftar Tilik
    //         Route::get('{jadwalAudit}/daftar_tilik/{unit}/{type}', [HasilAuditController::class, 'daftar_tilik'])->name('dashboard.hasil_audit.daftar_tilik');

    //         // PTK
    //         Route::get('{ptk}/ptk', [HasilAuditController::class, 'ptk'])->name('dashboard.hasil_audit.ptk');

    //         // Laporan
    //         Route::get('{laporan}/laporan', [HasilAuditController::class, 'laporan'])->name('dashboard.hasil_audit.laporan');
    //     });
    // });
});
