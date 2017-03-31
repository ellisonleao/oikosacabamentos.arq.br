<?php
/**
* @version 1.0.1
* @package PWebFBLikeBox
* @copyright © 2012 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Perfect-Web Tips.
 *
 * @since		1.5
 */
class JElementPwebTip extends JElement
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	 var	$_name = 'PwebTip';

	function fetchElement($name, $value, &$node, $control_name = 'params')
	{
		$html = '';
		
		$module_id = JRequest::getVar('id', 0);
		if ($module_id == 0) 
		{
			$module_cid = JRequest::getVar('cid', array());
			if (count($module_cid)) $module_id = $module_cid[0];
		}
		
		if ($module_id > 0) 
		{
			switch ($node->attributes('tip'))
			{
				case 'code':
					$html = 
					'<pre style="float:left;margin:7px 0;font-size:11px">&lt;a href=&quot;javascript:pwebFBLikeBox'.$module_id.'.toggleBox()&quot;&gt;Click here&lt;/a&gt;</pre>';
					break;
			}
		}

		return $html;
	}
}