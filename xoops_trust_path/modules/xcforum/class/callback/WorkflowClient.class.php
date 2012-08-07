<?php
/**
 * @package xcforum
 * @version $Id: WorkflowClient.class.php,v 1.0 $
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * workflow client delegate
**/
class Xcforum_WorkflowClientDelegate implements Legacy_iWorkflowClientDelegate
{
    /**
     * getClientList
     *
     * @param mixed[]   &$list
     *  @list[]['dirname']
     *  @list[]['dataname']
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
    
        //dirname list of xcforum
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
    
        foreach($dirnames as $dir){
            //setup client module info
           $list[] = array('dirname'=>$dir, 'dataname'=>'forums');
           $list[] = array('dirname'=>$dir, 'dataname'=>'topics');
           $list[] = array('dirname'=>$dir, 'dataname'=>'posts');
       }
        }
    
        $isCalled = true;
    }

    /**
     * updateStatus
     *
     * @param string &$result
     * @param string $dirname
     * @param string $dataname
     * @param int $id
     * @param string $id
     * @param Lenum_Status $status
     *
     * @return  void
     */ 
    public static function updateStatus(/*** string ***/ &$result, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** int ***/ $id, /*** Lenum_Status ***/ $status)
    {
        //don't call this method multiple times when site owner duplicate.
        static $isCalled = false;
        if($isCalled === true){
            return;
        }
    
        $dirnames = Legacy_Utils::getDirnameListByTrustDirname(basename(dirname(dirname(dirname(__FILE__)))));
        foreach($dirnames as $xcforumDirname){
            if($dirname == $xcforumDirname && $dataname=='{tablename}'){
                $handler = Legacy_Utils::getModuleHandler($dataname, $dirname);
                $obj = $handler->get($id);
                $obj->set('status', $status);
                $result = $handler->insert($obj);
            }
        }
    
        $isCalled = true;
    }
}

?>
