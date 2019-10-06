<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>
        <title>m.Cerebr.it</title>
	<!-- Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		
		
		<?php $isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
		if ( $isiPad ) { ?>
		<?php // This is an iPad! ?>
		<meta name="viewport" content="width=1080, initial-scale=.7, maximum-scale=1.0, user-scalable=no">
		<?php    } else { ?>
		<?php // Not an iPad ?>
		<meta name="viewport" content="width=450, minimum-scale=.7, maximum-scale=.7, user-scalable=no">
		<?php } ?>		
	
		<meta name="apple-mobile-web-app-capable" content="yes" />
	
		<!-- JS libraries -->
		<script type="text/javascript" src="/js/lib/jquery.1.7.1.min.js"></script>
		<!-- <script type="text/javascript" src="/js/lib/jquery.jeditable.min.js"></script> -->
		<script type="text/javascript" src="/js/lib/jquery.validate.min.js"></script>
		<script type="text/javascript" src="/js/lib/md5-min.js"></script>
		<script type="text/javascript" src="/js/lib/jquery.wipetouch.js"></script>
		<script type="text/javascript" src="/js/lib/html2canvas.js"></script>
		<script type="text/javascript" src="/js/lib/base64.js"></script>
		<script type="text/javascript" src="/js/lib/canvas2image.js"></script>
		<!-- <script type="text/javascript" src="/js/lib/three.min.js"></script>-->
		<!-- <script type="text/javascript" src="/js/lib/TrackballControls.js"></script> --> 
		<!--<script type="text/javascript" src="/js/lib/CSS3DRenderer.js"></script>-->
        <script type="text/javascript" src="/js/lib/rotate3Di.js"></script>
        <script type="text/javascript" src="/js/lib/jquery.easing.min.js"></script>
        <script type="text/javascript" src="/js/lib/jquery.booklet.latest.min.js"></script>
        <script type="text/javascript" src="/js/lib/jquery-css-transform.js"></script>
		<!-- <script type="text/javascript" src="/js/lib/jquery.infinitescroll.min.js"></script> -->
		<!-- <script type="text/javascript" src="/js/lib/jquery.ui.selectmenu.min.js"></script> -->
		<!-- <script type="text/javascript" src="/js/lib/shake.js"></script>-->
		<script type="text/javascript" src="/js/lib/jquery-ui-1.8.16.custom.min.js"></script>
		<!-- <script type="text/javascript" src="/js/lib/jquery.tinyscrollbar.min.js"></script>	 -->
		<script type="text/javascript" src="/js/lib/jquery.sparkline.min.js"></script>
		<!-- <script type="text/javascript" src="/js/lib/jquery.spritely-0.6.js"></script> -->
		<!-- <script type="text/javascript" src="/js/lib/jquery.flip.min.js"></script> -->
		<!-- <script type="text/javascript" src="/js/lib/tiny_mce/jquery.tinymce.js"></script>-->

		<!-- <script src="http://mrdoob.github.com/three.js/build/three.min.js"></script> -->

		<!-- Style -->
		<link rel="shortcut icon" href="../img/cerebrit_mobile_header-favicon.ico">
		
		<!--<link href="/css/style.css" rel="stylesheet" type="text/css" /> -->
		<link href="/css/iphone.css" rel="stylesheet" media="all and (max-device-width: 480px)"/> 
		<link href="/css/mobile.css" rel="stylesheet"/> 
        
		<link href="/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
        <link href="/css/jquery.booklet.latest.css" rel="stylesheet" type="text/css" />        
	
		<script type="text/javascript" src="/js/lib/jquery.ui.core.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.widget.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.mouse.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.draggable.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wColorPicker.js"></script> 
		<script type="text/javascript" src="/js/lib/wPaint.js"></script>

		<script type="text/javascript" src="/js/lib/jquery.ui.touch-punch.min.js"></script>
		
		<script type="text/javascript" src="/js/global.js"></script>
		<script type="text/javascript" src="/js/mobile.js"></script>
	
		<link href="/css/wColorPicker.css" rel="stylesheet" type="text/css" />
		<link href="/css/wPaint.css" rel="stylesheet" type="text/css" />
	
		<link href="/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />

    </head>

    <body> 

		<div class="mobile_page_wrapper">

			<div id="logbooks" logbook_id= "20" logbook_name= "all" article_id= "0" page_id= "0">

			</div>
		
			<div id="mobile_header" chapter_id="26" chapter_name="mobile">
			
				<input class="tile_id_hidden" type="hidden" value="">
				<input class="link_id_hidden" type="hidden" value="">
				<input class="tile_position_hidden" type="hidden" value="">
				<input class="tile_style_hidden" type="hidden" value="">
				<input class="tile_brand_hidden" type="hidden" value="0">
				
                
				<div id="mobile_page_title">
					<a href="http://mobile.cerebrit.com" id="mobile_page_title_link">m.Cerebr.it</a>
				</div>
                
				<?php //$account->FetchTemplate("top_nav", array("element1"=>$account->logbook_name, "element2"=>$account->article_name), "render"); ?>

				<a id="cerebrit_header_link" href="http://www.cerebrit.com"><img id="cerebrit_mobile_header" src="/img/cerebrit_mobile_header.gif"/></a>
                
				<div id="home_title" template="" nav="close">
					<a href="javascript:OpenNav();"><img src="../img/quill_pen.png" style="position: absolute; top: 10px; left: 2px; width: 20px;"/><a>
				</div> 
                
				<div id="catnav">

				<ul id="nav">
					<li><a href="http://www.cerebrit.com/">Home</a></li>
					<li><a href="http://www.cerebrit.com:82/">CMS</a></li>
					<li><a href="http://seo.cerebrit.com/">SEO</a></li>	  
					<li><a href="http://seo.cerebrit.com/">NOLJ</a></li>
					<!-- <li id="flipbook_link" style="margin-left: 40px;"><a href="javascript:">-Flipbook-</a></li>	 -->
					<li>
						<div id="article_list_container">
							loading...
						</div>
					</li>
				</ul>
				
				</div> <!-- Closes catnav -->				
		
				<div id="account" template="sign_in_mobile"></div>	

				<script type="text/javascript" src="/js/mobile_sign_in.js"></script>
				
			</div>

			<div id="mobile_container">
				
				<div id="navigation_container"></div>
				<div id="mobile_parse"></div>
				<div id="mobile_page" style=""></div>

				
			</div>
			
		</div>
	
		<div class="mobile_footer"><div id="footer_msg">Mobile Feed</div></div>
		
    </body>
</html>
