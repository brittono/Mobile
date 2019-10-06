<?php

include_once('constants.inc');
include_once('page.inc');

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

?>
