
<?php
						  if(!empty($comments))
						  {
						  	foreach($comments as $comment)
							{
								echo"
								   <li>";		      
									if(fileExistsInPath(__USER_IMAGE_PATH.$comment['User']['image'] ) && $comment['User']['image']!='' ) 
										{
											echo $this->Html->image('/'.__USER_IMAGE_PATH.$comment['User']['image'],array('id'=>'image_img','height'=>160,'width'=>160));
										}
										else{
											if($comment['User']['sex']==0)
											  echo $this->Html->image('profile_women.png',array('id'=>'image_img','height'=>160,'width'=>160)); else echo $this->Html->image('profile_men.png',array('id'=>'image_img','height'=>160,'width'=>160));
										}
									
									
				                    echo"<h3>".$comment['User']['name']." :</h3>
									<br />
				                    <span>    
									   ".$this->Gilace->filter_editor($comment['Comment']['body'])." 
									</span>
				                </li>
								
								";
								
							}
						  }
						  
						?>