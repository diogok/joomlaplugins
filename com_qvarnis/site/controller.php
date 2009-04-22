<?php
// No direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
/*
 * qvarnis Component Controller
 */
class QvarnisController extends JController
{
	function __construct()
	{
    	parent::__construct();
 
    	// Register Extra tasks
    	$this->registerTask('skicka','skicka');
	}
	
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {
        parent::display();
    }
    
    function skicka()
    {
    	//JRequest::setVar( 'view', 'qvarnis' );
    	//JRequest::setVar( 'layout', 'form'  );
    	//JRequest::setVar('hidemainmenu', 1);
 
    	parent::display();
    }
}
?>