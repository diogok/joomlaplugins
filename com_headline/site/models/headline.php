<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class headlineModelheadline extends JModel {

    private $table = "#__headline";

    function getAll(){
        $db =& JFactory::getDBO();

        $query = "SELECT a.id as id, a.title as title"
		. "\n FROM #__content AS a"	;
		
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

        if(count($rows) < 1) return $rows ;

        $query = "SELECT * from ".$this->table ;
		$db->setQuery( $query );
		$actual = $db->loadObject();

        if(!isset($actual->content_id)) return $rows ;
        foreach($rows as $row) {
            if($row->id == $actual->content_id) {
                if($actual->readmore == 1) {
                    $row->do_readmore = true;    
                }
                $row->headline_max_size = $actual->maxsize;
                $row->headline = true;
            } else {
                $row->headline = false;
            }
        }

        return $rows;
    }


    function save($id,$read,$max=150) {
        $db =& JFactory::getDBO();
        if($read == true) {
            $query = "update ".$this->table." set content_id = ".$id.", readmore = 1, maxsize = ".$max;
        } else {
            $query = "update ".$this->table." set content_id = ".$id.", readmore = 0, maxsize = ".$max;
        }
        $db->setQuery($query);
        $db->query();
    }

}
?>
