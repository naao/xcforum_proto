<?php
/**
 * @file
 * @package xcforum
 * @version $Id$
 **/

require_once dirname(__FILE__).'/Default.class.php' ;

/**
 * Xcforum_Antispam_Defaultmobile
 **/
class Xcforum_Antispam_Defaultmobile extends Xcforum_Antispam_Default {

public function checkValidate()
{
	if( $this->isMobile() ) {
		return true ;
	} else {
		return parent::checkValidate() ;
	}
}

}

?>