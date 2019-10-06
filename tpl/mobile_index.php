<?php

	include_once('./inc/constants.inc');
	include_once('./inc/account.inc');
	
	$account= new Account();
	$account->StartSession();
	
	$account->account_id= 1;
	$account->account= 'recent_chapters';
	$account->logbook_id= $account->FetchField("logbook_id", "logbooks", "logbook_id<>0 ORDER BY RAND()");
	$account->logbook_name= $account->FetchName($account->logbook_id, "logbook");
	$account->chapter_id= $account->FetchField("chapter_id", "articles", "logbook_id=$account->logbook_id ORDER BY RAND()");
	$account->chapter_name= $account->FetchName($account->chapter_id, "chapter");
	
	$host = parse_url($_SERVER["HTTP_HOST"]);
	$host= str_replace('%20', ' ', $host);
	$domain = explode('.', $host['path']);
	$subdomain = array_slice($domain, 0, count($domain) - 2 );
	
	if(isset($subdomain[0]) && $subdomain[0]!= "")
	{
		$account->account= 'stats';
		$account->chapter_name= $subdomain[0];
		$account->chapter_id= $account->FetchID($account->chapter_name, "chapter");
		$account->logbook_id= $account->FetchField("logbook_id", "articles", "chapter_id= $account->chapter_id ORDER BY RAND()");
		$account->logbook_name= $account->FetchName($account->logbook_id, "logbook");
	}
	if(isset($subdomain[1]) && $subdomain[1]!= "")
	{
		$account->account= 'recent_articles';		
		$account->chapter_name= $subdomain[1];
		$account->chapter_id= $account->FetchID($account->chapter_name, "chapter");
	}
	
	$request= str_replace('%20', ' ', $_SERVER["REQUEST_URI"]);
	$request= explode('/', $request);
	
	if(isset($request[1]) && $request[1]!= "")
	{
		$account->account= 'sign_in';
		$account->article_name= $request[1];
		$account->article_id= $account->FetchArticleID($account->chapter_id, $account->article_name);
		$account->logbook_id= $account->FetchField("logbook_id", "articles", "article_id= $account->article_id");
		
		if($account->article_id)
		{
			$account->account= 'recent_pages';
		}
	}
	if(isset($request[2]) && $request[2]!= "")
	{
		$account->account= '';
		$account->redirect= '404';
		$account->page_name= $request[2];
		$account->page_id= $account->LookUpPage($account->article_id, $account->page_name);

		if($account->page_id)
		{
			$account->account= 'stats';
			$account->redirect= '';
		}
	}
	if(isset($request[3]) && $request[3]!= "")
	{
		$account->account= '';	
		$account->user_name= $request[3];
		$account->user_id= $account->FetchId($account->user_name, "user");

		if($account->user_id)
		{
			$account->account= 'most_recent';
			$account->redirect= 'most_recent';
		}
	}

	$account->FetchArticle($account->chapter_id, $account->article_id, 0, "");

	//echo $account->chapter_id . "  " . $account->logbook_id . "  " . $account->article_id;
	
	if($account->chapter_name== "mobile")
	{
		include_once("./index.php");
	}
	else
	{
		include_once("./mobile_index.php");
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

    <head>

        <title>Scriblz</title>
	<!-- Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<?php $isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
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

		<script src="http://mrdoob.github.com/three.js/build/three.min.js"></script>

		<script type="text/javascript" src="/js/lib/jquery.ui.core.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.widget.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.mouse.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.draggable.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wColorPicker.js"></script> 
		<script type="text/javascript" src="/js/lib/wPaint.js"></script>
		
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
	
		<link href="/css/wColorPicker.css" rel="stylesheet" type="text/css" />
		<link href="/css/wPaint.css" rel="stylesheet" type="text/css" />
	
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

		<div id="logbooks_container" swipe_events="true">

			<?php $account->FetchTemplate("logbooks", array("logbook_id"=>$account->logbook_id, "user_id"=>$account->user_id, "article_id"=>$account->article_id, "page_id"=>$account->page_id, "chapter_name"=>$account->chapter_name, "logbook_name"=>$account->logbook_name, "article_name"=>$account->article_name, "page_name"=>$account->page_name), "curl"); ?>
			
		</div>

		<div id="top_nav">

			<?php $account->FetchTemplate("top_nav", array("element1"=>$account->logbook_name, "element2"=>$account->article_name), "render"); ?>
	
		</div>

		<div id="page_container">
<!--
			<div id="comments_link">
				Comments
			</div>
		
			<div id="comments">

				<?php echo $account->comment_data; ?>

			</div>
-->
			<div id="updates"><div id="pull_down">Pull DOWN for updates</div><a href="javascript:" id="fb_comments"><span id="comment_count"></span> Comments </a></div>	
			<div id="page">
			
				<?php echo $account->page_data; ?>
				
			</div>
			<div id="left_arrow"></div>
			<div id="right_arrow"></div>
			
		</div>

    </body>
</html>
