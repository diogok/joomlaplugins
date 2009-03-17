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
    }

    function display($tmpl = null)
    {
        parent::display($tmpl);
    }

    /**
     * Verifies access and adds a post to a forum.
     * @param the template
    */
    function savePost($tmpl = null)
    {
        $mainframe = &JFactory::getApplication();

        $id = JRequest::getVar('id');
        $data = JRequest::get('post');
        $files = JRequest::get('files');
        $params = &JComponentHelper::getParams('com_simplestforum');

        $topic = JRequest::getVar('topic');
        $parentId = JRequest::getVar('parentId');
        $rawtext = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWRAW);

        //verify a valid token
		$token	= JUtility::getToken();
        $model = $this->getModel('postlist');

		if(!JRequest::getInt($token, 0, 'post')) {
            $message = JText::_('REQUEST FORBIDDEN');
            $msgtype = 'error';
		} else if (!$model->store($data, $files, $rawtext)) {
            $message = $model->getError();
            $msgtype = 'error';
        } else if ($id) {
            $message = JText::_('POST EDITED SUCCESSFULLY');
            $msgtype = 'message';
        } else {
            $message = JText::_('YOUR POST HAS BEEN SUBMITTED');
            $msgtype = 'message';
        }

        if ($msgtype == 'error') { 
            if (!$parentId && $topic) {
                JRequest::setVar('view', 'topiclist');
            } else {
                JRequest::setVar('view', 'postlist');
            }

            $mainframe->enqueueMessage($model->getError(), 'error');
            parent::display();
            return;
        }

        if ($model->isModerationRequired()) {
            $mainframe->enqueueMessage(JText::_('MESSAGE MODERATION REQUIRED'));
        }
        
        if (!$parentId && $topic) {
            $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=topiclist&forumId='.(int)JRequest::getVar('forumId'), false), $message, $msgtype);
        } else {
            $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=postlist'.($parentId?'&parentId='.$parentId:'').($topic?'&topic=true':'').'&forumId='.(int)JRequest::getVar('forumId'), false), $message, $msgtype);
        }
    } //end savePost


    /**
     * Delete the post with the id supplied in the request
    */
    function delete()
    {
        $id = JRequest::getVar('id');
        $topic = JRequest::getVar('topic');
        $parentId = JRequest::getVar('parentId');

        $model = $this->getModel('postlist');

        if ($model->delete($id)) {
            $message = JText::_('THE POST HAS BEEN DELETED');
        } else {
            $message = $model->getError();
        }

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=postlist'.($parentId?'&parentId='.$parentId:'').($topic?'&topic=true':'').'&forumId='.(int)JRequest::getVar('forumId'), false), $message);
    } //end delete

    function delete_forum()
    {
        $id[] = JRequest::getVar('id');

        $model = $this->getModel('forumlist');

        if ($model->delete($id)) {
            $message = JText::_('THE FORUM HAS BEEN DELETED');
        } else {
            $message = $model->getError();
        }

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist',false), $message);
    } //end delete

    function approve()
    {
        $mainframe = &JFactory::getApplication();
        $id = JRequest::getVar('id');
        $topic = JRequest::getVar('topic');
        $parentId = JRequest::getVar('parentId');
        $forumId = JRequest::getVar('forumId');

        $model = $this->getModel('postlist');

        if ($model->approve($id)) {
            $message = JText::_('POST APPROVED');
            $msgtype = 'message';
        } else {
            if (!$parentId && $topic) {
                JRequest::setVar('view', 'topiclist');
            } else {
                JRequest::setVar('view', 'postlist');
            }

            $mainframe->enqueueMessage($model->getError(), 'error');
            parent::display();
            return;
        }

        if (!$parentId && $topic) {
            $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=topiclist&forumId='.(int)JRequest::getVar('forumId'), false), $message, $msgtype);
        } else {
            $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=postlist'.($parentId?'&parentId='.$parentId:'').($topic?'&topic=true':'').'&forumId='.(int)JRequest::getVar('forumId'), false), $message, $msgtype);
        }
    } //end approve

    function removeAttachment()
    {
        $mainframe = &JFactory::getApplication();
        $id = JRequest::getVar('id');
        $topic = JRequest::getVar('topic');
        $parentId = JRequest::getVar('parentId');
        $forumId = JRequest::getVar('forumId');

        $model = $this->getModel('postlist');

        if ($model->removeAttachment($id)) {
            $message = JText::_('ATTACHMENT REMOVED');
            $msgtype = 'message';
        } else {
            if (!$parentId && $topic) {
                JRequest::setVar('view', 'topiclist');
            } else {
                JRequest::setVar('view', 'postlist');
            }

            $mainframe->enqueueMessage($model->getError(), 'error');
            parent::display();
            return;
        }

        if (!$parentId && $topic) {
            $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=topiclist&forumId='.(int)JRequest::getVar('forumId'), false), $message, $msgtype);
        } else {
            $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=postlist'.($parentId?'&parentId='.$parentId:'').($topic?'&topic=true':'').'&forumId='.(int)JRequest::getVar('forumId'), false), $message, $msgtype);
        }
    } //end removeAttachment

    function extension()
    {
        $mainframe = &JFactory::getApplication();
        $postData = JRequest::get('request');
        $extension = JRequest::getWord('extension');
        $task = JRequest::getWord('extensionTask');

        JPluginHelper::importPlugin('component');
        $mainframe->triggerEvent('onExtensionTask', array($extension, $task, &$postData));

        $this->setRedirect(JRoute::_('index.php?option=com_simplestforum&view=forumlist', false));
    } //end extension

    function getPost()
    {
        $id = JRequest::getInt('id');
        $db = &JFactory::getDBO();

        $query = 'SELECT a.*
                  FROM #__simplestforum_post AS a
                  WHERE a.id = '.(int)$id
        ;
        $db->setQuery($query);
        $post = $db->loadObject();

        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');
        if (!ForumHelper::verifyPermissions('view', $post->forumId)) {
            return;
        }

        echo "{'subject': '".addcslashes($post->subject, '\\\'')."', 'message': '".addcslashes(ereg_replace("(\r\n)|(\n)", '\n' ,$post->message), '\\\'')."'}";
    } //end getPost

} //end class
?>
