<?
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');
 
class articlelistController extends JController {

    function display() {
            // Global Checkin
            $doCheckin = true ;
            if($doCheckin) {
                echo "<!-- Global checkin -->";
                $user = & JFactory::getUser();
                $db =& JFactory::getDBO();
                $nullDate = $db->getNullDate();

                $tn = "#__content";

                $query = 'SELECT checked_out FROM '.$tn.' WHERE checked_out > 0';
                $db->setQuery( $query );
                $res = $db->query();

                $query = 'UPDATE '.$tn.' SET checked_out = 0, checked_out_time = '.$db->Quote($nullDate);
                $query .= ' WHERE checked_out > 0';

                $db->setQuery( $query );
                $res = $db->query();
            }
            parent::display();
    }

}
?>
