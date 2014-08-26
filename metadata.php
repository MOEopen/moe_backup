<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'moe_backup',
    'title'        => 'MOe :: Backup',
    'description'  => 'Shopsicherung',
    'thumbnail'    => '',
    'version'      => '0.0.1',
    'author'       => 'M. Oehme',
    'url'          => 'http://www.freizeit.tips',
    'email'        => '',
    'extend' => array(
    ),
    'files'        => array(
      // 'moe_backup'      => 'moe/moe_backup/controllers/moe_backup.php',
      // 'moe_message'     => 'moe/Lib/Traits/moe_messages.php',
      
      // 'moe_backup'                 => 'moe/moe_backup/models/moe_backup.php',
      'moe_backup'                 => 'moe/moe_backup/models/moe_backup.php',
      'moe_backup_list'            => 'moe/moe_backup/models/moe_backup_list.php',
      'moe_backup_files'           => 'moe/moe_backup/models/moe_backup_files.php',
      'moe_backup_admin'           => 'moe/moe_backup/controllers/admin/moe_backup_admin.php',
      'moe_backup_admin_main'      => 'moe/moe_backup/controllers/admin/moe_backup_admin_main.php',
      'moe_backup_admin_list'      => 'moe/moe_backup/controllers/admin/moe_backup_admin_list.php',
      'moe_backup_events'          => 'moe/moe_backup/core/moe_backup_events.php',
    ),
    'events'       => array(
      'onActivate'   => 'moe_backup_events::onActivate',
      'onDeactivate' => 'moe_backup_events::onDeactivate'
    ),
    'templates' => array(
      'moe_backup.tpl'             => 'moe/moe_backup/views/tpl/moe_backup.tpl',
      'moe_backup_admin.tpl'       => 'moe/moe_backup/views/admin/moe_backup_admin.tpl',
      'moe_backup_admin_main.tpl'  => 'moe/moe_backup/views/admin/moe_backup_admin_main.tpl',
      'moe_backup_admin_list.tpl'  => 'moe/moe_backup/views/admin/moe_backup_admin_list.tpl'
    ),
    'settings' => array(
      array('group' => 'backupmain', 'name' => 'moe_BackupStarterFile',       'type' => 'str',   'value' => 'modules/moe/moe_backup/public/moe_backup_starter.php'),
    ),

);