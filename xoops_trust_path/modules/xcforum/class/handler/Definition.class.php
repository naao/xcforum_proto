<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit;
}

/**
 * Xcforum_DefinitionObject
**/
class Xcforum_DefinitionObject extends Legacy_AbstractObject
{
    const PRIMARY = 'definition_id';
    const DATANAME = 'definition';

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function __construct()
    {
        parent::__construct();  
        $this->initVar('definition_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('field_name', XOBJ_DTYPE_STRING, '', false, 32);
        $this->initVar('label', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('field_type', XOBJ_DTYPE_STRING, '', false, 16);
        $this->initVar('validation', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('required', XOBJ_DTYPE_INT, '', false);
        $this->initVar('weight', XOBJ_DTYPE_INT, '', false);
        $this->initVar('show_list', XOBJ_DTYPE_INT, '', false);
        $this->initVar('search_flag', XOBJ_DTYPE_INT, '', false);
        $this->initVar('description', XOBJ_DTYPE_TEXT, '', false);
        $this->initVar('options', XOBJ_DTYPE_TEXT, '', false);
        $this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);
   }

    /**
     * getShowStatus
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getShowStatus()
    {
        switch($this->get('status')){
            case Lenum_Status::DELETED:
                return _MD_LEGACY_STATUS_DELETED;
            case Lenum_Status::REJECTED:
                return _MD_LEGACY_STATUS_REJECTED;
            case Lenum_Status::POSTED:
                return _MD_LEGACY_STATUS_POSTED;
            case Lenum_Status::PUBLISHED:
                return _MD_LEGACY_STATUS_PUBLISHED;
        }
    }

	public function getImageNumber()
	{
		return 0;
	}

}

/**
 * Xcforum_DefinitionHandler
**/
class Xcforum_DefinitionHandler extends Legacy_AbstractClientObjectHandler
{
    public /*** string ***/ $mTable = '{dirname}_definition';
    public /*** string ***/ $mPrimary = 'definition_id';
    public /*** string ***/ $mClass = 'Xcforum_DefinitionObject';

    /**
     * __construct
     * 
     * @param   XoopsDatabase  &$db
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public function __construct(/*** XoopsDatabase ***/ &$db,/*** string ***/ $dirname)
    {
        $this->mTable = strtr($this->mTable,array('{dirname}' => $dirname));
        parent::XoopsObjectGenericHandler($db);
    }

}

?>
