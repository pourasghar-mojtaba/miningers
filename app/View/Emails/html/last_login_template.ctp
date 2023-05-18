<?php
	 
	if($country_id!=104){
		Configure::write('Config.language', 'eng');
	}else Configure::write('Config.language', 'per');
	
?> 
 <table style="width: 700px;font-family: tahoma;font-size: 12px;margin: 10px;line-height: 25px" align="center" border='0'cellpadding="0" cellspacing="0" dir="rtl">
    <tr>
        <td style="background: #252525;height: 75px;">
            <div style="float: left;"> 
             	<a href="http://miningers.com/">
    				<img width="275" height="68" alt="" src="http://miningers.com/img/icons/Miningers-Notification-Header.png">  
    			</a>     
            </div>
    		<div style="color: #FFFFFF; float: right;font-family: tahoma; margin-right: 10px;margin-top: 50px;">
    			<?php echo __('private_madaner_newsletter') ?>
    		</div>
        </td>
    </tr>
    <tr>
        <td style="background-color: #ffffff;border: 1px solid #acacac;">
            <div style="background-color: #ebebeb;border: 1px solid #acacac;margin: 10px;padding: 5px;">
    		    <!--main template-->
					<table style="line-height: 20px;" border="0" cellpadding="0" cellspacing="0" width="98%" dir="rtl" align="center">
							<tr>
							  <td>
								<div style="float: right;width: 130px;">
								<?php
									if(fileExistsInPath(__USER_IMAGE_PATH.$image) && !empty($image) ) 
										{
											echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$image."' />";
										}
										else{
											if($sex==0)
											  echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL."img/profile_women.png' />";									 
											elseif($sex==1) 
											  echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL."img/profile_men.png' />";
											
											elseif($sex==2) 
											  echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL."img/company.png' />";	
										}								
								?>
								</div>
								<div style="margin-right: 10px"> 
									<h3 style="font-family: tahoma;"> <?php echo __('hello') ?> ، <?php echo $name ?> </h3>
		
									<div style="font-family: tahoma;font-size: 13px;margin: 5px;">
										<?php echo __('last_login_dtl1') ?>
									</div>
								 </div>	
							  </td>
							</tr>
							
                            <tr>
								<td style="font-family: tahoma;font-size: 13px;">
									<b><?php echo __('laste_week_users_registred') ?></b> 
								</td>
							</tr>
                            <tr>
                                <td>
                                    <div style="margin-top: 5px;font-family: tahoma;font-size: 13px;border: 1px solid #bfbfbf;background-color: #ffffff;padding: 5px;width: 97%;">
									
									  <?php 
									  	 if(!empty($last_users)){
										 	foreach($last_users as $last_user)
											{
												echo "
													<div style='border-bottom: 1px solid #bbb7b7; min-height: 120px;'>	
			                                          <div style='float: right;height: 100px;min-width: 100px;overflow: hidden;'>";
			    									  if(fileExistsInPath(__USER_IMAGE_PATH.$last_user['User']['image']) && $last_user['User']['image']!='' ) 
															{
																echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$last_user['User']['image']."' />";
															}
															else{
																if($last_user['User']['sex']==0)
																  echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL."img/profile_women.png' />";									 
																elseif($last_user['User']['sex']==1) 
																  echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL."img/profile_men.png' />";
																
																elseif($last_user['User']['sex']==2) 
																  echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL."img/company.png' />";	
															}
													  
													  
													  echo"</div>
			                                          
			                                          <div style='float: right;font-family: tahoma;font-size: 12px'>
														".$last_user['User']['name']." @
														<a href='".__SITE_URL.$last_user['User']['user_name']."' style='color:#378abb'>
															".$last_user['User']['user_name']."
														</a>
													</div>
													<div style='float: left;font-family: tahoma;font-size: 12px'>
														<a href='".__SITE_URL.$last_user['User']['user_name']."' style='background-color: #4DAFF6;color: #FFFFFF;float: right;margin-left: 10px;margin-top: 15px;padding: 5px;text-decoration: none;'>
															".__('follow')."
														</a>
													</div>
			                                        <br>
			                                        <h2 style=' border-left: 1px outset rgba(0, 0, 0, 0.6);color: #666666;display: inline-block;font-size: 12px;margin: 0 5px 5px 0;padding: 5px;text-align: right;font-family: tahoma;font-size: 12px;'>
														".$last_user['User']['location']."
													</h2>
			                                        <h2 style=' border-left: 1px outset rgba(0, 0, 0, 0.6);color: #666666;display: inline-block;font-size: 12px;margin: 0 5px 5px 0;padding: 5px;text-align: right;font-family: tahoma;font-size: 12px;'>
														".$last_user['Industry']['title']."
													</h2>
			                                        <h2 style='color: #666666;display: inline-block;font-size: 12px;margin: 0 5px 5px 0;padding: 5px;text-align: right;font-family: tahoma;font-size: 12px;'>
													<a style='color: #666666' href='".$last_user['User']['site']."'>
														".$this->Gilace->filter_url($last_user['User']['site'])."
													</a></h2>
			                                        <div style='color: #666666;display: block;margin: 0 5px;text-align: justify;'>
				                                        ".$last_user['User']['details']."
				                                     </div>  
			                                      </div>
												";
											}
											
											
										 }
									  ?>	
									
                                      
									  
									  
									     
                                    </div>								
									
                                </td>
                            </tr>
							<?php if($last_users_count > 0){ ?>
							<tr >
								<td style="font-family: tahoma;font-size: 13px;">
									<b><?php echo $last_users_count; ?></b> <?php echo __('email_dtl_register'); ?>
								</td>
							</tr>
							<?php } ?>
                            <tr>
								<td style="font-family: tahoma;font-size: 13px;">
									<b> <?php echo __('hot_tags_this_week') ?> :</b> 
								</td>
							</tr>
                            <tr>
                                <td>
                                    
									<?php
										if(!empty($hot_post_tags))
										{
											foreach($hot_post_tags as $hot_post_tag)
											{
												echo "
												  <a   href='".__SITE_URL."posts/tags/".$hot_post_tag['Posttag']['title']."' style='color: #1a77bc;font-family: tahoma;font-size: 13px;font-weight: bold;margin-right:5px;'>
												  #".$hot_post_tag['Posttag']['title']."</a>  
												";
											}
										}
									 ?>
                                </td>
                            </tr>
							<tr>
								<td style="font-family: tahoma;font-size: 13px;border-bottom: 2px solid #959595;padding-bottom: 8px;padding-top: 5px">
									<b> <?php echo __('census_this_week') ?>  :</b> 
								</td>
							</tr>
							<tr>
								<td style="font-family: tahoma;font-size: 13px;">
									 <?php echo __('unread_message') ?>  : <?php echo $new_message_count; ?>
								</td>
							</tr>
							<tr>
								<td style="font-family: tahoma;font-size: 13px;">
									  <?php echo __('unread_notification') ?> :<?php echo $notification_count ?>
								</td>
							</tr>
                            <?php if($week_fllower_count>0){  ?>                                
                            <tr>
								<td style="font-family: tahoma;font-size: 13px;">
									  <?php echo __('week_fllower') ?>  :  <?php echo  $week_fllower_count.' '.__('person') ?>
								</td>
							</tr>
                            <tr>
								<td style="font-family: tahoma;font-size: 13px;">
									 <?php echo __('four_last_follower') ?> :
								</td>
							</tr>
                            <tr>
                                <td>
								
								  <?php 
								  	 if(!empty($week_fllowers)){
									 	foreach($week_fllowers as $week_fllower){
											echo "
												<div style='font-family: tahoma;font-size: 13px;border: 1px solid #000000;background-color: #cccccc;float: right;width: 48%;height: 190px;margin: 5px;'>
			                                        <div style='float: right;height: 100px;min-width: 100px;overflow: hidden;margin-bottom: 5px'>";
													
														if(fileExistsInPath(__USER_IMAGE_PATH.$week_fllower['User']['image']) && $week_fllower['User']['image']!='' ) 
															{
																echo "<img style='max-height:100px;width:100px;' src='".__SITE_URL.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$week_fllower['User']['image']."' />";
															}
															else{
																if($week_fllower['User']['sex']==0)
																  echo "<img style='max-height:100px;width:100px;' src='".__SITE_URL."img/profile_women.png' />";									 
																elseif($week_fllower['User']['sex']==1) 
																  echo "<img style='max-height:100px;width:100px;' src='".__SITE_URL."img/profile_men.png' />";
																
																elseif($week_fllower['User']['sex']==2) 
																  echo "<img style='max-height:100px;width:100px;' src='".__SITE_URL."img/company.png' />";	
															}	
															
													echo"		
			        							    </div>
			                                        <div style=' display: inline-block;line-height: 200%;margin-right: 5px;position: relative;vertical-align: top;font-family: tahoma;font-size: 13px;'>
			                                        	<span title='".$week_fllower['User']['name']."'>".substr($week_fllower['User']['name'],0,38); if(strlen($week_fllower['User']['name'])>38) echo '...'; echo "</span><br>
			                                			
														<a href='".__SITE_URL."/".$week_fllower['User']['user_name']."' title='".$week_fllower['User']['user_name']."'  style='color: #0069ff;width:50px;overfollow:hidden'>@".substr($week_fllower['User']['user_name'],0,38); if(strlen($week_fllower['User']['user_name'])>38) echo '...'; echo"</a>
			                                			<br>
			                                            <span title='".$week_fllower['Industry']['title']."'>".substr($week_fllower['Industry']['title'],0,38);if(strlen($week_fllower['Industry']['title'])>38) echo '...'; echo"</span> |
			                                            <span title='".$week_fllower['User']['location']."'>".substr($week_fllower['User']['location'],0,38); if(strlen($week_fllower['User']['location'])>38) echo '...'; echo"</span>
			                                        </div>
			     										
													<div style='border-top: 1px solid rgba(0, 0, 0, 0.2); clear: both;display: block;float: none;height: 75px;margin-right: 8px;margin-top: 5px;padding-top: 5px;width: 280px;'>
			                            			 	
			                                            <div style='background-color: #669900;color: #FFFFFF;font-family: tahoma;font-size: 13px;float: right;height: 70px;width: 80px;margin-right: 7px;position: relative;'>
			                                                <span style='float: right;font-size: 20px;margin-right: 27px;margin-top: 10px;'>".$week_fllower['0']['post_count']."</span>
			                                                <label style='font-size: 10px;float: right;font-size: 10px;margin-right: 12px;margin-top: 5px;'>".__('my_post')."</label>
			                                            </div>
														
														<div style='background-color: #42719f;color: #FFFFFF;font-family: tahoma;font-size: 13px;float: right;height: 70px;width: 80px;margin-right: 13px;position: relative;'>
			                                                <span style='float: right;font-size: 20px;margin-right: 27px;margin-top: 10px;'>".$week_fllower['0']['new_follow_count']."</span>
			                                                <label style='font-size: 10px;float: right;font-size: 10px;margin-right: 12px;margin-top: 5px;'>".__('follow_payees')."</label>
			                                            </div>
														
														<div style='background-color: #424242;color: #FFFFFF;font-family: tahoma;font-size: 13px;float: right;height: 70px;width: 80px;margin-right: 13px;position: relative;'>
			                                                <span style='float: right;font-size: 20px;margin-right: 27px;margin-top: 10px;'>".$week_fllower['0']['new_tofollow_count']."</span>
			                                                <label style='font-size: 10px;float: right;font-size: 10px;margin-right: 12px;margin-top: 5px;'>".__('chasers')."</label>
			                                            </div>
			                                            		 
			                                       </div>  
			 
			                                    </div>
											";
										}
									 }							  
								  ?>
								                                 
                                </td>
                            </tr>
                            
                            <?php
                             }
                            ?>
                            
						</table>
				<!--main template-->
				
    	    </div>
        </td>
    </tr>
    <tr>
        <td align="center" style="font-family: tahoma;font-size: 12px;">
		  <?php echo __('newsletter_info1'); ?>
           <?php echo $name.' '; ?> (<?php echo $email; ?>)  
		   <?php echo __('newsletter_info2'); ?>
		    -
          <a href="<?php echo __SITE_URL.'/sendemails/edit' ?>" style="color:#3182bd"> <?php echo __('cancel_subscription') ?> </a>  
        </td>
    </tr>
	<tr>
        <td align="center" style="font-family: tahoma;font-size: 12px;">
		 <?php echo __('newsletter_info3') ?>
           :
          <a href="<?php echo __Madaner_Email; ?>" style="color:#3182bd"> <?php echo __Madaner_Email; ?> </a>  
        </td>
    </tr>
	<tr>
        <td align="center" style="font-family: tahoma;font-size: 12px;">
            
		  <?php echo __('newsletter_info4') ?>
          <a href="http://www.miningers.com" style="color:#3182bd"> www.miningers.com </a>  
        </td>
    </tr>

</table>
