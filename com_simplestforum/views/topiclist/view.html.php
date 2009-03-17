<?php
/**
* @version 1.4.1B
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
 * Topic List View
 *
 * @package Simplest Forum
 */
class SimplestForumViewTopicList extends JView
{
    function display( $tmpl = null )
    {
        jimport('joomla.plugin.helper');
        JPluginHelper::importPlugin('component');

        $mainframe = &JFactory::getApplication();
        $params = $mainframe->getPageParameters();

        //get the forum and parent thread id (if applicable)
        $forumId = JRequest::getVar('forumId', $params->get('forumid'));

        $pparams = $mainframe->getPageParameters('com_simplestforum');
        $limit = $mainframe->getUserStateFromRequest('postlist.limit', 'limit', 10, 'int');
        $limitStart = JRequest::getVar('limitstart', null);

        $model = &$this->getModel('topiclist');
        $model->setForumId($forumId);
        $model->setLimits($limitStart, $limit);
        $items = $model->getData();

        // get the current user's name
        $user = &JFactory::getUser();

        if (!$user->get('guest')) {
            $name = $user->get('name');
        } else {
            $this->assign('showName', true);
            $name = JRequest::getVar('name');
        }

        if ($items === false) {
            $this->assignRef('error', $model->getError());
        } else {
            require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');
            $forum = ForumHelper::getForum($forumId);
            $this->assign('postAllowed', ForumHelper::verifyPermissions('post', $forum));

            $this->assignRef('items', $items);
            $this->assignRef('forum', $forum);
            $this->assignRef('message', JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW));
            $this->assignRef('subject', JRequest::getVar('subject'));
            $this->assignRef('name', $name);
            $this->assignRef('application', $mainframe);
        }

        $this->assignRef('params', $pparams);

        // set the bread crumbs
        $pathway = &$mainframe->getPathway();
        $pathway->addItem($forum->name.' '.JText::_('TOPIC LIST'), '');

        // set up the router for the pagination
        $router = &$mainframe->getRouter();
        $router->setVar('option', 'com_simplestforum');
        $router->setVar('view', 'topiclist');
        $router->setVar('forumId', $forumId);

        jimport('joomla.html.pagination');
        $pagination = new JPagination($model->getTotal(), $limitStart, $limit);
        $this->assignRef('pagination', $pagination);

        parent::display($tmpl);
    } //end display
}
?>
