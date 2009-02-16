<?
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class frontbannerController extends JController {

    function display() {
        $m = $this->getModel();
        if($m->getPath() === false) {
            JRequest::setVar("err","Directory components/com_frontbanner/banners, or media/banners, must be writable, and it is not.");
        }
        parent::display();
    }

    function save() {
        
        $link = JRequest::getVar("link","NOLINK");
        if($link == "NOLINK" or strlen($link) <= 2) {
            JRequest::setVar("err","The link field is mandatory.");
            JRequest::setVar("task","display");
            $this->execute("display");
            $this->redirect();
            return ;
        }
        
        $tmpFile = $_FILES["file"]["tmp_name"];
        $type = $_FILES["file"]["type"];
        
        if(strpos($type,"jpeg") === false) {
            JRequest::setVar("err","Only JPEG files are accepted.");
            JRequest::setVar("task","display");
            $this->execute("display");
            $this->redirect();
            return ;
        }

        $model = $this->getModel();
        $id = $model->createBanner();

        $path = $model->getPath().DS.$id.'.jpg' ;
        move_uploaded_file($tmpFile,$path);

        $model->updateBanner($id,$path,$link);

        $this->execute("display");
    }

    function delete(){
        $id = JRequest::getVar("id");
        $this->getModel()->deleteBanner($id);
        JRequest::setVar("task","display");
        $this->execute("display");
        $this->redirect();
    }

}
?>
