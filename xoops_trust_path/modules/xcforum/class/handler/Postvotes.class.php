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
 * Xcforum_PostvotesObject
**/
class Xcforum_PostvotesObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'vote_id';
    const DATANAME = 'postvotes';
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
        $this->initVar('vote_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('post_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('uid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('vote_point', XOBJ_DTYPE_INT, '', false);
        $this->initVar('vote_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('vote_ip', XOBJ_DTYPE_INT, '', false);
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
 * Xcforum_PostvotesHandler
**/
class Xcforum_PostvotesHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_postvotes';
    public /*** string ***/ $mPrimary = 'vote_id';
    public /*** string ***/ $mClass = 'Xcforum_PostvotesObject';

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
