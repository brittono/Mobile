<?php

include_once('constants.inc');
include_once('brand.inc');

class Type extends Brand
{
	function FetchAccountType($chapter_id= 0)
	{
		$SQL= "SELECT account_id 
				FROM types t
				LEFT JOIN chapters c ON c.account_id= t.account_id
				WHERE c.chapter_id= $chapter_id
				LIMIT 1;";

		$result= $this->DbConnect($SQL);
		if($row= $result->fetch_assoc())
		{
			$type_id= $row['type_id'];
			$this->account_id= $type_id;
		}
		
		return $account_id;
	}
}

?>

