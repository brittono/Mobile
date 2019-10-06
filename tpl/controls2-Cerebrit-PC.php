
<div id="controls" page="2">

	<div id="X-coord">
		<span>X : </span>
		<input id="xcoord" type="text" maxlength= "4" value=""/>
		<span>px</span>
	</div>	

	<div id="Y-coord">
		<span>Y : </span>
		<input id="ycoord" type="text" maxlength= "4" value=""/>
		<span>px</span>	
	</div>

	<div id="W-coord">
		<span>W : </span>
		<input id="wcoord" type="text" maxlength= "4" value=""/>
		<span>px</span>
	</div>	

	<div id="H-coord">
		<span>H : </span>
		<input id="hcoord" type="text" maxlength= "4" value=""/>
		<span>px</span>	
	</div>

	<div id="tile_join1">
		<span>1 : </span>
		<input id="1coord" type="text" maxlength= "4" value=""/>
		<span>px</span>
	</div>	

	<div id="tile_join2">
		<span>2 : </span>
		<input id="2coord" type="text" maxlength= "4" value=""/>
		<span>px</span>	
	</div>
	
	<div id="back1"><a href="javascript:">Back</a></div>
	
	<input type="button" id= "tile_link" value="&T" class="btn"/>
	<input type="button" id= "full_page_paint_button" value="Full Page Paint" class="btn"/>
	<input type="button" id= "full_page_edit_button" value="Full Page Edit" class="btn"/>
	<input id="save_tile_button" value="Save" class="btn" type="submit">
	<input id="del_tile_button" value="Del" class="btn" type="button">
	<textarea id="tile_style"></textarea>

	<div id="more3"><a href="javascript:">More</a></div>
	
	<input class="tile_id_hidden" type="hidden">
	<input class="tile_position_hidden" type="hidden">
	<input class="tile_style_hidden" type="hidden">
	<input class="tile_content_hidden" type="hidden">
	<input class="tile_back_hidden" type="hidden">
	<input class="tile_brand_hidden" type="hidden">	
	<input class="tile_type_hidden" type="hidden">	
	
</div>

<script>

	$("#tile_link").click(function() {
		
		var tile_id= $("#1coord").val();
		var link_id= $("#2coord").val();
		
		LinkTiles(tile_id, link_id, function () {
		
		});
	});

	$("#full_page_edit_button").click(function() {

		FullPageEdit();
	});

	$("#full_page_paint_button").click(function() {

		FullPagePaint();
	});	
	
	$("#del_tile_button").click(function() {

		var id= $(".tile_id_hidden").val();
		$("#tile_style").val("");
		DeleteTile(id, function(tile) {
		
			var article_id= $("#logbooks").attr("article_id");
			var page_id= $("#logbooks").attr("page_id");
			$("#page").html("<img src='../img/loading2.gif'/>");
			LoadPage(article_id, page_id, "", function(page) {
			
				$("#page").html(page.page_data);
				$.getScript('/js/user.js');
			});
		});
		
		$("#xcoord").val("");
		$("#ycoord").val("");
		$(".tile_id_hidden").val("");
		$(".tile_style_hidden").val("");
		$(".tile_content_hidden").val("");
		$(".tile_position_hidden").val("");		
		$(".tile_back_hidden").val("");
	});

	$("#save_tile_button").click(function() {

		var style= $("#tile_style").val();		
		var xcoord = $("#xcoord").val();
		var ycoord = $("#ycoord").val();		
		var position= "left: " + xcoord + "px; top: " + ycoord + "px;"; 
	
		$(".tile_style_hidden").val(style);
		$(".tile_position_hidden").val(position);
		
		var content= $(".tile_content_hidden").val();

		$("#page").html("<img src='../img/loading2.gif'/>");
		SaveTile(content, function(tile) {

			var article_id= $("#logbooks").attr("article_id");		
			var page_id= $("#logbooks").attr("page_id");

			LoadPage(article_id, page_id, "", function(page) {

				$("#page").html(page.page_data);
				$.getScript('/js/user.js');
			});	
		});

		var back_content= $(".tile_back_hidden").val();
		SaveBack(back_content, function(back) {
		
		});	
	});

	$("#back1").click(function() {

		var id= $(".tile_id_hidden").val();
		var style= $("#tile_style").val();		
		var xcoord = $("#xcoord").val();
		var ycoord = $("#ycoord").val();		
		var position= "left: " + xcoord + "px; top: " + ycoord + "px;"; 	
		var content= $(".tile_content_hidden").val();
		var back= $(".tile_back_hidden").val();
		var brand= $(".tile_brand_hidden").val();
		var type= $(".tile_type_hidden").val();

		$("#controls").html("<img src='./img/loading3.gif' style='position: absolute; top: 40px; left: 450px; width: 100px; height: 100px;'/>");
	
		LoadTemplate("controls", {"" : ""}, function(template) {
		
			$("#dialog").html(template.template_data);		
			$("#tile_content").val(content);
			$(".tile_id_hidden").val(id);
			$(".tile_content_hidden").val(content);
			$(".tile_style_hidden").val(style);
			$(".tile_position_hidden").val(position);
			$(".tile_back_hidden").val(back);
			$(".tile_brand_hidden").val(brand);
			$(".tile_type_hidden").val(type);
		});	
	});

	$("#more3").click(function() {
	
		var tile_id= $(".tile_id_hidden").val();
		var style= $(".tile_style_hidden").val();
		var content= $(".tile_content_hidden").val();
		var back= $(".tile_back_hidden").val();
		var type= $(".tile_type_hidden").val();
		var brand= $(".tile_brand_hidden").val();		
		var xcoord = $("#xcoord").val();
		var ycoord = $("#ycoord").val();		
		var position= "left: " + xcoord + "px; top: " + ycoord + "px;"; 

		$("#controls").html("<img src='./img/loading3.gif' style='position: absolute; top: 40px; left: 450px; width: 100px; height: 100px;'/>");
		
		LoadTemplate("controls3", {"" : ""}, function(template) {
		
			$("#dialog").html(template.template_data);
			$(".tile_id_hidden").val(tile_id); 
			$(".tile_style_hidden").val(style); 
			$(".tile_content_hidden").val(content);
			$(".tile_position_hidden").val(position);
			$("#tile_back").val(back);
			$(".tile_back_hidden").val(back);
			$(".tile_brand_hidden").val(brand);
			$(".tile_type_hidden").val(type);
			if(brand== "flippable")
			{
				$("#flip_button").val("Flippable");
			}
			else
			{
				$("#flip_button").val("No Flip");
			}

			if(type== "free")
			{
				$("#front_button").val("Free Front");
				$("#back_button").val("Free Back");
			}
			if(type== "user")
			{
				$("#front_button").val("User Front");
				$("#back_button").val("User Back");
			}
			if(type== "member")
			{
				$("#front_button").val("Member Front");
				$("#back_button").val("Member Back");
			}
			if(type== "user_front")
			{
				$("#front_button").val("User Front");
				$("#back_button").val("Free Back");
			}
			if(type== "user_back")
			{
				$("#front_button").val("Free Front");
				$("#back_button").val("User Back");
			}
			if(type== "member_front")
			{
				$("#front_button").val("Member Front");
				$("#back_button").val("Free Back");
			}
			if(type== "member_back")
			{
				$("#front_button").val("Free Front");
				$("#back_button").val("Member Back");
			}
			if(type== "user_member")
			{
				$("#front_button").val("User Front");
				$("#back_button").val("Member Back");
			}
			if(type== "member_user")
			{
				$("#front_button").val("Member Front");
				$("#back_button").val("User Back");
			}
		});	
	});	
	
</script>


