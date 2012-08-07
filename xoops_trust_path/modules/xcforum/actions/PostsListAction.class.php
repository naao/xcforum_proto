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

require_once XCFORUM_TRUST_PATH . '/class/AbstractListAction.class.php';

/**
 * Xcforum_PostsListAction
**/
class Xcforum_PostsListAction extends Xcforum_AbstractListAction
{
	const DATANAME = 'posts';

	private /*** object ***/ $mTopicObj = NULL;
	private /*** object ***/ $mForumObj = NULL;

	private /*** Xcforum_ForumaccessHandler **/ $mfAccHandler ;
	private /*** array **/ $mForumAcc ;


	//public /*** string ***/ $mPrimary = 'post_id';

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
		$this->mForumAcc = $this->mfAccHandler->get_forum_permissions('can_read');

		$topic_id = (int)$this->mRoot->mContext->mRequest->getRequest('topic_id');
		$topicHandler =& $this->mAsset->getObject('handler', 'topics', false);
		$topicObj = $topicHandler->get($topic_id);

		$forum_id = $topicObj->get('forum_id');
		if ( $forum_id > 0 ){
			if ( isset($this->mForumAcc) && count($this->mForumAcc) >0 ){
				if ( in_array( $forum_id, $this->mForumAcc) ){
					return true;
				}
			}
		}
		return false;
	}

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
		$handler = Legacy_Utils::getModuleHandler('topics', $this->mAsset->mDirname);
		$this->mTopicObj = $handler->getObjects($topic_id);
		//adump($this->mTopicObj);

		$forum_id = $this->mTopicObj->getShow('forum_id');
		$handler = Legacy_Utils::getModuleHandler('forums', $this->mAsset->mDirname);
		$this->mForumObj = $handler->get($forum_id);
		//adump($this->mForumObj);
		// TODO: CATEGORY AND FORUM PERMISSION

		return true;;
	}

	/**
	 * executeViewIndex
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewIndex(/***obj XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_post_list.html');
		//$render->setTemplateName($this->mAsset->mDirname . '_topic_view.html');
		$render->setAttribute('topicObj', $this->mTopicObj);
		$render->setAttribute('forumObj', $this->mForumObj);
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);

		parent:: executeViewInput($render);

	}
}

?>
