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
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * Simplest Forum List Model
 *
 * @package Simplest Forum
 */
class SimplestForumModelForumList extends JModel
{
    /**
     * The forums.
    */
    var $_data;

    /**
     * The total number of forums to display.
    */
    var $_limit;

    /**
     * The offset / page of the forums.
    */
    var $_limitstart;

    /**
     * The ordering of the forums.
    */
    var $_ordering;

	/**
	 * Overridden constructor
	 * @access	protected
	*/
	function SimplestForumModelForumList()
	{
		parent::__construct();
	}


    /**
     * Sets the ordering of the forums.
     * @param $ordering one of 'name', 'id', or 'ordering' which determines how
     * the forums will be sorted / ordered
     * @param $orderingDirection one of 'desc' or 'asc' for descending or
     * ascending order respectively
    */
    function setOrdering($ordering, $orderingDirection)
    {
        $this->_ordering = $ordering;

        // ensure a valid ordering
        switch ($this->_ordering) {
            case 'name':
                $this->_ordering = 'a.name';
                break;
            case 'id':
                $this->_ordering = 'a.id';
                break;
            case 'ordering':
                $this->_ordering = 'a.ordering';
                break;
            default:
                $this->_ordering = 'a.ordering';
                break;
        }

        $this->_ordering .= $orderingDirection == 'desc'?' DESC':' ASC';
    } //end setOrdering


    /**
     * Sets the limits for forum retrieval.
     * @param $limitstart int the offset to begin at
     * @param $limit int the maximum number of forums to grab
    */
    function setLimits($limitstart, $limit)
    {
        $this->_limitstart = $limitstart;
        $this->_limit = $limit;
    } //end setLimits


    /**
     * Creates a query string for retrieval of data from the database.
     * @access private
     * @return string the query string for the database operation
    */
    function _buildQuery()
    {
        $query = 'SELECT a.id, a.name, a.ordering
                  FROM #__simplestforum_forum AS a
                  ORDER BY '.$this->_ordering
        ;

        return $query;
    } //end _buildQuery


    /**
     * Returns an array of forum items from the database.
     * @return array an array of forum objects
    */
    function getData()
    {
        //only perform the operation if we not already done so
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_db->setQuery($query, $this->_limitstart, $this->_limit);
            $this->_data = ($this->_data = $this->_db->loadObjectList())?$this->_data:array();
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

    /**
     * Returns the total number of forums.
    */
    function getTotal()
    {
        $query = 'SELECT COUNT(*)
                  FROM #__simplestforum_forum'
        ;
        $this->_db->setQuery($query);

        return $this->_db->loadResult();
    } //end getTotal


    /**
     * Saves the ordering for the specified forum ids
    */
    function saveOrder($cid, $orders)
    {
        foreach ($orders as $key => $value) {
            $forum = &JTable::getInstance('forum', 'SimplestForumTable');
            $forum->load($cid[$key]);
            $forum->ordering = $value;
            $forum->store();
        }
    } //end saveOrder


    /**
     * Moves the forum with the specified ID up or down in the ordering list.
     * @param $id the ID of the forum to be moved
     * @param $direction a positive integer to move the forum down in the list
     * or negative to move it up in the list
     * @return true if the operation is successful, false otherwise. If false
     * is returned the error is also set using setError
    */
    function move($id, $direction)
    {
        $table = &JTable::getInstance('forum', 'SimplestForumTable');
        
        if (!$table->load($id)) {
            $this->setError(JText::_('ERROR COULD NOT ORDER FORUM'));
            return false;
        }

        if (!$table->move($direction)) {
            $this->setError(JText::_('ERROR COULD NOT ORDER FORUM'));
            return false;
        }

        return true;
    } //end orderUp

} //end class
?>
