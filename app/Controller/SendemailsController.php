<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email'); 
class SendemailsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
 
   public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('deactive_activeemail');
	}
/**
* 
* @param undefined $user_id
* 
*/	 
 function deactive_activeemail($user_id){
    $this->Sendemail->recursive = -1;
    $this->Sendemail->Activateemail->recursive = -1; 
    
    $this->set('title_for_layout',__('deactive_send_active_email'));
	$this->set('description_for_layout',__('deactive_send_active_email'));
	$this->set('keywords_for_layout',__('deactive_send_active_email'));	
    
    $this->request->data['Sendemail']['user_id']= $user_id;
	$this->request->data=Sanitize::clean($this->request->data);
	
	$result = $this->Sendemail->find('count', array('conditions' => 
                                                                    array('Sendemail.user_id' => $this->request->data['Sendemail']['user_id'])));
    
    if($result>0){
        $this->Session->setFlash(__('send_active_email_to_user_deactive_ago'),'error');
        return;
    }
    
    $this->request->data['Activateemail']['user_id']= $user_id;
	$this->request->data=Sanitize::clean($this->request->data);
	
	$result = $this->Sendemail->Activateemail->find('count', array('conditions' => 
                                                                    array('Activateemail.user_id' => $this->request->data['Activateemail']['user_id'])));
	if($result>0){
        $this->request->data['Sendemail']['user_id']= $user_id;
        $this->request->data['Sendemail']['onsharing']='1';
        $this->request->data['Sendemail']['oncomment']='1';
        $this->request->data['Sendemail']['onfollow']='1';
        $this->request->data['Sendemail']['onmessage']='1';
        $this->request->data['Sendemail']['onnewsletteremail']='1';
        $this->request->data['Sendemail']['onlastloginemail']='1';
        $this->request->data['Sendemail']['onactiveemail']='0';
    	$this->request->data=Sanitize::clean($this->request->data);
    	
    	$this->Sendemail->create();
    	if($this->Sendemail->save($this->request->data))
    	{				
    		$this->Session->setFlash(__('deactive_send_active_email_successfull'),'success');
    	}
    	else
    	{
    		$this->Session->setFlash(__('deactive_send_active_email_notsuccessfull'),'error');
    	}
    }else $this->Session->setFlash(__('can_not_send_email_to_user'),'error');
	
    
   
    	
 }
 /**
 * 
 * 
*/
 function edit()
	{ 
		$User_Info= $this->Session->read('User_Info');
		$this->Sendemail->recursive = -1;
		if ($this->request->is('post')) 
        { 
			//pr($this->request->data);return;
			$this->request->data['Sendemail']['user_id']= $User_Info['id'];
			$this->request->data=Sanitize::clean($this->request->data);
			
			
			$result = $this->Sendemail->find('count', array('conditions' => array('Sendemail.user_id' => $User_Info['id'])));		
			if($result>0){
				if(isset($this->request->data['Sendemail']['onsharing'])){
					$onsharing = $this->request->data['Sendemail']['onsharing']; 
				}else $onsharing= 0;
				
				if(isset($this->request->data['Sendemail']['oncomment'])){
					$oncomment = $this->request->data['Sendemail']['oncomment']; 
				}else $oncomment= 0;
				
				if(isset($this->request->data['Sendemail']['onfollow'])){
					$onfollow = $this->request->data['Sendemail']['onfollow']; 
				}else $onfollow= 0;
				
				if(isset($this->request->data['Sendemail']['onmessage'])){
					$onmessage = $this->request->data['Sendemail']['onmessage']; 
				}else $onmessage= 0;
                
                if(isset($this->request->data['Sendemail']['onnewsletteremail'])){
					$onnewsletteremail = $this->request->data['Sendemail']['onnewsletteremail']; 
				}else $onnewsletteremail= 0;
                
                if(isset($this->request->data['Sendemail']['onlastloginemail'])){
					$onlastloginemail = $this->request->data['Sendemail']['onlastloginemail']; 
				}else $onlastloginemail= 0;
				
				$ret= $this->Sendemail->updateAll(
				    array( 
						'Sendemail.onsharing' => '"'.$onsharing.'"' ,
						'Sendemail.oncomment' => '"'.$oncomment.'"' ,
						'Sendemail.onfollow' => '"'.$onfollow.'"' ,
						'Sendemail.onmessage' => '"'.$onmessage.'"', 
                        'Sendemail.onnewsletteremail' => '"'.$onnewsletteremail.'"',
                        'Sendemail.onlastloginemail' => '"'.$onlastloginemail.'"'
					),   //fields to update
				    array( 'Sendemail.user_id' => $User_Info['id'] )  //condition
				  );
				if($ret)
				{
					$this->Session->setFlash(__('edit_send_email_setting_successfull'),'success');
				}
				else $this->Session->setFlash(__('edit_send_email_setting_notsuccessfull'),'error');
			}
			else
			{
				$this->Sendemail->create();
				if($this->Sendemail->save($this->request->data))
				{				
					$this->Session->setFlash(__('edit_send_email_setting_successfull'),'success');
				}
				else
				{
					$this->Session->setFlash(__('edit_send_email_setting_notsuccessfull'),'error');
				}
			}
		}

			$options['fields'] = array(
				'Sendemail.id',
				'Sendemail.user_id',
				'Sendemail.onsharing',
				'Sendemail.oncomment',
				'Sendemail.onfollow',
				'Sendemail.onmessage',
                'Sendemail.onlastloginemail',
                'Sendemail.onnewsletteremail'
			   );
					   
			$options['conditions'] = array(
				'Sendemail.user_id'=>$User_Info['id']
			);
			$sendemail = $this->Sendemail->find('first',$options);
			$this->set(compact('sendemail'));
	
		$options = array();
			$this->loadModel('User');
			$this->User->recursive = -1;
			$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.user_name',
				'User.cover_image',
				'User.cover_x',
				'User.cover_y',
				'User.cover_zoom',
				'User.image'
			   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
			
		$this->set('title_for_layout',__('send_email_setting'));
		$this->set('description_for_layout',__('edit_send_email_setting'));
		$this->set('keywords_for_layout',__('send_email_setting'));	

  }
 
 
 
 
function send_email()
{
	$user_id=$_REQUEST['user_id'];
	$location=$_REQUEST['location'];
	$text=$_REQUEST['text'];
	$post_id=$_REQUEST['post_id'];
	$User_Info= $this->Session->read('User_Info');
	
	if($this->Session->read('register_key')!=-1){
		return;
	}
	
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;
	$user->id = $user_id;
	if(!$user->exists())
	{
		$user_id= str_replace('@','',$user_id);
		$options['fields'] = array(
			'User.id',
		   );
		$options['conditions'] = array(
		'User.user_name'=> $user_id
		);	   
		$info= $user->find('first',$options);
		$user_id=$info['User']['id'];
	}
	 
	
	$ret=$this->Sendemail->check_send_email($user_id,$location);
	 
	if($ret['status']){
			
			
			$options['fields'] = array(
					'User.id',
					'User.name',
					'User.email',
					'User.image',
					'User.user_name',
					'User.sex'
				   );
			$options['conditions'] = array(
			'User.id '=> $user_id
			);	   
			$to_user_info= $user->find('first',$options);
			
			$options['fields'] = array(
					'User.id',
					'User.name',
					'User.email',
					'User.image',
					'User.user_name',
					'User.sex'
				   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$from_user_info= $user->find('first',$options);
			
			
			$Email = new CakeEmail();
			
			switch($location){
				case 'onsharing':
					$Email->template('sharing_sendemail', 'sendemail_layout');
					$Email->subject($from_user_info['User']['name']." (@".$from_user_info['User']['user_name'].") ".__('sharing_post'));
					break;
				case 'oncomment':
					$Email->template('comment_sendemail', 'sendemail_layout');
					$Email->subject($from_user_info['User']['name']." (@".$from_user_info['User']['user_name'].") ".__('comment_on_your_post'));
					break;
				case 'onfollow':
					$Email->template('follow_sendemail', 'sendemail_layout');
					$Email->subject($from_user_info['User']['name']." (@".$from_user_info['User']['user_name'].") ".__('follow_you'));
					break;
				case 'onmessage':
					$Email->template('message_sendemail', 'sendemail_layout');
					$Email->subject($from_user_info['User']['name']." (@".$from_user_info['User']['user_name'].") ".__('message_for_you'));
					break;			
			}
		
			$Email->emailFormat('html');
			$Email->to($to_user_info['User']['email']);
			$Email->from(array(__Madaner_Email => __Email_Name));
			$Email->viewVars(array('from_name'=>$from_user_info['User']['name'],'from_user_name'=>$from_user_info['User']['user_name'],'to_name'=>$to_user_info['User']['name'],'text'=>$text,'email'=>$to_user_info['User']['email'],'name'=>$to_user_info['User']['name'],'image'=>$from_user_info['User']['image'],'post_id'=>$post_id,'sex'=>$from_user_info['User']['sex']));
			$Email->send();
			
			$this->set('ajaxData', '');	 
			$this->render('/Elements/Sendemails/Ajax/ajax_result','ajax');		 
	}
} 
 
 
 
 
 
}









