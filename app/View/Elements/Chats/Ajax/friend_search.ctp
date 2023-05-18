<?php
 //print_r($search_result);
 if ($search_result)
{
	$x = 0;
	foreach($search_result as $value)
	{		
		$image=__SITE_URL.'/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$value['User']['image'];
		$friends[$x] = array("name" => $value['User']["name"],"user_id" => $value['User']["id"],"image" =>$image);
		$x++;	
	}
	
	$response = $_GET["callback"] . "(" . json_encode($friends) . ")";
	
	echo $response;
}




?>

 