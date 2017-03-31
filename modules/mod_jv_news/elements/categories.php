<?php
/**
 * @package JV News Module for Joomla! 1.5
 * @author http://www.joomlavision.com
 * @copyright (C) 2010- JoomlaVision.Com
 * @license PHP files are GNU/GPL
**/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a category element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementCategories extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'categories';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db = &JFactory::getDBO();		
		//$section	= $node->attributes('section');
		$class		= $node->attributes('class');	
		$query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope = "content" ORDER BY title';	
		$db->setQuery($query);
		$sections = $db->loadObjectList();
		if(count($sections)){
			foreach($sections as $item){
				$options[] = JHTML::_('select.optgroup',$item->title);
				$sql = 'SELECT c.id, c.title' .
						' FROM #__categories AS c' .
						' WHERE c.published = 1' .
						' AND c.section = '.$item->id. 
						' ORDER BY c.ordering';
				$db->setQuery($sql);
				$categories = $db->loadObjectList();
				if(count($categories)){
				foreach($categories as $item1){
					$options[] = JHTML::_('select.option',''.$item->id.'_'.$item1->id.'',''.$item1->title.'');
				}
				}				
			}
		}		
		//$selections				= JHTML::_('menu.linkoptions');
		return JHTML::_('select.genericlist',   $options, ''.$control_name.'['.$name.'][]', 'class="inputbox" style="width:90%;" size="10" multiple="multiple"', 'value', 'text', $value, $control_name.$name );
	}
}