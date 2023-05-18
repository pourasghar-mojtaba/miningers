<?php

 $filename = __Excel_Path.'export.xls';
 $titles= array(__('name'),__('user_name'),__('email'),__('first_login_date'),__('last_login_date'),__('login_count'));
 $this->Excel->onedate_export($filename,__('from_date').' '.$from_date.' '.__('to_date').' '.$to_date,$titles,$userloglogins);
 

?>