<?php

 $filename = __Excel_Path.'export.xls';
 $titles= array(__('name'),__('user_name'),__('email'),__('status'),__('created'),__('user_type'),__('sex'),__('role'),__('industry'),__('location'),__('site'),__('register_key'));
 $this->Excel->user_export($filename,'Users',$titles,$users);


?>