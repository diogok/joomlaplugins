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
 * A helper class for some common forum operations.
 * @package Simplest Forum
*/
class ForumHelper
{
    /**
     * Returns a forum object for the forum whose id is the specified id.
     * @param forumId the id of the desired forum
     * @return object a forum object or null if none is found
    */
    function getForum($forumId)
    {
        $database = &JFactory::getDBO();
        $query = 'SELECT a.*
                  FROM #__simplestforum_forum AS a
                  WHERE a.id = '.(int)$forumId;
        $database->setQuery($query);

        return $database->loadObject();
    } //end getForumName

    /**
     * Returns the relative date / time that the date occurred from with
     * respect to right now.
     * parameter format.
     * @param $date string Y-m-d H:i:s date
    */
    function getDate($date)
    {
        $lang = &JFactory::getLanguage();
        $lang->load('com_simplestforum');

        $time = strtotime(gmdate('Y-m-d H:i:s')) - strtotime($date);
        $minutes = (int)($time / 60);
        $hours = (int)($minutes / 60);
        $days = (int)($hours / 24);

        if ($days == 0) {
            if ($hours == 0) {
                if ($minutes == 0) {
                    return JText::sprintf('SECONDS AGO X1', $time);
                }

                if ($minutes == 1) {
                    return JText::_('MINUTE AGO');
                }

                return JText::sprintf('MINUTES AGO X1', $minutes);
            }

            if ($hours == 1) {
                return JText::_('HOUR AGO');
            }

            return JText::sprintf('HOURS AGO X1', $hours);
        }
        
        if ($days == 1) {
            return JText::_('YESTERDAY');
        }

        if ($days <= 30) {
            return JText::sprintf('DAYS AGO X1', $days);
        }

        return JHTML::_('date', $date);
    } //end getDate

    /**
     * Returns true if the currently logged in user has *at least* the
     * specified permission. The permission may be specified as the integer gid
     * or the name of the user type, i.e., 'Registered'.
     * @param $testGroup the string group to test against for the specified forum, i.e., 'view', 'post', or 'moderate'
     * @param $forum the ID of the forum to test or the forum itself
     * @param $user an optional user, if none is supplied the current user is used
     * @return true if the user is permitted for the specified testGroup
    */
    function verifyPermissions($testGroup, $forum, $user = null)
    {
        if (is_numeric($forum)) {
            $forum = ForumHelper::getForum($forum);
        }
        
        if (!$forum) {
            throw new Exception('Invalid forum supplied for verifyPermissions');
        }

        $params = &JComponentHelper::getParams('com_simplestforum');

        switch ($testGroup) {
            case 'view':
                $testGroup = $forum->viewgid === null?$params->get('viewgid'):$forum->viewgid;
                break;
            case 'post':
                $testGroup = $forum->postgid === null?$params->get('postgid'):$forum->postgid;
                break;
            case 'moderate':
                $testGroup = $forum->moderategid === null?$params->get('moderategid'):$forum->moderategid;
                break;
            default:
                throw new Exception('Invalid test group supplied for verifyPermissions');
                break;
        }

        // if we checking for permissions for a guest, it doesn't matter what
        // our user level is
        if (!$testGroup) {
            return true;
        }

        if (!$user) {
            $user = &JFactory::getUser();
        }

        $gid = $user->get('gid');
        $gid = $gid?$gid:0;

        return $gid >= $testGroup;
    } //end verifyPermissions

    public static function renderBackLink()
    {
        $params = &JComponentHelper::getParams('com_simplestforum');

        if ($params->get('showbacklink')) {
?>
<div style="text-align:center;margin-top:10px;">
    <a href="http://simplestforum.org"><img style="border:0px;" src="components/com_simplestforum/assets/sflink.png" alt="Power by Simplest Forum - Copyright Ambitionality Software LLC 2008. All rights reserved." /></a>
</div>
<?php
        }
    } //end renderBackLink

} //end class
?>
