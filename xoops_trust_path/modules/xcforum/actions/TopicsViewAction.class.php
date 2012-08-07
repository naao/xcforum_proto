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

require_once XCFORUM_TRUST_PATH . '/class/AbstractViewAction.class.php';

require_once XOOPS_ROOT_PATH . '/core/XCube_PageNavigator.class.php';
//require_once XCFORUM_TRUST_PATH . '/class/Func.class.php';

/**
 * Xcforum_TopicsViewAction
 **/
class Xcforum_TopicsViewAction extends Xcforum_AbstractViewAction
{
	const DATANAME = 'topics';

	private /*** object ***/ $mForumObj = NULL;
	private /*** object ***/ $mPostsObj = NULL;
	private /*** object ***/ $mTopics_ExtObj = NULL;

	private /*** Xcforum_ForumaccessHandler **/ $mfAccHandler ;
	private /*** array **/ $mForumAcc ;

	public /*** Xcforum_PostsObject ***/ $mPosts = NULL;

	private /*** int ***/ $ext_link_id ;
	private /*** int ***/ $forum_id ;

	private /*** boolean ***/ $can_read = false;
	private /*** boolean ***/ $can_post = false;
	private /*** boolean ***/ $can_edit = false;
	private /*** boolean ***/ $can_delete = false;
	private /*** boolean ***/ $can_reply = false;
	private /*** boolean ***/ $isadminormod = false;

	/**
	 * prepare
	 *
	 * @param	void
	 *
	 * @return	bool
	 **/
	public function prepare()
	{
		parent::prepare();

		$topic_id = (int)$this->mRoot->mContext->mRequest->getRequest('topic_id');

		$handler =& $this->mAsset->getObject('handler','Posts',false);
		//$handler = Legacy_Utils::getModuleHandler('posts', $this->mAsset->mDirname);
		$criteria = new criteriacompo;
		$criteria->add(new criteria('topic_id', $topic_id));
		$criteria->addSort('post_time', 'ASC'); // TODO: other sorts
		$this->mPostsObj = $handler->getObjects($criteria);

	if (is_object($this->mObject)){
			$this->forum_id = (int)$this->mObject->get('forum_id');
			$handler = Legacy_Utils::getModuleHandler('forums', $this->mAsset->mDirname);
			$this->mForumObj = $handler->get($this->forum_id);
			//adump($this->mForumObj);

			$this->ext_link_id = $this->mObject->get('topic_external_link_id');
			//adump($external_link_id);
			if ( $this->ext_link_id >0 ){
				$mExtHandler =& $this->mAsset->getObject('handler','Topics',false);
				$criteria = new criteriacompo;
				$criteria->add(new criteria('topic_external_link_id', $this->ext_link_id));
				$this->mTopics_ExtObj = $mExtHandler->getObjects($criteria);
			}
			//adump($this->mTopics_ExtObj);

			return true;
		}
		return false;
	}

	/**
	 * hasPermission
	 *
	 * @param   void
	 *
	 * @return  bool
	 **/
	public function hasPermission()
	{
		$this->mfAccHandler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		$this->mForumAcc = $this->mfAccHandler->get_forum_all_permissions();

		if (!is_object($this->mObject)){
			return false;   // TODO: No item error message
		} else {
			if ( isset($this->forum_id) && $this->forum_id > 0 ){
				if ( isset($this->mForumAcc) && count($this->mForumAcc) >0 ){
					if ( in_array( $this->forum_id, $this->mForumAcc['can_read']) ){
						$this->can_read = true;
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * executeViewSuccess
	 *
	 * @param	XCube_RenderTarget	&$render
	 *
	 * @return	void
	 **/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
		// set each permission flags and usr's info

		if (is_object($this->mObject)){
			if ( isset($this->forum_id) && $this->forum_id > 0 ){
				if ( isset($this->mForumAcc) && count($this->mForumAcc) >0 ){
					if ( $this->can_read === true ){
						// permissions
						$params = array();
						$params['uid'] = $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? $this->mRoot->mContext->mXoopsUser->get('uid') : 0 ;
						$params['can_read'] = true;
						$params['can_post'] = in_array( $this->forum_id, $this->mForumAcc['post']);
						$params['can_edit'] = in_array( $this->forum_id, $this->mForumAcc['edit']);
						$params['can_delete'] = in_array( $this->forum_id, $this->mForumAcc['delete']);
						$params['can_reply'] = in_array( $this->forum_id, $this->mForumAcc['post']);
						$params['isadminormod'] = $this->mod_isadmin ? true : in_array( $this->forum_id, $this->mForumAcc['moderate']);
						$params['mod_config'] =& $this->mod_config;

						foreach($this->mPostsObj as &$obj){
						/*
						 * 		// permissions
						 */

							$rtn = Xcforum_Utils::processThispost($obj, $this->mObject, $params);

							$obj->initVar('poster_dispname', XOBJ_DTYPE_STRING, $rtn['poster_dispname'], false);
							$obj->initVar('poster_uname', XOBJ_DTYPE_STRING, $rtn['poster_uname'], false);
							$obj->initVar('poster_name', XOBJ_DTYPE_STRING, $rtn['poster_name'], false);
							$obj->initVar('poster_gname', XOBJ_DTYPE_STRING, $rtn['poster_gname'], false);
							$obj->initVar('poster_avatar', XOBJ_DTYPE_STRING, $rtn['poster_avatar'], false);
							$obj->initVar('poster_from4disp', XOBJ_DTYPE_STRING, $rtn['poster_from4disp'], false);
							$obj->initVar('signature', XOBJ_DTYPE_STRING, $rtn['poster_signature'], false);

							$obj->initVar('poster_uid', XOBJ_DTYPE_INT, $rtn['poster_uid'], false);
							$obj->initVar('poster_regdate', XOBJ_DTYPE_INT, $rtn['poster_regdate'], false);
							$obj->initVar('poster_rank', XOBJ_DTYPE_INT, $rtn['poster_rank'], false);
							$obj->initVar('poster_is_online', XOBJ_DTYPE_INT, $rtn['poster_is_online'], false);
							$obj->initVar('poster_posts_count', XOBJ_DTYPE_INT, $rtn['poster_posts_count'], false);

							$obj->initVar('can_read', XOBJ_DTYPE_INT, true, false);
							$obj->initVar('can_post', XOBJ_DTYPE_INT, $rtn['can_post'], false);
							$obj->initVar('can_edit', XOBJ_DTYPE_INT, $rtn['can_edit'], false);
							$obj->initVar('can_delete', XOBJ_DTYPE_INT, $rtn['can_delete'], false);
							$obj->initVar('can_reply', XOBJ_DTYPE_INT, $rtn['can_reply'], false);

						}
						$this->mForumObj->initVar('isadminormod', XOBJ_DTYPE_INT, $params['isadminormod'], false);
						unset($params);
					}
				}
			}
		}

		//$memberHandler =& Xcforum_Utils::getXoopsHandler('member');
		//$cri = new criteriacompo();
		//$cri->add( new criteria('uid', $uids, 'IN'));
		//$mUsers = $memberHandler->getUsers();
		//adump($mUsers);
		//adump($users);
		/*
		$uses[$uid] = array(
			'uid' => $uid,
			'uname' => $obj->get('uname'),
			'name' => $obj->get('name'),
		);
		*/

		$render->setTemplateName($this->mAsset->mDirname . '_topic_view.html');
		$render->setAttribute('forumObj', $this->mForumObj);
		$render->setAttribute('topicObj', $this->mObject);
		$render->setAttribute('postObj', $this->mPostsObj);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
		$render->setAttribute('topics', $this->mTopics_ExtObj);
		$render->setAttribute('external_link_id', $this->ext_link_id);
		$render->setAttribute('tree_tp_count', @count($this->mTopics_ExtObj));
		parent:: executeViewSuccess($render);


	}

}

?>
