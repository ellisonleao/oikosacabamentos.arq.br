<?php
/**
 * @package JV News Module for Joomla! 1.5
 * @author http://www.ZooTemplate.com
 * @copyright (C) 2010- ZooTemplate.com
 * @license PHP files are GNU/GPL
**/

defined("_JEXEC")or die("Restrict  accesses helper.php");
//Get modules helper
require_once(dirname(__FILE__).DS.'helper.php');
$categories = ( array )$params->get('categories',array());
//var_dump($categories);
$jvNews = new modJVNewsHelper($params);
$templateType = $params->get('template_type');
if($templateType == 'horizon') {
	if(count($categories)){		
		$listSections = $jvNews->getSection();
		$imgAlign = $params->get('img_align');
		require(JModuleHelper::getLayoutPath('mod_jv_news',$templateType.DS.'default'));
	}
} else {
	$listSections = $jvNews->getSectionByVTemp();
	if($listSections) require(JModuleHelper::getLayoutPath('mod_jv_news',$templateType.DS.'default'));
}