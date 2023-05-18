<?php    
	$User_Info= $this->Session->read('User_Info');
	if(!empty($posts)){
		foreach ($posts as $post)
		{	
?>
 
    <?php echo $this->element('/Posts/post',array('post'=>$post,'in_paginate'=>TRUE,'is_comment'=>FALSE)); ?>
	<div id="answer_place_<?php echo $post['Post']['post_id']; ?>"></div>
	<script>
	expand_post('expand_'+<?php echo $post['Post']['post_id']; ?>,<?php echo $post['Post']['post_id']; ?>,1);
    </script> 
 
 
		  
<?php
	 
		}
	}
?>		  
<script>
	//show outOfBox icons
		$(".post").mouseenter(function(e) {
            $(".outOfBox",this).fadeIn(400);
        });
		$(".post").mouseleave(function(e) {
            $(".outOfBox",this).fadeOut(400);
        });	
</script>
