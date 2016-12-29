<?php

class moe_backup_files {
  protected $sBackupSchemaArgName = 'BackupSchema';
  protected $sListSeparator       = ";";
  protected $sCompressCommand     = "tar czf ";
  protected $sCompressExtention   = ".tar.gz";
  
  // protected $sCompressCommand     = "zip -r ";
  // protected $sCompressExtention   = ".zip";
  
  protected $aCompressionSchemes = array(
                                          'gz'  => array( 'sCompressCommand' => 'tar czf '  , 'sCompressExtention' => '.tar.gz'),
                                          'zip' => array( 'sCompressCommand' => 'zip -rqy ' , 'sCompressExtention' => '.zip'),
                                        );
  
  const EOL = "<br />\n";
  
  public function getBackupStarterFile() {
    $sBackupStarterFile = oxRegistry::getConfig()->getConfigParam( 'sShopURL' ) . oxRegistry::getConfig()->getConfigParam( 'moe_BackupStarterFile' ) . '?' . $this->getBackupSchemaArgName() . '=';
    return $sBackupStarterFile;
  }
  
  public function execute($sOxid) {
	// $aBackupDef     = $this->getBackupDefFromDb($sOxid);
    $this->setOxid( $sOxid );
    $this->setLog( "Beginne mit ausführen des Backupschemas [{$this->getBackupDeffField( $this->getOxid(), 'OCBTITLE')}]");
    // echo "<pre>".print_r( $this, true )."</pre>";die();
    $this->setCompressionsCommands();
    // Destination for Backupfiles
    $sBackupSaveDir = oxRegistry::getConfig()->getConfigParam( 'sShopDir' ) . $this->revisedPath( $this->getBackupDeffField( $this->getOxid(), 'MOEBACKUPDIR'), false );
    // This Dir will be saved
    $BackupDir       = oxRegistry::getConfig()->getConfigParam( 'sShopDir' ) . $this->revisedPath( $this->getBackupDeffField( $this->getOxid(), 'MOEINCLUDE'), false );
    $sBackupFileName = $this->getBackupDeffField( $this->getOxid(), 'MOEFILEPREFIX') . '_' . strftime("%Y-%m-%d_%H-%M-%S", time()) . $this->sCompressExtention;
    $aExcludedDirs   = $this->getExcludedDirs( $this->getBackupDeffField( $this->getOxid(), 'MOEEXCLUDE') );
    // echo $aExcludedDirs . "\n";
    $sCommand        = $this->sCompressCommand . $sBackupSaveDir . $sBackupFileName . $aExcludedDirs . ' ' . $BackupDir;
    // echo $sCommand.self::EOL;
    // echo "Test, Funktion:". __function__ .", Line: ". __line__ ."<br>";
    $sSystemOutput = system($sCommand, $MyResult);
    if ($MyResult != 0)
    {
      $this->setLog( 'Fehler beim Speichern der Shop-Dateien' );
      $this->setLog( $sCommand );
      $this->setLog( "Systemmeldungen: ".$sSystemOutput );
      return;
    } else {
      $this->setLog( "Speichern der Shop-Dateien des Backupschemas [{$this->getBackupDeffField( $this->getOxid(), 'OCBTITLE')}] erfolgreich" );
    }
    // $Test = "/data/test";
    // echo $this->revisedPath( $Test, false );
    $this->executeLog();
  }
  
  protected function setCompressionsCommands() {
    $sCompressionScheme = $this->getBackupDeffField($this->getOxid(), "MOECOMPRESSIONSCHEME");
    if( empty( $sCompressionScheme ) ) return;
    if( empty( $this->aCompressionSchemes[$sCompressionScheme] ) ) return;
    $this->sCompressCommand   = $this->aCompressionSchemes[$sCompressionScheme]['sCompressCommand'];
    $this->sCompressExtention = $this->aCompressionSchemes[$sCompressionScheme]['sCompressExtention'];
  }
  
  protected $aBackupDef = array();
  
  protected function getBackupDeffField($Oxid, $Fieldname) {
    $aBackupDef     = $this->getBackupDefFromDb($Oxid);
    return $aBackupDef[$Fieldname];
  }
  
  public function getBackupDefFromDb($Oxid = null) {
    if( !empty( $this->aBackupDef[$Oxid] ) ) {
      return $this->aBackupDef[$Oxid];
    }
    $oDb = oxDb::getDb( oxDb::FETCH_MODE_ASSOC );
    $sSql = "SELECT * FROM `moe_backup` WHERE `OXID` LIKE ".$oDb->quote( $Oxid )." LIMIT 0, 1;";
    if ( ( $aBackupDef = $oDb->select( $sSql ) ) ) {
      $this->aBackupDef[$Oxid] = $aBackupDef->fields;
      return $this->aBackupDef[$Oxid];
    }
    return false;
  }
  
  protected $sOxid;
  
  protected function setOxid( $sOxid ) {
    $this->sOxid = $sOxid;
  }
  
  protected function getOxid() {
    return $this->sOxid;
  }
  
  protected function revisedPath( $Path, $bFirstShlash = true ) {
    if( $bFirstShlash === false ) {
      if (substr($Path, 0, 1) == "/")  $Path = substr($Path, 1, strlen($Path));
    }
    if (substr($Path, -1) != "/") $Path = $Path."/";
    return $Path;
  }
  
  protected function getPath($Path) {
    if (empty($Path)) die("Bei dem Aufruf der Funktion getParam() wurde kein Wert übergeben");
    
    // Versuche konischen (absoluten) Pfad zu erzeugen, falls der Pfad nicht existiert wird false zurückgegeben
    $_sPath = realpath($Path);
    
    // Wenn false, dann soll neuer Pfad angelegt werden. Falls das nicht möglich ist wird das Script abgebrochen
    if(!$_sPath)
    {
      if (!mkdir($Path, 0777, true)) die("<b>Fehler:</b> Das Importverzeichniss ".$Path." ist nicht vorhanden und kann nicht angelegt werden.");
      $_sPath = realpath($Path);
    }
    return $_sPath;
  }
  
  private function getExcludedDirs( $sExcludedDirs ) {
    if( !empty( $sExcludedDirs ) ) {
      $aExcludedDirs = explode( $this->getListSeparator(), $sExcludedDirs);
      $sToBackupShopDir = oxRegistry::getConfig()->getConfigParam( 'sShopDir' );
      $sRetExcludedDirs = '';
      
      foreach ($aExcludedDirs as $sExcludedDir)
      {
        $sRetExcludedDirs .= ' --exclude='.$sToBackupShopDir.$sExcludedDir;
      }
      return $sRetExcludedDirs;
    }
    return;
  }

  public function getListSeparator() {
    return $this->sListSeparator;
  }
  public function setListSeparator( $sListSeparator ) {
    $this->sListSeparator = $sListSeparator;
  }
  
  public function getBackupSchemaArgName() {
    return $this->sBackupSchemaArgName;
  }
  public function setBackupSchemaArgName( $sBackupSchemaArgName ) {
    $this->sBackupSchemaArgName = $sBackupSchemaArgName;
  }
  
  protected $oLogger = null;
  
  protected function executeLog() {
    $Logger = $this->getLogger();
    if( $Logger !== false ) {
       echo "<pre>";
       print_r( $this->oLogger->execute() );
       echo "</pre>";
      $Logger->execute();
      echo "<pre>";
      echo $Logger->getLogAsString();
      echo "</pre>";
    } else {
      echo "Mist";
    }
  }  
  protected function setLog( $sMsg ) {
    $Logger = $this->getLogger();
    if( $this->oLogger !== false ) {
      $this->oLogger->info( $sMsg );
    }
  }
  
  protected function getLogger() {
    if( is_null( $this->oLogger ) ) {
      if( class_exists( 'moe_logger_composite' ) ) {
        // echo "Klasse ist ladbar<br>";
        if( $this->getBackupDeffField( $this->getOxid(), 'MOELOGGINGDIR') == '' ) {
          $this->oLogger = false;
        } else {
          $moe_logger_screen = oxNew( 'moe_logger_screen', 100 );
          $moe_logger_file   = oxNew( 'moe_logger_file', 100, $this->getLogFileName()  );
          $this->oLogger = oxNew( 'moe_logger_composite' );
          $this->oLogger->addLogger( $moe_logger_file );
          $this->oLogger->addLogger( $moe_logger_screen );
        }
      } else {
        $this->oLogger = false;
      }
    }
    return $this->oLogger;
  }
  
  protected function getLogFileName() {
    return $this->getBackupDeffField( $this->getOxid(), 'MOELOGGINGDIR') . $this->getBackupDeffField( $this->getOxid(), 'MOEFILEPREFIX') . '_' . 'Log_' . strftime("%Y-%m", time()). '.log';
  }
  
  protected function echoLog() {
    $aLog = $this->logger->execute();
    echo $aLog['moe_logger_screen'];
  }
}