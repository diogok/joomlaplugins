<?
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class frontuploaderController extends JController {

    function display() {
        $file = $_FILES["file"];
        $fname = $file["name"];
        $ftmp = $file["tmp_name"];
        $name = $_POST["name"];
        $path = $this->getModel()->getPath() . DS . $fname ;
        if(file_exists($ftmp)) {
            move_uploaded_file($ftmp,$path);
            $this->getModel()->insertFile($name,$path);
        } 
        if(strlen($_GET["id_to_delete"]) >= 1) {
            $model = $this->getModel();
            $model->deleteFile($_GET["id_to_delete"]);
        }
        parent::display();
    }


}
?>
