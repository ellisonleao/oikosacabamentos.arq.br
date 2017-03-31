<?php 
/**
 * @package JV News Module for Joomla! 1.5
 * @author http://www.ZooTemplate.com
 * @copyright (C) 2010- ZooTemplate.com
 * @license JS files are GNU/GPL
**/

// no direct access
defined('_JEXEC') or die('Restricted access');
$document = &JFactory::getDocument();
$document->addStyleSheet("modules/mod_jv_news/assets/css/default.css");
 ?>
<?php
	$columns = $params->get('columns',2);
	if($columns > count($listSections))
		$columns = count($listSections);
	switch ($columns)
	{
		case '1':
			$width = '100';
			break;
		case '2':
			$width = '49';
			break;
		case '3':
			$width = '32.9';
			break;
		case '4':
			$width = '24.5';
			break;
		case '5':
			$width = '19.5';
			break;
		default:
			$width = '49';
	}
	$seperator = 1;
?>
<div style="display: none;"><a title="Joomla Templates" href="http://www.ZooTemplate.com">Joomla Templates</a> and Joomla Extensions by ZooTemplate.com</div>
<div class="jv_news_wrap">
<?php for($i=0;$i<count($listSections);$i++) : ?>
<?php 
$listCats = $jvNews->getCategoryBySecId($listSections[$i]->id);
$listItems = $jvNews->getItemsByCatId($listCats[0]->id); ?>
<?php if(count($listCats) > 0) : ?>
<?php if($seperator == 1) : ?>
<div class="jv-frame-cat">
<?php endif; ?>

	<div class="jv-category" style="width: <?php echo $width ?>%">
	<div class="jvpadding">
		<ul class="jv-title-category">
		<?php
		$k=0; 
		foreach($listCats as $item){ ?>		
		<li <?php if($k==0) {echo 'class="jv-firstitem"';} if($k==count($listCats) - 1) {echo 'class="jv-lastitem"';} ?> ><a href="<?php echo $item->link ?>"><span class="jv-title-category"><?php echo $item->title ?></span></a></li>
		<?php $k++; }  ?>
		</ul>
		<div class="cls"></div>
		<ul class="lead">
		<?php $lead = (int)$params->get('no_intro_items',1);
			if(count($listItems) <= $lead) $lead1 = count($listItems);
			else $lead1 = $lead;
		?>
		<?php for($j=0;$j<$lead1;$j++) : ?>
			<li class="jv-article-title">
				<h4><a href="<?php echo $listItems[$j]->link; ?>" title="<?php echo $listItems[$j]->title; ?>"><?php echo $listItems[$j]->title; ?></a></h4>

				<?php if ($listItems[$j]->thumb != '' && $params->get('is_image',1) == 1) :?>
				<a href="<?php echo $listItems[$j]->link; ?>" title="<?php echo $listItems[$j]->title; ?>">
				<img src="<?php echo $listItems[$j]->thumb; ?>" alt="<?php echo $listItems[$j]->title; ?>" title="<?php echo $listItems[$j]->title; ?>" class="<?php if($imgAlign == "left") { echo "jv-sectcont-thumb-left";} else { echo "jv-sectcont-thumb-right"; }?>" />
				</a>
				<?php endif; ?>

				<?php if ($listItems[$j]->introtext != false) :?>
				<p class="jv-sectcont-introtext"><?php echo ($listItems[$j]->introtext); ?></p>
				<?php endif; ?>
				<?php if($params->get('show_readmore') == 1) {?>
				<p class="jv-news-readmore">
					<a class="readmore" href="<?php echo $listItems[$j]->link; ?>"><?php echo JTEXT::_('NEWS READ MORE'); ?></a>
				</p>
				<?php }?>
			</li>
		<?php endfor; ?>
		</ul>
		<?php if($lead < count($listItems)):?>
		<p class="more_link"><?php echo JTEXT::_('NEWS MORE LINK'); ?></p>
		<ul class="article-item clearfix">
		<?php for($j=$lead;$j<count($listItems);$j++) : ?>
			<li>
				<a href="<?php echo $listItems[$j]->link; ?>"><?php echo $listItems[$j]->title; ?></a>
			</li>
		<?php endfor; ?>
		</ul>
		<?php endif; ?>
	</div>
	</div>

<?php if($seperator == $columns) : ?>
</div>
<?php endif; ?>
<?php 
	if($seperator == $columns)
		$seperator = 1;
	else
		$seperator++;
?>
<?php endif; ?>
<?php endfor; ?>
<div class="clearfix"></div>
</div>