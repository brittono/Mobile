<div id="change_password" PID="">
	<form id="change_password_form" style="margin: 0px; padding: 0px;">
		<table>
			<tr>
				<td><label><span style="font-size: 32px; font-weight: bolder; color: #C67E00;">U</span><span style="font-size: 16px; margin: 0 20px 0 0; color: #E5E216;">ser</span></label></td>
				<td style="padding: 0 0 0 0;"><input tabindex="1" style="font-size: 20px; width: 100px; padding: 5px;" name="username" id="username" maxlength="10"/></td>
				<td><a id="back" href="javascript:">Back</a></td>
			</tr>
			<tr>
				<td><label><span style="font-size: 32px; font-weight: bolder; color: #C67E00;">P</span><span style="font-size: 16px; color: #E5E216;">ass</span></label></td>
				<td><input tabindex= "2" name="password" id="password" type="password" style="font-size: 20px; width: 100px; padding: 5px;"/></td>
				<td><input tabindex= "3" type="button" id="change_password_submit" style="font-size: 16px;" value="Change"/></td>
			</tr>
		</table>
	</form>
</div>

<script>

	$("#back").click(function() {

			window.location.assign('/');
	});

	var container= $("#error");
	$("#change_password_form").validate({
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

	$("#change_password_submit").click(function() {

		if($("#change_password_form").valid())
		{
			var user_name= $("#username").val();
			var password= hex_md5($("#password").val());
			var PID= $("#change_password").attr("PID");			

			CheckUserName(user_name, function(user) {
				if(user.user_id) 
				{
					UserPassword(user.user_id, password, PID, function(password) {
						if(password.updated=== true)
						{
							ShowMessages([{text: user_name + ", your password has been updated."}], 0);
							setTimeout(function(){window.location.assign('/')},4000);
						}
						else
						{
							ShowError("PID/user mismatch.");
						}
					});
				}
				else
				{
					ShowError("User does not match");
				}
			});
		}
	});

</script>
