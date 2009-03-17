<?php
/**
* @version 1.4.2
* @package Simplest Forum
* @copyright Copyright (C) 2008 Ambitionality Software LLC. All rights reserved.
* @license GNU/GPL
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
//no direct access
defined( '_JEXEC' ) or die( 'Restricted Access' );

jimport( 'joomla.application.component.view' );

/**
 * Forum List View
 *
 * @package Simplest Forum
 */
class SimplestForumViewForumList extends JView
{
    function display( $tmpl = null )
    {
        $mainframe = &JFactory::getApplication();
        jimport('joomla.html.pagination');

        //set up the tool bar
        JToolBarHelper::title( JText::_( 'FORUM LIST' ), 'generic.png' );
        JToolBarHelper::custom('config', 'config.png', 'config.png', JText::_('PARAMETERS'), false);
        JToolBarHelper::deleteList(JText::_('CONFIRM DELETE'));
        JToolBarHelper::editList();
        JToolBarHelper::addNewX();
        JToolBarHelper::custom('showHelp', 'help', 'help', JText::_('HELP'), false);

        $model = $this->getModel();

        // get our filters
        $filter_orderDirection = $mainframe->getUserStateFromRequest('com_simplestforum.filter_orderDir', 'filter_order_Dir', 'asc', 'word');
        $filter_order = $mainframe->getUserStateFromRequest('com_simplestforum.filter_order', 'filter_order', 'ordering', 'word');
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest('com_simplestforum.limitstart', 'limitstart', 0, 'int');

        // apply the filters
        $model->setLimits($limitstart, $limit);
        $model->setOrdering($filter_order, $filter_orderDirection);

        $pagination = new JPagination($model->getTotal(), $limitstart, $limit);
        $items = $model->getData();

        // pass the filters on to our view
        $this->assignRef('items', $items);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('filter_order', $filter_order);
        $this->assignRef('filter_orderDir', $filter_orderDirection);

        parent::display($tmpl);
    } //end display
}
?>
