<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<?php JHTML::_('behavior.mootools'); ?>
<script type="text/javascript">
/* <![CDATA[ */
window.addEvent('domready', function() {
    if( window.parent && window.parent.postFrameLoaded ) {
        window.parent.postFrameLoaded(document, <?php echo $this->complete?'true':'false'; ?>);
    }
});
/* ]]> */
</script>
<?php if (!$this->item) { ?>
<?php echo JText::_('YOU ARE NOT AUTHORIZED TO PERFORM THIS ACTION'); ?>
<?php } else { ?>
<form id="postForm" name="postForm" method="post" class="Text" action="<?php echo JRoute::_('index.php'); ?>" enctype="multipart/form-data" />
    <dl>
        <?php if (@$this->showName) { ?>
        <dt>
            <label for="name"><?php echo JText::_('NAME'); ?></label>
        </dt>
        <dd>
            <input type="text" id="name" name="name" size="25" maxlength="25" value="<?php echo @$this->item->name; ?>" />
        </dd>
        <?php } ?>
        <dt>
            <label for="subject"><?php echo JText::_('SUBJECT'); ?></label>
        </dt>
        <dd>
            <input type="text" id="subject" name="subject" size="50" maxlength="100" value="<?php echo @$this->item->subject; ?>" />
        </dd>
        <dt>
            <label for="message"><?php echo JText::_('MESSAGE'); ?></label>
        </dt>
        <dd>
            <textarea class="text_area" id="message" name="message" rows="10" cols="50"><?php echo @$this->item->message; ?></textarea>
        </dd>
    </dl>
    <input type="hidden" name="option" value="com_simplestforum" />
    <input type="hidden" id="task" name="task" value="savePost" />
    <input type="hidden" id="id" name="id" value="<?php echo @$this->item->id; ?>" />
    <input type="hidden" id="parentId" name="parentId" value="<?php echo @$this->item->parentId; ?>" />
    <input type="hidden" name="forumId" value="<?php echo @$this->item->forumId; ?>" />
    <input type="hidden" name="tmpl" value="component" />
    <input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
    <?php $this->application->triggerEvent('onRenderPostForm'); ?>
    <div style="text-align:right;">
        <input type="submit" style="border: 1px solid #ccc; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #fff; background: #cb002b;" value="<?php echo JText::_('SUBMIT'); ?>" />
    </div>
</form>
<?php } ?>
