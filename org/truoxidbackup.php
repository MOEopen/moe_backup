<?php

class truoxidbackup extends oxUBase
{
	// Aufruf über http://{Shop_Dir}/?cl=truoxidbackup?cl=truoxidbackup&sBackupTyp=

	protected $sTOP_DIR 					= "/devjjbxt";
	protected $sReplaceNeedle 				= array("/", "\\", " ");
	protected $sIgnoreTable   = " --ignore-table=";

	protected $_sDbHost; 
	protected $_sDbName;
	protected $_sDbUser;
	protected $_sDbPassword;

  private $sShopDir;
  
  private $bLogToScreenSuccess = true;
  private $bLogToScreenFail    = true;
  private $bLogToFileSuccess   = false;
  private $bLogToFileFail      = false;
	
	public $sDataBackupPath       = 'data/backup/database/';
	public $sBackupPicShopPath    = 'data/backup/pic_shop/';
	public $sBackupPicImportPath  = 'data/backup/pic_import/';
	public $sFilesBackupPath      = 'data/backup/shop/';
  public $sCsvBackupPath        = 'data/backup/CSV/';
  public $sModulesBackupPath    = 'data/backup/modules/';
  public $sAllBackupPath;
  
  public $sToBackupPicDir       = 'out/pictures/';
  public $sToBackupImportDir    = 'data/import/';
  public $sToBackupModulesDir   = 'modules/';
  public $sToBackupShopDir;     
  
  private $aExcludedDirs = array();
  private $sExcludedDirs;

  private $oConfig;

  protected $_sThisTemplate = 'truoxidbackup.tpl';

  const EOL = "<br />\n";
  
  private $sCsvDelimiter = ';';
  private $sCsvEnclosure = '|';
  private $sCsvEscaped   = '\\';
  private $sCsvEOL       = "\n";
  
  private $oSmarty;
  
  public function init()
  {
    parent::init();
    if (empty($this->oConfig))  $this->oConfig  = oxConfig::getInstance();
    if (empty($this->sShopDir)) $this->sShopDir = $this->oConfig->getConfigParam( 'sShopDir' );
    $this->oSmarty = oxUtilsView::getInstance()->getSmarty();
  }

  public function getPathAbs($sBackupPath){
    // Gibt validen Pfad zum Sicherungsverzeichnis zurück
    $this->sAllBackupPath = $this->getVar($sBackupPath);
    $this->sAllBackupPath = $this->getPath($this->sShopDir.$this->sAllBackupPath);
    $this->sAllBackupPath = $this->getValidatePath($this->sAllBackupPath);
    return  $this->sAllBackupPath;;
  }
  
  
  private function getBackupFilenamePrefix()
  {
    $return1 = str_replace($this->sReplaceNeedle, "_", $this->sShopDir);
    $return2 = str_replace("_home_", "", $return1);
    return $return2;
  }
	
  private function getPath($Path) {
    if (empty($Path)) die("Bei dem Aufruf der Funktion getParam() wurde kein Wert übergeben");
    
    // Versuche konischen (absoluten) Pfad zu erzeugen, falls der Pfad nicht existiert wird false zurückgegeben
    $_sPath = realpath($Path);
    
    // Wenn false, dann soll neuer Pfad angelegt werden. Falls das nicht möglich ist wird das Script abgebrochen
    if(!$_sPath)
    {
      if (!mkdir($Path, 0777, true)) die("<b>Fehler:</b> Das Importverzeichniss ".$Path." ist nicht vorhanden und kann nicht angelegt werden.");
      $_sPath = realpath($Path);
    }
    if(substr($Path, 0, 6) == '/home/' AND substr($_sPath, 0 , 6) != '/home/')
    {
      $_sPath = '/home'.$_sPath;
    }
    return $_sPath;
  }
  
  private function getValidatePath($Path) {
    //if (substr($Path, 0, 1) == "/")  $Path = substr($Path, 1, strlen($Path));
    if (substr($Path, -1) != "/") $Path = $Path."/";
    return $Path;
  }
  
	private function getVar($VarName)
		{
			if (empty($VarName)) $die('Bei dem Aufruf der Funktion getVar() wurde kein Wert übergeben');
			
			//http://dev11.truman.de.server917-han.de-nserver.de/index.php?cl=tru_ModulVorlagen_Variablen&s_tru_ModulVorlagen_Var1=var_aus_request
			if ($_REQUEST[$VarName] == 'true') return true;
			if ($_REQUEST[$VarName] == 'false') return false;
			If (isset($_REQUEST[$VarName]) and $_REQUEST[$VarName] != '')
			{
			  return $_REQUEST[$VarName];
			}
			
			if (empty($this->oConfig)) $this->oConfig = oxConfig::getInstance();
			$VarValue = $this->oConfig->getConfigParam( $VarName );
			if (is_bool($VarValue))
			{
			  return $VarValue;
			} elseif(!is_null($VarValue) and $VarValue != '') {
			  return $VarValue;
			}
			
			if(isset($this->$VarName) and $this->$VarName != '')
			{
			  return $this->$VarName;
			}
			
			return false;
		} 
 
	
  public function render()
  {
    set_time_limit(0);
    parent::render();

    $aCommands = array(
                      'data' => array(
                                      'Ausgabe'  => 'Starte mit dem Erzeugen eines Datenbankdumps'.self::EOL, 
                                      'function' => 'backupTruDb',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=data'>Erzeugen eines Datenbankdumps</a>",
                                      ),
                      'datatables' => array(
                                      'Ausgabe'  => 'Starte mit dem Erzeugen eines Datenbankdumps einzelner Tabellen'.self::EOL, 
                                      'function' => 'backupTruTables',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=datatables'>Erzeugen eines Datenbankdumps einzelner Tabellen</a>",
                                      ),
                      'datatablescsv' => array(
                                      'Ausgabe'  => 'Starte mit dem Erzeugen eines Datenbankdumps einzelner Tabellen als CSV-Datei'.self::EOL, 
                                      'function' => 'backupTruTablesCsv',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=datatablescsv'>Erzeugen eines Datenbankdumps einzelner Tabellen als CSV-Datei</a>",
                                      ),
                      'picture' => array(
                                      'Ausgabe'  => 'Starte mit der Sicherung der Shop-Bilder'.self::EOL, 
                                      'function' => 'backupPicShop',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=picture'>Sicherung der Shop-Bilder</a>",
                                      ),
                      'import' => array(
                                      'Ausgabe'  => 'Starte mit der Sicherung der Import-Bilder'.self::EOL, 
                                      'function' => 'backupPicImport',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=import'>Sicherung der Sever-Bilder</a>",
                                      ),
                      'shopfiles' => array(
                                      'Ausgabe'  => 'Starte mit der Sicherung der Shop-Dateien'.self::EOL, 
                                      'function' => 'backupShopfiles',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=shopfiles'>Shop-Dateien</a>",
                                      ),
                      'modules' => array(
                                      'Ausgabe'  => 'Starte mit der Sicherung der Modul-Dateien'.self::EOL, 
                                      'function' => 'backupModules',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=modules'>Sicherung der Module</a>",
                                      ),
                      'ExcludedDirs' => array(
                                      'Ausgabe'  => 'Starte mit der Ausgabe der ExcludedDirs'.self::EOL, 
                                      'function' => 'getExcludedDirs',
                                      'Link'     => "<a href='?cl=truoxidbackup&sBackupTyp=ExcludedDirs'>ExcludedDirs</a>",
                                      ),
                      );
    
    $aCommand = $aCommands[$_GET['sBackupTyp']];
    if (empty($aCommand['function'])) {
      echo 'Es wurde keine korrekte Funktion übergeben!';
    } else {
      $this->Ausgabe( $aCommand['Ausgabe'] , 2);
      $this->$aCommand['function']();
    }
    
    $this->oSmarty->assign('aCommands', $aCommands);
    //$this->monitor($this->oSmarty->_tpl_vars['SmartyAusgabe'], true);
    $this->SmartyAusgabe = "";
    return $this->_sThisTemplate;

  }
  
  public function Standardausgabe() {
    $sAusgabe .= "<br />";
    $sAusgabe .= "<a href='?cl=truoxidbackup&sBackupTyp=data'>Erzeugen eines Datenbankdumps</a><br />";
    $sAusgabe .= "<a href='?cl=truoxidbackup&sBackupTyp=picture'>Sicherung der Shop-Bilder</a><br />";
    $sAusgabe .= "<a href='?cl=truoxidbackup&sBackupTyp=import'>Sicherung der Sever-Bilder</a><br />";
    $sAusgabe .= "<a href='?cl=truoxidbackup&sBackupTyp=shopfiles'>Shop-Dateien</a><br />";
    $sAusgabe .= "<a href='?cl=truoxidbackup&sBackupTyp=modules'>Sicherung der Module</a><br />";
    $sAusgabe .= "<br /><hr><br />";
    return $sAusgabe;
  }

  public function Paramausgabe() {
    $sPAusgabe .= "<br />";
    $sPAusgabe .= "<a href='?cl=truoxidbackup&sBackupTyp=ExcludedDirs'>ExcludedDirs</a><br />"; 
    $sPAusgabe .= "<br /><hr><br />";
    return $sPAusgabe;
  }

  private function Ausgabe($Meldung, $Success)
  {
    $SmartyAusgabe = $this->oSmarty->_tpl_vars['SmartyAusgabe'];
    switch ($Success)
    {
      case 0:
        if($this->getVar('bLogToScreenFail')) $this->oSmarty->assign('SmartyAusgabe', $SmartyAusgabe.$Meldung);
        break;
      case 1:
        if($this->getVar('bLogToScreenSuccess')) $this->oSmarty->assign('SmartyAusgabe', $SmartyAusgabe.$Meldung);
        break;
      case 2:
        if(($this->getVar('bLogToScreenFail')) or ($this->getVar('bLogToScreenSuccess'))) $this->oSmarty->assign('SmartyAusgabe', $SmartyAusgabe.$Meldung);
        break;
    }
  }
  
	private function backupTruDb()	{
		startprofile('#'.__CLASS__.'#'.__FILE__);
		
    $oDb = oxDb::getDb();
    
		$this->_sDbHost     = $this->getConfig()->getConfigParam('dbHost');
		$this->_sDbName     = $this->getConfig()->getConfigParam('dbName');
		$this->_sDbUser     = $this->getConfig()->getConfigParam('dbUser');
		$this->_sDbPassword = $this->getConfig()->getConfigParam('dbPwd');

		$this->sBackupFile = $this->getBackupFilenamePrefix() . strftime("%Y-%m-%d_%H-%M-%S", time()) . "_database_" . $this->_sDbName.'.sql';
    $this->sBackupFileAndDir = $this->getPathAbs('sDataBackupPath').$this->sBackupFile;					
		
    $this->sSqlShowViews = "show full tables where table_type = 'VIEW'";			// VIEWS ermitteln
		$oViews = $oDb->execute($this->sSqlShowViews);
		$aViews = array();
		while (!$oViews->EOF) { 
		  $this->aViews[] = $oViews->fields[0];
		  $oViews->MoveNext();
		}
		$this->sViews = $this->sIgnoreTable.$this->_sDbName.".".implode($this->sIgnoreTable.$this->_sDbName.".",$this->aViews);		// Alle nicht zu sichernden Tabellen
		$this->sMySqlDump = "/usr/local/mysql5/bin/mysqldump --opt --quick".$this->sViews." --host=127.0.0.1 --user=".$this->_sDbUser." --password=".$this->_sDbPassword." ".$this->_sDbName ." > ".$this->sBackupFileAndDir;

		$sSystemOutput = system($this->sMySqlDump, $MyResult);
		if ($MyResult != 0)
    {
      $this->Ausgabe( 'Fehler bei dem Erzeugen des MySqlDumps'.self::EOL, 1);
      $this->Ausgabe( $this->sMySqlDump.self::EOL, 1);
      $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
      return;
    } else {
      $this->Ausgabe( 'Erzeugen des MySqlDumps erfolgreich'.self::EOL, 2);
    }
	
    $sMyTarOutput = "tar -cz -C ".$this->getPathAbs('sDataBackupPath')." -f ".$this->sBackupFileAndDir.".tar.gz"." ".$this->sBackupFile;
    $sSystemOutput = system($sMyTarOutput, $MyResult);
		if ($MyResult != 0)
    {
      $this->Ausgabe( 'Fehler beim Erzeugen des Tarballs'.self::EOL, 1);
      $this->Ausgabe( $sMyTarOutput.self::EOL, 1);
      $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
      return;
    } else {
      $this->Ausgabe( 'Erzeugen des Tarballs erfolgreich'.self::EOL, 2);
    }
			
		if(!unlink($this->sBackupFileAndDir))
    {
      $this->Ausgabe( 'Fehler beim Löschen des MySqlDumps'.self::EOL, 2);
      return;
    } else {
      $this->Ausgabe( 'Löschen des MySqlDumps erfolgreich'.self::EOL, 2);
    }
		
		stopprofile('#'.__CLASS__.'#'.__FILE__);  
		
		return;
	} 
  
	private function backupTruTables()	{
		startprofile('#'.__CLASS__.'#'.__FILE__);
		
    $oDb = oxDb::getDb();
    
		$this->_sDbHost     = $this->getConfig()->getConfigParam('dbHost');
		$this->_sDbName     = $this->getConfig()->getConfigParam('dbName');
		$this->_sDbUser     = $this->getConfig()->getConfigParam('dbUser');
		$this->_sDbPassword = $this->getConfig()->getConfigParam('dbPwd');
    
    if(empty($_REQUEST['IncTables'])) {
      die('Es wurden keine Tabellen zum sichern angegeben!');
    } else {
      if(is_array($_REQUEST['IncTables'])) {
        $sTablesForFile = implode('_', $_REQUEST['IncTables']);
        $sTablesForDump = implode(' ', $_REQUEST['IncTables']);
      } else {
        $sTablesForFile = $sTablesForDump = $_REQUEST['IncTables'];
      }
      
      $this->sBackupFile = $this->getBackupFilenamePrefix() . strftime("%Y-%m-%d_%H-%M-%S", time()) . "_database_" . $this->_sDbName.'_'.$sTablesForFile.'.xml';
      $this->sBackupFileAndDir = $this->getPathAbs('sDataBackupPath').$this->sBackupFile;					

      $this->sMySqlDump = "/usr/local/mysql5/bin/mysqldump --opt --quick --xml --host=127.0.0.1 --user=".$this->_sDbUser." --password=".$this->_sDbPassword." ".$this->_sDbName ." --tables ".$sTablesForDump." > ".$this->sBackupFileAndDir;
      
      $sSystemOutput = system($this->sMySqlDump, $MyResult);
      if ($MyResult != 0)
      {
        $this->Ausgabe( 'Fehler bei dem Erzeugen des MySqlDumps'.self::EOL, 1);
        $this->Ausgabe( $this->sMySqlDump.self::EOL, 1);
        $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
        return;
      } else {
        $this->Ausgabe( 'Erzeugen des MySqlDumps erfolgreich'.self::EOL, 2);
      }
    }
		
		stopprofile('#'.__CLASS__.'#'.__FILE__);  
		
		return;
	} 
  
  private function backupTruTablesCsv() {
    $sSQL1 ='SELECT * FROM `oxarticles` LIMIT 0 , 1;';
    $oDb = oxDb::getDb(true);
    $this->sBackupFileAndDir = $this->getPathAbs('sCsvBackupPath').'oxarticles.csv';
    $oFilePointer = fopen($this->sBackupFileAndDir, 'w');
    $oHeadline = $oDb->execute($sSQL1);
    while (!$oHeadline->EOF) { 
      $sCsvHeadLine = $this->sCsvEnclosure;
      foreach($oHeadline->fields as $key => $value) {
        $aCsvHeadLine[] = $key;
        
      }
      $oHeadline->MoveNext();
    }
    $sCsvHeadLine .= implode($this->sCsvEnclosure.$this->sCsvDelimiter.$this->sCsvEnclosure, $aCsvHeadLine);
    $sCsvHeadLine .= $this->sCsvEnclosure.$this->sCsvEOL;
    //$this->monitor($sCsvHeadLine);
    fputs($oFilePointer, $sCsvHeadLine);
    $sSQL2 = "SELECT * FROM `oxarticles` LIMIT 0, 999999;";
    $oArticles = $oDb->execute($sSQL2);  
    while (!$oArticles->EOF) { 
      //$sCsvBodyLine = $oArticles->fields;
      
      $sCsvBodyLine  = $this->sCsvEnclosure;
      $sCsvBodyLine .= implode($this->sCsvEnclosure.$this->sCsvDelimiter.$this->sCsvEnclosure, $oArticles->fields);
      $sCsvBodyLine .= $this->sCsvEnclosure.$this->sCsvEOL;
      fputs($oFilePointer, $sCsvBodyLine);
      //$this->monitor($sCsvBodyLine);
      $oArticles->MoveNext();
    }
    fclose($oFilePointer);
    //$this->monitor($sCsvBodyLine);
  }
	 		
  private function backupPicShop()	{
			startprofile('#'.__CLASS__.'#'.__FILE__);
			$this->sBackupSaveDir =$this->getPathAbs('sBackupPicShopPath');			                                                    // Sicherungsverzeichnis
      $this->sToBackupPicDir = $this->sShopDir."out/pictures/";                                                     // zu sichernder Daten/Verzeichnisse
			$this->sBackupFile = $this->getBackupFilenamePrefix() . strftime("%Y-%m-%d_%H-%M-%S", time()) . "_pic-shop";  // Sicherungsdateiname
			//echo "sBackupFileName: $this->sBackupFile <br />";
      $this->sBackupFileAndDir = $this->sBackupSaveDir.$this->sBackupFile;	                                        // Sicherungsverzeichnis + Sicherungsdateiname
			//echo "sBackupFileAndDir: $this->sBackupFileAndDir <br />";
			$sMyTarOutput = "tar czf ".$this->sBackupFileAndDir.".tar.gz"." ".$this->sToBackupPicDir;
      //echo $sMyTarOutput.self::EOL;
     	$sSystemOutput = system($sMyTarOutput, $MyResult);
				if ($MyResult != 0)
      {
        $this->Ausgabe( 'Fehler beim Speichern der Shop-Bilder'.self::EOL, 1);
        $this->Ausgabe( $sMyTarOutput.self::EOL, 1);
        $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
        return;
      } else {
        $this->Ausgabe( 'Speichern der Shop-Bilder erfolgreich'.self::EOL, 2);
      }
			stopprofile('#'.__CLASS__.'#'.__FILE__);
			return;
		}
		
	private function backupPicImport()	{
			startprofile('#'.__CLASS__.'#'.__FILE__);
  
      $this->sBackupSaveDir = $this->getPathAbs('sBackupPicImportPath');			                                                  // Sicherungsverzeichnis
      $this->sToBackupImportDir = $this->sShopDir."data/import/";                                                     // zu sichernder Daten/Verzeichnisse
			//echo "sToBackupImportDir: $this->sToBackupImportDir <br />";
      $this->sBackupFile = $this->getBackupFilenamePrefix() . strftime("%Y-%m-%d_%H-%M-%S", time()) . "_pic-import";  // Sicherungsdateiname
			//echo "sBackupFile: $this->sBackupFile <br />";
			$this->sBackupFileAndDir = $this->sBackupSaveDir.$this->sBackupFile;                                            // Sicherungsverzeichnis + Sicherungsdateiname
			//echo "sBackupFileAndDir: $this->sBackupFileAndDir <br />";
			$sMyTarOutput = "tar czf ".$this->sBackupFileAndDir.".tar.gz"." ".$this->sToBackupImportDir;
      //echo $sMyTarOutput;
  		$sSystemOutput = system($sMyTarOutput, $MyResult);
      	if ($MyResult != 0)
      {
        $this->Ausgabe( 'Fehler beim Speichern der Import-Bilder'.self::EOL, 1);
        $this->Ausgabe( $sMyTarOutput.self::EOL, 1);
        $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
        return;
      } else {
        $this->Ausgabe( 'Speichern der Import-Bilder erfolgreich'.self::EOL, 2);
      }
      stopprofile('#'.__CLASS__.'#'.__FILE__); 
			return;
		}

	private function backupShopfiles()	{
			startprofile('#'.__CLASS__.'#'.__FILE__);
      //$this->sBackupSaveDir = $this->getFilesBackupPath();	                                                           // Sicherungsverzeichnis
      $this->sBackupSaveDir = $this->getPathAbs('sFilesBackupPath');			                                                           // Sicherungsverzeichnis
			$this->sToBackupShopDir = $this->sShopDir;                                                                           // zu sichernder Daten/Verzeichnisse
			//echo "sBackupSaveDir: $this->sBackupSaveDir <br />";
      $this->sBackupFile = $this->getBackupFilenamePrefix() . strftime("%Y-%m-%d_%H-%M-%S", time()) . "_shop";             // Sicherungsdateiname
			//echo "sBackupFile: $this->sBackupFile <br />";
			$this->sBackupFileAndDir = $this->sBackupSaveDir.$this->sBackupFile;                                                 // Sicherungsverzeichnis + Sicherungsdateiname
			//echo "sBackupFileAndDir: $this->sBackupFileAndDir <br />"; 
      
      $sMyTarOutput = 'tar  -czf  '.$this->sBackupFileAndDir.'.tar.gz'.$this->getExcludedDirs().' '.$this->sToBackupShopDir;
      
      //echo $sMyTarOutput.self::EOL;
      $sSystemOutput = system($sMyTarOutput, $MyResult);
      if ($MyResult != 0)
      {
        $this->Ausgabe( 'Fehler beim Speichern der Shop-Dateien'.self::EOL, 1);
        $this->Ausgabe( $sMyTarOutput.self::EOL, 1);
        $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
        return;
      } else {
        $this->Ausgabe( 'Speichern der Shop-Dateien erfolgreich'.self::EOL, 2);
      }
			stopprofile('#'.__CLASS__.'#'.__FILE__);
			return;
		} 
    
  private function backupModules () {
      $this->sBackupSaveDir = $this->getPathAbs('sModulesBackupPath');			                                                  // Sicherungsverzeichnis
      $this->sToBackupImportDir = $this->sShopDir.$this->sToBackupModulesDir;                                                     // zu sichernder Daten/Verzeichnisse
			
      $this->sBackupFile = $this->getBackupFilenamePrefix() . strftime("%Y-%m-%d_%H-%M-%S", time()) . "_modules";  // Sicherungsdateiname
			//echo "sBackupFile: $this->sBackupFile <br />";
			$this->sBackupFileAndDir = $this->sBackupSaveDir.$this->sBackupFile;                                            // Sicherungsverzeichnis + Sicherungsdateiname
			//echo "sBackupFileAndDir: $this->sBackupFileAndDir <br />";
			$sMyTarOutput = "tar czf ".$this->sBackupFileAndDir.".tar.gz"." ".$this->sToBackupImportDir;
      //echo $sMyTarOutput;
  		$sSystemOutput = system($sMyTarOutput, $MyResult);
      	if ($MyResult != 0)
      {
        $this->Ausgabe( 'Fehler beim Speichern der Module'.self::EOL, 1);
        $this->Ausgabe( $sMyTarOutput.self::EOL, 1);
        $this->Ausgabe( "Systemmeldungen: ".$sSystemOutput.self::EOL, 1);
        return;
      } else {
        $this->Ausgabe( 'Speichern der Module erfolgreich'.self::EOL, 2);
      }
     
			return;
		}

    private function getExcludedDirs() {
      $this->sExcludedDirs ="tmp/* data/backup/* data/import/pictures/* statistik dumper cgi-bin FTP phpMyAdmin* export/TRUMAN out/pictures/* move Move";
      $this->aExcludedDirs = explode(' ', $this->sExcludedDirs);
      
      $this->sToBackupShopDir = $this->sShopDir;
      $this->sExcludedDirs = '';
      
      foreach ($this->aExcludedDirs as $sExcludedDir)
      {
        $this->sExcludedDirs .= ' --exclude='.$this->sToBackupShopDir.$sExcludedDir;
      }
     $this->Ausgabe( $this->sExcludedDirs.self::EOL, 2);
     return $this->sExcludedDirs;
    }

  public function monitor($obj, $print = true)
  {
      ob_start();
      echo "\n#########################\n<pre>\n";
      print_r($obj);
      echo "\n</pre>\n";
      $out = ob_get_contents();
      ob_end_clean();
      if ($print == true) echo $out;
      return $out;
  }		
	
		
}	
