<?defined('_JEXEC') or die('Restricted access'); ?>
<p class="msgHead"><?=$this->mOk?></p>
<h1>Choose an article for the Headline</h1>
<form method="post" action="<?=JRoute::_('index.php?option=com_headline&amp;task=save')?>">
<select name="article">
<? if(count($this->articles) >= 1): ?>
    <? foreach($this->articles as $art): ?>
        <? if($art->headline): ?>
            <? $ck=($art->do_readmore)?"checked":"";?>
            <? $max=$art->headline_max_size ?>
            <option value="<?=$art->id?>" selected><?=$art->title?></option>
        <? else: ?>
            <option value="<?=$art->id?>"><?=$art->title?></option>
        <? endif; ?>
    <? endforeach; ?>
<? endif; ?>
</select>
<Br />
Show "Read more" link?<input type="checkbox" name="readmore" value="1" <?=$ck?>/>
<Br />
Number of characters to show:<input type="text" name="maxsize" value="<?=$max?>" />
<br />
<input type="submit" value="Save"/>
</form>
