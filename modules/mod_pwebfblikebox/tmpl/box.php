<?php
/**
* @version 1.0.2
* @package PWebFBLikeBox
* @copyright © 2013 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die('Restricted access');

$layout 		= 'slidebox';
$moduleclass 	= 'pweb'. $params->get('align') .' '. $layout .' '. htmlspecialchars($params->get('moduleclass_sfx'));
$style 			= ($params->get('background') ? ' style="background-color:'.$params->get('background').';border-color:'.$params->get('background').'"' : '');
$bg_container 	= ($params->get('inset_background') ? 'background-color:'.$params->get('inset_background') : '');
?>
<!-- PWebFBLikeBox -->
<div id="pwebfblikebox<?php echo $module->id; ?>" class="pwebfblikebox <?php echo $moduleclass; ?>"<?php echo $style . ($params->get('rtl') ? ' dir="rtl"' : ''); ?>>
	<div class="pwebfblikebox_tab <?php echo $params->get('tab'); ?>"<?php echo $style; ?>></div>
	<?php if ($params->get('pretext')) : ?>
	<p class="pwebfblikebox_pretext"><?php echo $params->get('pretext'); ?></p>
	<?php endif; ?>
	<div class="pwebfblikebox_container" style="width:<?php echo (int)$params->get('width', 292); ?>px;<?php echo $bg_container; ?>"><?php echo $LikeBox; ?></div>
</div>

<script type="text/javascript">
<?php echo modPWebFBLikeBoxHelper::getDebugScript(); ?>
(function($){
	pwebFBLikeBox<?php echo $module->id; ?> = new pwebFBLikeBox({
		prefix: 	'pwebfblikebox<?php echo $module->id; ?>',
		open: 		'<?php echo $params->get('open_event'); ?>',
		close: 		'<?php echo $params->get('close_event'); ?>',
		position: 	'<?php echo $params->get('align'); ?>',
		top: 		<?php echo (int)$params->get('top', -1); ?>,
		layout: 	'<?php echo $layout; ?>'
	});
})(typeof document.id != "undefined" ? document.id : $);
<?php echo modPWebFBLikeBoxHelper::getTrackSocialScript(); ?>
</script>
<!-- PWebFBLikeBox end -->