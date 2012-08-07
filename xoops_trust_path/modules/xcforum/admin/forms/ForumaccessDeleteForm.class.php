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
 * Xcforum_ForumaccessDeleteForm
**/
class Xcforum_ForumaccessDeleteForm extends XCube_ActionForm
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
        return "module.xcforum.ForumaccessDeleteForm.TOKEN";
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
        $this->mFormProperties['forum_id'] = new XCube_IntProperty('forum_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['forum_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['forum_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['forum_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_ID);
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
        $this->set('forum_id', $obj->get('forum_id'));
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
        $obj->set('forum_id', $this->get('forum_id'));
    }
}

?>
