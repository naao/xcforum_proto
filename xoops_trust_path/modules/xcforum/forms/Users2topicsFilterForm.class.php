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

define('XCFORUM_USERS2TOPICS_SORT_KEY_UID', 1);
define('XCFORUM_USERS2TOPICS_SORT_KEY_TOPIC_ID', 2);
define('XCFORUM_USERS2TOPICS_SORT_KEY_U2T_TIME', 3);
define('XCFORUM_USERS2TOPICS_SORT_KEY_U2T_MARKED', 4);
define('XCFORUM_USERS2TOPICS_SORT_KEY_U2T_RSV', 5);
define('XCFORUM_USERS2TOPICS_SORT_KEY_POSTTIME', 6);

define('XCFORUM_USERS2TOPICS_SORT_KEY_DEFAULT', XCFORUM_USERS2TOPICS_SORT_KEY_UID);

/**
 * Xcforum_Users2topicsFilterForm
**/
class Xcforum_Users2topicsFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_USERS2TOPICS_SORT_KEY_UID => 'uid',
 	   XCFORUM_USERS2TOPICS_SORT_KEY_TOPIC_ID => 'topic_id',
 	   XCFORUM_USERS2TOPICS_SORT_KEY_U2T_TIME => 'u2t_time',
 	   XCFORUM_USERS2TOPICS_SORT_KEY_U2T_MARKED => 'u2t_marked',
 	   XCFORUM_USERS2TOPICS_SORT_KEY_U2T_RSV => 'u2t_rsv',
 	   XCFORUM_USERS2TOPICS_SORT_KEY_POSTTIME => 'posttime',

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
        return XCFORUM_USERS2TOPICS_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('topic_id')) !== null) {
			$this->mNavi->addExtra('topic_id', $value);
			$this->_mCriteria->add(new Criteria('topic_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('u2t_time')) !== null) {
			$this->mNavi->addExtra('u2t_time', $value);
			$this->_mCriteria->add(new Criteria('u2t_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('u2t_marked')) !== null) {
			$this->mNavi->addExtra('u2t_marked', $value);
			$this->_mCriteria->add(new Criteria('u2t_marked', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('u2t_rsv')) !== null) {
			$this->mNavi->addExtra('u2t_rsv', $value);
			$this->_mCriteria->add(new Criteria('u2t_rsv', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
