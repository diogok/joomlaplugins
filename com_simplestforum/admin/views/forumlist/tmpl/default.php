<?php defined( '_JEXEC' ) or die( 'Restricted Access' ); ?>
<script type="text/javascript">
function submitbutton(pressbutton) {
    if( pressbutton == 'showHelp' ) {
        window.open('http://simplestforum.org/index.php?option=com_content&view=category&id=35&Itemid=58', '_blank', '', false);
        return false;
    }

    submitform(pressbutton);
    return false;
}
</script>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="5"><?php echo JHTML::_('grid.sort', JText::_('ID'), 'id', $this->filter_orderDir, $this->filter_order == 'id'); ?></th>
                <th width="20">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
                </th>
                <th style="text-align:left;"><?php echo JHTML::_('grid.sort', JText::_('NAME'), 'name', $this->filter_orderDir, $this->filter_order == 'name'); ?></th>
                <th style="text-align:left;width:100px;"><?php echo JHTML::_('grid.sort', JText::_('ORDERING'), 'ordering', $this->filter_orderDir, $this->filter_order == 'ordering'); ?><?php echo JHTML::_('grid.order', $this->items);?></th>
            </tr>
        </thead>
        <tbody>
        <?php
        for( $i = 0, $n=count($this->items); $i < $n; $i++ ) {
            $row = $this->items[$i];
            $checked = JHTML::_('grid.id', $i, $row->id );
            $link = JRoute::_('index.php?option=com_simplestforum&view=forumlist&task=edit&cid[]='.$row->id);
            ?>
            <tr class="row<?php echo ($i % 2); ?>">
                <td><?php echo $row->id; ?></td>
                <td><?php echo $checked; ?></td>
                <td><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></td>
                <td class="order">
                    <span><?php echo $this->pagination->orderUpIcon($i); ?></span>
                    <span><?php echo $this->pagination->orderDownIcon($i, $n); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo @$disabled; ?> class="text_area" style="text-align:center" />
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php echo $this->pagination->getListFooter(); ?>
</div>
<input type="hidden" name="option" value="com_simplestforum" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_orderDir; ?>" />
</form>
