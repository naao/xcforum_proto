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

require_once XOOPS_TRUST_PATH . '/modules/xcforum/preload/AssetPreload.class.php';
Xcforum_AssetPreloadBase::prepare(basename(dirname(dirname(__FILE__))));

?>
