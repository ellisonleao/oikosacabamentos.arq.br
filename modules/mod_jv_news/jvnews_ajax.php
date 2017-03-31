<?php
/**
 * @package JV News Module for Joomla! 1.5
 * @author http://www.ZooTemplate.com
 * @copyright (C) 2010- ZooTemplate.com
 * @license PHP files are GNU/GPL
**/

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
class JVNewsAjaxHelper{
	function __construct($moduleId){
		$this->getModuleConfig($moduleId);
	}	
	/*
	 * Function get module jv news config
	 * @Created by joomvision
	 */
	function getModuleConfig($moduleId){
		$db = &JFactory::getDBO();
		$sql = "SELECT params FROM #__modules WHERE id=$moduleId";
		$db->setQuery($sql);
		$data = $db->loadResult();
		$params = new JParameter($data);
		$this->params = $params;
	}
	//End
	/*
	 * Function get content by cat id
	 * @Created by joomvision
	 */
	function getContentByCatId($catId){
		$catInfo = $this->getCategoryInfo($catId);
		$linkCat = JRoute::_(ContentHelperRoute::getCategoryRoute($catInfo->id,$catInfo->section));
		$linkCat = str_replace("/modules/mod_jv_news","",$linkCat);
		$str ='<div class="items_e_cat">
				<div class="item_header_wrap">
					<div class="item_header"><a class="title" href="'.$linkCat.'">'.$catInfo->title.'</a>
					<div class="cat_addremove"><a id="add_cat'.$catId.'" href="#" class="cat_add">&nbsp;</a><a id="cat_remove'.$catId.'" href="#" class="cat_remove">&nbsp;</a></div>
					</div>';
		$listItem = $this->getItemsByCatId($catId);
		if(count($listItem)) {
			$str.='<ul class="cat_morelink">';
			$i=1;
			$defaultItem = $this->params->get('v_default_item');
			foreach($listItem as $item){
				if($i<=$defaultItem) {
					$str.='<li class="active"><a href="'.$item->link.'">'.$item->title.'</a></li>';
				} else {
					$str.='<li class="block"><a href="'.$item->link.'">'.$item->title.'</a></li>';
				}
				$i++;
			}
		}
		$str.='</div><div class="cls"></div></div>';
		return $str;
	}
	//EndR

	/*
	 * Function get all items by category
	 * @Created by joomvision
	 */
	function getItemsByCatId($catId){
		$db         =& JFactory::getDBO();
		$intro_lenght = intval($this->params->get( 'intro_length', 200) );
		$nullDate   = $db->getNullDate();
		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		$count = $this->params->get('v_max_item');
		$where      = 'a.state = 1'
		. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
		. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )';
		// Ordering
		$ordering       = 'a.created DESC';
		$query = 'SELECT a.*,a.id as key1, cc.id as key2, cc.title as cat_title, ' .
            ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
            ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
            ' FROM #__content AS a' .             
            ' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
            ' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
            ' WHERE '. $where .' AND s.id > 0' .		
            ' AND s.published = 1 AND cc.id='.$catId.
            ' AND cc.published = 1' .
            ' ORDER BY '. $ordering;		
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
		$i      = 0;
		$lists  = array();
		foreach ( $rows as $row ){
			$lists[$i]->title = $row->title;
			$linkTmp = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			$lists[$i]->link = str_replace("/modules/mod_jv_news","",$linkTmp);
			$i++;
		}
		return $lists;
	}
	//End
	function getCategoryInfo($catId){
		$db         =& JFactory::getDBO();
		$sql ="SELECT id,title,section
				FROM #__categories AS c 
				WHERE c.published = 1 AND c.id=".$catId;
		$db->setQuery($sql);
		return $db->loadObject();
	}
	/*
	 * Function get latest items by section id
	 * @Created by joomvision
	 */
	function getLatestItemBySecId($secId,$noHeadLine,$noLink){
		global $mainframe;
		$db         =& JFactory::getDBO();
		$user       =& JFactory::getUser();
		$userId     = (int) $user->get('id');
		//$count = (int)$this->params->get('v_no_latest_item') + (int)$this->params->get('v_no_link_item');
		$count = (int)$noHeadLine+(int)$noLink;
		$intro_lenght = intval($this->params->get( 'intro_length', 100) );
		$aid        = $user->get('aid', 0);
		$imgWidth = $this->params->get('image_width');
		$imgHeight = $this->params->get	('image_height');
		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access     = !$contentConfig->get('shownoauth');
		$nullDate   = $db->getNullDate();
		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		$where      = 'a.state = 1'
		. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
		. ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
		;
		// Ordering
		$ordering       = 'a.created DESC';
		// Content Items only
		$query = 'SELECT a.*,a.id as key1, cc.id as key2, cc.title as cat_title, ' .
            ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
            ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
            ' FROM #__content AS a' .             
            ' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
            ' INNER JOIN #__sections AS s ON s.id = a.sectionid' .
            ' WHERE '. $where .' AND s.id > 0' .
		($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
            ' AND s.published = 1 '.
            ' AND cc.published = 1 AND s.id = '.$secId.
            ' ORDER BY '. $ordering;					
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();		
		$i      = 0;
		$lists  = array();
		foreach ( $rows as $row ){
			//$imageurl = modJVHeadLineHelper::checkImage($row->introtext);
			$imageurl = $this->checkImage($row->introtext);
			//var_dump($imageurl);
			$folderImg = DS.$row->id;
			$this->createdDirThumb('com_content',$folderImg);
			$lists[$i]->title = $row->title;
			$lists[$i]->alias = $row->alias;
			$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			$lists[$i]->link = str_replace("/modules/mod_jv_news","",$lists[$i]->link);
			$lists[$i]->introtext = $this->introContent($row->introtext, $intro_lenght);
			$lists[$i]->created = $row->created;
			$lists[$i]->thumb ='';			
			if($imageurl !='' && $this->FileExists($imageurl)) {
				$lists[$i]->thumb = $this->getThumb($row->introtext,$imgWidth,$imgHeight,false,$row->id);
				//var_dump($lists[$i]->thumb);
				if($lists[$i]->thumb){
					$images_size = $this->getImageSizes($lists[$i]->thumb);
					if($images_size[0] != $imgWidth || $images_size[1] != $imgHeight) {
						@unlink($lists[$i]->thumb);
						$lists[$i]->thumb = $this->getThumb($row->introtext,$imgWidth,$imgHeight,false,$row->id);
					}
				}				
				$lists[$i]->thumb = str_replace(JPATH_BASE,"",$lists[$i]->thumb);
				$lists[$i]->thumb = substr($lists[$i]->thumb,1);
			}
			$i++;
		}
		return $lists;
	}
	//End function
	/*
	 * Function check existion of image in content
	 * @Created by joomvision
	 */
	function checkImage($file) {
		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $file, $matches);
		if(count($matches)){
			return $matches[1];
		} else {return '';}
	}
	//End function
	function FileExists($file) {
		if($file){
			$file = JPATH_BASE.DS.$file;
			if(file_exists($file))
			return true;
			else
			return false;
		} else {return false;}
	}
	function getThumb($text, $tWidth,$tHeight, $reflections=false,$id=0){		
		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);
		$paths = array();
		$showbug = true;
		if (isset($matches[1])) {
			$image_path = $matches[1];
			//joomla 1.5 only
			$full_url = JURI::base();
			$full_url = str_replace("/mod_jv_news","",$full_url);
			//remove any protocol/site info from the image path
			$parsed_url = parse_url($full_url);
			$paths[] = $full_url;
			if (isset($parsed_url['path']) && $parsed_url['path'] != "/") $paths[] = $parsed_url['path'];
			foreach ($paths as $path) {
				if (strpos($image_path,$path) !== false) {
					$image_path = substr($image_path,strpos($image_path, $path)+strlen($path));
				}
			}
			
			// remove any / that begins the path
			if (substr($image_path, 0 , 1) == '/') $image_path = substr($image_path, 1);
			//if after removing the uri, still has protocol then the image
			//is remote and we don't support thumbs for external images
			if (strpos($image_path,'http://') !== false ||
			strpos($image_path,'https://') !== false) {
				return false;
			}
			// create a thumb filename
			$file_div = strrpos($image_path,'.');
			$thumb_ext = substr($image_path, $file_div);
			$thumb_prev = substr($image_path, 0, $file_div);
			$thumb_path = '';
			$thumb_path = JPATH_ROOT.DS.'images/stories/thumbs/com_content/'.$id.'/jvnews_'.$tWidth.'x'.$tHeight.$thumb_ext;
			// check to see if this file exists, if so we don't need to create it
			if ($thumb_path !='' && function_exists("gd_info") && !file_exists($thumb_path)) {
				// file doens't exist, so create it and save it
				include_once('thumbnail.inc.php');
				$image_path = JPATH_ROOT.DS.$image_path;				
				$thumb = new JVThumbnail($image_path);
				if ($thumb->error) {
					if ($showbug)   echo "JV Image ERROR: " . $thumb->errmsg . ": " . $image_path;
					return false;
				}
				//$thumb->resize($size);
				$thumb->resize_image($tWidth,$tHeight);
				if ($reflections) {
					$thumb->createReflection(30,30,60,false);
				}
				if (!is_writable(dirname($thumb_path))) {
					$thumb->destruct();
					return false;
				}
				$thumb->save($thumb_path);
				$thumb->destruct();
			}
			return ($thumb_path);
		} else {
			return false;
		}
	}
	function createdDirThumb($comp='com_content',$folderImage=''){
		$thumbImgParentFolder = JPATH_BASE.DS.'images'.DS.'stories'.DS.'thumbs'.DS.$comp.$folderImage;
		if(!JFolder::exists($thumbImgParentFolder)){
			JFolder::create($thumbImgParentFolder);
		}
	}
	function getImageSizes($file) {
		return getimagesize($file);
	}
	/*
	 * Function get intro content
	 * @Created by joomvision
	 */
	function introContent($str, $limit = 100,$end_char = '&#8230;'){
		if (trim($str) == '') return $str;
		// always strip tags for text
		$str = strip_tags($str);
		preg_match('/\s*(?:\S*\s*){'.(int)$limit.'}/', $str, $matches);
		if (strlen($matches[0]) == strlen($str))$end_char = '';
		return rtrim($matches[0]).$end_char;
	}
	
	/*
	 * Function render content headline
	 * @Created by joomvision
	 */
	function renderHeadLine($secId,$noHeadLine,$noLink,&$str,&$strMoreLink){
		$latestItem = $this->getLatestItemBySecId($secId,$noHeadLine,$noLink);
		$str = "";
		$strMoreLink ='';
		$slideBarLeft = "margin-left:".($this->params->get('image_width'))."px";
		$slideMaskWidth = "width:".($this->params->get('image_width'))."px";
		$slideMaskHeight = "height:".($this->params->get('image_height'))."px";		
		if(count($latestItem)){			
			$strMask = '<div class="news_mask" style="'.$slideMaskHeight.';'.$slideMaskWidth.'" id="news_mask'.$secId.'">';
			if(count($latestItem) <= $noHeadLine) {$noHeadLine1 = count($latestItem);} else {$noHeadLine1 = $noHeadLine;}			
			for($i=0;$i<$noHeadLine1;$i++){
				if($i!=0) {$cssImage = "block";} else {$cssImage = "active";}				
				$strMask.='<div class="news_img '.$cssImage.'">';
				if($latestItem[$i]->thumb){ 
					$url = str_replace("/modules/mod_jv_news","",JURI::base());					
					$imgSrc = $url.$latestItem[$i]->thumb;
					$strMask.='<a href="'.$latestItem[$i]->link.'" title="'.$latestItem[$i]->title.'"><img src="'.$imgSrc.'" alt="" /></a>';
				}
				$strMask.="</div>";	
			}
			$strMask.="</div>";			
			$strNewsBar = '<div class="news_bar" id="news_bar'.$secId.'" style="'.$slideBarLeft.'">';
			
			for($i=0;$i<$noHeadLine1;$i++){
				if($i==0) {$cssBar = "selected";} else {$cssBar = "";}
				$strNewsBar.='<a href="'.$latestItem[$i]->link.'" class="item '.$cssBar.'"><span>'.$latestItem[$i]->title.'</span></a>';
			}
			$strNewsBar.="</div>";
			$str.=$strMask.$strNewsBar;			
			if($noHeadLine < count($latestItem)){
				$strMoreLink.='<ul class="news_more">';
				for($i=$noHeadLine;$i<count($latestItem);$i++){
					$strMoreLink.='<li><a href="'.$latestItem[$i]->link.'">'.$latestItem[$i]->title.'</a></li>';
				}
				$strMoreLink.="</ul>";
			}
		}		
	}
	//End headline
}
?>