<?php
/**
 * @package JV News Module for Joomla! 1.5
 * @author http://www.ZooTemplate.com
 * @copyright (C) 2010- ZooTemplate.com
 * @license PHP files are GNU/GPL
**/

define( 'DS', DIRECTORY_SEPARATOR );
$rootFolder = explode(DS,dirname(__FILE__));
//current level in diretoty structure
$currentfolderlevel = 2;
array_splice($rootFolder,-$currentfolderlevel);
$base_folder = implode(DS,$rootFolder);
if(is_dir($base_folder.DS.'libraries'.DS.'joomla')) {
	define( '_JEXEC', 1 );
	define('JPATH_BASE',implode(DS,$rootFolder));
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	require_once(JPATH_BASE .DS.'libraries/joomla/factory.php');
	require_once (JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php');	
	$mainframe =& JFactory::getApplication('site');
	$mainframe->initialise();
	require_once 'jvnews_ajax.php';
	$secId = isset($_REQUEST['secId']) ? $_REQUEST['secId'] : '';	
	$strCatId = isset($_REQUEST['catId']) ? $_REQUEST['catId'] : '';
	$moduleId = isset($_REQUEST['moduleId']) ? $_REQUEST['moduleId'] : '';
	$noHeadLine = isset($_REQUEST['noHeadline']) ? $_REQUEST['noHeadline'] : '';
	$noLink = isset($_REQUEST['noLink']) ? $_REQUEST['noLink'] : '';
	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
	$ajaxHelper = new JVNewsAjaxHelper($moduleId);
	$str = $strItemOfCat = $strMoreLink = '';	
	$ajaxHelper->renderHeadLine($secId,$noHeadLine,$noLink,$str,$strMoreLink);
	switch($action){
		case "save":									
			if($strCatId) {
				$strCatId = substr($strCatId,0,strrpos($strCatId,","));
				$aryCatId = explode(",",$strCatId);				
				foreach($aryCatId as $item){
					$strItemOfCat.= $ajaxHelper->getContentByCatId($item);
				}
			}
			$aryJson = array('secId'=>$secId,'strItem'=>$strItemOfCat,'strHeadLine'=>$str,'strMoreLink'=>$strMoreLink);
			echo json_encode($aryJson);
		break;
		case "reset":
			$aryJson = array('secId'=>$secId,'strHeadLine'=>$str,'strMoreLink'=>$strMoreLink);
			echo json_encode($aryJson);
		break;	
	}
}
?>