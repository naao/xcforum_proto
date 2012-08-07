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

if(!defined('XCFORUM_TRUST_PATH'))
{
    define('XCFORUM_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/xcforum');
}

require_once XCFORUM_TRUST_PATH . '/class/XcforumUtils.class.php';

//
// Define a basic manifesto.
//
$modversion['name'] = $myDirName;
$modversion['version'] = 0.01;
$modversion['description'] = _MI_XCFORUM_DESC_XCFORUM;
$modversion['author'] = _MI_XCFORUM_LANG_AUTHOR;
$modversion['credits'] = _MI_XCFORUM_LANG_CREDITS;
$modversion['help'] = 'help.html';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/module_icon.png';
$modversion['dirname'] = $myDirName;
$modversion['trust_dirname'] = 'xcforum';
$modversion['role'] = 'comment';

$modversion['cube_style'] = true;
$modversion['legacy_installer'] = array(
    'installer'   => array(
        'class'     => 'Installer',
        'namespace' => 'Xcforum',
        'filepath'  => XCFORUM_TRUST_PATH . '/admin/class/installer/XcforumInstaller.class.php'
    ),
    'uninstaller' => array(
        'class'     => 'Uninstaller',
        'namespace' => 'Xcforum',
        'filepath'  => XCFORUM_TRUST_PATH . '/admin/class/installer/XcforumUninstaller.class.php'
    ),
    'updater' => array(
        'class'     => 'Updater',
        'namespace' => 'Xcforum',
        'filepath'  => XCFORUM_TRUST_PATH . '/admin/class/installer/XcforumUpdater.class.php'
    )
);
$modversion['disable_legacy_2nd_installer'] = false;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = array(
//    '{prefix}_{dirname}_xxxx',
##[cubson:tables]
    '{prefix}_{dirname}_forums',
	'{prefix}_{dirname}_forumaccess',
    '{prefix}_{dirname}_topics',
    '{prefix}_{dirname}_posts',
    '{prefix}_{dirname}_users2topics',
    '{prefix}_{dirname}_postvotes',
    '{prefix}_{dirname}_posthistories',
    '{prefix}_{dirname}_definition',

##[/cubson:tables]
);

//
// Templates. You must never change [cubson] chunk to get the help of cubson.
//
$modversion['templates'] = array(
/*
    array(
        'file'        => '{dirname}_xxx.html',
        'description' => _MI_XCFORUM_TPL_XXX
    ),
*/
##[cubson:templates]
		array('file' => '{dirname}_forum_form.html','description' => _MI_XCFORUM_TPL_FORUMS_EDIT),
		array('file' => '{dirname}_forum_list.html','description' => _MI_XCFORUM_TPL_FORUMS_LIST),
		array('file' => '{dirname}_topic_list.html','description' => _MI_XCFORUM_TPL_TOPICS_LIST),
		array('file' => '{dirname}_topic_view.html','description' => _MI_XCFORUM_TPL_TOPICS_VIEW),
		array('file' => '{dirname}_post_list.html','description' => _MI_XCFORUM_TPL_POSTS_LIST),
		array('file' => '{dirname}_post_view.html','description' => _MI_XCFORUM_TPL_POSTS_VIEW),
		array('file' => '{dirname}_inc_eachpost.html','description' => _MI_XCFORUM_TPL_TOPICS_VIEW),
		array('file' => '{dirname}_main.css','description' => _MI_XCFORUM_TPL_FORUMS_EDIT),
		array('file' => '{dirname}_post_form.html','description' => _MI_XCFORUM_TPL_POSTS_EDIT),
		array('file' => '{dirname}_inc_post_form_quick.html','description' => _MI_XCFORUM_TPL_POSTS_EDIT),
		array('file' => '{dirname}_inc_moderators.html','description' => _MI_XCFORUM_TPL_POSTS_EDIT),
	array('file' => '{dirname}_comment_listposts_flat.html','description' => _MI_XCFORUM_TPL_POSTS_LIST),
		array('file' => '{dirname}_comment_listtopics.html','description' => _MI_XCFORUM_TPL_POSTS_LIST),
	//
        array('file' => '{dirname}_forums_delete.html','description' => _MI_XCFORUM_TPL_FORUMS_DELETE),
        array('file' => '{dirname}_forums_edit.html','description' => _MI_XCFORUM_TPL_FORUMS_EDIT),
        array('file' => '{dirname}_forums_list.html','description' => _MI_XCFORUM_TPL_FORUMS_LIST),
        array('file' => '{dirname}_forums_view.html','description' => _MI_XCFORUM_TPL_FORUMS_VIEW),
		array('file' => '{dirname}_forumaccess_delete.html','description' => _MI_XCFORUM_TPL_FORUMS_DELETE),
		array('file' => '{dirname}_forumaccess_edit.html','description' => _MI_XCFORUM_TPL_FORUMS_EDIT),
		array('file' => '{dirname}_forumaccess_list.html','description' => _MI_XCFORUM_TPL_FORUMS_LIST),
		array('file' => '{dirname}_forumaccess_view.html','description' => _MI_XCFORUM_TPL_FORUMS_VIEW),
       array('file' => '{dirname}_topics_delete.html','description' => _MI_XCFORUM_TPL_TOPICS_DELETE),
        array('file' => '{dirname}_topics_edit.html','description' => _MI_XCFORUM_TPL_TOPICS_EDIT),
        array('file' => '{dirname}_topics_list.html','description' => _MI_XCFORUM_TPL_TOPICS_LIST),
        array('file' => '{dirname}_topics_view.html','description' => _MI_XCFORUM_TPL_TOPICS_VIEW),
        array('file' => '{dirname}_posts_delete.html','description' => _MI_XCFORUM_TPL_POSTS_DELETE),
        array('file' => '{dirname}_posts_edit.html','description' => _MI_XCFORUM_TPL_POSTS_EDIT),
        array('file' => '{dirname}_posts_list.html','description' => _MI_XCFORUM_TPL_POSTS_LIST),
        array('file' => '{dirname}_posts_view.html','description' => _MI_XCFORUM_TPL_POSTS_VIEW),
        array('file' => '{dirname}_users2topics_delete.html','description' => _MI_XCFORUM_TPL_USERS2TOPICS_DELETE),
        array('file' => '{dirname}_users2topics_edit.html','description' => _MI_XCFORUM_TPL_USERS2TOPICS_EDIT),
        array('file' => '{dirname}_users2topics_list.html','description' => _MI_XCFORUM_TPL_USERS2TOPICS_LIST),
        array('file' => '{dirname}_users2topics_view.html','description' => _MI_XCFORUM_TPL_USERS2TOPICS_VIEW),
        array('file' => '{dirname}_postvotes_delete.html','description' => _MI_XCFORUM_TPL_POSTVOTES_DELETE),
        array('file' => '{dirname}_postvotes_edit.html','description' => _MI_XCFORUM_TPL_POSTVOTES_EDIT),
        array('file' => '{dirname}_postvotes_list.html','description' => _MI_XCFORUM_TPL_POSTVOTES_LIST),
        array('file' => '{dirname}_postvotes_view.html','description' => _MI_XCFORUM_TPL_POSTVOTES_VIEW),
        array('file' => '{dirname}_posthistories_delete.html','description' => _MI_XCFORUM_TPL_POSTHISTORIES_DELETE),
        array('file' => '{dirname}_posthistories_edit.html','description' => _MI_XCFORUM_TPL_POSTHISTORIES_EDIT),
        array('file' => '{dirname}_posthistories_list.html','description' => _MI_XCFORUM_TPL_POSTHISTORIES_LIST),
        array('file' => '{dirname}_posthistories_view.html','description' => _MI_XCFORUM_TPL_POSTHISTORIES_VIEW),
        array('file' => '{dirname}_definition_delete.html','description' => _MI_XCFORUM_TPL_DEFINITION_DELETE),
        array('file' => '{dirname}_definition_edit.html','description' => _MI_XCFORUM_TPL_DEFINITION_EDIT),
        array('file' => '{dirname}_definition_list.html','description' => _MI_XCFORUM_TPL_DEFINITION_LIST),
        array('file' => '{dirname}_definition_view.html','description' => _MI_XCFORUM_TPL_DEFINITION_VIEW),

##[/cubson:templates]
);

//
// Admin panel setting
//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php?action=Index';
$modversion['adminmenu'] = array(
/*
    array(
        'title'    => _MI_XCFORUM_LANG_XXXX,
        'link'     => 'admin/index.php?action=xxx',
        'keywords' => _MI_XCFORUM_KEYWORD_XXX,
        'show'     => true,
        'absolute' => false
    ),
*/
	array(
        'title'		=> _MI_XCFORUM_ADMENU_FORUMACCESS,
        'link'	=> 'admin/index.php?action=ForumaccessEdit',
        'keywords'	=> _MI_XCFORUM_ADMENU_FORUMACCESS,
        'show'	=> true,
        'absolute' => false
		),

	array(
		'title'		=> _MI_XCFORUM_ADMENU_ADVANCEDADMIN,
		'link'	=> 'admin/index.php?action=AdvanceEdit',
		'keywords'	=> _MI_XCFORUM_ADMENU_ADVANCEDADMIN,
		'show'	=> true,
		'absolute' => false
	)
##[cubson:adminmenu]
##[/cubson:adminmenu]
	);
//
// Public side control setting
//
$modversion['hasMain'] = 1;
$modversion['hasSearch'] = 0;
$modversion['sub'] = array(
/*
    array(
        'name' => _MI_XCFORUM_LANG_SUB_XXX,
        'url'  => 'index.php?action=XXX'
    ),
*/
##[cubson:submenu]
##[/cubson:submenu]
);

//
// Config setting
//
$modversion['config'] = array(
/*
    array(
        'name'          => 'xxxx',
        'title'         => '_MI_XCFORUM_TITLE_XXXX',
        'description'   => '_MI_XCFORUM_DESC_XXXX',
        'formtype'      => 'xxxx',
        'valuetype'     => 'xxx',
        'options'       => array(xxx => xxx,xxx => xxx),
        'default'       => 0
    ),
*/

	array(
		'name'			=> 'access_controller',
		'title' 		=> '_MI_XCFORUM_LANG_ACCESS_CONTROLLER',
		'description'	=> '_MI_XCFORUM_DESC_ACCESS_CONTROLLER',
		'formtype'		=> 'server_module',
		'valuetype' 	=> 'text',
		'default'		=> '',
		'options'		=> array('none', 'cat', 'group')
	),
	array(
		'name'			=> 'auth_type' ,
		'title' 		=> "_MI_XCFORUM_LANG_AUTH_TYPE" ,
		'description'	=> "_MI_XCFORUM_DESC_AUTH_TYPE" ,
		'formtype'		=> 'textbox' ,
		'valuetype' 	=> 'text' ,
		'default'		=> 'viewer|poster|manager' ,
		'options'		=> array()
	) ,

    array(
        'name'          => 'use_forums_status' ,
        'title'         => '_MI_XCFORUM_LANG_USE_FORUMS_STATUS' ,
        'description'   => '_MI_XCFORUM_DESC_USE_FORUMS_STATUS' ,
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
        'options'       => array()
    ) ,
                    
    array(
        'name'          => 'use_topics_status' ,
        'title'         => '_MI_XCFORUM_LANG_USE_TOPICS_STATUS' ,
        'description'   => '_MI_XCFORUM_DESC_USE_TOPICS_STATUS' ,
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
        'options'       => array()
    ) ,
                    
    array(
        'name'          => 'use_posts_status' ,
        'title'         => '_MI_XCFORUM_LANG_USE_POSTS_STATUS' ,
        'description'   => '_MI_XCFORUM_DESC_USE_POSTS_STATUS' ,
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
        'options'       => array()
    ) ,
                    
    array(
        'name'          => 'use_activity' ,
        'title'         => '_MI_XCFORUM_LANG_USE_ACTIVITY' ,
        'description'   => '_MI_XCFORUM_DESC_USE_ACTIVITY' ,
        'formtype'      => 'yesno',
        'valuetype'     => 'int',
        'default'       => 0,
        'options'       => array()
    ) ,
                    
    array(
        'name'          => 'tag_dirname' ,
        'title'         => '_MI_XCFORUM_LANG_TAG_DIRNAME' ,
        'description'   => '_MI_XCFORUM_DESC_TAG_DIRNAME' ,
        'formtype'      => 'server_module',
        'valuetype'     => 'text',
        'default'       => '',
        'options'       => array('none','tag')
    ) ,
                    
    array(
        'name'          => 'css_file' ,
        'title'         => "_MI_XCFORUM_LANG_CSS_FILE" ,
        'description'   => "_MI_XCFORUM_DESC_CSS_FILE" ,
        'formtype'      => 'textbox' ,
        'valuetype'     => 'text' ,
        'default'       => '/modules/'.$myDirName.'/index.php?sub=main_css',
        'options'       => array()
    ) ,


##[cubson:config]
##[/cubson:config]
);
$constpref = '_MI_XCFORUM';

$modversion['config'][] = array(
	'name'			=> 'top_message' ,
	'title'			=> $constpref.'_TOP_MESSAGE' ,
	'description'	=> '' ,
	'formtype'		=> 'textarea' ,
	'valuetype'		=> 'text' ,
	'default'		=> constant($constpref.'_TOP_MESSAGEDEFAULT') ,
	'options'		=> array()
)  ;

$modversion['config'][] = array(
	'name'			=> 'show_breadcrumbs' ,
	'title'			=> $constpref.'_SHOW_BREADCRUMBS' ,
	'description'	=> '' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 1 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'default_options' ,
	'title'			=> $constpref.'_DEFAULT_OPTIONS' ,
	'description'	=> $constpref.'_DEFAULT_OPTIONSDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'text' ,
	'default'		=> 'smiley,xcode,br,number_entity' ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'use_name' ,
	'title'			=> $constpref.'_USENAME' ,
	'description'		=> $constpref.'_USENAMEDESC' ,
	'formtype'		=> 'select',
	'valuetype'		=> 'int',
	'default'		=> '0',
	'options'		=> array( $constpref.'_USENAME_UNAME' => 0, $constpref.'_USENAME_NAME' => 1)
);

$modversion['config'][] = array(
	'name'			=> 'allow_html' ,
	'title'			=> $constpref.'_ALLOW_HTML' ,
	'description'	=> $constpref.'_ALLOW_HTMLDSC' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'allow_textimg' ,
	'title'			=> $constpref.'_ALLOW_TEXTIMG' ,
	'description'	=> $constpref.'_ALLOW_TEXTIMGDSC' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'allow_sig' ,
	'title'			=> $constpref.'_ALLOW_SIG' ,
	'description'	=> $constpref.'_ALLOW_SIGDSC' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 1 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'allow_sigimg' ,
	'title'			=> $constpref.'_ALLOW_SIGIMG' ,
	'description'	=> $constpref.'_ALLOW_SIGIMGDSC' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'posts_per_topic' ,
	'title'			=> $constpref.'_POSTS_PER_TOPIC' ,
	'description'	=> $constpref.'_POSTS_PER_TOPICDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 50 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'hot_threshold' ,
	'title'			=> $constpref.'_HOT_THRESHOLD' ,
	'description'	=> $constpref.'_HOT_THRESHOLDDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 10 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'topics_per_page' ,
	'title'			=> $constpref.'_TOPICS_PER_PAGE' ,
	'description'	=> $constpref.'_TOPICS_PER_PAGEDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 20 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'use_vote' ,
	'title'			=> $constpref.'_USE_VOTE' ,
	'description'	=> '' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 1 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'use_solved' ,
	'title'			=> $constpref.'_USE_SOLVED' ,
	'description'	=> '' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'allow_mark' ,
	'title'			=> $constpref.'_ALLOW_MARK' ,
	'description'	=> '' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'allow_hideuid' ,
	'title'			=> $constpref.'_ALLOW_HIDEUID' ,
	'description'	=> '' ,
	'formtype'		=> 'yesno' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'viewallbreak' ,
	'title'			=> $constpref.'_VIEWALLBREAK' ,
	'description'	=> $constpref.'_VIEWALLBREAKDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 10 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'selfeditlimit' ,
	'title'			=> $constpref.'_SELFEDITLIMIT' ,
	'description'	=> $constpref.'_SELFEDITLIMITDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 31536000 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'selfdellimit' ,
	'title'			=> $constpref.'_SELFDELLIMIT' ,
	'description'	=> $constpref.'_SELFDELLIMITDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 0 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'css_uri' ,
	'title'			=> $constpref.'_CSS_URI' ,
	'description'	=> $constpref.'_CSS_URIDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'text' ,
	'default'		=> '{mod_url}/index.php?page=main_css' ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'images_dir' ,
	'title'			=> $constpref.'_IMAGES_DIR' ,
	'description'	=> $constpref.'_IMAGES_DIRDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'text' ,
	'default'		=> 'images' ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'body_editor' ,
	'title'			=> $constpref.'_BODY_EDITOR' ,
	'description'	=> $constpref.'_BODY_EDITORDSC' ,
	'formtype'		=> 'select' ,
	'valuetype'		=> 'text' ,
	'default'		=> 'xoopsdhtml' ,
	'options'		=> array( 'xoopsdhtml' => 'xoopsdhtml' /*, 'common/spaw' => 'common_spaw' */, 'common/fckeditor' => 'common_fckeditor' )
) ;

$modversion['config'][] = array(
	'name'			=> 'anonymous_name' ,
	'title'			=> $constpref.'_ANONYMOUS_NAME' ,
	'description'	=> $constpref.'_ANONYMOUS_NAMEDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'text' ,
	'default'		=> _GUESTS ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'icon_meanings' ,
	'title'			=> $constpref.'_ICON_MEANINGS' ,
	'description'	=> $constpref.'_ICON_MEANINGSDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'text' ,
	'default'		=> constant( $constpref.'_ICON_MEANINGSDEF' ) ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'guest_vote_interval' ,
	'title'			=> $constpref.'_GUESTVOTE_IVL' ,
	'description'	=> $constpref.'_GUESTVOTE_IVLDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'int' ,
	'default'		=> 86400 ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'antispam_groups' ,
	'title'			=> $constpref.'_ANTISPAM_GROUPS' ,
	'description'	=> $constpref.'_ANTISPAM_GROUPSDSC' ,
	'formtype'		=> 'group_multi' ,
	'valuetype'		=> 'array' ,
	'default'		=> array(3) ,
	'options'		=> array()
) ;

$modversion['config'][] = array(
	'name'			=> 'antispam_class' ,
	'title'			=> $constpref.'_ANTISPAM_CLASS' ,
	'description'	=> $constpref.'_ANTISPAM_CLASSDSC' ,
	'formtype'		=> 'textbox' ,
	'valuetype'		=> 'text' ,
	'default'		=> 'defaultmobilesmart' ,
	'options'		=> array()
) ;


//
// Block setting
//
$modversion['blocks'] = array(
	1 => array(
		'func_num'          => 1,
		'file'              => 'TopicBlock.class.php',
		'class'             => 'TopicBlock',
		'name'              => _MI_XCFORUM_BLOCK_NAME_TOPIC,
		'description'       => _MI_XCFORUM_BLOCK_DESC_TOPIC,
		'options'           => '5|',
		'template'          => '{dirname}_block_list_topics.html',
		'show_all_module'   => true,
		'can_clone'         => true,
		'visible_any'       => false
	),
/*
	x => array(
		'func_num'			=> x,
		'file'				=> 'xxxBlock.class.php',
		'class' 			=> 'xxx',
		'name'				=> _MI_LEFORUM_BLOCK_NAME_xxx,
		'description'		=> _MI_LEFORUM_BLOCK_DESC_xxx,
		'options'			=> '',
		'template'			=> '{dirname}_block_xxx.html',
		'show_all_module'	=> true,
		'visible_any'		=> true
	),
*/
##[cubson:block]
##[/cubson:block]
);


// Notification
$modversion['hasNotification'] = 1;
$modversion['notification'] = array(
	'lookup_file' => 'notification.php' ,
	'lookup_func' => "{$myDirName}_notify_iteminfo" ,
	'category' => array(
		array(
			'name' => 'topic' ,
			'title' => constant($constpref.'_NOTCAT_TOPIC') ,
			'description' => constant($constpref.'_NOTCAT_TOPICDSC') ,
			'subscribe_from' => 'index.php' ,
			'item_name' => 'topic_id' ,
			'allow_bookmark' => 1 ,
		) ,
		array(
			'name' => 'forum' ,
			'title' => constant($constpref.'_NOTCAT_FORUM') ,
			'description' => constant($constpref.'_NOTCAT_FORUMDSC') ,
			'subscribe_from' => 'index.php' ,
			'item_name' => 'forum_id' ,
			'allow_bookmark' => 1 ,
		) ,
		array(
			'name' => 'category' ,
			'title' => constant($constpref.'_NOTCAT_CAT') ,
			'description' => constant($constpref.'_NOTCAT_CATDSC') ,
			'subscribe_from' => 'index.php' ,
			'item_name' => 'cat_id' ,
			'allow_bookmark' => 1 ,
		) ,
		array(
			'name' => 'global' ,
			'title' => constant($constpref.'_NOTCAT_GLOBAL') ,
			'description' => constant($constpref.'_NOTCAT_GLOBALDSC') ,
			'subscribe_from' => 'index.php' ,
		) ,
	) ,
	'event' => array(
		array(
			'name' => 'newpost' ,
			'category' => 'topic' ,
			'title' => constant($constpref.'_NOTIFY_TOPIC_NEWPOST') ,
			'caption' => constant($constpref.'_NOTIFY_TOPIC_NEWPOSTCAP') ,
			'description' => constant($constpref.'_NOTIFY_TOPIC_NEWPOSTCAP') ,
			'mail_template' => 'topic_newpost' ,
			'mail_subject' => constant($constpref.'_NOTIFY_TOPIC_NEWPOSTSBJ') ,
		) ,
		array(
			'name' => 'newpost' ,
			'category' => 'forum' ,
			'title' => constant($constpref.'_NOTIFY_FORUM_NEWPOST') ,
			'caption' => constant($constpref.'_NOTIFY_FORUM_NEWPOSTCAP') ,
			'description' => constant($constpref.'_NOTIFY_FORUM_NEWPOSTCAP') ,
			'mail_template' => 'forum_newpost' ,
			'mail_subject' => constant($constpref.'_NOTIFY_FORUM_NEWPOSTSBJ') ,
		) ,
		array(
			'name' => 'newtopic' ,
			'category' => 'forum' ,
			'title' => constant($constpref.'_NOTIFY_FORUM_NEWTOPIC') ,
			'caption' => constant($constpref.'_NOTIFY_FORUM_NEWTOPICCAP') ,
			'description' => constant($constpref.'_NOTIFY_FORUM_NEWTOPICCAP') ,
			'mail_template' => 'forum_newtopic' ,
			'mail_subject' => constant($constpref.'_NOTIFY_FORUM_NEWTOPICSBJ') ,
		) ,
		array(
			'name' => 'newpost' ,
			'category' => 'category' ,
			'title' => constant($constpref.'_NOTIFY_CAT_NEWPOST') ,
			'caption' => constant($constpref.'_NOTIFY_CAT_NEWPOSTCAP') ,
			'description' => constant($constpref.'_NOTIFY_CAT_NEWPOSTCAP') ,
			'mail_template' => 'category_newpost' ,
			'mail_subject' => constant($constpref.'_NOTIFY_CAT_NEWPOSTSBJ') ,
		) ,
		array(
			'name' => 'newtopic' ,
			'category' => 'category' ,
			'title' => constant($constpref.'_NOTIFY_CAT_NEWTOPIC') ,
			'caption' => constant($constpref.'_NOTIFY_CAT_NEWTOPICCAP') ,
			'description' => constant($constpref.'_NOTIFY_CAT_NEWTOPICCAP') ,
			'mail_template' => 'category_newtopic' ,
			'mail_subject' => constant($constpref.'_NOTIFY_CAT_NEWTOPICSBJ') ,
		) ,
		array(
			'name' => 'newforum' ,
			'category' => 'category' ,
			'title' => constant($constpref.'_NOTIFY_CAT_NEWFORUM') ,
			'caption' => constant($constpref.'_NOTIFY_CAT_NEWFORUMCAP') ,
			'description' => constant($constpref.'_NOTIFY_CAT_NEWFORUMCAP') ,
			'mail_template' => 'category_newforum' ,
			'mail_subject' => constant($constpref.'_NOTIFY_CAT_NEWFORUMSBJ') ,
		) ,
		array(
			'name' => 'newpost' ,
			'category' => 'global' ,
			'title' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOST') ,
			'caption' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTCAP') ,
			'description' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTCAP') ,
			'mail_template' => 'global_newpost' ,
			'mail_subject' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTSBJ') ,
		) ,
		array(
			'name' => 'newtopic' ,
			'category' => 'global' ,
			'title' => constant($constpref.'_NOTIFY_GLOBAL_NEWTOPIC') ,
			'caption' => constant($constpref.'_NOTIFY_GLOBAL_NEWTOPICCAP') ,
			'description' => constant($constpref.'_NOTIFY_GLOBAL_NEWTOPICCAP') ,
			'mail_template' => 'global_newtopic' ,
			'mail_subject' => constant($constpref.'_NOTIFY_GLOBAL_NEWTOPICSBJ') ,
		) ,
		array(
			'name' => 'newforum' ,
			'category' => 'global' ,
			'title' => constant($constpref.'_NOTIFY_GLOBAL_NEWFORUM') ,
			'caption' => constant($constpref.'_NOTIFY_GLOBAL_NEWFORUMCAP') ,
			'description' => constant($constpref.'_NOTIFY_GLOBAL_NEWFORUMCAP') ,
			'mail_template' => 'global_newforum' ,
			'mail_subject' => constant($constpref.'_NOTIFY_GLOBAL_NEWFORUMSBJ') ,
		) ,
		array(
			'name' => 'newpostfull' ,
			'category' => 'global' ,
			'title' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULL') ,
			'caption' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULLCAP') ,
			'description' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULLCAP') ,
			'mail_template' => 'global_newpostfull' ,
			'mail_subject' => constant($constpref.'_NOTIFY_GLOBAL_NEWPOSTFULLSBJ') ,
		) ,
		array(
			'name' => 'waiting' ,
			'category' => 'global' ,
			'title' => constant($constpref.'_NOTIFY_GLOBAL_WAITING') ,
			'caption' => constant($constpref.'_NOTIFY_GLOBAL_WAITINGCAP') ,
			'description' => constant($constpref.'_NOTIFY_GLOBAL_WAITINGCAP') ,
			'mail_template' => 'global_waiting' ,
			'mail_subject' => constant($constpref.'_NOTIFY_GLOBAL_WAITINGSBJ') ,
			'admin_only' => 1 ,
		)
	)
) ;
//adump($modversion['notification']);

?>
