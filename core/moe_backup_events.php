<?php

class moe_backup_events {
  
  public static $sBackupDefTable = "moe_backup";
  
  /**
   * Create Backuptable
   */
  public static function adBackupTableDef() {
    $sSql = "CREATE TABLE IF NOT EXISTS `moe_backup` (
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
             ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    oxDb::getDb()->execute( $sSql );
  }
  
  /**
   * Create Indexes
   */
  public static function adBackupTableIndexes() {
    $sSql = "ALTER TABLE `moe_backup` ADD PRIMARY KEY (`OXID`);";
    oxDb::getDb()->execute( $sSql );
  }
  
  /**
   * Insert Backup Actions
   */
  public static function adBackupTableBasicContent() {
    $aSql[] = "INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('b029da7d9163b9d97f569dfc1393f17f', 'Module Sichern', '', 'Modules', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/', 'modules/moe/moe_backup/.git', 'gggvvvv');";
    $aSql[] = "INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('2d975b6493b0719c9d6a145393f8e89a', 'Komplettsicherung', '', 'Backup_all', '0000-00-00 00:00:00', 'data/backup/shop/', 'data/backup/shop/', '', 'tmp/*;data/backup/*;data/import/pictures/*;statistik;dumper;cgi-bin;FTP;phpMyAdmin*;export/TRUMAN;out/pictures/*;move;Move', '');";
    $aSql[] = "INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('0e45279d2ec42b72462434fccaf60f2b', 'Modul moe_backup sichern', '', 'moe_backup', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/moe/moe_backup/', 'modules/moe/moe_backup/.git', '');";
    $aSql[] = "INSERT INTO `moe_backup` (`OXID`, `OCBTITLE`, `OCBTITLE_1`, `MOEFILEPREFIX`, `OCBDATETIME`, `MOEBACKUPDIR`, `MOELOGGINGDIR`, `MOEINCLUDE`, `MOEEXCLUDE`, `MOEDESCRIPTION`) VALUES('4df525a6d0102bbfe9a52d9cffd0ef15', 'Modul azimport sichern', '', 'azimport', '0000-00-00 00:00:00', 'data/backup/modules/', 'data/backup/modules/', 'modules/anzido/azimport/', '', '');";
    
    foreach( $aSql as $sSql ) {
      oxDb::getDb()->execute( $sSql );
    }
  }
  
  /**
   * Proof, if Table exists
   */
  public static function existTable($sTable) {
    $sSql = "SHOW TABLES LIKE '{$sTable}'";
    $oDb  = oxDb::getDb();
    $rs   = $oDb->select( $sSql );
    if ($rs != false && $rs->recordCount() > 0) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Getter for Backup Definition Table
   */
  public static function getBackupDefTable() {
    return self::$sBackupDefTable;
  }
  
  /**
   * Delete Backup Table
   */
  public static function deleteBackuptable() {
    $sSql = "DROP TABLE IF EXISTS moe_backup;";
    oxDb::getDb()->execute( $sSql );
  }
  
  /**
   * Execute action on activate event
   */
  public static function onActivate() {
    $_sBackupDefTable = self::getBackupDefTable();
    if( self::existTable( $_sBackupDefTable ) != true ) {
      // Create Backuptable
      self::adBackupTableDef();
      
      // Create Indexes
      self::adBackupTableIndexes();
      
      // Insert Backup Actions
      self::adBackupTableBasicContent();
    }
  }

  /**
   * Execute action on deactivate event
   */
  public static function onDeactivate() {
    // Delete Backup Table
    // self::deleteBackuptable();
  }

}

?>