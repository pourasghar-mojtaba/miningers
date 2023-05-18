<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class SharepostnotificationsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Sharepostnotifications';
	
    var $helpers = array('Form');
	
	
public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('insertdate');
	}	
	 
function insertdate(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$notifications = $this->Sharepostnotification->query("
        select * from sharepost_notifications as Sharepostnotification
    ");
    
    // pr($notifications);
    
    if(!empty($notifications)){
        foreach($notifications as $notification)
        {
            $long = strtotime($notification['Sharepostnotification']['created']);
			$ret= $this->Sharepostnotification->updateAll(
				    array('Sharepostnotification.insertdt' =>'"'.date('Ymd',$long).'"',
						  'Sharepostnotification.inserttm' =>'"'.date('Hi',$long).'"'	
						),
				    array( 'Sharepostnotification.id' => $notification['Sharepostnotification']['id'] )  //condition
		   		);
        }
    }
    
    
 }	
 
 
 
 
 
 
 
 
}
