<div id="headline">
<span class="headlineText"><?=$article->micro_text?></span>
<? if($actual->readmore ==1 ): ?>
<a href="<?=JRoute::_("index.php?option=com_content&amp;view=article&amp;id=".$article->id)?>">Read More</a>
<? endif; ?>
</div>
