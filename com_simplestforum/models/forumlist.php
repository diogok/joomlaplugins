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
 * Simplest Forum Forum List model
 *
 * @package Simplest Forum
 */
class SimplestForumModelForumList extends JModel
{
    /**
     * The list of forums.
    */
    var $_data;


    /**
     * Construct it.
    */
	function SimplestForumModelForumList()
	{
		parent::__construct();
	}

    /**
     * Creates a query string for retrieval of data from the database.
     * @access private
     * @return string the query string for the database operation
    */
    function _buildQuery()
    {
        $user = &JFactory::getUser();
        $params = &JComponentHelper::getParams('com_simplestforum');
        $defaultViewGID = $params->get('viewgid');
        $defaultModerateGID = $params->get('moderategid');

        $query = 'SELECT a.*, COUNT(b.id) AS posts, MAX(b.date) AS lastActivity, a.viewgid
                  FROM #__simplestforum_forum AS a
                  LEFT JOIN #__simplestforum_post AS b ON b.forumId = a.id AND (b.published = true OR IF(a.moderategid IS NOT NULL, a.moderategid, '.$this->_db->Quote($defaultModerateGID).') <= '.$this->_db->Quote($user->get('gid')).')
                  WHERE IF(a.viewgid IS NOT NULL, a.viewgid, '.$this->_db->Quote($defaultViewGID).') <= '.$this->_db->Quote($user->get('gid')).'
                  GROUP BY a.id
                  ORDER BY a.'.$params->get('ordering', 'name')
        ;

        return $query;
    } //end _buildQuery


    /**
     * Returns an array of forum items from the database.
     * @return array an array of forum objects
    */
    function getData()
    {
        if (!isset($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = ($this->_data = $this->_getList($query))?$this->_data:array();
        }

        return $this->_data;
    } //end getData

    /**
     * Deletes forums based on request supplied ids (as an array cid).
     * @return bool true on successfuly deletion, false otherwise. On
     * error setError is called.
    */
    function delete($cid)
    {
        // if we are given bogus junk
        if (!$cid || !is_array($cid)) {
            return false;
        }

        $query = 'DELETE FROM #__simplestforum_forum WHERE id IN ('.implode(',', $cid).')';
        $this->_db->setQuery($query);

        if ($this->_db->query()) {
            return false;
        }

        // delete child posts
        $query = 'DELETE FROM #__simplestforum_forum WHERE forumId IN ('.implode(',', $cid).')';
        $this->_db->setQuery($query);

        return $this->_db->query();
    } //end delete

} //end class
?>
