<?php
  $User_Info= $this->Session->read('User_Info');
 ?> 
 
<?php if(isset($User_Info)){ ?>
 
 <aside id="new_notification" class="dataBox big_box">
<p><?php echo __('users_for_follow') ?> <a  href="<?php echo __SITE_URL.'users/search?user_type=0'; ?>"> 
	<?php echo __('all_users'); ?> </a></p> 
	
	<div id="body"></div>
</aside>
 
<aside id="new_tag" class="dataBox big_box">
		<p>
		<?php echo __('hot_subject') ?> 
			<a  href="<?php echo __SITE_URL.'posts/all_tags'; ?>"> <?php echo __('all_subject'); ?> 		</a>
		</p> 
		<ul class="hashTag">
		<?php
			 if(isset($User_Info)) $tags=$this->requestAction(__SITE_URL.'posts/new_tag/');
			 if(!empty($tags)){
			 	foreach($tags as $tag){
			 	echo "<li> 
						<a href='".__SITE_URL."posts/tags/".$tag['Posttag']['title']."'> 
						<span class='icon icon-tag'></span>
						<span class='text'>
						".
							$tag['Posttag']['title']." 
						</span>
						</a> 
					  </li>";
				 }
			 }	 
		?>  
		</ul> 
</aside>
<?php } ?>
 
