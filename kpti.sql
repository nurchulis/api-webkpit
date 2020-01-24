-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 24, 2020 at 07:37 PM
-- Server version: 10.1.37-MariaDB-0+deb9u1
-- PHP Version: 7.0.33-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kpti`
--

-- --------------------------------------------------------

--
-- Table structure for table `aplikasi`
--

CREATE TABLE `aplikasi` (
  `id_aplikasi` int(5) NOT NULL,
  `judul` varchar(300) NOT NULL,
  `gambar` varchar(300) NOT NULL,
  `keterangan` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE `artikel` (
  `id_artikel` int(5) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi_singkat` varchar(200) NOT NULL,
  `gambar` varchar(300) NOT NULL,
  `keterangan` longtext NOT NULL,
  `status` varchar(10) NOT NULL,
  `label` varchar(20) NOT NULL,
  `tgl_edit` datetime NOT NULL,
  `tgl_buat` datetime NOT NULL,
  `log_kunjungan` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id_artikel`, `judul`, `deskripsi_singkat`, `gambar`, `keterangan`, `status`, `label`, `tgl_edit`, `tgl_buat`, `log_kunjungan`) VALUES
(1, 'Menabung tanah untuk masa depan', 'singkat', 'https://nusantaranews.co/assets/uploads/2016/12/Kecenderungan-orang-Indonesia-melakukan-investasi-tanah.-Foto-IlustrasiIST.jpg', 'isi keterangan', 'draft', 'tanah', '2020-01-13 03:12:23', '2020-01-13 06:08:10', 0),
(2, 'Tanah Mulai naik sekarang', 'singkat', 'https://nusantaranews.co/assets/uploads/2016/12/Kecenderungan-orang-Indonesia-melakukan-investasi-tanah.-Foto-IlustrasiIST.jpg', 'isi keterangan', 'draft', 'tanah', '2020-01-13 03:12:23', '2020-01-13 06:08:10', 0),
(30, 'Haiashia', 'singkat', '161bf9ed6231056c94c16925fc80ae79.jpeg', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(34, 'Haiashia', 'singkat', 'f7abdb6b6965b8bb33fdea60252290bb.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(35, 'Haiashia', 'singkat', 'ccdfe9697b232e793ce38eca522e4983.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(36, 'Haiashia', 'singkat', '345807afdc87d2df8ed2b60748e1ad2a.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(37, 'hahahah', 'singkat', 'e034957fc5dc141bde2de399fa51d455.png', 'uhuyd', 'on', 'rumah', '2020-01-14 03:12:23', '0000-00-00 00:00:00', 0),
(38, 'Haiashia', 'singkat', 'c79853f90540f78d5d46c3ea4a17919c.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(39, 'Haiashia', 'singkat', '3b4b6cc98115361aa523573f43a9d167.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(40, 'Haiashia', '', '97998c81f332fd77630d65390ca41ded.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(41, 'hahahah', 'singakt lull', '8ff9c46c5ac943a46e73e6c16bee0e52.png', 'uhuyd', 'on', 'rumah', '2020-01-24 03:35:05', '2020-01-14 03:12:23', 4),
(42, 'usas', '', '093c3cb5e3ddb0fee9f4aad3ccc61c2f.png', 'ada', 'on', 'tanah', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0),
(43, 'usas', 'singkat lur', '2fe4b7a651e0e3fa889ebc89b627aaf8.png', 'ada', 'on', 'tanah', '2020-01-24 08:29:00', '2020-01-24 08:29:00', 0),
(44, 'usas', 'singkat lur', '08d84f306e9656a94e8c9375f284bf9e.png', 'ada', 'on', 'tanah', '2020-01-24 03:31:52', '2020-01-24 03:31:52', 0);

-- --------------------------------------------------------

--
-- Table structure for table `detail_rumah`
--

CREATE TABLE `detail_rumah` (
  `id_detail` int(6) NOT NULL,
  `luas_tanah` int(5) NOT NULL,
  `luas_bangunan` int(5) NOT NULL,
  `kamar_tidur` int(5) NOT NULL,
  `kamar_mandi` int(5) NOT NULL,
  `garasi` int(5) NOT NULL,
  `spesifikasi_lain` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `detail_rumah`
--

INSERT INTO `detail_rumah` (`id_detail`, `luas_tanah`, `luas_bangunan`, `kamar_tidur`, `kamar_mandi`, `garasi`, `spesifikasi_lain`) VALUES
(1, 200, 300, 2, 3, 1, 'AC, TV, Pemotong rumput'),
(2, 200, 340, 2, 3, 0, 'AC');

-- --------------------------------------------------------

--
-- Table structure for table `detail_tanah`
--

CREATE TABLE `detail_tanah` (
  `id_detail` int(5) NOT NULL,
  `luas_tanah` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `detail_tanah`
--

INSERT INTO `detail_tanah` (`id_detail`, `luas_tanah`) VALUES
(1, '400'),
(2, '300'),
(3, '1000');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id_gallery` int(5) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `kategori` varchar(30) NOT NULL,
  `url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `jasakonsultan`
--

CREATE TABLE `jasakonsultan` (
  `id_konsultan` int(5) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `keterangan` text NOT NULL,
  `foto_1` varchar(300) NOT NULL,
  `foto_2` varchar(300) NOT NULL,
  `foto_3` varchar(300) NOT NULL,
  `foto_4` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `jasa_kontruksi`
--

CREATE TABLE `jasa_kontruksi` (
  `id_jasakontruksi` int(5) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `keterangan` varchar(1000) NOT NULL,
  `gambar1` varchar(300) NOT NULL,
  `gambar2` varchar(300) NOT NULL,
  `gambar3` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `karir`
--

CREATE TABLE `karir` (
  `id_karir` int(5) NOT NULL,
  `judul` varchar(300) NOT NULL,
  `gambar` varchar(300) NOT NULL,
  `keterangan` varchar(2000) NOT NULL,
  `download` varchar(300) NOT NULL,
  `url_daftar` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `pelatihan`
--

CREATE TABLE `pelatihan` (
  `id_pelatihan` int(5) NOT NULL,
  `judul` varchar(300) NOT NULL,
  `gambar` varchar(300) NOT NULL,
  `keterangan` varchar(2000) NOT NULL,
  `link_pendaftaran` varchar(300) NOT NULL,
  `download` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(5) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `keterangan` varchar(1000) NOT NULL,
  `gambar` varchar(500) NOT NULL,
  `status` varchar(20) NOT NULL,
  `kategori` varchar(200) NOT NULL,
  `sertifikat` varchar(30) NOT NULL,
  `provinsi` varchar(50) NOT NULL,
  `kabupaten` varchar(50) NOT NULL,
  `harga` int(15) NOT NULL,
  `foto_1` varchar(500) NOT NULL,
  `foto_2` varchar(500) NOT NULL,
  `foto_3` varchar(500) NOT NULL,
  `foto_4` varchar(500) NOT NULL,
  `id_detail` varchar(5) NOT NULL,
  `nomor_kontak` varchar(30) NOT NULL,
  `nama_cs` varchar(30) NOT NULL,
  `log_kunjungan` int(8) NOT NULL,
  `tgl_edit` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `judul`, `keterangan`, `gambar`, `status`, `kategori`, `sertifikat`, `provinsi`, `kabupaten`, `harga`, `foto_1`, `foto_2`, `foto_3`, `foto_4`, `id_detail`, `nomor_kontak`, `nama_cs`, `log_kunjungan`, `tgl_edit`) VALUES
(1, 'Jaminan Untung Besar Punya Tanah Di Bantul Jogjakarta', 'Kami sediakan pilihan lokasi terbaik, layak investasi. Baiknya anda beli di Bantul pariwisata berkembang disana. Anda bebas pilih; kavling ruko, kost maupun perumahan. Mari bercepat! Kavling Bantul permai siap bangun hunia dekat dengan jalan ke wisata pantai.\r\n\r\n-              Dekat stasiun sentolo\r\n\r\n-              Dekat Sat Brimob Sentolo\r\n\r\n-              Legalitas SHM\r\n\r\n-              Dekat wisata karst tubing sedayu', 'https://cdn.carro.co/jualo/original/18279283/rumahku-istanaku-teru-rumah-dijual-18279283.jpg', 'ada', 'rumah', 'SHM', 'yogyakarta', 'kulonprogo', 20000000, 'https://cdn.carro.co/jualo/original/18279283/rumahku-istanaku-teru-rumah-dijual-18279283.jpg', 'https://cdn.carro.co/jualo/original/18279283/rumahku-istanaku-teru-rumah-dijual-18279283.jpg', 'https://cdn.carro.co/jualo/original/18279283/rumahku-istanaku-teru-rumah-dijual-18279283.jpg', 'https://cdn.carro.co/jualo/original/18279283/rumahku-istanaku-teru-rumah-dijual-18279283.jpg', '1', '0838383838', 'KPTI1', 0, '0000-00-00 00:00:00'),
(2, 'Kavlingan Strategis dekat Bakal Jalur Bedah Menoreh, pasti Untung (Unggulan)', '\r\nWah, daerah selatan Borobudur akan dibangun Kawasan Ekonomi Ekslusif ya? Wah, Jalur penghubung Bandara JIA-Borobudur lebarnya 12 meter ya? Wah, tanah di daerah Nanggulan bakal jadi sasaran Investor ya?\r\n\r\nSTOP terkaget-kaget! Mari beraksi. Kami antar Anda survey ke bebrapa lokasi premium di Nanggulan.\r\n\r\n> Legalitas aman SHM Pecah\r\n\r\n> Bandara JIA sudah beroperasi\r\n\r\n> Bangun Rumah Hemat 200 jt', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/qgyoaskljvpdnebtxcrufizwmNanggulan_1.jpg', 'ada', 'tanah', 'SHM', 'jakarta', 'bogor', 200000000, 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/mxbdlcyovtrapenhizujksqwf1_1.JPG', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/uklcnajsprqehvygzfxdwbotm1.jpg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/nmfitvzlypoajruxckeqsghbdnanggulan_permai_1.jpg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/ubmtwikngceyjplasvrfqdzhoASDFG_1.jpg', '1', '0838383838', 'KPTI2', 0, '0000-00-00 00:00:00'),
(3, 'Dapatkan Provit 40% Pertahun Dengan Inves Tanah di Area Bandara YIA\n', '\r\nWah, daerah selatan Borobudur akan dibangun Kawasan Ekonomi Ekslusif ya? Wah, Jalur penghubung Bandara JIA-Borobudur lebarnya 12 meter ya? Wah, tanah di daerah Nanggulan bakal jadi sasaran Investor ya?\r\n\r\nSTOP terkaget-kaget! Mari beraksi. Kami antar Anda survey ke bebrapa lokasi premium di Nanggulan.\r\n\r\n> Legalitas aman SHM Pecah\r\n\r\n> Bandara JIA sudah beroperasi\r\n\r\n> Bangun Rumah Hemat 200 jt', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/qgyoaskljvpdnebtxcrufizwmNanggulan_1.jpg', 'ada', 'tanah', 'SHM', 'jakarta', 'bogor', 200000000, 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/mxbdlcyovtrapenhizujksqwf1_1.JPG', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/uklcnajsprqehvygzfxdwbotm1.jpg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/nmfitvzlypoajruxckeqsghbdnanggulan_permai_1.jpg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/ubmtwikngceyjplasvrfqdzhoASDFG_1.jpg', '2', '0838383838', 'KPTI1', 0, '0000-00-00 00:00:00'),
(4, 'Bandara YIA Megah Berdiri, Yakin Tak Ingin Punyai Rumah di Bantul?', '\r\nBandara YIA sudah terlihat anggun terbangun, bukti nyata bahwa infrastruktur Jogja semakin maju, apalagi pembangunan TOL juga sudah terwacanakan. Tak ayal kawasan Wates menjadi spot favorit untuk berinves properti, kampus dan RS baru sudah terencanakan untuk dibangun di sana. \r\nMemiliki investasi tanah di kawasan ini adalah keputusan tepat dengan keuntungan berlipat. Selayaknya Anda membeli tanah di area ini untuk aset masa depan keluarga Anda. Keuntungannya bisa mencapai 60% setahun.\r\nInfo Sekitar Lokasi\r\n•    Kota Satelit Baru Wates \r\n•    POLRES Wates Kota\r\n•    Calon RS Panti Rapih\r\n•    Exit Tol Yogya-Cilacap\r\n•    Kawasan Penyangga Bandara (KPB) YIA', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/hvycfnwlupimtedzaxqgjbrskWhatsApp_Image_2019-07-26_at_15.13.34.jpeg', 'ada', 'rumah', 'SHM', 'yogyakarta', 'bantul', 100000000, 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/goeyhujsncdzvpiwafmxbrtkl1c97df10-f312-4a62-86aa-e690740d8201.jpeg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/goeyhujsncdzvpiwafmxbrtkl1c97df10-f312-4a62-86aa-e690740d8201.jpeg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/goeyhujsncdzvpiwafmxbrtkl1c97df10-f312-4a62-86aa-e690740d8201.jpeg', 'https://s3-ap-southeast-1.amazonaws.com/propertytoday/ioqvregafnlkwbjdyzsmphxct79c9dcea-ec2f-4527-a687-4c3fc0699e46.jpeg', '2', '0838383838', 'KPTI2', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `prospektus`
--

CREATE TABLE `prospektus` (
  `id_prospektus` int(5) NOT NULL,
  `judul` varchar(300) NOT NULL,
  `gambar` varchar(300) NOT NULL,
  `foto_1` varchar(300) NOT NULL,
  `foto_2` varchar(300) NOT NULL,
  `foto_3` varchar(300) NOT NULL,
  `foto_4` varchar(300) NOT NULL,
  `foto_5` varchar(300) NOT NULL,
  `lokasi` varchar(300) NOT NULL,
  `keterangan` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id_slider` int(5) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `url` varchar(300) NOT NULL,
  `keterangan` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `tentang_kami`
--

CREATE TABLE `tentang_kami` (
  `id_tentangkami` int(5) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `keterangan` varchar(1000) NOT NULL,
  `foto_1` varchar(300) NOT NULL,
  `foto_2` varchar(300) NOT NULL,
  `foto_3` varchar(300) NOT NULL,
  `foto_4` varchar(300) NOT NULL,
  `foto_5` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aplikasi`
--
ALTER TABLE `aplikasi`
  ADD PRIMARY KEY (`id_aplikasi`);

--
-- Indexes for table `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id_artikel`);

--
-- Indexes for table `detail_rumah`
--
ALTER TABLE `detail_rumah`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `detail_tanah`
--
ALTER TABLE `detail_tanah`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id_gallery`);

--
-- Indexes for table `jasakonsultan`
--
ALTER TABLE `jasakonsultan`
  ADD PRIMARY KEY (`id_konsultan`);

--
-- Indexes for table `jasa_kontruksi`
--
ALTER TABLE `jasa_kontruksi`
  ADD PRIMARY KEY (`id_jasakontruksi`);

--
-- Indexes for table `karir`
--
ALTER TABLE `karir`
  ADD PRIMARY KEY (`id_karir`);

--
-- Indexes for table `pelatihan`
--
ALTER TABLE `pelatihan`
  ADD PRIMARY KEY (`id_pelatihan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `prospektus`
--
ALTER TABLE `prospektus`
  ADD PRIMARY KEY (`id_prospektus`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id_slider`);

--
-- Indexes for table `tentang_kami`
--
ALTER TABLE `tentang_kami`
  ADD PRIMARY KEY (`id_tentangkami`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aplikasi`
--
ALTER TABLE `aplikasi`
  MODIFY `id_aplikasi` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id_artikel` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `detail_rumah`
--
ALTER TABLE `detail_rumah`
  MODIFY `id_detail` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `detail_tanah`
--
ALTER TABLE `detail_tanah`
  MODIFY `id_detail` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id_gallery` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jasakonsultan`
--
ALTER TABLE `jasakonsultan`
  MODIFY `id_konsultan` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jasa_kontruksi`
--
ALTER TABLE `jasa_kontruksi`
  MODIFY `id_jasakontruksi` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `karir`
--
ALTER TABLE `karir`
  MODIFY `id_karir` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pelatihan`
--
ALTER TABLE `pelatihan`
  MODIFY `id_pelatihan` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `prospektus`
--
ALTER TABLE `prospektus`
  MODIFY `id_prospektus` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id_slider` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tentang_kami`
--
ALTER TABLE `tentang_kami`
  MODIFY `id_tentangkami` int(5) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
