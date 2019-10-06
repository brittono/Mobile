<div id="public_controls">

	<textarea id="tile_content" style="left: 5px; top: 3px; width: 260px; height: 65px; font-size: 12px;"></textarea>	

	<input class="btn" id="add_tile_button" value="Add" type="button" style="font-size: 10px; left: 120px; position: absolute; top: 70px;">
	<input class="btn" id="add_paint_button" value="Paint" type="button" style="font-size: 10px; left: 165px; position: absolute; top: 70px;">
	<input class="btn" id="save_tile_button" value="Save" type="button" style="font-size: 10px; left: 215px; position: absolute; top: 70px;">	
	<input class="btn" id="move_top_button" value="Top" type="button" style="font-size: 10px; left: 75px; position: absolute; top: 70px;">	
	
	<input class="tile_id_hidden" type="hidden">
	<input class="tile_position_hidden" type="hidden">
	<input class="tile_style_hidden" type="hidden">
	<input class="tile_content_hidden" type="hidden">
	<input class="tile_back_hidden" type="hidden">	
	<input class="tile_brand_hidden" type="hidden">	
	<input class="tile_type_hidden" type="hidden">	

</div>

<script>

	$(document).on('mouseup', '.tile_wrapper', LoadTileValues);	

	$("#save_tile_button").click(function() {

		var content= $('#tile_content').val();
		var brand= $(".tile_brand_hidden").val();

		$("#page").css("background", "#fff");
		$("#page").html("<img src='../img/loading2.gif'/>");		
		SaveTile(content, function(tile) {

			var article_id= $("#logbooks").attr("article_id");		
			var page_id= $("#logbooks").attr("page_id");

			LoadPage(article_id, page_id, "", function(page) {

				$("#page").css("background", "url('../img/parchment_web.jpg')");
				$("#page").html(page.page_data);
				$.getScript('/js/public.js');
			});	
		});
	});	
	
	$("#add_paint_button").click(function() {

		var tile_content= "";

		AddTile("paint", tile_content, function(paint) {
		
			AppendPaint(paint.tile_id, paint.style);
		});
	});	
	
	$("#add_tile_button").click(function() {

		var tile_content= $("#tile_content").val();
		
		AddTile("editable", tile_content, function(tile) {
			
			AppendPublicTile(tile.tile_id, tile.style, tile.content);
			$(".tile_content_hidden").val(tile.tile_content);
		});
	});	
	
	$("#move_top_button").click(function() {
	
		var tile_id= $(".tile_id_hidden").val();
		var page_id= $("#logbooks").attr("page_id");
		
		$("#page").css("background", "#fff");
		$("#page").html("<img src='../img/loading2.gif'/>");		
		
		MoveToTop(tile_id, page_id, "json", function(tile) {
		
			var article_id= $("#logbooks").attr("article_id");
			
			LoadPage(article_id, page_id, "", function(update) {
	
				$("#page").css("background", "url('../img/parchment_web.jpg')");
				$("#page").html(update.page_data);
				$.getScript('/js/public.js');
			});
		});
	});
	
</script>