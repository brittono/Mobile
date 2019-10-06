<?php

include_once('constants.inc');
include_once('article.inc');

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
  
?>
