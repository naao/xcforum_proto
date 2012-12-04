<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
 **/

require_once XOOPS_TRUST_PATH.'/modules/xcforum/include/comment_functions.php' ;
require_once XOOPS_TRUST_PATH.'/modules/xcforum/include/main_functions.php' ;
require_once XOOPS_TRUST_PATH.'/libs/altsys/class/D3NotificationHandler.class.php' ;

//require_once XCFORUM_TRUST_PATH . '/class/AbstractAction.class.php';

// abstract class for xcforum comment integration
/**
 * Xcforum_Comment_Abstract
 **/
class Xcforum_Comment_Abstract {

	//get module asset for handlers
	var $asset = null;

	/**
	 * @var xcforum_TopicsHandler
	 *
	 * @private
	 **/
	var $_mTopicsHandler = null;

	/**
	 * @var xcforum_PostsHandler
	 *
	 * @private
	 **/
	var $_mPostsHandler = null;

	/**
	 * @var $_mPostsActionForm
	 *
	 * @private
	 **/
	var $_mPostsActionForm = null;

	/**
	 * @var xcforum_PostsRender
	 *
	 * @private
	 **/
	var $_mPostsRender = null;

	/**
	 * @var xcforum_PostsObject
	 *
	 * @private
	 **/
	var $_mOject = null;

	/**
	 * @var string[]
	 *
	 * @private
	 **/
	var $_mOptions = array();

	public $mRoot = null;
	public $xcforumModule = null;
	public $mModule = null;
	public $mTopic = null;
	protected $_mIsTopicLoaded = false;
	public $mPosts = null;
	protected $_mIsPostsLoaded = false;

	public $mUser = null;
	public $mXoopsUser = null;

var $xcforum_dirname = '' ;
var $mydirname = '' ;
var $module = null ;
var $mytrustdirname = '' ;
var $mod_config = array() ;
var $smarty = null ;

public function __construct( $xcforum_dirname , $target_dirname , $target_trustdirname = '' )
{

	$this->mRoot =& XCube_Root::getSingleton();
	//adump($this->mModule);

	$this->mUser =& $this->mRoot->mContext->mUser ;
	$this->mXoopsUser =& $this->mRoot->mContext->mXoopsUser ;
	//adump($this->mUser,$this->mXoopsUser);

	$this->mydirname = $target_dirname ;
	$this->mytrustdirname = $target_trustdirname ;
	$this->xcforum_dirname = $xcforum_dirname ;

	// set $this->mod_config as config of target_module
	if( $this->mydirname ) {
		$module_hanlder =& xoops_gethandler( 'module' ) ;
		$config_handler =& xoops_gethandler( 'config' ) ;
		$this->module =& $module_hanlder->getByDirname( $this->mydirname ) ;
		if( is_object( $this->module ) ) {
			$this->mod_config =& $config_handler->getConfigsByCat( 0 , $this->module->getVar( 'mid' ) ) ;
		}
	}

	if( empty( $xcforum_dirname ) ) $this->setxcforumDirname() ;
	if( $this->xcforum_dirname ) {
		$forum_module_hanlder =& xoops_gethandler( 'module' ) ;
		$this->xcforumModule =& $forum_module_hanlder->getByDirname( $this->xcforum_dirname ) ;
		if( is_object( $this->xcforumModule ) ) {
			$forum_config_handler =& xoops_gethandler( 'config' ) ;
			$forum_ModuleConfig =& $forum_config_handler->getConfigsByCat( 0 ,  $this->xcforumModule->getVar( 'mid' ) ) ;
		}
	}

	//get module asset for handlers
	$this->asset = null;
	XCube_DelegateUtils::call(
		'Module.xcforum.Global.Event.GetAssetManager',
		new XCube_Ref($this->asset),$this->xcforum_dirname
	);

	// handler
	$this->_mTopicsHandler =& $this->asset->getObject('handler','topics');
	$this->_mPostsHandler =& $this->asset->getObject('handler','posts');

	// actionform
	$this->_mPostsActionForm =& $this->asset->getObject('form','posts',false,'edit');
	// render
	$this->_mPostsRender =& $this->asset->getObject('render','posts',false,'edit');

}


// set smarty
function setSmarty( &$smarty )
{
	$this->smarty =& $smarty ;
}


// abstract (override it)
// set xcforum_dirname from parameter or config
function setxcforumDirname( $xcforum_dirname = '' )
{
	if( $xcforum_dirname ) {
		$this->xcforum_dirname = $xcforum_dirname ;
	} else if( ! empty( $this->mod_config['comment_dirname'] ) ) {
		$this->xcforum_dirname = $this->mod_config['comment_dirname'] ;
	} else {
		$this->xcforum_dirname = 'xcforum' ;
	}
}


// get forum_id from $params or config
// override it if necessary
protected function getForumId( $params )
{
	if( ! empty( $params['forum_id'] ) ) {
		return intval( $params['forum_id'] ) ;
	} else if( ! empty( $this->mod_config['comment_forum_id'] ) ) {
		return $this->mod_config['comment_forum_id'] ;
	} else {
		return 1 ;
	}
}


// get view from $params or config
// override it if necessary
protected function getView( $params )
{
	if( ! empty( $params['view'] ) ) {
		return $params['view'] ;
	} else if( ! empty( $this->mod_config['comment_view'] ) ) {
		return $this->mod_config['comment_view'] ;
	} else {
		return 'listposts' ;
	}
}


// get view from $params or config
// override it if necessary
protected function getOrder( $params )
{
	if( ! empty( $params['order'] ) ) {
		return strtolower( $params['order'] ) ;
	} else if( ! empty( $this->mod_config['comment_order'] ) ) {
		return $this->mod_config['comment_order'] ;
	} else {
		return 'desc' ;
	}
}


// get number of posts will be displayed from $params or config
// override it if necessary
function getPostsNum( $params )
{
	if( ! empty( $params['posts_num'] ) ) {
		return $params['posts_num'] ;
	} else if( ! empty( $this->mod_config['comment_posts_num'] ) ) {
		return $this->mod_config['comment_posts_num'] ;
	} else {
		return 10 ;
	}
}


// abstract (override it)
// get reference description as string
protected function fetchDescription( $link_id )
{
	return false ;
}


// abstract (override it)
// get reference information as array
protected function fetchSummary( $link_id )
{
	return array( 'module_name' => '' , 'subject' => '' , 'uri' => '' , 'summary' => '' ) ;
	// all values should be HTML escaped.
}


// get external_link_id from $params
// override it if necessary
protected function external_link_id( $params )
{
	return @$params['id'] ;
}


// get subject not escaped
// override it if necessary
protected function getSubjectRaw( $params )
{
	return empty( $params['subject_escaped'] ) ? @$params['subject'] : $this->unhtmlspecialchars( @$params['subject'] ) ;
}


// public
public function displayCommentsInline( $params )
{
	$new_params = $this->restructParams( $params ) ;

	// get topics
	$attribute = array('topic_external_link_id' => (int)$new_params['external_link_id']);
	$topicsObj = $this->_mTopicsHandler->getTopicsObj( $attribute );
	foreach ($topicsObj as $topic){
		$topic_ids[] = $topic->get('topic_id');
		// TODO check forum permission
	}

		//adump($new_params['external_link_id'],$topic_ids);
	//get block options
	//$limit = $this->getBlockOption('limit');
	$start = 0;    //TODO
	$limit = 50;    //TODO
	$order = null;   //TODO
	//TODO

	// get posts
	$attribute = array('topic_id' => $topic_ids);    // only for XCL2.2 or more
	$order = array('modified_time' => 'DESC');
	$this->_mObject = $this->_mPostsHandler->getPostsObj($attribute, $order, $limit, $start);
		//adump($this->_mObject);

	$this->_render_comments( $this->xcforum_dirname , $new_params['forum_id'] , $new_params , $this->smarty ) ;

}


// public
public function displayCommentsCount( $params )
{
	$comments_count = $this->countComments( $this->restructParams( $params ) ) ;

	if( empty( $params['var'] ) ) {
		// display
		echo $comments_count ;
	} else {
		// assign as "var"
		$this->smarty->assign( $params['var'] , $comments_count ) ;
	}
}


// protected
protected function restructParams( $params )
{
	return array(
		'class' => $params['class'] ,
		'view' => $this->getView( $params ) ,
		'order' => $this->getOrder( $params ) ,
		'posts_num' => $this->getPostsNum( $params ) ,
		'subject_raw' => $this->getSubjectRaw( $params ) ,
		'forum_id' => $this->getForumId( $params ) ,
		'forum_dirname' => $this->xcforum_dirname ,
		'external_link_id' => $this->external_link_id( $params ) ,
		'external_dirname' => $this->mydirname ,
		'external_trustdirname' => $this->mytrustdirname ,
	) ;
}


// minimum check
// if you want to allow "string id", override it
public function validate_id( $link_id )
{
	$ret = intval( $link_id ) ;
	if( $ret <= 0 ) return false ;
	return $ret ;
}


// callback on newtopic/edit/reply/delete
// abstract
public function onUpdate( $mode , $link_id , $forum_id , $topic_id , $post_id = 0 )
{
	return true ;
}


// can vote
// override it if necessary
public function canVote( $link_id , $original_flag , $post_id )
{
	return $original_flag ;
}


// can post
// override it if necessary
public function canPost( $link_id , $original_flag )
{
	return $original_flag ;
}


// can reply
// override it if necessary
public function canReply( $link_id , $original_flag , $post_id )
{
	return $original_flag ;
}


// can edit
// override it if necessary
public function canEdit( $link_id , $original_flag , $post_id )
{
	return $original_flag ;
}


// can delete
// override it if necessary
public function canDelete( $link_id , $original_flag , $post_id )
{
	return $original_flag ;
}


// can delete
// override it if necessary
public function needApprove( $link_id , $original_flag )
{
	return $original_flag ;
}


// processing xoops notification for 'comment'
// override it if necessary
public function processCommentNotifications( $mode , $link_id , $forum_id , $topic_id , $post_id )
{
	// non-module integration returns false quickly
	if( ! is_object( $this->module ) ) return false ;

	$not_module =& $this->module ;
	$not_modid = $this->module->getVar('mid') ;
	$not_catinfo =& notificationCommentCategoryInfo( $not_modid ) ;

	// module without 'comment' notification
	if( empty( $not_catinfo ) ) return false ;

	$not_category = $not_catinfo['name'] ;
	$not_itemid = $link_id ;
	$not_event = 'comment' ; // 'comment_submit'?

	$comment_tags = array( 'X_COMMENT_URL' => XOOPS_URL.'/modules/'.$this->xcforum_dirname.'/index.php?post_id='.intval($post_id) ) ;

	$users2notify = xcforum_get_users_can_read_forum( $this->xcforum_dirname , $forum_id ) ;
	if( empty( $users2notify ) ) $users2notify = array( 0 ) ;

	$not_handler =& D3NotificationHandler::getInstance() ;
	$not_handler->triggerEvent( $this->mydirname , $this->mytrustdirname , $not_category , $not_itemid , $not_event , $comment_tags , $users2notify ) ;
}


// returns comment count
// override it if necessary
public function countComments( $params )
	{
	$db =& Database::getInstance() ;

	$forum_id = $params['forum_id'] ;
	$mydirname = $params['forum_dirname'] ;

	// check the xcforum exists and is active
	$module_hanlder =& xoops_gethandler( 'module' ) ;
	$module =& $module_hanlder->getByDirname( $mydirname ) ;
	if( ! is_object( $module ) || ! $module->getVar('isactive') ) {
		return 0 ;
	}

	// does not check the permission of "module_read" about the xcforum

	// query it
	$select = $params['view'] == 'listtopics' ? 'COUNT(t.topic_id)' : 'SUM(t.topic_posts_count)' ;
	$sql = "SELECT $select FROM ".$db->prefix($mydirname."_topics")." t WHERE t.forum_id=$forum_id AND ! t.topic_invisible AND topic_external_link_id='".addslashes($params['external_link_id'])."'" ;
	if( ! $trs = $db->query( $sql ) ) die( 'xcforum_comment_error in '.__LINE__ ) ;
	list( $count ) = $db->fetchRow( $trs ) ;
	
	return $count ;
}


// returns posts count (does not check the permissions)
public function getPostsCount( $forum_id , $link_id )
{
	$db =& Database::getInstance() ;

	list( $count ) = $db->fetchRow( $db->query( "SELECT COUNT(*) FROM ".$db->prefix($this->xcforum_dirname."_posts")." p LEFT JOIN ".$db->prefix($this->xcforum_dirname."_topics")." t ON t.topic_id=p.topic_id WHERE t.forum_id=$forum_id AND t.topic_external_link_id='$link_id'" ) ) ;

	return intval( $count ) ;
}


// returns topics count (does not check the permissions)
public function getTopicsCount( $forum_id , $link_id )
{
	$db =& Database::getInstance() ;

	list( $count ) = $db->fetchRow( $db->query( "SELECT COUNT(*) FROM ".$db->prefix($this->xcforum_dirname."_topics")." t WHERE t.forum_id=$forum_id AND t.topic_external_link_id='$link_id'" ) ) ;

	return intval( $count ) ;
}


// unhtmlspecialchars (utility)
protected function unhtmlspecialchars( $text , $quotes = ENT_QUOTES )
{
	return strtr( $text , array_flip( get_html_translation_table( HTML_SPECIALCHARS , $quotes ) ) + array( '&#039;' => "'" ) ) ;
}

protected function _render_comments( $mydirname , $forum_id , $params , &$smarty )
{

	//render template
	$templatefile =  $this->xcforum_dirname.'_comment_listposts_flat.html' ;
	//render template
	$this->_mPostsRender = new XCube_RenderTarget();
	$this->_mPostsRender->setTemplateName($templatefile);
	$this->_mPostsRender->setAttribute('legacy_buffertype',XCUBE_RENDER_TARGET_TYPE_MAIN);
	$this->_mPostsRender->setAttribute('dirname', $this->xcforum_dirname);
	$this->_mPostsRender->setAttribute('objects', $this->_mObject);
	//$render->setAttribute('cloud', $cloud);
	//$render->setAttribute('sizeArr', $sizeArr);
	XCube_Root::getSingleton()->getRenderSystem('Legacy_RenderSystem')->render($this->_mPostsRender);

	echo $this->_mPostsRender->getResult();


/*
	$tpl->assign(
		array(
			'mydirname' => $this->mydirname ,
			'mod_url' => XOOPS_URL.'/modules/'.$this->mydirname ,
			'mod_imageurl' => XOOPS_URL.'/modules/'.$this->mydirname.'/'.$xoopsModuleConfig['images_dir'] ,
			'mod_config' => $xoopsModuleConfig ,
			'uid' => $uid ,
			'uname' => $poster_uname4disp ,
			'subject_raw' => $params['subject_raw'] ,
			'postorder' => $postorder ,
			'icon_meanings' => $d3forum_icon_meanings ,
			'category' => $category4assign ,
			'forum' => $forum4assign ,
			'topics' => $topics ,
			'topic_hits' => intval( @$topic_hits ) ,
			'post_hits' => intval( @$post_hits ) ,
			'posts' => $posts ,
			'odr_options' => @$odr_options ,
			'solved_options' => @$solved_options ,
//			'query' => $query4assign ,
			'pagenav' => $pagenav ,	//naao
			'pos' => $pos ,		//naao
			'external_link_id' => $external_link_id ,
			'page' => 'listtopics' ,
			'plugin_params' => $params ,
			'xoops_pagetitle' => $forum4assign['title'] ,
			'antispam' => $antispam4assign ,
		)
	) ;
*/

}

}
?>