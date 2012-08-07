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
 * Xcforum_TopicsObject
**/
class Xcforum_TopicsObject extends Xcforum_CriteriaObject
{
    const PRIMARY = 'topic_id';
    const DATANAME = 'topics';
    public $mChildList = array('posts','users2topics');
    public $mParentList = array('forums');

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
        $this->initVar('topic_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('forum_id', XOBJ_DTYPE_INT, '', true);
        $this->initVar('topic_external_link_id', XOBJ_DTYPE_STRING, '', false, 255);
        $this->initVar('topic_title', XOBJ_DTYPE_STRING, '', true, 255);
        $this->initVar('topic_first_uid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_first_post_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_first_post_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_last_uid', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_last_post_id', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_last_post_time', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_views', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_posts_count', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_locked', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_sticky', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_solved', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_invisible', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_votes_sum', XOBJ_DTYPE_INT, '', false);
        $this->initVar('topic_votes_count', XOBJ_DTYPE_INT, '', false);
        $this->initVar('status', XOBJ_DTYPE_INT, '', false);
        $this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);

		/*
	     *		$sql = "SELECT t.*, lp.post_text AS lp_post_text, lp.subject AS lp_subject, lp.icon AS lp_icon,
		  lp.number_entity AS lp_number_entity, lp.special_entity AS lp_special_entity,
		  lp.guest_name AS lp_guest_name, fp.subject AS fp_subject, fp.icon AS fp_icon,
		  fp.number_entity AS fp_number_entity, fp.special_entity AS fp_special_entity,
		  fp.guest_name AS fp_guest_name, u2t.u2t_time, u2t.u2t_marked, u2t.u2t_rsv FROM "

	     */

   }

	/**
	 * initAdditionalFields
	 *
	 * @param int $init_mode
	 **/
	public function initAdditionalFields( /*** int ***/ $init_mode = 0 )
	{
			// additional items
			$this->initVar('lp_post_text', XOBJ_DTYPE_TEXT, '', false);
			$this->initVar('lp_subject', XOBJ_DTYPE_STRING, '', false, 255);
			$this->initVar('lp_icon', XOBJ_DTYPE_INT, '', false);
			$this->initVar('lp_number_entity', XOBJ_DTYPE_INT, '', false);
			$this->initVar('lp_special_entity', XOBJ_DTYPE_INT, '', false);
			$this->initVar('lp_guest_name', XOBJ_DTYPE_STRING, '', false, 25);

			$this->initVar('fp_post_text', XOBJ_DTYPE_TEXT, '', false);
			$this->initVar('fp_subject', XOBJ_DTYPE_STRING, '', false, 255);
			$this->initVar('fp_icon', XOBJ_DTYPE_INT, '', false);
			$this->initVar('fp_number_entity', XOBJ_DTYPE_INT, '', false);
			$this->initVar('fp_special_entity', XOBJ_DTYPE_INT, '', false);
			$this->initVar('fp_guest_name', XOBJ_DTYPE_STRING, '', false, 25);

			$this->initVar('u2t_time', XOBJ_DTYPE_INT, '', false);
			$this->initVar('u2t_marked', XOBJ_DTYPE_INT, '', false);
			$this->initVar('u2t_rsv', XOBJ_DTYPE_INT, '', false);

		if ($init_mode >= 2){
			$this->initVar('forum_title', XOBJ_DTYPE_STRING, '', 255);
		}

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

}

/**
 * Xcforum_TopicsHandler
**/
class Xcforum_TopicsHandler extends Xcforum_CriteriaHandler
{
    public /*** string ***/ $mTable = '{dirname}_topics';
    public /*** string ***/ $mPrimary = 'topic_id';
    public /*** string ***/ $mClass = 'Xcforum_TopicsObject';

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
    }

	public function countTopicByCategory(/*** int ***/ $catId)
	{
		$cri = new Criteria('category_id', $catId);
		return $this->getCount($cri);
	}

	/**
	 * updateTopicFromPost
	 *
	 * @param	Xcforum_PostObject  $obj
	 *
	 * @param	array  $attributes
	 *
	 * @return	Xcforum_TopicsObject
	 **/
	public function updateTopicFromPost(/*** Xcforum_PostsObject ***/ $obj, /*** array ***/ $attributes)
	{
		// called from Posts.class.php

		$_topic_id = ($obj->get('topic_id'));
			//adump($_topic_id);
		if (!empty($_topic_id) && (int)$_topic_id >0){
			$topic = $this->get($_topic_id);
		} else {
			// add new topic
			$topic = new Xcforum_TopicsObject;
			//$topic->set('forum_id', $forum_id);
			$topic->set('topic_title', $obj->get('subject'));
			$topic->set('topic_first_post_id', $obj->get('post_id'));
			$topic->set('topic_first_post_time', $obj->get('post_time'));
		}

		foreach($attributes as $key => $value){
			$topic->set($key, $value);
		}
			//adump($topic);
			// update post information
		//$topic->set('topic_last_post_id', $obj->get('post_id'));
		//$topic->set('topic_last_post_time', $obj->get('post_time'));
		$topic->set('topic_posts_count', (int)$topic->get('topic_posts_count') +1 );
		if($topic instanceof Xcforum_TopicsObject){
			if($this->insert($topic, true) === true){
				$inserted_id = (int)$this->db->getInsertId();
				$tp_id = $inserted_id >0 ? $inserted_id : $_topic_id;
				$topic->set('topic_id', $tp_id );
				return $topic;
			}
		}
		return NULL;
	}

	/**
	 * initAdditionalFields
	 *
	 * @param Xcforum_PostsObject $obj
	 * @param int $init_mode
	 **/
	public function initAdditionalFields( /*** Xcforum_PostsObject ***/ $obj, /*** int ***/ $init_mode )
	{
		if ( $obj instanceof Xcforum_TopicsObject)
		{
			$obj->initAdditionalFields( $init_mode );
		}
	}

	/**
	 * getTopic
	 *
	 * @param	int  $topic_id
	 *
	 * @return	Xcforum_TopicsList
	 **/
//	public function getTopic(/*** int ***/ $topic_id)
//	{
//		if (isset($topic_id) && $topic_id > 0) {
//			$topic_obj = $this->get($topic_id);
//			return $topic_obj;
//		}
//	}

	/**************************************
	 *  xcforum comment integration functions
	**************************************/

	/**
	 * getTopicsObj
	 *
	 * @param	array  $attributes
	 *
	 * @return	Xcforum_TopicsList
	 **/
	public function getTopicsObj(/*** array ***/ $attributes, /*** array ***/ $orders=null ,
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

}

?>
