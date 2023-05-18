<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class PostnotificationsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Postnotifications';
	
    var $helpers = array('Form');
	
	
	/**
	* 
	* @var 
	* 
	*/ 
	 
 public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('insertdate');
	}	
	 
function insertdate(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$notifications = $this->Postnotification->query("
        select * from post_notifications as Postnotification
    ");
    
    // pr($notifications);
    
    if(!empty($notifications)){
        foreach($notifications as $notification)
        {
            $long = strtotime($notification['Postnotification']['created']);
			$ret= $this->Postnotification->updateAll(
				    array('Postnotification.insertdt' =>'"'.date('Ymd',$long).'"',
						  'Postnotification.inserttm' =>'"'.date('Hi',$long).'"'	
						),
				    array( 'Postnotification.id' => $notification['Postnotification']['id'] )  //condition
		   		);
        }
    }
    
    
 }	
 
 
 
 
 
 
 
 
 
 
}
