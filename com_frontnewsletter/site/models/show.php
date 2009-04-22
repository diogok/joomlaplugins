<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class frontnewsletterModelshow extends JModel {
    private $tBase = "#__frontnewsletter";

    public function get($id) {
        $db = JFactory::getDBO();
        $q = "select * from ".$this->tBase."_newsletters where id = ".$id ;
        $db->setQuery($q);
        $news = $db->loadObjectList();
        $new = $news[0];
        $q = "select * from ".$this->tBase."_sent where id_newsletter  = ".$new->id.";";
        $db->setQuery($q);
        $sents =  $db->loadObjectList();
        if(count($sents) < 1) return $new;
        foreach($sents as $sent) {
            $new->sent[$sent->id] = clone $sent ;
            $ids[] = $sent->id ;
        }
        $q = "select * from ".$this->tBase."_sent_users where id_sent in  (".implode(",",$ids).");";
        $db->setQuery($q);
        $users = $db->loadObjectList();
        if(count($users) < 1)  return $new;
        foreach($users as $user) {
            $new->sent[$user->id_sent]->users[$user->id] = clone $user;
        }
        return $new ;
    }

}
?>
