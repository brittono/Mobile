<div id="top_nav_article"></div>
<div id="top_nav_page"></div>

<!--
<a id="top_nav_article_link" href="/<?php echo $logbook->user_name . '/' . $logbook->article_name . '/'; ?>"><?php echo $logbook->article_name; ?></a>
<a id="top_nav_page_link" href="/<?php echo $logbook->user_name .'/'. $logbook->article_name .'/'. $logbook->page_name . '/'; ?>"><?php echo $logbook->page_name; ?></a>
<a id="top_nav_user_link" href="/<?php echo $logbook->user_name . '/'; ?>"><?php echo $logbook->user_name; ?></a>
-->


<script>

$(document).ready(function() {

	$("#top_nav").data("swipe_events", false);

	$("#comments_link").toggle(function() {}, function() {});

	$("#page").wipetouch({

		tapToClick: true,

		wipeLeft: function(result) { 

		},
		wipeRight: function(result) { 

		},
		wipeUp: function(result) { 

		},
		wipeDown: function(result) { 

		}
	});

	var messages = [
		{ text : "Start making edits" }
	];
	
	ShowMessages(messages, 0);

//	GenerateLinks();

});

</script>
