<?php
/**
 * @copyright	Copyright (C) 2008 - 2011 ZooTemplate.com. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
include_once (dirname(__FILE__).DS.'libs'.DS.'zt_tools.php');
include_once (dirname(__FILE__).DS.'zt_menus'.DS.'zt.common.php');
include_once (dirname(__FILE__).DS.'libs'.DS.'zt_vars.php');
unset($this->_scripts[$this->baseurl . '/media/system/js/caption.js']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<jdoc:include type="head" />
<?php JHTML::_('behavior.mootools'); ?>
	<?php
		$document = JFactory::getDocument();
		$document->addStyleSheet($ztTools->baseurl() . 'templates/system/css/system.css');
		$document->addStyleSheet($ztTools->baseurl() . 'templates/system/css/general.css');
		$document->addStyleSheet($ztTools->templateurl() . 'css/default.css');
		$document->addStyleSheet($ztTools->templateurl() . 'css/template.css');
		
		if($ztrtl == 'rtl') {
			$document->addStyleSheet($ztTools->templateurl() . 'css/template_rtl.css');
			$document->addStyleSheet($ztTools->templateurl() . 'css/typo_rtl.css');
		} else {
			$document->addStyleSheet($ztTools->templateurl() . 'css/typo.css');
		}
		
		if($ztTools->getParam('zt_google_fonts')) {
			$document->addStyleSheet($ztTools->templateurl() . 'css/googlefonts.css');
		}
		
		$document->addScript($ztTools->templateurl() . 'js/zt.script.js');
	?>

	<?php if($ztTools->getParam('zt_google_fonts')) : ?>
	<link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css' />
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo $ztTools->templateurl(); ?>css/css3.php?url=<?php echo $ztTools->templateurl(); ?>" type="text/css" />
	<script type="text/javascript">
		var baseurl = "<?php echo $ztTools->baseurl() ; ?>";
		var ztpathcolor = '<?php echo $ztTools->templateurl(); ?>css/colors/';
		var tmplurl = '<?php echo $ztTools->templateurl();?>';
		var CurrentFontSize = parseInt('<?php echo $ztTools->getParam('zt_font');?>');
	</script>
	<link href="<?php echo $ztTools->parse_ztcolor_cookie($ztcolorstyle); ?>" rel="stylesheet" type="text/css" />
	<!--[if lte IE 6]>
	<link rel="stylesheet" href="<?php echo $ztTools->templateurl(); ?>css/ie6.css" type="text/css" />
	<script type="text/javascript" src="<?php echo $ztTools->templateurl() ?>js/ie_png.js"></script>
	<script type="text/javascript">
	window.addEvent ('load', function() {
	   ie_png.fix('.png');
	});
	</script>
	<![endif]-->
	<!--[if lte IE 7]>
	<link rel="stylesheet" href="<?php echo $ztTools->templateurl(); ?>css/ie7.css" type="text/css" />
	<![endif]-->
</head>
<body id="bd" class="fs<?php echo $ztTools->getParam('zt_font'); ?> <?php echo $ztTools->getParam('zt_display'); ?> <?php echo $ztTools->getParam('zt_display_style'); ?> <?php echo $ztrtl; ?>">
<div id="zt-wrapper">
	<div id="zt-wrapper-inner">
		
		<!-- HEADER -->
		<div id="zt-header" class="clearfix">
			<div class="zt-wrapper">
				<div id="zt-header-inner">
					<h1 id="zt-logo"><a class="png" href="<?php echo $ztTools->baseurl() ; ?>" title="<?php echo $ztTools->sitename() ; ?>">
						<span><?php echo $ztTools->sitename() ; ?></span></a>
					</h1>
					<div id="zt-social-network">
						<?php if($ztTools->getParam('zt_function')) : ?>
						<div id="zt-tool"><?php echo $changecolor; ?></div>
						<?php endif; ?>
						<?php if($this->countModules('top')) : ?>
								<jdoc:include type="modules" name="top" />
						<?php endif; ?>	
					</div>
				</div>
			</div>
		</div>
		<!-- END HEADER -->
		<!-- Main Menu -->
		<div id="zt-mainmenu" class="clearfix">
			<div class="zt-wrapper">
				<div id="zt-mainmenu-inner">
					<?php $menu->show(); ?>
				</div>
				<?php if($this->countModules('search')) : ?>
					<div id="zt-search">
						<jdoc:include type="modules" name="search" />
					</div>
				<?php endif; ?>
			</div>	
		</div>	
		<!-- #Main Menu -->
		<!-- Slide Show -->
		<div id="zt-slideshow" class="clearfix">
			<div class="zt-wrapper">
				<?php if($this->countModules('slideshow')) : ?>
					<div id="zt-slideshow-inner">
						<jdoc:include type="modules" name="slideshow" />
					</div>
				<?php endif; ?>
			</div>	
		</div>	
		<!-- #Slide Show -->
		<!-- User 1 -->
		<?php
			$spotlight = array ('user1','user2','user3');
			$botsl1 = $ztTools->calSpotlight($spotlight,$ztTools->isOP()?100:100, '%');
			if( $botsl1 ) :
		?>	
		<div id="zt-userwrap1" class="clearfix">
			<div class="zt-wrapper">
				<div id="zt-userwrap1-inner">
					<?php if($this->countModules('user1')) : ?>
					<div id="zt-user1" class="zt-user zt-box<?php echo $botsl1['user1']['class']; ?>" style="width: <?php echo $botsl1['user1']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user1" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
					<?php if($this->countModules('user2')) : ?>
					<div id="zt-user2" class="zt-user zt-box<?php echo $botsl1['user2']['class']; ?>" style="width: <?php echo $botsl1['user2']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user2" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
					<?php if($this->countModules('user3')) : ?>
					<div id="zt-user3" class="zt-user zt-box<?php echo $botsl1['user3']['class']; ?>" style="width: <?php echo $botsl1['user3']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user3" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>	
		</div>	
		<?php endif; ?>
		<!-- #Main Menu -->
		<div id="zt-mainframe" class="clearfix ">
			<div class="zt-wrapper">
				<div id="zt-mainframe-inner">
					<!-- CONTAINER -->
					<div id="zt-container<?php echo $zt_width; ?>" class="clearfix">
						<div id="zt-content">
						
							<?php   if($this->countModules('left')) : ?>
							<div id="zt-left">
								<div id="zt-left-inner">
									<jdoc:include type="modules" name="left" style="ztxhtml" />
								</div>
							</div>
							<?php endif; ?>
							<div id="zt-content-inner"  class="zt-content-inner">
								<div id="zt-middle-inner">
									<div id="zt-component" class="clearfix">
										<jdoc:include type="message" />
										<jdoc:include type="component" />
										<?php if($this->countModules('col1')) : ?>
										<div id="zt-col1">
											<div class="zt-box-inside">
												<jdoc:include type="modules" name="col1"/>
											</div>
										</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
							<?php   if($this->countModules('right')) : ?>
							<div id="zt-right">
								<div id="zt-right-inner">
									<jdoc:include type="modules" name="right" style="ztxhtml" />
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<!-- END CONTAINER -->	
				</div>
			</div>
		</div>
		<?php
			$spotlight1 = array ('user4','user5','user6','user7');
			$botsl2 = $ztTools->calSpotlight($spotlight1,$ztTools->isOP()?100:100, '%');
			if( $botsl2 ) :
		?>	
		<div id="zt-userwrap2" class="clearfix">
			<div class="zt-wrapper">
				<div id="zt-userwrap2-inner">
					<?php if($this->countModules('user4')) : ?>
					<div id="zt-user4" class="zt-user zt-box<?php echo $botsl2['user4']['class']; ?>" style="width: <?php echo $botsl2['user4']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user4" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
					<?php if($this->countModules('user5')) : ?>
					<div id="zt-user5" class="zt-user zt-box<?php echo $botsl2['user5']['class']; ?>" style="width: <?php echo $botsl2['user5']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user5" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
					<?php if($this->countModules('user6')) : ?>
					<div id="zt-user6" class="zt-user zt-box<?php echo $botsl2['user6']['class']; ?>"  style="width: <?php echo $botsl2['user6']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user6" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
					<?php if($this->countModules('user7')) : ?>
					<div id="zt-user7" class="zt-user zt-box<?php echo $botsl2['user7']['class']; ?>"  style="width: <?php echo $botsl2['user7']['width']; ?>;">
						<div class="zt-box-inside">
							<jdoc:include type="modules" name="user7" style="ztxhtml" />
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>	
		</div>	
		<?php endif; ?>
		
		<!-- Footer -->
		<div id="zt-footer" class="clearfix">
			<div class="zt-wrapper">
				<div id="zt-footer-inner">
					<?php if($ztTools->getParam('zt_footer')) : ?>
					<?php echo $ztTools->getParam('zt_footer_text'); ?>
					<?php else : ?>
					Copyright &copy; 2008 - 2011 <a href="http://www.zootemplate.com" title="Joomla Templates">Joomla Templates</a> by <a href="http://www.zootemplate.com" title="ZooTemplate">ZooTemplate.Com</a>. All rights reserved.
					<?php endif; ?>
				</div>
			</div>	
		</div>	
		<!-- #Footer -->
		<jdoc:include type="modules" name="debug" />
	</div>
</div>
</body>
</html>