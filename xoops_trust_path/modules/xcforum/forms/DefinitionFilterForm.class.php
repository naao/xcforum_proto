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

define('XCFORUM_DEFINITION_SORT_KEY_DEFINITION_ID', 1);
define('XCFORUM_DEFINITION_SORT_KEY_FIELD_NAME', 2);
define('XCFORUM_DEFINITION_SORT_KEY_LABEL', 3);
define('XCFORUM_DEFINITION_SORT_KEY_FIELD_TYPE', 4);
define('XCFORUM_DEFINITION_SORT_KEY_VALIDATION', 5);
define('XCFORUM_DEFINITION_SORT_KEY_REQUIRED', 6);
define('XCFORUM_DEFINITION_SORT_KEY_WEIGHT', 7);
define('XCFORUM_DEFINITION_SORT_KEY_SHOW_LIST', 8);
define('XCFORUM_DEFINITION_SORT_KEY_SEARCH_FLAG', 9);
define('XCFORUM_DEFINITION_SORT_KEY_DESCRIPTION', 10);
define('XCFORUM_DEFINITION_SORT_KEY_OPTIONS', 11);
define('XCFORUM_DEFINITION_SORT_KEY_POSTTIME', 12);

define('XCFORUM_DEFINITION_SORT_KEY_DEFAULT', XCFORUM_DEFINITION_SORT_KEY_DEFINITION_ID);

/**
 * Xcforum_DefinitionFilterForm
**/
class Xcforum_DefinitionFilterForm extends Xcforum_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   XCFORUM_DEFINITION_SORT_KEY_DEFINITION_ID => 'definition_id',
 	   XCFORUM_DEFINITION_SORT_KEY_FIELD_NAME => 'field_name',
 	   XCFORUM_DEFINITION_SORT_KEY_LABEL => 'label',
 	   XCFORUM_DEFINITION_SORT_KEY_FIELD_TYPE => 'field_type',
 	   XCFORUM_DEFINITION_SORT_KEY_VALIDATION => 'validation',
 	   XCFORUM_DEFINITION_SORT_KEY_REQUIRED => 'required',
 	   XCFORUM_DEFINITION_SORT_KEY_WEIGHT => 'weight',
 	   XCFORUM_DEFINITION_SORT_KEY_SHOW_LIST => 'show_list',
 	   XCFORUM_DEFINITION_SORT_KEY_SEARCH_FLAG => 'search_flag',
 	   XCFORUM_DEFINITION_SORT_KEY_DESCRIPTION => 'description',
 	   XCFORUM_DEFINITION_SORT_KEY_OPTIONS => 'options',
 	   XCFORUM_DEFINITION_SORT_KEY_POSTTIME => 'posttime',

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
        return XCFORUM_DEFINITION_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('definition_id')) !== null) {
			$this->mNavi->addExtra('definition_id', $value);
			$this->_mCriteria->add(new Criteria('definition_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('field_name')) !== null) {
			$this->mNavi->addExtra('field_name', $value);
			$this->_mCriteria->add(new Criteria('field_name', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('label')) !== null) {
			$this->mNavi->addExtra('label', $value);
			$this->_mCriteria->add(new Criteria('label', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('field_type')) !== null) {
			$this->mNavi->addExtra('field_type', $value);
			$this->_mCriteria->add(new Criteria('field_type', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('validation')) !== null) {
			$this->mNavi->addExtra('validation', $value);
			$this->_mCriteria->add(new Criteria('validation', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('required')) !== null) {
			$this->mNavi->addExtra('required', $value);
			$this->_mCriteria->add(new Criteria('required', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('weight')) !== null) {
			$this->mNavi->addExtra('weight', $value);
			$this->_mCriteria->add(new Criteria('weight', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('show_list')) !== null) {
			$this->mNavi->addExtra('show_list', $value);
			$this->_mCriteria->add(new Criteria('show_list', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('search_flag')) !== null) {
			$this->mNavi->addExtra('search_flag', $value);
			$this->_mCriteria->add(new Criteria('search_flag', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('description')) !== null) {
			$this->mNavi->addExtra('description', $value);
			$this->_mCriteria->add(new Criteria('description', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('options')) !== null) {
			$this->mNavi->addExtra('options', $value);
			$this->_mCriteria->add(new Criteria('options', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
