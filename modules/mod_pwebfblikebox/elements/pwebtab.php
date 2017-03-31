<?php
/**
* @version 1.0
* @package PWebFBLikeBox
* @copyright © 2012 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.pane');

/**
 * Perfect-Web Configuration slide tabs.
 *
 * @since		1.5
 */
class JElementPwebTab extends JElement {

    /**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var $_name = 'PwebTab';
	
	function fetchTooltip()
	{
		return;
	}
	
    function fetchElement($name, $value, &$node, $control_name = 'params')
    {
        $text = $node->_attributes['description'];
		
        $html = '';
		if (class_exists('JPaneSliders')) {
			$html  = '</td></tr></table>';
			$html .= JPaneSliders::endPanel();
			$html .= JPaneSliders::startPanel(JText::_($text), $text);
			$html .= '<table width="100%" class="paramlist admintable" cellspacing="1">';
			$html .= '<tr><td colspan="2" style="padding:0">';
		}

        return $html;
    }
}