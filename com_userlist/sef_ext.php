<?php
/**
* SEF advance component extension
*
* This extension will give the SEF advance style URLs to the userlist component
* Place this file (sef_ext.php) in the main component directory
* Note that the class must be named: sef_componentname
*
* Copyright (C) 2003-2004 Emir Sakic, http://www.sakic.net, All rights reserved.
*
* Comments: for SEF advance > v3.6
**/

class sef_userlist {

	/**
	* Creates the SEF advance URL out of the request
	* Input: $string, string, The request URL (index.php?option=com_userlist&Itemid=$Itemid)
	* Output: $sefstring, string, SEF advance URL (userlist/$orderby/$direction/$limit/$limitstart/)
	**/
	function create ($string) {
 		// $string == "index.php?option=com_userlist&Itemid=$Itemid&orderby=$orderby&direction=$direction&limit=$limit&limitstart=$limitstart"
		$sefstring = "";
		if (eregi("&amp;orderby=",$string)) {
			$temp = split("&amp;orderby=", $string);
			$temp = split("&", $temp[1]);
			$sefstring .= $temp[0]."/";
		}
		if (eregi("&amp;direction=",$string)) {
			$temp = split("&amp;direction=", $string);
			$temp = split("&", $temp[1]);
			$sefstring .= $temp[0]."/";
		}
		if (eregi("&amp;limit=",$string)) {
			$temp = split("&amp;limit=", $string);
			$temp = split("&", $temp[1]);
			$sefstring .= $temp[0]."/";
		}
		if (eregi("&amp;limitstart=",$string)) {
			$temp = split("&amp;limitstart=", $string);
			$temp = split("&", $temp[1]);
			$sefstring .= $temp[0]."/";
		}
 		// $sefstring == "$orderby/$direction/$limit/$limitstart/"
		return $sefstring;
	}

 	/**
	* Reverts to the query string out of the SEF advance URL
	* Input:
	*    $url_array, array, The SEF advance URL split in arrays
	*    $pos, int, The position offset for virtual directories (first virtual directory, which is the component name, begins at $pos+1)
	* Output: $QUERY_STRING, string, query string (orderby=$orderby&direction=$direction&limit=$limit&limitstart=$limitstart)
	*    Note that this will be added to already defined first part (option=com_userlist&Itemid=$Itemid)
	**/
	function revert ($url_array, $pos) {
		if (( ini_get('register_globals')==1 && (!defined('RG_EMULATION') || RG_EMULATION==1) ) ||
			( ini_get('register_globals')==0 && (defined('RG_EMULATION') && RG_EMULATION==1) ) ) {
			// if register globals on, emulation on OR register globals off, emulation on
			// then define all variables you pass as globals
			global $orderby, $direction, $limit, $limitstart;
		}
 		// Examine the SEF advance URL and extract the variables building the query string
		$QUERY_STRING = "";
		if (isset($url_array[$pos+2]) && $url_array[$pos+2]!="") {
			// .../userlist/$orderby/
			$orderby = $url_array[$pos+2];
			$_GET['orderby'] = $orderby;
			$_REQUEST['orderby'] = $orderby;
			$QUERY_STRING .= "&orderby=$orderby";
		}
		if (isset($url_array[$pos+3]) && $url_array[$pos+3]!="") {
			// .../userlist/$orderby/$direction/
			$direction = $url_array[$pos+3];
			$_GET['direction'] = $direction;
			$_REQUEST['direction'] = $direction;
			$QUERY_STRING .= "&direction=$direction";
		}
		if (isset($url_array[$pos+4]) && $url_array[$pos+4]!="") {
			// .../userlist/$orderby/$direction/$limit/
			$limit = $url_array[$pos+4];
			$_GET['limit'] = $limit;
			$_REQUEST['limit'] = $limit;
			$QUERY_STRING .= "&limit=$limit";
		}
		if (isset($url_array[$pos+5]) && $url_array[$pos+5]!="") {
			// .../userlist/$orderby/$direction/$limit/$limitstart/
			$limitstart = $url_array[$pos+5];
			$_GET['limitstart'] = $limitstart;
			$_REQUEST['limitstart'] = $limitstart;
			$QUERY_STRING .= "&limitstart=$limitstart";
		}
		// $QUERY_STRING == "orderby=$orderby&direction=$direction&limit=$limit&limitstart=$limitstart";
		return $QUERY_STRING;
	}

}
?>