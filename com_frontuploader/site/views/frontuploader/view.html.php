<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class frontuploaderViewfrontuploader extends JView {

    function display($tpl = null){
        $model = $this->getModel();
        $files = $model->getFiles();
        $this->assignRef("files",$files) ;
        $user = JFactory::getUser();
        if(stripos($user->usertype,"admin") === false) {
            $isAdmin = false;
        } else {
            $isAdmin = true;
        }
        $this->assignRef("isAdmin",$isAdmin);
        parent::display($tpl);
    }
}
?>
