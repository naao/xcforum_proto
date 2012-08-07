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
 * Xcforum_ForumsObject
**/
class Xcforum_ForumsObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'forum_id';
    const DATANAME = 'forums';
    public $mChildList = array('topics');

	private $moderate_groups = array();
	private $moderate_users = array();

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
        $this->initVar('forum_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('category_id', XOBJ_DTYPE_INT, '', true);
        $this->initVar('forum_external_link_format', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('forum_title', XOBJ_DTYPE_STRING, '', true, 255);
        $this->initVar('forum_desc', XOBJ_DTYPE_TEXT, '', false);
        $this->initVar('forum_topics_count', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_posts_count', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_last_post_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_last_post_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_weight', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_options', XOBJ_DTYPE_TEXT, '', false);
        $this->initVar('status', XOBJ_DTYPE_INT, '', false);
		// additional
		//$this->initVar('bit_new', XOBJ_DTYPE_INT, 0, false);
        //$this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);
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
			case Lenum_Status::PROGRESS:
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
	 * setModerateGroups
	 * @param	array  $moderate_groups
	 * @return	NULL
	 **/
	public function setModerateGroups( /** array ***/ $groups )
	{
		$this->moderate_groups = $groups;
	}

	/**
	 * getModerateGroups
	 * @return	array
	 **/
	public function getModerateGroups(){
		// called from templates
		return $this->moderate_groups;
	}

	/**
	 * setModerateUsers
	 * @param	array  $moderate_users
	 * @return	NULL
	 **/
	public function setModerateUsers( /** array ***/ $users ){
		$this->moderate_users = $users;
	}

	/**
	 * getModerateUsers
	 * @return	array
	 **/
	public function getModerateUsers(){
		// called from templates
		return $this->moderate_users;
	}

}

/**
 * Xcforum_ForumsHandler
**/
class Xcforum_ForumsHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_forums';
    public /*** string ***/ $mPrimary = 'forum_id';
    public /*** string ***/ $mClass = 'Xcforum_ForumsObject';

	private /*** array ***/ $grpNames = NULL;
	private /*** array ***/ $moderateGids = NULL;
	private /*** array ***/ $moderateUids = NULL;

	public /*** XCube_Root ***/ $mRoot = null;
	public /*** Xcforum_Module ***/ $mModule = null;
	public /*** Xcforum_AssetManager ***/ $mAsset = null;
	private $mfAccHandler ;

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
		    $this->mModule =& $this->mRoot->mContext->mModule;
			$this->mAsset =& $this->mModule->mAssetManager;

    }

	/**
	 * getForum
	 *
	 * @param	int  $forum_id
	 *
	 * @return	Xcforum_ForumsList
	 **/

 //	public function getForum(/*** int ***/ $forum_id)
//	{
//		if (isset($forum_id) && $forum_id > 0) {
//			$forum_obj = $this->get($forum_id);
//			return $forum_obj;
//		}
//	}


	/**
	 * getForumsObj
	 *
	 * @param	array  $attributes
	 * @param	array  $orders
	 * @param	int    $limit
	 * @param	int    $start
	 *
	 * @return	array
	 **/
	public function getForumsObj(/*** array ***/ $attributes, /*** array ***/ $orders=null ,
			/*** int ***/ $limit=50, /*** int ***/ $start=0)
	{
		// get topics Objects
		$criteria = new CriteriaCompo();
		foreach($attributes as $key => $value){
			$criteria->add(new Criteria($key, $value));
		}
		//		return $this->getObjects($criteria);
		if (isset($orders)){
			foreach($orders as $key => $value){
				$criteria->setSort($key, $value);
			}
		}
		return $this->getObjects($criteria, $limit, $start);
	}

	/**
	 * updateForumFromTopic
	 *
	 * @param	Xcforum_TopicsObject  $obj
	 *
	 * @param	array  $attributes
	 *
	 * @return	Xcforum_ForumsObject
	 **/
	public function updateForumFromTopic(/*** Xcforum_TopicsObject ***/ $obj, /*** array ***/ $attributes, /*** array ***/ $opt)
	{
		// called from Posts.class.php
		// TODO: 権限エラー(？）が出るが書き込みは完了している
		$_forum_id = ($obj->get('forum_id'));
		//adump($_topic_id);
		if (!empty($_forum_id) && (int)$_forum_id >0){
			$forum = $this->get($_forum_id);
			adump($opt);
			foreach($attributes as $key => $value){
				$forum->set($key, $value);
			}
			if ($opt['post_countup']===true){
				$forum->set( 'forum_posts_count', (int)$forum->get('forum_posts_count')+1 );
			}
			if ($opt['topic_countup']===true){
				$forum->set( 'forum_topics_count', (int)$forum->get('forum_topics_count')+1 );
			}
			//adump($forum);
			// update topic information
			if($forum instanceof Xcforum_ForumsObject){
				if($this->insert($forum, true) === true){
					//die;
					return $this->get($_forum_id);
				}
			}
		}

		return NULL;
	}

	/**
	 * getForumList
	 *
	 * @param	array  $categories
	 *
	 * @return	array
	 **/
	public function getForumsTitle( /*** array ***/ $categories=array() )
	{
		//$forumObjs = $this->getForumsObj();

		//$mHandler = & $this->_getHandler();
		$criteria = new CriteriaCompo();
		if (isset($categories)){
			foreach ($categories as $cat){
				$criteria->add(new Criteria( 'category_id', $cat ) );
			}
		}
		$forumObjs =& $this->getObjects($criteria);
		foreach ($forumObjs as $forum){
			$rtn[$forum->getVar('forum_id')]['forum_title']= $forum->getVar('forum_title');
			$rtn[$forum->getVar('forum_id')]['cat_id']= $forum->getVar('category_id');
		}
		//adump($forumObjs, $rtn);
		return $rtn;
	}

	/**
	 * setModerateGroups
	 *
	 * @param	Xcforum_ForumsObject  $mFobj
	 * @param	int  $forum_id
	 *
	 * @return	array
	 **/
	public function setModerateGroups( /*** Xcforum_ForumsObject ***/ &$mFobj, /*** int ***/ $forum_id )
	{
		if (!is_object($this->mfAccHandler)){
			$this->mfAccHandler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		}

		// get all group mames
		if ( ! is_array($this->grpNames) ){
			$this->grpNames =& $this->_getGroups();
		}
		// get moderoators group_ids
		if ( ! is_array($this->moderateGids) ){
			$this->moderateGids =$this->mfAccHandler->get_permitted_groups( 'moderate' );
		}

		// moderator groups
		$moderate_groups = array();
		if (!empty($this->moderateGids[$forum_id])){
			foreach ($this->moderateGids[$forum_id] as $gid){
				$moderate_groups[$gid]['gid'] = $gid;
				$moderate_groups[$gid]['gname'] = $this->grpNames[$gid];
			}
		}
		if ($mFobj instanceof Xcforum_ForumsObject){
			$mFobj->setModerateGroups( $moderate_groups );
		}
		return $moderate_groups ;
	}

	/**
	 * setModerateUsers
	 *
	 * @param	Xcforum_ForumsObject  $mFobj
	 * @param	int  $forum_id
	 *
	 * @return	array
	 **/
	public function setModerateUsers( /*** Xcforum_ForumsObject ***/ &$mFobj, /*** int ***/ $forum_id )
	{
		// get moderoators User_ids
		if ( ! is_array($this->moderateUids) ){
			$this->moderateUids =$this->mfAccHandler->get_permitted_users( 'moderate' );
		}

		// moderator users
		$moderate_users = array();
		if (!empty($this->moderateUids[$forum_id])){
			foreach ($this->moderateUids[$forum_id] as $uid){
				$moderate_users[$uid]['uid'] = $uid;
				$users[$uid] = new XoopsUser($uid);
				$uname = $users[$uid]->getUnameFromId($uid, $this->mRoot->mContext->mModuleConfig['use_name']);
				$moderate_users[$uid]['uname'] = !empty($uname) ? $uname : $users[$uid]->getUnameFromId($uid, 0);
				unset($uname);
			}
		}
		if ($mFobj instanceof Xcforum_ForumsObject){
			$mFobj->setModerateUsers( $moderate_users );
		}
		return $moderate_users ;
	}

	/**
	 * _setGroups
	 *
	 * @return	array
	 **/
	private function _getGroups()
	{
		// get all groups
		$group_handler =& xoops_gethandler( 'group' ) ;
		$criteria = new Criteriacompo();
		$criteria->setSort('groupid', 'DESC');
		$groups =& $group_handler->getObjects($criteria) ;

		// make Access Control array by Groups
		$grpNames = array();
		foreach( $groups as $group ) {
			$gid = (int)$group->getVar('groupid') ;
			$grpNames[$gid] = $group->getVar('name') ;
		}

		return $grpNames;

	}


}

?>
