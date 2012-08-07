<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
    exit();
}

/**
 * Xcforum_TopicBlock
**/
class Xcforum_TopicBlock extends Legacy_BlockProcedure
{
    /**
     * @var Xcforum_TopicHandler
     * 
     * @private
    **/
    var $_mHandler = null;
    
    /**
     * @var Xcforum_TopicObject
     * 
     * @private
    **/
    var $_mOject = null;
    
    /**
     * @var string[]
     * 
     * @private
    **/
    var $_mOptions = array();
    
    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @public
    **/
    public function prepare()
    {
        return parent::prepare() && $this->_parseOptions() && $this->_setupObject();
    }
    
    /**
     * _parseOptions
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    protected function _parseOptions()
    {
        $opts = explode('|',$this->_mBlock->get('options'));
        $this->_mOptions = array(
            'limit'	=> (intval($opts[0])>0 ? intval($opts[0]) : 5),
            'catIds'	=> $opts[1],
        );
        return true;
    }
    
    /**
     * getBlockOption
     * 
     * @param   string  $key
     * 
     * @return  string
     * 
     * @public
    **/
    public function getBlockOption($key)
    {
        return isset($this->_mOptions[$key]) ? $this->_mOptions[$key] : null;
    }
    
    /**
     * getOptionForm
     * 
     * @param   void
     * 
     * @return  string
     * 
     * @public
    **/
    public function getOptionForm()
    {
        if(!$this->prepare())
        {
            return null;
        }
		$form = '<label for="'. $this->_mBlock->get('dirname') .'block_limit">'._AD_LEFORUM_LANG_DISPLAY_NUMBER.'</label>&nbsp;:
		<input type="text" size="5" name="options[0]" id="'. $this->_mBlock->get('dirname') .'block_limit" value="'.$this->getBlockOption('limit').'" /><br />
		<label for="'. $this->_mBlock->get('dirname') .'block_catIds">'._AD_LEFORUM_LANG_SHOW_CAT.'</label>&nbsp;:
		<input type="text" size="64" name="options[1]" id="'. $this->_mBlock->get('dirname') .'block_catIds" value="'.$this->getBlockOption('catIds').'" /><br />';
		return $form;
    }

    /**
     * _setupObject
     * 
     * @param   void
     * 
     * @return  bool
     * 
     * @private
    **/
    protected function _setupObject()
    {
    	$categoryIds = null;
		$objects = array();
		$catIdArr = array();

    	//get block options
    	$limit = $this->getBlockOption('limit');
    
        //get module asset for handlers
        $asset = null;
        XCube_DelegateUtils::call(
            'Module.xcforum.Global.Event.GetAssetManager',
            new XCube_Ref($asset),
            $this->_mBlock->get('dirname')
        );
    
        $this->_mHandler =& $asset->getObject('handler','topics');
    
    	$categoryArr = null;
    	if(trim($this->getBlockOption('catIds'))){
	    	$categoryArr['dirname'] = Xcforum_Utils::getAccessController($this->_mBlock->get('dirname'))->get('dirname');
	    	$categoryArr['id'] = explode(',', $this->getBlockOption('catIds'));
	    }

	    $attributes=array();
//		$this->_mObject = $this->_mHandler->getComments($categoryArr, null, null, $this->getBlockOption('limit'));
		$this->_mObject = $this->_mHandler->getTopicsObj($attributes, '' , $limit,  0);
        return true;
    }

    /**
     * execute
     * 
     * @param   void
     * 
     * @return  void
     * 
     * @public
    **/
    public function execute()
    {
        $root =& XCube_Root::getSingleton();
    
        $render =& $this->getRenderTarget();
        $render->setTemplateName($this->_mBlock->get('template'));
        $render->setAttribute('block', $this->_mObject);
        $render->setAttribute('topic_id', $this->_mBlock->get('topic_id'));
        $render->setAttribute('dirname', $this->_mBlock->get('dirname'));
        $renderSystem =& $root->getRenderSystem($this->getRenderSystemName());
        //var_dump($renderSystem);die();
        $renderSystem->renderBlock($render);
    }
}

?>
