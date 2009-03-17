<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<div class="contentpane<?php echo $this->params->get('pageclass_sfx'); ?>">
<?php if ($this->params->get('show_page_title')) { ?>
<div class="Titles" style="display:none"><strong><?php echo $this->params->get('page_title'); ?></strong></div>
<?php } ?>
<?php if (@$this->error) { ?>
<p>
    <?php echo $this->error; ?>
</p>
<?php } else { ?>
<p class="Titles">
    <?php echo $this->forum->description; ?>
</p>
<p>&nbsp;</p>
<a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=forumlist'); ?>" style="border: 1px solid #999; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;">&lt;&lt; <?php echo JText::_('BACK TO FORUM LIST'); ?></a>
<?php if (!count($this->items)) { ?>
<p>&nbsp;</p>
<p class="Text">
    <strong><?php echo JText::_('THERE ARE NO TOPICS IN THIS FORUM YET'); ?></strong>
</p>
<?php } ?>
<table border="0" cellpadding="0" cellspacing="2" width="100%" class="Text" style="border: 1px solid #ccc; margin: 10px 0 5px 0;">
    <thead>
        <tr bgcolor="#d4d4d4">
            <th width="70%" class="Titles" style="text-align:left; padding: 4px 10px;"><?php echo JText::_('TOPIC'); ?></th>
          <th width="5%" class="Titles" style="text-align:center;width:10%; padding: 4px;"><?php echo JText::_('REPLIES'); ?></th>
          <th width="25%" class="Titles" style="width:15%;text-align:center; padding: 4px;"><?php echo JText::_('LAST ACTIVITY'); ?></th>
      </tr>
        <tr>
            <td colspan="3" style="text-align:center; padding: 3px 0 4px 0;">
                <a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=postlist&forumId='.$this->forum->id); ?>" style="border: 1px solid #999; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;">&lt;&lt; <?php echo JText::_('VIEW ALL POSTS'); ?>&gt;&gt;</a>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                  $item = $this->items[$i];
        ?>
        <tr class="sectiontableentry<?php echo ($i % 2); ?><?php if (!$item->published) { echo ' requiresapproval'; } ?>">
            <td style="border-top: 1px dashed #ccc; padding: 4px 0;" valign="top"><a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=postlist&forumId='.(int)$this->forum->id.'&parentId='.$item->id.'&topic=true'); ?>"><?php echo $item->subject; ?></a></td>
            <td style="border-top: 1px dashed #ccc; padding: 4px 0;" align="center" valign="top"><?php echo $item->replies; ?></td>
            <td style="border-top: 1px dashed #ccc; padding: 4px 0;" valign="top"><?php echo ForumHelper::getDate($item->lastActivity); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<div class="Text" style="text-align:right;">
    <form name="paginationForm" method="post" action="<?php echo JRoute::_('index.php'); ?>">
        <input type="hidden" name="option" value="com_simplestforum" />
        <input type="hidden" name="view" value="topiclist" />
        <input type="hidden" name="forumId" value="<?php echo @$this->forum->id; ?>" />
        <?php echo $this->pagination->getListFooter(); ?>
    </form>
</div>
<?php if ($this->postAllowed) { ?>
<?php include(JPATH_COMPONENT.DS.'views'.DS.'postlist'.DS.'tmpl'.DS.'default_form.php'); ?>
<?php } ?>
<?php ForumHelper::renderBackLink(); ?>
<?php } ?>
</div>
