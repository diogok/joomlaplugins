<?
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );

class frontbannerModelfrontbanner extends JModel {

    public $tab = "#__frontbanner";

    public function getPath() {
        $path = 'media'.DS.'banners' ;
        if(is_writable($path)) {
            return $path ;
        }
         $path = 'components'.DS.'com_frontbanner'.DS.'banners' ;
        if(is_writable($path)) {
            return $path ;
        }
       return false; 
    }

    function getBanners(){
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "select * from ".$tab ;
        $db->setQuery($q);
        $banners = $db->loadAssocList();
        if(count($banners) < 1) {
            return array();
        }
        $bs = array();
        foreach($banners as $banner) {
            if(file_exists($banner["path"])) {
                $bs[] = $banner ;
            }
        }
        return $bs ;
    }

    function getActive(){
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "select * from ".$tab." where active = 1" ;
        $db->setQuery($q);
        $banners = $db->loadResult();
        return $banners[0];
    }

    function createBanner() {
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "insert into ".$tab." (active) values ( 0 )" ;
        $db->setQuery($q);
        $db->query();
        $id = $db->insertid();
        return $id ;
    }

    function deleteBanner($id) {
        $tab = $this->tab ;
        $db = JFactory::getDBO();
        $q = "select * from ".$tab." where id = ".$id ;
        $db->setQuery($q);
        $b = $db->loadAssoc();
        @unlink($b["path"]);
        $q = "delete from ".$tab." where id = ".$id ;
        $db->setQuery($q);
        $db->query();
    }

    function updateBanner($id,$path,$link){
        $tab = $this->tab ;
        $db = JFactory::getDBO();

        if(strpos($link,"http://") === false)  {
            $link = "http://".$link ;
        }

        $q = " update ".$tab." set path = '".$path."', link = '".$link."' where id = ".$id.";";
        $db->setQuery($q);
        $db->query();
    }
}
?>
