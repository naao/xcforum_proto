<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
 **/

/**
 * Xcforum_Antispam_Abstrac
 **/
class Xcforum_Antispam_Abstract {

public $errors = array() ;

public function getErrors4Html()
	{
	$ret = '' ;
	foreach( $this->errors as $error ) {
		$ret .= '<span style="color:#f00;">'.htmlspecialchars($error).'</span><br />' ;
	}

	return $ret ;
}

public function getHtml4Assign()
{
	return array(
		'html_in_form' => '' ,
		'js_global' => '' ,
		'js_in_validate_function' => '' ,
	) ;
}

public function checkValidate()
{
	return true ;
}

public function isMobile()
{
	if( class_exists( 'Wizin_User' ) ) {
		// WizMobile (gusagi)
		$user =& Wizin_User::getSingleton();
		return $user->bIsMobile ;
	} else if( defined( 'HYP_K_TAI_RENDER' ) && HYP_K_TAI_RENDER ) {
		// hyp_common ktai-renderer (nao-pon)
		return true ;
	} else {
		return false ;
	}
}

}

?>