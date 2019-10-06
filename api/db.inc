<?php 

include_once('constants.inc');
include_once('simple_html_dom.php');
include_once('SimpleImage.php');

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
		//$db = new mysqli('localhost', 'root', 'sorbo123', 'cerebrit');
		$db = pg_connect("host=ec2-184-73-210-189.compute-1.amazonaws.com port=5432 dbname=d9v0f2hs6rppvr user=mryoltfwkygite password=83c5e8c819a1c57f182374231a8a6c2e997ffd40011325df2ae6c47ffcff9a67");
		if($result= pg_query($db, "$SQL"))
		{
			$this->exec= true;
			//$this->rows= pg_affected_rows($db);
			//$this->info= pg_info($db);
		}
		
		//$this->insert_id= mysqli_insert_id($db);
		//$this->error= $db->error;

		pg_close();

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
	
	function FetchField($field= "", $table= "", $sql= "", $enc_type= "")
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
		$template_data= "";
		$template_path= $_SERVER['DOCUMENT_ROOT'] . "/TPL/" . $name . ".php";

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

		return $this->template_name;
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

?>
