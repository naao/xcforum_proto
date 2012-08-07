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

define('XCFORUM_POSTS_SORT_KEY_POST_ID', 1);
define('XCFORUM_POSTS_SORT_KEY_PID', 2);
define('XCFORUM_POSTS_SORT_KEY_TOPIC_ID', 3);
define('XCFORUM_POSTS_SORT_KEY_POST_TIME', 4);
define('XCFORUM_POSTS_SORT_KEY_MODIFIED_TIME', 5);
define('XCFORUM_POSTS_SORT_KEY_UID', 6);
define('XCFORUM_POSTS_SORT_KEY_UID_HIDDEN', 7);
define('XCFORUM_POSTS_SORT_KEY_POSTER_IP', 8);
define('XCFORUM_POSTS_SORT_KEY_MODIFIER_IP', 9);
define('XCFORUM_POSTS_SORT_KEY_SUBJECT', 10);
define('XCFORUM_POSTS_SORT_KEY_SUBJECT_WAITING', 11);
define('XCFORUM_POSTS_SORT_KEY_HTML', 12);
define('XCFORUM_POSTS_SORT_KEY_SMILEY', 13);
define('XCFORUM_POSTS_SORT_KEY_XCODE', 14);
define('XCFORUM_POSTS_SORT_KEY_BR', 15);
define('XCFORUM_POSTS_SORT_KEY_NUMBER_ENTITY', 16);
define('XCFORUM_POSTS_SORT_KEY_SPECIAL_ENTITY', 17);
define('XCFORUM_POSTS_SORT_KEY_ICON', 18);
define('XCFORUM_POSTS_SORT_KEY_ATTACHSIG', 19);
define('XCFORUM_POSTS_SORT_KEY_INVISIBLE', 20);
define('XCFORUM_POSTS_SORT_KEY_APPROVAL', 21);
define('XCFORUM_POSTS_SORT_KEY_VOTES_SUM', 22);
define('XCFORUM_POSTS_SORT_KEY_VOTES_COUNT', 23);
define('XCFORUM_POSTS_SORT_KEY_DEPTH_IN_TREE', 24);
define('XCFORUM_POSTS_SORT_KEY_ORDER_IN_TREE', 25);
define('XCFORUM_POSTS_SORT_KEY_PATH_IN_TREE', 26);
define('XCFORUM_POSTS_SORT_KEY_UNIQUE_PATH', 27);
define('XCFORUM_POSTS_SORT_KEY_GUEST_NAME', 28);
define('XCFORUM_POSTS_SORT_KEY_GUEST_EMAIL', 29);
define('XCFORUM_POSTS_SORT_KEY_GUEST_URL', 30);
define('XCFORUM_POSTS_SORT_KEY_GUEST_PASS_MD5', 31);
define('XCFORUM_POSTS_SORT_KEY_GUEST_TRIP', 32);
define('XCFORUM_POSTS_SORT_KEY_POST_TEXT', 33);
define('XCFORUM_POSTS_SORT_KEY_POST_TEXT_WAITING', 34);
define('XCFORUM_POSTS_SORT_KEY_STATUS', 35);
define('XCFORUM_POSTS_SORT_KEY_POSTTIME', 36);

define('XCFORUM_POSTS_SORT_KEY_DEFAULT', XCFORUM_POSTS_SORT_KEY_POST_ID);

/**
 * Xcforum_PostsFilterForm
**/
class Xcforum_PostsFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_POSTS_SORT_KEY_POST_ID => 'post_id',
 	   XCFORUM_POSTS_SORT_KEY_PID => 'pid',
 	   XCFORUM_POSTS_SORT_KEY_TOPIC_ID => 'topic_id',
 	   XCFORUM_POSTS_SORT_KEY_POST_TIME => 'post_time',
 	   XCFORUM_POSTS_SORT_KEY_MODIFIED_TIME => 'modified_time',
 	   XCFORUM_POSTS_SORT_KEY_UID => 'uid',
 	   XCFORUM_POSTS_SORT_KEY_UID_HIDDEN => 'uid_hidden',
 	   XCFORUM_POSTS_SORT_KEY_POSTER_IP => 'poster_ip',
 	   XCFORUM_POSTS_SORT_KEY_MODIFIER_IP => 'modifier_ip',
 	   XCFORUM_POSTS_SORT_KEY_SUBJECT => 'subject',
 	   XCFORUM_POSTS_SORT_KEY_SUBJECT_WAITING => 'subject_waiting',
 	   XCFORUM_POSTS_SORT_KEY_HTML => 'html',
 	   XCFORUM_POSTS_SORT_KEY_SMILEY => 'smiley',
 	   XCFORUM_POSTS_SORT_KEY_XCODE => 'xcode',
 	   XCFORUM_POSTS_SORT_KEY_BR => 'br',
 	   XCFORUM_POSTS_SORT_KEY_NUMBER_ENTITY => 'number_entity',
 	   XCFORUM_POSTS_SORT_KEY_SPECIAL_ENTITY => 'special_entity',
 	   XCFORUM_POSTS_SORT_KEY_ICON => 'icon',
 	   XCFORUM_POSTS_SORT_KEY_ATTACHSIG => 'attachsig',
 	   XCFORUM_POSTS_SORT_KEY_INVISIBLE => 'invisible',
 	   XCFORUM_POSTS_SORT_KEY_APPROVAL => 'approval',
 	   XCFORUM_POSTS_SORT_KEY_VOTES_SUM => 'votes_sum',
 	   XCFORUM_POSTS_SORT_KEY_VOTES_COUNT => 'votes_count',
 	   XCFORUM_POSTS_SORT_KEY_DEPTH_IN_TREE => 'depth_in_tree',
 	   XCFORUM_POSTS_SORT_KEY_ORDER_IN_TREE => 'order_in_tree',
 	   XCFORUM_POSTS_SORT_KEY_PATH_IN_TREE => 'path_in_tree',
 	   XCFORUM_POSTS_SORT_KEY_UNIQUE_PATH => 'unique_path',
 	   XCFORUM_POSTS_SORT_KEY_GUEST_NAME => 'guest_name',
 	   XCFORUM_POSTS_SORT_KEY_GUEST_EMAIL => 'guest_email',
 	   XCFORUM_POSTS_SORT_KEY_GUEST_URL => 'guest_url',
 	   XCFORUM_POSTS_SORT_KEY_GUEST_PASS_MD5 => 'guest_pass_md5',
 	   XCFORUM_POSTS_SORT_KEY_GUEST_TRIP => 'guest_trip',
 	   XCFORUM_POSTS_SORT_KEY_POST_TEXT => 'post_text',
 	   XCFORUM_POSTS_SORT_KEY_POST_TEXT_WAITING => 'post_text_waiting',
 	   XCFORUM_POSTS_SORT_KEY_STATUS => 'status',
 	   XCFORUM_POSTS_SORT_KEY_POSTTIME => 'posttime',

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
        return XCFORUM_POSTS_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('post_id')) !== null) {
			$this->mNavi->addExtra('post_id', $value);
			$this->_mCriteria->add(new Criteria('post_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('pid')) !== null) {
			$this->mNavi->addExtra('pid', $value);
			$this->_mCriteria->add(new Criteria('pid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_id')) !== null) {
			$this->mNavi->addExtra('topic_id', $value);
			$this->_mCriteria->add(new Criteria('topic_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('post_time')) !== null) {
			$this->mNavi->addExtra('post_time', $value);
			$this->_mCriteria->add(new Criteria('post_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('modified_time')) !== null) {
			$this->mNavi->addExtra('modified_time', $value);
			$this->_mCriteria->add(new Criteria('modified_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('uid_hidden')) !== null) {
			$this->mNavi->addExtra('uid_hidden', $value);
			$this->_mCriteria->add(new Criteria('uid_hidden', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('poster_ip')) !== null) {
			$this->mNavi->addExtra('poster_ip', $value);
			$this->_mCriteria->add(new Criteria('poster_ip', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('modifier_ip')) !== null) {
			$this->mNavi->addExtra('modifier_ip', $value);
			$this->_mCriteria->add(new Criteria('modifier_ip', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('subject')) !== null) {
			$this->mNavi->addExtra('subject', $value);
			$this->_mCriteria->add(new Criteria('subject', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('subject_waiting')) !== null) {
			$this->mNavi->addExtra('subject_waiting', $value);
			$this->_mCriteria->add(new Criteria('subject_waiting', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('html')) !== null) {
			$this->mNavi->addExtra('html', $value);
			$this->_mCriteria->add(new Criteria('html', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('smiley')) !== null) {
			$this->mNavi->addExtra('smiley', $value);
			$this->_mCriteria->add(new Criteria('smiley', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('xcode')) !== null) {
			$this->mNavi->addExtra('xcode', $value);
			$this->_mCriteria->add(new Criteria('xcode', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('br')) !== null) {
			$this->mNavi->addExtra('br', $value);
			$this->_mCriteria->add(new Criteria('br', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('number_entity')) !== null) {
			$this->mNavi->addExtra('number_entity', $value);
			$this->_mCriteria->add(new Criteria('number_entity', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('special_entity')) !== null) {
			$this->mNavi->addExtra('special_entity', $value);
			$this->_mCriteria->add(new Criteria('special_entity', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('icon')) !== null) {
			$this->mNavi->addExtra('icon', $value);
			$this->_mCriteria->add(new Criteria('icon', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('attachsig')) !== null) {
			$this->mNavi->addExtra('attachsig', $value);
			$this->_mCriteria->add(new Criteria('attachsig', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('invisible')) !== null) {
			$this->mNavi->addExtra('invisible', $value);
			$this->_mCriteria->add(new Criteria('invisible', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('approval')) !== null) {
			$this->mNavi->addExtra('approval', $value);
			$this->_mCriteria->add(new Criteria('approval', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('votes_sum')) !== null) {
			$this->mNavi->addExtra('votes_sum', $value);
			$this->_mCriteria->add(new Criteria('votes_sum', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('votes_count')) !== null) {
			$this->mNavi->addExtra('votes_count', $value);
			$this->_mCriteria->add(new Criteria('votes_count', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('depth_in_tree')) !== null) {
			$this->mNavi->addExtra('depth_in_tree', $value);
			$this->_mCriteria->add(new Criteria('depth_in_tree', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('order_in_tree')) !== null) {
			$this->mNavi->addExtra('order_in_tree', $value);
			$this->_mCriteria->add(new Criteria('order_in_tree', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('path_in_tree')) !== null) {
			$this->mNavi->addExtra('path_in_tree', $value);
			$this->_mCriteria->add(new Criteria('path_in_tree', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('unique_path')) !== null) {
			$this->mNavi->addExtra('unique_path', $value);
			$this->_mCriteria->add(new Criteria('unique_path', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('guest_name')) !== null) {
			$this->mNavi->addExtra('guest_name', $value);
			$this->_mCriteria->add(new Criteria('guest_name', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('guest_email')) !== null) {
			$this->mNavi->addExtra('guest_email', $value);
			$this->_mCriteria->add(new Criteria('guest_email', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('guest_url')) !== null) {
			$this->mNavi->addExtra('guest_url', $value);
			$this->_mCriteria->add(new Criteria('guest_url', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('guest_pass_md5')) !== null) {
			$this->mNavi->addExtra('guest_pass_md5', $value);
			$this->_mCriteria->add(new Criteria('guest_pass_md5', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('guest_trip')) !== null) {
			$this->mNavi->addExtra('guest_trip', $value);
			$this->_mCriteria->add(new Criteria('guest_trip', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('post_text')) !== null) {
			$this->mNavi->addExtra('post_text', $value);
			$this->_mCriteria->add(new Criteria('post_text', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('post_text_waiting')) !== null) {
			$this->mNavi->addExtra('post_text_waiting', $value);
			$this->_mCriteria->add(new Criteria('post_text_waiting', $value));
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
