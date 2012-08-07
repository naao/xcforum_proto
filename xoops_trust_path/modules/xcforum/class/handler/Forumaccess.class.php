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
require_once XCFORUM_TRUST_PATH . '/class/XcforumUtils.class.php';
require_once XCFORUM_TRUST_PATH . '/class/AssetManager.class.php';

/**
 * Xcforum_ForumaccessObject
**/
class Xcforum_ForumaccessObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'forum_id';
    const DATANAME = 'forumaccess';
    public $mParentList = array('forums');


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
	    $this->initVar('permit_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('uid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('groupid', XOBJ_DTYPE_INT, '', false);
	    $this->initVar('permissions', XOBJ_DTYPE_TEXT, '', false);

	    //atrace(); die;
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
 * Xcforum_ForumaccessHandler
**/
class Xcforum_ForumaccessHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_forumaccess';
    public /*** string ***/ $mPrimary = 'forum_id';
    public /*** string ***/ $mClass = 'Xcforum_ForumaccessObject';

	protected /*** Xcforum_Utils ***/ $mUtils ;

	protected /*** array ***/ $mAccessController = array();

	public $mDirname ;  // must be public

	protected $mRoot;

	protected /*** array ***/ $fIdList4Cat ;
	protected /*** array ***/ $perm_keys = array('post', 'html', 'edit', 'delete', 'auto_approve', 'moderate');
	protected /*** array ***/ $mForumAcc ;

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

	    $this->mUtils = new Xcforum_Utils;
	    $this->mAsset = new Xcforum_AssetManager($this->mDirname);

	    $this->_setupAccessController('forums');
	    $this->mDirname = $dirname;
	    $this->mForumAcc = $this->_get_forum_permissions();
	    //adump($this->mForumAcc);
	    //atrace();
    }

	/**
	 * _getCatId
	 *
	 * @param	void
	 *
	 * @return	int
	 **/
/*	protected function _getCatId()
	{
		return intval($this->mRoot->mContext->mRequest->getRequest('category_id'));
	}
*/
	/**
	 * _setupAccessController
	 *
	 * @param	string	$dataname
	 *
	 * @return	void
	 **/
	protected function _setupAccessController(/*** string ***/ $dataname)
	{   //adump('AccessController Start');
		$this->mAccessController['main'] = Xcforum_Utils::getAccessControllerObject($this->mDirname, $dataname);
		$idList['view'] = $this->mAccessController['main']->getPermittedIdList(Xcforum_AbstractAccessController::VIEW, 0);
		//adump('AccessController End');
		$idList['post'] = $this->mAccessController['main']->getPermittedIdList(Xcforum_AbstractAccessController::POST, 0);
		$idList['manage'] = $this->mAccessController['main']->getPermittedIdList(Xcforum_AbstractAccessController::MANAGE, 0);

		$this->fIdList4Cat['view'] = $this->_getForumid4Cats($idList['view']);
		$this->fIdList4Cat['post'] = $this->_getForumid4Cats($idList['post']);
		$this->fIdList4Cat['manage'] = $this->_getForumid4Cats($idList['manage']);

	}

	private function _getForumid4Cats( $idList )
	{
		$cForum_id = array();
		$criteria = new Criteriacompo();
		if(count($idList)>0){
			$criteria->add(new Criteria('category_id', $idList, 'IN'));
			$fHandler =& $this->mAsset->getObject('handler', 'Forums',false);
			$fObjects = $fHandler->getObjects($criteria);
			foreach ($fObjects as $mF){
				$cForum_id[] = $mF->get('forum_id');
			}
		}
		return $cForum_id;
	}


	/**
	 * get_forums_permissions
	 *
	 * @return  array
	 * @remark  returns single all roles for this user
	 *
	 **/
	public function get_forum_all_permissions( )
	{
		if (isset($this->mForumAcc['can_read'])){
			$cForumAcc = $this->_check_category_permissions($this->mForumAcc, "can_read") ;

				// TODO all permission rols must be set
			$rtn['can_read'] = isset($this->mForumAcc['can_read']) ? array_intersect( $this->mForumAcc['can_read'], $cForumAcc ) :  array() ;

			foreach ($this->perm_keys as $key){
				$cForumAcc = $this->_check_category_permissions($this->mForumAcc, $key) ;
				$rtn[$key] = isset($this->mForumAcc[$key]) ? array_intersect( $this->mForumAcc[$key], $cForumAcc ) : array() ;
			}
			return  $rtn;
		}
		return array();
	}

	/**
	 * get_forums_permissions
	 *
	 * @return  array
	 * @remark  returns single role 'can_read' or 'can_post' or 'can_edit' or 'can_delete' or 'is_moderator' for this user
	 *
	 **/
	public function get_forum_permissions( /*** string ***/ $attr )
	{
		//adump($mForumAcc);
		return  isset($this->mForumAcc[$attr]) ? array_intersect( $this->mForumAcc[$attr], $this->_check_category_permissions($this->mForumAcc, $attr) ) : array();
	}

	/**
	 * get_forums_can_read
	 *
	 * @return  array
	 **/
	private function _get_forum_permissions()
	{
		// Group permissions
		$this->mRoot = XCUBE_Root::getSingleton();
		$mygroups = $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? $this->mRoot->mContext->mXoopsUser->groups() : array(XOOPS_GROUP_ANONYMOUS);

		$mFgroupAcc = array();

		// get Froumaccess object for this user's group with $forum_id
		$criteria = new Criteriacompo();
		$criteria->add (new criteria('groupid', $mygroups, 'IN') );
		$criteria->add (new criteria('uid', NULL) );
		$mFgroupAccObj = $this->getObjects($criteria);
		foreach ( $mFgroupAccObj as $mF ){
			$forum_id = $mF->get('forum_id') ;
			$mFgroupAcc['can_read'][$forum_id] = $forum_id;
			$fgAccArr = unserialize( $mF->get('permissions') ) ;
			foreach( $fgAccArr as $key => $val ) {
				if ( (int)$val >0 ) {
					$mFgroupAcc[$key][$forum_id] = $forum_id ;
				}
			}
		}
		//adump($mFgroupAcc);

		// User permissions
		$uid = $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? $this->mRoot->mContext->mXoopsUser->get('uid') : 0;

		// get Froumaccess object for this user with $forum_id
		$criteria = new Criteriacompo();
		$criteria->add (new criteria('groupid', NULL) );
		$criteria->add (new criteria('uid', $uid) );
		$mFuserAccObj = $this->getObjects($criteria);

		// User permissions
		foreach ($mFuserAccObj as $mF){
			$forum_id = $mF->get('forum_id');
			$mFgroupAcc['can_read'][$forum_id] = $forum_id;
			$fuAccArr = unserialize( $mF->get('permissions') ) ;
			foreach( $fuAccArr as $key => $val ) {
				if ( (int)$val >0 ) {
					$mFgroupAcc[$key][$forum_id] = $forum_id ;
				}
			}
		}

		//adump($mFgroupAcc);
		//adump($this->perm_keys,$mFgroupAcc);
		return $mFgroupAcc;

	}

	/**
	 * check_category_permissions
	 *
	 * @return  array
	 **/
	private function _check_category_permissions($mForumAcc, $method){
		$cForum_id = array();
		if ( isset($mForumAcc['can_read'])){

			$criteria = new Criteriacompo();
			//get permitted categories to show
			switch ($method) {
				case "post":
				case "html":
				case "edit":
				case "delete":
				case "auto_approve":
					$idList = !empty($this->fIdList4Cat['post']) ? $this->fIdList4Cat['post'] : array();
					break;
				case "moderate":
					$idList = !empty($this->fIdList4Cat['manage']) ? $this->fIdList4Cat['manage'] : array();
					break;
				case "can_read":
				default:
					$idList = !empty($this->fIdList4Cat['view']) ? $this->fIdList4Cat['view'] : array();
					break;
			}
		}
		return  $idList ;
	}


	/**
	 * get_permitted_groups
	 *
	 * @return  array
	 **/
	public function get_permitted_groups( /*** string ***/ $attr, /*** array ***/ $forum_ids=array() )
	{
		$mFgroupAccObj = $this->_get_all_permitted_groups( $forum_ids );
		return !empty($mFgroupAccObj[$attr]) ? $mFgroupAccObj[$attr] : array() ;
	}

	/**
	 * get_all_permitted_groups
	 *
	 * @return  array
	 **/
	public function get_all_permitted_groups( /*** array ***/ $forum_ids=array() )
	{
		return $this->_get_all_permitted_groups( $forum_ids );
	}

	/**
	 * _get_all_permitted_groups
	 *
	 * @return  array
	 **/
	private function _get_all_permitted_groups( /*** array ***/ $forum_ids=array() )
	{
		$mFAcc = array();
		// get Froumaccess object for groups with $forum_ids
		$criteria = new Criteriacompo();
		if ( !empty($forum_ids) ){
			$criteria->add (new criteria('forum_id', $forum_ids, 'IN') );
		}
		$criteria->add (new criteria('groupid', 0, '>') );
		$criteria->add (new criteria('uid', NULL) );
		foreach ( $this->getObjects($criteria) as $mF ){
			$forum_id = $mF->get('forum_id') ;
			$group_id = $mF->get('groupid') ;
			$fgAccArr = unserialize( $mF->get('permissions') ) ;
			foreach( $fgAccArr as $key => $val ) {
				if ( (int)$val >0 ) {
					$mFAcc[$key][$forum_id][$group_id] = $group_id ;
				}
			}
		}
		return $mFAcc;
	}


	/**
	 * get_permitted_users
	 *
	 * @return  array
	 **/
	public function get_permitted_users( /*** string ***/ $attr, /*** array ***/ $forum_ids=array() )
	{
		$mFgroupAccObj = $this->_get_all_permitted_users( $forum_ids );
		return !empty($mFgroupAccObj[$attr]) ? $mFgroupAccObj[$attr] : array() ;
	}

	/**
	 * get_all_permitted_users
	 *
	 * @return  array
	 **/
	public function get_all_permitted_users( /*** array ***/ $forum_ids=array() )
	{
		return $this->_get_all_permitted_users( $forum_ids );
	}

	/**
	 * _get_all_permitted_users
	 *
	 * @return  array
	 **/
	private function _get_all_permitted_users( /*** array ***/ $forum_ids=array() )
	{
		$mFAcc = array();
		// get Froumaccess object for users with $forum_ids
		$criteria = new Criteriacompo();
		if ( !empty($forum_ids) ){
			$criteria->add (new criteria('forum_id', $forum_ids, 'IN') );
		}
		$criteria->add (new criteria('uid', 0, '>') );
		$criteria->add (new criteria('groupid', NULL) );
		foreach ( $this->getObjects($criteria) as $mF ){
			$forum_id = $mF->get('forum_id') ;
			$uid = $mF->get('uid') ;
			$fgAccArr = unserialize( $mF->get('permissions') ) ;
			foreach( $fgAccArr as $key => $val ) {
				if ( (int)$val >0 ) {
					$mFAcc[$key][$forum_id][$uid] = $uid ;
				}
			}
		}
		return $mFAcc;
	}

}

?>
