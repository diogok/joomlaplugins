<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class frontnewsletterModelfrontnewsletter extends JModel {
    private $tBase = "#__frontnewsletter";

    public function getNewsletters() {
        $db = JFactory::getDBO();
        $q = "select * from ".$this->tBase."_newsletters" ;
        $db->setQuery($q);
        $allNews = $db->loadObjectList();
        if(count($allNews) < 1) return array();
        $news = array();
        foreach($allNews as $n) {
            $ids[] = $n->id ;
            $news[$n->id] = clone $n;
        }
        $q = "select * from ".$this->tBase."_sent where id_newsletter in (".implode(",",$ids).");";
        $db->setQuery($q);
        $sents = $db->loadObjectList();
        if(count($sents) < 1) return $news ;
        foreach($sents as $sent) {
            $news[$sent->id_newsletter]->sent[] = clone $sent ;
        }
        return $news ;
    }

    function create($newsletter) {
        $db = JFactory::getDBO();
        $db->insertObject($this->tBase."_newsletters",$newsletter,"id");
        $id = $db->insertid();
        return $id ;
    }

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

    function update($news) {
        $db = JFactory::getDBO();
        $news->subject = $db->getEscaped($news->subject) ;
        $news->content = $db->getEscaped($news->content) ;
        $q  = "update ".$this->tBase."_newsletters set subject = '".$news->subject."', content = '".$news->content."' ";
        $q .= " where id = ".$news->id." ;";
        $db->setQuery($q);
        $db->query();
    }

    public function getUsers($ids) {
        $db = JFactory::getDBO();
        $q = "select id,name,email from #__users where id in (".implode(",",$ids).")";
        $db->setQuery($q);
        return $db->loadObjectList();
    }

    public function insertSent($news,$users) {
        $db = JFactory::getDBO();

        $sent = new StdClass ;
        $sent->id = null;
        $sent->date = time();
        $sent->id_newsletter = $news->id;
        $sent->content = $news->content;
        $sent->subject  = $news->subject ;

        $db->insertObject($this->tBase."_sent",$sent,"id");

        if(count($users) < 1) return ;
        foreach($users as $user) {
            $uSent = new StdClass ;
            $uSent->id = null;
            $uSent->id_sent = $sent->id;
            $uSent->id_user = $user->id ;
            $uSent->email = $user->email;
            $db->insertObject($this->tBase."_sent_users",$uSent,"id");
        }
    }
}
?>
