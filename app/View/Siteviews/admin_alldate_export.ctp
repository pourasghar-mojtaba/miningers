<?php

 $filename = __Excel_Path.'export.xls';
 $titles= array(__('date'),__('count_view'));
 $this->Excel->site_alldate_export($filename,__('from_date').' '.$from_date.' '.__('to_date').' '.$to_date,$titles,$siteviews);
 

?>