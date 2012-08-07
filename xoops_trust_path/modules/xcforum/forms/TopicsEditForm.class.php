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

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

/**
 * Xcforum_TopicsEditForm
**/
class Xcforum_TopicsEditForm extends XCube_ActionForm
{
	/**
	 * getTokenName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getTokenName()
	{
		return "module.xcforum.TopicsEditForm.TOKEN";
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function prepare()
	{
		//
		// Set form properties
		//
        $this->mFormProperties['topic_id'] = new XCube_IntProperty('topic_id');
        $this->mFormProperties['forum_id'] = new XCube_IntProperty('forum_id');
        $this->mFormProperties['topic_external_link_id'] = new XCube_StringProperty('topic_external_link_id');
        $this->mFormProperties['topic_title'] = new XCube_StringProperty('topic_title');
        $this->mFormProperties['topic_first_uid'] = new XCube_IntProperty('topic_first_uid');
        $this->mFormProperties['topic_first_post_id'] = new XCube_IntProperty('topic_first_post_id');
        $this->mFormProperties['topic_first_post_time'] = new XCube_IntProperty('topic_first_post_time');
        $this->mFormProperties['topic_last_uid'] = new XCube_IntProperty('topic_last_uid');
        $this->mFormProperties['topic_last_post_id'] = new XCube_IntProperty('topic_last_post_id');
        $this->mFormProperties['topic_last_post_time'] = new XCube_IntProperty('topic_last_post_time');
        $this->mFormProperties['topic_views'] = new XCube_IntProperty('topic_views');
        $this->mFormProperties['topic_posts_count'] = new XCube_IntProperty('topic_posts_count');
        $this->mFormProperties['topic_locked'] = new XCube_IntProperty('topic_locked');
        $this->mFormProperties['topic_sticky'] = new XCube_IntProperty('topic_sticky');
        $this->mFormProperties['topic_solved'] = new XCube_IntProperty('topic_solved');
        $this->mFormProperties['topic_invisible'] = new XCube_IntProperty('topic_invisible');
        $this->mFormProperties['topic_votes_sum'] = new XCube_IntProperty('topic_votes_sum');
        $this->mFormProperties['topic_votes_count'] = new XCube_IntProperty('topic_votes_count');
        $this->mFormProperties['status'] = new XCube_IntProperty('status');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');
        $this->mFormProperties['tags'] = new XCube_TextProperty('tags');

	
		//
		// Set field properties
		//
       $this->mFieldProperties['topic_id'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_id']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_ID);
       $this->mFieldProperties['forum_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['forum_id']->setDependsByArray(array('required'));
$this->mFieldProperties['forum_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_ID);
       $this->mFieldProperties['topic_external_link_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['topic_external_link_id']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['topic_external_link_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_EXTERNAL_LINK_ID);
        $this->mFieldProperties['topic_external_link_id']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_TOPIC_EXTERNAL_LINK_ID, '255');
        $this->mFieldProperties['topic_external_link_id']->addVar('maxlength', '255');
       $this->mFieldProperties['topic_title'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['topic_title']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['topic_title']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_TITLE);
        $this->mFieldProperties['topic_title']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_TOPIC_TITLE, '255');
        $this->mFieldProperties['topic_title']->addVar('maxlength', '255');
       $this->mFieldProperties['topic_first_uid'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_first_uid']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_first_uid']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_FIRST_UID);
       $this->mFieldProperties['topic_first_post_id'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_first_post_id']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_first_post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_FIRST_POST_ID);
       $this->mFieldProperties['topic_first_post_time'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_first_post_time']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_first_post_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_FIRST_POST_TIME);
       $this->mFieldProperties['topic_last_uid'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_last_uid']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_last_uid']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_LAST_UID);
       $this->mFieldProperties['topic_last_post_id'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_last_post_id']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_last_post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_LAST_POST_ID);
       $this->mFieldProperties['topic_last_post_time'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_last_post_time']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_last_post_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_LAST_POST_TIME);
       $this->mFieldProperties['topic_views'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_views']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_views']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_VIEWS);
       $this->mFieldProperties['topic_posts_count'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_posts_count']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_posts_count']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_POSTS_COUNT);
       $this->mFieldProperties['topic_locked'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_locked']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_locked']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_LOCKED);
       $this->mFieldProperties['topic_sticky'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_sticky']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_sticky']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_STICKY);
       $this->mFieldProperties['topic_solved'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_solved']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_solved']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_SOLVED);
       $this->mFieldProperties['topic_invisible'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_invisible']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_invisible']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_INVISIBLE);
       $this->mFieldProperties['topic_votes_sum'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_votes_sum']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_votes_sum']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_VOTES_SUM);
       $this->mFieldProperties['topic_votes_count'] = new XCube_FieldProperty($this);
//$this->mFieldProperties['topic_votes_count']->setDependsByArray(array('required'));
//$this->mFieldProperties['topic_votes_count']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_VOTES_COUNT);
        $this->mFieldProperties['status'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['posttime'] = new XCube_FieldProperty($this);
	}

	/**
	 * load
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function load(/*** XoopsSimpleObject ***/ &$obj)
	{
        $this->set('topic_id', $obj->get('topic_id'));
        $this->set('forum_id', $obj->get('forum_id'));
        $this->set('topic_external_link_id', $obj->get('topic_external_link_id'));
        $this->set('topic_title', $obj->get('topic_title'));
        $this->set('topic_first_uid', $obj->get('topic_first_uid'));
        $this->set('topic_first_post_id', $obj->get('topic_first_post_id'));
        $this->set('topic_first_post_time', $obj->get('topic_first_post_time'));
        $this->set('topic_last_uid', $obj->get('topic_last_uid'));
        $this->set('topic_last_post_id', $obj->get('topic_last_post_id'));
        $this->set('topic_last_post_time', $obj->get('topic_last_post_time'));
        $this->set('topic_views', $obj->get('topic_views'));
        $this->set('topic_posts_count', $obj->get('topic_posts_count'));
        $this->set('topic_locked', $obj->get('topic_locked'));
        $this->set('topic_sticky', $obj->get('topic_sticky'));
        $this->set('topic_solved', $obj->get('topic_solved'));
        $this->set('topic_invisible', $obj->get('topic_invisible'));
        $this->set('topic_votes_sum', $obj->get('topic_votes_sum'));
        $this->set('topic_votes_count', $obj->get('topic_votes_count'));
        $this->set('status', $obj->get('status'));
        $this->set('posttime', $obj->get('posttime'));
      $tags = is_array($obj->mTag) ? implode(' ', $obj->mTag) : null;
        if(count($obj->mTag)>0) $tags = $tags.' ';
        $this->set('tags', $tags);
	}

	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
        $obj->set('forum_id', $this->get('forum_id'));
        $obj->set('topic_external_link_id', $this->get('topic_external_link_id'));
        $obj->set('topic_title', $this->get('topic_title'));
        $obj->set('topic_first_uid', $this->get('topic_first_uid'));
        $obj->set('topic_first_post_id', $this->get('topic_first_post_id'));
        $obj->set('topic_first_post_time', $this->get('topic_first_post_time'));
        $obj->set('topic_last_uid', $this->get('topic_last_uid'));
        $obj->set('topic_last_post_id', $this->get('topic_last_post_id'));
        $obj->set('topic_last_post_time', $this->get('topic_last_post_time'));
        $obj->set('topic_views', $this->get('topic_views'));
        $obj->set('topic_posts_count', $this->get('topic_posts_count'));
        $obj->set('topic_locked', $this->get('topic_locked'));
        $obj->set('topic_sticky', $this->get('topic_sticky'));
        $obj->set('topic_solved', $this->get('topic_solved'));
        $obj->set('topic_invisible', $this->get('topic_invisible'));
        $obj->set('topic_votes_sum', $this->get('topic_votes_sum'));
        $obj->set('topic_votes_count', $this->get('topic_votes_count'));
        $obj->mTag = explode(' ', trim($this->get('tags')));
	}

	/**
	 * _makeUnixtime
	 * 
	 * @param	string	$key
	 * 
	 * @return	void
	**/
	protected function _makeUnixtime($key)
	{
		$timeArray = explode('-', $this->get($key));
		return mktime(0, 0, 0, $timeArray[1], $timeArray[2], $timeArray[0]);
	}
}

?>
