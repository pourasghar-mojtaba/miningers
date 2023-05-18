<?php 
	echo $this->Html->css('index_'.$locale);
 ?>


<section id="homePage">
	<div class="col-md-3 col-md-offset-0 col-sm-3">
        <?php echo $this->element('user_box'); ?>
		<?php echo $this->element('right'); ?>
    </div>
    <div class="col-md-6 col-sm-6" >
		<div id="tag_body" class="">
			
			<?php 
			echo "
						   		<div class='dataBox noPadding' >
				    				<div class='postGroup'>
						   ";   
			$User_Info= $this->Session->read('User_Info');
			$inline_posts = explode(',',$select_ids);
			if(!empty($inline_posts)){
				foreach($inline_posts as $inline_post){
					if(!empty($posts)){
						
						foreach ($posts as $post)
						{	
						  
						  
						  if($post['PALL']['post_id']==$inline_post)
						  	{
								if($inline_post == $post_id){
									if(count($posts)==1){
									$is_comment = TRUE;
										}else $is_comment= FALSE;
									echo $this->element('/Posts/parent_post',array('post'=>$post,'is_ads'=>FALSE,'in_paginate'=>FALSE,'is_comment'=>$is_comment));
								}else
								{
									
									echo $this->element('/Posts/parent_post',array('post'=>$post,'is_ads'=>FALSE,'in_paginate'=>FALSE,'is_comment'=>TRUE));
								}
								
							}
						  	 
						}
						
					}
				}
			}
			echo "
						   			</div>
								</div>
						   "; 
		?>
			
		</div>            
		<div id="tag_loading"></div>
              
    </div>
	
 
</section>
<aside class="col-md-3">
	<?php echo $this->element('left'); ?>
 </aside>
<script>
	select_notification();
	refresh_notification(0);
</script>