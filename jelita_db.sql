--
-- PostgreSQL database dump
--

-- Dumped from database version 16.1
-- Dumped by pg_dump version 16.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE ONLY public.submenu DROP CONSTRAINT submenu_menu_id_foreign;
ALTER TABLE ONLY public.status_ptk_auditor DROP CONSTRAINT status_ptk_auditor_ptk_id_foreign;
ALTER TABLE ONLY public.status_ptk_auditee DROP CONSTRAINT status_ptk_auditee_ptk_id_foreign;
ALTER TABLE ONLY public.status_laporan DROP CONSTRAINT status_laporan_laporan_id_foreign;
ALTER TABLE ONLY public.status_audit_auditor DROP CONSTRAINT status_audit_auditor_unit_id_foreign;
ALTER TABLE ONLY public.status_audit_auditor DROP CONSTRAINT status_audit_auditor_prodi_id_foreign;
ALTER TABLE ONLY public.status_audit_auditor DROP CONSTRAINT status_audit_auditor_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.status_audit_auditor DROP CONSTRAINT status_audit_auditor_fakultas_id_foreign;
ALTER TABLE ONLY public.status_audit_auditee DROP CONSTRAINT status_audit_auditee_unit_id_foreign;
ALTER TABLE ONLY public.status_audit_auditee DROP CONSTRAINT status_audit_auditee_prodi_id_foreign;
ALTER TABLE ONLY public.status_audit_auditee DROP CONSTRAINT status_audit_auditee_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.status_audit_auditee DROP CONSTRAINT status_audit_auditee_fakultas_id_foreign;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_unit_id_foreign;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_prodi_id_foreign;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_fakultas_id_foreign;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_auditor_penilai_id_foreign;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_auditor_dinilai_id_foreign;
ALTER TABLE ONLY public.standar DROP CONSTRAINT standar_peraturan_id_foreign;
ALTER TABLE ONLY public.setting DROP CONSTRAINT setting_jabatan_id_foreign;
ALTER TABLE ONLY public.role_submenu DROP CONSTRAINT role_submenu_submenu_id_foreign;
ALTER TABLE ONLY public.role_submenu DROP CONSTRAINT role_submenu_role_id_foreign;
ALTER TABLE ONLY public.role_menu DROP CONSTRAINT role_menu_role_id_foreign;
ALTER TABLE ONLY public.role_menu DROP CONSTRAINT role_menu_menu_id_foreign;
ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_role_id_foreign;
ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_permission_id_foreign;
ALTER TABLE ONLY public.ptk DROP CONSTRAINT ptk_unit_id_foreign;
ALTER TABLE ONLY public.ptk DROP CONSTRAINT ptk_prodi_id_foreign;
ALTER TABLE ONLY public.ptk DROP CONSTRAINT ptk_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.ptk_form_rencana DROP CONSTRAINT ptk_form_rencana_ptk_id_foreign;
ALTER TABLE ONLY public.ptk_form_rencana DROP CONSTRAINT ptk_form_rencana_form_id_foreign;
ALTER TABLE ONLY public.ptk_form_rencana DROP CONSTRAINT ptk_form_rencana_auditee_id_foreign;
ALTER TABLE ONLY public.ptk_form DROP CONSTRAINT ptk_form_ptk_id_foreign;
ALTER TABLE ONLY public.ptk_form DROP CONSTRAINT ptk_form_form_id_foreign;
ALTER TABLE ONLY public.ptk_form_deskripsi DROP CONSTRAINT ptk_form_deskripsi_ptk_id_foreign;
ALTER TABLE ONLY public.ptk_form_deskripsi DROP CONSTRAINT ptk_form_deskripsi_form_id_foreign;
ALTER TABLE ONLY public.ptk_form_deskripsi DROP CONSTRAINT ptk_form_deskripsi_auditor_id_foreign;
ALTER TABLE ONLY public.ptk_form DROP CONSTRAINT ptk_form_auditor_id_foreign;
ALTER TABLE ONLY public.ptk_form DROP CONSTRAINT ptk_form_auditee_id_foreign;
ALTER TABLE ONLY public.ptk DROP CONSTRAINT ptk_fakultas_id_foreign;
ALTER TABLE ONLY public.ptk_auditor DROP CONSTRAINT ptk_auditor_ptk_id_foreign;
ALTER TABLE ONLY public.ptk_auditor DROP CONSTRAINT ptk_auditor_auditor_id_foreign;
ALTER TABLE ONLY public.ptk_auditee DROP CONSTRAINT ptk_auditee_ptk_id_foreign;
ALTER TABLE ONLY public.ptk_auditee DROP CONSTRAINT ptk_auditee_auditee_id_foreign;
ALTER TABLE ONLY public.prodi DROP CONSTRAINT prodi_jenjang_id_foreign;
ALTER TABLE ONLY public.prodi DROP CONSTRAINT prodi_fakultas_id_foreign;
ALTER TABLE ONLY public.pasal DROP CONSTRAINT pasal_peraturan_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_unit_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_prodi_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_form_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_fakultas_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_auditor_id_foreign;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_auditee_id_foreign;
ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_unit_id_foreign;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_prodi_id_foreign;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_form_id_foreign;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_fakultas_id_foreign;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_auditee_id_foreign;
ALTER TABLE ONLY public.laporan DROP CONSTRAINT laporan_unit_id_foreign;
ALTER TABLE ONLY public.laporan DROP CONSTRAINT laporan_prodi_id_foreign;
ALTER TABLE ONLY public.laporan DROP CONSTRAINT laporan_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.laporan_form DROP CONSTRAINT laporan_form_laporan_id_foreign;
ALTER TABLE ONLY public.laporan_form DROP CONSTRAINT laporan_form_form_id_foreign;
ALTER TABLE ONLY public.laporan_form DROP CONSTRAINT laporan_form_auditor_id_foreign;
ALTER TABLE ONLY public.laporan DROP CONSTRAINT laporan_fakultas_id_foreign;
ALTER TABLE ONLY public.laporan_auditor DROP CONSTRAINT laporan_auditor_laporan_id_foreign;
ALTER TABLE ONLY public.laporan_auditor DROP CONSTRAINT laporan_auditor_auditor_id_foreign;
ALTER TABLE ONLY public.laporan_auditee DROP CONSTRAINT laporan_auditee_laporan_id_foreign;
ALTER TABLE ONLY public.laporan_auditee DROP CONSTRAINT laporan_auditee_auditee_id_foreign;
ALTER TABLE ONLY public.kategori DROP CONSTRAINT kategori_standar_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_unit_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_prodi_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_kriteria_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_form_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_fakultas_id_foreign;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_auditor_id_foreign;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_unit_id_foreign;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_prodi_id_foreign;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_form_id_foreign;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_fakultas_id_foreign;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_auditee_id_foreign;
ALTER TABLE ONLY public.jabatan_user DROP CONSTRAINT jabatan_user_user_id_foreign;
ALTER TABLE ONLY public.jabatan_user DROP CONSTRAINT jabatan_user_unit_id_foreign;
ALTER TABLE ONLY public.jabatan_user DROP CONSTRAINT jabatan_user_prodi_id_foreign;
ALTER TABLE ONLY public.jabatan_user DROP CONSTRAINT jabatan_user_jabatan_id_foreign;
ALTER TABLE ONLY public.jabatan_user DROP CONSTRAINT jabatan_user_fakultas_id_foreign;
ALTER TABLE ONLY public.jabatan_unit DROP CONSTRAINT jabatan_unit_unit_id_foreign;
ALTER TABLE ONLY public.jabatan_unit DROP CONSTRAINT jabatan_unit_jabatan_id_foreign;
ALTER TABLE ONLY public.instrumen DROP CONSTRAINT instrumen_standar_id_foreign;
ALTER TABLE ONLY public.instrumen DROP CONSTRAINT instrumen_peraturan_id_foreign;
ALTER TABLE ONLY public.instrumen_pasal_ayat DROP CONSTRAINT instrumen_pasal_ayat_pasal_id_foreign;
ALTER TABLE ONLY public.instrumen_pasal_ayat DROP CONSTRAINT instrumen_pasal_ayat_instrumen_id_foreign;
ALTER TABLE ONLY public.instrumen_pasal_ayat DROP CONSTRAINT instrumen_pasal_ayat_ayat_id_foreign;
ALTER TABLE ONLY public.instrumen DROP CONSTRAINT instrumen_level_id_foreign;
ALTER TABLE ONLY public.instrumen_kriteria DROP CONSTRAINT instrumen_kriteria_kriteria_id_foreign;
ALTER TABLE ONLY public.instrumen_kriteria DROP CONSTRAINT instrumen_kriteria_instrumen_id_foreign;
ALTER TABLE ONLY public.instrumen DROP CONSTRAINT instrumen_kategori_id_foreign;
ALTER TABLE ONLY public.instrumen_jenjang DROP CONSTRAINT instrumen_jenjang_unit_id_foreign;
ALTER TABLE ONLY public.instrumen_jenjang DROP CONSTRAINT instrumen_jenjang_prodi_id_foreign;
ALTER TABLE ONLY public.instrumen_jenjang DROP CONSTRAINT instrumen_jenjang_jenjang_id_foreign;
ALTER TABLE ONLY public.instrumen_jenjang DROP CONSTRAINT instrumen_jenjang_instrumen_id_foreign;
ALTER TABLE ONLY public.instrumen DROP CONSTRAINT instrumen_jenis_pertanyaan_id_foreign;
ALTER TABLE ONLY public.instrumen_jabatan DROP CONSTRAINT instrumen_jabatan_jabatan_id_foreign;
ALTER TABLE ONLY public.instrumen_jabatan DROP CONSTRAINT instrumen_jabatan_instrumen_id_foreign;
ALTER TABLE ONLY public.form DROP CONSTRAINT form_jadwal_id_foreign;
ALTER TABLE ONLY public.form DROP CONSTRAINT form_instrumen_id_foreign;
ALTER TABLE ONLY public.berita_acara DROP CONSTRAINT berita_acara_unit_id_foreign;
ALTER TABLE ONLY public.berita_acara DROP CONSTRAINT berita_acara_prodi_id_foreign;
ALTER TABLE ONLY public.berita_acara DROP CONSTRAINT berita_acara_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.berita_acara DROP CONSTRAINT berita_acara_fakultas_id_foreign;
ALTER TABLE ONLY public.berita_acara_auditor DROP CONSTRAINT berita_acara_auditor_berita_acara_id_foreign;
ALTER TABLE ONLY public.berita_acara_auditor DROP CONSTRAINT berita_acara_auditor_auditor_id_foreign;
ALTER TABLE ONLY public.berita_acara_auditee DROP CONSTRAINT berita_acara_auditee_berita_acara_id_foreign;
ALTER TABLE ONLY public.berita_acara_auditee DROP CONSTRAINT berita_acara_auditee_auditee_id_foreign;
ALTER TABLE ONLY public.ayat DROP CONSTRAINT ayat_peraturan_id_foreign;
ALTER TABLE ONLY public.ayat DROP CONSTRAINT ayat_pasal_id_foreign;
ALTER TABLE ONLY public.auditor DROP CONSTRAINT auditor_user_id_foreign;
ALTER TABLE ONLY public.auditor DROP CONSTRAINT auditor_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_user_id_foreign;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_unit_id_foreign;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_prodi_id_foreign;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_jabatan_id_foreign;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_fakultas_id_foreign;
ALTER TABLE ONLY public.auditee_auditor DROP CONSTRAINT auditee_auditor_unit_id_foreign;
ALTER TABLE ONLY public.auditee_auditor DROP CONSTRAINT auditee_auditor_prodi_id_foreign;
ALTER TABLE ONLY public.auditee_auditor DROP CONSTRAINT auditee_auditor_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.auditee_auditor DROP CONSTRAINT auditee_auditor_fakultas_id_foreign;
ALTER TABLE ONLY public.auditee_auditor DROP CONSTRAINT auditee_auditor_auditor_id_foreign;
ALTER TABLE ONLY public.auditee_auditor DROP CONSTRAINT auditee_auditor_auditee_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_unit_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_prodi_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_fakultas_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_auditor_penilai_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_auditor_dinilai_id_foreign;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_assessment_form_id_foreign;
ALTER TABLE ONLY public.assessment_form DROP CONSTRAINT assessment_form_jadwal_audit_id_foreign;
ALTER TABLE ONLY public.assessment_form DROP CONSTRAINT assessment_form_assessment_pertanyaan_id_foreign;
DROP INDEX public.sessions_user_id_index;
DROP INDEX public.sessions_last_activity_index;
DROP INDEX public.model_has_roles_model_id_model_type_index;
DROP INDEX public.model_has_permissions_model_id_model_type_index;
DROP INDEX public.jobs_queue_index;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
ALTER TABLE ONLY public.unit DROP CONSTRAINT unit_pkey;
ALTER TABLE ONLY public.submenu DROP CONSTRAINT submenu_pkey;
ALTER TABLE ONLY public.status_ptk_auditor DROP CONSTRAINT status_ptk_auditor_pkey;
ALTER TABLE ONLY public.status_ptk_auditee DROP CONSTRAINT status_ptk_auditee_pkey;
ALTER TABLE ONLY public.status_laporan DROP CONSTRAINT status_laporan_pkey;
ALTER TABLE ONLY public.status_audit_auditor DROP CONSTRAINT status_audit_auditor_pkey;
ALTER TABLE ONLY public.status_audit_auditee DROP CONSTRAINT status_audit_auditee_pkey;
ALTER TABLE ONLY public.status_assessment DROP CONSTRAINT status_assessment_pkey;
ALTER TABLE ONLY public.standar DROP CONSTRAINT standar_pkey;
ALTER TABLE ONLY public.standar DROP CONSTRAINT standar_kode_unique;
ALTER TABLE ONLY public.setting DROP CONSTRAINT setting_pkey;
ALTER TABLE ONLY public.sessions DROP CONSTRAINT sessions_pkey;
ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_name_unique;
ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_name_guard_name_unique;
ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_pkey;
ALTER TABLE ONLY public.ptk DROP CONSTRAINT ptk_pkey;
ALTER TABLE ONLY public.ptk_form_rencana DROP CONSTRAINT ptk_form_rencana_pkey;
ALTER TABLE ONLY public.ptk_form DROP CONSTRAINT ptk_form_pkey;
ALTER TABLE ONLY public.ptk_form_deskripsi DROP CONSTRAINT ptk_form_deskripsi_pkey;
ALTER TABLE ONLY public.ptk_auditor DROP CONSTRAINT ptk_auditor_pkey;
ALTER TABLE ONLY public.ptk_auditee DROP CONSTRAINT ptk_auditee_pkey;
ALTER TABLE ONLY public.prodi DROP CONSTRAINT prodi_pkey;
ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_name_guard_name_unique;
ALTER TABLE ONLY public.peraturan DROP CONSTRAINT peraturan_pkey;
ALTER TABLE ONLY public.password_reset_tokens DROP CONSTRAINT password_reset_tokens_pkey;
ALTER TABLE ONLY public.pasal DROP CONSTRAINT pasal_pkey;
ALTER TABLE ONLY public.notifikasi DROP CONSTRAINT notifikasi_pkey;
ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
ALTER TABLE ONLY public.menu DROP CONSTRAINT menu_pkey;
ALTER TABLE ONLY public.menu DROP CONSTRAINT menu_menu_unique;
ALTER TABLE ONLY public.link DROP CONSTRAINT link_pkey;
ALTER TABLE ONLY public.level DROP CONSTRAINT level_slug_unique;
ALTER TABLE ONLY public.level DROP CONSTRAINT level_pkey;
ALTER TABLE ONLY public.level DROP CONSTRAINT level_nama_unique;
ALTER TABLE ONLY public.laporan DROP CONSTRAINT laporan_pkey;
ALTER TABLE ONLY public.laporan_form DROP CONSTRAINT laporan_form_pkey;
ALTER TABLE ONLY public.laporan_auditor DROP CONSTRAINT laporan_auditor_pkey;
ALTER TABLE ONLY public.laporan_auditee DROP CONSTRAINT laporan_auditee_pkey;
ALTER TABLE ONLY public.kriteria DROP CONSTRAINT kriteria_slug_unique;
ALTER TABLE ONLY public.kriteria DROP CONSTRAINT kriteria_pkey;
ALTER TABLE ONLY public.kriteria DROP CONSTRAINT kriteria_nama_unique;
ALTER TABLE ONLY public.kategori DROP CONSTRAINT kategori_standar_id_kode_unique;
ALTER TABLE ONLY public.kategori DROP CONSTRAINT kategori_pkey;
ALTER TABLE ONLY public.jobs DROP CONSTRAINT jobs_pkey;
ALTER TABLE ONLY public.job_batches DROP CONSTRAINT job_batches_pkey;
ALTER TABLE ONLY public.jenjang DROP CONSTRAINT jenjang_pkey;
ALTER TABLE ONLY public.jenis_pertanyaan DROP CONSTRAINT jenis_pertanyaan_slug_unique;
ALTER TABLE ONLY public.jenis_pertanyaan DROP CONSTRAINT jenis_pertanyaan_pkey;
ALTER TABLE ONLY public.jenis_pertanyaan DROP CONSTRAINT jenis_pertanyaan_nama_unique;
ALTER TABLE ONLY public.jawaban_auditor DROP CONSTRAINT jawaban_auditor_pkey;
ALTER TABLE ONLY public.jawaban_auditee DROP CONSTRAINT jawaban_auditee_pkey;
ALTER TABLE ONLY public.jadwal_audit DROP CONSTRAINT jadwal_audit_pkey;
ALTER TABLE ONLY public.jabatan DROP CONSTRAINT jabatan_slug_unique;
ALTER TABLE ONLY public.jabatan DROP CONSTRAINT jabatan_pkey;
ALTER TABLE ONLY public.jabatan DROP CONSTRAINT jabatan_nama_unique;
ALTER TABLE ONLY public.instrumen DROP CONSTRAINT instrumen_pkey;
ALTER TABLE ONLY public.form DROP CONSTRAINT form_pkey;
ALTER TABLE ONLY public.fakultas DROP CONSTRAINT fakultas_pkey;
ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_uuid_unique;
ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_pkey;
ALTER TABLE ONLY public.cache DROP CONSTRAINT cache_pkey;
ALTER TABLE ONLY public.cache_locks DROP CONSTRAINT cache_locks_pkey;
ALTER TABLE ONLY public.berita_acara DROP CONSTRAINT berita_acara_pkey;
ALTER TABLE ONLY public.berita_acara_auditor DROP CONSTRAINT berita_acara_auditor_pkey;
ALTER TABLE ONLY public.berita_acara_auditee DROP CONSTRAINT berita_acara_auditee_pkey;
ALTER TABLE ONLY public.ayat DROP CONSTRAINT ayat_pkey;
ALTER TABLE ONLY public.auditor DROP CONSTRAINT auditor_pkey;
ALTER TABLE ONLY public.auditee DROP CONSTRAINT auditee_pkey;
ALTER TABLE ONLY public.assessment_pertanyaan DROP CONSTRAINT assessment_pertanyaan_pkey;
ALTER TABLE ONLY public.assessment_jawaban DROP CONSTRAINT assessment_jawaban_pkey;
ALTER TABLE ONLY public.assessment_form DROP CONSTRAINT assessment_form_pkey;
ALTER TABLE public.submenu ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.status_ptk_auditor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.status_ptk_auditee ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.status_laporan ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.status_audit_auditor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.status_audit_auditee ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.status_assessment ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.standar ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.setting ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.ptk_form_rencana ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.ptk_form_deskripsi ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.ptk_form ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.ptk_auditor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.ptk_auditee ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.peraturan ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.pasal ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.notifikasi ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.menu ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.level ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.laporan_form ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.laporan_auditor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.laporan_auditee ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.kriteria ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.kategori ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.jenjang ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.jenis_pertanyaan ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.jabatan ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.instrumen ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.failed_jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.berita_acara_auditor ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.berita_acara_auditee ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.ayat ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.assessment_pertanyaan ALTER COLUMN id DROP DEFAULT;
ALTER TABLE public.assessment_form ALTER COLUMN id DROP DEFAULT;
DROP TABLE public.users;
DROP TABLE public.unit;
DROP SEQUENCE public.submenu_id_seq;
DROP TABLE public.submenu;
DROP SEQUENCE public.status_ptk_auditor_id_seq;
DROP TABLE public.status_ptk_auditor;
DROP SEQUENCE public.status_ptk_auditee_id_seq;
DROP TABLE public.status_ptk_auditee;
DROP SEQUENCE public.status_laporan_id_seq;
DROP TABLE public.status_laporan;
DROP SEQUENCE public.status_audit_auditor_id_seq;
DROP TABLE public.status_audit_auditor;
DROP SEQUENCE public.status_audit_auditee_id_seq;
DROP TABLE public.status_audit_auditee;
DROP SEQUENCE public.status_assessment_id_seq;
DROP TABLE public.status_assessment;
DROP SEQUENCE public.standar_id_seq;
DROP TABLE public.standar;
DROP SEQUENCE public.setting_id_seq;
DROP TABLE public.setting;
DROP TABLE public.sessions;
DROP SEQUENCE public.roles_id_seq;
DROP TABLE public.roles;
DROP TABLE public.role_submenu;
DROP TABLE public.role_menu;
DROP TABLE public.role_has_permissions;
DROP SEQUENCE public.ptk_form_rencana_id_seq;
DROP TABLE public.ptk_form_rencana;
DROP SEQUENCE public.ptk_form_id_seq;
DROP SEQUENCE public.ptk_form_deskripsi_id_seq;
DROP TABLE public.ptk_form_deskripsi;
DROP TABLE public.ptk_form;
DROP SEQUENCE public.ptk_auditor_id_seq;
DROP TABLE public.ptk_auditor;
DROP SEQUENCE public.ptk_auditee_id_seq;
DROP TABLE public.ptk_auditee;
DROP TABLE public.ptk;
DROP TABLE public.prodi;
DROP SEQUENCE public.permissions_id_seq;
DROP TABLE public.permissions;
DROP SEQUENCE public.peraturan_id_seq;
DROP TABLE public.peraturan;
DROP TABLE public.password_reset_tokens;
DROP SEQUENCE public.pasal_id_seq;
DROP TABLE public.pasal;
DROP SEQUENCE public.notifikasi_id_seq;
DROP TABLE public.notifikasi;
DROP TABLE public.model_has_roles;
DROP TABLE public.model_has_permissions;
DROP SEQUENCE public.migrations_id_seq;
DROP TABLE public.migrations;
DROP SEQUENCE public.menu_id_seq;
DROP TABLE public.menu;
DROP TABLE public.link;
DROP SEQUENCE public.level_id_seq;
DROP TABLE public.level;
DROP SEQUENCE public.laporan_form_id_seq;
DROP TABLE public.laporan_form;
DROP SEQUENCE public.laporan_auditor_id_seq;
DROP TABLE public.laporan_auditor;
DROP SEQUENCE public.laporan_auditee_id_seq;
DROP TABLE public.laporan_auditee;
DROP TABLE public.laporan;
DROP SEQUENCE public.kriteria_id_seq;
DROP TABLE public.kriteria;
DROP SEQUENCE public.kategori_id_seq;
DROP TABLE public.kategori;
DROP SEQUENCE public.jobs_id_seq;
DROP TABLE public.jobs;
DROP TABLE public.job_batches;
DROP SEQUENCE public.jenjang_id_seq;
DROP TABLE public.jenjang;
DROP SEQUENCE public.jenis_pertanyaan_id_seq;
DROP TABLE public.jenis_pertanyaan;
DROP TABLE public.jawaban_auditor;
DROP TABLE public.jawaban_auditee;
DROP TABLE public.jadwal_audit;
DROP TABLE public.jabatan_user;
DROP TABLE public.jabatan_unit;
DROP SEQUENCE public.jabatan_id_seq;
DROP TABLE public.jabatan;
DROP TABLE public.instrumen_pasal_ayat;
DROP TABLE public.instrumen_kriteria;
DROP TABLE public.instrumen_jenjang;
DROP TABLE public.instrumen_jabatan;
DROP SEQUENCE public.instrumen_id_seq;
DROP TABLE public.instrumen;
DROP TABLE public.form;
DROP TABLE public.fakultas;
DROP SEQUENCE public.failed_jobs_id_seq;
DROP TABLE public.failed_jobs;
DROP TABLE public.cache_locks;
DROP TABLE public.cache;
DROP SEQUENCE public.berita_acara_auditor_id_seq;
DROP TABLE public.berita_acara_auditor;
DROP SEQUENCE public.berita_acara_auditee_id_seq;
DROP TABLE public.berita_acara_auditee;
DROP TABLE public.berita_acara;
DROP SEQUENCE public.ayat_id_seq;
DROP TABLE public.ayat;
DROP TABLE public.auditor;
DROP TABLE public.auditee_auditor;
DROP TABLE public.auditee;
DROP SEQUENCE public.assessment_pertanyaan_id_seq;
DROP TABLE public.assessment_pertanyaan;
DROP TABLE public.assessment_jawaban;
DROP SEQUENCE public.assessment_form_id_seq;
DROP TABLE public.assessment_form;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: assessment_form; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assessment_form (
    id bigint NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    assessment_pertanyaan_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.assessment_form OWNER TO postgres;

--
-- Name: assessment_form_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assessment_form_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assessment_form_id_seq OWNER TO postgres;

--
-- Name: assessment_form_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assessment_form_id_seq OWNED BY public.assessment_form.id;


--
-- Name: assessment_jawaban; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assessment_jawaban (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    prodi_id uuid,
    fakultas_id uuid,
    unit_id uuid,
    assessment_form_id bigint NOT NULL,
    auditor_penilai_id uuid NOT NULL,
    auditor_dinilai_id uuid NOT NULL,
    jawaban character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.assessment_jawaban OWNER TO postgres;

--
-- Name: assessment_pertanyaan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.assessment_pertanyaan (
    id bigint NOT NULL,
    pertanyaan text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.assessment_pertanyaan OWNER TO postgres;

--
-- Name: assessment_pertanyaan_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.assessment_pertanyaan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assessment_pertanyaan_id_seq OWNER TO postgres;

--
-- Name: assessment_pertanyaan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.assessment_pertanyaan_id_seq OWNED BY public.assessment_pertanyaan.id;


--
-- Name: auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auditee (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    user_id uuid NOT NULL,
    prodi_id uuid,
    fakultas_id uuid,
    unit_id uuid,
    jabatan_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.auditee OWNER TO postgres;

--
-- Name: auditee_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auditee_auditor (
    jadwal_audit_id uuid NOT NULL,
    auditee_id uuid NOT NULL,
    auditor_id uuid NOT NULL,
    prodi_id uuid,
    fakultas_id uuid,
    unit_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.auditee_auditor OWNER TO postgres;

--
-- Name: auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.auditor (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    user_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.auditor OWNER TO postgres;

--
-- Name: ayat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ayat (
    id bigint NOT NULL,
    peraturan_id bigint NOT NULL,
    pasal_id bigint NOT NULL,
    ayat integer NOT NULL,
    isi text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ayat OWNER TO postgres;

--
-- Name: ayat_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ayat_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ayat_id_seq OWNER TO postgres;

--
-- Name: ayat_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ayat_id_seq OWNED BY public.ayat.id;


--
-- Name: berita_acara; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.berita_acara (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    tgl date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.berita_acara OWNER TO postgres;

--
-- Name: berita_acara_auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.berita_acara_auditee (
    id bigint NOT NULL,
    berita_acara_id uuid NOT NULL,
    auditee_id uuid NOT NULL,
    approve boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.berita_acara_auditee OWNER TO postgres;

--
-- Name: berita_acara_auditee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.berita_acara_auditee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.berita_acara_auditee_id_seq OWNER TO postgres;

--
-- Name: berita_acara_auditee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.berita_acara_auditee_id_seq OWNED BY public.berita_acara_auditee.id;


--
-- Name: berita_acara_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.berita_acara_auditor (
    id bigint NOT NULL,
    berita_acara_id uuid NOT NULL,
    auditor_id uuid NOT NULL,
    approve boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.berita_acara_auditor OWNER TO postgres;

--
-- Name: berita_acara_auditor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.berita_acara_auditor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.berita_acara_auditor_id_seq OWNER TO postgres;

--
-- Name: berita_acara_auditor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.berita_acara_auditor_id_seq OWNED BY public.berita_acara_auditor.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: fakultas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.fakultas (
    id uuid NOT NULL,
    nama character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.fakultas OWNER TO postgres;

--
-- Name: form; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.form (
    id uuid NOT NULL,
    jadwal_id uuid NOT NULL,
    instrumen_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.form OWNER TO postgres;

--
-- Name: instrumen; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.instrumen (
    id bigint NOT NULL,
    peraturan_id bigint,
    standar_id bigint NOT NULL,
    kategori_id bigint NOT NULL,
    level_id bigint NOT NULL,
    jenis_pertanyaan_id bigint NOT NULL,
    kode character varying(255),
    pernyataan text NOT NULL,
    indikator text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.instrumen OWNER TO postgres;

--
-- Name: instrumen_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.instrumen_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.instrumen_id_seq OWNER TO postgres;

--
-- Name: instrumen_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.instrumen_id_seq OWNED BY public.instrumen.id;


--
-- Name: instrumen_jabatan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.instrumen_jabatan (
    instrumen_id bigint NOT NULL,
    jabatan_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.instrumen_jabatan OWNER TO postgres;

--
-- Name: instrumen_jenjang; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.instrumen_jenjang (
    instrumen_id bigint NOT NULL,
    jenjang_id bigint,
    prodi_id uuid,
    unit_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.instrumen_jenjang OWNER TO postgres;

--
-- Name: instrumen_kriteria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.instrumen_kriteria (
    instrumen_id bigint NOT NULL,
    kriteria_id bigint NOT NULL,
    isi text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.instrumen_kriteria OWNER TO postgres;

--
-- Name: instrumen_pasal_ayat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.instrumen_pasal_ayat (
    instrumen_id bigint NOT NULL,
    pasal_id bigint,
    ayat_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.instrumen_pasal_ayat OWNER TO postgres;

--
-- Name: jabatan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jabatan (
    id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    unik boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT jabatan_type_check CHECK (((type)::text = ANY ((ARRAY['prodi'::character varying, 'fakultas'::character varying, 'universitas'::character varying])::text[])))
);


ALTER TABLE public.jabatan OWNER TO postgres;

--
-- Name: jabatan_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jabatan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jabatan_id_seq OWNER TO postgres;

--
-- Name: jabatan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jabatan_id_seq OWNED BY public.jabatan.id;


--
-- Name: jabatan_unit; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jabatan_unit (
    jabatan_id bigint NOT NULL,
    unit_id uuid NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jabatan_unit OWNER TO postgres;

--
-- Name: jabatan_user; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jabatan_user (
    user_id uuid NOT NULL,
    jabatan_id bigint NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jabatan_user OWNER TO postgres;

--
-- Name: jadwal_audit; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jadwal_audit (
    id uuid NOT NULL,
    jadwal character varying(255) NOT NULL,
    tgl_mulai date NOT NULL,
    tgl_selesai date NOT NULL,
    import boolean DEFAULT false NOT NULL,
    fitur_auditor boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jadwal_audit OWNER TO postgres;

--
-- Name: jawaban_auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jawaban_auditee (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditee_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    jawaban text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jawaban_auditee OWNER TO postgres;

--
-- Name: jawaban_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jawaban_auditor (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditor_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    kriteria_id bigint NOT NULL,
    catatan text,
    daftar_tilik boolean NOT NULL,
    ptk boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jawaban_auditor OWNER TO postgres;

--
-- Name: jenis_pertanyaan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jenis_pertanyaan (
    id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jenis_pertanyaan OWNER TO postgres;

--
-- Name: jenis_pertanyaan_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jenis_pertanyaan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jenis_pertanyaan_id_seq OWNER TO postgres;

--
-- Name: jenis_pertanyaan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jenis_pertanyaan_id_seq OWNED BY public.jenis_pertanyaan.id;


--
-- Name: jenjang; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jenjang (
    id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.jenjang OWNER TO postgres;

--
-- Name: jenjang_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jenjang_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jenjang_id_seq OWNER TO postgres;

--
-- Name: jenjang_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jenjang_id_seq OWNED BY public.jenjang.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: kategori; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kategori (
    id bigint NOT NULL,
    standar_id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    kode character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.kategori OWNER TO postgres;

--
-- Name: kategori_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.kategori_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.kategori_id_seq OWNER TO postgres;

--
-- Name: kategori_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.kategori_id_seq OWNED BY public.kategori.id;


--
-- Name: kriteria; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.kriteria (
    id bigint NOT NULL,
    nama text NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.kriteria OWNER TO postgres;

--
-- Name: kriteria_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.kriteria_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.kriteria_id_seq OWNER TO postgres;

--
-- Name: kriteria_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.kriteria_id_seq OWNED BY public.kriteria.id;


--
-- Name: laporan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.laporan (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    tgl date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.laporan OWNER TO postgres;

--
-- Name: laporan_auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.laporan_auditee (
    id bigint NOT NULL,
    laporan_id uuid NOT NULL,
    auditee_id uuid NOT NULL,
    approve boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.laporan_auditee OWNER TO postgres;

--
-- Name: laporan_auditee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.laporan_auditee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.laporan_auditee_id_seq OWNER TO postgres;

--
-- Name: laporan_auditee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.laporan_auditee_id_seq OWNED BY public.laporan_auditee.id;


--
-- Name: laporan_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.laporan_auditor (
    id bigint NOT NULL,
    laporan_id uuid NOT NULL,
    auditor_id uuid NOT NULL,
    approve boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.laporan_auditor OWNER TO postgres;

--
-- Name: laporan_auditor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.laporan_auditor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.laporan_auditor_id_seq OWNER TO postgres;

--
-- Name: laporan_auditor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.laporan_auditor_id_seq OWNED BY public.laporan_auditor.id;


--
-- Name: laporan_form; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.laporan_form (
    id bigint NOT NULL,
    laporan_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditor_id uuid,
    kelebihan text,
    ruang_peningkatan text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.laporan_form OWNER TO postgres;

--
-- Name: laporan_form_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.laporan_form_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.laporan_form_id_seq OWNER TO postgres;

--
-- Name: laporan_form_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.laporan_form_id_seq OWNED BY public.laporan_form.id;


--
-- Name: level; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.level (
    id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.level OWNER TO postgres;

--
-- Name: level_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.level_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.level_id_seq OWNER TO postgres;

--
-- Name: level_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.level_id_seq OWNED BY public.level.id;


--
-- Name: link; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.link (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditee_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    link text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.link OWNER TO postgres;

--
-- Name: menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.menu (
    id bigint NOT NULL,
    menu character varying(255) NOT NULL,
    status boolean NOT NULL,
    route character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.menu OWNER TO postgres;

--
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menu_id_seq OWNER TO postgres;

--
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.menu_id_seq OWNED BY public.menu.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO postgres;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO postgres;

--
-- Name: notifikasi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notifikasi (
    id bigint NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    form_id uuid,
    auditor_id uuid,
    auditee_id uuid,
    pesan character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT notifikasi_status_check CHECK (((status)::text = ANY ((ARRAY['terkirim'::character varying, 'diterima'::character varying, 'selesai'::character varying])::text[])))
);


ALTER TABLE public.notifikasi OWNER TO postgres;

--
-- Name: notifikasi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.notifikasi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notifikasi_id_seq OWNER TO postgres;

--
-- Name: notifikasi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notifikasi_id_seq OWNED BY public.notifikasi.id;


--
-- Name: pasal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pasal (
    id bigint NOT NULL,
    peraturan_id bigint NOT NULL,
    pasal integer NOT NULL,
    isi text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.pasal OWNER TO postgres;

--
-- Name: pasal_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pasal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pasal_id_seq OWNER TO postgres;

--
-- Name: pasal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pasal_id_seq OWNED BY public.pasal.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: peraturan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.peraturan (
    id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    tahun integer NOT NULL,
    status boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.peraturan OWNER TO postgres;

--
-- Name: peraturan_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.peraturan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.peraturan_id_seq OWNER TO postgres;

--
-- Name: peraturan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.peraturan_id_seq OWNED BY public.peraturan.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    "group" character varying(255),
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO postgres;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO postgres;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: prodi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.prodi (
    id uuid NOT NULL,
    jenjang_id bigint NOT NULL,
    fakultas_id uuid NOT NULL,
    nama character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.prodi OWNER TO postgres;

--
-- Name: ptk; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ptk (
    id uuid NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    fakultas_id uuid,
    prodi_id uuid,
    unit_id uuid,
    tgl date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ptk OWNER TO postgres;

--
-- Name: ptk_auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ptk_auditee (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    auditee_id uuid NOT NULL,
    approve boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ptk_auditee OWNER TO postgres;

--
-- Name: ptk_auditee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ptk_auditee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ptk_auditee_id_seq OWNER TO postgres;

--
-- Name: ptk_auditee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ptk_auditee_id_seq OWNED BY public.ptk_auditee.id;


--
-- Name: ptk_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ptk_auditor (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    auditor_id uuid NOT NULL,
    approve boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ptk_auditor OWNER TO postgres;

--
-- Name: ptk_auditor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ptk_auditor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ptk_auditor_id_seq OWNER TO postgres;

--
-- Name: ptk_auditor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ptk_auditor_id_seq OWNED BY public.ptk_auditor.id;


--
-- Name: ptk_form; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ptk_form (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditor_id uuid,
    auditee_id uuid,
    analisis text,
    akibat text,
    target text,
    kategori_temuan character varying(255),
    pic text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT ptk_form_kategori_temuan_check CHECK (((kategori_temuan)::text = ANY ((ARRAY['observasi'::character varying, 'minor'::character varying, 'mayor'::character varying])::text[])))
);


ALTER TABLE public.ptk_form OWNER TO postgres;

--
-- Name: ptk_form_deskripsi; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ptk_form_deskripsi (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditor_id uuid,
    deskripsi text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ptk_form_deskripsi OWNER TO postgres;

--
-- Name: ptk_form_deskripsi_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ptk_form_deskripsi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ptk_form_deskripsi_id_seq OWNER TO postgres;

--
-- Name: ptk_form_deskripsi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ptk_form_deskripsi_id_seq OWNED BY public.ptk_form_deskripsi.id;


--
-- Name: ptk_form_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ptk_form_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ptk_form_id_seq OWNER TO postgres;

--
-- Name: ptk_form_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ptk_form_id_seq OWNED BY public.ptk_form.id;


--
-- Name: ptk_form_rencana; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ptk_form_rencana (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    form_id uuid NOT NULL,
    auditee_id uuid,
    rencana text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ptk_form_rencana OWNER TO postgres;

--
-- Name: ptk_form_rencana_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ptk_form_rencana_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ptk_form_rencana_id_seq OWNER TO postgres;

--
-- Name: ptk_form_rencana_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ptk_form_rencana_id_seq OWNED BY public.ptk_form_rencana.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO postgres;

--
-- Name: role_menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_menu (
    role_id bigint NOT NULL,
    menu_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role_menu OWNER TO postgres;

--
-- Name: role_submenu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_submenu (
    role_id bigint NOT NULL,
    submenu_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role_submenu OWNER TO postgres;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id uuid,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- Name: setting; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.setting (
    id bigint NOT NULL,
    nama_setting character varying(255) NOT NULL,
    jabatan_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT setting_nama_setting_check CHECK (((nama_setting)::text = ANY ((ARRAY['prodi'::character varying, 'fakultas'::character varying, 'universitas'::character varying])::text[])))
);


ALTER TABLE public.setting OWNER TO postgres;

--
-- Name: setting_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.setting_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.setting_id_seq OWNER TO postgres;

--
-- Name: setting_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.setting_id_seq OWNED BY public.setting.id;


--
-- Name: standar; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.standar (
    id bigint NOT NULL,
    peraturan_id bigint NOT NULL,
    nama character varying(255) NOT NULL,
    kode character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.standar OWNER TO postgres;

--
-- Name: standar_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.standar_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.standar_id_seq OWNER TO postgres;

--
-- Name: standar_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.standar_id_seq OWNED BY public.standar.id;


--
-- Name: status_assessment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status_assessment (
    id bigint NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    prodi_id uuid,
    fakultas_id uuid,
    unit_id uuid,
    auditor_penilai_id uuid NOT NULL,
    auditor_dinilai_id uuid NOT NULL,
    status character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT status_assessment_status_check CHECK (((status)::text = 'selesai'::text))
);


ALTER TABLE public.status_assessment OWNER TO postgres;

--
-- Name: status_assessment_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_assessment_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_assessment_id_seq OWNER TO postgres;

--
-- Name: status_assessment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_assessment_id_seq OWNED BY public.status_assessment.id;


--
-- Name: status_audit_auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status_audit_auditee (
    id bigint NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    prodi_id uuid,
    fakultas_id uuid,
    unit_id uuid,
    status character varying(255) DEFAULT 'not_started'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT status_audit_auditee_status_check CHECK (((status)::text = ANY ((ARRAY['not_started'::character varying, 'in_progress'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.status_audit_auditee OWNER TO postgres;

--
-- Name: status_audit_auditee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_audit_auditee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_audit_auditee_id_seq OWNER TO postgres;

--
-- Name: status_audit_auditee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_audit_auditee_id_seq OWNED BY public.status_audit_auditee.id;


--
-- Name: status_audit_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status_audit_auditor (
    id bigint NOT NULL,
    jadwal_audit_id uuid NOT NULL,
    prodi_id uuid,
    fakultas_id uuid,
    unit_id uuid,
    status character varying(255) DEFAULT 'not_started'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT status_audit_auditor_status_check CHECK (((status)::text = ANY ((ARRAY['not_started'::character varying, 'in_progress'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.status_audit_auditor OWNER TO postgres;

--
-- Name: status_audit_auditor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_audit_auditor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_audit_auditor_id_seq OWNER TO postgres;

--
-- Name: status_audit_auditor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_audit_auditor_id_seq OWNED BY public.status_audit_auditor.id;


--
-- Name: status_laporan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status_laporan (
    id bigint NOT NULL,
    laporan_id uuid NOT NULL,
    status character varying(255) DEFAULT 'not_started'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT status_laporan_status_check CHECK (((status)::text = ANY ((ARRAY['not_started'::character varying, 'in_progress'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.status_laporan OWNER TO postgres;

--
-- Name: status_laporan_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_laporan_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_laporan_id_seq OWNER TO postgres;

--
-- Name: status_laporan_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_laporan_id_seq OWNED BY public.status_laporan.id;


--
-- Name: status_ptk_auditee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status_ptk_auditee (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    status character varying(255) DEFAULT 'not_started'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT status_ptk_auditee_status_check CHECK (((status)::text = ANY ((ARRAY['not_started'::character varying, 'in_progress'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.status_ptk_auditee OWNER TO postgres;

--
-- Name: status_ptk_auditee_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_ptk_auditee_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_ptk_auditee_id_seq OWNER TO postgres;

--
-- Name: status_ptk_auditee_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_ptk_auditee_id_seq OWNED BY public.status_ptk_auditee.id;


--
-- Name: status_ptk_auditor; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status_ptk_auditor (
    id bigint NOT NULL,
    ptk_id uuid NOT NULL,
    status character varying(255) DEFAULT 'not_started'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT status_ptk_auditor_status_check CHECK (((status)::text = ANY ((ARRAY['not_started'::character varying, 'in_progress'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.status_ptk_auditor OWNER TO postgres;

--
-- Name: status_ptk_auditor_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_ptk_auditor_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_ptk_auditor_id_seq OWNER TO postgres;

--
-- Name: status_ptk_auditor_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_ptk_auditor_id_seq OWNED BY public.status_ptk_auditor.id;


--
-- Name: submenu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.submenu (
    id bigint NOT NULL,
    menu_id bigint NOT NULL,
    submenu character varying(255) NOT NULL,
    status boolean NOT NULL,
    route character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.submenu OWNER TO postgres;

--
-- Name: submenu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.submenu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.submenu_id_seq OWNER TO postgres;

--
-- Name: submenu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.submenu_id_seq OWNED BY public.submenu.id;


--
-- Name: unit; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.unit (
    id uuid NOT NULL,
    nama character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.unit OWNER TO postgres;

--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id uuid NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    no_telepon character varying(255),
    gauth_id character varying(255),
    gauth_type character varying(255),
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: assessment_form id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_form ALTER COLUMN id SET DEFAULT nextval('public.assessment_form_id_seq'::regclass);


--
-- Name: assessment_pertanyaan id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_pertanyaan ALTER COLUMN id SET DEFAULT nextval('public.assessment_pertanyaan_id_seq'::regclass);


--
-- Name: ayat id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ayat ALTER COLUMN id SET DEFAULT nextval('public.ayat_id_seq'::regclass);


--
-- Name: berita_acara_auditee id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditee ALTER COLUMN id SET DEFAULT nextval('public.berita_acara_auditee_id_seq'::regclass);


--
-- Name: berita_acara_auditor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditor ALTER COLUMN id SET DEFAULT nextval('public.berita_acara_auditor_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: instrumen id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen ALTER COLUMN id SET DEFAULT nextval('public.instrumen_id_seq'::regclass);


--
-- Name: jabatan id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan ALTER COLUMN id SET DEFAULT nextval('public.jabatan_id_seq'::regclass);


--
-- Name: jenis_pertanyaan id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jenis_pertanyaan ALTER COLUMN id SET DEFAULT nextval('public.jenis_pertanyaan_id_seq'::regclass);


--
-- Name: jenjang id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jenjang ALTER COLUMN id SET DEFAULT nextval('public.jenjang_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: kategori id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kategori ALTER COLUMN id SET DEFAULT nextval('public.kategori_id_seq'::regclass);


--
-- Name: kriteria id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kriteria ALTER COLUMN id SET DEFAULT nextval('public.kriteria_id_seq'::regclass);


--
-- Name: laporan_auditee id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditee ALTER COLUMN id SET DEFAULT nextval('public.laporan_auditee_id_seq'::regclass);


--
-- Name: laporan_auditor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditor ALTER COLUMN id SET DEFAULT nextval('public.laporan_auditor_id_seq'::regclass);


--
-- Name: laporan_form id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_form ALTER COLUMN id SET DEFAULT nextval('public.laporan_form_id_seq'::regclass);


--
-- Name: level id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.level ALTER COLUMN id SET DEFAULT nextval('public.level_id_seq'::regclass);


--
-- Name: menu id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu ALTER COLUMN id SET DEFAULT nextval('public.menu_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: notifikasi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi ALTER COLUMN id SET DEFAULT nextval('public.notifikasi_id_seq'::regclass);


--
-- Name: pasal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pasal ALTER COLUMN id SET DEFAULT nextval('public.pasal_id_seq'::regclass);


--
-- Name: peraturan id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.peraturan ALTER COLUMN id SET DEFAULT nextval('public.peraturan_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: ptk_auditee id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditee ALTER COLUMN id SET DEFAULT nextval('public.ptk_auditee_id_seq'::regclass);


--
-- Name: ptk_auditor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditor ALTER COLUMN id SET DEFAULT nextval('public.ptk_auditor_id_seq'::regclass);


--
-- Name: ptk_form id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form ALTER COLUMN id SET DEFAULT nextval('public.ptk_form_id_seq'::regclass);


--
-- Name: ptk_form_deskripsi id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_deskripsi ALTER COLUMN id SET DEFAULT nextval('public.ptk_form_deskripsi_id_seq'::regclass);


--
-- Name: ptk_form_rencana id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_rencana ALTER COLUMN id SET DEFAULT nextval('public.ptk_form_rencana_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: setting id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.setting ALTER COLUMN id SET DEFAULT nextval('public.setting_id_seq'::regclass);


--
-- Name: standar id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standar ALTER COLUMN id SET DEFAULT nextval('public.standar_id_seq'::regclass);


--
-- Name: status_assessment id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment ALTER COLUMN id SET DEFAULT nextval('public.status_assessment_id_seq'::regclass);


--
-- Name: status_audit_auditee id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditee ALTER COLUMN id SET DEFAULT nextval('public.status_audit_auditee_id_seq'::regclass);


--
-- Name: status_audit_auditor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditor ALTER COLUMN id SET DEFAULT nextval('public.status_audit_auditor_id_seq'::regclass);


--
-- Name: status_laporan id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_laporan ALTER COLUMN id SET DEFAULT nextval('public.status_laporan_id_seq'::regclass);


--
-- Name: status_ptk_auditee id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_ptk_auditee ALTER COLUMN id SET DEFAULT nextval('public.status_ptk_auditee_id_seq'::regclass);


--
-- Name: status_ptk_auditor id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_ptk_auditor ALTER COLUMN id SET DEFAULT nextval('public.status_ptk_auditor_id_seq'::regclass);


--
-- Name: submenu id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.submenu ALTER COLUMN id SET DEFAULT nextval('public.submenu_id_seq'::regclass);


--
-- Data for Name: assessment_form; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assessment_form (id, jadwal_audit_id, assessment_pertanyaan_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: assessment_jawaban; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assessment_jawaban (id, jadwal_audit_id, prodi_id, fakultas_id, unit_id, assessment_form_id, auditor_penilai_id, auditor_dinilai_id, jawaban, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: assessment_pertanyaan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.assessment_pertanyaan (id, pertanyaan, created_at, updated_at) FROM stdin;
1	Rekan auditor memenuhi kode etik (integritas, obyektifitas, kerahasiaan, kompeten) selama bertugas audit mutu internal.	2024-10-15 06:13:14	2024-10-15 06:13:14
2	Rekan auditor telah melakukan audit dokumen sebelum melakukan audit lapangan.	2024-10-15 06:13:14	2024-10-15 06:13:14
3	Rekan auditor mampu berkomunikasi dengan baik kepada pihak yang diaudit dan tim auditor lainnya.	2024-10-15 06:13:14	2024-10-15 06:13:14
4	Rekan auditor berkontribusi dan mampu bekerja sama dalam tim audit mutu internal.	2024-10-15 06:13:14	2024-10-15 06:13:14
5	Rekan auditor menunjukkan kedisiplinan dalam menjalankan tugas audit mutu internal.	2024-10-15 06:13:14	2024-10-15 06:13:14
6	Rekan auditor berpenampilan yang baik, rapi, dan sopan.	2024-10-15 06:13:14	2024-10-15 06:13:14
\.


--
-- Data for Name: auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auditee (id, jadwal_audit_id, user_id, prodi_id, fakultas_id, unit_id, jabatan_id, created_at, updated_at) FROM stdin;
9d3fc8e8-af34-4575-98cd-556d915e7934	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ab-4508-4e14-881b-d98970ff7681	9d3fc7a8-28cd-4565-aabb-1a3fae335c44	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-b1ee-4aea-927e-f2d60b28f6f2	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ad-3960-47c0-93c7-9d629aae763c	9d3fc7a8-2b05-4b26-ab09-4a7740b99042	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-b3f4-4c8d-aa15-baa228c4e1a8	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7af-2191-4941-804c-bef64c7492a2	9d3fc7a8-2d1c-48d7-a98d-a8370a41e314	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-b646-4757-b68c-a5e77d7604ec	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7b1-13bc-4f0b-a4aa-25c9cca8d210	9d3fc7a8-2efe-48ef-adc5-744780322464	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-b99d-4215-8d8a-cb65d2b03e05	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7b2-f07d-4549-9f90-a60523483452	9d3fc7a8-30da-44ba-ae1c-0fa3b7350325	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-bc93-410b-ba21-fd2532c2541e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7b4-d08a-4049-9f58-3a2e0cb9cf77	9d3fc7a8-328c-41cf-98cf-02468eeaa008	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-bfbe-43b6-bdc4-3c7a10d2325d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7b6-b319-41f4-abf1-47d937cd0438	9d3fc7a8-33f4-4c61-b754-0ed6aece001a	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-c23f-4050-aabf-422a86ffb838	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7b8-8c49-4bc1-a2b4-dfa5b2ff362f	9d3fc7a8-355d-40fc-aefa-b461dc31ba98	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-c488-448c-9f66-df0b2733888c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ba-75e8-4887-9d5f-ee5252aba7c0	9d3fc7a8-38a4-4600-bb49-0aa308ace0d8	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-c6b7-4778-bd28-abcadba6cfe4	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7bc-693a-47ad-887d-f9d35d6bb086	9d3fc7a8-3ae3-4483-86ab-d7f750eb0c0f	\N	\N	6	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-cb52-486b-a14a-dfa0e1dddd49	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7be-53cf-41b2-bb07-43761fe5da44	9d3fc7a8-3cf7-4aca-bf7e-9b8877a9736a	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-cd47-48ee-99c8-f69a938b9c78	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7c0-42cf-47ee-8261-eb8a6b43612a	9d3fc7a8-3ef8-4e0e-9c18-4b7c0f687d72	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-cf7b-4004-a367-5956e07df3e7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7c2-39c8-4b2d-8769-5c943686e6cd	9d3fc7a8-413e-4f58-8147-6f509e469ec3	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-d179-4161-83af-be2a9d80ccf9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7c4-27c6-4c65-8888-2dac08a3e200	9d3fc7a8-44bc-4bf7-bcef-a0a4d67139a2	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-d364-4142-a268-f91b32d94092	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7c6-1ed1-491b-b29b-1ff8a6dd3063	9d3fc7a8-4763-4c93-9175-8478a550c115	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-d570-4138-84e5-45acccc9f1fa	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7c9-4b61-4765-9f1b-9d8a49639c3f	9d3fc7a8-490f-48ac-b3ee-a9ea4c4c401f	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-d731-442c-a1ad-2da8bbe0eaba	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7cb-437e-4190-a761-03fc33519b82	9d3fc7a8-4b67-4585-9a1d-bde204d5f87c	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-d948-4bf9-b945-eddd4968faf1	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7cd-49b2-4305-8972-3b0e50c4d15d	9d3fc7a8-4d4c-4c55-8467-ca5c1ffabd64	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-db81-43cf-882c-74b78caf2b70	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7cf-510d-41b8-930e-d189d72bdbc1	9d3fc7a8-4f33-4521-9a1c-cbc04bcdd43f	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-ddde-4968-a2ef-aa6d26cd3ef5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7d1-3fa9-44b8-be06-1945c0e8d23f	9d3fc7a8-50c1-4ee5-a5e1-3089cb847c93	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-e129-47b0-b27f-e7b249acfb78	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7d3-2dc0-4b42-a57a-6b1001b51b85	9d3fc7a8-5263-4581-805b-1f2c4a659b6b	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-e6fb-49c7-9eaa-08f45f701ef0	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7d5-25b9-4c2b-9d53-5007b940fa27	9d3fc7a8-53d9-455f-b0b8-7cd3f200b98a	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-e966-4fe8-b1a5-881b7c74428c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7d7-0b87-41fe-95cf-8d421f50c28c	9d3fc7a8-5574-402f-a401-c7591b0c5b02	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-ebf5-4d28-baf0-e6c0bfdd1af1	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7d8-e7b5-4f36-b880-9214dbd9af06	9d3fc7a8-56fc-47a5-9d5c-1f5351161414	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-ee6b-4337-8744-f44f282aed82	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7da-c06d-4973-91e9-dd93bb3410dc	9d3fc7a8-58f8-4b59-833b-8d5e6c4f34ec	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-f099-4a17-aa47-d74e09b31011	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7dc-a1ce-45a5-a1b2-e10a81099253	9d3fc7a8-5abf-42bf-9ae5-0dbb5fc152b9	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-f247-4e4c-9012-c501c23e633e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7de-8bf8-4680-9b2d-dcc30f75e3c8	9d3fc7a8-5c6e-4fe9-af18-9a43605787a4	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-f40d-45f4-891f-8a476370bc0b	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7e0-ab70-457e-b9c6-a54f44bb15ff	9d3fc7a8-5df8-4ac7-b46c-a52ba9d4625c	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-f60c-49d5-ae87-22ffd3416293	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7e2-8ad2-4ad1-958e-0795823332ed	9d3fc7a8-6058-45a8-a317-ff25014630fb	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-f7e8-4294-8920-763c29b7ca19	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7e4-6dae-43e2-ade5-3679c5a6fae1	9d3fc7a8-63ab-468e-b289-5dd2af10aba2	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-f995-4d35-a3c3-c4495564787e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7e6-500e-4416-af22-dc69353b15f7	9d3fc7a8-6603-4e8f-b5a1-4b957c4403e5	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-fb80-47b0-bd8a-e6f5c07312d2	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7e8-3273-4598-ad83-1942e91bc2f4	9d3fc7a8-6869-4745-9022-a9937f5c3fc7	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-fd0d-4fbd-b1d4-78d24538ac7f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ea-0ee1-4b3e-ae0a-b3f3f1d2a713	9d3fc7a8-6a76-4930-8414-4b2f1a4514ba	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e8-fee8-414d-aab0-1a59306e6771	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ec-7e9d-4560-983b-8502af95b076	9d3fc7a8-6c7c-4716-b019-a53e5133fad8	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-00d7-4b7b-8921-d1712e939f3a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ee-9c99-4218-b621-e6489d0e75d1	9d3fc7a8-6e28-48ca-b772-6760433eae85	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-03f9-4715-92bb-13a563e179a3	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7f0-7f13-4ce0-9f46-11fa2c7f10ba	9d3fc7a8-6ff2-43ba-9146-1dadd8e445bb	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-05fa-474e-b558-891d6141c002	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7f2-65c8-4f4f-9293-0a2c369f45a8	9d3fc7a8-719d-4736-9240-b8f7c9a58713	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-0873-449d-a04b-1bd95909e28a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7f4-3d32-4076-ba73-cffe6a9b133c	9d3fc7a8-734a-4e29-88a1-95442b32871b	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-0b20-4b8a-b179-fa4b9942e9b9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7f6-1ead-42d6-9443-5bcb3c563786	9d3fc7a8-74e4-4224-bd5f-61335120a5f8	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-0e19-43a7-a906-3a5161afc673	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7f8-0ee3-4764-9f18-188381a75e0c	9d3fc7a8-7697-4daf-958a-e053c880f951	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-0ffb-4b4f-b42c-8faf82922afa	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7f9-ed00-43f2-8e7f-38bc19033be1	9d3fc7a8-7819-4e69-aad1-2241ef092850	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-1226-4ad4-a528-faf5cd3dc230	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7fb-c81b-4da0-a889-3ec022335988	9d3fc7a8-79c1-44ba-92c0-db053e567c8c	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-145c-4cb3-a385-01cd9bc132ba	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7fd-a6af-42c1-9bd6-fe5035255580	9d3fc7a8-7b32-4abf-b6b8-71fa27359e31	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-1634-492c-a0e5-557b88211b84	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc7ff-8d8e-4651-92ad-81f8f7b041a6	9d3fc7a8-7cda-41be-9a00-f2aba9aec153	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-1807-49fa-8934-128978765f19	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc801-8123-4fc4-a873-7650e3abd8e8	9d3fc7a8-7e7d-479b-a26b-72f5613ce0de	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-19a5-4c7c-b834-a498117eaad6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc803-79ea-40cb-a4e1-ff0d57f2f34f	9d3fc7a8-8009-4889-b876-5eb4c8541c5f	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-1b95-48af-a970-2b8d6d45eed5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc806-5ead-4f09-9afc-5550802bb678	9d3fc7a8-818c-42e7-919e-3dbb5a1a5e35	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-1d6d-44b3-842b-6c519d23f9d7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc808-6fdc-4f99-83c2-3f0231f90ebf	9d3fc7a8-839c-4d72-97c2-2d2fdc4731b8	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-1f88-43fa-945c-0de6e24ca73f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc80a-5b27-44c6-86e4-dda699dbc49d	9d3fc7a8-855c-4a03-9e2c-b4f0b21a4db7	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-2270-46c0-9e7c-8c22d68a4fc5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc80c-578c-4071-9d22-ddff58ad362d	9d3fc7a8-8922-4919-8d37-940b9300f51a	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-2482-493f-8fad-a2b6997bbb5c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc80e-4224-472f-836c-ba8cee2ba284	9d3fc7a8-8b3d-43e7-b333-ae0684965284	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-268d-4044-a07b-4437ad479cbd	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc810-2ec1-44dc-9e4b-d56081fc550a	9d3fc7a8-8d3e-4ada-9363-a82372be1a8d	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-28bc-4db9-af49-e09f0c13e255	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc812-17f4-4c32-97cf-978da054fa4f	9d3fc7a8-8f54-493d-bb62-e4a61a404717	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-2aaf-45d5-a553-3a1e4ef2d3f0	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc813-eeb9-46ed-9b26-e621ffe33096	9d3fc7a8-91c0-4f41-b4ba-de2a9940c52f	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-2ca8-45d1-952c-fd3f0a73a407	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc815-c0d1-4fb5-8fa0-5099a8540dd5	9d3fc7a8-942c-4ea3-93f5-92eeaaacaae2	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-2f7d-46d0-b2fa-b9e20f36670d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc817-aaf5-408d-b60d-d6997313ee50	9d3fc7a8-9608-4049-9468-f7a24c074767	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-31f6-4c3c-8de5-67db1766297f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc819-84af-4815-b18a-3404d438ec1a	9d3fc7a8-97fd-456c-b005-97dbdb9e6c22	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-34db-4c5e-957e-238a81553ce6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc81b-6765-487e-b291-9510340c55e4	9d3fc7a8-9a30-46d2-896d-14dbb8790795	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-37bb-4c65-899b-10d3b412926b	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc81d-51e8-46c9-96e1-859c80259d6a	9d3fc7a8-9c6d-45de-8530-9c6b551c6558	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-3a24-46d3-8a0c-530ee8e9ff05	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc81f-3681-40cd-9923-06ed5c7d3af7	9d3fc7a8-9e43-4611-b3b1-d4a5d5b22454	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-3c54-4631-80af-1f56127f86cb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc821-198c-48d1-89dc-71c11889f5ac	9d3fc7a8-9ffa-422b-a490-7189bb13ded1	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-3e57-46cd-8a6c-37a19b0b0923	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc823-e09f-4434-aa21-274e4bc4b3d3	9d3fc7a8-a259-442e-ae35-f3a15504d589	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-405a-4ccd-b1f5-9288f18a3e30	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc825-ef70-4ede-97f4-a51e68e848a3	9d3fc7a8-a44e-4999-830d-2864740397aa	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-42bf-4e69-b434-1c8c303bd161	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc827-dd0a-4986-8d58-2bd6bb7c6d57	9d3fc7a8-a658-480e-a522-48e3fb8e1db4	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-44aa-4ce6-a5ab-7835fb181d15	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc829-d05c-4391-ac7e-0103875b393f	9d3fc7a8-a83f-4b34-a95e-9765f9b3cd7b	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-4668-4fa7-9164-59c7ec45ea83	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc82b-bc2c-4035-b97f-6f6012b8de0a	9d3fc7a8-ab62-4854-95ff-7dc0bef8db43	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-484b-4a86-9544-b9dc0c7054b5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc82d-9a27-4b2c-829a-dda39fa35954	9d3fc7a8-add6-40dd-9e1c-2bdfb0b53386	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-4acc-469a-b08f-2da44e0b0e16	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc82f-7367-4242-a899-d48154325f67	9d3fc7a8-b023-455b-9452-6f21ecd14d6b	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-4d03-4014-a42a-08cd11013a3a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc831-6883-494c-87b6-547b9e787bef	9d3fc7a8-b333-4cff-b79a-3d3e1fda6ad8	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-4f38-42f6-b5f4-3ad83474b8eb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc833-49ae-44cf-af7c-39fb46eeee98	9d3fc7a8-b56d-460e-b897-556e136570df	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-516c-4763-8888-689a1847ccc2	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc835-3438-4613-aa48-927ef4a5e7d8	9d3fc7a8-b823-42e8-90af-54097bfa4054	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-5348-45ed-ad03-8215a2ea40cc	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc837-1872-46ce-8175-305a39745b71	9d3fc7a8-ba15-4b92-9f79-b3c63459eabe	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-555e-429b-9199-345cf2df47aa	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc839-001b-4e38-a5ce-c07c396245b7	9d3fc7a8-bbee-4bc9-9e50-2737ac29aa2a	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-5790-432d-973f-96c7c2a700e9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc83a-f24f-4960-b617-0453215a689c	9d3fc7a8-be23-4d8e-9655-27f733a429ac	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-5a95-4dd8-8714-9e8d09ce2dac	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc83d-353c-473f-9718-8d402b0e14f0	9d3fc7a8-bfec-4657-a943-b096ddd9d208	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-5d7c-4fdb-a173-09ee5faa95ff	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc83f-253e-4c4a-b14f-a08783f3694a	9d3fc7a8-c188-4569-a22c-3817f2dc8f1e	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-60ca-4b38-b9e5-4b05a84823d1	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc841-0c67-48ab-83b9-59fe1ef5feff	9d3fc7a8-c34d-4c65-989b-2345a86ae9d2	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-6474-4bf2-9455-23f54a10a825	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc842-ef9f-401a-a0cf-67791e298a02	9d3fc7a8-c4e8-4b3c-add4-177af6431e1a	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-66ad-431a-a72f-fa973d5eef4d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc844-e30b-40db-bca0-40acda16ba3f	9d3fc7a8-c6a7-48a7-8fca-d47f107d8ee2	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-6891-493c-bd97-022efd6c7f44	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc846-d093-42e3-b6f8-2353b03cc785	9d3fc7a8-c843-44de-a2ca-1af639f4026b	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-6a4f-4487-b800-546fff02f973	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc848-be3d-4d02-9a80-f73220276f9b	9d3fc7a8-ca1a-4883-a5ba-a633b3e8fb02	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-6c66-4e6c-973d-6b8d62954d9a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc84a-aedc-4013-aa4f-c47eac65747e	9d3fc7a8-cc78-4564-834d-2415ce0a3274	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-6efb-4eb6-a0b4-816d1ffa3181	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc84c-93b2-4d01-b543-f20ea55b63f6	9d3fc7a8-ce64-4f94-872b-32680d6fff97	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-7145-433e-a9f0-5ac2aa12c59c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc84e-85d9-47fc-971f-b892b50c077f	9d3fc7a8-d0d3-4c52-8024-79c10515dcc9	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-73a7-46f6-8848-3a1a6833c302	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc850-76e4-400f-9141-416032dfd627	9d3fc7a8-d2d5-466a-a48c-715c9d1b6f69	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-75f6-45b4-8a1d-5b9cd921fa8c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc852-5b43-4304-bbb5-31e2e7218477	9d3fc7a8-d4c4-4b3b-8a25-bf1bc988a5b7	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-78b2-4fd9-aa9f-4c662ac668a2	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc854-388a-4e16-83c4-61647ce60ff5	9d3fc7a8-d68f-4108-b079-afbfa200a018	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-7ac7-4f50-8993-49047fc3d3f7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc856-1899-4dc6-98f9-7e811c2044db	9d3fc7a8-dad3-43b7-9f5f-93cb4bd1054c	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-7cc4-49ad-a4ad-d13698e68c56	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc857-fe56-4a90-803a-16dc278b1ae8	9d3fc7a8-dea7-4ae5-a096-fc54e3a3d621	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-7ece-4f86-867d-1c93c968388b	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc859-f2a4-42ef-97b3-9558fe0e20a1	9d3fc7a8-e1b9-4a55-915d-62bab20420e7	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-8127-4f6b-af8b-064464297c14	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc85b-dc10-4efb-956b-be0c6d9647cc	9d3fc7a8-e412-40fe-9fe1-6e77ec884cb5	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-8400-4038-beaa-39b31eeb3e8c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc85d-c97c-4112-b051-6e7858892d6e	9d3fc7a8-e61e-41df-887f-81dcf5635fae	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-86a8-4e8f-878a-cc0301139e9c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc85f-afe9-4551-b493-83897f072e60	9d3fc7a8-e822-4989-9875-db53a04449ee	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-8aab-4d83-b293-b43c7148aba5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc861-95ca-47a0-b3cb-11be97ca9fd1	9d3fc7a8-ea2b-4d64-98da-7a72af8e412c	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-8cee-41e1-a528-f4a4ca315802	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc863-7d0f-4aef-a17b-47d37a4ca703	9d3fc7a8-ec65-4cc3-b951-c0b290c17c08	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-8ed4-4b7b-beb0-bfc3a552fe8c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc865-60f2-41ad-92ee-c88dfda59f8d	9d3fc7a8-ee4b-4ae3-9ede-cdcc9d414e76	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-90df-4b61-b6ba-d5e660e99c0d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc867-4f8c-487f-806e-3e419eff4b2b	9d3fc7a8-f05d-44a6-a6da-554677767f40	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-95d9-4e07-8770-882fa1c18aca	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc869-3613-43f4-baea-0c1a378b1595	9d3fc7a8-f21e-464f-85bd-76b2bddca9c7	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-97ee-4acd-85e0-9513b7f5b656	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc86b-12cc-4291-9934-721bfc43e4ad	9d3fc7a8-f3f0-467e-8f6a-da9af6e30b48	\N	\N	6	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-a849-4927-baad-9f122ab7be2b	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc87c-23a6-4172-ba7c-7410b6ac0c88	\N	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-aafc-476d-bc20-b5df845781d5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc893-e6c1-4387-8374-849290c04d84	\N	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-ad4e-4add-815d-60c10208755d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8ab-0a4f-4b9a-aab3-fe37e2237c46	\N	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-afb1-450c-b198-1f7b1a776254	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8c1-c5a9-4b16-a1ea-0b4d7403ef6c	\N	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-b1b4-4ff1-8af3-2d0917f41bea	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc882-d75e-4190-bf55-4a9067e042ca	\N	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-b3dd-4672-bc23-a15ebab96b45	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc899-e623-4eac-b649-6b33f2100167	\N	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-b5e5-45a9-8530-e7bb98d3d438	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8b0-b64d-490e-8cdd-6f3ff92a8496	\N	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-b846-4417-ae44-d2509a32d77d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8c7-7a87-4d53-b8ba-ed33908a6f98	\N	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-ba4d-4729-8023-9c63fe4bed7a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc87e-59eb-4555-823f-916a57fb5066	\N	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-bc16-47ca-afed-9e42d3decf91	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc895-d756-41f3-aba9-beeca860fe4f	\N	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-be0c-42d3-9e86-2d5b7430e628	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8ac-ec14-420c-83ce-ebdb84726941	\N	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-c00c-44c6-8f4f-b26f8792e82f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8c3-b0ff-4b00-ab70-dd124f37fa80	\N	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-c1ec-4adc-8e0f-a97c6f32408c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc878-52f0-4946-8eb0-d25e8fe3217b	\N	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-c3ea-4eb9-8ae7-215931b5bd23	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc890-294d-4a46-a5bd-14633cb280c8	\N	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-c5f7-4eef-b7db-e2fb4d8f0475	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8a7-4007-401d-b7ef-286e796ee231	\N	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-c7ee-4ce9-b696-95645c07a7f6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8bd-f2c4-4b8a-923e-ef13844bd221	\N	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-c9bb-4413-8849-5b1681163834	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc880-e111-4787-af26-3aa17d15952c	\N	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-cb88-4e2f-9a74-d85583903311	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc897-c108-4f08-88c2-b68d1d7ed767	\N	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-cd67-4ccb-a3af-e66806443b20	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8ae-d0f9-4ea7-a97c-c5b489f8e5a1	\N	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-cf93-4ee0-8657-a70c0c9d9a10	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8c5-9083-4c36-ac4c-1b9fcb74ed57	\N	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-d1da-4459-a49e-b9e845bc5994	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc876-69d5-46a4-9518-f6cf725ff213	\N	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-d403-4548-a48b-0a3836612293	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc88e-4fe6-4e2d-981f-6beab174fd37	\N	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-d682-4dd2-86d6-3643dc079b29	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8a5-545a-43bd-9983-f6a67243678e	\N	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-d864-481e-93c4-ee174d21a169	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8bc-049c-4a85-8690-aece5f5b5a3b	\N	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-da27-4a95-997f-33fb9db4e362	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc86e-d7fa-441f-a7f9-c877e15a4699	\N	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-dbd0-4370-b9f6-9905dbcff1e5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc886-ba81-4c5a-ab7c-75b9f1d43199	\N	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-dd7b-4087-bc84-7fd5724bead8	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc89d-a8e8-4d47-9d67-f9f0e663af11	\N	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-dfb5-426e-809f-eb89fd08fac7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8b4-7fcd-46d4-b051-67d136d0fec4	\N	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-e1d4-4058-95fd-f19858eb8635	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc872-9f14-4001-ad99-a9fadd756c9d	\N	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-e3d2-49ea-a456-73ce96a5acd2	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc88a-81db-435c-abfb-01f66117285b	\N	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-e58b-4b10-890a-0c9f1b5d5eb7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8a1-7da7-4ed6-8177-992fb61e21f6	\N	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-e731-421e-b4f6-f2264ace8b6d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8b8-3b64-413d-8692-1b17582c041e	\N	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-e91f-4cf0-b8bf-4bac65145c72	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc86c-f7e3-4697-a468-328b74441e44	\N	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-eae2-4b2a-bb13-7a9c1a9bbccc	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc884-c3a2-43b3-9c3e-11f3eb3087c5	\N	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-ecaf-4cce-9808-07829c8b0670	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc89b-c919-4e4a-80cf-91e9652c0c1b	\N	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-eeac-4053-99a2-2814082bf513	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8b2-9fa2-4d9a-9ddf-74c040f6e21e	\N	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-f08e-47e1-9c88-e94f5fef11f3	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc874-8573-4a74-898c-c79fec4849fc	\N	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-f279-4003-8ac3-c685e1cf10a1	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc88c-69fd-4f60-a035-e6200c96f058	\N	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-f440-40dc-9cc4-b640d6039625	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8a3-6893-460d-98c1-1bf0a270c876	\N	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-f7b1-4ebf-9415-308728db3c66	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8ba-1ddc-4f7b-9919-a39c5a41c87d	\N	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-f9bf-47a0-aa42-857cb9a41b04	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc87a-388f-488d-a0f7-4d6aa71facda	\N	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-fc44-4ee1-8452-063512dccddf	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc892-044c-4202-94c4-471547821b9b	\N	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8e9-fe94-495e-8126-adcd86c2aae5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8a9-1a70-4ec6-b66f-7e9429730571	\N	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-0115-4396-99fb-b4a066c6cd3c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8bf-e09a-4b14-8a54-7e50c8172779	\N	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-0345-4f51-940a-e7a25c04e75a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc870-b598-4fb8-8524-4dad9c6b272a	\N	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	2	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-0584-4ae5-995d-a1785bc7ade9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc888-a10e-4365-b410-b6d2b59d8d96	\N	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	3	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-0778-4ee1-ab78-c6c7765a28ec	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc89f-8ecf-479d-a978-776070339354	\N	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	4	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-095b-43ff-a575-112383720a98	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8b6-5bbf-43f3-bde4-d9f01c295f84	\N	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	5	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-10fc-4d79-90cb-71ec19d3cc6c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8d8-932a-4d09-b18b-82134554b518	\N	\N	9d3fc77d-9e8b-4654-bd94-ee0960a729cb	16	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-12c5-496b-a2b7-db82097c8755	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8d4-ba68-4f5a-a6bb-05ff4b1b9c5a	\N	\N	9d3fc77d-a2d9-4a34-bae4-0e50d6f422da	13	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-14c6-4495-b163-d21bd471c0bd	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8db-6c29-4625-864a-159c7f5e8777	\N	\N	9d3fc77d-a0b2-48b6-afee-335aabe2f441	17	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-1693-4a64-975e-376150fba4d6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8d6-a78b-4529-a859-94d4bc50eecd	\N	\N	9d3fc77d-a515-4039-9a5f-65d8ab715540	15	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-196d-4de7-af21-498b8d3a3486	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8cf-0562-4646-a704-890d1f7c69b7	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	9	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-1b0f-4625-b643-3ac75d7d21a8	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8d0-e649-4623-96a7-f32d26179f22	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	10	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-1ce2-4799-86ae-5ad957a8fd44	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8cd-2d49-4616-a0b8-d11bb5400716	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	8	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-1ec2-44ba-b9e6-fe9626a4ebfc	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8d2-d5cd-4c22-97ff-0139bd1f562a	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	11	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-20b7-457f-bf71-e9a7e2ae9254	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8cb-4b6b-41df-acbd-46deee5952a0	\N	\N	9d3fc77d-98dd-45e7-9cfe-fe20a9a85ae7	14	2024-10-15 06:13:12	2024-10-15 06:13:12
9d3fc8ea-2324-452a-9554-97bcdb877b5e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9d3fc8c9-697a-4f42-8465-8b49a0333f78	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	12	2024-10-15 06:13:12	2024-10-15 06:13:12
\.


--
-- Data for Name: auditee_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auditee_auditor (jadwal_audit_id, auditee_id, auditor_id, prodi_id, fakultas_id, unit_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.auditor (id, jadwal_audit_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ayat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ayat (id, peraturan_id, pasal_id, ayat, isi, created_at, updated_at) FROM stdin;
1	1	1	1	Penjaminan Mutu Pendidikan Tinggi adalah kegiatan sistemik untuk meningkatkan mutu pendidikan tinggi secara berencana dan berkelanjutan.	2024-10-15 06:09:42	2024-10-15 06:09:42
2	1	1	2	Standar Nasional Pendidikan Tinggi yang selanjutnya disebut SN Dikti adalah satuan standar yang meliputi standar nasional pendidikan ditambah dengan standar penelitian dan standar pengabdian kepada masyarakat.	2024-10-15 06:09:42	2024-10-15 06:09:42
3	1	1	3	Tridharma Perguruan Tinggi yang selanjutnya disebut Tridharma adalah kewajiban perguruan tinggi untuk menyelenggarakan pendidikan, penelitian, dan pengabdian kepada masyarakat.	2024-10-15 06:09:42	2024-10-15 06:09:42
4	1	2	1	Penjaminan Mutu Pendidikan Tinggi dilakukan melalui penetapan, pelaksanaan, evaluasi, pengendalian, dan peningkatan standar pendidikan tinggi.	2024-10-15 06:09:42	2024-10-15 06:09:42
5	1	3	1	Setiap perguruan tinggi wajib melaksanakan penjaminan mutu pendidikan tinggi.	2024-10-15 06:09:42	2024-10-15 06:09:42
6	1	4	1	Penjaminan Mutu Pendidikan Tinggi terdiri atas Penjaminan Mutu Internal dan Penjaminan Mutu Eksternal.	2024-10-15 06:09:42	2024-10-15 06:09:42
7	1	4	2	Penjaminan Mutu Internal adalah penjaminan mutu yang dilaksanakan oleh perguruan tinggi sendiri.	2024-10-15 06:09:42	2024-10-15 06:09:42
8	1	4	3	Penjaminan Mutu Eksternal adalah penjaminan mutu yang dilaksanakan oleh lembaga di luar perguruan tinggi.	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: berita_acara; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.berita_acara (id, jadwal_audit_id, fakultas_id, prodi_id, unit_id, tgl, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: berita_acara_auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.berita_acara_auditee (id, berita_acara_id, auditee_id, approve, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: berita_acara_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.berita_acara_auditor (id, berita_acara_id, auditor_id, approve, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: fakultas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.fakultas (id, nama, created_at, updated_at) FROM stdin;
9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Pertanian	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Biologi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ekonomi dan Bisnis	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-1526-42fe-866a-5f65231bd245	Peternakan	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-1788-476f-8fa7-73933937e593	Hukum	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Ilmu Sosial dan Ilmu Politik	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Kedokteran	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Ilmu-Ilmu Kesehatan	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Ilmu Budaya	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-24af-4504-aac7-89a578a8ea65	Matematika dan IPA	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	Ilmu Perikanan dan Kelautan	2024-10-15 06:09:41	2024-10-15 06:09:41
\.


--
-- Data for Name: form; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.form (id, jadwal_id, instrumen_id, created_at, updated_at) FROM stdin;
9d3fc8e7-1977-4b3a-a8f3-215129599847	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	1	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-1c04-4074-843d-9aff57cd78fe	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	2	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-1def-43f9-a986-54ed0f369193	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	3	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-1ff9-4d05-8cbb-259d718fe9d3	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	4	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-21c1-4aab-8336-b3fd6ffd24b6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	5	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-23eb-4705-a6e4-3b8f2e11d24e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	6	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-2643-4a50-8c20-927a04edecaa	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	7	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-28c4-4ad1-9228-a6e7778c4e12	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	8	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-2b35-4c38-a9c2-2f9758feff19	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	9	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-2df9-4347-bc79-91e9820d3de6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	10	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-3090-4952-923a-da535974bb9e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	11	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-32b5-41ec-b369-e6bafeb46700	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	12	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-3580-4125-b49d-bfc6d8534540	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	13	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-378b-4ff2-b3e6-4f0c19404dcd	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	14	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-39b5-436c-adda-4c2f05469144	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	15	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-3ba4-4123-9a2d-0200340f639a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	16	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-3db1-4240-9d74-ea82afa25d04	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	17	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-3fa5-4bf8-91e8-3fcd664e3390	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	18	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-41f1-484c-91cf-a0ac3e5f1222	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	19	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-43ae-4237-be2e-d38d3d196dea	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	20	2024-10-15 06:13:10	2024-10-15 06:13:10
9d3fc8e7-45ee-476f-a752-8899e7938143	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	21	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-47f7-4f91-b898-a85b71e4d055	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	22	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-4b13-4198-bed9-42f61959233c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	23	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-4d10-4d39-952e-445851c6cf34	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	24	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-4f99-4554-8584-fbea56e1da1d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	25	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-529e-4fbb-b97d-c194ea7bd833	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	26	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-55a3-4239-a1bd-0b65275e6e0f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	38	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-585b-4a2f-a36d-c3f623f27d0b	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	27	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-5aec-47d7-aeca-be8480b400be	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	28	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-5d18-4cbb-84f0-af9f3586613e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	29	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-5fae-46da-9fa8-2ef4b6567aa9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	30	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-6232-44b3-8921-34dafd9bf48f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	31	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-6461-4511-afbf-182b9501f2b3	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	32	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-6691-4834-af40-e98f6ae42ec8	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	33	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-68ef-4fe1-8e0c-a4225d982f45	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	34	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-6ae0-4ec6-a0f0-042199b058f0	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	35	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-6d13-40ec-bb4d-672795473b3a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	36	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-6f11-47fb-a55f-6767ba836e96	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	37	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-70f9-408b-b36e-67de7b569f72	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	39	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-734c-4eb3-bea5-d33598fcd0e2	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	40	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-7592-499d-b380-e32980e1f5a0	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	41	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-781c-4e64-b45c-42276183eb7e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	42	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-7b4f-4e9c-a790-e85aa6ef461f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	43	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-7d7f-4b7c-bd5e-29f78499b7f3	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	62	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-7f79-407d-b6ed-8db285dde650	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	63	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-814c-4905-a393-5230d0b29116	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	64	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-8377-4fcc-a272-2f48ba2e58a6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	65	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-85d4-4067-bcd8-0c480038d67f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	66	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-87f9-4bcb-ba28-f1551ff4d3fd	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	67	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-8aca-4de0-ad36-9bcf9fa19e52	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	68	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-8d5d-44c2-b603-94b15c6c01aa	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	69	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-8f7a-4f89-a821-207262a8412a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	70	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-917c-43dc-b244-ca9d520cb0b5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	71	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-9436-48f7-a25f-2d9b9bfaa967	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	72	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-962f-422c-8b6e-50871f0cdcb4	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	73	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-97ea-46d1-82c8-98868ffe35db	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	44	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-9989-4f77-a5a0-36581c901358	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	45	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-9b15-4b8b-8630-0c8aa61ab875	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	46	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-9d0d-4e53-b57f-5aaa1c016fd4	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	47	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-9f69-4882-87e6-fd5695ed21d4	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	48	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-a182-4e62-a035-5f0b4bac6d19	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	49	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-a53e-4b28-b0cc-ba4ead862438	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	50	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-a7a6-4623-8100-bd14209b2b10	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	51	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-a9a4-4103-8a00-3b689cafe031	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	52	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-abb3-41d3-9cd2-143024a8e6eb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	53	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-ad7a-4855-867b-9c0b45c2f8c7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	54	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-af78-4259-9fc7-d5f900aeabda	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	55	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-b165-4007-aef8-cc4b6c52f190	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	56	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-b33d-4568-a611-6dd2cfb56eb0	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	57	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-b529-4dd8-a95d-1f426e665fb4	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	58	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-b6ec-419f-bbab-050331cf5d8e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	59	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-b8b2-44bf-8d13-b239b1140e64	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	60	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-bafd-4c7b-9000-63dc91fc8833	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	61	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-bd0e-45f1-ae5f-6f61deab2b8d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	74	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-bfb9-4b1b-ac4a-a2125b5a169f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	75	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-c219-4dd6-b9ef-cd5ad5006c14	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	76	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-c410-4c9e-bc33-2fbf58dc0aa9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	77	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-c649-456b-b1af-bd4ac10b8722	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	78	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-c8cb-4c7e-a024-a4f4e540d5fb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	79	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-cb5e-4ab7-ae61-0c1a5b751983	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	80	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-cd89-45e6-b70b-2ee72bc8264a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	81	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-cfa5-48a6-9604-fe946898bc54	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	82	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-d176-449e-a6d5-d2c5719fcd9b	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	83	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-d38a-49f9-a090-3a73906a1ef1	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	84	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-d580-4464-ac48-859f18027d9e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	85	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-d78c-4ce2-8963-e2c1b256cb6c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	86	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-d9b6-4ab4-a290-d9e64cdefc91	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	87	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-dbdb-4f54-82b9-bcb906455445	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	88	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-de3c-48a9-8c91-4ffa7eb543ee	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	89	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-e03e-4f75-8eb0-c1d4686d7765	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	90	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-e232-4f14-8e71-e4a75479d18a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	91	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-e453-4475-8340-68637e8b4d69	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	92	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-e620-4a62-8274-86b412897f11	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	93	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-e7cd-45d1-b7b5-1632215a47a6	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	94	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-e9b1-4f4f-987d-1c5b767b3d68	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	95	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-ebc9-4fed-8e3a-761c134974ea	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	96	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-edec-490c-8fe0-21ec727292f8	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	97	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-f007-4182-9eab-6eba27429b4f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	98	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-f27c-4c50-85a7-919a2f8ca8c5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	99	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-f4bb-4cd3-ae26-4de1da251c67	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	100	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-f73f-4f8c-88c6-d71ccbd08b84	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	101	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-f921-4d41-b1fe-b621d0db53cf	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	102	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-fb16-4e9e-98b8-9475316f303c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	103	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-fcf0-44c0-9f02-8ba26b2c13cf	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	104	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e7-ff48-486a-ad06-928d0e06ef51	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	105	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-0197-4e40-8350-3737eb0b8916	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	106	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-03d0-4965-bf31-21ca9bd8daa7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	107	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-059a-4e50-8d8f-29ffa4dfd6eb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	108	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-0751-4a9d-b8f7-6750de84bee5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	109	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-0933-40ed-b4ee-1473a4832336	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	110	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-0b17-4fc0-9887-9ef62e4089a9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	111	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-0cde-4254-8325-dfaea89d795a	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	112	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-0eac-43cb-ae9f-10c953bb0771	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	113	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-10a4-45e8-b46f-95da42c5874e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	114	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-1267-44d1-a966-5cd27b8c46af	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	115	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-1448-4004-b2ec-a4146298c5b0	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	116	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-16fe-45d4-b25f-dc72ceb39ae9	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	117	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-1960-459e-bd10-ba1882baaeed	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	118	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-1b9c-427f-ba4a-9d05a45428cb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	119	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-1da5-4b19-a985-696f093d60ef	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	120	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-1f6e-4b10-83aa-afa9db7f0bca	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	121	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-2266-4b59-a3d1-982dd8d512c5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	122	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-2449-4fcc-ba95-f23eb402d145	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	123	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-2648-4c13-a1cc-9d6c7e198633	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	124	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-280b-4e19-8877-6e64a305b332	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	125	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-2a7f-4c49-a00b-82783958e06d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	126	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-2cd4-41de-a540-19b7dfb9c55e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	127	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-2eee-491a-9044-5fafc5cde057	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	128	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-31f7-4082-b148-2582827f6a72	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	129	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-3488-4fd2-bc8e-581636bc1953	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	130	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-3724-4571-adee-53205617a62f	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	131	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-39e5-460c-b7e5-a01b9e81e3bf	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	132	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-3c0d-41d8-bb33-de64c4ca2f5e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	133	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-3e82-4e5f-b9e2-82086ef75852	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	134	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-40e8-4848-9da5-4f374f93e006	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	135	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-436d-4d42-9a25-8fac84f835be	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	136	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-45c9-45fc-b802-2477fc3fc29d	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	137	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-48bd-4f39-9f91-4198af6e6635	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	138	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-4af4-424e-9ebb-ce34fc9a2644	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	139	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-4d4e-499a-b6d4-617711c5e86c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	140	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-4f84-4212-959b-8f510606bb6c	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	141	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-518a-49cb-8954-b4d93020a177	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	142	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-5363-4c4f-813e-4c9cee23218e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	143	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-5504-4826-aaa4-ee26559ba9a7	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	144	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-56c1-4ed5-a59c-578075f02be5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	145	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-58ef-4756-a66e-bd51639128b1	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	146	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-5b01-4b84-8635-287a7d745e2e	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	147	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-5d64-4ddb-8d0d-1d2e9e28a8b8	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	148	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-5f52-4071-99ac-47373dcaaa07	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	149	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-6186-4ad7-aa94-7b3b068b4d11	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	150	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-63ce-4ff0-b678-6f7a041715da	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	151	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-65fd-40d5-a64c-e6dbe72898cf	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	152	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-684d-4675-9548-f4808dcbc6ae	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	153	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-6b4b-447b-bdd4-f8755a5d4586	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	154	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-6de9-42a9-8ec6-cdf9e346abd5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	155	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-7049-4ef6-8152-c41fba92ea53	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	156	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-724b-495b-9b38-afb894c8b288	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	157	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-742a-40e0-8078-ad3947e70551	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	158	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-760b-4b32-b1d0-4e2bfc251991	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	159	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-7859-411b-8ed5-66d62ffdba22	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	160	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-7a4b-42ac-981d-9dbca1b8c070	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	161	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-7bf9-4c77-8a45-c43e65066d58	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	162	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-7dbb-40cd-906c-2cb3f85d1353	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	163	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-7f6e-42ef-9ddb-63f98361e455	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	164	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-8140-44bf-ad43-de252a8fb543	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	165	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-8303-46b3-b5a4-aed56bcb1829	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	166	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-84ba-4d50-bef5-2484e4258bba	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	167	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-867b-4cdf-a106-a205ab122518	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	168	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-882e-43b4-867b-60cb030ffbdb	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	169	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-8a24-453d-8c3b-1f471e4ddad5	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	170	2024-10-15 06:13:11	2024-10-15 06:13:11
9d3fc8e8-8cd0-43e5-b5bf-49fc1afa0643	9d3fc8e7-1495-46f4-b29d-2d971f5d4311	171	2024-10-15 06:13:11	2024-10-15 06:13:11
\.


--
-- Data for Name: instrumen; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.instrumen (id, peraturan_id, standar_id, kategori_id, level_id, jenis_pertanyaan_id, kode, pernyataan, indikator, created_at, updated_at) FROM stdin;
1	1	1	1	1	1	PDL-1	Program Studi telah memiliki rumusan CPL yang mencakup aspek sikap, pengetahuan, keterampilan umum, dan keterampilan khusus	Program Studi telah memiliki rumusan CPL yang mencakup aspek sikap, pengetahuan, keterampilan umum, dan keterampilan khusus	2024-10-15 06:13:03	2024-10-15 06:13:03
2	1	1	1	1	1	PDL-2	Penyusunan CPL telah melibatkan pemangku kepentingan internal dan eksternal (termasuk asosiasi dan iduka (dunia industri dan dunia kerja))	Penyusunan CPL telah melibatkan pemangku kepentingan internal dan eksternal (termasuk asosiasi dan iduka (dunia industri dan dunia kerja))	2024-10-15 06:13:03	2024-10-15 06:13:03
3	1	1	1	1	1	PDL-3	Penyusunan CPL program studi telah memperhatikan 7 aspek:\n\t\t\t\t1.\tvisi dan misi perguruan tinggi;\n\t\t\t\t2.\tkerangka kualifikasi nasional Indonesia;\n\t\t\t\t3.\tperkembangan ilmu pengetahuan dan teknologi;\n\t\t\t\t4.\tkebutuhan kompetensi kerja dari dunia kerja; \n\t\t\t\t5.\tranah keilmuan program studi; \n\t\t\t\t6.\tkompetensi utama lulusan program studi; dan\n\t\t\t\t7.\tkurikulum program studi sejenis.	Penyusunan CPL program studi telah memperhatikan 7 aspek:\n\t\t\t\t1.\tvisi dan misi perguruan tinggi;\n\t\t\t\t2.\tkerangka kualifikasi nasional Indonesia;\n\t\t\t\t3.\tperkembangan ilmu pengetahuan dan teknologi;\n\t\t\t\t4.\tkebutuhan kompetensi kerja dari dunia kerja; \n\t\t\t\t5.\tranah keilmuan program studi; \n\t\t\t\t6.\tkompetensi utama lulusan program studi; dan\n\t\t\t\t7.\tkurikulum program studi sejenis.	2024-10-15 06:13:03	2024-10-15 06:13:03
4	1	1	1	1	1	PDL-4	Persentase matakuliah yang CPL dan CPMK yang tertuang di RPS telah disosialisasikan kepada mahasiswa oleh dosen penanggungjawab matakuliah melalui eldiru/di kelas.	Persentase matakuliah yang CPL dan CPMK yang tertuang di RPS telah disosialisasikan kepada mahasiswa oleh dosen penanggungjawab matakuliah melalui eldiru/di kelas.	2024-10-15 06:13:03	2024-10-15 06:13:03
5	1	1	1	1	1	PDL-5	Program Studi memiliki peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL serta dilengkapi dengan instrumen pengukuran pemenuhan CPL	Program Studi memiliki peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL serta dilengkapi dengan instrumen pengukuran pemenuhan CPL	2024-10-15 06:13:03	2024-10-15 06:13:03
6	1	1	1	1	1	PDL-6	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (D3)	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (D3)	2024-10-15 06:13:03	2024-10-15 06:13:03
7	1	1	1	1	1	PDL-7	Kompetensi utama lulusan program studi telah mengacu pada kompetensi yang disusun oleh asosiasi prodi dan pihak terkait (pengguna lulusan, organisasi profesi, alumni) 	Kompetensi utama lulusan program studi telah mengacu pada kompetensi yang disusun oleh asosiasi prodi dan pihak terkait (pengguna lulusan, organisasi profesi, alumni) 	2024-10-15 06:13:03	2024-10-15 06:13:03
8	1	1	1	1	1	PDL-8	Program studi memiliki bukti sahih pelaksanaan asesmen terhadap ketercapaian CPL program studi.	Program studi memiliki bukti sahih pelaksanaan asesmen terhadap ketercapaian CPL program studi.	2024-10-15 06:13:03	2024-10-15 06:13:03
9	1	1	1	1	2	PDL-9	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	2024-10-15 06:13:03	2024-10-15 06:13:03
10	1	1	1	1	2	PDL-10	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	2024-10-15 06:13:03	2024-10-15 06:13:03
11	1	1	1	1	2	PDL-11	Persentase lulusan tepat waktu pada jenjang pendidikan D3.	Persentase lulusan tepat waktu pada jenjang pendidikan D3.	2024-10-15 06:13:03	2024-10-15 06:13:03
12	1	1	1	1	2	PDL-12	Rata-rata produktivitas program studi (tingkat kelulusan).	Rata-rata produktivitas program studi (tingkat kelulusan).	2024-10-15 06:13:03	2024-10-15 06:13:03
13	1	1	1	1	1	PDL-13	Jumlah prestasi akademik mahasiswa ditingkat lokal/nasional/Internasional.	Jumlah prestasi akademik mahasiswa ditingkat lokal/nasional/Internasional.	2024-10-15 06:13:03	2024-10-15 06:13:03
14	1	1	1	1	1	PDL-14	Tingkat prestasi non akademik mahasiswa?	Tingkat prestasi non akademik mahasiswa?	2024-10-15 06:13:04	2024-10-15 06:13:04
15	1	1	1	1	2	PDL-15	Persentase kesesuaian bidang kerja	Persentase kesesuaian bidang kerja	2024-10-15 06:13:04	2024-10-15 06:13:04
16	1	1	1	1	2	PDL-16	Rata-rata masa tunggu lulusan program studi D3/S1?	Rata-rata masa tunggu lulusan program studi D3/S1?	2024-10-15 06:13:04	2024-10-15 06:13:04
17	1	1	1	1	1	PDL-17	Jumlah publikasi mahasiswa di jurnal/prosiding.	Jumlah publikasi mahasiswa di jurnal/prosiding.	2024-10-15 06:13:04	2024-10-15 06:13:04
18	1	1	1	1	2	PDL-18	Jumlah mahasiswa yang melakukan diseminasi pada kegiatan seminar Internasional dan Nasional.	Jumlah mahasiswa yang melakukan diseminasi pada kegiatan seminar Internasional dan Nasional.	2024-10-15 06:13:04	2024-10-15 06:13:04
19	1	1	1	1	2	PDL-19	Jumlah tulisan mahasiswa pada media massa	Jumlah tulisan mahasiswa pada media massa	2024-10-15 06:13:04	2024-10-15 06:13:04
20	1	1	1	1	2	PDL-20	Jumlah Luaran mahasiswa dalam bentuk HAKI	Jumlah Luaran mahasiswa dalam bentuk HAKI	2024-10-15 06:13:04	2024-10-15 06:13:04
21	1	1	1	1	2	PDL-21	Jumlah Luaran mahasiswa dalam bentuk book chapter.	Jumlah Luaran mahasiswa dalam bentuk book chapter.	2024-10-15 06:13:04	2024-10-15 06:13:04
22	1	1	1	1	2	PDL-22	Jumlah Luaran mahasiswa dalam bentuk buku.	Jumlah Luaran mahasiswa dalam bentuk buku.	2024-10-15 06:13:04	2024-10-15 06:13:04
23	1	1	1	1	2	PDL-23	Presentase Kelulusan pada first taker (PFT) pada pengujian kompetensi nasional?	Presentase Kelulusan pada first taker (PFT) pada pengujian kompetensi nasional?	2024-10-15 06:13:04	2024-10-15 06:13:04
24	1	1	2	1	1	PDP-1	Program studi telah memiliki RPS untuk semua mata kuliah	Program studi telah memiliki RPS untuk semua mata kuliah	2024-10-15 06:13:04	2024-10-15 06:13:04
25	1	1	2	1	1	PDP-2	Dokumen RPS yang dimiliki PS telah mencakup unsur perencanaan, pelaksanaan dan penilaian pada proses pembelajaran.	Dokumen RPS yang dimiliki PS telah mencakup unsur perencanaan, pelaksanaan dan penilaian pada proses pembelajaran.	2024-10-15 06:13:04	2024-10-15 06:13:04
26	1	1	2	1	1	PDP-3	PS sudah melaksanakan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester (Permendikbudristek No.53 Tahun 2023).	PS sudah melaksanakan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester (Permendikbudristek No.53 Tahun 2023).	2024-10-15 06:13:04	2024-10-15 06:13:04
38	1	1	1	2	2	PDL-25	Persentase lulusan tepat waktu untuk Program Sarjana: (Merujuk pada LAM)	Persentase lulusan tepat waktu untuk Program Sarjana: (Merujuk pada LAM)	2024-10-15 06:13:05	2024-10-15 06:13:05
27	1	1	2	1	1	PDP-4	PS telah melaksanakan pemenuhan beban belajar dalam bentuk kuliah, responsi, tutorial, seminar, praktikum, praktik, studio, penelitian, perancangan, pengembangan, tugas akhir, pelatihan bela negara, pertukaran pelajar, magang, wirausaha, pengabdian kepada masyarakat, dan/atau bentuk pembelajaran lain.\n                Bentuk pembelajaran melalui kegiatan belajar terbimbing, penugasan terstruktur, dan/atau mandiri.	PS telah melaksanakan pemenuhan beban belajar dalam bentuk kuliah, responsi, tutorial, seminar, praktikum, praktik, studio, penelitian, perancangan, pengembangan, tugas akhir, pelatihan bela negara, pertukaran pelajar, magang, wirausaha, pengabdian kepada masyarakat, dan/atau bentuk pembelajaran lain.\n                Bentuk pembelajaran melalui kegiatan belajar terbimbing, penugasan terstruktur, dan/atau mandiri.	2024-10-15 06:13:04	2024-10-15 06:13:04
28	1	1	2	1	2	PDP-5	Persentase mata kuliah yang telah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif.\n                Penjelasan: a. Valid atau sahih menunjukkan penilaian mencerminkan kemampuan mahasiswa yang diukur (soal ujian sesuai dengan kisi-kisi materi dan indikator pencapaian kompetensi). b. Reliabel atau andal yaitu ketika soal ujian digunakan berulang-ulang, hasilnya selalu konsisten. c. Transparan atau terbuka yaitu prosedur penilaian, kriteria penilaian, dan dasar pengambilan keputusan hasil penilaian dapat diketahui oleh pihak yang berkepentingan. d. Akuntabel yaitu penilaian dapat dipertanggungjawabkan, baik dari segi mekanisme, prosedur, teknik, maupun hasilnya. e. Berkeadilan yaitu penilaian tidak menguntungkan atau merugikan peserta didik dari perbedaan latar belakang agama, suku, budaya, adat istiadat, status sosial ekonomi, gender dan mahasiswa berkebutuhan khusus. f. Objektif berdasarkan pada standar yang jelas dan bebas dari pengaruh subjektivitas penilai. g. Edukatif yaitu penilaian dapat memotivasi mahasiswa agar mampu memperbaiki kinerja belajar dan meraih prestasi belajar yang lebih tinggi.\n                	Persentase mata kuliah yang telah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif.\n                Penjelasan: a. Valid atau sahih menunjukkan penilaian mencerminkan kemampuan mahasiswa yang diukur (soal ujian sesuai dengan kisi-kisi materi dan indikator pencapaian kompetensi). b. Reliabel atau andal yaitu ketika soal ujian digunakan berulang-ulang, hasilnya selalu konsisten. c. Transparan atau terbuka yaitu prosedur penilaian, kriteria penilaian, dan dasar pengambilan keputusan hasil penilaian dapat diketahui oleh pihak yang berkepentingan. d. Akuntabel yaitu penilaian dapat dipertanggungjawabkan, baik dari segi mekanisme, prosedur, teknik, maupun hasilnya. e. Berkeadilan yaitu penilaian tidak menguntungkan atau merugikan peserta didik dari perbedaan latar belakang agama, suku, budaya, adat istiadat, status sosial ekonomi, gender dan mahasiswa berkebutuhan khusus. f. Objektif berdasarkan pada standar yang jelas dan bebas dari pengaruh subjektivitas penilai. g. Edukatif yaitu penilaian dapat memotivasi mahasiswa agar mampu memperbaiki kinerja belajar dan meraih prestasi belajar yang lebih tinggi.	2024-10-15 06:13:04	2024-10-15 06:13:04
29	1	1	2	1	2	PDP-6	Persentase mata kuliah yang melakukan penilaian hasil belajar mahasiswa dengan bentuk penilaian formatif dan sumatif.\n                Penjelasan: a. Penilaian Formatif: Memberikan tugas berupa projek kepada siswa, baik secara individu maupun kelompok. Setelah tugas selesai, dosen memberikan tanggapan dan saran untuk perbaikan. b. Penilaian Sumatif: Melakukan kuis atau ujian harian untuk mengukur pemahaman siswa terhadap materi yang sudah disampaikan.	Persentase mata kuliah yang melakukan penilaian hasil belajar mahasiswa dengan bentuk penilaian formatif dan sumatif.\n                Penjelasan: a. Penilaian Formatif: Memberikan tugas berupa projek kepada siswa, baik secara individu maupun kelompok. Setelah tugas selesai, dosen memberikan tanggapan dan saran untuk perbaikan. b. Penilaian Sumatif: Melakukan kuis atau ujian harian untuk mengukur pemahaman siswa terhadap materi yang sudah disampaikan.	2024-10-15 06:13:04	2024-10-15 06:13:04
30	1	1	2	1	2	PDP-7	Persentase mata kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa.	Persentase mata kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa.	2024-10-15 06:13:04	2024-10-15 06:13:04
31	1	1	2	1	1	PDP-8	Tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa. (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).	Tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa. (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).	2024-10-15 06:13:04	2024-10-15 06:13:04
32	1	1	2	1	1	PDP-9	Program doktor telah mewajibkan pelibatan penguji dari luar perguruan tinggi.	Program doktor telah mewajibkan pelibatan penguji dari luar perguruan tinggi.	2024-10-15 06:13:05	2024-10-15 06:13:05
33	1	1	2	1	1	PDP-10	Tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.	Tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.	2024-10-15 06:13:05	2024-10-15 06:13:05
34	1	1	2	1	1	PDP-11	Tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk matakuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah.	Tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk matakuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah.	2024-10-15 06:13:05	2024-10-15 06:13:05
35	1	1	2	1	1	PDP-12	Tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.	Tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.	2024-10-15 06:13:05	2024-10-15 06:13:05
36	1	1	2	1	1	PDP-13	Tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry.	Tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry.	2024-10-15 06:13:05	2024-10-15 06:13:05
37	1	1	1	2	2	PDL-24	Persentase lulusan tepat waktu untuk Program Diploma 3\n            (Merujuk pada Perban PT no 20 tahun 2019)	Persentase lulusan tepat waktu untuk Program Diploma 3\n            (Merujuk pada Perban PT no 20 tahun 2019)	2024-10-15 06:13:05	2024-10-15 06:13:05
39	1	1	1	2	2	PDL-26	Persentase lulusan tepat waktu untuk Program Profesi. (Merujuk pada LAM)	Persentase lulusan tepat waktu untuk Program Profesi. (Merujuk pada LAM)	2024-10-15 06:13:05	2024-10-15 06:13:05
40	1	1	1	2	2	PDL-27	Persentase lulusan tepat waktu untuk Program Magister Merujuk pada LAM	Persentase lulusan tepat waktu untuk Program Magister Merujuk pada LAM	2024-10-15 06:13:05	2024-10-15 06:13:05
41	1	1	1	2	2	PDL-28	Persentase lulusan tepat waktu untuk Program Doktor (Merujuk pada Perban PT no 20 tahun 2019)	Persentase lulusan tepat waktu untuk Program Doktor (Merujuk pada Perban PT no 20 tahun 2019)	2024-10-15 06:13:05	2024-10-15 06:13:05
42	1	1	1	2	1	PDL-29	UPPS melakukan pengukuran kepuasan pengguna lulusan yang mencakup 5 aspek sebagai berikut: \n                a.\tPelaksanaan tracer study terkoordinasi di tingkat PT,\n                b.\tKegiatan tracer study dilakukan secara reguler setiap tahun dan terdokumentasi,\n                c.\tIsi kuesioner mencakup seluruh pertanyaan inti tracer study DIKTI.\n                d.\tDitargetkan pada seluruh populasi ( untuk sarjana :lulusan TS-4 s.d. TS-2; untuk diploma TS-3 sd TS-2; untuk magister; TS-4-TS-2. untuk profesi :  TS-1……; untuk doktor  TS-4-TS-2 ),\n                e.\tHasilnya disosialisasikan dan digunakan untuk pengembangan kurikulum dan pembelajaran.	UPPS melakukan pengukuran kepuasan pengguna lulusan yang mencakup 5 aspek sebagai berikut: \n                a.\tPelaksanaan tracer study terkoordinasi di tingkat PT,\n                b.\tKegiatan tracer study dilakukan secara reguler setiap tahun dan terdokumentasi,\n                c.\tIsi kuesioner mencakup seluruh pertanyaan inti tracer study DIKTI.\n                d.\tDitargetkan pada seluruh populasi ( untuk sarjana :lulusan TS-4 s.d. TS-2; untuk diploma TS-3 sd TS-2; untuk magister; TS-4-TS-2. untuk profesi :  TS-1……; untuk doktor  TS-4-TS-2 ),\n                e.\tHasilnya disosialisasikan dan digunakan untuk pengembangan kurikulum dan pembelajaran.	2024-10-15 06:13:05	2024-10-15 06:13:05
43	1	1	2	2	1	PDP-14	Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup tiga aspek:\n                a.\tperencanaan proses pembelajaran;\n                b.\tpelaksanaan proses pembelajaran; dan\n                c.\tpenilaian proses pembelajaran.	Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup tiga aspek:\n                a.\tperencanaan proses pembelajaran;\n                b.\tpelaksanaan proses pembelajaran; dan\n                c.\tpenilaian proses pembelajaran.	2024-10-15 06:13:05	2024-10-15 06:13:05
62	1	1	3	2	1	PDM-3	Tenaga kependidikan yang kompeten dengan kualifikasi akademik yang sesuai kebutuhan, tugas dan fungsinya	Tenaga kependidikan yang kompeten dengan kualifikasi akademik yang sesuai kebutuhan, tugas dan fungsinya	2024-10-15 06:13:06	2024-10-15 06:13:06
63	1	1	3	2	1	PDM-4	Tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria sebagai berikut:\n                    1) mengakomodasi kebutuhan pendidikan mahasiswa;\n                    2) mengakomodasi pelaksanaan tugas dosen, tutor, instruktur, asisten, dan pembimbing sesuai dengan bidang keahlian dan tenaga kependidikan;\n                    3) ramah terhadap mahasiswa, dosen, dan tenaga kependidikan yang berkebutuhan khusus; dan\n                    4) memadai untuk menyelenggarakan pendidikan dan manajemen pendidikan tinggi sesuai kebutuhan penyelenggaraan dan rencana pengembangan pendidikan.	Tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria sebagai berikut:\n                    1) mengakomodasi kebutuhan pendidikan mahasiswa;\n                    2) mengakomodasi pelaksanaan tugas dosen, tutor, instruktur, asisten, dan pembimbing sesuai dengan bidang keahlian dan tenaga kependidikan;\n                    3) ramah terhadap mahasiswa, dosen, dan tenaga kependidikan yang berkebutuhan khusus; dan\n                    4) memadai untuk menyelenggarakan pendidikan dan manajemen pendidikan tinggi sesuai kebutuhan penyelenggaraan dan rencana pengembangan pendidikan.	2024-10-15 06:13:06	2024-10-15 06:13:06
64	1	1	3	2	1	PDM-5	Tersedia sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)	Tersedia sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)	2024-10-15 06:13:06	2024-10-15 06:13:06
65	1	1	3	2	1	PDM-6	Kemudahan akses terhadap sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)	Kemudahan akses terhadap sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)	2024-10-15 06:13:06	2024-10-15 06:13:06
66	1	1	3	2	1	PDM-7	Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah	Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah	2024-10-15 06:13:06	2024-10-15 06:13:06
67	1	1	3	2	1	PDM-8	Tersedia dokumen rancangan anggaran pendapatan dan belanja yang dimiliki oleh UPPS	Tersedia dokumen rancangan anggaran pendapatan dan belanja yang dimiliki oleh UPPS	2024-10-15 06:13:06	2024-10-15 06:13:06
68	1	2	5	2	1	PNP-1	Hasil penelitian dosen diintegrasikan ke dalam proses pembelajaran.	Hasil penelitian dosen diintegrasikan ke dalam proses pembelajaran.	2024-10-15 06:13:06	2024-10-15 06:13:06
69	1	2	5	2	1	PNP-2	Fakultas memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.	Fakultas memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.	2024-10-15 06:13:06	2024-10-15 06:13:06
70	1	2	5	2	1	PNP-3	Dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.	Dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.	2024-10-15 06:13:06	2024-10-15 06:13:06
71	1	2	6	2	1	PNM-1	UPPS memiliki dosen dan/atau peneliti yang tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.	UPPS memiliki dosen dan/atau peneliti yang tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.	2024-10-15 06:13:06	2024-10-15 06:13:06
72	1	3	8	2	1	PMP-1	Dosen terlibat dalam kegiatan PkM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat.	Dosen terlibat dalam kegiatan PkM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat.	2024-10-15 06:13:06	2024-10-15 06:13:06
73	1	3	8	2	1	PMP-2	Dosen melaksanakan kegiatan PkM (Pengabdian Kepada Masyarakat) sesuai dengan roadmap PKM.	Dosen melaksanakan kegiatan PkM (Pengabdian Kepada Masyarakat) sesuai dengan roadmap PKM.	2024-10-15 06:13:06	2024-10-15 06:13:06
44	1	1	2	2	1	PDP-15	Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4 aspek:\n                a.\tmenciptakan suasana belajar yang menyenangkan, inklusif, kolaboratif, kreatif, dan efektif;\n                b.\tmemberikan kesempatan belajar yang sama tanpa membedakan latar belakang pendidikan, sosial, ekonomi, budaya, bahasa, jalur penerimaan mahasiswa, dan kebutuhan khusus mahasiswa;\n                c.\tmenjamin keamanan, kenyamanan, dan kesejahteraan hidup sivitas akademika;\n                d.\tmemberikan fleksibilitas dalam proses pendidikan untuk memfasilitasi pendidikan berkelanjutan sepanjang hayat.	Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4 aspek:\n                a.\tmenciptakan suasana belajar yang menyenangkan, inklusif, kolaboratif, kreatif, dan efektif;\n                b.\tmemberikan kesempatan belajar yang sama tanpa membedakan latar belakang pendidikan, sosial, ekonomi, budaya, bahasa, jalur penerimaan mahasiswa, dan kebutuhan khusus mahasiswa;\n                c.\tmenjamin keamanan, kenyamanan, dan kesejahteraan hidup sivitas akademika;\n                d.\tmemberikan fleksibilitas dalam proses pendidikan untuk memfasilitasi pendidikan berkelanjutan sepanjang hayat.	2024-10-15 06:13:05	2024-10-15 06:13:05
45	1	1	2	2	1	PDP-16	Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan	Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan	2024-10-15 06:13:05	2024-10-15 06:13:05
46	1	1	2	2	1	PDP-17	Tersedia dokumen kebijakan MBKM	Tersedia dokumen kebijakan MBKM	2024-10-15 06:13:05	2024-10-15 06:13:05
47	1	1	2	2	1	PDP-18	Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh program studi	Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh program studi	2024-10-15 06:13:05	2024-10-15 06:13:05
48	1	1	2	2	1	PDP-19	Tersedia dokumen kebijakan program khusus seperti\n                percepatan, transfer kredit, perolehan kredit, double degree, atau\n                micro-credentials	Tersedia dokumen kebijakan program khusus seperti\n                percepatan, transfer kredit, perolehan kredit, double degree, atau\n                micro-credentials	2024-10-15 06:13:05	2024-10-15 06:13:05
49	1	1	2	2	1	PDP-20	Program Studi melaksanakan program khusus (percepatan/ transfer\n                kredit/ perolehan kredit/ double degree/ micro-credentials)?	Program Studi melaksanakan program khusus (percepatan/ transfer\n                kredit/ perolehan kredit/ double degree/ micro-credentials)?	2024-10-15 06:13:05	2024-10-15 06:13:05
50	1	1	2	2	1	PDP-21	Tersedia dokumen kebijakan penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi.	Tersedia dokumen kebijakan penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi.	2024-10-15 06:13:05	2024-10-15 06:13:05
51	1	1	2	2	1	PDP-22	Tersedia dokumen pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)?	Tersedia dokumen pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)?	2024-10-15 06:13:05	2024-10-15 06:13:05
52	1	1	2	2	1	PDP-23	Tersedia dokumen hasil evaluasi proses pembelajaran yang memenuhi aspek berikut: (pasal 24 dan 25 PerMen 53 th 2023)\n                a.\taktivitas pembelajaran pada setiap angkatan;\n                b.\tjumlah mahasiswa aktif pada setiap angkatan;\n                c.\tMasa Tempuh Kurikulum;\n                d.\tmasa penyelesaian studi mahasiswa; dan \n                e.\ttingkat serapan lulusan mahasiswa di dunia kerja.	Tersedia dokumen hasil evaluasi proses pembelajaran yang memenuhi aspek berikut: (pasal 24 dan 25 PerMen 53 th 2023)\n                a.\taktivitas pembelajaran pada setiap angkatan;\n                b.\tjumlah mahasiswa aktif pada setiap angkatan;\n                c.\tMasa Tempuh Kurikulum;\n                d.\tmasa penyelesaian studi mahasiswa; dan \n                e.\ttingkat serapan lulusan mahasiswa di dunia kerja.	2024-10-15 06:13:05	2024-10-15 06:13:05
53	1	1	2	2	1	PDP-24	Tersedia pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti yang paling lambat dilaksanakan 2 bulan setelah akhir semester	Tersedia pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti yang paling lambat dilaksanakan 2 bulan setelah akhir semester	2024-10-15 06:13:05	2024-10-15 06:13:05
54	1	1	2	2	1	PDP-25	Tersedia bukti dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan	Tersedia bukti dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan	2024-10-15 06:13:06	2024-10-15 06:13:06
55	1	1	2	2	1	PDP-26	Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas (LAKIP)	Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas (LAKIP)	2024-10-15 06:13:06	2024-10-15 06:13:06
56	1	1	2	2	1	PDP-27	Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek untuk peningkatan proses dan hasil belajar secara berkelanjutan yang dievaluasi secara berkala (Renstra)	Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek untuk peningkatan proses dan hasil belajar secara berkelanjutan yang dievaluasi secara berkala (Renstra)	2024-10-15 06:13:06	2024-10-15 06:13:06
57	1	1	2	2	1	PDP-28	Tersedia dokumen peraturan tentang etika akademik	Tersedia dokumen peraturan tentang etika akademik	2024-10-15 06:13:06	2024-10-15 06:13:06
58	1	1	2	2	1	PDP-29	Tersedia dokumen laporan hasil penegakan etika akademik	Tersedia dokumen laporan hasil penegakan etika akademik	2024-10-15 06:13:06	2024-10-15 06:13:06
59	1	1	2	2	1	PDP-30	Tersedia dokumen laporan hasil AMAI bidang akademik dan non akademik	Tersedia dokumen laporan hasil AMAI bidang akademik dan non akademik	2024-10-15 06:13:06	2024-10-15 06:13:06
60	1	1	3	2	2	PDM-1	Jumlah dosen tersertifikasi dosen (serdos)	Jumlah dosen tersertifikasi dosen (serdos)	2024-10-15 06:13:06	2024-10-15 06:13:06
61	1	1	3	2	1	PDM-2	Dosen pada pendidikan vokasi yang berasal dari praktisi dunia usaha, dunia industri, dan dunia kerja.	Dosen pada pendidikan vokasi yang berasal dari praktisi dunia usaha, dunia industri, dan dunia kerja.	2024-10-15 06:13:06	2024-10-15 06:13:06
74	1	3	9	2	1	PMM-1	Tersediannya anggaran PkM setiap dosen minimal 5 juta per tahun.	Tersediannya anggaran PkM setiap dosen minimal 5 juta per tahun.	2024-10-15 06:13:06	2024-10-15 06:13:06
75	1	4	10	2	1	NAK-1	Terdapat survey untuk mengetahui kepuasan mitra kerjasama yang diukur dengan instrumen yang sahih	Terdapat survey untuk mengetahui kepuasan mitra kerjasama yang diukur dengan instrumen yang sahih	2024-10-15 06:13:06	2024-10-15 06:13:06
76	1	4	12	2	1	NAT-1	Terdapat bukti/pengakuan yang sahih bahwa pimpinan UPPS/Prodi memiliki karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik)	Terdapat bukti/pengakuan yang sahih bahwa pimpinan UPPS/Prodi memiliki karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik)	2024-10-15 06:13:06	2024-10-15 06:13:06
77	1	4	12	2	1	NAT-2	Terdapat bukti/pengakuan kapabilitas pimpinan UPPS/Prodi mencakup 5 aspek:\n                1)\tkredibel\n                2)\ttransparan\n                3)\takuntanbel\n                4)\tbertanggung jawab\n                5)\tadil 	Terdapat bukti/pengakuan kapabilitas pimpinan UPPS/Prodi mencakup 5 aspek:\n                1)\tkredibel\n                2)\ttransparan\n                3)\takuntanbel\n                4)\tbertanggung jawab\n                5)\tadil 	2024-10-15 06:13:06	2024-10-15 06:13:06
78	1	4	13	2	1	NAM-1	Tersedia rencana program kegiatan mahasiswa	Tersedia rencana program kegiatan mahasiswa	2024-10-15 06:13:06	2024-10-15 06:13:06
79	1	4	13	2	1	NAM-2	Tersedia dokumen pelaksanaan kegiatan kemahasiswaan	Tersedia dokumen pelaksanaan kegiatan kemahasiswaan	2024-10-15 06:13:06	2024-10-15 06:13:06
80	1	4	13	2	1	NAM-3	Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan kemahasiswaan (beasiswa, kesehatan, asrama mahasiswa, fasilitas olahraga) dengan menggunakan instrumen yang sahih	Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan kemahasiswaan (beasiswa, kesehatan, asrama mahasiswa, fasilitas olahraga) dengan menggunakan instrumen yang sahih	2024-10-15 06:13:06	2024-10-15 06:13:06
81	1	4	15	2	1	NAS-1	Tersedia sarana prasarana umum di lingkungan fakultas (Laboratorium, Kantin, parkir, dan ruang UKM)	Tersedia sarana prasarana umum di lingkungan fakultas (Laboratorium, Kantin, parkir, dan ruang UKM)	2024-10-15 06:13:07	2024-10-15 06:13:07
82	1	4	15	2	1	NAS-2	Tersedia dokumen inventarisasi Barang Milik Negara (BMN)	Tersedia dokumen inventarisasi Barang Milik Negara (BMN)	2024-10-15 06:13:07	2024-10-15 06:13:07
83	1	4	15	2	1	NAS-3	Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum	Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum	2024-10-15 06:13:07	2024-10-15 06:13:07
84	1	1	2	3	1	PDP-31	Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru	Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru	2024-10-15 06:13:07	2024-10-15 06:13:07
85	1	1	2	3	1	PDP-32	Tersedia dokumen kebijakan Rekognisi Pembelajaran Lampau (RPL) di Unsoed yang sesuai dengan ketentuan peraturan perundang-undangan	Tersedia dokumen kebijakan Rekognisi Pembelajaran Lampau (RPL) di Unsoed yang sesuai dengan ketentuan peraturan perundang-undangan	2024-10-15 06:13:07	2024-10-15 06:13:07
86	1	1	2	3	1	PDP-33	Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB	Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB	2024-10-15 06:13:07	2024-10-15 06:13:07
87	1	1	2	3	2	PDP-34	Persentase mahasiswa yang puas terhadap layanan administrasi, layanan akademik, layanan konseling, kesehatan dan kebutuhan dasar untuk mahasiswa berkebutuhan khusus (Merujuk pada LAM Teknik dan BAN PT)	Persentase mahasiswa yang puas terhadap layanan administrasi, layanan akademik, layanan konseling, kesehatan dan kebutuhan dasar untuk mahasiswa berkebutuhan khusus (Merujuk pada LAM Teknik dan BAN PT)	2024-10-15 06:13:07	2024-10-15 06:13:07
88	1	1	2	3	1	PDP-35	Tersedia sistem informasi terkait data pendidikan	Tersedia sistem informasi terkait data pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
89	1	1	3	3	1	PDM-9	Tersedia dokumen kebijakan tata kelola teknologi informasi yang memudahkan pengambilan keputusan dan penyebaran ilmu pengetahuan	Tersedia dokumen kebijakan tata kelola teknologi informasi yang memudahkan pengambilan keputusan dan penyebaran ilmu pengetahuan	2024-10-15 06:13:07	2024-10-15 06:13:07
90	1	1	3	3	1	PDM-10	Unsoed memiliki rencana strategis keuangan yang memuat perencanaan anggaran keuangan	Unsoed memiliki rencana strategis keuangan yang memuat perencanaan anggaran keuangan	2024-10-15 06:13:07	2024-10-15 06:13:07
91	1	1	3	3	1	PDM-11	Tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian	Tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian	2024-10-15 06:13:07	2024-10-15 06:13:07
92	1	1	3	3	1	PDM-12	Tersedia dokumen kebijakan bantuan biaya pendidikan	Tersedia dokumen kebijakan bantuan biaya pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
93	1	1	3	3	1	PDM-13	Terdapat data mahasiswa penerima bantuan biaya Pendidikan	Terdapat data mahasiswa penerima bantuan biaya Pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
94	1	2	4	3	1	PNL-1	Hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta target dampak Unsoed.	Hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta target dampak Unsoed.	2024-10-15 06:13:07	2024-10-15 06:13:07
95	1	2	4	3	1	PNL-2	Hasil penelitian dosen dapat diakses dan dimanfaatkan oleh Masyarakat melalui jurnal yang memiliki oleh Unsoed	Hasil penelitian dosen dapat diakses dan dimanfaatkan oleh Masyarakat melalui jurnal yang memiliki oleh Unsoed	2024-10-15 06:13:07	2024-10-15 06:13:07
96	1	2	5	3	1	PNP-4	LPPM memiliki dokumen tata kelola di bidang penelitian berupa RIP, Renstra, Renop dan Roadmap penelitian.	LPPM memiliki dokumen tata kelola di bidang penelitian berupa RIP, Renstra, Renop dan Roadmap penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
97	1	2	5	3	1	PNP-5	LPPM memiliki pedoman pelaksanaan penelitian yang mencakup kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.	LPPM memiliki pedoman pelaksanaan penelitian yang mencakup kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
98	1	2	5	3	1	PNP-6	LPPM melakukan penilaian penelitian secara sistematis, obyektif, transparan, dan akuntabel.	LPPM melakukan penilaian penelitian secara sistematis, obyektif, transparan, dan akuntabel.	2024-10-15 06:13:07	2024-10-15 06:13:07
99	1	2	5	3	1	PNP-7	LPPM memiliki dokumen penjaminan mutu penelitian.	LPPM memiliki dokumen penjaminan mutu penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
100	1	2	5	3	1	PNP-8	LPPM melaksanakan penjaminan mutu penelitian sesuai dengan dokumen penjaminan mutu yang ditetapkan.	LPPM melaksanakan penjaminan mutu penelitian sesuai dengan dokumen penjaminan mutu yang ditetapkan.	2024-10-15 06:13:07	2024-10-15 06:13:07
101	1	2	5	3	1	PNP-9	LPPM memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.	LPPM memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.	2024-10-15 06:13:07	2024-10-15 06:13:07
102	1	2	5	3	1	PNP-10	LPPM memberikan akses kepada Fakultas/UPPS terkait laporan kinerja bidang penelitian.	LPPM memberikan akses kepada Fakultas/UPPS terkait laporan kinerja bidang penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
103	1	2	5	3	1	PNP-11	LPPM memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.	LPPM memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
104	1	2	6	3	1	PNM-2	Unsoed memiliki sarana dan prasarana penelitian; mekanisme penugasan dan ketersediaan sistem informasi manajemen yang tangguh untuk menjamin keberlanjutan dan peningkatan kompetensi dosen dalam hal penelitian.	Unsoed memiliki sarana dan prasarana penelitian; mekanisme penugasan dan ketersediaan sistem informasi manajemen yang tangguh untuk menjamin keberlanjutan dan peningkatan kompetensi dosen dalam hal penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
105	1	2	6	3	1	PNM-3	Unsoed menyediakan akses sarana dan prasarana penelitian; mekanisme penugasan dan ketersediaan sistem informasi manajemen yang tangguh untuk menjamin keberlanjutan dan peningkatan kompetensi dosen dalam hal penelitian.	Unsoed menyediakan akses sarana dan prasarana penelitian; mekanisme penugasan dan ketersediaan sistem informasi manajemen yang tangguh untuk menjamin keberlanjutan dan peningkatan kompetensi dosen dalam hal penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
106	1	2	6	3	1	PNM-4	Unsoed memiliki mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.	Unsoed memiliki mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
107	1	2	6	3	1	PNM-5	Unsoed memiliki sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.	Unsoed memiliki sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
108	1	2	6	3	1	PNM-6	Unsoed mengalokasikan dana penelitian paling sedikit 15% dari DIPA PNBP	Unsoed mengalokasikan dana penelitian paling sedikit 15% dari DIPA PNBP	2024-10-15 06:13:08	2024-10-15 06:13:08
109	1	3	7	3	1	PML-1	Dosen memiliki luaran wajib PkM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian.	Dosen memiliki luaran wajib PkM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian.	2024-10-15 06:13:08	2024-10-15 06:13:08
110	1	3	8	3	1	PMP-3	Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik.	Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik.	2024-10-15 06:13:08	2024-10-15 06:13:08
111	1	3	8	3	1	PMP-4	Dosen terlibat dalam kegiatan PkM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat.	Dosen terlibat dalam kegiatan PkM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat.	2024-10-15 06:13:08	2024-10-15 06:13:08
112	1	3	8	3	1	PMP-5	Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan.	Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan.	2024-10-15 06:13:08	2024-10-15 06:13:08
113	1	3	8	3	1	PMP-6	Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan.	Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan.	2024-10-15 06:13:08	2024-10-15 06:13:08
114	1	3	8	3	1	PMP-7	Tersedia dokumen ketentuan dalam kerja sama PkM.	Tersedia dokumen ketentuan dalam kerja sama PkM.	2024-10-15 06:13:08	2024-10-15 06:13:08
115	1	3	8	3	1	PMP-8	Tersedia dokumen persyaratan untuk publikasi hasil PKM dan ketentuan penulisannya.	Tersedia dokumen persyaratan untuk publikasi hasil PKM dan ketentuan penulisannya.	2024-10-15 06:13:08	2024-10-15 06:13:08
116	1	3	8	3	1	PMP-9	Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM	Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM	2024-10-15 06:13:08	2024-10-15 06:13:08
117	1	3	8	3	1	PMP-10	Tersedia dokumen renstra LPPM terkait yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.	Tersedia dokumen renstra LPPM terkait yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
118	1	3	8	3	1	PMP-11	Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.	Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.	2024-10-15 06:13:08	2024-10-15 06:13:08
119	1	3	8	3	1	PMP-12	Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.	Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.	2024-10-15 06:13:08	2024-10-15 06:13:08
120	1	3	8	3	1	PMP-13	Dosen melaksanakan kegiatan PkM (Pengabdian Kepada Masyarakat) sesuai dengan roadmap PKM.	Dosen melaksanakan kegiatan PkM (Pengabdian Kepada Masyarakat) sesuai dengan roadmap PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
121	1	3	9	3	1	PMM-2	Unsoed memiliki sarana dan prasarana PKM	Unsoed memiliki sarana dan prasarana PKM	2024-10-15 06:13:08	2024-10-15 06:13:08
122	1	3	9	3	1	PMM-3	Unsoed menyediakan akses ke sarana dan prasarana PKM	Unsoed menyediakan akses ke sarana dan prasarana PKM	2024-10-15 06:13:08	2024-10-15 06:13:08
123	1	3	9	3	1	PMM-4	Unsoed memiliki mekanisme penugasan dan peningkatan kompetensi dosen dalam hal PKM.	Unsoed memiliki mekanisme penugasan dan peningkatan kompetensi dosen dalam hal PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
124	1	3	9	3	1	PMM-5	Unsoed memiliki sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.	Unsoed memiliki sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
125	1	3	9	3	1	PMM-6	Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.	Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.	2024-10-15 06:13:09	2024-10-15 06:13:09
126	1	3	9	3	1	PMM-7	Luaran PkM disebarluarkan kepada masyarakat dalam bentuk hasil PkM.	Luaran PkM disebarluarkan kepada masyarakat dalam bentuk hasil PkM.	2024-10-15 06:13:09	2024-10-15 06:13:09
127	1	3	9	3	1	PMM-8	Unsoed mengalokasikan dana PkM paling sedikit 5% dari DIPA PNBP.	Unsoed mengalokasikan dana PkM paling sedikit 5% dari DIPA PNBP.	2024-10-15 06:13:09	2024-10-15 06:13:09
128	1	3	9	3	1	PMM-9	UPPS memiliki kelompok pelaksana PKM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.	UPPS memiliki kelompok pelaksana PKM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.	2024-10-15 06:13:09	2024-10-15 06:13:09
129	1	4	10	3	1	NAK-2	Terdapat dokumen kerjasama tingkat lokal, nasional atau internasional baik dalam bidang pendidikan, penelitian dan atau pengabdian	Terdapat dokumen kerjasama tingkat lokal, nasional atau internasional baik dalam bidang pendidikan, penelitian dan atau pengabdian	2024-10-15 06:13:09	2024-10-15 06:13:09
130	1	4	10	3	1	NAK-3	Tersedia dokumen kebijakan kerjasama di tingkat universitas	Tersedia dokumen kebijakan kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
131	1	4	10	3	1	NAK-4	Tersedia dokumen SOP Kerjasama di tingkat universitas	Tersedia dokumen SOP Kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
132	1	4	10	3	1	NAK-5	Tersedia dokumen rencana pengembangan kerjasama di tingkat universitas	Tersedia dokumen rencana pengembangan kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
133	1	4	10	3	1	NAK-6	Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama	Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama	2024-10-15 06:13:09	2024-10-15 06:13:09
134	1	4	10	3	1	NAK-7	Terdapat survey untuk mengetahui kepuasan mitra kerjasama yang diukur dengan instrumen yang sahih	Terdapat survey untuk mengetahui kepuasan mitra kerjasama yang diukur dengan instrumen yang sahih	2024-10-15 06:13:09	2024-10-15 06:13:09
135	1	4	11	3	1	NAL-1	Tersedia Unit Layanan Terpadu	Tersedia Unit Layanan Terpadu	2024-10-15 06:13:09	2024-10-15 06:13:09
136	1	4	11	3	1	NAL-2	Pelayanan terlaksana secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan	Pelayanan terlaksana secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan	2024-10-15 06:13:09	2024-10-15 06:13:09
137	1	4	11	3	1	NAL-3	Tersedia dokumen SOP layanan	Tersedia dokumen SOP layanan	2024-10-15 06:13:09	2024-10-15 06:13:09
138	1	4	11	3	1	NAL-4	Dilaksanakannya survei kepuasan layanan	Dilaksanakannya survei kepuasan layanan	2024-10-15 06:13:09	2024-10-15 06:13:09
139	1	4	12	3	1	NAT-3	Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi berasaskan pada prinsip efektifitas, efisiensi dan produktifitas	Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi berasaskan pada prinsip efektifitas, efisiensi dan produktifitas	2024-10-15 06:13:09	2024-10-15 06:13:09
140	1	4	13	3	1	NAM-4	Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru	Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru	2024-10-15 06:13:09	2024-10-15 06:13:09
141	1	4	13	3	1	NAM-5	Tersedia dokumen audit penerimaan mahasiswa baru	Tersedia dokumen audit penerimaan mahasiswa baru	2024-10-15 06:13:09	2024-10-15 06:13:09
142	1	4	13	3	1	NAM-6	Tersedia dokumen kebijakan kegiatan mahasiswa	Tersedia dokumen kebijakan kegiatan mahasiswa	2024-10-15 06:13:09	2024-10-15 06:13:09
143	1	4	13	3	1	NAM-7	Tersedia dokumen kebijakan terkait prestasi mahasiswa	Tersedia dokumen kebijakan terkait prestasi mahasiswa	2024-10-15 06:13:09	2024-10-15 06:13:09
144	1	4	14	3	1	NAU-1	Tersedia dokumen rancangan anggaran	Tersedia dokumen rancangan anggaran	2024-10-15 06:13:09	2024-10-15 06:13:09
145	1	4	14	3	1	NAU-2	Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu	Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu	2024-10-15 06:13:09	2024-10-15 06:13:09
146	1	4	14	3	1	NAU-3	Tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)	Tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)	2024-10-15 06:13:09	2024-10-15 06:13:09
147	1	4	14	3	1	NAU-4	Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan	Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan	2024-10-15 06:13:09	2024-10-15 06:13:09
148	1	4	15	3	1	NAS-4	Tersedia sarana prasarana umum di lingkungan universitas (guest house, masjid, asrama mahasiswa, GOR, poli klinik, Bis, Koperasi, dan Perpustakaan)	Tersedia sarana prasarana umum di lingkungan universitas (guest house, masjid, asrama mahasiswa, GOR, poli klinik, Bis, Koperasi, dan Perpustakaan)	2024-10-15 06:13:09	2024-10-15 06:13:09
149	1	1	1	1	1	PDL-6	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S1)	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S1)	2024-10-15 06:13:10	2024-10-15 06:13:10
150	1	1	1	1	1	PDL-6	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S2)	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S2)	2024-10-15 06:13:10	2024-10-15 06:13:10
151	1	1	1	1	1	PDL-6	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S3)	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (S3)	2024-10-15 06:13:10	2024-10-15 06:13:10
152	1	1	1	1	1	PDL-6	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (Profesi)	Program studi telah memiliki rumusan kompetensi utama lulusan yang memenuhi ketentuan minimal program pendidikan (Profesi)	2024-10-15 06:13:10	2024-10-15 06:13:10
153	1	1	1	1	2	PDL-9	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
154	1	1	1	1	2	PDL-9	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
155	1	1	1	1	2	PDL-9	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
156	1	1	1	1	2	PDL-9	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	Rata-rata IPK lulusan pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
157	1	1	1	1	2	PDL-10	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
158	1	1	1	1	2	PDL-10	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
159	1	1	1	1	2	PDL-10	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
160	1	1	1	1	2	PDL-10	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	Rata-rata masa studi lulusan program studi pada tahun TS (tahun sekarang)	2024-10-15 06:13:10	2024-10-15 06:13:10
161	1	1	1	1	2	PDL-11	Persentase lulusan tepat waktu pada jenjang pendidikan S1.	Persentase lulusan tepat waktu pada jenjang pendidikan S1.	2024-10-15 06:13:10	2024-10-15 06:13:10
162	1	1	1	1	2	PDL-11	Persentase lulusan tepat waktu pada jenjang pendidikan S2/Magister.	Persentase lulusan tepat waktu pada jenjang pendidikan S2/Magister.	2024-10-15 06:13:10	2024-10-15 06:13:10
163	1	1	1	1	2	PDL-11	Persentase lulusan tepat waktu pada jenjang pendidikan S3/Doktor.	Persentase lulusan tepat waktu pada jenjang pendidikan S3/Doktor.	2024-10-15 06:13:10	2024-10-15 06:13:10
164	1	1	1	1	2	PDL-11	Persentase lulusan tepat waktu pada jenjang pendidikan Profesi.	Persentase lulusan tepat waktu pada jenjang pendidikan Profesi.	2024-10-15 06:13:10	2024-10-15 06:13:10
165	1	1	1	1	2	PDL-12	Persentase tingkat kelulusan.	Persentase tingkat kelulusan.	2024-10-15 06:13:10	2024-10-15 06:13:10
166	1	1	1	1	2	PDL-12	Persentase tingkat kelulusan.	Persentase tingkat kelulusan.	2024-10-15 06:13:10	2024-10-15 06:13:10
167	1	1	1	1	2	PDL-12	Persentase tingkat kelulusan.	Persentase tingkat kelulusan.	2024-10-15 06:13:10	2024-10-15 06:13:10
168	1	1	1	1	2	PDL-12	Persentase tingkat kelulusan.	Persentase tingkat kelulusan.	2024-10-15 06:13:10	2024-10-15 06:13:10
169	1	1	2	3	1	PNP-24	Tersedia pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti yang paling lambat dilaksanakan 2 bulan setelah akhir semester	Tersedia pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti yang paling lambat dilaksanakan 2 bulan setelah akhir semester	2024-10-15 06:13:10	2024-10-15 06:13:10
170	1	1	2	3	1	PNP-25	Tersedia bukti dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan	Tersedia bukti dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan	2024-10-15 06:13:10	2024-10-15 06:13:10
171	1	1	1	2	1	NPM-1	Apakah UPPS telah melaksanakan Rapat Tinjauan Manajemen?	Apakah UPPS telah melaksanakan Rapat Tinjauan Manajemen?	2024-10-15 06:13:10	2024-10-15 06:13:10
\.


--
-- Data for Name: instrumen_jabatan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.instrumen_jabatan (instrumen_id, jabatan_id, created_at, updated_at) FROM stdin;
37	2	2024-10-15 06:13:05	2024-10-15 06:13:05
37	3	2024-10-15 06:13:05	2024-10-15 06:13:05
38	2	2024-10-15 06:13:05	2024-10-15 06:13:05
38	3	2024-10-15 06:13:05	2024-10-15 06:13:05
39	2	2024-10-15 06:13:05	2024-10-15 06:13:05
39	3	2024-10-15 06:13:05	2024-10-15 06:13:05
40	2	2024-10-15 06:13:05	2024-10-15 06:13:05
40	3	2024-10-15 06:13:05	2024-10-15 06:13:05
41	2	2024-10-15 06:13:05	2024-10-15 06:13:05
41	3	2024-10-15 06:13:05	2024-10-15 06:13:05
42	2	2024-10-15 06:13:05	2024-10-15 06:13:05
42	5	2024-10-15 06:13:05	2024-10-15 06:13:05
43	2	2024-10-15 06:13:05	2024-10-15 06:13:05
43	3	2024-10-15 06:13:05	2024-10-15 06:13:05
44	2	2024-10-15 06:13:05	2024-10-15 06:13:05
44	3	2024-10-15 06:13:05	2024-10-15 06:13:05
45	2	2024-10-15 06:13:05	2024-10-15 06:13:05
45	3	2024-10-15 06:13:05	2024-10-15 06:13:05
46	2	2024-10-15 06:13:05	2024-10-15 06:13:05
46	3	2024-10-15 06:13:05	2024-10-15 06:13:05
47	2	2024-10-15 06:13:05	2024-10-15 06:13:05
47	3	2024-10-15 06:13:05	2024-10-15 06:13:05
48	2	2024-10-15 06:13:05	2024-10-15 06:13:05
48	3	2024-10-15 06:13:05	2024-10-15 06:13:05
49	2	2024-10-15 06:13:05	2024-10-15 06:13:05
49	3	2024-10-15 06:13:05	2024-10-15 06:13:05
50	2	2024-10-15 06:13:05	2024-10-15 06:13:05
50	3	2024-10-15 06:13:05	2024-10-15 06:13:05
51	2	2024-10-15 06:13:05	2024-10-15 06:13:05
51	3	2024-10-15 06:13:05	2024-10-15 06:13:05
52	2	2024-10-15 06:13:05	2024-10-15 06:13:05
52	3	2024-10-15 06:13:05	2024-10-15 06:13:05
53	2	2024-10-15 06:13:06	2024-10-15 06:13:06
53	3	2024-10-15 06:13:06	2024-10-15 06:13:06
54	2	2024-10-15 06:13:06	2024-10-15 06:13:06
54	3	2024-10-15 06:13:06	2024-10-15 06:13:06
55	2	2024-10-15 06:13:06	2024-10-15 06:13:06
55	3	2024-10-15 06:13:06	2024-10-15 06:13:06
56	2	2024-10-15 06:13:06	2024-10-15 06:13:06
56	3	2024-10-15 06:13:06	2024-10-15 06:13:06
57	2	2024-10-15 06:13:06	2024-10-15 06:13:06
57	3	2024-10-15 06:13:06	2024-10-15 06:13:06
58	2	2024-10-15 06:13:06	2024-10-15 06:13:06
58	3	2024-10-15 06:13:06	2024-10-15 06:13:06
59	2	2024-10-15 06:13:06	2024-10-15 06:13:06
59	3	2024-10-15 06:13:06	2024-10-15 06:13:06
60	2	2024-10-15 06:13:06	2024-10-15 06:13:06
60	4	2024-10-15 06:13:06	2024-10-15 06:13:06
61	2	2024-10-15 06:13:06	2024-10-15 06:13:06
61	3	2024-10-15 06:13:06	2024-10-15 06:13:06
62	2	2024-10-15 06:13:06	2024-10-15 06:13:06
62	4	2024-10-15 06:13:06	2024-10-15 06:13:06
63	2	2024-10-15 06:13:06	2024-10-15 06:13:06
63	4	2024-10-15 06:13:06	2024-10-15 06:13:06
64	2	2024-10-15 06:13:06	2024-10-15 06:13:06
64	4	2024-10-15 06:13:06	2024-10-15 06:13:06
65	2	2024-10-15 06:13:06	2024-10-15 06:13:06
65	4	2024-10-15 06:13:06	2024-10-15 06:13:06
66	2	2024-10-15 06:13:06	2024-10-15 06:13:06
67	2	2024-10-15 06:13:06	2024-10-15 06:13:06
67	4	2024-10-15 06:13:06	2024-10-15 06:13:06
68	2	2024-10-15 06:13:06	2024-10-15 06:13:06
68	3	2024-10-15 06:13:06	2024-10-15 06:13:06
69	2	2024-10-15 06:13:06	2024-10-15 06:13:06
70	2	2024-10-15 06:13:06	2024-10-15 06:13:06
71	2	2024-10-15 06:13:06	2024-10-15 06:13:06
72	2	2024-10-15 06:13:06	2024-10-15 06:13:06
73	2	2024-10-15 06:13:06	2024-10-15 06:13:06
74	2	2024-10-15 06:13:06	2024-10-15 06:13:06
75	2	2024-10-15 06:13:06	2024-10-15 06:13:06
76	2	2024-10-15 06:13:06	2024-10-15 06:13:06
77	2	2024-10-15 06:13:06	2024-10-15 06:13:06
78	5	2024-10-15 06:13:06	2024-10-15 06:13:06
79	5	2024-10-15 06:13:06	2024-10-15 06:13:06
80	5	2024-10-15 06:13:07	2024-10-15 06:13:07
81	4	2024-10-15 06:13:07	2024-10-15 06:13:07
82	4	2024-10-15 06:13:07	2024-10-15 06:13:07
83	2	2024-10-15 06:13:07	2024-10-15 06:13:07
84	8	2024-10-15 06:13:07	2024-10-15 06:13:07
84	17	2024-10-15 06:13:07	2024-10-15 06:13:07
85	14	2024-10-15 06:13:07	2024-10-15 06:13:07
86	8	2024-10-15 06:13:07	2024-10-15 06:13:07
86	17	2024-10-15 06:13:07	2024-10-15 06:13:07
87	8	2024-10-15 06:13:07	2024-10-15 06:13:07
87	17	2024-10-15 06:13:07	2024-10-15 06:13:07
88	8	2024-10-15 06:13:07	2024-10-15 06:13:07
89	13	2024-10-15 06:13:07	2024-10-15 06:13:07
90	9	2024-10-15 06:13:07	2024-10-15 06:13:07
91	9	2024-10-15 06:13:07	2024-10-15 06:13:07
92	8	2024-10-15 06:13:07	2024-10-15 06:13:07
93	8	2024-10-15 06:13:07	2024-10-15 06:13:07
94	12	2024-10-15 06:13:07	2024-10-15 06:13:07
95	12	2024-10-15 06:13:07	2024-10-15 06:13:07
96	12	2024-10-15 06:13:07	2024-10-15 06:13:07
97	12	2024-10-15 06:13:07	2024-10-15 06:13:07
98	12	2024-10-15 06:13:07	2024-10-15 06:13:07
99	12	2024-10-15 06:13:07	2024-10-15 06:13:07
100	12	2024-10-15 06:13:07	2024-10-15 06:13:07
101	12	2024-10-15 06:13:07	2024-10-15 06:13:07
102	12	2024-10-15 06:13:08	2024-10-15 06:13:08
103	12	2024-10-15 06:13:08	2024-10-15 06:13:08
104	12	2024-10-15 06:13:08	2024-10-15 06:13:08
105	12	2024-10-15 06:13:08	2024-10-15 06:13:08
106	12	2024-10-15 06:13:08	2024-10-15 06:13:08
107	12	2024-10-15 06:13:08	2024-10-15 06:13:08
108	9	2024-10-15 06:13:08	2024-10-15 06:13:08
109	12	2024-10-15 06:13:08	2024-10-15 06:13:08
110	12	2024-10-15 06:13:08	2024-10-15 06:13:08
111	12	2024-10-15 06:13:08	2024-10-15 06:13:08
112	12	2024-10-15 06:13:08	2024-10-15 06:13:08
113	12	2024-10-15 06:13:08	2024-10-15 06:13:08
114	12	2024-10-15 06:13:08	2024-10-15 06:13:08
115	12	2024-10-15 06:13:08	2024-10-15 06:13:08
116	12	2024-10-15 06:13:08	2024-10-15 06:13:08
117	12	2024-10-15 06:13:08	2024-10-15 06:13:08
118	12	2024-10-15 06:13:08	2024-10-15 06:13:08
119	12	2024-10-15 06:13:08	2024-10-15 06:13:08
120	12	2024-10-15 06:13:08	2024-10-15 06:13:08
121	12	2024-10-15 06:13:08	2024-10-15 06:13:08
122	12	2024-10-15 06:13:08	2024-10-15 06:13:08
123	12	2024-10-15 06:13:08	2024-10-15 06:13:08
124	12	2024-10-15 06:13:08	2024-10-15 06:13:08
125	12	2024-10-15 06:13:09	2024-10-15 06:13:09
126	12	2024-10-15 06:13:09	2024-10-15 06:13:09
127	9	2024-10-15 06:13:09	2024-10-15 06:13:09
128	12	2024-10-15 06:13:09	2024-10-15 06:13:09
129	11	2024-10-15 06:13:09	2024-10-15 06:13:09
130	11	2024-10-15 06:13:09	2024-10-15 06:13:09
131	11	2024-10-15 06:13:09	2024-10-15 06:13:09
132	11	2024-10-15 06:13:09	2024-10-15 06:13:09
133	11	2024-10-15 06:13:09	2024-10-15 06:13:09
134	12	2024-10-15 06:13:09	2024-10-15 06:13:09
135	15	2024-10-15 06:13:09	2024-10-15 06:13:09
136	15	2024-10-15 06:13:09	2024-10-15 06:13:09
137	15	2024-10-15 06:13:09	2024-10-15 06:13:09
138	15	2024-10-15 06:13:09	2024-10-15 06:13:09
139	9	2024-10-15 06:13:09	2024-10-15 06:13:09
140	8	2024-10-15 06:13:09	2024-10-15 06:13:09
141	8	2024-10-15 06:13:09	2024-10-15 06:13:09
142	10	2024-10-15 06:13:09	2024-10-15 06:13:09
143	10	2024-10-15 06:13:09	2024-10-15 06:13:09
144	9	2024-10-15 06:13:09	2024-10-15 06:13:09
145	8	2024-10-15 06:13:09	2024-10-15 06:13:09
146	9	2024-10-15 06:13:09	2024-10-15 06:13:09
147	9	2024-10-15 06:13:09	2024-10-15 06:13:09
148	9	2024-10-15 06:13:09	2024-10-15 06:13:09
169	16	2024-10-15 06:13:10	2024-10-15 06:13:10
170	16	2024-10-15 06:13:10	2024-10-15 06:13:10
171	2	2024-10-15 06:13:10	2024-10-15 06:13:10
171	3	2024-10-15 06:13:10	2024-10-15 06:13:10
171	4	2024-10-15 06:13:10	2024-10-15 06:13:10
171	5	2024-10-15 06:13:10	2024-10-15 06:13:10
\.


--
-- Data for Name: instrumen_jenjang; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.instrumen_jenjang (instrumen_id, jenjang_id, prodi_id, unit_id, created_at, updated_at) FROM stdin;
1	1	\N	\N	\N	\N
1	2	\N	\N	\N	\N
1	3	\N	\N	\N	\N
1	4	\N	\N	\N	\N
1	5	\N	\N	\N	\N
2	1	\N	\N	\N	\N
2	2	\N	\N	\N	\N
2	3	\N	\N	\N	\N
2	4	\N	\N	\N	\N
2	5	\N	\N	\N	\N
3	1	\N	\N	\N	\N
3	2	\N	\N	\N	\N
3	3	\N	\N	\N	\N
3	4	\N	\N	\N	\N
3	5	\N	\N	\N	\N
4	1	\N	\N	\N	\N
4	2	\N	\N	\N	\N
4	3	\N	\N	\N	\N
4	4	\N	\N	\N	\N
4	5	\N	\N	\N	\N
5	1	\N	\N	\N	\N
5	2	\N	\N	\N	\N
5	3	\N	\N	\N	\N
5	4	\N	\N	\N	\N
5	5	\N	\N	\N	\N
6	1	\N	\N	\N	\N
7	1	\N	\N	\N	\N
7	2	\N	\N	\N	\N
7	3	\N	\N	\N	\N
7	4	\N	\N	\N	\N
7	5	\N	\N	\N	\N
8	1	\N	\N	\N	\N
8	2	\N	\N	\N	\N
8	3	\N	\N	\N	\N
8	4	\N	\N	\N	\N
8	5	\N	\N	\N	\N
9	1	\N	\N	\N	\N
10	1	\N	\N	\N	\N
11	1	\N	\N	\N	\N
12	1	\N	\N	\N	\N
13	1	\N	\N	\N	\N
13	2	\N	\N	\N	\N
13	3	\N	\N	\N	\N
13	4	\N	\N	\N	\N
13	5	\N	\N	\N	\N
14	1	\N	\N	\N	\N
14	2	\N	\N	\N	\N
14	3	\N	\N	\N	\N
15	1	\N	\N	\N	\N
15	2	\N	\N	\N	\N
15	3	\N	\N	\N	\N
15	4	\N	\N	\N	\N
15	5	\N	\N	\N	\N
16	1	\N	\N	\N	\N
16	2	\N	\N	\N	\N
17	2	\N	\N	\N	\N
17	3	\N	\N	\N	\N
17	4	\N	\N	\N	\N
17	5	\N	\N	\N	\N
18	2	\N	\N	\N	\N
18	3	\N	\N	\N	\N
18	4	\N	\N	\N	\N
18	5	\N	\N	\N	\N
19	2	\N	\N	\N	\N
19	3	\N	\N	\N	\N
19	4	\N	\N	\N	\N
19	5	\N	\N	\N	\N
20	2	\N	\N	\N	\N
20	3	\N	\N	\N	\N
20	4	\N	\N	\N	\N
20	5	\N	\N	\N	\N
21	2	\N	\N	\N	\N
21	3	\N	\N	\N	\N
21	4	\N	\N	\N	\N
21	5	\N	\N	\N	\N
22	2	\N	\N	\N	\N
22	3	\N	\N	\N	\N
22	4	\N	\N	\N	\N
22	5	\N	\N	\N	\N
23	\N	9d3fc7a8-ab62-4854-95ff-7dc0bef8db43	\N	2024-10-15 06:13:04	2024-10-15 06:13:04
23	\N	9d3fc7a8-a83f-4b34-a95e-9765f9b3cd7b	\N	2024-10-15 06:13:04	2024-10-15 06:13:04
24	1	\N	\N	\N	\N
24	2	\N	\N	\N	\N
24	3	\N	\N	\N	\N
24	4	\N	\N	\N	\N
24	5	\N	\N	\N	\N
25	1	\N	\N	\N	\N
25	2	\N	\N	\N	\N
25	3	\N	\N	\N	\N
25	4	\N	\N	\N	\N
25	5	\N	\N	\N	\N
26	1	\N	\N	\N	\N
26	2	\N	\N	\N	\N
26	3	\N	\N	\N	\N
26	4	\N	\N	\N	\N
26	5	\N	\N	\N	\N
27	1	\N	\N	\N	\N
27	2	\N	\N	\N	\N
27	3	\N	\N	\N	\N
27	4	\N	\N	\N	\N
27	5	\N	\N	\N	\N
28	1	\N	\N	\N	\N
28	2	\N	\N	\N	\N
28	3	\N	\N	\N	\N
28	4	\N	\N	\N	\N
28	5	\N	\N	\N	\N
29	1	\N	\N	\N	\N
29	2	\N	\N	\N	\N
29	3	\N	\N	\N	\N
29	4	\N	\N	\N	\N
29	5	\N	\N	\N	\N
30	1	\N	\N	\N	\N
30	2	\N	\N	\N	\N
30	3	\N	\N	\N	\N
30	4	\N	\N	\N	\N
30	5	\N	\N	\N	\N
31	1	\N	\N	\N	\N
31	2	\N	\N	\N	\N
31	3	\N	\N	\N	\N
31	4	\N	\N	\N	\N
31	5	\N	\N	\N	\N
32	4	\N	\N	\N	\N
33	1	\N	\N	\N	\N
33	2	\N	\N	\N	\N
33	3	\N	\N	\N	\N
33	4	\N	\N	\N	\N
33	5	\N	\N	\N	\N
34	1	\N	\N	\N	\N
34	2	\N	\N	\N	\N
34	3	\N	\N	\N	\N
34	4	\N	\N	\N	\N
34	5	\N	\N	\N	\N
35	1	\N	\N	\N	\N
35	2	\N	\N	\N	\N
35	3	\N	\N	\N	\N
35	4	\N	\N	\N	\N
35	5	\N	\N	\N	\N
36	1	\N	\N	\N	\N
84	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
84	\N	\N	9d3fc77d-a0b2-48b6-afee-335aabe2f441	2024-10-15 06:13:07	2024-10-15 06:13:07
85	\N	\N	9d3fc77d-98dd-45e7-9cfe-fe20a9a85ae7	2024-10-15 06:13:07	2024-10-15 06:13:07
86	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
86	\N	\N	9d3fc77d-a0b2-48b6-afee-335aabe2f441	2024-10-15 06:13:07	2024-10-15 06:13:07
87	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
87	\N	\N	9d3fc77d-a0b2-48b6-afee-335aabe2f441	2024-10-15 06:13:07	2024-10-15 06:13:07
88	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
89	\N	\N	9d3fc77d-a2d9-4a34-bae4-0e50d6f422da	2024-10-15 06:13:07	2024-10-15 06:13:07
90	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
91	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
92	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
93	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:07	2024-10-15 06:13:07
94	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
95	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
96	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
97	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
98	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
99	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
100	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
101	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:07	2024-10-15 06:13:07
102	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
103	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
104	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
105	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
106	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
107	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
108	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:08	2024-10-15 06:13:08
109	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
110	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
111	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
112	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
113	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
114	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
115	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
116	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
117	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
118	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
119	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
120	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
121	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
122	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
123	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
124	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:08	2024-10-15 06:13:08
125	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:09	2024-10-15 06:13:09
126	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:09	2024-10-15 06:13:09
127	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
128	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:09	2024-10-15 06:13:09
129	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
130	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
131	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
132	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
133	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
134	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:13:09	2024-10-15 06:13:09
135	\N	\N	9d3fc77d-a515-4039-9a5f-65d8ab715540	2024-10-15 06:13:09	2024-10-15 06:13:09
136	\N	\N	9d3fc77d-a515-4039-9a5f-65d8ab715540	2024-10-15 06:13:09	2024-10-15 06:13:09
137	\N	\N	9d3fc77d-a515-4039-9a5f-65d8ab715540	2024-10-15 06:13:09	2024-10-15 06:13:09
138	\N	\N	9d3fc77d-a515-4039-9a5f-65d8ab715540	2024-10-15 06:13:09	2024-10-15 06:13:09
139	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
140	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
141	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
142	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
143	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
144	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
145	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
146	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
147	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
148	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:13:09	2024-10-15 06:13:09
149	2	\N	\N	\N	\N
150	3	\N	\N	\N	\N
151	4	\N	\N	\N	\N
152	5	\N	\N	\N	\N
153	2	\N	\N	\N	\N
154	5	\N	\N	\N	\N
155	3	\N	\N	\N	\N
156	4	\N	\N	\N	\N
157	2	\N	\N	\N	\N
158	3	\N	\N	\N	\N
159	4	\N	\N	\N	\N
160	5	\N	\N	\N	\N
161	2	\N	\N	\N	\N
162	3	\N	\N	\N	\N
163	4	\N	\N	\N	\N
164	5	\N	\N	\N	\N
165	2	\N	\N	\N	\N
166	3	\N	\N	\N	\N
167	4	\N	\N	\N	\N
168	5	\N	\N	\N	\N
169	\N	\N	9d3fc77d-9e8b-4654-bd94-ee0960a729cb	2024-10-15 06:13:10	2024-10-15 06:13:10
170	\N	\N	9d3fc77d-9e8b-4654-bd94-ee0960a729cb	2024-10-15 06:13:10	2024-10-15 06:13:10
\.


--
-- Data for Name: instrumen_kriteria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.instrumen_kriteria (instrumen_id, kriteria_id, isi, created_at, updated_at) FROM stdin;
1	1	Rumusan CPL belum mencakup aspek sikap, pengetahuan, keterampilan umum, dan keterampilan khusus	2024-10-15 06:13:03	2024-10-15 06:13:03
1	2	CPL memenuhi kompetensi yang mencakup aspek; sikap, pengetahuan, keterampilan umum, dan keterampilan khusus	2024-10-15 06:13:03	2024-10-15 06:13:03
1	3	PS Memiliki kompetensi tambahan sebagai penciri program studi	2024-10-15 06:13:03	2024-10-15 06:13:03
2	1	CPL disusun dengan melibatkan sebagian dari pemangku kepentingan	2024-10-15 06:13:03	2024-10-15 06:13:03
2	2	Keterlibatan pemangu kepentingan dalam penyusunan CPL meliputi pihak internal dan eksternal (termasuk asosiasi dan iduka (dunia industri dan dunia kerja))	2024-10-15 06:13:03	2024-10-15 06:13:03
2	3	-	2024-10-15 06:13:03	2024-10-15 06:13:03
3	1	CPL yang disusun belum memperhatikan 7 aspek.	2024-10-15 06:13:03	2024-10-15 06:13:03
3	2	CPL yang disusun dengan memperhatikan 7 aspek.	2024-10-15 06:13:03	2024-10-15 06:13:03
3	3	CPL dievaluasi secara rutin dan terdokumentasi	2024-10-15 06:13:03	2024-10-15 06:13:03
4	1	Belum semua matakuliah melaksanakan (kurang dari 100%)	2024-10-15 06:13:03	2024-10-15 06:13:03
4	2	100% matakuliah CPL dan CPMK nya telah disosialisasikan kepada mahasiswa oleh dosen penanggungjawab matakuliah melalui eldiru/di kelas 	2024-10-15 06:13:03	2024-10-15 06:13:03
4	3	Tersedia bukti sosialisasi RPS	2024-10-15 06:13:03	2024-10-15 06:13:03
5	1	Kurikulum PS belum memuat peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL	2024-10-15 06:13:03	2024-10-15 06:13:03
5	2	Dokumen kurikulum PS memuat peta kurikulum yang menunjukkan kaitan dan kontribusi mata kuliah terhadap CPL	2024-10-15 06:13:03	2024-10-15 06:13:03
5	3	Kurikulum PS telah dilengkapi dengan instrumen pengukuran pemenuhan CPL	2024-10-15 06:13:03	2024-10-15 06:13:03
6	1	Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.	2024-10-15 06:13:03	2024-10-15 06:13:03
6	2	Kompetensi Utama Lulusan Program studi memenuhi ketentuan :\n                Program D3, minimal:\n                1.\tmenguasai konsep teoretis bidang pengetahuan dan keterampilan tertentu secara umum;\n                2.\tmampu menyelesaikan pekerjaan berlingkup luas; dan\n                3.\tmampu memilih metode yang sesuai dari beragam pilihan yang sudah maupun belum baku berdasarkan analisis data;	2024-10-15 06:13:03	2024-10-15 06:13:03
6	3	Memiliki Kompetensi tambahan sebagai penciri PS	2024-10-15 06:13:03	2024-10-15 06:13:03
7	1	Belum melibatkan asosiasi dan pihak terkait(pengguna lulusan, organisasi profesi, alumni)	2024-10-15 06:13:03	2024-10-15 06:13:03
7	2	Kompetensi utama lulusan mengacu pada kompetensi yang disusun oleh asosiasi prodi dan pihak terkait (pengguna lulusan, organisasi profesi, alumni)	2024-10-15 06:13:03	2024-10-15 06:13:03
7	3	Terdokumentasi	2024-10-15 06:13:03	2024-10-15 06:13:03
8	1	Belum melaksanakan asesmen terhadap ketercapaian CPL	2024-10-15 06:13:03	2024-10-15 06:13:03
8	2	Terdapat bukti sahih pelaksanaan  asesmen terhadap ketercapaian CPL Program Studi	2024-10-15 06:13:03	2024-10-15 06:13:03
8	3	Program studi melakukan asesmen terhadap ketercapaian CPL yang dilaksanakan secara rutin (setiap semester) dan hasilnya terdokumentasi dengan baik	2024-10-15 06:13:03	2024-10-15 06:13:03
9	1	Kurang dari D3 = 2,10\n                	2024-10-15 06:13:03	2024-10-15 06:13:03
9	2	Rata-rata IPK Lulusan D3 = 2,10	2024-10-15 06:13:03	2024-10-15 06:13:03
9	3	Lebih dari D3 = 2,10	2024-10-15 06:13:03	2024-10-15 06:13:03
10	1	-	2024-10-15 06:13:03	2024-10-15 06:13:03
10	2	Rata-rata masa studi lulusan : D3 = 12 semester	2024-10-15 06:13:03	2024-10-15 06:13:03
10	3	Kurang dari : D3 = 12 semester	2024-10-15 06:13:03	2024-10-15 06:13:03
11	1	Prosentase Lulusan Tepat waktu D3 : < 30%	2024-10-15 06:13:03	2024-10-15 06:13:03
11	2	Prosentase Lulusan Tepat waktu D3 = 30-80%	2024-10-15 06:13:03	2024-10-15 06:13:03
11	3	Prosentase Lulusan Tepat waktu D3 > 80%	2024-10-15 06:13:03	2024-10-15 06:13:03
12	1	Lebih dari D3 = 75%	2024-10-15 06:13:03	2024-10-15 06:13:03
12	2	Produktivitas (tingkat kelulusan) D3 = 75%	2024-10-15 06:13:03	2024-10-15 06:13:03
12	3	Kurang dari D3 = 75%	2024-10-15 06:13:03	2024-10-15 06:13:03
13	1	Memiliki prestasi akademik mahasiswa ditingkat lokal	2024-10-15 06:13:03	2024-10-15 06:13:03
13	2	Memiliki prestasi akademik mahasiswa ditingkat nasional minimal 2 buah	2024-10-15 06:13:03	2024-10-15 06:13:03
13	3	Memiliki prestasi  akademik mahasiswa ditingkat Internasional minimal 1 buah	2024-10-15 06:13:03	2024-10-15 06:13:03
14	1	Memiliki prestasi akademik mahasiswa ditingkat lokal	2024-10-15 06:13:04	2024-10-15 06:13:04
14	2	Memiliki prestasi non akademik mahasiswa ditingkat nasional minimal 3 buah	2024-10-15 06:13:04	2024-10-15 06:13:04
14	3	Memiliki prestasi akademik mahasiswa ditingkat internasional minimal 1 buah	2024-10-15 06:13:04	2024-10-15 06:13:04
15	1	Kesesuaian bidang kerja: <50% sesuai	2024-10-15 06:13:04	2024-10-15 06:13:04
15	2	Kesesuaian bidang kerja= 50% 	2024-10-15 06:13:04	2024-10-15 06:13:04
15	3	Kesesuaian bidang kerja: >50% sesuai 	2024-10-15 06:13:04	2024-10-15 06:13:04
16	1	Lebih dari 6 bulan	2024-10-15 06:13:04	2024-10-15 06:13:04
16	2	Masa tunggu lulusan = 6 bulan	2024-10-15 06:13:04	2024-10-15 06:13:04
16	3	Kurang dari 6 bulan	2024-10-15 06:13:04	2024-10-15 06:13:04
17	1	Kurang dari S1 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-6\n                S2 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-2\n                S3 = Jumlah publikasi prosiding/jurnal internasional bereputasi/sinta 1 minimal 1 buah	2024-10-15 06:13:04	2024-10-15 06:13:04
17	2	S1 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-6\n                S2 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-2\n                S3 = Jumlah publikasi prosiding/jurnal internasional bereputasi/sinta 1 minimal 1 buah	2024-10-15 06:13:04	2024-10-15 06:13:04
17	3	S1 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-6 lebih dari 1 buah\n                S2 = Jumlah publikasi prosiding/jurnal nasional terakreditasi sinta 1-2 lebih dari 1 buah\n                S3 = Jumlah publikasi prosiding/jurnal internasional bereputasi /sinta 1 minimal 1 buah	2024-10-15 06:13:04	2024-10-15 06:13:04
18	1	Tidak ada mahasiswa yang melakukan diseminasi pada seminar nasional/internasional	2024-10-15 06:13:04	2024-10-15 06:13:04
18	2	S1: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional minimal 1 orang\n                S2: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional minimal 2 orang\n                S3: Jumlah mahasiswa yang melakukan diseminasi pada seminar internasional minimal 1 orang	2024-10-15 06:13:04	2024-10-15 06:13:04
18	3	S1: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional lebih adri 1 orang\n                S2: Jumlah mahasiswa yang melakukan diseminasi pada seminar nasional lebih dari 2 orang\n                S3: Jumlah mahasiswa yang melakukan diseminasi pada seminar internasional lebih dari 1 orang	2024-10-15 06:13:04	2024-10-15 06:13:04
19	1	Tidak ada	2024-10-15 06:13:04	2024-10-15 06:13:04
19	2	Menulis pada media massa = 1 	2024-10-15 06:13:04	2024-10-15 06:13:04
19	3	Menulis pada media massa >1 	2024-10-15 06:13:04	2024-10-15 06:13:04
20	1	Tidak ada	2024-10-15 06:13:04	2024-10-15 06:13:04
20	2	Luaran mahasiswa dalam bentuk HAKI = 1 buah 	2024-10-15 06:13:04	2024-10-15 06:13:04
20	3	Luaran mahasiswa dalam bentuk HAKI > 1 buah	2024-10-15 06:13:04	2024-10-15 06:13:04
21	1	Tidak ada	2024-10-15 06:13:04	2024-10-15 06:13:04
21	2	Luaran mahasiswa dalam bentuk book chapter = 1 	2024-10-15 06:13:04	2024-10-15 06:13:04
21	3	Luaran mahasiswa dalam bentuk book chapter > 1 	2024-10-15 06:13:04	2024-10-15 06:13:04
22	1	Tidak ada	2024-10-15 06:13:04	2024-10-15 06:13:04
22	2	Luaran mahasiswa dalam bentuk buku = 1 	2024-10-15 06:13:04	2024-10-15 06:13:04
22	3	Luaran mahasiswa dalam bentuk buku > 1 	2024-10-15 06:13:04	2024-10-15 06:13:04
23	1	Kurang dari 65%	2024-10-15 06:13:04	2024-10-15 06:13:04
23	2	Prosentase Kelulusan pada first taker (PFT) pada pengujian kompetensi nasional (LAM PT Kes) = 65%	2024-10-15 06:13:04	2024-10-15 06:13:04
23	3	Lebih dari 65%	2024-10-15 06:13:04	2024-10-15 06:13:04
24	1	PS belum memiliki RPS untuk semua mata kuliah (100%)	2024-10-15 06:13:04	2024-10-15 06:13:04
24	2	Ketersediaan RPS pada semua mk (100%)	2024-10-15 06:13:04	2024-10-15 06:13:04
24	3	PS sudah memiliki RPS untuk semua mata kuliah (100%) dan telah melakukan pembaharuan pada tahun ajaran terakhir	2024-10-15 06:13:04	2024-10-15 06:13:04
25	1	RPS yang dimiliki PS belum mencakup keseluruhan aspek: perencanaan, pelaksanaan dan penilaian proses pembelajaran	2024-10-15 06:13:04	2024-10-15 06:13:04
25	2	Dokumen Rencana Pembelajaran Semester (RPS) mata kuliah mencakup tiga aspek: a. perencanaan proses pembelajaran; b. pelaksanaan proses pembelajaran; dan c. penilaian proses pembelajaran.	2024-10-15 06:13:04	2024-10-15 06:13:04
25	3	RPS yang dimiliki PS telah mencakup unsur perencanaan, pelaksanaan dan penilaian pada proses pembelajaran, memiliki format penulisan seragam dan/atau dilengkapi dengan contoh soal ujian	2024-10-15 06:13:04	2024-10-15 06:13:04
26	1	PS belum melaksanakan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester	2024-10-15 06:13:04	2024-10-15 06:13:04
26	2	Proses pembelajaran dilaksanakan dengan sistem SKS dengan ketentuan 1 SKS setara dengan 45 jam per semester	2024-10-15 06:13:04	2024-10-15 06:13:04
26	3	-	2024-10-15 06:13:04	2024-10-15 06:13:04
27	1	PS melaksanakan pemenuhan beban belajar dalam bentuk kuliah.	2024-10-15 06:13:04	2024-10-15 06:13:04
27	2	Pemenuhan beban belajar berupa: bentuk kuliah, responsi, tutorial, seminar, praktikum, praktik, studio, penelitian, perancangan, pengembangan, tugas akhir, pelatihan bela negara, pertukaran pelajar, magang, wirausaha, pengabdian kepada masyarakat, dan/atau bentuk pembelajaran lain. Bentuk pembelajaran melalui kegiatan belajar terbimbing, penugasan terstruktur, dan/atau mandiri.	2024-10-15 06:13:04	2024-10-15 06:13:04
27	3	PS melakukan monitoring, evaluasi dan menindaklanjuti pemenuhan beban belajar.	2024-10-15 06:13:04	2024-10-15 06:13:04
28	1	Belum 100% Mata Kuliah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif	2024-10-15 06:13:04	2024-10-15 06:13:04
28	2	100% Mata Kuliah melaksanakan penilaian dan hasil evaluasi proses pembelajaran dengan mengikuti pedoman penilaian dan hasil evaluasi proses pembelajaran yang valid, reliabel, transparan, akuntabel, berkeadilan, objektif, dan edukatif	2024-10-15 06:13:04	2024-10-15 06:13:04
28	3	PS melakukan monitoring, evaluasi dan menindaklanjuti pemenuhan pelaksanaan penilaian hasil evaluasi proses pembelajaran	2024-10-15 06:13:04	2024-10-15 06:13:04
29	1	Kurang dari 100% mata kuliah.	2024-10-15 06:13:04	2024-10-15 06:13:04
29	2	100% Mata Kuliah melakukan penilaian hasil belajar mahasiswa dalam bentuk penilaian formatif dan penilaian sumatif.	2024-10-15 06:13:04	2024-10-15 06:13:04
29	3	PS melakukan monitoring, evaluasi dan menindaklanjuti pelaksanaan penilaian belajar.	2024-10-15 06:13:04	2024-10-15 06:13:04
30	1	<100% Mata Kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa.	2024-10-15 06:13:04	2024-10-15 06:13:04
30	2	100% Mata Kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa dan tersedia rubrik penilaian di RPS.	2024-10-15 06:13:04	2024-10-15 06:13:04
30	3	100% Mata Kuliah yang melakukan sosialisasi mekanisme penilaian kepada mahasiswa dan tersedia rubrik penilaian di RPS	2024-10-15 06:13:04	2024-10-15 06:13:04
31	1	Tidak tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).	2024-10-15 06:13:04	2024-10-15 06:13:04
31	2	Tersedia dokumen bukti sosialisasi mekanisme penilaian kepada mahasiswa (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian).	2024-10-15 06:13:04	2024-10-15 06:13:04
31	3	Bukti sosialisasi mekanisme penilaian kepada mahasiswa (misalnya dalam kontrak pembelajaran yang menjelaskan mekanisme penilaian) setiap semester terdokumentasi secara konsisten dan mudah diakses	2024-10-15 06:13:04	2024-10-15 06:13:04
32	1	Program doktor belum mewajibkan pelibatan penguji dari luar perguruan tinggi.	2024-10-15 06:13:05	2024-10-15 06:13:05
32	2	Program doktor mewajibkan pelibatan penguji dari luar perguruan tinggi.	2024-10-15 06:13:05	2024-10-15 06:13:05
32	3	Program doktor mewajibkan pelibatan penguji dari luar perguruan tinggi berdasarkan standar kualifikasi penguji eksternal.	2024-10-15 06:13:05	2024-10-15 06:13:05
33	1	Tidak tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.	2024-10-15 06:13:05	2024-10-15 06:13:05
33	2	Tersedia dokumen kurikulum PS yang mencakup materi pembelajaran mengacu pada capaian pembelajaran lulusan dengan tingkat kedalaman dan keluasan sesuai jenis dan program pendidikan, serta standar kompetensi lulusan, dengan memperhatikan perkembangan IPTEK terkini dan relevansinya dengan dunia kerja.	2024-10-15 06:13:05	2024-10-15 06:13:05
33	3	Kurikulum PS berbasis Outcome (OBE) dan dievaluasi secara rutin dan terdokumentasi dengan baik.	2024-10-15 06:13:05	2024-10-15 06:13:05
34	1	Tidak tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk mata kuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah	2024-10-15 06:13:05	2024-10-15 06:13:05
34	2	Tersedia dokumen RPS yang mencakup materi pembelajaran dalam bentuk matakuliah, modul, blok tematik atau bentuk lainnya, dan dapat diperkaya dengan program kompetensi mikro pada semua mata kuliah	2024-10-15 06:13:05	2024-10-15 06:13:05
34	3	RPS dievaluasi secara secara konsisten dan dapat diakses mahasiswa di Eldiru.	2024-10-15 06:13:05	2024-10-15 06:13:05
35	1	Tidak tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.	2024-10-15 06:13:05	2024-10-15 06:13:05
35	2	Tersedia dokumen kurikulum PS yang mencakup CPL, masa tempuh kurikulum, metode pembelajaran, modalitas pembelajaran, syarat kompetensi dan/atau kualifikasi calon mahasiswa, penilaian hasil belajar, dan materi pembelajaran, serta tata cara penerimaan mahasiswa pada berbagai tahapan kurikulum.	2024-10-15 06:13:05	2024-10-15 06:13:05
35	3	Kurikulum PS  dievaluasi secara konsisten,  terdokumentasi   lengkap dan transparan dalam web prodi. 	2024-10-15 06:13:05	2024-10-15 06:13:05
36	1	Tidak tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry	2024-10-15 06:13:05	2024-10-15 06:13:05
36	2	Tersedia dokumen kurikulum PS Vokasi yang menerapkan kurikulum sistem ganda yang memfasilitasi mahasiswa untuk melaksanakan pembelajaran gabungan di perguruan tinggi dengan magang di dunia kerja/usaha/industri dan/atau dalam bentuk teaching industry	2024-10-15 06:13:05	2024-10-15 06:13:05
36	3	Kurikulum dievaluasi secara konsisten, terdokumentasi   lengkap dan transparan dalam web fakultas	2024-10-15 06:13:05	2024-10-15 06:13:05
37	1	< 50%	2024-10-15 06:13:05	2024-10-15 06:13:05
37	2	=  50 %	2024-10-15 06:13:05	2024-10-15 06:13:05
37	3	> 50 %	2024-10-15 06:13:05	2024-10-15 06:13:05
38	1	< 80%	2024-10-15 06:13:05	2024-10-15 06:13:05
38	2	=  80 %	2024-10-15 06:13:05	2024-10-15 06:13:05
38	3	> 80 %	2024-10-15 06:13:05	2024-10-15 06:13:05
39	1	< 75%	2024-10-15 06:13:05	2024-10-15 06:13:05
39	2	=  75 %	2024-10-15 06:13:05	2024-10-15 06:13:05
39	3	> 75 %	2024-10-15 06:13:05	2024-10-15 06:13:05
40	1	< 80%	2024-10-15 06:13:05	2024-10-15 06:13:05
40	2	=  80 %	2024-10-15 06:13:05	2024-10-15 06:13:05
40	3	> 80 %	2024-10-15 06:13:05	2024-10-15 06:13:05
41	1	< 50%	2024-10-15 06:13:05	2024-10-15 06:13:05
41	2	=  50 %	2024-10-15 06:13:05	2024-10-15 06:13:05
41	3	> 50 %	2024-10-15 06:13:05	2024-10-15 06:13:05
42	1	Pelaksanaan tracer studi  mencakup < 5 aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
42	2	Pelaksanaan tracer studi  mencakup 5 aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
42	3	-	2024-10-15 06:13:05	2024-10-15 06:13:05
43	1	Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup kurang dari tiga aspek:\n                a.\tperencanaan proses pembelajaran;\n                b.\tpelaksanaan proses pembelajaran; dan\n                c.\tpenilaian proses pembelajaran.	2024-10-15 06:13:05	2024-10-15 06:13:05
43	2	Tersedia dokumen Rencana Pembelajaran Semester (RPS) mata kuliah yang mencakup tiga aspek:\n                a.\tperencanaan proses pembelajaran;\n                b.\tpelaksanaan proses pembelajaran; dan\n                c.\tpenilaian proses pembelajaran.	2024-10-15 06:13:05	2024-10-15 06:13:05
43	3	Tersedianya dokumen  Rencana Pembelajaran Semester (RPS)  yang mencakup lebih dari 3 aspek antara lain target capaian pembelajaran, bahan kajian, metode pembelajaran,  waktu dan tahapan asesmen, hasil capaian pembelajaran, disosialisasikan, ditinjau dan disesuaikan secara berkala dan dapat diakses oleh mahasiswa	2024-10-15 06:13:05	2024-10-15 06:13:05
44	1	Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran <4 aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
44	2	Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4 aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
44	3	Tersedia dokumen hasil survey kepuasan mahasiswa terhadap penyelenggaraan pembelajaran berdasarkan 4   aspek dan disosialisasikan melalui berbagai media sosial yang dimiliki UPPS	2024-10-15 06:13:05	2024-10-15 06:13:05
45	1	Tidak tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan.	2024-10-15 06:13:05	2024-10-15 06:13:05
45	2	Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan.	2024-10-15 06:13:05	2024-10-15 06:13:05
45	3	Tersedia dokumen penetapan beban belajar dalam sistem blok, modul, atau bentuk lain sesuai kebutuhan pemenuhan capaian pembelajaran lulusan yang disosialisasikan dan ditinjau secara berkala.	2024-10-15 06:13:05	2024-10-15 06:13:05
46	1	Tidak tersedia tidak tersedia dokumen kebijakan MBKM 	2024-10-15 06:13:05	2024-10-15 06:13:05
46	2	Tersedia dokumen kebijakan MBKM yang meliputi dokumen pada level universitas 	2024-10-15 06:13:05	2024-10-15 06:13:05
46	3	Tersedia dokumen kebijakan MBKM yang meliputi dokumen pada level universitas dan UPPS yang disosialisasikan  media sosial fakultas/prodi  dan ditinjau secara berkala	2024-10-15 06:13:05	2024-10-15 06:13:05
47	1	Tidak tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh program studi	2024-10-15 06:13:05	2024-10-15 06:13:05
47	2	Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh  program studi	2024-10-15 06:13:05	2024-10-15 06:13:05
47	3	Tersedia dokumen kebijakan penetapan beban belajar, masa tempuh kurikulum, dan distribusi beban belajar pada program penuh waktu dan paruh waktu pada seluruh  program studi yang disosialisasikan dan direview secara berkala (4 tahun minimal untuk S1 dan 2 tahun minimal untuk S2)	2024-10-15 06:13:05	2024-10-15 06:13:05
48	1	Tidak tersedia dokumen kebijakan program khusus seperti\n                percepatan, transfer kredit, perolehan kredit, double degree, atau\n                micro-credentials	2024-10-15 06:13:05	2024-10-15 06:13:05
48	2	ersedia dokumen kebijakan program khusus seperti\n                percepatan, transfer kredit, perolehan kredit, double degree, atau\n                micro-credentials	2024-10-15 06:13:05	2024-10-15 06:13:05
48	3	Tersedia dokumen kebijakan program khusus seperti\n                percepatan, transfer kredit, perolehan kredit, double degree, atau\n                micro-credentials yang disosialisasikan dan ditinjau secara berkala.	2024-10-15 06:13:05	2024-10-15 06:13:05
49	1	Program Studi tidak melaksanakan program khusus (percepatan/ transfer kredit/ perolehan kredit/ double degree/ micro-credentials)	2024-10-15 06:13:05	2024-10-15 06:13:05
49	2	Program Studi melaksanakan program khusus (percepatan/ transfer\n                kredit/ perolehan kredit/ double degree/ micro-credentials) dengan mitra dalam negeri	2024-10-15 06:13:05	2024-10-15 06:13:05
49	3	Program Studi melaksanakan program khusus (percepatan/ transfer\n                kredit/ perolehan kredit/ double degree/ micro-credentials) dengan mitra dalam negeri dan mitra luar negeri	2024-10-15 06:13:05	2024-10-15 06:13:05
84	1	Tidak tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru.	2024-10-15 06:13:07	2024-10-15 06:13:07
50	1	Tidak tersedia Dokumen Kebijakan Penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi	2024-10-15 06:13:05	2024-10-15 06:13:05
50	2	Tersedianya Dokumen Kebijakan Penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi	2024-10-15 06:13:05	2024-10-15 06:13:05
50	3	Tersedianya Dokumen Kebijakan Penetapan kewajiban magang di dunia usaha, dunia industri, dan dunia kerja yang relevan pada program vokasi dan dievaluasi secara berkala	2024-10-15 06:13:05	2024-10-15 06:13:05
51	1	Tidak Tersedia Dokumen Pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)	2024-10-15 06:13:05	2024-10-15 06:13:05
51	2	Tersedianya Dokumen Pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya)	2024-10-15 06:13:05	2024-10-15 06:13:05
51	3	Dokumen Pedoman tugas akhir yang sesuai dengan program pendidikan (termasuk jenis dan pengakuannya) di sosialisasikan yang di media sosial fakultas/Prodi dan di evaluasi secara berkala	2024-10-15 06:13:05	2024-10-15 06:13:05
52	1	Tersedia dokumen Hasil evaluasi proses pembelajaran namun kurang dari 2 (dua) aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
52	2	Tersedia dokumen Hasil evaluasi proses pembelajaran telah memenuhi 2 (dua) aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
52	3	Tersedia dokumen Hasil evaluasi proses pembelajaran telah memenuhi lebih dari 2 (dua) aspek	2024-10-15 06:13:05	2024-10-15 06:13:05
53	1	Tidak dilakukan  Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.	2024-10-15 06:13:05	2024-10-15 06:13:05
53	2	Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.	2024-10-15 06:13:06	2024-10-15 06:13:06
53	3	Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester dan dilakukan evaluasi  secara berkala terhadap hasil penilaian sumatif.	2024-10-15 06:13:06	2024-10-15 06:13:06
54	1	Tidak tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.	2024-10-15 06:13:06	2024-10-15 06:13:06
54	2	Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.	2024-10-15 06:13:06	2024-10-15 06:13:06
54	3	Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan yang disosialisasikan di media sosial fakultas/prodi dan ditinjau secara berkala.	2024-10-15 06:13:06	2024-10-15 06:13:06
55	1	Tidak tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas.	2024-10-15 06:13:06	2024-10-15 06:13:06
55	2	Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas.	2024-10-15 06:13:06	2024-10-15 06:13:06
55	3	Tersedia bukti dokumen hasil evaluasi penyelenggaraan program pendidikan di fakultas yang disosialisasikan di media sosial fakultas/prodi dan ditinjau secara berkala.	2024-10-15 06:13:06	2024-10-15 06:13:06
56	1	Tidak tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek.	2024-10-15 06:13:06	2024-10-15 06:13:06
56	2	Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek.	2024-10-15 06:13:06	2024-10-15 06:13:06
56	3	Tersedia dokumen rencana pengembangan jangka panjang, jangka menengah dan jangka pendek yang ditindaklanjuti dan disosialisasikan secara berkala di media sosial fakultas.	2024-10-15 06:13:06	2024-10-15 06:13:06
57	1	Tidak tersedia dokumen peraturan tentang etika akademik.	2024-10-15 06:13:06	2024-10-15 06:13:06
57	2	Tersedia dokumen peraturan tentang etika akademik.	2024-10-15 06:13:06	2024-10-15 06:13:06
57	3	Tersedia dokumen peraturan tentang etika akademik dan disosialisasikan di media sosial fakultas/prodi.	2024-10-15 06:13:06	2024-10-15 06:13:06
58	1	Tidak tersedia laporan hasil penegakan etika akademik.	2024-10-15 06:13:06	2024-10-15 06:13:06
58	2	Tersedia dokumen laporan hasil penegakan etika akademik.	2024-10-15 06:13:06	2024-10-15 06:13:06
58	3	Tersedia dokumen laporan hasil penegakan etika akademik dan dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:06	2024-10-15 06:13:06
59	1	Tidak tersedia laporan hasil AMAI bidang akademik dan non akademik.	2024-10-15 06:13:06	2024-10-15 06:13:06
59	2	Tersedia laporan hasil AMAI bidang akademik dan non akademik.	2024-10-15 06:13:06	2024-10-15 06:13:06
59	3	Tersedia laporan hasil AMAI bidang akademik dan non akademik dilakukan sosialisasi dan ditindaklanjuti secara berkala.	2024-10-15 06:13:06	2024-10-15 06:13:06
60	1	<63%	2024-10-15 06:13:06	2024-10-15 06:13:06
60	2	=63%	2024-10-15 06:13:06	2024-10-15 06:13:06
60	3	>63%	2024-10-15 06:13:06	2024-10-15 06:13:06
61	1	Tidak ada dosen praktisi dari dunia usaha, dunia industri atau dunia kerja	2024-10-15 06:13:06	2024-10-15 06:13:06
61	2	Terdapat dosen praktisi dari dunia usaha, dunia industri dan dunia kerja minimal satu orang per semester	2024-10-15 06:13:06	2024-10-15 06:13:06
61	3	Terdapat dosen praktisi dari dunia usaha, dunia industri dan dunia kerja minimal 2 orang per semester	2024-10-15 06:13:06	2024-10-15 06:13:06
62	1	Jumlah tenaga kependidikan kurang dan berpendidikan kurang dari D3	2024-10-15 06:13:06	2024-10-15 06:13:06
62	2	Semua tenaga pendidikan kompeten dengan pendidikan minimal D3	2024-10-15 06:13:06	2024-10-15 06:13:06
62	3	Semua tenaga pendidikan kompeten dengan pendidikan minimal S1	2024-10-15 06:13:06	2024-10-15 06:13:06
63	1	Tidak tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria	2024-10-15 06:13:06	2024-10-15 06:13:06
63	2	Tersedia sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria.	2024-10-15 06:13:06	2024-10-15 06:13:06
63	3	1) Tersedia data sarana dan prasarana teknologi informasi dan sarana pembelajaran yang memenuhi 4 kriteria \n                                2) Dilakukan monitoring dan evaluasi secara rutin setiap tahun untuk menjamin keberlanjutan	2024-10-15 06:13:06	2024-10-15 06:13:06
64	1	Tidak tersedia sarana dan prasarana yang memenuhi standar keamanan dan keselamatan kerja (K3)	2024-10-15 06:13:06	2024-10-15 06:13:06
64	2	Tersedia sarana dan prasarana yang memenuhi standar keamanan dan keselamatan kerja (K3)	2024-10-15 06:13:06	2024-10-15 06:13:06
64	3	Tersedia sarana dan prasarana mutakhir yang memenuhi standar keamanan dan keselamatan kerja (K3)  dilakukan monitoring evaluasi secara berkala	2024-10-15 06:13:06	2024-10-15 06:13:06
65	1	Tidak dapat mengakses sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja)	2024-10-15 06:13:06	2024-10-15 06:13:06
65	2	Kemudahan akses sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja) dengan tingkat kepuasan minimal 80%	2024-10-15 06:13:06	2024-10-15 06:13:06
65	3	Kemudahan akses sarana dan prasarana yang memenuhi standar K3 (standar keamanan dan keselamatan kerja) dengan tingkat kepuasan minimal 80% dan dilakukan evaluasi secara rutin setiap tahun untuk menjamin keberlanjutan	2024-10-15 06:13:06	2024-10-15 06:13:06
66	1	Tidak tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah	2024-10-15 06:13:06	2024-10-15 06:13:06
66	2	Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah	2024-10-15 06:13:06	2024-10-15 06:13:06
66	3	1) Tersedia sumber pembelajaran terbuka (materi pembelajaran, jurnal, buku, LMS) yang dapat diakses dengan mudah dan dievaluasi secara rutin setiap tahun\n                                2) Tingkat kepuasan terhadap akses sumber pembelajaran terbuka minimal 80%	2024-10-15 06:13:06	2024-10-15 06:13:06
67	1	Tidak tersedia dokumen rancangan anggaran pendapatan dan belanja	2024-10-15 06:13:06	2024-10-15 06:13:06
67	2	Tersedia dokumen rancangan anggaran pendapatan dan belanja	2024-10-15 06:13:06	2024-10-15 06:13:06
67	3	Tersedia dokumen rancangan anggaran pendapatan dan belanja serta dilakukan monitoring dan evaluasi secara berkala	2024-10-15 06:13:06	2024-10-15 06:13:06
68	1	Belum semua hasil penelitian dosen dijadikan materi ajar yang tertuang dalam RPS mata kuliah yang diajarkan.	2024-10-15 06:13:06	2024-10-15 06:13:06
68	2	Semua hasil penelitian dosen dijadikan materi ajar yang tertuang dalam RPS mata kuliah yang diajarkan.	2024-10-15 06:13:06	2024-10-15 06:13:06
68	3	Semua hasil penelitian dosen dijadikan materi ajar yang tertuang dalam RPS mata kuliah yang diajarkan dan dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:06	2024-10-15 06:13:06
69	1	Tidak memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.	2024-10-15 06:13:06	2024-10-15 06:13:06
69	2	Memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed.	2024-10-15 06:13:06	2024-10-15 06:13:06
69	3	Memiliki dokumen rencana strategis (renstra) penelitian yang berisi roadmap dan tema penelitian unggulan untuk mendukung pencapaian visi Unsoed secara berkala.	2024-10-15 06:13:06	2024-10-15 06:13:06
70	1	Belum semua dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.	2024-10-15 06:13:06	2024-10-15 06:13:06
70	2	Semua dosen melakukan penelitian dengan mengacu pada dokumen rencana strategis (renstra) penelitian.	2024-10-15 06:13:06	2024-10-15 06:13:06
70	3	-	2024-10-15 06:13:06	2024-10-15 06:13:06
71	1	Tidak setiap dosen tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.	2024-10-15 06:13:06	2024-10-15 06:13:06
71	2	Setiap dosen tergabung dalam kelompok riset sesuai dengan prioritas/topik penelitian, kualifikasi akademik, dan rekam jejak penelitian.	2024-10-15 06:13:06	2024-10-15 06:13:06
71	3	-	2024-10-15 06:13:06	2024-10-15 06:13:06
72	1	Tidak semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat	2024-10-15 06:13:06	2024-10-15 06:13:06
72	2	Semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat	2024-10-15 06:13:06	2024-10-15 06:13:06
72	3	-	2024-10-15 06:13:06	2024-10-15 06:13:06
73	1	Belum semua dosen melaksanakan PkM sesuai dengan roadmap Unsoed.	2024-10-15 06:13:06	2024-10-15 06:13:06
73	2	Semua dosen melaksanan PkM sesuai dengan roadmap Unsoed.	2024-10-15 06:13:06	2024-10-15 06:13:06
73	3	Semua PkM yang dilaksanakan sesuai dengan roadmap Unsoed dan menghasilkan luaran wajib dan luaran tambahan.	2024-10-15 06:13:06	2024-10-15 06:13:06
74	1	< 5 juta	2024-10-15 06:13:06	2024-10-15 06:13:06
74	2	= 5 juta	2024-10-15 06:13:06	2024-10-15 06:13:06
74	3	> 5 juta	2024-10-15 06:13:06	2024-10-15 06:13:06
75	1	Tidak dilakukan survey	2024-10-15 06:13:06	2024-10-15 06:13:06
75	2	Dilakukan survey dan tersedia laporan hasil survey	2024-10-15 06:13:06	2024-10-15 06:13:06
75	3	Dilakukan survey dan tersedia laporan hasil survey yang ditindaklanjuti	2024-10-15 06:13:06	2024-10-15 06:13:06
76	1	Tidak memiliki bukti 3 karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik) 	2024-10-15 06:13:06	2024-10-15 06:13:06
76	2	Terdapat bukti/pengakuan  memiliki bukti 3 karakter kepemimpinan (kepemimpinan organisasi,  kepemimpinan operasional  dan kepemimpinan publik)	2024-10-15 06:13:06	2024-10-15 06:13:06
76	3	-	2024-10-15 06:13:06	2024-10-15 06:13:06
77	1	Tidak memiliki bukti 5 aspek  bukti/pengakuan kapabilitas pimpinan UPPS/Prodi	2024-10-15 06:13:06	2024-10-15 06:13:06
77	2	Terdapat bukti 5 aspek  bukti/pengakuan kapabilitas pimpinan UPPS/Prodi	2024-10-15 06:13:06	2024-10-15 06:13:06
77	3	-	2024-10-15 06:13:06	2024-10-15 06:13:06
78	1	Tidak tersedia rencana program kegiatan mahasiswa	2024-10-15 06:13:06	2024-10-15 06:13:06
78	2	Tersedia rencana program kegiatan mahasiswa	2024-10-15 06:13:06	2024-10-15 06:13:06
78	3	Tersedia rencana program kegiatan mahasiswa dan ditindaklanjuti	2024-10-15 06:13:06	2024-10-15 06:13:06
79	1	Tidak tersedia dokumen pelaksanaan kegiatan kemahasiswaan	2024-10-15 06:13:06	2024-10-15 06:13:06
79	2	Tersedia dokumen pelaksanaan kegiatan kemahasiswaan	2024-10-15 06:13:06	2024-10-15 06:13:06
79	3	Tersedia dokumen pelaksanaan kegiatan kemahasiswaan dan dilakukan monitoring dan evaluasi	2024-10-15 06:13:06	2024-10-15 06:13:06
80	1	Tidak tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan	2024-10-15 06:13:06	2024-10-15 06:13:06
80	2	Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan	2024-10-15 06:13:06	2024-10-15 06:13:06
80	3	Tersedia dokumen hasil monitoring evaluasi kepuasan mahasiswa terhadap layanan yang dilaksanakan secara konsisten dan ditindaklanjuti secara berkala	2024-10-15 06:13:07	2024-10-15 06:13:07
81	1	Tidak tersedia sarana prasarana umum di lingkungan fakultas	2024-10-15 06:13:07	2024-10-15 06:13:07
81	2	Tersedia sarana prasarana umum di lingkungan fakultas	2024-10-15 06:13:07	2024-10-15 06:13:07
81	3	Tersedia sarana prasarana umum di lingkungan fakultas dan dilakukan monitoring dan evaluasi secara berkala	2024-10-15 06:13:07	2024-10-15 06:13:07
82	1	Tidak tersedia dokumen keberadaan sarana prasarana umum di lingkungan universitas	2024-10-15 06:13:07	2024-10-15 06:13:07
82	2	Tersedia dokumen keberadaan sarana prasarana umum di lingkungan universitas	2024-10-15 06:13:07	2024-10-15 06:13:07
82	3	Tersedia dokumen keberadaan sarana prasarana umum di lingkungan universitas dan disosialisasikan secara berkala serta dipublikasikan	2024-10-15 06:13:07	2024-10-15 06:13:07
83	1	Tidak tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum	2024-10-15 06:13:07	2024-10-15 06:13:07
83	2	Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum	2024-10-15 06:13:07	2024-10-15 06:13:07
83	3	Tersedia dokumen hasil audit dan monitoring dan evaluasi sarana dan prasarana umum dan ditindak lanjuti secara berkala	2024-10-15 06:13:07	2024-10-15 06:13:07
84	2	Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru.	2024-10-15 06:13:07	2024-10-15 06:13:07
84	3	Tersedia dokumen laporan dan evaluasi penerimaan mahasiswa baru yang disosialisasikan dan ditindaklanjuti secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
85	1	Tidak tersedia dokumen kebijakan RPL di Unsoed.	2024-10-15 06:13:07	2024-10-15 06:13:07
85	2	Tersedia dokumen kebijakan RPL di Unsoed.	2024-10-15 06:13:07	2024-10-15 06:13:07
85	3	Tersedia dokumen kebijakan RPL di Unsoed yang disosialisasikan dan dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
86	1	Tidak tersedia dokumen kebijakan serta laporan PKKM dan PKKMB.	2024-10-15 06:13:07	2024-10-15 06:13:07
86	2	Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB.	2024-10-15 06:13:07	2024-10-15 06:13:07
86	3	Tersedia dokumen kebijakan serta laporan PKKM dan PKKMB yang disosialisasikan dan dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
87	1	<75%	2024-10-15 06:13:07	2024-10-15 06:13:07
87	2	75%	2024-10-15 06:13:07	2024-10-15 06:13:07
87	3	>75%	2024-10-15 06:13:07	2024-10-15 06:13:07
88	1	Belum memiliki sistem informasi terkait data pendidikan.	2024-10-15 06:13:07	2024-10-15 06:13:07
88	2	Tersedia sistem informasi terkait data pendidikan.	2024-10-15 06:13:07	2024-10-15 06:13:07
88	3	Tersedia dan dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
89	1	Tidak tersedia dokumen kebijakan tata kelola teknologi informasi	2024-10-15 06:13:07	2024-10-15 06:13:07
89	2	Tersedia dokumen kebijakan tata kelola teknologi informasi	2024-10-15 06:13:07	2024-10-15 06:13:07
89	3	Tersedia dokumen kebijakan tata kelola teknologi informasi yang dievaluasi secara rutin setiap tahun	2024-10-15 06:13:07	2024-10-15 06:13:07
90	1	Tidak tersedia rencana strategis keuangan yang memuat perencanaan anggaran keuangan	2024-10-15 06:13:07	2024-10-15 06:13:07
90	2	Tersedia rencana strategis keuangan yang memuat perencanaan anggaran keuangan	2024-10-15 06:13:07	2024-10-15 06:13:07
90	3	Rencana strategis keuangan yang memuat perencanaan anggaran keuangan dan dievaluasi secara berkala	2024-10-15 06:13:07	2024-10-15 06:13:07
91	1	Tidak tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian	2024-10-15 06:13:07	2024-10-15 06:13:07
91	2	Tersedia dokumen hasil audit keuangan oleh auditor dengan opini wajar tanpa pengecualian	2024-10-15 06:13:07	2024-10-15 06:13:07
91	3	-	2024-10-15 06:13:07	2024-10-15 06:13:07
92	1	Tidak tersedia dokumen kebijakan bantuan biaya Pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
92	2	Tersedia dokumen kebijakan bantuan biaya Pendidikan dan terdapat mahasiswa yang memperoleh bantuan biaya pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
92	3	Tersedia dokumen kebijakan bantuan biaya pendidikan, terdapat mahasiswa yang memperoleh bantuan biaya Pendidikan dan disosialisasikan di sosial media secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
93	1	Tidak terdapat data mahasiswa penerima bantuan biaya pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
93	2	Terdapat data mahasiswa penerima bantuan biaya pendidikan	2024-10-15 06:13:07	2024-10-15 06:13:07
93	3	Terdapat data mahasiswa penerima bantuan biaya Pendidikan dan dipublikasikan secara berkala	2024-10-15 06:13:07	2024-10-15 06:13:07
94	1	Tidak semua hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi  serta terpublikasi pada jurnal nasional (SINTA 1 – 6). 	2024-10-15 06:13:07	2024-10-15 06:13:07
94	2	Semua hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta terpublikasi pada jurnal nasional (SINTA 1 – 6). 	2024-10-15 06:13:07	2024-10-15 06:13:07
94	3	Semua hasil penelitian dosen memiliki luaran penelitian yang bermutu, relevan, dan bermanfaat, serta mendukung pelaksanaan misi dan pencapaian visi serta terpublikasi pada jurnal nasional (SINTA 1 – 6) serta sebagian terpublikasi dalam jurnal internasional bereputasi	2024-10-15 06:13:07	2024-10-15 06:13:07
95	1	Unsoed belum memiliki jurnal yang menjadi media untuk mempublikasikan hasil penelitian dosen	2024-10-15 06:13:07	2024-10-15 06:13:07
95	2	Unsoed sudah  memiliki jurnal yang menjadi media untuk mempublikasikan hasil penelitian dosen	2024-10-15 06:13:07	2024-10-15 06:13:07
95	3	Masing-masing rumpun bidang ilmu di Unsoed sudah  memiliki jurnal yang menjadi media untuk mempublikasikan hasil penelitian dosen	2024-10-15 06:13:07	2024-10-15 06:13:07
96	1	LPPM memiliki sebagian dokumen tata kelola perguruan tinggi di bidang penelitian antara lain berupa: RIP, Renstra, Renop dan roadmap penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
96	2	LPPM memiliki semua dokumen tata kelola perguruan tinggi di bidang penelitian antara lain berupa: RIP, Renstra, Renop dan roadmap penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
96	3	LPPM memiliki semua dokumen tata kelola perguruan tinggi di bidang penelitian antara lain berupa: RIP, Renstra, Renop dan roadmap penelitian, dievaluasi secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
97	1	Belum memiliki dokumen pedoman pelaksanaan penelitian yang mencakup: kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
97	2	LPPM memiliki dokumen pedoman pelaksanaan penelitian yang mencakup: kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
97	3	LPPM memiliki dokumen pedoman pelaksanaan penelitian yang mencakup: kode etik, tata kelola HKI, ketentuan kerjasama dan publikasi hasil penelitian serta dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
98	1	Tidak semua kegiatan penelitian dosen dilakukan melalui proses seleksi yang kompetitif, monev secara sistematis, obyektif, transparan, dan akuntabel.	2024-10-15 06:13:07	2024-10-15 06:13:07
98	2	Semua kegiatan penelitian dosen dilakukan melalui proses seleksi yang kompetitif, monev secara sistematis, obyektif, transparan, dan akuntabel.	2024-10-15 06:13:07	2024-10-15 06:13:07
98	3	Semua kegiatan penelitian dosen dilakukan melalui proses seleksi yang kompetitif, monev secara sistematis, obyektif, transparan, dan akuntabel dan dapat diakses.	2024-10-15 06:13:07	2024-10-15 06:13:07
99	1	Tidak memiliki dokumen penjaminan mutu penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
99	2	Memiliki dokumen penjaminan mutu penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
99	3	Memiliki dokumen penjaminan mutu penelitian serta dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:07	2024-10-15 06:13:07
100	1	Melaksanakan penjaminan mutu yang tidak sesuai dengan dokumen standar mutu penelitian yang ditetapkan.	2024-10-15 06:13:07	2024-10-15 06:13:07
100	2	Melaksanakan penjaminan mutu yang sesuai dengan dokumen standar mutu penelitian yang ditetapkan.	2024-10-15 06:13:07	2024-10-15 06:13:07
100	3	Melaksanakan penjaminan mutu yang sesuai dengan dokumen standar mutu penelitian yang ditetapkan dan melakukan upaya peningkatan mutu penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
101	1	Tidak memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.	2024-10-15 06:13:07	2024-10-15 06:13:07
101	2	Memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu.	2024-10-15 06:13:07	2024-10-15 06:13:07
101	3	Memiliki dokumen laporan kinerja bidang penelitian yang dilakukan secara akuntabel dan tepat waktu dan dipublikasikan.	2024-10-15 06:13:07	2024-10-15 06:13:07
102	1	Fakultas/UPPS tidak dapat mengakses dokumen laporan kinerja bidang penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
102	2	Fakultas/UPPS dapat mengakses dokumen laporan kinerja bidang penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
102	3	Fakultas/UPPS dapat mengakses dokumen laporan kinerja bidang penelitian dan dapat mengunduh laporan kinerja bidang penelitian.	2024-10-15 06:13:07	2024-10-15 06:13:07
103	1	Tidak memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
103	2	Memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
103	3	Memiliki dokumen hasil evaluasi pelaksanaan dan hasil penelitian yang mencakup prinsip kemanfaatan, kemutakhiran, serta antisipasi terhadap kebutuhan masa depan untuk mendukung kepentingan nasional dan sesuai dengan Roadmap Penelitian Unsoed dan dipublikasikan.	2024-10-15 06:13:08	2024-10-15 06:13:08
104	1	Unsoed belum memiliki keterbatasan sarana dan prasarana penelitian	2024-10-15 06:13:08	2024-10-15 06:13:08
104	2	Unsoed memiliki sarana dan prasarana penelitian	2024-10-15 06:13:08	2024-10-15 06:13:08
104	3	Unsoed memiliki sarana dan prasarana penelitian serta dimonitoring secara rutin.	2024-10-15 06:13:08	2024-10-15 06:13:08
105	1	Unsoed memiliki keterbatasan akses sarana dan prasarana penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
105	2	Unsoed menyediakan akses sarana dan prasarana penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
105	3	Unsoed menyediakan akses sarana dan prasarana penelitian serta dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
106	1	Tidak tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
106	2	Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
106	3	Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam hal penelitian serta dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
107	1	Tidak tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
107	2	Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian.	2024-10-15 06:13:08	2024-10-15 06:13:08
107	3	Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan penelitian yang dimonitoring secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
108	1	Alokasi dana penelitian kurang dari 15%	2024-10-15 06:13:08	2024-10-15 06:13:08
108	2	Alokasi dana penelitian sebesar 15%	2024-10-15 06:13:08	2024-10-15 06:13:08
108	3	Alokasi dana penelitian lebih dari 15%	2024-10-15 06:13:08	2024-10-15 06:13:08
109	1	Tidak semua dosen memiliki luaran wajib PKM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian	2024-10-15 06:13:08	2024-10-15 06:13:08
109	2	Semua dosen memiliki luaran wajib PKM minimal luaran wajib yang telah ditentukan oleh LPPM pada masing-masing skim pengabdian	2024-10-15 06:13:08	2024-10-15 06:13:08
109	3	Semua dosen memiliki luaran wajib PKM dan luaran tambahan pada masing-masing skim pengabdian	2024-10-15 06:13:08	2024-10-15 06:13:08
110	1	Tidak tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik	2024-10-15 06:13:08	2024-10-15 06:13:08
110	2	Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik	2024-10-15 06:13:08	2024-10-15 06:13:08
110	3	Tersedia dokumen/sistem Pengelolaan PkM oleh LPPM dengan prinsip tata kelola perguruan tinggi yang baik, disosialisasikan dan dilakukan monitoring evaluasi secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
111	1	Tidak semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat	2024-10-15 06:13:08	2024-10-15 06:13:08
111	2	Semua dosen terlibat dalam kegiatan PKM baik sebagai ketua atau anggota dengan melakukan transfer ilmu dan teknologi kepada Masyarakat	2024-10-15 06:13:08	2024-10-15 06:13:08
111	3	-	2024-10-15 06:13:08	2024-10-15 06:13:08
112	1	Tidak tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan	2024-10-15 06:13:08	2024-10-15 06:13:08
112	2	Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan	2024-10-15 06:13:08	2024-10-15 06:13:08
112	3	Tersedia dokumen kode etik PkM sesuai dengan ketentuan Peraturan perundang-undangan dan disosialisasikan.	2024-10-15 06:13:08	2024-10-15 06:13:08
113	1	Tidak tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan	2024-10-15 06:13:08	2024-10-15 06:13:08
113	2	Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan	2024-10-15 06:13:08	2024-10-15 06:13:08
113	3	Tersedia dokumen pengelolaan dan kepemilikan hak atas kekayaan intelektual sesuai dengan ketentuan peraturan perundang-undangan dan disosialisasikan	2024-10-15 06:13:08	2024-10-15 06:13:08
114	1	Tidak tersedia dokumen ketentuan dalam kerja sama PkM	2024-10-15 06:13:08	2024-10-15 06:13:08
114	2	Tersedia dokumen ketentuan dalam kerja sama PkM	2024-10-15 06:13:08	2024-10-15 06:13:08
114	3	Tersedia dokumen ketentuan dalam kerja sama PkM dan disosialisasikan.	2024-10-15 06:13:08	2024-10-15 06:13:08
115	1	Tidak tersedia dokumen persyaratan untuk publikasi hasil PkM dan ketentuan penulisannya	2024-10-15 06:13:08	2024-10-15 06:13:08
115	2	Tersedia dokumen persyaratan untuk publikasi hasil PkM dan ketentuan penulisannya	2024-10-15 06:13:08	2024-10-15 06:13:08
115	3	Tersedia dokumen persyaratan untuk publikasi hasil PkM dan ketentuan penulisannya dan disosialisasikan.	2024-10-15 06:13:08	2024-10-15 06:13:08
116	1	Tidak terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM	2024-10-15 06:13:08	2024-10-15 06:13:08
116	2	Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM	2024-10-15 06:13:08	2024-10-15 06:13:08
116	3	Terdapat dokumen proses penilaian PkM secara sistematis, obyektif, transparan, dan akuntabel yang dilakukan LPPM dan bisa diakses	2024-10-15 06:13:08	2024-10-15 06:13:08
117	1	Tidak tersedia dokumen renstra LPPM yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
117	2	Tersedia dokumen renstra LPPM yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
117	3	Tersedia dokumen renstra LPPM yang berisi roadmap dan tema PkM unggulan untuk mendukung pencapaian visi Unsoed dan disosialisasikan.	2024-10-15 06:13:08	2024-10-15 06:13:08
118	1	Tidak tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.	2024-10-15 06:13:08	2024-10-15 06:13:08
118	2	Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun.	2024-10-15 06:13:08	2024-10-15 06:13:08
118	3	Tersedia dokumen proses seleksi, monitoring dan evaluasi yang dilakukan oleh LPPM untuk menjamin mutu kegiatan PkM yang dilaksanakan setiap tahun dan disosialisasikan.	2024-10-15 06:13:08	2024-10-15 06:13:08
119	1	Tidak tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.	2024-10-15 06:13:08	2024-10-15 06:13:08
119	2	Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program.	2024-10-15 06:13:08	2024-10-15 06:13:08
119	3	Tersedia dokumen pelaksanaan forum pelaporan kinerja LPPM terkait program dan dievaluasi secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
120	1	Belum semua dosen melaksanakan PkM sesuai dengan roadmap Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
120	2	Semua dosen melaksanan PkM sesuai dengan roadmap Unsoed.	2024-10-15 06:13:08	2024-10-15 06:13:08
120	3	Semua PkM yang dilaksanakan sesuai dengan roadmap Unsoed dan menghasilkan luaran wajib dan luaran tambahan.	2024-10-15 06:13:08	2024-10-15 06:13:08
121	1	Unsoed belum memiliki keterbatasan sarana dan prasarana PKM	2024-10-15 06:13:08	2024-10-15 06:13:08
121	2	Unsoed memiliki sarana dan prasarana PKM	2024-10-15 06:13:08	2024-10-15 06:13:08
121	3	Unsoed memiliki sarana dan prasarana PKM serta dimonitoring secara rutin.	2024-10-15 06:13:08	2024-10-15 06:13:08
122	1	Unsoed memiliki keterbatasan akses sarana dan prasarana PKM	2024-10-15 06:13:08	2024-10-15 06:13:08
122	2	Unsoed menyediakan akses sarana dan prasarana PKM	2024-10-15 06:13:08	2024-10-15 06:13:08
122	3	Unsoed menyediakan akses sarana dan prasarana PKM serta dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
123	1	Tidak tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam melakukan PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
123	2	Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam melakukan PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
123	3	Tersedia mekanisme penugasan dan peningkatan kompetensi dosen dalam melakukan PKM serta dilakukan monitoring dan evaluasi secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
124	1	Tidak tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
124	2	Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM.	2024-10-15 06:13:08	2024-10-15 06:13:08
124	3	Tersedia sistem informasi manajemen penelitian (Sinelitabmas) yang tangguh untuk menjamin keberlanjutan PKM yang dimonitoring secara berkala.	2024-10-15 06:13:08	2024-10-15 06:13:08
125	1	Tidak tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.	2024-10-15 06:13:09	2024-10-15 06:13:09
125	2	Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM.	2024-10-15 06:13:09	2024-10-15 06:13:09
125	3	Tersedia sistem informasi dan komunikasi yang andal untuk mendokumentasikan, mengevaluasi, melaporkan, dan menyebarluaskan proses dan hasil PkM serta ada upaya pemeliharaan dan perbaikan.	2024-10-15 06:13:09	2024-10-15 06:13:09
126	1	Luaran PkM tidak disebarluaskan	2024-10-15 06:13:09	2024-10-15 06:13:09
126	2	Luaran PkM disajikan dalam seminar nasional, buku TTG, buku ajar, HKI, dll.	2024-10-15 06:13:09	2024-10-15 06:13:09
126	3	Luaran PkM disajikan dalam bentuk paten atau paten sederhana.	2024-10-15 06:13:09	2024-10-15 06:13:09
127	1	Kurang dari 5% dari DIPA PNBP	2024-10-15 06:13:09	2024-10-15 06:13:09
127	2	5% dari DIPA PNBP	2024-10-15 06:13:09	2024-10-15 06:13:09
127	3	Lebih besar dari 5% dari DIPA PNBP	2024-10-15 06:13:09	2024-10-15 06:13:09
128	1	Tidak memiliki kelompok pelaksana PkM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.	2024-10-15 06:13:09	2024-10-15 06:13:09
128	2	Memiliki kelompok pelaksana PkM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada.	2024-10-15 06:13:09	2024-10-15 06:13:09
128	3	Memiliki kelompok pelaksana PkM sesuai dengan prioritas/topik PkM, kualifikasi akademik, dan rekam jejak PkM melalui skim yang ada dan berkelanjutan.	2024-10-15 06:13:09	2024-10-15 06:13:09
129	1	Tidak semua dokumen kerjasama lengkap	2024-10-15 06:13:09	2024-10-15 06:13:09
129	2	Semua kerjasama dipayungi oleh dokumen MoU atau PKS dengan mitra dari dalam negeri	2024-10-15 06:13:09	2024-10-15 06:13:09
129	3	Semua kerjasama dipayungi oleh dokumen MoU atau PKS dengan mitra dari dalam negeri dan luar negeri	2024-10-15 06:13:09	2024-10-15 06:13:09
130	1	Tidak tersedia dokumen kebijakan kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
130	2	Tersedia dokumen kebijakan kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
130	3	Tersedia dokumen kebijakan kerjasama di tingkat universitas dan dalam pelaksanaannya dilakukan monitoring dan evaluasi secara rutin setiap tahun	2024-10-15 06:13:09	2024-10-15 06:13:09
131	1	Tidak tersedia dokumen SOP Kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
131	2	Tersedia dokumen SOP Kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
131	3	Tersedia dokumen SOP Kerjasama di tingkat universitas dan dalam pelaksanaannya dilakukan monitor dan evaluasi secara rutin setiap tahun	2024-10-15 06:13:09	2024-10-15 06:13:09
132	1	Tidak tersedia rencana pengembangan kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
132	2	Tersedia rencana pengembangan kerjasama di tingkat universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
132	3	Tersedia rencana pengembangan kerjasama di tingkat universitas dan dalam pelaksanaannya dilakukan monitor dan evaluasi secara rutin setiap tahun	2024-10-15 06:13:09	2024-10-15 06:13:09
133	1	Tidak tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama	2024-10-15 06:13:09	2024-10-15 06:13:09
133	2	Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama	2024-10-15 06:13:09	2024-10-15 06:13:09
133	3	Tersedia dokumen monitoring dan evaluasi kerjasama yang terdiri atas jumlah, lingkup, relevansi, hasil dan kemanfaatan kerjasama serta adanya upaya tindak lanjut	2024-10-15 06:13:09	2024-10-15 06:13:09
134	1	Tidak dilakukan survey	2024-10-15 06:13:09	2024-10-15 06:13:09
134	2	Dilakukan survey dan tersedia laporan hasil survey	2024-10-15 06:13:09	2024-10-15 06:13:09
134	3	Dilakukan survey dan tersedia laporan hasil survey yang ditindaklanjuti	2024-10-15 06:13:09	2024-10-15 06:13:09
135	1	Tidak tersedia dokumen yang menunjukkan keberadaan Unit Layanan Terpadu	2024-10-15 06:13:09	2024-10-15 06:13:09
135	2	Tersedia dokumen yang menunjukkan keberadaan Unit Layanan Terpadu	2024-10-15 06:13:09	2024-10-15 06:13:09
135	3	Tersedia dokumen yang menunjukkan keberadaan unit layanan terpadu	2024-10-15 06:13:09	2024-10-15 06:13:09
136	1	Belum terlaksana pelayanan secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan	2024-10-15 06:13:09	2024-10-15 06:13:09
136	2	Terlaksana pelayanan secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan	2024-10-15 06:13:09	2024-10-15 06:13:09
136	3	Terlaksana pelayanan secara transparan, akuntabel, dan sesuai dengan peraturan perundang-undangan serta dilakukan monitoring dan evaluasi secara berkala	2024-10-15 06:13:09	2024-10-15 06:13:09
137	1	Tidak tersedia dokumen SOP layanan	2024-10-15 06:13:09	2024-10-15 06:13:09
137	2	Tersedia dokumen SOP layanan	2024-10-15 06:13:09	2024-10-15 06:13:09
137	3	Tersedia dokumen SOP layanan dan ditinjau secara berkala	2024-10-15 06:13:09	2024-10-15 06:13:09
138	1	Tidak tersedia survey kepuasan layanan	2024-10-15 06:13:09	2024-10-15 06:13:09
138	2	Tersedia survey kepuasan layanan dan terdapat laporan hasil survey yang dapat diakses publik	2024-10-15 06:13:09	2024-10-15 06:13:09
138	3	Tersedia survey kepuasan layanan dan terdapat laporan hasil survey yang dapat diakses publik serta ditindaklanjuti	2024-10-15 06:13:09	2024-10-15 06:13:09
139	1	Tidak terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi  berasaskan pada prinsip efektifitas, efisiensi dan produktifitas	2024-10-15 06:13:09	2024-10-15 06:13:09
139	2	Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi yang memuat dokumen formal struktur organisasi dan tata kerja yang dilengkapi tugas dan fungsinya	2024-10-15 06:13:09	2024-10-15 06:13:09
139	3	Terdapat dokumen kebijakan tata pamong pengelolaan pendidikan tinggi yang memuat dokumen formal struktur organisasi dan tata kerja yang dilengkapi tugas dan fungsinya, telah berjalan secara konsisten dan menjamin tata pamong yang baik serta berjalan efektif dan efisien.	2024-10-15 06:13:09	2024-10-15 06:13:09
140	1	Tidak tersedia dokumen SOP untuk proses penerimaan mahasiswa baru	2024-10-15 06:13:09	2024-10-15 06:13:09
140	2	Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru	2024-10-15 06:13:09	2024-10-15 06:13:09
140	3	Tersedia dokumen SOP untuk proses penerimaan mahasiswa baru Dievaluasi secara rutin setiap tahun untuk perbaikan keberlanjutan	2024-10-15 06:13:09	2024-10-15 06:13:09
141	1	Tidak tersedia dokumen audit penerimaan mahasiswa baru	2024-10-15 06:13:09	2024-10-15 06:13:09
141	2	Tersedia dokumen audit penerimaan mahasiswa baru	2024-10-15 06:13:09	2024-10-15 06:13:09
141	3	Tersedia dokumen audit penerimaan mahasiswa baru Dievaluasi secara rutin setiap tahun untuk perbaikan keberlanjutan	2024-10-15 06:13:09	2024-10-15 06:13:09
142	1	Tidak tersedia dokumen kebijakan kegiatan mahasiswa	2024-10-15 06:13:09	2024-10-15 06:13:09
142	2	Tersedia dokumen kebijakan kegiatan mahasiswa	2024-10-15 06:13:09	2024-10-15 06:13:09
142	3	Tersedia dokumen kebijakan kegiatan mahasiswa dan disosialisasikan secara berkala serta dipublikasikan	2024-10-15 06:13:09	2024-10-15 06:13:09
143	1	Tidak tersedia dokumen kebijakan terkait prestasi mahasiswa	2024-10-15 06:13:09	2024-10-15 06:13:09
143	2	Tersedia dokumen kebijakan terkait prestasi mahasiswa	2024-10-15 06:13:09	2024-10-15 06:13:09
143	3	Tersedia dokumen kebijakan terkait prestasi mahasiswa dan disosialisasikan secara berkala serta dipublikasikan	2024-10-15 06:13:09	2024-10-15 06:13:09
144	1	Tidak tersedia dokumen rancangan anggaran	2024-10-15 06:13:09	2024-10-15 06:13:09
144	2	Tersedia dokumen rancangan anggaran	2024-10-15 06:13:09	2024-10-15 06:13:09
144	3	Tersedia dokumen rancangan anggaran dan dilakukan monitoring serta evaluasi secara berkala	2024-10-15 06:13:09	2024-10-15 06:13:09
145	1	Tidak tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu	2024-10-15 06:13:09	2024-10-15 06:13:09
145	2	Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu	2024-10-15 06:13:09	2024-10-15 06:13:09
145	3	Tersedia dokumen kebijakan pembiayaan mahasiswa yang berpotensi secara akademik dan kurang mampu dan disosialisasikan secara berkala serta dipublikasikan	2024-10-15 06:13:09	2024-10-15 06:13:09
146	1	Tidak tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)	2024-10-15 06:13:09	2024-10-15 06:13:09
146	2	Tersedia Unit Pengendali dan Monitoring Rencana Anggaran (SPI)	2024-10-15 06:13:09	2024-10-15 06:13:09
146	3		2024-10-15 06:13:09	2024-10-15 06:13:09
147	1	Tidak tersedia dokumen hasil audit, monitoring dan evaluasi keuangan	2024-10-15 06:13:09	2024-10-15 06:13:09
147	2	Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan	2024-10-15 06:13:09	2024-10-15 06:13:09
147	3	Tersedia dokumen hasil audit, monitoring dan evaluasi keuangan dan ditindak lanjuti secara berkala	2024-10-15 06:13:09	2024-10-15 06:13:09
148	1	Tidak tersedia sarana prasarana umum di lingkungan universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
148	2	Tersedia sarana prasarana umum di lingkungan universitas	2024-10-15 06:13:09	2024-10-15 06:13:09
148	3	Tersedia sarana prasarana umum di lingkungan universitas dan dilakukan monitoring dan evaluasi secara berkala	2024-10-15 06:13:09	2024-10-15 06:13:09
149	1	Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.	2024-10-15 06:13:10	2024-10-15 06:13:10
149	2	Kompetensi Utama Lulusan Program studi memenuhi ketentuan :\n                Sarjana, minimal:\n                1.\tmenguasai konsep teoretis bidang pengetahuan dan keterampilan tertentu secara umum dan khusus untuk menyelesaikan masalah secara prosedural sesuai dengan lingkup pekerjaannya; dan\n                2.\tmampu beradaptasi terhadap situasi perubahan yang dihadapi	2024-10-15 06:13:10	2024-10-15 06:13:10
149	3	Memiliki Kompetensi tambahan sebagai penciri PS	2024-10-15 06:13:10	2024-10-15 06:13:10
150	1	Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.	2024-10-15 06:13:10	2024-10-15 06:13:10
150	2	Kompetensi Utama Lulusan Program studi memenuhi ketentuan :\n                Magister, minimal:\n                menguasai teori bidang pengetahuan tertentu untuk mengembangkan ilmu pengetahuan dan teknologi melalui riset atau penciptaan karya inovatif;	2024-10-15 06:13:10	2024-10-15 06:13:10
150	3	Memiliki Kompetensi tambahan sebagai penciri PS	2024-10-15 06:13:10	2024-10-15 06:13:10
151	1	Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.	2024-10-15 06:13:10	2024-10-15 06:13:10
151	2	Kompetensi Utama Lulusan Program studi memenuhi ketentuan :\n                Doktor, minimal:\n                1.\tmenguasai filosofi keilmuan bidang ilmu pengetahuan dan keterampilan tertentu;\n                2.\tmampu melakukan pendalaman dan perluasan ilmu pengetahuan dan teknologi melalui riset atau penciptaan karya orisinal dan teruji;	2024-10-15 06:13:10	2024-10-15 06:13:10
151	3	Memiliki Kompetensi tambahan sebagai penciri PS	2024-10-15 06:13:10	2024-10-15 06:13:10
152	1	Rumusan kompetensi utama lulusan belum memenuhi ketentuan minimal program pendidikan.	2024-10-15 06:13:10	2024-10-15 06:13:10
152	2	Kompetensi Utama Lulusan Program studi memenuhi ketentuan :\n                A. Profesi, minimal:\n                1.\tmenguasai teori aplikasi bidang pengetahuan dan keterampilan tertentu dengan memanfaatkan ilmu pengetahuan dan teknologi pada bidang profesi tertentu; dan\n                2.\tmampu mengelola sumber daya, menerapkan standar profesi, mengevaluasi, dan mengembangkan strategi organisasi;\n                B. Spesialis, minimal:\n                menguasai teori bidang ilmu pengetahuan tertentu untuk mengembangkan ilmu pengetahuan dan teknologi pada bidang keilmuan dan praktik profesionalnya melalui praktik profesional serta didukung dengan riset keilmuan;	2024-10-15 06:13:10	2024-10-15 06:13:10
152	3	Memiliki Kompetensi tambahan sebagai penciri PS	2024-10-15 06:13:10	2024-10-15 06:13:10
153	1	Kurang dari S1 = 2,10\n                	2024-10-15 06:13:10	2024-10-15 06:13:10
153	2	Rata-rata IPK Lulusan S1 = 2,10	2024-10-15 06:13:10	2024-10-15 06:13:10
153	3	Lebih dari S1 = 2,10	2024-10-15 06:13:10	2024-10-15 06:13:10
154	1	Kurang dari Profesi = 3,10\n                	2024-10-15 06:13:10	2024-10-15 06:13:10
154	2	Rata-rata IPK Lulusan Profesi = 3,10	2024-10-15 06:13:10	2024-10-15 06:13:10
154	3	Lebih dari Profesi = 3,10	2024-10-15 06:13:10	2024-10-15 06:13:10
155	1	Kurang dari S2/Magister = 3,25\n                	2024-10-15 06:13:10	2024-10-15 06:13:10
155	2	Rata-rata IPK Lulusan S2/Magister = 3,25	2024-10-15 06:13:10	2024-10-15 06:13:10
155	3	Lebih dari S2/Magister = 3,25	2024-10-15 06:13:10	2024-10-15 06:13:10
156	1	Kurang dari Doktor = 3,25 (memenuhi semua CPL)\n                	2024-10-15 06:13:10	2024-10-15 06:13:10
156	2	Rata-rata IPK Lulusan Doktor = 3,25 (memenuhi semua CPL)	2024-10-15 06:13:10	2024-10-15 06:13:10
156	3	Lebih dari Doktor = 3,25 (memenuhi semua CPL)	2024-10-15 06:13:10	2024-10-15 06:13:10
157	1	-	2024-10-15 06:13:10	2024-10-15 06:13:10
157	2	Rata-rata masa studi lulusan : S1 = 16 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
157	3	Kurang dari : S1 = 16 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
158	1	-	2024-10-15 06:13:10	2024-10-15 06:13:10
158	2	Rata-rata masa studi lulusan : S2 = 8 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
158	3	Kurang dari : S2 = 8 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
159	1	-	2024-10-15 06:13:10	2024-10-15 06:13:10
159	2	Rata-rata masa studi lulusan : S3 = 12 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
159	3	Kurang dari : S3 = 12 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
160	1	-	2024-10-15 06:13:10	2024-10-15 06:13:10
160	2	Rata-rata masa studi lulusan : Profesi = 4 semester, Profesi Kedokteran Umum = 8 semester, Profesi Kedokteran gigi = 8 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
160	3	Kurang dari : Profesi = 4 semester, Profesi Kedokteran Umum = 8 semester, Profesi Kedokteran gigi = 8 semester	2024-10-15 06:13:10	2024-10-15 06:13:10
161	1	Prosentase Lulusan Tepat waktu S1 : < 30%	2024-10-15 06:13:10	2024-10-15 06:13:10
161	2	Prosentase Lulusan Tepat waktu S1 = 30-80%	2024-10-15 06:13:10	2024-10-15 06:13:10
161	3	Prosentase Lulusan Tepat waktu S1 > 80%	2024-10-15 06:13:10	2024-10-15 06:13:10
162	1	Prosentase Lulusan Tepat waktu S2/Magister : < 30%	2024-10-15 06:13:10	2024-10-15 06:13:10
162	2	Prosentase Lulusan Tepat waktu S2/Magister = 30-80%	2024-10-15 06:13:10	2024-10-15 06:13:10
162	3	Prosentase Lulusan Tepat waktu S2/Magister > 80%	2024-10-15 06:13:10	2024-10-15 06:13:10
163	1	Prosentase Lulusan Tepat waktu S3/Doktor : < 30%	2024-10-15 06:13:10	2024-10-15 06:13:10
163	2	Prosentase Lulusan Tepat waktu S3/Doktor = 30-80%	2024-10-15 06:13:10	2024-10-15 06:13:10
163	3	Prosentase Lulusan Tepat waktu S3/Doktor > 80%	2024-10-15 06:13:10	2024-10-15 06:13:10
164	1	Prosentase Lulusan Tepat waktu Profesi : < 30%	2024-10-15 06:13:10	2024-10-15 06:13:10
164	2	Prosentase Lulusan Tepat waktu Profesi = 30-80%	2024-10-15 06:13:10	2024-10-15 06:13:10
164	3	Prosentase Lulusan Tepat waktu Profesi > 80%	2024-10-15 06:13:10	2024-10-15 06:13:10
165	1	Lebih dari S1 = minimal 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
165	2	Produktivitas (tingkat kelulusan) S1 = minimal 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
165	3	Kurang dari S1 = minimal 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
166	1	Lebih dari S2/Magister = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
166	2	Produktivitas (tingkat kelulusan) S2/Magister = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
166	3	Kurang dari S2/Magister = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
167	1	Lebih dari S3/Doktor = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
167	2	Produktivitas (tingkat kelulusan) S3/Doktor = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
167	3	Kurang dari S3/Doktor = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
168	1	Lebih dari Profesi = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
168	2	Produktivitas (tingkat kelulusan) Profesi = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
168	3	Kurang dari Profesi = 75%	2024-10-15 06:13:10	2024-10-15 06:13:10
169	1	Tidak dilakukan  Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.	2024-10-15 06:13:10	2024-10-15 06:13:10
169	2	Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester.	2024-10-15 06:13:10	2024-10-15 06:13:10
169	3	Pelaporan hasil penilaian sumatif dari masing-masing program studi ke PD Dikti paling lambat dilaksanakan 2 bulan setelah akhir semester dan dilakukan evaluasi  secara berkala terhadap hasil penilaian sumatif.	2024-10-15 06:13:10	2024-10-15 06:13:10
170	1	Tidak tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.	2024-10-15 06:13:10	2024-10-15 06:13:10
170	2	Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program pendidikan.	2024-10-15 06:13:10	2024-10-15 06:13:10
170	3	Tersedia dokumen penetapan syarat dan predikat kelulusan sesuai program Pendidikan yang disosialisasikan di media sosial fakultas/prodi dan ditinjau secara berkala.	2024-10-15 06:13:10	2024-10-15 06:13:10
171	1	Belum dilaksanakan	2024-10-15 06:13:10	2024-10-15 06:13:10
171	2	Sudah dilaksanakan	2024-10-15 06:13:10	2024-10-15 06:13:10
171	3	Memiliki laporan pelaksanaan RTM	2024-10-15 06:13:10	2024-10-15 06:13:10
\.


--
-- Data for Name: instrumen_pasal_ayat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.instrumen_pasal_ayat (instrumen_id, pasal_id, ayat_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: jabatan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jabatan (id, nama, slug, type, unik, created_at, updated_at) FROM stdin;
1	Rektor	rektor	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
2	Dekan	dekan	fakultas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
3	WD I	wd-i	fakultas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
4	WD II	wd-ii	fakultas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
5	WD III	wd-iii	fakultas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
6	Kaprodi	kaprodi	prodi	t	2024-10-15 06:09:14	2024-10-15 06:09:14
7	Dosen	dosen	prodi	f	2024-10-15 06:09:14	2024-10-15 06:09:14
8	WR I	wr-i	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
9	WR II	wr-ii	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
10	WR III	wr-iii	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
11	WR IV	wr-iv	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
12	Ketua LPPM	ketua-lppm	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
13	Ketua LPTSI	ketua-lptsi	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
14	Ketua LP3M	ketua-lp3m	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
15	Ketua ULT	ketua-ult	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
16	Kepala Biro Akademik	kepala-biro-akademik	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
17	Kepala Biro Keuangan	kepala-biro-keuangan	universitas	t	2024-10-15 06:09:14	2024-10-15 06:09:14
\.


--
-- Data for Name: jabatan_unit; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jabatan_unit (jabatan_id, unit_id, created_at, updated_at) FROM stdin;
8	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:09:14	2024-10-15 06:09:14
9	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:09:14	2024-10-15 06:09:14
10	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:09:14	2024-10-15 06:09:14
11	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:09:14	2024-10-15 06:09:14
12	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:09:14	2024-10-15 06:09:14
13	9d3fc77d-a2d9-4a34-bae4-0e50d6f422da	2024-10-15 06:09:14	2024-10-15 06:09:14
14	9d3fc77d-98dd-45e7-9cfe-fe20a9a85ae7	2024-10-15 06:09:14	2024-10-15 06:09:14
15	9d3fc77d-a515-4039-9a5f-65d8ab715540	2024-10-15 06:09:14	2024-10-15 06:09:14
16	9d3fc77d-9e8b-4654-bd94-ee0960a729cb	2024-10-15 06:09:14	2024-10-15 06:09:14
17	9d3fc77d-a0b2-48b6-afee-335aabe2f441	2024-10-15 06:09:14	2024-10-15 06:09:14
\.


--
-- Data for Name: jabatan_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jabatan_user (user_id, jabatan_id, fakultas_id, prodi_id, unit_id, created_at, updated_at) FROM stdin;
9d3fc7ab-4508-4e14-881b-d98970ff7681	6	\N	9d3fc7a8-28cd-4565-aabb-1a3fae335c44	\N	2024-10-15 06:09:43	2024-10-15 06:09:43
9d3fc7ad-3960-47c0-93c7-9d629aae763c	6	\N	9d3fc7a8-2b05-4b26-ab09-4a7740b99042	\N	2024-10-15 06:09:45	2024-10-15 06:09:45
9d3fc7af-2191-4941-804c-bef64c7492a2	6	\N	9d3fc7a8-2d1c-48d7-a98d-a8370a41e314	\N	2024-10-15 06:09:46	2024-10-15 06:09:46
9d3fc7b1-13bc-4f0b-a4aa-25c9cca8d210	6	\N	9d3fc7a8-2efe-48ef-adc5-744780322464	\N	2024-10-15 06:09:47	2024-10-15 06:09:47
9d3fc7b2-f07d-4549-9f90-a60523483452	6	\N	9d3fc7a8-30da-44ba-ae1c-0fa3b7350325	\N	2024-10-15 06:09:48	2024-10-15 06:09:48
9d3fc7b4-d08a-4049-9f58-3a2e0cb9cf77	6	\N	9d3fc7a8-328c-41cf-98cf-02468eeaa008	\N	2024-10-15 06:09:50	2024-10-15 06:09:50
9d3fc7b6-b319-41f4-abf1-47d937cd0438	6	\N	9d3fc7a8-33f4-4c61-b754-0ed6aece001a	\N	2024-10-15 06:09:51	2024-10-15 06:09:51
9d3fc7b8-8c49-4bc1-a2b4-dfa5b2ff362f	6	\N	9d3fc7a8-355d-40fc-aefa-b461dc31ba98	\N	2024-10-15 06:09:52	2024-10-15 06:09:52
9d3fc7ba-75e8-4887-9d5f-ee5252aba7c0	6	\N	9d3fc7a8-38a4-4600-bb49-0aa308ace0d8	\N	2024-10-15 06:09:53	2024-10-15 06:09:53
9d3fc7bc-693a-47ad-887d-f9d35d6bb086	6	\N	9d3fc7a8-3ae3-4483-86ab-d7f750eb0c0f	\N	2024-10-15 06:09:55	2024-10-15 06:09:55
9d3fc7be-53cf-41b2-bb07-43761fe5da44	6	\N	9d3fc7a8-3cf7-4aca-bf7e-9b8877a9736a	\N	2024-10-15 06:09:56	2024-10-15 06:09:56
9d3fc7c0-42cf-47ee-8261-eb8a6b43612a	6	\N	9d3fc7a8-3ef8-4e0e-9c18-4b7c0f687d72	\N	2024-10-15 06:09:57	2024-10-15 06:09:57
9d3fc7c2-39c8-4b2d-8769-5c943686e6cd	6	\N	9d3fc7a8-413e-4f58-8147-6f509e469ec3	\N	2024-10-15 06:09:58	2024-10-15 06:09:58
9d3fc7c4-27c6-4c65-8888-2dac08a3e200	6	\N	9d3fc7a8-44bc-4bf7-bcef-a0a4d67139a2	\N	2024-10-15 06:10:00	2024-10-15 06:10:00
9d3fc7c6-1ed1-491b-b29b-1ff8a6dd3063	6	\N	9d3fc7a8-4763-4c93-9175-8478a550c115	\N	2024-10-15 06:10:01	2024-10-15 06:10:01
9d3fc7c9-4b61-4765-9f1b-9d8a49639c3f	6	\N	9d3fc7a8-490f-48ac-b3ee-a9ea4c4c401f	\N	2024-10-15 06:10:03	2024-10-15 06:10:03
9d3fc7cb-437e-4190-a761-03fc33519b82	6	\N	9d3fc7a8-4b67-4585-9a1d-bde204d5f87c	\N	2024-10-15 06:10:04	2024-10-15 06:10:04
9d3fc7cd-49b2-4305-8972-3b0e50c4d15d	6	\N	9d3fc7a8-4d4c-4c55-8467-ca5c1ffabd64	\N	2024-10-15 06:10:06	2024-10-15 06:10:06
9d3fc7cf-510d-41b8-930e-d189d72bdbc1	6	\N	9d3fc7a8-4f33-4521-9a1c-cbc04bcdd43f	\N	2024-10-15 06:10:07	2024-10-15 06:10:07
9d3fc7d1-3fa9-44b8-be06-1945c0e8d23f	6	\N	9d3fc7a8-50c1-4ee5-a5e1-3089cb847c93	\N	2024-10-15 06:10:08	2024-10-15 06:10:08
9d3fc7d3-2dc0-4b42-a57a-6b1001b51b85	6	\N	9d3fc7a8-5263-4581-805b-1f2c4a659b6b	\N	2024-10-15 06:10:10	2024-10-15 06:10:10
9d3fc7d5-25b9-4c2b-9d53-5007b940fa27	6	\N	9d3fc7a8-53d9-455f-b0b8-7cd3f200b98a	\N	2024-10-15 06:10:11	2024-10-15 06:10:11
9d3fc7d7-0b87-41fe-95cf-8d421f50c28c	6	\N	9d3fc7a8-5574-402f-a401-c7591b0c5b02	\N	2024-10-15 06:10:12	2024-10-15 06:10:12
9d3fc7d8-e7b5-4f36-b880-9214dbd9af06	6	\N	9d3fc7a8-56fc-47a5-9d5c-1f5351161414	\N	2024-10-15 06:10:13	2024-10-15 06:10:13
9d3fc7da-c06d-4973-91e9-dd93bb3410dc	6	\N	9d3fc7a8-58f8-4b59-833b-8d5e6c4f34ec	\N	2024-10-15 06:10:15	2024-10-15 06:10:15
9d3fc7dc-a1ce-45a5-a1b2-e10a81099253	6	\N	9d3fc7a8-5abf-42bf-9ae5-0dbb5fc152b9	\N	2024-10-15 06:10:16	2024-10-15 06:10:16
9d3fc7de-8bf8-4680-9b2d-dcc30f75e3c8	6	\N	9d3fc7a8-5c6e-4fe9-af18-9a43605787a4	\N	2024-10-15 06:10:17	2024-10-15 06:10:17
9d3fc7e0-ab70-457e-b9c6-a54f44bb15ff	6	\N	9d3fc7a8-5df8-4ac7-b46c-a52ba9d4625c	\N	2024-10-15 06:10:18	2024-10-15 06:10:18
9d3fc7e2-8ad2-4ad1-958e-0795823332ed	6	\N	9d3fc7a8-6058-45a8-a317-ff25014630fb	\N	2024-10-15 06:10:20	2024-10-15 06:10:20
9d3fc7e4-6dae-43e2-ade5-3679c5a6fae1	6	\N	9d3fc7a8-63ab-468e-b289-5dd2af10aba2	\N	2024-10-15 06:10:21	2024-10-15 06:10:21
9d3fc7e6-500e-4416-af22-dc69353b15f7	6	\N	9d3fc7a8-6603-4e8f-b5a1-4b957c4403e5	\N	2024-10-15 06:10:22	2024-10-15 06:10:22
9d3fc7e8-3273-4598-ad83-1942e91bc2f4	6	\N	9d3fc7a8-6869-4745-9022-a9937f5c3fc7	\N	2024-10-15 06:10:23	2024-10-15 06:10:23
9d3fc7ea-0ee1-4b3e-ae0a-b3f3f1d2a713	6	\N	9d3fc7a8-6a76-4930-8414-4b2f1a4514ba	\N	2024-10-15 06:10:25	2024-10-15 06:10:25
9d3fc7ec-7e9d-4560-983b-8502af95b076	6	\N	9d3fc7a8-6c7c-4716-b019-a53e5133fad8	\N	2024-10-15 06:10:26	2024-10-15 06:10:26
9d3fc7ee-9c99-4218-b621-e6489d0e75d1	6	\N	9d3fc7a8-6e28-48ca-b772-6760433eae85	\N	2024-10-15 06:10:28	2024-10-15 06:10:28
9d3fc7f0-7f13-4ce0-9f46-11fa2c7f10ba	6	\N	9d3fc7a8-6ff2-43ba-9146-1dadd8e445bb	\N	2024-10-15 06:10:29	2024-10-15 06:10:29
9d3fc7f2-65c8-4f4f-9293-0a2c369f45a8	6	\N	9d3fc7a8-719d-4736-9240-b8f7c9a58713	\N	2024-10-15 06:10:30	2024-10-15 06:10:30
9d3fc7f4-3d32-4076-ba73-cffe6a9b133c	6	\N	9d3fc7a8-734a-4e29-88a1-95442b32871b	\N	2024-10-15 06:10:31	2024-10-15 06:10:31
9d3fc7f6-1ead-42d6-9443-5bcb3c563786	6	\N	9d3fc7a8-74e4-4224-bd5f-61335120a5f8	\N	2024-10-15 06:10:32	2024-10-15 06:10:32
9d3fc7f8-0ee3-4764-9f18-188381a75e0c	6	\N	9d3fc7a8-7697-4daf-958a-e053c880f951	\N	2024-10-15 06:10:34	2024-10-15 06:10:34
9d3fc7f9-ed00-43f2-8e7f-38bc19033be1	6	\N	9d3fc7a8-7819-4e69-aad1-2241ef092850	\N	2024-10-15 06:10:35	2024-10-15 06:10:35
9d3fc7fb-c81b-4da0-a889-3ec022335988	6	\N	9d3fc7a8-79c1-44ba-92c0-db053e567c8c	\N	2024-10-15 06:10:36	2024-10-15 06:10:36
9d3fc7fd-a6af-42c1-9bd6-fe5035255580	6	\N	9d3fc7a8-7b32-4abf-b6b8-71fa27359e31	\N	2024-10-15 06:10:37	2024-10-15 06:10:37
9d3fc7ff-8d8e-4651-92ad-81f8f7b041a6	6	\N	9d3fc7a8-7cda-41be-9a00-f2aba9aec153	\N	2024-10-15 06:10:39	2024-10-15 06:10:39
9d3fc801-8123-4fc4-a873-7650e3abd8e8	6	\N	9d3fc7a8-7e7d-479b-a26b-72f5613ce0de	\N	2024-10-15 06:10:40	2024-10-15 06:10:40
9d3fc803-79ea-40cb-a4e1-ff0d57f2f34f	6	\N	9d3fc7a8-8009-4889-b876-5eb4c8541c5f	\N	2024-10-15 06:10:41	2024-10-15 06:10:41
9d3fc806-5ead-4f09-9afc-5550802bb678	6	\N	9d3fc7a8-818c-42e7-919e-3dbb5a1a5e35	\N	2024-10-15 06:10:43	2024-10-15 06:10:43
9d3fc808-6fdc-4f99-83c2-3f0231f90ebf	6	\N	9d3fc7a8-839c-4d72-97c2-2d2fdc4731b8	\N	2024-10-15 06:10:44	2024-10-15 06:10:44
9d3fc80a-5b27-44c6-86e4-dda699dbc49d	6	\N	9d3fc7a8-855c-4a03-9e2c-b4f0b21a4db7	\N	2024-10-15 06:10:46	2024-10-15 06:10:46
9d3fc80c-578c-4071-9d22-ddff58ad362d	6	\N	9d3fc7a8-8922-4919-8d37-940b9300f51a	\N	2024-10-15 06:10:47	2024-10-15 06:10:47
9d3fc80e-4224-472f-836c-ba8cee2ba284	6	\N	9d3fc7a8-8b3d-43e7-b333-ae0684965284	\N	2024-10-15 06:10:48	2024-10-15 06:10:48
9d3fc810-2ec1-44dc-9e4b-d56081fc550a	6	\N	9d3fc7a8-8d3e-4ada-9363-a82372be1a8d	\N	2024-10-15 06:10:50	2024-10-15 06:10:50
9d3fc812-17f4-4c32-97cf-978da054fa4f	6	\N	9d3fc7a8-8f54-493d-bb62-e4a61a404717	\N	2024-10-15 06:10:51	2024-10-15 06:10:51
9d3fc813-eeb9-46ed-9b26-e621ffe33096	6	\N	9d3fc7a8-91c0-4f41-b4ba-de2a9940c52f	\N	2024-10-15 06:10:52	2024-10-15 06:10:52
9d3fc815-c0d1-4fb5-8fa0-5099a8540dd5	6	\N	9d3fc7a8-942c-4ea3-93f5-92eeaaacaae2	\N	2024-10-15 06:10:53	2024-10-15 06:10:53
9d3fc817-aaf5-408d-b60d-d6997313ee50	6	\N	9d3fc7a8-9608-4049-9468-f7a24c074767	\N	2024-10-15 06:10:54	2024-10-15 06:10:54
9d3fc819-84af-4815-b18a-3404d438ec1a	6	\N	9d3fc7a8-97fd-456c-b005-97dbdb9e6c22	\N	2024-10-15 06:10:56	2024-10-15 06:10:56
9d3fc81b-6765-487e-b291-9510340c55e4	6	\N	9d3fc7a8-9a30-46d2-896d-14dbb8790795	\N	2024-10-15 06:10:57	2024-10-15 06:10:57
9d3fc81d-51e8-46c9-96e1-859c80259d6a	6	\N	9d3fc7a8-9c6d-45de-8530-9c6b551c6558	\N	2024-10-15 06:10:58	2024-10-15 06:10:58
9d3fc81f-3681-40cd-9923-06ed5c7d3af7	6	\N	9d3fc7a8-9e43-4611-b3b1-d4a5d5b22454	\N	2024-10-15 06:10:59	2024-10-15 06:10:59
9d3fc821-198c-48d1-89dc-71c11889f5ac	6	\N	9d3fc7a8-9ffa-422b-a490-7189bb13ded1	\N	2024-10-15 06:11:01	2024-10-15 06:11:01
9d3fc823-e09f-4434-aa21-274e4bc4b3d3	6	\N	9d3fc7a8-a259-442e-ae35-f3a15504d589	\N	2024-10-15 06:11:02	2024-10-15 06:11:02
9d3fc825-ef70-4ede-97f4-a51e68e848a3	6	\N	9d3fc7a8-a44e-4999-830d-2864740397aa	\N	2024-10-15 06:11:04	2024-10-15 06:11:04
9d3fc827-dd0a-4986-8d58-2bd6bb7c6d57	6	\N	9d3fc7a8-a658-480e-a522-48e3fb8e1db4	\N	2024-10-15 06:11:05	2024-10-15 06:11:05
9d3fc829-d05c-4391-ac7e-0103875b393f	6	\N	9d3fc7a8-a83f-4b34-a95e-9765f9b3cd7b	\N	2024-10-15 06:11:06	2024-10-15 06:11:06
9d3fc82b-bc2c-4035-b97f-6f6012b8de0a	6	\N	9d3fc7a8-ab62-4854-95ff-7dc0bef8db43	\N	2024-10-15 06:11:08	2024-10-15 06:11:08
9d3fc82d-9a27-4b2c-829a-dda39fa35954	6	\N	9d3fc7a8-add6-40dd-9e1c-2bdfb0b53386	\N	2024-10-15 06:11:09	2024-10-15 06:11:09
9d3fc82f-7367-4242-a899-d48154325f67	6	\N	9d3fc7a8-b023-455b-9452-6f21ecd14d6b	\N	2024-10-15 06:11:10	2024-10-15 06:11:10
9d3fc831-6883-494c-87b6-547b9e787bef	6	\N	9d3fc7a8-b333-4cff-b79a-3d3e1fda6ad8	\N	2024-10-15 06:11:11	2024-10-15 06:11:11
9d3fc833-49ae-44cf-af7c-39fb46eeee98	6	\N	9d3fc7a8-b56d-460e-b897-556e136570df	\N	2024-10-15 06:11:13	2024-10-15 06:11:13
9d3fc835-3438-4613-aa48-927ef4a5e7d8	6	\N	9d3fc7a8-b823-42e8-90af-54097bfa4054	\N	2024-10-15 06:11:14	2024-10-15 06:11:14
9d3fc837-1872-46ce-8175-305a39745b71	6	\N	9d3fc7a8-ba15-4b92-9f79-b3c63459eabe	\N	2024-10-15 06:11:15	2024-10-15 06:11:15
9d3fc839-001b-4e38-a5ce-c07c396245b7	6	\N	9d3fc7a8-bbee-4bc9-9e50-2737ac29aa2a	\N	2024-10-15 06:11:16	2024-10-15 06:11:16
9d3fc83a-f24f-4960-b617-0453215a689c	6	\N	9d3fc7a8-be23-4d8e-9655-27f733a429ac	\N	2024-10-15 06:11:18	2024-10-15 06:11:18
9d3fc83d-353c-473f-9718-8d402b0e14f0	6	\N	9d3fc7a8-bfec-4657-a943-b096ddd9d208	\N	2024-10-15 06:11:19	2024-10-15 06:11:19
9d3fc83f-253e-4c4a-b14f-a08783f3694a	6	\N	9d3fc7a8-c188-4569-a22c-3817f2dc8f1e	\N	2024-10-15 06:11:20	2024-10-15 06:11:20
9d3fc841-0c67-48ab-83b9-59fe1ef5feff	6	\N	9d3fc7a8-c34d-4c65-989b-2345a86ae9d2	\N	2024-10-15 06:11:22	2024-10-15 06:11:22
9d3fc842-ef9f-401a-a0cf-67791e298a02	6	\N	9d3fc7a8-c4e8-4b3c-add4-177af6431e1a	\N	2024-10-15 06:11:23	2024-10-15 06:11:23
9d3fc844-e30b-40db-bca0-40acda16ba3f	6	\N	9d3fc7a8-c6a7-48a7-8fca-d47f107d8ee2	\N	2024-10-15 06:11:24	2024-10-15 06:11:24
9d3fc846-d093-42e3-b6f8-2353b03cc785	6	\N	9d3fc7a8-c843-44de-a2ca-1af639f4026b	\N	2024-10-15 06:11:25	2024-10-15 06:11:25
9d3fc848-be3d-4d02-9a80-f73220276f9b	6	\N	9d3fc7a8-ca1a-4883-a5ba-a633b3e8fb02	\N	2024-10-15 06:11:27	2024-10-15 06:11:27
9d3fc84a-aedc-4013-aa4f-c47eac65747e	6	\N	9d3fc7a8-cc78-4564-834d-2415ce0a3274	\N	2024-10-15 06:11:28	2024-10-15 06:11:28
9d3fc84c-93b2-4d01-b543-f20ea55b63f6	6	\N	9d3fc7a8-ce64-4f94-872b-32680d6fff97	\N	2024-10-15 06:11:29	2024-10-15 06:11:29
9d3fc84e-85d9-47fc-971f-b892b50c077f	6	\N	9d3fc7a8-d0d3-4c52-8024-79c10515dcc9	\N	2024-10-15 06:11:30	2024-10-15 06:11:30
9d3fc850-76e4-400f-9141-416032dfd627	6	\N	9d3fc7a8-d2d5-466a-a48c-715c9d1b6f69	\N	2024-10-15 06:11:32	2024-10-15 06:11:32
9d3fc852-5b43-4304-bbb5-31e2e7218477	6	\N	9d3fc7a8-d4c4-4b3b-8a25-bf1bc988a5b7	\N	2024-10-15 06:11:33	2024-10-15 06:11:33
9d3fc854-388a-4e16-83c4-61647ce60ff5	6	\N	9d3fc7a8-d68f-4108-b079-afbfa200a018	\N	2024-10-15 06:11:34	2024-10-15 06:11:34
9d3fc856-1899-4dc6-98f9-7e811c2044db	6	\N	9d3fc7a8-dad3-43b7-9f5f-93cb4bd1054c	\N	2024-10-15 06:11:35	2024-10-15 06:11:35
9d3fc857-fe56-4a90-803a-16dc278b1ae8	6	\N	9d3fc7a8-dea7-4ae5-a096-fc54e3a3d621	\N	2024-10-15 06:11:37	2024-10-15 06:11:37
9d3fc859-f2a4-42ef-97b3-9558fe0e20a1	6	\N	9d3fc7a8-e1b9-4a55-915d-62bab20420e7	\N	2024-10-15 06:11:38	2024-10-15 06:11:38
9d3fc85b-dc10-4efb-956b-be0c6d9647cc	6	\N	9d3fc7a8-e412-40fe-9fe1-6e77ec884cb5	\N	2024-10-15 06:11:39	2024-10-15 06:11:39
9d3fc85d-c97c-4112-b051-6e7858892d6e	6	\N	9d3fc7a8-e61e-41df-887f-81dcf5635fae	\N	2024-10-15 06:11:40	2024-10-15 06:11:40
9d3fc85f-afe9-4551-b493-83897f072e60	6	\N	9d3fc7a8-e822-4989-9875-db53a04449ee	\N	2024-10-15 06:11:42	2024-10-15 06:11:42
9d3fc861-95ca-47a0-b3cb-11be97ca9fd1	6	\N	9d3fc7a8-ea2b-4d64-98da-7a72af8e412c	\N	2024-10-15 06:11:43	2024-10-15 06:11:43
9d3fc863-7d0f-4aef-a17b-47d37a4ca703	6	\N	9d3fc7a8-ec65-4cc3-b951-c0b290c17c08	\N	2024-10-15 06:11:44	2024-10-15 06:11:44
9d3fc865-60f2-41ad-92ee-c88dfda59f8d	6	\N	9d3fc7a8-ee4b-4ae3-9ede-cdcc9d414e76	\N	2024-10-15 06:11:45	2024-10-15 06:11:45
9d3fc867-4f8c-487f-806e-3e419eff4b2b	6	\N	9d3fc7a8-f05d-44a6-a6da-554677767f40	\N	2024-10-15 06:11:47	2024-10-15 06:11:47
9d3fc869-3613-43f4-baea-0c1a378b1595	6	\N	9d3fc7a8-f21e-464f-85bd-76b2bddca9c7	\N	2024-10-15 06:11:48	2024-10-15 06:11:48
9d3fc86b-12cc-4291-9934-721bfc43e4ad	6	\N	9d3fc7a8-f3f0-467e-8f6a-da9af6e30b48	\N	2024-10-15 06:11:49	2024-10-15 06:11:49
9d3fc86c-f7e3-4697-a468-328b74441e44	2	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	\N	2024-10-15 06:11:50	2024-10-15 06:11:50
9d3fc86e-d7fa-441f-a7f9-c877e15a4699	2	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	\N	2024-10-15 06:11:52	2024-10-15 06:11:52
9d3fc870-b598-4fb8-8524-4dad9c6b272a	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	\N	2024-10-15 06:11:53	2024-10-15 06:11:53
9d3fc872-9f14-4001-ad99-a9fadd756c9d	2	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	\N	2024-10-15 06:11:54	2024-10-15 06:11:54
9d3fc874-8573-4a74-898c-c79fec4849fc	2	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	\N	2024-10-15 06:11:55	2024-10-15 06:11:55
9d3fc876-69d5-46a4-9518-f6cf725ff213	2	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	\N	2024-10-15 06:11:57	2024-10-15 06:11:57
9d3fc878-52f0-4946-8eb0-d25e8fe3217b	2	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	\N	2024-10-15 06:11:58	2024-10-15 06:11:58
9d3fc87a-388f-488d-a0f7-4d6aa71facda	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	\N	2024-10-15 06:11:59	2024-10-15 06:11:59
9d3fc87c-23a6-4172-ba7c-7410b6ac0c88	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	\N	2024-10-15 06:12:00	2024-10-15 06:12:00
9d3fc87e-59eb-4555-823f-916a57fb5066	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	\N	2024-10-15 06:12:02	2024-10-15 06:12:02
9d3fc880-e111-4787-af26-3aa17d15952c	2	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	\N	2024-10-15 06:12:03	2024-10-15 06:12:03
9d3fc882-d75e-4190-bf55-4a9067e042ca	2	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	\N	2024-10-15 06:12:05	2024-10-15 06:12:05
9d3fc884-c3a2-43b3-9c3e-11f3eb3087c5	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	\N	2024-10-15 06:12:06	2024-10-15 06:12:06
9d3fc886-ba81-4c5a-ab7c-75b9f1d43199	3	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	\N	2024-10-15 06:12:07	2024-10-15 06:12:07
9d3fc888-a10e-4365-b410-b6d2b59d8d96	3	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	\N	2024-10-15 06:12:08	2024-10-15 06:12:08
9d3fc88a-81db-435c-abfb-01f66117285b	3	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	\N	2024-10-15 06:12:10	2024-10-15 06:12:10
9d3fc88c-69fd-4f60-a035-e6200c96f058	3	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	\N	2024-10-15 06:12:11	2024-10-15 06:12:11
9d3fc88e-4fe6-4e2d-981f-6beab174fd37	3	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	\N	2024-10-15 06:12:12	2024-10-15 06:12:12
9d3fc890-294d-4a46-a5bd-14633cb280c8	3	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	\N	2024-10-15 06:12:13	2024-10-15 06:12:13
9d3fc892-044c-4202-94c4-471547821b9b	3	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	\N	2024-10-15 06:12:15	2024-10-15 06:12:15
9d3fc893-e6c1-4387-8374-849290c04d84	3	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	\N	2024-10-15 06:12:16	2024-10-15 06:12:16
9d3fc895-d756-41f3-aba9-beeca860fe4f	3	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	\N	2024-10-15 06:12:17	2024-10-15 06:12:17
9d3fc897-c108-4f08-88c2-b68d1d7ed767	3	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	\N	2024-10-15 06:12:18	2024-10-15 06:12:18
9d3fc899-e623-4eac-b649-6b33f2100167	3	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	\N	2024-10-15 06:12:20	2024-10-15 06:12:20
9d3fc89b-c919-4e4a-80cf-91e9652c0c1b	4	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	\N	2024-10-15 06:12:21	2024-10-15 06:12:21
9d3fc89d-a8e8-4d47-9d67-f9f0e663af11	4	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	\N	2024-10-15 06:12:22	2024-10-15 06:12:22
9d3fc89f-8ecf-479d-a978-776070339354	4	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	\N	2024-10-15 06:12:24	2024-10-15 06:12:24
9d3fc8a1-7da7-4ed6-8177-992fb61e21f6	4	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	\N	2024-10-15 06:12:25	2024-10-15 06:12:25
9d3fc8a3-6893-460d-98c1-1bf0a270c876	4	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	\N	2024-10-15 06:12:26	2024-10-15 06:12:26
9d3fc8a5-545a-43bd-9983-f6a67243678e	4	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	\N	2024-10-15 06:12:27	2024-10-15 06:12:27
9d3fc8a7-4007-401d-b7ef-286e796ee231	4	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	\N	2024-10-15 06:12:29	2024-10-15 06:12:29
9d3fc8a9-1a70-4ec6-b66f-7e9429730571	4	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	\N	2024-10-15 06:12:30	2024-10-15 06:12:30
9d3fc8ab-0a4f-4b9a-aab3-fe37e2237c46	4	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	\N	2024-10-15 06:12:31	2024-10-15 06:12:31
9d3fc8ac-ec14-420c-83ce-ebdb84726941	4	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	\N	2024-10-15 06:12:32	2024-10-15 06:12:32
9d3fc8ae-d0f9-4ea7-a97c-c5b489f8e5a1	4	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	\N	2024-10-15 06:12:34	2024-10-15 06:12:34
9d3fc8b0-b64d-490e-8cdd-6f3ff92a8496	4	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	\N	2024-10-15 06:12:35	2024-10-15 06:12:35
9d3fc8b2-9fa2-4d9a-9ddf-74c040f6e21e	5	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	\N	\N	2024-10-15 06:12:36	2024-10-15 06:12:36
9d3fc8b4-7fcd-46d4-b051-67d136d0fec4	5	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	\N	\N	2024-10-15 06:12:37	2024-10-15 06:12:37
9d3fc8b6-5bbf-43f3-bde4-d9f01c295f84	5	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	\N	\N	2024-10-15 06:12:38	2024-10-15 06:12:38
9d3fc8b8-3b64-413d-8692-1b17582c041e	5	9d3fc7a8-1526-42fe-866a-5f65231bd245	\N	\N	2024-10-15 06:12:40	2024-10-15 06:12:40
9d3fc8ba-1ddc-4f7b-9919-a39c5a41c87d	5	9d3fc7a8-1788-476f-8fa7-73933937e593	\N	\N	2024-10-15 06:12:41	2024-10-15 06:12:41
9d3fc8bc-049c-4a85-8690-aece5f5b5a3b	5	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	\N	\N	2024-10-15 06:12:42	2024-10-15 06:12:42
9d3fc8bd-f2c4-4b8a-923e-ef13844bd221	5	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	\N	\N	2024-10-15 06:12:43	2024-10-15 06:12:43
9d3fc8bf-e09a-4b14-8a54-7e50c8172779	5	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	\N	\N	2024-10-15 06:12:45	2024-10-15 06:12:45
9d3fc8c1-c5a9-4b16-a1ea-0b4d7403ef6c	5	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	\N	\N	2024-10-15 06:12:46	2024-10-15 06:12:46
9d3fc8c3-b0ff-4b00-ab70-dd124f37fa80	5	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	\N	\N	2024-10-15 06:12:47	2024-10-15 06:12:47
9d3fc8c5-9083-4c36-ac4c-1b9fcb74ed57	5	9d3fc7a8-24af-4504-aac7-89a578a8ea65	\N	\N	2024-10-15 06:12:48	2024-10-15 06:12:48
9d3fc8c7-7a87-4d53-b8ba-ed33908a6f98	5	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	\N	\N	2024-10-15 06:12:50	2024-10-15 06:12:50
9d3fc8c9-697a-4f42-8465-8b49a0333f78	12	\N	\N	9d3fc77d-91df-439c-846a-efd5aab93966	2024-10-15 06:12:51	2024-10-15 06:12:51
9d3fc8cb-4b6b-41df-acbd-46deee5952a0	14	\N	\N	9d3fc77d-98dd-45e7-9cfe-fe20a9a85ae7	2024-10-15 06:12:52	2024-10-15 06:12:52
9d3fc8cd-2d49-4616-a0b8-d11bb5400716	8	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:12:53	2024-10-15 06:12:53
9d3fc8cf-0562-4646-a704-890d1f7c69b7	9	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:12:55	2024-10-15 06:12:55
9d3fc8d0-e649-4623-96a7-f32d26179f22	10	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:12:56	2024-10-15 06:12:56
9d3fc8d2-d5cd-4c22-97ff-0139bd1f562a	11	\N	\N	9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	2024-10-15 06:12:57	2024-10-15 06:12:57
9d3fc8d4-ba68-4f5a-a6bb-05ff4b1b9c5a	13	\N	\N	9d3fc77d-a2d9-4a34-bae4-0e50d6f422da	2024-10-15 06:12:58	2024-10-15 06:12:58
9d3fc8d6-a78b-4529-a859-94d4bc50eecd	15	\N	\N	9d3fc77d-a515-4039-9a5f-65d8ab715540	2024-10-15 06:13:00	2024-10-15 06:13:00
9d3fc8d8-932a-4d09-b18b-82134554b518	16	\N	\N	9d3fc77d-9e8b-4654-bd94-ee0960a729cb	2024-10-15 06:13:01	2024-10-15 06:13:01
9d3fc8db-6c29-4625-864a-159c7f5e8777	17	\N	\N	9d3fc77d-a0b2-48b6-afee-335aabe2f441	2024-10-15 06:13:03	2024-10-15 06:13:03
\.


--
-- Data for Name: jadwal_audit; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jadwal_audit (id, jadwal, tgl_mulai, tgl_selesai, import, fitur_auditor, created_at, updated_at) FROM stdin;
9d3fc8e7-1495-46f4-b29d-2d971f5d4311	Audit 1	2024-08-01	2024-10-31	f	f	2024-10-15 06:13:10	2024-10-15 06:13:10
\.


--
-- Data for Name: jawaban_auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jawaban_auditee (id, jadwal_audit_id, form_id, auditee_id, fakultas_id, prodi_id, unit_id, jawaban, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: jawaban_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jawaban_auditor (id, jadwal_audit_id, form_id, auditor_id, fakultas_id, prodi_id, unit_id, kriteria_id, catatan, daftar_tilik, ptk, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: jenis_pertanyaan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jenis_pertanyaan (id, nama, slug, created_at, updated_at) FROM stdin;
1	text	text	2024-10-15 06:09:42	2024-10-15 06:09:42
2	number	number	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: jenjang; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jenjang (id, nama, created_at, updated_at) FROM stdin;
1	D3	2024-10-15 06:09:41	2024-10-15 06:09:41
2	S1	2024-10-15 06:09:41	2024-10-15 06:09:41
3	S2	2024-10-15 06:09:41	2024-10-15 06:09:41
4	S3	2024-10-15 06:09:41	2024-10-15 06:09:41
5	Profesi	2024-10-15 06:09:41	2024-10-15 06:09:41
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: kategori; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.kategori (id, standar_id, nama, kode, created_at, updated_at) FROM stdin;
1	1	Luaran	L	2024-10-15 06:09:42	2024-10-15 06:09:42
2	1	Proses	P	2024-10-15 06:09:42	2024-10-15 06:09:42
3	1	Masukan	M	2024-10-15 06:09:42	2024-10-15 06:09:42
4	2	Luaran	L	2024-10-15 06:09:42	2024-10-15 06:09:42
5	2	Proses	P	2024-10-15 06:09:42	2024-10-15 06:09:42
6	2	Masukan	M	2024-10-15 06:09:42	2024-10-15 06:09:42
7	3	Luaran	L	2024-10-15 06:09:42	2024-10-15 06:09:42
8	3	Proses	P	2024-10-15 06:09:42	2024-10-15 06:09:42
9	3	Masukan	M	2024-10-15 06:09:42	2024-10-15 06:09:42
10	4	Kerjasama	K	2024-10-15 06:09:42	2024-10-15 06:09:42
11	4	Layanan	L	2024-10-15 06:09:42	2024-10-15 06:09:42
12	4	Tatakelola	T	2024-10-15 06:09:42	2024-10-15 06:09:42
13	4	Kemahasiswaan	M	2024-10-15 06:09:42	2024-10-15 06:09:42
14	4	Keuangan	U	2024-10-15 06:09:42	2024-10-15 06:09:42
15	4	Sarana dan Prasarana	S	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: kriteria; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.kriteria (id, nama, slug, created_at, updated_at) FROM stdin;
1	Belum Memenuhi	belum-memenuhi	2024-10-15 06:09:42	2024-10-15 06:09:42
2	Memenuhi	memenuhi	2024-10-15 06:09:42	2024-10-15 06:09:42
3	Melampaui	melampaui	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: laporan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.laporan (id, jadwal_audit_id, fakultas_id, prodi_id, unit_id, tgl, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: laporan_auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.laporan_auditee (id, laporan_id, auditee_id, approve, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: laporan_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.laporan_auditor (id, laporan_id, auditor_id, approve, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: laporan_form; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.laporan_form (id, laporan_id, form_id, auditor_id, kelebihan, ruang_peningkatan, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: level; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.level (id, nama, slug, created_at, updated_at) FROM stdin;
1	Prodi Program Studi	prodi	2024-10-15 06:09:42	2024-10-15 06:09:42
2	Fakultas Unit Pengelola Program Studi	fakultas	2024-10-15 06:09:42	2024-10-15 06:09:42
3	Universitas	universitas	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: link; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.link (id, jadwal_audit_id, form_id, auditee_id, fakultas_id, prodi_id, unit_id, link, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.menu (id, menu, status, route, created_at, updated_at) FROM stdin;
1	Dashboard	t	dashboard	2024-10-15 06:09:41	2024-10-15 06:09:41
2	Audit	t	audit	2024-10-15 06:09:41	2024-10-15 06:09:41
3	User	t	user	2024-10-15 06:09:41	2024-10-15 06:09:41
4	Dokumen	t	dokumen	2024-10-15 06:09:41	2024-10-15 06:09:41
5	Assessment	t	assessment	2024-10-15 06:09:41	2024-10-15 06:09:41
6	Akademik	t	akademik	2024-10-15 06:09:41	2024-10-15 06:09:41
7	Menu	t	menu	2024-10-15 06:09:41	2024-10-15 06:09:41
8	Auditan	t	auditan	2024-10-15 06:09:41	2024-10-15 06:09:41
9	Auditor	t	auditor	2024-10-15 06:09:41	2024-10-15 06:09:41
10	Peer Assessment	t	peer-assessment	2024-10-15 06:09:41	2024-10-15 06:09:41
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2024_08_28_125105_create_permission_tables	1
5	2024_08_28_125106_create_menu_table	1
6	2024_08_28_125107_create_submenu_table	1
7	2024_08_28_125108_create_role_menu_table	1
8	2024_08_28_125109_create_role_submenu_table	1
9	2024_08_28_141702_create_jenjang_table	1
10	2024_08_28_141708_create_fakultas_table	1
11	2024_08_28_141713_create_prodi_table	1
12	2024_08_28_141719_create_unit_table	1
13	2024_08_28_174027_create_jabatan_table	1
14	2024_08_28_174030_create_jabatan_unit_table	1
15	2024_08_28_174032_create_jabatan_user_table	1
16	2024_08_29_192856_create_peraturan_table	1
17	2024_08_30_060624_create_pasal_table	1
18	2024_08_30_060629_create_ayat_table	1
19	2024_08_30_112714_create_standar_table	1
20	2024_08_30_112749_create_kategori_table	1
21	2024_08_30_112819_create_level_table	1
22	2024_08_30_112824_create_kriteria_table	1
23	2024_08_30_112830_create_jenis_pertanyaan_table	1
24	2024_08_30_155936_create_instrumen_table	1
25	2024_08_30_155941_create_instrumen_pasal_ayat_table	1
26	2024_08_30_155950_create_instrumen_jenjang_table	1
27	2024_08_30_155959_create_instrumen_kriteria_table	1
28	2024_08_30_160007_create_instrumen_jabatan_table	1
29	2024_08_30_160010_create_setting_table	1
30	2024_08_30_160012_create_jadwal_audit_table	1
31	2024_08_30_160016_create_auditee_table	1
32	2024_08_30_160020_create_auditor_table	1
33	2024_08_30_160023_create_auditee_auditor_table	1
34	2024_08_30_234117_create_form_table	1
35	2024_08_31_000620_create_assessment_pertanyaan_table	1
36	2024_08_31_000627_create_assessment_form_table	1
37	2024_08_31_000634_create_assessment_jawaban_table	1
38	2024_09_01_110223_create_status_audit_auditee_table	1
39	2024_09_01_110231_create_jawaban_auditee_table	1
40	2024_09_01_110254_create_link_table	1
41	2024_09_01_110304_create_jawaban_auditor_table	1
42	2024_09_01_110313_create_status_audit_auditor_table	1
43	2024_09_01_111525_create_berita_acara_table	1
44	2024_09_01_111602_create_berita_acara_auditee_table	1
45	2024_09_01_111606_create_berita_acara_auditor_table	1
46	2024_09_01_111625_create_ptk_table	1
47	2024_09_01_111630_create_ptk_form_table	1
48	2024_09_01_111641_create_ptk_form_deskripsi_table	1
49	2024_09_01_111648_create_ptk_form_rencana_table	1
50	2024_09_01_111659_create_ptk_auditee_table	1
51	2024_09_01_111703_create_ptk_auditor_table	1
52	2024_09_01_112311_create_laporan_table	1
53	2024_09_01_112320_create_laporan_auditee_table	1
54	2024_09_01_112324_create_laporan_auditor_table	1
55	2024_09_01_112333_create_laporan_form_table	1
56	2024_09_01_112355_create_notifikasi_table	1
57	2024_09_01_112428_create_status_assessment_table	1
58	2024_09_01_112445_create_status_ptk_auditor_table	1
59	2024_09_01_112448_create_status_ptk_auditee_table	1
60	2024_09_01_112458_create_status_laporan_table	1
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	9d3fc780-0480-4700-b4a6-87d75a4a4695
4	App\\Models\\User	9d3fc781-f67a-4cb7-aaf1-3e7aac0fc66b
4	App\\Models\\User	9d3fc784-1cd8-479b-ad40-d86ee84e801c
4	App\\Models\\User	9d3fc786-05aa-45ab-8e0b-5aed86dec369
4	App\\Models\\User	9d3fc787-f8d5-4027-985c-5cf051ed074e
4	App\\Models\\User	9d3fc789-ddf9-4870-9175-ea21ae49f70f
4	App\\Models\\User	9d3fc78b-cb8d-4a01-82a1-157f4a38d1ff
4	App\\Models\\User	9d3fc78d-b439-4450-89e6-9807017ea9d8
4	App\\Models\\User	9d3fc78f-9e1f-4d50-a9c1-588ed4734f26
4	App\\Models\\User	9d3fc791-8dc6-4601-9470-d447ec20646c
4	App\\Models\\User	9d3fc793-761d-4e58-8f17-e6b15e06ffd6
4	App\\Models\\User	9d3fc795-5e37-4dfd-8006-e9dffd330123
4	App\\Models\\User	9d3fc797-58da-4067-85b7-c53772b712a8
4	App\\Models\\User	9d3fc799-5e6b-40a2-872a-5d9cd4fe238f
4	App\\Models\\User	9d3fc79b-41cf-4ae4-8f80-0d597d7f4e50
4	App\\Models\\User	9d3fc79d-1d7e-4e39-b4d5-63385a9d933b
4	App\\Models\\User	9d3fc79f-28d3-48f6-8e81-c89f12e8c862
4	App\\Models\\User	9d3fc7a1-10d6-4556-a5ea-d0f3577c4a48
4	App\\Models\\User	9d3fc7a2-fdeb-4086-a4c6-fbfa19e1a341
4	App\\Models\\User	9d3fc7a4-ec81-4560-8404-3a613a430135
4	App\\Models\\User	9d3fc7a6-d400-48d6-a92b-990221751ece
7	App\\Models\\User	9d3fc7ab-4508-4e14-881b-d98970ff7681
7	App\\Models\\User	9d3fc7ad-3960-47c0-93c7-9d629aae763c
7	App\\Models\\User	9d3fc7af-2191-4941-804c-bef64c7492a2
7	App\\Models\\User	9d3fc7b1-13bc-4f0b-a4aa-25c9cca8d210
7	App\\Models\\User	9d3fc7b2-f07d-4549-9f90-a60523483452
7	App\\Models\\User	9d3fc7b4-d08a-4049-9f58-3a2e0cb9cf77
7	App\\Models\\User	9d3fc7b6-b319-41f4-abf1-47d937cd0438
7	App\\Models\\User	9d3fc7b8-8c49-4bc1-a2b4-dfa5b2ff362f
7	App\\Models\\User	9d3fc7ba-75e8-4887-9d5f-ee5252aba7c0
7	App\\Models\\User	9d3fc7bc-693a-47ad-887d-f9d35d6bb086
7	App\\Models\\User	9d3fc7be-53cf-41b2-bb07-43761fe5da44
7	App\\Models\\User	9d3fc7c0-42cf-47ee-8261-eb8a6b43612a
7	App\\Models\\User	9d3fc7c2-39c8-4b2d-8769-5c943686e6cd
7	App\\Models\\User	9d3fc7c4-27c6-4c65-8888-2dac08a3e200
7	App\\Models\\User	9d3fc7c6-1ed1-491b-b29b-1ff8a6dd3063
7	App\\Models\\User	9d3fc7c9-4b61-4765-9f1b-9d8a49639c3f
7	App\\Models\\User	9d3fc7cb-437e-4190-a761-03fc33519b82
7	App\\Models\\User	9d3fc7cd-49b2-4305-8972-3b0e50c4d15d
7	App\\Models\\User	9d3fc7cf-510d-41b8-930e-d189d72bdbc1
7	App\\Models\\User	9d3fc7d1-3fa9-44b8-be06-1945c0e8d23f
7	App\\Models\\User	9d3fc7d3-2dc0-4b42-a57a-6b1001b51b85
7	App\\Models\\User	9d3fc7d5-25b9-4c2b-9d53-5007b940fa27
7	App\\Models\\User	9d3fc7d7-0b87-41fe-95cf-8d421f50c28c
7	App\\Models\\User	9d3fc7d8-e7b5-4f36-b880-9214dbd9af06
7	App\\Models\\User	9d3fc7da-c06d-4973-91e9-dd93bb3410dc
7	App\\Models\\User	9d3fc7dc-a1ce-45a5-a1b2-e10a81099253
7	App\\Models\\User	9d3fc7de-8bf8-4680-9b2d-dcc30f75e3c8
7	App\\Models\\User	9d3fc7e0-ab70-457e-b9c6-a54f44bb15ff
7	App\\Models\\User	9d3fc7e2-8ad2-4ad1-958e-0795823332ed
7	App\\Models\\User	9d3fc7e4-6dae-43e2-ade5-3679c5a6fae1
7	App\\Models\\User	9d3fc7e6-500e-4416-af22-dc69353b15f7
7	App\\Models\\User	9d3fc7e8-3273-4598-ad83-1942e91bc2f4
7	App\\Models\\User	9d3fc7ea-0ee1-4b3e-ae0a-b3f3f1d2a713
7	App\\Models\\User	9d3fc7ec-7e9d-4560-983b-8502af95b076
7	App\\Models\\User	9d3fc7ee-9c99-4218-b621-e6489d0e75d1
7	App\\Models\\User	9d3fc7f0-7f13-4ce0-9f46-11fa2c7f10ba
7	App\\Models\\User	9d3fc7f2-65c8-4f4f-9293-0a2c369f45a8
7	App\\Models\\User	9d3fc7f4-3d32-4076-ba73-cffe6a9b133c
7	App\\Models\\User	9d3fc7f6-1ead-42d6-9443-5bcb3c563786
7	App\\Models\\User	9d3fc7f8-0ee3-4764-9f18-188381a75e0c
7	App\\Models\\User	9d3fc7f9-ed00-43f2-8e7f-38bc19033be1
7	App\\Models\\User	9d3fc7fb-c81b-4da0-a889-3ec022335988
7	App\\Models\\User	9d3fc7fd-a6af-42c1-9bd6-fe5035255580
7	App\\Models\\User	9d3fc7ff-8d8e-4651-92ad-81f8f7b041a6
7	App\\Models\\User	9d3fc801-8123-4fc4-a873-7650e3abd8e8
7	App\\Models\\User	9d3fc803-79ea-40cb-a4e1-ff0d57f2f34f
7	App\\Models\\User	9d3fc806-5ead-4f09-9afc-5550802bb678
7	App\\Models\\User	9d3fc808-6fdc-4f99-83c2-3f0231f90ebf
7	App\\Models\\User	9d3fc80a-5b27-44c6-86e4-dda699dbc49d
7	App\\Models\\User	9d3fc80c-578c-4071-9d22-ddff58ad362d
7	App\\Models\\User	9d3fc80e-4224-472f-836c-ba8cee2ba284
7	App\\Models\\User	9d3fc810-2ec1-44dc-9e4b-d56081fc550a
7	App\\Models\\User	9d3fc812-17f4-4c32-97cf-978da054fa4f
7	App\\Models\\User	9d3fc813-eeb9-46ed-9b26-e621ffe33096
7	App\\Models\\User	9d3fc815-c0d1-4fb5-8fa0-5099a8540dd5
7	App\\Models\\User	9d3fc817-aaf5-408d-b60d-d6997313ee50
7	App\\Models\\User	9d3fc819-84af-4815-b18a-3404d438ec1a
7	App\\Models\\User	9d3fc81b-6765-487e-b291-9510340c55e4
7	App\\Models\\User	9d3fc81d-51e8-46c9-96e1-859c80259d6a
7	App\\Models\\User	9d3fc81f-3681-40cd-9923-06ed5c7d3af7
7	App\\Models\\User	9d3fc821-198c-48d1-89dc-71c11889f5ac
7	App\\Models\\User	9d3fc823-e09f-4434-aa21-274e4bc4b3d3
7	App\\Models\\User	9d3fc825-ef70-4ede-97f4-a51e68e848a3
7	App\\Models\\User	9d3fc827-dd0a-4986-8d58-2bd6bb7c6d57
7	App\\Models\\User	9d3fc829-d05c-4391-ac7e-0103875b393f
7	App\\Models\\User	9d3fc82b-bc2c-4035-b97f-6f6012b8de0a
7	App\\Models\\User	9d3fc82d-9a27-4b2c-829a-dda39fa35954
7	App\\Models\\User	9d3fc82f-7367-4242-a899-d48154325f67
7	App\\Models\\User	9d3fc831-6883-494c-87b6-547b9e787bef
7	App\\Models\\User	9d3fc833-49ae-44cf-af7c-39fb46eeee98
7	App\\Models\\User	9d3fc835-3438-4613-aa48-927ef4a5e7d8
7	App\\Models\\User	9d3fc837-1872-46ce-8175-305a39745b71
7	App\\Models\\User	9d3fc839-001b-4e38-a5ce-c07c396245b7
7	App\\Models\\User	9d3fc83a-f24f-4960-b617-0453215a689c
7	App\\Models\\User	9d3fc83d-353c-473f-9718-8d402b0e14f0
7	App\\Models\\User	9d3fc83f-253e-4c4a-b14f-a08783f3694a
7	App\\Models\\User	9d3fc841-0c67-48ab-83b9-59fe1ef5feff
7	App\\Models\\User	9d3fc842-ef9f-401a-a0cf-67791e298a02
7	App\\Models\\User	9d3fc844-e30b-40db-bca0-40acda16ba3f
7	App\\Models\\User	9d3fc846-d093-42e3-b6f8-2353b03cc785
7	App\\Models\\User	9d3fc848-be3d-4d02-9a80-f73220276f9b
7	App\\Models\\User	9d3fc84a-aedc-4013-aa4f-c47eac65747e
7	App\\Models\\User	9d3fc84c-93b2-4d01-b543-f20ea55b63f6
7	App\\Models\\User	9d3fc84e-85d9-47fc-971f-b892b50c077f
7	App\\Models\\User	9d3fc850-76e4-400f-9141-416032dfd627
7	App\\Models\\User	9d3fc852-5b43-4304-bbb5-31e2e7218477
7	App\\Models\\User	9d3fc854-388a-4e16-83c4-61647ce60ff5
7	App\\Models\\User	9d3fc856-1899-4dc6-98f9-7e811c2044db
7	App\\Models\\User	9d3fc857-fe56-4a90-803a-16dc278b1ae8
7	App\\Models\\User	9d3fc859-f2a4-42ef-97b3-9558fe0e20a1
7	App\\Models\\User	9d3fc85b-dc10-4efb-956b-be0c6d9647cc
7	App\\Models\\User	9d3fc85d-c97c-4112-b051-6e7858892d6e
7	App\\Models\\User	9d3fc85f-afe9-4551-b493-83897f072e60
7	App\\Models\\User	9d3fc861-95ca-47a0-b3cb-11be97ca9fd1
7	App\\Models\\User	9d3fc863-7d0f-4aef-a17b-47d37a4ca703
7	App\\Models\\User	9d3fc865-60f2-41ad-92ee-c88dfda59f8d
7	App\\Models\\User	9d3fc867-4f8c-487f-806e-3e419eff4b2b
7	App\\Models\\User	9d3fc869-3613-43f4-baea-0c1a378b1595
7	App\\Models\\User	9d3fc86b-12cc-4291-9934-721bfc43e4ad
6	App\\Models\\User	9d3fc86c-f7e3-4697-a468-328b74441e44
6	App\\Models\\User	9d3fc86e-d7fa-441f-a7f9-c877e15a4699
6	App\\Models\\User	9d3fc870-b598-4fb8-8524-4dad9c6b272a
6	App\\Models\\User	9d3fc872-9f14-4001-ad99-a9fadd756c9d
6	App\\Models\\User	9d3fc874-8573-4a74-898c-c79fec4849fc
6	App\\Models\\User	9d3fc876-69d5-46a4-9518-f6cf725ff213
6	App\\Models\\User	9d3fc878-52f0-4946-8eb0-d25e8fe3217b
6	App\\Models\\User	9d3fc87a-388f-488d-a0f7-4d6aa71facda
6	App\\Models\\User	9d3fc87c-23a6-4172-ba7c-7410b6ac0c88
6	App\\Models\\User	9d3fc87e-59eb-4555-823f-916a57fb5066
6	App\\Models\\User	9d3fc880-e111-4787-af26-3aa17d15952c
6	App\\Models\\User	9d3fc882-d75e-4190-bf55-4a9067e042ca
6	App\\Models\\User	9d3fc884-c3a2-43b3-9c3e-11f3eb3087c5
6	App\\Models\\User	9d3fc886-ba81-4c5a-ab7c-75b9f1d43199
6	App\\Models\\User	9d3fc888-a10e-4365-b410-b6d2b59d8d96
6	App\\Models\\User	9d3fc88a-81db-435c-abfb-01f66117285b
6	App\\Models\\User	9d3fc88c-69fd-4f60-a035-e6200c96f058
6	App\\Models\\User	9d3fc88e-4fe6-4e2d-981f-6beab174fd37
6	App\\Models\\User	9d3fc890-294d-4a46-a5bd-14633cb280c8
6	App\\Models\\User	9d3fc892-044c-4202-94c4-471547821b9b
6	App\\Models\\User	9d3fc893-e6c1-4387-8374-849290c04d84
6	App\\Models\\User	9d3fc895-d756-41f3-aba9-beeca860fe4f
6	App\\Models\\User	9d3fc897-c108-4f08-88c2-b68d1d7ed767
6	App\\Models\\User	9d3fc899-e623-4eac-b649-6b33f2100167
6	App\\Models\\User	9d3fc89b-c919-4e4a-80cf-91e9652c0c1b
6	App\\Models\\User	9d3fc89d-a8e8-4d47-9d67-f9f0e663af11
6	App\\Models\\User	9d3fc89f-8ecf-479d-a978-776070339354
6	App\\Models\\User	9d3fc8a1-7da7-4ed6-8177-992fb61e21f6
6	App\\Models\\User	9d3fc8a3-6893-460d-98c1-1bf0a270c876
6	App\\Models\\User	9d3fc8a5-545a-43bd-9983-f6a67243678e
6	App\\Models\\User	9d3fc8a7-4007-401d-b7ef-286e796ee231
6	App\\Models\\User	9d3fc8a9-1a70-4ec6-b66f-7e9429730571
6	App\\Models\\User	9d3fc8ab-0a4f-4b9a-aab3-fe37e2237c46
6	App\\Models\\User	9d3fc8ac-ec14-420c-83ce-ebdb84726941
6	App\\Models\\User	9d3fc8ae-d0f9-4ea7-a97c-c5b489f8e5a1
6	App\\Models\\User	9d3fc8b0-b64d-490e-8cdd-6f3ff92a8496
6	App\\Models\\User	9d3fc8b2-9fa2-4d9a-9ddf-74c040f6e21e
6	App\\Models\\User	9d3fc8b4-7fcd-46d4-b051-67d136d0fec4
6	App\\Models\\User	9d3fc8b6-5bbf-43f3-bde4-d9f01c295f84
6	App\\Models\\User	9d3fc8b8-3b64-413d-8692-1b17582c041e
6	App\\Models\\User	9d3fc8ba-1ddc-4f7b-9919-a39c5a41c87d
6	App\\Models\\User	9d3fc8bc-049c-4a85-8690-aece5f5b5a3b
6	App\\Models\\User	9d3fc8bd-f2c4-4b8a-923e-ef13844bd221
6	App\\Models\\User	9d3fc8bf-e09a-4b14-8a54-7e50c8172779
6	App\\Models\\User	9d3fc8c1-c5a9-4b16-a1ea-0b4d7403ef6c
6	App\\Models\\User	9d3fc8c3-b0ff-4b00-ab70-dd124f37fa80
6	App\\Models\\User	9d3fc8c5-9083-4c36-ac4c-1b9fcb74ed57
6	App\\Models\\User	9d3fc8c7-7a87-4d53-b8ba-ed33908a6f98
5	App\\Models\\User	9d3fc8c9-697a-4f42-8465-8b49a0333f78
5	App\\Models\\User	9d3fc8cb-4b6b-41df-acbd-46deee5952a0
5	App\\Models\\User	9d3fc8cd-2d49-4616-a0b8-d11bb5400716
5	App\\Models\\User	9d3fc8cf-0562-4646-a704-890d1f7c69b7
5	App\\Models\\User	9d3fc8d0-e649-4623-96a7-f32d26179f22
5	App\\Models\\User	9d3fc8d2-d5cd-4c22-97ff-0139bd1f562a
5	App\\Models\\User	9d3fc8d4-ba68-4f5a-a6bb-05ff4b1b9c5a
5	App\\Models\\User	9d3fc8d6-a78b-4529-a859-94d4bc50eecd
5	App\\Models\\User	9d3fc8d8-932a-4d09-b18b-82134554b518
5	App\\Models\\User	9d3fc8db-6c29-4625-864a-159c7f5e8777
\.


--
-- Data for Name: notifikasi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifikasi (id, jadwal_audit_id, fakultas_id, prodi_id, unit_id, form_id, auditor_id, auditee_id, pesan, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: pasal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pasal (id, peraturan_id, pasal, isi, created_at, updated_at) FROM stdin;
1	1	1	Dalam Peraturan Menteri ini yang dimaksud dengan:\n1. Penjaminan Mutu Pendidikan Tinggi adalah kegiatan sistemik untuk meningkatkan mutu pendidikan tinggi secara berencana dan berkelanjutan.\n2. Standar Nasional Pendidikan Tinggi yang selanjutnya disebut SN Dikti adalah satuan standar yang meliputi standar nasional pendidikan ditambah dengan standar penelitian dan standar pengabdian kepada masyarakat.\n3. Tridharma Perguruan Tinggi yang selanjutnya disebut Tridharma adalah kewajiban perguruan tinggi untuk menyelenggarakan pendidikan, penelitian, dan pengabdian kepada masyarakat.	2024-10-15 06:09:42	2024-10-15 06:09:42
2	1	2	Penjaminan Mutu Pendidikan Tinggi dilakukan melalui penetapan, pelaksanaan, evaluasi, pengendalian, dan peningkatan standar pendidikan tinggi.	2024-10-15 06:09:42	2024-10-15 06:09:42
3	1	3	Setiap perguruan tinggi wajib melaksanakan penjaminan mutu pendidikan tinggi.	2024-10-15 06:09:42	2024-10-15 06:09:42
4	1	4	Penjaminan Mutu Pendidikan Tinggi terdiri atas Penjaminan Mutu Internal dan Penjaminan Mutu Eksternal.	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: peraturan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.peraturan (id, nama, tahun, status, created_at, updated_at) FROM stdin;
1	Permenristekdikti No 53 Tahun 2023 tentang Sistem Penjaminan Mutu Pendidikan Tinggi	2023	t	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, "group", guard_name, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: prodi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.prodi (id, jenjang_id, fakultas_id, nama, created_at, updated_at) FROM stdin;
9d3fc7a8-28cd-4565-aabb-1a3fae335c44	2	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Agribisnis	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-2b05-4b26-ab09-4a7740b99042	2	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Teknologi Pangan	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-2d1c-48d7-a98d-a8370a41e314	2	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Teknik Pertanian	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-2efe-48ef-adc5-744780322464	2	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Agroteknologi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-30da-44ba-ae1c-0fa3b7350325	2	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Proteksi Tanaman	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-328c-41cf-98cf-02468eeaa008	2	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Biologi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-33f4-4c61-b754-0ed6aece001a	2	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Mikrobiologi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-355d-40fc-aefa-b461dc31ba98	2	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Biologi Terapan	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-38a4-4600-bb49-0aa308ace0d8	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ekonomi Pembangunan	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-3ae3-4483-86ab-d7f750eb0c0f	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Manajemen	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-3cf7-4aca-bf7e-9b8877a9736a	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Akuntansi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-3ef8-4e0e-9c18-4b7c0f687d72	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Pendidikan Ekonomi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-413e-4f58-8147-6f509e469ec3	2	9d3fc7a8-1526-42fe-866a-5f65231bd245	Peternakan	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-44bc-4bf7-bcef-a0a4d67139a2	2	9d3fc7a8-1788-476f-8fa7-73933937e593	Hukum	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-4763-4c93-9175-8478a550c115	2	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Administrasi Publik	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-490f-48ac-b3ee-a9ea4c4c401f	2	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Ilmu Komunikasi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-4b67-4585-9a1d-bde204d5f87c	2	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Ilmu Politik	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-4d4c-4c55-8467-ca5c1ffabd64	2	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Hubungan Internasional	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-4f33-4521-9a1c-cbc04bcdd43f	2	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Pendidikan Dokter	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-50c1-4ee5-a5e1-3089cb847c93	2	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Kedokteran Gigi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-5263-4581-805b-1f2c4a659b6b	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik Elektro	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-53d9-455f-b0b8-7cd3f200b98a	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik Sipil	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-5574-402f-a401-c7591b0c5b02	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik Geologi	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-56fc-47a5-9d5c-1f5351161414	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Informatika	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-58f8-4b59-833b-8d5e6c4f34ec	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik Industri	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-5abf-42bf-9ae5-0dbb5fc152b9	2	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik Mesin	2024-10-15 06:09:41	2024-10-15 06:09:41
9d3fc7a8-5c6e-4fe9-af18-9a43605787a4	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Kesehatan Masyarakat	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-5df8-4ac7-b46c-a52ba9d4625c	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Keperawatan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6058-45a8-a317-ff25014630fb	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Farmasi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-63ab-468e-b289-5dd2af10aba2	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Ilmu Gizi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6603-4e8f-b5a1-4b957c4403e5	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Pendidikan Jasmani	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6869-4745-9022-a9937f5c3fc7	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Sastra Inggris	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6a76-4930-8414-4b2f1a4514ba	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Sastra Indonesia	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6c7c-4716-b019-a53e5133fad8	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Pendidikan Bahasa Indonesia	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6e28-48ca-b772-6760433eae85	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Pendidikan Bahasa Inggris	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-6ff2-43ba-9146-1dadd8e445bb	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Sastra Jepang	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-719d-4736-9240-b8f7c9a58713	2	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Pendidikan Bahasa Jepang	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-734a-4e29-88a1-95442b32871b	2	9d3fc7a8-24af-4504-aac7-89a578a8ea65	Kimia	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-74e4-4224-bd5f-61335120a5f8	2	9d3fc7a8-24af-4504-aac7-89a578a8ea65	Matematika	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-7697-4daf-958a-e053c880f951	2	9d3fc7a8-24af-4504-aac7-89a578a8ea65	Fisika	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-7819-4e69-aad1-2241ef092850	2	9d3fc7a8-24af-4504-aac7-89a578a8ea65	Statistika	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-79c1-44ba-92c0-db053e567c8c	2	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	Manajemen Sumberdaya Perairan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-7b32-4abf-b6b8-71fa27359e31	2	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	Akuakultur	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-7cda-41be-9a00-f2aba9aec153	2	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	Ilmu Kelautan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-7e7d-479b-a26b-72f5613ce0de	2	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Biologi Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-8009-4889-b876-5eb4c8541c5f	2	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Keperawatan Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-818c-42e7-919e-3dbb5a1a5e35	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Manajemen Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-839c-4d72-97c2-2d2fdc4731b8	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ekonomi Pembangunan Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-855c-4a03-9e2c-b4f0b21a4db7	2	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Akuntansi Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-8922-4919-8d37-940b9300f51a	2	9d3fc7a8-1788-476f-8fa7-73933937e593	Hukum Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-8b3d-43e7-b333-ae0684965284	2	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Hubungan Internasional Kelas Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-8d3e-4ada-9363-a82372be1a8d	1	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Agribisnis	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-8f54-493d-bb62-e4a61a404717	1	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Perencanaan Sumber Daya Lahan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-91c0-4f41-b4ba-de2a9940c52f	1	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Budidaya Ikan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-942c-4ea3-93f5-92eeaaacaae2	1	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Administrasi Bisnis	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-9608-4049-9468-f7a24c074767	1	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Administrasi Perkantoran	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-97fd-456c-b005-97dbdb9e6c22	1	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Akuntansi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-9a30-46d2-896d-14dbb8790795	1	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Bisnis Internasional	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-9c6d-45de-8530-9c6b551c6558	1	9d3fc7a8-1526-42fe-866a-5f65231bd245	Budidaya Ternak	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-9e43-4611-b3b1-d4a5d5b22454	1	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Bahasa Inggris	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-9ffa-422b-a490-7189bb13ded1	1	9d3fc7a8-22db-4d88-aa8c-1fa0438cd152	Bahasa Mandarin	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-a259-442e-ae35-f3a15504d589	5	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Anestesiologi dan Terapi Intensif	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-a44e-4999-830d-2864740397aa	5	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Pendidikan Profesi Akuntan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-a658-480e-a522-48e3fb8e1db4	5	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Pendidikan Profesi Dokter	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-a83f-4b34-a95e-9765f9b3cd7b	5	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Pendidikan Profesi Dokter Gigi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ab62-4854-95ff-7dc0bef8db43	5	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Pendidikan Profesi Ners	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-add6-40dd-9e1c-2bdfb0b53386	5	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Pendidikan Profesi Apoteker	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-b023-455b-9452-6f21ecd14d6b	4	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Ilmu Pertanian	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-b333-4cff-b79a-3d3e1fda6ad8	4	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Biologi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-b56d-460e-b897-556e136570df	4	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Akuntansi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-b823-42e8-90af-54097bfa4054	4	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ilmu Manajemen	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ba15-4b92-9f79-b3c63459eabe	4	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ekonomi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-bbee-4bc9-9e50-2737ac29aa2a	4	9d3fc7a8-1526-42fe-866a-5f65231bd245	Peternakan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-be23-4d8e-9655-27f733a429ac	4	9d3fc7a8-1788-476f-8fa7-73933937e593	Hukum	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-bfec-4657-a943-b096ddd9d208	4	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Administrasi Publik	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-c188-4569-a22c-3817f2dc8f1e	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Magister Ilmu Lingkungan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-c34d-4c65-989b-2345a86ae9d2	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Magister Penyuluh Pertanian	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-c4e8-4b3c-add4-177af6431e1a	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Magister Bioteknologi Pertanian	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-c6a7-48a7-8fca-d47f107d8ee2	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Magister Agribisnis	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-c843-44de-a2ca-1af639f4026b	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Agronomi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ca1a-4883-a5ba-a633b3e8fb02	3	9d3fc7a8-0ca8-4e58-b1dd-3986ff9ff12a	Ilmu Pangan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-cc78-4564-834d-2415ce0a3274	3	9d3fc7a8-0f66-4dd0-a2cb-930761a8a577	Biologi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ce64-4f94-872b-32680d6fff97	3	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ekonomi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-d0d3-4c52-8024-79c10515dcc9	3	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Ilmu Manajemen	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-d2d5-466a-a48c-715c9d1b6f69	3	9d3fc7a8-12aa-4540-b12e-f0864585fbb2	Magister Akuntansi (MAKSI)	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-d4c4-4b3b-8a25-bf1bc988a5b7	3	9d3fc7a8-1526-42fe-866a-5f65231bd245	Magister Peternakan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-d68f-4108-b079-afbfa200a018	3	9d3fc7a8-1788-476f-8fa7-73933937e593	Magister Hukum	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-dad3-43b7-9f5f-93cb4bd1054c	3	9d3fc7a8-1788-476f-8fa7-73933937e593	Magister Kenotariatan (MKn)	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-dea7-4ae5-a096-fc54e3a3d621	3	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Magister Administrasi Publik	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-e1b9-4a55-915d-62bab20420e7	3	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Magister Sosiologi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-e412-40fe-9fe1-6e77ec884cb5	3	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Magister Ilmu Komunikasi	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-e61e-41df-887f-81dcf5635fae	3	9d3fc7a8-19ad-4ef6-8c19-fd62ea579e48	Ilmu Politik	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-e822-4989-9875-db53a04449ee	3	9d3fc7a8-1e75-450b-8680-6e86fb53dd78	Teknik Sipil	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ea2b-4d64-98da-7a72af8e412c	3	9d3fc7a8-1c49-48da-abd1-622a2db55ea3	Ilmu Biomedis	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ec65-4cc3-b951-c0b290c17c08	3	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Kesehatan Masyarakat	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-ee4b-4ae3-9ede-cdcc9d414e76	3	9d3fc7a8-20aa-4e07-a833-5433fe8b3468	Magister Keperawatan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-f05d-44a6-a6da-554677767f40	3	9d3fc7a8-24af-4504-aac7-89a578a8ea65	Fisika	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-f21e-464f-85bd-76b2bddca9c7	3	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	Ilmu Kelautan	2024-10-15 06:09:42	2024-10-15 06:09:42
9d3fc7a8-f3f0-467e-8f6a-da9af6e30b48	3	9d3fc7a8-2680-4228-b09e-ab55e4c02fc1	Sumberdaya Akuatik	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: ptk; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ptk (id, jadwal_audit_id, fakultas_id, prodi_id, unit_id, tgl, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ptk_auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ptk_auditee (id, ptk_id, auditee_id, approve, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ptk_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ptk_auditor (id, ptk_id, auditor_id, approve, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ptk_form; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ptk_form (id, ptk_id, form_id, auditor_id, auditee_id, analisis, akibat, target, kategori_temuan, pic, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ptk_form_deskripsi; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ptk_form_deskripsi (id, ptk_id, form_id, auditor_id, deskripsi, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: ptk_form_rencana; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ptk_form_rencana (id, ptk_id, form_id, auditee_id, rencana, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
\.


--
-- Data for Name: role_menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_menu (role_id, menu_id, created_at, updated_at) FROM stdin;
1	1	2024-10-15 06:09:41	2024-10-15 06:09:41
1	1	2024-10-15 06:09:41	2024-10-15 06:09:41
2	1	2024-10-15 06:09:41	2024-10-15 06:09:41
2	1	2024-10-15 06:09:41	2024-10-15 06:09:41
3	1	2024-10-15 06:09:41	2024-10-15 06:09:41
3	1	2024-10-15 06:09:41	2024-10-15 06:09:41
4	1	2024-10-15 06:09:41	2024-10-15 06:09:41
4	1	2024-10-15 06:09:41	2024-10-15 06:09:41
5	1	2024-10-15 06:09:41	2024-10-15 06:09:41
5	1	2024-10-15 06:09:41	2024-10-15 06:09:41
6	1	2024-10-15 06:09:41	2024-10-15 06:09:41
6	1	2024-10-15 06:09:41	2024-10-15 06:09:41
7	1	2024-10-15 06:09:41	2024-10-15 06:09:41
7	1	2024-10-15 06:09:41	2024-10-15 06:09:41
2	8	2024-10-15 06:09:41	2024-10-15 06:09:41
2	8	2024-10-15 06:09:41	2024-10-15 06:09:41
3	8	2024-10-15 06:09:41	2024-10-15 06:09:41
3	8	2024-10-15 06:09:41	2024-10-15 06:09:41
5	8	2024-10-15 06:09:41	2024-10-15 06:09:41
5	8	2024-10-15 06:09:41	2024-10-15 06:09:41
6	8	2024-10-15 06:09:41	2024-10-15 06:09:41
6	8	2024-10-15 06:09:41	2024-10-15 06:09:41
7	8	2024-10-15 06:09:41	2024-10-15 06:09:41
7	8	2024-10-15 06:09:41	2024-10-15 06:09:41
2	8	2024-10-15 06:09:41	2024-10-15 06:09:41
3	8	2024-10-15 06:09:41	2024-10-15 06:09:41
5	8	2024-10-15 06:09:41	2024-10-15 06:09:41
6	8	2024-10-15 06:09:41	2024-10-15 06:09:41
7	8	2024-10-15 06:09:41	2024-10-15 06:09:41
2	8	2024-10-15 06:09:41	2024-10-15 06:09:41
3	8	2024-10-15 06:09:41	2024-10-15 06:09:41
5	8	2024-10-15 06:09:41	2024-10-15 06:09:41
6	8	2024-10-15 06:09:41	2024-10-15 06:09:41
7	8	2024-10-15 06:09:41	2024-10-15 06:09:41
4	9	2024-10-15 06:09:41	2024-10-15 06:09:41
4	9	2024-10-15 06:09:41	2024-10-15 06:09:41
4	10	2024-10-15 06:09:41	2024-10-15 06:09:41
4	10	2024-10-15 06:09:41	2024-10-15 06:09:41
4	9	2024-10-15 06:09:41	2024-10-15 06:09:41
4	9	2024-10-15 06:09:41	2024-10-15 06:09:41
1	1	2024-10-15 06:09:41	2024-10-15 06:09:41
1	1	2024-10-15 06:09:41	2024-10-15 06:09:41
1	4	2024-10-15 06:09:41	2024-10-15 06:09:41
1	4	2024-10-15 06:09:41	2024-10-15 06:09:41
1	7	2024-10-15 06:09:41	2024-10-15 06:09:41
1	7	2024-10-15 06:09:41	2024-10-15 06:09:41
1	3	2024-10-15 06:09:41	2024-10-15 06:09:41
1	3	2024-10-15 06:09:41	2024-10-15 06:09:41
1	6	2024-10-15 06:09:41	2024-10-15 06:09:41
1	6	2024-10-15 06:09:41	2024-10-15 06:09:41
1	2	2024-10-15 06:09:41	2024-10-15 06:09:41
1	2	2024-10-15 06:09:41	2024-10-15 06:09:41
1	5	2024-10-15 06:09:41	2024-10-15 06:09:41
1	5	2024-10-15 06:09:41	2024-10-15 06:09:41
\.


--
-- Data for Name: role_submenu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_submenu (role_id, submenu_id, created_at, updated_at) FROM stdin;
2	20	2024-10-15 06:09:41	2024-10-15 06:09:41
3	20	2024-10-15 06:09:41	2024-10-15 06:09:41
5	20	2024-10-15 06:09:41	2024-10-15 06:09:41
6	20	2024-10-15 06:09:41	2024-10-15 06:09:41
7	20	2024-10-15 06:09:41	2024-10-15 06:09:41
2	21	2024-10-15 06:09:41	2024-10-15 06:09:41
3	21	2024-10-15 06:09:41	2024-10-15 06:09:41
5	21	2024-10-15 06:09:41	2024-10-15 06:09:41
6	21	2024-10-15 06:09:41	2024-10-15 06:09:41
7	21	2024-10-15 06:09:41	2024-10-15 06:09:41
4	22	2024-10-15 06:09:41	2024-10-15 06:09:41
4	23	2024-10-15 06:09:41	2024-10-15 06:09:41
1	9	2024-10-15 06:09:41	2024-10-15 06:09:41
1	9	2024-10-15 06:09:41	2024-10-15 06:09:41
1	10	2024-10-15 06:09:41	2024-10-15 06:09:41
1	10	2024-10-15 06:09:41	2024-10-15 06:09:41
1	11	2024-10-15 06:09:41	2024-10-15 06:09:41
1	11	2024-10-15 06:09:41	2024-10-15 06:09:41
1	12	2024-10-15 06:09:41	2024-10-15 06:09:41
1	12	2024-10-15 06:09:41	2024-10-15 06:09:41
1	13	2024-10-15 06:09:41	2024-10-15 06:09:41
1	13	2024-10-15 06:09:41	2024-10-15 06:09:41
1	4	2024-10-15 06:09:41	2024-10-15 06:09:41
1	4	2024-10-15 06:09:41	2024-10-15 06:09:41
1	5	2024-10-15 06:09:41	2024-10-15 06:09:41
1	5	2024-10-15 06:09:41	2024-10-15 06:09:41
1	6	2024-10-15 06:09:41	2024-10-15 06:09:41
1	6	2024-10-15 06:09:41	2024-10-15 06:09:41
1	7	2024-10-15 06:09:41	2024-10-15 06:09:41
1	7	2024-10-15 06:09:41	2024-10-15 06:09:41
1	8	2024-10-15 06:09:41	2024-10-15 06:09:41
1	8	2024-10-15 06:09:41	2024-10-15 06:09:41
1	16	2024-10-15 06:09:41	2024-10-15 06:09:41
1	16	2024-10-15 06:09:41	2024-10-15 06:09:41
1	17	2024-10-15 06:09:41	2024-10-15 06:09:41
1	17	2024-10-15 06:09:41	2024-10-15 06:09:41
1	18	2024-10-15 06:09:41	2024-10-15 06:09:41
1	18	2024-10-15 06:09:41	2024-10-15 06:09:41
1	19	2024-10-15 06:09:41	2024-10-15 06:09:41
1	19	2024-10-15 06:09:41	2024-10-15 06:09:41
1	1	2024-10-15 06:09:41	2024-10-15 06:09:41
1	1	2024-10-15 06:09:41	2024-10-15 06:09:41
1	2	2024-10-15 06:09:41	2024-10-15 06:09:41
1	2	2024-10-15 06:09:41	2024-10-15 06:09:41
1	3	2024-10-15 06:09:41	2024-10-15 06:09:41
1	3	2024-10-15 06:09:41	2024-10-15 06:09:41
1	14	2024-10-15 06:09:41	2024-10-15 06:09:41
1	14	2024-10-15 06:09:41	2024-10-15 06:09:41
1	15	2024-10-15 06:09:41	2024-10-15 06:09:41
1	15	2024-10-15 06:09:41	2024-10-15 06:09:41
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
1	pusjamu	web	2024-10-15 06:09:14	2024-10-15 06:09:14
2	gkm	web	2024-10-15 06:09:14	2024-10-15 06:09:14
3	gpm	web	2024-10-15 06:09:14	2024-10-15 06:09:14
4	auditor	web	2024-10-15 06:09:14	2024-10-15 06:09:14
5	pj_universitas	web	2024-10-15 06:09:14	2024-10-15 06:09:14
6	pj_fakultas	web	2024-10-15 06:09:14	2024-10-15 06:09:14
7	pj_prodi	web	2024-10-15 06:09:14	2024-10-15 06:09:14
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
P9xbiksTuiRAdf0meKzhZTUFWDhkmQR9hZnM8eXY	\N	141.148.153.213	Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1	YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnFDazBiM3JlbEtqSktIRjU3b3ZsS2FJbUZPa2o1MU82bjhjOHJNciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vamVsaXRhLmNvZGV3aXRobWUubXkuaWQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19	1728972643
wUVxOi2Kte5iVvcKiOYVYnRDf6H4f0Vq74HXD8AX	9d3fc780-0480-4700-b4a6-87d75a4a4695	36.73.33.159	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36 Edg/129.0.0.0	YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNUVzanI5YnljaEprcGZUY3FQMnpJTm1CSUpCdml6dHdMcTVkck5OayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6NDg6e2k6MDtzOjE4OiJhbGVydC5jb25maWcudGl0bGUiO2k6MTtzOjE3OiJhbGVydC5jb25maWcudGV4dCI7aToyO3M6MTg6ImFsZXJ0LmNvbmZpZy50aW1lciI7aTozO3M6MjM6ImFsZXJ0LmNvbmZpZy5iYWNrZ3JvdW5kIjtpOjQ7czoxODoiYWxlcnQuY29uZmlnLndpZHRoIjtpOjU7czoyMzoiYWxlcnQuY29uZmlnLmhlaWdodEF1dG8iO2k6NjtzOjIwOiJhbGVydC5jb25maWcucGFkZGluZyI7aTo3O3M6MzA6ImFsZXJ0LmNvbmZpZy5zaG93Q29uZmlybUJ1dHRvbiI7aTo4O3M6Mjg6ImFsZXJ0LmNvbmZpZy5zaG93Q2xvc2VCdXR0b24iO2k6OTtzOjMwOiJhbGVydC5jb25maWcuY29uZmlybUJ1dHRvblRleHQiO2k6MTA7czoyOToiYWxlcnQuY29uZmlnLmNhbmNlbEJ1dHRvblRleHQiO2k6MTE7czoyOToiYWxlcnQuY29uZmlnLnRpbWVyUHJvZ3Jlc3NCYXIiO2k6MTI7czoyNDoiYWxlcnQuY29uZmlnLmN1c3RvbUNsYXNzIjtpOjEzO3M6MTc6ImFsZXJ0LmNvbmZpZy5pY29uIjtpOjE0O3M6MTI6ImFsZXJ0LmNvbmZpZyI7aToxNTtzOjE4OiJhbGVydC5jb25maWcudGl0bGUiO2k6MTY7czoxNzoiYWxlcnQuY29uZmlnLnRleHQiO2k6MTc7czoxODoiYWxlcnQuY29uZmlnLnRpbWVyIjtpOjE4O3M6MjM6ImFsZXJ0LmNvbmZpZy5iYWNrZ3JvdW5kIjtpOjE5O3M6MTg6ImFsZXJ0LmNvbmZpZy53aWR0aCI7aToyMDtzOjIzOiJhbGVydC5jb25maWcuaGVpZ2h0QXV0byI7aToyMTtzOjIwOiJhbGVydC5jb25maWcucGFkZGluZyI7aToyMjtzOjMwOiJhbGVydC5jb25maWcuc2hvd0NvbmZpcm1CdXR0b24iO2k6MjM7czoyODoiYWxlcnQuY29uZmlnLnNob3dDbG9zZUJ1dHRvbiI7aToyNDtzOjMwOiJhbGVydC5jb25maWcuY29uZmlybUJ1dHRvblRleHQiO2k6MjU7czoyOToiYWxlcnQuY29uZmlnLmNhbmNlbEJ1dHRvblRleHQiO2k6MjY7czoyOToiYWxlcnQuY29uZmlnLnRpbWVyUHJvZ3Jlc3NCYXIiO2k6Mjc7czoyNDoiYWxlcnQuY29uZmlnLmN1c3RvbUNsYXNzIjtpOjI4O3M6MTc6ImFsZXJ0LmNvbmZpZy5pY29uIjtpOjI5O3M6MTI6ImFsZXJ0LmNvbmZpZyI7aTozMDtzOjE4OiJhbGVydC5kZWxldGUudGl0bGUiO2k6MzE7czoxNzoiYWxlcnQuZGVsZXRlLnRleHQiO2k6MzI7czoyMzoiYWxlcnQuZGVsZXRlLmJhY2tncm91bmQiO2k6MzM7czoxODoiYWxlcnQuZGVsZXRlLndpZHRoIjtpOjM0O3M6MjM6ImFsZXJ0LmRlbGV0ZS5oZWlnaHRBdXRvIjtpOjM1O3M6MjA6ImFsZXJ0LmRlbGV0ZS5wYWRkaW5nIjtpOjM2O3M6Mjg6ImFsZXJ0LmRlbGV0ZS5zaG93Q2xvc2VCdXR0b24iO2k6Mzc7czozMDoiYWxlcnQuZGVsZXRlLmNvbmZpcm1CdXR0b25UZXh0IjtpOjM4O3M6Mjk6ImFsZXJ0LmRlbGV0ZS5jYW5jZWxCdXR0b25UZXh0IjtpOjM5O3M6Mjk6ImFsZXJ0LmRlbGV0ZS50aW1lclByb2dyZXNzQmFyIjtpOjQwO3M6MjQ6ImFsZXJ0LmRlbGV0ZS5jdXN0b21DbGFzcyI7aTo0MTtzOjE3OiJhbGVydC5kZWxldGUuaWNvbiI7aTo0MjtzOjI5OiJhbGVydC5kZWxldGUuc2hvd0NhbmNlbEJ1dHRvbiI7aTo0MztzOjMxOiJhbGVydC5kZWxldGUuY29uZmlybUJ1dHRvbkNvbG9yIjtpOjQ0O3M6MzI6ImFsZXJ0LmRlbGV0ZS5zaG93TG9hZGVyT25Db25maXJtIjtpOjQ1O3M6Mjc6ImFsZXJ0LmRlbGV0ZS5hbGxvd0VzY2FwZUtleSI7aTo0NjtzOjMwOiJhbGVydC5kZWxldGUuYWxsb3dPdXRzaWRlQ2xpY2siO2k6NDc7czoxMjoiYWxlcnQuZGVsZXRlIjt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo1MDoiaHR0cHM6Ly9qZWxpdGEuY29kZXdpdGhtZS5teS5pZC9hdWRpdC9qYWR3YWwtYXVkaXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7czozNjoiOWQzZmM3ODAtMDQ4MC00NzAwLWI0YTYtODdkNzVhNGE0Njk1IjtzOjU6ImFsZXJ0IjthOjA6e319	1728972823
\.


--
-- Data for Name: setting; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.setting (id, nama_setting, jabatan_id, created_at, updated_at) FROM stdin;
1	universitas	1	2024-10-15 06:09:14	2024-10-15 06:09:14
2	fakultas	2	2024-10-15 06:09:14	2024-10-15 06:09:14
3	fakultas	3	2024-10-15 06:09:14	2024-10-15 06:09:14
4	fakultas	4	2024-10-15 06:09:14	2024-10-15 06:09:14
5	fakultas	5	2024-10-15 06:09:14	2024-10-15 06:09:14
6	prodi	6	2024-10-15 06:09:14	2024-10-15 06:09:14
7	prodi	7	2024-10-15 06:09:14	2024-10-15 06:09:14
8	universitas	8	2024-10-15 06:09:14	2024-10-15 06:09:14
9	universitas	9	2024-10-15 06:09:14	2024-10-15 06:09:14
10	universitas	10	2024-10-15 06:09:14	2024-10-15 06:09:14
11	universitas	11	2024-10-15 06:09:14	2024-10-15 06:09:14
12	universitas	12	2024-10-15 06:09:14	2024-10-15 06:09:14
13	universitas	13	2024-10-15 06:09:14	2024-10-15 06:09:14
14	universitas	14	2024-10-15 06:09:14	2024-10-15 06:09:14
15	universitas	15	2024-10-15 06:09:14	2024-10-15 06:09:14
16	universitas	16	2024-10-15 06:09:14	2024-10-15 06:09:14
17	universitas	17	2024-10-15 06:09:14	2024-10-15 06:09:14
\.


--
-- Data for Name: standar; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.standar (id, peraturan_id, nama, kode, created_at, updated_at) FROM stdin;
1	1	Standar Nasional Pendidikan	PD	2024-10-15 06:09:42	2024-10-15 06:09:42
2	1	Standar Penelitian	PN	2024-10-15 06:09:42	2024-10-15 06:09:42
3	1	Pengabdian Kepada Masyarakat	PM	2024-10-15 06:09:42	2024-10-15 06:09:42
4	1	Non-Akademik	NA	2024-10-15 06:09:42	2024-10-15 06:09:42
\.


--
-- Data for Name: status_assessment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status_assessment (id, jadwal_audit_id, prodi_id, fakultas_id, unit_id, auditor_penilai_id, auditor_dinilai_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: status_audit_auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status_audit_auditee (id, jadwal_audit_id, prodi_id, fakultas_id, unit_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: status_audit_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status_audit_auditor (id, jadwal_audit_id, prodi_id, fakultas_id, unit_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: status_laporan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status_laporan (id, laporan_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: status_ptk_auditee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status_ptk_auditee (id, ptk_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: status_ptk_auditor; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status_ptk_auditor (id, ptk_id, status, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: submenu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.submenu (id, menu_id, submenu, status, route, created_at, updated_at) FROM stdin;
1	2	Instrumen	t	instrumen	2024-10-15 06:09:41	2024-10-15 06:09:41
2	2	Jadwal Audit	t	jadwal-audit	2024-10-15 06:09:41	2024-10-15 06:09:41
3	2	Hasil Audit	t	hasil-audit	2024-10-15 06:09:41	2024-10-15 06:09:41
4	3	User	t	user	2024-10-15 06:09:41	2024-10-15 06:09:41
5	3	Role	t	role	2024-10-15 06:09:41	2024-10-15 06:09:41
6	3	Jabatan	t	jabatan	2024-10-15 06:09:41	2024-10-15 06:09:41
7	3	Auditor	t	auditor	2024-10-15 06:09:41	2024-10-15 06:09:41
8	3	Auditan	t	auditan	2024-10-15 06:09:41	2024-10-15 06:09:41
9	4	Peraturan	t	peraturan	2024-10-15 06:09:41	2024-10-15 06:09:41
10	4	Standar	t	standar	2024-10-15 06:09:41	2024-10-15 06:09:41
11	4	Level	t	level	2024-10-15 06:09:41	2024-10-15 06:09:41
12	4	Kriteria	t	kriteria	2024-10-15 06:09:41	2024-10-15 06:09:41
13	4	Jenis Pertanyaan	t	jenis-pertanyaan	2024-10-15 06:09:41	2024-10-15 06:09:41
14	5	Pertanyaan	t	pertanyaan	2024-10-15 06:09:41	2024-10-15 06:09:41
15	5	Hasil	t	hasil	2024-10-15 06:09:41	2024-10-15 06:09:41
16	6	Jenjang	t	jenjang	2024-10-15 06:09:41	2024-10-15 06:09:41
17	6	Fakultas	t	fakultas	2024-10-15 06:09:41	2024-10-15 06:09:41
18	6	Prodi	t	prodi	2024-10-15 06:09:41	2024-10-15 06:09:41
19	6	Unit	t	unit	2024-10-15 06:09:41	2024-10-15 06:09:41
20	8	Dokumen	t	dokumen	2024-10-15 06:09:41	2024-10-15 06:09:41
21	8	Lapangan	t	lapangan	2024-10-15 06:09:41	2024-10-15 06:09:41
22	9	Dokumen	t	dokumen	2024-10-15 06:09:41	2024-10-15 06:09:41
23	9	Lapangan	t	lapangan	2024-10-15 06:09:41	2024-10-15 06:09:41
\.


--
-- Data for Name: unit; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.unit (id, nama, created_at, updated_at) FROM stdin;
9d3fc77d-91df-439c-846a-efd5aab93966	LPPM	2024-10-15 06:09:13	2024-10-15 06:09:13
9d3fc77d-98dd-45e7-9cfe-fe20a9a85ae7	LP3M	2024-10-15 06:09:13	2024-10-15 06:09:13
9d3fc77d-9b60-4a7a-8978-cd45c91de6fe	WR	2024-10-15 06:09:13	2024-10-15 06:09:13
9d3fc77d-9e8b-4654-bd94-ee0960a729cb	Biro Akademik	2024-10-15 06:09:13	2024-10-15 06:09:13
9d3fc77d-a0b2-48b6-afee-335aabe2f441	Biro Keuangan	2024-10-15 06:09:13	2024-10-15 06:09:13
9d3fc77d-a2d9-4a34-bae4-0e50d6f422da	LPTSI	2024-10-15 06:09:14	2024-10-15 06:09:14
9d3fc77d-a515-4039-9a5f-65d8ab715540	ULT	2024-10-15 06:09:14	2024-10-15 06:09:14
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, no_telepon, gauth_id, gauth_type, remember_token, created_at, updated_at) FROM stdin;
9d3fc780-0480-4700-b4a6-87d75a4a4695	Admin	admin@example.com	\N	$2y$12$zB3uXqgm5BovCXSn6gVH9OtQSNZpMWL5aFsOoP6BVBwpWDzlbSBji	\N	\N	\N	\N	2024-10-15 06:09:15	2024-10-15 06:09:15
9d3fc781-f67a-4cb7-aaf1-3e7aac0fc66b	Auditor 1	auditor1@example.com	\N	$2y$12$pzqBm3gZUkCvuOwUe589peLys6d4tAL/uOVnbALGWOf64eUZyJ6Zy	\N	\N	\N	\N	2024-10-15 06:09:16	2024-10-15 06:09:16
9d3fc784-1cd8-479b-ad40-d86ee84e801c	Auditor 2	auditor2@example.com	\N	$2y$12$wp.MjQjjTaQ/uybqfisZR.03zll7A6l08oU9WMrp26mhl2K1tsAEW	\N	\N	\N	\N	2024-10-15 06:09:18	2024-10-15 06:09:18
9d3fc786-05aa-45ab-8e0b-5aed86dec369	Auditor 3	auditor3@example.com	\N	$2y$12$OGL1I7vwAc75h1CpbekW4u9SCiSrDsQRgPvSkmAA8A8QQbngrHiSq	\N	\N	\N	\N	2024-10-15 06:09:19	2024-10-15 06:09:19
9d3fc787-f8d5-4027-985c-5cf051ed074e	Auditor 4	auditor4@example.com	\N	$2y$12$l7ytr38.YSmqch3qidz5TuYlBHThQapzvqpAtWDFPt0/7.axQzXOa	\N	\N	\N	\N	2024-10-15 06:09:20	2024-10-15 06:09:20
9d3fc789-ddf9-4870-9175-ea21ae49f70f	Auditor 5	auditor5@example.com	\N	$2y$12$eDxC.k9oT8/04PEg8U179uHEtYXeX3Snd/Vi6AmjKVIxDr3sUMztC	\N	\N	\N	\N	2024-10-15 06:09:22	2024-10-15 06:09:22
9d3fc78b-cb8d-4a01-82a1-157f4a38d1ff	Auditor 6	auditor6@example.com	\N	$2y$12$DjMDxy/3m0nZguKtgsRoS.cHCN.fHL5.xiTdkg5enKXiyVB3HsnV6	\N	\N	\N	\N	2024-10-15 06:09:23	2024-10-15 06:09:23
9d3fc78d-b439-4450-89e6-9807017ea9d8	Auditor 7	auditor7@example.com	\N	$2y$12$OIyYmzKhIBD44m7oTlbQ7uIYugCqkQCcJmndesxuXkV2nMjsirAX.	\N	\N	\N	\N	2024-10-15 06:09:24	2024-10-15 06:09:24
9d3fc78f-9e1f-4d50-a9c1-588ed4734f26	Auditor 8	auditor8@example.com	\N	$2y$12$agPAaw0IU3i6pxnd5Tpkg.CJJNKjM21OyQnpMvZ.H.fLrihjRif3u	\N	\N	\N	\N	2024-10-15 06:09:25	2024-10-15 06:09:25
9d3fc791-8dc6-4601-9470-d447ec20646c	Auditor 9	auditor9@example.com	\N	$2y$12$q/Q2QenKO49PTxPF.q0pn.WCUOnDaukzLAHsv8020HxGVGvWKZJHy	\N	\N	\N	\N	2024-10-15 06:09:27	2024-10-15 06:09:27
9d3fc793-761d-4e58-8f17-e6b15e06ffd6	Auditor 10	auditor10@example.com	\N	$2y$12$I24fDApRklZE26Is6kNPE.2xu3EO7zU2R8UM6D0M1.oStkAqgR/MC	\N	\N	\N	\N	2024-10-15 06:09:28	2024-10-15 06:09:28
9d3fc795-5e37-4dfd-8006-e9dffd330123	Auditor 11	auditor11@example.com	\N	$2y$12$u5.UXLq1oSyDyz5k6bGSuedTDHo.8Hd0INvJrBbLu0ND.UquFNCbC	\N	\N	\N	\N	2024-10-15 06:09:29	2024-10-15 06:09:29
9d3fc797-58da-4067-85b7-c53772b712a8	Auditor 12	auditor12@example.com	\N	$2y$12$87DYRrwSv6qel9/Fb7Y5NuonKAZOTCHI/vXcpbic3qXpuG0qFJMfa	\N	\N	\N	\N	2024-10-15 06:09:30	2024-10-15 06:09:30
9d3fc799-5e6b-40a2-872a-5d9cd4fe238f	Auditor 13	auditor13@example.com	\N	$2y$12$YCFEUdv4PHTrA37XsYb6VONCabqzBCPQrvmk2sTk4i0ViZTuKVGD.	\N	\N	\N	\N	2024-10-15 06:09:32	2024-10-15 06:09:32
9d3fc79b-41cf-4ae4-8f80-0d597d7f4e50	Auditor 14	auditor14@example.com	\N	$2y$12$JzRisgby1e.eM4o9ziY39.pePHJIAe/whuJt.ywV9mIIdiq2mq4uO	\N	\N	\N	\N	2024-10-15 06:09:33	2024-10-15 06:09:33
9d3fc79d-1d7e-4e39-b4d5-63385a9d933b	Auditor 15	auditor15@example.com	\N	$2y$12$3rdhIunq2sYI173bCNY9Sue6eRS5g5RzYk3orN5WEv0hBXCDiDFkK	\N	\N	\N	\N	2024-10-15 06:09:34	2024-10-15 06:09:34
9d3fc79f-28d3-48f6-8e81-c89f12e8c862	Auditor 16	auditor16@example.com	\N	$2y$12$LF6bwJXU9y4kPHSa/o2GWeqfnkmaYoJs903/3dhnxNpTJZQeUyIB2	\N	\N	\N	\N	2024-10-15 06:09:35	2024-10-15 06:09:35
9d3fc7a1-10d6-4556-a5ea-d0f3577c4a48	Auditor 17	auditor17@example.com	\N	$2y$12$YAsx9hL2vGRNqPLpIj8U/.1xwSoO4c9frzz8/li0rrMce3LP0hhMO	\N	\N	\N	\N	2024-10-15 06:09:37	2024-10-15 06:09:37
9d3fc7a2-fdeb-4086-a4c6-fbfa19e1a341	Auditor 18	auditor18@example.com	\N	$2y$12$iROd/1mbJXB5BlgKM6ZLJeQfCrZVcKa9fVckzjnLvgbnjFChmmGrO	\N	\N	\N	\N	2024-10-15 06:09:38	2024-10-15 06:09:38
9d3fc7a4-ec81-4560-8404-3a613a430135	Auditor 19	auditor19@example.com	\N	$2y$12$K5.1nyM3n7HtnhJgTIbYd.6VpMOUD.4GzeEWwPieaLqmE1fz1Ar3i	\N	\N	\N	\N	2024-10-15 06:09:39	2024-10-15 06:09:39
9d3fc7a6-d400-48d6-a92b-990221751ece	Auditor 20	auditor20@example.com	\N	$2y$12$Y.q2bbszU7aB7.FCh8htieAOOsMNviYsrxyjRXQ0CFLn0YeOHjIFG	\N	\N	\N	\N	2024-10-15 06:09:40	2024-10-15 06:09:40
9d3fc7ab-4508-4e14-881b-d98970ff7681	Kaprodi Agribisnis	pjprodiagribisniss1@example.com	\N	$2y$12$6jL4M1mrPy6y2YR3U/FiBui/Bjasb9L8uJRz0yuCy32goEUMHWKbS	\N	\N	\N	\N	2024-10-15 06:09:43	2024-10-15 06:09:43
9d3fc7ad-3960-47c0-93c7-9d629aae763c	Kaprodi Teknologi Pangan	pjproditeknologipangans1@example.com	\N	$2y$12$In1pllMHAvWGneTCv4sD1eBq.rlvuxMwKsbwELuAPDTD90Xr20/Hq	\N	\N	\N	\N	2024-10-15 06:09:45	2024-10-15 06:09:45
9d3fc7af-2191-4941-804c-bef64c7492a2	Kaprodi Teknik Pertanian	pjproditeknikpertanians1@example.com	\N	$2y$12$XzmzZPDhbZeoT83vQHVyN.diOodn42OSF6cH/dhCGWqMiAkAel.Zq	\N	\N	\N	\N	2024-10-15 06:09:46	2024-10-15 06:09:46
9d3fc7b1-13bc-4f0b-a4aa-25c9cca8d210	Kaprodi Agroteknologi	pjprodiagroteknologis1@example.com	\N	$2y$12$lKrlGYK3sYntpWDVlNevduk7bpGdUNyRL1okOmuQ1MlnQqJIgjbwC	\N	\N	\N	\N	2024-10-15 06:09:47	2024-10-15 06:09:47
9d3fc7b2-f07d-4549-9f90-a60523483452	Kaprodi Proteksi Tanaman	pjprodiproteksitanamans1@example.com	\N	$2y$12$O1bAzhyUt.D5fypDHFDOpu33pK5I17Mtl6gyuo9TuW7ROGFz4giBS	\N	\N	\N	\N	2024-10-15 06:09:48	2024-10-15 06:09:48
9d3fc7b4-d08a-4049-9f58-3a2e0cb9cf77	Kaprodi Biologi	pjprodibiologis1@example.com	\N	$2y$12$4OafGQh2sbnW8MZTS72QKOluXP22G/XlHsieoakbW6/6y1pAwFw/e	\N	\N	\N	\N	2024-10-15 06:09:50	2024-10-15 06:09:50
9d3fc7b6-b319-41f4-abf1-47d937cd0438	Kaprodi Mikrobiologi	pjprodimikrobiologis1@example.com	\N	$2y$12$/Gs3P32vJ4kX8d/d4ABQ9O9UwVcTExjmFFwKFxPBciLsF161uubqm	\N	\N	\N	\N	2024-10-15 06:09:51	2024-10-15 06:09:51
9d3fc7b8-8c49-4bc1-a2b4-dfa5b2ff362f	Kaprodi Biologi Terapan	pjprodibiologiterapans1@example.com	\N	$2y$12$i24.Qjl6L4tuhdmxK7NQQuMDmbGfEQs6B6Sigf4JX2PYaebo//FE2	\N	\N	\N	\N	2024-10-15 06:09:52	2024-10-15 06:09:52
9d3fc7ba-75e8-4887-9d5f-ee5252aba7c0	Kaprodi Ekonomi Pembangunan	pjprodiekonomipembangunans1@example.com	\N	$2y$12$UabTK5ai0MtybN3J.W33YOiAFkZlKYpL5FTKyRQ0f2wXehYXKOHme	\N	\N	\N	\N	2024-10-15 06:09:53	2024-10-15 06:09:53
9d3fc7bc-693a-47ad-887d-f9d35d6bb086	Kaprodi Manajemen	pjprodimanajemens1@example.com	\N	$2y$12$PFEqs9boKrMjrqQaQmFkPOH8rneilcWnshUAywKj4EM5KPWrX8lii	\N	\N	\N	\N	2024-10-15 06:09:55	2024-10-15 06:09:55
9d3fc7be-53cf-41b2-bb07-43761fe5da44	Kaprodi Akuntansi	pjprodiakuntansis1@example.com	\N	$2y$12$oyF5WmlpKf5lW7kZV3r7FuBbm90eGiQYekey6H4fmbf7Jlkb03ffy	\N	\N	\N	\N	2024-10-15 06:09:56	2024-10-15 06:09:56
9d3fc7c0-42cf-47ee-8261-eb8a6b43612a	Kaprodi Pendidikan Ekonomi	pjprodipendidikanekonomis1@example.com	\N	$2y$12$SN/1rBGmP7iqRUGyv6Est.T81d8X59HdZZDbIeLhOGM7hwlTR1ClK	\N	\N	\N	\N	2024-10-15 06:09:57	2024-10-15 06:09:57
9d3fc7c2-39c8-4b2d-8769-5c943686e6cd	Kaprodi Peternakan	pjprodipeternakans1@example.com	\N	$2y$12$YJl24kElNK/6.LqWKPhwueOunXiv12zu30qs0c67AYaWG21DRPl6e	\N	\N	\N	\N	2024-10-15 06:09:58	2024-10-15 06:09:58
9d3fc7c4-27c6-4c65-8888-2dac08a3e200	Kaprodi Hukum	pjprodihukums1@example.com	\N	$2y$12$bRwA8s.D.LYyOGfe8xHExezpNLYM7p.sLNMTUQyABbkto60NDNoJC	\N	\N	\N	\N	2024-10-15 06:10:00	2024-10-15 06:10:00
9d3fc7c6-1ed1-491b-b29b-1ff8a6dd3063	Kaprodi Administrasi Publik	pjprodiadministrasipubliks1@example.com	\N	$2y$12$8r5cSd5KLmBK./DG9ZwnpuGbPNOt7B6fZLhmJ1If0u6kuePLYPDYW	\N	\N	\N	\N	2024-10-15 06:10:01	2024-10-15 06:10:01
9d3fc7c9-4b61-4765-9f1b-9d8a49639c3f	Kaprodi Ilmu Komunikasi	pjprodiilmukomunikasis1@example.com	\N	$2y$12$.d2Aj7UQcPoXB5F9A1K5N.nnsFHBHaWpO48iaV2rdLg1o6Mjm3Avu	\N	\N	\N	\N	2024-10-15 06:10:03	2024-10-15 06:10:03
9d3fc7cb-437e-4190-a761-03fc33519b82	Kaprodi Ilmu Politik	pjprodiilmupolitiks1@example.com	\N	$2y$12$Nh21xxddzXT6ARadOVamk.8v1CYsebUAgumI2XuYg0ZzX62VXlqTO	\N	\N	\N	\N	2024-10-15 06:10:04	2024-10-15 06:10:04
9d3fc7cd-49b2-4305-8972-3b0e50c4d15d	Kaprodi Hubungan Internasional	pjprodihubunganinternasionals1@example.com	\N	$2y$12$sxBNT0AVmGCsQaOCc2pituiOKTeNYBlwX.gNkYmZl/eKKpdAkQb36	\N	\N	\N	\N	2024-10-15 06:10:06	2024-10-15 06:10:06
9d3fc7cf-510d-41b8-930e-d189d72bdbc1	Kaprodi Pendidikan Dokter	pjprodipendidikandokters1@example.com	\N	$2y$12$MMFrsMyA59PJg7qHWd4lV.4nSpJZewrHVf7JzMvwzJjCcuoSjGuQu	\N	\N	\N	\N	2024-10-15 06:10:07	2024-10-15 06:10:07
9d3fc7d1-3fa9-44b8-be06-1945c0e8d23f	Kaprodi Kedokteran Gigi	pjprodikedokterangigis1@example.com	\N	$2y$12$XpcjMZBSA56E5M3koGG3peiuEr.TnaEAKKhKlxWjWKOCHwkjYb0ne	\N	\N	\N	\N	2024-10-15 06:10:08	2024-10-15 06:10:08
9d3fc7d3-2dc0-4b42-a57a-6b1001b51b85	Kaprodi Teknik Elektro	pjproditeknikelektros1@example.com	\N	$2y$12$mDBeb.e7uCJG9WPlUsPV8u5tYNn2Xl8c7NeR4xhFgCy9RDi52Vas6	\N	\N	\N	\N	2024-10-15 06:10:10	2024-10-15 06:10:10
9d3fc7d5-25b9-4c2b-9d53-5007b940fa27	Kaprodi Teknik Sipil	pjproditekniksipils1@example.com	\N	$2y$12$EkQQ0Wv0rdy5h6ORZovM3O/A/wkBhYXqeXFDEdf9/VCPvh9Yo5BJa	\N	\N	\N	\N	2024-10-15 06:10:11	2024-10-15 06:10:11
9d3fc7d7-0b87-41fe-95cf-8d421f50c28c	Kaprodi Teknik Geologi	pjproditeknikgeologis1@example.com	\N	$2y$12$Z79I/yiCULvnXcIRpLmUcexqeaNj4M8jb.GhHEVufOkSV6yLjenT6	\N	\N	\N	\N	2024-10-15 06:10:12	2024-10-15 06:10:12
9d3fc7d8-e7b5-4f36-b880-9214dbd9af06	Kaprodi Informatika	pjprodiinformatikas1@example.com	\N	$2y$12$ACjjN2U5/lOhMXwxFrSIc.28AzQ0/jXfDaY9SJOnIhKUGNmK.g4DS	\N	\N	\N	\N	2024-10-15 06:10:13	2024-10-15 06:10:13
9d3fc8cf-0562-4646-a704-890d1f7c69b7	WR II	wrii@example.com	\N	$2y$12$huUadaBoDge4orDqjKeWJOl0qnFyLy58dXgm/erLQhlp20QOjG5Ya	\N	\N	\N	\N	2024-10-15 06:12:55	2024-10-15 06:12:55
9d3fc7da-c06d-4973-91e9-dd93bb3410dc	Kaprodi Teknik Industri	pjproditeknikindustris1@example.com	\N	$2y$12$kYJWIurcRBBgEzkN7bqkCeMD7i2W1VGveVaRcog4DRvTRD4qv6Y1.	\N	\N	\N	\N	2024-10-15 06:10:15	2024-10-15 06:10:15
9d3fc7dc-a1ce-45a5-a1b2-e10a81099253	Kaprodi Teknik Mesin	pjproditeknikmesins1@example.com	\N	$2y$12$PYAGzCjGrMOVP8Pkuiv//Ohqjw.2VDk8RAyI76aKHCtyd32x7UNBy	\N	\N	\N	\N	2024-10-15 06:10:16	2024-10-15 06:10:16
9d3fc7de-8bf8-4680-9b2d-dcc30f75e3c8	Kaprodi Kesehatan Masyarakat	pjprodikesehatanmasyarakats1@example.com	\N	$2y$12$UJgX37F.gNmP2ngqVKflkeA/JEZD34/yUW1KIcdpKa6oJ5.Nkxw4S	\N	\N	\N	\N	2024-10-15 06:10:17	2024-10-15 06:10:17
9d3fc7e0-ab70-457e-b9c6-a54f44bb15ff	Kaprodi Keperawatan	pjprodikeperawatans1@example.com	\N	$2y$12$tTn3gMIxwt47CRmTwjxWwem1w1gJjMUDu95ZKyhu.fWEtgNbb8fau	\N	\N	\N	\N	2024-10-15 06:10:18	2024-10-15 06:10:18
9d3fc7e2-8ad2-4ad1-958e-0795823332ed	Kaprodi Farmasi	pjprodifarmasis1@example.com	\N	$2y$12$bKEev3xv/adnjwtfNcRLdOXtfBs1H/AH1mkkm469sP828vWdPE1N2	\N	\N	\N	\N	2024-10-15 06:10:20	2024-10-15 06:10:20
9d3fc7e4-6dae-43e2-ade5-3679c5a6fae1	Kaprodi Ilmu Gizi	pjprodiilmugizis1@example.com	\N	$2y$12$jOWRlHCZfbFO3MFE73pbDeE.01OwXJjwl/BBaYHAYYGp1hvcVi3Lq	\N	\N	\N	\N	2024-10-15 06:10:21	2024-10-15 06:10:21
9d3fc7e6-500e-4416-af22-dc69353b15f7	Kaprodi Pendidikan Jasmani	pjprodipendidikanjasmanis1@example.com	\N	$2y$12$QL4j.r0VEVm9JJ9P.3yLK.97OaFYbcV.Zm9wCvhrmJy6tsZlH6f7C	\N	\N	\N	\N	2024-10-15 06:10:22	2024-10-15 06:10:22
9d3fc7e8-3273-4598-ad83-1942e91bc2f4	Kaprodi Sastra Inggris	pjprodisastrainggriss1@example.com	\N	$2y$12$FdjV8xNnAoH8LJaVDfwDhuQI.lmlZlSriB5YJKTbMZWfpCh.BW7YG	\N	\N	\N	\N	2024-10-15 06:10:23	2024-10-15 06:10:23
9d3fc7ea-0ee1-4b3e-ae0a-b3f3f1d2a713	Kaprodi Sastra Indonesia	pjprodisastraindonesias1@example.com	\N	$2y$12$UqE6GRe3O/PeQQM0qw4OzOhNQ.MDlvUN302x46rBZP72pPhE.TJWO	\N	\N	\N	\N	2024-10-15 06:10:25	2024-10-15 06:10:25
9d3fc7ec-7e9d-4560-983b-8502af95b076	Kaprodi Pendidikan Bahasa Indonesia	pjprodipendidikanbahasaindonesias1@example.com	\N	$2y$12$dECQCPPfUE6jpvhldqPO..gxZL0GOZHPsxGoW714LulLxIAoXvRMS	\N	\N	\N	\N	2024-10-15 06:10:26	2024-10-15 06:10:26
9d3fc7ee-9c99-4218-b621-e6489d0e75d1	Kaprodi Pendidikan Bahasa Inggris	pjprodipendidikanbahasainggriss1@example.com	\N	$2y$12$bX9SOyxB9fR6EY.wiItXKenKKuqNkXCH9cWp6RZUH8yVZyHd4IrRq	\N	\N	\N	\N	2024-10-15 06:10:28	2024-10-15 06:10:28
9d3fc7f0-7f13-4ce0-9f46-11fa2c7f10ba	Kaprodi Sastra Jepang	pjprodisastrajepangs1@example.com	\N	$2y$12$0r5AHRCigAasL1FLEy.fpOEMzrm6B0OZXwHmOo6Cob2tKlSCYlfAK	\N	\N	\N	\N	2024-10-15 06:10:29	2024-10-15 06:10:29
9d3fc7f2-65c8-4f4f-9293-0a2c369f45a8	Kaprodi Pendidikan Bahasa Jepang	pjprodipendidikanbahasajepangs1@example.com	\N	$2y$12$tiQA7cR8Wk9B3X8wnY30POH1SMsKGqJBvDuvNv3tbld0W1spOsFEG	\N	\N	\N	\N	2024-10-15 06:10:30	2024-10-15 06:10:30
9d3fc7f4-3d32-4076-ba73-cffe6a9b133c	Kaprodi Kimia	pjprodikimias1@example.com	\N	$2y$12$tVJXg51iaJHubvOlu1OuCe2Vcj64yn1JQUZKIh8ESzmi14n0Bnbx6	\N	\N	\N	\N	2024-10-15 06:10:31	2024-10-15 06:10:31
9d3fc7f6-1ead-42d6-9443-5bcb3c563786	Kaprodi Matematika	pjprodimatematikas1@example.com	\N	$2y$12$26o4ALr7.9V0/sZ0PxQ2nO7hvq6MH0aETX7Zj1othm3/YLUlm9DQC	\N	\N	\N	\N	2024-10-15 06:10:32	2024-10-15 06:10:32
9d3fc7f8-0ee3-4764-9f18-188381a75e0c	Kaprodi Fisika	pjprodifisikas1@example.com	\N	$2y$12$VhFbAVF4sgqVb01m3d2h8ul29VvZOIJsIG1B7VL41Prb8lG0PTfqu	\N	\N	\N	\N	2024-10-15 06:10:34	2024-10-15 06:10:34
9d3fc7f9-ed00-43f2-8e7f-38bc19033be1	Kaprodi Statistika	pjprodistatistikas1@example.com	\N	$2y$12$l6Kt5czybGdKBiBix2fKnOlhejoh1mClynRP.b8xAR4vlCtZMRH.2	\N	\N	\N	\N	2024-10-15 06:10:35	2024-10-15 06:10:35
9d3fc7fb-c81b-4da0-a889-3ec022335988	Kaprodi Manajemen Sumberdaya Perairan	pjprodimanajemensumberdayaperairans1@example.com	\N	$2y$12$ZztAD.ZnW3sekc/AIi9bReMtTf4YX7whY.9VjLlZXIzm2aeNoJQ0S	\N	\N	\N	\N	2024-10-15 06:10:36	2024-10-15 06:10:36
9d3fc7fd-a6af-42c1-9bd6-fe5035255580	Kaprodi Akuakultur	pjprodiakuakulturs1@example.com	\N	$2y$12$6JWAR.JQB54UR7l2mecNO.6FPg/EO7xfVlrBBajekDzwghxFWtH/y	\N	\N	\N	\N	2024-10-15 06:10:37	2024-10-15 06:10:37
9d3fc7ff-8d8e-4651-92ad-81f8f7b041a6	Kaprodi Ilmu Kelautan	pjprodiilmukelautans1@example.com	\N	$2y$12$13GFOjGH.nzccsvEc4q5G.IbVrtmWZoGO4ky6/iFDyZl8CzEzOA/.	\N	\N	\N	\N	2024-10-15 06:10:39	2024-10-15 06:10:39
9d3fc801-8123-4fc4-a873-7650e3abd8e8	Kaprodi Biologi Internasional	pjprodibiologiinternasionals1@example.com	\N	$2y$12$eLHO0uaGZfpcwE5mT11QX.ZRjtd7oUKFMDYRsu/vNy1BVm1i2nLjO	\N	\N	\N	\N	2024-10-15 06:10:40	2024-10-15 06:10:40
9d3fc803-79ea-40cb-a4e1-ff0d57f2f34f	Kaprodi Keperawatan Internasional	pjprodikeperawataninternasionals1@example.com	\N	$2y$12$hdOOcl6zaT7nWXXc.ad2HOHFLU9g6InxahB6K1KCE3iS9eTbJfZWS	\N	\N	\N	\N	2024-10-15 06:10:41	2024-10-15 06:10:41
9d3fc806-5ead-4f09-9afc-5550802bb678	Kaprodi Manajemen Internasional	pjprodimanajemeninternasionals1@example.com	\N	$2y$12$wOW73fXRIYr0OH5XhsU6fuUVbMkAbpgFtANBy5tTx8XQCxG./fjAi	\N	\N	\N	\N	2024-10-15 06:10:43	2024-10-15 06:10:43
9d3fc808-6fdc-4f99-83c2-3f0231f90ebf	Kaprodi Ekonomi Pembangunan Internasional	pjprodiekonomipembangunaninternasionals1@example.com	\N	$2y$12$7O2gjlsWfPCIUhTnXn6OG.gVab3gQtCGnSFCN2zr/2mqBZgr5TKOe	\N	\N	\N	\N	2024-10-15 06:10:44	2024-10-15 06:10:44
9d3fc80a-5b27-44c6-86e4-dda699dbc49d	Kaprodi Akuntansi Internasional	pjprodiakuntansiinternasionals1@example.com	\N	$2y$12$4j.AozrB8E2TyjT6C/OU4.lqRlUzh8mWDLn47kP4hl0WC/SbXerli	\N	\N	\N	\N	2024-10-15 06:10:46	2024-10-15 06:10:46
9d3fc80c-578c-4071-9d22-ddff58ad362d	Kaprodi Hukum Internasional	pjprodihukuminternasionals1@example.com	\N	$2y$12$VWsl/kQptGyyK7O84QgN4.wN7myOjd5p71iuV1s3dvRqhzqYEUVVq	\N	\N	\N	\N	2024-10-15 06:10:47	2024-10-15 06:10:47
9d3fc80e-4224-472f-836c-ba8cee2ba284	Kaprodi Hubungan Internasional Kelas Internasional	pjprodihubunganinternasionalkelasinternasionals1@example.com	\N	$2y$12$PngxDiTJROljInr/KLIRCOEBzWENb5GzHR5vYLrHNpKFMdmHxLli.	\N	\N	\N	\N	2024-10-15 06:10:48	2024-10-15 06:10:48
9d3fc810-2ec1-44dc-9e4b-d56081fc550a	Kaprodi Agribisnis	pjprodiagribisnisd3@example.com	\N	$2y$12$E3VYHG8xhMfoga/vIS/Vd.vHrSE2Tpn8kpk.4ywXoe1rA38aoXGHK	\N	\N	\N	\N	2024-10-15 06:10:50	2024-10-15 06:10:50
9d3fc812-17f4-4c32-97cf-978da054fa4f	Kaprodi Perencanaan Sumber Daya Lahan	pjprodiperencanaansumberdayalahand3@example.com	\N	$2y$12$ae./qHW.1oZllNsjMspAJ.ggjuVv.FQWfrchK5/RJJlR0GX0Rl6mG	\N	\N	\N	\N	2024-10-15 06:10:51	2024-10-15 06:10:51
9d3fc813-eeb9-46ed-9b26-e621ffe33096	Kaprodi Budidaya Ikan	pjprodibudidayaikand3@example.com	\N	$2y$12$x/OEvoIrQIRb8DFVkqKroeniYjrXlapD7CNbsgLdXJaYYcrIlWImq	\N	\N	\N	\N	2024-10-15 06:10:52	2024-10-15 06:10:52
9d3fc815-c0d1-4fb5-8fa0-5099a8540dd5	Kaprodi Administrasi Bisnis	pjprodiadministrasibisnisd3@example.com	\N	$2y$12$6YcgD3VRPhZlhTS.2xEnf.QB4NLnAOsRKM4NyqfpNyEaXXhkdwy0u	\N	\N	\N	\N	2024-10-15 06:10:53	2024-10-15 06:10:53
9d3fc817-aaf5-408d-b60d-d6997313ee50	Kaprodi Administrasi Perkantoran	pjprodiadministrasiperkantorand3@example.com	\N	$2y$12$DhBn0SGfSRWubVKSRJUEo.0hDE1QeoYo0Nx1x8l//dMUnEKT.6jk6	\N	\N	\N	\N	2024-10-15 06:10:54	2024-10-15 06:10:54
9d3fc819-84af-4815-b18a-3404d438ec1a	Kaprodi Akuntansi	pjprodiakuntansid3@example.com	\N	$2y$12$Tu.NVEsrbcGpsZBPzak7i.ef9CvlIHiX/UJM0aVmlBNnvsa0Z3Lja	\N	\N	\N	\N	2024-10-15 06:10:56	2024-10-15 06:10:56
9d3fc81b-6765-487e-b291-9510340c55e4	Kaprodi Bisnis Internasional	pjprodibisnisinternasionald3@example.com	\N	$2y$12$bIQO7e8sSYrYKkzx1UGOJ.hv8QyTRxdV5WdG3KZFy1G1fPoKrDtb2	\N	\N	\N	\N	2024-10-15 06:10:57	2024-10-15 06:10:57
9d3fc81d-51e8-46c9-96e1-859c80259d6a	Kaprodi Budidaya Ternak	pjprodibudidayaternakd3@example.com	\N	$2y$12$Scpgb5lzuAQfDnf8YeBnUOCZxOtfqedais1AKl.S6huR8oDYYIyoe	\N	\N	\N	\N	2024-10-15 06:10:58	2024-10-15 06:10:58
9d3fc81f-3681-40cd-9923-06ed5c7d3af7	Kaprodi Bahasa Inggris	pjprodibahasainggrisd3@example.com	\N	$2y$12$n7VhpqAmlyHl2kFgMwMZOOKEiUGQmjUE/C69f0uriebdb/sFE8GBO	\N	\N	\N	\N	2024-10-15 06:10:59	2024-10-15 06:10:59
9d3fc821-198c-48d1-89dc-71c11889f5ac	Kaprodi Bahasa Mandarin	pjprodibahasamandarind3@example.com	\N	$2y$12$SQcWGiFrtW8RvbzW6tlegeL0947s/jncU7d6iVOCRYiCJdGopQKG6	\N	\N	\N	\N	2024-10-15 06:11:01	2024-10-15 06:11:01
9d3fc823-e09f-4434-aa21-274e4bc4b3d3	Kaprodi Anestesiologi dan Terapi Intensif	pjprodianestesiologidanterapiintensifprofesi@example.com	\N	$2y$12$nRRE7p5g8aXfuStW2ua4G.rOh17pZit7RZfa6lUt2u3/lRSyO8Rpy	\N	\N	\N	\N	2024-10-15 06:11:02	2024-10-15 06:11:02
9d3fc825-ef70-4ede-97f4-a51e68e848a3	Kaprodi Pendidikan Profesi Akuntan	pjprodipendidikanprofesiakuntanprofesi@example.com	\N	$2y$12$MhQUWtJDQcoNk/OdV0/AiOqV8BvG5y2wGh4ucXJ1p6jarAHiFh7q6	\N	\N	\N	\N	2024-10-15 06:11:04	2024-10-15 06:11:04
9d3fc827-dd0a-4986-8d58-2bd6bb7c6d57	Kaprodi Pendidikan Profesi Dokter	pjprodipendidikanprofesidokterprofesi@example.com	\N	$2y$12$N5NxmEDqe0nToVTfMVu.j.wR3llOP619twdZKeMAFsYJ5hCWvsTTC	\N	\N	\N	\N	2024-10-15 06:11:05	2024-10-15 06:11:05
9d3fc8d0-e649-4623-96a7-f32d26179f22	WR III	wriii@example.com	\N	$2y$12$hxQdzkaNXLrRMMr6J5ryZujDBygP6NnBcz3otzJm65TBQCEA/GZ1e	\N	\N	\N	\N	2024-10-15 06:12:56	2024-10-15 06:12:56
9d3fc829-d05c-4391-ac7e-0103875b393f	Kaprodi Pendidikan Profesi Dokter Gigi	pjprodipendidikanprofesidoktergigiprofesi@example.com	\N	$2y$12$M2Nak8.ke35JmkoUXm8TveUz5Xzg6ZEo8lvjVWxDZMvyxniXfJcPi	\N	\N	\N	\N	2024-10-15 06:11:06	2024-10-15 06:11:06
9d3fc82b-bc2c-4035-b97f-6f6012b8de0a	Kaprodi Pendidikan Profesi Ners	pjprodipendidikanprofesinersprofesi@example.com	\N	$2y$12$vNhBGgjK7xF6LXQ3H1UrQeuisZECOumiOElVmfPYMfB/QQA09mE5q	\N	\N	\N	\N	2024-10-15 06:11:08	2024-10-15 06:11:08
9d3fc82d-9a27-4b2c-829a-dda39fa35954	Kaprodi Pendidikan Profesi Apoteker	pjprodipendidikanprofesiapotekerprofesi@example.com	\N	$2y$12$Zb1M0HHz9HoQK71ArZkdaulB6v/5B35Aaup741wB6BLN9hbLSp7JS	\N	\N	\N	\N	2024-10-15 06:11:09	2024-10-15 06:11:09
9d3fc82f-7367-4242-a899-d48154325f67	Kaprodi Ilmu Pertanian	pjprodiilmupertanians3@example.com	\N	$2y$12$JileT7bFNYqWZvNzL5txXuH2nCHn8XukXSUiP0MJ2D8V1aLpGLqti	\N	\N	\N	\N	2024-10-15 06:11:10	2024-10-15 06:11:10
9d3fc831-6883-494c-87b6-547b9e787bef	Kaprodi Biologi	pjprodibiologis3@example.com	\N	$2y$12$IPMFXC4vP5vAq4EtycVgieaaMr/y7SSv8Hpjf6/myeCsY//ohaRCm	\N	\N	\N	\N	2024-10-15 06:11:11	2024-10-15 06:11:11
9d3fc833-49ae-44cf-af7c-39fb46eeee98	Kaprodi Akuntansi	pjprodiakuntansis3@example.com	\N	$2y$12$jQkotROP/kC9ymKYmvTY0eOBi2fnxIUMz/ZbB/3714t/Pqa8KmIXe	\N	\N	\N	\N	2024-10-15 06:11:13	2024-10-15 06:11:13
9d3fc835-3438-4613-aa48-927ef4a5e7d8	Kaprodi Ilmu Manajemen	pjprodiilmumanajemens3@example.com	\N	$2y$12$A9vwHSZYxLMbK38AndY1teec4Yht17aq4eWwve8qgjrMJRFOlEv6.	\N	\N	\N	\N	2024-10-15 06:11:14	2024-10-15 06:11:14
9d3fc837-1872-46ce-8175-305a39745b71	Kaprodi Ekonomi	pjprodiekonomis3@example.com	\N	$2y$12$.5iEB6GJSypkrLBNaYS4jOMdGVtR3p.YJ4xbH90FUbp3plX04DOjW	\N	\N	\N	\N	2024-10-15 06:11:15	2024-10-15 06:11:15
9d3fc839-001b-4e38-a5ce-c07c396245b7	Kaprodi Peternakan	pjprodipeternakans3@example.com	\N	$2y$12$Vl6EjL.Ts7vNlnPxAzLLzuatIiN5SJG5ZHvUgWwDpcCgO9jLcfiLC	\N	\N	\N	\N	2024-10-15 06:11:16	2024-10-15 06:11:16
9d3fc83a-f24f-4960-b617-0453215a689c	Kaprodi Hukum	pjprodihukums3@example.com	\N	$2y$12$OM73gcWZRBlqp.57KZ.hV.rM2zryXqMcUDV2UUzL.vsEkiRn14/Cm	\N	\N	\N	\N	2024-10-15 06:11:18	2024-10-15 06:11:18
9d3fc83d-353c-473f-9718-8d402b0e14f0	Kaprodi Administrasi Publik	pjprodiadministrasipubliks3@example.com	\N	$2y$12$RnLd2BnBIs5KrjSAYrH.p.mDW6MWc.WesCfsLjkRvp/5oqvkNnRbK	\N	\N	\N	\N	2024-10-15 06:11:19	2024-10-15 06:11:19
9d3fc83f-253e-4c4a-b14f-a08783f3694a	Kaprodi Magister Ilmu Lingkungan	pjprodimagisterilmulingkungans2@example.com	\N	$2y$12$qVsulNOePHNg7NlGzVE.yOBcf3g7Qary7/zY/jdZn.27mkTugrNqm	\N	\N	\N	\N	2024-10-15 06:11:20	2024-10-15 06:11:20
9d3fc841-0c67-48ab-83b9-59fe1ef5feff	Kaprodi Magister Penyuluh Pertanian	pjprodimagisterpenyuluhpertanians2@example.com	\N	$2y$12$20s3UUMV0/WdCmS2EQvbIuJ6/NRvGsXA/wmc4moUbWgnm0m3SWJZG	\N	\N	\N	\N	2024-10-15 06:11:22	2024-10-15 06:11:22
9d3fc842-ef9f-401a-a0cf-67791e298a02	Kaprodi Magister Bioteknologi Pertanian	pjprodimagisterbioteknologipertanians2@example.com	\N	$2y$12$98TjNlFm8kmmBQKl82caKOdD5bRvTAyK8AIyI6yJrxiMNxv5GCtTO	\N	\N	\N	\N	2024-10-15 06:11:23	2024-10-15 06:11:23
9d3fc844-e30b-40db-bca0-40acda16ba3f	Kaprodi Magister Agribisnis	pjprodimagisteragribisniss2@example.com	\N	$2y$12$IY8SMF1kndpctVXBE9zf.OARFabtEMA2dmucZ.KlaFW/J6xuEnLGu	\N	\N	\N	\N	2024-10-15 06:11:24	2024-10-15 06:11:24
9d3fc846-d093-42e3-b6f8-2353b03cc785	Kaprodi Agronomi	pjprodiagronomis2@example.com	\N	$2y$12$Mf1exbKXV8.aEMKbTXazC.bRKu3u96AT/fMk8deuOk0hP6zbKRWje	\N	\N	\N	\N	2024-10-15 06:11:25	2024-10-15 06:11:25
9d3fc848-be3d-4d02-9a80-f73220276f9b	Kaprodi Ilmu Pangan	pjprodiilmupangans2@example.com	\N	$2y$12$5za.HEqx0rG0K9uzuNXRRON/K5HvX3PrukBSoGJHqHOYfMQDBIxyq	\N	\N	\N	\N	2024-10-15 06:11:27	2024-10-15 06:11:27
9d3fc84a-aedc-4013-aa4f-c47eac65747e	Kaprodi Biologi	pjprodibiologis2@example.com	\N	$2y$12$/8QhEKYDruo48vRMLc2h/uU82iE17oPpIQBsCGFHdOK9xh1o8H7oa	\N	\N	\N	\N	2024-10-15 06:11:28	2024-10-15 06:11:28
9d3fc84c-93b2-4d01-b543-f20ea55b63f6	Kaprodi Ekonomi	pjprodiekonomis2@example.com	\N	$2y$12$Q.ZA9ilRkaE.TfwKTBPY7.n6lgpX7xNowvO7tS.U00N9WHYM22ZEe	\N	\N	\N	\N	2024-10-15 06:11:29	2024-10-15 06:11:29
9d3fc84e-85d9-47fc-971f-b892b50c077f	Kaprodi Ilmu Manajemen	pjprodiilmumanajemens2@example.com	\N	$2y$12$B0e7NKoiuuYFEXttPYlP2e.HBfz8e24kUbkmaN3dmkWieH9.YMtDi	\N	\N	\N	\N	2024-10-15 06:11:30	2024-10-15 06:11:30
9d3fc850-76e4-400f-9141-416032dfd627	Kaprodi Magister Akuntansi (MAKSI)	pjprodimagisterakuntansi(maksi)s2@example.com	\N	$2y$12$OyQbwdgBWgAJsaTWyQoRsej0f1YAnGWAllMNbf.McxcAfn0WLL8rK	\N	\N	\N	\N	2024-10-15 06:11:32	2024-10-15 06:11:32
9d3fc852-5b43-4304-bbb5-31e2e7218477	Kaprodi Magister Peternakan	pjprodimagisterpeternakans2@example.com	\N	$2y$12$91/C86YsEuXAEdeMyjCHBOvz6gK2mhm9RCdDAhPcQseDPLH6OyTN2	\N	\N	\N	\N	2024-10-15 06:11:33	2024-10-15 06:11:33
9d3fc854-388a-4e16-83c4-61647ce60ff5	Kaprodi Magister Hukum	pjprodimagisterhukums2@example.com	\N	$2y$12$p8uuXXHqrmIoKM8Q6FkUx.YAbXw95H3Spcwn6DG/ofT.THrPBcO66	\N	\N	\N	\N	2024-10-15 06:11:34	2024-10-15 06:11:34
9d3fc856-1899-4dc6-98f9-7e811c2044db	Kaprodi Magister Kenotariatan (MKn)	pjprodimagisterkenotariatan(mkn)s2@example.com	\N	$2y$12$PAQAh2shS9TFJImyd3tYj.WVOi3jJvU/NRSyZMaSOUfVt50bnQTs2	\N	\N	\N	\N	2024-10-15 06:11:35	2024-10-15 06:11:35
9d3fc857-fe56-4a90-803a-16dc278b1ae8	Kaprodi Magister Administrasi Publik	pjprodimagisteradministrasipubliks2@example.com	\N	$2y$12$bsFSxbiHWykgINNaopOsyeWyBegvalcTcyMiy4UxNEIdgjzWWidJi	\N	\N	\N	\N	2024-10-15 06:11:37	2024-10-15 06:11:37
9d3fc859-f2a4-42ef-97b3-9558fe0e20a1	Kaprodi Magister Sosiologi	pjprodimagistersosiologis2@example.com	\N	$2y$12$l4DiVhcxOJiJ2UlBUFg7VuECzPzN8DTzXi.SJwez1AV/vzZfeQ3M.	\N	\N	\N	\N	2024-10-15 06:11:38	2024-10-15 06:11:38
9d3fc85b-dc10-4efb-956b-be0c6d9647cc	Kaprodi Magister Ilmu Komunikasi	pjprodimagisterilmukomunikasis2@example.com	\N	$2y$12$9f0hWxKcdwAuMWScV7HYhOeNFOOC6bfCaFeQZ9Buhvrn1PHhI.hsK	\N	\N	\N	\N	2024-10-15 06:11:39	2024-10-15 06:11:39
9d3fc85d-c97c-4112-b051-6e7858892d6e	Kaprodi Ilmu Politik	pjprodiilmupolitiks2@example.com	\N	$2y$12$ybRk0Z5ssdTYo5rV2JK1N.LXRnsIHdAQP5QZL0oqERTMzGMvxTVL6	\N	\N	\N	\N	2024-10-15 06:11:40	2024-10-15 06:11:40
9d3fc85f-afe9-4551-b493-83897f072e60	Kaprodi Teknik Sipil	pjproditekniksipils2@example.com	\N	$2y$12$R0c9PXmqzq7gHs/GMm9vB.aB2V5Mhk.mATwkGe3WflkQTMUIYTmtG	\N	\N	\N	\N	2024-10-15 06:11:42	2024-10-15 06:11:42
9d3fc861-95ca-47a0-b3cb-11be97ca9fd1	Kaprodi Ilmu Biomedis	pjprodiilmubiomediss2@example.com	\N	$2y$12$G8B8RLvsnp7xZOXDwAe7vOnhT1M8Lvgof1/ZDcmd9x09.36HT521G	\N	\N	\N	\N	2024-10-15 06:11:43	2024-10-15 06:11:43
9d3fc863-7d0f-4aef-a17b-47d37a4ca703	Kaprodi Kesehatan Masyarakat	pjprodikesehatanmasyarakats2@example.com	\N	$2y$12$wr..KMxuxtx.pAFuHVOr2uDll9LmzSyjxoXgotMsxrrkrdCbjoVja	\N	\N	\N	\N	2024-10-15 06:11:44	2024-10-15 06:11:44
9d3fc865-60f2-41ad-92ee-c88dfda59f8d	Kaprodi Magister Keperawatan	pjprodimagisterkeperawatans2@example.com	\N	$2y$12$o.b/TLliayXa3v.A9UiwuObkA691aGoA1JCEgGSMjs7/qQViKgC5q	\N	\N	\N	\N	2024-10-15 06:11:45	2024-10-15 06:11:45
9d3fc867-4f8c-487f-806e-3e419eff4b2b	Kaprodi Fisika	pjprodifisikas2@example.com	\N	$2y$12$vGISfSuoJORvj7FVWuP0DOtrc3mMvCS5EYYmar.Q9yoWx0LVZCLga	\N	\N	\N	\N	2024-10-15 06:11:47	2024-10-15 06:11:47
9d3fc869-3613-43f4-baea-0c1a378b1595	Kaprodi Ilmu Kelautan	pjprodiilmukelautans2@example.com	\N	$2y$12$cFPcpaIT56rm4oXWWMppuOs1lPR3NQHWZF9mElT87IXNtl3cB/R8G	\N	\N	\N	\N	2024-10-15 06:11:48	2024-10-15 06:11:48
9d3fc86b-12cc-4291-9934-721bfc43e4ad	Kaprodi Sumberdaya Akuatik	pjprodisumberdayaakuatiks2@example.com	\N	$2y$12$95.PefW2MqoxD4Hb7XGnUOaPbVXhamvMiU4A0O21fJTRH7cyg0FVK	\N	\N	\N	\N	2024-10-15 06:11:49	2024-10-15 06:11:49
9d3fc86c-f7e3-4697-a468-328b74441e44	DEKAN Pertanian	dekanpertanian@example.com	\N	$2y$12$HlCZriVIyWrAEQiH1gVg..KVoSMpqnpuBmSH0bCJuCh7EPtRqMJfe	\N	\N	\N	\N	2024-10-15 06:11:50	2024-10-15 06:11:50
9d3fc86e-d7fa-441f-a7f9-c877e15a4699	DEKAN Biologi	dekanbiologi@example.com	\N	$2y$12$NYZ5FoeO6/oguTu.12cipePitW.sCTZdhZxAlTO9p3.AZ2a0rxinS	\N	\N	\N	\N	2024-10-15 06:11:52	2024-10-15 06:11:52
9d3fc870-b598-4fb8-8524-4dad9c6b272a	DEKAN Ekonomi dan Bisnis	dekanekonomidanbisnis@example.com	\N	$2y$12$7svbsMdaA/0gHaFHIITwlu0wRmszIFCAnSmfzDRJqoqWWVwKr9a5.	\N	\N	\N	\N	2024-10-15 06:11:53	2024-10-15 06:11:53
9d3fc872-9f14-4001-ad99-a9fadd756c9d	DEKAN Peternakan	dekanpeternakan@example.com	\N	$2y$12$UDl40d58QKIaAnfp2Q9eyuTp8EP0yjT7fL2dsDxMDDRqo7bUibnTu	\N	\N	\N	\N	2024-10-15 06:11:54	2024-10-15 06:11:54
9d3fc874-8573-4a74-898c-c79fec4849fc	DEKAN Hukum	dekanhukum@example.com	\N	$2y$12$rKIZVLzAB7N2bQNpoxsFMu7jqI.H3RqDglyPq2Qcq6L4QxaZ.U/Pq	\N	\N	\N	\N	2024-10-15 06:11:55	2024-10-15 06:11:55
9d3fc876-69d5-46a4-9518-f6cf725ff213	DEKAN Ilmu Sosial dan Ilmu Politik	dekanilmusosialdanilmupolitik@example.com	\N	$2y$12$YTISO3ESzSy8K/WcqeNHHuOy0ez6zfQqkPDZbW4zzI8dOWJBMtyD6	\N	\N	\N	\N	2024-10-15 06:11:57	2024-10-15 06:11:57
9d3fc878-52f0-4946-8eb0-d25e8fe3217b	DEKAN Kedokteran	dekankedokteran@example.com	\N	$2y$12$k.8A9mvH9EFVxbou3Jxl2u/PRzvwZFvNIlfFLQqSjK6CCT6POTZmK	\N	\N	\N	\N	2024-10-15 06:11:58	2024-10-15 06:11:58
9d3fc87a-388f-488d-a0f7-4d6aa71facda	DEKAN Teknik	dekanteknik@example.com	\N	$2y$12$nlox2DhbzF0TATpF.38RDOHXqPzTAg/mYhpDmxs1k1ERM6sxhlEy2	\N	\N	\N	\N	2024-10-15 06:11:59	2024-10-15 06:11:59
9d3fc87c-23a6-4172-ba7c-7410b6ac0c88	DEKAN Ilmu-Ilmu Kesehatan	dekanilmu-ilmukesehatan@example.com	\N	$2y$12$XqsXmu0DwYsKTiIbkze85O8ee.8IPMaHl9K9D356auV07Wgy9OW4y	\N	\N	\N	\N	2024-10-15 06:12:00	2024-10-15 06:12:00
9d3fc87e-59eb-4555-823f-916a57fb5066	DEKAN Ilmu Budaya	dekanilmubudaya@example.com	\N	$2y$12$b7DvFB.MjTaJ4gaQahldc.9CNV/tR7YLU/TR8kHHKSeeqzb.eclbG	\N	\N	\N	\N	2024-10-15 06:12:02	2024-10-15 06:12:02
9d3fc880-e111-4787-af26-3aa17d15952c	DEKAN Matematika dan IPA	dekanmatematikadanipa@example.com	\N	$2y$12$m4dV70uhZs19ZWzj93qzXOavm/GJ1TqNjR15W05B/KqfligoJg7Y2	\N	\N	\N	\N	2024-10-15 06:12:03	2024-10-15 06:12:03
9d3fc882-d75e-4190-bf55-4a9067e042ca	DEKAN Ilmu Perikanan dan Kelautan	dekanilmuperikanandankelautan@example.com	\N	$2y$12$VtRW8CEv..cTtmSNE2fca.1iv7ELiijl81z3Us0JrTn9Wbh8oSCiG	\N	\N	\N	\N	2024-10-15 06:12:05	2024-10-15 06:12:05
9d3fc884-c3a2-43b3-9c3e-11f3eb3087c5	WD I Pertanian	wdipertanian@example.com	\N	$2y$12$3ChjHYpmpbt7lhrrMe6Sv.u0vfu4LeJefjugYMs/vECEcRyj4d03q	\N	\N	\N	\N	2024-10-15 06:12:06	2024-10-15 06:12:06
9d3fc886-ba81-4c5a-ab7c-75b9f1d43199	WD I Biologi	wdibiologi@example.com	\N	$2y$12$5osJh2X3tViAZrYvhLIKhuJca9U5snYYmyu0a3a/yaU3jQoGcL0b.	\N	\N	\N	\N	2024-10-15 06:12:07	2024-10-15 06:12:07
9d3fc888-a10e-4365-b410-b6d2b59d8d96	WD I Ekonomi dan Bisnis	wdiekonomidanbisnis@example.com	\N	$2y$12$ISqOP1TMWZYH718npgHkOOlxJ6k5dLsMJ2FPKoL3/KgckxYNWpCpa	\N	\N	\N	\N	2024-10-15 06:12:08	2024-10-15 06:12:08
9d3fc88a-81db-435c-abfb-01f66117285b	WD I Peternakan	wdipeternakan@example.com	\N	$2y$12$K.WHwd4acXH0iCFrSscLb.NWDpkFGup/RGbQChbG6XOicDsdCRxqS	\N	\N	\N	\N	2024-10-15 06:12:10	2024-10-15 06:12:10
9d3fc88c-69fd-4f60-a035-e6200c96f058	WD I Hukum	wdihukum@example.com	\N	$2y$12$IjQf.ZMddCSpk58csvWmKOCqZgSC1jqdZRPq8QwH8DrDethes7WyW	\N	\N	\N	\N	2024-10-15 06:12:11	2024-10-15 06:12:11
9d3fc88e-4fe6-4e2d-981f-6beab174fd37	WD I Ilmu Sosial dan Ilmu Politik	wdiilmusosialdanilmupolitik@example.com	\N	$2y$12$txtjOkh9OsR9pBLIdNl7dOxIhPO3ysEql/WkkZXKPDuRkbh0P5hIm	\N	\N	\N	\N	2024-10-15 06:12:12	2024-10-15 06:12:12
9d3fc890-294d-4a46-a5bd-14633cb280c8	WD I Kedokteran	wdikedokteran@example.com	\N	$2y$12$qCdjNAsSS6WzAu3VJB5dGOiDl.xnz0YF8uSK/t/OvPIp1A3ixJNPa	\N	\N	\N	\N	2024-10-15 06:12:13	2024-10-15 06:12:13
9d3fc892-044c-4202-94c4-471547821b9b	WD I Teknik	wditeknik@example.com	\N	$2y$12$TS6RWLB6iX2nI8OC.h8ye.ftPe9iS2Zcsw6EK2HFGedC6zr5Jor.2	\N	\N	\N	\N	2024-10-15 06:12:15	2024-10-15 06:12:15
9d3fc893-e6c1-4387-8374-849290c04d84	WD I Ilmu-Ilmu Kesehatan	wdiilmu-ilmukesehatan@example.com	\N	$2y$12$yvdPtGwY0mVYEc0IaafXUezFJ/5KPM97KzLVSCugV2GV5uqeyU59W	\N	\N	\N	\N	2024-10-15 06:12:16	2024-10-15 06:12:16
9d3fc895-d756-41f3-aba9-beeca860fe4f	WD I Ilmu Budaya	wdiilmubudaya@example.com	\N	$2y$12$KjHs2oOdcgv/bYEgLXT3xOm0asHLgWfesf9QRW7qIjbK6SzcnXoWO	\N	\N	\N	\N	2024-10-15 06:12:17	2024-10-15 06:12:17
9d3fc897-c108-4f08-88c2-b68d1d7ed767	WD I Matematika dan IPA	wdimatematikadanipa@example.com	\N	$2y$12$sJIJqrnjEmQYhN0SS7Dp/ej21JI17XEfB0bWahVfD2.7zg0T8TBsm	\N	\N	\N	\N	2024-10-15 06:12:18	2024-10-15 06:12:18
9d3fc899-e623-4eac-b649-6b33f2100167	WD I Ilmu Perikanan dan Kelautan	wdiilmuperikanandankelautan@example.com	\N	$2y$12$HkTuPxUAb3qf1LznfegQZOnhlN7sMTCjuqLJuWHOHiA6.6xQjB5By	\N	\N	\N	\N	2024-10-15 06:12:20	2024-10-15 06:12:20
9d3fc89b-c919-4e4a-80cf-91e9652c0c1b	WD II Pertanian	wdiipertanian@example.com	\N	$2y$12$vS.H0B2HU05gcPox19yEJ.Nzo2CNxH09QtTmmC2WGeAmhb9MAjKaO	\N	\N	\N	\N	2024-10-15 06:12:21	2024-10-15 06:12:21
9d3fc89d-a8e8-4d47-9d67-f9f0e663af11	WD II Biologi	wdiibiologi@example.com	\N	$2y$12$bZ8oZL58Cdg9HDa0UgRLqup6Vxisq4/bjfzCriiapKrPLaZ168afe	\N	\N	\N	\N	2024-10-15 06:12:22	2024-10-15 06:12:22
9d3fc89f-8ecf-479d-a978-776070339354	WD II Ekonomi dan Bisnis	wdiiekonomidanbisnis@example.com	\N	$2y$12$OqW4tAw18FLLz3UyXgsQlelWJyo.OCO.6hlkJIykLZkaHrKxraYWG	\N	\N	\N	\N	2024-10-15 06:12:24	2024-10-15 06:12:24
9d3fc8a1-7da7-4ed6-8177-992fb61e21f6	WD II Peternakan	wdiipeternakan@example.com	\N	$2y$12$Ey1amlD.HueRcqt0wcJ4WO7hXNCvcqRXeAEI/WcA2TdwY58t3YGMW	\N	\N	\N	\N	2024-10-15 06:12:25	2024-10-15 06:12:25
9d3fc8a3-6893-460d-98c1-1bf0a270c876	WD II Hukum	wdiihukum@example.com	\N	$2y$12$7z3TL0Cw9dEf0A5RKtDereyvbuJarQ1xgxd9GmfrkMrkGSEvrYpB.	\N	\N	\N	\N	2024-10-15 06:12:26	2024-10-15 06:12:26
9d3fc8a5-545a-43bd-9983-f6a67243678e	WD II Ilmu Sosial dan Ilmu Politik	wdiiilmusosialdanilmupolitik@example.com	\N	$2y$12$6L6QynBF04BGgREFkycpNulGjh18lCex2MRB2FKoURnL204NRGgWq	\N	\N	\N	\N	2024-10-15 06:12:27	2024-10-15 06:12:27
9d3fc8a7-4007-401d-b7ef-286e796ee231	WD II Kedokteran	wdiikedokteran@example.com	\N	$2y$12$vDv30DEuJFjEUWrgx2F9hOtrn7pO.azpsuv93alkG/k5rbzSorBBy	\N	\N	\N	\N	2024-10-15 06:12:29	2024-10-15 06:12:29
9d3fc8a9-1a70-4ec6-b66f-7e9429730571	WD II Teknik	wdiiteknik@example.com	\N	$2y$12$lc/OwRa20KZzbb1kIJV.QOxw/FA8/zfIi4LJvS6aELYLiQUEjTmxO	\N	\N	\N	\N	2024-10-15 06:12:30	2024-10-15 06:12:30
9d3fc8ab-0a4f-4b9a-aab3-fe37e2237c46	WD II Ilmu-Ilmu Kesehatan	wdiiilmu-ilmukesehatan@example.com	\N	$2y$12$5DClF47/gI6.y0tuOCk8YOjIMchh1BAVxIXHvPZ2PyHw1dyi1J9v2	\N	\N	\N	\N	2024-10-15 06:12:31	2024-10-15 06:12:31
9d3fc8ac-ec14-420c-83ce-ebdb84726941	WD II Ilmu Budaya	wdiiilmubudaya@example.com	\N	$2y$12$2PY9hda2Rbo3uUzWFE6Oo.8TwhVs2TySUJqiePbZx8NL16yljrGh6	\N	\N	\N	\N	2024-10-15 06:12:32	2024-10-15 06:12:32
9d3fc8ae-d0f9-4ea7-a97c-c5b489f8e5a1	WD II Matematika dan IPA	wdiimatematikadanipa@example.com	\N	$2y$12$qg2OJAKXMGdLP7tM.gvLQ.GCnMxL0D3A6oghh0rqT7kTb3q.L8wL.	\N	\N	\N	\N	2024-10-15 06:12:34	2024-10-15 06:12:34
9d3fc8b0-b64d-490e-8cdd-6f3ff92a8496	WD II Ilmu Perikanan dan Kelautan	wdiiilmuperikanandankelautan@example.com	\N	$2y$12$VUmOe4bSesXAyladCAYLwevqCmKiYCdiY/IZHjJvM0ipcMY8dVXH.	\N	\N	\N	\N	2024-10-15 06:12:35	2024-10-15 06:12:35
9d3fc8b2-9fa2-4d9a-9ddf-74c040f6e21e	WD III Pertanian	wdiiipertanian@example.com	\N	$2y$12$M8WTNynDpjMPaa5nnDgR9euiLDIaxiCK12J1r.8HJw6T5dj3HsXqK	\N	\N	\N	\N	2024-10-15 06:12:36	2024-10-15 06:12:36
9d3fc8b4-7fcd-46d4-b051-67d136d0fec4	WD III Biologi	wdiiibiologi@example.com	\N	$2y$12$Ninya2QMBNUalFlyCyIZGuZ6Dqu.OM4/Albxp.tE5Q6.BiD6n1A8m	\N	\N	\N	\N	2024-10-15 06:12:37	2024-10-15 06:12:37
9d3fc8b6-5bbf-43f3-bde4-d9f01c295f84	WD III Ekonomi dan Bisnis	wdiiiekonomidanbisnis@example.com	\N	$2y$12$v6U7PBCUH0.UVmsHFOSRv.JKK3mgYOLcgAbtsTdk5OBZWYx0KlcIC	\N	\N	\N	\N	2024-10-15 06:12:38	2024-10-15 06:12:38
9d3fc8b8-3b64-413d-8692-1b17582c041e	WD III Peternakan	wdiiipeternakan@example.com	\N	$2y$12$/DiL6edUCMmQtHn6fhGCy.sP9bqC2BAu60kmXQ3fcBZtq7aB61e.C	\N	\N	\N	\N	2024-10-15 06:12:40	2024-10-15 06:12:40
9d3fc8ba-1ddc-4f7b-9919-a39c5a41c87d	WD III Hukum	wdiiihukum@example.com	\N	$2y$12$w4MVCjV.8.vFQ7esMm0fG.4skb.X4RIB6fnlvFL.1wP8V8y0a1ZHq	\N	\N	\N	\N	2024-10-15 06:12:41	2024-10-15 06:12:41
9d3fc8bc-049c-4a85-8690-aece5f5b5a3b	WD III Ilmu Sosial dan Ilmu Politik	wdiiiilmusosialdanilmupolitik@example.com	\N	$2y$12$vAee4tmW31f7IGzm/Y.Fmey68powELQZbvarKlcg0roELARkXB4Ki	\N	\N	\N	\N	2024-10-15 06:12:42	2024-10-15 06:12:42
9d3fc8bd-f2c4-4b8a-923e-ef13844bd221	WD III Kedokteran	wdiiikedokteran@example.com	\N	$2y$12$BGJj47bCdCzkYciUzQXpUeYOylURvuaNg5GoG1zJOlS00Y50R00Je	\N	\N	\N	\N	2024-10-15 06:12:43	2024-10-15 06:12:43
9d3fc8bf-e09a-4b14-8a54-7e50c8172779	WD III Teknik	wdiiiteknik@example.com	\N	$2y$12$XZlpPLZ3B2neuFAPGuh7guDI3seGmO6aZjXMKF6dIABcAB6AxkIoe	\N	\N	\N	\N	2024-10-15 06:12:45	2024-10-15 06:12:45
9d3fc8c1-c5a9-4b16-a1ea-0b4d7403ef6c	WD III Ilmu-Ilmu Kesehatan	wdiiiilmu-ilmukesehatan@example.com	\N	$2y$12$SMjAkeC5uZkRMr7qyrwVXeLWT7Gk3HFvaCHAut.gBwz5dz2TPPukS	\N	\N	\N	\N	2024-10-15 06:12:46	2024-10-15 06:12:46
9d3fc8c3-b0ff-4b00-ab70-dd124f37fa80	WD III Ilmu Budaya	wdiiiilmubudaya@example.com	\N	$2y$12$3ydTclXDC/nJcVGv4GsRKOOiMtGZaN.vYfbfyXNkKRM1sJZ8Axa9.	\N	\N	\N	\N	2024-10-15 06:12:47	2024-10-15 06:12:47
9d3fc8c5-9083-4c36-ac4c-1b9fcb74ed57	WD III Matematika dan IPA	wdiiimatematikadanipa@example.com	\N	$2y$12$DWDtM.39Ofh4V0U4kCrXfuD269oF4zl9P55W5wmxJM4XvnxXasvT.	\N	\N	\N	\N	2024-10-15 06:12:48	2024-10-15 06:12:48
9d3fc8c7-7a87-4d53-b8ba-ed33908a6f98	WD III Ilmu Perikanan dan Kelautan	wdiiiilmuperikanandankelautan@example.com	\N	$2y$12$UXiL4WLHgf3URtWsxnPt2u1omWrEOoytpzv6HaVi5mzRszY9e85wC	\N	\N	\N	\N	2024-10-15 06:12:50	2024-10-15 06:12:50
9d3fc8c9-697a-4f42-8465-8b49a0333f78	Ketua LPPM	ketualppm@example.com	\N	$2y$12$Vfe3C6otqaeOx4citiwjx.GE7B8/pJ2JgKcoFQAapg2Kqii9B9ffO	\N	\N	\N	\N	2024-10-15 06:12:51	2024-10-15 06:12:51
9d3fc8cb-4b6b-41df-acbd-46deee5952a0	Ketua LP3M	ketualp3m@example.com	\N	$2y$12$uPRD/xELtXOYKyoCS8xj8.Na0W4Bmgxg1HmPZu7udOmzH2E6grRjO	\N	\N	\N	\N	2024-10-15 06:12:52	2024-10-15 06:12:52
9d3fc8cd-2d49-4616-a0b8-d11bb5400716	WR I	wri@example.com	\N	$2y$12$fBmEJ8AHo.qt59q91i9ZwOyN8ko6sSaFPs9qjKVsSwB26pu61.ViS	\N	\N	\N	\N	2024-10-15 06:12:53	2024-10-15 06:12:53
9d3fc8d2-d5cd-4c22-97ff-0139bd1f562a	WR IV	wriv@example.com	\N	$2y$12$IkHfM43CLjB1Qvn8AGAyF./yVtSNLsLKrPcQp0CNo9KzwO9eehIiW	\N	\N	\N	\N	2024-10-15 06:12:57	2024-10-15 06:12:57
9d3fc8d4-ba68-4f5a-a6bb-05ff4b1b9c5a	Ketua LPTSI	ketualptsi@example.com	\N	$2y$12$DyMAqXRSxIC.8vWll7KwCeo3MmET2z0EvuLxEqP/S/ol8z8PoMy/6	\N	\N	\N	\N	2024-10-15 06:12:58	2024-10-15 06:12:58
9d3fc8d6-a78b-4529-a859-94d4bc50eecd	Ketua ULT	ketuault@example.com	\N	$2y$12$jPnUPyv/wz9OmpjnfY7ftugHVX.u7jYgfPCKtZgg.LO6oRntfhZ4G	\N	\N	\N	\N	2024-10-15 06:13:00	2024-10-15 06:13:00
9d3fc8d8-932a-4d09-b18b-82134554b518	Kepala Biro Akademik	kepalabiroakademik@example.com	\N	$2y$12$8li2oHE7Lp9yfoNFentKhO8IifjHJI5z.BoUcjny/bd/ws8uWG6sS	\N	\N	\N	\N	2024-10-15 06:13:01	2024-10-15 06:13:01
9d3fc8db-6c29-4625-864a-159c7f5e8777	Kepala Biro Keuangan	kepalabirokeuangan@example.com	\N	$2y$12$Qsx70st0F0Cr8zter/qih.LM4vbPvqyOyaN.nWpb9.xQvUxbBpN4m	\N	\N	\N	\N	2024-10-15 06:13:03	2024-10-15 06:13:03
\.


--
-- Name: assessment_form_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assessment_form_id_seq', 1, false);


--
-- Name: assessment_pertanyaan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.assessment_pertanyaan_id_seq', 6, true);


--
-- Name: ayat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ayat_id_seq', 8, true);


--
-- Name: berita_acara_auditee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.berita_acara_auditee_id_seq', 1, false);


--
-- Name: berita_acara_auditor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.berita_acara_auditor_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: instrumen_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.instrumen_id_seq', 171, true);


--
-- Name: jabatan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jabatan_id_seq', 17, true);


--
-- Name: jenis_pertanyaan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jenis_pertanyaan_id_seq', 2, true);


--
-- Name: jenjang_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jenjang_id_seq', 5, true);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: kategori_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.kategori_id_seq', 15, true);


--
-- Name: kriteria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.kriteria_id_seq', 3, true);


--
-- Name: laporan_auditee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.laporan_auditee_id_seq', 1, false);


--
-- Name: laporan_auditor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.laporan_auditor_id_seq', 1, false);


--
-- Name: laporan_form_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.laporan_form_id_seq', 1, false);


--
-- Name: level_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.level_id_seq', 3, true);


--
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.menu_id_seq', 10, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 60, true);


--
-- Name: notifikasi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notifikasi_id_seq', 1, false);


--
-- Name: pasal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pasal_id_seq', 4, true);


--
-- Name: peraturan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.peraturan_id_seq', 1, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);


--
-- Name: ptk_auditee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ptk_auditee_id_seq', 1, false);


--
-- Name: ptk_auditor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ptk_auditor_id_seq', 1, false);


--
-- Name: ptk_form_deskripsi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ptk_form_deskripsi_id_seq', 1, false);


--
-- Name: ptk_form_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ptk_form_id_seq', 1, false);


--
-- Name: ptk_form_rencana_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ptk_form_rencana_id_seq', 1, false);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 7, true);


--
-- Name: setting_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.setting_id_seq', 17, true);


--
-- Name: standar_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.standar_id_seq', 4, true);


--
-- Name: status_assessment_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_assessment_id_seq', 1, false);


--
-- Name: status_audit_auditee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_audit_auditee_id_seq', 1, false);


--
-- Name: status_audit_auditor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_audit_auditor_id_seq', 1, false);


--
-- Name: status_laporan_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_laporan_id_seq', 1, false);


--
-- Name: status_ptk_auditee_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_ptk_auditee_id_seq', 1, false);


--
-- Name: status_ptk_auditor_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_ptk_auditor_id_seq', 1, false);


--
-- Name: submenu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.submenu_id_seq', 23, true);


--
-- Name: assessment_form assessment_form_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_form
    ADD CONSTRAINT assessment_form_pkey PRIMARY KEY (id);


--
-- Name: assessment_jawaban assessment_jawaban_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_pkey PRIMARY KEY (id);


--
-- Name: assessment_pertanyaan assessment_pertanyaan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_pertanyaan
    ADD CONSTRAINT assessment_pertanyaan_pkey PRIMARY KEY (id);


--
-- Name: auditee auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_pkey PRIMARY KEY (id);


--
-- Name: auditor auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditor
    ADD CONSTRAINT auditor_pkey PRIMARY KEY (id);


--
-- Name: ayat ayat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ayat
    ADD CONSTRAINT ayat_pkey PRIMARY KEY (id);


--
-- Name: berita_acara_auditee berita_acara_auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditee
    ADD CONSTRAINT berita_acara_auditee_pkey PRIMARY KEY (id);


--
-- Name: berita_acara_auditor berita_acara_auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditor
    ADD CONSTRAINT berita_acara_auditor_pkey PRIMARY KEY (id);


--
-- Name: berita_acara berita_acara_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara
    ADD CONSTRAINT berita_acara_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: fakultas fakultas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.fakultas
    ADD CONSTRAINT fakultas_pkey PRIMARY KEY (id);


--
-- Name: form form_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.form
    ADD CONSTRAINT form_pkey PRIMARY KEY (id);


--
-- Name: instrumen instrumen_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen
    ADD CONSTRAINT instrumen_pkey PRIMARY KEY (id);


--
-- Name: jabatan jabatan_nama_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan
    ADD CONSTRAINT jabatan_nama_unique UNIQUE (nama);


--
-- Name: jabatan jabatan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan
    ADD CONSTRAINT jabatan_pkey PRIMARY KEY (id);


--
-- Name: jabatan jabatan_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan
    ADD CONSTRAINT jabatan_slug_unique UNIQUE (slug);


--
-- Name: jadwal_audit jadwal_audit_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jadwal_audit
    ADD CONSTRAINT jadwal_audit_pkey PRIMARY KEY (id);


--
-- Name: jawaban_auditee jawaban_auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_pkey PRIMARY KEY (id);


--
-- Name: jawaban_auditor jawaban_auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_pkey PRIMARY KEY (id);


--
-- Name: jenis_pertanyaan jenis_pertanyaan_nama_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jenis_pertanyaan
    ADD CONSTRAINT jenis_pertanyaan_nama_unique UNIQUE (nama);


--
-- Name: jenis_pertanyaan jenis_pertanyaan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jenis_pertanyaan
    ADD CONSTRAINT jenis_pertanyaan_pkey PRIMARY KEY (id);


--
-- Name: jenis_pertanyaan jenis_pertanyaan_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jenis_pertanyaan
    ADD CONSTRAINT jenis_pertanyaan_slug_unique UNIQUE (slug);


--
-- Name: jenjang jenjang_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jenjang
    ADD CONSTRAINT jenjang_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: kategori kategori_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kategori
    ADD CONSTRAINT kategori_pkey PRIMARY KEY (id);


--
-- Name: kategori kategori_standar_id_kode_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kategori
    ADD CONSTRAINT kategori_standar_id_kode_unique UNIQUE (standar_id, kode);


--
-- Name: kriteria kriteria_nama_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kriteria
    ADD CONSTRAINT kriteria_nama_unique UNIQUE (nama);


--
-- Name: kriteria kriteria_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kriteria
    ADD CONSTRAINT kriteria_pkey PRIMARY KEY (id);


--
-- Name: kriteria kriteria_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kriteria
    ADD CONSTRAINT kriteria_slug_unique UNIQUE (slug);


--
-- Name: laporan_auditee laporan_auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditee
    ADD CONSTRAINT laporan_auditee_pkey PRIMARY KEY (id);


--
-- Name: laporan_auditor laporan_auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditor
    ADD CONSTRAINT laporan_auditor_pkey PRIMARY KEY (id);


--
-- Name: laporan_form laporan_form_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_form
    ADD CONSTRAINT laporan_form_pkey PRIMARY KEY (id);


--
-- Name: laporan laporan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_pkey PRIMARY KEY (id);


--
-- Name: level level_nama_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.level
    ADD CONSTRAINT level_nama_unique UNIQUE (nama);


--
-- Name: level level_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.level
    ADD CONSTRAINT level_pkey PRIMARY KEY (id);


--
-- Name: level level_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.level
    ADD CONSTRAINT level_slug_unique UNIQUE (slug);


--
-- Name: link link_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_pkey PRIMARY KEY (id);


--
-- Name: menu menu_menu_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu
    ADD CONSTRAINT menu_menu_unique UNIQUE (menu);


--
-- Name: menu menu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu
    ADD CONSTRAINT menu_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: notifikasi notifikasi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_pkey PRIMARY KEY (id);


--
-- Name: pasal pasal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pasal
    ADD CONSTRAINT pasal_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: peraturan peraturan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.peraturan
    ADD CONSTRAINT peraturan_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: prodi prodi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prodi
    ADD CONSTRAINT prodi_pkey PRIMARY KEY (id);


--
-- Name: ptk_auditee ptk_auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditee
    ADD CONSTRAINT ptk_auditee_pkey PRIMARY KEY (id);


--
-- Name: ptk_auditor ptk_auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditor
    ADD CONSTRAINT ptk_auditor_pkey PRIMARY KEY (id);


--
-- Name: ptk_form_deskripsi ptk_form_deskripsi_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_deskripsi
    ADD CONSTRAINT ptk_form_deskripsi_pkey PRIMARY KEY (id);


--
-- Name: ptk_form ptk_form_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form
    ADD CONSTRAINT ptk_form_pkey PRIMARY KEY (id);


--
-- Name: ptk_form_rencana ptk_form_rencana_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_rencana
    ADD CONSTRAINT ptk_form_rencana_pkey PRIMARY KEY (id);


--
-- Name: ptk ptk_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk
    ADD CONSTRAINT ptk_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_name_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_unique UNIQUE (name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: setting setting_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.setting
    ADD CONSTRAINT setting_pkey PRIMARY KEY (id);


--
-- Name: standar standar_kode_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standar
    ADD CONSTRAINT standar_kode_unique UNIQUE (kode);


--
-- Name: standar standar_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standar
    ADD CONSTRAINT standar_pkey PRIMARY KEY (id);


--
-- Name: status_assessment status_assessment_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_pkey PRIMARY KEY (id);


--
-- Name: status_audit_auditee status_audit_auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditee
    ADD CONSTRAINT status_audit_auditee_pkey PRIMARY KEY (id);


--
-- Name: status_audit_auditor status_audit_auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditor
    ADD CONSTRAINT status_audit_auditor_pkey PRIMARY KEY (id);


--
-- Name: status_laporan status_laporan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_laporan
    ADD CONSTRAINT status_laporan_pkey PRIMARY KEY (id);


--
-- Name: status_ptk_auditee status_ptk_auditee_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_ptk_auditee
    ADD CONSTRAINT status_ptk_auditee_pkey PRIMARY KEY (id);


--
-- Name: status_ptk_auditor status_ptk_auditor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_ptk_auditor
    ADD CONSTRAINT status_ptk_auditor_pkey PRIMARY KEY (id);


--
-- Name: submenu submenu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.submenu
    ADD CONSTRAINT submenu_pkey PRIMARY KEY (id);


--
-- Name: unit unit_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.unit
    ADD CONSTRAINT unit_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: assessment_form assessment_form_assessment_pertanyaan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_form
    ADD CONSTRAINT assessment_form_assessment_pertanyaan_id_foreign FOREIGN KEY (assessment_pertanyaan_id) REFERENCES public.assessment_pertanyaan(id) ON DELETE CASCADE;


--
-- Name: assessment_form assessment_form_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_form
    ADD CONSTRAINT assessment_form_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_assessment_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_assessment_form_id_foreign FOREIGN KEY (assessment_form_id) REFERENCES public.assessment_form(id) ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_auditor_dinilai_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_auditor_dinilai_id_foreign FOREIGN KEY (auditor_dinilai_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_auditor_penilai_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_auditor_penilai_id_foreign FOREIGN KEY (auditor_penilai_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: assessment_jawaban assessment_jawaban_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.assessment_jawaban
    ADD CONSTRAINT assessment_jawaban_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee_auditor auditee_auditor_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee_auditor
    ADD CONSTRAINT auditee_auditor_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee_auditor auditee_auditor_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee_auditor
    ADD CONSTRAINT auditee_auditor_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee_auditor auditee_auditor_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee_auditor
    ADD CONSTRAINT auditee_auditor_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee_auditor auditee_auditor_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee_auditor
    ADD CONSTRAINT auditee_auditor_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee_auditor auditee_auditor_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee_auditor
    ADD CONSTRAINT auditee_auditor_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee_auditor auditee_auditor_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee_auditor
    ADD CONSTRAINT auditee_auditor_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee auditee_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee auditee_jabatan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_jabatan_id_foreign FOREIGN KEY (jabatan_id) REFERENCES public.jabatan(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee auditee_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee auditee_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee auditee_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditee auditee_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditee
    ADD CONSTRAINT auditee_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditor auditor_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditor
    ADD CONSTRAINT auditor_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auditor auditor_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.auditor
    ADD CONSTRAINT auditor_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: ayat ayat_pasal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ayat
    ADD CONSTRAINT ayat_pasal_id_foreign FOREIGN KEY (pasal_id) REFERENCES public.pasal(id) ON DELETE CASCADE;


--
-- Name: ayat ayat_peraturan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ayat
    ADD CONSTRAINT ayat_peraturan_id_foreign FOREIGN KEY (peraturan_id) REFERENCES public.peraturan(id) ON DELETE CASCADE;


--
-- Name: berita_acara_auditee berita_acara_auditee_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditee
    ADD CONSTRAINT berita_acara_auditee_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: berita_acara_auditee berita_acara_auditee_berita_acara_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditee
    ADD CONSTRAINT berita_acara_auditee_berita_acara_id_foreign FOREIGN KEY (berita_acara_id) REFERENCES public.berita_acara(id) ON DELETE CASCADE;


--
-- Name: berita_acara_auditor berita_acara_auditor_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditor
    ADD CONSTRAINT berita_acara_auditor_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: berita_acara_auditor berita_acara_auditor_berita_acara_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara_auditor
    ADD CONSTRAINT berita_acara_auditor_berita_acara_id_foreign FOREIGN KEY (berita_acara_id) REFERENCES public.berita_acara(id) ON DELETE CASCADE;


--
-- Name: berita_acara berita_acara_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara
    ADD CONSTRAINT berita_acara_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: berita_acara berita_acara_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara
    ADD CONSTRAINT berita_acara_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: berita_acara berita_acara_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara
    ADD CONSTRAINT berita_acara_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: berita_acara berita_acara_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.berita_acara
    ADD CONSTRAINT berita_acara_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: form form_instrumen_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.form
    ADD CONSTRAINT form_instrumen_id_foreign FOREIGN KEY (instrumen_id) REFERENCES public.instrumen(id) ON DELETE CASCADE;


--
-- Name: form form_jadwal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.form
    ADD CONSTRAINT form_jadwal_id_foreign FOREIGN KEY (jadwal_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: instrumen_jabatan instrumen_jabatan_instrumen_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_jabatan
    ADD CONSTRAINT instrumen_jabatan_instrumen_id_foreign FOREIGN KEY (instrumen_id) REFERENCES public.instrumen(id) ON DELETE CASCADE;


--
-- Name: instrumen_jabatan instrumen_jabatan_jabatan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_jabatan
    ADD CONSTRAINT instrumen_jabatan_jabatan_id_foreign FOREIGN KEY (jabatan_id) REFERENCES public.jabatan(id) ON DELETE CASCADE;


--
-- Name: instrumen instrumen_jenis_pertanyaan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen
    ADD CONSTRAINT instrumen_jenis_pertanyaan_id_foreign FOREIGN KEY (jenis_pertanyaan_id) REFERENCES public.jenis_pertanyaan(id) ON DELETE CASCADE;


--
-- Name: instrumen_jenjang instrumen_jenjang_instrumen_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_jenjang
    ADD CONSTRAINT instrumen_jenjang_instrumen_id_foreign FOREIGN KEY (instrumen_id) REFERENCES public.instrumen(id) ON DELETE CASCADE;


--
-- Name: instrumen_jenjang instrumen_jenjang_jenjang_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_jenjang
    ADD CONSTRAINT instrumen_jenjang_jenjang_id_foreign FOREIGN KEY (jenjang_id) REFERENCES public.jenjang(id) ON DELETE CASCADE;


--
-- Name: instrumen_jenjang instrumen_jenjang_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_jenjang
    ADD CONSTRAINT instrumen_jenjang_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: instrumen_jenjang instrumen_jenjang_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_jenjang
    ADD CONSTRAINT instrumen_jenjang_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: instrumen instrumen_kategori_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen
    ADD CONSTRAINT instrumen_kategori_id_foreign FOREIGN KEY (kategori_id) REFERENCES public.kategori(id) ON DELETE CASCADE;


--
-- Name: instrumen_kriteria instrumen_kriteria_instrumen_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_kriteria
    ADD CONSTRAINT instrumen_kriteria_instrumen_id_foreign FOREIGN KEY (instrumen_id) REFERENCES public.instrumen(id) ON DELETE CASCADE;


--
-- Name: instrumen_kriteria instrumen_kriteria_kriteria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_kriteria
    ADD CONSTRAINT instrumen_kriteria_kriteria_id_foreign FOREIGN KEY (kriteria_id) REFERENCES public.kriteria(id) ON DELETE CASCADE;


--
-- Name: instrumen instrumen_level_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen
    ADD CONSTRAINT instrumen_level_id_foreign FOREIGN KEY (level_id) REFERENCES public.level(id) ON DELETE CASCADE;


--
-- Name: instrumen_pasal_ayat instrumen_pasal_ayat_ayat_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_pasal_ayat
    ADD CONSTRAINT instrumen_pasal_ayat_ayat_id_foreign FOREIGN KEY (ayat_id) REFERENCES public.ayat(id) ON DELETE CASCADE;


--
-- Name: instrumen_pasal_ayat instrumen_pasal_ayat_instrumen_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_pasal_ayat
    ADD CONSTRAINT instrumen_pasal_ayat_instrumen_id_foreign FOREIGN KEY (instrumen_id) REFERENCES public.instrumen(id) ON DELETE CASCADE;


--
-- Name: instrumen_pasal_ayat instrumen_pasal_ayat_pasal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen_pasal_ayat
    ADD CONSTRAINT instrumen_pasal_ayat_pasal_id_foreign FOREIGN KEY (pasal_id) REFERENCES public.pasal(id) ON DELETE CASCADE;


--
-- Name: instrumen instrumen_peraturan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen
    ADD CONSTRAINT instrumen_peraturan_id_foreign FOREIGN KEY (peraturan_id) REFERENCES public.peraturan(id) ON DELETE CASCADE;


--
-- Name: instrumen instrumen_standar_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.instrumen
    ADD CONSTRAINT instrumen_standar_id_foreign FOREIGN KEY (standar_id) REFERENCES public.standar(id) ON DELETE CASCADE;


--
-- Name: jabatan_unit jabatan_unit_jabatan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_unit
    ADD CONSTRAINT jabatan_unit_jabatan_id_foreign FOREIGN KEY (jabatan_id) REFERENCES public.jabatan(id) ON DELETE CASCADE;


--
-- Name: jabatan_unit jabatan_unit_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_unit
    ADD CONSTRAINT jabatan_unit_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: jabatan_user jabatan_user_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_user
    ADD CONSTRAINT jabatan_user_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: jabatan_user jabatan_user_jabatan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_user
    ADD CONSTRAINT jabatan_user_jabatan_id_foreign FOREIGN KEY (jabatan_id) REFERENCES public.jabatan(id) ON DELETE CASCADE;


--
-- Name: jabatan_user jabatan_user_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_user
    ADD CONSTRAINT jabatan_user_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: jabatan_user jabatan_user_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_user
    ADD CONSTRAINT jabatan_user_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: jabatan_user jabatan_user_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jabatan_user
    ADD CONSTRAINT jabatan_user_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditee jawaban_auditee_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditee jawaban_auditee_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditee jawaban_auditee_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditee jawaban_auditee_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditee jawaban_auditee_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditee jawaban_auditee_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditee
    ADD CONSTRAINT jawaban_auditee_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_kriteria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_kriteria_id_foreign FOREIGN KEY (kriteria_id) REFERENCES public.kriteria(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: jawaban_auditor jawaban_auditor_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jawaban_auditor
    ADD CONSTRAINT jawaban_auditor_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: kategori kategori_standar_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.kategori
    ADD CONSTRAINT kategori_standar_id_foreign FOREIGN KEY (standar_id) REFERENCES public.standar(id) ON DELETE CASCADE;


--
-- Name: laporan_auditee laporan_auditee_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditee
    ADD CONSTRAINT laporan_auditee_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: laporan_auditee laporan_auditee_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditee
    ADD CONSTRAINT laporan_auditee_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan_auditor laporan_auditor_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditor
    ADD CONSTRAINT laporan_auditor_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: laporan_auditor laporan_auditor_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_auditor
    ADD CONSTRAINT laporan_auditor_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan laporan_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: laporan_form laporan_form_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_form
    ADD CONSTRAINT laporan_form_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: laporan_form laporan_form_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_form
    ADD CONSTRAINT laporan_form_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: laporan_form laporan_form_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan_form
    ADD CONSTRAINT laporan_form_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: laporan laporan_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: laporan laporan_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: laporan laporan_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.laporan
    ADD CONSTRAINT laporan_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: link link_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: link link_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: link link_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: link link_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: link link_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: link link_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.link
    ADD CONSTRAINT link_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: notifikasi notifikasi_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifikasi
    ADD CONSTRAINT notifikasi_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: pasal pasal_peraturan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pasal
    ADD CONSTRAINT pasal_peraturan_id_foreign FOREIGN KEY (peraturan_id) REFERENCES public.peraturan(id) ON DELETE CASCADE;


--
-- Name: prodi prodi_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prodi
    ADD CONSTRAINT prodi_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: prodi prodi_jenjang_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.prodi
    ADD CONSTRAINT prodi_jenjang_id_foreign FOREIGN KEY (jenjang_id) REFERENCES public.jenjang(id) ON DELETE CASCADE;


--
-- Name: ptk_auditee ptk_auditee_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditee
    ADD CONSTRAINT ptk_auditee_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: ptk_auditee ptk_auditee_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditee
    ADD CONSTRAINT ptk_auditee_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: ptk_auditor ptk_auditor_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditor
    ADD CONSTRAINT ptk_auditor_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: ptk_auditor ptk_auditor_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_auditor
    ADD CONSTRAINT ptk_auditor_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: ptk ptk_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk
    ADD CONSTRAINT ptk_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON DELETE CASCADE;


--
-- Name: ptk_form ptk_form_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form
    ADD CONSTRAINT ptk_form_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: ptk_form ptk_form_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form
    ADD CONSTRAINT ptk_form_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: ptk_form_deskripsi ptk_form_deskripsi_auditor_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_deskripsi
    ADD CONSTRAINT ptk_form_deskripsi_auditor_id_foreign FOREIGN KEY (auditor_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: ptk_form_deskripsi ptk_form_deskripsi_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_deskripsi
    ADD CONSTRAINT ptk_form_deskripsi_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: ptk_form_deskripsi ptk_form_deskripsi_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_deskripsi
    ADD CONSTRAINT ptk_form_deskripsi_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: ptk_form ptk_form_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form
    ADD CONSTRAINT ptk_form_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: ptk_form ptk_form_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form
    ADD CONSTRAINT ptk_form_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: ptk_form_rencana ptk_form_rencana_auditee_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_rencana
    ADD CONSTRAINT ptk_form_rencana_auditee_id_foreign FOREIGN KEY (auditee_id) REFERENCES public.auditee(id) ON DELETE CASCADE;


--
-- Name: ptk_form_rencana ptk_form_rencana_form_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_rencana
    ADD CONSTRAINT ptk_form_rencana_form_id_foreign FOREIGN KEY (form_id) REFERENCES public.form(id) ON DELETE CASCADE;


--
-- Name: ptk_form_rencana ptk_form_rencana_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk_form_rencana
    ADD CONSTRAINT ptk_form_rencana_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: ptk ptk_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk
    ADD CONSTRAINT ptk_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: ptk ptk_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk
    ADD CONSTRAINT ptk_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON DELETE CASCADE;


--
-- Name: ptk ptk_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ptk
    ADD CONSTRAINT ptk_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_menu role_menu_menu_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_menu
    ADD CONSTRAINT role_menu_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES public.menu(id) ON DELETE CASCADE;


--
-- Name: role_menu role_menu_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_menu
    ADD CONSTRAINT role_menu_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_submenu role_submenu_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_submenu
    ADD CONSTRAINT role_submenu_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_submenu role_submenu_submenu_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_submenu
    ADD CONSTRAINT role_submenu_submenu_id_foreign FOREIGN KEY (submenu_id) REFERENCES public.submenu(id) ON DELETE CASCADE;


--
-- Name: setting setting_jabatan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.setting
    ADD CONSTRAINT setting_jabatan_id_foreign FOREIGN KEY (jabatan_id) REFERENCES public.jabatan(id) ON DELETE CASCADE;


--
-- Name: standar standar_peraturan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.standar
    ADD CONSTRAINT standar_peraturan_id_foreign FOREIGN KEY (peraturan_id) REFERENCES public.peraturan(id) ON DELETE CASCADE;


--
-- Name: status_assessment status_assessment_auditor_dinilai_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_auditor_dinilai_id_foreign FOREIGN KEY (auditor_dinilai_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: status_assessment status_assessment_auditor_penilai_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_auditor_penilai_id_foreign FOREIGN KEY (auditor_penilai_id) REFERENCES public.auditor(id) ON DELETE CASCADE;


--
-- Name: status_assessment status_assessment_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_assessment status_assessment_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: status_assessment status_assessment_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_assessment status_assessment_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_assessment
    ADD CONSTRAINT status_assessment_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_audit_auditee status_audit_auditee_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditee
    ADD CONSTRAINT status_audit_auditee_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_audit_auditee status_audit_auditee_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditee
    ADD CONSTRAINT status_audit_auditee_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: status_audit_auditee status_audit_auditee_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditee
    ADD CONSTRAINT status_audit_auditee_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_audit_auditee status_audit_auditee_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditee
    ADD CONSTRAINT status_audit_auditee_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_audit_auditor status_audit_auditor_fakultas_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditor
    ADD CONSTRAINT status_audit_auditor_fakultas_id_foreign FOREIGN KEY (fakultas_id) REFERENCES public.fakultas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_audit_auditor status_audit_auditor_jadwal_audit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditor
    ADD CONSTRAINT status_audit_auditor_jadwal_audit_id_foreign FOREIGN KEY (jadwal_audit_id) REFERENCES public.jadwal_audit(id) ON DELETE CASCADE;


--
-- Name: status_audit_auditor status_audit_auditor_prodi_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditor
    ADD CONSTRAINT status_audit_auditor_prodi_id_foreign FOREIGN KEY (prodi_id) REFERENCES public.prodi(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_audit_auditor status_audit_auditor_unit_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_audit_auditor
    ADD CONSTRAINT status_audit_auditor_unit_id_foreign FOREIGN KEY (unit_id) REFERENCES public.unit(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: status_laporan status_laporan_laporan_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_laporan
    ADD CONSTRAINT status_laporan_laporan_id_foreign FOREIGN KEY (laporan_id) REFERENCES public.laporan(id) ON DELETE CASCADE;


--
-- Name: status_ptk_auditee status_ptk_auditee_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_ptk_auditee
    ADD CONSTRAINT status_ptk_auditee_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: status_ptk_auditor status_ptk_auditor_ptk_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status_ptk_auditor
    ADD CONSTRAINT status_ptk_auditor_ptk_id_foreign FOREIGN KEY (ptk_id) REFERENCES public.ptk(id) ON DELETE CASCADE;


--
-- Name: submenu submenu_menu_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.submenu
    ADD CONSTRAINT submenu_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES public.menu(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

