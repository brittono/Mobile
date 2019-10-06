
<div id="controls" page="1" select="article">

	<div id="article_controls">
		
		<div id="article_select"></div>
		
		<div id="page_select"></div>
		
	</div>
	
	<input type="text" name="title" id="title"/>
	<input type="button" name="add" id="add" value="+" class="btn"/>
	<input type="button" name="delete" id="delete" value="-" class="btn"/>
	<input type="button" name="save" id="save" value= "s" class="btn"/>
	<input id="add_tile_button" value="Add" type="button" class="btn">
	<input id="add_paint_button" value="Paint" type="button" class="btn">
	<input id="save_tile_button" value="Save" type="submit" class="btn">
	<div id="more2"><a href="javascript:">More</a></div>

	<textarea id="tile_content"></textarea>	
	
		<!-- <input class="tile_contents" type="hidden"> -->

	<input class="tile_id_hidden" type="hidden">
	<input class="tile_position_hidden" type="hidden">
	<input class="tile_style_hidden" type="hidden">
	<input class="tile_content_hidden" type="hidden">
	<input class="tile_back_hidden" type="hidden">	
	<input class="tile_brand_hidden" type="hidden">	
	<input class="tile_type_hidden" type="hidden">	

</div>

<script>

	$("#header").attr("swipe_events", "false");

	var article_id= $("#logbooks").attr("article_id");	
	var page_id= $("#logbooks").attr("page_id");
	
	//$(document).on('mouseup', '.tile_wrapper', LoadTileValues);	
	
	LogbookSelect(article_id, page_id);

	AttachArticleLinkEvents();
	AttachPageLinkEvents();

	$("#save").click(function() {
	
		var select= $("#controls").attr("select");
		var user_id= $("#logbooks").attr("user_id");
		var article_id= $("#logbooks").attr("article_id");
		var page_id= $("#logbooks").attr("page_id");
		var title= $("#title").val();
	
		var article_order= $("#article_list").sortable("toArray");
		var page_order= $("#page_list").sortable("toArray");
	
		SaveArticleOrder(article_order, page_order);

		if(title!= '')
		{
			if(select== "article")
			{		
				SaveArticle(article_id, 0, title, function(article) {
					
					$("#article_select").html("loading...");
					$("#page_select").html("loading...");
					LogbookSelect(article_id, page_id);
					$("#title").val("");
				});
			}
			else
			{
				SaveArticle(article_id, page_id, title, function(article) {
					
					$("#article_select").html("loading...");
					$("#page_select").html("loading...");
					LogbookSelect(article_id, page_id);
					$("#title").val("");
				});			
			}
		}
	});

	$("#add").click(function() {

		var select= $("#controls").attr("select");
		var chapter_id= $("#header").attr("chapter_id");
		var logbook_id= $("#logbooks").attr("logbook_id");
		var user_id= $("#logbooks").attr("user_id");
		var article_id= $("#logbooks").attr("article_id");
		var page_id= $("#logbooks").attr("page_id");
		var title= $("#title").val();
	
		if(select== "article")
		{
			CreateArticle(chapter_id, logbook_id, title, function(article) {
				$("#article_select").html("loading...");
				$("#page_select").html("loading...");
				LogbookSelect(article.article_id, article.page_id);
			});
		}
		else
		{
			CreatePage(article_id, title, function(article) {

				$("#page_select").html("loading...");
				LogbookSelect(article_id, article.page_id);
			});	
		}

	});

	$("#delete").click(function() {
	
		var select= $("#controls").attr("select");
		var article_id= $("#logbooks").attr("article_id");
		var page_id= $("#logbooks").attr("page_id");
		var title= $("#title").val();
	
		if(select== "article")
		{
			DeleteArticle(article_id, title);
			$("#article_select").html("loading...");
			$("#page_select").html("loading...");
			LogbookSelect(0, 0);
		
		}
		else
		{
			DeletePage(page_id, title);
			$("#page_select").html("loading...");
			LogbookSelect(article_id, 0);
		}

	});

	$("#add_tile_button").click(function() {

		var tile_content= $("#tile_content").val();
		//$("#tile_content").val(tile_content);
		
		AddTile("editable", tile_content, function(tile) {
			
			AppendTile(tile.tile_id, tile.style);
			$(".tile_content_hidden").val(tile.tile_content);
		});
	});

	$("#add_paint_button").click(function() {

		var tile_content= "";

		AddTile("paint", tile_content, function(paint) {
		
			AppendPaint(paint.tile_id, paint.style);
		});
	});

	$("#save_tile_button").click(function() {

		$("._wPaint_menu").css("display","none");
		var content= $('#tile_content').val();
		var brand= $(".tile_brand_hidden").val();
		$(".tile_content_hidden").val(content);
		
		$("#page").html("<img src='/website/img/loading3.gif'/>");
		SaveTile(content, function(tile) {

			var article_id= $("#logbooks").attr("article_id");		
			var page_id= $("#logbooks").attr("page_id");

			LoadPage(article_id, page_id, "", function(page) {

				$("#page").html(page.page_data);
				$.getScript('/website/js/user.js');
			});	
		});

		var back_content= $(".tile_back_hidden").val();
		SaveBack(back_content, function(back) {
		
		});
	});

	$("#more2").click(function() {
	
		var tile_id= $(".tile_id_hidden").val();
		var position= $('.tile_position_hidden').val();
		var style= $(".tile_style_hidden").val();
		var content= $("#tile_content").val();
		var type= $(".tile_type_hidden").val();
		var brand= $(".tile_brand_hidden").val();
		var back= $(".tile_back_hidden").val();

		var xcoord= GetXCoord(position);
		var ycoord= GetYCoord(position);

		$("#dialog").html("<img src='/website/img/loading3.gif'");
		
		LoadTemplate("controls2", {"" : ""}, function(template) {
		
			$("#dialog").html(template.template_data);
			$("#xcoord").val(xcoord);
			$("#ycoord").val(ycoord);
			$("#tile_style").val(style);
			$(".tile_id_hidden").val(tile_id); 
			$(".tile_style_hidden").val(style); 
			$(".tile_content_hidden").val(content);
			$(".tile_position_hidden").val(position);
			$(".tile_type_hidden").val(type);
			$(".tile_brand_hidden").val(brand);
			$(".tile_back_hidden").val(back);
		});	
	});

</script>
	
