<?php

class Sendemail extends AppModel {
	public $name = 'Sendemail';
	public $useTable = "sendemail_settings"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $hasOne = array(
		'Activateemail' => array(
			'className' => 'Activateemail',
				'foreignKey' => 'user_id',
		'dependent' => false
		),
		'Newslettersend' => array(
			'className' => 'Newslettersend',
			'foreignKey' => 'user_id',
			'dependent' => false
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'id',
			'dependent' => false
		)
	);

/**
 * 
 * @param undefined $privacy_user_id
 * @param undefined $user_id
 * @param undefined $privacy_location
 * 
*/
 function check_send_email($user_id,$location)
 {
 	$send_email= array();
	$count = $this->find('count', array('conditions' => array('Sendemail.user_id' => $user_id)));
	if($count<=0){
		return array('status'=>TRUE);
	}
	else if($count>0){
		$options['fields'] = array(
				'Sendemail.id',
				'Sendemail.onsharing',
				'Sendemail.oncomment',
				'Sendemail.onfollow' ,
				'Sendemail.onmessage',
                'Sendemail.onlastloginemail',
                'Sendemail.onnewsletteremail',
                'Sendemail.onactiveemail'
		 );
				   
		$options['conditions'] = array(
				'Sendemail.user_id'=>$user_id
		);
		$send_email = $this->find('first',$options);
		 
		switch($location){
			case 'onsharing':
				$status = $send_email['Sendemail']['onsharing'];
				break;
			case 'oncomment':
				$status = $send_email['Sendemail']['oncomment'];
				break;
			case 'onfollow':
				$status = $send_email['Sendemail']['onfollow'];
				break;
			case 'onmessage':
				$status = $send_email['Sendemail']['onmessage'];
				break;
			case 'onlastloginemail':
				$status = $send_email['Sendemail']['onlastloginemail'];
				break;
			case 'onnewsletteremail':
				$status = $send_email['Sendemail']['onnewsletteremail'];
				break;	
            case 'onactiveemail':
				$status = $send_email['Sendemail']['onactiveemail'];
				break;    				
		}
		
		
	}	
	return array('status'=>$status);

 }
 
 
 
}

?>