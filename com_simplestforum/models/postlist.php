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
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Simplest Forum Post List model
 *
 * @package Simplest Forum
 */
class SimplestForumModelPostList extends JModel
{
    /**
     * The list of posts.
    */
    var $_data;

    /**
     * The id of the parent post (the thread id)
    */
    var $_thread;

    /**
     * The id of the forum of posts for which we should be concerned.
    */
    var $_forumId;

    /**
     * The starting offset for the pagination limit.
    */
    var $_limitStart;

    /**
     * The number of posts to display.
    */
    var $_limit;


    /**
     * Construct it.
    */
	function SimplestForumModelPostList()
	{
		parent::__construct();
	}


    /**
     * Set the limits of the query.
     * @param $limitstart int the offset into the result set to begin returning
     * results
     * @param $limit the number of items to return in the result set
    */
    function setLimits($limitstart, $limit)
    {
        $this->_limit = $limit;
        $this->_limitstart = $limitstart;

        unset($this->_data);
    } //end setLimits


    /**
     * Sets the parent thread id.
     * @param parentId the id of the parent thread
    */
    function setThread($thread)
    {
        $this->_thread = $thread;

        unset($this->_data);
    } //end setParentId


    /**
     * Sets the id for the parent forum.
     * @param forumId the id of the forum to focus on.
    */
    function setForumId($forumId)
    {
        $this->_forumId = $forumId;

        unset($this->_data);
    } //end setForumId


    /**
     * Creates a query string for retrieval of data from the database.
     * @return string the query string for the database operation
    */
    function _buildQuery($countOnly = false)
    {
        $where = array(
            'a.forumId = '.(int)$this->_forumId,
        );

        //if there is a parent thread, include only those posts on that thread
        if ($this->_thread) {
            $where[] = 'a.thread = '.(int)$this->_thread;
        }

        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');

        // unless we are a moderator, let's hide unpublished posts
        if (!ForumHelper::verifyPermissions('moderate', $this->_forumId)) {
            $where[] = 'a.published = true';
        }

        $order = 'a.thread DESC, a.date';

        $query = ($countOnly?
                 'SELECT COUNT(*) ':
                 'SELECT DISTINCT a.*, IF(b.id IS NOT NULL, b.name, IF(LENGTH(a.authorId) > 0 AND a.authorId != '.$this->_db->Quote('0').', a.authorId, '.$this->_db->Quote(JText::_('ANONYMOUS')).')) AS name '
                 ).'
                  FROM #__simplestforum_post AS a
                  LEFT JOIN #__users AS b on b.id = a.authorId
                  WHERE '.implode(' AND ', $where).'
                  ORDER BY '.$order
        ;

        return $query;
    } //end _buildQuery


    /**
     * Returns an array of forum items from the database.
     * @return array an array of forum objects
    */
    function getData()
    {
        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');

        // make sure that the current user is cool to view these
        if (!ForumHelper::verifyPermissions('view', $this->_forumId)) {
            $this->setError(JText::_('ERROR NO AUTH'));
            return false;
        }

        // don't get the data if we already have it
        if (!isset($this->_data)) {
            $query = $this->_buildQuery(false);
            if ($this->_limitstart || $this->_limit) {
                $this->_db->setQuery($query, $this->_limitstart, $this->_limit);
            } else {
                $this->_db->setQuery($query);
            }
            $posts = ($posts = $this->_db->loadObjectList())?$posts:array();

            // build a tree for the posts to determine parent / child
            // relationships and nesting
            require_once(JPATH_COMPONENT.DS.'helpers'.DS.'tree.php');
            $tree = new AmbitionalityHelperTree($posts, 'id', 'parentId');
            $tree->traverse(array($this, 'orderChildren'), null, true);
            $tree->traverse(array($this, 'calculateDepth'), null, false);

            $this->_data = $tree->getItems();
        }

        return $this->_data;
    } //end getData


    /**
     * Orders the children of a tree node
     * @param the node whose children should be ordered
    */
    function orderChildren(&$node)
    {
        $node->sortChildren(array($this, 'comparePosts'));
    } //end orderChildren


    /**
     * Returns the total number of items regardless of pagination (limits).
     * @return the number of items returned by the current query parameters
    */
    function getTotal()
    {
        $query = $this->_buildQuery(true);
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    } //end getTotal


    /**
     * Returns a reverse comparison by date for the two given tree nodes.
     * @param $left tree node the left comparator
     * @param $right tree node the right comparator
     * @return a value greater than 0 if the right is more recent than the
     * left, a value less than 0 if the right is less recent than the left, and
     * 0 if they are of the same time
    */
    function comparePosts(&$left, &$right)
    {
        $l = $left->getObject();
        $r = $right->getObject();

        return strtotime($r->date) - strtotime($l->date);
    } //end comparePosts


    /**
     * Calculates the depth of each node
     * @param $node the tree node to calculate depth for
    */
    function calculateDepth(&$node)
    {
        $nodeObject = &$node->getObject();
        if ($nodeObject) {
            $nodeObject->depth = $node->getDepth();
        }
    } //end calculateDepth


    /**
     * Deletes a post whose id is supplied in the request (id).
     * @return bool true if the delete is successful, false otherwise.
    */
    function delete($id)
    {
        if (!$id) {
            $this->setError(JText::_('ERROR NO SUCH POST'));
            return false;
        }

        $user = &JFactory::getUser();

        $query = 'SELECT a.forumId
                  FROM #__simplestforum_post AS a
                  WHERE a.id = '.(int)$id
        ;
        $this->_db->setQuery($query);
        $forumId = $this->_db->loadResult();

        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');

        $where = array(
            'a.id = '.$id,
        );

        if (!ForumHelper::verifyPermissions('moderate', $forumId)) {
            $where[] = 'a.authorId = '.$user->get('id');
        }

        $query = 'DELETE a.*
                  FROM #__simplestforum_post AS a
                  WHERE '.implode(' AND ', $where)
        ;
        $this->_db->setQuery($query);

        if (!$this->_db->query()) {
            $this->setError(JText::_('ERROR POST NOT DELETED'));
            return false;
        }

        return true;
    } //end delete


    /**
     * Stores a post from the specified associative array to the database.
     * @param data an associative array whose keys correspond to the field names
     * of a post object.
     * @return bool true of the object is stored successfully, false otherwise.
     * on failure, setError is called.
    */
    function store($data, $files, $rawtext)
    {
        $mainframe = &JFactory::getApplication();
        $params = &JComponentHelper::getParams('com_simplestforum');
        $forumId = JFilterInput::clean($data['forumId'], 'int');
        $id = JFilterInput::clean($data['id'], 'int');
        $user = &JFactory::getUser();

        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');

        //make sure the user is allowed to post
        if (!ForumHelper::verifyPermissions('post', $forumId)) {
            $this->setError(JText::_('ERROR NO AUTH'));
            return false;
        }

        $row = &JTable::getInstance('post', 'SimplestForumTable');

        // if this is an existing post, make sure that we are allowed to edit it
        if ($id) {
            // if there is no such post already
            if (!$row->load($id)) {
                $this->setError(JText::_('NO SUCH POST'));
                return false;
            }

            // if we are not a moderator or the original author
            if (!ForumHelper::verifyPermissions('moderate', $forumId) && $row->get('authorId') != $user->get('id')) {
                $this->setError(JText::_('ERROR NO AUTH'));
                return false;
            }
        }

        if (!$row->bind($data)) {
            $this->setError($row->getError());
            return false;
        }

        $row->message = $rawtext;

        // if there is a parent, we need to get some info from it
        if ($row->parentId) {
            $parent = &JTable::getInstance('post', 'SimplestForumTable');
            
            if (!$parent->load($row->parentId)) {
                $this->setError(JText::_('INVALID PARENT POST'));
                return false;
            }

            $row->thread = $parent->thread;
        }

        $newTopic = !$row->parentId && !$row->thread;
        $this->_isNewTopic = $newTopic;

        // if moderation is required and we are not the moderator, do not publish this post
        $row->set('published', ForumHelper::verifyPermissions('moderate', $forumId) || !(($params->get('approvenewtopics') && $newTopic) || $params->get('approvenewposts')));
        $this->_moderationRequired = !$row->get('published');

        // if it's new
        if (!$id) {
            //get the user and date information
            if (!$user->get('guest')) {
                $row->set('authorId', $user->id);
            } else if (isset($data['name'])) {
                // check for invalid names
                $name = JFilterInput::clean($data['name']);

                if (!$name || !strlen($name)) {
                    $this->setError(JText::_('ERROR DISALLOWED NAME SUPPLIED'));
                    return false;
                }

                $disallowedNames = '('.implode(')|(', explode(',', $params->get('disallowednames', ''))).')';

                if (eregi($disallowedNames, $name)) {
                    $this->setError(JText::_('ERROR DISALLOWED NAME SUPPLIED'));
                    return false;
                }

                // do not allow numeric names since that would allow someone to
                // spoof a real user
                if (is_numeric($name)) {
                    $this->setError(JText::_('ERROR DISALLOWED NAME SUPPLIED'));
                    return false;
                }

                $row->authorId = $name;
            } else {
                $row->authorId = JText::_('ANONYMOUS');
            }
            $row->date = gmdate('Y-m-d H:i:s');
        } else {
            $row->set('modified', gmdate('Y-m-d H:i:s'));
            $row->set('modified_by', $user->get('id'));
        }

        if (!$row->check()) {
            $this->setError($row->getError());
            return false;
        }

        $error = array();
        jimport('joomla.plugin.helper');
        JPluginHelper::importPlugin('component');
        $mainframe->triggerEvent('onBeforeSavePost', array(&$row, &$data, &$files, &$rawtext, &$error));

        // if there was no handler called for our message text, let's strip
        // anything remotely dangerous
        if (!$row->messageHandled) {
            $row->message = strip_tags($rawtext);
        }

        unset($row->messageHandled);

        if (!empty($error)) {
            foreach ($error as $er) {
                $this->setError($er);
            }
            
            return false;
        }

        if (!$row->store()) {
            $this->setError($row->getError());
            return false;
        }

        // if this is the root of a new thread, set it's thread to it's id
        if ($newTopic) {
            $query = 'UPDATE #__simplestforum_post AS a
                      SET a.thread = '.(int)$row->id.'
                      WHERE a.id = '.(int)$row->id
            ;
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $mainframe = &JApplication::getApplication();

                $mainframe->enqueueMessage(JText::_('INFO MESSAGE UNABLE TO BE SET POST AS THREAD START'), 'info');
            }

            $row->thread = $row->id;
        }

        $this->_forumId = $row->forumId;
        $this->_thread = $row->thread;

        $error = array();
        $mainframe->triggerEvent('onAfterSavePost', array(&$row, &$files, &$rawtext, &$error));

        if (!empty($error)) {
            foreach ($error as $er) {
                $this->setError($er);
            }
            
            $row->delete();
            $mainframe->triggerEvent('onPostRollback', array(&$row, &$files, &$rawtext));
            return false;
        }

        return true;
    } //end store

    /**
     * Approves the post with the specified id
     * @param postId integer the id of the post to be approved
     * @return true on success, on failure returns false and sets error using setError
    */
    function approve($postId)
    {
        $query = 'SELECT a.forumId
                  FROM #__simplestforum_post AS a
                  WHERE a.id = '.(int)$postId
        ;
        $this->_db->setQuery($query);
        $forumId = $this->_db->loadResult();

        require_once(JPATH_COMPONENT.DS.'helpers'.DS.'forum.php');
        if (!ForumHelper::verifyPermissions('moderate', $forumId)) {
            $this->setError(JText::_('YOU ARE NOT AUTHORIZED TO PERFORM THIS ACTION'));
            return false;
        }

        $query = 'UPDATE `#__simplestforum_post` AS a
                  SET a.published = true
                  WHERE a.id = '.(int)$postId
        ;
        $this->_db->setQuery($query);

        if (!$this->_db->query()) {
            $this->setError(JText::_('ERROR DATABASE'));
            return false;
        }

        return true;
    } //end approve


    /**
     * Returns whether the recently saved post represents the start of a new
     * thread.
    */
    function isNewTopic()
    {
        return $this->_isNewTopic;
    } //end isNewTopic


    /**
     * Returns whether or not moderation is required on the recently saved post
    */
    function isModerationRequired()
    {
        return $this->_moderationRequired;
    } //end isModerationRequired

    /**
     * Returns the post with the specified id
     * @param $id int the ID of the post to retrieve
     * @return the post or NULL if none is found
    */
    function getPost($id)
    {
        $query = 'SELECT a.*
                  FROM #__simplestforum_post AS a
                  WHERE a.id = '.(int)$id
        ;
        $this->_db->setQuery($query);

        return $this->_db->loadObject();
    } //end getPost

    public function isOriginalAuthor($postId, $userId)
    {
        $query = 'SELECT a.authorId
                  FROM #__simplestforum_post AS a
                  WHERE a.id = '.(int)$postId
        ;
        $this->_db->setQuery($query);

        $result = $this->_db->loadResult();

        return $result == $userId;
    } //end isOriginalAuthor

} //end class
?>
