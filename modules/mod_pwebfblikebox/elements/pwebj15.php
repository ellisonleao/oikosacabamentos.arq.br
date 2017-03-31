<?php
/**
* @version 1.0
* @package PWebFBLikeBox
* @copyright © 2012 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

/**
 * Perfect-Web alert about wrong installation file.
 *
 * @since		1.6
 */
class JFormFieldPwebJ15 extends JFormField
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		if (version_compare(JVERSION, '1.6.0') >= 0) 
		{
			JFactory::getApplication()->enqueueMessage('You have installed module version for Joomla! 1.5. Go to <a href="index.php?option=com_installer&view=manage&filters[search]=perfect+facebook+like+box">Extensions Manager</a> and uninstall this module and then use proper installation file.', 'error');
		}
		return null;
	}
}