<?php
// MODEL PHP of QVARNIS
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );
class QvarnisModelQvarnis extends JModel
{
    /**
    * Gets the greeting
    * @return string The greeting to be displayed to the user
    */
    function getUsers()
    {
    	$db = &JFactory::getDBO();
         // get a list of all users
        $query = 'SELECT a.email
                  FROM `#__users` AS a'
        ;
        $db->setQuery($query);
        $items = ($items = $db->loadObjectList())?$items:array();
        return $items;
    }
}

?>