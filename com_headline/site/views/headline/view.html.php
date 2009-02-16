<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class headlineViewheadline extends JView {

    function display($tpl = null){
        $model = $this->getModel();
        $arts = $model->getAll();
        $this->assignRef("articles",$arts);
        if(JRequest::getVar("task") == "save") {
            $msg = "Headline saved with success.";
            $this->assignRef("mOk",$msg);
        }
        parent::display($tpl);
    }
}
?>
