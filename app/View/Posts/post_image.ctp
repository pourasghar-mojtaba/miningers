<?php 
	echo $this->Html->css('index_'.$locale);
 ?>

<script>

var count=0;
refresh_post_image(<?php echo $user_id; ?>);	
	
$(window).scroll(function(){  
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
		   count++; 
		   refresh_post_image(<?php echo $user_id; ?>);
		}  
}); 
</script>

<section id="homePage">
	<div class="col-md-3 col-md-offset-0 col-sm-3">
        <?php echo $this->element('user_box'); ?>
		<?php echo $this->element('right'); ?>
    </div>
    <div class="col-md-6 col-sm-6" >
		<div id="post_image_body"></div>            
		<span class="post_image_loading" style="margin: 40%">
            <?php echo "<img src ='".__SITE_URL."img/loader/big_loader.gif' />"; ?> 
        </span> 
              
    </div>
	
 <aside class="col-md-3">
	<?php echo $this->element('left'); ?>
 </aside>
</section>
<script>
	select_notification();
	refresh_notification(0);
</script>
