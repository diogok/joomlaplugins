<?defined('_JEXEC') or die('Restricted access'); ?>
<p class="title">File List</p>
<? if($this->isAdmin): ?>
<p class="msg"><?=$this->msg?></p>
<form method="post" enctype="multipart/form-data">
    <table width="350" cellspacing="0" cellpadding="2" border="0">
        <tr>
            <td width="50">
                <label for="file">Name:</label>  
            </td>
            <td>
                <input type="text" name="name" id="file" />
            </td>
        </tr>
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
<br />
<ul class="fileList">
<? if(count($this->files) >= 1): ?>
<? foreach($this->files as $f): ?>
<li class="fileItem">
    <a href="/<?=$f["path"]?>" target="_frontuploader">
    <?=$f["name"]?>
    </a>
    <? if ($this->isAdmin): ?>
    (
     <a href="?option=com_frontuploader&id_to_delete=<?=$f["id"]?>" onclick="return confirm("Are you to delete <?=$f["name"]?>?")">
        Delete
      </a>
    )
    <? endif; ?>
</li>
<? endforeach; ?>
<? endif; ?>
