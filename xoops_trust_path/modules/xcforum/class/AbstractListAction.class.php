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

require_once XCFORUM_TRUST_PATH . '/class/AbstractAction.class.php';
require_once XOOPS_ROOT_PATH . '/core/XCube_PageNavigator.class.php';

/**
 * Xcforum_AbstractListAction
**/
abstract class Xcforum_AbstractListAction extends Xcforum_AbstractAction
{
	public /*** XoopsSimpleObject[] ***/ $mObjects = null;

	public /*** Xcforum_AbstractFilterForm ***/ $mFilter = null;

	/**
	 * &_getFilterForm
	 * 
	 * @param	void
	 * 
	 * @return	Xcforum_{Tablename}FilterForm
	**/
	protected function _getFilterForm()
	{
		$filter = $this->mAsset->getObject('filter', $this->_getConst('DATANAME'), false);
		$filter->prepare($this->_getPageNavi(), $this->_getHandler());
		return $filter;
	}

	/**
	 * _getBaseUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getBaseUrl()
	{
		return Legacy_Utils::renderUri($this->mAsset->mDirname, $this->_getConst('DATANAME'));
	}

	/**
	 * _getActionTitle
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getActionTitle()
	{
		return _LIST;
	}

	/**
	 * _getActionName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getActionName()
	{
		return 'list';
	}

	/**
	 * getPageTitle
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getPagetitle()
	{
		///XCL2.2 only
		return Legacy_Utils::formatPagetitle($this->mRoot->mContext->mModule->mXoopsModule->get('name'), null, $this->_getActionName());
	}

	/**
	 * &_getPageNavi
	 * 
	 * @param	void
	 * 
	 * @return	&XCube_PageNavigator
	**/
	protected function &_getPageNavi()
	{
		$navi = new XCube_PageNavigator($this->_getBaseUrl(), XCUBE_PAGENAVI_START);
		return $navi;
	}

	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{

		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch();
	
		$handler =& $this->_getHandler();
		$this->mObjects =& $handler->getObjects($this->mFilter->getCriteria());
	
		return XCFORUM_FRAME_VIEW_INDEX;
	}

	/**
	 * execute
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function execute()
	{
		return $this->getDefaultView();
	}

}

?>
