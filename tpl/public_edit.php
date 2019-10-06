<div id="home_title_edit">

	<a href="javascript:PublicEdit();"><img src="/img/quill_pen_awolfillustrations.png" style="position: relative; top: 5px;"/></a>
	<a id="public_edit_edit" href="javascript:PublicEdit();" style="font-size: 10px; position: absolute; left: 15px; top: 75px; display: none;">Exit</a>
	<a id="public_edit_exit" href="javascript:PublicEditExit();" style="font-size: 10px; position: absolute; left: 15px; top: 75px; display: none;">Exit</a>
	
</div>


<script>

	var account_id= $("#header").attr("account_id");

	function PublicEdit() 
	{
		$("#header").attr("swipe_events", "false");
		$("#account").html("<img src='./img/loading3.gif'/>");		
		LoadTemplate('public_controls', {"" : ""}, function(template) {
			$("#account").attr("template", "public_controls");
			$("#public_edit_exit").css("display", "block");
			$("#account").html(template.template_data);
		});	

		$.getScript('/js/public.js');
	}
	
	function PublicEditExit()
	{
		$("#account").html("<img src='./img/loading3.gif'/>");		
		LoadTemplate('article_info', {"" : ""}, function(template) {
			$("#account").attr("template", "article_info");
			$("#public_edit_exit").css("display", "none");
			$("#account").html(template.template_data);
		});		
		
		$.getScript('/js/exit.js');
	}

</script>