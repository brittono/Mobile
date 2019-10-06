<div id="user_info">
	<div id="welcome"></div>
	<div id="last_login">Last login : <?php //echo date("F j, Y ", strtotime($_SESSION['last_login'])); ?> </div>
	<div id="profile_pic"></div>
	<input id="logout" value="Logout" type="button" onClick="Logout(function() { window.location.reload(); })">
</div>