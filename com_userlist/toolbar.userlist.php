<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );
require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ($act) {
	case "info":
		menuJobs::INFO_MENU();
		break;
	default:
		menuJobs::SAVE_CANCEL_MENU();
		break;
}
?>