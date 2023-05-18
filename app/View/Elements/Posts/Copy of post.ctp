<?php
  $User_Info= $this->Session->read('User_Info');
  $style='';
  if(!empty($is_ads)){
  	if($is_ads){
	  	$style="style='background:#f4d279'";
	 }
  }
  
 ?>
<div <?php echo $style; ?> class="post inactive userData mainPost" id="post_<?php echo $post['PALL']['post_id']; ?>">
	
    	<div class="ax">
		  <a href="<?php echo __SITE_URL.$post['PALL']['parent_user_name'] ?>">
			<?php  		  
				if(fileExistsInPath(__USER_IMAGE_PATH.$post['PALL']['post_user_image'] ) && $post['PALL']['post_user_image']!='' ) 
				{
					echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$post['PALL']['post_user_image'],array('id'=>'image_img'));
				}
				else
				{
					if($post['PALL']['post_user_sex']==0)
					  echo $this->Html->image('profile_women.png',array('id'=>'image_img')); 
					elseif($post['PALL']['post_user_sex']==1)
					  echo $this->Html->image('profile_men.png',array('id'=>'image_img')); 
					elseif($post['PALL']['post_user_sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img'));   
				}	
			 ?>
		  </a>	 
		</div>
	
		<?php 
		  if(isset($post['PALL']['parent_user_name'])) {
		  	$post_id=$this->requestAction(__SITE_URL.'posts/get_paret_post_id/'.$post['PALL']['post_id']);
			$post_id = $post_id['id'];
		  }	
		  else $post_id = $post['PALL']['post_id'];
		?>
        <header class="data" style="min-width: 541px;width:541px;">
            <h2 class="userName">
				<a href="<?php echo __SITE_URL.$post['PALL']['post_user_name'] ?>">
						<?php echo $post['PALL']['post_name']; ?>
				</a>
			</h2>
            <a class="userAtSign" href="<?php echo __SITE_URL.$post['PALL']['post_user_name']?>">@<?php echo $post['PALL']['post_user_name']; ?>
			</a>

            <span class="date" id="learn_post_date">
			    <?php echo "<a href='".__SITE_URL."posts/view/".$post['PALL']['post_id']."'>"; ?>
				<?php  
					echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($post['PALL']['created']));  
				?>
			</a></span>

        </header>
        <article style="min-width: 550px;width:84%;">
            <?php 
			if(isset($post['PALL']['parent_user_name'])) {  
		  echo"<a href='".__SITE_URL.$post['PALL']['parent_user_name']."'>
					@".$post['PALL']['parent_user_name']." :
			</a>";
		  }
			
			
			$body = $post['PALL']['body'];		
			
			if($this->request->params['action']=='view')
			{
				echo $this->Gilace->filter_editor($body); 
			}else{
				echo $this->Gilace->filter_editor(mb_substr($body,0,200)); 
				if(mb_strlen($body)>200){
					echo " ... <a class='userAtSign' href='".__SITE_URL."posts/view/".$post['PALL']['post_id']."'>".__('continue')."</a>";
				}	
			}
				
			
			?>
        </article>
		<?php					    
			if(!empty($post['PALL']['url']) && $post['PALL']['url']!='')
				 echo "<div class='tile size_2 violet1 postLink'><div class='symbol'></div></div>";
		?>
		
		<div class="imagePlace">
		    <figure>
		        <?php 			   
				if(!empty($post['PALL']['url']) && $post['PALL']['url']!='')
					{
						echo"<figcaption>";
						echo"<a rel='nofollow' href='".$post['PALL']['url']." ' target='_blank'>
		                   ".$this->Gilace->filter_url($post['PALL']['url'])."               
					       </a>
						  </figcaption>
						";
					}
			  ?>
			  <a href="<?php echo __SITE_URL.$post['PALL']['post_user_name'] ?>">
				  <div id="imagePlace_<?php echo $post['PALL']['post_id']; ?>">
				  		<?php
							if(!empty($post['PALL']['image']) && $post['PALL']['image']!='')
							{
								echo $this->Html->image('/'.__POST_IMAGE_PATH.$post['PALL']['image'],array('class'=>'bigimg')); 
							}
						  ?>
	                      
				  </div>
			  </a>
		   </figure>
		</div> 	  
		
		 
        <div class="clear"></div>
		
        <footer>
		<?php if(isset($User_Info)){ ?>
			<div class="socialNetwork" id="share_body_<?php echo $post['PALL']['post_id']; ?>">
            	
				<?php if($is_comment){ 
					echo "<div class='tile comment gray2' 
					onclick=set_comment('".$post['PALL']['post_user_name']."','".$post['PALL']['post_id']."','".$post_id."','".$post['PALL']['user_id']."')>"; 
				
				}else {
					
					if($in_paginate){
						if(!isset($post['PALL']['parent_user_name'])|| empty($post['PALL']['parent_user_name'])) { 
					echo "<div class='tile comment gray2' id='learn_post_comment'
					onclick=expand_post('expand_". $post['PALL']['post_id']."',". $post['PALL']['post_id'].",0) >"; }
					else echo "<div class='tile comment gray2' onclick=window.location.href='".__SITE_URL."posts/view/".$post_id."'>"; 
					}else  echo "<div class='tile comment gray2'  onclick=set_comment('".$post['PALL']['user_name']."','".$post['PALL']['post_id']."','".$post_id."','".$post['PALL']['user_id']."')  >";
				}
				?>	
				
            		<div class="symbol"></div> 
					<?php echo($post['PALL']['commnet_count']); ?>
				</div>
				
				
				<?php
				 
				if($post['PALL']['type']>0 ){
					if($User_Info['id']==$post['PALL']['share_user_id']) { 
						echo "<div class='tile unshare lajani' id='share_btn_".$post['PALL']['post_id']."' 
						 onclick=paginate_unshare(".$post['PALL']['post_id'].")>";
					}
					else
					{
						echo "<div class='tile share lajani' id='share_btn_".$post['PALL']['post_id']."' 
						 onclick=paginate_share(".$post['PALL']['post_id'].")>";
					}
				?>
            	<span class="learn_post_share" style="">&nbsp;</span>
				<?php if(isset($post['PALL']['share_user_id'])&&$post['PALL']['share_user_id']>0) { ?>
                    <span class="more"><?php echo __('shared') ?>
					 <a href="<?php echo __SITE_URL.$post['PALL']['share_user_name'] ?>">
					  	@<?php echo $post['PALL']['share_user_name'] ?>
					  </a></span> 
				<?php } ?>
				<?php if($User_Info['id']!=$post['PALL']['share_user_id']){ ?>	
                    <span class="more"></span>
                    <span class="numb">
						<div id="share_body_<?php echo $post['PALL']['post_id']; ?>" style="float:right">
							<span id="share_count_<?php echo $post['PALL']['post_id']; ?>"> 
							<?php echo($post['PALL']['sharecount']);  ?></span>
							<span id="share_loading_<?php echo $post['PALL']['post_id']; ?>"></span>
                    		<div class="symbol"></div>
							<input type="hidden"  name="" id="share_post_user_id_<?php echo $post['PALL']['post_id']; ?>" value="<?php echo $post['PALL']['share_user_id']; ?>"/> 
						</div>	
                    </span>
				<?php } ?>	
                </div>
				<?php }  ?>
			<?php if($User_Info['id']==$post['PALL']['post_user_id']){ 
                if(isset($post['PALL']['parent_id'])) {
						$post_id_arr=$this->requestAction(__SITE_URL.'posts/getdelete_postchilds/'.$post['PALL']['post_id']);
						
					  }else $post_id_arr=array(0);
					  if(empty($post_id_arr)){
						$post_id_arr = array(0);
					  }
			 
			  echo "<div class='tile delete red' onclick='delete_post_confirm(".$post['PALL']['post_id'].",".json_encode($post_id_arr).")' >";
			  echo"<span id='delete_post_loading_". $post['PALL']['post_id']."'></span>";
			?>
			
				<div class="symbol"></div>
				<?php echo __('delete') ?>
			</div>	
				
			<?php 
			
					echo " <div id='ads' onclick=ads_post_form(".$post['PALL']['post_id'].") class='learn_post_report'>
	    				".__('ads_post')."  </div>	";
			  } 
            
                if($User_Info['id']!=$post['PALL']['user_id']){
    				echo "<div id='ads' onclick=send_infraction_report_post_form(".$post['PALL']['post_id'].") class='learn_post_report'>
    				".__('send_infraction_report_post')." </div>	 ";
					
					 
					
    				echo "<span id='infraction_report_post_loading_".$post['PALL']['post_id']."'></span>";
			    }
            
			?>
			
            </div>
			
			
			
            <?php 
            
			
				 
            
			if($in_paginate){
				if(!isset($post['PALL']['parent_user_name'])|| empty($post['PALL']['parent_user_name'])) { 
				?>
				<div class="maximize" id="expand_<?php echo $post['PALL']['post_id']; ?>" 
				onclick="expand_post(expand_<?php echo $post['PALL']['post_id']; ?>,<?php echo $post['PALL']['post_id']; ?>,0);">	
				</div>
				<?php }else echo "<a class='maximize' target='_blank' href='".__SITE_URL."posts/view/".$post_id."'></a>"; 
			  }
			 ?>
			 
			 <?php } // end of check login ?>
			 
			<?php if(isset($User_Info)){ ?> 
            <span id="favorite_body_<?php echo $post['PALL']['post_id']; ?>"  >
				<span class="learn_post_favorite" style="float:left;position: absolute;left: -20px;top: 20px;">&nbsp;</span>
				<?php if($post['PALL']['me_favorite']>0){  ?>
				<div class="favorite tile size_2 outOfBox green" style="display: none;" onclick="paginate_unfavorite(<?php echo $post['PALL']['post_id']; ?>)" id="favorite_btn_<?php echo $post['PALL']['post_id']; ?>">
				<?php }else{?> 
				<div class="favorite tile size_2 outOfBox atashi" style="display: none;" onclick="paginate_favorite(<?php echo $post['PALL']['post_id']; ?>)" id="favorite_btn_<?php echo $post['PALL']['post_id']; ?>">
				<?php } ?>
					<span id="favorite_loading_<?php echo $post['PALL']['post_id']; ?>"></span>
					<div class="symbol"></div>
				</div>
			</span>
			<?php } ?>
			
			
			
			<a href="http://www.facebook.com/sharer.php?u='<?php echo __SITE_URL."posts/view/".$post['PALL']['post_id'] ?>?&t=<?php echo $post['PALL']['body']; ?>" target="_blank" title="<?php echo __('share_in_facebook') ?>"  rel="nofollow" >
	            <div class="fbShare tile size_2 outOfBox blue1" style="display: none;">
				<div class="symbol"></div>
				</div>
	            <span class="learn_post_facebook" style="position: absolute;left: -26px;top: 60px;">&nbsp;</span>
            </a>
            
            
			<a href="https://plus.google.com/share?url=<?php echo __SITE_URL."posts/view/".$post['PALL']['post_id'] ?>" title="<?php echo __('share_in_gplus') ?>" target="_blank" rel="nofollow">
	            <div class="gplusShare tile size_2 outOfBox red" style="display: none;">
				<div class="symbol"></div>
				</div>
	            <span class="learn_post_facebook" style="position: absolute;left: -26px;top: 60px;">&nbsp;</span>
            </a>
            
            
            
            
            <div class="linkedInShare tile size_2 outOfBox1 blueLinkedIn" style="display: none;" style="display:none">
			<div class="symbol"></div></div>
        </footer>
		
		<div class="clear"></div>
    </div>
	
	 
	