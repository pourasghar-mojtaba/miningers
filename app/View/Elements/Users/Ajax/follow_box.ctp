 <?php
 	$User_Info= $this->Session->read('User_Info');
    echo $this->Html->css('follow_box_'.$locale); 
   
 ?>
 
<div class="myForm" style="width:720px;height: 460px;overflow-y: scroll;">
    <header class="ajaxheader">
    	<strong> <?php echo __('welcome_to_madaner') ?></strong><br>

    	<?php echo __('follow_5users') ?>
    </header>
     
    <div class="follow_box_body" id="ex3">
      
            	<ul class="result" >
    			 <?php
                     if(!empty($search_result))
                      {
                      	 foreach($search_result as $value)
                    	 {
                    	 	echo $this->element('/Users/user_info',array('value'=>$value));
                    	 }
                      }
                 ?>
                </ul>
     
    </div>
</div>
 