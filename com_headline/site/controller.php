<?
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class headlineController extends JController {

    function display() {
        parent::display();
    }

    function save() {
        $m = $this->getModel();
        if(JRequest::getVar("readmore") == 1) {
            $readmore = true ;
        } else {
            $readmore = false ;
        }
        $m->save(JRequest::getVar("article"),$readmore,JRequest::getVar("maxsize"));
        $this->display();
    }


}
?>
