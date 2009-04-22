<?defined('_JEXEC') or die('Restricted access'); ?>
<? $news = $this->news ?>
<h1>Newsletters</h1>
<p class='msg'><?=JRequest::getVar("msg","")?></p>
<p><a href="<?=JRoute::_("index.php?option=com_frontnewsletter&view=create")?>">Create new newsletter</a></p>
<? if(count($news) >= 1): ?>
<ul id="newsletterList">
    <? foreach($news as $n):?>
    <li>
        <a href="<?=JRoute::_("index.php?option=com_frontnewsletter&view=show&news_id=".$n->id)?>">
            <?=date("F d, Y",$n->date)?>
        </a>
        -
        <?=$n->subject?>
        <button onclick="location.href='<?=JRoute::_("index.php?option=com_frontnewsletter&view=send&news_id=".$n->id)?>'">
            Send
        </button>
    </li>
    <? endforeach;?>
</ul>
<? endif;?>
