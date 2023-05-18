<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class NotificationsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Notifications';
	
    var $helpers = array('Form');
	
	
	/**
	* 
	* @var 
	* 
	*/ 
	 
 public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('insertnotification');
	}	
	 
function insertnotification(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$notifications = $this->Notification->query("
        select * from vmnotifications as Notification
    ");
    
    // pr($notifications);
    
    if(!empty($notifications)){
        foreach($notifications as $notification)
        {
			$this->request->data['Notification']['from_user_id']= $notification['Notification']['from_user_id'];
        	$this->request->data['Notification']['to_user_id']=$notification['Notification']['to_user_id'];
        	$this->request->data['Notification']['notification_id']=$notification['Notification']['notification_id'];
        	$this->request->data['Notification']['viewed']=$notification['Notification']['viewed'];
        	$this->request->data['Notification']['type']=$notification['Notification']['type'];
        	$this->request->data['Notification']['notification_type']=$notification['Notification']['notification_type'];
        	$this->request->data['Notification']['notification_body']=$notification['Notification']['notification_body'];
        	$this->request->data['Notification']['created']=$notification['Notification']['created'];
        	$this->Notification->create();
        	
        	$this->request->data=Sanitize::clean($this->request->data);

            if($this->Notification->save($this->request->data)){
               
        	}
        }
    }
    
    
 }	
 
 
 
 
 
 
 
 
 
 
}
