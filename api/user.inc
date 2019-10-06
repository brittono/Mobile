<?php

include_once('db.inc');

class User extends DB
{

	public $user_name= "";
	public $email= "";

	function __construct()
	{
		$this->StartSession();
	}
	
	public function StartSession()
	{
		if(!isset($_SESSION)) 
		{
			$cb_session = session_name("cb_session");
			//session_set_cookie_params(0, '/', '.localhost');
			session_start();
			$_SESSION['init']= true;
		}
	}
	
    public function CreateAccount($user_name="", $password="", $enc_type= "")
    {
		$UID= uniqid();
		$SQL= "INSERT INTO users (user_name, password, UID, created_on, account_id) VALUES ('$user_name', '$password', '$UID', CURRENT_TIMESTAMP(), '4'); ";
		$result= $this->DbConnect($SQL);
		if($result)
		{
			$this->user_name= $user_name;
			$this->user_id= $this->insert_id;
			$json["created"]= TRUE;
		}
		if($enc_type== "json")
		{
			$json["user_name"]= $this->user_name;
			$json["UID"]= $UID;
			$json["user_id"]= $this->user_id;
			echo json_encode($json);
		}
		
		return $this->user_id;
    }
	
	public function CreateFBAccount($user_name= "", $email="", $FacebookID= 0, $enc_type= "")
	{
		$UID= uniqid();
		$SQL= "INSERT INTO users (user_name, UID, email, FacebookID, created_on, last_login) VALUES ('$user_name', '$UID', '$email', '$FacebookID', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP()); ";
		$result= $this->DbConnect($SQL);
		if($result)
		{
			$json["created"]= TRUE;
		}
		if($enc_type== "json")
		{
			echo json_encode($json);
		}
		
		return $this->insert_id;		
	}

    public function CreateEmailAccount($user_name="", $email="", $enc_type= "")
    {
		$UID= uniqid();
		$SQL= "INSERT INTO users (user_name, email, UID, created_on) VALUES ('$user_name', '$email', '$UID', CURRENT_TIMESTAMP()); ";
		$result= $this->DbConnect($SQL);
		if($result)
		{
			$this->user_name= $user_name;
			$this->user_id= $this->insert_id;
			$json["created"]= TRUE;
		}
		if($enc_type== "json")
		{
			$json["user_name"]= $this->user_name;
			$json["UID"]= $UID;
			$json["user_id"]= $this->user_id;
			echo json_encode($json);
		}
		
		return $this->insert_id;
    }
	
	public function FetchUserEmail($user_name= "", $enc_type= "none")
	{
		$user_id= $this->FetchId($user_name, "user");
		$SQL= "SELECT email FROM users WHERE user_id= $user_id LIMIT 1";
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$email= $row['email'];
			$this->email= $email;
			$this->user_id= $user_id;
		}
		if($enc_type== "json")
		{
			$json['email']= $this->email;
			$json['user_id']= $this->user_id;
			echo json_encode($json);			
		}
		else
		{
			return $this->email;
		}
	}
	
    public function Login($user_name= "", $password= "", $enc_type= "")
    {
		$_SESSION['user_key']= 0;
		$_SESSION['user_id']= 0; 
		$_SESSION['user_name']= ""; 
		$_SESSION['account_id']= 0;    	

		$json["logged_in"]= false;
		
		$this->StartSession();
		$SQL= "SELECT u.user_id, u.user_name, u.account_id, u.email 
				FROM users u 
				WHERE u.user_name= '$user_name' 
				AND u.password='$password' 
				AND u.confirmed= '1'
				LIMIT 1;";
		
		$result= $this->DbConnect($SQL);

		if($row= $result->fetch_assoc())
		{
			$this->user_id= $row['user_id'];
			$this->user_name= $row['user_name'];
			$this->account_id= $row['account_id'];
			$this->email= $row['email'];
			$this->user_key= uniqid();
			$this->UpdateTable("users", "user_id", $this->user_id, "auth_key", $this->user_key);
			$this->UpdateTable("users", "user_id", $this->user_id, "last_login", "CURRENT_TIMESTAMP()", true);
			$this->UpdateTable("users", "user_id", $this->user_id, "logged_in", "1", true);
			
			$_SESSION['user_key']= $this->user_key;
			$_SESSION['user_id']= $this->user_id; 
			$_SESSION['user_name']= $this->user_name; 
			$_SESSION['account_id']= $this->account_id;

			if($this->account_id >= 3)
			{
				$this->authorized= true;
			}
			
			$this->logged_in= true;		
		}
		else
		{
			$user_name= intval($user_name);
			$Query = array('user_id' => $user_name);
			$m = new MongoClient("mongodb://Meteor:Oregon123@cerebrit.com:27001/NOLJ");
			$db = $m->selectDB('NOLJ');
			$collection = new MongoCollection($db, 'users');
			$cursor = $collection->find($Query);
			foreach($cursor as $doc)
			{
				$this->logged_in= true;	
				$this->authorized= false;
				$this->user_id= $user_name;
				$this->user_name= $user_name;
				$this->email= "";
				$_SESSION['MONGO_ID']= $this->user_name;
			}

		}
		if($enc_type== "json")
		{
			$json["authorized"]= $this->authorized;
			$json["logged_in"]= $this->logged_in;	
			$json["user_id"]= $this->user_id;
			$json["user_name"]= $this->user_name;
			$json["email"]= $this->email;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");			
			echo json_encode($json);				
		}
		if($enc_type== "app")
		{
			$json["authorized"]= $this->authorized;
			$json["logged_in"]= $this->logged_in;	
			$json["user_id"]= $this->user_id;
			$json["user_name"]= $this->user_name; 
			$json["email"]= $this->email;			
			echo "DisplayLogin(" . json_encode($json) . ");";	
		}
		if($enc_type== "callback")
		{

			$this->json["authorized"]= $this->authorized;
			$this->json["logged_in"]= $this->logged_in;	
			$this->json["user_id"]= $this->user_id;
			$this->json["user_name"]= $this->user_name;
			$this->json["email"]= $this->email;	
			echo "DisplayLogin(" . json_encode($this->json) . ");";		
		}
		return $this->user_id;
    }

	public function LoggedIn($enc_type= "")
	{
		$user_id= 0;
		$user_name= "";
		$account_id= 1;
		$email= "";
		$user_key = "0";
		$auth_key= "n/a";
		$json['logged_in']= false;
		$json['account']= $this->account_id;
		$json['chapter_id']= $this->chapter_id;
		
		$this->StartSession();

		if(isset($_SESSION['user_key']))
		{
			$user_key= $_SESSION['user_key'];
		}

		if(isset($_SESSION['user_id']))
		{
			$user_id= $_SESSION['user_id'];
		}

		$SQL= "SELECT u.auth_key, u.user_name, u.account_id, u.email, u.logged_in 
				FROM users u
				WHERE u.user_id= $user_id 
				AND confirmed= '1' 
				LIMIT 1 ;";	

		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$auth_key= $row['auth_key'];
				$user_name= $row['user_name'];
				$account_id= $row['account_id'];
				$email= $row['email'];
				$logged_in= $row['logged_in'];
			}
		}

		if($auth_key== $user_key)
		{
			$this->logged_in= true;
			$this->UpdateTable("users", "user_id", $user_id, "timestamp", "CURRENT_TIMESTAMP()", true);
			$this->UpdateTable("users", "user_id", $user_id, "logged_in", "1");
			$this->user_id= $user_id;
			$this->user_name= $user_name;
			$this->account_id= $account_id;
			$this->email= $email;

			if($account_id >= 3)
			{
				$this->authorized= true;
			}
		}
		else if($this->FBLoggedIn())
		{
			$this->logged_in= true;
		}
		/*
		else
		{
			if(isset($_SESSION['MONGO_ID']))
			{
				$user_name= $_SESSION['MONGO_ID'];
			}
			$Query = array('user_id' => $user_name, "logged_in"=>1);
			$m = new MongoClient("mongodb://Meteor:Oregon123@cerebrit.com:27001/NOLJ");
			$db = $m->selectDB('NOLJ');
			$collection = new MongoCollection($db, 'users');
			$cursor = $collection->find($Query);
			foreach($cursor as $doc)
			{
				if($doc["logged_in"]== 1)
				{
					$this->logged_in= true;	
					$this->authorized= false;
					$this->user_name= $user_name;
					$this->email= "";
					$this->user_id= 0;
					$_SESSION["MONGO_ID"]= $user_name;
				}
				else 
				{
					$this->logged_in= false;
					$this->Logout();
				}
			}
		}
		*/

		if($enc_type== "app")
		{
			if($logged_in== "1") 
			{
				$this->logged_in= true;
				$this->UpdateTable("users", "user_id", $user_id, "timestamp", "CURRENT_TIMESTAMP()", true);
				$this->UpdateTable("users", "user_id", $user_id, "logged_in", "1");
				$this->user_id= $user_id;
				$this->user_name= $user_name;
				$this->account_id= $account_id;
				$this->email= $email;
	
				if($account_id >= 3)
				{
					$this->authorized= true;
				}	
			}
		}

		if($enc_type== "json")
		{
			$json['authorized']= $this->authorized;
			$json['user_id']= $this->user_id;
			$json['email']= $this->email;
			$json['user_name']= $this->user_name;			
			$json['logged_in']= $this->logged_in;
			$json['fb_user_id']= $this->fb_user_id;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			echo json_encode($json);
		}
		
		if($enc_type== "app")
		{
			$json['authorized']= $this->authorized;
			$json['user_id']= $this->user_id;
			$json['email']= $this->email;
			$json['user_name']= $this->user_name;			
			$json['logged_in']= $this->logged_in;
			$json['fb_user_id']= $this->fb_user_id;
			echo "DisplayLoggedIn(" . json_encode($json) . ");";
		}

		if($enc_type== "callback")
		{
			$json['authorized']= $this->authorized;
			$json['user_id']= $this->user_id;
			$json['email']= $this->email;
			$json['user_name']= $this->user_name;			
			$json['logged_in']= $this->logged_in;
			$json['fb_user_id']= $this->fb_user_id;
			echo "DisplayLoggedIn(" . json_encode($json) . ");";
		}	
		
		return $this->logged_in;
	}
	
	public function FBLoggedIn()
	{

		$config = array(
			'appId' => '410610009004609',
			'secret' => 'ab1997d0b8d828b1deac893691dfc470'
		);

		$this->fb_user_id= $user_id;
		
		if($this->fb_user_id!= 0)
		{
			$this->user_id= $this->FetchField("user_id", "users", "FacebookID= $user_id");
		
			if($this->user_id== 0)
			{
				$user_profile = $facebook->api('/me','GET');
				$this->CreateFBAccount($user_profile['first_name'], $user_profile['email'], $user_id);				
			}

			$this->email= $this->FetchField("email", "users", "FacebookID= $user_id");
			$this->user_name= $this->FetchField("user_name", "users", "FacebookID= $user_id");
			$this->account_id= $this->FetchField("account_id", "users", "FacebookID= $user_id");
			$this->logged_in= true;
			$this->UpdateTable("users", "user_id", $user_id, "timestamp", "CURRENT_TIMESTAMP()", true);
			$this->UpdateTable("users", "user_id", $user_id, "online", "1");
		}
		
		return $this->fb_user_id;
	}
	
	public function Verify($UID= 0)
	{
		$SQL= "SELECT user_id, user_name FROM users WHERE UID= '$UID' AND confirmed= '0' LIMIT 1;";
		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$user_id= $row['user_id'];
				$user_name= $row['user_name'];
				$this->user_id= $user_id;
				$this->user_name= $user_name;
				if($this->user_id)
				{
					$json["confirmed"]= "yes";
					$json["user_name"]= $user_name;
					$this->ConfirmUser($this->user_id);
				}
			}
			else
			{
				$json["confirmed"]= "no";			
			}
		}
		
		echo json_encode($json);
	}
	
	public function ConfirmUser($user_id= 0)
	{
		if($user_id)
		{
			$SQL= "UPDATE users SET confirmed= '1', UID='' WHERE user_id= $user_id;";
			$this->DbConnect($SQL);
		}
	}
	
	public function Logout($enc_type= "")
	{
		if(isset($_SESSION['user_id']))
		{
			$user_id= $_SESSION['user_id'];
			$this->UpdateTable("users", "user_id", $user_id, "online", "0");
		}

		session_unset();
		
		if($enc_type== "json")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");			
			$json["logged_out"]= true;
			echo json_encode($json);
		}

		if($enc_type== "app")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");						
			$json["logged_out"]= "true";
			echo "DisplayLogout(" . json_encode($json) . ");";
		}

		if($enc_type== "callback")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");						
			$json["logged_out"]= "true";
			echo "DisplayLogout(" . json_encode($json) . ");";
		}		
		
		
		return $this->exec;
	}
	
	public function FetchDefaultArticle($user_id= 0, $enc_type= "")
	{
		$SQL= "SELECT article_id FROM articles WHERE user_id= $user_id ORDER BY article_order ASC LIMIT 1";
			
		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$article_id= $row['article_id'];
				$this->article_id= $article_id;
			}
		}

		if($enc_type== "json")
		{
			$json["article_id"]= $this->article_id;
			echo json_encode($json);
		}
		
		return $this->article_id;
	}
	
	public function FetchRandomUser($logbook= 0, $enc_type= "")
	{
		$SQL= "SELECT user_id, user_name FROM users WHERE confirmed= '1' ORDER BY RAND() LIMIT 1";
			
		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$user_id= $row['user_id'];
				$user_name= $row['user_name'];
				$this->user_id= $user_id;
				$this->user_name= $user_name;
			}
		}
		if($enc_type== "json")
		{
			$json['user_id']= $this->user_id;
			$json['user_name']= $this->user_name;		
			echo json_encode($json);
		}
		
		return $this->user_id;
	}

	public function ResetPassword($user_id= "", $email= "", $enc_type= "")
	{
		$json["sent"]= false;

		$PID= uniqid();
		$this->UpdateTable("users", "user_id", $user_id, 'PID', $PID);
		
		if($this->SendEmail($email, "Cerebrit.com - Password reset", "Click <a href='www.cerebrit.com/sign_in/?PID=$PID'>here</a> to reset your password"))
		{
			$json['sent']= true;
		}
		
		if($enc_type== "json")
		{
			echo json_encode($json);
		}

		return $PID;
	}
	
	public function UpdateInfo($username= "", $email= "", $password= "", $enc_type= "")
	{
		$user_id= 0;
		$json["updated"]= false;
		$mongo_id= 0;
		if($this->LoggedIn())
		{
			$user_id= $this->user_id;
			if(isset($_SESSION['MONGO_ID']))
			{
				$mongo_id= $_SESSION['MONGO_ID'];
			}
			else
			{
				$mongo_id= "";
			}		
			if($user_id== 0 && $mongo_id!= "")
			{
				$user_id= $this->CreateAccount($username, $password);
				$this->UpdateTable("users", "user_id", $user_id, 'confirmed', 1, true);
				$Query= array('user_id' => $mongo_id);
				$m = new MongoClient("mongodb://Meteor:Oregon123@cerebrit.com:27001/NOLJ");
				$db = $m->selectDB('NOLJ');
				$collection = new MongoCollection($db, 'users');
				$cursor = $collection->remove($Query, array("justOne" => true));		
			}
		}
		if($this->UpdateTable("users", "user_id", $user_id, 'user_name', $username))
		{
			$json['email']= 'updated';
		}		
		if($this->UpdateTable("users", "user_id", $user_id, 'email', $email))
		{
			$json['user']= 'updated';
		}
		if($this->UpdateTable("users", "user_id", $user_id, 'password', $password))
		{
			$json['password']= 'updated';
		}
		$json['updated']= 'true';
		$json["user_id"]= $user_id;
		$this->Logout();	
		if($enc_type== "json")
		{
			echo json_encode($json);
		}
		if($enc_type== "app")
		{
			echo "DisplayUserInfo(" .json_encode($json) . ")";
		}		

		return $user_id;
	}

	public function UpdatePassword($user_id= "", $password= "", $PID= "", $enc_type= "")
	{
		$json['updated']= false;		
		$this->user_id= $user_id;

		$SQL= "SELECT PID FROM users WHERE user_id= '$user_id' AND PID= '$PID' LIMIT 1";
			
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$PID_auth= $row["PID"];

			if($PID_auth== $PID)
			{
				if($this->UpdateTable("users", "user_id", $this->user_id, 'password', $password))
				{
					$json["password"]= "updated";
				}
				if($this->UpdateTable("users", "user_id", $this->user_id, 'PID', ''))
				{
					$json["updated"]= "true";
					$json["pid"]= "reset";
				}
			}
		}
		
		if($enc_type== "json")
		{
			echo json_encode($json);
		}
		
		return $this->user_id;
	}

	public function FetchUserList($logbook_id= 0, $enc_type= "")
	{
		$SQL= "SELECT * FROM users WHERE logbook_id= '$logbook_id' and confirmed= '1';";

		$result= $this->DbConnect($SQL);
		$this->list_data= "<ul id='user_list' class='user_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";

		$row= $result->fetch_assoc();
		$json['first']= $row['user_id'];
		$this->list_data.= "<li id= 'user_$row[user_id]' class='user_link' user_id='$row[user_id]'><span>$row[user_name]</span></option>";

		while($row= $result->fetch_assoc())
		{
			$this->list_data.= "<li id= 'user_$row[user_id]' class='user_link' user_id='$row[user_id]'><span>$row[user_name]</span></option>";
		}

		$this->list_data.= "</ul>";

		if($enc_type== "json")
		{
			$json['list_data']= $this->list_data;
			echo json_encode($json);
		}		
		
		return $this->list_data;
	}

	public function Upload($name= "", $path= "", $full_size= "", $enc_type= "")
	{
		$image= $this->GetData($path);
		$file_path= DOCUMENT_ROOT . "/uploads/" . $name;
		
		file_put_contents($file_path, $image);

		if($full_size== "yes")
		{
			//$this->json['echo']= 'here';
			$image = new SimpleImage();
			$image->load($file_path);
			$image->resizeToWidth(460);
			$image->save($file_path);
		}
			
		$SQL= "INSERT INTO images (image, image_name, created_on, last_modified) VALUES ('" . mysql_escape_string(file_get_contents($file_path)) . "', '$name', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP()); ";
		
		$this->DbConnect($SQL);

		if($enc_type== "json")
		{	
			$this->json['name']= $name;
			$this->json['path']= "/uploads/$name";
			//$this->json['sql']= $SQL;
			//$this->json['img']= $image; 
			$this->json['insert_id']= $this->insert_id;
			echo json_encode($this->json);
		}
		
		return $this->insert_id;
	}

	public function CreateToken($enc_type="")
	{
		$user_id= mt_rand(0000000000, 9999999999);
		$token = '';
		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		for ($i = 0; $i < 12; $i++) {
			$token .= $characters[rand(0, strlen($characters) - 1)];
			$token = md5($token);
		}

		$m = new MongoClient("mongodb://Meteor:Oregon123@cerebrit.com:27001/NOLJ");
		$db = $m->selectDB('NOLJ');
		$collection = new MongoCollection($db, 'users');

		$b = array('user_id' => $user_id, 'token'=> $token, 'logged_in'=> 1);
		if($result= $collection->insert($b))
		{
			$this->json["result"]= $result;
			$this->json["user_id"]= $user_id;
			$this->json["token"]= $token;
			$this->json["user_name"]= "Anonymous";
		}

		if($enc_type== "app")
		{
			echo "DisplayCreateToken(" . json_encode($this->json) . ");";
		}

		return $result;
	}
}

?>
