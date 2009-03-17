<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<form id="postForm" name="postForm" method="post" class="Text" action="<?php echo JRoute::_('index.php'); ?>" enctype="multipart/form-data" />
    <fieldset>
        <legend style="Text"><strong><?php echo isset($this->parent)?JText::_('POST A RESPONSE'):JText::_('POST A NEW MESSAGE'); ?></strong></legend>
        <p>&nbsp;</p>
        <dl>
            <?php if (@$this->showName) { ?>
            <dt>
                <label for="name" class="Text"><?php echo JText::_('NAME'); ?></label>
            </dt>
            <dd>
                <input type="text" id="name" style="border: 1px solid #ccc; margin: 5px 0;" name="name" size="25" maxlength="25" value="<?php echo $this->name; ?>" />
            </dd>
            <?php } ?>
            <dt>
                <label for="subject" class="Text"><?php echo JText::_('SUBJECT'); ?></label>
            </dt>
            <dd>
                <input type="text" id="subject" style="border: 1px solid #ccc; margin: 5px 0;" name="subject" size="50" maxlength="100" value="<?php echo isset($this->subject)?$this->subject:(isset($this->parent)?(JText::_('RE').': '.$this->parent->subject):''); ?>" />
            </dd>
            <dt>
                <label for="message"><?php echo JText::_('MESSAGE'); ?></label>
            </dt>
            <dd>
                <textarea class="text_area" style="border: 1px solid #ccc; margin: 5px 0;" id="message" name="message" rows="10" cols="50"><?php echo isset($this->message)?$this->message:''; ?></textarea>
            </dd>
        </dl>
        <input type="hidden" name="option" value="com_simplestforum" />
        <input type="hidden" id="task" name="task" value="savePost" />
        <input type="hidden" id="id" name="id" value="<?php echo @$this->postId; ?>" />
        <input type="hidden" id="parentId" name="parentId" value="<?php echo isset($this->parent)?$this->parent->id:''; ?>" />
        <input type="hidden" name="forumId" value="<?php echo $this->forum->id; ?>" />
        <input type="hidden" name="topic" value="<?php echo @$this->topic?'1':'0'; ?>" />
        <input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
        <?php $this->application->triggerEvent('onRenderPostForm'); ?>
        <div style="text-align:right;">
            <div align="center">
              <input type="submit" style="border: 1px solid #ccc; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #fff; background: #cb002b; cursor:pointer;" value="<?php echo JText::_('SUBMIT'); ?>" />
                </div>
        </div>
</fieldset>
</form>
