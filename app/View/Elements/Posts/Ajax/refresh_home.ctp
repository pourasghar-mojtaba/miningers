
<?php    
 //pr($posts);
	$User_Info= $this->Session->read('User_Info');
	
	if(!empty($posts)){
		foreach ($posts as $post)
		{	
		   echo "
		   		<div class='dataBox noPadding' >
    				<div class='postGroup'>
		   ";
		   echo $this->element('/Posts/parent_post',array('post'=>$post,'in_paginate'=>TRUE,'is_comment'=>FALSE));
		   echo "
		   			</div>
				</div>
		   ";
		}
	}
?>	
		  
 <script>
	postsImage();
	dropdown();	
</script>