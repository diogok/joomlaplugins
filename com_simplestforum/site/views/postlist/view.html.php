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
 * Post List View
 *
 * @package Simplest Forum
 */
class SimplestForumViewPostList extends JView
{
    function display( $tmpl = null )
    {
        jimport('joomla.plugin.helper');
        JPluginHelper::importPlugin('component');

        $mainframe = &JFactory::getApplication();
        $doc = &JFactory::getDocument();
        $params = $mainframe->getPageParameters();

        //get the forum and parent thread id (if applicable)
        $parentId = JRequest::getVar('parentId');
        $forumId = JRequest::getVar('forumId', $params->get('forumid'));
        $topic = JRequest::getVar('topic');
        $limit = $mainframe->getUserStateFromRequest('postlist.limit', 'limit', 10, 'int');
        $limitStart = JRequest::getVar('limitstart', null);

        $model = &$this->getModel('postlist');

        $parent = $model->getPost($parentId);

        $model->setForumId($forumId);
        if ($parent) {
            $model->setThread($parent->thread?$parent->thread:$parent->id);
        }
        $model->setLimits($limitStart, $limit);
        $items = $model->getData();

        if ($items === false) {
            $this->assignRef('error', $model->getError());
        } else {
            require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');
            $forum = ForumHelper::getForum($forumId);

            $postAllowed = ForumHelper::verifyPermissions('post', $forum);
            $moderateAllowed = ForumHelper::verifyPermissions('moderate', $forum);

            if ($moderateAllowed || $postAllowed) {
                $doc->addScript(JURI::base().'media/system/js/mootools.js');
                $doc->addScript(JURI::base().'components/com_simplestforum/assets/textarea.js');
            }

            // set the title and meta description for the top level post
            $titlePost = $parent?$parent:(count($items)?$items[0]:null);
            if ($titlePost) {
                $doc->setTitle(substr($titlePost->subject, 0, 50));
                $doc->setDescription(substr(ereg_replace("[\r\n]", '', strip_tags($titlePost->message)), 0, 50));
            }

            // get the current user's name
            $user = &JFactory::getUser();

            if (!$user->get('guest')) {
                $name = $user->get('name');
            } else {
                $this->assign('showName', true);
                $name = JRequest::getVar('name');
            }

            $postId = JRequest::getVar('id');

            $this->assignRef('application', $mainframe);
            $this->assignRef('forum', $forum);
            $this->assignRef('items', $items);
            $this->assignRef('message', JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW));
            $this->assignRef('postId', $postId);
            $this->assignRef('moderateAllowed', $moderateAllowed);
            $this->assignRef('name', $name);
            $this->assignRef('parent', $parent);
            $this->assignRef('postAllowed', $postAllowed);
            $this->assignRef('subject', JRequest::getVar('subject'));
            $this->assignRef('parentId', $parentId);
            $this->assign('topic', $topic);
        }

        // set the bread crumbs
        $pathway = &$mainframe->getPathway();
        $pathway->addItem($forum->name.' '.JText::_('TOPIC LIST'), JRoute::_('index.php?option=com_simplestforum&view=topiclist&forumId='.$forum->id));
        $pathway->addItem(JText::_('POST LIST'), '');

        // set up the router for the pagination
        $router = &$mainframe->getRouter();
        $router->setVar('option', 'com_simplestforum');
        $router->setVar('view', 'postlist');
        $router->setVar('forumId', $forumId);
        $router->setVar('parentId', $parentId);
        $router->setVar('topic', $topic);

        jimport('joomla.html.pagination');
        $pagination = new JPagination($model->getTotal(), $limitStart, $limit);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('params', $params);
        $this->assignRef('Itemid', $active->id);

        parent::display($tmpl);
    } //end display

    protected function isOriginalAuthor($postId)
    {
        $user = &JFactory::getUser();

        if ($user->get('guest')) {
            return false;
        }

        $model = &$this->getModel();

        return $model->isOriginalAuthor($postId, $user->get('id'));
    } //end isOriginalAuthor

} //end class
?>
