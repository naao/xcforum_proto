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

define('XCFORUM_POSTVOTES_SORT_KEY_VOTE_ID', 1);
define('XCFORUM_POSTVOTES_SORT_KEY_POST_ID', 2);
define('XCFORUM_POSTVOTES_SORT_KEY_UID', 3);
define('XCFORUM_POSTVOTES_SORT_KEY_VOTE_POINT', 4);
define('XCFORUM_POSTVOTES_SORT_KEY_VOTE_TIME', 5);
define('XCFORUM_POSTVOTES_SORT_KEY_VOTE_IP', 6);
define('XCFORUM_POSTVOTES_SORT_KEY_POSTTIME', 7);

define('XCFORUM_POSTVOTES_SORT_KEY_DEFAULT', XCFORUM_POSTVOTES_SORT_KEY_VOTE_ID);

/**
 * Xcforum_PostvotesFilterForm
**/
class Xcforum_PostvotesFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_POSTVOTES_SORT_KEY_VOTE_ID => 'vote_id',
 	   XCFORUM_POSTVOTES_SORT_KEY_POST_ID => 'post_id',
 	   XCFORUM_POSTVOTES_SORT_KEY_UID => 'uid',
 	   XCFORUM_POSTVOTES_SORT_KEY_VOTE_POINT => 'vote_point',
 	   XCFORUM_POSTVOTES_SORT_KEY_VOTE_TIME => 'vote_time',
 	   XCFORUM_POSTVOTES_SORT_KEY_VOTE_IP => 'vote_ip',
 	   XCFORUM_POSTVOTES_SORT_KEY_POSTTIME => 'posttime',

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
        return XCFORUM_POSTVOTES_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('vote_id')) !== null) {
			$this->mNavi->addExtra('vote_id', $value);
			$this->_mCriteria->add(new Criteria('vote_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('post_id')) !== null) {
			$this->mNavi->addExtra('post_id', $value);
			$this->_mCriteria->add(new Criteria('post_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('vote_point')) !== null) {
			$this->mNavi->addExtra('vote_point', $value);
			$this->_mCriteria->add(new Criteria('vote_point', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('vote_time')) !== null) {
			$this->mNavi->addExtra('vote_time', $value);
			$this->_mCriteria->add(new Criteria('vote_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('vote_ip')) !== null) {
			$this->mNavi->addExtra('vote_ip', $value);
			$this->_mCriteria->add(new Criteria('vote_ip', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
