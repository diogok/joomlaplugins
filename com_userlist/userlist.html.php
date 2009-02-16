<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function deleteLink($obj) {
    $link  = "<a href='".JRoute::_('index.php?option=com_userlist&id_to_delete='.$obj->id)."' onclick='return confirmDel()'>Delete</a>";
    return $link;
}

class HTML_userlist_content {

	function showlist($rows, $total_results, $pageNav, $limitstart, $query_ext, $search='', $settings, $base_url) {
		global $mosConfig_sitename, $userlist_version;

        $nUser = JFactory::getUser();
        if ($nUser->name == "Administrator") {
            $allowDelete = true;
        }

        if ($search=='') {
			$search = _USRL_SEARCH;
		}
?>
<!-- Userlist Component by Emir Sakic, http://www.sakic.net -->
<script type="text/javascript">
<!--
function validate(){
	if ((document.usrlform.search=="") || (document.usrlform.search.value=="")) {
		alert('<?php echo _USRL_SEARCH_ALERT; ?>');
		return false;
	} else {
		return true;
	}
}
function confirmDel() {
    return confirm("Are you sure to delete this user?");
}
//-->
</script>
  <table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane" style="width: 100%">
  	<tr>
  	  <td colspan="2"><span class="contentheading"><?php echo _USRL_USERLIST; ?></span></td>
  	</tr>
    <tr>
      <td valign="top" class="contentdescription">
        <form name="usrlform" method="post" action="<?php echo sefRelToAbs("$base_url"); ?>" onsubmit="return validate()">
         <?php printf(_USRL_REGISTERED_USERS, $mosConfig_sitename, $total_results); ?><br />
         <br />
         <input type="text" name="search" class="inputbox" style="width:150px" maxlength="100" value="<?php echo $search; ?>"  onblur="if(this.value=='') this.value='<?php echo $search; ?>';" onfocus="if(this.value=='<?php echo $search; ?>') this.value='';" />
         <input type="image" src="components/com_userlist/images/search_icon.gif" alt="<?php echo _USRL_SEARCH; ?>" align="top" style="border: 0px;" />
        </form>
      </td>
    </tr>
    <tr>
      <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
    		<td><a href="<?php echo sefRelToAbs("$base_url"); ?>"><?php echo _USRL_LIST_ALL; ?></a></td>
    		<td align="right" style="text-align:right;"><?php echo _PN_DISPLAY_NR; ?> <?php echo $pageNav->writeLimitBox("$base_url$query_ext"); ?></td>
          </tr>
        </table>
        <hr noshade="noshade" size="1" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="20" align="center" class="sectiontableheader">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php if ($settings->name) { ?>
            <td height="20" class="sectiontableheader"><?php echo _USRL_NAME; ?> <a href="<?php echo sefRelToAbs("$base_url&orderby=name"); ?>"><img src="components/com_userlist/images/down.gif" width="8" height="5" border="0" alt="<?php echo _USRL_ASC; ?>" /></a> <a href="<?php echo sefRelToAbs("$base_url&orderby=name&direction=DESC"); ?>"><img src="components/com_userlist/images/up.gif" width="8" height="5" border="0" alt="<?php echo _USRL_DESC; ?>" /></a></td>
<?php } ?>
<?php if ($settings->username) { ?>
            <td height="20" class="sectiontableheader"><?php echo _USRL_USERNAME; ?> <a href="<?php echo sefRelToAbs("$base_url&orderby=username"); ?>"><img src="components/com_userlist/images/down.gif" width="8" height="5" border="0" alt="<?php echo _USRL_ASC; ?>" /></a> <a href="<?php echo sefRelToAbs("$base_url&orderby=username&direction=DESC"); ?>"><img src="components/com_userlist/images/up.gif" width="8" height="5" border="0" alt="<?php echo _USRL_DESC; ?>" /></a></td>
<?php } ?>
<?php if ($settings->email) { ?>
            <td height="20" class="sectiontableheader"><?php echo _USRL_EMAIL; ?> <a href="<?php echo sefRelToAbs("$base_url&orderby=email"); ?>"><img src="components/com_userlist/images/down.gif" width="8" height="5" border="0" alt="<?php echo _USRL_ASC; ?>" /></a> <a href="<?php echo sefRelToAbs("$base_url&orderby=email&direction=DESC"); ?>"><img src="components/com_userlist/images/up.gif" width="8" height="5" border="0" alt="<?php echo _USRL_DESC; ?>" /></a></td>
<?php } ?>
<?php if ($settings->usertype) { ?>
            <td height="20" class="sectiontableheader"><?php echo _USRL_USERTYPE; ?> <a href="<?php echo sefRelToAbs("$base_url&orderby=usertype"); ?>"><img src="components/com_userlist/images/down.gif" width="8" height="5" border="0" alt="<?php echo _USRL_ASC; ?>" /></a> <a href="<?php echo sefRelToAbs("$base_url&orderby=usertype&direction=DESC"); ?>"><img src="components/com_userlist/images/up.gif" width="8" height="5" border="0" alt="<?php echo _USRL_DESC; ?>" /></a></td>
<?php } ?>
<?php if ($settings->joindate) { ?>
            <td height="20" class="sectiontableheader"><?php echo _USRL_JOIN_DATE; ?> <a href="<?php echo sefRelToAbs("$base_url&orderby=registerDate"); ?>"><img src="components/com_userlist/images/down.gif" width="8" height="5" border="0" alt="<?php echo _USRL_ASC; ?>" /></a> <a href="<?php echo sefRelToAbs("$base_url&orderby=registerDate&direction=DESC"); ?>"><img src="components/com_userlist/images/up.gif" width="8" height="5" border="0" alt="<?php echo _USRL_DESC; ?>" /></a></td>
<?php } ?>
<?php if ($settings->lastvisitdate) { ?>
            <td height="20" class="sectiontableheader"><?php echo _USRL_LAST_LOGIN; ?> <a href="<?php echo sefRelToAbs("$base_url&orderby=lastvisitDate"); ?>"><img src="components/com_userlist/images/down.gif" width="8" height="5" border="0" alt="<?php echo _USRL_ASC; ?>" /></a> <a href="<?php echo sefRelToAbs("$base_url&orderby=lastvisitDate&direction=DESC"); ?>"><img src="components/com_userlist/images/up.gif" width="8" height="5" border="0" alt="<?php echo _USRL_DESC; ?>" /></a></td>
<?php } ?>
    <? if($allowDelete){?>
        <td height="20" class="sectiontableheader">Delete</td>
    <? }?>
          </tr>
<?php
	$i = 1;
	foreach($rows as $row) {
		$evenodd = $i % 2;
		if ($evenodd == 0) {
			$usrl_class = "sectiontableentry1";
		} else {
			$usrl_class = "sectiontableentry2";
		}
		$nr=$i+$limitstart;
		echo "\t<tr class=\"$usrl_class\">\n";
		echo "\t\t<td align=\"center\">$nr</td>\n";
		if ($settings->name) {
			echo "\t\t<td>$row->name</td>\n";
		}
		if ($settings->username) {
			echo "\t\t<td>$row->username</td>\n";
		}
		if ($settings->email) {
			echo "\t\t<td><a href=\"mailto:$row->email\">$row->email</a></td>\n";
		}
		if ($settings->usertype) {
			echo "\t\t<td>$row->usertype</td>\n";
		}
		if ($settings->joindate) {
			echo "\t\t<td>".convertDate($row->registerDate)."</td>\n";
		}
		if ($settings->lastvisitdate) {
			echo "\t\t<td>".convertDate($row->lastvisitDate)."</td>\n";
		}
        if($allowDelete) {
            echo "\t\t<td>".deleteLink($row)."</td>\n";
        }
        echo "\t</tr>\n";
        $i++;
	}
?>
        <tr>
          <td width="100%" height="20" class="sectiontableheader" colspan="9" align="center" style="text-align:center;"><?php echo $pageNav->writePagesLinks("$base_url$query_ext"); ?></td>
        </tr>
        </table>
        <hr noshade="noshade" size="1" />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td nowrap="nowrap"><font size="1">Userlist <?php echo $userlist_version; ?> by <a href="http://www.sakic.net" target="_blank">Sakic.Net</a></font></td>
            <td align="right" style="text-align:right;"><?php echo $pageNav->writePagesCounter(); ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<!-- Userlist Component by Emir Sakic, http://www.sakic.net -->
<?php
	}
}
?>
