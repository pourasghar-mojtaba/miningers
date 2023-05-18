
<table style="width: 700px;font-family: tahoma;font-size: 12px;margin: 10px;line-height: 25px" align="center" border='0'cellpadding="0" cellspacing="0" dir="rtl">
            <tr>
                <td style="background: #252525;height: 75px;">
                    <div style="float: left;"> 
                     	<a href="http://miningers.com/">
		    				<img width="275" height="68" alt="" src="http://madaner.ir/img/icons/Miningers-Notification-Header.png">  
		    			</a>    
                    </div>
            		<div style="float: right;color: #ffffff;">
            			
            		</div>
                </td>
            </tr>
            <tr>
                <td style="background-color: #ffffff;border: 1px solid #acacac;">
                    <div style="background-color: #ebebeb;border: 1px solid #acacac;margin: 10px;padding: 5px;">
            		    <table width="100%">
							<tr>
								<td valign="top">
									<?php
									if(fileExistsInPath(__USER_IMAGE_PATH.$user_image) && $user_image!='' ) 
										{
											echo "<img style='max-height:130px;width:100px;' src='".__SITE_URL.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$user_image."' />";
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
								</td>
								<td valign="top">
									<div style="float: right;font-family: tahoma;font-size: 12px">
										<b><?php echo $name; ?></b> 
										<a href="<?php echo __SITE_URL.$user_name ?>" style="text-decoration: none;color: #2482ce">
										@ <?php echo $user_name; ?></a>
									</div>
									<div style="float: left;font-family: tahoma;font-size: 12px">
										<a href="<?php echo __SITE_URL."posts/view/".$post_id ?>" style="text-decoration: none;color: #2482ce">
										  <?php
                                              echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($post_date));
                                          ?>
										</a>
									</div>
									<br />
									<div style="font-family: tahoma;font-size:12px">
										<?php 
                                            echo $this->Gilace->filter_editor($body); 
                                        ?>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
                                <?php
                                   if(!empty($url)){
                                ?>
									<div style="width: 97%;background-color: #ada7a7;height: 30px;margin: 5px;">
										<div style="float: left;margin-right: 5px">
											<img src="<?php echo __SITE_URL; ?>img/icons/link_icon.png" />
										</div>
										<div style="float: left;direction: ltr;margin-top: 6px">
											<a  href="<?php echo $url; ?>" style="text-decoration: none;font-family: tahoma;font-size: 12px;color:#363933">
												<?php
                                                    echo $this->Gilace->filter_url($url);
                                                ?>
											</a>
										</div>
										
									</div>
                                <?php
                                    }
                                ?>   
                                <?php
                                   if(!empty($post_image)){
                                ?> 
									<div style="width: 97%;background-color: #ada7a7;margin: 5px;">
										<div style="float: left;margin-right: 5px">
											<img src="<?php echo __SITE_URL; ?>img/icons/image_icon.png" />
										</div>
										<br />
										<div style="direction: ltr;margin-top: 6px">
											<img style="max-width: 550px" src="<?php echo __SITE_URL.__POST_IMAGE_PATH.$post_image; ?>" />
										</div>
										
									</div>
                                <?php
                                    }
                                ?>     
								</td>
							</tr>
						</table>
            	    </div>
                </td>
            </tr>
            <tr>
                <td align="center" style="font-family: tahoma;font-size: 12px;">
				  <?php echo __('newsletter_info1'); ?>
                   (<?php echo $email; ?>)  
				   <?php echo __('newsletter_info2'); ?>
				    -
                  <a href="#" style="color:#3182bd"> <?php echo __('cancel_subscription') ?> </a>  
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
                  <a href="http://www.madaner.ir" style="color:#3182bd"> www.madaner.ir </a>  
                </td>
            </tr>
        	
        </table>


