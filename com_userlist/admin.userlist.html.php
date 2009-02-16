<?php
class HTML_class {

	function editConfig( &$row, &$lists, $option ) {
		global $mosConfig_absolute_path;
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="100%" class="sectionname">Userlist
    	Configuration</td>
  </tr>
</table>
<?php
	if (file_exists("js/dhtml.js")) {
		echo "<script language=\"javascript\" src=\"js/dhtml.js\"></script>\n";
	}
?>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
  <tr>
    <td width="" class="tabpadding">&nbsp;</td>
    <td id="tab1" class="offtab" onClick="dhtml.cycleTab(this.id)">Settings</td>
    <td id="tab2" class="offtab" onClick="dhtml.cycleTab(this.id)">Entries</td>
    <td width="90%" class="tabpadding">&nbsp;</td>
  </tr>
</table>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
			if (pressbutton == 'save') {
				//if (confirm ("Are you sure?")) {
					submitform( pressbutton );
				//}
			} else {
				document.location.href = 'index2.php';
			}
	}
</script>
<form action="index2.php" method="POST" name="adminForm">
  <div id="page1" class="pagetext">
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
      <tr>
        <td width="185">Language:</td>
        <td> <?php echo $lists['lang']; ?> </td>
      </tr>
      <tr>
        <td width="185">Rows per page:</td>
        <td> <?php echo $lists['rows']; ?> </td>
      </tr>
    </table>
  </div>
  <div id="page2" class="pagetext">
    <table cellpadding="2" cellspacing="4" border="0" width="100%" class="adminform">
      <tr>
        <td width="185">Show Name:</td>
        <td> <?php echo $lists['name']; ?> </td>
      </tr>
      <tr>
        <td width="185">Show Username:</td>
        <td> <?php echo $lists['username']; ?> </td>
      </tr>
      <tr>
        <td width="185">Show E-mail:</td>
        <td> <?php echo $lists['email']; ?> </td>
      </tr>
      <tr>
        <td width="185">Show Usertype:</td>
        <td> <?php echo $lists['usertype']; ?> </td>
      </tr>
      <tr>
        <td width="185">Show Join Date:</td>
        <td> <?php echo $lists['joindate']; ?> </td>
      </tr>
      <tr>
        <td width="185">Show Last Visit Date:</td>
        <td> <?php echo $lists['lastvisitdate']; ?> </td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="">
</form>
<script language="javascript" type="text/javascript">
	dhtml.cycleTab('tab1');
</script>
<?php }

	function editLang( $language, $content, $option, $act ) {
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr>
<td class="sectionname" width="100%">Userlist Language Editor</td>
</tr>
</table>
<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				form.act.value = "";
				submitform( pressbutton );
				return;
			}
			submitform( pressbutton );
		}
</script>
<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminform">
  <form action="index2.php" method="POST" name="adminForm">
    <tr>
      <th>Path: components/com_userlist/lang/<?php echo $language; ?>.php</th>
    </tr>
    <tr>
      <td><textarea cols="100" rows="20" name="content" class="inputbox"><?php echo $content; ?></textarea></td>
    </tr>
    <tr>
	  <td class="error">Note: Language file must be writable to save changes</td>
    </tr>
  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="language" value="<?php echo $language; ?>">
  <input type="hidden" name="act" value="<?php echo $act; ?>">
  <input type="hidden" name="task" value="">
  </form>
</table>
<?php }

	function showInfo($file) { ?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="adminform">
	<tr>
	  <td>
	  <pre><?php include ($file); ?></pre>
	  </td>
	</tr>
	</table>
<?php
	}

	function showCopyright() {
?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
      		<td align="right">Copyright &copy; 2002-2004, Emir Sakic (<a href="http://www.sakic.net" target="_blank">http://www.sakic.net</a>). All Rights Reserved.</td>
          </tr>
        </table>
<?php
	}

}
?>