<?php
// Description : Userlist
// Created : 23-May-2004, Emir Sakic, http://www.sakic.net
// Version : 2.5
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$userlist_version = '2.5';					// script version

require_once( $mainframe->getPath( 'front_html' ) );

// For delete
$nUser = JFactory::getUser();
if ($nUser->name == "Administrator") {
    $allowDelete = true;
}

$iDel = JRequest::getVar("id_to_delete");
if($allowDelete and isset($iDel)) {
    $dUser = JFactory::getUser($iDel);
    $name = $dUser->name ;
    if($dUser->name == "Administrator") {
        echo "<p class='err'>You cannot delete Administrator.</p>";
    } else if(strlen($name) >= 2) {
        if($dUser->delete()) {
            echo "<p class='msg'>User ".$name." deleted with success.</p>";
        } else {
            echo "<p class='err'>Error deleting ".$name.".</p>";
        }
    } else {
        echo "<p class='err'>Error deleting ".$name.".</p>";
    }
}

// Find out $Itemid
$database->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_userlist'");
$base_url = "index.php?option=com_userlist&Itemid=" . $database->loadResult();	// Base URL string

list_users($base_url);

function list_users($base_url) {
	global $database, $mosConfig_lang;
	// Load settings
	$database->setQuery( "SELECT * FROM #__usrl_config LIMIT 1" );
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$settings =& $rows[0];

	if (file_exists("components/com_userlist/lang/$mosConfig_lang.php")) {
		include_once("components/com_userlist/lang/$mosConfig_lang.php");
	} else {
		include_once("components/com_userlist/lang/english.php");
	}

	if (!defined('_PN_DISPLAY_NR')) {
		define('_PN_DISPLAY_NR', 'Display #');
	}

	require_once("includes/pageNavigation.php");
	$orderby = mosGetParam( $_REQUEST, 'orderby', 'registerDate');
	$direction = mosGetParam( $_REQUEST, 'direction', 'ASC');
	$search = mosGetParam( $_REQUEST, 'search', '');
	$limitstart = mosGetParam( $_REQUEST, 'limitstart', 0 );
	$limit = mosGetParam( $_REQUEST, 'limit', $settings->rows );
	// Total
	$database->setQuery("SELECT count(id) FROM #__users");
	$total_results = $database->loadResult();
	// Search total
	$query = "SELECT count(id) FROM #__users";
	if ($search != "") {
		$query .= " WHERE (name LIKE '%$search%' OR username LIKE '%$search%')";
	}
	$database->setQuery($query);
	$total = $database->loadResult();
	if ($limit > $total) {
		$limitstart = 0;
	}
	$query_ext = "";
	// Select query
	if (!$settings->usertype) {	
        // faster query	
        $query = "SELECT name, username, email, usertype, registerDate, lastvisitDate FROM #__users AS u";
	} else if (class_exists('JFactory')) {	
        // Joomla! 1.5
		$query = "SELECT u.id as id, u.name AS name, u.username AS username, u.email AS email, u.registerDate AS registerDate, u.lastvisitDate AS lastvisitDate, g.name AS usertype"
			. "\nFROM #__users AS u"
			. "\nINNER JOIN #__core_acl_aro AS aro ON aro.value = u.id"	// map user to aro
			. "\nINNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.id"	// map aro to group
			. "\nINNER JOIN #__core_acl_aro_groups AS g ON g.id = gm.group_id";
	} else {		$query = "SELECT u.name AS name, u.username AS username, u.email AS email, u.registerDate AS registerDate, u.lastvisitDate AS lastvisitDate, g.name AS usertype"			. "\nFROM #__users AS u"			. "\nINNER JOIN #__core_acl_aro AS aro ON aro.value = u.id"	
        // map user to aro		
        . "\nINNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.aro_id"
            // map aro to group		
            ."\nINNER JOIN #__core_acl_aro_groups AS g ON g.group_id = gm.group_id";
	}
	if ($search != "") {		$query .= " WHERE (u.name LIKE '%$search%' OR u.username LIKE '%$search%')";		$query_ext .= "&amp;search=".$search;	}	$query .= " ORDER BY '$orderby' $direction, u.id $direction";	if ($orderby != "id") {		$query_ext .= "&amp;orderby=".$orderby."&amp;direction=".$direction;	}	$query .= " LIMIT $limitstart, $limit";

	$database->setQuery($query);	$rows = $database->loadObjectList();
	//echo str_replace('#_', 'jos', $query)."<pre>"; print_r($rows); die;
	$pageNav = new mosPageNav($total, $limitstart, $limit);
	HTML_userlist_content::showlist($rows, $total_results, $pageNav, $limitstart, $query_ext, $search, $settings, $base_url);}
function convertDate($date) {	global $mosConfig_offset;
	$format = _USRL_DATE_FORMAT;	if ( $date != "0000-00-00 00:00:00" && ereg("([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})", $date, $regs ) ) {		$date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );		$date = $date > -1 ? strftime( $format, $date + ($mosConfig_offset*60*60) ) : '-';	} else {		$date = _USRL_NEVER;	}
	return $date;}
?>
