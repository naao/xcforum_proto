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
 * Xcforum_PostsDeleteForm
**/
class Xcforum_PostsDeleteForm extends XCube_ActionForm
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
        return "module.xcforum.PostsDeleteForm.TOKEN";
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
        $this->mFormProperties['post_id'] = new XCube_IntProperty('post_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['post_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['post_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_ID);
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
        $this->set('post_id', $obj->get('post_id'));
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
        $obj->set('post_id', $this->get('post_id'));
    }
}

?>
