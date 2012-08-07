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
 * Xcforum_TopicsDeleteForm
**/
class Xcforum_TopicsDeleteForm extends XCube_ActionForm
{
    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.xcforum.TopicsDeleteForm.TOKEN";
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function prepare()
    {
        //
        // Set form properties
        //
        $this->mFormProperties['topic_id'] = new XCube_IntProperty('topic_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['topic_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['topic_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['topic_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_ID);
    }

    /**
     * load
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function load(/*** XoopsSimpleObject ***/ &$obj)
    {
        $this->set('topic_id', $obj->get('topic_id'));
    }

    /**
     * update
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function update(/*** XoopsSimpleObject ***/ &$obj)
    {
        $obj->set('topic_id', $this->get('topic_id'));
    }
}

?>
