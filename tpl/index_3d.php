<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>

        <title>Cerebrit.com</title>
	<!-- Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<?php
		$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
		if ( $isiPad ) { ?>
		<?php // This is an iPad! ?>
		<meta name="viewport" content="width=1080; initial-scale=.7; maximum-scale=1.0, user-scalable=no;">
		<?php    } else { ?>
		<?php // Not an iPad ?>
		<meta name="viewport" content="width=960, minimum-scale=.33, maximum-scale=.33, user-scalable=no">
		<?php } ?>		
	
		<!-- JS libraries -->
		<script type="text/javascript" src="/js/lib/jquery.1.7.1.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.jeditable.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.validate.min.js"></script> 
		<script type="text/javascript" src="/js/lib/md5-min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.wipetouch.js"></script>
		<script type="text/javascript" src="/js/lib/shake.js"></script>
		<script type="text/javascript" src="/js/lib/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="/js/lib/jquery.tinyscrollbar.min.js"></script>	 
		<script type="text/javascript" src="/js/lib/jquery.sparkline.min.js"></script>	
		<script type="text/javascript" src="/js/lib/jquery.spritely-0.6.js"></script>
		<script type="text/javascript" src="/js/lib/jquery.flip.min.js"></script>
		<script type="text/javascript" src="/js/lib/tiny_mce/jquery.tinymce.js"></script>

		<script type="text/javascript" src="/js/lib/three.min.js"></script>
		<script type="text/javascript" src="/js/lib/CSS3DRenderer.js"></script>
        <script type="text/javascript" src="/js/lib/TrackballControls.js"></script>

		<script type="text/javascript" src="/js/lib/jquery.ui.core.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.widget.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.mouse.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.draggable.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wColorPicker.1.2.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wPaint.1.3.min.js"></script>
		
		<!-- <script type="text/javascript" src="/js/lib/jquery.ui.selectmenu.min.js"></script> -->

		<script type="text/javascript" src="/js/lib/jquery.ui.touch-punch.min.js"></script>
		
		<script type="text/javascript" src="/js/global.js"></script>
		<!-- <script type="text/javascript" src="/js/mobile.js"></script> -->
		<script type="text/javascript" src="/js/lib/sprite.js"></script>
        
		<script type="text/javascript" src="/js/lib/kinetic-v4.2.0.min.js"></script>
	
		<!-- Style -->
		<link rel="shortcut icon" href="favicon.ico" />
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<link href="/css/3d.css" rel="stylesheet" type="text/css" />
		<link href="/css/iphone.css" rel="stylesheet" media="all and (max-device-width: 480px)"> 

		
		<!-- <link href="/css/ipad_portrait.css" rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)"> -->
		<!-- <link href="/css/ipad_landscape.css" rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)"> -->
		<!-- <link href="/css/desktop.css" rel="stylesheet" media="all and (min-device-width: 1025px)">  -->
	
		<link href="/css/wColorPicker.1.2.min.css" rel="stylesheet" type="text/css" />
		<link href="/css/wPaint.1.3.min.css" rel="stylesheet" type="text/css" />
	
		<link href="/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
		
		<link href="http://fonts.googleapis.com/css?family=Megrim" rel="stylesheet" type="text/css">
<!--
		<link href="http://fonts.googleapis.com/css?family=Geo" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Stint+Ultra+Condensed" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Megrim" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Ewert" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet" type="text/css">
-->
		<!-- Google Analytics -->
		<script type="text/javascript">

			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-17953479-1']);
			_gaq.push(['_trackPageview']);

			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();

		</script>

		<script language="JavaScript" type="text/JavaScript"></script>
	
		
    </head>

    <body>

    <div>hello 3d </div> 
		<div id="3d_content">
        </div>
       
		<script type="text/javascript" src="/js/3D.js"></script>

	

    </body>
</html>
