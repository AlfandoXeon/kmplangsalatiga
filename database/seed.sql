-- ============================================================================
-- Database Seeder: db_kmplang
-- Data Awal Resmi Website K'mplang Salatiga
-- ============================================================================

USE `db_kmplang`;

-- 1. Seeder Admin, Pengurus & Anggota Awal
INSERT INTO `users` (`id`, `nama_lengkap`, `nim`, `email`, `password_hash`, `role`, `jabatan_organisasi`, `asal_daerah`, `fakultas`, `angkatan`, `no_whatsapp_encrypted`, `tanggal_lahir`, `motivasi`, `status`) VALUES
(1, 'Grateo Alfando Atmojo', '672023001', 'admin@kmplang.org', '$2y$10$sv1jt/ORE6oS4GnuFyOCA.tLmiFzFNx1LE6G9Ud0jiU40Un0Wpks.', 'admin', 'Administrator Sistem', 'Lampung Timur', 'Fakultas Teknologi Informasi (FTI)', '2023', '', '2004-05-15', 'Membangun sistem informasi modern dan memajukan organisasi K\'mplang Salatiga.', 'active'),
(2, 'Yohanes Pengurus', '672023002', 'yohanes@kmplang.org', '$2y$10$sv1jt/ORE6oS4GnuFyOCA.tLmiFzFNx1LE6G9Ud0jiU40Un0Wpks.', 'pengurus', 'Badan Pengurus Harian (BPH)', 'Bandar Lampung', 'Fakultas Ekonomika dan Bisnis (FEB)', '2023', '', '2004-08-20', 'Mengabdi untuk mahasiswa perantau Lampung di Kota Salatiga.', 'active'),
(3, 'Eben Mahasiswa', '672024011', 'eben@kmplang.org', '$2y$10$sv1jt/ORE6oS4GnuFyOCA.tLmiFzFNx1LE6G9Ud0jiU40Un0Wpks.', 'anggota', 'Koordinator Rekrutmen', 'Metro Lampung', 'Fakultas Hukum', '2024', '', '2005-01-10', 'Menjalin silaturahmi erat dengan sesama perantau Sang Bumi Ruwa Jurai.', 'active')
ON DUPLICATE KEY UPDATE
    `jabatan_organisasi` = VALUES(`jabatan_organisasi`),
    `angkatan` = VALUES(`angkatan`);

-- 2. Seeder Site Settings (CMS Landing Page)
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `group_name`) VALUES
('hero_title', 'RUMAH PERANTAU LAMPUNG', 'hero'),
('hero_subtitle', 'Di Kota Salatiga', 'hero'),
('hero_description', 'Menjadi keluarga di tanah rantau, menjunjung tinggi adat budaya Sang Bumi Ruwa Jurai, dan merajut prestasi akademik maupun organisasi bersama.', 'hero'),
('about_title', 'Lebih dari Sekadar Komunitas Perantau', 'about'),
('about_content_1', 'K\'mplang Salatiga adalah wadah resmi bagi mahasiswa perantau asal Provinsi Lampung yang menempuh pendidikan tinggi di Kota Salatiga. Kami hadir bukan sekadar sebagai perkumpulan daerah, melainkan sebuah keluarga yang saling menguatkan, berbagi ruang bertumbuh, dan menjaga solidaritas di tanah rantau.', 'about'),
('about_content_2', 'Nama "K\'mplang" (Keluarga Mahasiswa Perantauan Lampung) terinspirasi dari panganan khas tanah Lampung, Kemplang, yang selalu menghadirkan rasa hangat dan kebersamaan di setiap suasana. Semangat itulah yang kami rawat lintas angkatan dan lintas perguruan tinggi di Salatiga.', 'about'),
('sambutan_ketua', 'Tabik Pun! Selamat datang di portal resmi K\'mplang Salatiga. Sistem informasi ini dibangun untuk mewujudkan tata kelola organisasi yang transparan, terstruktur, serta menjadi ruang publikasi karya, budaya, dan dokumentasi seluruh keluarga besar mahasiswa Lampung di Kota Salatiga.', 'greeting'),
('nama_ketua', 'Ketua Umum K\'mplang Periode 2026/2027', 'greeting'),
('social_instagram', 'https://www.instagram.com/k_mplangsalatiga', 'contact'),
('social_tiktok', 'https://www.tiktok.com/@kmplangsalatiga', 'contact'),
('contact_email', 'sekretariat@kmplang.org', 'contact'),
('contact_whatsapp', '085225216552', 'contact'),
('address', 'Salatiga, Jawa Tengah, Indonesia', 'contact')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- 3. Seeder Work Programs (Data Proker Lengkap 2026/2027 beserta Progres)
INSERT INTO `work_programs` (`id`, `divisi`, `periode`, `nama_program`, `tujuan`, `indikator_kualitas`, `indikator_kuantitas`, `gambaran_kegiatan`, `waktu_kegiatan`, `anggaran`, `penanggung_jawab`, `status`, `progres_persen`) VALUES
(1, 'BPH', '2026/2027', 'Raker (Rapat Kerja)', 'Merencanakan program kerja komprehensif selama satu periode kepengurusan', 'Semua divisi memiliki minimal 1 program kerja terukur', 'Seluruh pengurus (100%)', 'Sidang pleno pembahasan program kerja, indikator capaian, dan anggaran seluruh divisi.', '14 September 2025 pukul 13.00 WIB', 'Rp 0', 'Yohanes', 'selesai', 100),
(2, 'BPH', '2026/2027', 'Rapat Rutin Bulanan', 'Membahas progres pelaksanaan program setiap divisi secara berkala', 'Tercapainya evaluasi progres bulanan yang terdokumentasi', '80% dari pengurus (13 orang)', 'Doa pembuka, laporan progres per divisi, diskusi kendala, warna sari, dan penutup.', 'Setiap bulan minggu pertama', 'Rp 0', 'Yohanes', 'berlangsung', 65),
(3, 'BPH', '2026/2027', 'Rapat Evaluasi (Raev)', 'Mengukur keberhasilan, mengidentifikasi kelemahan, dan merumuskan perbaikan', 'Tersusunnya analisis SWOT dan laporan ketercapaian tiap divisi', '80% dari pengurus (13 orang)', 'Evaluasi tengah periode dan akhir periode secara menyeluruh.', 'Februari dan Agustus (6 bulan sekali)', 'Rp 0', 'Yohanes', 'rencana', 20),
(4, 'BPH', '2026/2027', 'Rekrutmen Mahasiswa Baru', 'Menjaring dan menyambut mahasiswa baru asal Lampung di Salatiga', 'Bertambahnya anggota aktif dan pengenalan K\'mplang sejak awal kuliah', '30 Mahasiswa Baru', 'Penyambutan melalui Welcoming Party, pendataan QR/form resmi, dan pendampingan maba.', '27 September 2025', 'Rp 0', 'Eben', 'selesai', 100),
(5, 'BPH', '2026/2027', 'Malam Keakraban (Makrab)', 'Mempererat ikatan kekeluargaan dan penanaman nilai organisasi bagi anggota', 'Anggota memahami sejarah, filosofi, dan solidaritas K\'mplang', 'Minimal 25 peserta', 'Kemah keakraban 2 hari 1 malam, sesi materi kepemimpinan, api unggun, dan pentas budaya.', '14-15 Maret 2026', 'Rp 2.500.000', 'Eka', 'rencana', 35),
(6, 'BPH', '2026/2027', 'Tertib Kas & Kontribusi Pengurus', 'Mendisiplinkan pengurus dan memperkuat kemandirian kas internal', 'Pencatatan kas bulanan tertib dan transparan', 'Seluruh pengurus', 'Iuran kas rutin Rp 10.000 setiap rapat bulanan dengan pembukuan terbuka.', 'Setiap rapat bulanan', 'Rp 0', 'Eka', 'berlangsung', 75),
(7, 'Humas', '2026/2027', 'Visitasi Orda Lampung', 'Mempererat jejaring dengan organisasi daerah Lampung di kota sekitar', 'Terbangunnya kolaborasi antar-orda mahasiswa Lampung', '50% dari pengurus', 'Kunjungan silaturahmi, diskusi tata kelola organisasi, dan pertukaran gagasan.', 'Kondisional', 'Rp 0', 'Ivona', 'rencana', 15),
(8, 'Humas', '2026/2027', 'Visitasi Etnis di UKSW', 'Membangun komunikasi harmonis dan pertukaran budaya antar etnis di UKSW', 'Terjalinnya sinergi lintas etnis dalam kegiatan kemahasiswaan', '50% dari pengurus', 'Forum silaturahmi antar etnis, sharing program kerja, dan kolaborasi festival budaya.', 'Kondisional', 'Rp 0', 'Ivona', 'rencana', 25),
(9, 'Humas', '2026/2027', 'Rekrutmen Eksternal Kampus', 'Merangkul mahasiswa perantau Lampung di perguruan tinggi se-Salatiga', 'Keterwakilan anggota dari berbagai kampus di Salatiga', 'Seluruh tim Humas', 'Sosialisasi ke UIN Salatiga, kampus sekitar, dan publikasi media sosial.', 'Kondisional', 'Rp 0', 'Humas', 'berlangsung', 60),
(10, 'Wirausaha', '2026/2027', 'USDA (Usaha Dana Kuliner Lampung)', 'Menambah kas mandiri organisasi sekaligus mengenalkan kuliner khas Lampung', 'Target pemasukan bersih tercapai secara konsisten tiap bulan', 'Seluruh pengurus', 'Pemasaran keripik pisang khas Lampung, kopi robusta Lampung, dan produk kreatif.', '2 kali setiap bulan', 'Rp 0', 'Veni', 'berlangsung', 70),
(11, 'Olahraga', '2026/2027', 'SAPU LIDI (Satu Jumat Penuh Latihan & Silaturahmi)', 'Menjaga kebugaran jasmani dan mempererat keakraban lewat olahraga rutin', 'Latihan badminton berjalan konsisten dengan partisipasi aktif', 'Minimal 50% anggota hadir', 'Latihan bulutangkis rutin setiap Jumat sore pukul 17.00-20.00 WIB.', 'Setiap hari Jumat', 'Rp 370.000 / bln', 'JOJO', 'berlangsung', 80),
(12, 'Olahraga', '2026/2027', 'GUGUS AKSI (Turnamen Persahabatan)', 'Meningkatkan sportivitas dan kolaborasi olahraga antar etnis/komunitas', 'Terselenggaranya turnamen persahabatan yang sportif', 'Minimal 4 etnis/komunitas', 'Mini turnamen badminton atau mini soccer memperebutkan piala persahabatan.', 'Setiap 4-5 bulan sekali', 'Rp 1.000.000', 'JOJO', 'rencana', 20),
(13, 'Olahraga', '2026/2027', 'KOMPAK (Kebersamaan Olahraga K\'mplang)', 'Membangun gaya hidup sehat dalam suasana rekreasi bersama', 'Terciptanya ruang relaksasi positif di sela perkuliahan', 'Minimal 10 peserta', 'Fun run pagi, jalan sehat keliling Salatiga, dan senam bersama.', '2-3 kali per periode', 'Rp 200.000', 'JOJO', 'rencana', 30),
(14, 'Tari', '2026/2027', 'Latihan Tari Tradisional Lampung', 'Melestarikan seni tari adat Lampung (wiraga, wirama, wirasa)', 'Anggota menguasai repertoar tari penyambutan dan kreasi Lampung', 'Minimal 5 penari tiap sesi', 'Latihan rutin Tari Sigeh Penguten, Tari Bedana, dan Tari Melinting.', '2 kali sebulan (Jumat 17.00 WIB)', 'Rp 0', 'Gloria', 'berlangsung', 75),
(15, 'Tari', '2026/2027', 'Workshop Busana Adat & Siger', 'Meningkatkan pemahaman tata cara pemakaian pakaian adat Lampung yang benar', 'Anggota terampil mengenakan Siger, Kain Tapis, dan atribut adat', 'Minimal 5 peserta per sesi', 'Praktik langsung urutan pemakaian busana adat Pepadun dan Saibatin.', 'Minggu ke-2 latihan bulanan', 'Rp 0', 'Gloria', 'berlangsung', 70),
(16, 'Perkap', '2026/2027', 'Inventarisasi & Pemeliharaan Aset', 'Mendata seluruh aset budaya dan perlengkapan organisasi secara berkala', 'Seluruh aset tercatat rapi dan terhindar dari kerusakan/kehilangan', 'Tim Perlengkapan', 'Pengecekan kondisi Siger, kain tapis, alat musik tradisional, dan inventaris sekretariat.', '1 bulan sekali', 'Rp 0', 'Nathan', 'berlangsung', 85),
(17, 'Perkap', '2026/2027', 'Sistem Buku Sewa Inventaris', 'Menertibkan administrasi peminjaman dan penyewaan atribut adat Lampung', 'Setiap peminjaman tercatat lengkap dengan tanggal kembali', 'Tim Perlengkapan', 'Pengelolaan buku registrasi pinjam/sewa inventaris maksimal 1 minggu.', '2 kali sebulan', 'Rp 35.000', 'Felix (Udin)', 'berlangsung', 80),
(18, 'Musik', '2026/2027', 'ASIK (Ayo Semangat Musik Lampung)', 'Mengembangkan bakat musik anggota dan mengaransemen lagu daerah Lampung', 'Terbentuknya ensambel musik K\'mplang yang siap tampil di acara budaya', '5 - 10 personel', 'Latihan rutin lagu Tanoh Lado, Cangget Agung, Lampung Sai, dan aransemen akustik.', 'Minggu terakhir tiap bulan', 'Rp 0', 'Saut', 'berlangsung', 70),
(19, 'Musik', '2026/2027', 'Live Musik & Malam Apresiasi', 'Mengasah mental panggung anggota di ruang publik sekaligus penggalangan dana', 'Penampilan musik berjalan apik dan mendapat apresiasi penonton', 'Pengurus & Anggota', 'Pertunjukan akustik lagu daerah dan populer di kafe mitra / panggung kampus.', '3 bulan sekali', 'Rp 0', 'Saut', 'rencana', 30),
(20, 'Kerohanian', '2026/2027', 'Persekutuan & Doa Bersama', 'Menjadi wadah pertumbuhan spiritual dan saling mendoakan di tanah rantau', 'Terbangunnya dukungan moril dan rohani antar anggota', 'Minimal 75% anggota', 'Pertemuan ibadah, refleksi rohani, dan doa syafaat bersama.', '2 kali setiap bulan', 'Rp 25.000', 'Novita', 'berlangsung', 75),
(21, 'Kerohanian', '2026/2027', 'Ibadah Padang & Alam Terbuka', 'Memperkuat keakraban dan rasa syukur melalui kegiatan rohani di alam', 'Suasana kekeluargaan yang hangat dan menyegarkan', 'Minimal 80% anggota', 'Ibadah padang di kawasan wisata alam Salatiga/Kopeng disertai permainan kebersamaan.', '1 kali per periode', 'Rp 0', 'Kila', 'rencana', 15),
(22, 'Kerohanian', '2026/2027', 'Perayaan Natal Keluarga K\'mplang', 'Merayakan sukacita Natal dan mempererat tali persaudaraan di perantauan', 'Tingkat kehadiran dan partisipasi tinggi dari anggota serta alumni', '30+ anggota & tamu', 'Ibadah perayaan Natal, persembahan pujian, penampilan tari Lampung, dan jamuan kasih.', '24 Januari 2026', 'Rp 500.000', 'Novita', 'selesai', 100),
(23, 'Kerohanian', '2026/2027', 'Aksi Berbagi Takjil Ramadhan', 'Menumbuhkan kepedulian sosial K\'mplang kepada masyarakat Kota Salatiga', 'Tersalurkannya paket takjil dengan tertib dan penuh empati', 'Seluruh pengurus & anggota', 'Pembagian paket berbuka puasa gratis bagi pengguna jalan dan pekerja harian di Salatiga.', '21 Februari 2026', 'Rp 250.000', 'Kila', 'selesai', 100)
ON DUPLICATE KEY UPDATE
    `periode` = VALUES(`periode`),
    `nama_program` = VALUES(`nama_program`),
    `tujuan` = VALUES(`tujuan`),
    `status` = VALUES(`status`),
    `progres_persen` = VALUES(`progres_persen`);

-- 4. Seeder Initial Activities (Menggunakan aset gambar asli yang tersedia di public/assets/images)
INSERT INTO `activities` (`id`, `judul`, `slug`, `kategori_kegiatan`, `deskripsi`, `tanggal_kegiatan`, `lokasi`, `cover_image`, `author_id`, `view_count`, `is_featured`) VALUES
(1, 'Pelantikan Pengurus K\'mplang Salatiga Periode 2026/2027', 'pelantikan-pengurus-kmplang-periode-2026-2027', 'Organisasi', 'Momen bersejarah serah terima jabatan dan pengukuhan seluruh jajaran pengurus Keluarga Mahasiswa Perantauan Lampung (K\'mplang) di Kota Salatiga masa bakti 2026/2027. Acara dihadiri oleh dewan penasehat, pengurus demisioner, serta anggota aktif dalam suasana khidmat dan penuh kekeluargaan.', '2025-08-30', 'Salatiga, Jawa Tengah', 'thumb.jpg', 1, 148, 1),
(3, 'Malam Budaya & Silaturahmi Keluarga Besar K\'mplang', 'malam-budaya-dan-silaturahmi-keluarga-besar-kmplang', 'Budaya', 'Pertemuan akbar mahasiswa perantau Lampung lintas angkatan di Kota Salatiga. Kegiatan diisi dengan penampilan tari tradisional, alunan musik klasik Tanoh Lado, diskusi program kerja, serta santap malam kuliner khas kampung halaman.', '2025-10-18', 'Aula Kemahasiswaan Salatiga', 'ins.png', 1, 96, 1)
ON DUPLICATE KEY UPDATE
    `judul` = VALUES(`judul`),
    `kategori_kegiatan` = VALUES(`kategori_kegiatan`),
    `deskripsi` = VALUES(`deskripsi`),
    `cover_image` = VALUES(`cover_image`),
    `is_featured` = VALUES(`is_featured`);

-- 5. Seeder Activity Media (Menggunakan berkas fisik yang valid)
INSERT INTO `activity_media` (`id`, `activity_id`, `file_path`, `original_name`, `file_type`, `mime_type`, `file_size`) VALUES
(1, 1, 'thumb.jpg', 'Dokumentasi Utama Pelantikan Pengurus.jpg', 'image', 'image/jpeg', 4238024),
(2, 1, 'ins.png', 'Potret Kebersamaan Pengurus K-mplang.png', 'image', 'image/png', 205891),
(3, 1, 'LampungThumb.jpg', 'Simbol Budaya Sang Bumi Ruwa Jurai.jpg', 'image', 'image/jpeg', 388356),
(4, 3, 'ins.png', 'Sesi Foto Bersama Keluarga Besar.png', 'image', 'image/png', 205891),
(5, 3, 'remix.jpg', 'Pentas Musik Daerah Lampung.jpg', 'image', 'image/jpeg', 37085)
ON DUPLICATE KEY UPDATE
    `activity_id` = VALUES(`activity_id`),
    `file_path` = VALUES(`file_path`),
    `original_name` = VALUES(`original_name`),
    `mime_type` = VALUES(`mime_type`),
    `file_size` = VALUES(`file_size`);

-- 6. Seeder Articles (Wawasan Budaya & Sejarah Lampung)
INSERT INTO `articles` (`id`, `judul`, `slug`, `kategori`, `ringkasan`, `konten`, `cover_image`, `author_id`, `status`, `view_count`, `waktu_baca_menit`) VALUES
(1, 'Mengenal Siger Lampung: Mahkota Keagungan dan Simbol Sang Bumi Ruwa Jurai', 'mengenal-siger-lampung-mahkota-keagungan-dan-simbol-sang-bumi-ruwa-jurai', 'Budaya', 'Telaah filosofis di balik mahkota emas Siger Lampung yang menyatukan dua jurai adat besar: Sai Batin dan Pepadun.', '<p>Siger merupakan mahkota pengantin wanita khas suku Lampung yang menjadi ikon kebanggaan provinsi berjuluk <strong>Sang Bumi Ruwa Jurai</strong>. Mahkota berwarna kuning keemasan ini bukan sekadar aksesoris pelengkap busana adat, melainkan sarat makna filosofis, martabat, dan kehormatan tinggi masyarakat Lampung.</p><p>Bentuk siger melambangkan sembilan lekukan pada mahkota adat <em>Pepadun</em> (melambangkan sembilan marga Abung Siwo Mego) serta tujuh lekukan pada adat <em>Saibatin</em> (melambangkan tujuh gelar bangsawan pesisir). Keduanya mencerminkan keragaman adat istiadat yang bersatu dalam keharmonisan tanah Lampung.</p><p>Di perantauan Kota Salatiga, K\'mplang dengan bangga menyematkan lambang Siger sebagai identitas resmi organisasi—pengingat bagi setiap mahasiswa perantau untuk senantiasa menjaga nama baik daerah, menjunjung etika <em>Piil Pesenggiri</em>, dan merawat persaudaraan di tanah rantau.</p>', 'LampungThumb.jpg', 1, 'published', 112, 3),
(3, 'Falsafah Piil Pesenggiri sebagai Kompas Moral Mahasiswa Perantau Lampung', 'falsafah-piil-pesenggiri-sebagai-kompas-moral-mahasiswa-perantau-lampung', 'Sejarah', 'Empat pilar kearifan lokal masyarakat Lampung—Juluk Adek, Nemui Nyimah, Nengah Nyappur, dan Sakai Sambayan—dalam kehidupan akademik di perantauan.', '<p>Bagi masyarakat adat Lampung, <strong>Piil Pesenggiri</strong> merupakan pandangan hidup yang mengatur kehormatan diri, tata krama sosial, dan tanggung jawab moral di tengah masyarakat. Nilai ini tidak pernah lekang oleh jarak, termasuk ketika putra-putri Lampung merantau menuntut ilmu di Kota Salatiga.</p><p>Terdapat empat unsur utama yang menopang falsafah Piil Pesenggiri:</p><ol><li><strong>Bejuluk Beadek</strong>: Menjaga identitas dan nama baik melalui prestasi serta keteladanan sikap.</li><li><strong>Nemui Nyimah</strong>: Sikap ramah, santun, dan terbuka dalam menyambut sesama maupun tamu dari berbagai latar belakang budaya.</li><li><strong>Nengah Nyappur</strong>: Kemampuan berbaur, berdialog, dan aktif berkontribusi di lingkungan kampus serta masyarakat multikultural.</li><li><strong>Sakai Sambayan</strong>: Semangat gotong royong dan tolong-menolong tanpa pamrih, yang menjadi fondasi utama berdirinya K\'mplang Salatiga.</li></ol><p>Melalui penghayatan keempat pilar ini, setiap anggota K\'mplang diharapkan tidak hanya unggul secara intelektual di bangku kuliah, tetapi juga menjadi duta budaya yang membawa kehangatan dan integritas Lampung di Jawa Tengah.</p>', 'thumb.jpg', 1, 'published', 74, 4)
ON DUPLICATE KEY UPDATE
    `judul` = VALUES(`judul`),
    `ringkasan` = VALUES(`ringkasan`),
    `konten` = VALUES(`konten`),
    `cover_image` = VALUES(`cover_image`),
    `waktu_baca_menit` = VALUES(`waktu_baca_menit`);

-- 7. Seeder Announcements (Papan Poster Pengumuman & Agenda)
INSERT INTO `announcements` (`id`, `judul`, `isi`, `tipe`, `tanggal_acara`, `lokasi`, `tautan`, `is_active`, `author_id`) VALUES
(1, 'SAPU LIDI: Latihan Rutin Bulutangkis & Silaturahmi Jumat Sore', 'Terbuka untuk seluruh anggota dan mahasiswa perantau Lampung di Salatiga. Mari jaga kebugaran sekaligus melepas penat perkuliahan bersama keluarga besar K\'mplang.', 'agenda', '2026-11-13', 'GOR Bulutangkis Salatiga', '/program-kerja?divisi=Olahraga', 1, 1),
(2, 'Registrasi Keanggotaan K\'mplang Periode 2026/2027 Terbuka', 'Bagi mahasiswa baru maupun mahasiswa aktif asal Provinsi Lampung di UKSW, UIN Salatiga, dan sekitarnya yang belum terdata di direktori resmi, silakan mendaftarkan akun melalui portal ini.', 'pengumuman', NULL, 'Portal Online K\'mplang', '/register', 1, 1)
ON DUPLICATE KEY UPDATE
    `judul` = VALUES(`judul`),
    `isi` = VALUES(`isi`),
    `is_active` = VALUES(`is_active`);
