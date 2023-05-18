<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class FollownotificationsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
    var $name = 'Follownotifications';	 
    
    var $helpers = array('Form');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('insertdate');
	}	
	 
function insertdate(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$notifications = $this->Follownotification->query("
        select * from follow_notifications as Follownotification limit 22000,4000
    ");
    
    // pr($notifications);
    
    if(!empty($notifications)){
        foreach($notifications as $notification)
        {
            $long = strtotime($notification['Follownotification']['created']);
			$ret= $this->Follownotification->updateAll(
				    array('Follownotification.insertdt' =>'"'.date('Ymd',$long).'"',
						  'Follownotification.inserttm' =>'"'.date('Hi',$long).'"'	
						),
				    array( 'Follownotification.id' => $notification['Follownotification']['id'] )  //condition
		   		);
        }
    }
    
    
 }	 
 
 
 
 
 
 
 
 
 
 
 
}
