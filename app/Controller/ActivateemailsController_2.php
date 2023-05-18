<?php
/**
 * Static content controller.
 *
 * This file will render views from views/newsletters/
 *
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email'); 
/**
 * Static content controller
 *
 */
class ActivateemailsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Activateemails';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
	


var $components = array('GilaceDate'); 

 
 public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('get_inactive_users');
}
 
 function get_inactive_users(){
 	
	$this->autoRender = false; 
    
	$this->Activateemail->User->recursive = -1;
	$this->Activateemail->recursive = -1;
		
	$users = $this->Activateemail->User->query("
		
	 Select * from(	
		(		SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						 User.register_key ,
						 User.created 
				FROM `users` AS `User`  
			WHERE `User`.`status` = 0 	
              and User.id not in (select user_id from sendemail_settings as Sendemail)
              and User.id not in (select user_id from activate_emails as Activateemail)  			
			LIMIT 0 , 1
		 )	
	  union all	 
	     (		SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						 User.register_key ,
						 User.created 
				FROM `users` AS `User` 
					Inner join activate_emails as Activateemail
						   on  Activateemail.user_id = User.id 
						   and Activateemail.send_type = 0	   
			WHERE `User`.`status` = 0 
              and User.id not in (select user_id from sendemail_settings as Sendemail)				
			LIMIT 0 , 1
		 )	
		 
	  union all	 
	     (		SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						 User.register_key ,
						 User.created 
				FROM `users` AS `User` 
					Inner join activate_emails as Activateemail
						   on  Activateemail.user_id = User.id 
						   and Activateemail.send_type = 1	   
			WHERE `User`.`status` = 0 	
                and User.id not in (select user_id from sendemail_settings as Sendemail)			
			LIMIT 0 , 1
		 )	 
	  union all	 
	     (		SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						 User.register_key ,
						 User.created 
				FROM `users` AS `User` 
					Inner join activate_emails as Activateemail
						   on  Activateemail.user_id = User.id 
						   and Activateemail.send_type = 2	   
			WHERE `User`.`status` = 0 	
                and User.id not in (select user_id from sendemail_settings as Sendemail)			
			LIMIT 0 , 1
		 )	
		 
	   ) as User LIMIT 0 , 1	 	 
	");
    
    /*$users = $this->Activateemail->User->query("
				SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						 User.register_key ,
						 User.created 
				FROM `users` AS `User` 
			WHERE `User`.`id` = 26 
			ORDER BY `User`.id  asc
			limit 0,1
	");*/
		
	if(!empty($users)){
		foreach($users as $user)
		{
			
			if(!$this->check_existsend_email($user['User']['id'])){					   
				//$active_email= $this->get_send_email($user['User']['id']);
				$diff_day=$this->GilaceDate->diffdate($user['User']['created'],date('Y-m-d H:i:s'));
				if($diff_day>3){
					if($this->send_email($user['User']['id'],0,$user['User']['name'],$user['User']['user_name'],$user['User']['email'],$user['User']['register_key'],1))
					{
						
					}
				}		
			}
            else
            {
                $active_email= $this->get_send_email($user['User']['id']);
                $diff_day=$this->GilaceDate->diffdate($active_email['Activateemail']['created'],date('Y-m-d H:i:s'));
                
                /* >7 */
                if($diff_day>7 && $active_email['Activateemail']['send_type']==0){
					if($this->send_email($user['User']['id'],1,$user['User']['name'],$user['User']['user_name'],$user['User']['email'],$user['User']['register_key'],0))
					{
						
					}
				}
                /* >14 */
                if($diff_day>14 && $active_email['Activateemail']['send_type']==1){
					if($this->send_email($user['User']['id'],2,$user['User']['name'],$user['User']['user_name'],$user['User']['email'],$user['User']['register_key'],0))
					{
						
					}
				}
                
                 /* >30 */
                if($diff_day>30 && $active_email['Activateemail']['send_type']==2){
					if($this->send_email($user['User']['id'],3,$user['User']['name'],$user['User']['user_name'],$user['User']['email'],$user['User']['register_key'],0))
					{
						
					}
				}
                
            }
		}
		 		
	} 
	
 }
 
 /**
 * 
 * @param undefined $user_id
 * @param undefined $send_type
 * 
*/
 public function check_send_email($user_id,$send_type){
 	$this->Activateemail->recursive = -1;
    $options['conditions'] = array(
		'Activateemail.user_id'=>$user_id ,
		'Activateemail.send_type'=>$send_type ,
	);
	$count = $this->Activateemail->find('count',$options);
	if ($count>0 ) return TRUE; return FALSE;
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 public function check_existsend_email($user_id){
 	$this->Activateemail->recursive = -1;
    $options['conditions'] = array(
		'Activateemail.user_id'=>$user_id 
	);
	$count = $this->Activateemail->find('count',$options);
	if ($count>0 ) return TRUE; return FALSE;
 }
/**
* 
* @param undefined $user_id
* 
*/ 
public function get_send_email($user_id){
 	$this->Activateemail->recursive = -1;
    $options['fields'] = array(
	'Activateemail.created' ,
    'Activateemail.send_type'
   );
	$options['conditions'] = array(
		'Activateemail.user_id'=> $user_id
	);
	return $this->Activateemail->find('first',$options);
}
 
 /**
 * 
 * @param undefined $name
 * @param undefined $user_name
 * @param undefined $email
 * @param undefined $register_key
 * 
*/
 public function send_email($user_id,$send_type,$name,$user_name,$email,$register_key,$isinsert){
 	// send email;
	
    $this->Activateemail->User->recursive = -1;
	$this->Activateemail->recursive = -1;
    
    try{
		$Email = new CakeEmail();
        $Email->reset();
		$Email->template('activeemail_template', 'newsletter_layout');
		$Email->subject(__('send_activeemail'));
		$Email->emailFormat('html');
		$Email->to($email);
		$Email->from(array(__Madaner_Email => __Email_Name));
		$Email->viewVars(array('name'=>$name,'email'=>$email,'user_name'=>$user_name,'register_key'=>$register_key,'user_id'=>$user_id));	
		$ret=$Email->send();
        if(!$ret){
            /* set log */
    			Controller::loadModel('Errorlog');
    			$this->Errorlog->get_log('ActivateemailsController','Cant Send email ='.$email);
		    /* set log */
            
        /* if(!$this->Activateemail->User->delete($user_id))
    		{
    			Controller::loadModel('Errorlog');
    			$this->Errorlog->get_log('ActivateemailsController','Cant Delete user ='.$user_id);
    		}*/
        }
        
        /* set log */
    			Controller::loadModel('Errorlog');
    			$this->Errorlog->get_log('ActivateemailsController','Send email ='.$email);
		/* set log */
        
        
	}catch(Exception $e){
		/* set log */
			Controller::loadModel('Errorlog');
			$this->Errorlog->get_log('ActivateemailsController',$e->getMessage());
		/* set log */
		 
	}	
    
	
	if($isinsert){
		$this->request->data['Activateemail']['user_id']=$user_id;
		$this->request->data['Activateemail']['send_type']=$send_type;
	 	
		if($this->Activateemail->save($this->request->data))
		{
			
		}
	}else
	{
		if($send_type==3){
           $ret= $this->Activateemail->updateAll(
		    array( 'Activateemail.created' =>'"'.date('Y-m-d H:i:s').'"',
				   'Activateemail.send_type' =>'"0"'),   //fields to update
		    array( 'Activateemail.user_id' => $user_id )  //condition
		  ); 
        }
        else
        {
            $ret= $this->Activateemail->updateAll(
		    array( 'Activateemail.send_type' =>'"'.$send_type.'"'),   //fields to update
		    array( 'Activateemail.user_id' => $user_id )  //condition
		  );
        }
	}
	
 }
 
//=========================================================================================================================
		
	
	
}

