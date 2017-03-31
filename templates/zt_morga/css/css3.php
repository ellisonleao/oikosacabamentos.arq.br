<?php 
/*------------------------------------------------------------------------
* ZT Template 1.5
* ------------------------------------------------------------------------
* Copyright (c) 2008-2011 ZooTemplate. All Rights Reserved.
* @license - Copyrighted Commercial Software
* Author: ZooTemplate
* Websites:  http://www.zootemplate.com
-------------------------------------------------------------------------*/
header('Content-type: text/css; charset: UTF-8');
header('Cache-Control: must-revalidate');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
$url = $_REQUEST['url'];
?>
#zt-userwrap2-inner,
#zt-userwrap2-inner2,
#zt-inset-inner,
#zt-inset-inner2,
#zt-bottom-inner,
#zt-user5 .style1 h3.moduletitle,
#zt-user6 .style1 h3.moduletitle,
#zt-left .style1 h3.moduletitle,
#zt-right .style1 h3.moduletitle,
#zt-userwrap3 .style1 h3.moduletitle,
.box .ztmodule {
	-webkit-border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	behavior: url(<?php echo $url; ?>/css/css3.htc);
}
.button,
.button2 {
	position: relative;
	-webkit-border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	behavior: url(<?php echo $url; ?>/css/css3.htc);
}

#menusys_mega .menusub_mega {
	-webkit-border-radius: 0 0 5px 5px;
	-moz-border-radius: 0 0 5px 5px;
	border-radius: 0 0 5px 5px;
	behavior: url(<?php echo $url; ?>/css/css3.htc);
}

