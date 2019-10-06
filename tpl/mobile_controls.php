<div id="mobile_controls">

	<form style="margin: 0; padding: 0;">
		<input type="button" id="mobile_post" name="mobile_post" value="Post" />
		<input type="button" id="mobile_paint" name="mobile_paint" value="Paint" />
		<!-- <input type="button" id="mobile_login_submit" name="mobile_login_submit" value="L" > -->
		<!-- <img id="mobile_gravatar_pic" src="/img/gravatar_default.jpg"/>	 -->
		<!--<div id="mobile_user">Email: <input type="input" name="mobile_user" style="width: 120px;" /></div>-->

		<input type="hidden" class="tile_id_hidden"/>
		<input type="hidden" class="tile_style_hidden"/>
		<input type="hidden" class="tile_content_hidden"/>
		<input type="hidden" class="tile_brand_hidden"/>
		<input type="hidden" class="tile_back_hidden"/>

		<!-- <div id="mobile_logbooks_overview">

		</div> -->

		<div id="mobile_articles_overview">

		</div>

	</form>

</div>

<script>

	var logbook_id= $("#logbooks").attr("logbook_id");

//	LoadLogbookList(26, function(list) {
	
		//$("#mobile_logbooks_overview").html(list.list_data);
		
	LoadMobileArticleList(26, 0, function(list) {
		
		logbook_id= $("#logbook_list option:selected").attr("logbook_id");
		logbook_name= $("#logbook_list option:selected").val();		
		$("#mobile_articles_overview").html(list.list_data);
	});		
	
//	});
	
	$(document).on("change", "#logbook_list", function() {
	
		logbook_id= $("#logbook_list option:selected").attr("logbook_id");	
		logbook_name= $("#logbook_list option:selected").val();	
		
		LoadLogbook(26, logbook_id, 0, 20, function(logbook) {

			$("#logbooks").attr("logbook_id", logbook_id);
			$("#logbooks").attr("logbook_name", logbook_name);
			$("#mobile_page").html(logbook.tile_content);
			
			LoadMobileArticleList(26, logbook_id, function(list) {
				
				logbook_id= $("#logbook_list option:selected").attr("logbook_id");
				$("#mobile_articles_overview").html(list.list_data);
			});				
		});	
	});

	$(document).on("change", "#article_list", function() {
	
		article_id= $("#article_list option:selected").attr("article_id");
		logbook_id= $("#logbooks").attr("logbook_id");
		
		if(article_id== 0)
		{
			LoadLogbook(26, logbook_id, 0, 20, function(logbook) {

				$("#logbooks").attr("logbook_id", logbook_id);
				$("#logbooks").attr("logbook_name", logbook_name);
				$("#mobile_page").html(logbook.tile_content);
				
				LoadMobileArticleList(26, logbook_id, function(list) {
					
					logbook_id= $("#logbook_list option:selected").attr("logbook_id");
					$("#mobile_articles_overview").html(list.list_data);
				});				
			});	
		}
		else
		{
			LoadArticle(logbook_id, article_id, 0, "", function(article) {
				
				$("#mobile_page").html(article.page_data);
			});
		}
	});
	
	$("#mobile_paint").click(function() {

		$(".navigation").hide();
		AddTile(2, "Start Scribbling", function(tile) {
			AppendMobilePaint(tile.tile_id, tile.style);
		});
	});

</script>
