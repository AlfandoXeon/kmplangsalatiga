USE `db_kmplang`;

-- 1. Seeder Admin & User Awal
INSERT INTO `users` (`id`, `nama_lengkap`, `nim`, `email`, `password_hash`, `role`, `asal_daerah`, `fakultas`, `no_whatsapp_encrypted`, `tanggal_lahir`, `motivasi`, `status`) VALUES
(1, 'Grateo Alfando Atmojo', '672023001', 'admin@kmplang.org', '$2y$10$sv1jt/ORE6oS4GnuFyOCA.tLmiFzFNx1LE6G9Ud0jiU40Un0Wpks.', 'admin', 'Lampung Timur', 'Teknologi Informasi', '', '2004-05-15', 'Membangun sistem dan memajukan organisasi Kmplang Salatiga', 'active'),
(2, 'Yohanes Pengurus', '672023002', 'yohanes@kmplang.org', '$2y$10$sv1jt/ORE6oS4GnuFyOCA.tLmiFzFNx1LE6G9Ud0jiU40Un0Wpks.', 'pengurus', 'Bandar Lampung', 'Ekonomi dan Bisnis', '', '2004-08-20', 'Mengabdi untuk perantau Lampung di Salatiga', 'active'),
(3, 'Eben Mahasiswa', '672024010', 'eben@kmplang.org', '$2y$10$sv1jt/ORE6oS4GnuFyOCA.tLmiFzFNx1LE6G9Ud0jiU40Un0Wpks.', 'anggota', 'Metro Lampung', 'Hukum', '', '2005-01-10', 'Menjalin silaturahmi dengan sesama perantau', 'active')
ON DUPLICATE KEY UPDATE `nama_lengkap`=VALUES(`nama_lengkap`);

-- 2. Seeder Site Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `group_name`) VALUES
('hero_title', 'RUMAH PERANTAU LAMPUNG', 'hero'),
('hero_subtitle', 'Di Kota Salatiga', 'hero'),
('hero_description', 'Menjadi keluarga di tanah rantau, menjunjung tinggi budaya, dan meraih mimpi bersama.', 'hero'),
('about_title', 'Lebih dari Sekedar Komunitas', 'about'),
('about_content_1', 'K\'mplang Salatiga adalah wadah bagi mahasiswa perantau dari Lampung yang menempuh pendidikan di Salatiga. Kami bukan hanya sekumpulan mahasiswa, tapi sebuah keluarga yang saling mendukung, berbagi tawa, dan menjaga semangat kebersamaan di tanah rantau.', 'about'),
('about_content_2', 'Nama "K\'mplang" (Komunitas Mahasiswa Perantauan Lampung) terinspirasi dari makanan khas Lampung, Kemplang, yang melambangkan kehangatan dan kebersamaan. Kami berharap komunitas ini menjadi tempat yang nyaman dan selalu dirindukan.', 'about'),
('sambutan_ketua', 'Tabik Pun! Selamat datang di website resmi K\'mplang Salatiga. Sistem informasi ini dibangun untuk mewujudkan tata kelola organisasi yang modern, transparan, serta menjadi ruang interaksi yang erat bagi seluruh mahasiswa perantau Lampung di Kota Salatiga.', 'greeting'),
('nama_ketua', 'Ketua K\'mplang Periode 2025/2026', 'greeting'),
('social_instagram', 'https://www.instagram.com/k_mplangsalatiga', 'contact'),
('social_tiktok', 'https://www.tiktok.com/@kmplangsalatiga', 'contact'),
('contact_email', 'sekretariat@kmplang.org', 'contact'),
('contact_whatsapp', '085225216552', 'contact'),
('address', 'Salatiga, Jawa Tengah, Indonesia', 'contact')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);

-- 3. Seeder Work Programs (Data Proker Lengkap 2025/2026)
INSERT INTO `work_programs` (`id`, `divisi`, `nama_program`, `tujuan`, `indikator_kualitas`, `indikator_kuantitas`, `gambaran_kegiatan`, `waktu_kegiatan`, `anggaran`, `penanggung_jawab`, `status`) VALUES
(1, 'BPH', 'Raker', 'Merencanakan rencana kerja selama satu periode', 'Semua divisi memiliki minimal 1 program untuk dikerjakan', 'Seluruh pengurus', 'Rapat bersama, berdiskusi tentang seluruh program dari semua divisi', '14 September 2025 pukul 13.00 WIB', 'Rp 0', 'Yohanes', 'selesai'),
(2, 'BPH', 'Rapat Rutin', 'Membahas progres program setiap divisi', 'Ada perkembangan/terlaksananya program', '80% dari pengurus (13)', 'Doa, diskusi, sharing per divisi, warna sari dan penutup.', 'Setiap bulan minggu pertama', 'Rp 0', 'Yohanes', 'berlangsung'),
(3, 'BPH', 'Rapat Evaluasi (Raev)', 'Mengukur keberhasilan, mengidentifikasi kelemahan, dan mengambil keputusan yang lebih baik untuk kedepannya.', 'Membuat SWOT, melihat ketercapaian program, dan membuat rancangan kebutuhan setiap divisi.', '80% dari pengurus (13)', 'Doa, diskusi per setiap divisi, sharing per divisi, warna sari dan penutup.', '6 bulan sekali dalam 1 periode (Februari dan Agustus)', 'Rp 0', 'Yohanes', 'rencana'),
(4, 'BPH', 'Rekrut Maba', 'Mendapatkan anggota', 'Menambah keanggotaan, dan memperkenalkan KMPLANG', '30 Maba', 'Merekrut melalui kegiatan Welcoming Party dengan Barcode dan menghubungi melalui WA. Meminta data maba di kampus', '27 September 2025', 'Rp 0', 'Eben', 'selesai'),
(5, 'BPH', 'Makrab', 'Menambah engagement anggota', 'Anggota memahami makna dari makrab', '25 orang', 'Mempersiapkan makrab dari bulan Januari (usda, live musik, jual keripik dan kopi), menginap 2 hari 1 malam, pembekalan materi, fun games dan kebersamaan', '14-15 Maret 2026', 'Rp 2.500.000', 'Eka', 'rencana'),
(6, 'BPH', 'Kas + Denda', 'Mendisiplinkan setiap pengurus dan menambah uang kas', 'Disiplin kas berkala', 'Semua pengurus', 'Kas 10.000 setiap rapat per bulan, telat denda 5.000, izin 10.000', 'Setiap rapat', 'Rp 0', 'Eka', 'berlangsung'),
(7, 'Humas', 'Visitasi Orda Lampung', 'Mempererat hubungan dengan Orda Lampung dan memperoleh info sistem kerja', 'Menambah wawasan dan menjalin komunikasi berkelanjutan', '50% dari pengurus', 'Penyambutan, perkenalan masing-masing organisasi, sharing session, dan penutupan.', 'Kondisional', 'Rp 0', 'Ivona', 'rencana'),
(8, 'Humas', 'Visitasi Etnis di UKSW', 'Membangun komunikasi dan pertukaran informasi antar etnis', 'Saling mengenal dan menghargai antar etnis di UKSW', '50% dari pengurus', 'Penyambutan, perkenalan etnis, sharing session mengenai program kerja dan event budaya.', 'Kondisional', 'Rp 0', 'Ivona', 'rencana'),
(9, 'Humas', 'Rekrutmen Eksternal UKSW', 'Mendapatkan anggota eksternal UKSW', 'Menambah keanggotaan mahasiswa Lampung di kampus lain', 'Seluruh pengurus', 'Merekrut dengan mengunjungi kampus UIN Salatiga dan jejaring media sosial.', 'Kondisional', 'Rp 0', 'Humas', 'berlangsung'),
(10, 'Wirausaha', 'USDA (Usaha Dana)', 'Menambah dana kas kmplang dan mengenalkan produk khas Lampung', 'Minimal target mencapai 680.000 dalam satu bulan', 'Seluruh pengurus', 'Menjual produk khas Lampung seperti keripik pisang aneka rasa dan kopi Lampung.', 'Satu bulan 2 kali', 'Rp 0', 'Veni', 'berlangsung'),
(11, 'Olahraga', 'SAPU LIDI (Satu Jumat Penuh Latihan dan Silaturahmi)', 'Menjalin keakraban dan meningkatkan keterampilan olahraga', 'Latihan rutin berjalan lancar dan suasana akrab', 'Minimal 50% anggota hadir', 'Latihan badminton rutin setiap Jumat sore jam 17.00-20.00 WIB. Iuran 5k per orang.', 'Setiap Jumat', 'Rp 370.000 / bln', 'JOJO', 'berlangsung'),
(12, 'Olahraga', 'GUGUS AKSI', 'Meningkatkan sportivitas, prestasi, dan kolaborasi antar etnis', 'Terjalin kerjasama antar etnis melalui olahraga', 'Minimal 4 etnis/komunitas', 'Mini turnamen badminton atau mini soccer dengan hadiah piala persahabatan.', 'Setiap 4-5 bulan sekali', 'Rp 1.000.000', 'JOJO', 'rencana'),
(13, 'Olahraga', 'KOMPAK (Kebersamaan Olahraga K\'MPLANG)', 'Mempererat kekompakan dan gaya hidup sehat', 'Kegiatan santai dan penuh semangat kekeluargaan', 'Minimal 10 peserta', 'Lari pagi sehat, jalan santai keliling kota Salatiga, atau sepedaan.', '2-3 kali per periode', 'Rp 200.000', 'JOJO', 'rencana'),
(14, 'Tari', 'Latihan Tari Rutin', 'Meningkatkan pemahaman seni tari Lampung (wiraga, wirama, wirasa)', 'Mengembangkan bakat tari tradisional anggota', 'Minimal 5 orang tiap sesi', 'Latihan gerak tari Lampung (Sigeh Penguten, Bedana, Melinting).', '2 kali sebulan (Jumat 17.00)', 'Rp 0', 'Gloria', 'berlangsung'),
(15, 'Tari', 'Mempelajari Baju Adat dan Aksesoris Tari', 'Meningkatkan pengetahuan urutan dan tata cara busana adat', 'Memahami pemakaian siger, selempang, dan kain tapis', 'Minimal 5 orang', 'Praktik langsung memakai kelengkapan busana tari Lampung.', 'Minggu ke-2 latihan', 'Rp 0', 'Gloria', 'berlangsung'),
(16, 'Perkap', 'Pendataan dan Pengecekan Rutin', 'Mendata seluruh aset dan mengecek kondisi berkala', 'Mengantisipasi kerusakan dan kehilangan inventaris', 'Petugas perkap', 'Inventarisasi alat musik, pakaian adat, dan perlengkapan kesekretariatan.', '1 bulan sekali', 'Rp 0', 'Nathan', 'berlangsung'),
(17, 'Perkap', 'Buku Sewa Inventaris', 'Mencatat peminjaman dan batas pengembalian barang', 'Tertib administrasi peminjaman perlengkapan', 'Petugas perkap', 'Pencatatan form sewa dan waktu peminjaman maksimal 1 minggu.', '2 kali sebulan', 'Rp 35.000', 'Felix (Udin)', 'berlangsung'),
(18, 'Musik', 'ASIK (Ayo Semangat Musik)', 'Berlatih alat musik dan mengenal lagu daerah Lampung', 'Menguasai lagu-lagu tradisional dan pop daerah', '5 - 10 orang', 'Latihan aransemen musik tradisional Lampung dan kolaborasi modern.', 'Minggu terakhir tiap bulan', 'Rp 0', 'Saut', 'berlangsung'),
(19, 'Musik', 'Live Musik K\'mplang', 'Mengasah mental tampil di depan publik dan usaha dana', 'Meningkatkan rasa percaya diri dan musikalitas anggota', 'Pengurus & Anggota', 'Penampilan musik akustik di cafe mitra atau acara kampus.', '3 bulan sekali', 'Rp 0', 'Saut', 'rencana'),
(20, 'Kerohanian', 'Ibadah Rutin', 'Menjadi wadah bertumbuh dalam iman dan persekutuan', 'Menumbuhkan iman dan relasi sesama perantau', 'Minimal 75% pengurus & anggota', 'Pujian, doa pembuka, sharing firman, persembahan, dan doa syafaat.', 'Sebulan 2 kali', 'Rp 25.000', 'Novita', 'berlangsung'),
(21, 'Kerohanian', 'Ibadah Padang', 'Memperkuat iman dalam suasana alam terbuka', 'Membangun keakraban antar anggota di luar ruangan', 'Minimal 80% pengurus & anggota', 'Kebaktian padang di alam terbuka dengan games kebersamaan.', '1 kali per periode', 'Rp 0', 'Kila', 'rencana'),
(22, 'Kerohanian', 'Natal KMPLANG 2026', 'Merayakan kelahiran Kristus dan mempererat persaudaraan', 'Tingkat partisipasi tinggi seluruh keluarga besar', '30+ anggota', 'Ibadah perayaan natal, pertunjukan tari budaya, dan tukar kado.', '24 Januari 2026', 'Rp 500.000', 'Novita', 'rencana'),
(23, 'Kerohanian', 'Bagi Takjil Ramadhan', 'Menumbuhkan kepedulian sosial di bulan suci Ramadhan', 'Berbagi berkat kepada masyarakat sekitar Salatiga', 'Seluruh pengurus', 'Aksi sosial pembagian makanan takjil gratis di jalan utama Salatiga.', '21 Februari 2026', 'Rp 250.000', 'Kila', 'rencana')
ON DUPLICATE KEY UPDATE `nama_program`=VALUES(`nama_program`);

-- 4. Seeder Initial Activity (Pelantikan Pengurus 2025/2026)
INSERT INTO `activities` (`id`, `judul`, `slug`, `deskripsi`, `tanggal_kegiatan`, `lokasi`, `cover_image`, `author_id`, `view_count`) VALUES
(1, 'Pelantikan Pengurus K\'mplang Periode 2025/2026', 'pelantikan-pengurus-kmplang-periode-2025-2026', 'Momen bersejarah serah terima jabatan dan pengukuhan seluruh jajaran pengurus Keluarga Mahasiswa dan Pelajar Lampung di Salatiga untuk masa bakti 2025/2026. Acara dihadiri oleh seluruh dewan penasehat, pengurus demisioner, dan segenap anggota aktif.', '2025-08-30', 'Salatiga', 'pelantikan1.jpg', 1, 142)
ON DUPLICATE KEY UPDATE `judul`=VALUES(`judul`);

-- 5. Seeder Activity Media (Dokumentasi Pelantikan Foto 1-6 & Cover)
INSERT INTO `activity_media` (`id`, `activity_id`, `file_path`, `original_name`, `file_type`, `mime_type`, `file_size`) VALUES
(1, 1, 'pelantikan1.jpg', 'Pelantikan Pengurus Baru.jpg', 'image', 'image/jpeg', 17972225),
(2, 1, 'pelantikan2.jpg', 'Foto Bersama Anggota.jpg', 'image', 'image/jpeg', 18056670),
(3, 1, 'covvv.jpg', 'Suasana Akrab Penuh Canda.jpg', 'image', 'image/jpeg', 18839685),
(4, 1, 'thumb.jpg', 'Momen Serah Terima Jabatan.jpg', 'image', 'image/jpeg', 16124389),
(5, 1, 'pelantikan5.jpg', 'Sesi Diskusi Program Kerja.jpg', 'image', 'image/jpeg', 16242852),
(6, 1, 'pelantikan6.jpg', 'Keceriaan di Acara Pelantikan.jpg', 'image', 'image/jpeg', 13877738)
ON DUPLICATE KEY UPDATE `file_path`=VALUES(`file_path`);

-- 6. Seeder Initial Article
INSERT INTO `articles` (`id`, `judul`, `slug`, `kategori`, `konten`, `cover_image`, `author_id`, `status`, `view_count`) VALUES
(1, 'Mengenal Siger Lampung: Mahkota Keagungan dan Simbol Sang Bumi Ruwa Jurai', 'mengenal-siger-lampung-mahkota-keagungan-dan-simbol-sang-bumi-ruwa-jurai', 'Budaya', '<p>Siger merupakan mahkota pengantin wanita khas suku Lampung yang menjadi ikon kebanggaan provinsi berjuluk Sang Bumi Ruwa Jurai. Mahkota berwarna kuning keemasan ini bukan sekadar aksesoris pelengkap busana adat, melainkan sarat makna filosofis dan kehormatan tinggi perempuan Lampung.</p><p>Bentuk siger melambangkan sembilan lekukan pada mahkota pepadun dan tujuh lekukan pada saibatin, mencerminkan keragaman adat istiadat yang bersatu dalam keharmonisan tanah Lampung. Di perantauan Kota Salatiga, K\'mplang bangga membawa lambang Siger sebagai wujud luhur melestarikan identitas tanah kelahiran.</p>', 'LampungThumb.jpg', 1, 'published', 88)
ON DUPLICATE KEY UPDATE `judul`=VALUES(`judul`);
