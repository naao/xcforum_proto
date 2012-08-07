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
 * Xcforum_AdvanceObject
 **/
class Xcforum_AdvanceObject extends Xcforum_CriteriaObject
{
	const PRIMARY = '';
	const DATANAME = '';
	public $mParentList = array('');

	/**
	 * __construct
	 *
	 * @param   void
	 *
	 * @return  void
	 **/
	public function __construct()
	{

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
 * XcforumAdvanceHandler
 **/
class Xcforum_AdvanceHandler extends Xcforum_CriteriaHandler
//class Xcforum_AdvanceHandler extends XoopsObjectHandler
{
	public /*** string ***/ $mTable = '{dirname}_postvotes';
	public /*** string ***/ $mPrimary = 'vote_id';
	public /*** string ***/ $mClass = 'Xcforum_AdvanceObject';

	public $mDirname ;  // must be public

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
		parent::XoopsObjectGenericHandler($db);
		$this->mDirname = $dirname;
	}

	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false)
	{
	}

	function _makeCriteriaElement4sql($criteria, &$obj)
	{
	}

}

?>
