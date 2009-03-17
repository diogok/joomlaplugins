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
class SimplestForumTablePost extends JTable
{
    /**
     * The unique id of the post.
    */
    var $id = null;

    /**
     * The subject of the post.
    */
    var $subject = null;

    /**
     * The main content of the post.
    */
    var $message = null;

    /**
     * The id of the user originating the post, if any.
    */
    var $authorId = null;

    /**
     * The parent post id, if any.
    */
    var $parentId = null;

    /**
     * The id of the forum that the post belongs to.
    */
    var $forumId = null;

    /**
     * The number of the thread that this post belongs to.
    */
    var $thread = null;

    /**
     * Whether or not the post is visible to the public.
    */
    var $published = null;

    /**
     * The date and time that the post was submitted on.
    */
    var $date = null;

    /**
     * The date / time that the post was modified on.
    */
    var $modified = null;

    /**
     * The ID of the user that modified the post.
    */
    var $modified_by = null;


    function SimplestForumTablePost( $db ) {
        parent::__construct( '#__simplestforum_post', 'id', $db );
    }


    /**
     * Checks to ensure that the message and subject have not been left blank.
    */
    function check()
    {
        if (isset($this->message) && !strlen($this->message)) {
            $this->setError(JText::_('ERROR MESSAGE MAY NOT BE BLANK'));
            return false;
        }

        if (isset($this->subject) && !strlen($this->subject)) {
            $this->setError(JText::_('ERROR SUBJECT MAY NOT BE BLANK'));
            return false;
        }

        return parent::check();
    } //end check

} //end class
?>
