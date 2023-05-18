<?php

  $User_Info= $this->Session->read('User_Info');
   

  if(!empty($search_result))
  {
  	 foreach($search_result as $value)
	 {
	 	echo $this->element('/Users/user_info',array('value'=>$value));
	 }
  }
?>