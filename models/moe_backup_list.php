<?php

class moe_backup_list extends oxList
{
    
    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = 'moe_backup';
    
    public function getTourdatesForProduct( $productId )
    {
        $sTable = getViewName('moe_backup');
                
        $sSelect  = "SELECT * FROM $sTable ";
        $sSelect .= "WHERE OCBTICKET = '" . $productId . "'";
        
        $this->selectString($sSelect);
    }
}