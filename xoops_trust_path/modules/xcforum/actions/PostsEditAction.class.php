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

require_once XCFORUM_TRUST_PATH . '/actions/TopicsEditAction.class.php';

/**
 * Xcforum_PostsEditAction
**/
class Xcforum_PostsEditAction extends Xcforum_AbstractEditAction
{
    const DATANAME = 'posts';

	//public /*** string ***/ $mPrimary = 'post_id';

	public /*** Xcforum_TopicsEditAction ***/ $mTopicsObj ;

	private /*** object ***/ $mParentObj = null;
	private /*** Xcforum_ForumaccessHandler **/ $mfAccHandler ;
	private /*** array **/ $mForumAcc ;

	private /*** int ***/ $forum_id ;

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

	    // in case of NEW post
        if($this->mObject->isNew()){
			if($this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser')){
				$this->mObject->set('uid', $this->mRoot->mContext->mXoopsUser->get('uid'));
			}
				$this->mObject->set('post_time', time());
				$this->mObject->set('modified_time', time());
				$this->mObject->set('uid_hidden', 0);
				$this->mObject->set('poster_ip', addslashes(@$_SERVER['REMOTE_ADDR']));
				$this->mObject->set('modifier_ip', addslashes(@$_SERVER['REMOTE_ADDR']));

				// get pid --> is it response post?
				$pid = (int)$this->mRoot->mContext->mRequest->getRequest('pid');
				if (isset($pid) && $pid > 0) {
					$this->mObject->set('pid', $pid);
					$topic_id = (int)$this->mRoot->mContext->mRequest->getRequest('topic_id');
					$mParentHandler = & $this->_getHandler();
					$criteria = new CriteriaCompo();
					$criteria->add(new Criteria( 'post_id', $pid ) );
					$mGotObjects =& $mParentHandler->getObjects($criteria ,true);
					$this->mParentObj =& $mGotObjects[0];
					// get parent object --> is it really response post?
					if (is_object($this->mParentObj)){
						$this->mObject->set('topic_id', $this->mParentObj->get('topic_id'));
						$this->mObject->set('subject', "Re: ".ltrim ($this->mParentObj->get('subject'), 'Re: '));
						$this->mObject->set('depth_in_tree', (int)$this->mParentObj->get('depth_in_tree')+1);
					}
				}
				//adump($pid,$topic_id);
				//adump($this->mParentObj);
        }


     return true;
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
		//return $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? true : false;

		if (!is_object($this->mObject)){
			return false;   // TODO: No item error message
		} else {

			$topic_id =(int)$this->mObject->get('topic_id');
			if ( $topic_id > 0 ){
				$topicHandler =& $this->mAsset->getObject('handler', 'topics', false);
				$this->mTopicObj = $topicHandler->get($topic_id);
				//adump($this->mTopicObj);
				$this->forum_id = $this->mTopicObj->get('forum_id');
			} else {
				$this->forum_id = (int)$this->mRoot->mContext->mRequest->getRequest('forum_id');
			}
			if ( $this->forum_id <=0 ){
				return false;
			}
			$this->mfAccHandler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
			$this->mForumAcc = $this->mfAccHandler->get_forum_all_permissions();

			if ( isset($this->forum_id) && $this->forum_id > 0 ){
				if ( isset($this->mForumAcc) && count($this->mForumAcc) >0 ){
					if($this->mObject->isNew() && in_array( $this->forum_id, $this->mForumAcc['post']) ){
							return true;
					} else if ( in_array( $this->forum_id, $this->mForumAcc['edit']) ){
						// check edit permission
						if ( ! $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ){
							return false;    // TODO for Guest edit
						}
						$isadminormod = $this->mod_isadmin ? true : in_array( $this->forum_id, $this->mForumAcc['moderate']);
						$poster_uid = (int)$this->mObject->get('uid');
						$uid = $this->mRoot->mContext->mXoopsUser->get('uid');
						if ( $isadminormod || ($poster_uid == $uid && ( time() <=$this->mObject->get('post_time') + $this->mod_config['selfeditlimit'] )) ){
							return true;
						}
					}
				}
			}
		}

		return false;

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
		//atrace();

		$render->setTemplateName($this->mAsset->mDirname . '_post_form.html');
		//$render->setAttribute('actionForm', $this->mActionForm);
		// $render->setAttribute('object', $this->mObject);
		// $render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);

		//set tag usage
		$render->setAttribute('tag_dirname', $this->mRoot->mContext->mModuleConfig['tag_dirname']);

		$render->setAttribute('forum_id', intval($this->mRoot->mContext->mRequest->getRequest('forum_id')));

		// in case of a child post
		if (is_object($this->mParentObj)){
			//adump("reply");
			$render->setAttribute('mode', 'reply');
			$render->setAttribute('reference_subject', htmlspecialchars($this->mParentObj->get('subject'), ENT_QUOTES, _CHARSET));
			$render->setAttribute('reference_name', 'name'); // TODO
			$render->setAttribute('reference_quote',  '[quote]'.$this->mParentObj->get('post_text').'[/quote]');
			$render->setAttribute('reference_message', htmlspecialchars($this->mParentObj->get('post_text'), ENT_QUOTES, _CHARSET));
			$render->setAttribute('topic_id', $this->mParentObj->get('topic_id'));
			$render->setAttribute('subject', htmlspecialchars("Re: ".ltrim ($this->mParentObj->get('subject'), 'Re: '), ENT_QUOTES, _CHARSET));
		}

		parent:: executeViewInput($render);

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
		//$this->mRoot->mController->executeRedirect($this->_getNextUri($this->_getConst('DATANAME')), 1, _MD_XCFORUM_ERROR_DBUPDATE_FAILED);
	}

}
?>
