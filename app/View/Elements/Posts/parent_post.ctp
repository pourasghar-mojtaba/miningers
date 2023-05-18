<?php
 // pr($post);
  $User_Info= $this->Session->read('User_Info');
  $style='';
  if(!empty($is_ads)){
  	if($is_ads){
	  	$style="style='background:#f4d279'";
	 }
  }
  
  
  if($post['PALL']['post_user_id']==$User_Info['id']){
      if(isset($post['PALL']['parent_id'])) {
		$post_id_arr=$this->requestAction(__SITE_URL.'posts/getdelete_postchilds/'.$post['PALL']['post_id']);
		
	  }else $post_id_arr=array(0);
	  if(empty($post_id_arr)){
		$post_id_arr = array(0);
	  }
  }
    
  if($post['PALL']['sharecount'] > 0){
  		$share_users=$this->requestAction(__SITE_URL.'allposts/get_share_post_users/'.$post['PALL']['post_id']);
  }
  
  if(isset($post['PALL']['parent_user_name'])) {
  	$post_id=$this->requestAction(__SITE_URL.'posts/get_paret_post_id/'.$post['PALL']['post_id']);
	$parent_id = $post_id['id'];
  }	
  else $parent_id = $post['PALL']['parent_id'];
  
  if($parent_id=='') $parent_id =0;
  
 ?>
 
  
 <div class="post_body">
		<div id="post_<?php echo $post['PALL']['post_id']; ?>" class="post <?php if($is_comment) echo 'answer ';elseif($is_ads) echo 'ads';else if(!$in_paginate) echo "answer direct";  ?>">
            <header>
                <div class="postStatus">
                    <?php
                       //echo $post['PALL']['sharecount'];
					    if($post['PALL']['sharecount']>0){ 
							//if($post['PALL']['post_user_id']!=$User_Info['id']){
								$sharepost_info=$this->requestAction(__SITE_URL.'shareposts/get_sharepost_info/'.$post['PALL']['post_id']);
								if(!empty($sharepost_info)){
									if($sharepost_info['User']['share_user_id']==$User_Info['id']){
										echo __('shared')." <a href='".__SITE_URL.$sharepost_info['User']['share_user_name']."'  target='_blank' >".__('me')."</a>";
									}else
		                            echo __('shared')." <a href='".__SITE_URL.$sharepost_info['User']['share_user_name']."'  target='_blank' >@".$sharepost_info['User']['share_user_name']."</a>";
		                        }
						 // }	
						}
                    
                    ?>
                </div>
                <div class="postTranslate">
                    <!--translate with bing-->
                </div>
                <div class="clear"></div>
            </header>
            <article class="embedEdit">
				<?php
					if($post['PALL']['blog_id']==0){
				?>
                <div class="dropdown editIcon" role="menu">
				
                    <?php if(!empty($User_Info)){ ?> <div class="icon icon-ellipsis dropdownBtn"></div> <?php } ?>
                    <ul>
                        <?php if($post['PALL']['post_user_id']==$User_Info['id']){ ?>
	                        <?php if($User_Info['country_id']==104){	 ?>
								<li onclick="popUp('<?php echo __SITE_URL; ?>postads/ads_post_form','id=<?php echo $post['PALL']['post_id'] ?>');" ><?php echo __('promote_post') ?>
								</li>
							
                        	<?php } ?>
                        <?php } ?>
                        <?php if($post['PALL']['post_user_id']!=$User_Info['id']){ ?>
                        <li onclick="popUp('<?php echo __SITE_URL; ?>infractionreportposts/send_infraction_report_post','action=load_page&id=<?php echo $post['PALL']['post_id'] ?>');" >
                            <?php echo __('report') ?>                           
                        </li>
                        <?php } ?>
                        <!--
                        <?php if($post['PALL']['post_user_id']==$User_Info['id']){ ?>
                        <li><?php echo __('edit_post') ?></li>
                        <?php } ?>-->
						
                        <?php if($post['PALL']['post_user_id']==$User_Info['id'] ){ ?>
                        <li  <?php echo"onclick='delete_post_confirm(".$post['PALL']['post_id'].",".json_encode($post_id_arr).")'; " ?>>
                            <?php echo __('delete_post') ?>                            
                        </li>
                        <?php } ?>
                    </ul>
                </div>
				<?php } ?>
                <div class="imagePlace">
                    <a href="<?php echo __SITE_URL.$post['PALL']['post_user_name'] ?>">
                        <div class="ax">
                            <?php 
								echo $this->Gilace->user_image($post['PALL']['post_user_image'],$post['PALL']['post_user_sex'],$post['PALL']['post_user_name'],''); 		               					
                			 ?>
                        </div>
                    </a>
                </div>
				
                <div class="textPlace dataArticle">
                    <div class="fontSize17">
					<!--<span><?php echo $post['PALL']['post_name'] ?></span>-->
                    <a href="<?php echo __SITE_URL.$post['PALL']['post_user_name'] ?>" class="atSign"> 
                    <?php echo $post['PALL']['post_name'] ?>
                    </a>:</div>
                    
					<a class="date" href="<?php 
					if($post['PALL']['blog_id']!=0){
						echo __SITE_URL."blogs/view/".$post['PALL']['blog_id'];
					}else
					echo __SITE_URL."posts/view/".$post['PALL']['post_id'];
					
					 ?>">
                        <?php  
        					if(($post['PALL']['created']!=$post['PALL']['all_created']) && ($post['PALL']['all_created']!='0000-00-00 00:00:00')){
								$created = $post['PALL']['all_created'];
							}else $created = $post['PALL']['created'];
							
							if($locale =='per')
                                echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($created));  
                            if($locale =='eng')
                                echo date("Y/m/d - H:i",strtotime($created));    
        				?>
                    </a>
					
                    <?php 
						echo "<div id='postcontent_".$post['PALL']['post_id']."'>";
						if(!empty($post['PALL']['parent_user_name'])){ ?>
                        <a href="<?php echo __SITE_URL.$post['PALL']['parent_user_name'] ?>" class="atSign">@
                            <?php echo $post['PALL']['parent_user_name'] ?>
                        </a> : 
						
                    <?php } 
						
                        $body = $post['PALL']['body'];	
						echo $this->Gilace->filter_editor($body); 
						/*				
            			if($this->request->params['action']=='view')
            			{
            				echo $this->Gilace->filter_editor($body); 
            			}else{
            				echo $this->Gilace->filter_editor(mb_substr($body,0,200)); 
            				if(mb_strlen($body)>200){
            					echo " ... <a class='userAtSign' href='".__SITE_URL."posts/view/".$post['PALL']['post_id']."'>".__('continue')."</a>";
            				}	
            			}*/
                    ?>  
					</div>
					<?php if(!empty($post['PALL']['url']) && $post['PALL']['url']!=''){ ?>  
                     <!--<div class="Horizontal_bar"></div>-->
					 <?php } ?>
                     <div class="translation">
                     <!--it is translation of this text :D-->
                     </div>
                     
                     <?php if(!empty($post['PALL']['url']) && $post['PALL']['url']!=''){ ?>
                      <?php  if(empty($post['PALL']['url_content']) && empty($post['PALL']['url_title'])){ ?>
						  <div class="media">
	                        <div class="linkBox">
	                            <div class="tile box33x33 purple">
	                                <span class="icon icon-link-1"></span>
	                            </div>
	                            <a href="<?php echo $post['PALL']['url']; ?>" target="_blank">
	                                <?php
	                                   echo  $this->Gilace->filter_url($post['PALL']['url'])
	                                ?>
	                            </a>
	                        </div>
	                     </div> 
					 <?php }else{ ?>
					   <div class="link_preview">
						 <div class="thumb">
						 	<img src="<?php echo $post['PALL']['url_image'];  ?>">
						 </div>
						 <div class="title"><?php echo $this->Gilace->convert_character_editor($post['PALL']['url_title']);  ?></div>
						 	<div class="desc"><?php echo $this->Gilace->filter_editor($post['PALL']['url_content']);  ?></div>
							<div class="url">
								<a href="<?php echo $post['PALL']['url']; ?>" target="_blank">
	                                <?php
	                                   echo  $this->Gilace->filter_url($post['PALL']['url'])
	                                ?>
	                            </a>
							</div>
							
							
						 <div class="clear">  </div>
			   		   </div>
					   <div class="clear">  </div>
                     <?php }
					 
					 } ?>
                     
                     <?php if(!empty($post['PALL']['image']) && $post['PALL']['image']!=''){ ?>
					 <div class="media">
                        <figure class="imgBox">
                            <!--<div class="tile box33x33 red">
                                <span class="icon icon-camera-1"></span>
                            </div>-->
                           <?php
						   	  echo $this->Html->image('/'.__POST_IMAGE_PATH.$post['PALL']['image']); 
						   ?>
                        </figure>
                     </div>
					 <?php } ?>
					 
					 <?php if(!empty($post['PALL']['video']) && $post['PALL']['video']!=''){ ?>
					 <div class="media">
                        <figure class="videoBox">
                           <?php
						   	  echo $this->Gilace->filter_editor($post['PALL']['video']);
						   ?>
                        </figure>
                     </div>
					 <?php } ?>
					 
					 
					 <?php if(!empty($User_Info)){ ?>
					 
	                     <ul class="socialActivities">
	                        <li>
								<span id="favorite_body_<?php echo $post['PALL']['post_id']; ?>"  >
									<?php 
									/*if($post['PALL']['blog_id']!=0){
											echo "<a href='".__SITE_URL."blogs/view/".$post['PALL']['blog_id']."' >".__('favorite')."</a>";
										}else{*/
										if($post['PALL']['me_favorite']>0){
											echo "<span id='favorite_btn_".$post['PALL']['post_id']."' onclick='paginate_unfavorite(".$post['PALL']['post_id'].")'>".__('notfavorite')."</span>"; 
										}
										else{
											echo "<span  id='favorite_btn_".$post['PALL']['post_id']."' onclick='paginate_favorite(".$post['PALL']['post_id'].")' >".__('favorite')."</span>"; 
										}
									// }												
									?>	
								</span>
								<span id="favorite_loading_<?php echo $post['PALL']['post_id']; ?>"></span>
							</li>
							<?php
								echo "<li>";
								if($post['PALL']['blog_id']!=0){
									echo "<a href='".__SITE_URL."blogs/view/".$post['PALL']['blog_id']."' >".__('replay')."</a>";
								}else{
							?>
	                        <li onclick="replay_post(this,<?php echo $post['PALL']['post_id']; ?>,<?php if(!$in_paginate) echo 0;elseif(!$is_comment) echo $post['PALL']['parent_id']; else echo 0; ?>,<?php echo $parent_id; ?>)">
							<?php echo __('replay'); ?>	
							<?php } ?>
												
							</li>
	                        <?php if(isset($User_Info)){if($post['PALL']['post_user_id']!=$User_Info['id']){ ?>
							<li>
								<span id="share_body_<?php echo $post['PALL']['post_id']; ?>"  >
									<?php 
									//echo $post['PALL']['share_user_id'];
										if(($post['PALL']['type']>0 && $User_Info['id']==$post['PALL']['share_user_id']) || (!empty($post['PALL']['me_share']) && $post['PALL']['me_share']>0)){
											echo "<span id='share_btn_".$post['PALL']['post_id']."' onclick='paginate_unshare(".$post['PALL']['post_id'].",".$post['PALL']['post_user_id'].")'>".__('notshare')."</span>"; 
										}
										else{
											echo "<span  id='share_btn_".$post['PALL']['post_id']."' onclick='paginate_share(".$post['PALL']['post_id'].",".$post['PALL']['post_user_id'].")' >".__('share')."</span>"; 
										}											
									?>	
								</span>
								<span id="share_loading_<?php echo $post['PALL']['post_id']; ?>"></span>
							</li>
							<?php }} 
							
							if($post['PALL']['blog_id']!=0){
									 
								}else{
							?>
							
	                        <li> 
                                <?php if($post['PALL']['post_user_id']==$User_Info['id']){ ?>
								<?php if($User_Info['country_id']==104){	 ?>
		                        <div class="postStatus" style="color:#e8c104" 
									onclick="popUp('<?php echo __SITE_URL; ?>postads/ads_post_form','id=<?php echo $post['PALL']['post_id'] ?>');" >
									<?php echo __('promote_post') ?> 
								
								</div>
								<?php } ?>
								<?php }else { ?>
								<div class="postStatus" style="color:#e8c104">
                                    <?php
                                       if(!empty($User_Info)){
									   		$adspost_info=$this->requestAction(__SITE_URL.'postads/get_adspost_info/'.$post['PALL']['post_id'].'/'.$User_Info['id']);
										     
											if(!empty($adspost_info))
	                                        {
	                                            echo __('promote_post_by')." <a href='".__SITE_URL.$adspost_info['User']['ads_user_name']."'  target='_blank' >@".$adspost_info['User']['ads_user_name']."</a>";
	                                        } 
									   }
									                                      
                                    ?>
                                </div>
								<?php } ?>
                            </li>
							<?php } ?>
	                     </ul>
						 <?php } ?>
					 
					 <?php if($post['PALL']['sharecount']>0 || $post['PALL']['favoritecount']){ ?>
                     <div class="resultAndsuggest">

                        <div class="result col-sm-5">
                            <?php if($post['PALL']['sharecount']>0){ ?>
							<div>
                                <span><?php echo __('share'); ?></span>
                                <div><?php echo $post['PALL']['sharecount']; ?></div>
                            </div>
							<?php } ?>
							<?php if($post['PALL']['favoritecount']>0){ ?>
                            <div>
                                <span><?php echo __('favorite'); ?></span>
                                <div><?php echo $post['PALL']['favoritecount']; ?></div>
                            </div>
							<?php } ?>
                        </div>
						<?php
							if(!empty($share_users)){
                                //pr($share_users);
								echo "<div class='suggest col-sm-7'>";
							 foreach($share_users as $share_user){
							 	
								echo "<a href='".__SITE_URL.$share_user['User']['user_name']."' class='ax masterTooltip' title='".$share_user['User']['name']."'  >";
		                        	echo $this->Gilace->user_image($share_user['User']['image'],$share_user['User']['sex'],$share_user['User']['user_name'],''); 
								echo"</a>";								
							 }
							 echo"</div>";
      		              }           
						?>
                     </div>
					 <?php } ?>
               </div>
            </article>
       </div>
       <div id="answer_place_<?php echo $post['PALL']['post_id']; ?>">
	   	
			<div class="post postReply" style="display:none" id="replay_post_<?php echo $post['PALL']['post_id']; ?>">
	            <?php echo $this->Form->create('Post', array('id'=>'AddCommentForm_'.$post['PALL']['post_id'],'name'=>'AddCommentForm','class'=>'myForm','enctype'=>'multipart/form-data','action'=>'/add_comment')); ?>
	                <div class="insertNewPost">
	                        <div class="col-sm-12">
	                            <div class="textBoxCounter">
	                                <?php echo $this->Form->textarea('newcomment_input',array('label'=>'','type'=>'text','id'=>'newcomment_input_'.$post['PALL']['post_id'],'rows'=>5,'maxlength'=>500,'placeholder'=>__('answer_to').' @'.$post['PALL']['post_user_name'],'class'=>'myFormComponent notTrans fixHeight')); ?>
	                                <input type="hidden"  name="data[Post][parent_id]" id="commnet_post_id_<?php echo $post['PALL']['post_id'] ?>" 
	                                value="<?php echo $post['PALL']['post_id'] ?>"/>
			                        <input type="hidden" id="parent_commnet_post_id_<?php echo $post['PALL']['post_id'] ?>" 
	                                value="<?php echo $post['PALL']['post_id'] ?>" />
			                        <input type="hidden"  id="commnet_post_user_id_<?php echo $post['PALL']['post_id'] ?>" 
	                                value="<?php echo $post['PALL']['post_user_id']; ?>" name="data[Post][commnet_post_user_id]" />
									
									<input type="hidden" value="<?php if($parent_id==0) echo $post['PALL']['post_id']; else echo $parent_id; ?>" name="data[Post][main_parent_post_id]" />
	                                <span class="counter">500</span>
	                            </div>
	                        </div>
	                        <div class="col-sm-12" style="display: none" id="newcomment_link_box_<?php echo $post['PALL']['post_id'] ?>">
	                            <div class="insertLinkBox">
	                                <input name="data[Post][newcomment_link]" id="newcomment_link_<?php echo $post['PALL']['post_id'] ?>" type="text" placeholder="<?php echo __('insert_link_in_box') ?>" class="myFormComponent ltr" onkeyup="load_comment_link_preview(<?php echo $post['PALL']['post_id'] ?>);">
	                                <div class="tile box33x33 trans clearInput" onclick="clear_comment_link(<?php echo $post['PALL']['post_id'] ?>)">
	                                    <span class="icon icon-cancel"></span>
	                                </div>
	                            </div>
	                        </div>
							
							<div class="col-sm-12" style="display: none" id="newcomment_video_box_<?php echo $post['PALL']['post_id'] ?>">
		                        <div class="insertLinkBox">
		                            <input  type="text" id='newcomment_video_<?php echo $post['PALL']['post_id'] ?>' name="data[Post][video]" 
									placeholder="<?php echo __('insert_video_link_in_box') ?>" class="myFormComponent ltr" onkeyup="get_video(<?php echo $post['PALL']['post_id'] ?>);">
		                            <div class="tile box33x33 trans clearInput" onclick="clear_comment_video(<?php echo $post['PALL']['post_id'] ?>)">
		                                <span class="icon icon-cancel"></span>
		                            </div>
		                        </div>
		                    </div>
							
							<input type="hidden" id="url_title_<?php echo $post['PALL']['post_id'] ?>" name="data[Post][url_title]" />
							<input type="hidden" id="url_content_<?php echo $post['PALL']['post_id'] ?>" name="data[Post][url_content]" />
							<input type="hidden" id="url_image_<?php echo $post['PALL']['post_id'] ?>" name="data[Post][url_image]" />
							
	                        <div class="col-sm-12">
	                            <div id="attachment_place_<?php echo $post['PALL']['post_id'] ?>"></div>    
	    		                <div id="comment_result_<?php echo $post['PALL']['post_id'] ?>" style="float:right"></div>
	                        </div>
							<div class="col-sm-12">
								<div class="url_loading" id="url_loading_<?php echo $post['PALL']['post_id'] ?>"></div>
								<div class="preview_bord">
									<img  class="add_image" id="add_image_<?php echo $post['PALL']['post_id'] ?>"  />
									<div id="video_preview_<?php echo $post['PALL']['post_id'] ?>"></div>
									<div class="extract" style="display: none;" id="extract_<?php echo $post['PALL']['post_id'] ?>">
						   			 <div class=""><a class="delete float-right" href="#"></a></div>
									 <div class="float-left extract-thumb" id="extract-thumb_<?php echo $post['PALL']['post_id'] ?>">
									 </div>
									 <div class="float-left extract-info">
										<span id="title_<?php echo $post['PALL']['post_id'] ?>"></span>
										<span id="url_<?php echo $post['PALL']['post_id'] ?>"></span>
										<span id="desc_<?php echo $post['PALL']['post_id'] ?>"></span>
										<div class="nav">
											<img id="prev" src="<?php echo __SITE_URL.'img/icons/prev.gif'; ?>">
											<img id="next" src="<?php echo __SITE_URL.'img/icons/next.gif'; ?>">
											<span id="navount"></span>
										</div>
									 </div>
						   		   </div>
							    </div>
							</div>
							
	                        <!--<div class="col-sm-6">
	                            <div class="fileUpload myFormComponent">
	                                <div class="btn red uploadBtn" id="newcomment_add_image_<?php echo $post['PALL']['post_id'] ?>">
	                                    <span class="icon icon-camera-1"></span>
	                                    <span class="text"><?php echo __('addimage'); ?></span>
	                                </div>
	                                <?php echo $this->Form->input('newcomment_image',array('label'=>'','type'=>'file','id'=>'newcomment_image_'.$post['PALL']['post_id'])); ?>
	                            </div>
	                        </div>-->
							
							<div class="tile box33x33 trans " onclick="show_link(<?php echo $post['PALL']['post_id'] ?>);">
		                        <span class="icon icon-link"></span>
		                    </div>
							<div class="tile box33x33 trans " onclick="show_image(<?php echo $post['PALL']['post_id'] ?>);">
		                        <span class="icon icon-camera-1"></span>
								
		                    </div>
							<input type="file" style="position:absolute;top:-20000px;" id='newcomment_image_<?php echo $post['PALL']['post_id'] ?>' name="data[Post][newcomment_image]">
							<div class="tile box33x33 trans " onclick="show_video(<?php echo $post['PALL']['post_id'] ?>);">
		                        <span class="icon icon-video"><img src="<?php echo __SITE_URL.'img/icons/video.png' ?>" /></span>
		                    </div>
							
	                        <div class="col-sm-9 upload_location">
	                            <button role="button" type="submit" class="myFormComponent green">
	                                <span class="text"><?php echo __('send_post'); ?></span>
	                                <span class="icon icon-left-open"></span>
	                            </button>
	                            <div id="comment_loading_<?php echo $post['PALL']['post_id'] ?>" style="float:left;margin-left: 
								5px"> </div>
	                        </div>
							<div class="clear"></div
	                </div>
					
					
	                <div class="clear"></div>
					<span id="post_category_<?php echo $post['PALL']['post_id'] ?>">
						
					</span>
	                <div class="clear"></div>
	            </form>
	        </div>
			
		   </div> 
  </div>
<?php
	//print_r($this->request->params['action']);
?>
<script>
	 
	$('#categoryposts li').each(function(i)
	{
	   if($(this).attr('check_value')==1){
	   	var post_input= "<input type='radio' name='data[Post][categorypost_id]' checked value="+$(this).attr('value')+">"+$(this).attr('title');
	   }else
	    var post_input= "<input type='radio' name='data[Post][categorypost_id]' value="+$(this).attr('value')+">"+$(this).attr('title');
		
	   $('#post_category_'+<?php echo $post['PALL']['post_id'] ?>).append(post_input);
	});
	
	function show_link(id){
		$('#newcomment_link_box_'+id).fadeIn(400);	
	};	 
	function show_video(id){
		$('#newcomment_video_box_'+id).fadeIn(400);	
	};
	
	function clear_comment_video(id){
		$('#video_preview_'+id).html("");
		$('#newcomment_video_box_'+id).fadeOut(400);
		$('#newcomment_video_'+id).val("");
		$("#url_loading_"+id).css("display","none");
	}
	
	function clear_comment_link(id){
		$('#newcomment_link_box_'+id).fadeOut(400);
		$('#newcomment_link_'+id).val("");	
		$("#extract_"+id).fadeOut("slow");
		$("#url_loading_"+id).css("display","none");
	}
	
	function clear_comment_image(id){
		$("#add_image_"+id).fadeOut("slow");
		$("#newcomment_image_"+id).val("");
		$('#newpost_image_attachment_'+id).parent().fadeOut(200);
		$('#newpost_image_attachment_'+id).remove();
		$("#url_loading_"+id).css("display","none");
	}
	
	function get_video(id){
		clear_comment_link(id);
		clear_comment_image(id);
		$('#video_preview_'+id).html($("#newcomment_video_"+id).val());
	};
	
	$('#newcomment_input_'+<?php echo $post['PALL']['post_id'] ?>).on('paste', function () {
	  var element = this;
	  setTimeout(function () {	  	 
	    var text = $(element).val();	
			
		urls = text.match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/);
		if(urls!=null){
			for (var i = 0, il = urls.length; i < il; i++) {
			    var url=urls[i];
												
				if($('#newcomment_link_'+<?php echo $post['PALL']['post_id'] ?>).val()==''){
					$('#newcomment_link_'+<?php echo $post['PALL']['post_id'] ?>).val(url);
					$('#newcomment_link_box_'+<?php echo $post['PALL']['post_id'] ?>).fadeIn(400);	
					var str = text.replace(url, "");
					$('#newcomment_input_'+<?php echo $post['PALL']['post_id'] ?>).val(str);
					load_comment_link_preview(<?php echo $post['PALL']['post_id'] ?>);
				}				
				break;
			 }
		}
		
	  }, 100);
	});
	
	function getBase64FromImageUrl(e,id) {		
		clear_comment_link(id);
		clear_comment_video(id);
		var img = new Image();
		var url = URL.createObjectURL(e.target.files[0]);
	    img.src = url;
	    img.onload = function () {
	    	var canvas = document.createElement("canvas");
		    canvas.width =this.width;
		    canvas.height =this.height;
		    var ctx = canvas.getContext("2d");
		    ctx.drawImage(this, 0, 0);
		    var dataURL = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
		    //alert(  dataURL.replace(/^data:image\/(png|jpg);base64,/, ""));
			$('#add_image_'+id).attr('src',dataURL.replace(/^data:image\/(png|jpg);base64,/, ""));
			$("#add_image_"+id).css("display","block");
	    }
	  } 
	
	function show_image(id){
		$('#newcomment_image_'+id).trigger('click');
		var input = document.getElementById('newcomment_image_'+id);
     		input.addEventListener('change', function(e) {getBase64FromImageUrl(e,id)});
	};
	 
	
	function load_comment_link_preview(id){
		
	   	 var imgArray;
		 var title;
		 var desc;
		 var index = 0;
		 var link = $("#newcomment_link_"+id).val();
		  
		 if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#newcomment_link_"+id).val()))
		 {
			 clear_comment_image(id);
			 clear_comment_video(id);
			 
			 imgArray = new Array();
			 index = 0;
			 title = "";
			 desc = "";
			 $("#url_loading_"+id).css("display","block");
			 if(link.length){
				 // Encode url so we can have single line url instead of different parts
				 elink = encodeURIComponent(link);
				 $.ajax({
				   type: "POST",
				   url: _url+'posts/extract_url',
				   data: "link="+elink,
				   success: function(responce){	
					if(responce != "0")
					{
					  var json = $.parseJSON(responce);
					  $.each(json, function(key, val) {
						//alert(val.src);
						
						if(val.src != null){
							imgArray.push(val.src);
							$("#trick").attr("src",val.src);
							//$(".array").append("<br>"+val.src);
						}
						
						//console.log(val);
						if(val.title != null)
							title = val.title;
						if(val.url != null)
							link = val.url;
						if(val.desc != null)
							desc = val.desc;
						
					  });
						//alert(title);
						if(imgArray.length > 0){
							// if images found then show nav icons
							//$(".nav").show();
							$(".nav").hide();
							// also hide image holder
							$("#extract-thumb_"+id).css("visibility","visible");

							if($("#extract-thumb_"+id).html() == "")
							   $("#extract-thumb_"+id).append('<img src="'+imgArray[0]+'" >');
							else
							   $("#extract-thumb_"+id).html('<img src="'+imgArray[0]+'" >');
						}else{
							// if images not found then hide nav icons
							$(".nav").hide();
							// also hide image holder
							$("#extract-thumb_"+id).css("visibility","hidden");
						}
						//console.log(title);
						$(".extract-info #title_"+id).html(title);
						$(".extract-info #url_"+id).html(link);
						$(".extract-info #desc_"+id).html(desc);
						
						$("#url_title_"+id).val(title);
						$("#url_content_"+id).val(desc);
						$("#url_image_"+id).val(imgArray[0]);
						
						//showcount(index);
						$("#extract_"+id).slideDown("slow");
					 }else{
						show_warning_msg('<?php echo __("this_url_doesnt_exists")?>');
						
					 }
					 $("#url_loading_"+id).css("display","none");
				   }	
				});

			 }else{
				show_warning_msg('<?php echo __("please_enter_link")?>');
			 }
		 }else{
			show_warning_msg('<?php echo __("enter_correct_link")?>');
			$("#extract_"+id).fadeOut("slow");
	   }
	}


	_answer_to = '<?php echo __('answer_to'); ?>';
	_show_comment = '<?php echo !$in_paginate; ?>';
	_action = "<?php echo $this->request->params['action']; ?>"; 
    checkdir('<?php echo substr($post["PALL"]["body"],0,1)?>','<?php echo $post["PALL"]["post_id"]?>');
	function checkdir(body,post_id){
		if(body.charAt(0).charCodeAt(0) < 200 )
		  {
		  	$('#postcontent_'+post_id).css('direction','ltr');
			$('#postcontent_'+post_id).css('text-align','left');
		  } else{
		  	$('#postcontent_'+post_id).css('direction','rtl');
			$('#postcontent_'+post_id).css('text-align','right');
		  } 
	}
	
    $('.clearInput').click(function(e) {
            var parent = $(this).parent();
    	    $('input',parent).val('');
        });
       textBoxCounter(500);
    
	jQuery(document).ready(function(){
		
		var commnet_post_id = $('#commnet_post_id_'+<?php echo $post['PALL']['post_id'] ?>).val();
		//refresh_comment(commnet_post_id);
		
		$('#AddCommentForm_'+<?php echo $post['PALL']['post_id'] ?>).on('submit', function(e) {
				if($('#newcomment_input_'+<?php echo $post['PALL']['post_id'] ?>).val()==''){
					show_warning_msg(_enter_text);
					e.preventDefault();
					return false;
				}
				
				var newpost_video =$('#newcomment_video_'+<?php echo $post['PALL']['post_id'] ?>).val(); 
				if(newpost_video.trim()!=''){
					
					if(!newpost_video.endsWith('</iframe>') ) {
					 	show_warning_msg("<?php echo __('enter_valid_iframe') ?>");
						e.preventDefault();
						return false;  
					}
				}
				
				e.preventDefault();
                $("#comment_loading_"+<?php echo $post['PALL']['post_id'] ?>).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');
                $(this).ajaxSubmit({
                    target: '#comment_result_'+<?php echo $post['PALL']['post_id'] ?>,
                    success:  afterPostSuccess , //call function after success
					error  :  afterPostError
                });
            });
			
			function afterPostSuccess()  {
				$("#comment_loading_"+<?php echo $post['PALL']['post_id'] ?>).empty();
				var post_user_id = $('#commnet_post_user_id_'+<?php echo $post['PALL']['post_id'] ?>).val();
				var body = $('#newcomment_input_'+<?php echo $post['PALL']['post_id'] ?>).val();				
				$('#AddCommentForm_'+<?php echo $post['PALL']['post_id'] ?>).resetForm();  // reset form
	            $('#newcomment_link_'+<?php echo $post['PALL']['post_id'] ?>).val('');
				
				$('#newcomment_image_attachment_'+<?php echo $post['PALL']['post_id'] ?>).parent().fadeOut(200);
			    $('#newcomment_image_attachment_'+<?php echo $post['PALL']['post_id'] ?>).parent().remove();
				
			    $('#newcomment_input_'+<?php echo $post['PALL']['post_id'] ?>).val('');
				$("#comment_counter_"+<?php echo $post['PALL']['post_id']; ?>).val(500);
				var commnet_post_id = $('#parent_commnet_post_id_'+<?php echo $post['PALL']['post_id'] ?>).val();
			    show_success_msg(_save_post_success);
                $('.postReply').slideUp(1000);
                $('.postReply').remove();
				switch(_action){
					case 'view':
						refresh_comment($('#view_post_id').val());
						break;	
					/*case 'refresh_profile_post':
						refresh_profile_post(0,<?php echo $post['PALL']['share_user_id']; ?>);
						break;*/					
					default:
						new_refresh_home();
						break;
				}

				send_privacy_email(post_user_id,'oncomment',body,<?php echo $post['PALL']['post_id'] ?>);
       	  	}
		  
		  function afterPostError()  {
			show_error_msg(_save_post_notsuccess);
       	  }
		
		
		$('#newcomment_image_'+<?php echo $post['PALL']['post_id'] ?>).change(function(){

			var count = 0;
			var arr = $("#newcomment_image_attachment_"+<?php echo $post['PALL']['post_id'] ?>).map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_image);return;
			}
           
            var attach="<span class='imageStatus' id='newpost_image_attachment_"+<?php echo $post['PALL']['post_id'] ?>+"'><span style='cursor:pointer' onclick=clear_comment_image(<?php echo $post['PALL']['post_id'] ?>); class='icon icon-cancel clearInput' id='closethis_'></span>"+_image_added+"</span>";
            
			$('#attachment_place_'+<?php echo $post['PALL']['post_id'] ?>).append(attach);
			$("#attachment_place_"+<?php echo $post['PALL']['post_id'] ?>).css("display","block");
			$("#closethis").click(function(e) {
		       $(this).parent().fadeOut(200,function(){$(this).remove()});
			   clear_comment_image(<?php echo $post['PALL']['post_id'] ?>);
		    });
		});
		
		
		
		 $('#newcomment_add_image_'+<?php echo $post['PALL']['post_id'] ?>).click(function(){
			 $('#newcomment_image_'+<?php echo $post['PALL']['post_id'] ?>).trigger('click');
	        //$(this).parent().find('input').click();
			$("#closethis").click(function(e) {
		        $(this).parent().fadeOut(200,function(){$(this).remove()});
				clear_comment_image(<?php echo $post['PALL']['post_id'] ?>);
	    	});
			
	      });	
		  
		  $('input, textarea').keyup(function() {
		    $(this).val().charAt(0).charCodeAt(0) < 200 ? $(this).css('direction','ltr') : $(this).css('direction','rtl');
		});
	});


$(document).ready(function() {
        // Tooltip only Text
        $('.masterTooltip').hover(function(){
                // Hover over code
                var title = $(this).attr('title');
                $(this).data('tipText', title).removeAttr('title');
                $('<p class="tooltip"></p>')
                .text(title)
                .appendTo('body')
                .fadeIn('slow');
        }, function() {
                // Hover out code
                $(this).attr('title', $(this).data('tipText'));
                $('.tooltip').remove();
        }).mousemove(function(e) {
                var mousex = e.pageX + 20; //Get X coordinates
                var mousey = e.pageY + 10; //Get Y coordinates
                $('.tooltip')
                .css({ top: mousey, left: mousex })
        });
});
</script>
