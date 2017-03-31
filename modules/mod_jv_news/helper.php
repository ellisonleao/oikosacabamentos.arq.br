<?php
/**
 * @package JV News Module for Joomla! 1.5
 * @author http://www.ZooTemplate.com
 * @copyright (C) 2010- ZooTemplate.com
 * @license PHP files are GNU/GPL
**/

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
class modJVNewsHelper{
	var $params = array();
	var $section = array();
	function __construct($params){
		$this->params = $params;
		$this->createdDirThumb();
	}	
	function getCookie($name){			
		if (isset($_COOKIE[$name]) && trim($_COOKIE[$name] !=''))
			return urldecode($_COOKIE[$name]);			
		else
			return false;
	}
	function getCategories(){
		$categories = ( array )$this->params->get('categories',array());
		if(count($categories)){
			$strCatId = implode(',',$categories);
			$categoriesCondi = " AND c.id IN ($strCatId)";
		}
		$db	=& JFactory::getDBO();
		$order = (string)$this->params->get('cat_ordering',1);
		switch($order){		
			case "1":
				$orderBy = " ORDER BY c.title ASC";
				break;
			case "2":
				$orderBy = " ORDER BY c.title DESC";
				break;
			case "3":
				$orderBy = " ORDER BY c.ordering";
				break;				
		}
		$sql ="SELECT id,title,section
					 FROM #__categories AS c 
					 WHERE c.published = 1 ".(count($categories) ? $categoriesCondi:'').$orderBy;		
		$db->setQuery($sql);
		$results = $db->loadObjectList();
		$rows = array();
		if(count($results)){
			$i=0;
			foreach($results as $item){
				$rows[$i]->id = $item->id;
				$rows[$i]->title = $item->title;
				$rows[$i]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id,$item->section));
				$i++;
			}
		}
		return $rows;
	}
	function getItemsByCatId($catId){
		global $mainframe;
		$db         =& JFactory::getDBO();
		$user       =& JFactory::getUser();
		$userId     = (int) $user->get('id');
		$count = (int)$this->params->get('no_intro_items') + (int)$this->params->get('no_link_items');
		$intro_lenght = intval($this->params->get( 'intro_length', 200) );
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
            ' AND s.published = 1 AND cc.id='.$catId.
            ' AND cc.published = 1' .
            ' ORDER BY '. $ordering;		
		$db->setQuery($query, 0, $count);
		$rows = $db->loadObjectList();
		$i      = 0;
		$lists  = array();
		foreach ( $rows as $row ){
			//$imageurl = modJVHeadLineHelper::checkImage($row->introtext);
			$imageurl = $this->checkImage($row->introtext);
			$folderImg = DS.$row->id;
			$this->createdDirThumb('com_content',$folderImg);
			$lists[$i]->title = $row->title;
			$lists[$i]->alias = $row->alias;
			$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			$lists[$i]->introtext = $this->introContent($row->introtext, $intro_lenght);
			$lists[$i]->created = $row->created;
			$lists[$i]->thumb ='';
			if($this->FileExists($imageurl)) {
				$lists[$i]->thumb = $this->getThumb($row->introtext,$imgWidth,$imgHeight,false,$row->id);
				$images_size = $this->getImageSizes($lists[$i]->thumb);
				if($images_size[0] != $imgWidth || $images_size[1] != $imgHeight) {
					@unlink($lists[$i]->thumb);
					$lists[$i]->thumb = $this->getThumb($row->introtext,$imgWidth,$imgHeight,false,$row->id);
				}			
			} 
			$i++;
		}
		return $lists;
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
	//End
	function createdDirThumb($comp='com_content',$folderImage=''){
		$thumbImgParentFolder = JPATH_BASE.DS.'images'.DS.'stories'.DS.'thumbs'.DS.$comp.$folderImage;
		if(!JFolder::exists($thumbImgParentFolder)){
			JFolder::create($thumbImgParentFolder);
		}
	}
	function getThumb($text, $tWidth,$tHeight, $reflections=false,$id=0){
		preg_match("/\<img.+?src=\"(.+?)\".+?\/>/", $text, $matches);
		$paths = array();
		$showbug = true;
		if (isset($matches[1])) {
			$image_path = $matches[1];
			//joomla 1.5 only
			$full_url = JURI::base();
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
			$thumb_path = 'images/stories/thumbs/com_content/'.$id.'/jvnews_'.$tWidth.'x'.$tHeight.$thumb_ext;
			// check to see if this file exists, if so we don't need to create it
			if ($thumb_path !='' && function_exists("gd_info") && !file_exists($thumb_path)) {
				// file doens't exist, so create it and save it
				include_once('thumbnail.inc.php');
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
		if(file_exists($file))
		return true;
		else
		return false;
	}
	function getImageSizes($file) {
		return getimagesize($file);
	}
	function getSection(){
		$db	=& JFactory::getDBO();
		$categories = ( array ) $this->params->get('categories',array());
		$aryTmp = array();
		if(count($categories)){
			foreach($categories as $item){
				$aryTmp[] = substr($item,0,strpos($item,'_'));
			}
			$section = array_unique($aryTmp);			
			$this->section = array_unique($aryTmp);
			$strSection = implode(',',$section);
			$sql = "SELECT id, title 
					FROM #__sections 
					WHERE published = 1 AND scope = 'content' AND id IN (".$strSection.") 
					ORDER BY title";
		$db->setQuery($sql);
		$results = $db->loadObjectList();
		$rows = array();
		if(count($results)){
			$i=0;
			foreach($results as $item){
				$rows[$i]->id = $item->id;
				$rows[$i]->title = $item->title;
				$rows[$i]->link = JRoute::_(ContentHelperRoute::getSectionRoute($item->id));
				$i++;
			}
		}			
		}
		return $rows;			
	}
	function getCategoryBySecId($secId){
		$db	=& JFactory::getDBO();
		$categories = ( array ) $this->params->get('categories',array());
		$aryTmp = array();
		if(count($categories)){
			foreach($categories as $item){
				$sect = $secId."_";	
				$sectTmp = substr($item,0,(int)strpos($item,'_') + 1);
				if($sect == $sectTmp) {			
					$aryTmp[] = substr($item,(int)strpos($item,"_") + 1,strlen($item));
				}
			}
			$strCat = implode(',',$aryTmp);
			$sql ="SELECT id,title,section
					 FROM #__categories AS c 
					 WHERE c.published = 1  AND id IN (".$strCat.") ORDER BY ordering ASC";		
			$db->setQuery($sql);
			$results = $db->loadObjectList();
			$rows = array();
			if(count($results)){
				$i=0;
				foreach($results as $item1){
					$rows[$i]->id = $item1->id;
					$rows[$i]->title = $item1->title;
					$rows[$i]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item1->id,$item1->section));
					$i++;
				}
			}
		}
		return $rows;	
		//var_dump($aryTmp);		
	}
	
	/*
	 * Function get latest items by section id
	 * @Created by joomvision
	 */
	function getLatestItemBySecId($secId){ 
		global $mainframe;
		$db         =& JFactory::getDBO();
		$user       =& JFactory::getUser();
		$userId     = (int) $user->get('id');
		$count = (int)$this->params->get('v_no_latest_item') + (int)$this->params->get('v_no_link_item');
		$intro_lenght = intval($this->params->get( 'intro_length', 200) );
		$aid        = $user->get('aid', 0);
		$imgWidth = $this->params->get('image_width');
		$imgHeight = $this->params->get	('image_height');
		$contentConfig = &JComponentHelper::getParams( 'com_content' );
		$access     = !$contentConfig->get('shownoauth');
		$nullDate   = $db->getNullDate();
		$date =& JFactory::getDate();
		$now = $date->toMySQL();
		$amountCookie = 'amount'.$secId;
		$amountCookie = $this->getCookie($amountCookie);	
		if($amountCookie){
			$aryAmount = explode(',',$amountCookie);
			$noHeadLine = $aryAmount[0];
			$noLink = $aryAmount[1];
			$count = (int)$noHeadLine + (int)$noLink;
		}	
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
			$folderImg = DS.$row->id;
			$this->createdDirThumb('com_content',$folderImg);
			$lists[$i]->title = $row->title;
			$lists[$i]->alias = $row->alias;
			$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));
			$lists[$i]->introtext = $this->introContent($row->introtext, $intro_lenght);
			$lists[$i]->created = $row->created;
			$lists[$i]->thumb ='';
			if($this->FileExists($imageurl)) {
				$lists[$i]->thumb = $this->getThumb($row->introtext,$imgWidth,$imgHeight,false,$row->id);
				$images_size = $this->getImageSizes($lists[$i]->thumb);
				if($images_size[0] != $imgWidth || $images_size[1] != $imgHeight) {
					@unlink($lists[$i]->thumb);
					$lists[$i]->thumb = $this->getThumb($row->introtext,$imgWidth,$imgHeight,false,$row->id);
				}			
			} 
			$i++;
		}
		return $lists;
	}
	//End function
	
	/*
	 * Function get category by section id
	 * @Created by joomvision
	 */
	function getAllCatBySecId($secId){
		$db	=& JFactory::getDBO();			
		$sql ="SELECT c.id,c.title,c.section
				FROM #__categories AS c ".
            " INNER JOIN #__sections AS s ON s.id = c.section " . 
			" WHERE c.published = 1  AND s.id > 0 AND s.id =".$secId.
			" ORDER BY c.ordering ASC";		
		$db->setQuery($sql);
		$results = $db->loadObjectList();
		$rows = array();
		if(count($results)){
			$i=0;
			foreach($results as $item1){
				$rows[$i]->id = $item1->id;
				$rows[$i]->title = $item1->title;
				$rows[$i]->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item1->id,$item1->section));
				$i++;
			}
		}			
		return $rows;	
	}
	//End
	/*
	 * Function get content by cat id
	 * @Created by joomvision
	 */
	function getContentByCatId($catId,$noItem){		
		$catInfo = $this->getCategoryInfo($catId);
		$linkCat = JRoute::_(ContentHelperRoute::getCategoryRoute($catInfo->id,$catInfo->section));		
		$str ='<div class="items_e_cat">
				<div class="item_header_wrap">
					<div class="item_header"><a class="title" href="'.$linkCat.'">'.$catInfo->title.'</a>
					<div class="cat_addremove"><a id="add_cat'.$catId.'" href="#" class="cat_add">&nbsp;</a><a id="cat_remove'.$catId.'" href="#" class="cat_remove">&nbsp;</a></div>
					</div>';
		$listItem = $this->getAllItemsByCatId($catId);
		if(count($listItem)) {
			$str.='<ul class="cat_morelink">';
			$i=1;			
			foreach($listItem as $item){
				if($i<=$noItem) {
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
	function getCategoryInfo($catId){
		$db         =& JFactory::getDBO();
		$sql ="SELECT id,title,section 
				FROM #__categories AS c 
				WHERE c.published = 1 AND c.id=".$catId;
		$db->setQuery($sql);
		return $db->loadObject();
	}
	/*
	 * Function get all items by category
	 * @Created by joomvision
	 */
	function getAllItemsByCatId($catId){
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
			$lists[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catslug, $row->sectionid));	
			//$lists[$i]->link = str_replace("/modules/mod_jv_news","",$linkTmp);		
			$i++;
		}
		return $lists;
	}
	//End
	
	/*
	 * Function get section by vertical template
	 * @Created by joomvision
	 */
	function getSectionByVTemp(){
		$db	=& JFactory::getDBO();
		$sections = ( array ) $this->params->get('v_section',array());
		if(count($sections)){
			$strSecId = implode(',',$sections);
			$sectionsCondi = " AND id IN ($strSecId)";
		}
		$orderBy = $this->params->get('v_section_orderding');
		$ordering =" ORDER BY title";
		if($orderBy == 1) {
			$ordering = " ORDER BY title ASC";
		} else if($orderBy == 2){
			$ordering = " ORDER BY title DESC";
		} else if($orderBy == 3){
			$ordering = " ORDER BY ordering DESC";
		} else {
			$ordering = " ORDER BY ordering ASC";
		}
		$sql='SELECT id, title 
				FROM #__sections 
				WHERE published = 1 AND scope = "content" '.(count($sections) ? $sectionsCondi :'').' '.$ordering;			
		$db->setQuery($sql);
		$results = $db->loadObjectList();
		$rows = array();
		if(count($results)){
			$i=0;
			foreach($results as $item){
				$rows[$i]->id = $item->id;
				$rows[$i]->title = $item->title;
				$rows[$i]->link = JRoute::_(ContentHelperRoute::getSectionRoute($item->id));				
				$i++;
			}
		}
		return $rows;			
	}
	//End
	
}
?>