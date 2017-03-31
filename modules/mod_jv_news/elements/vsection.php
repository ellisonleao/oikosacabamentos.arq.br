<?php
/**
* @version		$Id: section.php 10707 2008-08-21 09:52:47Z eddieajau $
* @package		Joomla.Framework
* @subpackage	Parameter
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a section element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JElementVsection extends JElement
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'vsection';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$db =& JFactory::getDBO();
		$class		= $node->attributes('class');
		$query = 'SELECT id, title FROM #__sections WHERE published = 1 AND scope = "content" ORDER BY title';
		$db->setQuery($query);
		$options = $db->loadObjectList();
		$cId = JRequest::getVar('cid','');
		if($cId !='') $cId = $cId[0];
		if($cId == ''){
			$cId = JRequest::getVar('id');
		}
		$sql = "SELECT params FROM #__modules WHERE id=$cId";
		$db->setQuery($sql);
		$paramsConfigObj = $db->loadObjectList();
		$db->setQuery($sql);
		$data = $db->loadResult();
		$params = new JParameter($data);
		$template = $params->get('template_type','horizon');
?>	
		<script type="text/javascript">	
		var jpaneAutoHeight = function(){
			 $$('.jpane-slider').each(function(item){
			      item.setStyle('height','auto'); 
			  });
		};
		window.addEvent('load',function(){
			 setTimeout(jpaneAutoHeight,400);
			 var layout = "<?php echo $template; ?>";
			 var rowNewsCat = $('paramscategories').getParent().getParent();
		     for(i=0;i<=6;i++){
		    	 rowNewsCat.addClass('jv_news_category');		    	  
		    	 rowNewsCat = rowNewsCat.getNext();		    	 
			}
			var rowNewsSection = $('paramsv_section').getParent().getParent();	
			for(i=0;i<=6;i++){
				rowNewsSection.addClass('jv_news_section');
				rowNewsSection = rowNewsSection.getNext();
			}
			var jvNewsCat = $$('.jv_news_category');
			var jvNewsSect = $$('.jv_news_section');	
			var selectStyle = function(style){
				switch(style) {
				case "horizon":
					jvNewsCat.each(function(item){
						item.setStyle('display','');
   					}.bind(this));
					jvNewsSect.each(function(item){
						item.setStyle('display','none');
   					}.bind(this));	 
					break;
				case "vertical":
					jvNewsCat.each(function(item){
						item.setStyle('display','none');
   					}.bind(this));
					jvNewsSect.each(function(item){
						item.setStyle('display','');
   					}.bind(this));
					break;
				}
			}
			selectStyle(layout);
			$('paramstemplate_type').addEvent('change',function(){
					selectStyle(this.value);                
		  	});
		});
		</script>
	<?php		
		//array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('Select Section').' -', 'id', 'title'));
		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.'][]', 'class="'.$class.'" style="width:90%;" multiple="multiple" size="10"', 'id', 'title', $value, $control_name.$name);		
	}
}
