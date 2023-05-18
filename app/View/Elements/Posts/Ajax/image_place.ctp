
 
 
      <?php
		if(!empty($post['Post']['image']) && $post['Post']['image']!='')
		{
			echo $this->Html->image('/'.__POST_IMAGE_PATH.$post['Post']['image'],array('class'=>'bigimg')); 
		}
	  ?>
  