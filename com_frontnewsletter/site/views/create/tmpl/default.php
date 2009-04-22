<?defined('_JEXEC') or die('Restricted access'); ?>
<h1>Newsletters</h1>
<form method="POST" action='<?=JRoute::_("index.php?option=com_frontnewsletter&task=create")?>'>
    <table id="createNewsTable">
        <tr>
            <td>Subject</td>
            <td><input type="text" size='30' name="subject" /></td>
        </tr>
        <tr>
            <td valign='top'>Content</td>
            <td>
                <?
                     $editor =& JFactory::getEditor();
                     echo $editor->display('content', '', '600', '300', '60', '20', false);
                ?>
            </td>
        </tr>
    </table>
    <p><input type="submit" value="Save" /><input type="button" value="Cancel" onclick="history.back()" /></p>
</form>
