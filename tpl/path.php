<?php

	$account->account= 'sign_in';

	if(isset($subdomain[0]) && $subdomain[0]!= "")
	{
		$account->account= 'article_info';
		$account->chapter_name= $subdomain[0];
		$account->chapter_id= $account->FetchID($account->chapter_name, "chapter");
		//$account->logbook_id= 2;
		//$account->logbook_id= $account->FetchField("logbook_id", "catalog", "chapter_id= $account->chapter_id ORDER BY RAND()");
		//$account->logbook_name= $account->FetchName($account->logbook_id, "logbook");
	}

	else
	{
		$account->chapter_name= "ebook";
		$account->chapter_id= 16;
		$account->account= 'article_info';		
	}

	if(isset($subdomain[1]) && $subdomain[1]!= "")
	{
		$account->account= 'recent_articles';		
		//$account->logbook_name= $subdomain[1];
		//$account->logbook_id= $account->FetchID($account->logbook_name, "logbook");
	}
	
	$request= str_replace('%20', ' ', $_SERVER["REQUEST_URI"]);
	$request= explode('/', $request);
	
	if(isset($request[1]) && $request[1]!= "")
	{
		$account->account= 'article_info';
		$account->article_name= $request[1];
		$account->article_id= $account->FetchID($account->article_name, "article");
		
		if($account->article_id)
		{
			$account->account= 'article_info';
		}
	}
	if(isset($request[2]) && $request[2]!= "")
	{
		$account->account= 'sign_in';
		$account->redirect= '404';
		$account->page_name= $request[2];
		$account->page_id= $account->LookUpPage($account->article_id, $account->page_name);
		//$account->logbook_id= 13;
		//$account->logbook_name= "fullscreen";
		
		if($account->page_id)
		{
			$account->account= 'stats';
			$account->redirect= '';
		}
	}
	if(isset($request[3]) && $request[3]!= "")
	{
		$account->account= '';	
		$account->user_name= $request[3];
		$account->user_id= $account->FetchId($account->user_name, "user");
		//$account->logbook_id= 4;
		//$account->logbook_id= "post";
		
		
		if($account->user_id)
		{
			$account->account= 'most_recent';
			$account->redirect= 'most_recent';
		}
	}

	if($account->chapter_name== "mobile" || $account->chapter_name== "m")
	{
		//$account->FetchArticle($account->logbook_id, $account->articles_id, 0, "");	
		include_once("/var/www/TPL/index_mobile.php");
	}
	else if($account->chapter_name== "feed")
	{
		include_once("/var/www/TPL/index_feed.php");
	}
	else if($account->chapter_name== "canvas")
	{
		$account->chapter_id= 12;
		$account->chapter_name= "canvas";
		//$account->FetchArticle($account->logbook_id, $account->article_id, 1, "");
		include_once("/var/www/TPL/index_canvas.php");
	}
	else if($account->chapter_name== "wiki")
	{
		//echo $account->logbook_id;
		$account->FetchArticle($account->logbook_id, $account->article_id, 1, "");
		if($account->logbook_name== "fullscreen")
		{
			include_once("/var/www/TPL/index_wiki.php");
		}
		else
		{
			include_once("/var/www/TPL/index_standard.php");
		}
	}
	else
	{
		$account->FetchArticle($account->logbook_id, $account->article_id, 1, "");
		include_once("/var/www/TPL/index_standard.php");	
	}

?>
