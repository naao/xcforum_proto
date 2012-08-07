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

require_once XCFORUM_TRUST_PATH . '/class/AbstractAction.class.php';

define('XCFORUM_FRAME_PERFORM_SUCCESS', 1);
define('XCFORUM_FRAME_PERFORM_FAIL', 2);
define('XCFORUM_FRAME_INIT_SUCCESS', 3);

define('XCFORUM_FRAME_VIEW_NONE','none');
define('XCFORUM_FRAME_VIEW_SUCCESS','success');
define('XCFORUM_FRAME_VIEW_ERROR','error');
define('XCFORUM_FRAME_VIEW_INDEX','index');
define('XCFORUM_FRAME_VIEW_INPUT','input');
define('XCFORUM_FRAME_VIEW_PREVIEW','preview');
define('XCFORUM_FRAME_VIEW_CANCEL','cancel');

/**
 * Xcforum_Module
**/
class Xcforum_Module extends Legacy_ModuleAdapter
{
	public /*** string ***/ $mActionName = null;

	public /*** Xcforum_AbstractAction ***/ $mAction = null;

	public /*** bool ***/ $mAdminFlag = false;

	public /*** Xcforum_AssetManager ***/ $mAssetManager = null;

	protected /*** string ***/ $_mPreferenceEditUrl = null;

	protected /*** string ***/ $_mHelpViewUrl = null;

	protected /*** Enum[] ***/ $_mAllowViewNames = array(
		XCFORUM_FRAME_VIEW_NONE,
		XCFORUM_FRAME_VIEW_SUCCESS,
		XCFORUM_FRAME_VIEW_ERROR,
		XCFORUM_FRAME_VIEW_INDEX,
		XCFORUM_FRAME_VIEW_INPUT,
		XCFORUM_FRAME_VIEW_PREVIEW,
		XCFORUM_FRAME_VIEW_CANCEL
	);

	/**
	 * startup
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function startup()
	{

		parent::startup();
	
		XCube_DelegateUtils::call('Module.xcforum.Global.Event.GetAssetManager',new XCube_Ref($this->mAssetManager),$this->mXoopsModule->get('dirname'));
	
		$root =& XCube_Root::getSingleton();
		$root->mController->mExecute->add(array(&$this, 'execute'));

		//
		// TODO/Insert your initialization code.
		$GLOBALS['xoopsModuleConfig'] =& $root->mContext->mModuleConfig; // added naao
		//if (($html = apc_fetch('html')) === TRUE) {
		//	apc_delete('html');
		//}
		//apc_clear_cache();
		//
	}

	/**
	 * setAdminMode
	 * 
	 * @param	bool  $flag
	 * 
	 * @return	void
	**/
	public function setAdminMode(/*** bool ***/ $flag)
	{
		$this->mAdminFlag = $flag;
	}

	/**
	 * _getDefaultActionName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	private function _getDefaultActionName()
	{

		$req = XCube_Root::getSingleton()->mContext->mRequest;
		$dataId = $req->getRequest(_REQUESTED_DATA_ID);
		$dataname = $req->getRequest(_REQUESTED_DATA_NAME);
		$dataname = isset($dataname) ? $dataname : '';
		$action = $req->getRequest(_REQUESTED_ACTION_NAME);
	
		if($dataId>0){
			if(isset($action)){
				$actionName = ucfirst($dataname).ucfirst($action);
			}
			else{
				$actionName = ucfirst($dataname).'View';
			}
		}
		else{
			if(isset($action)){
				$actionName = ucfirst($dataname).ucfirst($action);
			}
			else{
				//$actionName = ucfirst($dataname).'List';
				$actionName = 'ForumsList';
			}
		}
	
		return $actionName;
	}

	/**
	 * setActionName
	 * 
	 * @param	string	$name
	 * 
	 * @return	void
	**/
	public function setActionName(/*** string ***/ $name)
	{
		$this->mActionName = $name;
	}

	/**
	 * getRenderSystemName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getRenderSystemName()
	{
		if(!$this->mAdminFlag)
		{
			return parent::getRenderSystemName();
		}
	
		// TODO will be use site config
		if(!defined('XCFORUM_ADMIN_RENDER_REGISTED'))
		{
			define('XCFORUM_ADMIN_RENDER_REGISTED',true);
			$root =& XCube_Root::getSingleton();
			$root->overrideSiteConfig(
				array(
					'RenderSystems' => array(
						'Xcforum_AdminRenderSystem' => 'Xcforum_AdminRenderSystem'
					),
					'Xcforum_AdminRenderSystem' => array(
						'root' => XCFORUM_TRUST_PATH,
						'path' => '/admin/class/XcforumAdminRenderSystem.class.php',
						'class' => 'Xcforum_AdminRenderSystem'
					)
				)
			);
		}
	
		return 'Xcforum_AdminRenderSystem';
	}

	/**
	 * getAdminMenu
	 * 
	 * @param	void
	 * 
	 * @return	{string 'title',string 'link',string 'keywords',bool 'show',bool 'absolute'}[]
	**/
	public function getAdminMenu()
	{
		//atrace();
		//exit;
		if(is_array($this->mAdminMenu))
		{
			return $this->mAdminMenu;
		}
	
		$root =& XCube_Root::getSingleton();
	
		// load admin menu
		$adminMenu = $this->mXoopsModule->getInfo('adminmenu');
		if(!is_array($adminMenu))
		{
			$adminMenu = array();
		}
	
		// add preference menu
		if($url = $this->getPreferenceEditUrl())
		{
			$adminMenu[] = array(
				'title'    => _PREFERENCES,
				'link'	   => $url,
				'absolute' => true
			);
		}
	
		// add help menu
		if($url = $this->getHelpViewUrl())
		{
			$adminMenu[] = array(
				'title'    => _HELP,
				'link'	   => $url,
				'absolute' => true
			);
		}
	
		$this->mAdminMenu = array();
		foreach($adminMenu as $menu)
		{
			if(!(isset($menu['absolute']) && $menu['absolute']))
			{
				$menu['link'] = XOOPS_MODULE_URL . '/' . $this->mXoopsModule->get('dirname') . '/' . $menu['link'];
			}
			$this->mAdminMenu[] = $menu;
		}
	
		return $this->mAdminMenu;
	}

	/**
	 * getPreferenceEditUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getPreferenceEditUrl()
	{
		if($this->_mPreferenceEditUrl === null)
		{
			if(is_array($this->mXoopsModule->getInfo('config')) && count($this->mXoopsModule->getInfo('config')) > 0)
			{
				$root =& XCube_Root::getSingleton();
				$this->_mPreferenceEditUrl = $root->mController->getPreferenceEditUrl($this->mXoopsModule);
			}
			else
			{
				$this->_mPreferenceEditUrl = false;
			}
		}
	
		return $this->_mPreferenceEditUrl;
	}

	/**
	 * getHelpViewUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getHelpViewUrl()
	{
		if($this->_mHelpViewUrl === null)
		{
			if($this->mXoopsModule->hasHelp())
			{
				$root =& XCube_Root::getSingleton();
				$this->_mHelpViewUrl = $root->mController->getHelpViewUrl($this->mXoopsModule);
			}
			else
			{
				$this->_mHelpViewUrl = false;
			}
		}
	
		return $this->_mHelpViewUrl;
	}

	/**
	 * execute
	 * 
	 * @param	XCube_Controller  &$controller
	 * 
	 * @return	void
	**/
	public function execute(/*** XCube_Controller ***/ &$controller)
	{
		//atrace();
		if($this->_createAction() === false)
		{
			//adump('doActionNotFoundError');
			//exit;
			$this->doActionNotFoundError();
			die();
		}
	
		if($this->mAction->prepare() === false)
		{
			//adump('doPreparationError');
			//exit;
			$this->doPreparationError();
			die();
		}
	
		if($this->mAction->hasPermission() === false)
		{
			//adump('doPermissionError');
			//exit;
			$this->doPermissionError();
			die();
		}
	
		$viewStatus = (getenv('REQUEST_METHOD') == 'POST') ?
			$this->mAction->execute() :
			$this->mAction->getDefaultView();
	
		if(in_array($viewStatus,$this->_mAllowViewNames))
		{
			$methodName = 'executeView' . ucfirst($viewStatus);
			if(is_callable(array($this->mAction,$methodName)))
			{
				$render = $this->getRenderTarget();
				$this->mAction->$methodName($render);
				$render->setAttribute('xoops_pagetitle', $this->mAction->getPagetitle());
				$this->mAction->setHeaderScript();
			}
		}
	}

	/**
	 * _createAction
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	private function _createAction()
	{
		$root =& XCube_Root::getSingleton();
	
		if($this->mActionName == null)
		{
			$this->mActionName = $root->mContext->mRequest->getRequest('action');
			if($this->mActionName == null)
			{
				$this->mActionName = $this->_getDefaultActionName();
			}
		}
	
		if(!ctype_alnum($this->mActionName))
		{
			return false;
		}
	
		$fileName = ($this->mAdminFlag ? '/admin' : '')
			. '/actions/' . ucfirst($this->mActionName) . 'Action.class.php';
		switch(true)
		{
			case file_exists(
				$path = XOOPS_MODULE_PATH . '/' . $this->mXoopsModule->get('dirname') . $fileName
			):
				break;
			case file_exists(
				$path = XCFORUM_TRUST_PATH . '/' . $fileName
			):
				break;
			default:
				return false;
		}
	
		require_once $path;
	
		$className = 'Xcforum_' . ($this->mAdminFlag ? 'Admin_' : '')
//		$className = 'Xcforum_'
			. ucfirst($this->mActionName) . 'Action';
		//adump($className);
		if(class_exists($className))
		{
			$this->mAction = new $className();
		}
		if(!$this->mAction instanceof Xcforum_AbstractAction)
		{
			return false;
		}
	
		return true;
	}

	/**
	 * doActionNotFoundError
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	private function doActionNotFoundError()
	{
		/**
		 * Module.xcforum.Global.Event.Exception.ActionNotFound
		 * 
		 * @param	string	$dirname
		 * 
		 * @return	void
		**/
		XCube_DelegateUtils::call('Module.xcforum.Global.Event.Exception.ActionNotFound',$this->mAssetManager->mDirname);
		/**
		 * Module.{dirname}.Event.Exception.ActionNotFound
		 * 
		 * @param	void
		 * 
		 * @return	void
		**/
		XCube_DelegateUtils::call('Module.' . $this->mXoopsModule->get('dirname') . '.Event.Exception.ActionNotFound');
		$root =& XCube_Root::getSingleton();
		$root->mController->executeForward(XOOPS_URL);
	}

	/**
	 * doPreparationError
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	private function doPreparationError()
	{
		/**
		 * Module.xcforum.Global.Event.Exception.Preparation
		 * 
		 * @param	string	$dirname
		 * 
		 * @return	void
		**/
		XCube_DelegateUtils::call('Module.xcforum.Global.Event.Exception.Preparation',$this->mAssetManager->mDirname);
		/**
		 * Module.{dirname}.Event.Exception.Preparation
		 * 
		 * @param	void
		 * 
		 * @return	void
		**/
		XCube_DelegateUtils::call('Module.' . $this->mXoopsModule->get('dirname') . '.Event.Exception.Preparation');
		$root =& XCube_Root::getSingleton();
		$root->mController->executeForward(XOOPS_URL);
	}

	/**
	 * doPermissionError
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	private function doPermissionError()
	{
		/**
		 * Module.xcforum.Global.Event.Exception.Permission
		 * 
		 * @param	string	$dirname
		 * 
		 * @return	void
		**/
		XCube_DelegateUtils::call('Module.xcforum.Global.Event.Exception.Permission',$this->mAssetManager->mDirname);
		/**
		 * Module.{dirname}.Event.Exception.Permission
		 * 
		 * @param	void
		 * 
		 * @return	void
		**/
		XCube_DelegateUtils::call('Module.' . $this->mXoopsModule->get('dirname') . '.Event.Exception.Permission');
		$root =& XCube_Root::getSingleton();
		$root->mController->executeRedirect(XOOPS_URL, 1, _MD_XCFORUM_ERROR_PERMISSION);
	}
}

?>
