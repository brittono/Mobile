<div id="views">

	<table id="views_table">
		<tr>
			<td>Views</td>
			<td id="view_count" style="color: white;"></td>
			<td id="view_chart"></td>
		</tr>
		<tr>
			<td>Facebook</td>
			<td id= "facebook" style="color: white;"></td>
			<td id="facebook_button"></td>
		</tr>
		<tr>
			<td>Twitter</td>
			<td id="twitter" style="color: white;"></td>
			<td id="twitter_button"></td>
		</tr>
	</table>

</div>

<script>

	var page_id= $("#logbooks").attr("page_id");

	LoadPageViews(page_id, function(page) {
		$("#view_count").text(page.views);
	});
/*
	LoadPageViews(page_id, function(page) {
		$("#view_count").sparkline(page.views, {width: "150px"});
	});
*/
</script>
