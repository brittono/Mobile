<?php

include_once('../api/user.inc');

$enc_type= "";
$action= "";

extract($_POST);
extract($_GET);

$user= new User();

$user->StartSession();

switch($action)
{
	case 'Create':
		$user->CreateAccount($user_name, $password, "json");
		break;
	case 'CreateEmail':
		$user->CreateEmailAccount($user_name, $email, "json");
		break;		
	case 'Verify':
		$user->Verify($UID);
		break;
	case 'UserName':
		$user->FetchID($user_name, "user", "json");
		break;
	case 'UserID':
		$user->FetchName($user_id, "user", "json");
		break;
	case 'Email':
		$user->FetchUserEmail($user_name, "json");
		break;
	case 'Login':
		$user->Login($user_name, $password, $enc_type);
		break;
	case 'Logout':
		$user->Logout($enc_type);
		break;
	case 'LoggedIn':
		$user->LoggedIn($enc_type);
		break;	
	case 'Update':
		$user->UpdateTable("users", "user_id", $user_id, $field, $data, false, "json");
		break;
	case 'Random':
		$user->FetchRandomUser(0, "json");
		break;		
	case 'Reset':
		$user->ResetPassword($user_id, $email, "json");
		break;
	case 'Info':
		$user->UpdateInfo($username, $email, $password, $enc_type);
		break;
	case 'Password':
		$user->UpdatePassword($user_id, $password, $PID, "json");
		break;
	case 'UserList':
		$user->FetchUserList($logbook_id, "json");
		break;
	case 'Upload':
		$user->Upload($name, $path, $full_size, "json");
		break;
	case 'Token':
		$user->CreateToken($enc_type);
		break;
}

?>
