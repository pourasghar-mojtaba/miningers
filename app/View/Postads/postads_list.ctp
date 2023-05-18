<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
	echo $this->Html->css('ads_'.$locale);
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'post_ads')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
                <form class="myForm">
                    <div class="col-md-12">
                        <?php
            				if(!empty($postads)){
            			?>
            	        	<table border="0" cellpadding="0" align="right" style="width:100%" class="ads">
            	               <?php  echo $this->Session->flash(); ?>					    	 
            	                <tr>
            	                    <th>
            							<?php echo __('post_id') ?>
            						</th>
            						<th width="70">
            							<?php echo __('base_amount') ?>
            						</th>
            						<th>
            							<?php echo __('select_member') ?>
            						</th>
            						<th width="80">
            							<?php echo __('sum_price') ?>
            						</th>
            						<th>
            							<?php echo __('refid') ?>
            						</th>
            						<th>
            							<?php echo __('bankmessage_id') ?>
            						</th>
            						<th width="60">
            							<?php echo __('status') ?>
            						</th>
            						<th width="100">
            							<?php echo __('action') ?>
            						</th>
            	                </tr>
            					<?php
            						 
                                    foreach ($postads as $postad){
            							$bank_message='';
            							if(!empty($postad['Bankmessag']['message'])){
            								$bank_message=$postad['Bankmessag']['message'];
            							}
            							
            							echo "
            								<tr>
            				                    <td><a href='".__SITE_URL."posts/view/".$postad['Postad']['post_id']."' target='_blank'>
            										".$postad['Postad']['post_id']."
            										</a>
            									</td>
            									<td widtd='70'>
            										".$postad['Postad']['base_amount'].' '.__('rial')."
            									</td>
            									<td>
            										".$postad['Postad']['select_member']."
            									</td>
            									<td widtd='80'>
            										".$postad['Postad']['sum_price'].' '.__('rial')."
            									</td>
            									<td>
            										".$postad['Postad']['sale_reference_id']."
            									</td>
            									<td>
            										".$bank_message."
            									</td> 
            									<td widtd='60'>";
            									if($postad['Postad']['status']==1)
            									     echo "<span class='label_success'>".__('active')."</span>";
            									   else echo "<span class='label_important'>".__('inactive')."</span>";	
            									echo"		   
            									</td>
            									<td widtd='100'>";
            										 if($postad['Postad']['sale_reference_id']==0 || $postad['Postad']['sale_reference_id']==''){
            										 	echo "<input type='button' onclick='retry_pay(".$postad['Postad']['sum_price'].",".$postad['Postad']['id'].")' value='".__('retry_pay')."' ><span id='ads_loading'></span> ";
            										 }else
            										 echo "<span class='label_success'>".__('pay_success')."</span>";
            									echo"</td>
            				                </tr>
            							";
            						}
            					?>
            	            </table>
            			<?php
            				}
            				else
            				  echo"<div class='flash_info'>".__('not_exist_ads')."</div>"
            			?>
                    </div>
                    <div class="clear"></div>
                </form>
       		</div>
        </div>
        <div class="col-sm-3">
            <?php echo $this->element('left_edit_profile'); ?> 
        </div>
        <div class="clear"></div>
    </div>

         

 

<script>
function retry_pay(sum_price,row_id)	{    
  $("#ads_loading").html('<img width="22" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');
    $.ajax({
		type: "POST",
		url: _url+'postads/retry_pay/'+sum_price+'/'+row_id,
		data: '',
		dataType: "json",
		success: function(response)
		{
			 if(response.success == true) {			
					setTimeout("location.href = '"+_url+"getway/banks/pay/"+response.token+"?cn=postads&ac=postads_list' ", 1);
				$("#ads_loading").empty();	
			}
			else 
			 {
				if( response.message ) {
					show_error_msg(response.message);
				} 
				$("#ads_loading").empty();
			 }	 
		}

	  });
    
} 
</script>