<?

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class frontbannerViewfrontbanner extends JView {

    function display($tpl = null){
        $model = $this->getModel();
        $bs = $model->getBanners();
        $this->assignRef("banners",$bs);

        $action = JRoute::_('index.php?option=com_frontbanner&task=save');
        $this->assignRef("action",$action);

        $user = JFactory::getUser();
        if($user->authorize('com_content','edit','content','all')) {
            $editor = true;
        }else {
            $editor = false ;
        }
        $this->assignRef("editor",$editor);

        $this->assignRef("err",JRequest::getVar("err"));

        parent::display($tpl);
    }
}
?>
