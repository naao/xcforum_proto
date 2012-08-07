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

define('XCFORUM_FORUMACCESS_SORT_KEY_FORUM_ID', 1);
define('XCFORUM_FORUMACCESS_SORT_KEY_UID', 2);
define('XCFORUM_FORUMACCESS_SORT_KEY_UID', 3);
define('XCFORUM_FORUMACCESS_SORT_KEY_GROUPID', 4);
define('XCFORUM_FORUMACCESS_SORT_KEY_CAN_POST', 5);
define('XCFORUM_FORUMACCESS_SORT_KEY_CAN_EDIT', 6);
define('XCFORUM_FORUMACCESS_SORT_KEY_CAN_DELETE', 7);
define('XCFORUM_FORUMACCESS_SORT_KEY_POST_AUTO_APPROVED', 8);
define('XCFORUM_FORUMACCESS_SORT_KEY_IS_MODERATOR', 9);

define('XCFORUM_FORUMACCESS_SORT_KEY_DEFAULT', XCFORUM_FORUMACCESS_SORT_KEY_FORUM_ID);

/**
 * Xcforum_Admin_ForumaccessFilterForm
**/
class Xcforum_Admin_ForumaccessFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_FORUMACCESS_SORT_KEY_FORUM_ID => 'forum_id',
 	   XCFORUM_FORUMACCESS_SORT_KEY_UID => 'uid',
 	   XCFORUM_FORUMACCESS_SORT_KEY_UID => 'uid',
 	   XCFORUM_FORUMACCESS_SORT_KEY_GROUPID => 'groupid',
 	   XCFORUM_FORUMACCESS_SORT_KEY_CAN_POST => 'can_post',
 	   XCFORUM_FORUMACCESS_SORT_KEY_CAN_EDIT => 'can_edit',
 	   XCFORUM_FORUMACCESS_SORT_KEY_CAN_DELETE => 'can_delete',
 	   XCFORUM_FORUMACCESS_SORT_KEY_POST_AUTO_APPROVED => 'post_auto_approved',
 	   XCFORUM_FORUMACCESS_SORT_KEY_IS_MODERATOR => 'is_moderator',

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
        return XCFORUM_FORUMACCESS_SORT_KEY_DEFAULT;
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
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('groupid')) !== null) {
			$this->mNavi->addExtra('groupid', $value);
			$this->_mCriteria->add(new Criteria('groupid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('can_post')) !== null) {
			$this->mNavi->addExtra('can_post', $value);
			$this->_mCriteria->add(new Criteria('can_post', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('can_edit')) !== null) {
			$this->mNavi->addExtra('can_edit', $value);
			$this->_mCriteria->add(new Criteria('can_edit', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('can_delete')) !== null) {
			$this->mNavi->addExtra('can_delete', $value);
			$this->_mCriteria->add(new Criteria('can_delete', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('post_auto_approved')) !== null) {
			$this->mNavi->addExtra('post_auto_approved', $value);
			$this->_mCriteria->add(new Criteria('post_auto_approved', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('is_moderator')) !== null) {
			$this->mNavi->addExtra('is_moderator', $value);
			$this->_mCriteria->add(new Criteria('is_moderator', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
