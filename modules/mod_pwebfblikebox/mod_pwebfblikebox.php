<?php
/**
* @version 1.0.2
* @package PWebFBLikeBox
* @copyright © 2013 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).'/helper.php');

$app =& JFactory::getApplication();

$params->def('id', $module->id);
if ($app->getCfg('debug') OR JRequest::getInt('debug')) $params->set('debug', 1);

if (strpos($params->get('layout', 'box'), 'static') === false)
{	
	if (JRequest::getCmd('tmpl') == 'component') return; //j1.5
	
	// Set media path
	$media_url = JURI::base(true).'/modules/mod_pwebfblikebox/'; //j1.5
	
	JHtml::_('behavior.mootools'); //j1.5
	
	$doc =& JFactory::getDocument();
	$doc->addScript($media_url.'js/mootools.likebox.js');
	$doc->addStyleSheet($media_url.'css/likebox.css'); //j1.5
	
	// IE CSS
	if (!defined('MOD_PWEB_FBLIKEBOX_IE')) {
		define('MOD_PWEB_FBLIKEBOX_IE', 1);
		$doc->addCustomTag(
			 '<!--[if lte IE 8]>'."\r\n"
			.'<link rel="stylesheet" href="'.$media_url.'css/ie.css" />'."\r\n"
			.'<![endif]-->'."\r\n"
		);
	}
}

// Auto RTL
$rtl = (int)JFactory::getLanguage()->isRTL();
$params->set('rtl', $rtl);
if ($rtl) $params->set('align', $params->get('align') == 'left' ? 'right' : 'left');

// Set params
modPWebFBLikeBoxHelper::setParams($params);

// Get LikeBox
$LikeBox = modPWebFBLikeBoxHelper::displayLikeBox();

require JModuleHelper::getLayoutPath('mod_pwebfblikebox', $params->get('layout', 'box'));
