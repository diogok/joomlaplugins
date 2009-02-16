<?
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).DS.'helper.php' );

$db =& JFactory::getDBO();

$query = "SELECT * from #__headline" ;
$db->setQuery( $query );
$actual = $db->loadObject();

$query = "SELECT * from #__content where id = ".$actual->content_id ;
$db->setQuery( $query );
$article = $db->loadObject();

$t = $article->introtext ;
$t = strip_tags($t);
$maxSize = $actual->maxsize ;
if(strlen($t) > $maxSize) {
    while(preg_match("/[\w]/",$t[$maxSize])) {
        $maxSize++ ;
    }
    $t = substr($t,0,$maxSize);
    $t .= "...";
}
$article->micro_text = $t;

require( JModuleHelper::getLayoutPath( 'mod_headline' ) );
?>
