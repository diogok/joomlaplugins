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
 * Post List View
 *
 * @package Simplest Forum
 */
class SimplestForumViewPostEdit extends JView
{
    function display( $tmpl = null )
    {
        // make sure that we can scan the plugins for the "components"
        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');
        jimport('joomla.plugin.helper');
        JPluginHelper::importPlugin('component');

        $mainframe = &JFactory::getApplication();

        //get the forum and parent thread id (if applicable)
        $id = JRequest::getVar('id');
        $postData = JRequest::get('request');
        $message = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);
        if ($message) {
            $postData['message'] = $message;
        }

        $model = &$this->getModel('postedit');
        $model->setId($id);
        $item = $model->getData($postData);

        $user = &JFactory::getUser();

        // if we just completed a save
        $this->assign('complete', JRequest::getVar('complete'));

        // only show the name if we are dealing with a guest, registered users
        // do not have the option to pretend they are someone else
        $this->assign('showName', $user->get('guest'));
        $this->assignRef('item', $item);
        $this->assignRef('application', $mainframe);

        parent::display($tmpl);
    } //end display

} //end class
?>
