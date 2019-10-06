<div id="verify">
	<form id="verify_form">
		<div id="verify_link_label">Click <a id="verify_link" href="javascript:">here</a> to verify</div>
		<div id="verify_email_label"><label style="font-size: 24px; font-weight: bolder;">Email</label></div>
		<input tabindex= "1" name="email" id="verify_email" style="font-size: 16px; width: 110px;"/>
		<input tabindex= "2" type="button" id="verify_send" UID="" value="Send"/>
	</form>
</div>

<script>

	$("#verify_link").click(function() {
		Verify($('#verify_send').attr('UID'), function() {  
			LoadTemplate("sign_in", function(template) {
		 		$("#account").html(template.template_data);
				ShowMessages([{text : 'You have been verified, login to use site'}], 0);
			});
		});
	});

	$("#verify_form").validate({
		errorLabelContainer: $("#error"),
		rules: {
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			email: {
				required: "Enter an email address",
			}
		}
	});

	$("#verify_send").click(function() {

		if($("#verify_form").valid())
		{
			var email= $("#email").val();
			var UID= $("#verify_send").attr("UID");
			var user_name= $("#verify_send").attr("user_name");
			var user_id= $("#verify_send").attr("user_id");
			
			SendEmail(email, user_name + ", Welcome to Cerebrit.com", "Welcome to Cerebrit.com, click http://www.cerebrit.com/sign_in/?UID=" + UID + " to verify your account.", function(mail) {
				if(mail.sent=== true)
				{
					UpdateUser(user_id, "email", email, function(update) {
						if(update.updated=== true)
						{
							var messages= [
								{ text: "Account has been updated" },
								{ text: "Email has been sent" }];
								ShowMessages(messages, 0);
						}
					});

					$("#verify_email").val("");
					$("#verify_send").attr("UID", "");
					$("#verify_send").attr("user_name", "");

					LoadTemplate('sign_in', function(template) {
						$("#account").html(template.template_data);
						$("#username").val(user_name);
					});
				}
				else
				{
					ShowError("DB error, try again.");
				}
			});	
		}
	});


</script>
