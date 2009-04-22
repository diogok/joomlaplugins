<?defined('_JEXEC') or die('Restricted access'); ?>
<h1>Newsletters</h1>
<h2>Subject: <?=$this->new->subject?></h2>
<form method="post" action="<?=JRoute::_("index.php?option=com_frontnewsletter&task=send")?>" id="sendNewsForm">
    <input type="hidden" name="news_id" value="<?=$this->new->id?>" />
    <p>Choose users to send</p>
    <table width="80%">
    <? $i = 0 ; ?>
    <? foreach($this->users as $u):?>
    <? if($i ==0 ): ?>
        <tr>
    <? endif; ?>
        <td width='33%' valign="top">
            <input type="checkbox" name="users[]" value="<?=$u->id?>" /><?=$u->name?>
        </td>
        <? $i++ ?>
    <? if($i == 3): ?>
        </tr>
    <? endif;?>
    <? endforeach;?>
    </table>
    <script>
        var marked = false;
        function markAllSend() {
            var form =  document.getElementById("sendNewsForm");
            if(marked == false) {
                var mark = true ;
                marked = true;
            } else {
                var mark = false ;
                marked = false;
            }
            for(i=0;(a = form.getElementsByTagName("input")[i]);i++) {
                a.checked = mark;
            }
        }
    </script>
    <p><a href="#" onclick='markAllSend();return false'>Mark all</a></p>
    <button type='submit'>Send</button>
    <button type='button' onclick='history.back(); return false;'>Cancel</button>
</form>
