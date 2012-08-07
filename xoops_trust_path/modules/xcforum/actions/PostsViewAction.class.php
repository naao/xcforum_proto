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

/**
 * Xcforum_PostsViewAction
**/
class Xcforum_PostsViewAction extends Xcforum_AbstractViewAction
{
	const DATANAME = 'posts';

	private /*** Xcforum_ForumaccessHandler **/ $mfAccHandler ;
	private /*** array **/ $mForumAcc ;
	private /*** object ***/ $mForumObj = NULL;
	private /*** object ***/ $mTopicObj = NULL;
	private /*** object ***/ $mTopics_ExtObj = NULL;
	private /*** object ***/ $mPostsObj = NULL;

	private /*** int ***/ $ext_link_id ;
	private /*** int ***/ $forum_id ;

	private /*** boolean ***/ $can_read = false;
	private /*** boolean ***/ $can_post = false;
	private /*** boolean ***/ $can_edit = false;
	private /*** boolean ***/ $can_delete = false;
	private /*** boolean ***/ $can_reply = false;
	private /*** boolean ***/ $isadminormod = false;

	//public /*** string ***/ $mPrimary = 'post_id';

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

		if (!is_object($this->mObject)){
			//return false;   // TODO: No item error message
		} else {
			$topic_id =(int)$this->mObject->get('topic_id');

			$topicHandler =& $this->mAsset->getObject('handler', 'topics', false);
			$this->mTopicObj = $topicHandler->get($topic_id);
			//adump($this->mTopicObj);

			$this->forum_id = $this->mTopicObj->get('forum_id');
			$handler = Legacy_Utils::getModuleHandler('forums', $this->mAsset->mDirname);
			$this->mForumObj = $handler->get($this->forum_id);

			$this->ext_link_id = $this->mTopicObj->get('topic_external_link_id');
			//adump($external_link_id);
			if ( $this->ext_link_id >0 ){
				$mExtHandler =& $this->mAsset->getObject('handler','Topics',false);
				$criteria = new criteriacompo;
				$criteria->add(new criteria('topic_external_link_id', $this->ext_link_id));
				$this->mTopics_ExtObj = $mExtHandler->getObjects($criteria);
			}
			$mHandler =& $this->mAsset->getObject('handler','Posts',false);
			$criteria = new criteriacompo;
			$criteria->add(new criteria('topic_id', $topic_id));
			$criteria->addSort('post_time', 'ASC'); // TODO: other sorts
			$this->mPostsObj = $mHandler->getObjects($criteria);
		}

		return true;
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
		//$this->mForumAcc = $this->mfAccHandler->get_forum_permissions('can_read');
		$this->mForumAcc = $this->mfAccHandler->get_forum_all_permissions();

		if (!is_object($this->mTopicObj)){
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

					$rtn = Xcforum_Utils::processThispost($this->mObject, $this->mTopicObj, $params);
					//adump($rtn);

					$this->mObject->initVar('poster_dispname', XOBJ_DTYPE_STRING, $rtn['poster_dispname'], false);
					$this->mObject->initVar('poster_uname', XOBJ_DTYPE_STRING, $rtn['poster_uname'], false);
					$this->mObject->initVar('poster_name', XOBJ_DTYPE_STRING, $rtn['poster_name'], false);
					$this->mObject->initVar('poster_gname', XOBJ_DTYPE_STRING, $rtn['poster_gname'], false);

					$this->mObject->initVar('can_read', XOBJ_DTYPE_INT, true, false);
					$this->mObject->initVar('can_post', XOBJ_DTYPE_INT, $rtn['can_post'], false);
					$this->mObject->initVar('can_edit', XOBJ_DTYPE_INT, $rtn['can_edit'], false);
					$this->mObject->initVar('can_delete', XOBJ_DTYPE_INT, $rtn['can_delete'], false);
					$this->mObject->initVar('can_reply', XOBJ_DTYPE_INT, $rtn['can_reply'], false);
					$this->mForumObj->initVar('isadminormod', XOBJ_DTYPE_INT, $rtn['isadminormod'], false);

					// for posts tree
					foreach($this->mPostsObj as &$obj){
						$rtn = Xcforum_Utils::processThispost($obj, $this->mTopicObj, $params);
						$obj->initVar('poster_dispname', XOBJ_DTYPE_STRING, $rtn['poster_dispname'], false);
						$obj->initVar('poster_uname', XOBJ_DTYPE_STRING, $rtn['poster_uname'], false);
						$obj->initVar('poster_name', XOBJ_DTYPE_STRING, $rtn['poster_name'], false);
						$obj->initVar('poster_gname', XOBJ_DTYPE_STRING, $rtn['poster_gname'], false);

					}
				}
			}
		}

		$render->setTemplateName($this->mAsset->mDirname . '_post_view.html');
		$render->setAttribute('forumObj', $this->mForumObj);
		$render->setAttribute('topicObj', $this->mTopicObj);
		$render->setAttribute('postObj', $this->mObject);
		$render->setAttribute('postsObj', $this->mPostsObj);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);

		$render->setAttribute('topics', $this->mTopics_ExtObj);
		$render->setAttribute('external_link_id', $this->ext_link_id);
		$render->setAttribute('tree_tp_count', @count($this->mTopics_ExtObj));
		$render->setAttribute('can_edit', $this->can_edit);
		$render->setAttribute('can_delete', $this->can_delete);
		$render->setAttribute('can_reply', $this->can_reply);

		parent:: executeViewSuccess($render);
	}

}

?>
