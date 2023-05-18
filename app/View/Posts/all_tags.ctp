
<?php 
	echo $this->Html->css('all_tag_'.$locale); 
?>
 
<script>

var count=0;
refresh_alltag(count);	
	
$(window).scroll(function(){  
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
		   count++; 
		   refresh_alltag(count);
		}  
}); 
</script>
 		
		<?php echo $this->element('right_panel'); ?>
<section id="mainPanel">
  <header> <?php echo __('all_subject'); ?> </header>
<div id="tag_loading1">
<?php echo $this->Html->image('/img/loader/big_loader.gif'); ?>
</div>	 
<div id="tag_body"></div>
             
<div id="tag_loading"></div>
	

	

</section>