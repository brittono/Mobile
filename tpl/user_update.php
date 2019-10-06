<div id="user_update" user_name= "">

	<form id="user_update_form">
			<label id="email_label">Email</label></td>
				<input id="email_change" tabindex="1" name="email" id="email" maxlength="30" type="text"/></td>
				<a id="user_update_back" href="javascript:">Back</a>
			<label id="pass_label">Pass</label>
				<input tabindex= "2" name="password" id="password_change" type="password" />
				<input tabindex= "3" type="button" id="user_update_submit" user_id="" value="Submit" class="btn"/>
			</tr>
	</form>
</div>


<script>

	$("#user_update_back").click(function() {

		LoadTemplate('user_info', {"" : ""}, function(template) {
			var user_name= $("#user_update").attr("user_name");
			$("#account").html(template.template_data);
			$("#user_info_user_name").text(user_name);
		});
	});

	var container= $("#error");
	$("#user_update_form").validate({
		errorLabelContainer: $("#error"),
		rules: {
			email: {
				required: true,
				email: true
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

	$("#user_update_submit").click(function() {

		if($("#user_update_form").valid())
		{
			user_name= $("#user_update").attr("user_name");
			email= $("#email_change").val();
			password= hex_md5($("#password_change").val());
		
			UserInfo(email, password, function(account) {
				if(account.updated== true)
				{
					var messages = [
						{ text : "Account has been updated." }
					];
		
					ShowMessages(messages, 0);
					LoadTemplate('user_info', {"" : ""}, function(template) {

						$("#account").html(template.template_data);
						$("#user_info").attr("user_name", user_name);
						$("#user_name").text(user_name);
					});
				}
				else
				{
					ShowError("Error updating user");
				}
			});
		}
	});	

</script>
