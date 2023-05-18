<?php    
//pr($posts);
	$User_Info= $this->Session->read('User_Info');
	/*if(!empty($posts)){
		foreach ($posts as $post)
		{	
		  echo $this->element('/Posts/parent_post',array('post'=>$post,'in_paginate'=>TRUE,'is_comment'=>TRUE)); 
		}
	}*/
	$inline_posts = explode(',',$select_ids);
	if(!empty($inline_posts)){
		foreach($inline_posts as $inline_post){
			if(!empty($posts)){
				foreach ($posts as $post)
				{	
				  if($post['PALL']['post_id']==$inline_post)
				  	echo $this->element('/Posts/parent_post',array('post'=>$post,'in_paginate'=>TRUE,'is_comment'=>TRUE)); 
				}
			}
		}
	}
?>		  
 <script>
	postsImage();
	dropdown();	
</script>