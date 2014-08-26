<?php

class moe_backup_admin_list extends oxAdminList
{

    /**
     * Current class template name.
     * @var string
     */
    protected $_sThisTemplate = 'moe_backup_admin_list.tpl';
    
    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'moe_backup';

    /**
     * Type of list.
     *
     * @var string
     */
    protected $_sListType = 'moe_backup_list';
}
