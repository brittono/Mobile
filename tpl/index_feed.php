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
		<meta name="viewport" content="width=980, minimum-scale=.3, maximum-scale=.3, user-scalable=no">
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
		<script type="text/javascript" src="/js/lib/jquery.masonry.min.js"></script>

		<script src="http://mrdoob.github.com/three.js/build/three.min.js"></script>

		<script type="text/javascript" src="/js/lib/jquery.ui.core.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.widget.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.mouse.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.draggable.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wColorPicker.1.2.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wPaint.1.3.min.js"></script>
		
		<!-- <script type="text/javascript" src="/js/lib/jquery.ui.selectmenu.min.js"></script> -->

		<script type="text/javascript" src="/js/lib/jquery.ui.touch-punch.min.js"></script>
		
		<script type="text/javascript" src="/js/global.js"></script>
		<script type="text/javascript" src="/js/mobile.js"></script>
	
		<!-- Style -->
		<link rel="shortcut icon" href="favicon.ico" />
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<link href="/css/iphone.css" rel="stylesheet" media="all and (max-device-width: 480px)"> 
		
		<?php if($account->chapter_name== "mobile")
		{ 
		?>
			<link href="/css/mobile.css" rel="stylesheet" media="all and (max-device-width: 480px)"> 
		<?php 
		}	
		?>

		
		<!-- <link href="/css/ipad_portrait.css" rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)"> -->
		<!-- <link href="/css/ipad_landscape.css" rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)"> -->
		<!-- <link href="/css/desktop.css" rel="stylesheet" media="all and (min-device-width: 1025px)">  -->
	
		<link href="/css/wColorPicker.1.2.min.css" rel="stylesheet" type="text/css" />
		<link href="/css/wPaint.1.3.min.css" rel="stylesheet" type="text/css" />
	
		<link href="/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />

		<link href="http://fonts.googleapis.com/css?family=Geo" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Stint+Ultra+Condensed" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Megrim" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Ewert" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet" type="text/css">

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

    </head>

    <body>
	
		<div id="fb-root"></div>
		<script>
			window.fbAsyncInit = function() {
			  FB.init({
				appId      : '410610009004609', // App ID
				channelUrl : 'www.cerebrit.com/', // Channel File
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true  // parse XFBML
			  });
			  // Additional initialization code here
			};
			// Load the SDK Asynchronously
			(function(d){
			   var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			   if (d.getElementById(id)) {return;}
			   js = d.createElement('script'); js.id = id; js.async = true;
			   js.src = "//connect.facebook.net/en_US/all.js";
			   ref.parentNode.insertBefore(js, ref);
			 }(document));
		</script>
	
		<div id="fb-root"></div>
		<script>
		/*
		(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		*/
		</script>	
	
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>	
	
		<div id="header_container">
		
			<?php $account->FetchTemplate("header", array("account"=>$account->account, "chapter_id"=>$account->chapter_id, "chapter_name"=>$account->chapter_name, "logbook_name"=>$account->logbook_name), "render"); ?>

		</div>

		<div id="container">
			
			<?php echo $account->FetchContent(0, "item"); ?>
				
		</div>

		<script>
			$(function(){
				$('#container').masonry({
					// options
					itemSelector : '.item',
					columnWidth : 50
				});
			});
			
			$('#mobile_page').infinitescroll({
			
				// callback		: function () { console.log('using opts.callback'); },
				navSelector  	: "a#link",
				nextSelector 	: "a#link",
				itemSelector 	: ".mobile_wrapper",
				debug		 	: true,
				dataType	 	: 'json',
				// behavior		: 'twitter',
				appendCallback	: false, // USE FOR PREPENDING
				// pathParse     	: function( pathStr, nextPage ){ return pathStr.replace('2', nextPage ); }
			}, function( response ) {
				alert('here');
				});
		
			
		</script>

    </body>
</html>
