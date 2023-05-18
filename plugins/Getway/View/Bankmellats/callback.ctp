<?php
  echo $this->Html->css('/getway/css/getway');
  echo $this->Html->css('setting_'.$locale);
  $Pay_Info= $this->Session->read('Pay_Info');
 // pr($Pay_Info);
?>

<div class="settingContent">
 	<div class="col-sm-9 settingForms col-md-offset-2">
         <form id="pay_form" name="pay_form">
			<div id="generalSetting">	 			
				<div class="col-md-12">
					 <?php echo $this->Session->flash(); ?>
	    		</div>
				<?php if($result_value){ ?>
				<div class="col-md-6">
					 <div id="title" class="col-md-3"> <?php echo __('transaction_id') ?> : </div>
					 <div id="title" class="col-md-6"><?php echo $transaction_id; ?></div>
	    		</div>
				<div class="col-md-6">
					 <div id="title" class="col-md-3"> <?php echo __('order_id') ?> : </div>
					 <div id="title" class="col-md-6"><?php echo $order_id; ?></div>
	    		</div>
				<?php } ?>
				<div class="col-md-12">              					 
                    <a href="<?php echo $back_url; ?>">
					<button type="button" class="green myFormComponent" id="pay">
                        <span class="text"><?php echo __('confirm_purchase'); ?></span>
                        <span class="icon icon-left-open"></span>
                    </button>
					</a>
                </div>
			</div>	
		</form>		
    </div>
	 			
 </div>				

<script>
  /*$('#pay').click(function(){
     // document.location.href = _url+'users/edit_profile'
     document.location.href = '<?php echo $back_url; ?>';
  });*/
</script>