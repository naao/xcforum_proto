<?php

// this file can be included only from main or admin (not from blocks)


// add fields for tree structure into $posts or $categories
function xcforum_make_treeinformations( $data )
{
}


// check done
function xcforum_get_forum_permissions_of_current_user( $mydirname )
{
}


// check done
function xcforum_get_category_permissions_of_current_user( $mydirname )
{
}


// check done
function xcforum_get_users_can_read_forum( $mydirname , $forum_id , $cat_id = null )
{
}


// check done
function xcforum_get_forum_moderate_groups4show( $mydirname , $forum_id )
{
}


// check done
function xcforum_get_forum_moderate_users4show( $mydirname , $forum_id )
{
}


// check done
function xcforum_get_category_moderate_groups4show( $mydirname , $cat_id )
{
}


// check done
function xcforum_get_category_moderate_users4show( $mydirname , $cat_id )
{
}


// select box for jumping into a specified forum
function xcforum_make_jumpbox_options( $mydirname , $whr4cat , $whr4forum , $forum_selected = 0 )
{
}


// select box for jumping into a specified category
function xcforum_make_cat_jumpbox_options( $mydirname , $whr4cat , $cat_selected = 0 )
{
}


function xcforum_trigger_event( $mydirname ,  $category , $item_id , $event , $extra_tags=array() , $user_list=array() , $omit_user_id=null )
{
}


// started from {XOOPS_URL} for conventional modules
function xcforum_get_comment_link( $external_link_format , $external_link_id )
{
}


// started from class:: for native d3comment modules
function xcforum_get_comment_description( $mydirname , $external_link_format , $external_link_id )
{
}

// get object for comment integration  // naao modified
function &xcforum_main_get_comment_object( $forum_dirname, $external_link_format )
{
}

// get samples of category options
function xcforum_main_get_categoryoptions4edit( $d3forum_configs_can_be_override )
{
}


// hook topic_id/external_link_id into $_POST['mode'] = 'reply' , $_POST['post_id']
function xcforum_main_posthook_sametopic( $mydirname )
{
}

?>