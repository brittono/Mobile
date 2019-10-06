<?php

include_once('../api/page.inc');

$enc_type= "";
$action= "";

extract($_POST);
extract($_GET);
$page= new Page();

$page->StartSession();

switch($action)
{
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
}
