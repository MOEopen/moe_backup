<?php
//ini_set("display_errors",true);
//ini_set("error_reporting",E_ALL ^ E_NOTICE ^ E_WARNING);

require_once( '../../../../bootstrap.php' );
// echo "<h1>Backup Test</h1><br>\n";

$oConfig  = oxRegistry::getConfig();
$sShopDir = $oConfig->getConfigParam( 'sShopDir' );

$oDb = oxDb::getDb();

$sDbHost     = $oConfig->getConfigParam('dbHost');
$sDbName     = $oConfig->getConfigParam('dbName');
$sDbUser     = $oConfig->getConfigParam('dbUser');
$sDbPassword = $oConfig->getConfigParam('dbPwd');

$sTargetDbHost     = '127.0.0.1';
$sTargetDbName     = "usrdb_ee2qklfr11";
$sTargetDbUser     = "ee2qklfr11";
$sTargetDbPassword = "ydhvfjwb";

$aMysqldumpPath = array(
    '77.75.250.123'  => '/usr/local/mysql5/bin/mysqldump',
    '77.75.249.58'   => '/usr/local/mysql5/bin/mysqldump',
    '192.168.88.132' => '/usr/bin/mysqldump',
    '83.138.82.125' => '/usr/iports/bin/mysqldump',
    '83.138.83.54' => 'mysqldump',
);
$sMysqldumpPath = @$aMysqldumpPath[$_SERVER['SERVER_ADDR']];
if(empty($sMysqldumpPath)) die('Kein Pfad zu mysqldump hinterlegt, f�r Server IP '.$_SERVER['SERVER_ADDR']);

switch( @$_REQUEST['interval'] ){
    case 'monat':
        $sDir = 'data/backup/database_mon/';
        break;
    case 'tag':
        $sDir = 'data/backup/database_tag/';
        break;
    case 'stunde':
    default :
        $sDir = 'data/backup/database_std/';
}
$sBackupFile = $sDir.strftime("%Y-%m-%d_%H-%M-%S", time()) . "_database_" . $sDbName.'.sql';
$sBackupFileAndDir = $sShopDir.$sBackupFile.'.gz';
// echo $sBackupFileAndDir;
// die();

$sSqlShowViews = "show full tables where table_type = 'VIEW'";			// VIEWS ermitteln
$oViews = $oDb->execute($sSqlShowViews);
$aViews = array();
while (!$oViews->EOF) { 
  $aViews[] = $oViews->fields[0];
  $oViews->MoveNext();
}
// print_r( $aViews );

$sIgnoreTable   = " --ignore-table=";
$sViews = $sIgnoreTable.$sDbName.".".implode($sIgnoreTable.$sDbName.".",$aViews);		// Alle nicht zu sichernden Tabellen
// echo $sViews;

// $sMySqlDump = "/usr/local/mysql5/bin/mysqldump --opt --quick".$sViews." --host=127.0.0.1 --user=".$sDbUser." --password=".$sDbPassword." ".$sDbName ." > ".$sBackupFileAndDir;

switch( @$_REQUEST['job'] ) {
    case 'dump':
        $sMySqlDump = $sMysqldumpPath ." --opt --quick".$sViews." --host=".$sDbHost." --user=".$sDbUser." --password=".$sDbPassword." ".$sDbName ." | gzip > ".$sBackupFileAndDir;
        break;
    case 'copy':
        $sMySqlDump = $sMysqldumpPath ." --opt --quick".$sViews." --host=127.0.0.1 --user=".$sDbUser." --password=".$sDbPassword." ".$sDbName ." | /usr/local/mysql5/bin/mysql --user={$sTargetDbUser} --password={$sTargetDbPassword} --host={$sTargetDbHost} {$sTargetDbName}";
        break;
    default:
        die('Nicht berechtigter Zugriff!');
}

// echo $sMySqlDump;

$sSystemOutput = system($sMySqlDump, $MyResult);
if ($MyResult != 0)
{
  echo 'Fehler bei dem Erzeugen des MySqlDumps<br>';
  echo $sMySqlDump."<br>";
  echo "Systemmeldungen: ".$sSystemOutput."<br>";
  return;
} else {
  echo 'Erzeugen des MySqlDumps erfolgreich<br>';
}

