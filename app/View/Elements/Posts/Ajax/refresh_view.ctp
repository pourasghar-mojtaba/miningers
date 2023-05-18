<?php    

$inline_posts = explode(',',$select_ids);
	if(!empty($inline_posts)){
		foreach($inline_posts as $inline_post){
			if(!empty($posts)){
				foreach ($posts as $post)
				{	
				  if($post['PALL']['post_id']==$inline_post)
				  	{
						if($inline_post == $post_id){
							echo $this->element('/Posts/parent_post',array('post'=>$post,'is_ads'=>FALSE,'in_paginate'=>FALSE,'is_comment'=>FALSE));
						}else
						echo $this->element('/Posts/parent_post',array('post'=>$post,'is_ads'=>FALSE,'in_paginate'=>FALSE,'is_comment'=>TRUE));
					}
				  	 
				}
			}
		}
	}

?>		  
 <script>
	postsImage();
	dropdown();	
</script>