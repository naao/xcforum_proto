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
 * Xcforum_PosthistoriesEditForm
**/
class Xcforum_PosthistoriesEditForm extends XCube_ActionForm
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
		return "module.xcforum.PosthistoriesEditForm.TOKEN";
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
        $this->mFormProperties['history_id'] = new XCube_IntProperty('history_id');
        $this->mFormProperties['post_id'] = new XCube_IntProperty('post_id');
        $this->mFormProperties['history_time'] = new XCube_IntProperty('history_time');
        $this->mFormProperties['data'] = new XCube_TextProperty('data');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');

	
		//
		// Set field properties
		//
       $this->mFieldProperties['history_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['history_id']->setDependsByArray(array('required'));
$this->mFieldProperties['history_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_HISTORY_ID);
       $this->mFieldProperties['post_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['post_id']->setDependsByArray(array('required'));
$this->mFieldProperties['post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_ID);
       $this->mFieldProperties['history_time'] = new XCube_FieldProperty($this);
$this->mFieldProperties['history_time']->setDependsByArray(array('required'));
$this->mFieldProperties['history_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_HISTORY_TIME);
       $this->mFieldProperties['data'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['data']->setDependsByArray(array('required'));
        $this->mFieldProperties['data']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_DATA);
        $this->mFieldProperties['posttime'] = new XCube_FieldProperty($this);
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
        $this->set('history_id', $obj->get('history_id'));
        $this->set('post_id', $obj->get('post_id'));
        $this->set('history_time', $obj->get('history_time'));
        $this->set('data', $obj->get('data'));
        $this->set('posttime', $obj->get('posttime'));
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
        $obj->set('post_id', $this->get('post_id'));
        $obj->set('history_time', $this->get('history_time'));
        $obj->set('data', $this->get('data'));
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
