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

require_once XCFORUM_TRUST_PATH . '/class/AbstractFilterForm.class.php';

define('XCFORUM_FORUMS_SORT_KEY_FORUM_ID', 1);
define('XCFORUM_FORUMS_SORT_KEY_CATEGORY_ID', 2);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_EXTERNAL_LINK_FORMAT', 3);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_TITLE', 4);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_DESC', 5);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_TOPICS_COUNT', 6);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_POSTS_COUNT', 7);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_LAST_POST_ID', 8);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_LAST_POST_TIME', 9);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_WEIGHT', 10);
define('XCFORUM_FORUMS_SORT_KEY_FORUM_OPTIONS', 11);
define('XCFORUM_FORUMS_SORT_KEY_STATUS', 12);
define('XCFORUM_FORUMS_SORT_KEY_POSTTIME', 13);

define('XCFORUM_FORUMS_SORT_KEY_DEFAULT', XCFORUM_FORUMS_SORT_KEY_FORUM_ID);

/**
 * Xcforum_ForumsFilterForm
**/
class Xcforum_ForumsFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_ID => 'forum_id',
 	   XCFORUM_FORUMS_SORT_KEY_CATEGORY_ID => 'category_id',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_EXTERNAL_LINK_FORMAT => 'forum_external_link_format',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_TITLE => 'forum_title',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_DESC => 'forum_desc',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_TOPICS_COUNT => 'forum_topics_count',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_POSTS_COUNT => 'forum_posts_count',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_LAST_POST_ID => 'forum_last_post_id',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_LAST_POST_TIME => 'forum_last_post_time',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_WEIGHT => 'forum_weight',
 	   XCFORUM_FORUMS_SORT_KEY_FORUM_OPTIONS => 'forum_options',
 	   XCFORUM_FORUMS_SORT_KEY_STATUS => 'status',
 	   XCFORUM_FORUMS_SORT_KEY_POSTTIME => 'posttime',

    );

    /**
     * getDefaultSortKey
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function getDefaultSortKey()
    {
        return XCFORUM_FORUMS_SORT_KEY_DEFAULT;
    }

    /**
     * fetch
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function fetch()
    {
        parent::fetch();
    
        $root =& XCube_Root::getSingleton();
    
		if (($value = $root->mContext->mRequest->getRequest('forum_id')) !== null) {
			$this->mNavi->addExtra('forum_id', $value);
			$this->_mCriteria->add(new Criteria('forum_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('category_id')) !== null) {
			$this->mNavi->addExtra('category_id', $value);
			$this->_mCriteria->add(new Criteria('category_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_external_link_format')) !== null) {
			$this->mNavi->addExtra('forum_external_link_format', $value);
			$this->_mCriteria->add(new Criteria('forum_external_link_format', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_title')) !== null) {
			$this->mNavi->addExtra('forum_title', $value);
			$this->_mCriteria->add(new Criteria('forum_title', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_desc')) !== null) {
			$this->mNavi->addExtra('forum_desc', $value);
			$this->_mCriteria->add(new Criteria('forum_desc', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_topics_count')) !== null) {
			$this->mNavi->addExtra('forum_topics_count', $value);
			$this->_mCriteria->add(new Criteria('forum_topics_count', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_posts_count')) !== null) {
			$this->mNavi->addExtra('forum_posts_count', $value);
			$this->_mCriteria->add(new Criteria('forum_posts_count', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_last_post_id')) !== null) {
			$this->mNavi->addExtra('forum_last_post_id', $value);
			$this->_mCriteria->add(new Criteria('forum_last_post_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_last_post_time')) !== null) {
			$this->mNavi->addExtra('forum_last_post_time', $value);
			$this->_mCriteria->add(new Criteria('forum_last_post_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_weight')) !== null) {
			$this->mNavi->addExtra('forum_weight', $value);
			$this->_mCriteria->add(new Criteria('forum_weight', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_options')) !== null) {
			$this->mNavi->addExtra('forum_options', $value);
			$this->_mCriteria->add(new Criteria('forum_options', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('status')) !== null) {
			$this->mNavi->addExtra('status', $value);
			$this->_mCriteria->add(new Criteria('status', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
