<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Tindakan extends Seeder
{
    public function run()
    {
        $this->db->query("INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `jenis_layanan`, `tarif`, `keterangan`) VALUES
(1, 'Administrasi Pasien Baru', 'Rawat Jalan', 25000, ''),
(2, 'Administrasi Pasien Lama', 'Rawat Jalan', 10000, ''),
(3, 'Administrasi Pasien Operasi', 'Rawat Jalan', 125000, ''),
(4, 'Jasa Konsultasi Dokter', 'Rawat Jalan', 100000, ''),
(5, 'Jasa Konsultasi Dokter (Khusus/2 Dokter)', 'Rawat Jalan', 110000, ''),
(6, 'Jasa Konsultasi Dokter (3 Dokter)', 'Rawat Jalan', 165000, ''),
(7, 'Jasa Konsultasi Dokter Emergency (Libur)', 'Rawat Jalan', 150000, ''),
(8, 'Dokter Umum IGD + Dokter Mata IGD', 'Rawat Jalan', 200000, 'Rp50.000 + Rp150.000'),
(9, 'Dokter Umum IGD + Dokter Mata Biasa', 'Rawat Jalan', 150000, 'Rp50.000 + Rp100.000'),
(10, 'Dokter Mata IGD + Dokter Mata Biasa', 'Rawat Jalan', 150000, ''),
(11, 'Jasa Konsultasi Dokter Umum', 'Rawat Jalan', 50000, ''),
(12, 'Jasa Konsultasi Dokter Umum IGD', 'Rawat Jalan', 100000, ''),
(13, 'Jasa Konsultasi Penyakit Dalam (Visite)', 'Rawat Jalan', 90000, ''),
(14, 'Jasa Konsultasi Penyakit Dalam (Operasi)', 'Rawat Jalan', 150000, ''),
(15, 'Jasa Konsultasi Dokter Anak (Pre Op)', 'Rawat Jalan', 150000, ''),
(16, 'Jasa Konsultasi Dokter Anak', 'Rawat Jalan', 100000, ''),
(17, 'Redresing/Perban Tekan', 'Rawat Jalan', 40000, ''),
(18, 'Pemeriksaan Shirmer Test', 'Rawat Jalan', 40000, ''),
(19, 'Pemeriksaan Anel Test', 'Rawat Jalan', 125000, ''),
(20, 'Pemeriksaan Rapid Test (Bius Lokal)', 'Rawat Jalan', 150000, ''),
(21, 'Pemeriksaan Rapid Test', 'Rawat Jalan', 200000, 'Pakai Surat Keterangan'),
(22, 'Pemeriksaan Rapid Test Antigen (Bius Umum)', 'Rawat Jalan', 109000, ''),
(23, 'Pemeriksaan Swab', 'Rawat Jalan', 300000, ''),
(24, 'Pemeriksaan Flourescein Test', 'Rawat Jalan', 40000, ''),
(25, 'Heacting Aff', 'Rawat Jalan', 150000, ''),
(26, 'Heacting Kornea', 'Rawat Jalan', 250000, 'Tindakan dilakukan di Poli'),
(27, 'Screeping Conjungtiva', 'Rawat Jalan', 150000, 'Tindakan dilakukan di Poli'),
(28, 'Screeping Kornea', 'Rawat Jalan', 150000, 'Tindakan dilakukan di Poli'),
(29, 'Korpus Alineum Kornea', 'Rawat Jalan', 150000, ''),
(30, 'Korpus Alineum di Konjungtiva', 'Rawat Jalan', 75000, ''),
(31, 'Ekstripasi Lithiasis (Ca Oxalat) Simple', 'Rawat Jalan', 200000, 'Tindakan dilakukan di Poli'),
(32, 'Ekstripasi Lithiasis (Ca Oxalat) Multiple', 'Rawat Jalan', 2000000, 'Tindakan dilakukan di OK'),
(33, 'Epilasi', 'Rawat Jalan', 75000, ''),
(34, 'Debrimen Ulkus Kornea', 'Rawat Jalan', 75000, ''),
(35, 'Spooling', 'Rawat Jalan', 75000, ''),
(36, 'Injeksi Sub Konjungtiva', 'Rawat Jalan', 2000000, ''),
(37, 'Kutur & Sensitivity Test', 'Rawat Jalan', 750000, ''),
(38, 'Injeksi Steroid Intravena', 'Rawat Jalan', 800000, ''),
(39, 'Milia/Milium Poly', 'Rawat Jalan', 250000, ''),
(40, 'Injeksi Medricain', 'Rawat Jalan', 150000, ''),
(41, 'Fitting RGP', 'Rawat Jalan', 750000, ''),
(42, 'Injeksi Gentamycin', 'Rawat Jalan', 600000, ''),
(43, 'RGP', 'Rawat Jalan', 5000000, 'Dua mata'),
(44, 'Hordeolum/Khalazion (Dilakukan di IGD)', 'Rawat Jalan', 1000000, ''),
(45, 'Massage Bola Mata', 'Rawat Jalan', 300000, ''),
(46, 'Massage Nevus Optilens', 'Rawat Jalan', 400000, ''),
(47, 'Pemeriksaan Gula Darah', 'Pemeriksaan Penunjang', 25000, ''),
(48, 'Pemeriksaan Autoref Keratometri', 'Pemeriksaan Penunjang', 25000, ''),
(49, 'Pemeriksaan Tonometri Non Contact', 'Pemeriksaan Penunjang', 50000, ''),
(50, 'Pemeriksaan Automated Perimetri Satu Mata', 'Pemeriksaan Penunjang', 300000, ''),
(51, 'Pemeriksaan Automated Perimetri Dua Mata', 'Pemeriksaan Penunjang', 500000, ''),
(52, 'Pemeriksaan USG Satu Mata', 'Pemeriksaan Penunjang', 500000, ''),
(53, 'Pemeriksaan USG Dua Mata', 'Pemeriksaan Penunjang', 800000, ''),
(54, 'Pemeriksaan Biometri', 'Pemeriksaan Penunjang', 100000, ''),
(55, 'Pemeriksaan Fundus Camera', 'Pemeriksaan Penunjang', 500000, ''),
(56, 'Pemeriksaan FFA', 'Pemeriksaan Penunjang', 1000000, ''),
(57, 'Tindakan YAG Laser', 'Pemeriksaan Penunjang', 3000000, ''),
(58, 'Tindakan Fotokoagulasi Laser', 'Pemeriksaan Penunjang', 3000000, ''),
(59, 'Pemeriksaan Keratometri', 'Pemeriksaan Penunjang', 30000, ''),
(60, 'Pemeriksaan Indirect Fundus Copy', 'Pemeriksaan Penunjang', 50000, ''),
(61, 'Pemeriksaan Retino Perifer', 'Pemeriksaan Penunjang', 300000, ''),
(62, 'Pemeriksaan OCT', 'Pemeriksaan Penunjang', 90000, ''),
(63, 'CT-Scan Orbita', 'Pemeriksaan Penunjang', 1100000, ''),
(64, 'CT-Scan Head', 'Pemeriksaan Penunjang', 1100000, ''),
(65, 'Thorax', 'Pemeriksaan Penunjang', 143000, ''),
(66, 'EKG', 'Pemeriksaan Penunjang', 52000, ''),
(67, 'Echocardiography', 'Pemeriksaan Penunjang', 676000, ''),
(68, 'Mantouk Test', 'Pemeriksaan Penunjang', 150000, ''),
(69, 'Suntik Insulin', 'Pemeriksaan Penunjang', 40000, ''),
(70, 'Cam Vision (Terapi Mata Malas) Dua Mata', 'Pemeriksaan Penunjang', 3000000, ''),
(71, 'Cam Vision (Terapi Mata Malas) Satu Mata', 'Pemeriksaan Penunjang', 2000000, ''),
(72, 'Injeksi Intravena (Rawat Inap)', 'Pemeriksaan Penunjang', 500000, ''),
(73, 'Heacting Aff', 'Operasi', 2000000, 'Dilakukan di OK'),
(74, 'Hordeolum/Khalazion', 'Operasi', 2000000, 'Dilakukan di OK'),
(75, 'Ekstraksi Nukleus', 'Operasi', 2000000, ''),
(76, 'Spooling Hipopion', 'Operasi', 2000000, ''),
(77, 'Ekstraksi Loa-Loa', 'Operasi', 2000000, ''),
(78, 'Injeksi OK (Flamicort, Avastin, Patizra, d.l.l.)', 'Operasi', 3000000, 'Jangan lupa cek BHP-nya'),
(79, 'Granuloma', 'Operasi', 2000000, ''),
(80, 'Prothesa Mata', 'Operasi', 1600000, ''),
(81, 'Eksplorasi Luka Kornea', 'Operasi', 1600000, ''),
(82, 'Xanthelasma Simple', 'Operasi', 2000000, ''),
(83, 'Xanthelasma Multiple', 'Operasi', 3500000, ''),
(84, 'Jahitan Palpebra Multiple/Konjungtiva dengan ukuran dari ½ cm', 'Operasi', 2500000, ''),
(85, 'Ruptur Margo Palpebra Simple', 'Operasi', 2500000, ''),
(86, 'Tumor dengan ukuran kurang dari ½ cm', 'Operasi', 2000000, ''),
(87, 'Jahitan Palpebra/Konjungtiva ukuran ½ cm', 'Operasi', 3000000, ''),
(88, 'Tumor dengan ukuran kurang dari ½ cm - 1½ cm', 'Operasi', 3000000, ''),
(89, 'Pemasangan PLUG PUNGTUM', 'Operasi', 4000000, ''),
(90, 'Ruptur Margo Palpebra Multiple', 'Operasi', 4500000, 'Repair Palpebra/Rekonstruksi Palpebra'),
(91, 'Injeksi Intravitreal (Triamcinolone, IVTA)', 'Operasi', 3000000, ''),
(92, 'Injeksi Intraokuler', 'Operasi', 3000000, ''),
(93, 'Heacting Konjungtiva dengan Robekan Kecil', 'Operasi', 2000000, ''),
(94, 'Heacting Konjungtiva dengan Robekan Besar', 'Operasi', 3500000, ''),
(95, 'Fiksasi Skelera/Iris Claw', 'Operasi', 5000000, ''),
(96, 'Iridektomi Perifer/Optis', 'Operasi', 3500000, ''),
(97, 'Sinekiolisis', 'Operasi', 3500000, ''),
(98, 'Soundage Punctum Lakrimal', 'Operasi', 3500000, ''),
(99, 'Tumor Palpebra dengan ukuran lebih dari 1½ cm', 'Operasi', 5500000, 'Evakuasi Tumor/Wide Eksisi Tumor'),
(100, 'Evakuasi Tumor Palpebra', 'Operasi', 5500000, ''),
(101, 'Evakuas Tumor Konjungtiva', 'Operasi', 5500000, ''),
(102, 'Ekstirpasi Tumor (Kista) Palpebra', 'Operasi', 5500000, ''),
(103, 'ECCE', 'Operasi', 6500000, ''),
(104, 'ICCE', 'Operasi', 6500000, ''),
(105, 'Clear Lens Extraction (CLE)', 'Operasi', 9500000, ''),
(106, 'Blefaro Plasti', 'Operasi', 4500000, ''),
(107, 'Flap Konjungtiva', 'Operasi', 4500000, ''),
(108, 'Fascia Lata', 'Operasi', 6000000, ''),
(109, 'Parasentese', 'Operasi', 3500000, ''),
(110, 'Extirpasi Pterygium dengan Cangkok Amnion/Konjungtiva', 'Operasi', 4000000, ''),
(111, 'Bare Sclera', 'Operasi', 4000000, ''),
(112, 'Screeping Kornea', 'Operasi', 4000000, ''),
(113, 'Graft Konjungtiva', 'Operasi', 4000000, ''),
(114, 'Nevus Konjungtiva', 'Operasi', 4000000, ''),
(115, 'Ekstraksi Korpus Sklera Konjungtiva', 'Operasi', 4000000, ''),
(116, 'Ekstraksi IOFB (Intraoculer Foreign Body)', 'Operasi', 2000000, ''),
(117, 'SICS + IOL (Small Incisim Cataract Surgery)', 'Operasi', 5000000, ''),
(118, 'Ruptur Kornea Sklera', 'Operasi', 8500000, ''),
(119, 'Heacting Kornea Sklera', 'Operasi', 8500000, ''),
(120, 'Trabekulektomi', 'Operasi', 6500000, ''),
(121, 'Ekplantasi Implant', 'Operasi', 6500000, ''),
(122, 'Repair Implant', 'Operasi', 6500000, ''),
(123, 'Implantasi IOL (Fiksasi Sklera)', 'Operasi', 11500000, ''),
(124, 'Ekstraksi/Ekstirpasi IOL/Lensa (ICCE)', 'Operasi', 6500000, ''),
(125, 'Eksplantasi IOL', 'Operasi', 6500000, ''),
(126, 'IOL Exchange', 'Operasi', 11500000, ''),
(127, 'Exchange Heavy Fluid', 'Operasi', 6500000, ''),
(128, 'CTR', 'Operasi', 2500000, ''),
(129, 'Ruptur Margo Palpebra dengan Ruptur Punctum dan Kanalis Lakrimalis', 'Operasi', 9500000, ''),
(130, 'Rekanilisasi/Ruptur Punctum Kanalis Lakrimalis', 'Operasi', 6500000, ''),
(131, 'Heacting Kornea', 'Operasi', 6500000, ''),
(132, 'Heacting Iris', 'Operasi', 6500000, ''),
(133, 'Heacting Sklera', 'Operasi', 6500000, ''),
(134, 'Corneal Patch (Tambal Kornea)', 'Operasi', 17000000, ''),
(135, 'Skleral Patch (Tambal Kornea)', 'Operasi', 6500000, ''),
(136, 'Iridectomi Sectoral', 'Operasi', 7000000, ''),
(137, 'Evicerasi/Enukleasi', 'Operasi', 5000000, ''),
(138, 'Evicerasi/Enukleasi + DFG', 'Operasi', 6000000, ''),
(139, 'Periosted Graft', 'Operasi', 6000000, ''),
(140, 'Eksentrasi', 'Operasi', 10500000, ''),
(141, 'Orbitotomi Anterior', 'Operasi', 8000000, ''),
(142, 'Autograft Defek Palpebra (Simple)', 'Operasi', 8500000, ''),
(143, 'Autograft Defek Palpebra (Multiple)', 'Operasi', 8500000, ''),
(144, 'Jasa Anestesi', 'Operasi', 3000000, ''),
(145, 'Laser Iridektomi Perifer', 'Operasi', 3500000, ''),
(146, 'Laser Glaucoma (TSCPC)', 'Operasi', 3500000, ''),
(147, 'Heacting Palpebra', 'Operasi', 3000000, ''),
(148, 'Insisi Konjungtiva (Insisi Palpebra)', 'Operasi', 2000000, ''),
(149, 'Heavy Fluid', 'Operasi', 3000000, ''),
(150, 'Reposisi Iris', 'Operasi', 4400000, ''),
(151, 'Repair Pupil', 'Operasi', 4400000, ''),
(152, 'Repair Iris', 'Operasi', 4400000, ''),
(153, 'Repair Iridodialisa', 'Operasi', 4400000, ''),
(154, 'Reposisi IOL', 'Operasi', 6500000, ''),
(155, 'Eksplorasi Vistula', 'Operasi', 3000000, ''),
(156, 'Silikon Tube', 'Operasi', 4000000, ''),
(157, 'Silikon Oil Exchange', 'Operasi', 11500000, ''),
(158, 'Silikon Oil', 'Operasi', 3600000, ''),
(159, 'Dacyro Cysto Rhinostomy (DCR)', 'Operasi', 8500000, 'Operasi pembuatan saluran air mata'),
(160, 'Flap Amnion (sudah termasuk Anestesi)', 'Operasi', 10000000, 'AMT (Amnion Membran Transplantasi)'),
(161, 'Strabismus (sudah termasuk Anestesi)', 'Operasi', 13500000, ''),
(162, 'Ablasio Retina', 'Operasi', 15000000, ''),
(163, 'Membranektomi', 'Operasi', 15000000, ''),
(164, 'Vitrektomi Lengkap', 'Operasi', 35000000, ''),
(165, 'Sklera Bucling', 'Operasi', 7000000, ''),
(166, 'Retinektomi', 'Operasi', 15000000, ''),
(167, 'Operasi Katarak dengan teknik Phaco Emulsifikasi', 'Operasi', 10000000, 'Sudah termasuk obat'),
(168, 'Enukleasi + DFG + Prothese', 'Operasi', 11600000, ''),
(169, 'Koreksi Symplepharon Berat', 'Operasi', 10000000, ''),
(170, 'Symblepharon', 'Operasi', 7500000, ''),
(171, 'Orbitonomi Lateral/Tumir Orbita', 'Operasi', 10000000, ''),
(172, 'Ptosis Repair/Repair Brow Ptosis (ODS)', 'Operasi', 8500000, ''),
(173, 'Ptosis Repair + Fascia Lata', 'Operasi', 11500000, ''),
(174, 'Rekonstruksi Kelopak Mata Berat', 'Operasi', 8500000, 'Repair Epiblepharon'),
(175, 'Rekonstruksi Socket Berat', 'Operasi', 8500000, ''),
(176, 'Syndromed Blepharopymosis', 'Operasi', 12000000, ''),
(177, 'Koreksi Ektropion/Entropion', 'Operasi', 7000000, 'Repair Entropion'),
(178, 'Eksisi Tumor Adneksa Sedang', 'Operasi', 5500000, ''),
(179, 'Eksisi Biopsi', 'Operasi', 5500000, ''),
(180, 'Pthisis + Graft Mucosa Bibir', 'Operasi', 11500000, ''),
(181, 'Rekonstruksi Socket Berat + DFG + Prothese', 'Operasi', 13100000, ''),
(182, 'Keratoplasty Optical Transplantasi Kornea (Cornea Opacity)', 'Operasi', 25000000, ''),
(183, 'Evakuasi Silicon Oil (Sudah termasuk Anestesi)', 'Operasi', 14000000, ''),
(184, 'Injeksi SFG Intra Vaskulaer', 'Operasi', 3000000, ''),
(185, 'Nevus/Veruca', 'Operasi', 2000000, ''),
(186, 'Kenacort', 'Operasi', 3000000, ''),
(187, 'Injeksi Gas', 'Operasi', 3000000, ''),
(188, 'Plug Plungtum', 'Operasi', 3000000, ''),
(189, 'Discoil', 'Operasi', 4500000, ''),
(190, 'Everting Suture', 'Operasi', 4500000, ''),
(191, 'Eridectomi Secforal', 'Operasi', 8500000, ''),
(192, 'Bowens', 'Operasi', 3500000, ''),
(193, 'PCU', 'Operasi', 8500000, ''),
(194, 'Lekomisis', 'Operasi', 2000000, ''),
(195, 'Aspirasi Irigasi (I/a)', 'Operasi', 5400000, ''),
(196, 'Flap Kornea', 'Operasi', 5000000, '');");
    }
}