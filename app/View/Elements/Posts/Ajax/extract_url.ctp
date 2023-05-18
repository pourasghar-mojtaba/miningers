<?php
 
	if(!empty($doc)){
		//print_r($doc);
		$tags = $doc->getElementsByTagName('img');

		$title = $doc->getElementsByTagName("title");

		if($title->length > 0){
		  $title = $title->item(0)->nodeValue;
		}
		
		$metas = $doc->getElementsByTagName("meta");

		for ($i = 0; $i < $metas->length; $i++)
		{
			$meta = $metas->item($i);
			if(strtolower($meta->getAttribute('name')) == 'description')
				$description = $meta->getAttribute('content');
		}

		//echo $desc = $doc->getElementsByTagName('description');

		$data = array();
		foreach ($tags as $index=>$tag) {
			$src    = $tag->getAttribute('src');
			$width  = $tag->getAttribute('width');
			$height = $tag->getAttribute('height');
			if($width == "" || $height == ""){
				list($width, $height) = @getimagesize($src);
			}
			// ignore images with height or width less than 50px
			if($width >= "50" || $height >= "50"){
				$data[$index]['src']    = $src;
			}
		}

		$tags = get_meta_tags($link);
		$data[]["title"] = $title; /*(strlen($title) > 50 ) ?  substr_replace($title,"...",50) : $title;*/
		$data[]["url"] = (strlen($link) > 50) ?  substr_replace($link,"...",50) :  $link;
		$data[]["desc"] = (strlen($description) > 300) ?  substr_replace($description,"...",300) : $description;

		echo json_encode($data);
	}else
		echo "0";


?>

 