<?php

include_once('constants.inc');
include_once('tile.inc');

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

?>
