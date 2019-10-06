<?php

include_once('../api/chapter.inc');

$action= ""; 

extract($_GET);
extract($_POST);

$chapter= new Chapter();

$chapter->StartSession();

switch($action)
{
	case 'Load':
		$chapter->FetchLogbook($chapter_id, $logbook_id, $limit, $offset, $enc_type);
		break;
	case 'Feed':
		$chapter->FetchFeed($chapter_id, $limit, $offset, $enc_type);
		break;		
	case 'LogbookList':
		$chapter->FetchLogbookList($article_id, $enc_type);
		break;
}

?>
