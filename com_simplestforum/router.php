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
function SimplestForumBuildRoute(&$query)
{
    $view = '';
	$segments = array();

    if (isset($query['view'])) {
        $view = $query['view'];
        $segments[] = $query['view'];
        unset($query['view']);
    }

    if (isset($query['forumId'])) {
        $segments[] = $query['forumId'];
        unset($query['forumId']);
    }

    if (isset($query['parentId'])) {
        $segments[] = $query['parentId'];
        unset($query['parentId']);
    }

    if ($view == 'postlist' && isset($query['topic'])) {
        $segments[] = $query['topic'];
        unset($query['topic']);
    }

    if ($view == 'postedit' && isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    }

	return $segments;
}

/**
 * The routes are constructed as follows:

   View         Route Construction
   postlist     /postlist/FORUM_ID/PARENT_ID/TOPIC_MODE
   postedit     /postedit/FORUM_ID/PARENT_ID/ID
   topiclist    /topiclist/FORUM_ID
   forumlist    /forumlist/
*/
function SimplestForumParseRoute($segments)
{
	$vars = array();

	//Get the active menu item
	$menu =& JSite::getMenu();
	$item =& $menu->getActive();

	// Count route segments
	$count = count($segments);
    $view = $segments[0];

	//Handle View and Identifier
	switch($view) {
        case 'extension':
            $vars['view'] = 'extension';
            break;
		case 'forumlist':
			$vars['view']   = 'forumlist';
            break;
		case 'topiclist':
			$vars['view']   = 'topiclist';
            if (isset($segments[1])) {
                $vars['forumId']   = $segments[1];
            }
            break;
        case 'postlist':
			$vars['view']   = 'postlist';
            if (isset($segments[1])) {
                $vars['forumId']   = $segments[1];
            }
            if (isset($segments[2])) {
                $vars['parentId']   = $segments[2];
            }
            if (isset($segments[3])) {
                $vars['topic']   = $segments[3];
            }
            break;
        case 'postedit':
			$vars['view']   = 'postedit';
            if (isset($segments[1])) {
                $vars['forumId']   = $segments[1];
            }
            if (isset($segments[2])) {
                $vars['parentId']   = $segments[2];
            }
            if (isset($segments[3])) {
                $vars['id']   = $segments[3];
            }
            break;
	}

    if (isset($item->id)) {
        $vars['Itemid'] = $item->id;
    }

	return $vars;
}
?>
