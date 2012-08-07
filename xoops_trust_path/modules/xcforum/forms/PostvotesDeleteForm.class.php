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
 * Xcforum_PostvotesDeleteForm
**/
class Xcforum_PostvotesDeleteForm extends XCube_ActionForm
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
        return "module.xcforum.PostvotesDeleteForm.TOKEN";
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
        $this->mFormProperties['vote_id'] = new XCube_IntProperty('vote_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['vote_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['vote_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['vote_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTE_ID);
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
        $this->set('vote_id', $obj->get('vote_id'));
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
        $obj->set('vote_id', $this->get('vote_id'));
    }
}

?>
