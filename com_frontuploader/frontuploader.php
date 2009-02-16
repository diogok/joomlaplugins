<?php

defined( '_VALID_MOS' ) or die( 'Restricted access' );
defined( '_JEXEC' ) or die( 'Restricted access' );

global $mosConfig_offset, $mosConfig_live_site;

$now 		= _CURRENT_SERVER_TIME;
$access 	= !$mainframe->getCfg( 'shownoauth' );
$nullDate 	= $database->getNullDate();

jimport('joomla.application.component.view');

$isAdmin = false ;
$user = JFactory::getUser();
if($user->authorize('com_content','edit','content','all')) {
    $isAdmin = true ;
}

$menu =& JSite::getMenu();
$item = $menu->getActive();
$mparams =& $menu->getParams($item->id);

$title 		= $mparams->get( 'title', 	'File uploader' );
$folder		= $mparams->get( 'folder', 	'components/com_frontuploader/files/' );

$file = $_FILES["file"];
$fname = $file["name"];
$ftmp = $file["tmp_name"];

if(strlen($ftmp) >= 2) {
	move_uploaded_file($ftmp,$folder.$fname);
	$msg = "File uploaded";
} else if(strlen($_GET["df"]) >= 2) {
	$df = $_GET["df"] ;
	if($df[0] == "." or $df[0] == "/") {
	} else if(file_exists($folder.$df)) {
		@unlink($folder.$df);
	}
	header("location: index.php?option=com_simple_frontend_uploader");
}

?>

<? if($isAdmin): ?>
<p class="title"><?=$title?></p>
<p class="msg"><?=$msg?></p>
<form method="post" enctype="multipart/form-data">
    <table width="350" cellspacing="0" cellpadding="2" border="0">
        <tr>
            <td width="50">
                <label for="file">File:</label>  
            </td>
            <td>
                <input type="file" name="file" id="file" />
            </td>
        </tr>
        <tr> 
        	<td>&nbsp;</td> 
	        <td><input type="submit" value="Send" /></td>
        </tr>
    </table>
</form>
<br />
<? endif; ?>
<p>File list</p>
<br />
<ul class="fileList">
<?
$dir = opendir($folder);
while($file = readdir($dir)):
if($file == "." or $file =="..") continue ;
?>
<li class="fileItem">
    <a href="/<?=$folder.$file?>" target="_blank">
    <?=$file?>
    </a>
    <? if ($isAdmin): ?>
    (
     <a href="?option=com_simple_frontend_uploader&df=<?=$file?>" onclick="return confirm("Are you to delete <?=$file?>?")">
        Delete
      </a>
    )
    <? endif; ?>
</li>
<?
endwhile;
?>
</ul>
