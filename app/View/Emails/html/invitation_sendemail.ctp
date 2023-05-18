 
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
				   	<td>
						<?php echo $to_name; ?>
					</td>
				   </tr>
				   <tr>
				   	<td style="font-family: tahoma;font-size: 13px;font-weight: bold;">
						 <?php echo $from_name; ?> (@<?php echo "<a href='".__SITE_URL.$from_user_name."'>".$from_user_name."</a>" ?>) :
				         <?php echo __('invitation_titr1'); ?>
					</td>
				   </tr>
				    <tr>
				        <td style="font-family: tahoma;font-size: 13px;">
				        <?php echo __('invitation_titr2'); ?>
				 			
				        </td>
				    </tr>
					<tr>
						<td style="font-family: tahoma;font-size: 13px;">
							
				             <?php echo __('invitation_titr3'); ?>
						</td>
					</tr>
					<tr>
						<td style="font-family: tahoma;font-size: 13px;color: #2e73d1;" align="left">
							 <a href="<?php echo $register_key; ?>"><?php echo $register_key; ?></a>
						</td>
					</tr>
				</table>
				
				<!--body--> 
				
    	    </div>
        </td>
    </tr>
	<tr>
        <td align="center" style="font-family: tahoma;font-size: 12px;">
		 <?php echo __('newsletter_info3') ?>
           :
          <a href="<?php echo __Madaner_Email; ?>" style="color:#3182bd"> <?php echo __Madaner_Email; ?></a>  
        </td>
    </tr>
	<tr>
        <td align="center" style="font-family: tahoma;font-size: 12px;">
            
		  <?php echo __('newsletter_info4') ?>
          <a href="http://www.madaner.ir" style="color:#3182bd"> www.madaner.ir </a>  
        </td>
    </tr>

</table>
