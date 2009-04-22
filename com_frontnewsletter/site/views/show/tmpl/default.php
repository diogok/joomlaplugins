<?defined('_JEXEC') or die('Restricted access'); ?>
<h1>Newsletters</h1>
<h2>Subject: <?=$this->new->subject?></h2>
<? if(count($this->new->sent) >= 1): ?>
<p class='title'>Times sent</p>
<ul>
    <? foreach($this->new->sent as $s):?>
        <li>At <?=date("F d, Y",$s->date)?> for <?=count($s->users)?> users.</li>
    <? endforeach; ?>
</ul>
<? endif ;?>
<form method="POST" action='<?=JRoute::_("index.php?option=com_frontnewsletter&task=save")?>'>
    <input type="hidden" name="news_id" value="<?=$this->new->id?>" />
    <table id="createNewsTable">
        <tr>
            <td>Subject</td>
            <td><input type="text" size='30' name="subject"  value="<?=$this->new->subject?>"/></td>
        </tr>
        <tr>
            <td valign='top'>Content</td>
            <td>
                <?
                     $editor =& JFactory::getEditor();
                     echo $editor->display('content', $this->new->content, '600', '300', '60', '20', false);
                ?>
            </td>
        </tr>
    </table>
    <p><input type="submit" value="Save" /><input type="button" value="Cancel" onclick="history.back()" /></p>
</form>
