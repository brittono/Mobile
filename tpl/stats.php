<div id="stats">

	<table id="stats_table">
		<tr>
			<td style="color: #000; font-size: 10px;" colspan=2>Tiles and Edits</td>
		</tr>
		<tr>
			<td id="edit_chart"></td>
			<td id="tile_count" style="color: #000; text-align: right; font-size: 9px; width: 25px;"></td>
		</tr>
	</table>

</div>

<script>

	var chapter_id= $("#mobile_header").attr("chapter_id");

	LoadStats(chapter_id, 0, 0, function(stats) {
		$("#tile_count").text(stats.edit_count);
		$("#edit_chart").sparkline(stats.tile_timeline, {width: "80px"});
	});

</script>
