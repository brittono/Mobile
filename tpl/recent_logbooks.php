<div id="most_recent">

	<span id="recent_chapters" style="color: blue;">Recent Logbooks: </span> 

</div>

<script>
	
	var chapter_id= $("#header").attr("chapter_id");
	var logbook_id= $("#logbooks").attr("logbook_id");
	
	LoadRecentUpdates(0, 0, 0, 3, "logbook", function(updates) {

		var len= updates.updates.length - 1;
		$(updates.updates).each(function(index, data) {
			if(index!= len)
			{
				$("#most_recent").append("<span class='page_link'><a href='http://" + data['chapter_name'] + "." + data['logbook_name'] + ".cerebrit.com'>" + data['chapter_name'] + "." + data['logbook_name'] + "</a>, </span>");
			}
			else
			{
				$("#most_recent").append("<span class='page_link'><a href='http://" + data['chapter_name'] + "." + data['logbook_name'] + ".cerebrit.com'>" + data['chapter_name'] + "." + data['logbook_name'] + "</a> </span>");
			}
		});
	});

</script>
