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

define('XCFORUM_POSTHISTORIES_SORT_KEY_HISTORY_ID', 1);
define('XCFORUM_POSTHISTORIES_SORT_KEY_POST_ID', 2);
define('XCFORUM_POSTHISTORIES_SORT_KEY_HISTORY_TIME', 3);
define('XCFORUM_POSTHISTORIES_SORT_KEY_DATA', 4);
define('XCFORUM_POSTHISTORIES_SORT_KEY_POSTTIME', 5);

define('XCFORUM_POSTHISTORIES_SORT_KEY_DEFAULT', XCFORUM_POSTHISTORIES_SORT_KEY_HISTORY_ID);

/**
 * Xcforum_PosthistoriesFilterForm
**/
class Xcforum_PosthistoriesFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_POSTHISTORIES_SORT_KEY_HISTORY_ID => 'history_id',
 	   XCFORUM_POSTHISTORIES_SORT_KEY_POST_ID => 'post_id',
 	   XCFORUM_POSTHISTORIES_SORT_KEY_HISTORY_TIME => 'history_time',
 	   XCFORUM_POSTHISTORIES_SORT_KEY_DATA => 'data',
 	   XCFORUM_POSTHISTORIES_SORT_KEY_POSTTIME => 'posttime',

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
        return XCFORUM_POSTHISTORIES_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('history_id')) !== null) {
			$this->mNavi->addExtra('history_id', $value);
			$this->_mCriteria->add(new Criteria('history_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('post_id')) !== null) {
			$this->mNavi->addExtra('post_id', $value);
			$this->_mCriteria->add(new Criteria('post_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('history_time')) !== null) {
			$this->mNavi->addExtra('history_time', $value);
			$this->_mCriteria->add(new Criteria('history_time', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('data')) !== null) {
			$this->mNavi->addExtra('data', $value);
			$this->_mCriteria->add(new Criteria('data', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
