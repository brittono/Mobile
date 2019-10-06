<div id="sign_in">
	<form id="sign_in_form" style="margin: 0px; padding: 0px;">
		<label id="sign_in_username_label">User</label><div id="sign_in_username"><input type="text" placeholder="User" tabindex="1" name="username" id="username" maxlength="8"/></div>
		<label id="sign_in_password_label">Password</label><div id="sign_in_password"><input tabindex= "2" placeholder="Pass" name="password" id="password" type="password" maxlength="8"/></div>
		<div id="sign_in_submit_div"><input tabindex= "3" type="button" id="sign_in_submit" class="btn" value="Log In"/></div>
		<a tabindex= "4" href="javascript:" id="register">Register</a><a tabindex= "5" href="javascript:" id="f_password" >Reset</a>
	</form>
	<div id="error"></div>
</div>

<script type="text/javascript" src="/static/js/sign_in.js"></script>