<?php

class moe_backup extends oxI18n
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'moe_backup';
    
    protected $_sCoreTable = 'moe_backup';
    
    protected $oConfig;
    protected $sShopUrl;
    
    /**
     * Class constructor, sets shop ID for article (oxconfig::getShopId()),
     * initiates parent constructor (parent::oxI18n()).
     *
     * @param array $aParams The array of names and values of oxArticle instance properties to be set on object instantiation
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }
    
    public function getBackupStarterFile() {
      $backup = oxNew( 'moe_backup_files' );
      return $backup->getBackupStarterFile();
    }
    
    
}