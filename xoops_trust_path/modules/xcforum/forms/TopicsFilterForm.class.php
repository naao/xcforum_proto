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

define('XCFORUM_TOPICS_SORT_KEY_TOPIC_ID', 1);
define('XCFORUM_TOPICS_SORT_KEY_FORUM_ID', 2);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_EXTERNAL_LINK_ID', 3);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_TITLE', 4);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_FIRST_UID', 5);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_FIRST_POST_ID', 6);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_FIRST_POST_TIME', 7);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_UID', 8);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_POST_ID', 9);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_POST_TIME', 10);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_VIEWS', 11);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_POSTS_COUNT', 12);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_LOCKED', 13);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_STICKY', 14);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_SOLVED', 15);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_INVISIBLE', 16);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_VOTES_SUM', 17);
define('XCFORUM_TOPICS_SORT_KEY_TOPIC_VOTES_COUNT', 18);
define('XCFORUM_TOPICS_SORT_KEY_STATUS', 19);
define('XCFORUM_TOPICS_SORT_KEY_POSTTIME', 20);

// change default
//define('XCFORUM_TOPICS_SORT_KEY_DEFAULT', XCFORUM_TOPICS_SORT_KEY_TOPIC_ID);
define('XCFORUM_TOPICS_SORT_KEY_DEFAULT', - XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_POST_TIME);

/**
 * Xcforum_TopicsFilterForm
**/
class Xcforum_TopicsFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_ID => 'topic_id',
	   //XCFORUM_TOPICS_SORT_KEY_FORUM_ID => 'forum_id',
 	   XCFORUM_TOPICS_SORT_KEY_FORUM_ID => 't.forum_id',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_EXTERNAL_LINK_ID => 'topic_external_link_id',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_TITLE => 'topic_title',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_FIRST_UID => 'topic_first_uid',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_FIRST_POST_ID => 'topic_first_post_id',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_FIRST_POST_TIME => 'topic_first_post_time',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_UID => 'topic_last_uid',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_POST_ID => 'topic_last_post_id',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_LAST_POST_TIME => 'topic_last_post_time',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_VIEWS => 'topic_views',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_POSTS_COUNT => 'topic_posts_count',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_LOCKED => 'topic_locked',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_STICKY => 'topic_sticky',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_SOLVED => 'topic_solved',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_INVISIBLE => 'topic_invisible',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_VOTES_SUM => 'topic_votes_sum',
 	   XCFORUM_TOPICS_SORT_KEY_TOPIC_VOTES_COUNT => 'topic_votes_count',
 	   XCFORUM_TOPICS_SORT_KEY_STATUS => 'status',
 	   XCFORUM_TOPICS_SORT_KEY_POSTTIME => 'posttime',

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
        return XCFORUM_TOPICS_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('topic_id')) !== null) {
			$this->mNavi->addExtra('topic_id', $value);
			$this->_mCriteria->add(new Criteria('topic_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('forum_id')) !== null) {
			$this->mNavi->addExtra('forum_id', $value);
			$this->_mCriteria->add(new Criteria('forum_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_external_link_id')) !== null) {
			$this->mNavi->addExtra('topic_external_link_id', $value);
			$this->_mCriteria->add(new Criteria('topic_external_link_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_title')) !== null) {
			$this->mNavi->addExtra('topic_title', $value);
			$this->_mCriteria->add(new Criteria('topic_title', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_first_uid')) !== null) {
			$this->mNavi->addExtra('topic_first_uid', $value);
			$this->_mCriteria->add(new Criteria('topic_first_uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_first_post_id')) !== null) {
			$this->mNavi->addExtra('topic_first_post_id', $value);
			$this->_mCriteria->add(new Criteria('topic_first_post_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_first_post_time')) !== null) {
			$this->mNavi->addExtra('topic_first_post_time', $value);
			$this->_mCriteria->add(new Criteria('topic_first_post_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_last_uid')) !== null) {
			$this->mNavi->addExtra('topic_last_uid', $value);
			$this->_mCriteria->add(new Criteria('topic_last_uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_last_post_id')) !== null) {
			$this->mNavi->addExtra('topic_last_post_id', $value);
			$this->_mCriteria->add(new Criteria('topic_last_post_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_last_post_time')) !== null) {
			$this->mNavi->addExtra('topic_last_post_time', $value);
			$this->_mCriteria->add(new Criteria('topic_last_post_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_views')) !== null) {
			$this->mNavi->addExtra('topic_views', $value);
			$this->_mCriteria->add(new Criteria('topic_views', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_posts_count')) !== null) {
			$this->mNavi->addExtra('topic_posts_count', $value);
			$this->_mCriteria->add(new Criteria('topic_posts_count', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_locked')) !== null) {
			$this->mNavi->addExtra('topic_locked', $value);
			$this->_mCriteria->add(new Criteria('topic_locked', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_sticky')) !== null) {
			$this->mNavi->addExtra('topic_sticky', $value);
			$this->_mCriteria->add(new Criteria('topic_sticky', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_solved')) !== null) {
			$this->mNavi->addExtra('topic_solved', $value);
			$this->_mCriteria->add(new Criteria('topic_solved', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_invisible')) !== null) {
			$this->mNavi->addExtra('topic_invisible', $value);
			$this->_mCriteria->add(new Criteria('topic_invisible', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_votes_sum')) !== null) {
			$this->mNavi->addExtra('topic_votes_sum', $value);
			$this->_mCriteria->add(new Criteria('topic_votes_sum', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_votes_count')) !== null) {
			$this->mNavi->addExtra('topic_votes_count', $value);
			$this->_mCriteria->add(new Criteria('topic_votes_count', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('status')) !== null) {
			$this->mNavi->addExtra('status', $value);
			$this->_mCriteria->add(new Criteria('status', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

	    // TXT
	    $myts =& Xcforum_Utils::getMytextSanitizer();
	    $q_txt = $root->mContext->mRequest->getRequest('txt');
	    if( ! empty( $q_txt ) ) {
		    $txt = $myts->stripSlashesGPC( $q_txt ) ;
		    //$query4assign['txt'] = htmlspecialchars( $txt , ENT_QUOTES , _CHARSET ) ;
		    $txt4sql = addslashes( $txt ) ;
		    //$whr_txt = "fp.subject LIKE '%$txt4sql%' OR fp.post_text LIKE '%$txt4sql%'" ;
		    $this->_mCriteria->add(new criteria('topic_title', $txt4sql, 'LIKE'));
	    } else {
		    //$query4assign['txt'] = '' ;
		    //$whr_txt = '1' ;
	    }

        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }

	/**
	 * getTotalItems
	 *
	 * @param   int  &$total
	 *
	 * @return  void
	 **/
	public function getTotalItems(/*** int ***/ &$total)
	{
		$total = $this->_mHandler->getCount($this->getCriteria());
		//$total = 20; // for test

	}


}

?>
