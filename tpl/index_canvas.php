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

		<!-- <script src="http://mrdoob.github.com/three.js/build/three.min.js"></script> -->

		<script type="text/javascript" src="/js/lib/jquery.ui.core.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.widget.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.mouse.min.js"></script> 
		<script type="text/javascript" src="/js/lib/jquery.ui.draggable.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wColorPicker.1.2.min.js"></script> 
		<script type="text/javascript" src="/js/lib/wPaint.1.3.min.js"></script>
		
		<!-- <script type="text/javascript" src="/js/lib/jquery.ui.selectmenu.min.js"></script> -->

		<script type="text/javascript" src="/js/lib/jquery.ui.touch-punch.min.js"></script>
		
		<script type="text/javascript" src="/js/global.min.js"></script>
		<script type="text/javascript" src="/js/mobile.min.js"></script>
		<script type="text/javascript" src="/js/lib/sprite.js"></script>
		<script type="text/javascript" src="/js/lib/kinetic-v4.2.0.min.js"></script>
	
		<!-- Style -->
		<link rel="shortcut icon" href="favicon.ico" />
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<link href="/css/wiki.css" rel="stylesheet" type="text/css" />
		<link href="/css/wiki_frame.css" rel="stylesheet" type="text/css" />
		<link href="/css/canvas.css" rel="stylesheet" type="text/css" />
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

		<script language="JavaScript" type="text/JavaScript">

		function LoadWiki()
		{
			$.ajax({
				type: 'GET',
				url: 'http://dev.cerebrit.com/ajax/logbook.php',
				dataType: 'jsonp',
				data: {
					action: 'Home'
				}
			});	
		}
		
		function DisplayHomeWiki(wiki)
		{
			//alert(wiki.page_name);
			$("#top_nav_element2").html("<a href='http://wiki.cerebrit.com/" + wiki.article_name + "/" + wiki.page_name + "'>" + wiki.page_name + "</a>");
		}		
		
		function MM_preloadImages() { //v3.0
		  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
			var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
			if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
		}

		function AnimateJackson() {

			document.images['jackson'].src= 'images/jackson-jackson_02_over.gif';
			document.images['jackson_01'].src= 'images/jackson_01-jackson_02_over.gif';
			document.images['jackson_02'].src= 'images/jackson_02-over.gif';
		}

		function ReleaseJackson() {

			document.images['jackson'].src= 'images/jackson.gif';
			document.images['jackson_01'].src= 'images/jackson_01.gif';
			document.images['jackson_02'].src= 'images/jackson_02.gif';
		}
			
		</script>		
		
    </head>

    <body onLoad="MM_preloadImages('images/no-over.gif', 'images/action-over.gif', 'images/jackson_01-over.gif', 'images/cerebrit-over.gif')">

		<div id="page_container">
		
			<div id="header_container">
			
				<?php 

					if($account->chapter_name=== "")
					{
						$site_name= "";
					}
					else
					{
						$site_name= $account->chapter_name . ".";
					}
					$account->FetchTemplate("header", array("account"=>$account->account, "chapter_id"=>$account->chapter_id, "chapter_name"=>$account->chapter_name, "site_name"=>$site_name, "logbook_name"=>$account->logbook_name, "element1"=>$account->article_name, "element2"=>$account->page_name), "render"); 

				?>
	 
			</div>
			
			<div id="splash_container">
			
	<table align="center" cellpadding="10" cellspacing="10" bgcolor="#000000" class="bordertable">  <!-- outborder table  -->
<tr><td>

	<table height="378" border="0" cellpadding="0" cellspacing="0">									<!-- block level content -->
					
		<td width="600" rowspan="2">				<!-- title table cell -->
		
			<table border="0" width="100%" cellpadding="0" cellspacing="0">							<!-- title table -->
				<tr>
					<td width="50%"><img src="../naj.js/images/no_01.gif" name="no" width="227" height="132" onMouseOut="document.images['no'].src= '../naj.js/images/no_01.gif'" onMouseOver="document.images['no'].src= '../naj.js/images/no_01-over.gif'">&nbsp;<img src="../naj.js/images/dash.gif">
					</td>
					<td width="50%" rowspan="2">
					
						<div class= "info" id="speech" style="position: absolute; left:40%; top: 8%; visibility: hidden; width: 400px; color: #625DAC;">... click me to download!</div>
						<div style="position: absolute; left:52%; top: 12%;"><a href="http://dev.cerebrit.com/uploads/NAJ_Win_v1.0.zip"><img src="../naj.js/images/cerebrit.gif" width="160" border= "0" name="cerebrit" onMouseOut="document.images['cerebrit'].src= '../naj.js/images/cerebrit.gif'; document.getElementById('speech').style.visibility='hidden';" onMouseOver="document.images['cerebrit'].src= '../naj.js/images/cerebrit-over.gif'; document.getElementById('speech').style.visibility='visible';"></a></div>
					
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
						
							<tr>
							
								<td rowspan="2" width="45"><img src="../naj.js/images/cerebrit_01.gif" width="45"></td>
								<td valign="top" width="75"><img src="../naj.js/images/cerebrit_02.gif" width="75" name="screen" onMouseOut="document.images['screen'].src= '../naj.js/images/cerebrit_02.gif'" onMouseOver="document.images['screen'].src= '../naj.js/images/cerebrit_02-over.gif'"></td>
								<td rowspan="2" width="39"><img src="../naj.js/images/cerebrit_03.gif" width="39"></td>
								<td valign="top" width="48"><img src="../naj.js/images/cerebrit_04.gif" width= "48" name="wave" onMouseOut="document.images['wave'].src= '../naj.js/images/cerebrit_04.gif'" onMouseOver="document.images['wave'].src= '../naj.js/images/cerebrit_04-over.gif'"></td>
								<td rowspan="2" width="150"><img src="../naj.js/images/cerebrit_05.gif" width="150"></td>
								
							</tr>
							
								<td><img src="../naj.js/images/cerebrit_06.gif" width="75"></td>
								<td><img src="../naj.js/images/cerebrit_07.gif" width="48"></td>
							<tr>
							
						
						
						</table>
					
					</td>
					
					
				</tr>
				<tr>
					
				<td><img src="../naj.js/images/action.gif" name="action" width="300" height="196" onMouseOut="document.images['action'].src= '../naj.js/images/action.gif'" onMouseOver="document.images['action'].src= '../naj.js/images/action-over.gif'"></td>
				</tr>
				<tr>	
					<td colspan="2"><img src="../naj.js/images/jackson_01.gif" name="jackson" width="600" height="126" onMouseOut="document.images['jackson'].src= '../naj.js/images/jackson_01.gif'" onMouseOver="document.images['jackson'].src= '../naj.js/images/jackson_01-over.gif'"></td>
				</tr>
			</table>		<!-- end title table -->
			
		</td>		<!-- end title table cell -->
		
		<td width="200">				<!-- info table cell -->
		
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="screenshots">		<!-- info table -->
				<tr style="color: white; text-align: center;"><td>This week</td></tr>
				<tr>
					<td><img src="../naj.js/images/hallway.gif" name="hallway" width="200"></td>
				</tr>
				<tr style="color: white; text-align: center;"><td>Last week</td></tr>
				<tr>
					<td><img src="../naj.js/images/dojo.gif" width="200"></td>
				</tr>
				<tr style="color: white; text-align: center;"><td>Most popular</td></tr>
				<tr>	
					<td><img src="../naj.js/images/park.gif" width="200"></td>
				</tr>
			</table>		<!-- end info table -->
			
		</td>							<!-- end info cell -->
	</tr>
	<tr>
		<td rowspan= "2" class="info">Created by: Britton O'Toole</td>				<!-- end screenshot cell -->
	</tr>
	</table>				<!-- end block content table -->
	
</td></tr>

</table>		<!-- end border table -->
			
			</div>
			
			<canvas id="canvas" width="960" height="600"></canvas>
			
<!--
			<div id="canvas_background">
			
				<div id="canvas_walkable1"></div>
				<div id="canvas_walkable2"></div>
				<div id="canvas_walkable3"></div>
				<div id="canvas_walkable4"></div>
			
				 <div id="character"/></div>
				<img id="character" src="./img/canvas/characters/iso_right.png"/>
			
				<div id="canvas_foreground"></div>
				
			</div>-->
			

			
		</div>

    </body>
</html>
