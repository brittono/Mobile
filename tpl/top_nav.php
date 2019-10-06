<div id="top_nav_element1">cb_element1_cb</div>
<div id="top_nav_divider">:</div>
<div id="top_nav_element2">cb_element2_cb</div>

<!--
<a id="top_nav_article_link" href="/<?php echo $logbook->user_name . '/' . $logbook->article_name . '/'; ?>"><?php echo $logbook->article_name; ?></a>
<a id="top_nav_page_link" href="/<?php echo $logbook->user_name .'/'. $logbook->article_name .'/'. $logbook->page_name . '/'; ?>"><?php echo $logbook->page_name; ?></a>
<a id="top_nav_user_link" href="/<?php echo $logbook->user_name . '/'; ?>"><?php echo $logbook->user_name; ?></a>
-->

<script>
	
	var messages = [
		{ text : "Welcome to Cerebrit.com" },
		{ text : "Take some time to explore the site..." },
		{ style : "color: rgb(202, 101, 40);", text : "... swipe left or right to change pages ..." },
		{ style : "color: green; text-decoration: underline;", text : "... swipe up or down to change articles ..." },
		{ style : "color: black;", text : "... <span style='color: #FF84B3; font-size: 24px; font-style: italic; font-weight: bold;'>SHAKE</span> on a mobile to change user" }
	];
	
	ShowMessages(messages, 0);
	GenerateLinks('logbook');
	
	var chapter_name= $("#header").attr("chapter_name");
	var logbook_name= $("#logbooks").attr("logbook_name");
	var article_name= $("#logbooks").attr("article_name");
	var page_id= $("#logbooks").attr("page_id");
	
	LoadCommentCount(page_id, function(count) {
	
		$("#comment_count").html(count.comment_count);
	});

</script>
	
