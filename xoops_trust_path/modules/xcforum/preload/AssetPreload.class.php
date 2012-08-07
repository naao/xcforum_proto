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

if(!defined('XCFORUM_TRUST_PATH'))
{
    define('XCFORUM_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/xcforum');
}

require_once XCFORUM_TRUST_PATH . '/class/XcforumUtils.class.php';
require_once XCFORUM_TRUST_PATH . '/class/Enum.class.php';
/**
 * Xcforum_AssetPreloadBase
**/
class Xcforum_AssetPreloadBase extends XCube_ActionFilter
{
    public $mDirname = null;

    /**
     * prepare
     * 
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public static function prepare(/*** string ***/ $dirname)
    {
        static $setupCompleted = false;
        if(!$setupCompleted)
        {
            $setupCompleted = self::_setup($dirname);
        }
    }

    /**
     * _setup
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public static function _setup(/*** string ***/ $dirname)
    {
        $root =& XCube_Root::getSingleton();
        $instance = new self($root->mController);
        $instance->mDirname = $dirname;
        $root->mController->addActionFilter($instance);

	    require_once XCFORUM_TRUST_PATH . '/class/Textsanitizer.php' ;
	    $root =& XCube_Root::getSingleton();
	    //$root->mController->mSetupTextFilter->add('Xcforum_TextSanitizer::getInstance', XCUBE_DELEGATE_PRIORITY_FINAL-5);
	    $root->mController->mSetupTextFilter->add('Xcforum_TextSanitizer::getInstance', XCUBE_DELEGATE_PRIORITY_FINAL-5);
	    //$root->setTextFilter( Xcforum_TextSanitizer::getInstance() );

	     return true;

    }

	function preFilter() {
	}


    /**
     * preBlockFilter
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function preBlockFilter()
    {
        $file = XCFORUM_TRUST_PATH . '/class/callback/DelegateFunctions.class.php';
        $this->mRoot->mDelegateManager->add('Module.xcforum.Global.Event.GetAssetManager','Xcforum_AssetPreloadBase::getManager');
        $this->mRoot->mDelegateManager->add('Legacy_Utils.CreateModule','Xcforum_AssetPreloadBase::getModule');
        $this->mRoot->mDelegateManager->add('Legacy_Utils.CreateBlockProcedure','Xcforum_AssetPreloadBase::getBlock');
        $this->mRoot->mDelegateManager->add('Module.'.$this->mDirname.'.Global.Event.GetNormalUri','Xcforum_CoolUriDelegate::getNormalUri', $file);

        $this->mRoot->mDelegateManager->add('Legacy_CategoryClient.GetClientList','Xcforum_CatClientDelegate::getClientList', XCFORUM_TRUST_PATH.'/class/callback/AccessClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_CategoryClient.'.$this->mDirname.'.GetClientData','Xcforum_CatClientDelegate::getClientData', XCFORUM_TRUST_PATH.'/class/callback/AccessClient.class.php');
        //Group Client
        $this->mRoot->mDelegateManager->add('Legacy_GroupClient.GetClientList','Xcforum_GroupClientDelegate::getClientList', XCFORUM_TRUST_PATH.'/class/callback/AccessClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_GroupClient.'.$this->mDirname.'.GetClientData','Xcforum_GroupClientDelegate::getClientData', XCFORUM_TRUST_PATH.'/class/callback/AccessClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_GroupClient.GetActionList','Xcforum_GroupClientDelegate::getActionList', XCFORUM_TRUST_PATH.'/class/callback/AccessClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_WorkflowClient.GetClientList','Xcforum_WorkflowClientDelegate::getClientList', XCFORUM_TRUST_PATH.'/class/callback/WorkflowClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_WorkflowClient.UpdateStatus','Xcforum_WorkflowClientDelegate::updateStatus', XCFORUM_TRUST_PATH.'/class/callback/WorkflowClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_ActivityClient.GetClientList','Xcforum_ActivityClientDelegate::getClientList', XCFORUM_TRUST_PATH.'/class/callback/ActivityClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_ActivityClient.'.$this->mDirname.'.GetClientData','Xcforum_ActivityClientDelegate::getClientData', XCFORUM_TRUST_PATH.'/class/callback/ActivityClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_ActivityClient.'.$this->mDirname.'.GetClientFeed','Xcforum_ActivityClientDelegate::getClientFeed', XCFORUM_TRUST_PATH.'/class/callback/ActivityClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_TagClient.GetClientList','Xcforum_TagClientDelegate::getClientList', XCFORUM_TRUST_PATH.'/class/callback/TagClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_TagClient.'.$this->mDirname.'.GetClientData','Xcforum_TagClientDelegate::getClientData', XCFORUM_TRUST_PATH.'/class/callback/TagClient.class.php');
        $this->mRoot->mDelegateManager->add('Legacy_ImageClient.GetClientList','Xcforum_ImageClientDelegate::getClientList', XCFORUM_TRUST_PATH.'/class/callback/ImageClient.class.php');

	}

    /**
     * getManager
     * 
     * @param   Xcforum_AssetManager  &$obj
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public static function getManager(/*** Xcforum_AssetManager ***/ &$obj,/*** string ***/ $dirname)
    {
        require_once XCFORUM_TRUST_PATH . '/class/AssetManager.class.php';
        $obj = Xcforum_AssetManager::getInstance($dirname);
    }

    /**
     * getModule
     * 
     * @param   Legacy_AbstractModule  &$obj
     * @param   XoopsModule  $module
     * 
     * @return  void
    **/
    public static function getModule(/*** Legacy_AbstractModule ***/ &$obj,/*** XoopsModule ***/ $module)
    {
        if($module->getInfo('trust_dirname') == 'xcforum')
        {
            require_once XCFORUM_TRUST_PATH . '/class/Module.class.php';
            $obj = new Xcforum_Module($module);
        }
    }

    /**
     * getBlock
     * 
     * @param   Legacy_AbstractBlockProcedure  &$obj
     * @param   XoopsBlock  $block
     * 
     * @return  void
    **/
    public static function getBlock(/*** Legacy_AbstractBlockProcedure ***/ &$obj,/*** XoopsBlock ***/ $block)
    {
        $moduleHandler =& Xcforum_Utils::getXoopsHandler('module');
        $module =& $moduleHandler->get($block->get('mid'));
        if(is_object($module) && $module->getInfo('trust_dirname') == 'xcforum')
        {
            require_once XCFORUM_TRUST_PATH . '/blocks/' . $block->get('func_file');
            $className = 'Xcforum_' . substr($block->get('show_func'), 4);
            $obj = new $className($block);
        }
    }
}

?>
