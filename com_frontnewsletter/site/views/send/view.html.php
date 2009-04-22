<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class frontnewsletterViewsend extends JView {

    function display($tpl = null){
        $model = $this->getModel();
        $id = JRequest::getVar("news_id",0);
        $new = $model->get($id);
        $this->assignRef("new",$new);
        $users = $model->getUsers();
        $this->assignRef("users",$users);
        parent::display($tpl);
    }
}
?>
