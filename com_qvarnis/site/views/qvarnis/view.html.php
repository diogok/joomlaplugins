<?php
// no direct access
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
 
/*
 * HTML View class for the qvarnis Component
 */
 
class QvarnisViewQvarnis extends JView
{
    function display($tpl = null)
    {
/***************** BEGIN view init ****************************/ 
    	global $mainframe;

		$pathway 	= & $mainframe->getPathway();
		$document	= & JFactory::getDocument();

		// Get the parameters of the active menu item
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();
		$params	= &$mainframe->getParams();

		$category	= $this->get('category');
		$items		= $this->get('data');
		$total		= $this->get('total');
		$pagination	= &$this->get('pagination');

		// Set page title
		$menus	= &JSite::getMenu();
		$menu	= $menus->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object( $menu )) {
			$menu_params = new JParameter( $menu->params );
			if (!$menu_params->get( 'page_title')) {
				$params->set('page_title',	$category->title);
			}
		} else {
			$params->set('page_title',	$category->title);
		}

		$document->setTitle( $params->get( 'page_title' ) );
    	$this->assignRef('image',		$image);
		$this->assignRef('params',		$params);
		$this->assignRef('items',		$items);
		$this->assignRef('category',	$category);
		$this->assignRef('pagination',	$pagination);
    	
/***************** END view init ****************************/    	
    	
    	
    	
       	$subject=JRequest::getVar( 'subject' );
   		$message=JRequest::getVar( 'message' );
   		if(!empty($subject) && !empty($message))
   		{
   			$model =& $this->getModel();
			$users = $model->getUsers();
   			$mailer =& JFactory::getMailer();
			//$params =& JComponentHelper::getParams( 'com_massmail' );

			// Build e-mail message format
			$sender=& JFactory::getUser();
			$mailer->setSender($sender->email);
			$mailer->setSubject(stripslashes( $subject));
			$mailer->setBody(stripslashes($message));
			//$mailer->IsHTML($mode);

			// Add recipients

			foreach($users as $user)
			{
				$mailer->addRecipient($user->email);
            //test
			}

			// Send the Mail
			$rs	= $mailer->Send();

			// Check for an error
			if ( JError::isError($rs) )
			{
				$msg = $rs->getError();
			}
			else
			{
				$msg = $rs ? JText::sprintf( 'E-mail sent!') : JText::_('The mail could not be sent');
			}
			$this->assignRef('msg',		$msg);
			parent::display($tpl);
			return;
   		}
    	
        parent::display($tpl);
    }
}
?>
