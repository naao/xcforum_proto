<?php

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

class Xcforum_Root {

	public $mRoot ;
	public $mModule ;
	public $mUser ;
	public $mXoopsUser ;
	//public $xoops_root_path ;
	//public $mydirname ;
	//public $mid ;
	//public $mname ;
	public $mod_config ;
	public $myts ;
	public $db ;	// Database instance
	public $params = array() ;	//some parameters

	public function __construct()
	{
		//$this->xoops_root_path = XOOPS_ROOT_PATH;

		$this->mRoot = $root = XCube_Root::getSingleton();
		$this->db =& $root->mController->mDB;

		// module ID & name
		$this->mModule =& $root->mContext->mXoopsModule;
		//$this->mid = $this->mModule->get('mid');
		//$this->mname = $this->mModule->get('name');
		//$this->mydirname = $this->mModule->get('dirname');
		// module config
		$this->mod_config =& $root->mContext->mModuleConfig;
		//adump($this->mod_config);

		// user
		$this->mUser =& $this->mRoot->mContext->mUser ;
		$this->mXoopsUser =& $this->mRoot->mContext->mXoopsUser ;

		// mytextsanitizer
		require_once XCFORUM_TRUST_PATH . '/class/Textsanitizer.php' ;
		$this->myts =& Xcforum_TextSanitizer::getInstance() ;

	}

	function &getInstance()
	{
		static $instance;
		if (!isset($instance))
			$instance = new Xcforum_Root();
		return $instance;
	}

} // end class

?>