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
 * Xcforum_ForumaccessListAction
**/
class Xcforum_Admin_ForumaccessListAction extends Xcforum_AbstractListAction
{
	const DATANAME = 'forumaccess';

	/**
	 * &_getFilterForm
	 *
	 * @param	void
	 *
	 * @return	Xcforum_{Tablename}FilterForm
	 **/
	protected function _getFilterForm()
	{
		$filter = $this->mAsset->getObject('filter', $this->_getConst('DATANAME'), $this->mod_isadmin);
		$filter->prepare($this->_getPageNavi(), $this->_getHandler());
		return $filter;
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
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function prepare()
	{
		//atrace(); exit;
		parent::prepare();

		return true;
	}

	/**
	 * executeViewIndex
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName('xcforum_forumaccess_list.html');
		//$render->setAttribute('objects', $this->mObjects);
		//$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		//$render->setAttribute('pageNavi', $this->mFilter->mNavi);

		parent::executeViewIndex($render);
	}
}

?>
