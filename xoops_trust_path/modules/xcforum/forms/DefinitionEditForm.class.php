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
 * Xcforum_DefinitionEditForm
**/
class Xcforum_DefinitionEditForm extends XCube_ActionForm
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
		return "module.xcforum.DefinitionEditForm.TOKEN";
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
        $this->mFormProperties['definition_id'] = new XCube_IntProperty('definition_id');
        $this->mFormProperties['field_name'] = new XCube_StringProperty('field_name');
        $this->mFormProperties['label'] = new XCube_StringProperty('label');
        $this->mFormProperties['field_type'] = new XCube_StringProperty('field_type');
        $this->mFormProperties['validation'] = new XCube_StringProperty('validation');
        $this->mFormProperties['required'] = new XCube_IntProperty('required');
        $this->mFormProperties['weight'] = new XCube_IntProperty('weight');
        $this->mFormProperties['show_list'] = new XCube_IntProperty('show_list');
        $this->mFormProperties['search_flag'] = new XCube_IntProperty('search_flag');
        $this->mFormProperties['description'] = new XCube_TextProperty('description');
        $this->mFormProperties['options'] = new XCube_TextProperty('options');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');

	
		//
		// Set field properties
		//
       $this->mFieldProperties['definition_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['definition_id']->setDependsByArray(array('required'));
$this->mFieldProperties['definition_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_DEFINITION_ID);
       $this->mFieldProperties['field_name'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['field_name']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['field_name']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FIELD_NAME);
        $this->mFieldProperties['field_name']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_FIELD_NAME, '32');
        $this->mFieldProperties['field_name']->addVar('maxlength', '32');
       $this->mFieldProperties['label'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['label']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['label']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_LABEL);
        $this->mFieldProperties['label']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_LABEL, '255');
        $this->mFieldProperties['label']->addVar('maxlength', '255');
       $this->mFieldProperties['field_type'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['field_type']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['field_type']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FIELD_TYPE);
        $this->mFieldProperties['field_type']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_FIELD_TYPE, '16');
        $this->mFieldProperties['field_type']->addVar('maxlength', '16');
       $this->mFieldProperties['validation'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['validation']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['validation']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VALIDATION);
        $this->mFieldProperties['validation']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_VALIDATION, '255');
        $this->mFieldProperties['validation']->addVar('maxlength', '255');
       $this->mFieldProperties['required'] = new XCube_FieldProperty($this);
$this->mFieldProperties['required']->setDependsByArray(array('required'));
$this->mFieldProperties['required']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_REQUIRED);
       $this->mFieldProperties['weight'] = new XCube_FieldProperty($this);
$this->mFieldProperties['weight']->setDependsByArray(array('required'));
$this->mFieldProperties['weight']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_WEIGHT);
       $this->mFieldProperties['show_list'] = new XCube_FieldProperty($this);
$this->mFieldProperties['show_list']->setDependsByArray(array('required'));
$this->mFieldProperties['show_list']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_SHOW_LIST);
       $this->mFieldProperties['search_flag'] = new XCube_FieldProperty($this);
$this->mFieldProperties['search_flag']->setDependsByArray(array('required'));
$this->mFieldProperties['search_flag']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_SEARCH_FLAG);
       $this->mFieldProperties['description'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['description']->setDependsByArray(array('required'));
        $this->mFieldProperties['description']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_DESCRIPTION);
       $this->mFieldProperties['options'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['options']->setDependsByArray(array('required'));
        $this->mFieldProperties['options']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_OPTIONS);
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
        $this->set('definition_id', $obj->get('definition_id'));
        $this->set('field_name', $obj->get('field_name'));
        $this->set('label', $obj->get('label'));
        $this->set('field_type', $obj->get('field_type'));
        $this->set('validation', $obj->get('validation'));
        $this->set('required', $obj->get('required'));
        $this->set('weight', $obj->get('weight'));
        $this->set('show_list', $obj->get('show_list'));
        $this->set('search_flag', $obj->get('search_flag'));
        $this->set('description', $obj->get('description'));
        $this->set('options', $obj->get('options'));
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
        $obj->set('field_name', $this->get('field_name'));
        $obj->set('label', $this->get('label'));
        $obj->set('field_type', $this->get('field_type'));
        $obj->set('validation', $this->get('validation'));
        $obj->set('required', $this->get('required'));
        $obj->set('weight', $this->get('weight'));
        $obj->set('show_list', $this->get('show_list'));
        $obj->set('search_flag', $this->get('search_flag'));
        $obj->set('description', $this->get('description'));
        $obj->set('options', $this->get('options'));
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
