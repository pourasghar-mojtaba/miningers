<?php

class Siteview extends AppModel {
	public $name = 'Siteview';
	public $useTable = "site_views"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
 
 function get_log()
  {
	$option['conditions'] = array(
                'date(Siteview.created)' => date('Y-m-d')
			);
			
	$count = $this->find('count',$option);
	
	if($count==0){
						
		 if(!$this->save()){
            /* set log */
				Controller::loadModel('Errorlog');
				$this->Errorlog->get_log('Siteview Model','Can not insert in Siteview');
			/* set log */
         }
    }else
    {    
        $ret= $this->query("
            UPDATE site_views as Siteview set 
                Siteview.count_view = Siteview.count_view +1
            where   date(Siteview.created) =  '".date('Y-m-d')."'
        ");
	
 	}
	 	
  }
	
	
}

?>