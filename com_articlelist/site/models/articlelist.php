<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class articlelistModelarticlelist extends JModel {


    function getAll(){
        $db =& JFactory::getDBO();

        $query = "SELECT a.id as id, a.title as title"
		. "\n FROM #__content AS a"	;
		
		
		$db->setQuery( $query );
		$rows = $db->loadObjectList();
        return $rows;
    }

}
?>
