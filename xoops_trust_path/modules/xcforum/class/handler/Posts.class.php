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
require_once XCFORUM_TRUST_PATH . '/class/handler/Criteria.class.php';

/**
 * Xcforum_PostsObject
**/
class Xcforum_PostsObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'post_id';
    const DATANAME = 'posts';
    public $mChildList = array('postvotes','posthistories');
    public $mParentList = array('topics');

	public $mTopic = null;
	protected $_mIsTopicLoaded = false;
	public $mParent = null;
	protected $_mIsParentLoaded = false;
	public $mChildren = null;
	protected $_mIsChildrenLoaded = false;
	public $mReplyPath = array();
	protected $_mReplyPathLoaded = false;
	public $mChildIdList = array();

    /**
     * __construct
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function __construct()
    {
        parent::__construct();  
        $this->initVar('post_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('pid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('post_time', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('modified_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('uid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('uid_hidden', XOBJ_DTYPE_INT, '', false);
        $this->initVar('poster_ip', XOBJ_DTYPE_STRING, '', false, 15);
        $this->initVar('modifier_ip', XOBJ_DTYPE_STRING, '', false, 15);
        $this->initVar('subject', XOBJ_DTYPE_STRING, '', true, 255);
        $this->initVar('subject_waiting', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('html', XOBJ_DTYPE_INT, '', false);
        $this->initVar('smiley', XOBJ_DTYPE_INT, '', false);
        $this->initVar('xcode', XOBJ_DTYPE_INT, '', false);
        $this->initVar('br', XOBJ_DTYPE_INT, '', false);
        $this->initVar('number_entity', XOBJ_DTYPE_INT, '', false);
        $this->initVar('special_entity', XOBJ_DTYPE_INT, '', false);
        $this->initVar('icon', XOBJ_DTYPE_INT, '', false);
        $this->initVar('attachsig', XOBJ_DTYPE_INT, '', false);
        $this->initVar('invisible', XOBJ_DTYPE_INT, '', false);
        $this->initVar('approval', XOBJ_DTYPE_INT, '', false);
        $this->initVar('votes_sum', XOBJ_DTYPE_INT, '', false);
        $this->initVar('votes_count', XOBJ_DTYPE_INT, '', false);
        $this->initVar('depth_in_tree', XOBJ_DTYPE_INT, '', false);
        $this->initVar('order_in_tree', XOBJ_DTYPE_INT, '', false);
        $this->initVar('path_in_tree', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('unique_path', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('guest_name', XOBJ_DTYPE_STRING, '', false, 25);
        $this->initVar('guest_email', XOBJ_DTYPE_STRING, '', false, 60);
        $this->initVar('guest_url', XOBJ_DTYPE_STRING, '', false, 100);
        $this->initVar('guest_pass_md5', XOBJ_DTYPE_STRING, '', false, 40);
        $this->initVar('guest_trip', XOBJ_DTYPE_STRING, '', false, 40);
	    $this->initVar('post_text', XOBJ_DTYPE_TEXT, '', true);
        $this->initVar('post_text_waiting', XOBJ_DTYPE_STRING, '', false, 0);
        $this->initVar('status', XOBJ_DTYPE_INT, '', false);
        $this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);

		//$this->initVar('forum_id', XOBJ_DTYPE_INT, '', false);
   }

    /**
     * getShowStatus
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getShowStatus()
    {
        switch($this->get('status')){
            case Lenum_Status::DELETED:
                return _MD_LEGACY_STATUS_DELETED;
            case Lenum_Status::REJECTED:
                return _MD_LEGACY_STATUS_REJECTED;
            case Lenum_Status::POSTED:
                return _MD_LEGACY_STATUS_POSTED;
            case Lenum_Status::PUBLISHED:
                return _MD_LEGACY_STATUS_PUBLISHED;
        }
    }

	public function getImageNumber()
	{
		return 1;
	}

	/**
	 * @public
	 * load parent category Object of this category.
	 */
	public function loadParent($dirname)
	{
		if ($this->_mIsParentLoaded == false) {
			$handler = $this->_getHandler($dirname, $this->getPrimary);
			$this->mParent = $handler->get($this->get('p_id'));
			$this->_mIsParentLoaded = true;
		}
	}

	/**
	 * @public
	 * load child post Objects.
	 */
	public function loadChildren()
	{
		if ($this->_mIsChildrenLoaded == false) {
			$handler = Legacy_Utils::getModuleHandler('post', $this->getDirname());
			$this->mChildren = $handler->getObjects(new Criteria('p_id', $this->get('post_id')));
			$this->_mIsChildrenLoaded = true;
		}
	}

	public function getReplyPath()
	{
		if($this->_mIsReplyPathLoaded==false){
			$handler = Legacy_Utils::getModuleHandler('post', $this->getDirname());
			$this->_loadReplyPath($handler, $this->get('p_id'));
			$this->_mIsReplyPathLoaded=true;
		}
		return $this->mReplyPath;
	}

	/**
	 * @private
	 * load reply path array retroactively.
	 */
	protected function _loadReplyPath($handler, $p_id){
		$post = $handler->get($p_id);
		if(is_object($post)){
			array_unshift($this->mReplyPath, $post->get('post_id'));
			$this->_loadReplyPath($handler, $post->get('pid'));
		}
	}

	/**
	 * @public
	 * get child post' id array.
	 */
	public function getChildIdList()
	{
		$handler = Legacy_Utils::getModuleHandler('post', $this->getDirname());
		return $handler->getIdList(new Criteria('pid', $this->get('post_id')));
	}

	/**
	 * getPrimary
	 *
	 * @param	void
	 *
	 * @return	string
	 **/
	public function getPrimary()
	{
		return self::PRIMARY;
	}

	/**
	 * getDataname
	 *
	 * @param	void
	 *
	 * @return	string
	 **/
	public function getDataname()
	{
		return self::DATANAME;
	}

}

/**
 * Xcforum_PostsHandler
**/
class Xcforum_PostsHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_posts';
    public /*** string ***/ $mPrimary = 'post_id';
    public /*** string ***/ $mClass = 'Xcforum_PostsObject';

	public /*** XCube_Root ***/ $mRoot = null;
	public /*** int ***/ $got_forum_id = 0;

	public /*** Xcforum_PostsObject ***/ $inserted_obj = null;
	public /*** Xcforum_TopicsObject ***/ $topic_obj = null;

	static /*** boolean ***/ $post_updated = false;

    /**
     * __construct
     * 
     * @param   XoopsDatabase  &$db
     * @param   string  $dirname
     * 
     * @return  void
    **/
    public function __construct(/*** XoopsDatabase ***/ &$db,/*** string ***/ $dirname)
    {
        $this->mTable = strtr($this->mTable,array('{dirname}' => $dirname));
        parent::XoopsObjectGenericHandler($db);

		$this->mRoot =& XCube_Root::getSingleton();
		$this->got_forum_id = intval($this->mRoot->mContext->mRequest->getRequest('forum_id'));
    }

	/**
	 * @public
	 * get child post' id array.
	 */
/*	public function getChildIdList()
	{
		$handler = Legacy_Utils::getModuleHandler('post', $this->getDirname());
		return $handler->getIdList(new Criteria('pid', $this->get('post_id')));
	}
*/

	/**
	 * insert
	 *
	 * @param	XCforum_PostObject  $obj
	 * @param	bool  $force
	 *
	 * @return	bool
	 **/
	public function insert(/*** Xcform_PostObject ***/ $obj, /*** bool ***/ $force=false)
	{
		/*  order as:
			1:insert post
			2:update topic by post_id |or| insert topic by post_id and forum_id
			3:update forum by post and topic
			4:update post by topic_id
		*/

		// 1:insert post
		if(parent::insert($obj, $force)===true ){
			$this->inserted_obj = $obj;
			// Only for a new post
			if ($obj->isNew()){
				// 2:update topic |or| insert topic by post_id and forum_id
				$handler = Legacy_Utils::getModuleHandler('topics', $this->getDirname());
				$ins_arr = array(
					'topic_last_post_id' => $obj->get('post_id'),
					'topic_last_post_time' => $obj->get('post_time')
				);

				// Adding a New Topic in the forum
				//adump($this->got_forum_id);
				if($this->got_forum_id > 0) {
					$ins_arr = array_merge( $ins_arr, array(
							'forum_id'=> $this->got_forum_id )
					);
				}
				$tpObj =& $handler->updateTopicFromPost($obj,$ins_arr);
				//adump($tpObj);

				if (is_object($tpObj)){
					// 3:update forum by post and topic
					$this->topic_obj = $tpObj;
				//	$frHandler =& $this->mAsset->getObject('handler', 'Forums',false);
					$frHandler = Legacy_Utils::getModuleHandler('forums', $this->getDirname());
					$frObj = $frHandler->get($this->got_forum_id);
					$ins_arr = array(
						'forum_last_post_id' => (int)$tpObj->get('topic_last_post_id'),
						'forum_last_post_time' => (int)$tpObj->get('topic_last_post_time')
					);
					$pst_countup = ($obj->isNEW()) ? true : false;
					$tpc_countup = ($tpObj->isNEW()) ? true : false;

					$frHandler->updateForumFromTopic( $tpObj, $ins_arr, array('post_countup' => $pst_countup, 'topic_countup' => $tpc_countup) );

					//$sql = "SELECT MAX(topic_last_post_id),MAX(topic_last_post_time),COUNT(topic_id),SUM(topic_posts_count) FROM ".$db->prefix($dirname."_topics")." WHERE forum_id=$forum_id" ;

					// 4:update post by topic_id
					$inserted_topic_id = $tpObj->get('topic_id');
					if( $this->updatePost($obj,
						array(
							'topic_id' => $inserted_topic_id
						)
					) == true ){
						if ($this->post_updated !== true){
							// update the podie;st tree information
							$this->updatePostTree();
							$this->post_updated = true;
						}
					//die; //for debug
						return true;
					}
					return false;
				}
				return false;
				// TODO: error handle and return
			}
			return false;
		}
	}

	/**
	 * updatePost
	 *
	 * @param	Xcforum_PostObject  $obj
	 *
	 * @return	int
	 **/
	public function updatePost(/*** Xcforum_PostObject ***/ $obj, /*** array ***/ $attributes)
	{
		//atrace();
		$post_id = ($obj->get('post_id'));
		if (!empty($post_id) && (int)$post_id >0){
			$post_obj = $this->get($post_id);
			foreach($attributes as $key => $value){
				$post_obj->set($key, $value);
			}
		}
			if($this->insert($post_obj, true) === true){
				$this->inserted_obj = $post_obj;
				return $this->db->getInsertId();
			}
		return -1;
	}

	/*
	 * updatePostTree
	 */
	public function updatePostTree(){
		// called by PostsEditAction::prepare
		$first_post_id = $this->topic_obj->get('topic_first_post_id');
		$first_post_id = ($first_post_id >0) ? $first_post_id : 0;
		$obj = $this->inserted_obj;
		$unique_path = $obj->get('unique_path');
		$trsFunc =& Xcforum_Utils::getTransactFuncObject();
		$tree_array = $trsFunc->maketree_recursive( $first_post_id, 'post_id' , array() , 0 , empty( $unique_path ) ? '.1' : $unique_path ) ;
		if( ! empty( $tree_array ) ) {
			$mObj = new Xcforum_PostsObject();
			$mHandler = new self($this->mRoot->mController->mDB, $this->mDirname) ;
			foreach( $tree_array as $key => $val ) {
				//$db->queryF( "UPDATE ".$db->prefix($this->mAsset->mDirname."_posts")." SET depth_in_tree=".$val['depth'].", order_in_tree=".($key+1).", unique_path='".addslashes($val['unique_path'])."' WHERE post_id=".$val['post_id'] ) ;
				$mObj = $mHandler->get($val['post_id']);
				if( is_object($mObj)){
					$mObj->set('depth_in_tree', $val['depth'] );
					$mObj->set('order_in_tree', $key+1 );
					$mObj->set('unique_path', addslashes($val['unique_path']) );
					$mHandler->insert($mObj, false);
				}
			}
		}

	}



	/**
	 * filter and get topics
	 *
	 * @param int[]	$aclIds
	 * @param mixed		$moduleArr
	 *  string	$moduleArr['dirname']
	 *  string	$moduleArr['dataname']
	 * @param int		$uid
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	Xcforum_PostsObject[]
	 */
//	public function getComments(/*** int ***/ $aclIds=null, /*** mixed[] ***/ $moduleArr=null, /*** int ***/ $uid=null, /*** int ***/ $limit=10, /*** int ***/ $start=0)
/*	{
		$cri = new CriteriaCompo();

		$aclCriteria = $this->_getCriteriaForAclFilter($aclIds);
		if($aclCriteria->hasChildElements()){
			$cri->add($aclCriteria);
		}

		$moduleCriteria = $this->_getCriteriaForModuleFilter($moduleArr);
		if(isset($moduleCriteria)){
			$cri->add($moduleCriteria);
		}

		if($uid>0){
			$cri->add(new Criteria('uid', $uid));
		}

		$cri->setSort('updatetime', 'DESC');
		return $this->getObjects($cri, $limit, $start);
	}
*/

	/**************************************
	 *  xcforum comment integration functions
	 **************************************/

	/**
	 * getPostsObj
	 *
	 * @param	array  $attributes
	 *
	 * @return	Xcforum_PostsList
	 **/
	public function getPostsObj(/*** array ***/ $attributes, /*** array ***/ $orders=null ,
								/*** int ***/ $limit=50, /*** int ***/ $start=0)
	{
		// get posts Objects
		$criteria = new CriteriaCompo();
		foreach($attributes as $key => $value){
			if (is_array($value)){
				$criteria->add(new Criteria($key, $value, 'IN'));    // only for XCL2.2 or more
			} else {
				$criteria->add(new Criteria($key, $value));
			}
		}
		if (isset($orders)){
			foreach($orders as $key => $value){
				$criteria->setSort($key, $value);
			}
		}
		return $this->getObjects($criteria, $limit, $start);
	}

}

?>
