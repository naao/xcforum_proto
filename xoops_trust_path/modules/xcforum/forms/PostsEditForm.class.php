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
 * Xcforum_PostsEditForm
**/
class Xcforum_PostsEditForm extends XCube_ActionForm
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
		return "module.xcforum.PostsEditForm.TOKEN";
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
        $this->mFormProperties['post_id'] = new XCube_IntProperty('post_id');
        $this->mFormProperties['pid'] = new XCube_IntProperty('pid');
        $this->mFormProperties['topic_id'] = new XCube_IntProperty('topic_id');
        $this->mFormProperties['post_time'] = new XCube_IntProperty('post_time');
        $this->mFormProperties['modified_time'] = new XCube_IntProperty('modified_time');
        $this->mFormProperties['uid'] = new XCube_IntProperty('uid');
        $this->mFormProperties['uid_hidden'] = new XCube_IntProperty('uid_hidden');
        $this->mFormProperties['poster_ip'] = new XCube_StringProperty('poster_ip');
        $this->mFormProperties['modifier_ip'] = new XCube_StringProperty('modifier_ip');
        $this->mFormProperties['subject'] = new XCube_StringProperty('subject');
        $this->mFormProperties['subject_waiting'] = new XCube_StringProperty('subject_waiting');
        $this->mFormProperties['html'] = new XCube_IntProperty('html');
        $this->mFormProperties['smiley'] = new XCube_IntProperty('smiley');
        $this->mFormProperties['xcode'] = new XCube_IntProperty('xcode');
        $this->mFormProperties['br'] = new XCube_IntProperty('br');
        $this->mFormProperties['number_entity'] = new XCube_IntProperty('number_entity');
        $this->mFormProperties['special_entity'] = new XCube_IntProperty('special_entity');
        $this->mFormProperties['icon'] = new XCube_IntProperty('icon');
        $this->mFormProperties['attachsig'] = new XCube_IntProperty('attachsig');
        $this->mFormProperties['invisible'] = new XCube_IntProperty('invisible');
        $this->mFormProperties['approval'] = new XCube_IntProperty('approval');
        $this->mFormProperties['votes_sum'] = new XCube_IntProperty('votes_sum');
        $this->mFormProperties['votes_count'] = new XCube_IntProperty('votes_count');
        $this->mFormProperties['depth_in_tree'] = new XCube_IntProperty('depth_in_tree');
        $this->mFormProperties['order_in_tree'] = new XCube_IntProperty('order_in_tree');
        $this->mFormProperties['path_in_tree'] = new XCube_TextProperty('path_in_tree');
        $this->mFormProperties['unique_path'] = new XCube_TextProperty('unique_path');
        $this->mFormProperties['guest_name'] = new XCube_StringProperty('guest_name');
        $this->mFormProperties['guest_email'] = new XCube_StringProperty('guest_email');
        $this->mFormProperties['guest_url'] = new XCube_StringProperty('guest_url');
        $this->mFormProperties['guest_pass_md5'] = new XCube_StringProperty('guest_pass_md5');
        $this->mFormProperties['guest_trip'] = new XCube_StringProperty('guest_trip');
        $this->mFormProperties['post_text'] = new XCube_TextProperty('post_text');
        $this->mFormProperties['post_text_waiting'] = new XCube_TextProperty('post_text_waiting');
        $this->mFormProperties['status'] = new XCube_IntProperty('status');
        $this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');
        $this->mFormProperties['tags'] = new XCube_TextProperty('tags');

		//$this->mFormProperties['forum_id'] = new XCube_IntProperty('forum_id');

	
		//
		// Set field properties
		//
       $this->mFieldProperties['post_id'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['post_id']->setDependsByArray(array('required'));
		$this->mFieldProperties['post_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_ID);
       $this->mFieldProperties['pid'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['pid']->setDependsByArray(array('required'));
		$this->mFieldProperties['pid']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_PID);
       $this->mFieldProperties['topic_id'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['topic_id']->setDependsByArray(array('required'));
		$this->mFieldProperties['topic_id']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_TOPIC_ID);
       $this->mFieldProperties['post_time'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['post_time']->setDependsByArray(array('required'));
		//$this->mFieldProperties['post_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_TIME);
       $this->mFieldProperties['modified_time'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['modified_time']->setDependsByArray(array('required'));
		//$this->mFieldProperties['modified_time']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_MODIFIED_TIME);
        $this->mFieldProperties['uid'] = new XCube_FieldProperty($this);
       $this->mFieldProperties['uid_hidden'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['uid_hidden']->setDependsByArray(array('required'));
		//$this->mFieldProperties['uid_hidden']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_UID_HIDDEN);
       $this->mFieldProperties['poster_ip'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['poster_ip']->setDependsByArray(array('maxlength'));
       // $this->mFieldProperties['poster_ip']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POSTER_IP);
        $this->mFieldProperties['poster_ip']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_POSTER_IP, '15');
        $this->mFieldProperties['poster_ip']->addVar('maxlength', '15');
       $this->mFieldProperties['modifier_ip'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['modifier_ip']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['modifier_ip']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_MODIFIER_IP);
        $this->mFieldProperties['modifier_ip']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_MODIFIER_IP, '15');
        $this->mFieldProperties['modifier_ip']->addVar('maxlength', '15');
       $this->mFieldProperties['subject'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['subject']->setDependsByArray(array('required','maxlength'));
        $this->mFieldProperties['subject']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_SUBJECT);
        $this->mFieldProperties['subject']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_SUBJECT, '255');
        $this->mFieldProperties['subject']->addVar('maxlength', '255');
       $this->mFieldProperties['subject_waiting'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['subject_waiting']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['subject_waiting']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_SUBJECT_WAITING);
        $this->mFieldProperties['subject_waiting']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_SUBJECT_WAITING, '255');
        $this->mFieldProperties['subject_waiting']->addVar('maxlength', '255');
       $this->mFieldProperties['html'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['html']->setDependsByArray(array('required'));
		//$this->mFieldProperties['html']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_HTML);
       $this->mFieldProperties['smiley'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['smiley']->setDependsByArray(array('required'));
		//$this->mFieldProperties['smiley']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_SMILEY);
       $this->mFieldProperties['xcode'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['xcode']->setDependsByArray(array('required'));
		//$this->mFieldProperties['xcode']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_XCODE);
       $this->mFieldProperties['br'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['br']->setDependsByArray(array('required'));
		//$this->mFieldProperties['br']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_BR);
       $this->mFieldProperties['number_entity'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['number_entity']->setDependsByArray(array('required'));
		//$this->mFieldProperties['number_entity']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_NUMBER_ENTITY);
       $this->mFieldProperties['special_entity'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['special_entity']->setDependsByArray(array('required'));
		//$this->mFieldProperties['special_entity']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_SPECIAL_ENTITY);
       $this->mFieldProperties['icon'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['icon']->setDependsByArray(array('required'));
		//$this->mFieldProperties['icon']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_ICON);
       $this->mFieldProperties['attachsig'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['attachsig']->setDependsByArray(array('required'));
		//$this->mFieldProperties['attachsig']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_ATTACHSIG);
       $this->mFieldProperties['invisible'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['invisible']->setDependsByArray(array('required'));
		//$this->mFieldProperties['invisible']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_INVISIBLE);
       $this->mFieldProperties['approval'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['approval']->setDependsByArray(array('required'));
		//$this->mFieldProperties['approval']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_APPROVAL);
       $this->mFieldProperties['votes_sum'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['votes_sum']->setDependsByArray(array('required'));
		//$this->mFieldProperties['votes_sum']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTES_SUM);
       $this->mFieldProperties['votes_count'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['votes_count']->setDependsByArray(array('required'));
		//$this->mFieldProperties['votes_count']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_VOTES_COUNT);
       $this->mFieldProperties['depth_in_tree'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['depth_in_tree']->setDependsByArray(array('required'));
		//$this->mFieldProperties['depth_in_tree']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_DEPTH_IN_TREE);
       $this->mFieldProperties['order_in_tree'] = new XCube_FieldProperty($this);
		//$this->mFieldProperties['order_in_tree']->setDependsByArray(array('required'));
		//$this->mFieldProperties['order_in_tree']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_ORDER_IN_TREE);
       $this->mFieldProperties['path_in_tree'] = new XCube_FieldProperty($this);
        //$this->mFieldProperties['path_in_tree']->setDependsByArray(array('required'));
        //$this->mFieldProperties['path_in_tree']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_PATH_IN_TREE);
       $this->mFieldProperties['unique_path'] = new XCube_FieldProperty($this);
        //$this->mFieldProperties['unique_path']->setDependsByArray(array('required'));
       // $this->mFieldProperties['unique_path']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_UNIQUE_PATH);
       $this->mFieldProperties['guest_name'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['guest_name']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['guest_name']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_GUEST_NAME);
        $this->mFieldProperties['guest_name']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_GUEST_NAME, '25');
        $this->mFieldProperties['guest_name']->addVar('maxlength', '25');
       $this->mFieldProperties['guest_email'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['guest_email']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['guest_email']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_GUEST_EMAIL);
        $this->mFieldProperties['guest_email']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_GUEST_EMAIL, '60');
        $this->mFieldProperties['guest_email']->addVar('maxlength', '60');
       $this->mFieldProperties['guest_url'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['guest_url']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['guest_url']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_GUEST_URL);
        $this->mFieldProperties['guest_url']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_GUEST_URL, '100');
        $this->mFieldProperties['guest_url']->addVar('maxlength', '100');
       $this->mFieldProperties['guest_pass_md5'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['guest_pass_md5']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['guest_pass_md5']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_GUEST_PASS_MD5);
        $this->mFieldProperties['guest_pass_md5']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_GUEST_PASS_MD5, '40');
        $this->mFieldProperties['guest_pass_md5']->addVar('maxlength', '40');
       $this->mFieldProperties['guest_trip'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['guest_trip']->setDependsByArray(array('maxlength'));
        //$this->mFieldProperties['guest_trip']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_GUEST_TRIP);
        $this->mFieldProperties['guest_trip']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_GUEST_TRIP, '40');
        $this->mFieldProperties['guest_trip']->addVar('maxlength', '40');
       $this->mFieldProperties['post_text'] = new XCube_FieldProperty($this);
        //$this->mFieldProperties['post_text']->setDependsByArray(array('required'));
        //$this->mFieldProperties['post_text']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_TEXT);
       $this->mFieldProperties['post_text_waiting'] = new XCube_FieldProperty($this);
        //$this->mFieldProperties['post_text_waiting']->setDependsByArray(array('required','maxlength'));
       // $this->mFieldProperties['post_text_waiting']->addMessage('required', _MD_XCFORUM_ERROR_REQUIRED, _MD_XCFORUM_LANG_POST_TEXT_WAITING);
        //$this->mFieldProperties['post_text_waiting']->addMessage('maxlength', _MD_XCFORUM_ERROR_MAXLENGTH, _MD_XCFORUM_LANG_POST_TEXT_WAITING, '0');
       // $this->mFieldProperties['post_text_waiting']->addVar('maxlength', '0');
        $this->mFieldProperties['status'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['posttime'] = new XCube_FieldProperty($this);

		//$this->mFieldProperties['forum_id'] = new XCube_FieldProperty($this);
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
		/*
		if( $obj->isNew() ){
			adump("load:isnew");
		} else {
			adump("load:isedit");
		}
		*/
        $this->set('post_id', $obj->get('post_id'));
        $this->set('pid', $obj->get('pid'));
        $this->set('topic_id', $obj->get('topic_id'));
        $this->set('post_time', $obj->get('post_time'));
        $this->set('modified_time', $obj->get('modified_time'));
        $this->set('uid', $obj->get('uid'));
        $this->set('uid_hidden', $obj->get('uid_hidden'));
        $this->set('poster_ip', $obj->get('poster_ip'));
        $this->set('modifier_ip', $obj->get('modifier_ip'));
        $this->set('subject', $obj->get('subject'));
        $this->set('subject_waiting', $obj->get('subject_waiting'));
        $this->set('html', $obj->get('html'));
        $this->set('smiley', $obj->get('smiley'));
        $this->set('xcode', $obj->get('xcode'));
        $this->set('br', $obj->get('br'));
        $this->set('number_entity', $obj->get('number_entity'));
        $this->set('special_entity', $obj->get('special_entity'));
        $this->set('icon', $obj->get('icon'));
        $this->set('attachsig', $obj->get('attachsig'));
        $this->set('invisible', $obj->get('invisible'));
        $this->set('approval', $obj->get('approval'));
        $this->set('votes_sum', $obj->get('votes_sum'));
        $this->set('votes_count', $obj->get('votes_count'));
        $this->set('depth_in_tree', $obj->get('depth_in_tree'));
        $this->set('order_in_tree', $obj->get('order_in_tree'));
        $this->set('path_in_tree', $obj->get('path_in_tree'));
        $this->set('unique_path', $obj->get('unique_path'));
        $this->set('guest_name', $obj->get('guest_name'));
        $this->set('guest_email', $obj->get('guest_email'));
        $this->set('guest_url', $obj->get('guest_url'));
        $this->set('guest_pass_md5', $obj->get('guest_pass_md5'));
        $this->set('guest_trip', $obj->get('guest_trip'));
        $this->set('post_text', $obj->get('post_text'));
        $this->set('post_text_waiting', $obj->get('post_text_waiting'));
        $this->set('status', $obj->get('status'));
        $this->set('posttime', $obj->get('posttime'));
      $tags = is_array($obj->mTag) ? implode(' ', $obj->mTag) : null;
        if(count($obj->mTag)>0) $tags = $tags.' ';
        $this->set('tags', $tags);

		//$this->set('forum_id', $obj->get('forum_id'));
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

	/*	if( $obj->isNew() ){
			adump("update:isnew");
		} else {
			adump("update:isedit");
		}
	*/
		$obj->set('pid', $this->get('pid'));
		$obj->set('topic_id', $this->get('topic_id'));
		$obj->set('subject', $this->get('subject'));

       //$obj->set('post_time', $this->get('post_time'));
		//$obj->set('modified_time', $this->get('modified_time'));
		// $obj->set('uid_hidden', $this->get('uid_hidden'));
		//$obj->set('poster_ip', $this->get('poster_ip'));
		//$obj->set('modifier_ip', $this->get('modifier_ip'));
        $obj->set('subject_waiting', $this->get('subject_waiting'));
        $obj->set('html', $this->get('html') ? 1 : 0);
        $obj->set('smiley', $this->get('smiley') ? 1 : 0);
        $obj->set('xcode', $this->get('xcode') ? 1 : 0);
        $obj->set('br', $this->get('br') ? 1 : 0);
        $obj->set('number_entity', $this->get('number_entity') ? 1 : 0);
        $obj->set('special_entity', $this->get('special_entity') ? 1 : 0);
        $obj->set('icon', $this->get('icon') ? 1 : 0);
        $obj->set('attachsig', $this->get('attachsig') ? 1 : 0);
        $obj->set('invisible', $this->get('invisible') ? 1 : 0);
        $obj->set('approval', $this->get('approval') ? 1 : 0);
		// $obj->set('votes_sum', $this->get('votes_sum'));
		//$obj->set('votes_count', $this->get('votes_count'));
		//$obj->set('depth_in_tree', $this->get('depth_in_tree'));
		//$obj->set('order_in_tree', $this->get('order_in_tree'));
		//$obj->set('path_in_tree', $this->get('path_in_tree'));
        $obj->set('unique_path', $this->get('unique_path'));
        $obj->set('guest_name', $this->get('guest_name'));
        $obj->set('guest_email', $this->get('guest_email'));
        $obj->set('guest_url', $this->get('guest_url'));
        $obj->set('guest_pass_md5', $this->get('guest_pass_md5'));
        $obj->set('guest_trip', $this->get('guest_trip'));
        $obj->set('post_text', $this->get('post_text'));
		//$obj->set('post_text_waiting', $this->get('post_text_waiting'));
        $obj->mTag = explode(' ', trim($this->get('tags')));

		//adump($obj);
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
