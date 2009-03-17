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
 * Form List View
 *
 * @package Simplest Forum
 */
class SimplestForumViewForumadd extends JView
{
    function display( $tmpl = null )
    {
        $mainframe = &JFactory::getApplication();

        $model = &$this->getModel('forumadd');
        $pparams = $mainframe->getPageParameters('com_simplestforum');

        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');

        $this->assignRef('items', $items);
        $this->assignRef('params', $pparams);
        $this->assignRef('pageTitle', $pageTitle);

        if($_POST["save"] == "forumadd") {
            $model->store($_POST);
            header("location: index.php?option=com_simplestforum&view=forumlist");
        }
        parent::display($tmpl);
    } //end display
    
} //end class
?>
