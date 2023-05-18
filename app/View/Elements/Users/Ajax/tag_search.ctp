<?php
 //print_r($search_result);
 if ($search_result)
{
	$x = 0;
	foreach($search_result as $value)
	{		
		$friends[$x] = array("title" => $value['Usertag']["title"],"id" => $value['Usertag']["id"]);
		$x++;	
	}
	
	$response = $_GET["callback"] . "(" . json_encode($friends) . ")";
	
	echo $response;
}




?>

 