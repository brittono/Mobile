<div id="facebook_sign_in">

	<div>A dialog should appear to Login.  If not, click <a id="facebook_back" href="javascript:">here</a> to go back.</div>

</div>

<script>

	$("#facebook_back").click(function() {

		$("#account").html("<img src='./img/loading3.gif'/>");
		LoadTemplate('article_info', {"" : ""}, function(template) {

			$("#account").html(template.template_data);
			$("#account").attr("template", "article_info");
		});
	});
	
/*
FB.getLoginStatus(function(response) {
	if (response.status === 'connected') {
		alert('connected');
	} else if (response.status === 'not_authorized') {
		alert('not authorized');
	} else {
		alert('not logged in');
	}
});
*/

</script>
