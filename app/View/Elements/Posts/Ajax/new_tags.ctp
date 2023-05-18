<ul>
<?php
	 //print_r($tags);
	 foreach($tags as $tag){
	 	echo "<li> 
					<a href='".__SITE_URL."posts/tags/".$tag['Posttag']['title']."'> #".$tag['Posttag']['title']." </a> 
			  </li>";
	 }
?>
  
</ul>