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

//require_once XCFORUM_TRUST_PATH . '/class/Func.class.php' ;		//is it needed here ??????

/**
 * Xcforum_AbstractAction
**/
abstract class Xcforum_AbstractAction
{
	public /*** XCube_Root ***/ $mRoot = null;

    public /*** Xcforum_Module ***/ $mModule = null;

    public /*** Xcforum_AssetManager ***/ $mAsset = null;

    public $mAccessController = array();

	public $mUser = null;
	public $mXoopsUser = null;
	public $mod_config = null;

//	public $func = null;
	//public $myts;

	public /*** var mod_isadmin ***/ $mod_isadmin ;

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function __construct()
    {
        $this->mRoot =& XCube_Root::getSingleton();
        $this->mModule =& $this->mRoot->mContext->mModule;
        $this->mAsset =& $this->mModule->mAssetManager;

		$this->mUser =& $this->mRoot->mContext->mUser ;
		$this->mXoopsUser =& $this->mRoot->mContext->mXoopsUser ;

	    $this->mod_config =& $this->mRoot->mContext->mModuleConfig;

		//$this->func =& Xcforum_Func::getInstance() ;

		//adump($this->mRoot->mContext->mUser->_mRoles);
		//return $this->mRoot->mContext->mUser->isInRole('Site.GuestUser') ? true : false;
		//return $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? true : false;
		//return $this->mRoot->mContext->mUser->isInRole('Site.Administrator') ? true : false;
		//return $this->mRoot->mContext->mUser->isInRole('Module.xcforum.Admin') ? true : false;
		$this->mod_isadmin = $this->mUser->isInRole('Module.'.$this->mAsset->mDirname.'.Admin') ? true : false;
		//$this->mod_isadmin = true;
   }

    /**
     * _getConst
     * 
     * @param   string  $key
     * @param   string  $action
     * 
     * @return  string
    **/
    protected function _getConst(/*** string ***/ $key, /*** string ***/ $action=null)
    {
        $action = isset($action) ? $action : $this->_getActionName();
        return constant(get_class($this).'::'. $key);
    }

    /**
     * _getHandler
     * 
     * @param   void
     * 
     * @return  Xcforum_{Tablename}Handler
    **/
    protected function _getHandler()
    {
        $handler = $this->mAsset->getObject('handler', constant(get_class($this).'::DATANAME'));
        return $handler;
    }

    /**
     * getPageTitle
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getPagetitle()
    {
        ///XCL2.2 only
        //return Legacy_Utils::formatPagetitle($this->mRoot->mContext->mModule->mXoopsModule->get('name'), $this->_getTitle(), $this->_getActionTitle());
	    return $this->mRoot->mContext->mModule->mXoopsModule->get('name') .':'. $this->_getPagetitle();
	    }

	/**
	 * _getPageTitle
	 *
	 * @param   void
	 *
	 * @return  string
	 **/
	protected function _getPagetitle()
	{
		return null;
	}

  /**
     * _getTitle
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getTitle()
    {
        if(! $this->mObject) return null;
        if($this->mObject->getShow('title')){
            return $this->mObject->getShow('title');
        }
        else{
            return null;
        }
    }

    /**
     * _getActionTitle
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getActionTitle()
    {
        return null;
    }

    /**
     * _getActionName
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getActionName()
    {
        return null;
    }


	/**
	 * _setupAccessController
	 * 
	 * @param	string	$dataname
	 * 
	 * @return	void
	**/
	protected function _setupAccessController(/*** string ***/ $dataname)
	{
		$this->mAccessController['main'] = Xcforum_Utils::getAccessControllerObject($this->mAsset->mDirname, $dataname);
	}
    /**
     * _getDatePickerScript
     * 
     * @param   void
     * 
     * @return  String
    **/
    protected function _getDatePickerScript()
    {
        return '$(".datePicker").each(function(){$(this).datepicker({dateFormat: "'._JSDATEPICKSTRING.'"});});';
    }

    /**
     * _getStylesheet
     * 
     * @param   void
     * 
     * @return  String
    **/
    protected function _getStylesheet()
    {
        return $this->mRoot->mContext->mModuleConfig['css_file'];
    }

    /**
     * setHeaderScript
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function setHeaderScript()
    {
        $headerScript = $this->mRoot->mContext->getAttribute('headerScript');
        $headerScript->addScript($this->_getDatePickerScript());
        $headerScript->addStylesheet($this->_getStylesheet());
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function prepare()
    {
        return true;
    }

    /**
     * hasPermission
     * 
     * @param   void
     * 
     * @return  bool
    **/
    public function hasPermission()
    {
        return true;
    }

    /**
     * getDefaultView
     * 
     * @param   void
     * 
     * @return  Enum
    **/
    public function getDefaultView()
    {
        return XCFORUM_FRAME_VIEW_NONE;
    }

    /**
     * execute
     * 
     * @param   void
     * 
     * @return  Enum
    **/
    public function execute()
    {
        return XCFORUM_FRAME_VIEW_NONE;
    }

    /**
     * executeViewSuccess
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
	    $render->setAttribute('dirname', $this->mAsset->mDirname);
	    $render->setAttribute('mod_url', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname);
	    $render->setAttribute('mod_imageurl', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname . '/images');
	    $render->setAttribute('mod_isadmin', $this->mod_isadmin);
	    $render->setAttribute('mod_config', $this->mod_config);
	    $render->setAttribute('pageNavi', $this->mFilter->mNavi);
    }

    /**
     * executeViewError
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewIndex
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
    {
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('mod_url', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname);
		$render->setAttribute('mod_imageurl', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname . '/images');
		$render->setAttribute('mod_isadmin', $this->mod_isadmin);
	    $render->setAttribute('mod_config', $this->mod_config);
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
   }

    /**
     * executeViewInput
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
    {
		$render->setAttribute('actionForm', $this->mActionForm);
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('mod_url', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname);
		$render->setAttribute('mod_imageurl', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname . '/images');
		$render->setAttribute('mod_isadmin', $this->mod_isadmin);
	    $render->setAttribute('mod_config', $this->mod_config);

		//set tag usage
		$render->setAttribute('tag_dirname', $this->mod_config['tag_dirname']);
   }

    /**
     * executeViewPreview
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewPreview(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * executeViewCancel
     * 
     * @param   XCube_RenderTarget  &$render
     * 
     * @return  void
    **/
    public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
    {
    }

    /**
     * _getNextUri
     * 
     * @param   void
     * 
     * @return  string
    **/
    protected function _getNextUri($tableName, $actionName=null)
    {
        $handler = $this->_getHandler();
        if($this->mObject && $this->mObject->get($handler->mPrimary)>0){
            return Legacy_Utils::renderUri($this->mAsset->mDirname, $tableName, $this->mObject->get($handler->mPrimary), $actionName);
        }
        else{
            return Legacy_Utils::renderUri($this->mAsset->mDirname, $tableName, 0, $actionName);
        }
    }

}

?>
