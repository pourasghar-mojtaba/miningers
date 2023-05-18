<?php
 //print_r($search_result);
 if ($search_result)
{
	$x = 0;
	foreach($search_result as $value)
	{		
		$friends[$x] = array("title" => $value['Usertag']["title"],"id" => $value['Usertag']["id"],"tag_count" => $value['0']["tag_count"]);
		$x++;	
	}
	
	$response = $_GET["callback"] . "(" . json_encode($friends) . ")";
	
	echo $response;
}




?>

 