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
// no direct access
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * Simplest Forum Controller
 *
 * @package Simplest Forum
 */
class SimplestForumController extends JController
{
    function SimplestForumController()
    {
        parent::__construct();

        $this->registerTask('add', 'edit');
        $this->registerTask('remove', 'delete');
    }

    /**
     * Cancels a forum edit operation.
     * @param tmpl The template
    */
    function cancel($tmpl = null)
    {
        $msg = JText::_('OPERATION CANCELED');

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist', false), $msg);
    }

    /**
     * Displays the default screen (forum list)
    */
    function display($tmpl = null)
    {
        parent::display($tmpl);
    }

    /**
     * Initiates a forum edit operation.
     * @param tmpl The template
    */
    function edit($tmpl = null)
    {
        JRequest::setVar('view', 'forumedit');

        parent::display($tmpl);
    } //end edit

    /**
     * Initiates a forum save operation.
     * @param tmpl The template
    */
    function save($tmpl = null)
    {
        JRequest::setVar('view', 'forumlist');

        $data = JRequest::get('post');
        $model = $this->getModel('forumedit');

        if ($model->store($data)) {
            $message = JText::_('FORUM SAVED SUCCESSFULLY');
        } else {
            $message = $model->getError();
        }

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist', false), $message);
    } //end save

    /**
     * Initiates a forum delete operation.
     * @param tmpl The template
    */
    function delete($tmpl = null)
    {
        JRequest::setVar('view', 'forumlist');

        $cid = JRequest::getVar('cid');
        $model = $this->getModel('forumlist');

        if ($model->delete($cid)) {
            $message = JText::_('FORUM(S) REMOVED SUCCESSFULLY');
        } else {
            $message = $model->getError();
        }

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist', false), $message);
    } //end delete


    /**
     * Saves the ordering of the forums.
    */
    function saveOrder()
    {
        $cid = JRequest::getVar('cid');
        $orders = JRequest::getVar('order');
        $model = $this->getModel('forumlist');

        if ($model->saveOrder($cid, $orders)) {
            $message = JText::_('OPERATION SUCCESSFUL');
        } else {
            $message = $model->getError();
        }

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist', false), $message);
    } //end saveOrder


    /**
     * Handles requests to order a forum down in the ordering list
    */
    function orderDown()
    {
        $this->move(1);
    } //end orderDown


    /**
     * Handles requests to order a forum up in the ordering list
    */
    function orderUp()
    {
        $this->move(-1);
    } //end orderUp


    /**
     * Helper function that actually does the work for orderUp and orderDown
     * @param $direction a positive number to move the forum down and a
     * negative number to move the forum up
    */
    function move($direction)
    {
        $cid = JRequest::getVar('cid');
        $model = $this->getModel('forumlist');

        if ($model->move($cid[0], $direction)) {
            $message = JText::_('OPERATION SUCCESSFUL');
        } else {
            $message = $model->getError();
        }

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist', false), $message);
    } //end move


    /**
     * Displays the configuration options page.
    */
    function config()
    {
        JRequest::setVar('view', 'config');

        parent::display();
    } //end config


    /**
     * Saves the configuration options.
    */
    function saveConfig()
    {
        $model = $this->getModel('config');
        $data = JRequest::get('post');

        if (!$model->store($data)) {
            $msg = JText::_('OPERATION FAILED');
            $type = 'error';

            $mainframe = &JFactory::getApplication();
            $mainframe->enqueueMessage($msg, 'error');

            JRequest::setVar('view', 'config');
            parent::display();
            return;
        }

        $link = JRoute::_('index.php?option=com_simplestforum&view=forumlist', false);
        $this->setRedirect($link, JText::_('OPERATION SUCCESSFUL'));
    } //end saveConfig

} //end class
?>
