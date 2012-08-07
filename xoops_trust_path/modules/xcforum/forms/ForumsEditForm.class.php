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
 * Xcforum_ForumsEditForm
**/
class Xcforum_ForumsEditForm extends XCube_ActionForm
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
		return "module.xcforum.ForumsEditForm.TOKEN";
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
        $this->mFormProperties['forum_id'] = new XCube_IntProperty('forum_id');
        $this->mFormProperties['category_id'] = new XCube_IntProperty('category_id');
        $this->mFormProperties['forum_external_link_format'] = new XCube_StringProperty('forum_external_link_format');
        $this->mFormProperties['forum_title'] = new XCube_StringProperty('forum_title');
        $this->mFormProperties['forum_desc'] = new XCube_TextProperty('forum_desc');
        $this->mFormProperties['forum_topics_count'] = new XCube_IntProperty('forum_topics_count');
        $this->mFormProperties['forum_posts_count'] = new XCube_IntProperty('forum_posts_count');
        $this->mFormProperties['forum_last_post_id'] = new XCube_IntProperty('forum_last_post_id');
        $this->mFormProperties['forum_last_post_time'] = new XCube_IntProperty('forum_last_post_time');
        $this->mFormProperties['forum_weight'] = new XCube_IntProperty('forum_weight');
        $this->mFormProperties['forum_options'] = new XCube_TextProperty('forum_options');
        $this->mFormProperties['status'] = new XCube_IntProperty('status');
        //$this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');
        $this->mFormProperties['tags'] = new XCube_TextProperty('tags');

	
		//
		// Set field properties
		//
       $this->mFieldProperties['forum_id'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['forum_id']->setDependsByArray(array('required'));
		$this->mFieldProperties['forum_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_ID);
       $this->mFieldProperties['category_id'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['category_id']->setDependsByArray(array('required'));
		$this->mFieldProperties['category_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_CATEGORY_ID);
       $this->mFieldProperties['forum_external_link_format'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['forum_external_link_format']->setDependsByArray(array('maxlength'));
        $this->mFieldProperties['forum_external_link_format']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_EXTERNAL_LINK_FORMAT);
        $this->mFieldProperties['forum_external_link_format']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_FORUM_EXTERNAL_LINK_FORMAT, '255');
        $this->mFieldProperties['forum_external_link_format']->addVar('maxlength', '255');
       $this->mFieldProperties['forum_title'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['forum_title']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['forum_title']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_TITLE);
        $this->mFieldProperties['forum_title']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_FORUM_TITLE, '255');
        $this->mFieldProperties['forum_title']->addVar('maxlength', '255');
       $this->mFieldProperties['forum_desc'] = new XCube_FieldProperty($this);
        //$this->mFieldProperties['forum_desc']->setDependsByArray(array());
        //$this->mFieldProperties['forum_desc']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_DESC);
       //$this->mFieldProperties['forum_topics_count'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['forum_topics_count']->setDependsByArray(array());
		//$this->mFieldProperties['forum_topics_count']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_TOPICS_COUNT);
       //$this->mFieldProperties['forum_posts_count'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['forum_posts_count']->setDependsByArray(array());
		//$this->mFieldProperties['forum_posts_count']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_POSTS_COUNT);
       //$this->mFieldProperties['forum_last_post_id'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['forum_last_post_id']->setDependsByArray(array());
		//$this->mFieldProperties['forum_last_post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_LAST_POST_ID);
       //$this->mFieldProperties['forum_last_post_time'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['forum_last_post_time']->setDependsByArray(array());
		//$this->mFieldProperties['forum_last_post_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_LAST_POST_TIME);
       $this->mFieldProperties['forum_weight'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['forum_weight']->setDependsByArray(array('required'));
		//$this->mFieldProperties['forum_weight']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_WEIGHT);
       $this->mFieldProperties['forum_options'] = new XCube_FieldProperty($this);
        //$this->mFieldProperties['forum_options']->setDependsByArray(array('required'));
        //$this->mFieldProperties['forum_options']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_FORUM_OPTIONS);
        $this->mFieldProperties['status'] = new XCube_FieldProperty($this);
       // $this->mFieldProperties['posttime'] = new XCube_FieldProperty($this);
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
        $this->set('forum_id', $obj->get('forum_id'));
        $this->set('category_id', $obj->get('category_id'));
        $this->set('forum_external_link_format', $obj->get('forum_external_link_format'));
			//adump($obj->get('forum_title'));
        $this->set('forum_title', $obj->get('forum_title'));
       $this->set('forum_desc', $obj->get('forum_desc'));
			//adump($obj->get('forum_topics_count'));
        $this->set('forum_topics_count', $obj->get('forum_topics_count') );
        $this->set('forum_posts_count', $obj->get('forum_posts_count'));
        $this->set('forum_last_post_id', $obj->get('forum_last_post_id'));
        $this->set('forum_last_post_time', $obj->get('forum_last_post_time'));
        $this->set('forum_weight', $obj->get('forum_weight'));
        $this->set('forum_options', $obj->get('forum_options'));
        $this->set('status', $obj->get('status'));
        //$this->set('posttime', $obj->get('posttime'));
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
        $obj->set('category_id', $this->get('category_id'));
        $obj->set('forum_external_link_format', $this->get('forum_external_link_format'));
        $obj->set('forum_title', $this->get('forum_title'));
        $obj->set('forum_desc', $this->get('forum_desc'));
        //$obj->set('forum_topics_count', $this->get('forum_topics_count'));
        //$obj->set('forum_posts_count', $this->get('forum_posts_count'));
        //$obj->set('forum_last_post_id', $this->get('forum_last_post_id'));
        //$obj->set('forum_last_post_time', $this->get('forum_last_post_time'));
        $obj->set('forum_weight', $this->get('forum_weight'));
        //$obj->set('forum_options', $this->get('forum_options'));
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
