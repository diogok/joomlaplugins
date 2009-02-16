<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'admin_html' ) );

/**
* Userlist Table Class
*
* Provides access to the table
*/
class mosUserlistConfig extends mosDBTable {
	/** @var int Unique id*/
	var $id=1;
	/** @var int */
	var $rows=null;
	/** @var int */
	var $name=null;
	/** @var int */
	var $username=null;
	/** @var int */
	var $email=null;
	/** @var int */
	var $usertype=null;
	/** @var int */
	var $joindate=null;
	/** @var int */
	var $lastvisitdate=null;

	/**
	* @param database A database connector object
	*/
	function mosUserlistConfig( $database ) {
		$this->mosDBTable( '#__usrl_config', 'id', $database );
	}
}

$option = mosGetParam( $_REQUEST, 'option', null );
$act = mosGetParam( $_REQUEST, 'act', null );
$task = mosGetParam( $_REQUEST, 'task', null );

switch($act) {
	case "lang":
		switch ($task) {
			case "save":
				saveLang( $language, $content, $option, $act );
				break;
			default:
				editLang( $option, $act );
			break;
		}
		break;
	case "info":
		HTML_class::showInfo("components/com_userlist/readme.txt");
		break;
	default:
		switch ($task) {
			case "save":
				saveConfig( $option );
				break;
			default:
				editConfig( $option );
			break;
		}
		break;
}

HTML_class::showCopyright();

// Functions

function editConfig($option) {
	global $database, $mosConfig_lang;

	$database->setQuery( "SELECT * FROM #__usrl_config LIMIT 1" );
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$row =& $rows[0];

	if (file_exists("../components/com_userlist/lang/$mosConfig_lang.php")) {
		$language = $mosConfig_lang;
	} else {
		$language = "english";
	}

	$lists = array();

	// build list
	$lists['lang'] = $language;

	// make a generic 5 - 50 list
	for ($i=5;$i<=50;$i=$i+5) {
		$rows_per_page[] = mosHTML::makeOption( $i, $i );
	}
	$lists['rows'] = mosHTML::selectList( $rows_per_page, 'rows', 'class="inputbox" size="1"',	'value', 'text', $row->rows );


	$lists['name'] = mosHTML::yesnoSelectList( 'name', 'class="inputbox"', $row->name );
	$lists['username'] = mosHTML::yesnoSelectList( 'username', 'class="inputbox"', $row->username );
	$lists['email'] = mosHTML::yesnoSelectList( 'email', 'class="inputbox"', $row->email );
	$lists['usertype'] = mosHTML::yesnoSelectList( 'usertype', 'class="inputbox"', $row->usertype );
	$lists['joindate'] = mosHTML::yesnoSelectList( 'joindate', 'class="inputbox"', $row->joindate );
	$lists['lastvisitdate'] = mosHTML::yesnoSelectList( 'lastvisitdate', 'class="inputbox"', $row->lastvisitdate );

	HTML_class::editConfig( $row, $lists, $option );
}

function saveConfig($option) {
	global $database;

	$row = new mosUserlistConfig( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	//echo '<pre>'; print_r($row);echo '</pre>';die;

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	mosRedirect( "index2.php?option=$option" );
}

function editLang( $option, $act ) {
	global $database, $mosConfig_lang;

	if (file_exists("../components/com_userlist/lang/$mosConfig_lang.php")) {
		$language = $mosConfig_lang;
	} else {
		$language = "english";
	}

	$file = "../components/com_userlist/lang/$language.php";
	$file = stripslashes($file);
	$f=fopen($file,"r");
	$content = fread($f, filesize($file));
	$content = htmlspecialchars($content);

	HTML_class::editLang( $language, $content, $option, $act );
}

function saveLang( $language, $content, $option, $act ) {
	$file = "../components/com_userlist/lang/$language.php";
	if (is_writable($file)==false){
		echo "<script>alert('Language file is not writable')</script>";
		mosRedirect( "index2.php?option=$option&act=$act" );
	}

	$fp = fopen ($file, "w");
	if ($fp==false){
		echo "<script>alert('Language file could not be opened')</script>";
		mosRedirect( "index2.php?option=$option&act=$act" );
	}

	fputs($fp,stripslashes($content));
	fclose($fp);

	mosRedirect( "index2.php?option=$option&act=$act" );
}

?>