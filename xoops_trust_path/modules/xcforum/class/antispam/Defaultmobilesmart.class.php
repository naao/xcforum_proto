<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
 **/

require_once dirname(__FILE__).'/Default.class.php' ;

/**
 * Xcforum_Antispam_Defaultmobilesmart
 **/
class Xcforum_Antispam_Defaultmobilesmart extends Xcforum_Antispam_Default {

public function checkValidate()
{
	if( $this->isMobile() ) {
		return true ;
	} else {
		return parent::checkValidate() ;
	}
}

public function isMobile()
{
	if( class_exists( 'Wizin_User' ) ) {
		// WizMobile (gusagi)
		$user =& Wizin_User::getSingleton();
		return $user->bIsMobile ;
	} else if( defined( 'HYP_K_TAI_RENDER' ) && HYP_K_TAI_RENDER && HYP_K_TAI_RENDER != 2 ) {
		// hyp_common ktai-renderer (nao-pon)
		return true ;
	} else {
		return false ;
	}
}

}

?>