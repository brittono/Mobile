<?php

include_once('constants.inc');
include_once('comment.inc');

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

?>

