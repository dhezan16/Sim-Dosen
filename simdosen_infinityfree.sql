-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: sim_dosen_db
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `dosen`
--

DROP TABLE IF EXISTS `dosen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nomor_registrasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nuptk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nidn` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gelar_depan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gelar_belakang` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `bidang_keahlian` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tmt_kerja` date DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_studi_id` bigint unsigned NOT NULL,
  `jabatan_fungsional_id` bigint unsigned NOT NULL,
  `kepangkatan_id` bigint unsigned NOT NULL,
  `pendidikan_id` bigint unsigned NOT NULL,
  `rumpun_ilmu_id` bigint unsigned NOT NULL,
  `status_serdos_id` bigint unsigned NOT NULL,
  `status_pegawai_id` bigint unsigned NOT NULL,
  `ikatan_kerja_id` bigint unsigned NOT NULL,
  `status_aktivitas_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dosen_nomor_registrasi_unique` (`nomor_registrasi`),
  UNIQUE KEY `dosen_nuptk_unique` (`nuptk`),
  UNIQUE KEY `dosen_nip_unique` (`nip`),
  UNIQUE KEY `dosen_nidn_unique` (`nidn`),
  UNIQUE KEY `dosen_email_unique` (`email`),
  KEY `dosen_program_studi_id_foreign` (`program_studi_id`),
  KEY `dosen_jabatan_fungsional_id_foreign` (`jabatan_fungsional_id`),
  KEY `dosen_kepangkatan_id_foreign` (`kepangkatan_id`),
  KEY `dosen_pendidikan_id_foreign` (`pendidikan_id`),
  KEY `dosen_rumpun_ilmu_id_foreign` (`rumpun_ilmu_id`),
  KEY `dosen_status_serdos_id_foreign` (`status_serdos_id`),
  KEY `dosen_status_pegawai_id_foreign` (`status_pegawai_id`),
  KEY `dosen_ikatan_kerja_id_foreign` (`ikatan_kerja_id`),
  KEY `dosen_status_aktivitas_id_foreign` (`status_aktivitas_id`),
  CONSTRAINT `dosen_ikatan_kerja_id_foreign` FOREIGN KEY (`ikatan_kerja_id`) REFERENCES `ikatan_kerja` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_jabatan_fungsional_id_foreign` FOREIGN KEY (`jabatan_fungsional_id`) REFERENCES `jabatan_fungsional` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_kepangkatan_id_foreign` FOREIGN KEY (`kepangkatan_id`) REFERENCES `kepangkatan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_pendidikan_id_foreign` FOREIGN KEY (`pendidikan_id`) REFERENCES `pendidikan` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_rumpun_ilmu_id_foreign` FOREIGN KEY (`rumpun_ilmu_id`) REFERENCES `rumpun_ilmu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_status_aktivitas_id_foreign` FOREIGN KEY (`status_aktivitas_id`) REFERENCES `status_aktivitas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_status_pegawai_id_foreign` FOREIGN KEY (`status_pegawai_id`) REFERENCES `status_pegawai` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `dosen_status_serdos_id_foreign` FOREIGN KEY (`status_serdos_id`) REFERENCES `status_serdos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosen`
--

/*!40000 ALTER TABLE `dosen` DISABLE KEYS */;
INSERT INTO `dosen` VALUES (1,'REG-003','1234567890123456',NULL,'Budi Santoso',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'198501012010011001',1,1,1,1,1,1,1,1,1,'2026-10-13 06:11:15','2026-10-17 00:09:26'),(3,'REG-002','2974623870462',NULL,'Abdul Rahman',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'19203865756190',1,1,1,1,1,1,1,1,1,'2026-10-17 00:25:04','2026-10-17 00:25:04'),(4,'REG-007','1345675645678978','1234567834','Bahlil',NULL,'M.kom','L','Jakarta','1977-01-19','bahlil@gmail.com','085246251349','jakarta selatan','Game designer','2026-11-09',NULL,'2345676565453456',1,2,1,1,2,2,1,1,1,'2026-11-08 21:21:19','2026-11-08 21:21:19');
/*!40000 ALTER TABLE `dosen` ENABLE KEYS */;

--
-- Table structure for table `fakultas`
--

DROP TABLE IF EXISTS `fakultas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fakultas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_fakultas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `universitas_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fakultas_nama_fakultas_unique` (`nama_fakultas`),
  KEY `fakultas_universitas_id_foreign` (`universitas_id`),
  CONSTRAINT `fakultas_universitas_id_foreign` FOREIGN KEY (`universitas_id`) REFERENCES `universitas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fakultas`
--

/*!40000 ALTER TABLE `fakultas` DISABLE KEYS */;
INSERT INTO `fakultas` VALUES (1,'Fakultas Teknik','2026-10-13 01:52:36','2026-10-13 01:52:36',1),(2,'Fakultas Hukum','2026-11-08 21:21:41','2026-11-08 21:21:41',1);
/*!40000 ALTER TABLE `fakultas` ENABLE KEYS */;

--
-- Table structure for table `ikatan_kerja`
--

DROP TABLE IF EXISTS `ikatan_kerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ikatan_kerja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_ikatan_kerja` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ikatan_kerja_nama_ikatan_kerja_unique` (`nama_ikatan_kerja`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ikatan_kerja`
--

/*!40000 ALTER TABLE `ikatan_kerja` DISABLE KEYS */;
INSERT INTO `ikatan_kerja` VALUES (1,'Dosen Tetap','2026-10-13 02:49:23','2026-10-13 02:49:23'),(2,'Dosen Tidak Tetap','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'Dosen Luar Biasa','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Dosen Tamu','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `ikatan_kerja` ENABLE KEYS */;

--
-- Table structure for table `jabatan_fungsional`
--

DROP TABLE IF EXISTS `jabatan_fungsional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jabatan_fungsional` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jabatan_fungsional_nama_jabatan_unique` (`nama_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jabatan_fungsional`
--

/*!40000 ALTER TABLE `jabatan_fungsional` DISABLE KEYS */;
INSERT INTO `jabatan_fungsional` VALUES (1,'Asisten Ahli','2026-10-13 02:46:10','2026-10-13 02:46:10'),(2,'Tenaga Pengajar','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'Lektor','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Lektor Kepala','2026-11-08 20:56:05','2026-11-08 20:56:05'),(5,'Profesor','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `jabatan_fungsional` ENABLE KEYS */;

--
-- Table structure for table `kepangkatan`
--

DROP TABLE IF EXISTS `kepangkatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kepangkatan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kepangkatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kepangkatan_kepangkatan_unique` (`kepangkatan`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kepangkatan`
--

/*!40000 ALTER TABLE `kepangkatan` DISABLE KEYS */;
INSERT INTO `kepangkatan` VALUES (1,'Penata Muda Tingkat I','2026-10-13 02:48:12','2026-10-13 02:48:12'),(2,'Penata Muda (III/a)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'Penata Muda Tingkat I (III/b)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Penata (III/c)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(5,'Penata Tingkat I (III/d)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(6,'Pembina (IV/a)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(7,'Pembina Tingkat I (IV/b)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(8,'Pembina Utama Muda (IV/c)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(9,'Pembina Utama Madya (IV/d)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(10,'Pembina Utama (IV/e)','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `kepangkatan` ENABLE KEYS */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_12_040318_create_fakultas_table',2),(5,'2026_10_12_041835_create_program_studis_table',3),(6,'2026_10_12_043552_create_jabatan_fungsionals_table',4),(7,'2026_10_12_074745_create_kepangkatans_table',5),(8,'2026_10_12_075759_create_pendidikans_table',6),(9,'2026_10_12_135332_create_rumpun_ilmus_table',7),(10,'2026_10_12_135601_create_status_serdos_table',8),(11,'2026_10_12_135809_create_status_pegawais_table',9),(12,'2026_10_12_135954_create_ikatan_kerjas_table',10),(13,'2026_10_12_140143_create_status_aktivitas_table',11),(14,'2026_10_13_074036_create_dosens_table',12),(15,'2026_11_03_050834_create_universitas_table',13),(16,'2026_11_03_051820_add_universitas_id_to_fakultas_table',14),(17,'2026_11_05_070154_add_universitas_id_to_program_studi_table',15),(18,'2026_11_05_070612_add_program_studi_status',16),(19,'2026_11_05_075125_add_program_studi_kode',17),(20,'2026_11_09_000001_add_biodata_to_dosen_table',18),(21,'2026_11_09_000002_create_riwayat_pendidikan_table',18),(22,'2026_11_09_000003_create_penelitian_table',18),(23,'2026_11_09_000004_create_pengabdian_table',18),(24,'2026_11_09_000005_create_publikasi_table',18),(25,'2026_11_09_000006_add_role_to_users_table',19);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

--
-- Table structure for table `pendidikan`
--

DROP TABLE IF EXISTS `pendidikan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pendidikan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_pendidikan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pendidikan_nama_pendidikan_unique` (`nama_pendidikan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pendidikan`
--

/*!40000 ALTER TABLE `pendidikan` DISABLE KEYS */;
INSERT INTO `pendidikan` VALUES (1,'S2','2026-10-13 02:48:31','2026-10-13 02:48:31'),(2,'S1','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'S3','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Profesi','2026-11-08 20:56:05','2026-11-08 20:56:05'),(5,'Spesialis','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `pendidikan` ENABLE KEYS */;

--
-- Table structure for table `penelitian`
--

DROP TABLE IF EXISTS `penelitian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `penelitian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `judul` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `skema` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_dana` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_dana` bigint unsigned DEFAULT NULL,
  `peran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ketua',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penelitian_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `penelitian_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `penelitian`
--

/*!40000 ALTER TABLE `penelitian` DISABLE KEYS */;
/*!40000 ALTER TABLE `penelitian` ENABLE KEYS */;

--
-- Table structure for table `pengabdian`
--

DROP TABLE IF EXISTS `pengabdian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengabdian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `judul` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber_dana` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah_dana` bigint unsigned DEFAULT NULL,
  `peran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ketua',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengabdian_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `pengabdian_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengabdian`
--

/*!40000 ALTER TABLE `pengabdian` DISABLE KEYS */;
/*!40000 ALTER TABLE `pengabdian` ENABLE KEYS */;

--
-- Table structure for table `program_studi`
--

DROP TABLE IF EXISTS `program_studi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `program_studi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_program_studi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakultas_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenjang` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `program_studi_fakultas_id_foreign` (`fakultas_id`),
  CONSTRAINT `program_studi_fakultas_id_foreign` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `program_studi`
--

/*!40000 ALTER TABLE `program_studi` DISABLE KEYS */;
INSERT INTO `program_studi` VALUES (1,'Teknik Informatika',1,'2026-10-13 01:52:59','2026-10-13 01:52:59','S1','Aktif','TI'),(2,'Hukum',2,'2026-11-08 21:22:28','2026-11-08 21:22:28','S1','Aktif','HK');
/*!40000 ALTER TABLE `program_studi` ENABLE KEYS */;

--
-- Table structure for table `publikasi`
--

DROP TABLE IF EXISTS `publikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publikasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `judul` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` year NOT NULL,
  `tautan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `publikasi_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `publikasi_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publikasi`
--

/*!40000 ALTER TABLE `publikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `publikasi` ENABLE KEYS */;

--
-- Table structure for table `riwayat_pendidikan`
--

DROP TABLE IF EXISTS `riwayat_pendidikan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_pendidikan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dosen_id` bigint unsigned NOT NULL,
  `jenjang` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_institusi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_studi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_lulus` year DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `riwayat_pendidikan_dosen_id_foreign` (`dosen_id`),
  CONSTRAINT `riwayat_pendidikan_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riwayat_pendidikan`
--

/*!40000 ALTER TABLE `riwayat_pendidikan` DISABLE KEYS */;
/*!40000 ALTER TABLE `riwayat_pendidikan` ENABLE KEYS */;

--
-- Table structure for table `rumpun_ilmu`
--

DROP TABLE IF EXISTS `rumpun_ilmu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rumpun_ilmu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_rumpun_ilmu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rumpun_ilmu_nama_rumpun_ilmu_unique` (`nama_rumpun_ilmu`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rumpun_ilmu`
--

/*!40000 ALTER TABLE `rumpun_ilmu` DISABLE KEYS */;
INSERT INTO `rumpun_ilmu` VALUES (1,'Ilmu Komputer','2026-10-13 02:48:47','2026-10-13 02:48:47'),(2,'Teknik','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'Ilmu Ekonomi','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Ilmu Pendidikan','2026-11-08 20:56:05','2026-11-08 20:56:05'),(5,'Ilmu Hukum','2026-11-08 20:56:05','2026-11-08 20:56:05'),(6,'Ilmu Kesehatan','2026-11-08 20:56:05','2026-11-08 20:56:05'),(7,'Ilmu Sosial','2026-11-08 20:56:05','2026-11-08 20:56:05'),(8,'Ilmu Agama','2026-11-08 20:56:05','2026-11-08 20:56:05'),(9,'Matematika dan IPA','2026-11-08 20:56:05','2026-11-08 20:56:05'),(10,'Pertanian','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `rumpun_ilmu` ENABLE KEYS */;

--
-- Table structure for table `status_aktivitas`
--

DROP TABLE IF EXISTS `status_aktivitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_aktivitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_status_aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_aktivitas_nama_status_aktivitas_unique` (`nama_status_aktivitas`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_aktivitas`
--

/*!40000 ALTER TABLE `status_aktivitas` DISABLE KEYS */;
INSERT INTO `status_aktivitas` VALUES (1,'Aktif','2026-10-13 02:49:32','2026-10-13 02:49:32'),(2,'Tugas Belajar','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'Izin Belajar','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Cuti','2026-11-08 20:56:05','2026-11-08 20:56:05'),(5,'Studi Lanjut','2026-11-08 20:56:05','2026-11-08 20:56:05'),(6,'Pensiun','2026-11-08 20:56:05','2026-11-08 20:56:05'),(7,'Keluar','2026-11-08 20:56:05','2026-11-08 20:56:05'),(8,'Almarhum','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `status_aktivitas` ENABLE KEYS */;

--
-- Table structure for table `status_pegawai`
--

DROP TABLE IF EXISTS `status_pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_status_pegawai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_pegawai_nama_status_pegawai_unique` (`nama_status_pegawai`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_pegawai`
--

/*!40000 ALTER TABLE `status_pegawai` DISABLE KEYS */;
INSERT INTO `status_pegawai` VALUES (1,'Aktif','2026-10-13 02:49:12','2026-10-13 02:49:12'),(2,'PNS','2026-11-08 20:56:05','2026-11-08 20:56:05'),(3,'PNS Dipekerjakan (DPK)','2026-11-08 20:56:05','2026-11-08 20:56:05'),(4,'Tetap Yayasan','2026-11-08 20:56:05','2026-11-08 20:56:05'),(5,'Kontrak','2026-11-08 20:56:05','2026-11-08 20:56:05'),(6,'Honorer','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `status_pegawai` ENABLE KEYS */;

--
-- Table structure for table `status_serdos`
--

DROP TABLE IF EXISTS `status_serdos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_serdos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_status_serdos` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status_serdos_nama_status_serdos_unique` (`nama_status_serdos`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_serdos`
--

/*!40000 ALTER TABLE `status_serdos` DISABLE KEYS */;
INSERT INTO `status_serdos` VALUES (1,'Belum Sertifikasi','2026-10-13 02:49:02','2026-10-13 02:49:02'),(2,'Sudah Sertifikasi','2026-11-08 20:56:05','2026-11-08 20:56:05');
/*!40000 ALTER TABLE `status_serdos` ENABLE KEYS */;

--
-- Table structure for table `universitas`
--

DROP TABLE IF EXISTS `universitas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `universitas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_universitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `universitas_nama_universitas_unique` (`nama_universitas`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `universitas`
--

/*!40000 ALTER TABLE `universitas` DISABLE KEYS */;
INSERT INTO `universitas` VALUES (1,'Universitas Muhammadiyah Bengkulu',NULL,'2026-11-08 21:28:13');
/*!40000 ALTER TABLE `universitas` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dosen',
  `program_studi_id` bigint unsigned DEFAULT NULL,
  `dosen_id` bigint unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_dosen_id_unique` (`dosen_id`),
  KEY `users_program_studi_id_foreign` (`program_studi_id`),
  CONSTRAINT `users_dosen_id_foreign` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_program_studi_id_foreign` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','admin@umb.ac.id',NULL,'$2y$12$hAZdQpKMBT73Op2QzmbW7ekPN8Zg5lfLLw4706NyPxfjYEfJAQsda','admin',NULL,NULL,NULL,'2026-11-08 20:56:05','2026-11-08 20:56:05'),(2,'Operator teknik informatika','teknikinformatika@gmail.com',NULL,'$2y$12$EAyMjWI1Sc8D1ZORuTjXwe92Cyx.0aFse9JCxzdDHBzSh32Z1sah6','operator',1,NULL,NULL,'2026-11-08 22:06:54','2026-11-08 22:06:54'),(3,'Abdul Rahman','abdul@gmail.com',NULL,'$2y$12$8kkZJ/yVeqHto9OEO3W89uW.ME94bqf2kQ091eBmHo.4SBBstfQ9W','dosen',NULL,3,NULL,'2026-11-08 22:08:27','2026-11-08 22:08:27');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-09 17:59:57
-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: sim_dosen_db
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-09 17:59:57
