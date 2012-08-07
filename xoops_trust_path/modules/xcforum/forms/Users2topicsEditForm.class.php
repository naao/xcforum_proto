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
 * Xcforum_Users2topicsEditForm
**/
class Xcforum_Users2topicsEditForm extends XCube_ActionForm
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
		return "module.xcforum.Users2topicsEditForm.TOKEN";
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
        $this->mFormProperties['uid'] = new XCube_IntProperty('uid');
        $this->mFormProperties['topic_id'] = new XCube_IntProperty('topic_id');
        $this->mFormProperties['u2t_time'] = new XCube_IntProperty('u2t_time');
        $this->mFormProperties['u2t_marked'] = new XCube_IntProperty('u2t_marked');
        $this->mFormProperties['u2t_rsv'] = new XCube_IntProperty('u2t_rsv');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');

	
		//
		// Set field properties
		//
        $this->mFieldProperties['uid'] = new XCube_FieldProperty($this);
       $this->mFieldProperties['topic_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['topic_id']->setDependsByArray(array('required'));
$this->mFieldProperties['topic_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_ID);
       $this->mFieldProperties['u2t_time'] = new XCube_FieldProperty($this);
$this->mFieldProperties['u2t_time']->setDependsByArray(array('required'));
$this->mFieldProperties['u2t_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_U2T_TIME);
       $this->mFieldProperties['u2t_marked'] = new XCube_FieldProperty($this);
$this->mFieldProperties['u2t_marked']->setDependsByArray(array('required'));
$this->mFieldProperties['u2t_marked']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_U2T_MARKED);
       $this->mFieldProperties['u2t_rsv'] = new XCube_FieldProperty($this);
$this->mFieldProperties['u2t_rsv']->setDependsByArray(array('required'));
$this->mFieldProperties['u2t_rsv']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_U2T_RSV);
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
        $this->set('uid', $obj->get('uid'));
        $this->set('topic_id', $obj->get('topic_id'));
        $this->set('u2t_time', $obj->get('u2t_time'));
        $this->set('u2t_marked', $obj->get('u2t_marked'));
        $this->set('u2t_rsv', $obj->get('u2t_rsv'));
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
        $obj->set('topic_id', $this->get('topic_id'));
        $obj->set('u2t_time', $this->get('u2t_time'));
        $obj->set('u2t_marked', $this->get('u2t_marked'));
        $obj->set('u2t_rsv', $this->get('u2t_rsv'));
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
