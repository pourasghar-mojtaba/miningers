<?php 
	echo $this->Html->css('index_'.$locale);
	$User_Info= $this->Session->read('User_Info');
	
?>

<section id="homePage">
	<div class="col-md-3 col-md-offset-0 col-sm-3">
        <?php 
			if(!empty($User_Info)){
				echo $this->element('user_box');  
		      echo $this->element('right'); 
			}  
		  ?>
    </div>
    <div class="col-md-6 col-sm-6" >
		<div id="search_result_body"></div>            
		<div id="search_result_loading"></div>
              
    </div>
	
 <aside class="col-md-3">
	<?php echo $this->element('left'); ?>
 </aside>
</section>
<?php 
if(!empty($User_Info)){
	?>
	<script>
		select_notification();
		refresh_notification(0);
	</script>
<?php
}  
?>

<script>

var count=0;
search_post(count,'<?php echo $search_word ?>');	
	
$(window).scroll(function(){  
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
		   count++; 
		   search_post(count,'<?php echo $search_word ?>');
		}  
}); 
</script>
 