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
require_once XCFORUM_TRUST_PATH . '/class/handler/Criteria.class.php';

/**
 * Xcforum_PosthistoriesObject
**/
class Xcforum_PosthistoriesObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'history_id';
    const DATANAME = 'posthistories';
    public $mParentList = array('posts');

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
        $this->initVar('history_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('post_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('history_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('data', XOBJ_DTYPE_TEXT, '', false);
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
 * Xcforum_PosthistoriesHandler
**/
class Xcforum_PosthistoriesHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_posthistories';
    public /*** string ***/ $mPrimary = 'history_id';
    public /*** string ***/ $mClass = 'Xcforum_PosthistoriesObject';

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
