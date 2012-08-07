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
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

/**
 * Xcforum_Admin_ForumaccessEditForm
**/
class Xcforum_Admin_ForumaccessEditForm extends XCube_ActionForm
{
	/**
	 * getTokenName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getTokenName()
	{
		return "module.xcforum.ForumaccessEditForm.TOKEN";
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function prepare()
	{
		//
		// Set form properties
		//
		$this->mFormProperties['permit_id'] = new XCube_IntProperty('permit_id');
       $this->mFormProperties['forum_id'] = new XCube_IntProperty('forum_id');
        $this->mFormProperties['uid'] = new XCube_IntProperty('uid');
        $this->mFormProperties['groupid'] = new XCube_IntProperty('groupid');
		$this->mFormProperties['permissions'] = new XCube_IntProperty('permissions');

	
		//
		// Set field properties
		//
		$this->mFieldProperties['permit_id'] = new XCube_FieldProperty($this);
       $this->mFieldProperties['forum_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['forum_id']->setDependsByArray(array('required'));
$this->mFieldProperties['forum_id']->addMessage('required', _MD_A_XCFORUM_ERROR_REQUIRED, _MD_A_XCFORUM_LANG_FORUM_ID);
       $this->mFieldProperties['uid'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['uid']->setDependsByArray(array('required'));
		//$this->mFieldProperties['uid']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_UID);
       $this->mFieldProperties['groupid'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['groupid']->setDependsByArray(array('required'));
		//$this->mFieldProperties['groupid']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_GROUPID);
		$this->mFieldProperties['permissions'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['permissions']->setDependsByArray(array('required'));
		$this->mFieldProperties['permissions']->addMessage('required', _MD_A_XCFORUM_ERROR_REQUIRED, _MD_A_XCFORUM_LANG_PERMISSIONS);

	}

	/**
	 * load
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function load(/*** XoopsSimpleObject ***/ &$obj)
	{
		//adump($obj->get('forum_id'));
		$this->set('permit_id', $obj->get('permit_id'));
        $this->set('forum_id', $obj->get('forum_id'));
        $this->set('uid', $obj->get('uid'));
        $this->set('groupid', $obj->get('groupid'));
		$this->set('permissions', $obj->get('permissions'));
	}

	/**
	 * @public
	 * @brief Gets a value indicating whether this action form keeps error messages or error flag.
	 * @return bool - If the action form is error status, returns true.
	 */
	function hasError()
	{
		//atrace(); //die;
		return (count($this->mErrorMessages) > 0 || $this->mErrorFlag);
	}

	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
/*      Don't use this method
		//adump($this->get('forum_id'));
		$obj->set('forum_id', $this->get('forum_id'));
        $obj->set('uid', $this->get('uid'));
        $obj->set('groupid', $this->get('groupid'));
        $obj->set('can_post', $this->get('can_post'));
        $obj->set('can_edit', $this->get('can_edit'));
        $obj->set('can_delete', $this->get('can_delete'));
        $obj->set('post_auto_approved', $this->get('post_auto_approved'));
        $obj->set('is_moderator', $this->get('is_moderator'));
*/
	}

	/**
	 * _makeUnixtime
	 * 
	 * @param	string	$key
	 * 
	 * @return	void
	**/
	protected function _makeUnixtime($key)
	{
		$timeArray = explode('-', $this->get($key));
		return mktime(0, 0, 0, $timeArray[1], $timeArray[2], $timeArray[0]);
	}
}

?>
