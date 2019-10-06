<?php
	
	DEFINE('DOCUMENT_ROOT', '/');
	//DEFINE('DB', 'cerebrit_dev');	
	DEFINE('URL', 'http://159.203.134.192');	
	
	/*
	$host = parse_url($_SERVER["HTTP_HOST"]);
	$host= str_replace('%20', ' ', $host);
	echo "<pre>" . var_dump($host) . "</pre>";
	$domain = explode('.', $host['path']);
	$subdomain = array_slice($domain, 0, count($domain) - 2 );
	
	ini_set("memory_limit","32M");
	
	if(isset($subdomain[0]) && $subdomain[0]== "dev")
	{
		DEFINE('DOCUMENT_ROOT', '/var/www/dev.cerebrit.com');
		DEFINE('DB', 'cerebrit_dev');	
		DEFINE('URL', 'http://dev.cerebrit.com');
	}
	else
	{
		DEFINE('DOCUMENT_ROOT', '/var/www/cerebrit.com');
		DEFINE('DB', 'cerebrit');	
		DEFINE('URL', 'http://wiki.cerebrit.com');		
	}
	*/
?>