<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm">
<div class="col100">
    <fieldset>
        <legend><?php echo JText::_('Basic Options'); ?></legend>
        <table class="admintable">
            <?php echo $this->item->render('params'); ?>
        </table>
    </fieldset>
    <fieldset>
        <legend><?php echo JText::_('Advanced Options'); ?></legend>
        <table class="admintable">
            <?php echo $this->item->render('params', 'advanced'); ?>
        </table>
    </fieldset>
</div>

<div class="clr"></div>

<input type="hidden" name="option" value="com_simplestforum" />
<input type="hidden" name="controller" value="config" />
<input type="hidden" name="task" value="" />
</form>
