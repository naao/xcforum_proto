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
 * Xcforum_Users2topicsObject
**/
class Xcforum_Users2topicsObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'uid';
    const DATANAME = 'users2topics';
    public $mParentList = array('topics');

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
        $this->initVar('uid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('u2t_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('u2t_marked', XOBJ_DTYPE_INT, '', false);
        $this->initVar('u2t_rsv', XOBJ_DTYPE_INT, '', false);
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
 * Xcforum_Users2topicsHandler
**/
class Xcforum_Users2topicsHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_users2topics';
    public /*** string ***/ $mPrimary = 'uid';
    public /*** string ***/ $mClass = 'Xcforum_Users2topicsObject';

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
