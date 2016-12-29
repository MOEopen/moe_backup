<?php
//ini_set("display_errors",true);
//ini_set("error_reporting",E_ALL ^ E_NOTICE ^ E_WARNING);

// zu erreichen unter: http://ce1.truman.de.server917-han.de-nserver.de/modules/moe/moe_backup/controllers/moe_backup_starter.php
require_once( '../../../../bootstrap.php' );
echo "<h1>Backup Test</h1><br>\n";

$backup = oxNew( 'moe_backup_files' );
$Oxid = oxRegistry::getConfig()->getRequestParameter( $backup->getBackupSchemaArgName() );

$backup->execute( $Oxid );
