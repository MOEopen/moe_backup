<?php

class moe_backup_admin_main extends oxAdminView
{

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'moe_backup_admin_main.tpl';
    
        /**
     * Loads contents info, passes it to Smarty engine and
     * returns name of template file "content_main.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();

        parent::render();

        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();

        // Product
        $oProductList = oxNew( "oxArticleList" );
        $oProductList->selectString("SELECT * FROM oxarticles WHERE oxparentid = '' AND oxactive = 1 ORDER BY oxtitle");

        $oBackup = oxNew( "moe_backup" );
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oBackup->loadInLang( $this->_iEditLang, $soxId );

            $oOtherLang = $oBackup->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oBackup->loadInLang( key($oOtherLang), $soxId );
            }

            // remove already created languages
            $aLang = array_diff ( oxRegistry::getLang()->getLanguageNames(), $oOtherLang );
            if ( count( $aLang))
                $this->_aViewData["posslang"] = $aLang;
            foreach ( $oOtherLang as $id => $language) {
                $oLang= new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] =  clone $oLang;
            }

        }

        $this->_aViewData["edit"] = $oBackup;
        $this->_aViewData["productlist"] = $oProductList;

        return $this->_sThisTemplate;
    }
    
    public function save()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        
//        $aParams = oxConfig::getRequestParameter( "editval");
        $aParams = oxRegistry::getConfig()->getRequestParameter( "editval");
        
        if ( $soxId == "-1")
        {
            $aParams['moe_backup__oxid'] = null;
        }
        
        $oBackup = oxNew('moe_backup');
        $oBackup->setLanguage(0);
        $oBackup->assign($aParams);
        $oBackup->setLanguage($this->_iEditLang);
        $oBackup->save();

        // set oxid if inserted
        $this->setEditObjectId( $oBackup->getId() );
    }
    
    
    /**
     * Saves content data to different language (eg. english).
     *
     * @return null
     */
    public function saveinnlang()
    {
        parent::save();

        $soxId = $this->getEditObjectId();
        
        $aParams = oxConfig::getRequestParameter("editval");

        $oBackup = oxNew( "moe_backup" );

        if ( $soxId != "-1")
        {
            $oBackup->loadInLang( $this->_iEditLang, $soxId );
        }
        else
        {
            $aParams['moe_backup__oxid'] = null;
        }
        $oBackup->setLanguage(0);
        $oBackup->assign($aParams);

        // apply new language
        $oBackup->setLanguage( oxConfig::getRequestParameter( "new_lang" ) );
        $oBackup->save();

        // set oxid if inserted
        $this->setEditObjectId( $oBackup->getId() );
    }
    
}
