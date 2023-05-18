<?php

  $User_Info= $this->Session->read('User_Info');

  if(!empty($users))
  {
  	 foreach($users as $value)
	 {
	 	echo $this->element('/Users/user_info',array('value'=>$value));
	 }
  }
?>