<?php
/**
* @version 1.0.2
* @package PWebFBLikeBox
* @copyright © 2013 Majestic Media sp. z o.o., All rights reserved. http://www.perfect-web.co
* @license GNU General Public Licence http://www.gnu.org/licenses/gpl-3.0.html
* @author Piotr Moćko
*/

defined('_JEXEC') or die('Restricted access');

$moduleclass = htmlspecialchars($params->get('moduleclass_sfx'));
$style_container = $params->get('inset_background') ? ' style="background-color:'.$params->get('inset_background').'"' : '';
?>
<!-- PWebFBLikeBox -->
<div id="pwebfblikebox<?php echo $module->id; ?>" class="pwebfblikebox static <?php echo $moduleclass; ?>"<?php echo $params->get('rtl') ? ' dir="rtl"' : ''; ?>>
	<?php if ($params->get('pretext')) : ?>
	<p class="pwebfblikebox_pretext"><?php echo $params->get('pretext'); ?></p>
	<?php endif; ?>
	<div class="pwebfblikebox_container"<?php echo $style_container; ?>><?php echo $LikeBox; ?></div>
</div>

<?php if ($javascript = modPWebFBLikeBoxHelper::getTrackSocialScript()) : ?>
<script type="text/javascript">
<?php echo $javascript; ?>
</script>
<?php endif; ?>
<!-- PWebFBLikeBox end -->