<div id="most_recent">

	<span id="recent_pages" style="color: blue;">Recent Pages: </span> 

</div>

<script>
	
	var chapter_id= $("#header").attr("chapter_id");
	var logbook_id= $("#logbooks").attr("logbook_id");
	
	LoadRecentUpdates(chapter_id, logbook_id, 0, 3, "page", function(updates) {

		var len= updates.updates.length - 1;
		$(updates.updates).each(function(index, data) {
			if(index!= len)
			{
				$("#most_recent").append("<span class='page_link'><a href='/" + data['article_name'] + "/" + data['page_name'] + "'>" + data['page_name'] + "</a>, </span>");
			}
			else
			{
				$("#most_recent").append("<span class='page_link'><a href='/" + data['article_name'] + "/" + data['page_name'] + "'>" + data['page_name'] + "</a></span>");
			}
		});
	});

</script>
