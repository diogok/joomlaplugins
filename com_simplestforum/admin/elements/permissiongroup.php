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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class JElementPermissionGroup extends JElement
{
    /**
     * Element name
     *
     * @access  protected
     * @var     string
     */
    var $_name = 'PermissionGroup';

    /**
     * Returns the HTML for the permission group parameter
    */
    function fetchElement($name, $value, &$node, $control_name)
    {
        $acl = &JFactory::getACL();
        $fieldName = $control_name.'['.$name.']';

        $items = array(
            JHTML::_('select.option', '0', 'Guest'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Registered'), 'Registered'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Author'), 'Author'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Editor'), 'Editor'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Publisher'), 'Publisher'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Manager'), 'Manager'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Administrator'), 'Administrator'),
            JHTML::_('select.option', $acl->get_group_id(null, 'Super Administrator'), 'Super Administrator'),
        );

        if (!is_callable(array($node, 'attributes')) || !$node->attributes('hideglobal')) {
            $items = array_merge(array(JHTML::_('select.option', '', JText::_('USE GLOBAL'))), $items);
        }

        return JHTML::_('select.genericlist', $items, $fieldName, 'style="width:200px;"', 'value', 'text', $value, $control_name.$name);
    } //end fetchElement

} //end class
