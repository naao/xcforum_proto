<?php
/**
 * @package xcforum
 * @version $Id: ActivityClient.class.php,v 1.0 $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * activity client delegate
**/
class Xcforum_ActivityClientDelegate implements Legacy_iActivityClientDelegate
{
    /**
     * getClientList
     *
     * @param mixed[]   &$list
     *  string  $list[]['dirname']
     *  string  $list[]['dataname']
     *  string  $list[]['access_controller']
     *
     * @return  void
     */ 
    public static function getClientList(/*** mixed[] ***/ &$list)
    {
        //don't call this method multiple times when site owner duplicate.
        static $isCalled = false;
        if($isCalled === true){
            return;
        }
    
        //get dirname list of this module
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
    
        foreach($dirnames as $dir){
            //setup client module info
                $list[] = array('dirname'=>$dir, 'dataname'=>'forums', 'access_controller'=>Xcforum_Utils::getModuleConfig($dir, 'access_controller'));
                $list[] = array('dirname'=>$dir, 'dataname'=>'topics', 'access_controller'=>Xcforum_Utils::getModuleConfig($dir, 'access_controller'));
                $list[] = array('dirname'=>$dir, 'dataname'=>'posts', 'access_controller'=>Xcforum_Utils::getModuleConfig($dir, 'access_controller'));
                $list[] = array('dirname'=>$dir, 'dataname'=>'users2topics', 'access_controller'=>Xcforum_Utils::getModuleConfig($dir, 'access_controller'));
       
        }
    
        $isCalled = true;
    }

    /**
     * getClientData
     *
     * @param mixed     &$list
     * @param string    $dirname
     * @param string    $dataname
     * @param int       $dataId
     *
     * @return  void
     */ 
    public static function getClientData(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId)
    {
        $handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
        if(! $handler){
            return;
        }
    
        //setup client module info
        $obj = $handler->get($dataId);
        if(is_object($obj)){
            $list['dirname'] = $dirname;
            $list['dataname'] = $dataname;
            $list['data_id'] = $dataId;
            $list['data'] = $obj;
            $handler = xoops_gethandler('module');
            $module = $handler->getByDirname($dirname);
            $list['title'] = $module->name();
            $list['template_name'] = 'db:'.$dirname .'_'. $dataname .'_inc_activity.html';
        }
    }

    /**
     * getClientFeed    Legacy_ActivityClient.{dirname}.GetClientFeed
     *
     * @param mixed     &$list
     *  string[]    $list['title']  entry's title
     *  string[]    $list['link']   link to entry
     *  string[]    $list['id']     entry's id(=permalink to entry)
     *  int[]       $list['updated']    unixtime
     *  int[]       $list['published']  unixtime
     *  string[]    $list['author']
     *  string[]    $list['content']
     * @param string    $dirname    client module's dirname
     * @param string    $dataname   client module's dataname(tablename)
     * @param int       $dataId     client module's primary key
     *
     * @return  void
     */ 
    public static function getClientFeed(/*** mixed ***/ &$list, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $dataId)
    {
        $handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
        if(! $handler){
            return;
        }
    
        //setup client module info
        $obj = $handler->get($dataId);
        $list['title'] = $obj->get('title');
        $list['link'] = Legacy_Utils::renderUri($dirname, $dataname, $dataId);
        $list['id'] = Legacy_Utils::renderUri($dirname, $dataname, $dataId);
        $list['published'] = $obj->get('posttime');
        $list['updated'] = $obj->get('updatetime');
        $list['author'] = Legacy_Utils::getUserName($obj->get('uid'));
        $list['content'] = null;
    }
}

?>
