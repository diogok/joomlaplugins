<?
class mod_frontbannerHelper {
    static public $tab = "#__frontbanner";
    static function getBanners(){
        $tab = self::$tab ;
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
        shuffle($bs);
        return $bs ;
    }
}
?>
