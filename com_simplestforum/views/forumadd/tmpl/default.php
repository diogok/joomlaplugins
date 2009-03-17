<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<div class="contentpane<?php echo $this->params->get('pageclass_sfx'); ?>">
<?php if ($this->params->get('show_page_title')) { ?>
<div class="componentheading"><?php echo $this->params->get('page_title'); ?></div>
<?php } ?>
<form method="post" class="textBlue14">
<input type="hidden" name="save" value="forumadd" />
Name:<br /> 
<input type="text" name="name" style=" border: 1px solid #ccc; padding: 2px; width: 300px" /><Br />
Description:<br />
<textarea name="description" cols="50" rows="10" style="border: 1px solid #ccc; padding: 2px; width: 300px"></textarea>
<br />
<input type="submit" value="Create" class="button" style="margin: 4px; cursor: pointer;" />
</form>
</div>
