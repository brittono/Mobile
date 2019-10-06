<div id="most_popular">

	<span id="popular_pages" style="color: green;">Popular articles: </span> 

</div>

<script>

	$("#popular_pages").click(function() {
	
		LoadarticleList(0, function(list) {
		
			$("#page").html(list.list_data);
		});
	});

	var user_id= $("#logbooks").attr("user_id");

	LoadPopularPages(user_id, function(popular) {

		var len= popular.articles.length - 1;
		$(popular.articles).each(function(index, data) {
			if(index!= len)
			{
				$("#most_popular").append("<span class='page_link'><a href='/" + data['user_name'] + "/" + data['article_name'] + "'>" + data['article_name'] + "</a>, </span>");
			}
			else
			{
				$("#most_popular").append("<span class='page_link'><a href='/" + data['user_name'] + "/" + data['article_name'] +  "'>" + data['article_name'] + "</a></span>");
			}
		});
	});

</script>
