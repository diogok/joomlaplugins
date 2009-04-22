<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class frontnewsletterViewCreate extends JView {

    function display($tpl = null){
        $model = $this->getModel();

        parent::display($tpl);
    }
}
?>
