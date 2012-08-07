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
 * Xcforum_PostvotesEditForm
**/
class Xcforum_PostvotesEditForm extends XCube_ActionForm
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
		return "module.xcforum.PostvotesEditForm.TOKEN";
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
        $this->mFormProperties['vote_id'] = new XCube_IntProperty('vote_id');
        $this->mFormProperties['post_id'] = new XCube_IntProperty('post_id');
        $this->mFormProperties['uid'] = new XCube_IntProperty('uid');
        $this->mFormProperties['vote_point'] = new XCube_IntProperty('vote_point');
        $this->mFormProperties['vote_time'] = new XCube_IntProperty('vote_time');
        $this->mFormProperties['vote_ip'] = new XCube_IntProperty('vote_ip');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');

	
		//
		// Set field properties
		//
       $this->mFieldProperties['vote_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['vote_id']->setDependsByArray(array('required'));
$this->mFieldProperties['vote_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTE_ID);
       $this->mFieldProperties['post_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['post_id']->setDependsByArray(array('required'));
$this->mFieldProperties['post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_ID);
        $this->mFieldProperties['uid'] = new XCube_FieldProperty($this);
       $this->mFieldProperties['vote_point'] = new XCube_FieldProperty($this);
$this->mFieldProperties['vote_point']->setDependsByArray(array('required'));
$this->mFieldProperties['vote_point']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTE_POINT);
       $this->mFieldProperties['vote_time'] = new XCube_FieldProperty($this);
$this->mFieldProperties['vote_time']->setDependsByArray(array('required'));
$this->mFieldProperties['vote_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTE_TIME);
       $this->mFieldProperties['vote_ip'] = new XCube_FieldProperty($this);
$this->mFieldProperties['vote_ip']->setDependsByArray(array('required'));
$this->mFieldProperties['vote_ip']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTE_IP);
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
        $this->set('vote_id', $obj->get('vote_id'));
        $this->set('post_id', $obj->get('post_id'));
        $this->set('uid', $obj->get('uid'));
        $this->set('vote_point', $obj->get('vote_point'));
        $this->set('vote_time', $obj->get('vote_time'));
        $this->set('vote_ip', $obj->get('vote_ip'));
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
        $obj->set('vote_point', $this->get('vote_point'));
        $obj->set('vote_time', $this->get('vote_time'));
        $obj->set('vote_ip', $this->get('vote_ip'));
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
