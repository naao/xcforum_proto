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

require_once XCFORUM_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Xcforum_ForumsEditAction
**/
class Xcforum_ForumsEditAction extends Xcforum_AbstractEditAction
{
    const DATANAME = 'forums';

	//public /*** string ***/ $mPrimary = 'forum_id';

	/**
	 * _getCatId
	 * 
	 * @param	void
	 * 
	 * @return	int
	**/
	protected function _getCatId()
	{
		return ($this->mObject->get('category_id')) ? $this->mObject->get('category_id') : intval($this->mRoot->mContext->mRequest->getRequest('category_id'));
	}

	/**
	 * hasPermission
	 * 
	 * @param	void
	 *
	 * @return	bool
	**/
	public function hasPermission()
	{
		$catId = $this->_getCatId();

		return $this->mod_isadmin;  // Only module administrator can edit forum settings
/*
		$catPerm = false;
		if($catId>0){
			//is Manager ?
			$check = $this->mAccessController['main']->check($catId, Xcforum_AbstractAccessController::MANAGE, 'forums');
			if($check==true){
				$catPerm = true;
			}
			//is new post and has post permission ?
			$check = $this->mAccessController['main']->check($catId, Xcforum_AbstractAccessController::POST, 'forums');
			if($check==true && $this->mObject->isNew()){
				$catPerm = true;
			}
			//is old post and your post ?
			if($check==true && ! $this->mObject->isNew() && $this->mObject->get('uid')==Legacy_Utils::getUid() && $this->mObject->get('uid')>0){
				$catPerm = true;
			}
		}
		else{
			$idList = array();
			$idList = $this->mAccessController['main']->getPermittedIdList(Xcforum_AbstractAccessController::POST, $this->_getCatId());
			if(count($idList)>0 || $this->mAccessController['main']->getAccessControllerType()=='none'){
				$catPerm = true;
			}
		}
		return $catPerm;
*/
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

        parent::prepare();
        if($this->mObject->isNew()){
			$this->mObject->set('category_id', $this->_getCatId());
			$this->mObject->set('forum_topics_count', 0);
			$this->mObject->set('forum_posts_count', 0);
			$this->mObject->set('forum_last_post_id', 0);
			$this->mObject->set('forum_last_post_time', 0);
			$this->mObject->set('status', 0);
			//$this->mObject->set('posttime', 0);
			//die;
			//adump($this->mObject);

       }

		$this->_setupAccessController('forums');

		//adump($temp=($this->mObject->get('topics_count')) ? $this->mObject->get('topics_count') : intval($this->mRoot->mContext->mRequest->getRequest('topics_count')));
		//$this->mObject->set('topics_count',$temp);

     return true;
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
		include XCFORUM_TRUST_PATH . '/include/constant_can_override.inc.php' ;
		$this->mObject->set('forum_options', Xcforum_Utils::set_category_options( $xcforum_configs_can_be_override ));

		$rtn = parent::execute();
		if ($rtn === true){
			// TODO: permissions are set same as the parent category. (also moderator)

		}
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
	    include XCFORUM_TRUST_PATH . '/include/constant_can_override.inc.php' ;
	    $options4html = "";
	    $forum_configs = @unserialize( $this->mObject->get('forum_options') ) ;
	    if( is_array( $forum_configs ) ) foreach( $forum_configs as $key => $val ) {
		    if( isset( $xcforum_configs_can_be_override[ $key ] ) ) {
			    $options4html .= htmlspecialchars( $key , ENT_QUOTES, _CHARSET ) . ':' . htmlspecialchars( $val , ENT_QUOTES, _CHARSET ) . "\n" ;
		    }
	    }
	    $render->setAttribute('forum_options', $options4html);
	    $render->setAttribute('forum_options_desc', Xcforum_Utils::main_get_categoryoptions4edit( $xcforum_configs_can_be_override ));
        //$render->setTemplateName($this->mAsset->mDirname . '_forums_edit.html');
		$render->setTemplateName($this->mAsset->mDirname . '_forum_form.html');
        //$render->setAttribute('actionForm', $this->mActionForm);
        //$render->setAttribute('object', $this->mObject);
        //$render->setAttribute('dirname', $this->mAsset->mDirname);
		//$render->setAttribute('mod_url', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname);
        $render->setAttribute('dataname', self::DATANAME);
		//$render->setAttribute('mod_isadmin', $this->mod_isadmin);

        //set tag usage
        //$render->setAttribute('tag_dirname', $this->mRoot->mContext->mModuleConfig['tag_dirname']);
        
		$render->setAttribute('accessController',$this->mAccessController['main']);

		parent:: executeViewInput($render);
 }

}
?>
