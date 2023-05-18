<?php
  $User_Info= $this->Session->read('User_Info');
  if(empty($user_id)){
  	$user_id = 0;
  }
  
  
 ?> 
 <?php if(isset($User_Info)){ ?>
 <aside class="dataBox">
<p><?php echo __('invite_your_freinds'); ?></p>
 	<section id="invitation">
		 <?php echo $this->Form->create('Invitation', array('id'=>'invitation_form','name'=>'invitation_form','enctype'=>'multipart/form-data','action'=>'/invite','class'=>'myForm')); ?>
			<label>  <?php echo __('invitation_detail1'); ?></label>
			<div class="col-sm-12">
				<input type="text" class="myFormComponent ltr" placeholder="<?php echo __('email'); ?>" id="invitation_email" name="invitation_email"  /> 
				<input type="submit" class="myFormComponent green" value="<?php echo __('send'); ?>" id="invitation_btn" />
			</div>
			<label> <?php echo __('invitation_detail2'); ?></label> 
			<span id="loading"> </span>
		</form>
    </section>
</aside>	

<?php if($user_id == $User_Info['id']){?>
 <aside class="dataBox">
	<p><?php echo __('events') ?> </p>
	<section class="nano has-scrollbar" id="notificationBox">
      <ul id="notification_body">  
	  	<?php echo "<img src ='".__SITE_URL."img/loader/big_loader.gif' />"; ?> 	    
	  </ul>
	  <span id="notification_loading"></span>
	 </section> 
</aside>
 

<?php
  }
 } ?>

<aside class="dataBox">
	<?php 
	//echo "<a href='http://www.raybin.ir/' target='_blank'><img src ='".__SITE_URL.__ADS_PATH."raybin.jpg' width='98%' /></a>";
	
	/*echo "<a href='http://miningram.madaner.ir/' target='_blank'>
		<img src ='".__SITE_URL.__ADS_PATH."miningram.jpg' width='98%' />
	  </a>";
	
	echo "<a href='http://minex.ir/' target='_blank'>
		<img src ='".__SITE_URL.__ADS_PATH."minex.jpg' width='98%' />
	  </a>";
	/*
	echo "<a href='http://rastak-expo.com/explain.aspx?lan=fa&id=1&kind=70' target='_blank'>
		<img src ='".__SITE_URL.__ADS_PATH."292-400.jpg' width='98%' />
	  </a>";
	
	if($locale =='per'){
	  	echo "<a href='http://imis2014.com/fa/' target='_blank'>
			<img src ='".__SITE_URL.__ADS_PATH."baner site mine FA M2.jpg' width='98%' />
		  </a>";
	  }else{
	  	echo "<a href='http://www.imis2015.com/' target='_blank'>
			<img src ='".__SITE_URL.__ADS_PATH."baner site mine EN M2.jpg' width='98%' />
		  </a>";
	  }	*/
	?> 
	
</aside>