
<div id="controls" page="3">

	<div id="back2"><a href="javascript:">Back</a></div>

	<input type="button" class="btn" id= "front_button" value="Free Front" />
	<input type="button" class="btn" id= "back_button" value="Free Back" />
	<input type="button" class="btn" id= "flip_button" value="Flippable" />
	<input id="save_tile_button" class="btn" value="Save" type="button">
	<input id="upload_image_button" class="btn" value="Upload" type="button">
	<!--<input id="del_tile_button" value="Del" type="button">-->
	<textarea id="tile_back"></textarea>
	<div id="image_path">
		<span>F:</span>
		<input id="imagepath" type="text"/>
	</div>
	<div id="image_name">
		<span>N:</span>
		<input id="imagename" type="text"/>	
	</div>

	<input class="tile_id_hidden" type="hidden">
	<input class="tile_position_hidden" type="hidden">
	<input class="tile_style_hidden" type="hidden">
	<input class="tile_content_hidden" type="hidden">
	<input class="tile_back_hidden" type="hidden">
	<input class="tile_brand_hidden" type="hidden">	
	<input class="tile_type_hidden" type="hidden">	

</div>

<script>
	
	$(document).on("click", "#flip_button", function() {
	
		var tile_id= $(".tile_id_hidden").val();
		var article_id= $("#logbooks").attr("article_id");
		var page_id= $("#page").attr("page_id");
		var brand= $(".tile_brand_hidden").val();
		var brand_id= 1;
		var new_brand= "";
		
		if($(this).val== "Flippable")
		{
			if(brand== "flippable")
			{
				brand_id= 1;
				new_brand= "editable";
			}
			else if(brand== "flippable_paint")
			{
				brand_id= 2;
				new_brand= "paint";
			}
		}
		else //Flippable
		{
			if(brand== "editable")
			{
				brand_id= 3;
				new_brand= "flippable";
			}
			else if(brand== "paint")
			{
				brand_id= 4;
				new_brand= "flippable_paint";
			}
		}

		UpdateTile(tile_id, "brand_id", brand_id, function(tile) {

			$("#flip_button").val("No Flip");
			$(".tile_brand_hidden").val(new_brand);
			LoadPage(article_id, page_id, "");	
		});			
	});	

	$(document).on("click", "#front_button", function() {
	
		var tile_id= $(".tile_id_hidden").val();
		var article_id= $("#logbooks").attr("article_id");
		var page_id= $("#logbooks").attr("page_id");
		var brand= $(".tile_brand_hidden").val();
		var button_val= "";
		var type= 1;

		if($(this).val()== "Free Front")
		{
			if(brand== "editable")
			{
				type= 4;
			}
			else if(brand== "paintable")
			{
				type= 5;
			}
			else if(brand== "flippable")
			{
				type= 7;
			}

			button_val= "User Front";
		}
		if($(this).val()== "User Front")
		{
			if(brand== "editable")
			{
				type= 10;	
			}
			else if(brand== "paintable")
			{
				type= 11;
			}
			else if(brand== "flippable")
			{
				type= 13;
			}

			button_val= "Member Front";
		}
		if($(this).val()== "Member Front")
		{
			if(brand== "editable")
			{
				type= 1;	
			}
			else if(brand== "paintable")
			{
				type= 2;
			}
			else if(brand== "flippable")
			{
				type= 3;
			}

			button_val= "Free Front";
		}

		UpdateTile(tile_id, "type_id", type, function(tile) {
		
			LoadPage(article_id, page_id, "", function(page) {

				$("#page").html(page.page_data);
			});					
		
			$("#front_button").val(button_val);
		});
	});	

	$(document).on("click", "#back_button", function() {
	
		var tile_id= $(".tile_id_hidden").val();
		var article_id= $("#logbooks").attr("article_id");
		var page_id= $("#logbooks").attr("page_id");
		var brand= $(".tile_brand_hidden").val();
		var front_val= $("#front_button").val();
		var button_val= "";
		var type= 1;

		if($(this).val()== "Free Back")
		{
			if(brand== "flippable")
			{
				if(front_val== "Free Front")
				{
					type= 8;
				}
				if(front_val== "User Front")
				{
					type= 6;
				}
				if(front_val== "Member Front")
				{
					type= 16;
				}
			}

			button_val= "User Back";
		}
		else if($(this).val()== "User Back")
		{
			if(brand== "flippable")
			{
				if(front_val== "Free Front")
				{
					type= 14;
				}
				if(front_val== "User Front")
				{
					type= 15;
				}
				if(front_val== "Member Front")
				{
					type= 12;
				}
			}

			button_val= "Member Back";
		}
		else if($(this).val()== "Member Back")
		{
			if(brand== "flippable")
			{
				if(front_val== "Free Front")
				{
					type= 3;
				}
				if(front_val== "User Front")
				{
					type= 7;
				}
				if(front_val== "Member Front")
				{
					type= 13;
				}
			}

			button_val= "Free Back";
		}

		UpdateTile(tile_id, "type_id", type, function(tile) {
		
			LoadPage(article_id, page_id, "", function(page) {

				$("#page").html(page.page_data);
			});					
		
			$("#back_button").val(button_val);
		});

	});	
	
	$("#save_tile_button").click(function() {
		
		$("._wPaint_menu").css("display","none");
		var content= $(".tile_content_hidden").val();

		SaveTile(content, function(tile) {

			var article_id= $("#logbooks").attr("article_id");
			var page_id= $("#logbooks").attr("page_id");
			var back_content= $("#tile_back").val();
			$(".tile_back_hidden").val(back_content);
			
			SaveBack(back_content, function(back) {
			
				LoadPage(article_id, page_id, "", function(page) {

					$("#page").html(page.page_data);
				});			
			});
		});
	});

	$("#upload_image_button").click(function() {
		
		var image_name= $("#imagename").val();
		var image_path= $("#imagepath").val();

		UploadImage(image_name, image_path, function(image) {

			var image_string= "<img src='" +image.path+ "'/ >"; 
			$("#tile_back").val(image_string);
		});
	});

	$("#back2").click(function() {

		var tile_id= $(".tile_id_hidden").val();
		var position= $('.tile_position_hidden').val();
		var style= $(".tile_style_hidden").val();
		var content= $(".tile_content_hidden").val();
		var back= $(".tile_back_hidden").val();
		var type= $(".tile_type_hidden").val();

		var xcoord= GetXCoord(position);
		var ycoord= GetYCoord(position);

		$("#controls").html("<img src='/website/img/loading3.gif'");
		
		LoadTemplate("controls2", {"" : ""}, function(template) {
		
			$("#dialog").html(template.template_data);
			$("#xcoord").val(xcoord);
			$("#ycoord").val(ycoord);
			$("#tile_style").val(style);
			$(".tile_id_hidden").val(tile_id); 
			$(".tile_style_hidden").val(style); 
			$(".tile_content_hidden").val(content);
			$(".tile_position_hidden").val(position);
			$(".tile_back_hidden").val(back);
			$(".tile_type_hidden").val(type);
		});	
	});
	
</script>


