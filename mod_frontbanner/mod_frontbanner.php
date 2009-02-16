<?
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).DS.'helper.php' );

$banners = mod_frontbannerHelper::getBanners( );
require( JModuleHelper::getLayoutPath( 'mod_frontbanner' ) );
?>
