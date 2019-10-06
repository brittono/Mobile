
<div id="controls" page="2">
	<div style="margin-left: 10px;" id="X-coord">
		<div style="width: 40px;">X : </div>
		<input style="width: 150px;" id="xcoord" type="text" maxlength= "4" value=""/>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('xcoord', 'px');">px</a></span>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('xcoord', '%');">%</a></span>
	</div>	

	<div style="margin-left: 10px;" id="Y-coord">
		<div style="width: 40px;">Y : </div>
		<input style="width: 150px;" id="ycoord" type="text" maxlength= "4" value=""/>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('ycoord', 'px');">px</a></span>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('ycoord', '%');">%</a></span>
	</div>
    
	<div style="margin-left: 10px;" id="Z-coord">
		<div style="width: 40px;">Z : </div>
		<input style="width: 150px;" id="zcoord" type="text" maxlength= "4" value=""/>
	</div>    

	<div style="margin-left: 10px;" id="W-coord">
		<div style="width: 40px;">W : </div>
		<input style="width: 150px;" id="wcoord" type="text" maxlength= "4" value=""/>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('wcoord', 'px');">px</a></span>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('wcoord', '%');">%</a></span>
	</div>	

	<div style="margin-left: 10px;" id="H-coord">
		<div style="width: 40px;">H : </div>
		<input style="width: 150px;" id="hcoord" type="text" maxlength= "4" value=""/>
		<span style="margin-left: 10px;"><a href="javascript:FormatString('hcoord', 'px');">px</a></span>	
		<span style="margin-left: 10px;"><a href="javascript:FormatString('hcoord', '%');">%</a></span>
	</div>

	<div style="margin-left: 10px;" id="tile_join1">
		<div style="width: 40px;">1 : </div>
		<input style="width: 150px;" id="1coord" type="text" maxlength= "4" value=""/>
		<span></span>
	</div>	

	<div style="margin-left: 10px;" id="tile_join2">
		<div style="width: 40px;">2 : </div>
		<input style="width: 150px;" id="2coord" type="text" maxlength= "4" value=""/>
		<span></span>	
	</div>
	
	<div id="back1"><a href="javascript:">Back</a></div>
	
	<input type="button" id= "tile_link" value="&T" class="btn"/>
	<input type="button" id= "full_page_paint_button" value="Full Page Paint" class="btn"/>
	<input type="button" id= "full_page_edit_button" value="Full Page Edit" class="btn"/>
	<input id="save_tile_button" value="Save" class="btn" type="submit">
	<input id="del_tile_button" value="Del" class="btn" type="button">
	<textarea style="width: 100%" id="tile_style"></textarea>

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
			
			var article_id= $("#logbooks").attr("article_id");
			var page_id= $("#page").attr("page_id");
			$("#page").html("<img src='/website/img/loading3.gif'/>");
			LoadPage(article_id, page_id, "", function(page) {
			
				$("#page").html(page.page_data);
				$.getScript('/website/js/user.js');
			});		
		});
	});

	$("#full_page_edit_button").click(function() {

		var tile_content= "";

		AddTile("editable", tile_content, function(tile) {
			
			$(".tile_id_hidden").val(tile.tile_id);
			FullPageEdit(tile.tile_id, tile.style);
		});
	});

	$("#full_page_paint_button").click(function() {
		
		var tile_content= "";

		AddTile("paint", tile_content, function(paint) {
		
			FullPagePaint(paint.tile_id, paint.style);
		});
	});	
	
	$("#del_tile_button").click(function() {

		var id= $(".tile_id_hidden").val();
		$("#tile_style").val("");
		DeleteTile(id, function(tile) {
		
			var article_id= $("#logbooks").attr("article_id");
			var page_id= $("#page").attr("page_id");
			$("#page").html("<img src='/static/img/loading3.gif'/>");
			LoadPage(article_id, page_id, "", function(page) {
			
				$("#page").html(page.page_data);
				$.getScript('/website/js/user.js');
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

		$("._wPaint_menu").css("display","none");
		var style= $("#tile_style").val();		
		var xcoord = $("#xcoord").val();
		var ycoord = $("#ycoord").val();
		var zcoord = $("#zcoord").val();
		var height = $("#hcoord").val();
		var width = $("#wcoord").val();	
		
		var size= "width: " + width + "px; height: " + height + "px;";
		var position= "left: " + xcoord + "px; top: " + ycoord + "px; z-index: " + zcoord; 
		style= RemoveSize(style);
	
		style= style + size;
	
		$(".tile_style_hidden").val(style);
		$(".tile_position_hidden").val(position);
		
		var content= $(".tile_content_hidden").val();

		$("#page").html("<img src='/static/img/loading3.gif'/>");
		SaveTile(content, function(tile) {

			var article_id= $("#logbooks").attr("article_id");		
			var page_id= $("#page").attr("page_id");

			LoadPage(article_id, page_id, "", function(page) {

				$("#page").html(page.page_data);
				$.getScript('/website/js/user.js');
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

		$("#dialog").html("<img src='/website/img/loading3.gif'");
	
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

		$("#controls").html("<img src='/website/img/loading3.gif'");
		
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


