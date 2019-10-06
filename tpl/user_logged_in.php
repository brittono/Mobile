<div id="user_logged_in">

	<a id="logged_in_icon" href="javascript: ">
		<div id="user_logged_in_icon">
			<img account_type="" id="logged_in_icon_image" src="/img/facebook.png"/>
			
			<div id="user_logged_in_user_name">
				cb_user_name_cb
			</div>
		</div>
	</a>
	
	<a id="edit_icon" href="javascript:NavCheck(0) "><img id="edit_icon_image" src="/img/edit.png"/></a>
	<!-- <a id="facebook_logout" href="javascript:">Logout</a> -->
	
</div>

<script>
	
	var acct_type= "cb_icon_cb";

	if(acct_type== "cb")
	{
		$("#logged_in_icon_image").attr("src", "/img/cerebrit_ico.png");
		$("#logged_in_icon_image").attr("account_type", "cb");
		$("#logged_in_icon_image").attr("style", "border: 1px solid black;");
	}
	else
	{
		$("#logged_in_icon_image").attr("src", "/img/facebook.png");
		$("#logged_in_icon_image").attr("account_type", "facebook");
	}

</script>