<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email'); 
class SmsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */

var $components = array('Gilace','SMS');
	 
public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('getsms');
}


 /**
 * 
 * 
*/
 function verify()
	{ 
		$User_Info= $this->Session->read('User_Info');
		if($User_Info['country_id']!=104){
			throw new NotFoundException(__('invalid page'));
			return;
		}
		
		App::import('Model','User');
		$user= new User();
		$user->recursive = -1;
 		
		$User_Info= $this->Session->read('User_Info');
		 
		if ($this->request->is('post')) 
        { 
			 
			$this->request->data=Sanitize::clean($this->request->data);
			if(isset($this->request->data['User']['mobile']) && !empty($this->request->data['User']['mobile'])){
				$mobile=$this->request->data['User']['mobile'];
				$verify=$this->Gilace->random_char(4);	
				$send_verify=$verify;
                
                $options['fields'] = array(
				'User.id',
				'User.verfiy_number' 
			   );
					   
        		$options['conditions'] = array(
        			'User.id'=>$User_Info['id']
        		);
        		$info = $user->find('first',$options);
                if($info['User']['verfiy_number']>=2	){
                    $this->Session->setFlash(__('send_for_you_max_sms'),'error');
                    return;
                }
                
                
				$result = $user->find('count', array('conditions' => array('User.mobile' => $mobile,'User.id <> '=>$User_Info['id'])));
				
				if($result<=0)
				{
					$resp=$this->SMS->GetUserBalance();
                    $response=$this->SMS->SendSMS(__('verify_code').' = '.$send_verify,$mobile,'normal') ;
                    if($response['state']=='error'){
                       // set log 
            				Controller::loadModel('Errorlog');
            				$this->Errorlog->get_log('SmsController SendSMS',$response['message']);
            		  // set log 
                    }
                    if($response['state']!='error')
						{
							 
                            $ret= $user->updateAll(
							    array( 
									'User.mobile' => '"'.$mobile.'"' ,
									'User.verify' => '"'.$verify.'"' 
								),   //fields to update
							    array( 'User.id' => $User_Info['id'] )  //condition
							  );
							if($ret)
							{
								$this->Session->write('mobile',$mobile);
								$this->Session->setFlash(__('send_verify_code_successfull'),'success');
                                $user->updateAll(
							    array( 
									'User.verfiy_number' => 'User.verfiy_number + 1'  
								),   //fields to update
							    array( 'User.id' => $User_Info['id'] )  //condition
							  );
							}
							else $this->Session->setFlash(__('send_verify_code_notsuccessfull'),'error');
						}else $this->Session->setFlash(__('not_send_verify_code'),'error');	
				}else $this->Session->setFlash(__('exist_mobile_number'),'error');
				
			}
			elseif(isset($this->request->data['User']['verify']) && !empty($this->request->data['User']['verify'])){
						
				$result = $user->find('count', array('conditions' => array('User.id' => $User_Info['id'],'verify'=>$this->request->data['User']['verify'])));
				if($result>0){
					 $ret= $user->updateAll(
					    array( 
							'User.verify' => '1' 
						),   //fields to update
					    array( 'User.id' => $User_Info['id'] )  //condition
					  );
					if($ret)
					{
						$this->Session->setFlash(__('the_mobile_verified_successfull'),'success');
					}
					else $this->Session->setFlash(__('the_mobile_verified_notsuccessfull'),'error');
				} 
			
			}	
				
		}

		 
		$options['fields'] = array(
				'User.id',
				'User.mobile',
				'User.verify' 
			   );
					   
		$options['conditions'] = array(
			'User.id'=>$User_Info['id']
		);
		$info = $user->find('first',$options);
		$this->set(compact('info'));	  
		
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
		
		$this->set('title_for_layout',__('send_post_with_sms'));
		$this->set('description_for_layout',__('send_post_with_sms'));
		$this->set('keywords_for_layout',__('send_post_with_sms'));	

  }
 
 /**
 * 
 * 
*/
function getsms(){
	 
	
	$from = $_REQUEST['from'];
    $from=$this->SMS->change_to_validnum($from);
	$message=$_REQUEST['message'];
	
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;
    
	$result = $user->find('count', array('conditions' => array('User.mobile' =>$from)));
	if($result<=0){
		/* set log */
		Controller::loadModel('Errorlog');
		$this->Errorlog->get_log('SMSConroller','not valid mobile number = '.$from);
	   /* set log */
	}
	else // save data
	{
		if(mb_strlen($message)>200){
			/* set log */
			Controller::loadModel('Errorlog');
			$this->Errorlog->get_log('SMSConroller','not valid message , a big message = '.$from.' message='.$message);
		   /* set log */
		   
		}else{ // count chatacrer < 200
			$message=trim($message);
			if(mb_strlen($message)<=0){
				/* set log */
				Controller::loadModel('Errorlog');
				$this->Errorlog->get_log('SMSConroller','not valid message ,an empty message = '.$from.' message='.$message);
			   /* set log */
			}
			else
			{
				$this->save_sms($from,$message);
                
			}
			
			
			
		}
		
		
	}
	
} 
/**
* 
* @param undefined $number
* @param undefined $message
* 
*/
function save_sms($number,$message)
{
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;
	
	App::import('Model','Post');
	$post= new Post();
	$post->recursive = -1;
	
	$options['fields'] = array(
				'User.id'  
			   );
					   
	$options['conditions'] = array(
		'User.mobile'=>$number
	);
	$info = $user->find('first',$options);
    if(empty($info)){
        /* set log */
    	Controller::loadModel('Errorlog');
    	$this->Errorlog->get_log('SMSConroller','Can not find user id , number = '.$number);
       /* set log */
    }
	
	$this->request->data['Post']['user_id']= $info['User']['id'];
	
    $this->request->data['Post']['body']=  $message;
    $this->request->data['Post']['sms']= 1;
	$this->request->data=Sanitize::clean($this->request->data);
	
    
   // print_r($this->request->data);  
    
	if($post->save($this->request->data))
	{
		$post_id= $post->getLastInsertID();
		
		$this->request->data = array();			
		$this->request->data['Allpost']['post_id']= $post_id;
		$this->request->data['Allpost']['user_id']= $info['User']['id'];
		$this->request->data['Allpost']['type']=0;
		$this->request->data=Sanitize::clean($this->request->data);
        $this->Post->Allpost->create();
		$this->Post->Allpost->save($this->request->data);
		
		$this->request->data = array();	
		$tags=$this->Gilace->get_tag($this->request->data['Post']['body'],'#'); 
		$post->Postrelatetag->Posttag->recursive = -1;
		if(isset($tags) && !empty($tags)){
			foreach($tags as $tag)
			{			
				$options['fields'] = array(
					'Posttag.id',
					'Posttag.title'
				   );
				$options['conditions'] = array(
					'Posttag.title '=> $tag
				);	   
				$tag_info= $post->Postrelatetag->Posttag->find('first',$options);
				if(isset($tag_info) && !empty($tag_info)){
					$tag_id[] = $tag_info['Posttag']['id'];
					continue;
				} 
				
				$this->request->data['Posttag']['title']= $tag;
				$post->Postrelatetag->Posttag->create();
				if($post->Postrelatetag->Posttag->save($this->request->data))
				{
					$tag_id[]=$post->Postrelatetag->Posttag->getLastInsertID();					
				}
                else
                {
                    /* set log */
            		Controller::loadModel('Errorlog');
            		$this->Errorlog->get_log('SMSConroller','Can not save on Posttag , number = '.$number);
            	   /* set log */
                }
			}
			$data = array();
			if(!empty($tag_id))
			{
				foreach($tag_id as $tid)
				{
					$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
					array_push($data,$dt);
				}
			}
				 
			if( isset($tag_id) && !empty($tag_id))
			{
				$post->Postrelatetag->create();
				if(!$post->Postrelatetag->saveMany($data))
                {
                    /* set log */
            		Controller::loadModel('Errorlog');
            		$this->Errorlog->get_log('SMSConroller','Can not save on Postrelatetag , number = '.$number);
            	   /* set log */
                }
			}
		}
	}
    else
    {
        /* set log */
		Controller::loadModel('Errorlog');
		$this->Errorlog->get_log('SMSConroller','Can not save on post , number = '.$number);
	   /* set log */
    }


	
}
 

 
 
 
 
 
}









