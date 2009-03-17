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

/**
 * Simplest Forum Post Table
 *
 * @package Simplest Forum
 */
class SimplestForumTableExtensionAttributes extends JTable
{
    /**
     * The unique id of the attribute.
    */
    var $id = null;

    /**
     * The id of the post that this attribute is associated with (if any)
    */
    var $postId = null;

    /**
     * The id of the forum that this attribute is associated with (if any)
    */
    var $forumId = null;

    /**
     * The first field able to be stored by the extension (this is integer
     * only)
    */
    var $aux1 = null;

    /**
     * The second field able to be stored by the extension (this is integer
     * only)
    */
    var $aux2 = null;

    /**
     * The third field able to be stored by the extension (this is integer
     * only)
    */
    var $aux3 = null;

    /**
     * The fourth field able to be stored by the extension. This attribute is
     * general purpose.
    */
    var $aux4 = null;


    function SimplestForumTableExtensionAttributes($db)
    {
        parent::__construct('#__simplestforum_extension_attributes', 'id', $db);
    }

} //end class
?>
