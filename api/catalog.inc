<?php

include('init.php');
include_once('./simple_html_dom.php');

// PHP AJAX class for handling JS AJAX requests

class Ajax
{
    public $insert_id;
    public $sql_error;
    public $result;

    public function Categories($filter= 'category')
    {
	if($filter== 'year')
	{
	    $order= "DESC";
	}
	$SQL= "SELECT DISTINCT $filter FROM records.records ORDER BY $filter $order;";
	$this->DbConnect($SQL);
	while($row= $this->result->fetch_assoc())
	{
	    echo "<span id='$row[$filter]' class='" . $filter . "_links'>$row[$filter]</span>&nbsp;&nbsp;&nbsp;&nbsp;";
	}
    }

    public function Records($filter, $id)
    {
	$SQL= "SELECT DISTINCT album FROM records.records WHERE $filter='$id';";
	$this->DbConnect($SQL);
//	echo "<ul style='list-style-type: none; margin: 0 10px; padding: 0px; text-align: left;'>\r\n";
	while($row= $this->result->fetch_assoc())
	{
	    echo "<span id='record_$row[album]' class='record_links' style='margin: 0; padding: 0; height: 15px; width: 400px; text-align: left;'>$row[album]</span><br/>\r\n";
	}
	echo "</ul>\r\n";
    }

    public function IsLoggedIn()
    {
	session_start();
	$SQL= "SELECT auth_key FROM user WHERE user= 'admin'";
	$result= $this->DbConnect($SQL);
	$row= $this->result->fetch_assoc();
	// Set the AuthKey
	if(isset($row['auth_key']))
	{
	    $AuthKey= $row['auth_key'];
	}
	else
	{
	    $AuthKey= NULL;
	}
	$UserKey= $_SESSION['auth_key'];
	if($UserKey== $AuthKey)
	{
	    $json= array("IsLoggedIn"=> "TRUE");
	    return TRUE;
	}
	else
	{
	    $json= array("IsLoggedIn"=> "FALSE");
	    return FALSE;
	}
    }

    public function Album($filter, $id)
    {
	if($filter== 'none')
	{
	    $SQL= "SELECT * FROM records.records ORDER BY rand() LIMIT 1;";
	}
	else
	{
	    $SQL= "SELECT * FROM records.records WHERE $filter= '$id' ORDER BY rand() LIMIT 1;";
	}
	$this->DbConnect($SQL);
	$row= $this->result->fetch_assoc();
	$rating_slider= "<img src='./img/covers/$row[cover]' style='width: 280px; height: 280px;' />";
	$rating= $row[rating];
	$stars= "";
	$star_count= intval($rating / 20);
	$final_star= $rating % 20;
	$i= 0;
	while($i < $star_count)
	{
	    $stars.= "<img src='./img/star.png' />";
	    $i++;
	}
	if($final_star > 7 && $final_star < 17)
	{
	    $stars.= "<img src='./img/star_half.png' />";
	}
	else if($final_star > 17)
	{
	    $stars.= "<img src='./img/star.png' />";
	}
	if($this->IsLoggedIn())
	{
	    $rating_slider.= "<div class='rating'></div><input type='text' class='value' value=$row[rating]/><div class='rating_submit'>Save</div>";
	}
	else
	{
	    $rating_slider.= "<div class='album_rating'>Rating: $stars</div>";
	}
	$j_array= array("id"=>$row[id], "img"=> $rating_slider, "desc"=>$row[description], "info"=>"<div>$row[artist]</div><div>$row[album]</div><div>$row[year]</div>", "rating"=>$row[rating]);
	echo json_encode($j_array);
//	while($row= $this->result->fetch_assoc())
//	{
//	    echo "<div id='$row[album]' class='record_links' style='margin: 0; padding: 0; height: 15px; width: 400px; text-align: left;'>$row[album]</div>\r\n";
//	}
//	echo "</ul>\r\n";
    }

    public function Add($album="unknown", $category="unfiled", $year=0000, $artist="unknown")
    {
	$cover= $this->ScrapeCoverArt($album, $artist);
	$SQL= "INSERT INTO records.records (album, category, year, artist, cover) VALUES ('$album', '$category', '$year', '$artist', '$cover');";
	$this->DbConnect($SQL);
//	{
//	    echo "<div id='$row[album]' class='record_links' style='margin: 0; padding: 0; height: 15px; width: 400px; text-align: left;'>$row[album]</div>\r\n";
//	}
//	echo "</ul>\r\n";
    }

    public function ScrapeCoverArt($album, $artist)
    {
	$f_album= str_replace(" ", "+", $album);
	$f_artist= str_replace(" ", "+", $artist);

	$url= "http://www.last.fm/music/" . $f_artist . "/" . $f_album . "/";

	$html = file_get_html($url);

	$ret['img'] = $html->find('.art', 0)->getAttribute("src");

	$img= file_get_contents($ret['img']);

	$dir= "./img/covers/";
	$filename= str_replace(" ", "_", $artist) . "_";
	$filename.= str_replace(" ", "_", $album);
	$filename.= ".png";
	$full_path= $dir . $filename;

	file_put_contents($full_path, $img);

	return $filename;
    }

    public function SaveDescription($content, $id)
    {
	$SQL= "UPDATE records.records SET description= '$content' WHERE id= '$id';";
	$this->DbConnect($SQL);
	echo $this->sql_error;
	echo $SQL;
//	echo $id . $content . $style . $position;
    }

    public function SaveInfo($artist, $album, $year, $id)
    {
	$SQL= "UPDATE records.records SET artist= '$artist', album= '$album', year='$year' WHERE id= '$id';";
	$this->DbConnect($SQL);
	echo $this->sql_error;
	echo $SQL;
//	echo $id . $content . $style . $position;
    }

    public function SaveRating($rating, $id)
    {
	$SQL= "UPDATE records.records SET rating= '$rating' WHERE id= '$id';";
	$this->DbConnect($SQL);
	echo $this->sql_error;
	echo $SQL;
//	echo $id . $content . $style . $position;
    }

    public function DbConnect($SQL)
    {
	$db = new mysqli('localhost', 'bro', 'br1tt0n', 'cerebrit');
	if($db->mysqli_connect_error)
	{
	    $this->mysqli_connect_error= $db->mysqli_connect_error;
	    $this->sql_error= 'Did not connect to Db';
	}
	else
	{
	    $this->sql_error= 'Connected. ';
	    // run the query and set debug params
	    if($this->result= $db->query("$SQL"))
	    {
		$this->sql_error.= ' Query executed.';
		$this->insert_id= $db->insert_id;
//	    	echo $this->result->num_rows;
	    }
	    else
	    {
		$this->sql_error.= ' Query not executed.' . ' ' . $db->error;

		$this->insert_id= '_0';
	    }
	    $db->close();
	}
			
    }
}

extract($_POST);
$ajax= new Ajax();

switch($action)
{
    case 'Categories':
	$ajax->Categories($filter);
	break;
    case 'Records':
	$ajax->Records($filter, $id);
	break;
    case 'Album':
	$ajax->Album($filter, $id);
	break;
    case 'Add':
	$ajax->Add($album, $category, $year, $artist);
	break;
    case 'SaveDescription':
	$ajax->SaveDescription($content, $id);
	break;
    case 'SaveInfo':
	$ajax->SaveInfo($artist, $album, $year, $id);
	break;
    case 'SaveRating':
	$ajax->SaveRating($rating, $id);
	break;
}
?>
