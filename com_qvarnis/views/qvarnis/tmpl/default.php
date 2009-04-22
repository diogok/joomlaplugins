<?php
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php if ( $this->params->get( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>"><?php echo $this->escape($this->params->get('page_title')); ?></div>
<?php endif; ?>
<?php
	if(!empty($this->msg))
	{
		echo $this->msg;
		return;
	}
?>
<p class="titleRed">Mailing</p>
<form action="index.php" method="post" name="mmForm" id="mmForm">
<div>
	<table class="admintable" style="margin-left: 0;">
    	<tr>
        	<td class="textBlue14">
            	<label for="subject">
                	<?php echo JText::_( 'Subject' ); ?>:
                </label>
			</td>
		</tr>
        <tr>
        	<td>
            	<input class="text_area" type="text" name="subject" id="greeting" style="border: 1px solid #ccc; padding: 2px; width: 200px; margin-bottom: 5px;" maxlength="25" value="<?php echo $this->greeting;?>" />
			</td>
		</tr>
		<tr>
			<td>
            	<?php
                	$editor =& JFactory::getEditor();
                    echo $editor->display('message', $this->content, '300', '300', '60', '20', false);
				?>
			</td>
		</tr>
	</table>
    <!-- Edited -->
    <script>
        function sendQvarnis() {
            var mmForm = document.getElementById("mmForm");
            mmForm.task.value='skicka';
            var content = tinyMCE.get('message').getContent();
            var textarea = document.getElementById('message') ;
            textarea.value = content ;
            textarea.innerHTML = content ;
            mmForm.submit();
        }
    </script>
	<input type="button" name="Submit" class="button" value="Send" onclick="sendQvarnis()"/>
	<input type="button" name="Submit" class="button" value="Cancel" onclick="mmForm.task.value='avbryt';mmForm.submit();"/>
 </div>
<input type="hidden" name="option" value="com_qvarnis" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="qvarnis" />
</form>
<?php echo JHTML::_( 'form.token' ); ?>
