<?php

//include_once('constants.inc');
//include_once('chapter.inc');
//include_once('/app/api/user.inc');

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

?>
