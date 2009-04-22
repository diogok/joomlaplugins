<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<div class="contentpane<?php echo $this->params->get('pageclass_sfx'); ?>">
<?php if ($this->params->get('show_page_title')) { ?>
<div class="componentheading"><?php echo $this->params->get('page_title'); ?></div>
<?php } ?>
<p>
    <?php echo htmlentities($this->params->get('intro'), ENT_COMPAT, 'UTF-8'); ?>
</p>
<?php if (!count($this->items)) { ?>
<p>
    <?php echo JText::_('THERE ARE NO FORUMS YET.'); ?>
</p>
<?php } ?>
<? if(stripos(JFactory::getUser()->usertype,"adm") !== false): ?>
<p>
    <a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=forumadd') ?>" >Add forum</a>
</p>
<? endif; ?>
<table class="Text" border="0" cellpadding="0" cellspacing="0" width="100%">
    <thead>
        <tr class="Titles">
            <th style="width:25%;text-align:left; padding: 5px 0; border-bottom: 1px solid #ccc;"><?php echo JText::_('FORUM'); ?></th>
            <th style="text-align:left; padding: 5px 0; border-bottom: 1px solid #ccc;"><?php echo JText::_('DESCRIPTION'); ?></th>
            <th style="width:10%;text-align:left; padding: 5px 0; border-bottom: 1px solid #ccc;"><?php echo JText::_('POSTS'); ?></th>
            <th style="width:15%;text-align:left; padding: 5px 0; border-bottom: 1px solid #ccc;"><?php echo JText::_('LAST ACTIVITY'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                  $item = $this->items[$i];
                  $color = ($i%2)?"#CAD1E7":"";
        ?>
        <tr class="sectiontableentry<?php echo ($i % 2); ?>" bgcolor="<?=$color?>">
            <td style="border-bottom: 1px dotted #ccc; padding: 4px 0;"><a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view='.($this->params->get('linktopics')?'topiclist':'postlist').'&forumId='.(int)$item->id); ?>"><?php echo stripslashes(htmlentities($item->name, ENT_COMPAT, 'UTF-8')); ?></a></td>
            <td style="border-bottom: 1px dotted #ccc; padding: 4px 0;"><?php echo stripslashes(htmlentities($item->description, ENT_COMPAT, 'UTF-8')); ?></td>
            <td style="text-align:center; border-bottom: 1px dotted #ccc; padding: 4px 0;"><?php echo $item->posts; ?></td>
            <td valign="top" style="text-align:right; border-bottom: 1px dotted #ccc; padding: 4px 0;"><?php echo ForumHelper::getDate($item->lastActivity); ?></td>
            <? if(stripos(JFactory::getUser()->usertype,"adm") !== false): ?>
                <td style="border-bottom: 1px dotted #ccc; text-align: right">
                <a href="<?=JRoute::_('index.php?option=com_simplestforum&view=forumlist&task=delete_forum&id='.(int)$item->id)?>"
                    onclick="return confirm('Are you sure to delete this forum?')">
                Delete</a>
                </td>
            <? endif; ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php ForumHelper::renderBackLink(); ?>
</div>
