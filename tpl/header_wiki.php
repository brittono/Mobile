<div id="header" class="wiki" chapter_id="cb_chapter_id_cb" chapter_name="cb_chapter_name_cb" flip="true" pan="false" swipe_events= "true">

	<div id="catnav" class="wiki">

	<ul id="nav">
	  <span style="color: white; font-style: italic; font-size: 11px; letter-spacing: 2px; margin-left: 10px;"></span>
	  <li><a href="http://wiki.cerebrit.com/">Main</a></li>  
	  <li style="visibility: hidden;"><a href="http://britton.cerebrit.com/">Resume/CV</a></li>  	  
	  <li style="visibility: hidden;"><a href="http://mobile.cerebrit.com/">Mobile</a></li>	  
	  <li style="visibility: hidden;"><a href="http://wiki.cerebrit.com/">Wiki</a></li>	 
	  <li style="visibility: hidden;"><a href="http://canvas.cerebrit.github.com/">NAJ.js</a></li>	  
	  <div style="visibility: hidden;" id="top_nav_element1">cb_element1_cb</div>
	  <li><div id="top_nav_element2">cb_element2_cb</div></li>
	</ul>
	</div> <!-- Closes catnav -->
	
	<!--
	<div id="message_container"><div id="message"></div></div> 				

	<div id="error"></div>
	-->

</div>

<script>

	$(document).ready(function() {
		
		//var color= RandomColor();
		//$("body").css("background", "#fff");
		$("#catnav").css("background", "none");
		$("#catnav").css("border-bottom", "none");
		$("#page").css("background", "#fff");
		$("#page").css("border", "none");
		$("#page_container").css("border", "none");
		$("#page_container").css("background", "none");
		$("#header").css("display", "none");
		
		//$("#top_nav_element2 a").css("text-decoration", "underline");	
	});

	//GenerateLinks('logbook');
	GenerateLinks('article');
	
	var UID= "";
	var PID= "";
	var get= window.location.search;

	if(get.indexOf("?UID=")!= -1)
	{
		var UID= get.replace("?UID=", "");
	}

	if(get.indexOf("?PID=")!= -1)
	{
		var PID= get.replace("?PID=", "");
	}

	if(UID)
	{		
		Verify(UID, function(user) {
			if(user.confirmed== 'yes')
			{
				var messages = [
					{ text : "Your account has been verified, page will reload" }
				];				
			}
			else
			{
				var messages = [
					{ text : "Invalid UID, page will reload" }
				];
			}
			ShowMessages(messages, 0);
		});
		
		setTimeout(function(){window.location.assign('/sign_in')},4000);
	}

	else if(PID)
	{
		LoadTemplate('change_password', {"" : ""}, function(template) {
			$("#account").html(template.template_data);
			$("#change_password").attr("PID", PID);
		});
	}

	else
	{
		var account_template= $("#account").attr("template");
		LoggedIn(function(user) {
			if(user.logged_in== true)
			{		
				if(user.authorized== false)
				{
					$.getScript('/js/viewer.js');
				}
				//$.getScript('/js/user.js');
				LoadTemplate('user_info', {"" : ""}, function(template) {
					$("#account").html(template.template_data);
					$("#account").attr("template", "user_info");
					$("#account").css("top", "1165px");
					$("#user_info").attr("user_name", user.user_name);
					$("#user_name").text(user.user_name);
				});	
			}
			else
			{
				$.getScript('/js/viewer.js');
				LoadTemplate(account_template, {"" : ""}, function(template) {
					$("#account").html(template.template_data);
				});	
			}
		});
	}

</script>
