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
				 		<?php echo __('your_new_password').' : '.$pass; ?>
				  
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

