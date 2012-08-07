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

require_once XCFORUM_TRUST_PATH . '/class/AbstractListAction.class.php';
require_once XCFORUM_TRUST_PATH . '/class/handler/Forumaccess.class.php';

/**
 * Xcforum_ForumsListAction
**/
class Xcforum_ForumsListAction extends Xcforum_AbstractListAction
{
	const DATANAME = 'forums';

	private $gotCatID ;
	private $mCatAcc ;
	private $mfAccHandler ;
	private $mForumAcc ;

	private /*** int ***/ $forum_id ;

	//public /*** string ***/ $mPrimary = 'forum_id';

	/**
	 * _getCatId
	 * 
	 * @param	void
	 * 
	 * @return	int
	**/
	/*
	protected function _getCatId()
	{
		return intval($this->mRoot->mContext->mRequest->getRequest('category_id'));
	}
	*/

	/**
	 * prepare
	 *
	 * @param	void
	 *
	 * @return	bool
	 **/
	public function prepare()
	{

		$this->mfAccHandler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		$this->mForumAcc = $this->mfAccHandler->get_forum_all_permissions();

		$this->mFilter = $this->_getFilterForm();
		$mCriteria = $this->mFilter->getCriteria();
		$mCriteria->add(new criteria('forum_id', $this->mForumAcc['can_read'], 'IN'));

		parent::prepare();
		$this->_setupAccessController('forums');

		$this->forum_id = (int)$this->mRoot->mContext->mRequest->getRequest('forum_id');

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

		$this->gotCatID = (int)$this->mRoot->mContext->mRequest->getRequest('category_id');
		//$this->mCatAcc = $this->mfAccHandler->get_category_all_permissions();

		$catPerm = true;
		if($this->gotCatID > 0){
			$catPerm = $this->mAccessController['main']->check($this->gotCatID, Xcforum_AbstractAccessController::VIEW, 'xcforum');
		}
		return $catPerm;
	}

	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{
		return parent::getDefaultView();
	/*
		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch();
	
		$handler =& $this->_getHandler();
		$criteria=$this->mFilter->getCriteria();
	
		$tree = array();
		if(! $this->_getCatId()){
			$catCriteria = new CriteriaCompo();

			//get permitted categories to show
			$idList = $this->mAccessController['main']->getPermittedIdList(Xcforum_AbstractAccessController::VIEW, $this->_getCatId());
			if(count($idList)>0 && $this->mAccessController['main']->getAccessControllerType()!='none'){
				$catCriteria->add(new Criteria('category_id', $idList, 'IN'));
				$criteria->add($catCriteria);
			}
		}
		$this->mObjects = $handler->getObjects($criteria);
	
		return XCFORUM_FRAME_VIEW_INDEX;
	*/
	}

	/**
	 * executeViewIndex
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
	{

		/*** Xcforum_ForumsHandler ***/
		$handler =& $this->_getHandler();

		$arr_bit_new = array();
		foreach ( $this->mObjects as &$obj){
			$forum_id = $obj->getshow('forum_id');
			$bit_new = ($obj->getshow('forum_last_post_time') ) && ! $obj->getshow('forum_topics_count')  ? 1 : 0 ;
			$arr_bit_new[$forum_id] = $bit_new;
			$obj->initVar('bit_new', XOBJ_DTYPE_INT, '', false);
			$obj->set('bit_new' , $bit_new);

			/*** Xcforum_PostsHandler ***/
			$mHandler =& $this->mAsset->getObject('handler','Posts',false);
			$last_post_id = $obj->getVar('forum_last_post_id');
			$mPostObj = $mHandler->get($last_post_id);

			if (is_object($mPostObj)){
				//user's info
				$uid = $mPostObj->get('uid');
				if ($uid >0){
					$users[$uid] = new XoopsUser($uid);
					$uname = $users[$uid]->getUnameFromId($uid, $this->mod_config['use_name']);
					$uname = !empty($uname) ? $uname : $users[$uid]->getUnameFromId($uid, 0);
					$obj->initVar('forum_last_post_dispname', XOBJ_DTYPE_STRING, $uname, false);
					unset($uname);
				} else {
					$obj->initVar('forum_last_post_dispname', XOBJ_DTYPE_STRING, $mPostObj->get('guest_name'), false);
				}
				$obj->initVar('forum_last_post_icon', XOBJ_DTYPE_STRING, $mPostObj->get('icon'), false);
			}

			$handler->setModerateGroups( $obj, $forum_id );
			$handler->setModerateUsers( $obj, $forum_id );

		}
		//adump($this->mObjects);


		$render->setTemplateName($this->mAsset->mDirname . '_forum_list.html');
		//$render->setAttribute('objects', $this->mObjects);
		//$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		//$render->setAttribute('mod_url', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname);
		//$render->setAttribute('mod_imageurl', XOOPS_MODULE_URL . '/'. $this->mAsset->mDirname . '/images');
		$render->setAttribute('arr_bit_new', $arr_bit_new);
		//$render->setAttribute('pageNavi', $this->mFilter->mNavi);
		$render->setAttribute('accessController', $this->mAccessController['main']);

		parent:: executeViewIndex($render);


	}
}

?>
