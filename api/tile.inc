<?php

include_once('constants.inc');
include_once('user.inc'); 

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
?>
