<div id="header" chapter_id="cb_chapter_id_cb" chapter_name="cb_chapter_name_cb" account_id="cb_account_id_cb" flip="true" pan="false" swipe_events= "true" >

	<a id="cerebrit_header_link" href="http://www.cerebrit.com"><div id="cerebrit_header"></div></a>

	<div id="home_title" template="sign_in">
		<img src="./img/loading3.gif" style="position: absolute; top: 20px; width: 50px;"/>
	</div>

	<div id="dialog" template="dialog">
		<img src="./img/loading3.gif" style="position: absolute; top: 20px; width: 50px;"/>
	</div>

</div>

<script>

	$(document).ready(function() {
		var color= RandomColor();
		$("#page").css("border", "1px solid " + color);
/*		
		if($("#header").attr("chapter_name")== "wiki")
		{
			$("#nav li:nth(6) a").css("text-decoration", "underline");	
			$("#nav li:nth(6) a").css("color", "#1168CC");
			$("#nav li:nth(6) a").css("color", "#fff");
			$("#nav li:nth(6)").addClass("active");
			$("#nav li:nth(6)").addClass("disabled");
		}
		if($("#header").attr("chapter_name")== "canvas")
		{
			$("#nav li:nth(8) a").css("text-decoration", "underline");			
			$("#nav li:nth(8) a").css("color", "#1168CC");
			$("#nav li:nth(8)").addClass("active");
			$("#nav li:nth(8)").addClass("disabled");
		}	*/	
	});

	//GenerateLinks('logbook');
//	GenerateLinks('article');
	
	LoadTemplate("sign_in", {"" : ""}, function(template) {
		$("#home_title").html(template.template_data);
		$("#home_title").attr("template", "sign_in");
	});	
	
	LoadTemplate("controls", {"" : ""}, function(template) {
		$("#dialog").html(template.template_data);
		$("#dialog").attr("template", "controls");
		$("#dialog").dialog();
	});		

</script>
