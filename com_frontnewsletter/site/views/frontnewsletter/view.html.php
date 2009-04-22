<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class frontnewsletterViewfrontnewsletter extends JView {

    function display($tpl = null){
        $model = $this->getModel();
        $news = $model->getNewsletters();
        $this->assignRef("news",$news);
        parent::display($tpl);
    }
}
?>
