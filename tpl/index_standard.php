<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Cookbook</title>
<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- JS libraries -->
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.jeditable.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/md5-min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.wipetouch.js"></script>
<!-- <script type="text/javascript" src="/js/lib/shake.js"></script> -->
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.tinyscrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.spritely-0.6.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.flip.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.masonry.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.easing.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.booklet.latest.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery-css-transform.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/three.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/base64.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/CSS3DRenderer.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/rotate3Di.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/canvas2image.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/html2canvas.js"></script>

<!-- <script src="http://mrdoob.github.com/three.js/build/three.min.js"></script> -->

<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.ui.mouse.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.ui.draggable.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/wColorPicker.1.2.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/wPaint.1.3.min.js"></script>

<!-- <script type="text/javascript" src="/js/lib/jquery.ui.selectmenu.min.js"></script> -->

<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/lib/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="<?php echo DOCUMENT_ROOT ?>/js/global.js"></script>
<!-- <script type="text/javascript" src="/js/mobile.js"></script> -->
<!-- <script type="text/javascript" src="/js/starfield.js"></script> -->

<!-- Style -->
<link rel="shortcut icon" href="http://www.cerebrit.com:81/<?php echo DOCUMENT_ROOT ?>/img/cerebrit_mobile_header-favicon.ico">
<link href="<?php echo DOCUMENT_ROOT ?>/css/cook.css" rel="stylesheet" type="text/css" />
<link href="<?php echo DOCUMENT_ROOT ?>/css/iphone.css" rel="stylesheet" media="all and (max-device-width: 480px)">
<!--<link href="<?php echo DOCUMENT_ROOT ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->

<!-- <link href="/css/ipad_portrait.css" rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)"> -->
<!-- <link href="/css/ipad_landscape.css" rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)"> -->
<!-- <link href="/css/desktop.css" rel="stylesheet" media="all and (min-device-width: 1025px)">  -->

<link href="<?php echo DOCUMENT_ROOT ?>/css/wColorPicker.1.2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo DOCUMENT_ROOT ?>/css/wPaint.1.3.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo DOCUMENT_ROOT ?>/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Megrim" rel="stylesheet" type="text/css">
<link href="<?php echo DOCUMENT_ROOT ?>/css/jquery.booklet.latest.css" rel="stylesheet" type="text/css" />
</head>

<body>

<!-- <script type="text/javascript" src="/js/facebook.js"></script> -->

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
				
				$account->FetchTemplate("header", array("account"=>$account->account, "chapter_id"=>$account->chapter_id, "chapter_name"=>$account->chapter_name, "site_name"=>$site_name, "logbook_name"=>$account->logbook_name, "element1"=>$account->article_name, "element2"=>$account->page_name, "account_id"=>$account->account_id), "render"); 
				echo $account->template_data;

			?>
  </div>
  <div id="logbooks_container" swipe_events="true">
    <?php $account->FetchTemplate("logbooks", array("logbook_id"=>$account->logbook_id, "user_id"=>$account->user_id, "article_id"=>$account->article_id, "page_id"=>$account->page_id, "chapter_name"=>$account->chapter_name, "logbook_name"=>$account->logbook_name, "article_name"=>$account->article_name, "page_name"=>$account->page_name), "curl"); ?>
  </div>
  <div id="page_container">
  <!--
		<div id="top_nav">

			<?php //$account->FetchTemplate("top_nav", array("element1"=>$account->logbook_name, "element2"=>$account->article_name), "render"); ?>
	
		</div>
		--> 
  
  <!--
			<div id="comments_link">
				Comments
			</div>
		
			<div id="comments">

				

			</div>
--> 
  <!-- <div id="updates"><div id="pull_down">Pull DOWN for updates</div><a href="javascript:" id="fb_comments"><span id="comment_count"></span> Comments </a></div> -->
  <div id="page"> </div>

<ul>
<li><a id="flip" href="javascript:Flip();">Flip!</a></li>
<li><a href="javascript:BMP();">Flatten to BMP</a></li>
<li><a href="javascript:">Export as HTML</a></li>
<li><a href="javascript:">View in <strong><span style="color: blue;">3</span><span style="color: red;">D</span></strong></a></li>
<li><a href="javascript:">Link</a></li>
<li><a href="javascript:">View as Flipbook</a></li>
<li><a href="javascript:">Export to PDF</a></li>
</ul>
  
  <!--<div id="left_arrow"></div> --> 
  <!-- <div id="right_arrow"></div> --> 
  
</div>

<script>
	//$('#page').rotate3Di(30);
</script>

</body>
</html>
