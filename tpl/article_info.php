<div id="article_info">

	<div id="info_article_name_label">Article Name: </div><div id="info_article_name">...</div>
	<!-- <div id="info_author_label">Created by: </div><div id="info_author">...</div>
	<div id="info_account_label">Type: </div><div id="info_account">...</div>-->
	<div id="info_contributors_label">Contributors: </div><div id="info_contributors">...</div>
	<!--<div id="info_last_edit_label">Last Edit: </div><div id="info_last_edit">...</div>-->
	<!-- <div id="info_page_loads_label">Page Loads: </div><div id="info_page_loads">...</div> -->
	<div id="info_page_label">Page: </div><div class="pagination pagination-mini" id="info_page">...</div>
	<div id="info_comments_label">Comments: </div><div id="info_comments"><a id="fb_comments" href="javascript:"></a></div>

</div>


<script>	
	
	var article_id= $("#logbooks").attr("article_id");
	var page_id= $("#logbooks").attr("page_id");
	LoadArticleInfo(article_id, page_id, "json", function(info) {
		
		$("#info_article_name").html("<span id='prev_article_link'><a href='javascript:'>&laquo;</a></span>" + "<a href='http://wiki.cerebrit.com/" + info.article_name + "'>" + info.article_name + "</a>" + "<span id='next_article_link'><a href='javascript:'>&raquo;</a></span>");
		$("#info_author").html("<a href='http://wiki." + info.account_name + ".cerebrit.com/" + info.user_name  + "'>" + info.user_name + "</a>");
		$("#info_account").html("<a href='http://feed." + info.account_name + ".cerebrit.com/'>" + info.account_name + "</a>");
		$("#info_page_loads").text(info.page_loads);
		$("#info_last_edit").text(info.last_modified);
		$("#info_page_loads").text(info.page_loads);
		$("#info_contributors").html(info.contributors);
		$("#info_page").html(info.page);
		$("#info_comments a").text(info.comment_count);
	});
	
</script>