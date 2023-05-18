
<?php    
 //pr($comments);
	$User_Info= $this->Session->read('User_Info');
	$i=1;
	if(!empty($comments)){
		foreach ($comments as $comment)
		{	
		   if($i==1){
		   	echo "<div class='post answer' id='answer_".$comment['Blogcomment']['id']."' style='background: #ffffff;margin-top: 50px'>";
		   }
		   else
		   echo "<div class='post answer' style='background: #ffffff;'>";
		   
		        echo"<article class='embedEdit'>";
				if(!empty($User_Info)){
		        if($comment['User']['id']==$User_Info['id']){    
					echo"<div role='menu' class='dropdown editIcon'>
                        <div class='icon icon-ellipsis dropdownBtn'></div>
                        <ul>
                            <li onclick='delete_blogcomment_confirm(".$comment['Blogcomment']['id'].")' style='display: none;'>".__('delete_post')."</li>
                        </ul>
                    </div>";
					}
				}	
					echo"
					<div class='imagePlace'>
		                <div class='ax'>";
							echo $this->Gilace->user_image($comment['User']['image'],$comment['User']['sex'],$comment['User']['user_name'],'');
						echo"</div>
		            </div>
		            <div class='textPlace dataArticle'>
		                <div class='fontSize17'><span>".$comment['User']['name']."</span>
						<a class='atSign' href='".__SITE_URL.$comment['User']['user_name']."'>
						@".$comment['User']['user_name']."</a>:</div>
		                <a class='date'>";
							if($locale =='per')
                                echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($comment['Blogcomment']['created']));  
                            if($locale =='eng')
                                echo date("Y/m/d - H:i",strtotime($comment['Blogcomment']['created']));   
						echo"</a>
		                ".$this->Gilace->filter_editor($comment['Blogcomment']['comment'])."
		           </div>
		        </article>
	   	  		</div>
				
		   ";
		   $i++;
		}
	}
?>	
<script>
	dropdown();	
</script>	  
 