<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class menuJobs {
	function SAVE_CANCEL_MENU() {
		mosMenuBar::startTable();

		mosMenuBar::save("save");

		mosMenuBar::cancel();

		mosMenuBar::spacer();

		mosMenuBar::endTable();
	}

	function INFO_MENU() {
		mosMenuBar::startTable();

		mosMenuBar::back();

		mosMenuBar::spacer();

		mosMenuBar::endTable();
	}
}
?>