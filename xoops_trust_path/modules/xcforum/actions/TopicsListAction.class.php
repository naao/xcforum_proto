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

/**
 * Xcforum_TopicsListAction
**/
class Xcforum_TopicsListAction extends Xcforum_AbstractListAction
{
	const DATANAME = 'topics';

	private /*** object ***/ $mForumObj = NULL;

	private /*** Xcforum_ForumaccessHandler **/ $mfAccHandler ;
	private /*** array **/ $mForumAcc ;

	//public /*** string ***/ $mPrimary = 'topic_id';

	var $mPostsObj;

	private /*** boolean ***/ $can_post = false;
	private /*** boolean ***/ $isadminormod = false;

	private /*** int ***/ $topic_hits ;
	private /*** array ***/ $query4assign ;
	private /*** array ***/ $solved_options ;

	/**
	 * prepare
	 *
	 * @param	void
	 *
	 * @return	bool
	 **/
	public function prepare()
	{
		$this->forum_id = (int)$this->mRoot->mContext->mRequest->getRequest('forum_id');

		parent::prepare();

		//adump($join);
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
		$this->mfAccHandler =& $this->mAsset->getObject('handler', 'Forumaccess',false);
		$this->mForumAcc = $this->mfAccHandler->get_forum_all_permissions();

		if ( $this->forum_id > 0 ){
			if ( isset($this->mForumAcc) && count($this->mForumAcc) >0 ){
				if ( in_array( $this->forum_id, $this->mForumAcc['can_read']) ){
					return true;
				}
			}
		} else {
			// allow to show agrigated list, but this case must be queried in where crause
			return true;
		}
		return false;
		//adump($this->mForumAcc); die();
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
		$handler = Legacy_Utils::getModuleHandler('forums', $this->mAsset->mDirname);
		if ( isset($this->forum_id) && $this->forum_id > 0 ){
			$this->mForumObj = $handler->get($this->forum_id);
		} else {
			$this->mForumObj = $handler->create();
		}

		// set moderators
		$handler->setModerateGroups( $this->mForumObj, $this->forum_id );
		$handler->setModerateUsers( $this->mForumObj, $this->forum_id );

		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch();

		$handler =& $this->_getHandler();
		$mCriteria = $this->mFilter->getCriteria();
		$mCriteria->add(new criteria('forum_id', $this->mForumAcc['can_read'], 'IN'));

		$db = $this->mRoot->mController->mDB;
		$dirname = $this->mAsset->mDirname;
		$uid = $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? $this->mRoot->mContext->mXoopsUser->get('uid') : 0 ;

		// forum_id
		if ( $this->forum_id > 0 ){
			$whr_forum = 't.forum_id=' . $this->forum_id;
			$isadminormod = $this->mod_isadmin ? true : in_array( $this->forum_id, $this->mForumAcc['moderate']);
			$fields_forum = "";
			$join_forum = "";
			$initial_mode = 1;
		} else {
			$whr_forum = 't.forum_id IN (' . implode(',', $this->mForumAcc['can_read']) . ') ';
			$isadminormod = $this->mod_isadmin ? true : false ;
			$fields_forum = ", f.forum_id, f.forum_title, f.forum_external_link_format ";
			$join_forum = " LEFT JOIN ".$db->prefix($dirname."_forums")." f ON f.forum_id=t.forum_id ";
			$initial_mode = 2;
		}


		// INVISIBLE
		$whr_invisible = $isadminormod ? '1' : '! t.topic_invisible' ;

		// SOLVED
		$this->solved_options = array(
			0 => '----' ,
			1 => _MD_XCFORUM_OPT_SOLVEDYES ,
			2 => _MD_XCFORUM_OPT_SOLVEDNO ,
		) ;

		$solved_sqls = array(
			0 => '1' ,
			1 => 't.topic_solved=1' ,
			2 => 't.topic_solved=0' ,
		) ;
		if( empty( $this->mod_config['use_solved' ] ) ) {
			// disable "solved function"
			$query4assign['solved'] = 0 ;
			$whr_solved = $solved_sqls[0] ;
		} else {
			$q_solved = (int)$this->mRoot->mContext->mRequest->getRequest('solved');
			if( ! empty( $this->solved_options[$q_solved] ) ) {
				$query4assign['solved'] = $q_solved ;
				$whr_solved = $solved_sqls[ $query4assign['solved'] ] ;
			} else {
				$query4assign['solved'] = 0 ;
				$whr_solved = $solved_sqls[0] ;
			}
		}
		$mCriteria->add(new criteria('topic_solved', $whr_solved));

		// TXT
		$myts =& Xcforum_Utils::getMytextSanitizer();
		$q_txt = $this->mRoot->mContext->mRequest->getRequest('txt');
		//adump($this->mRoot->mContext->mRequest->getRequest('txt'));
		//$q_txt = $_GET['txt'];
		if( ! empty( $q_txt ) ) {
			$txt = $myts->stripSlashesGPC( $q_txt ) ;
			$query4assign['txt'] = htmlspecialchars( $txt , ENT_QUOTES , _CHARSET ) ;
			$txt4sql = addslashes( $txt ) ;
			$whr_txt = "fp.subject LIKE '%$txt4sql%' OR fp.post_text LIKE '%$txt4sql%'" ;
			$mCriteria->add(new criteria('topic_title', $txt4sql, 'LIKE'));
		} else {
			$query4assign['txt'] = '' ;
			$whr_txt = '1' ;
		}
		$this->query4assign = $query4assign;
		$limit = $this->mod_config['topics_per_page'];

		// get counts of total topics
		$sql_base = "FROM "
			.$db->prefix($dirname."_topics")." t LEFT JOIN "
			.$db->prefix($dirname."_users2topics")." u2t ON t.topic_id=u2t.topic_id AND u2t.uid=$uid LEFT JOIN "
			.$db->prefix($dirname."_posts")." lp ON lp.post_id=t.topic_last_post_id LEFT JOIN "
			.$db->prefix($dirname."_posts")." fp ON fp.post_id=t.topic_first_post_id
			".$join_forum."
		  WHERE ($whr_forum) AND ($whr_invisible) AND ($whr_solved) AND ($whr_txt)" ;
		//		  WHERE t.forum_id=$this->forum_id AND ($whr_invisible) AND ($whr_solved) AND ($whr_txt)
		//		  AND ($whr_external_link_id) ORDER BY $odr_query LIMIT $pos,$num" ;

		$sql = $sql_base;

		$this->topic_hits = $handler->getCount( $mCriteria, $sql );

		// get topic objects
		$sql = "SELECT t.*, lp.post_text AS lp_post_text, lp.subject AS lp_subject, lp.icon AS lp_icon,
		  lp.number_entity AS lp_number_entity, lp.special_entity AS lp_special_entity,
		  lp.guest_name AS lp_guest_name, fp.subject AS fp_subject, fp.icon AS fp_icon,
		  fp.number_entity AS fp_number_entity, fp.special_entity AS fp_special_entity,
		  fp.guest_name AS fp_guest_name, u2t.u2t_time, u2t.u2t_marked, u2t.u2t_rsv
		  ".$fields_forum.$sql_base;

		// No6 argument is for additional initializztion : 1::forum_id>0, 2::forum_id==0
		$this->mObjects =& $handler->getObjects( $mCriteria, NULL, NULL, false, $sql, $initial_mode );

		return XCFORUM_FRAME_VIEW_INDEX;
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
		// permissions
		$params = array();
		$params['uid'] = $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser') ? $this->mRoot->mContext->mXoopsUser->get('uid') : 0 ;
		$params['can_read'] = true;
		$params['can_post'] = in_array( $this->forum_id, $this->mForumAcc['post']);
		$params['can_edit'] = in_array( $this->forum_id, $this->mForumAcc['edit']);
		$params['can_delete'] = in_array( $this->forum_id, $this->mForumAcc['delete']);
		$params['can_reply'] = in_array( $this->forum_id, $this->mForumAcc['post']);
		$params['isadminormod'] = $this->mod_isadmin ? true : in_array( $this->forum_id, $this->mForumAcc['moderate']);
		$params['mod_config'] =& $this->mod_config;

		//adump($mObj);
		if ( isset($this->forum_id) && $this->forum_id > 0 ){
			if ( isset($this->mForumAcc) && count($this->mForumAcc) >0 ){
				if ( in_array( $this->forum_id, $this->mForumAcc['can_read']) ){
					$this->mForumObj->initVar('can_post', XOBJ_DTYPE_INT, $params['can_post'], false);
					$isadminormod = $this->mod_isadmin || $params['isadminormod'];
					$this->mForumObj->initVar('isadminormod', XOBJ_DTYPE_INT, $isadminormod, false);
				}
			}
		}

		foreach($this->mObjects as &$mObj){

			$mObj->initVar('topic_votes_avg', XOBJ_DTYPE_INT, (int)$mObj->get('topic_votes_sum')/((int)$mObj->get('topic_votes_count')-0.01) , false);

			$bit_new = (int)$mObj->get('topic_last_post_time') > @$mObj->get('u2t_time') ? 1 : 0 ;
			$bit_hot = (int)$mObj->get('topic_votes_count') > $this->mod_config['hot_threshold'] ? 1 : 0 ;
			$mObj->initVar('bit_new', XOBJ_DTYPE_INT, $bit_new, false);
			$mObj->initVar('bit_hot', XOBJ_DTYPE_INT, $bit_hot, false);

			$mObj->initVar('topic_votes_src', XOBJ_DTYPE_STRING, "", false);
			$mObj->initVar('topic_page_jump', XOBJ_DTYPE_STRING, "", false);

			$params['guest_name'] = $mObj->get('fp_guest_name');
			$rtn = Xcforum_Utils::getPosterData($mObj->get('topic_first_uid'), $params);
			$poster_dispname = isset($rtn['poster_dispname']) ? $rtn['poster_dispname'] : "" ;
			$poster_uname = isset($rtn['poster_uname']) ? $rtn['poster_uname'] : "" ;
			$poster_name = isset($rtn['poster_name']) ? $rtn['poster_name'] : "" ;
			$poster_gname = isset($rtn['poster_gname']) ? $rtn['poster_gname'] : "" ;
			$mObj->initVar('first_post_dispname', XOBJ_DTYPE_STRING, $poster_dispname, false);
			$mObj->initVar('first_post_uname', XOBJ_DTYPE_STRING, $poster_uname, false);
			$mObj->initVar('first_post_name', XOBJ_DTYPE_STRING, $poster_name, false);
			$mObj->initVar('first_post_gname', XOBJ_DTYPE_STRING, $poster_gname, false);

			$params['guest_name'] = $mObj->get('lp_guest_name');
			$rtn = Xcforum_Utils::getPosterData($mObj->get('topic_last_uid'), $params);
			$poster_dispname = isset($rtn['poster_dispname']) ? $rtn['poster_dispname'] : "" ;
			$poster_uname = isset($rtn['poster_uname']) ? $rtn['poster_uname'] : "" ;
			$poster_name = isset($rtn['poster_name']) ? $rtn['poster_name'] : "" ;
			$poster_gname = isset($rtn['poster_gname']) ? $rtn['poster_gname'] : "" ;
			$mObj->initVar('last_post_dispname', XOBJ_DTYPE_STRING, $poster_dispname, false);
			$mObj->initVar('last_post_uname', XOBJ_DTYPE_STRING, $poster_uname, false);
			$mObj->initVar('last_post_name', XOBJ_DTYPE_STRING, $poster_name, false);
			$mObj->initVar('last_post_gname', XOBJ_DTYPE_STRING, $poster_gname, false);

		}

		//$render->setTemplateName($this->mAsset->mDirname . '_topics_list.html');
		$render->setTemplateName($this->mAsset->mDirname . '_topic_list.html');
		$render->setAttribute('forumObj', $this->mForumObj);
		//$render->setAttribute('objects', $this->mObjects);
		//$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', self::DATANAME);
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
		//adump($this->mFilter->mNavi);

		$render->setAttribute('solved_options', $this->solved_options);
		$render->setAttribute('query', $this->query4assign);

		$q_solved = (int)$this->mRoot->mContext->mRequest->getRequest('solved');
		if ( $q_solved >0 ){
			$qstr_solved = "&amp;solved=".$q_solved ;
		}

		$q_txt = $this->mRoot->mContext->mRequest->getRequest('txt');
		if ( $q_txt ){
			$qstr_txt = "&amp;txt=".htmlspecialchars( $q_txt , ENT_QUOTES , _CHARSET ) ; ;
		}

		$qstr = ($qstr_solved || $qstr_txt) ? $qstr_solved.$qstr_txt : "";
		$render->setAttribute('qstr', $qstr);

		$render->setAttribute('topic_hits', $this->topic_hits);

		// pagenav
		$pagenav = '' ;
		//$query4nav = 'topic_id=' . $topic_id ;
		$query4nav = 'action=TopicsList';
		$query4nav .= $this->forum_id > 0 ? '&amp;forum_id='. $this->forum_id : '';
		// LIMIT
		$num = $this->mod_config['topics_per_page'] < 5 ? 5 : (int)$this->mod_config['topics_per_page'] ;
		$pos = 0 ;
		if( $this->topic_hits > $num ) {
			// POS
			$pos = $this->mRoot->mContext->mRequest->getRequest('start');
			//$pos = isset( $pos ) ? (int)$pos : (($postorder == 0) || ($postorder == 2) ? (int)(($this->topic_hits-1) / $num) * $num : 0) ;
			$pos = isset( $pos ) ? (int)$pos : 0 ;
			require_once XCFORUM_TRUST_PATH . '/class/Pagenav.class.php' ;
			$pagenav_obj = new XcforumPagenav( $this->topic_hits , $num , $pos , 'start', $query4nav ) ;
			$pagenav = $pagenav_obj->getNav() ;

			$render->setAttribute('pagenav', $pagenav);
		}

		parent::executeViewIndex($render);
	}

	/**
	 * &_getPageNavi
	 *
	 * @param	void
	 *
	 * @return	&XCube_PageNavigator
	 **/
	protected function &_getPageNavi()
	{
		$navi = new XCube_PageNavigator($this->_getBaseUrl(), XCUBE_PAGENAVI_START);
		$navi->setPerpage($this->mod_config['topics_per_page']);
		return $navi;
	}


}

?>
