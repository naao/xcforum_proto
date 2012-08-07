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

require_once XCFORUM_TRUST_PATH . '/class/AbstractDeleteAction.class.php';

/**
 * Xcforum_ForumsDeleteAction
**/
class Xcforum_ForumsDeleteAction extends Xcforum_AbstractDeleteAction
{
	const DATANAME = 'forums';

	//public /*** string ***/ $mPrimary = 'forum_id';

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
		parent::prepare();
		$this->_setupAccessController('forums');

		return true;
	}

	/**
	 * executeViewInput
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_forums_delete.html');
		$render->setAttribute('actionForm', $this->mActionForm);
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('accessController', $this->mAccessController['main']);
	}
}

?>
