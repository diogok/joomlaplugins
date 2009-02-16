<?defined('_JEXEC') or die('Restricted access'); ?>
<p class='errMsg'><?=$this->err ?></p>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="contentpane">
	<tr>
     <td class="sectiontableheader">Article List</td>
	</tr>
	
	<?php
	$rows = $this->articles;
    if(count($rows) >= 1)
	    foreach ($rows as $row) {
     	$link = JRoute::_( 'index.php?option=com_content&amp;task=edit&amp;view=article&amp;id='. $row->id );
    		?>
     	
		<tr class="sectiontableentry"> 
			<td><a href="<?=$link; ?>"><?=$row->title; ?></a></td>
		</tr> 
<?
}
    ?>
</table>
