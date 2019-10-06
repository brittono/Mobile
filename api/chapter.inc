<?php

include_once('constants.inc');
include_once('logbook.inc');

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
		
		while($row= $result->fetch_assoc())
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

?>
