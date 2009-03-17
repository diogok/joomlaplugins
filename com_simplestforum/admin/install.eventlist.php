<?php
defined('_JEXEC') or die();

function com_install() {
    $installer = new SimplestForumInstaller('color:#0f0;', 'color:#f00;font-weight:bold;', 'color:#f72;');

    echo '<h3>Verifying Dependencies</h3>';
    $installer->verifyPHP();
    $installer->verifyMySQL();
    $installer->writeLog();

    $failures = $installer->getErrors();
    $warnings = $installer->getWarnings();

    echo 'Install Completed with '.$failures.' failures and '.$warnings.' warnings<br />';

    echo '<h3>Getting Support</h3>';
    echo '<p>For support documentation and getting started information, please see <a href="http://simplestforum.org/index.php?option=com_content&amp;view=category&amp;id=35&amp;Itemid=58" onclick="javascript:window.open(\'http://simplestforum.org/index.php?option=com_content&amp;view=category&amp;id=35&amp;Itemid=58\', \'_blank\', \'\', false);return false;">http://simplestforum.org/</a>.</p>';

    return $failures == 0;
} //end com_install

class SimplestForumInstaller
{
    var $_log;
    var $_errors;
    var $_warnings;
    var $_messageStyle;
    var $_errorStyle;
    var $_warningStyle;

    function __construct($messageStyle = '', $errorStyle = '', $warningStyle = '')
    {
        $this->_log = array();
        $this->_errors = 0;
        $this->_warnings = 0;

        $this->_messageStyle = $messageStyle;
        $this->_errorStyle = $errorStyle;
        $this->_warningStyle = $warningStyle;
    } //end constructor

    function getWarnings()
    {
        return $this->_warnings;
    } //end getWarnings

    function getErrors()
    {
        return $this->_errors;
    } //end getErrors

    function writeLog()
    {
        echo '<ul>';
        foreach ($this->_log as $log) {
            switch ($log->type) {
                case 'error':
                    echo '<li style="'.$this->_errorStyle.'">';
                    break;
                case 'warning':
                    echo '<li style="'.$this->_warningStyle.'">';
                    break;
                default:
                    echo '<li style="'.$this->_messageStyle.'">';
                    break;
            }
            echo $log->msg;
            echo '</li>';
        }
        echo '</ul>';
    } //end writeLog

    function _logMessage($msg, $type = 'message')
    {
        $message = new stdClass();
        $message->msg = $msg;
        $message->type = $type;
        $this->_log[] = $message;

        if ($type == 'error') {
            $this->_errors++;
        }  else if ($type == 'warning') {
            $this->_warnings++;
        }
    } //end _logMessage

    function clearLog()
    {
        $this->_log = array();
    } //end clearLog

    function verifyPHP()
    {
        $version = phpversion();

        if (!ereg('5(\.[[:digit:]]+){0,2}', $version)) {
            $this->_logMessage('Incompatible version of PHP detected ('.$version.'). PHP 5.x or better must be installed in order to use the Simplest Forum', 'error');
            return false;
        }

        $this->_logMessage('Acceptable PHP version found: '.$version);
        return true;
    } //end verifyPHP

    function verifyMySQL()
    {
        $query = 'SELECT VERSION()';
        $db = &JFactory::getDBO();
        $db->setQuery($query);
        $version = $db->loadResult();

        if (!ereg('5(\.[[:digit:]]+){0,2}', $version)) {
            $this->_logMessage('Incompatible version of MySQL detected ('.$version.'). MySQL 5.x or better must be installed in order to use the Simplest Forum', 'error');
            return false;
        }

        $this->_logMessage('Acceptable MySQL version found: '.$version);
        return true;
    } //end verifyMySQL

} //end class
?>
