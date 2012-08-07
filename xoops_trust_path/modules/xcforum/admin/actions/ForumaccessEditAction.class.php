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

require_once XCFORUM_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Xcforum_ForumaccessEditAction
**/

class Xcforum_Admin_ForumaccessEditAction extends Xcforum_AbstractEditAction
{
    const DATANAME = 'forumaccess';

	private /*** Xcforum_ForumsList ***/ $mForumObj = NULL;
	//private /*** object ***/ $mForumList = NULL;
	private /*** int ***/ $forum_id = null ;
	private /*** string ***/ $forum_title = null ;
	private /*** string ***/ $forum_catopts = null ;

	private /*** array ***/ $gids = array() ;

	private /*** array Xcforum_ForumsObject ***/ $mForumAcc = NULL ;
	private /*** array ***/ $grpAccObj = NULL;  // to Form object
	private /*** array ***/ $usrAccObj = NULL;  // to Form object
	//private /*** array ***/ $grpAccList = NULL;
	private /*** array ***/ $usrAccList = NULL;  // got from db

	/**
	 * _setupActionForm
	 *
	 * @param   void
	 *
	 * @return  void
	 **/
	protected function _setupActionForm()
	{
		$this->mActionForm =& $this->mAsset->getObject('form', $this->_getConst('DATANAME'), $this->mod_isadmin, $this->_getActionName());
		$this->mActionForm->prepare();
	}

	/**
	 * _getHandler
	 *
	 * @return	Xcforum_ForumaccessHandler
	 */
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		return $handler;
	}

	/**
	 * hasPermission
	 *
	 * @param	void
	 *
	 * @return	bool
	 **/
	public function hasPermission()
	{
		return $this->mod_isadmin;
	}

 /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
	    parent::prepare();
        if($this->mObject->isNew()){
			if($this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser')){
				$uid = $this->mRoot->mContext->mXoopsUser->get('uid');
				$this->mObject->set('uid', $uid);
			}
        }

	    $mReq = $this->mRoot->mContext->mRequest;
	    $forum_id = (int)$mReq->getRequest('forum_id');
	    $this->forum_id = ($forum_id > 0) ? $forum_id : 1 ;

	    $this->_setForumObject();

	    /* get forum permissions for current user
			You can get them like
				$can_post = $this->mHandler->get_forum_permissions('can_post');
				$can_edit = $this->mHandler->get_forum_permissions('can_edit');
					  : and so on
			or
	            $grpPerms =  $this->mHandler->get_forum_all_permissions();
		  */
	    $this->mHandler = $this->_getHandler();

	    // get Froumaccess object with $forum_id
	    $criteria = new Criteria( 'forum_id', $this->forum_id );
	    $criteria->setSort( 'groupid', 'ASC' );
	    $this->mForumAcc = $this->mHandler->getObjects($criteria);

	    $this->mObject->set('forum_id', $this->forum_id);

	    $this->_setForumAccArr ( $fgAccObj );   // byref
	    $this->_setGroupForm( $fgAccObj );

	    $this->_setUserForm();

     return true;
    }

	/**
	 * _getForumObject
	 *
	 * @return  void
	 **/
	private function _setForumObject()
	{
		$handler = Legacy_Utils::getModuleHandler('forums', $this->mAsset->mDirname);
		$this->mForumObj = $handler->get($this->forum_id);
		//adump($this->mForumObj);
		//$tmp = $handler->getForumsTitle();

		$this->_setupAccessController('forums');
		$mAccController = $this->mAccessController['main'];
		$catTitles = is_object($mAccController) ? $mAccController->getTitleList() : array() ;

		foreach ($handler->getForumsTitle() as $key => $val){
			//adump($key,$val);
			$selected = "";
			if ($key == $this->forum_id) {
				$selected = "selected";
				$this->forum_title = $val['forum_title'];
			}
			$this->forum_catopts .="<option value='".$key."'".$selected.">".htmlspecialchars( $catTitles[$val['cat_id']].' - '.$val['forum_title'], ENT_QUOTES, _CHARSET )."</option>\n";
		}
		//adump($this->forum_catopts);

	}

	/**
	 * _setForumAccArr
	 *
	 * @return  void
	 **/
	private function  _setForumAccArr( /*** array ***/ & $fgAccObj ){
		// make Access Control array
		if (!isset($fgAccObj)){
			$fgAccObj = array();
			foreach ($this->mForumAcc as &$forumAcc){
				$gid = (int)$forumAcc->getVar('groupid') ;
				if ($gid > 0){
					$fgAccObj[$gid] =& $forumAcc ;
				}
			}
		}
	}

	/**
	 * _setGroupForm
	 *
	 * @return  void
	 **/
	private function  _setGroupForm( /*** array ***/ & $fgAccObj ){
		// create group form
		$group_handler =& xoops_gethandler( 'group' ) ;
		$criteria = new Criteriacompo();
		$criteria->setSort('groupid', 'DESC');
		$groups =& $group_handler->getObjects($criteria) ;

		// make Access Control array by Groups
		foreach( $groups as $group ) {
			//adump($gid);
			$gid = (int)$group->getVar('groupid') ;
			$this->gids[] = $gid ;
			if( isset($fgAccObj[$gid]) ) {
				$fgAccObj[$gid]->initVar('can_read', XOBJ_DTYPE_INT, 1, false);  // set true
				$fgAccArr = unserialize( $fgAccObj[$gid]->get('permissions') ) ;
				foreach( $fgAccArr as $key => $val ) {
					$fgAccObj[$gid]->initVar($key, XOBJ_DTYPE_INT, $val, false);
				}
			} else {
				$fgAccObj[$gid] = new Xcforum_ForumaccessObject;
			}
			$fgAccObj[$gid]->initVar('gname', XOBJ_DTYPE_STRING, $group->getVar('name'), false);
		}
		//adump($this->gids,$fgAccObj);
		$this->grpAccObj = $fgAccObj;  // to Form object
	}

	/**
	 * _setUserForm
	 *
	 * @return  void
	 **/
	private function  _setUserForm(){
		// create user form
		$member_handler =& xoops_gethandler('member');

		$mHandler = $this->_getHandler();
		$criteria = new Criteriacompo();
		$criteria->add (new criteria('forum_id',$this->forum_id));
		$criteria->add (new criteria('groupid', NULL));
		$criteria->add (new criteria('uid', 0, '>'));
		$criteria->setSort('uid', 'ASC');
		$mFuserAccObj = $mHandler->getObjects($criteria);
		$fuAccObj = array();
		foreach($mFuserAccObj as $userObj){
			$uid=$userObj->get('uid');
			$user = $member_handler->getUser($uid);
			$uname = $user->get('uname');
			$fuAccObj[$uid] = new Xcforum_ForumaccessObject;
			$fuAccObj[$uid]->initVar('uid', XOBJ_DTYPE_INT, $uid , false);
			$fuAccObj[$uid]->initVar('uname4disp', XOBJ_DTYPE_STRING, htmlspecialchars( $uname, ENT_QUOTES, _CHARSET) , false);
			$fuAccArr = unserialize( $userObj->get('permissions') ) ;
			foreach( $fuAccArr as $key => $val ) {
				$fuAccObj[$uid]->initVar($key, XOBJ_DTYPE_INT, $val, false);
			}
		}
		$this->usrAccList = $mFuserAccObj;  // to Form object
		$this->usrAccObj = $fuAccObj;       // got from db
		//adump($fuAccObj);
	}

	/**
	 * execute
	 *
	 * @param   void
	 *
	 * @return  Enum
	 **/
	public function execute()
	{
		$mReq = $this->mRoot->mContext->mRequest;
		$this->forum_id = (int)$mReq->getRequest('forum_id');

	// group update
	//if( ! empty( $_POST['group_update'] ) && empty( $invaild_forum_id ) ) {
		$gp = $mReq->getRequest('group_update');
		$up = $mReq->getRequest('user_update');

		if( ! empty( $gp ) &&  empty( $up ) ) {
			$this->_update_group();
		}elseif( empty( $gp ) && ! empty( $up ) ) {
			$this->_update_user();
		}

		if ($this->mObject == null)
		{
			return XCFORUM_FRAME_VIEW_ERROR;
		}

		if ($this->mRoot->mContext->mRequest->getRequest('_form_control_cancel') != null)
		{
			return XCFORUM_FRAME_VIEW_CANCEL;
		}

		$this->mActionForm->load($this->mObject);

		$this->mActionForm->fetch();
		$this->mActionForm->validate();

		if ($this->mActionForm->hasError())
		{
			//return  XCFORUM_FRAME_VIEW_ERROR;
			// TODO
		}

		//$this->mActionForm->update($this->mObject);

		//return $this->_doExecute();
		return XCFORUM_FRAME_VIEW_SUCCESS;

	}

	/**
	 * update_group
	 *
	 * @param   void
	 *
	 * @return  void
	 **/
	private function _update_group()
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;

		$mHandler = $this->_getHandler();
		$criteria = new Criteriacompo();
		$criteria->add (new criteria('forum_id', $this->forum_id));
		$criteria->add (new criteria('groupid', 0, '>'));
		$criteria->add (new criteria('uid', NULL));

		$mHandler->deleteAll($criteria);
		//$db->query( "DELETE FROM ".$db->prefix($this->mAsset->mDirname."_forumaccess")." WHERE forum_id=$this->forum_id AND groupid>0" ) ;

		foreach( $this->gids as $gid) {
			//$gid = $group->getVar('groupid') ;
			$gid = (int)$gid;
			//adump($key);

			$p =$mReq->getRequest('can_read');
			$can_read = empty($p[$gid]) ? 0 : 1 ;
			if( $can_read === 1 ) {
				$p = $mReq->getRequest('permission');
				$perms = $p[$gid];
				$perm_arr = array() ;
				foreach ( $perms as $key => $val ){
					$perm_arr[$key] = empty($val) ? 0 : 1 ;
				}
				$mObject = $mHandler->create();
				$mObject->set('forum_id', $this->forum_id);
				$mObject->set('groupid', $gid);
				$mObject->set('uid', NULL);
				$mObject->set('permissions', serialize($perm_arr));
				$mHandler->insert($mObject, false);
			}
		}
		//redirect_header( XOOPS_URL."/modules/$mydirname/admin/index.php?page=forum_access&amp;forum_id=$forum_id" , 3 , _MD_D3FORUM_MSG_UPDATED ) ;
		//exit ;
	}

	/**
	 * _update_user
	 *
	 * @param   void
	 *
	 * @return  void
	 **/
	private function _update_user()
	{
		$db =& $this->mRoot->mController->mDB;
		$mReq = $this->mRoot->mContext->mRequest;
		$member_handler =& xoops_gethandler('member');

		$mHandler = $this->_getHandler();
		$criteria = new Criteriacompo();
		$criteria->add (new criteria('forum_id', $this->forum_id));
		$criteria->add (new criteria('groupid', NULL ));
		$criteria->add (new criteria('uid', 0, '>'));

		$mHandler->deleteAll($criteria);
		//$db->query( "DELETE FROM ".$db->prefix($this->mAsset->mDirname."_forumaccess")." WHERE forum_id=$this->forum_id AND uid>0" ) ;

		$can_read = $can_post = $can_edit = $can_delete = $post_auto_approved = $is_moderator = false ;

//		$p_post =$mReq->getRequest('can_post');
//		$can_post_ = is_array( $p_post ) ? $p_post : array() ;

		$p_read =$mReq->getRequest('can_read');
//		$can_read_ = is_array( $p_read ) ? $p_read + $can_post_ : $can_post_ ;

		foreach( $p_read as $uid => $can_read ) {
			$uid = (int)$uid ;
			if( $can_read ) {
				$p = $mReq->getRequest('permission');
				$perms = $p[$uid];
				$perm_arr = array() ;
				foreach ( $perms as $key => $val ){
					$perm_arr[$key] = empty($val) ? 0 : 1 ;
				}
				$mObject = $mHandler->create();
				$mObject->set('forum_id', $this->forum_id);
				$mObject->set('groupid', NULL);
				$mObject->set('uid', $uid);
				$mObject->set('permissions', serialize($perm_arr));
				$mHandler->insert($mObject, false);
			}
		}

		$p_uname = $mReq->getRequest('new_uname');
		$p_uid = $mReq->getRequest('new_uid');

		if( is_array( $p_uid ) || is_array( $p_uname ) ){
			for ( $i=0 ; $i<5 ; $i++ ){
				if( (int)$p_uid[$i] >= 0 ) {
					$uid = (int)$p_uid[$i];
					$user = $member_handler->getUser( $uid ) ;
				}
				if (!empty($p_uname[$i])){
					$criteria = new Criteria( 'uname' , addslashes( $p_uname[$i] ) ) ;
					@list( $user ) = $member_handler->getUsers( $criteria ) ;
					$uid = $user->uid();
				}
				if( is_object($user)) {
					$p = $mReq->getRequest('new_permission');
					$perms = $p[$i];
					$perm_arr = array() ;
					foreach ( $perms as $key => $val ){
						$perm_arr[$key] = empty($val) ? 0 : 1 ;
					}
					$mObject = $mHandler->create();
					$mObject->set('forum_id', $this->forum_id);
					$mObject->set('groupid', NULL);
					$mObject->set('uid', $uid);
					$mObject->set('permissions', serialize($perm_arr));
					$mHandler->insert($mObject, false);
				}
			}
		}

	}

	/**
	 * executeViewSuccess
	 *
	 * @param   XCube_RenderTarget  &$render
	 *
	 * @return  void
	 **/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
		$param = ($this->forum_id > 0) ? '&forum_id='.$this->forum_id : '';
		$this->mRoot->mController->executeRedirect(XOOPS_MODULE_URL.'/'.$this->mAsset->mDirname.'/admin/index.php?action=ForumaccessEdit'.$param, 1, XCFORUM_FRAME_VIEW_SUCCESS);
		//$this->mRoot->mController->executeRedirect('./index.php?action=ForumaccesEdit'.$param, 1, XCFORUM_FRAME_VIEW_SUCCESS);
		//$this->mRoot->mController->executeRedirect($this->_getNextUri($this->_getConst('DATANAME')), 1, XCFORUM_FRAME_VIEW_SUCCESS);
	}

	/**
	 * executeViewError
	 *
	 * @param   XCube_RenderTarget  &$render
	 *
	 * @return  void
	 **/
	public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
	{
		$param = ($this->forum_id > 0) ? '&forum_id='.$this->forum_id : '';
		$this->mRoot->mController->executeRedirect(XOOPS_MODULE_URL.'/'.$this->mAsset->mDirname.'/admin/index.php?action=ForumaccessEdit'.$param, 1, _MD_XUPDATE_ERROR_DBUPDATE_FAILED);
		//$this->mRoot->mController->executeRedirect('./index.php?action=ForumaccesEdit'.$param, 1, _MD_XUPDATE_ERROR_DBUPDATE_FAILED);
		//$this->mRoot->mController->executeRedirect($this->_getNextUri($this->_getConst('DATANAME')), 1, _MD_XUPDATE_ERROR_DBUPDATE_FAILED);
	}


   /**
     * executeViewInput
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
    {
	    //$this->_execute_GroupUpdate();

	    $render->setTemplateName('xcforum_forumaccess.html');
        //$render->setTemplateName($this->mAsset->mDirname . '_forumaccess_edit.html');
	//adump($this->mAccList);
	    $render->setAttribute('actionForm', $this->mActionForm);
	    $render->setAttribute('grpObjects', $this->grpAccObj);
	    $render->setAttribute('usrObjects', $this->usrAccObj);
	    $render->setAttribute('dirname', $this->mAsset->mDirname);
        $render->setAttribute('dataname', self::DATANAME);

	    $render->setAttribute('forum_jumpbox_options',$this->forum_catopts);
	    $render->setAttribute('forum_title',htmlspecialchars( $this->forum_title, ENT_QUOTES, _CHARSET )) ;
	    //$render->setAttribute('group_trs',$this->group_trs);

	    //adump(intval($this->mRoot->mContext->mRequest->getRequest('forum_id')));
	    //$render->setAttribute('forum_id', intval($this->mRoot->mContext->mRequest->getRequest('forum_id')));

	    //set tag usage
        $render->setAttribute('tag_dirname', $this->mRoot->mContext->mModuleConfig['tag_dirname']);

	    parent:: executeViewInput($render);

  }

}
?>
