<?php

include_once('../api/article.inc');

$chapter_name= "";
$logbook_name= "";
$enc_type= "";
$action= "";

extract($_POST);
extract($_GET);

$article= new Article();

$article->StartSession();

$article->chapter_name= $chapter_name;
$article->logbook_name= $logbook_name;

switch($action)
{
	case 'Load':
		$article->FetchPage($article_id, $page_id, $direction, $enc_type);
		break;
	case 'PageList':
		$article->FetchPageList($article_id, $enc_type);
		break;
	case 'Create':
		$article->CreateArticle($chapter_id, $logbook_id, $title, $enc_type);
		break;	
	case 'Delete':
		$article->DeleteArticle($article_id, $title, $enc_type);
		break;	
	case 'Change':
		$article->ChangeArticle($page_id, $article_id, $enc_type);
		break;		
	case 'Save':
		$article->SaveArticle($article_id, $page_id, $title, $enc_type);
		break;
	case 'SaveOrder':
		$article->SaveArticleOrder($article_order, $page_order, $enc_type);
		break;
	case 'NextPage':
		$article->FetchNextPage($article_id, $page_id);
		break;			
	case 'PrevPage':
		$article->FetchPrevPage($article_id, $page_id);
		break;
	case 'Popular':
		$article->FetchPopularPages($user_id, "json");
		break;
	case 'Stats':
		$article->FetchStats($chapter_id, $logbook_id, $article_id, $enc_type);
		break;
	case 'Metric':
		$article->FetchMetric($metric, $chapter_id, $logbook_id, $article_id, "json");
		break;
	case 'More':
		$article->FetchContent($page_id, $limit, $offset, $enc_type);
		break;
	case 'Info':
		$article->FetchInfo($article_id, $page_id, $enc_type);
		break;
	case 'Zipfile':
		$article->FetchZip($page_id, $enc_type);
		break;	
}

?>
