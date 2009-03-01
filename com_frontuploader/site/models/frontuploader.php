<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class frontuploaderModelfrontuploader extends JModel {

    public $tab = "#__frontuploader";

    public function getPath() {
        $path = 'components'.DS.'com_frontuploader'.DS.'files' ;
        if(is_writable($path)) {
            return $path ;
        }
        $path = 'media'.DS.'frontuploader' ;
        if(is_writable($path)) {
            return $path ;
        }
        return false; 
    }

    function getFiles(){
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "select * from ".$tab ;
        $db->setQuery($q);
        $files = $db->loadAssocList();
        return $files;
    }
    
    public function deleteFile($i){
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "select * from ".$tab." where id = ".$i ;
        $db->setQuery($q);
        $file = $db->loadObject() ;
        unlink($file->path);
        $q  = "delete from ".$tab." where id = ".$i ;
        $db->setQuery($q);
        $db->query();
    }

    function insertFile($name,$path) {
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "insert into ".$tab." ( name , path) values ( '".$name."','".$path."');" ;
        $db->setQuery($q);
        $db->query() ;
    }

}
?>
