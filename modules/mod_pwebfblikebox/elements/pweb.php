<?php
/**
* @version 1.0.2
* @package PWebFBLikeBox
* @copyright © 2013 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.parameter.element.radio');

/**
 * Perfect-Web
 *
 * @since		1.6
 */
class JElementPweb extends JElementRadio
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	var	$_name = 'Pweb';
	var $extension = 'mod_pwebfblikebox';
	var $documentation = 'http://www.perfect-web.co/joomla/modules/facebook-like-box-sidebar/documentation';
	
	function fetchElement($name, $value, &$node, $control_name = 'params')
	{		
		$doc =& JFactory::getDocument();
		
		// add documentation toolbar button
		$button = '<a href="'.$this->documentation.'" style="font-weight:bold;border-color:#025A8D;background-color:#DBE4E9;" target="_blank"><span class="icon-32-help"> </span> '.JText::_('MOD_PWEBFBLIKEBOX_DOCUMENTATION').'</a>';
		
		$bar =& JToolBar::getInstance();
		$bar->appendButton('Custom', $button, $this->extension.'-docs');
		
		
		// add feed script
		if ($value)
		{
			$doc->addScriptDeclaration(
				'(function(){'.
				'var pw=document.createElement("script");pw.type="text/javascript";pw.async=true;'.
				'pw.src="//www.perfect-web.co/updates/feed.js?ext='.$this->extension.'&j='.JVERSION.'";'.
				'var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(pw,s);'.
				'})();'
			);
		}
		
		$doc->addScriptDeclaration(
			'window.addEvent("domready",function(){'.
				// encode Facebook Page URL
				'if($("paramshref")){$("paramshref").addEvent("change",function(){'. //j1.5
					'this.value=encodeURI(decodeURI(this.value))'.
				'})}'.
			'});'
		);
		
		return parent::fetchElement($name, $value, $node, $control_name);
	}
}