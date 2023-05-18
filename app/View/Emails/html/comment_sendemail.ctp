 
<table style="width: 700px;font-family: tahoma;font-size: 12px;margin: 10px;line-height: 25px" align="center" border='0'cellpadding="0" cellspacing="0" dir="rtl">
    <tr>
        <td style="background: #252525;height: 75px;">
            <div style="float: left;"> 
             	<a href="http://miningers.com/">
    				<img width="275" height="68" alt="" src="http://madaner.ir/img/icons/Miningers-Notification-Header.png">  
    			</a>     
            </div>
    		<div style="color: #FFFFFF;float: right;font-weight: bold;margin-right: 5px;margin-top: 50px;">
    			 
    		</div>
        </td>
    </tr>
    <tr>
        <td style="background-color: #ffffff;border: 1px solid #acacac;">
            <div style="background-color: #ebebeb;border: 1px solid #acacac;margin: 10px;padding: 5px;">
    		   <!--body--> 
			   
			    <table width='100%' style="font-family: tahoma;font-size: 12px;" border='0' dir="rtl">
				   <tr>
				   	<td style="font-family: tahoma;font-size: 13px;">
						 <div style="float: right;width: 130px;">
							<?php
								if(fileExistsInPath(__USER_IMAGE_PATH.$image) && $image!='' ) 
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
							 <?php echo __('dear_user'); ?> ، <?php echo $to_name; ?>
						  </div>
						  <div style="font-family: tahoma;font-size: 13px;margin: 5px;">
								 <?php echo $from_name; ?> (@<?php echo "<a href='".__SITE_URL.$from_user_name."'>".$from_user_name."</a>" ?>) 
								 <?php echo "<a href='".__SITE_URL."posts/view/".$post_id."' style='color:#378abb'>".__('send_comment_on_your_post')."</a>"; ?>
						  </div>
					</td>
				   </tr>
					<tr>
						<td style="font-family: tahoma;font-size: 13px;">
							(<?php echo $text; ?>)
						</td>
					</tr>
					<tr>
						<td style="font-family: tahoma;font-size: 13px;">
							 <?php echo __('change_setting'); ?> <a href="<?php echo __SITE_URL; ?>sendemails/edit"> <?php echo __('send_email_setting'); ?> </a>
						</td>
					</tr>
				</table>
				
				<!--body--> 
				
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
          <a href="http://www.madaner.ir" style="color:#3182bd"> www.madaner.ir </a>  
        </td>
    </tr>

</table>

