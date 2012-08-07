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

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';

/**
 * Xcforum_Admin_AdvanceForm
**/
class Xcforum_Admin_AdvanceEditForm extends XCube_ActionForm
{
	public function getTokenName()
	{
		return "module.xcforum.AdvanceForm.TOKEN";
	}

	public function prepare()
	{
		return true;
	}

	public function load(/*** XoopsSimpleObject ***/ &$obj)
	{
	}

	function hasError()
	{
		return (count($this->mErrorMessages) > 0 || $this->mErrorFlag);
	}

	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
	}

}

?>
