<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="png search">
<form action="index.php" method="post">
		<?php
		    $output = '<input name="searchword" id="mod_search_searchword" maxlength="'.$maxlength.'" alt="'.$button_text.'" class="inputbox'.$moduleclass_sfx.'" type="text" size="'.$width.'" value="'.$text.'"  onblur="if(this.value==\'\') this.value=\''.$text.'\';" onfocus="if(this.value==\''.$text.'\') this.value=\'\';" />';

			$button = '<input type="submit" value="" class="button-search" onclick="this.form.searchword.focus();"/>';

			$output = $output.$button;

			echo $output;
		?>
	<input type="hidden" name="task"   value="search" />
	<input type="hidden" name="option" value="com_search" />
	<input type="hidden" name="Itemid" value="<?php echo $mitemid; ?>" />
</form>
</div>