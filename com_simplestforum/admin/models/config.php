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
 * Simplest Forum Forum Editing Model
 *
 * @package Simplest Forum
 */
class SimplestForumModelConfig extends JModel
{
	/**
	 * Overridden constructor
	 */
	function SimplestForumModelConfig()
	{
		parent::__construct();
	}

    /**
     * Returns a forum object from the database for the current forum id.
     * @return object the forum object or a blank stdClass if none is found
    */
    function getData()
    {
        //if there is no current data, get it
        if (empty($this->_data)) {
			$table = &JTable::getInstance('component');
			$table->loadByOption('com_simplestforum');

            $path = JPATH_COMPONENT.DS.'config.xml';
            $this->_data = new JParameter($table->params, $path);
        }

        return $this->_data;
    } //end getData


    /**
     * Binds and stores the Simplest Forum configuration parameters  object
     * from the data provided in the specified associative array.
     * @param data an associative array of values whose keys correspond
     * to the field names of a forum object
     * @return bool true if the store is successful, false otherwise.
     * On failure the error is set.
    */
    function store($data)
    {
		$table =& JTable::getInstance('component');
		if (!$table->loadByOption('com_simplestforum')) {
			return false;
		}

		$data['option'] = 'com_simplestforum';
		$table->bind($data);

		// pre-save checks
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// save the changes
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

        return true;
    } //end store function

} //end class
?>
