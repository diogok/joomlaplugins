<?
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class frontnewsletterController extends JController {

    function display() {
        $user =& JFactory::getUser();
        if(stripos($user->usertype,"admin") === false) {
            die('Restricted access');
        }
        parent::display();
    }

    function create() {
        $model = $this->getModel();

        $news = new StdClass;
        $news->id = null;
        $news->content = $_POST["content"];
        $news->subject = JRequest::getVar("subject","Newsletter");
        $news->date = time();
        
        $id = $model->create($news);
        JRequest::setVar("msg","Newsletter created");
        $this->execute("display");
    }

    function save() {
        $model = $this->getModel();
        $id  = JRequest::getVar("news_id",0) ;
        $news = $model->get($id) ;
        $news->subject = JRequest::getVar("subject","Newsletter");
        $news->content = $_POST["content"];
        $model->update($news);
        JRequest::setVar("msg","Newsletter saved");
        $this->execute("display");
    }

    function send(){
        $model = $this->getModel();
        $id  = JRequest::getVar("news_id",0) ;
        $news = $model->geT($id);

        $ids = JRequest::getVar("users",array());
        $users = $model->getUsers($ids);

        $mailer =& JFactory::getMailer();
        $sender=& JFactory::getUser();

        $message = $news->content ;
        preg_match_all('@<img src="([^"]+)"@',$message,$reg) ;
        if(count($reg[1]) >= 1) {
            $i = 0;
            foreach($reg[1] as $img) {
                $i++;
                $mailer->AddEmbeddedImage($img,"img-".$i,$img,"base64","image/jpeg");
                $message = str_replace($img,"cid:img-".$i,$message);
            }
        }

        $mailer->setSender($sender->email);
        $mailer->setSubject(stripslashes( $news->subject));
        $mailer->isHTML(true);
        $mailer->setBody(stripslashes($message));

        $i = 0;
        foreach($users as $user)
        {
            //$mailer->addRecipient($user->email);
            $mailer->addBCC($user->email);
            $i++ ;
            if($i == 10) {
                $mailer->send();
                $mailer->ClearAddresses();
                $mailer->ClearAllRecipients();
                $mailer->ClearBCCs();
                $mailer->ClearCCs();
                $i = 0 ;
            }
        }

        if($i != 0) {
            $mailer->send();
        }

        $model->insertSent($news,$users) ;

        JRequest::setVar("msg","Newsletter sent");
        $this->execute("display");
    }

}
?>
