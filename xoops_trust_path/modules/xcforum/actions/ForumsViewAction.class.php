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
 * Xcforum_ForumsViewAction
**/
class Xcforum_ForumsViewAction extends Xcforum_AbstractViewAction
{
	const DATANAME = 'forums';

	private /*** int ***/ $forum_id ;

	//public /*** string ***/ $mPrimary = 'forum_id';

	private /*** Xcforum_ForumaccessHandler **/ $mfAccHandler ;
	private /*** array **/ $mForumAcc ;

	/**
	 * _getCatId
	 * 
	 * @param	void
	 * 
	 * @return	int
	**/
	protected function _getCatId()
	{
		return $this->mObject->get('category_id');
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

		$this->mfAccHandler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		$this->mForumAcc = $this->mfAccHandler->get_forum_permissions('can_read');

		$mCriteria = $this->mFilter->getCriteria();
		$mCriteria->add(new criteria('forum_id', $this->mForumAcc['can_read'], 'IN'));

		parent::prepare();
		$this->_setupAccessController('forums');

		$this->forum_id =(int)$this->mRoot->mContext->mRequest->getRequest('forum_id');

		return true;
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
		//return $this->mAccessController['main']->check($this->_getCatId(), Xcforum_AbstractAccessController::VIEW, 'xcforum');
		return in_array($this->forum_id, $this->mForumAcc['can_read']);
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
		$render->setTemplateName($this->mAsset->mDirname . '_forums_view.html');
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		$render->setAttribute('accessController', $this->mAccessController['main']);
	}
}

?>
