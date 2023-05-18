<?php
	//pr($blogs);
	if(!empty($blogs)){
		foreach($blogs as $blog){
										
			echo "<li><a href='".__SITE_URL."blogs/view/".$blog['Blog']['id']."'>";
			echo"		
				 <span class='bl_ax'>";
					$user_image='';
					$width=70;
					$height=70;
					$image=$blog['Blog']['image'];
				    if(fileExistsInPath(__USER_BLOG_PATH.$image )&& $image!='' ) 
					{
						$user_image = $this->Html->image('/'.__USER_BLOG_PATH.__UPLOAD_THUMB.'/'.$image,array('width'=>$width,'height'=>$height,'id'=>'blog_thumb_image_'.$blog['Blog']['id']));
					}
					else{		 
						$user_image = $this->Html->image('new_blog.png',array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>$id));
					}
					echo $user_image;
				echo"	
				 </span>
				 <span class='blog_title'>";
				 if(mb_strlen($blog['0']['title'])>50){
				 	echo mb_substr($blog['0']['title'],0,45).'...';
				 }else echo $blog['0']['title'];
				 echo"
				 </span>
				 <span class='blog_date'>";
				 	
					if($locale =='per')
                        echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($blog['Blog']['created']));  
                    if($locale =='eng')
                        echo date("Y/m/d - H:i",strtotime($blog['Blog']['created']));   
				 echo"</span></a>
		      </li>";
		}
	}
?>
