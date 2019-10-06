<?php

include_once('../api/logbook.inc');

$chapter_id= 0;

$action= "";

$enc_type= "";
extract($_POST);
extract($_GET);
$logbook= new Logbook();
$logbook->StartSession();

$logbook->chapter_id= $chapter_id;
	
switch($action)
{
	case 'Load':
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchArticle($logbook_id, $article_id, $page_index, $direction, $enc_type);
		break;
	case 'ArticleList':
		$logbook->FetchArticleList($chapter_id, $logbook_id, $enc_type);
		break;	
	case 'NextArticle':
		$logbook->FetchNextArticle($article_id);
		break;
	case 'PrevArticle':
		$logbook->FetchPrevArticle($article_id);
		break;
	case 'Updates':
		$logbook->FetchUpdates($logbook_id, $page_id, $direction, "json");
		break;	
	case 'Email':
		$logbook->SendEmail($email, $subject, $body, "json");
		break;
	case 'Template':
		$vars= array();
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchTemplate($name, $vars, $enc_type, $vars_obj);
		break;
	case 'AppTemplate':
		$vars= array();
		if($enc_type== "")
		{
			$enc_type= "json";
		}
		$logbook->FetchTemplate($name, $vars, $enc_type, $vars_obj, $element);
		break;		
	case 'Random':
		$logbook->FetchRandomArticle($user_id, "json");
		break;
	case 'Home':
		$logbook->FetchArticle(0, 9, 1, "", "callback");
		break;		
}

?>



