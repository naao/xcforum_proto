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

interface Xcforum_AuthType
{
    const VIEW = "view";
    const POST = "post";
    const MANAGE = "manage";
}

?>
