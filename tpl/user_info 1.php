<div id="user_info" user_id= "cb_user_id_cb">
	<div id="user_info_user_name">cb_user_name_cb</div>
	<input id="logout" value="Logout" type="button" class="topcoat-button-bar__button--large">   
</div>


<script>

	$("#user_info_back").click(function() {

		LoadTemplate('article_info', {"" : ""}, function(template) {

			$("#account").html(template.template_data);
		});
	});

	$("#update_account").click(function() {

		var user_name= $("#user_info_user_name").text();
		LoadTemplate('user_update', {"" : ""}, function(template) {
			
			$("#account").html(template.template_data);
			CheckEmail(user_name, function(email) {
				$("#email").val(email.email);	
			});
			$("#user_update").attr("user_name", user_name);
		});
	});
	
	$("#load_controls").click(function() {

		NavCheck();
	});	

</script>
