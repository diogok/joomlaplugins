<?
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT.DS.'controller.php' );
 
$path = JPATH_COMPONENT.DS.'controller'.'.php';
require_once $path;
 
// Create the controller
$classname    = 'frontbannerController';
$controller   = new $classname();
 
// Perform the Request task
$controller->execute( JRequest::getVar( 'task' ) );
 
// Redirect if set by the controller
$controller->redirect();
?>
