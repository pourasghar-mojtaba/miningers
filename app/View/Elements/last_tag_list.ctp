<?php
  $User_Info= $this->Session->read('User_Info');
 ?>

<div class="sideMenu panels">
    <div class="titr">
        <span> <?php  echo __('site_tags'); ?> </span>
    </div>
	<div class="seperator_Hor"></div>		
	<ul class="MenuItem">
   <?php
   
  	 	$tags=$this->requestAction(__SITE_URL.'posttags/last_tag_list/');
		if(!empty($tags)){
			foreach($tags as $tag)
			{
				echo "<li><a href='".__SITE_URL."posts/tags/".$tag['Posttag']['title']."'> ".$tag['Posttag']['title']."</a>";
				     if($tag['Posttag']['count']>0){
					 	echo "<div class='tags_list'><span>".$tag['Posttag']['count']."</span></div>";
					 } 
				echo"</li>";
			}
		} 
   ?>
    </ul>
</div>
 