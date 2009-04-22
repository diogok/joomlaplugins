<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class frontnewsletterModelsend extends JModel {
    private $tBase = "#__frontnewsletter";

    public function get($id) {
        $db = JFactory::getDBO();
        $q = "select * from ".$this->tBase."_newsletters where id = ".$id ;
        $db->setQuery($q);
        $news = $db->loadObjectList();
        $new = $news[0];
        $q = "select * from ".$this->tBase."_sent where id_newsletter  = ".$new->id.";";
        $db->setQuery($q);
        $new->sent = $db->loadObjectList();
        return $new ;
    }

    public function getUsers() {
        $db = JFactory::getDBO();
        $q = "select id,name,email from #__users";
        $db->setQuery($q);
        return $db->loadObjectList();
    }

}
?>
