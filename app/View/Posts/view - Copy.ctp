<?php
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'index.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'index_'.$locale.'.css');
		
	echo $this->Html->css('index_'.$locale);
    
    
	
	echo $this->Html->script('jquery.form');
	$User_Info= $this->Session->read('User_Info');
	
	if(!empty($User_Info)){
		$current_user_id = $User_Info['id'];
	}else $current_user_id = 0;
?>

<section id="homePage">
	<div class="col-md-3 col-md-offset-0 col-sm-3">
		<?php if($current_user_id!=0){ ?>
        <?php echo $this->element('user_box'); ?>
		<?php echo $this->element('right'); ?>
	  <?php } ?>	
    </div>
	<input type="hidden" id="view_post_id" value="<?php echo $post_id; ?>" />
    <div class="col-md-6 col-sm-6"  >
         <span id="view_body">
		 <div class="dataBox noPadding" >
    		<div class="postGroup">
		 	<?php    
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
		?>
		    </div>
		</div>
		 </span>
         <span id="view_loading"></span>
		 		  
		 <script>
			postsImage();
			dropdown();	
		</script>
		            
    </div>
	
 <aside class="col-md-3">
 	<?php echo $this->element('left'); ?>
 </aside>
</section>
<style>
    #notification_body{width:200px;height:300px;overflow:auto;}	
</style>
<script>

if("<?php echo $current_user_id; ?>" > 0) select_notification();
if("<?php echo $current_user_id; ?>" > 0)  refresh_notification(0);
/*
 jQuery(
  function($)
  {
    var notificaion_count = 0;
    $('#notification_body').slimScroll({
          height: 'auto'
      });
    
    $('#notification_body').bind('scroll', function()
      {
        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
        {
           notificaion_count++;
		   refresh_notification(notificaion_count);
        }
      })
  }
);*/
</script>