<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<div class="contentpane<?php echo $this->params->get('post_pageclass_sfx'); ?>">
<?php if ($this->params->get('show_page_title')) { ?>
<div class="componentheading"><?php echo ($title = $this->params->get('page_title'))?$title.': '.$this->forum->name:$this->forum->name; ?></div>
<?php } ?>
<?php if (@$this->error) { ?>
<p>
    <?php echo $this->error; ?>
</p>
<?php } else { ?>
<p class="Titles">
    <?php echo htmlentities($this->forum->description, ENT_COMPAT, 'UTF-8'); ?>
</p><br />
<a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=forumlist'); ?>" style="border: 1px solid #999; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;"><?php echo JText::_('BACK TO FORUM LIST'); ?></a>
<span style="font-size:0.8em;"> - </span>
<a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=topiclist&forumId='.$this->forum->id); ?>" style="border: 1px solid #999; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;"><?php echo JText::_('BACK TO TOPIC LIST'); ?></a>
<?php if (isset($this->parent) || $this->topic) { ?>
<span style="font-size:0.8em;">&gt;&gt;</span>
<a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=postlist&forumId='.$this->forum->id); ?>" style="border: 1px solid #999; padding: 3px; margin: 5px; font: bold 11px Arial, Helvetica, sans-serif; color: #ccc; background: #cb002b; cursor:pointer;"><?php echo JText::_('VIEW ALL POSTS'); ?></a>
<?php } ?>
<?php if (!count($this->items)) { ?>
<p>&nbsp;</p>
<p class="Titles" style="font-size: 12px;">
    <?php echo JText::_('THERE ARE NO POSTS YET FOR THIS FORUM. PLEASE START US OFF!'); ?>
</p>
<?php } ?>
<?php foreach ($this->items as $item) {
          // call any third part plugins to enhance the display
          $this->application->triggerEvent('onBeforeRenderPost', array(&$item));

          // if no plugins were used to modify the messsage we will make sure that
          // plain text line breaks are changed to html breaks and no HTML is shown
          if (!$item->messageHandled) {
              $item->message = str_replace("\n", '<br />', strip_tags($item->message));
              $item->message = ereg_replace('(http:[^ ]+)', ' <a href="\\1">\\1</a> ', $item->message);
          }
          $class = array();
          if ($item->parentId) {
              $class[] = 'child c'.$item->depth;
          }
          if (isset($this->parent) && $this->parent->id == $item->id) {
              $class[] = 'parent';
          }
          if (!$item->published) {
              $class[] = 'requiresapproval';
          }
          $class = empty($class)?'':(' '.implode(' ', $class));
?>
    <div class="post<?php echo $class; ?>">
        <div id="subject_<?php echo $item->id; ?>" class="subject">
            <?php echo $item->subject; ?>
        </div>
        <div class="postby">
            <?php echo JText::_('POSTED'); ?> <?php echo ForumHelper::getDate($item->date); ?> <?php echo JText::_('BY'); ?> <?php echo $item->name; ?>
        </div>
        <?php if ($item->modified) { ?>
        <div class="postby">
            <?php echo JText::_('MODIFIED'); ?>: <?php echo ForumHelper::getDate($item->modified); ?>
        </div>
        <?php } ?>
        <div id="message_<?php echo $item->id; ?>" class="msg">
            <?php echo $item->message; ?>
        </div>
        <div class="buttons">
            <?php if (isset($item->buttons)) { ?>
            <?php echo implode(' | ', $item->buttons); ?>
            <?php } ?>
            <?php if ($this->postAllowed) { ?>
            <a href="#" onclick="javascript:toForm('<?php echo $item->id; ?>', 'respond');return false;" style="border: 1px solid #999; padding: 2px 3px; margin: 5px; font: bold 10px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;"><?php echo JText::_('RESPOND TO THIS MESSAGE'); ?></a>
            <?php } ?>
            <?php if (!$item->published && $this->moderateAllowed) { ?>
            <a href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=postlist&task=approve&id='.$item->id.'&forumId='.$this->forum->id.'&topic='.$this->topic); ?>" style="border: 1px solid #999; padding: 2px 3px; margin: 5px; font: bold 10px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;"><?php echo JText::_('APPROVE THIS MESSAGE'); ?></a>
            <?php }?>
            <?php if ($this->moderateAllowed || $this->isOriginalAuthor($item->id)) { ?>
            <a href="#" onclick="javascript:toForm('<?php echo $item->id; ?>', 'edit');return false;" style="border: 1px solid #999; padding: 2px 3px; margin: 5px; font: bold 10px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;"><?php echo JText::_('EDIT THIS MESSAGE'); ?></a>
            <a onclick="javascript:return confirm('<?php echo JText::_('CONFIRM DELETE MESSAGE'); ?>');" href="<?php echo JRoute::_('index.php?option=com_simplestforum&view=postlist&task=delete&id='.$item->id.'&forumId='.$this->forum->id.'&topic='.$this->topic.'&parentId='.$this->parentId); ?>" style="border: 1px solid #999; padding: 2px 3px; margin: 5px; font: bold 10px Arial, Helvetica, sans-serif; color: #cb002b; background: #ccc; cursor:pointer;"><?php echo JText::_('DELETE THIS MESSAGE'); ?></a>
            <?php } ?>
        </div>
    </div>
<?php } ?>
<div class="Text" style="text-align:right;">
    <form name="paginationForm" method="post" action="<?php echo JRoute::_('index.php'); ?>">
        <input type="hidden" name="option" value="com_simplestforum" />
        <input type="hidden" name="view" value="postlist" />
        <input type="hidden" id="parentId" name="parentId" value="<?php echo @$this->parent->id; ?>" />
        <input type="hidden" name="forumId" value="<?php echo @$this->forum->id; ?>" />
        <input type="hidden" name="topic" value="<?php echo @$this->topic?'1':'0'; ?>" />
        <?php echo $this->pagination->getListFooter(); ?>
    </form>
</div>
<?php if ($this->postAllowed) { ?>
<?php echo $this->loadTemplate('form'); ?>
<?php } ?>
<?php ForumHelper::renderBackLink(); ?>
<?php if ($this->moderateAllowed || $this->postAllowed) { ?>
<script type="text/javascript">
function toForm(id, operation) {
    var fx = new Fx.Scroll(window);
    var postForm = $('postForm');

    fx.toElement(postForm);

    if( operation == 'new' ) {
        $('subject').value = '';
        $('message').value = '';
        $('parentId').value = '<?php echo isset($this->parent)?$this->parent->id:''; ?>';
        return;
    }

    if( id != null ) {
        var formObj = new Object();
        formObj.option = 'com_simplestforum';
        formObj.task = 'getPost';
        formObj.id = id;
        formObj.format = 'raw';

        var aj = new Ajax('index.php', {data: formObj, onComplete: 
            function(resp) {
                var obj = Json.evaluate(resp);
                var post = null;

                switch( typeof( obj ) ) {
                    case 'object':
                        post = new Object();
                        post.subject = obj.subject;
                        post.message = obj.message.replace(/\\n/g, "\n");
                        post.id = id;

                        switch( operation ) {
                            case 'edit':
                                $('subject').value = post.subject;
                                $('message').value = post.message;
                                $('id').value = id;
                                return;
                            case 'respond':
                                $('subject').value = '<?php echo JText::_('RE'); ?>: '+post.subject;
                                $('parentId').value = id;
                                return;
                        }
                        break;
                    case 'string':
                    default:
                        break;
                }
            }
        }).request();
    }
}
</script>
<?php } ?>
<?php } ?>
</div>
