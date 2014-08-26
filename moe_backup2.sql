--
-- Tabellenstruktur für Tabelle `moe_backup`
--

CREATE TABLE IF NOT EXISTS `moe_backup` (
  `OXID` char(32) NOT NULL,
  `OCBTITLE` varchar(255) NOT NULL,
  `OCBTITLE_1` varchar(255) NOT NULL,
  `MOEFILEPREFIX` varchar(255) NOT NULL,
  `OCBDATETIME` datetime NOT NULL,
  `MOEBACKUPDIR` varchar(400) NOT NULL,
  `MOELOGGINGDIR` varchar(400) NOT NULL,
  `MOEINCLUDE` varchar(400) NOT NULL,
  `MOEEXCLUDE` varchar(400) NOT NULL,
  `MOEDESCRIPTION` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `moe_backup`
--

INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES
('b029da7d9163b9d97f569dfc1393f17f', 'Module Sichern', '', 'Modules', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/', 'modules/moe/moe_backup/.git', 'gggvvvv'),
('2d975b6493b0719c9d6a145393f8e89a', 'Komplettsicherung', '', 'Backup_all', '0000-00-00 00:00:00', 'data/backup/shop/', 'data/backup/shop/', '', 'tmp/*;data/backup/*;data/import/pictures/*;statistik;dumper;cgi-bin;FTP;phpMyAdmin*;export/TRUMAN;out/pictures/*;move;Move', ''),
('0e45279d2ec42b72462434fccaf60f2b', 'Modul moe_backup sichern', '', 'moe_backup', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/moe/moe_backup/', 'modules/moe/moe_backup/.git', ''),
('4df525a6d0102bbfe9a52d9cffd0ef15', 'Modul azimport sichern', '', 'azimport', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/anzido/azimport/', '', ''),
('db611a28fbc57d31d0cb47727765d30f', 'Theme Freizeit 01 sichern', '', 'ThemeFreizeit01', '0000-00-00 00:00:00', 'data/backup/ThemeFreizeit01/', 'data/backup/ThemeFreizeit01/', 'application/views/freizeit01/', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `moe_backup`
--
ALTER TABLE `moe_backup`
 ADD PRIMARY KEY (`OXID`);
