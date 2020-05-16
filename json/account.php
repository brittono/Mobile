<?php

//include_once('simple_html_dom.php');
//include_once('SimpleImage.php');

$enc_type= "";
$action= "";

extract($_POST);
extract($_GET);

$account= new Tile();

class DB
{
	public $chapter_id= 0;
	public $account_id= 1;
	public $type_id= 0;
	public $logbook_id= 0;
	public $article_id= 0;
	public $page_id= 0;
	public $comment_id= 0;
	public $tile_id= 0;
	public $brand_id= 0;
	public $user_id= 0;
	public $fb_user_id= 0;
	public $insert_id= 0;
	public $error= 0;
	public $rows= 0;
	public $info= "";
	public $tile_count= 0;
	public $page_count= 0;
	public $tile_data= "";
	public $parse_text= "";
	public $parse_ret= "";
	public $parse_plaintext= "";	
	public $template_name= "";
	public $template_data= "";
	public $chapter_name= "";
	public $logbook_name= "";
	public $account= "";
	public $redirect= "";
	public $facebook= "";
	public $json= "";
	public $logged_in= false;
	public $authorized= false;
	public $exec= false;

	public function DbConnect($SQL)
	{
		$db = new mysqli('localhost', 'root', 'root', 'cerebrit');
		//$db = pg_connect("host=ec2-184-73-210-189.compute-1.amazonaws.com dbname=d9v0f2hs6rppvr user=mryoltfwkygite password=83c5e8c819a1c57f182374231a8a6c2e997ffd40011325df2ae6c47ffcff9a67");
		if($result= mysqli_query("$SQL"))
		{
			$this->exec= true;
			$this->rows= mysqli_affected_rows($db);
			$this->info= mysqli_info($db);
		}
		
		$this->insert_id= mysqli_insert_id($db);
		$this->error= $db->error;

		$db->close();

		return $result;
	}
	
	public function FetchName($id= 0, $id_type= "", $enc_type= "")
	{
		$name_string= $id_type . "_name";
		$id_string= $id_type . "_id";
		$db_string= $id_type . "s";
		
		$SQL= "SELECT $name_string FROM $db_string WHERE $id_string= '$id' LIMIT 1";
		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_row())
			{
				$name= $row[0];
				$this->$name_string= $name;
				$this->json[$name_string]= $name;
			}
		}
		
		if($enc_type== "json")
		{
			echo json_encode($this->json);
		}		
	
		return $this->$name_string;
	}
	
	public function FetchID($name= 0, $name_type= "", $enc_type= "")
	{
		$id_string= $name_type . "_id";
		$name_string= $name_type . "_name";
		$db_string= $name_type . "s";
		
		$SQL= "SELECT $id_string FROM $db_string WHERE $name_string= '$name' LIMIT 1";
		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_row())
			{
				$id= $row[0];
				$this->$id_string= $id;
				$this->json[$id_string]= $id;
			}
		}
		
		if($enc_type== "json")
		{
			echo json_encode($this->json);
		}

		return $this->$id_string;
	}
	
	public function FetchField($field= "", $table= "", $sql= "", $enc_type= "")
	{
		$field_val= 0;

		$SQL= "SELECT $field FROM $table WHERE $sql LIMIT 1";
	
		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$field_val= $row[$field];
			}
		}
			
		return $field_val;
	}

	public function FetchRow($field_string= "", $table_string= "", $sql= "", $enc_type= "")
	{
		$fields= array();

		$SQL= "SELECT $field_string FROM $table_string WHERE $sql LIMIT 1";
		
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			foreach($row as $key=>$value)
			{
				$fields[$key]= $value;
			}
		}
			
		return $fields;
	}	
	
	public function FetchTemplate($name= "", $vars= array(), $enc_type= "", $vars_obj= "", $element=".mobile_page_wrapper")
	{
		if($name== "")
		{
			$name= "default";
		}
		$template_data= "789";
		//$template_path= $_SERVER['DOCUMENT_ROOT'] . "/TPL/" . $name . ".php";
		return $this->template_name;

		/*
		if($enc_type== "render")
		{
			if(file_exists($template_path))
			{
				$template_data= file_get_contents($template_path);
			}
			$this->template_name= $name;
			$template_data= $this->ParseTemplate($template_data, $vars);
			$this->template_data= $template_data;
			//echo $this->template_data;
		}
		
		else if($enc_type== "json")
		{
			$vars_obj= stripcslashes($vars_obj);
			$vars_obj= json_decode($vars_obj, true);
			
			$template_path= "../TPL/" . $name . ".php";
			if(file_exists($template_path))
			{
				$template_data= file_get_contents($template_path);
			}
			$template_data= $this->ParseTemplate($template_data, $vars_obj);
			$this->template_data= $template_data;
			$this->template_name= $name;
			$json["template_name"]= $this->template_name;
			$json["template_data"]= $this->template_data; 
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");			
			echo json_encode($json);
		}
		else if($enc_type== "callback")
		{
			$vars_obj= stripcslashes($vars_obj);
			$vars_obj= json_decode($vars_obj, true);
			
			$template_path= "../TPL/" . $name . ".php";
			if(file_exists($template_path))
			{
				$template_data= file_get_contents($template_path);
			}
			$template_data= $this->ParseTemplate($template_data, $vars_obj);
			$this->template_data= $template_data;
			$this->template_name= $name;
			$this->json["template_name"]= $this->template_name;
			$this->json["template_data"]= $this->template_data; 
			foreach($vars_obj as $key=>$pair)
			{
				$this->json[$key]= $pair;
			}
			echo "DisplayTemplate(" . json_encode($this->json) . ");";		
		}
		else if($enc_type== "app")
		{
			$vars_obj= stripcslashes($vars_obj);
			$vars_obj= json_decode($vars_obj, true);
			
			$template_path= "../Templates/" . $name . ".php";
			if(file_exists($template_path))
			{
				$template_data= file_get_contents($template_path);
			}
			$template_data= $this->ParseTemplate($template_data, $vars_obj);
			$this->template_data= $template_data;
			$this->template_name= $name;
			$json["template_name"]= $this->template_name;
			$json["template_data"]= $this->template_data; 
			$json["template_element"]= $element;
			echo "handleData(" . json_encode($json) . ");";		
		}		
		else if($enc_type== "curl")
		{
			//echo URL . "/templates/" . $name . ".php";
			$template_path= URL . "/TPL/" . $name . ".php";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $template_path);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$template_data = curl_exec($ch);
			curl_close($ch);
			$template_data= $this->ParseTemplate($template_data, $vars);
			$this->template_data= $template_data;
			$this->template_name= $name;
			echo $this->template_data;
		}
	*/
	} 

	public function ParseTemplate($template_data= "", $vars= array())
	{	
		foreach($vars as $var=> $data)
		{
			$template_var= "cb_" . $var . "_cb";
			$template_data= str_replace($template_var, $data, $template_data);	
		}

		//$template_data= parse_str($template_data);
		//$template_data= eval($template_data);


		return $template_data;
	}
	
	public function SendEmail($email= "", $subject="", $body="", $enc_type= "")
	{
		$json["sent"]= false;

		if(mail($email, $subject, $body))
		{
			if($enc_type== "json")
			{
				$json["sent"]= true;
				echo json_encode($json);
			}
		}
		else
		{
			return false;
		}
	}

	public function UpdateTable($table_name= "", $id_name= "", $id_value= 0, $field= "", $data= "", $raw= false, $enc_type="")
	{
		$json["updated"]= false;

		if($raw== true)
		{
			$SQL= "UPDATE $table_name SET $field= $data WHERE $id_name= '$id_value';";	
		}
		else
		{
			$SQL= "UPDATE $table_name SET $field= '$data' WHERE $id_name= '$id_value';";	
		}

		$result= $this->DbConnect($SQL);

		if($result)
		{
			$this->exec= true;
			$json["sql"]= $SQL;
			$json["updated"]= true;	
		}

		if($enc_type== "json")
		{
			//echo json_encode($json);
		}

		return $this->exec;
	}

	public function GetData($url= "") 
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
	
	public function InsertCatalog($article_id= 0, $chapter_id= 0, $logbook_id= 0)
	{
		$SQL= "INSERT INTO catalog (article_id, chapter_id, logbook_id) VALUES ($article_id, $chapter_id, $logbook_id)";
		$result= $this->DbConnect($SQL);
		
		return $this->insert_id;
	}	

	public function ScrapeGoogle($term= "", $enc_type= "")
	{ 
		$url= "https://www.google.com/search?safe=off&q=$term&oq=$term&num=30";
 
		$ret= array();
		$html = file_get_html($url);
		$index= 0;
		//$ret["html"]= $html;
		foreach($html->find('.g') as $element)
		{
			//$href= $element->href;
			$ret[$index]['type']= "text";
			$ret[$index]['text']= $element->outertext;
			$index= $index + 1;
		}
		
		$this->parse_ret= $ret;
		
		return $html;
	}
	
	public function ScrapeGgi($term= "", $enc_type= "")
	{
		$url= "https://www.google.com/search?safe=off&tbm=isch&q=$term&oq=$term&num=30";

		$ret= array();
		$html = file_get_html($url);
		$index= 0;
		foreach($html->find('a') as $element)
		{
			$href= $element->href;
			if($src_start= strpos($href, "?imgurl="))
			{
				$src_start= $src_start + 8;
				$src_end= strpos($href, "&amp;imgrefurl=");
				$ref_start= $src_end + 15;
				$ref_end= strpos($href, "&amp;usg=");
				$src_len= $src_end - $src_start;
				$ref_len= $ref_end - $ref_start;
				$src= substr($href, $src_start, $src_len);
				$href_substr= substr($href, $ref_start, $ref_len);
				$ret[$index]['type']= "image";
				$ret[$index]['src']= $src;
				$index= $index + 1;
				$ret[$index]['type']= "anchor";
				$ret[$index]['href']= $href_substr;
				$ret[$index]['innertext']= $href_substr;
			}
			else {
			
				$ret[$index]['type']= "anchor";
				$ret[$index]['href']= $href;				
				$ret[$index]['innertext']= $element->innertext;
				$index= $index + 1;
			}
			
			$index= $index + 1;
		}
		
		$this->parse_ret= $ret;
		
		return $html;
	}
	
	public function ScrapePage($url= "", $type= "", $enc_type= "")
	{
		if($url== "" || $url== "http://")
		{
			$url= "http://www.cerebrit.com";
		}
		$ret= array();
		$text= array();
		$html = file_get_html($url);
		$plaintext= $html->plaintext;
		$index= 0;
		foreach($html->find('a, img') as $element)
		{
			if($element->href!= '' && $element->plaintext!= '')
			{
				$ret[$index]['type']= "anchor";
				$ret[$index]['href']= $element->href;
				$ret[$index]['text']= $element->outertext;
				$ret[$index]['innertext']= $element->innertext;
				$ret[$index]['parent']= $element->parent();
				$ret[$index]['first_child']= $element->first_child();
				$ret[$index]['first_sibling']= $element->next_sibling();
			}
			else
			{
				if($element->src!= '')
				{
					$ret[$index]['type']= "image";
					$ret[$index]['src']= $element->src;
					$ret[$index]['href']= $element->parent()->href;
					$ret[$index]['parent']= $element->parent();
					$ret[$index]['first_child']= $element->first_child();				
					$ret[$index]['first_sibling']= $element->next_sibling();
				}
			}
			
			$index= $index + 1;
		}
		
		$index= 0;
		
		foreach($html->find('p') as $element)
		{
			$text[$index]['text']= $element->innertext;
			$text[$index]['parent']= $element->parent();
			$text[$index]['first_child']= $element->first_child();
			$text[$index]['first_sibling']= $element->next_sibling();
			$index= $index + 1;
		}
		
		$this->parse_text= $text;
		$this->parse_ret= $ret;
		$this->parse_plaintext= $plaintext;
		
		if($enc_type== "mobile")
		{
			$this->json['ret']= $this->parse_ret;
			$this->json['plaintext']= $this->parse_plaintext;
			$this->json['text']= $this->parse_text;
			echo json_encode($this->json);
		}
		
		return $this->parse_plaintext;
		//$img= file_get_contents($ret['img']);		
	}
}

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
			if($result->fetch_assoc())
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

class Tile extends User
{
	public $page_name= "";
	public $page_data= "";
	public $tile_content= "";
	public $composite_content= array();
	public $tiles= array();
	public $tile_count= 0;
	public $json= array();

    public function Save($tile_id= 0, $tile_content= "", $style= "", $position= "", $user_id= 0, $enc_type= "")
    {
		if($this->LoggedIn())
		{
			$user_id= $this->user_id;
		}
		else if($this->FBLoggedIn())
		{
			$user_id= $this->user_id;
		}
		else
		{
			$user_id= 0;
		}
		$SQL= " UPDATE tiles 
				SET tile_content= '$tile_content', style= '$style', position= '$position', edits= (edits+1), last_modified= CURRENT_TIMESTAMP(), last_modified_by= $user_id 
				WHERE tile_id= '$tile_id';";				
		
		if($this->DbConnect($SQL))
		{
			$tiles= $this->rows;
			$this->tile_id= $this->insert_id;
			$this->LookUpPage($tile_id);
			$this->LookUpArticle($this->page_id);
			$this->UpdateTable("pages", "page_id", $this->page_id, "last_modified", "CURRENT_TIMESTAMP()", true);
			$this->UpdateTable("articles", "article_id", $this->article_id, "last_modified", "CURRENT_TIMESTAMP()", true);
		}

		if($enc_type== "json")
		{
			$this->json["sql"]= $SQL;
			$this->json["tile_id"]= $this->tile_id;
			echo json_encode($this->json);
		}

		if($enc_type== "callback")
		{
			//$this->json["sql"]= $SQL;
			$this->json["tile_id"]= $this->tile_id;
			$this->json["tiles"]= $tiles;
			echo "DisplaySaveTile(" . json_encode($this->json) . ");";
		}		

		return $this->tile_id;
    }

    public function SavePaint($tile_id= 0, $paint_content= "", $style= "", $position= "", $enc_type= "")
    {
		if($this->LoggedIn())
		{
			$user_id= $this->user_id;
		}
		else
		{
			$user_id= 0;
		}

		$SQL= " UPDATE tiles 
				SET paint_content= '$paint_content', style= '$style', position= '$position', last_modified_by= '$user_id', last_modified= CURRENT_TIMESTAMP(), last_modified_by= '$user_id', edits= (edits+1)  
				WHERE tile_id= '$tile_id';";
		
		if($this->DbConnect($SQL))
		{
			$this->exec= true;
			$json["exec"]= $this->exec;
			$this->tile_id= $this->insert_id;
			$tiles= $this->rows;
			$this->LookUpPage($tile_id);
			$this->UpdateTable("pages", "page_id", $this->page_id, "last_modified", "CURRENT_TIMESTAMP()", true);
		}

		if($enc_type== "json")
		{
			$json["sql"]= $SQL;
			$json["tile_id"]= $this->tile_id;
			$json["style"]= $style;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			echo json_encode($json);
		}

		if($enc_type== "callback")
		{
			//$this->json["sql"]= $SQL;
			$this->json["tile_id"]= $this->tile_id;
			$this->json["tiles"]= $tiles;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			echo json_encode($this->json);
		}		
			
		return $this->tile_id;
    }

    public function SaveBack($tile_id= 0, $back_content= "", $enc_type= "")
    {
		if($this->LoggedIn())
		{
			$user_id= $_SESSION['user_id'];
		}

		$SQL= " UPDATE tiles 
				SET back_content= '$back_content', edits= (edits+1)  
				WHERE tile_id= '$tile_id';";
		
		if($this->DbConnect($SQL))
		{
			$this->exec= true;
			$json["exec"]= $this->exec;
		}

		if($enc_type== "json")
		{
			$json["back_content"]= $back_content;
			echo json_encode($json);
		}
			
		return $this->tile_id;
    }
	
	public function FetchBack($tile_id= 0, $enc_type="")
	{
		$SQL= "SELECT back_content, type_id FROM tiles WHERE tile_id= $tile_id LIMIT 1;";
	
		$result= $this->DbConnect($SQL);
		
		if($row= $result->fetch_assoc())
		{
			if($row['type_id']< 4)
			{
				$back_content= $row['back_content'];
			}
			else if($row['type_id']<= 6 || $row['type_id']== 8 || $row['type_id']== 16)
			{
				if($this->LoggedIn())
				{
					$back_content= $row['back_content'];
				}
				else
				{
					$back_content= "Make a comment or <a class='login_link' href='javascript:'>login</a> to view";
				}
			}
			else
			{
				if($this->LoggedIn())
				{
					$back_content= $row['back_content'];
				}
				else
				{
					$back_content= "<a class='login_link' href='javascript:'>Login</a> to view";
				}				
			}
						
			$this->exec= true;
			$json["exec"]= $this->exec;
		}

		if($enc_type== "json")
		{
			$json["content"]= $back_content;
			echo json_encode($json);
		}
		if($enc_type== "json")
		{
			$json["content"]= $back_content;
			$json["tiles"]= $this->rows;
			$json["tile_id"]= $this->tile_id;
			echo json_encode($json);
		}		
	}
	
    public function Create($brand= "", $style= "", $tile_content= "", $page_id= 0, $enc_type="")
    {
		if($this->LoggedIn())
		{
			$user_id= $this->user_id;
		}
		else
		{
			$user_id= 0;
		}
		$top_tile= $this->FetchTopTile($page_id);
		$tile_order= $top_tile +1;
		$tiles= 0;
		$this->brand_id= $this->FetchID($brand, "brand");
		
		$page_account_id= $this->FetchField("account_id", "pages", "page_id=$page_id");

		if(($this->account_id >= $page_account_id) && $page_id!= 0)
		{
			$SQL= "INSERT INTO tiles (brand_id, style, tile_content, tile_order, page_id, created_on, last_modified, edits, created_by) VALUES ('$this->brand_id', '$style', '$tile_content', '$tile_order', '$page_id', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), '1', $user_id);";

			if($this->DbConnect($SQL))
			{
				$this->tile_id= $this->insert_id;
				$tiles= $this->rows;
				$this->UpdateTable("pages", "page_id", $this->page_id, "last_modified", "CURRENT_TIMESTAMP()", true);
			}
		}

		if($enc_type== "json")
		{			
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$json["style"]= $style;
			$json["brand"]= $brand;
			$json["content"]= $tile_content;
			$json["tile_id"]= $this->tile_id;
			echo json_encode($json);
		}
		if($enc_type== "callback")
		{
			$json["style"]= $style;
			$json["brand"]= $brand;
			$json["content"]= $tile_content;
			$json["tile_id"]= $this->tile_id;
			$json["tiles"]= $tiles;
			echo "DisplayAddTile(" . json_encode($json) . ");";
		}				
		
		return $this->tile_id;
    }

	public function FetchTopTile($page_id= 0, $enc_type= "")
	{
		$top_tile= "";
		$SQL= "SELECT tile_id, tile_order FROM tiles WHERE page_id= $page_id ORDER BY tile_order DESC LIMIT 1;";

		$result= $this->DbConnect($SQL);
		
		if($row= $result->fetch_assoc())
		{
			$top_tile= $row['tile_order'];
			$this->tile_id= $row['tile_id'];
		}
		if($top_tile== "")
		{
			$top_tile= 0;
		}
		
		return $top_tile;
	}
	
	public function MoveToTop($tile_id= 0, $page_id= 0, $enc_type= "")
	{
		$top_tile= $this->FetchTopTile($page_id);
		
		if($this->tile_id!= $tile_id)
		{
			$tile_order= $top_tile + 1;
		}
		
		$this->UpdateTable("tiles", "tile_id", $tile_id, "tile_order", $tile_order, true, $enc_type);
		
		if($enc_type== "json")
		{
			$this->json['tile_order']= $tile_order;
			$this->json['tile_id']= $tile_id;
			$this->json['page_id']= $page_id;
			echo json_encode($this->json);
		}
		
		return $top_tile;
	}
	
    public function Comment($style= "", $comment= "", $article_id= 0, $page_id= 0, $user_id= 0, $enc_type= "json")
    {
		$SQL= "INSERT INTO tiles (style, tile_content, user_id, article_id, page_id, created_on, last_modified, type, edits) VALUES ('$style', '$comment', '$user_id', '$article_id', '$page_id', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'comment', '1');";

		if($this->DbConnect($SQL))
		{
			$this->exec= true;
			$json["exec"]= $this->exec;
			$this->comment_id= $this->insert_id;
		}
		
		if($enc_type== "json")
		{
			$json["comment_id"]= $this->comment_id;
			$json["style"]= $style;
			$json["comment"]= $comment;  
			echo json_encode($json);
		}

		return $this->comment_id;
    }

    public function ClearTile($tile_id= 0, $enc_type="")
    {
		if($this->LoggedIn() && $tile_id> 0)
		{
			$user_id= $this->user_id;	
			$SQL= "DELETE FROM tiles WHERE link_id= '$tile_id'";
			$result= $this->DbConnect($SQL);
		}
		else if($this->FBLoggedIn() && $tile_id> 0)
		{
			$user_id= $this->user_id;	
			$SQL= "DELETE FROM tiles WHERE link_id= '$tile_id'";
			$result= $this->DbConnect($SQL);		
		}
		if($enc_type= "json")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");			
			$this->json["id"]= $this->insert_id;
			echo json_encode($this->json);
		}

		return $tile_id;
    }
	
    public function DeleteTile($tile_id= 0, $enc_type="")
    {
    	$page_id= $this->Fetchfield("page_id", "tiles", "tile_id= $tile_id");	
    	$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");
    	$this->LoggedIn();
    	$tiles= 0;
		if(($this->account_id >= $page_account_id) && $tile_id> 0)
		{
			$user_id= $this->user_id;	
			$SQL= "DELETE FROM tiles WHERE tile_id= '$tile_id' LIMIT 1";
			$result= $this->DbConnect($SQL);
			$tiles= $this->rows;
		}		
		if($enc_type== "json")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$this->json["id"]= $this->insert_id;
			echo json_encode($this->json);
		}

		if($enc_type== "mobile")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$this->json["id"]= $this->insert_id;
			echo "DisplayDeleteTile(" . json_encode($this->json) . ")";
		}

		if($enc_type== "callback")
		{
			$this->json["id"]= $this->insert_id;
			$this->json["tiles"]= $tiles;
			echo "DisplayDeleteTile(" . json_encode($this->json) . ");";
		}		

		return $tile_id;
    }
	
	public function FetchContent($page_id= 0, $limit= 0, $offset= 0, $enc_type="", $order="ASC")
	{
		$page_string= "ti.page_id= '$page_id'";
	
		$SQL= "SELECT DISTINCT ti.*, ty.type_name, b.brand_name, b.brand_id, u.user_name
				FROM tiles ti 
				LEFT JOIN brands b ON ti.brand_id= b.brand_id 
				LEFT JOIN types ty ON ti.type_id= ty.type_id 
				LEFT JOIN users u ON ti.created_by= u.user_id 
				WHERE $page_string
				AND ti.type_id >= 1
				ORDER BY tile_order $order
				LIMIT $offset, $limit;";

		//$this->DbConnect($SQL);
		
		$result= $this->DbConnect($SQL);
		
		$count= 0;
		
		while($row= $result->fetch_assoc())
		{
			$this->CheckPriveleges($row, $enc_type);
			$this->tiles[]= $row;
			$count= $count + 1;
		}
			
		$this->tile_count= $count;
		
		if($enc_type== "callback")
		{
			//$this->content= $json;
			//$this->json["tiles"];
			//echo "DisplayTiles(" . json_encode($json) . ");";
		}
	
		if($enc_type== "mobile")
		{
			$this->json["page_id"]= $page_id;
			$this->json["offset"]= $offset;
			$this->json["content"]= $this->tile_content;
			$this->json["composite"]= $this->composite_content;
			$this->json["tile_count"]= $this->tile_count;
			//echo json_encode($this->json);			
		}

		return $this->tile_content;
	}

	public function FetchTile($tile_id= 0, $stack_id, $enc_type="")
	{
		$SQL= "SELECT ti.*, ty.type_name, b.brand_name, b.brand_id 
				FROM tiles ti 
				LEFT JOIN brands b ON ti.brand_id= b.brand_id 
				LEFT JOIN types ty ON ti.type_id= ty.type_id 
				WHERE ti.tile_id= $tile_id
				AND ti.type_id >= 1
				ORDER BY tile_order
				LIMIT 1;";
	
		//$this->DbConnect($SQL);
		
		$result= $this->DbConnect($SQL);
		
		$row= $result->fetch_assoc();

		$this->CheckPriveleges($row, $enc_type);
		
		$this->FetchTileLinks($tile_id, $enc_type);
		
		if($enc_type== "mobile" || $enc_type== "mobile2")
		{
			$this->json["tile_content"]= $this->tile_content;
			$this->json["composite"]= $this->composite_content; 
			$this->json["brand_id"]= $this->brand_id;
			$this->json["enc_type"]= $enc_type;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			echo json_encode($this->json);
		}
		
		return $row;
	}
	
	public function FetchTileLinks($tile_id= 0, $enc_type= "")
	{
		$SQL= "SELECT ti.*, ty.type_name, b.brand_name, b.brand_id 
				FROM tiles ti 
				LEFT JOIN brands b ON ti.brand_id= b.brand_id 
				LEFT JOIN types ty ON ti.type_id= ty.type_id 
				WHERE ti.link_id= $tile_id
				AND ti.type_id >= 1
				ORDER BY tile_order";

		//$this->DbConnect($SQL);
		
		$result= $this->DbConnect($SQL);
		
		//$count= 0;
		
		while($row= $result->fetch_assoc())
		{
			$this->CheckPriveleges($row, $enc_type);
			//$this->tiles[]= $row;
			//$count= $count + 1;
		}

		return true;
	}
	
	public function CheckPriveleges($tile= array(), $enc_type= "")
	{
		$type_name= $tile['type_name'];
		$this->brand_id= $tile['brand_id'];
		$link_id= $tile['link_id'];
		
		if($link_id> 0 && ($enc_type=="mobile" || $enc_type=="mobile2"))
		{
			$this->CheckLink($link_id, $tile, $enc_type);
		}
		else
		{
			switch($type_name)
			{
				case 'free':
					$this->CheckType($tile, $type_name, $enc_type);
					break;
				case 'user':
					if($this->LoggedIn() || $this->FBLoggedIn())
					{
						$this->CheckType($tile, $type_name, $enc_type);
					}
					else if($tile['brand_name']== "flippable")
					{
						$this->tile_content.= $this->FetchUserTileFlippable($tile, $enc_type);
					}
					else
					{
						$this->tile_content.= $this->FetchUserTile($tile, $enc_type);
					}
					break;
				case 'user_front':
					if($this->LoggedIn() || $this->FBLoggedIn())
					{
						$this->CheckType($tile, $type_name, $enc_type);
					}
					else
					{
						$this->tile_content.= $this->FetchUserTileFlippable($tile, $type_name, $enc_type);
					}
					break;			
				case 'user_back':
					$this->CheckType($tile, $type_name, $enc_type);
					break;			
				case 'member':
					if($this->LoggedIn() || $this->FBLoggedIn())
					{
						if($this->account_id> 3)
						{
							$this->CheckType($tile, $type_name, $enc_type);
						}
					}
					else if($tile['brand_name']== "flippable")
					{
						$this->tile_content.= $this->FetchMemberTileFlippable($tile, $enc_type);
					}
					else
					{
						$this->tile_content.= $this->FetchMemberTile($tile, $enc_type);
					}
					break;			
				case 'member_front':
					if($this->LoggedIn() || $this->FBLoggedIn())
					{
						if($this->account_id> 3)
						{
							$this->CheckType($tile, $type_name, $enc_type);
						}
					}
					else
					{
						$this->tile_content.= $this->FetchMemberTileFlippable($tile, $type_name, $enc_type);
					}
					break;
				case 'member_back':
					$this->CheckType($tile, $type_name, $enc_type);
					break;			
				case 'user_member':
					if($this->LoggedIn() || $this->FBLoggedIn())
					{
						$this->CheckType($tile, $type_name, $enc_type);
					}
					else
					{
						$this->tile_content.= $this->FetchMemberTileFlippable($tile, $type_name, $enc_type);
					}
					break;			
				case 'member_user':
					if($this->LoggedIn() || $this->FBLoggedIn())
					{
						if($this->account_id> 3)
						{
							$this->CheckType($tile, $type_name, $enc_type);
						}
					}
					else
					{
						$this->tile_content.= $this->FetchMemberTileFlippable($tile, $type_name, $enc_type);
					}
					break;
				case 'default':
					$this->CheckType($tile, $type_name, $enc_type);
					break;				
			}
		}
	}

	public function CheckType($tile= array(), $type_name, $enc_type= "")
	{
		$this->tile_count++;
		if($tile['brand_name']== 'paint' || $tile['brand_name']== 'paint composite' || $tile['brand_name']== 'flippable paint comp')
		{
			$this->tile_content.= $this->FetchPaintTile($tile, $type_name, $enc_type);
		}
		else if($tile['brand_name']== 'flippable')
		{
			$this->tile_content.= $this->FetchFlippableTile($tile, $type_name, $enc_type);
		}
		// all other content
		else
		{
			$this->tile_content.= $this->FetchEditableTile($tile, $type_name, $enc_type);
		}	
	}
	
	public function CheckCompositeType($tile= array(), $type_name, $enc_type= "")
	{
		$tile_id= $tile['tile_id'];
		$composite_content= " ";
		
		if($tile['brand_name']== 'composite')
		{
			$composite_content.= $this->FetchCompositeTile($tile, $type_name, $enc_type);
		}
		else if($tile['brand_name']== 'paint composite')
		{
			$composite_content.= $this->FetchCompositePaintTile($tile, $type_name, $enc_type);
		}
		else if($tile['brand_name']== 'flippable composite')
		{
			$composite_content.= $this->FetchCompositeFlippableTile($tile, $type_name, $enc_type);
		}	
	
		$this->composite_content[$tile_id]["tile_content"]= $composite_content;
	}
	
	public function CheckLink($link_id= 0, $tile= array(), $enc_type= "")
	{
		$tile_id= $tile['tile_id'];
		//$this->composite_content[$tile_id]= $tile_id;	
		$this->composite_content[$tile_id]= "";	
		$this->composite_content[$tile_id]["tile_id"]= $tile_id;
		$this->composite_content[$tile_id]["type_name"]= $tile_id;
		$this->composite_content[$tile_id]["link_id"]= $link_id;	
		//$link_tile= $this->FetchTile($link_id, "");
		//$type_name= $link_tile['type_name'];
		$type_name= $tile['type_name'];
		$brand_name= $tile['brand_name'];
		$this->composite_content[$tile_id]["type_name"]= $type_name;
		$this->composite_content[$tile_id]["brand_name"]= $brand_name;


		switch($type_name)
		{
			case 'free':
				$this->CheckCompositeType($tile, $type_name, $enc_type);
				break;
			case 'user':
				if($this->LoggedIn() || $this->FBLoggedIn())
				{
					$this->CheckCompositeType($tile, $type_name, $enc_type);
				}
				break;
			case 'user_front':
				if($this->LoggedIn() || $this->FBLoggedIn())
				{
					$this->CheckCompositeType($tile, $type_name, $enc_type);
				}
				break;			
			case 'user_back':
				$this->CheckCompositeType($tile, $type_name, $enc_type);
				break;			
			case 'member':
				if($this->LoggedIn() || $this->FBLoggedIn())
				{
					if($this->account_id> 3)
					{
						$this->CheckCompositeType($tile, $type_name, $enc_type);
					}
				}
				break;			
			case 'member_front':
				if($this->LoggedIn() || $this->FBLoggedIn())
				{
					if($this->account_id> 3)
					{
						$this->CheckCompositeType($tile, $type_name, $enc_type);
					}
				}
				break;
			case 'member_back':
				$this->CheckCompositeType($tile, $type_name, $enc_type);
				break;			
			case 'user_member':
				if($this->LoggedIn() || $this->FBLoggedIn())
				{
					$this->CheckCompositeType($tile, $type_name, $enc_type);
				}
				break;			
			case 'member_user':
				if($this->LoggedIn() || $this->FBLoggedIn())
				{
					if($this->account_id> 3)
					{
						$this->CheckCompositeType($tile, $type_name, $enc_type);
					}
				}
				break;
			case 'default':
				$this->CheckCompositeType($tile, $type_name, $enc_type);
				break;
		}
	}
	
	public function FetchUserTile($tile= array(), $enc_type= "")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper' type='user'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'>Make a comment or <a class='login_link' href='javascript:'>login</a> to view</div>
					</div>
				</div>			
				";
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
				<div class='mobile_wrapper' type='user'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'>Make a comment or <a class='login_link' href='javascript:'>login</a> to view</div>
					</div>
				</div>			
				";
		}
		else
		{
			$tile_data= "
				<div class='tile_wrapper' type='user' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'>Make a comment or <a class='login_link' href='javascript:'>login</a> to view</div>
					</div>
				</div>
				";
		}
		
		return $tile_data;	
	}

	public function FetchUserTileFlippable($tile= array(), $type_name, $enc_type= "")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper flippable' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'>Make a comment or <a class='login_link' href='javascript:'>login</a> to view, click to flip over</div>
					</div>
				</div>			
				";
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
				<div class='mobile_wrapper flippable' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'>Make a comment or <a class='login_link' href='javascript:'>login</a> to view, click to flip over</div>
					</div>
				</div>			
				";
		}		
		else
		{
			$tile_data= "
				<div class='tile_wrapper flippable' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'>Make a comment or <a class='login_link' href='javascript:'>login</a> to view, click to flip over</div>
					</div>
				</div>
				";
		}
		
		return $tile_data;	
	}

	public function FetchMemberTile($tile= array(), $enc_type= "")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper' type='member'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'><a class='login_link' href='javascript:'>Login</a> to view</div>
					</div>
				</div>			
				";
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
				<div class='mobile_wrapper' type='member'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'><a class='login_link' href='javascript:'>Login</a> to view</div>
					</div>
				</div>			
				";
		}		
		else
		{
			$tile_data= "
				<div class='tile_wrapper' type='member' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'><a class='login_link' href='javascript:'>Login</a> to view</div>
					</div>
				</div>
				";
		}
		
		return $tile_data;	
	}

	public function FetchMemberTileFlippable($tile= array(), $type_name, $enc_type= "")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper flippable' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'><a class='login_link' href='javascript:'>Login</a> to view, click to flip over</div>
					</div>
				</div>			
				";
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
				<div class='mobile_wrapper flippable' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'><a class='login_link' href='javascript:'>Login</a> to view, click to flip over</div>
					</div>
				</div>			
				";
		}		
		else
		{
			$tile_data= "
				<div class='tile_wrapper flippable' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style='$tile[style]'>
						<div style='font-size: 14px; font-weight: bold; position: relative; top: 30%;'><a class='login_link' href='javascript:'>Login</a> to view, click to flip over</div>
					</div>
				</div>
				";
		}
		
		return $tile_data;	
	}
	
	public function FetchPaintTile($tile= array(), $type_name, $enc_type="")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper paintable' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div class='paint'><div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img id='canvasImage$tile[tile_id]' src=''/></div></div>
					</div>
				</div>
				";		
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";	
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
				<div class='mobile_wrapper paintable' type='$type_name'>
					<div tile_id='$tile[tile_id]' style=\"$tile[style]\" class='mobile'>
						<div class='paint'><div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img style='width: 100%; height: 100%' id='canvasImage$tile[tile_id]' src=''/></div></div>
					</div>
				</div>
				";		
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";
			$tile_data.= $this->FetchMobileControls($tile);
		}
		else if($enc_type== "mobile2")
		{
			$tile_data= "
				<div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img style='width: 100%; height: 100%' id='canvasImage$tile[tile_id]' src=''/>
				</div>
				";
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";	
		}		
		else
		{
			$tile_data= "
				<div class='tile_wrapper paintable' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style=\"$tile[style]\">
						<div class='paint'><div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img style='width: 100%; height: 100%' id='canvasImage$tile[tile_id]' src=''/></div></div>
					</div>
				</div>
				";
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";	
		}
		
		return $tile_data;
	}

	public function FetchCompositeTile($tile= array(), $type_name, $enc_type="")
	{
		if($enc_type== "item")
		{
			$composite_data= "
				<div class='item_wrapper composite' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";		
			
		}
		else if($enc_type== "mobile")
		{
			$composite_data= "
	
					<div tile_id='$tile[tile_id]' class='mobile'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
	
				";		
			
		}		
		else
		{
			$composite_data= "
				<div class='tile_wrapper composite' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style=\"$tile[style]\">
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";
			
		}
		
		return $composite_data;
	}	
	
	public function FetchCompositePaintTile($tile= array(), $type_name, $enc_type="")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper composite' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div class='paint'><div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img id='canvasImage$tile[tile_id]' src=''/></div></div>
					</div>
				</div>
				";		
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";	
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
	
					<div tile_id='$tile[tile_id]' class='mobile composite'>
						<div class='paint'><div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img id='canvasImage$tile[tile_id]' src=''/></div></div>
					</div>
	
				";		
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";	
		}		
		else
		{
			$tile_data= "
				<div class='tile_wrapper composite' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style=\"$tile[style]\">
						<div class='paint'><div id= 'wPaint$tile[tile_id]' style='width: 100%; height: 100%;'><img id='canvasImage$tile[tile_id]' src=''/></div></div>
					</div>
				</div>
				";
			$tile_data.= "<script>$('#canvasImage$tile[tile_id]').attr('src', '$tile[paint_content]');</script>";	
		}
		
		return $tile_data;
	}
	
	public function FetchCommentTile($tile= array(), $type_name, $enc_type="")
	{
		if($enc_type== "item")
		{	
			$tile_data= "
				<div class='comment_wrapper' type='$type_name' style='$tile[position]'>
					<div comment_id='$tile[tile_id]' class='comment' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";
		}
		else if($enc_type== "mobile")
		{	
			$tile_data= "
				<div class='comment_wrapper' type='$type_name' style='$tile[position]'>
					<div comment_id='$tile[tile_id]' class='comment' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";
		}		
		else
		{
			$tile_data= "
				<div class='comment_wrapper' type='$type_name' style='$tile[position]'>
					<div comment_id='$tile[tile_id]' class='comment' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";		
		}
		
		return $tile_data;
	}
	
	public function FetchEditableTile($tile= array(), $type_name, $enc_type="")
	{
		if($enc_type== "item")
		{
			$tile_data= "
				<div class='item_wrapper' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";	
		}
		else if($enc_type== "mobile")
		{
			$tile_data= "
				<div class='mobile_wrapper' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";
			$tile_data.= $this->FetchMobileControls($tile);				
		}
		else if($enc_type== "mobile2")
		{
			$tile_data= "$tile[tile_content]"; 
			//$tile_data.= $this->FetchMobileControls($tile);				
		} 
		else if($enc_type== "spider")
		{
			$tile_data= "
				<div class='mobile_wrapper' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";
			$tile_data.= $this->FetchSpiderControls($tile);				
		}		
		else
		{
			$tile_data= "
				<div class='tile_wrapper' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>
				";	
		}
		
		return $tile_data;
	}

	public function FetchFlippableTile($tile= array(), $type_name, $enc_type="")
	{
		if($enc_type== "item")
		{	
			$tile_data= "
				<div class='item_wrapper flippable' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='item' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>";
		}
		else if($enc_type== "mobile")
		{	
			$tile_data= "
				<div class='mobile_wrapper flippable' type='$type_name'>
					<div tile_id='$tile[tile_id]' class='mobile' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>";
		}
		else
		{
			$tile_data= "
				<div class='tile_wrapper flippable' type='$type_name' style='$tile[position]'>
					<div tile_id='$tile[tile_id]' class='tile' style='$tile[style]'>
						<div class='editable'>$tile[tile_content]</div>
					</div>
				</div>";		
		}
		
		return $tile_data;
	}
	
	public function FetchSocialMedia($user_id= 0, $timestamp= 0)
	{
		$user_name= $this->FetchName($user_id, "user");
		$social_media= "<div class='social_media'>Posted by <a href='/$user_name'>$user_name</a> on ". date("M j, Y, g:i a", $timestamp);
		$social_media.= " <div class='fb-like' data-href='http://www.cerebrit.com/$user_name' data-send='false' data-layout='button_count' data-width='50' data-show-faces='false' data-font='verdana'></div>";
    	$social_media.= "<a href='https://twitter.com/share' class='twitter-share-button' data-url='http://$this->chapter_name.$this->logbook_name.cerebrit.com/$this->article_name/' data-lang='en' >Tweet</a>";
		$social_media.= "</div>";

		return $social_media;
	}

	public function FetchMobileControls($tile= array())
	{
		$user_name= $this->FetchName($tile['created_by'], "user");
		$modified_name= $this->FetchName($tile['last_modified_by'], "user");
		$social_media= "<div class='social_media'>Posted by <a class= 'mobile_user_feed_link' href='javascript:'>$user_name</a> on ". date('n/j/y', (strtotime($tile['created_on']) - 28800)) . " last modified by <a class='mobile_user_feed_link' href='javascript:'>$modified_name</a> on " . date('n/j/y g:i A', (strtotime($tile['last_modified']) - 28800));
		$social_media.= "</div>";		
		$social_media.= "<div class='navigation_container' tile_id='$tile[tile_id]' >";
			
		if($this->LoggedIn())
		{
			if($tile['created_by']== $this->user_id)
			{
				$this->FetchTemplate("navigation_mobile9", array("tile_id"=>$tile['tile_id']), "render");	
			}
			else
			{
				$this->FetchTemplate("navigation_mobile4", array("tile_id"=>$tile['tile_id']), "render");
			}
			$social_media.= $this->template_data;
		}
		else if($this->FBLoggedIn())
		{
			if($tile['created_by']== $this->user_id)
			{
				$this->FetchTemplate("navigation_mobile9", array("tile_id"=>$tile['tile_id']), "render");	
			}
			else
			{
				$this->FetchTemplate("navigation_mobile4", array("tile_id"=>$tile['tile_id']), "render");
			}
			$social_media.= $this->template_data;
		}
		else
		{
			if($tile['created_by']== 0)
			{
				$this->FetchTemplate("navigation_mobile9", array("tile_id"=>$tile['tile_id']), "render");
				$social_media.= $this->template_data;
			}
			else
			{
				$this->FetchTemplate("navigation_mobile4", array("tile_id"=>$tile['tile_id']), "render");
				$social_media.= $this->template_data;				
			}
		}
		
		$social_media.= "</div>";
		
		return $social_media;
	}
	
	public function FetchSpiderControls($tile= array())
	{
		$spider_controls= "";
		if($this->LoggedIn())
		{
			if($tile["type"]== "spider_anchor")
			{
				$spider_controls= "<div class='social_media anchor'><b>Path: </b><span class='spider_path'>$tile[href]</span></div>";
			}
			else if($tile["type"]== "spider_image")
			{
				$spider_controls= "<div class='social_media image'>Path: <span class='spider_path'>$tile[src]</span></div>";
			}
			$spider_controls.= "<div class='navigation_container'>";		
			$this->FetchTemplate("navigation_mobile6", array("tile_id"=>$tile['tile_id'], "type"=>$tile['type'], "url"=>$tile['url']), "render");
			$spider_controls.= $this->template_data;
		}
		else if($this->FBLoggedIn())
		{
			if($tile["type"]== "spider_anchor")
			{
				$spider_controls= "<div class='social_media anchor'><b>Path: </b><span class='spider_path'>$tile[href]</span></div>";
			}
			else if($tile["type"]== "spider_image")
			{
				$spider_controls= "<div class='social_media image'><b>Path: </b><span class='spider_path'>$tile[src]</span></div>";
			}
			$spider_controls.= "<div class='navigation_container'>";		
			$this->FetchTemplate("navigation_mobile6", array("tile_id"=>$tile['tile_id'], "type"=>$tile['type'], "url"=>$tile['url']), "render");
			$spider_controls.= $this->template_data;
		}
		else
		{	
			if($tile["type"]== "spider_anchor")
			{
				$spider_controls= "<div class='social_media anchor'><b>Path: </b><span class='spider_path'>$tile[href]</span></div>";
				$spider_controls.= "<div class='navigation_container'>";		
				$this->FetchTemplate("navigation_mobile6", array("tile_id"=>$tile['tile_id'], "type"=>$tile['type'], "url"=>$tile['url']), "render");
				$spider_controls.= $this->template_data;				
			}
			else if($tile["type"]== "spider_image")
			{
				$spider_controls= "<div class='social_media image'><b>Path: </b><span class='spider_path'>$tile[src]</span></div>";
				$spider_controls.= "<div class='navigation_container'>";		
				$this->FetchTemplate("navigation_mobile6", array("tile_id"=>$tile['tile_id'], "type"=>$tile['type'], "url"=>$tile['url']), "render");
				$spider_controls.= $this->template_data;				
			}
		}
		
		$spider_controls.= "</div>";
		
		return $spider_controls;
	}
	
	public function LookUpPage($tile_id= 0, $enc_type= "")
	{	
		$SQL= "SELECT page_id FROM tiles WHERE tile_id= $tile_id LIMIT 1;";

		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$page_id= $row['page_id'];
				$this->page_id= $page_id;
			}
		}

		return $this->page_id;
	}

	public function LookUpArticle($page_id= 0, $enc_type= "")
	{	
		$SQL= "SELECT article_id FROM pages WHERE page_id= $page_id LIMIT 1;";

		if($result= $this->DbConnect($SQL))
		{
			if($row= $result->fetch_assoc())
			{
				$article_id= $row['article_id'];
				$this->article_id= $article_id;
			}
		}

		return $this->article_id;
	}
	
	public function LinkTile($tile_id= 0, $link_id= 0, $enc_type= "")
	{
		$this->LoggedIn();
		$page_id= $this->FetchField("page_id", "tiles", "tile_id= $tile_id");
		$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");

		if($this->account_id >= $page_account_id)
		{
			if($tile_id== $link_id)
			{
				$this->UpdateTable("tiles", "tile_id", $link_id, "link_id", 0, true, $enc_type);
			}
			else
			{
				$this->UpdateTable("tiles", "tile_id", $link_id, "link_id", $tile_id, true, $enc_type);
			}
		}

		if($enc_type=="json") {
			$this->json["insert_id"]= $this->insert_id;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			echo json_encode($this->json);
		}
		if($enc_type=="callback") {
			$this->json["insert_id"]= $this->insert_id;
			$this->json["link"]= $link_id;
			$this->json["tile"]= $tile_id;
			echo "DisplayLinkTiles(" . json_encode($this->json) . ");";
		}

		return true;
	}	
	
	public function UpdateTile($tile_id= 0, $property="", $value= "", $enc_type= "")
	{
		$this->LoggedIn();
		$page_id= $this->FetchField("page_id", "tiles", "tile_id= $tile_id");
		$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");

		$rows= 0;
		if($this->account_id >= $page_account_id )
		{
			$this->UpdateTable("tiles", "tile_id", $tile_id, $property, $value, false);

			$rows= $this->rows;
		}

		if($enc_type== "json")
		{
			$this->json["insert_id"]= $this->insert_id;
			$this->json["tile_id"]= $tile_id;
			$this->json["value"]= $value;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");	
			echo json_encode($this->json);
		}

		if($enc_type== "callback")
		{
			$this->json["insert_id"]= $this->insert_id;
			$this->json["tile_id"]= $tile_id;
			$this->json["value"]= $value;
			$this->json["rows"]= $rows;
			echo "DisplayUpdateTile(" . json_encode($this->json) . ");";
		}

		return $this->insert_id;		
	}
}

class Page extends Tile
{
	public $page_order= 0;

	public function CreatePage($article_id= 0, $title= "", $enc_type= "")
	{
		$user_id= 0;
		
		if($this->LoggedIn())
		{	
			$user_id= $_SESSION['user_id'];
		}
		if($title== "")
		{
			$title= "blank";
		}

		$page_order= $this->FetchPageOrder($article_id);
		
		$SQL= "INSERT INTO pages (page_name, created_by, article_id, page_order, created_on, last_modified, timestamp) VALUES('$title', '$user_id', '$article_id', '$page_order', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP())";
		$this->DbConnect($SQL);
		$pages= $this->rows;

		$this->page_id= $this->insert_id;
		
		if($enc_type== "json")
		{
			$json["page_id"]= $this->page_id;
			echo json_encode($json);
		}

		if($enc_type== "callback")
		{
			$this->json["article_id"]= $article_id;	
			$this->json["page_id"]= $this->page_id;
			$this->json["pages"]= $pages;
			echo "AppendPage(" . json_encode($this->json) . ");";			
		}
		
		return $this->page_id;
	}

	public function FetchRandomPage($article_id= 0, $enc_type= "")
	{
		if($article_id== 0)
		{
			$article_string= "article_id<> 0";
		}
		else
		{
			$article_string= "article_id= $article_id";
		}
		$SQL= "SELECT page_id, page_name FROM pages WHERE $article_string ORDER BY RAND() LIMIT 1";
		
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$page_id= $row['page_id'];
			$page_name= $row['page_name'];
			$this->page_id= $page_id;
			$this->page_name= $page_name;
		}
		if($enc_type== "json")
		{
			$this->json["page_id"]= $this->page_id;
			$this->json["page_name"]= $this->page_name;
			echo json_encode($this->json);
		}
		
		return $this->page_id;
	}

	public function ChangePage($tile_id= 0, $page_id= 0, $enc_type= "")
	{
		$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");

		$this->LoggedIn();
		if($this->account_id >= $page_account_id)
		{
			$SQL= "UPDATE tiles SET page_id= '$page_id', last_modified= CURRENT_TIMESTAMP(), edits= (edits+1) WHERE tile_id= '$tile_id'; ";
			$this->DbConnect($SQL);
			$tiles= $this->rows;
		}
		
		$this->UpdateTable("pages", "page_id", $page_id, "last_modified", "CURRENT_TIMESTAMP()", true);

		if($enc_type== "callback")
		{
			$this->json["tiles"]= $tiles;
			echo "DisplayChangePage(" . json_encode($this->json) . ");";
		}
		
		return $this->exec;
	}

	public function FetchPageOrder($article_id= 0, $enc_type= "")
	{
		$page_order= 0;

		$SQL= "SELECT MAX(page_order) as page_order FROM pages WHERE article_id= $article_id;";
		
		$result= $this->DbConnect($SQL);
		
		if($row= $result->fetch_assoc())
		{
			$page_order= $row['page_order'];
			$this->page_order= $page_order + 1;
			$this->json["order"]= $page_order;
		}
		
		if($enc_type== "json")
		{
			echo json_encode($this->json);
		}
		
		return $this->page_order;
	}

	public function DeletePage($page_id, $title, $enc_type)
	{
		$user_id= 0;
		if($this->LoggedIn())
		{
			$user_id= $_SESSION['user_id'];	
		}
		$SQL= "DELETE FROM pages WHERE page_id= '$page_id' AND page_name= '$title' AND created_by= '$user_id'";
		$this->DbConnect($SQL);
		$this->json["pages"]= $this->rows;

		$SQL= "DELETE FROM tiles WHERE page_id= '$page_id' AND created_by= '$user_id'";
		$this->DbConnect($SQL);
		$this->json["tiles"]= $this->rows;

		if($enc_type== "callback")
		{
			echo "DisplayDeletedPage(" . json_encode($this->json) . ");";
		}
	}

	public function FetchPageViews($page_id= 0, $enc_type= "")
	{		
		$SQL= "SELECT load_count FROM pages WHERE page_id= $page_id LIMIT 1;";
 
		$result= $this->DbConnect($SQL);

		if($row= $result->fetch_assoc())
		{
			$views= $row["load_count"];
		}

		if($enc_type== "json")
		{
			$this->json["views"]= $views;
			echo json_encode($this->json);
		}

		return true;
	}
}

class Type extends Brand
{
	function FetchAccountType($chapter_id= 0)
	{
		$SQL= "SELECT account_id 
				FROM types t
				LEFT JOIN chapters c ON c.account_id= t.account_id
				WHERE c.chapter_id= $chapter_id
				LIMIT 1;";

		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$type_id= $row['type_id'];
			$this->account_id= $type_id;
		}
		
		return $account_id;
	}
}

class Brand extends Tile
{
	function FetchAccountType($chapter_id= 0)
	{
		$SQL= "SELECT account_id 
				FROM types t
				LEFT JOIN chapters c ON c.account_id= t.account_id
				WHERE c.chapter_id= $chapter_id
				LIMIT 1;";

		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$type_id= $row['type_id'];
			$this->account_id= $type_id;
		}
		
		return $account_id;
	}
}

class Comment extends Page
{
	
	public $comment_data= "";

	public function FetchComments($article_id= 0, $enc_type= "")
	{
		$image_string= "";
		$SQL= "SELECT c.*, u.user_name, u.email, c.comment_email FROM comments c LEFT JOIN users u ON c.user_id= u.user_id WHERE article_id= '$article_id';";

		if($result= $this->DbConnect($SQL))
		{
			if($result->num_rows== 0)
			{
				$this->comment_data= "<div id='no_comment_message'>No comments yet.</div>";
			}
			else if($enc_type="json")
			{
				$this->comment_data= "<div id='comment_list' class='comment_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";

				$row= $result->fetch_assoc();
				if($row['comment_email']== '')
				{
					$row['comment_email']= 'anon@email.com';
				}
				if($row['comment_image']!= '')
				{
					//$image_string= "<div class='comment_image'><img src='$row[comment_image]' style='max-width: 370px;'/></div>";
				}
				$hash= md5($row['comment_email']);
				$json['first']= $row['comment_id'];
				$this->comment_data.= "<div id= 'comment_$row[comment_id]' class='comment_link' comment_id='$row[comment_id]'>
											<div class='comment_intro'>$row[comment_intro]</div>
											<div class='comment_text'>$row[comment_text]</div>
											<div class='comment_email'><img src='http://www.gravatar.com/avatar/$hash?r=pg&s=15' style='float: left; margin: 0 0 0 10px;'/><div class='comment_user'> $row[comment_email]</div></div>
											<div class='comment_time'>" . date('D M j G:i:s T Y', strtotime($row['timestamp'])) . "</div>
											$image_string
										</div>
										";

				while($row= $result->fetch_assoc())
				{
					if($row['comment_email']== '')
					{
						$row['comment_email']= 'anon@email.com';
					}
					if($row['comment_image']!= '')
					{
						//$image_string= "<div class='comment_image'><img src='$row[comment_image]' style='max-width: 370px;'/></div>";
					}					
					$hash= md5($row['comment_email']);
					
					$this->comment_data.= "<div id= 'comment_$row[comment_id]' class='comment_link' comment_id='$row[comment_id]'>
												<div class='comment_intro'>$row[comment_intro]</div>
												<div class='comment_text'>$row[comment_text]</div>
												$image_string
												<div class='comment_email'><img src='http://www.gravatar.com/avatar/$hash?r=pg&s=15' style='float: left; margin: 0 0 0 10px;'/> <div class='comment_user'> $row[email]</div></div>
												<div class='comment_time'>" . date('D M j G:i:s T Y', strtotime($row['timestamp'])) . "</div>
											</div>
											";
				}

				$this->comment_data.= "</div>";					
			}
			else
			{
				$this->comment_data= "<ul id='comment_list' class='comment_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";

				$row= $result->fetch_assoc();
				if($row['comment_email']== '')
				{
					$row['comment_email']= 'anon@email.com';
				}
				if($row['comment_image']!= '')
				{
					$image_string= "<div class='comment_image'><img src='$row[comment_image]' style='max-width: 370px;'/></div>";
				}
				$hash= md5($row['comment_email']);
				$json['first']= $row['comment_id'];
				$this->comment_data.= "<li id= 'comment_$row[comment_id]' class='comment_link' comment_id='$row[comment_id]'>
											<div class='comment_time'>" . date('D M j G:i:s T Y', strtotime($row['timestamp'])) . "</div>
											<div class='comment_intro'>$row[comment_intro]</div>
											<div class='comment_text'>$row[comment_text]</div>
											<div class='comment_email'>-- <img src='http://www.gravatar.com/avatar/$hash?r=pg&s=15'/> $row[comment_email]</div>
											$image_string
										";

				while($row= $result->fetch_assoc())
				{
					if($row['comment_email']== '')
					{
						$row['comment_email']= 'anon@email.com';
					}
					if($row['comment_image']!= '')
					{
						$image_string= "<div class='comment_image'><img src='$row[comment_image]' style='max-width: 370px;'/></div>";
					}					
					$hash= md5($row['comment_email']);
					
					$this->comment_data.= "<li id= 'comment_$row[comment_id]' class='comment_link' comment_id='$row[comment_id]'>
												<div class='comment_time'>" . date('D M j G:i:s T Y', strtotime($row['timestamp'])) . "</div>
												<div class='comment_intro'>$row[comment_intro]</div>
												<div class='comment_text'>$row[comment_text]</div>
												$image_string
												<div class='comment_email'>-- <img src='http://www.gravatar.com/avatar/$hash?r=pg&s=15'/> $row[comment_email]</div>
											";
				}

				$this->comment_data.= "</ul>";	
			}
		}

		if($enc_type== "json")
		{
			$json['SQL']= $SQL;
			$json['comment_data']= $this->comment_data;
			echo json_encode($json);
		}
		
		return $this->comment_data;
	}

	public function AddComment($article_id= 0, $comment_email= "", $comment_intro= "", $comment_text= "", $comment_image= "", $enc_type= "")
	{
	
		if($this->LoggedIn())
		{
			$user_id= $this->user_id;
		}
		else
		{
			$user_id= "";
			$user_id= $this->FetchField("user_id", "users", "email= $comment_email");
		}
		
		$json['posted']= false;

		$SQL= "INSERT INTO comments (article_id, user_id, comment_email, comment_intro, comment_text, comment_image) VALUES ('$article_id', '$user_id', '$comment_email', '$comment_intro', '$comment_text', '$comment_image');";

		if($this->DbConnect($SQL))
		{
			$json['posted']= true;
		}

		if($enc_type== "json")
		{
			$json['SQL']= $SQL;
			echo json_encode($json);
		}

		return $this->insert_id;		
	}
	
	function FetchCommentCount($article_id= 0, $enc_type= "")
	{
	/*
		$chapter_id= $this->FetchField("chapter_id", "articles", "article_id=$article_id");
		$logbook_id= $this->FetchField("logbook_id", "articles", "article_id=$article_id");
		$article_id= $this->FetchField("article_id", "articles", "article_id=$article_id");
		
		$chapter_name= $this->FetchName($chapter_id, "chapter");
		$logbook_name= $this->FetchName($logbook_id, "logbook");
		$article_name= $this->FetchName($article_id, "article");
	
		$fb_comment_count = 0;
		$cb_comment_count = 0;
		
		$facebook = new Facebook(array(
			'appId'  => '410610009004609',
			'secret' => 'ab1997d0b8d828b1deac893691dfc470',
		));

		$fql = "SELECT url, share_count, like_count, comment_count, click_count, total_count 
				FROM link_stat WHERE url='http://www.cerebrit.com'";

		$result = $facebook->api(array(
			'method' => 'fql.query',
			'query' => $fql,
		));
		
		$fb_comment_count= $result[0]['comment_count'];
	*/	
		$SQL= "SELECT count(*) as comment_count FROM comments WHERE article_id= $article_id;";

		$result= $this->DbConnect($SQL);

		if($row= $result->fetch_assoc())
		{
			$cb_comment_count= $row["comment_count"];
		}
	
		if($enc_type== "json")
		{
			$this->json["comment_count"]= $cb_comment_count;
			echo json_encode($this->json);
		}
	
		return $cb_comment_count;
	}
}

class Article extends Comment
{
	public $article_name= "";
	public $pages= array();
	public $article_order= 0;

	public function FetchPage($article_id= 0, $page_id= 0, $direction="", $enc_type= "")
	{
		if($direction== "next" || $direction== "prev")
		{
			$current_page_index= 0;
			$this->FetchPages($article_id);

			foreach($this->pages as $index=>$page)
			{
				if($page['page_id']== $page_id)
				{
					$current_page_index= $index;
				}
			}			
			
			if($direction== "next")
			{
				$next_page_index= $current_page_index+1;
				if($next_page_index >= count($this->pages))
				{
					$next_page_index= 0;
				}
				$nextpage= $this->pages[$next_page_index]['page_id'];			
				$page_id= $nextpage;
			}
			else if($direction== "prev")
			{
				$prev_page_index= $current_page_index-1;
				if($prev_page_index < 0)
				{
					$prev_page_index= count($this->pages) - 1;
				}
				$prevpage= $this->pages[$prev_page_index]['page_id'];			
				$page_id= $prevpage;
			}
		}
		
		$this->page_name= $this->FetchName($page_id, "page");
		$created_by= $this->FetchField("created_by", "pages", "page_id= $page_id");
		$created_on= $this->FetchField("last_modified", "pages", "page_id= $page_id");
		//$this->account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");
		$this->page_name= $this->FetchName($page_id, "page");
		$this->article_name= $this->FetchName($article_id, "article");
		$this->page_data= $this->FetchContent($page_id, 200, 0);

		$this->page_id= $page_id;

		if($this->LoggedIn())
		{
			if($this->authorized)
			{ 
				/*$this->page_data.= "<script type='text/javascript' src='/js/user.js'></script>";*/
			}
		}
		else
		{
			//$this->page_data.= $this->FetchSocialMedia($created_by, strtotime($created_on));
			$this->UpdateTable("pages", "page_id", $this->page_id, "load_count", "load_count+1", true);
		}

		if($enc_type== "json")
		{
			$this->json['page_data']= $this->page_data; 
			$this->json['page_id']= $this->page_id; 
			$this->json['page_name']= $this->page_name;	
			$this->json['article_name']= $this->article_name;	
			$this->json['account_id']= $this->account_id;	
			echo json_encode($this->json);
		}
		else if($enc_type== "render")
		{
			echo $this->page_data;
		}
		else if($enc_type== "callback")
		{
			$this->json["page_data"]= $this->page_data;
			$this->json['page_id']= $this->page_id; 
			$this->json['page_name']= $this->page_name;	
			$this->json['article_name']= $this->article_name;	
			$this->json['account_id']= $this->account_id;
			$this->json['tile_count']= $this->tile_count;	
			echo "DisplayPage(" . json_encode($this->json) . ");";
		}

		return $this->page_data;
	}

	public function FetchPages($article_id= 0)
	{
		$pages= array();
		$SQL= "SELECT page_id, page_name FROM pages WHERE article_id= $article_id ORDER BY page_order ASC";
		$result= $this->DbConnect($SQL);
		while($row= $result->fetch_assoc())
		{
			$pages[]= $row;
			$this->pages= $pages;
		}
		$this->page_count= count($this->pages);
	
		return $this->pages;
	}
	
	public function CreateArticle($chapter_id= 0, $logbook_id= 0, $title= "", $enc_type= "")
	{
		$user_id= 0;
		if($this->LoggedIn())
		{
			$user_id= $_SESSION['user_id'];
		}
		if($title== "")
		{
			$title= "empty";
		}

		$article_order= $this->FetchArticleOrder($logbook_id);

		$SQL= "INSERT INTO articles (created_by, article_name, article_order, created_on, last_modified, timestamp) VALUES('$user_id', '$title', '$article_order', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP());";

		$result= $this->DbConnect($SQL);
		$this->article_id= $this->insert_id;
		$this->InsertCatalog($this->article_id, $chapter_id, $logbook_id); 
		
		$this->CreatePage($this->article_id, $title);
		$this->UpdateTable("logbooks", "logbook_id", $this->logbook_id, "last_modified", "CURRENT_TIMESTAMP()", true); 		
		
		if($enc_type== "json")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$this->json["article_id"]= $this->article_id;
			$this->json["page_id"]= $this->page_id;
			$this->json["title"]= $title;
			echo json_encode($this->json);
		}

		if($enc_type== "callback")
		{
			$this->json["article_id"]= $this->article_id;
			$this->json["page_id"]= $this->page_id;
			$this->json["title"]= $title;	
			$this->json["articles"]= $this->rows;
			echo "AppendArticle(" . json_encode($this->json) . ");";
		}

		return $this->article_id;
	}

	public function FetchArticleOrder($logbook_id= 0, $enc_type= "")
	{
		$article_order= 0;

		$SQL= "SELECT MAX(article_order) as article_order 
				FROM articles a
				LEFT JOIN catalog c ON a.article_id= c.article_id
				WHERE c.logbook_id= $logbook_id;";

		$result= $this->DbConnect($SQL);

		if($row= $result->fetch_assoc())
		{
			$article_order= $row['article_order'];
			$this->article_order= $article_order + 1;
			
		}
		
		if($enc_type== "json")
		{
			$this->json["order"]= $this->article_order;
			echo json_encode($this->json);
		}

		if($enc_type== "callback")
		{
			$this->json["order"]= $this->article_order;
			echo json_encode($this->json);			
		}
		
		return $this->article_order;
	}
	
	public function DeleteArticle($article_id, $title= "", $enc_type="")
	{
		$user_id= 0;
		if($this->LoggedIn())
		{
			$user_id= $_SESSION['user_id'];	
		}

		$article_account_id= $this->FetchField("account_id", "articles", "article_id= $article_id");

		if($this->account_id >= $article_account_id)
		{
			$SQL= "DELETE FROM articles WHERE created_by= '$user_id' AND article_name= '$title' AND article_id= '$article_id';";
			$this->DbConnect($SQL);
			$this->json["articles"]= $this->rows;
			$this->rows= 0;
			
			$SQL= "DELETE FROM pages WHERE created_by= '$user_id' AND article_id= '$article_id'";
			$this->DbConnect($SQL);
			$this->json["pages"]= $this->rows;
			$this->rows= 0;

			$SQL= "DELETE FROM tiles WHERE created_by= '$user_id' AND article_id= '$article_id';";
			$this->DbConnect($SQL);
			$this->json["tiles"]= $this->rows;
		}

		if($enc_type== "callback")
		{
			echo "DisplayDeletedArticle(" . json_encode($this->json) . ");";
		}

		return true;
	}

	public function ChangeArticle($page_id= 0, $article_id= 0, $enc_type)
	{
		$user_id= 0;
		if($this->LoggedIn())
		{
			$user_id= $_SESSION['user_id'];	
		}

		$article_account_id= $this->FetchField("account_id", "articles", "article_id= $article_id");

		$pages= 0;
		$tiles= 0;

		if($this->account_id >= $article_account_id)
		{
			$SQL= "UPDATE pages SET article_id= '$article_id', last_modified= CURRENT_TIMESTAMP() WHERE page_id= '$page_id';";
			$this->DbConnect($SQL);
			$pages= $this->rows;
			$this->rows= 0;
			
			$SQL= "UPDATE tiles SET article_id= '$article_id' WHERE page_id= '$page_id';";
			$this->DbConnect($SQL);
			$tiles= $this->rows;
			$this->rows= 0;
			
			$this->UpdateTable("articles", "article_id", $article_id, "last_modified", "CURRENT_TIMESTAMP()", true);
		}

		if($enc_type== "callback")
		{
			$this->json["pages"]= $pages;
			$this->json["tiles"]= $tiles;
			echo "DisplayChangeArticle(" . json_encode($this->json) . ");";
		}

		return true;
	}
	
	public function SaveArticle($article_id= 0, $page_id= 0, $title= "", $enc_type="")
	{
		$article_account_id= $this->FetchField("account_id", "articles", "article_id= $article_id");
		$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");

		if($this->LoggedIn())
		{
			if($article_id!= 0 && ($this->account_id >= $article_account_id))
			{
				if($page_id!= 0 && ($this->account_id >= $page_account_id))
				{
					$SQL= "UPDATE pages SET page_name= '$title', last_modified= CURRENT_TIMESTAMP() WHERE page_id= '$page_id';";	
					$info= "1 page saved.";
				}
				else
				{
					$SQL= "UPDATE articles SET article_name= '$title', last_modified= CURRENT_TIMESTAMP() WHERE article_id= '$article_id';";	
					$info= "1 article saved.";
				}
				$this->DbConnect($SQL);				
			}
		}
		else if($article_account_id== 1)
		{
			if($page_account_id== 1)
			{
				$SQL= "UPDATE pages SET page_name= '$title', last_modified= CURRENT_TIMESTAMP() WHERE page_id= '$page_id';";	
				$info= "1 page saved.";
			}
			else
			{
				$SQL= "UPDATE articles SET article_name= '$title', last_modified= CURRENT_TIMESTAMP() WHERE article_id= '$article_id';";	
				$info= "1 article saved.";
			}
			$this->DbConnect($SQL);
		}
		else
		{
			$info= "Insufficient priveleges.";
		}
		
		if($enc_type=="json")
		{
			$this->json["insert_id"]= $this->insert_id;
			echo json_encode($this->json);
		}
		if($enc_type=="callback")
		{
			$this->json["insert_id"]= $this->insert_id;
			$this->json["info"]= $info;
			echo "DisplaySaveArticle(" . json_encode($this->json) . ");";
		}		
		
		return $this->insert_id;
	}
	
	public function SaveArticleOrder($article_order= array(), $page_order= array(), $enc_type="")
	{
		$a_count= 0;
		$p_count= 0;
		if($article_order== "")
		{
			$article_order= array();
		}
		if($page_order== "")
		{
			$page_order= array();
		}
		if($this->LoggedIn())
		{	
			foreach($article_order as $order=>$article)
			{
				$article_id= substr($article, 8);
				$article_account_id= $this->FetchField("account_id", "articles", "article_id= $article_id");
				$order= $order + 1;
				if($article_id!= 0 && ($this->account_id >= $article_account_id))
				{
					$SQL= "UPDATE articles SET article_order= '$order' WHERE article_id= '$article_id';";
					$this->DbConnect($SQL);
					$a_count++;
				}
			}
			foreach($page_order as $order=>$page)
			{
				$page_id= substr($page, 5);
				$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");
				$order= $order + 1;
				if($page_id!= 0 && ($this->account_id >= $page_account_id))
				{
					$SQL= "UPDATE pages SET page_order= '$order' WHERE page_id= '$page_id';";
					$this->DbConnect($SQL);	
					$p_count++;
				}
			}
		}
		else
		{
			foreach($article_order as $order=>$article)
			{
				$article_id= substr($article, 8);
				$article_account_id= $this->FetchField("account_id", "articles", "article_id= $article_id");
				$order= $order + 1;
				if($article_account_id== 1)
				{
					$SQL= "UPDATE articles SET article_order= '$order' WHERE article_id= '$article_id';";
					$this->DbConnect($SQL);
					$a_count++;
				}
			}
			foreach($page_order as $order=>$page)
			{
				$page_id= substr($page, 5);
				$page_account_id= $this->FetchField("account_id", "pages", "page_id= $page_id");
				$order= $order + 1;
				if($page_account_id== 1)
				{
					$SQL= "UPDATE pages SET page_order= '$order' WHERE page_id= '$page_id';";
					$this->DbConnect($SQL);	
					$p_count++;
				}
			}			
		}

		if($enc_type=="callback")
		{
			$this->json["a_count"]= $a_count;
			$this->json["p_count"]= $p_count;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			echo json_encode($this->json);			
		}
	}
	
	public function FetchPageList($article_id= 0, $enc_type= "")
	{
		$SQL= "SELECT * FROM pages WHERE article_id= '$article_id' ORDER BY page_order ASC;";

		if($result= $this->DbConnect($SQL))
		{
			$json['first']= 0;
			$list_data= "<ul id='page_list' class='logbook_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";
			$count= 0;
			if($row= $result->fetch_assoc()) {
				$json['first']= $row['page_id'];
				$list_data.= "<li id= 'page_$row[page_id]' class='page_link' page_id='$row[page_id]'><span>$row[page_name]</span></option>";
				$count++;
				while($row= $result->fetch_assoc())
				{
					$list_data.= "<li id= 'page_$row[page_id]' class='page_link' page_id='$row[page_id]'><span>$row[page_name]</span></option>";
					$count++;
				}
			}

			$list_data.= "</ul>";
		}

		if($enc_type== "json")
		{
			$json['list_data']= $list_data;
			echo json_encode($json);
		}

		if($enc_type== "callback")
		{
			$json['list_data']= $list_data;
			$json['count']= $count;
			echo "DisplayPageList(" . json_encode($json) . ");";
		}		
		
		return $list_data;
	}
	
	public function FetchMobileArticleList($logbook_id= 0, $enc_type= "")
	{
		$account_id= 1;
		$user_id= 0;
		
		if($this->LoggedIn())
		{
			$account_id= $this->account_id;
			$user_id= $this->user_id;
		}
		
		$SQL= "SELECT * FROM articles a LEFT JOIN catalog c ON a.article_id= c.article_id WHERE c.logbook_id= '$logbook_id' WHERE a.account_id<= $account_id OR a.created_by= $user_id ORDER BY a.article_order ASC ;";

		if($result= $this->DbConnect($SQL))
		{
			$list_data= "<ul id='page_list' class='mobile_page_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";

			$row= $result->fetch_assoc();
			$json['first']= $row['page_id'];
			$list_data.= "<li id= 'article_$row[article_id]' class='article_link' article_id='$row[article_id]'><span>$row[article_name]</span></option>";

			while($row= $result->fetch_assoc())
			{
				$list_data.= "<li id= 'article_$row[article_id]' class='article_link' article_id='$row[article_id]'><span>$row[article_name]</span></option>";
			}

			$list_data.= "</ul>";
		}

		if($enc_type== "json")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");	
			$json['list_data']= $list_data;
			echo json_encode($json);
		}
		
		return $list_data;
	}	
	
	public function FetchPopularPages($logbook_id= 0, $limit= "6", $enc_type= "")
	{
		$metric_count= array();

		if($logbook_id== 0)
		{
			$logbook_string= "AND a.logbook_id<> 0";
		}
		else
		{
			$logbook_string= "AND a.logbook_id= $logbook_id";
		}
		
		$SQL= "select a.article_name, l.user_name, a.load_count 
				from articles a 
				left join logbooks l on a.logbook_id= l.logbook_id
				$logbook_string
				order by a.load_count 
				desc limit $limit";
 
		$result= $this->DbConnect($SQL);

		while($row= $result->fetch_assoc())
		{
			$popular[]= $row;
		}

		if($enc_type== "json")
		{
			$this->json["popular"]= $this->popular;
			echo json_encode($this->json);
		}

		return true;
	}

	public function FetchStats($chapter_id= 0, $logbook_id= 0, $article_id= 0, $enc_type= "")
	{
		$article_string= "p.article_id= $article_id";
		if($chapter_id== 0)
		{
			$chapter_string= "c.chapter_id<> 0";
		}
		else
		{
			$chapter_string= "c.chapter_id= $chapter_id";
		}
		if($logbook_id== 0)
		{
			$logbook_string= "c.logbook_id<> 0";
		}
		else
		{
			$logbook_string= "c.logbook_id= $logbook_id";
		}
		if($article_id== 0)
		{
			$article_string= "a.article_id<> 0";
		}
		else
		{
			$article_string= "a.article_id= $article_id";
		}
		
		$SQL= "SELECT sum(t.edits) AS edit_count, 
				count(t.tile_content) AS tile_count, 
				count(distinct t.page_id) AS page_count 
				FROM tiles t
				LEFT JOIN pages p ON p.page_id= t.page_id
				LEFT JOIN articles a ON a.article_id= p.article_id
				LEFT JOIN catalog c ON c.article_id= a.article_id
				WHERE $article_string
				AND $logbook_string
				AND $chapter_string;";
		//echo $SQL;		
		$result= $this->DbConnect($SQL);

		if($row= $result->fetch_assoc())
		{
			$edit_count= $row['edit_count'];
			$tile_count= $row['tile_count'];
			$page_count= $row['page_count'];
			$page_timeline= $this->FetchPageMetric("pages", $chapter_id, $logbook_id, $article_id);
			$tile_timeline= $this->FetchMetric("tiles", $chapter_id, $logbook_id, $article_id);
		}
		if($edit_count== null)
		{
			$edit_count= 0;
		}
		
		if($enc_type== "json")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			$json["edit_count"]= $edit_count;
			$json["tile_count"]= $tile_count;
			$json["page_count"]= $page_count;
			$json["tile_timeline"]= $tile_timeline;
			$json["page_timeline"]= $page_timeline;

			echo json_encode($json);
		}
		
		if($enc_type== "callback")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$json["edit_count"]= $edit_count;
			$json["tile_count"]= $tile_count;
			$json["page_count"]= $page_count;
			$json["tile_timeline"]= $tile_timeline;
			$json["page_timeline"]= $page_timeline;

			echo "DisplayStats(" . json_encode($json) . ");";			
		}
 
		return true;
	}

	public function FetchMetric($metric= "", $chapter_id= 0, $logbook_id= 0, $article_id= 0, $enc_type= "")
	{
		$timeline= array();

		if($chapter_id== 0)
		{
			$chapter_string= "c.chapter_id<> 0";
		}
		else
		{
			$chapter_string= "c.chapter_id= $chapter_id";
		}
		if($logbook_id== 0)
		{
			$logbook_string= "c.logbook_id<> 0";
		}
		else
		{
			$logbook_string= "c.logbook_id= $logbook_id";
		}
		if($article_id== 0)
		{
			$article_string= "p.article_id<> 0";
		}
		else
		{
			$article_string= "p.article_id= $article_id";	
		}

		if($metric== 'tiles')
		{
			$count_string= "DATE_FORMAT(t.last_modified, '%Y %j %H')";
			$group_string= "GROUP BY DATE_FORMAT(t.last_modified, '%Y %j %H')";
		}
		else if($metric== 'pages')
		{
			$count_string= "DATE_FORMAT(p.last_modified, '%Y %j %H')";
			$group_string= "GROUP BY DATE_FORMAT(p.last_modified, '%Y %j %H')";
		}
		else if($metric== 'users')
		{
			$count_string= "DATE_FORMAT(u.last_modified, '%Y %j %H')";
			$group_string= "GROUP BY u.last_modified_by";
		}

		$SQL= "SELECT count($count_string) as count, t.last_modified as last_modified 
				FROM tiles t
				LEFT JOIN pages p ON t.page_id= p.page_id
				LEFT JOIN articles a ON p.article_id= a.article_id
				LEFT JOIN catalog c ON a.article_id= c.article_id
				WHERE $logbook_string 
				AND $article_string
				AND $chapter_string
				$group_string
				ORDER BY $count_string ASC";
				
		$result= $this->DbConnect($SQL);

		$last_modified= "never";
		while($row= $result->fetch_assoc())
		{
			$timeline[]= $row['count'];
			$last_modified= $row['last_modified'];
		}

		if($enc_type== "json")
		{
			$json["sql"]= $SQL;
			$json["timeline"]= $timeline;
			$json["last_modified"]= $last_modified;
			$json["metric"]= $metric;
			echo json_encode($json);
		}

		return $timeline;
	}

	public function FetchPageMetric($metric= "", $chapter_id= 0, $logbook_id= 0, $article_id= 0, $enc_type= "")
	{
		$timeline= array();

		if($chapter_id== 0)
		{
			$chapter_string= "c.chapter_id<> 0";
		}
		else
		{
			$chapter_string= "c.chapter_id= $chapter_id";
		}
		if($logbook_id== 0)
		{
			$logbook_string= "c.logbook_id<> 0";
		}
		else
		{
			$logbook_string= "c.logbook_id= $logbook_id";
		}
		if($article_id== 0)
		{
			$article_string= "p.article_id<> 0";
		}
		else
		{
			$article_string= "p.article_id= $article_id";	
		}

		if($metric== 'tiles')
		{
			$count_string= "DATE_FORMAT(t.last_modified, '%Y %j %H')";
			$group_string= "GROUP BY DATE_FORMAT(t.last_modified, '%Y %j %H')";
		}
		else if($metric== 'pages')
		{
			$count_string= "DATE_FORMAT(p.last_modified, '%Y %j %H')";
			$group_string= "GROUP BY DATE_FORMAT(p.last_modified, '%Y %j %H')";
		}
		else if($metric== 'users')
		{
			$count_string= "DATE_FORMAT(u.last_modified, '%Y %j %H')";
			$group_string= "GROUP BY t.last_modified_by";
		}

		$SQL= "SELECT count($count_string) as count, DATE_FORMAT(p.last_modified, '%Y %j %H') as last_modified 
				FROM pages p
				LEFT JOIN articles a ON p.article_id= a.article_id
				LEFT JOIN catalog c ON a.article_id= c.article_id
				WHERE $logbook_string 
				AND $article_string
				AND $chapter_string
				$group_string
				ORDER BY $count_string ASC";
				
		$result= $this->DbConnect($SQL);

		$last_modified= "never";
		while($row= $result->fetch_assoc())
		{
			$timeline[]= $row['count'];
			$last_modified= $row['last_modified'];
		}

		if($enc_type== "json")
		{
			$json["sql"]= $SQL;
			$json["timeline"]= $timeline;
			$json["last_modified"]= $last_modified;
			$json["metric"]= $metric;
			echo json_encode($json);
		}

		return $timeline;
	}

	public function FetchInfo($article_id= 0, $page_id= 0, $enc_type= "")
	{
		$contributors= $this->FetchContributors($article_id);
		$c_string= "";
		foreach($contributors as $c)
		{
			$c_string.= "<a href='http://wiki.user.cerebrit.com/$c'>$c</a>, "; 
		}
		
		$c_string= substr_replace($c_string , "", -2);
		
		$page_loads= $this->FetchPageLoads($article_id);
		$comment_count= $this->FetchCommentCount($article_id);
		
		$page_string= "";
		$page_count= 1;
		$this->FetchPages($article_id);
		foreach($this->pages as $page)
		{
			$page_string.= "<a href='http://wiki.cerebrit.com/'";
		}
	
		$SQL= "SELECT a.article_name, u.user_name, acc.account_name, a.last_modified 
				FROM articles a
				LEFT JOIN users u ON a.created_by= u.user_id
				LEFT JOIN accounts acc ON a.account_id= acc.account_id
				WHERE a.article_id= $article_id;";
		
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$article_name= $row['article_name'];
			$user_name= $row['user_name'];
			$account_name= $row['account_name'];
			$last_modified= date('n/j/y g:i A', (strtotime($row['last_modified']) - 28800));
		}		

		$page_index= 1;
		$page_count= count($this->pages);
		$this->FetchPages($article_id);
		if($page_count > 6)
		{
			
		}
		
		if($page_count > 0)
		{
			if($this->pages[0]['page_id']== $page_id)
			{
				$page_string= "<ul><li class='page_left_arrow disabled'><a id='prev_page_link' href='javascript:'>&laquo;</a></li>";
			}
		}
		else
		{
			$page_string= "<ul><li class='page_left_arrow'><a id='prev_page_link' href='javascript:'>&laquo;</a></li>";
		}

		foreach($this->pages as $page)
		{
			if($page_id== $page['page_id'])
			{
				$page_string.= "<li class='pages_link disabled active'><a class='pagination_page_link' href='javascript:' page_id= $page[page_id]>$page_index</a></li>";
			}
			else
			{
				$page_string.= "<li class='pages_link'><a class='pagination_page_link' href='javascript:' page_id= $page[page_id]>$page_index</a></li>";
			}
			
			$page_index++;
		}
		
		if($page_count > 0)
		{
			if($this->pages[$page_count-1]['page_id']== $page_id)
			{
				$page_string.= "<li class='page_right_arrow disabled'><a id='next_page_link' href='javascript:'>&raquo;</a></li></ul>";
			}
		}
		else
		{
			$page_string.= "<li class='page_right_arrow'><a id='next_page_link' href='javascript:'>&raquo;</a></li></ul>";
		}
		
		if($enc_type== "json")
		{
			$this->json['article_name']= $article_name;
			$this->json['user_name']= $user_name;
			$this->json['account_name']= $account_name;
			$this->json['last_modified']= $last_modified;
			$this->json['contributors']= $c_string;
			$this->json['page_loads']= $page_loads;
			$this->json['comment_count']= $comment_count;
			$this->json['page']= $page_string;
			echo json_encode($this->json);
		}
	}
	
	public function FetchContributors($article_id= 0, $enc_type= "")
	{
		$SQL= "	SELECT DISTINCT u.user_name as created_by, us.user_name as last_modified 
				FROM tiles t 
				LEFT JOIN pages p on t.page_id= p.page_id 
				LEFT JOIN articles a on p.page_id= a.article_id
				LEFT JOIN users u on t.created_by= u.user_id
				LEFT JOIN users us on t.last_modified_by= us.user_id
				WHERE p.article_id= $article_id";
				
		$result= $this->DbConnect($SQL);

		$contributors= array();
		while($row= $result->fetch_assoc())
		{
			$contributors[]= $row['created_by'];
			$contributors[]= $row['last_modified'];
		}
		
		return array_unique($contributors);
	}
	
	public function FetchPageLoads($article_id=0, $enc_type= "")
	{
		$SQL= "SELECT SUM(load_count) as load_count FROM pages WHERE article_id= $article_id;";
		
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$loads= $row['load_count'];
		}
		
		return $loads;
	}

	public function FetchZip($page_id= 0, $enc_type="") 
	{

		// Prepare File
		$file = tempnam("zip", "zip");
		$zip = new ZipArchive();
		$zip->open($file, ZipArchive::OVERWRITE);

		// Stuff with content
		$zip->addFromString('page.html',"123");
		//$zip->addFile('file_on_server.ext', 'second_file_name_within_archive.ext');

		// Close and send to users
		$zip->close();

		if($enc_type== "json") {
			$this->json["file"]= readfile($file);
			$this->json["page_id"]= $this->page_id;
			$this->json["enc_type"]= "Zip";
			$this->json["filename"]= $file;
		}
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($file));
		header('Content-Disposition: attachment; filename="file.zip"');			
		readfile($file);
		unlink($file); 
			//echo json_encode($this->json);
		//}

		return true;
	}
	
}



class Logbook extends Article
{
	public $logbook_name= "";
	public $articles= array();

	public function FetchArticle($logbook_id= 0, $article_id= 0, $page_index= 0, $direction= "", $enc_type= "")
	{
		if($direction!= "")
		{
			$this->FetchArticles($this->chapter_id, $logbook_id);
			$current_article_index= 0;
		}
		$current_article_index= "";
		if($this->articles)
		{
			if($direction== "next")
			{
				foreach($this->articles as $index=>$article)
				{
					if($article['article_id']== $article_id)
					{
						$current_article_index= $index;
					}
				}
				$next_article_index= $current_article_index+1;
				if($next_article_index >= count($this->articles))
				{
					$next_article_index= 0;
				}

				$article_id= $this->articles[$next_article_index]['article_id'];
				$current_article_index= $next_article_index;
			}
			else if($direction== "prev")
			{
				foreach($this->articles as $index=>$article)
				{
					if($article['article_id']== $article_id)
					{
						$current_article_index= $index;
					}
				}
				$prev_article_index= $current_article_index-1;
				if($prev_article_index < 0)
				{
					$prev_article_index= count($this->articles) - 1;
				}
				$article_id= $this->articles[$prev_article_index]['article_id'];
				$current_article_index= $prev_article_index;
			}
		}
		
		if($article_id== 0)
		{
			if($this->chapter_id== 0)
			{
				$chapter_string= "c.chapter_id<> 0";
			}
			else
			{
				$chapter_string= "c.chapter_id= $this->chapter_id";
			}
			if($logbook_id== 0)
			{
				$article_id= $this->FetchRow("a.article_id", "articles a LEFT JOIN catalog c ON c.article_id= a.article_id", "c.logbook_id<>0 AND $chapter_string ORDER BY a.article_order ASC");
				$article_id= $article_id['article_id'];
			}
			else
			{
				$article_id= $this->FetchRow("a.article_id", "articles a LEFT JOIN catalog c ON c.article_id= a.article_id", "c.logbook_id= $logbook_id AND $chapter_string ORDER BY a.article_order ASC");
				if($article_id)
				{
					$article_id= $article_id['article_id'];
				}
				else
				{
					$article_id= 1;
				}
			}
		}
		$this->article_id= $article_id;
		$this->article_name= $this->FetchName($article_id, "article");
		$this->logbook_name= $this->FetchName($logbook_id, "logbook");
		$this->account_id= $this->FetchField("account_id", "articles", "article_id= $article_id");
		
		$pages= $this->FetchPages($article_id);
		if($page_index== 0)
		{
			$this->page_id= $pages[0]['page_id'];
			foreach($pages as $page)
			{
				//$this->page_data.= "<div class=\"ebook_page\">";
				$this->page_data.= $this->FetchContent($page['page_id'], 20, 0, $enc_type);

				//$this->page_data.= "</div>";
			}
		}
		else
		{
			if(isset($pages[$page_index-1]))
			{
				$this->page_id= $pages[$page_index-1]['page_id'];
				$this->UpdateTable("articles", "article_id", $this->article_id, "load_count", "load_count+1", true);
				$this->page_data= $this->FetchPage($this->article_id, $this->page_id, "");	
			}
			else
			{
				$this->UpdateTable("articles", "article_id", $this->article_id, "load_count", "load_count+1", true);
				$this->page_data= "";	
			}
		}

		if($enc_type== 'json')
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");			
			$this->json['article_id']= $this->article_id;
			$this->json['logbook_id']= $this->logbook_id;
			$this->json['account_id']= $this->account_id;
			$this->json['chapter_id']= $this->chapter_id;
			$this->json['article_name']= $this->article_name;
			$this->json['page_id']= $this->page_id;
			$this->json['page_name']= $this->page_name;
			$this->json['page_data']= $this->page_data;
			$this->json["composite"]= $this->composite_content;			
			$this->json['article_index']= $current_article_index;
			
			echo json_encode($this->json);			
		}
		else if($enc_type== 'mobile')
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$this->json['article_id']= $this->article_id;
			$this->json['logbook_id']= $this->logbook_id;
			$this->json['account_id']= $this->account_id;
			$this->json['chapter_id']= $this->chapter_id;
			$this->json['article_name']= $this->article_name;
			$this->json['page_id']= $this->page_id;
			$this->json['page_name']= $this->page_name;
			$this->json['page_data']= $this->page_data;
			$this->json["composite_data"]= $this->composite_content;	
			$this->json['article_index']= $current_article_index;
			echo json_encode($this->json);
		}
		else if($enc_type== 'callback')
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");						
			$this->json['article_id']= $this->article_id;
			$this->json['article_name']= $this->article_name;
			$this->json['page_id']= $this->page_id;	
			$this->json['page_name']= $this->page_name;		
			$this->json['page_count']= $this->page_count;
			echo "DisplayArticle(" . json_encode($this->json) . ")";
		}
		return $this->page_id;
	}

	public function FetchArticles($chapter_id= 0, $logbook_id= 0)
	{
		if($logbook_id== 0)
		{
			$logbook_string= "c.logbook_id<= '4'";
		}
		else
		{
			$logbook_string= "c.logbook_id<= $logbook_id";
		}
		if($chapter_id== 0)
		{
			$chapter_string= "c.chapter_id= '16'";
		}
		else
		{
			$chapter_string= "c.chapter_id= $chapter_id";
		}
		$articles= array();
		$SQL= "SELECT a.article_id, a.article_name 
				FROM articles a 
				LEFT JOIN catalog c ON a.article_id= c.article_id
				WHERE $chapter_string
				AND $logbook_string
				GROUP BY a.article_id
				ORDER BY article_order ASC";

		$result= $this->DbConnect($SQL);
		while($row= $result->fetch_assoc())
		{
			$articles[]= $row;
		}
		
		$this->articles= $articles;

		return $articles;
	}
	
	public function FetchArticleList($chapter_id= 0, $logbook_id= 0, $enc_type= "")
	{
		if($logbook_id== 0)
		{
			$logbook_string= "AND c.logbook_id<= 20";
		}
		else
		{
			$logbook_string= "AND c.logbook_id<= $logbook_id";
		}
		
		if($this->LoggedIn())
		{
			$user_id= $this->user_id;
			$account_id= $this->account_id;
		}
		else if($this->FBLoggedIn())
		{
			$user_id= $this->user_id;
			$account_id= $this->account_id;
		}
		else
		{
			$user_id= 0;
			$account_id= 2;
		}
		if($user_id > 0)
		{
			$user_string= "OR a.created_by= " . $user_id;
		}
		else
		{
			$user_string= "";
		}
		if($chapter_id== 0)
		{
			$chapter_string= "c.chapter_id<> 0";
		}
		else
		{
			$chapter_string= "c.chapter_id= $chapter_id";
		}

		$SQL= "SELECT a.* 
				FROM articles a 
				LEFT JOIN catalog c ON a.article_id= c.article_id
				LEFT JOIN logbooks l ON c.logbook_id= l.logbook_id
				WHERE ($chapter_string
				$logbook_string)
				$user_string
				GROUP BY a.article_id
				ORDER BY article_order ASC;";
				
				//echo $SQL;

		$result= $this->DbConnect($SQL);

		if($enc_type== "mobile")
		{
			$list_data= "<select onchange='LoadMobileArticle(\"\");' id='article_list' class='logbook_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";
		}
		else
		{
			$list_data= "<ul id='article_list' class='logbook_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";
		}

		//$row= $result->fetch_assoc();
		//$json['first']= $row['article_id'];
		if($enc_type== "mobile")
		{		
			$list_data.= "<option id='wiki_select_option' article_id='2'class='article_link'><span>Article Select</span></option>";
			$list_data.= "<option id='' article_id='2' class='article_link'></option>";
		}
		$count= 0;
		while($row= $result->fetch_assoc())
		{
			if($enc_type== "mobile")
			{
				$list_data.= "<option id= 'article_$row[article_id]' class='article_link' article_id='$row[article_id]'><span>$row[article_name]</span></option>";
			}
			else
			{
				$list_data.= "<li id= 'article_$row[article_id]' class='article_link' article_id='$row[article_id]'><span>$row[article_name]</span></li>";
			}
			$count++;
		}

		if($enc_type== "mobile")
		{ 
			$list_data.= "<option id='' article_id='2' class='article_link'></option>";
			$list_data.= "<option id='mobile_add_link' class='article_link' article_id='1'>.add</option>";
			$list_data.= "<option id='mobile_feed_link' class='article_link' article_id='3'>.feed</option>";
			$list_data.= "<option id='mobile_most_link' class='article_link' article_id='4'>.edits</option>";
			$list_data.= "<option id='mobile_reset_link' class='article_link' article_id='2'>.reset</option>";
			$list_data.= "</select>";
		}
		else
		{
			$list_data.= "</ul>";
		}

		if($enc_type== "json")
		{		
			$json['list_data']= $list_data;
			$json['sql']= $SQL;
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			echo json_encode($json);
		}

		if($enc_type== "callback")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");				
			$this->json['list_data']= $list_data;
			$this->json['count']= $count;
			echo "DisplayArticleList(" . json_encode($this->json) . ");";
		}

		
		if($enc_type== "mobile")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");						
			$json['list_data']= $list_data;
			$json['sql']= $SQL;
			echo "DisplayArticleList(" . json_encode($json) . ");";
		}
		
		
		
		return $list_data;
	}
	
	public function FetchRandomArticle($logbook_id= 0, $enc_type= "")
	{
		$SQL= "SELECT article_id, article_name FROM articles WHERE logbook_id= '$logbook_id' ORDER BY RAND() LIMIT 1";
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$article_id= $row['article_id'];
			$article_name= $row['article_name'];
			$this->article_id= $article_id;
			$this->article_name= $article_name;
		}
		if($enc_type== "json")
		{
			$json["article_id"]= $this->article_id;
			$json["article_name"]= $this->article_name;		
			echo json_encode($json);
		}
			
		return $this->article_id;
	}

	public function FetchFeatureArticle($chapter_id= 0, $enc_type= "")
	{
		$SQL= "SELECT a.article_id, a.article_name FROM articles a 
				LEFT JOIN logbooks l ON a.logbook_id= l.logbook_id 
				WHERE l.chapter_id= '$chapter_id'
				ORDER BY a.article_id LIMIT 1";

		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$article_id= $row['article_id'];
			$article_name= $row['article_name'];
			$this->article_id= $article_id;
			$this->article_name= $article_name;
		}
		if($enc_type== "json")
		{
			$json["article_id"]= $this->article_id;
			$json["article_name"]= $this->article_name;
			echo json_encode($json);
		}
			
		return $this->article_id;
	}

	public function Fetch404($enc_type= "")
	{
		$SQL= "SELECT p.article_id, p.page_id 
				FROM pages p 
				LEFT JOIN articles a on p.article_id= a.article_id 
				LEFT JOIN logbooks l ON a.logbook_id= l.logbook_id 
				WHERE l.chapter_id= 1
				AND l.logbook_name= '404'
				ORDER BY RAND() LIMIT 1";

		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$page_id= $row['page_id'];
			$article_id= $row['article_id'];
			$this->page_id= $page_id;
			$this->article_id= $article_id;
		}
		if($enc_type== "json")
		{
			$this->json["page_id"]= $page_id;
			$this->json["article_id"]= $article_id;
			echo json_encode($json);
		}
			
		return $this->article_id;
	}

	function FetchArticleID($chapter_id= 0, $article_name= "", $enc_type= "")
	{
		$SQL= "SELECT article_id FROM articles WHERE article_name= '$article_name' AND chapter_id= $chapter_id LIMIT 1;";
		
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_array())
		{
			$article_id= $row['article_id'];
			$this->article_id= $article_id;
		}
		if($enc_type== "json")
		{
			$this->json["article_id"]= $this->article_id;
			echo json_encode($this->json);
		}
		
		return $this->article_id;
	}
	
}

class Chapter extends Logbook
{

	public function FetchFeed($chapter_id= 0, $limit= 0, $offset= 0, $enc_type= "")
	{
		if($this->LoggedIn())
		{
			$account_id= $this->account_id;
		}
		else if($this->FBLoggedIn())
		{
			$account_id= $this->account_id;
		}
		if($chapter_id!= 0)
		{
			$chapter_string= "AND c.chapter_id= $chapter_id";
		}
		else {
			$chapter_string= "AND c.chapter_id <> 0";
		}
		$SQL= "SELECT DISTINCT * FROM tiles t 
				LEFT JOIN types ty ON t.type_id= ty.type_id
				LEFT JOIN brands b ON t.brand_id= b.brand_id
				LEFT JOIN pages p ON t.page_id= p.page_id
				LEFT JOIN catalog c ON p.article_id= c.article_id
				LEFT JOIN logbooks l ON c.logbook_id= l.logbook_id
				$chapter_string
				ORDER BY t.last_modified DESC LIMIT $offset, $limit";

		$this->DbConnect($SQL);
		$result= $this->DbConnect($SQL);
		
		while($row= pg_fetch_assoc())
		{
			$this->CheckPriveleges($row, $enc_type);
			$this->tiles[]= $row;
		}
		
		if($enc_type== "json")
		{
			$this->json["feed_data"]= $this->tile_content;		
			echo json_encode($this->json);
		}
		
		if($enc_type== "mobile")
		{		
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");
			$this->json["feed_data"]= $this->tile_content;	
			$this->json["composite_data"]= $this->composite_content;
			echo "DisplayLoadFeed(" . json_encode($this->json) . ");";
		}
		
		return $this->tile_content;	
	}
	
	public function FetchLogbook($chapter_id=0, $logbook_id=0 , $limit= 0, $offset= 0, $enc_type= "")
	{
		$content= "";
		$pages= array();
		$tile= "";
		$this->FetchArticles($chapter_id, $logbook_id);
		$count= 0;
		
		foreach($this->articles as $article)
		{
			$this->FetchPages($article['article_id']);
			
			foreach($this->pages as $page)
			{
				if($count<= $limit)
				{
					$content.= $this->FetchContent($page['page_id'], $limit, $offset, $enc_type, "DESC");
					$count= $count + $this->tile_count;
				}
			}
		}
		
		$this->json["articles"]= $this->articles;
		//$this->json["tile_content"]= $this->tile_content;
		
		if($enc_type== "json")
		{
			echo json_encode($this->json);
		}
		
		if($enc_type== "callback")
		{
			echo "DisplayTiles(" . json_encode($this->tiles) . ");";						
		}
		
		return $this->tile_content;
	}

	public function FetchLogbookList($article_id, $enc_type= "")
	{
		$account_id= 0;
		
		if($this->LoggedIn())
		{
			$account_id= $this->account_id;
		}
		else if($this->FBLoggedIn())
		{
			$account_id= $this->account_id;
		}
		
		$article_logbook= $this->FetchField("logbook_id", "catalog", "article_id= $article_id");
		
		$SQL= "SELECT l.logbook_id, l.logbook_name 
				FROM logbooks l 
				WHERE l.account_id < $account_id 
				ORDER BY l.logbook_id ASC;";

		$result= $this->DbConnect($SQL);

		if($enc_type== "mobile")
		{
			$list_data= "<select id='logbook_list' class='logbook_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";
			$list_data.= "<option id= 'logbook_0' class='logbook_link' logbook_id='0'><span>Select a Logbook</span></option>";	
		}
		else
		{
			$list_data= "<ul id='logbook_list' class='logbook_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";
		}

		//$row= $result->fetch_assoc();
		//$json['first']= $row['logbook_id'];

		while($row= $result->fetch_assoc())
		{
			if($enc_type== "mobile")
			{
				$list_data.= "<option id= 'logbook_$row[logbook_id]' class='logbook_link' logbook_id='$row[logbook_id]'><span>$row[logbook_name]</span></option>";
			}
			else
			{
				if($row['logbook_id']== $article_logbook)
				{
					$list_data.= "<li id= 'logbook_$row[logbook_id]' class='logbook_link selected' logbook_id='$row[logbook_id]'><span>$row[logbook_name]</span></li>";
				}
				else
				{
					$list_data.= "<li id= 'logbook_$row[logbook_id]' class='logbook_link' logbook_id='$row[logbook_id]'><span>$row[logbook_name]</span></li>";
				}
			}
		}

		if($enc_type== "mobile")
		{		
			$list_data.= "</select>";
		}
		else
		{
			$list_data.= "</ul>";	
		}
		if($enc_type== "json" || $enc_type== "mobile")
		{
			$json['list_data']= $list_data;
			echo json_encode($json);
		}
		
		return $list_data;
	}
	
	function FetchLogbookID($chapter_id= 0, $logbook_name= "", $enc_type= "")
	{
		$SQL= "SELECT logbook_id FROM logbooks WHERE logbook_name= '$logbook_name' AND chapter_id= $chapter_id LIMIT 1;";
		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_array())
		{
			$logbook_id= $row['logbook_id'];
			$this->logbook_id= $logbook_id;
		}
		if($enc_type== "json")
		{
			$this->json["logbook_id"]= $this->logbook_id;
			echo json_encode($this->json);
		}
		
		return $logbook_id;
	}
}


class Account extends Chapter
{

	public function FetchChapterList($article_id, $enc_type= "")
	{
		$account_id= 0;
		
		if($this->LoggedIn())
		{
			$account_id= $this->account_id;
		}
		else if($this->FBLoggedIn())
		{
			$account_id= $this->account_id;
		}

		$SQL= "SELECT c.* FROM chapters c WHERE c.account_id < $account_id ORDER BY c.chapter_id ASC;";	
		
		$result= $this->DbConnect($SQL);

		$list_data= "<ul id='chapter_list' class='logbook_list' style='margin: 0; padding: 0; list-style-type: none; width: 100%'>";

		//$row= $result->fetch_assoc();
		//$json['first']= $row['chapter_id'];
		//$list_data.= "<li id= 'chapter_$row[chapter_id]' class='chapter_link' chapter_id='$row[chapter_id]'><span>$row[chapter_name]</span></option>";

		$article_chapters= $this->FetchArticleChapters($article_id);
		
		while($row= $result->fetch_assoc())
		{
			if(in_array($row['chapter_id'], $article_chapters))
			{
				$list_data.= "<li id= 'chapter_$row[chapter_id]' class='chapter_link selected' chapter_id='$row[chapter_id]'><span>$row[chapter_name]</span></option>";
			}
			else
			{
				$list_data.= "<li id= 'chapter_$row[chapter_id]' class='chapter_link' chapter_id='$row[chapter_id]'><span>$row[chapter_name]</span></option>";
			}
		}

		$list_data.= "</ul>";
		$list_data.= "";

		if($enc_type== "json")
		{
			$json['sql']= $SQL;
			$json['list_data']= $list_data;
			echo json_encode($json);
		}
		
		return $list_data;
	}
	
	public function FetchArticleChapters($article_id= 0, $enc_type= "")
	{
		$SQL= "SELECT c.* FROM catalog c WHERE c.article_id = $article_id ORDER BY c.catalog_id ASC;";	
		$result= $this->DbConnect($SQL);
		$article_chapters= array();
		while($row= $result->fetch_assoc())
		{
			$article_chapters[]= $row['chapter_id'];
		}
		
		return $article_chapters;
	}
	
	public function FetchRecentUpdates($chapter_id= 0, $logbook_id= 0, $user_id= 0, $limit= 6, $type= "", $enc_type= "")
	{
		if($chapter_id== 0)
		{
			$chapter_string= "ch.chapter_id<> 0";
		}
		else
		{
			$chapter_string= "ch.chapter_id= $chapter_id";
		}
		if($logbook_id== 0)
		{
			$logbook_string= "l.logbook_id<> 0";
		}
		else
		{
			$logbook_string= "l.logbook_id= $logbook_id";
		}
		if($user_id== 0)
		{
			$user_string= "t.created_by<> 0";
		}
		else
		{
			$user_string= "t.created_by= $user_id";
		}
		switch($type)
		{
			case "chapter":
				$group_string= "GROUP BY c.chapter_id";
				break;
			case "logbook":
				$group_string= "GROUP BY l.logbook_id";
				break;
			case "article":
				$group_string= "GROUP BY a.article_id";
				break;
			case "page":
				$group_string= "GROUP BY p.page_id";
				break;
			default:
				$group_string= "";
				break;
		}
		
		$updates= array();

		$SQL= "SELECT p.page_id, p.page_name, l.logbook_id, l.logbook_name, a.article_id, a.article_name, ch.chapter_id, ch.chapter_name
				FROM tiles t
				LEFT JOIN pages p ON p.page_id= t.page_id 
				LEFT JOIN articles a ON a.article_id= p.article_id 
				LEFT JOIN catalog c ON c.article_id= a.article_id
				LEFT JOIN logbooks l ON l.logbook_id= c.logbook_id 
				LEFT JOIN chapters ch ON ch.chapter_id= c.chapter_id
				WHERE $chapter_string
				AND $logbook_string
				AND $user_string
				$group_string
				ORDER BY p.last_modified DESC
				LIMIT $limit";
		
		$result= $this->DbConnect($SQL);
		while($row= $result->fetch_assoc())
		{
			$updates[]= $row;
		}

		if($enc_type== "json")
		{
			$json["updates"]= $updates;
			echo json_encode($json);
		}

		return $updates;
	}
	
	function SaveChapter($article_id= 0, $chapter_arr= array(), $logbook_id)
	{
		$SQL= "DELETE FROM catalog WHERE article_id= $article_id";
		$result= $this->DbConnect($SQL);
		
		foreach($chapter_arr as $chapter_id)
		{
				$this->InsertCatalog($article_id, $chapter_id, $logbook_id);
		}
		
		return true;	
	}
	
	function ParseUrl($url= "", $text= "", $images= "", $links= "", $res_path="", $search="", $enc_type= "")
	{
		switch($search) {
			case 'default':
				$url= $url;
				$this->ScrapePage($url);
				break;
			case 'google':
				$term= substr($url, 9);
				$term= str_replace(" ", "+", $term);
				$search_url= "https://www.google.com/search?safe=off&q=$term&oq=$term";
				$url= "https://www.google.com";
				$images= "no";
				$links= "no";
				$text= "yes";
				$this->ScrapeGoogle($term);
				break;
			case 'gg-i':
				$term= substr($url, 12);
				$term= str_replace(" ", "+", $term);
				$url= "https://www.google.com";
				$html= $this->ScrapeGgi($term);
				break;
			case 'bing-i':
				$term= substr($url, 14);
				$term= str_replace(" ", "+", $term);
				$search_url= "http://www.bing.com/images/search?pq=$term&q=$term";
				$url= "http://www.bing.com";
				$this->ScrapePage($search_url);
				break;
			case 'tumblr':
				$term= substr($url, 9);
				$term= str_replace(" ", "+", $term);
				$search_url= "http://www.tumblr.com/tagged/$term";
				$url= "http://www.tumblr.com";
				$this->ScrapePage($search_url);
				break;
			case 'feed':
				$search_url= "http://popurls.com/";
				$url= "http://popurls.com";
				$this->ScrapePage($search_url);
				break;
			default:
				break;
		}
		
		$index= 0;
		$tile['url']= $url;
		
		if($images== "yes" && $links== "yes")
		{
			foreach($this->parse_ret as $element)
			{
				$tile['tile_id']= "scrape_$index";
				
				if($element['type']== "anchor")
				{
					$tile['type']= "spider_anchor";
					$tile['href']= $element['href'];
					if($res_path== "yes")
					{
						$tile['tile_content']= "<a class='spider_anchor' href='$url$element[href]' target='_blank'>$element[innertext]</a>";
					}
					else
					{
						$tile['tile_content']= "<a class='spider_anchor' href='$element[href]' target='_blank'>$element[innertext]</a>";
					}
					$tile['style']= "";
					$tile['created_by']= "";
					$tile['created_on']= "";
					$this->tile_data.= $this->FetchEditableTile($tile, "spider_anchor", "spider");						
				}
				else
				{
					$tile['type']= "spider_image";
					$tile['src']= $element['src'];
					if($res_path== "yes")
					{
						$tile['tile_content']= "<img class='spider_image' src='$url$element[src]'/>";
					}
					else
					{
						$tile['tile_content']= "<img class='spider_image' src='$element[src]'/>";
					}
					$tile['style']= "";
					$tile['created_by']= "";
					$tile['created_on']= "";
					$this->tile_data.= $this->FetchEditableTile($tile, "spider_image", "spider");						
				}	

				$index= $index + 1;
			}
		}
		else if($images== "yes")
		{
			foreach($this->parse_ret as $element)
			{				
				if($element['type']== "image")
				{
					$tile['type']= "spider_image";
					$tile['tile_id']= "scrape_$index";
					$tile['src']= $element['src'];
					if($res_path== "yes")
					{
						$tile['tile_content']= "<img src='$url$element[src]'/><br/>$url$element[href]";
					}
					else
					{
						$tile['tile_content']= "<img src='$element[src]'/><br/>$element[href]";
					}
					$tile['style']= "";
					$tile['created_by']= "";
					$tile['created_on']= "";
					$this->tile_data.= $this->FetchEditableTile($tile, "spider_image", "spider");						
				}
				
				$index= $index + 1;
			}
		}
		else if($links== "yes")
		{
			foreach($this->parse_ret as $element)
			{	
				if($element['type']== "anchor")
				{
					$tile['type']= "spider_anchor";
					$tile['tile_id']= "scrape_$index";
					$tile['href']= $element['href'];
					if($res_path== "yes")
					{
						$tile['tile_content']= "<a href='$url$element[href]'>$element[innertext]</a>";
					}
					else
					{
						$tile['tile_content']= $element['text'];
					}
					$tile['style']= "";
					$tile['created_by']= "";
					$tile['created_on']= "";
					$this->tile_data.= $this->FetchEditableTile($tile, "spider_anchor", "spider");	
					
					$index= $index + 1;					
				}
			}
		}
		if($text== "yes")
		{

			foreach($this->parse_ret as $element)
			{
				$tile['type']= "spider_text";
				$tile['tile_id']= "scrape_$index";
				if(isset($element['text']))
				{
					$tile['tile_content']= $element['text'];
				}
				$tile['style']= "";
				$tile['created_by']= "";
				$tile['created_on']= "";
				$this->tile_data.= $this->FetchEditableTile($tile, "spider_text", "spider");	
				$index= $index + 1;			
			}
			
			/*
			foreach($this->parse_text as $text)
			{
				$tile['type']= "spider_text";
				$tile['tile_id']= "scrape_$index";
				$tile['tile_content']= "$text[text]";
				$tile['style']= "";
				$tile['created_by']= "";
				$tile['created_on']= "";
				$this->tile_data.= $this->FetchEditableTile($tile, "spider_text", "spider");				
				$index= $index + 1;				
			}
			*/
		}
		
		if($enc_type== "mobile")
		{
			header('Content-Type: application/json');
			header("Access-Control-Allow-Origin: *");	
			$this->json["scrape_data"]= $this->tile_data;
			echo "DisplayParseUrl(" . json_encode($this->json) . ")";
		}
		else if($enc_type== "app")
		{
			$this->json["scrape_data"]= $this->tile_data;
			echo "DisplayParseUrl(" . json_encode($this->json) . ")";
		}
	}
}

$user= new User();
$tile= new Tile();
$page= new Page();
$article= new Article();
$logbook= new Logbook();
$account= new Account();


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
	case 'Load':
		$tile->FetchTile($tile_id, $stack_id, $enc_type);
		break;
	case 'Save':
		$tile->Save($tile_id, $tile_content, $style, $position, $user_id, $enc_type);
		break;
	case 'Paint':
		$tile->SavePaint($tile_id, $tile_content, $style, $position, $enc_type);
		break;
	case 'Back':
		$tile->SaveBack($tile_id, $back_content, "json");
		break;		
	case 'Flip':
		$tile->FetchBack($tile_id, $enc_type);
		break;		
	case 'Create':
		$tile->Create($brand, $style, $tile_content, $page_id, $enc_type);
		break;
	case 'Comment':
		$tile->Comment($style, $comment, $article_id, $page_id, $user_id, "json");
		break;
	case 'Clear':
		$tile->ClearTile($tile_id, "json");
		break;		
	case 'Delete':
		$tile->DeleteTile($tile_id, $enc_type);
		break;
	case 'Update':
		$tile->UpdateTile($tile_id, $property, $value, $enc_type);
		break;			
	case 'Link':
		$tile->LinkTile($tile_id, $link_id, $enc_type);
		break;			
	case 'Top':
		$tile->MoveToTop($tile_id, $page_id, $enc_type);
		break;					
	case 'Create':
		$page->CreatePage($article_id, $title, $enc_type);
		break;
	case 'Delete':
		$page->DeletePage($page_id, $title, $enc_type);
		break;
	case 'Views':
		$page->FetchPageViews($page_id, "json");
		break;
	case 'Change':
		$page->ChangePage($tile_id, $page_id, $enc_type);
		break;
	case 'Load':
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchArticle($logbook_id, $article_id, $page_index, $direction, $enc_type);
		break;
	case 'ArticleList':
		//FetchArticleList($chapter_id, $logbook_id, $enc_type);
		echo '456';
		break;
	case 'NextArticle':
		$logbook->FetchNextArticle($article_id);
		break;
	case 'PrevArticle':
		$logbook->FetchPrevArticle($article_id);
		break;
	case 'Updates':
		$logbook->FetchUpdates($logbook_id, $page_id, $direction, "json");
		break;	
	case 'Email':
		$logbook->SendEmail($email, $subject, $body, "json");
		break;
	case 'Template':
		$vars= array();
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchTemplate($name, $vars, $enc_type, $vars_obj);
		break;
	case 'AppTemplate':
		$vars= array();
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchTemplate($name, $vars, $enc_type, $vars_obj, $element);
		break;		
	case 'Random':
		$logbook->FetchRandomArticle($user_id, "json");
		break;
	case 'Home':
		$logbook->FetchArticle(0, 9, 1, "", "callback");
		break;		
	case 'Recent':
		$account->FetchRecentUpdates($chapter_id, $logbook_id, $user_id, $limit, $type, "json");
		break;
	case 'ChapterList':
		$account->FetchChapterList($article_id, "json");
		break;
	case 'Save':
		$account->SaveChapter($article_id, $chapter_arr, $logbook_id);
		break;
	case 'Parse':
		$account->ParseUrl($url, $res_text, $res_images, $res_links, $res_path, $search, $enc_type);
		break;
	case 'Load':
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchArticle($logbook_id, $article_id, $page_index, $direction, $enc_type);
		break;
	case 'ArticleList':
		$logbook->FetchArticleList($chapter_id, $logbook_id, $enc_type);
		break;	
	case 'NextArticle':
		$logbook->FetchNextArticle($article_id);
		break;
	case 'PrevArticle':
		$logbook->FetchPrevArticle($article_id);
		break;
	case 'Updates':
		$logbook->FetchUpdates($logbook_id, $page_id, $direction, "json");
		break;	
	case 'Email':
		$logbook->SendEmail($email, $subject, $body, "json");
		break;
	case 'Template':
		$vars= array();
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchTemplate($name, $vars, $enc_type, $vars_obj);
		break;
	case 'AppTemplate':
		$vars= array();
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchTemplate($name, $vars, $enc_type, $vars_obj, $element);
		break;		
	case 'Random':
		$logbook->FetchRandomArticle($user_id, "json");
		break;
	case 'Home':
		$logbook->FetchArticle(0, 9, 1, "", "callback");
		break;		
	case 'Load':
		$chapter->FetchLogbook($chapter_id, $logbook_id, $limit, $offset, $enc_type);
		break;
	case 'Feed':
		$account->FetchFeed($chapter_id, $limit, $offset, $enc_type);
		break;		
	case 'LogbookList':
		$chapter->FetchLogbookList($article_id, $enc_type);
		break;
	case 'Load':
		$article->FetchPage($article_id, $page_id, $direction, $enc_type);
		break;
	case 'PageList':
		$article->FetchPageList($article_id, $enc_type);
		break;
	case 'Create':
		$article->CreateArticle($chapter_id, $logbook_id, $title, $enc_type);
		break;	
	case 'Delete':
		$article->DeleteArticle($article_id, $title, $enc_type);
		break;	
	case 'Change':
		$article->ChangeArticle($page_id, $article_id, $enc_type);
		break;		
	case 'Save':
		$article->SaveArticle($article_id, $page_id, $title, $enc_type);
		break;
	case 'SaveOrder':
		$article->SaveArticleOrder($article_order, $page_order, $enc_type);
		break;
	case 'NextPage':
		$article->FetchNextPage($article_id, $page_id);
		break;			
	case 'PrevPage':
		$article->FetchPrevPage($article_id, $page_id);
		break;
	case 'Popular':
		$article->FetchPopularPages($user_id, "json");
		break;
	case 'Stats':
		$article->FetchStats($chapter_id, $logbook_id, $article_id, $enc_type);
		break;
	case 'Metric':
		$article->FetchMetric($metric, $chapter_id, $logbook_id, $article_id, "json");
		break;
	case 'More':
		$article->FetchContent($page_id, $limit, $offset, $enc_type);
		break;
	case 'Info':
		$article->FetchInfo($article_id, $page_id, $enc_type);
		break;
	case 'Zipfile':
		$article->FetchZip($page_id, $enc_type);
		break;	
}

?>


