<div id="sign_in">
	<form id="sign_in_form" style="margin: 0px; padding: 0px;">
		<label id="sign_in_username_label">User</label><div id="sign_in_username"><input type="text" placeholder="User" tabindex="1" name="username" id="username" maxlength="8"/></div>
		<label id="sign_in_password_label">Password</label><div id="sign_in_password"><input tabindex= "2" placeholder="Pass" name="password" id="password" type="password" maxlength="8"/></div>
		<div id="sign_in_submit_div"><input tabindex= "3" type="button" id="sign_in_submit" class="btn" value="Log In"/></div>
		<a tabindex= "4" href="javascript:" id="register">Register</a><a tabindex= "5" href="javascript:" id="f_password" >Reset</a>
	</form>
	<div id="error"></div>
</div>

<script>

	var container= $("#error");
	$("#sign_in_form").validate({
		errorLabelContainer: $("#error"),
		rules: {
			username: {
				required: true,
				minlength: 4
			},
			password: {
				required: true,
				minlength: 6
			}
		},
		messages: {
			username: {
				required: "Enter a username",
				minlength: jQuery.format("Enter at least {0} characters")
			},
			password: {
				required: "Provide a password",
				rangelength: jQuery.format("Enter at least {0} characters")
			}
		}
	});	

	$("#sign_in_submit").click(function() {

		if($("#sign_in_form").valid())
		{
			user_name= $("#username").val();
			password= hex_md5($("#password").val());
			chapter_name= $("#header").attr("chapter_name");
			logbook_name= $("#logbooks").attr("logbook_name");
		
			Login(user_name, password, function(account) {
				if(account.authorized== true)
				{
					//window.location.reload();
					//window.location.assign("http://" + chapter_name + ".cerebrit.com");
					
					LoadTemplate('user_info', {"user_name" : account.user_name}, function(template) {

						$("#account").html(template.template_data);
						$("#account").attr("flip", "false");
						$("#account").attr("template", "user_info");
						$("#user_info").attr("user_name", account.user_name);
						$("#user_name").text(account.user_name);
						LoadTemplate('user_logged_in', {"user_name" : account.user_name, "icon" : "cb"}, function(template2) {
						
							$("#home_title").html(template2.template_data);
							$("#home_title").attr("template", "user_logged_in");
							//$.getScript('/js/viewer.js');
						});
					});
				}
				else if(account.logged_in== true)
				{
					LoadTemplate('user_info', {"user_name" : account.user_name}, function(template) {

						$("#account").html(template.template_data);
						$("#account").attr("flip", "false");
						$("#account").attr("template", "user_info");
						$("#user_info").attr("user_name", account.user_name);
						$("#user_name").text(account.user_name);
						LoadTemplate('user_logged_in', {"user_name" : account.user_name, "icon" : "cb"}, function(template2) {
						
							$("#home_title").html(template2.template_data);
							$("#home_title").attr("template", "user_logged_in");
							//$.getScript('/js/viewer.js');
						});
					});
				}	
				else
				{
					ShowError("Invalid login");
				}
			});
		}
	});

	$("#register").click(function() {

		if($("#sign_in_form").valid())
		{
			var user_name= $("#username").val();
			var password= hex_md5($("#password").val());
		
			CheckUserName(user_name, function(user) {
				if(user.user_id)
				{
					ShowError("This username is already taken.");
				}
				else
				{
					CreateAccount(user_name, password, function(user) {
						if(user.created=== true)
						{
							ShowMessages([{text: "Welcome " +user.user_name+ ", account created."}], 0);
							LoadTemplate('verify', {"" : ""}, function(template) {
								$("#account").html(template.template_data);
								$("#account").attr("template", "verify");
								$("#verify_send").attr("UID", user.UID);
								$("#verify_send").attr("user_name", user.user_name);
								$("#verify_send").attr("user_id", user.user_id);
							});
						}
						else
						{
							ShowError("DB Error, try again later");
						}
					});
				}
			});
		}
	});

	$("#pass_reset").click(function() {

		var user_name= $("#username").val();
		CheckEmail(user_name, function(user) {

			if(user.email== '')
			{
				ShowError("No email for user.");
			}
			else
			{
				ResetPassword(user.user_id, user.email, function(email) {
					if(email.sent=== true)
					{
						ShowMessages([{text: "Email has been sent to " + user.email }], 0);
						setTimeout(function(){window.location.assign('/')},4000);
					}
					else
					{
						ShowError("System error, try again");
					}
				});
			}
		});
	});

</script>






