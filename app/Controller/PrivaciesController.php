<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class PrivaciesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */

var $components = array('SMS');	 

 
 function edit()
	{
		
		
		       
        
        $User_Info= $this->Session->read('User_Info');
		$this->Privacy->recursive = -1;
		if ($this->request->is('post')) 
        { 
			$this->request->data['Privacy']['user_id']= $User_Info['id'];
			$this->request->data=Sanitize::clean($this->request->data);
			$result = $this->Privacy->find('count', array('conditions' => array('Privacy.user_id' => $User_Info['id'])));		
			if($result>0){
				$ret= $this->Privacy->updateAll(
				    array( 
						'Privacy.commenting' => '"'.$this->request->data['Privacy']['commenting'].'"' ,
						'Privacy.sharing' => '"'.$this->request->data['Privacy']['sharing'].'"' ,
						'Privacy.messaging' => '"'.$this->request->data['Privacy']['messaging'].'"' 
					),   //fields to update
				    array( 'Privacy.user_id' => $User_Info['id'] )  //condition
				  );
				if($ret)
				{
					$this->Session->setFlash(__('edit_privacy_successfull'),'success');
				}
				else $this->Session->setFlash(__('edit_privacy_notsuccessfull'),'error');
			}
			else
			{
				$this->Privacy->create();
				if($this->Privacy->save($this->request->data))
				{				
					$this->Session->setFlash(__('edit_privacy_successfull'),'success');
				}
				else
				{
					$this->Session->setFlash(__('edit_privacy_notsuccessfull'),'error');
				}
			}	
		}

			$options['fields'] = array(
				'Privacy.id',
				'Privacy.user_id',
				'Privacy.commenting',
				'Privacy.sharing',
				'Privacy.messaging',
				'Privacy.send_notification_email'
			   );
					   
			$options['conditions'] = array(
				'Privacy.user_id'=>$User_Info['id']
			);
			$privacy = $this->Privacy->find('first',$options);
			$this->set(compact('privacy'));
			
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
	
		$this->set('title_for_layout',__('privacy'));
		$this->set('description_for_layout',__('edit_privacy'));
		$this->set('keywords_for_layout',__('privacy'));	

  }
 
 
 
 
 
 
}









