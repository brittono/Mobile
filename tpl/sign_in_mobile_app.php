<div id="account">
	<div id="sign_in_title">App.rebr.it Sign In From Server</div>
</div>

<div id="user"></div>

<div id="article_list_container"></div>

<div id="navigation_container"></div>

<div id="mobile_page"></div>

<div id="home_title"></div>

<div id="logbooks"></div>

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

	$(document).ready(function() {
		
		/*
		LoggedIn(0, function(user) {
		
			if(user.logged_in== true)
			{
				OpenNav(user.email, user.username, user.fb_user_id);			
			}
			else
			{
				LoadLogin();
			}
		});
		*/
		
		OpenApp("britton.william@hotmail.com", "cerebrit", 5);
	});

function LoadLogin() {
	
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
			$("#article_list_container").html("loading...");
		
			Login(user_name, password, function(account) {
				if(account.authorized== true)
				{
				}
				if(account.logged_in== true)
				{
					OpenApp(account.email, account, account.user_name, account.user_id);
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
}

</script>
