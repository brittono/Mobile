<?php

include_once('../api/tile.inc');

$enc_type= "";

$action= "";

extract($_POST);
extract($_GET);

$tile= new Tile();
$tile->StartSession();

switch($action)
{
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
}
	
?>




