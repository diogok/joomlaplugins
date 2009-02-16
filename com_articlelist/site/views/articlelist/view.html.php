<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class articlelistViewarticlelist extends JView {

    function display($tpl = null){
        $model = $this->getModel();
        $bs = $model->getAll();
        $this->assignRef("articles",$bs);

        parent::display($tpl);
    }
}
?>
