<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email'); 
App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

    var $name = 'Users';
	var $helpers = array('Gilace','Excel','PersianDate');
	var $components = array('Gilace','Httpupload','GilaceDate'); 
	 


var $paginate = array('User'=>array(
	    'limit' => 10,
	    'order' => array(
	         'User.id' => 'asc'
	     )
	),
	
);

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow(array('register','profile','search','ajax_search','get_notofication_list','profile_follow_payee','profile_chaser','get_last_login_users','search_suggest','new_notification','new_message_count','app_industries','app_search','app_profile','app_profile_chaser','app_profile_follow_payee','get_user_info','new_users'));  
}

public function beforeRender() {
    parent::beforeRender();
 }
	
public function  email(){
	 
	 
	   	$register_email=$_REQUEST['test_email'];
		$email = new CakeEmail('smtp');
		$email->from(array(__Madaner_Email => __Email_Name));
		$email->to($register_email);
		$email->subject('فعال سازی حساب کاربری');
		$ret=$email->send('Your account have been created successfully. Please go to users/login/ to login.'.__SITE_URL.'users/confirmation_email/'.$register_email); 
		
		if($ret=1)
			   {
			   		$response['success'] =false;
			   }
	   else  $response['success'] =true;
       $response['message'] = $response['success'] ? __('correct_email') :
           __('exist_email'); 

       $this->set('ajaxData', json_encode($response));
       $this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
		
		
	    
}



public function confirmation_email($register_key=null){
	
    $this->User->recursive = -1;
    $status=0;
	$options['conditions'] = array(
		'User.register_key'=>$register_key
	);
	$count = $this->User->find('count',$options);
	 
	if($count<=0){
		$this->Session->setFlash(__('not_exist_register_key'),'error'  ); 
		$this->set('status',0);
		return;
	} 
 
    $ret= $this->User->updateAll(
	    array( 'User.status' => '1' ),   //fields to update
	    array( 'User.register_key' => $register_key )  //condition
	  );
    $this->User->updateAll(
	    array( 'User.register_key' => '-1' ),   //fields to update
	    array( 'User.register_key' => $register_key )  //condition
	  );
	  
	if($ret)
	{
		$this->Session->setFlash(__('your_account_activation_successfully'),'success' ); 
		$status = 1;
	}
	else $this->Session->setFlash(__('your_account_not_activate'),'error' ); 
	
	$this->set('status',$status);
	
}


function register_form()
{
	$this->render('/Elements/Users/Ajax/register', 'ajax');
}

/**
 * register method
 * ajax requset
 * @param $data
 * @return void
 */
public function ajax_register() {
	$response['success'] = false;
	$response['message'] = null;
    $response['goto_prev_page']=FALSE;
	
	$error=false;
	$this->request->data['User']['user_name']=$_POST['user_name'];
	if($this->request->is('post') || $this->request->is('put'))
	{
		if(!preg_match('/^[a-zA-Z_0-9]/',$this->request->data['User']['user_name']))
		{
			$response['message'] = __('invalid_user_name');
			$response['success'] =FALSE;
            $response['goto_prev_page']=TRUE;
			$error=TRUE;
		}
	}
    
    if($_POST['captcha']!=$this->Session->read('captcha'))
	{
		$response['success'] = FALSE;
		$response['message'] = __('incorrect_captcha');
		$this->set('ajaxData', json_encode($response));
		$this->render('/Elements/Users/Ajax/ajax_register', 'ajax');	
		return FALSE;
	}
	
	if ($this->request->is('post') && $error!=TRUE) 
    { 

		   $this->request->data['User']['email']=$_POST['register_email'];
		   $this->request->data['User']['register_key']=$this->Gilace->random_char();	
		   $this->request->data['User']['role_id']=2;	// user role
		   $this->request->data['User']['name']=$_POST['name'];
		   $this->request->data['User']['user_name']=$_POST['user_name'];
		   $this->request->data['User']['password']=$_POST['password'];
		   
		   $this->request->data=Sanitize::clean($this->request->data);
		   
		    if(!$this->_check_email($this->request->data['User']['email'])){
			  $response['message'] = __('exist_email');
			  $response['success'] =FALSE;
              $response['goto_prev_page']=TRUE;
			  $this->set('ajaxData', json_encode($response));
			  $this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
			  return FALSE;
		   } 
		   
		   if(!$this->_check_user_name($this->request->data['User']['user_name'])){ 
			  $response['message'] = __('exist_user_name');
			  $response['success'] =FALSE;
              $response['goto_prev_page']=TRUE;
			  $this->set('ajaxData', json_encode($response));
			  $this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
			  return FALSE;
		   }
		   $this->User->create();
			if($this->User->save($this->request->data))
			{
				 $this->Session->write('Register',array('register_email'=> $this->request->data['User']['email'],'register_key'=> $this->request->data['User']['register_key']));
			    	 
				
				$register_email=$this->request->data['User']['email'];
				$register_key=$this->request->data['User']['register_key'];
				try{
					$Email = new CakeEmail();
					$Email->template('register_sendemail', 'sendemail_layout');
					$Email->subject(__('welcome_to_madaner'));
					$Email->emailFormat('html');
					$Email->to($register_email);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('name'=>$this->request->data['User']['name'],'register_key'=>$register_key,'email'=>$this->request->data['User']['email']));
					$Email->send();
					$this->Session->write('send_status',1);
				} catch (Exception $e) {
					$this->Session->write('send_status',0);
				}
				
				$response['message'] = __('register_successfully');
			    $response['success'] =TRUE;
				//$this->redirect('/pages/display');
			}
			else
			{
				$response['message'] = __('register_not_successfully');
			    $response['success'] =FALSE;
			}

    } 
	
	$this->set('ajaxData', json_encode($response));
	$this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
}	

public function app_register() {
	$response['success'] = false;
	$response['message'] = null;
	
	$error=false;
	$this->request->data['User']['user_name']=$_POST['user_name'];
	if($this->request->is('post') || $this->request->is('put'))
	{
		if(!preg_match('/^[a-zA-Z_0-9]/',$this->request->data['User']['user_name']))
		{
			$response['message'] = __('invalid_user_name');
			$response['success'] =FALSE;
			$error=TRUE;
		}
	}	
	if ($this->request->is('post') && $error!=TRUE) 
    { 
		   $this->request->data['User']['email']=$_POST['email'];
		   $this->request->data['User']['register_key']=$this->Gilace->random_char();	
		   $this->request->data['User']['role_id']=2;	// user role
		   $this->request->data['User']['name']=$_POST['name'];
		   $this->request->data['User']['user_name']=$_POST['user_name'];
		   $this->request->data['User']['password']=$_POST['password'];
		   
		   $this->request->data=Sanitize::clean($this->request->data);
		   
		    if(!$this->_check_email($this->request->data['User']['email'])){
			  $response['message'] = __('exist_email');
			  $response['success'] =FALSE;
			  $this->set('ajaxData', json_encode($response));
			  $this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
			  return FALSE;
		   } 
		   
		   if(!$this->_check_user_name($this->request->data['User']['user_name'])){ 
			  $response['message'] = __('exist_user_name');
			  $response['success'] =FALSE;
			  $this->set('ajaxData', json_encode($response));
			  $this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
			  return FALSE;
		   }
		   $this->User->create();
			if($this->User->save($this->request->data))
			{
				 $this->Session->write('Register',array('register_email'=> $this->request->data['User']['email'],'register_key'=> $this->request->data['User']['register_key']));
			    	 
				
				$register_email=$this->request->data['User']['email'];
				$register_key=$this->request->data['User']['register_key'];
				try{
					$Email = new CakeEmail();
					$Email->template('register_sendemail', 'sendemail_layout');
					$Email->subject(__('welcome_to_madaner'));
					$Email->emailFormat('html');
					$Email->to($register_email);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('name'=>$this->request->data['User']['name'],'register_key'=>$register_key,'email'=>$this->request->data['User']['email']));
					$Email->send();
					$this->Session->write('send_status',1);
				} catch (Exception $e) {
					$this->Session->write('send_status',0);
				}
				
				$response['message'] = __('register_successfully');
			    $response['success'] =TRUE;
				//$this->redirect('/pages/display');
			}
			else
			{
				$response['message'] = __('register_not_successfully');
			    $response['success'] =FALSE;
			}

    } 
	
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
}

public function register() {
				
	$this->set('title_for_layout',__('register'));
	$this->set('description_for_layout',__('register'));
	$this->set('keywords_for_layout',__('register'));
	$this->set('invite',TRUE);
	
	if(empty($_REQUEST['ragid'])){
		$this->Session->setFlash(__('not_invite_from_users'),'error');
		$this->set('invite',FALSE);
		return;
	}
	$id = Sanitize::clean($_REQUEST['ragid']);
	
	$this->User->recursive = -1;
	$options['fields'] = array(
		'User.id',
		'User.name',
		'User.user_name',
		'User.email',
    );
	$options['conditions'] = array(
		'md5(User.id)'=> $id
	);	   
	$user= $this->User->find('first',$options);	
	if(empty($user)){
		$this->Session->setFlash(__('not_invite_from_users'),'error');
		$this->set('invite',FALSE);
		return;
	}else{
		 $this->set('user_name',$user['User']['user_name']);
	}
	
	$industries = $this->User->Industry->query("
									SELECT 
											Industry.id, 
											Industry.title_".$this->Session->read('Config.language')." as title
											
									FROM industries AS Industry      
									ORDER BY Industry.title_".$this->Session->read('Config.language')." asc");
	$this->set(compact('industries'));
	
	
	$countries = $this->User->query("
									SELECT 
											Country.id, 
											Country.name_".$this->Session->read('Config.language')." as name 
											
									FROM countries AS Country      
									ORDER BY Country.name_".$this->Session->read('Config.language')." asc");
	$this->set(compact('countries'));	
	
	$error=false;
	
	 
	if($this->request->is('post') || $this->request->is('put'))
	{
		if(!preg_match('/^[a-zA-Z_0-9]/',$this->request->data['User']['user_name']))
		{
			$this->Session->setFlash(__('invalid_user_name'),'error');
			$error=TRUE;
		}
	}
	
	if ($this->request->is('post') && $error!=TRUE) 
    { 
			
		   $this->request->data['User']['register_key']= md5($this->Gilace->random_char());	
		   $this->request->data['User']['role_id']=2;	// user role
		   
		   
		   $this->request->data=Sanitize::clean($this->request->data);
		   $from_name = $this->request->data['User']['name'];
		   $from_user_name = $this->request->data['User']['user_name'];
		   
		    if(!$this->_check_email($this->request->data['User']['email'])){
	 	      $this->Session->setFlash(__('exist_email'),'error');
			  return FALSE;
		   } 
		   
		   if(!$this->_check_user_name($this->request->data['User']['user_name'])){
	 	      $this->Session->setFlash(__('exist_user_name'),'error');
			  return FALSE;
		   }
		   $this->User->create();
			if($this->User->save($this->request->data))
			{
				 $this->Session->write('Register',array('register_email'=> $this->request->data['User']['email'],'register_key'=> $this->request->data['User']['register_key']));
			    	 
				
				$register_email=$this->request->data['User']['email'];
				$register_key=$this->request->data['User']['register_key'];
				try{
					$Email = new CakeEmail();
					$Email->template('register_sendemail', 'sendemail_layout');
					$Email->subject(__('welcome_to_madaner'));
					$Email->emailFormat('html');
					$Email->to($register_email);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('name'=>$this->request->data['User']['name'],'register_key'=>$register_key,'email'=>$this->request->data['User']['email']));
					$Email->send();
					$this->Session->write('send_status',1);
				} catch (Exception $e) {
					$this->Session->write('send_status',0);
				}
				
				$this->Session->setFlash(__('register_successfully'),'success');
				//$this->redirect('/pages/display');
				
				$this->request->data = array();
				$this->User->Follow->recursive = -1;
				$from_user_id=$this->User->getLastInsertID();
				$this->request->data['Follow']['from_user_id']= $from_user_id;
				$this->request->data['Follow']['to_user_id']= $user['User']['id'];
				$this->User->Follow->create();
				if($this->User->Follow->save($this->request->data)){
					$this->request->data = array();
					$long = strtotime(date('Y-m-d H:i:s'));
					$this->request->data['Follownotification']['follow_id']= $this->User->Follow->getInsertId();
					$this->request->data['Follownotification']['from_user_id']= $from_user_id;
					$this->request->data['Follownotification']['to_user_id']= $user['User']['id'];
					$this->request->data['Follownotification']['insertdt']= date('Ymd',$long);
					$this->request->data['Follownotification']['inserttm']= date('Hi',$long);
					$this->request->data=Sanitize::clean($this->request->data);
					$this->User->Follow->Follownotification->save($this->request->data);
					
					$Email = new CakeEmail();
					$Email->template('follow_sendemail', 'sendemail_layout');
					$Email->subject(__('follow_you'));
					$Email->emailFormat('html');
					$Email->to($user['User']['email']);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('from_name'=>$from_name,'from_user_name'=>$from_user_name,'to_name'=>$user['User']['name'],'text'=>'','email'=>$user['User']['email'],'name'=>$user['User']['name'],'image'=>'','post_id'=>'','sex'=>-1));
					$Email->send();
				}
			}
			else
			{
				$this->Session->setFlash(__('register_not_successfully'),'error');
			}

    } 
	
}
	

public function _check_email($email) {
   $this->User->recursive = -1;		 	   
   $result = $this->User->find('count', array('conditions' => array('User.email' => $email)));
   if($result>=1)
   {
   		return false;
   }
	return TRUE; 
}

public function _check_user_name($user_name) {
   $this->User->recursive = -1;		 	   
   $result = $this->User->find('count', array('conditions' => array('User.user_name' => $user_name)));
   if($result>=1)
   {
   		return false;
   }
	return TRUE; 
}	
	
/**
* regitser with ajax
* 
*/



public function check_email() {
		 
		if ($this->request->is('post')) 
        { 
               $response['success'] = false;
		       $response['message'] = null;
			   $email=$_POST['email'];	   
			   $result = $this->User->find('count', array('conditions' => array('User.email' => $email)
));
			   if($result>=1)
			   {
			   		$response['success'] =false;
			   }
			   else  $response['success'] =true;
		       $response['message'] = $response['success'] ? __('correct_email') : __('exist_email'); 

		       $this->set('ajaxData', json_encode($response));
		       $this->render('/Elements/Users/Ajax/ajax_register', 'ajax');
        }
		 
}

 public function check_captcha() {
		 
		if ($this->request->is('post')) 
        { 
               $response['success'] = false;
		       $response['message'] = null;
			   $captcha=$_POST['captcha'];	 
			   
			   if($captcha!=$this->Session->read('captcha'))
				{
					$response['success'] =false;
				}
				else $response['success'] =true;
			   

		       $response['message'] = $response['success'] ? __('correct_captcha') :
		           __('incorrect_captcha'); 

		       $this->set('ajaxData', json_encode($response));
		       $this->render('/Elements/Users/Ajax/ajax_captcha', 'ajax');
        }
		 
	}


/**
* view profile of user
* @param undefined $id
* 
*/
function profile($id = null)
	{
		$this->Session->write('Site_Info',array(
		'OldController' => $this->request->params['controller'] ,
		'OldAction' => $this->request->params['action'], 
		'OldId' => $id
		));
		
				
		$User_Info= $this->Session->read('User_Info');
		$this->User->recursive = -1;
		$this->User->id = $id;
		if(!$this->User->exists())
		{
			// update view Follownotification
			 
			$ret= $this->User->Follow->Follownotification->updateAll(
			    array( 'Follownotification.viewed' =>'"1"'),   //fields to update
			    array( 'Follownotification.to_user_id' => $User_Info['id'] )  //condition
			  );		
			
			$options['fields'] = array(
			'User.id',
		   );
			$options['conditions'] = array(
			'User.user_name'=> $id
			);	   
			$user= $this->User->find('first',$options);
			if(!isset($user)){
				throw new NotFoundException(__('invalid id for user'));
				return;
			}
			$id=$user['User']['id'];
			$this->Session->write('Site_Info',array(
				'OldController' => $this->request->params['controller'] ,
				'OldAction' => $this->request->params['action'], 
				'OldId' => $id
				));
			if(!isset($id)){
				throw new NotFoundException(__('invalid id for user'));
				return;
			}
		}
		
		$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.age',
				'User.email',
				'User.image',
				'User.pdf',
				'User.user_name',
				'User.cover_image',
				'User.site',
				'User.location',
				'User.details',
				'Industry.id',
				'Industry.title_'.$this->Session->read('Config.language').' as title'
			   );
		$options['joins'] = array(
				array('table' => 'industries',
					'alias' => 'Industry',
					'type' => 'LEFT',
					'conditions' => array(
					'Industry.id = User.industry_id ',
					)
				) 
				
			);	
				   
		$options['conditions'] = array(
			'User.id'=>$id ,
			'User.status'=>1
		);
		$user = $this->User->find('first',$options);
		$this->set(compact('user'));
		if(empty($user)){
			$this->Session->setFlash(__('not_exist_user'),'error');
			return;
		}
		
		$this->set('title_for_layout',$user['User']['name']);
		$this->set('description_for_layout',$user['User']['details']);
		$this->set('keywords_for_layout',$user['User']['name']);
		
		$is_follow = $this->User->Follow->find('count', array('conditions' => array('Follow.from_user_id' => $User_Info['id'],'Follow.to_user_id'=>$id)));
		$this->set(compact('is_follow'));
		
		$this->User->Blockuser->recursive = -1;
		$is_block = $this->User->Blockuser->find('count', array('conditions' => array('Blockuser.from_user_id' => $User_Info['id'],'Blockuser.to_user_id'=>$id)));
		$this->set(compact('is_block'));
        
        
        $this->User->Chat->recursive = -1;
        $new_message_count = $this->User->Chat->find('count', array('conditions' => array('Chat.to_user_id'=>$User_Info['id'],'Chat.viewd'=>0,'Chat.from_delete'=>0)));
		
		$this->set('new_message_count',$new_message_count);
		$this->set('user_id',$id);
        
	}	


function app_profile($user_id ,$my_id)
	{	
		$User_Info= $this->Session->read('User_Info');
		
		$response['success'] = false;
		$response['message'] = null;
		
		$this->User->recursive = -1;
		$this->User->id = $user_id;
		if(!$this->User->exists())
		{
			$options['fields'] = array(
			'User.id',
		   );
			$options['conditions'] = array(
			'User.user_name'=> $user_id
			);	   
			$user= $this->User->find('first',$options);
			if(!isset($user)){
				$response['success'] = FALSE;
				$response['message'] = __('invalid_id_for_user');
				$this->set('ajaxData',  json_encode($response));
				$this->render('/Elements/Users/Ajax/ajax_result', 'ajax');
				return;
			}
			$user_id=$user['User']['id'];
			if(!isset($user_id)){
				$response['success'] = FALSE;
				$response['message'] = __('invalid_id_for_user');
				$this->set('ajaxData',  json_encode($response));
				$this->render('/Elements/Users/Ajax/ajax_result', 'ajax');
				return;
			}
		}
		
		$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.age',
				'User.email',
				'User.image',
				'User.pdf',
				'User.user_name',
				'User.cover_image',
				'User.site',
				'User.location',
				'User.details',
				'Industry.id',
				'Industry.title_'.$this->Session->read('Config.language').' as title'
			   );
		$options['joins'] = array(
				array('table' => 'industries',
					'alias' => 'Industry',
					'type' => 'LEFT',
					'conditions' => array(
					'Industry.id = User.industry_id ',
					)
				) 
				
			);	
				   
		$options['conditions'] = array(
			'User.id'=>$user_id ,
			'User.status'=>1
		);
		$user = $this->User->find('first',$options);
		 
		$response['user']=$user;
		if(empty($user)){
			$response['success'] = FALSE;
			$response['message'] = __('not_exist_user');
			$this->set('ajaxData',  json_encode($response));
			$this->render('/Elements/Users/Ajax/ajax_result', 'ajax');
			return;
		}
				
		$this->User->Post->recursive = -1;
		$post_count = $this->User->Post->find('count', array('conditions' => array('Post.user_id' => $user_id)));
		
		$this->User->Post->Sharepost->recursive = -1;
		$share_post_count = $this->User->Post->Sharepost->find('count', array('conditions' => array('Sharepost.user_id' => $user_id)));
		$post_count = $post_count + $share_post_count; 
		$response['post_count']=$post_count;
		
		$follow_me = $this->User->Follow->find('count', array('conditions' => array('Follow.from_user_id' => $user_id)));
		$response['follow_me']=$follow_me; 
		
		$this->User->Follow->recursive = -1;
		$me_follow = $this->User->Follow->find('count', array('conditions' => array('Follow.to_user_id' => $user_id)));
		$response['me_follow']=$me_follow;
		
		$is_follow = $this->User->Follow->find('count', array('conditions' => array('Follow.from_user_id' => $my_id,'Follow.to_user_id'=>$user_id)));
		$response['is_follow']=$is_follow;
		
		$this->User->Blockuser->recursive = -1;
		$is_block = $this->User->Blockuser->find('count', array('conditions' => array('Blockuser.from_user_id' => $my_id,'Blockuser.to_user_id'=>$user_id)));
		$response['is_block']=$is_block;
		$response['success'] = TRUE;
 		 		
		$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
}	


/**
* list on users
* @param undefined $id
* 
*/

function user_list()
{
	$this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$options['fields'] = array(
			'User.id',
			'User.name',
			'User.sex',
			'User.age',
			'User.email',
			'User.image',
			'User.user_name',
			' Follow.to_user_id '
		   );
		   
	/*$options['contain'] = array(
			'Follow' => array(
			'fields' => array('title_'.$this->Session->read('Config.language').' as title '),
			'conditions' => '',
			'order' => ''
			)
			);*/
	$options['joins'] = array(
		array('table' => 'follows',
		'alias' => 'Follow',
		'type' => 'LEFT',
		'conditions' => array(
		'Follow.to_user_id = User.id AND Follow.from_user_id ='.$User_Info['id'],
		)
		)
    );			   
	$options['conditions'] = array(
		'User.role_id'=>'2' ,
		'User.status'=>'1' ,
		'User.id <>'=> $User_Info['id']
	);	   
	$options['order'] = array(
		'User.id'=>'desc'
	);
	$users = $this->User->find('all',$options);
	$this->set(compact('users'));
}

/**
* 
* @param undefined $id
* 
*/
function edit_profile($id = null)
	{
		$error = FALSE;
		$this->User->recursive = -1;
		$User_Info= $this->Session->read('User_Info');
		$this->User->id = $User_Info['id'];
        if(($this->request->is('post') || $this->request->is('put')))
    		{
		        $datasource = $this->User->getDataSource();
		        try{
		            $datasource->begin();
		            if( isset($this->request->data['User']['industry_id']) && $this->request->data['User']['industry_id']==0 && $this->request->is('post'))
		                throw new Exception(__('invalid_industry'),1); 
		                
		            if(isset($this->request->data['User']['name']) && trim($this->request->data['User']['name'])=='' && $this->request->is('post'))
		                 throw new Exception(__('invalid_name'),2); 
		                 
		            if(!$this->User->exists())     
		                throw new Exception(__('invalid_id_for_user'),3);  
		            
		            
		    			$this->request->data=Sanitize::clean($this->request->data);			
		    			
		    			$data=Sanitize::clean($this->request->data);
						/*
		    			$file = $data['User']['pdf'];
		    			if($file['size']>0)
		    			  {
		    				$filename=$data['User']['old_pdf'];
		    				@unlink(__USER_FILE_PATH."/".$filename);
		    				
		    				$output=$this->_upload_pdf();
		    				if(!$output['error']) {
		    					$this->request->data['User']['pdf']=$output['filename'];
		    				}
		    				else {
		    					$this->Session->setFlash($output['message'],'error');
		    					$error=TRUE;
		    					$this->request->data['User']['pdf']='';
		    				}
		    			 }
		    			 else{
		    			 	$this->request->data['User']['pdf']=$this->request->data['User']['old_pdf'];
		    			 }*/
		                
		                if($this->User->save($this->request->data))
						{	
							$this->User->Industry->recursive = -1;
							$options['fields'] = array(
								'Industry.id',
								'Industry.title_'.$this->Session->read('Config.language').' as title'
							   );
							$options['conditions'] = array(
								'Industry.id '=> $this->request->data['User']['industry_id']
							);	   
							$industry_info= $this->User->Industry->find('first',$options);
							$this->Session->write('User_Info',array(
								'id' => $User_Info['id'] ,
								'name' => $this->request->data['User']['name'] ,
								'sex' => $User_Info['sex'] ,
								'age' => $User_Info['age'],
								'email' => $User_Info['email'],
								'image' => $User_Info['image'],
								'industry_id' => $this->request->data['User']['industry_id'],
								'user_type' => $User_Info['user_type'] ,
								'user_name' => $User_Info['user_name'],
								'location' => $this->request->data['User']['location'],
							    'industry_name'=>$industry_info['Industry']['title'],
			                    'details'=>$User_Info['details'],
			                    'follow_count' => $User_Info['follow_count']
								));
						}
						else
						{
		                    throw new Exception(__('edit_profile_notsuccessfull'),4);  
						} 
						
						if(!$this->User->Userrelatetag->deleteAll(array('Userrelatetag.user_id'=>$User_Info['id']),FALSE))
								throw new Exception(__('the_tag_not_saved'),7);
		                
		                if(isset($_POST['new_tags'])&& !empty($_POST['new_tags'])){
							$tags=$_POST['new_tags'];//explode('#',$this->request->data['Userrelatetag']['tag']);
							$tags=array_filter($tags,'strlen');
							$this->loadModel('Usertag');
							if(!empty($tags)){
								foreach($tags as $tag)
								{
									
                                    $count = $this->Usertag->find('count', array('conditions' => array('Usertag.title' => $tag)));
                                    if($count>0){
                                        continue;
                                    }
                                    
                                    $this->request->data['Usertag']['title']= $tag;
									$this->Usertag->create();
									
									if($this->Usertag->save($this->request->data))
									{
										$tag_id[]=$this->Usertag->getLastInsertID();					
									}
		                            else throw new Exception(__('the_tag_not_saved'),5);
								}
							}
							
						}
						$data = array();
						if(isset($this->request->data['Userrelatetag']['usertag_id'])){
							foreach($this->request->data['Userrelatetag']['usertag_id'] as $id)
							{
								$dt=array('Userrelatetag' => array('user_id' => $User_Info['id'],'usertag_id'=>$id));
								array_push($data,$dt);
							}
						}
						//pr($tag_id); 
						if(!empty($tag_id))
							{
								foreach($tag_id as $tid)
								{
									$dt=array('Userrelatetag' => array('user_id' => $User_Info['id'],'usertag_id'=>$tid));
									array_push($data,$dt);
								}
								
							}
							
						//pr($data);throw new Exception(); 
						
						if(!empty($this->request->data['Userrelatetag']['usertag_id']) || !empty($tag_id))
						{
							$this->User->Userrelatetag->create();
							if(!$this->User->Userrelatetag->saveMany($data))
		                            throw new Exception(__('the_user_tag_not_saved'),6);
						}
		                 
		                
		            
		            
		            $datasource->commit();
		            
		            $this->Session->setFlash(__('edit_profile_successfull'), 'success');
					 
		        } catch(Exception $e) {
		            $datasource->rollback();
					if(in_array($e->getCode(),array(1,2,3,4,5,6,7))){
						$this->Session->setFlash($e->getMessage(),'error');
					}else
						$this->Session->setFlash(__('edit_profile_notsuccessfull'),'error'); 
		        }     
		
		} 
		
		$options['fields'] = array(
			'User.id',
			'User.name',
			'User.sex',
			'User.age',
			'User.email',
			'User.image',
			'User.pdf',
			'User.user_name',
			'User.cover_image',
			'User.location',
			'User.site',
			'User.details',
			'User.industry_id'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
			
			
			
		$this->User->Industry->recursive = -1;
		
		$industries = $this->User->Industry->query("
									SELECT 
											Industry.id, 
											Industry.title_".$this->Session->read('Config.language')." as title
									FROM industries AS Industry      
									ORDER BY Industry.title_".$this->Session->read('Config.language')." asc");
		$this->set(compact('industries'));	
		
		$this->User->Userrelatetag->recursive = -1;
		
		$options['fields'] = array(
				'Usertag.id',
				'Usertag.title'
			   );
		   $options['joins'] = array(
			array('table' => 'usertags',
				'alias' => 'Usertag',
				'type' => 'LEFT',
				'conditions' => array(
				'Usertag.id = Userrelatetag.usertag_id ',
				)
			) 
			
		   );
			   
		$options['conditions'] = array(
			'Userrelatetag.user_id' => $User_Info['id']
		);
		$options['order'] = array(
		'Usertag.id'=>'asc'
		);
		$tags = $this->User->Userrelatetag->find('all',$options);
		$this->set('tags',$tags);
		
		
		$this->set('title_for_layout',__('edit_all_info'));
		$this->set('description_for_layout',__('edit_profile_description'));
		$this->set('keywords_for_layout',__('edit_all_info'));	
	 
	}

/**
* 
* @param undefined $id
* 
*/
function edit_account($id = null)
	{
		$this->User->recursive = -1;
		$User_Info= $this->Session->read('User_Info');
		$error=FALSE;
		
		$this->User->id = $User_Info['id'];
		if(!$this->User->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_user'),'error');
			$error=TRUE;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			if(!preg_match('/^[a-zA-Z_0-9]/',$this->request->data['User']['user_name']))
			{
				$this->Session->setFlash(__('invalid_user_name'),'error');
				$error=TRUE;
			}
		}
		
		if(($this->request->is('post') || $this->request->is('put')) && $error!=TRUE )
		{
			
			$this->request->data=Sanitize::clean($this->request->data);
			$this->request->data['User']['user_name']=trim(str_replace(' ','',$this->request->data['User']['user_name']));
			$result = $this->User->find('count', array('conditions' => array('User.user_name' => $this->request->data['User']['user_name'],'User.id <>'=>$User_Info['id'])));
		
			if($result>0){
				$this->Session->setFlash(__('exist_user_name'),'error');
			}
			else
			{
				 
				
				if($this->User->save($this->request->data))
				{				
					$this->Session->write('User_Info',array(
					'id' => $User_Info['id'] ,
					'name' => $User_Info['name'] ,
					'sex' => $this->request->data['User']['sex'] ,
					'age' => $User_Info['age'],
					'email' => $User_Info['email'],
					'image' => $User_Info['image'],
					'industry_id' => $User_Info['industry_id'],
					'user_type' => $User_Info['user_type'] ,
					'user_name' => $this->request->data['User']['user_name'] ,
					'location' => $User_Info['location'],
					'industry_name'=>$User_Info['industry_name'],
                    'details'=>$User_Info['details'],
                    'follow_count' => $User_Info['follow_count']
					
					));
					$this->Session->setFlash(__('edit_profile_successfull'),'success');
				}
				else
				{
					$this->Session->setFlash(__('edit_profile_notsuccessfull'),'error');
				}
			}
		}
		 
		
		
		$options['fields'] = array(
			'User.id',
			'User.name',
			'User.sex',
			'User.user_name',
			'User.user_type',
			'User.search_with_email',
			'User.email',
			'User.cover_image',
			'User.image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
			
			$this->set('title_for_layout',__('edit_account'));
		    $this->set('description_for_layout',__('edit_account_description'));
		    $this->set('keywords_for_layout',__('edit_account'));	
	}


/**
* 
* @param undefined $id
* 
*/
function edit_email($id = null)
	{
		$this->User->recursive = -1;
		$User_Info= $this->Session->read('User_Info');
		$this->User->id = $User_Info['id'];
		if(!$this->User->exists())
		{
			throw new NotFoundException(__('invalid id for user'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data['User']['email']=trim($this->request->data['User']['new_email']);
			
			$result = $this->User->find('count', array('conditions' => array('User.email' => $this->request->data['User']['email'],'User.id <>'=>$User_Info['id'])));
		
			if($result>0){
				$this->Session->setFlash(__('exist_email'),'error');
			}
			else
			{
				$this->request->data['User']['register_key']=$this->Gilace->random_char();
				$this->request->data['User']['status']=0;
				$this->request->data=Sanitize::clean($this->request->data);
				if($this->User->save($this->request->data))
				{
					
					$register_email=$this->request->data['User']['email'];
					$register_key=$this->request->data['User']['register_key'];
					try{
						$Email = new CakeEmail();
						$Email->template('register_sendemail', 'sendemail_layout');
						$Email->subject(__('welcome_to_madaner'));
						$Email->emailFormat('html');
						$Email->to($register_email);
						$Email->from(array(__Madaner_Email => __Email_Name));
						$Email->viewVars(array('name'=>$User_Info['name'],'register_key'=>$register_key,'email'=>$this->request->data['User']['email']));
						$Email->send();
						$this->Session->write('send_status',1);
						
						$this->Cookie->delete('User');
						$this->Session->delete('User_Info');
						$this->Session->delete('Login_Attempt');
						$this->Auth->logout();
						
					} catch (Exception $e) {
						$this->Session->write('send_status',0);
					}
					
					
					
					/*$this->Session->write('User_Info',array(
					'id' => $User_Info['id'] ,
					'name' => $User_Info['name'] ,
					'sex' => $User_Info['sex'] ,
					'age' => $User_Info['age'],
					'email' => $this->request->data['User']['email'],
					'image' => $User_Info['image'],
					'industry_id' => $User_Info['industry_id'],
					'user_type' => $User_Info['user_type'] ,
					'user_name' => $User_Info['user_name']
					
					));*/
					$this->Session->setFlash(__('edit_email_successfull'),'success');
				}
				else
				{
					$this->Session->setFlash(__('edit_email_notsuccessfull'),'error');
				}
			}
		}
		
		$options['fields'] = array(
			'User.id',
			'User.name',
			'User.sex',
			'User.user_name',
			'User.email',
			'User.cover_image',
			'User.image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
			
			$this->set('title_for_layout',__('edit_email'));
		    $this->set('description_for_layout',__('edit_email_description'));
		    $this->set('keywords_for_layout',__('edit_email'));	
	}


/**
* 
* @param undefined $id
* 
*/
function edit_password($id = null)
	{
		$this->User->recursive = -1;
		$User_Info= $this->Session->read('User_Info');
		$this->User->id = $User_Info['id'];
		if(!$this->User->exists())
		{
			throw new NotFoundException(__('invalid id for user'));
		}
		
		$options['fields'] = array(
			'User.id',
			'User.name',
			'User.sex',
			'User.user_name',
			'User.email',
			'User.cover_image',
			'User.image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			
			$this->request->data['User']['password']= $this->request->data['User']['new_password'] ;
			$new_pass = $this->request->data['User']['password'];
			$result = $this->User->find('count', array('conditions' => array('User.password' =>AuthComponent::password($this->request->data['User']['old_password']),'User.id'=>$User_Info['id'])));
		
			if($result<=0){
				$this->Session->setFlash(__('not_valid_password'),'error');
			}
			else
			{
				$this->request->data=Sanitize::clean($this->request->data);
				if($this->User->save($this->request->data))
				{
					$this->Session->setFlash(__('edit_password_successfull'),'success');
					try{
						$Email = new CakeEmail();
						$Email->template('forgetpass_sendemail', 'sendemail_layout');
						$Email->subject(__('change_password'));
						$Email->emailFormat('html');
						$Email->to($register_email);
						$Email->from(array(__Madaner_Email => __Email_Name));
						$Email->viewVars(array('name'=>$User_Info['name'],'password'=>$new_pass,'email'=>$register_email));
						$Email->send();
						$this->Session->write('send_status',1);
						
						} catch (Exception $e) {
							$this->Session->write('send_status',0);
					}
					
					
				}
				else
				{
					$this->Session->setFlash(__('edit_password_notsuccessfull'),'error');
				}
			}
		}
		
		
		
		$this->set('title_for_layout',__('edit_password'));
	    $this->set('description_for_layout',__('edit_password_description'));
	    $this->set('keywords_for_layout',__('edit_password'));	
		
	}

/**
* 
* 
*/
function search(){
	$User_Info= $this->Session->read('User_Info');
	if(!isset($User_Info)){
		$User_Info = array('id'=>0);
	}
	$user_type = -1;
	$end = 5;
    
	if(isset($_POST['first'])){
	 	$start = $_POST['first'];
	 }else{
	  	$start = 0;
	  }
	
	if($this->request->is('post') || $this->request->is('put') ){ 
		$this->request->data=Sanitize::clean($this->request->data);
		$search_word=$this->request->data['User']['search_word'];
		$this->Session->write('search_word',$search_word);
		if(isset($this->request->data['User']['user_type']))
			$user_type = $this->request->data['User']['user_type'];
		else $user_type = -1;

		$this->set('user_type', $user_type);		
		$this->set('action_type','post');
		$this->set('action_type_value', $search_word);	
	}
	  
	if(isset($_REQUEST['user_type']) ){

		$user_type = $_REQUEST['user_type']; 
		
		$this->set('action_type','user_type');
		$this->set('action_type_value', $user_type);
	}
		
	if(isset($_REQUEST['industry']) ){

		$industry = $_REQUEST['industry']; 
		
		$this->set('action_type','industry');
		$this->set('action_type_value', $industry);
		
	}
    
    if(isset($_REQUEST['tag']) ){

		$tag = $_REQUEST['tag']; 
		
		$this->set('action_type','tag');
		$this->set('action_type_value', $tag);
		
	}
	
	/*
	$industries_with_count = $this->User->Industry->query("
									SELECT 
											Industry.id, 
											Industry.title_".$this->Session->read('Config.language')." as title, 
											(select count(*) from users as User where  industry_id=Industry.id and User.status= 1 and User.role_id = 2 )as 'count'
									FROM industries AS Industry      
									ORDER BY Industry.title_".$this->Session->read('Config.language')." asc");
	$this->set(compact('industries_with_count'));*/
	
	
    $this->set(compact('search_result')); 
	$this->set(compact('user_type')); 
	
	$this->set('title_for_layout',__('search'));
    $this->set('description_for_layout',__('search_description'));
    $this->set('keywords_for_layout',__('search'));	

}

function ajax_search(){
	$User_Info= $this->Session->read('User_Info');
	if(!isset($User_Info)){
		$User_Info = array('id'=>0);
	}
	$user_type = -1;
	$end=5;
	
	if(isset($_REQUEST['first']))
	{
		$start = $_REQUEST['first'];
	}	
	
	if((isset($_REQUEST['action_type'])&&$_REQUEST['action_type']=='post') ){
		
		
		if(isset($_REQUEST['action_type'])){
			$search_word = $_REQUEST['action_type_value'];
			$user_type = $_REQUEST['user_type']; 
		}
	

		if($user_type==0 || $user_type == 1){
			$sql1= " and  User.user_type = ".$user_type;
		}else $sql1 = '';
		
		$this->set('sql1', $sql1);
		
		$search_result = $this->User->query("
										SELECT 
												distinct(User.id), 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												User.details,
												 User.location ,
												 User.site ,
												 User.user_type,
												 (select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=User.id )as 'count',
												 Industry.title_".$this->Session->read('Config.language')." as title
												 
										 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
												left join userrelatetags as Userrelatetag	
											    	   on Userrelatetag.user_id = User.id
											    left join usertags as Usertag       
											            on 	Usertag.id = Userrelatetag.usertag_id	  
										where (
											 (name like '".$search_word."%' or name like '% ".$search_word."%') or
											 (user_name like '".$search_word."%' or user_name like '% ".$search_word."%') or
											 ((User.email like '".$search_word."%' or User.email like '% ".$search_word."%') and User.search_with_email=1) or
											 (Usertag.title like '".$search_word."%' or Usertag.title like '% ".$search_word."%')
											   )
										   and User.status= 1
										   and User.role_id = 2 ".$sql1."
										   
										order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
												 
											LIMIT $start , $end	 
												 ");
		
	}
	 
	if(isset($_REQUEST['action_type']) &&  $_REQUEST['action_type']==''){
		$search_result = $this->User->query("
									SELECT 
											User.id, 
											User.name, 
											User.sex,
											User.email,
											User.image, 
											User.user_name,
											User.details,
											User.location ,
											User.site ,
											User.user_type,
											(select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=User.id )as 'count',
											 Industry.title_".$this->Session->read('Config.language')." as title
											 
									 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
									where  User.status= 1
									   and User.role_id = 2
									order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
											 
										LIMIT $start , $end	 	 
											 ");
	}
	
	if(isset($_REQUEST['action_type']) &&  $_REQUEST['action_type']=='user_type'){
		if(isset($_REQUEST['action_type'])){
			$user_type = $_REQUEST['action_type_value']; 
		}

		$search_result = $this->User->query("
									SELECT 
											User.id, 
											User.name, 
											User.sex,
											User.email,
											User.image, 
											User.user_name,
											User.details,
											 User.location ,
											 User.site ,
											 User.user_type,
											 (select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=User.id )as 'count',
											 Industry.title_".$this->Session->read('Config.language')." as title
											 
									 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
									where User.user_type = ".$user_type."
									   and User.status= 1
									   and User.role_id = 2
									   
									order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
											 
										LIMIT $start , $end	 	 
											 ");
	}
	
	
	if(isset($_REQUEST['action_type']) &&  $_REQUEST['action_type']=='industry'){
		if(isset($_REQUEST['action_type'])){
			$industry = $_REQUEST['action_type_value']; 
		}

		if($industry==0){
			$industry_sql='';
		}else
		$industry_sql= "User.industry_id = ".$industry." and ";

		$search_result = $this->User->query("
									SELECT 
											User.id, 
											User.name, 
											User.sex,
											User.email,
											User.image, 
											User.user_name,
											User.details,
											 User.location ,
											 User.site ,
											 User.user_type,
											 (select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=User.id )as 'count',
											 Industry.title_".$this->Session->read('Config.language')." as title
											 
									 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
									where   ".$industry_sql."
									       User.status= 1
									   and User.role_id = 2
									   
									order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
											 
										LIMIT $start , $end	 	 
											 ");
	}
    
    if(isset($_REQUEST['action_type']) &&  $_REQUEST['action_type']=='tag'){

    if(isset($_REQUEST['action_type'])){
			$title = $_REQUEST['action_type_value']; 
		}
		$search_result = $this->User->query("
									SELECT 
											User.id, 
											User.name, 
											User.sex,
											User.email,
											User.image, 
											User.user_name,
											User.details,
											 User.location ,
											 User.site ,
											 User.user_type,
											 (select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=User.id )as 'count',
											 Industry.title_".$this->Session->read('Config.language')." as title
											 
									 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
                                                inner join userrelatetags as Userrelatetag   
                                                        on  Userrelatetag.user_id = User.id
                                                inner join usertags as Usertag    
                                                        on Usertag.id= Userrelatetag.usertag_id     
									where Usertag.title = '".$title."'
									   and User.status= 1
									   and User.role_id = 2
									   
									order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
											 
										LIMIT $start , $end	 	 
											 ");
	}
		
		

		$this->set('search_result', $search_result);
		$this->render('/Elements/Users/Ajax/search_result', 'ajax');	

	
}

function app_industries(){
	$this->User->Industry->recursive = -1;
	
	$industries_with_count = $this->User->Industry->query("
		SELECT 
				Industry.id, 
				Industry.title_".$this->Session->read('Config.language')." as title, 
				(select count(*) from users as User where  industry_id=Industry.id and User.status= 1 and User.role_id = 2 )as 'count'
		FROM industries AS Industry      
		ORDER BY Industry.title_".$this->Session->read('Config.language')." asc");
	
    $this->set(array(
		'industries_with_count' => $industries_with_count,
		'_serialize' => array('industries_with_count')
		));
}
/**
* 
* @param undefined $user_id
* 
*/
function app_search($user_id){

	$user_type = -1;
	$end=5;
	
	if(isset($_REQUEST['first']))
	{
		$start = $_REQUEST['first'];
	}	
	
	if((isset($_REQUEST['action_type'])&&$_REQUEST['action_type']=='post') ){
		
		
		if(isset($_REQUEST['action_type'])){
			$search_word = $_REQUEST['action_type_value'];
		}
		
		$search_result = $this->User->query("
										SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												User.details,
												 User.location ,
												 User.site ,
												 User.user_type,
												 (select count(*) from follows where from_user_id=".$user_id." and to_user_id=User.id )as 'count',
												 Industry.title_".$this->Session->read('Config.language')." as title
												 
										 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
										where (
											     (name like '".$search_word."%' or name like '% ".$search_word."%') or
												 (user_name like '".$search_word."%' or user_name like '% ".$search_word."%')
											   )
										   and User.status= 1
										   and User.role_id = 2 
										   
										order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
												 
											LIMIT $start , $end	 
												 ");
		
	}
	 
	if(isset($_REQUEST['action_type']) &&  $_REQUEST['action_type']=='user_type'){
		if(isset($_REQUEST['action_type'])){
			$user_type = $_REQUEST['action_type_value']; 
		}

		$search_result = $this->User->query("
									SELECT 
											User.id, 
											User.name, 
											User.sex,
											User.email,
											User.image, 
											User.user_name,
											User.details,
											 User.location ,
											 User.site ,
											 User.user_type,
											 (select count(*) from follows where from_user_id=".$user_id." and to_user_id=User.id )as 'count',
											 Industry.title_".$this->Session->read('Config.language')." as title
											 
									 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
									where User.user_type = ".$user_type."
									   and User.status= 1
									   and User.role_id = 2
									   
									order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
											 
										LIMIT $start , $end	 	 
											 ");
	}
	
	
	if(isset($_REQUEST['action_type']) &&  $_REQUEST['action_type']=='industry'){
		if(isset($_REQUEST['action_type'])){
			$industry = $_REQUEST['action_type_value']; 
		}

		if($industry==0){
			$industry_sql='';
		}else
		$industry_sql= "User.industry_id = ".$industry." and ";

		$search_result = $this->User->query("
									SELECT 
											User.id, 
											User.name, 
											User.sex,
											User.email,
											User.image, 
											User.user_name,
											User.details,
											 User.location ,
											 User.site ,
											 User.user_type,
											 (select count(*) from follows where from_user_id=".$user_id." and to_user_id=User.id )as 'count',
											 Industry.title_".$this->Session->read('Config.language')." as title
											 
									 FROM users AS User
												left join industries Industry
											          on  User.industry_id = Industry.id
									where   ".$industry_sql."
									       User.status= 1
									   and User.role_id = 2
									   
									order by (
										    SELECT count(*)
										    FROM posts as Post
										    where Post.user_id = User.id
										 ) desc
											 
										LIMIT $start , $end	 	 
											 ");
	}
		
		
		 
		 
    $this->set(array(
		'search_result' => $search_result,
		'_serialize' => array('search_result')
		));
	
}
		
/**
* 
* @param undefined $id
* 
*/
	function edit($id = null)
	{
		$this->User->id = $id;
		if(!$this->User->exists())
		{
			throw new NotFoundException(__('invalid id for user'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			if($this->User->save($this->request->data))
			{
				$this->Session->setFlash(__('the user has been saved'), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the user could not be saved. Please, try again.'), 'flash_error', array('plugin' => 'alaxos'));
			}
		}
		
		$this->_set_user($id);
		
		$roles = $this->User->Role->find('list');
		$this->set(compact('roles'));
	}

	
/**
* ajax  delete
* @param undefined $id
* 
*/
/*public function delete($id = null) {
	
       $response['success'] = false;
       $response['message'] = null;

       $this->User->id = $id;
       if (!$this->User->exists())
       {
           throw new NotFoundException(__('Invalid user'));
       }

       $response['success'] = $this->User->delete($id);
       $response['message'] = $response['success'] ? __('user was deleted successfully') :
           __('user was not deleted');

       $this->set('ajaxData', json_encode($response));
       $this->render('ajax_delete', 'ajax');
	  
	}
*/	

	/**
	* 
	* 
*/
	function app_login()
	{
		$this->User->recursive = -1;
		$response['success'] = false;
		$response['message'] = null;
		$response['url'] = null;
		
		$this->request->data['User']['email']=$_POST['email'];			
		$this->request->data['User']['password']=$_POST['password'];			
		$this->request->data=Sanitize::clean($this->request->data);
		
		$find_by_username = $this->User->find('first', array(
	        'conditions' => array('user_name' => $this->request->data['User']['email']),
	        'fields' => 'email'
	      )
	    );

			
		if(empty($find_by_username)){
			$email = $this->request->data['User']['email'];
		}else {
			$email = $find_by_username['User']['email'];
			$this->request->data['User']['email']= $email;
		 }
        
		
		$ret = $this->Auth->login();
		 if($ret)
		{
		 
		$options['fields'] = array(
			'User.id',
			'User.name',
			'User.sex',
			'User.age',
			'User.email',
			'User.image',
			'User.industry_id',
			'User.user_type',
			'User.user_name'
		   );
		   
		$options['conditions'] = array(
			'User.email' => $email
		);
		$user = $this->User->find('first',$options); 

			 
			$user_info = array(
    			'id' => $user['User']['id'] ,
    			'name' => $user['User']['name'] ,
    			'sex' => $user['User']['sex'] ,
    			'age' => $user['User']['age'],
    			'email' => $user['User']['email'],
    			'image' => $user['User']['image'],
    			'industry_id' => $user['User']['industry_id'],
    			'user_type' => $user['User']['user_type'],
    			'user_name' => $user['User']['user_name']
    	    );
		} 
        else
         $ret = FALSE;
         
		$message = $ret ? __('login_sucsess') : __('not_valid_login');

        $this->set(array(
		'message' => $message,'result' => $ret ,'user_info' => $user_info,
		'_serialize' => array('message','result','user_info')
		));
	}
	
	/**
	* 
	* @param undefined $user_id
	* @param undefined $user_created
	* 
*/ 
	 
	function _check_show_learn($user_id,$user_created)
	{
		$this->loadModel('Learnobjectuser');
        $this->loadModel('Learnobject');
        $this->User->Learnobjectuser->recursive = -1;
		$this->User->Learnobjectuser->Learnobject->recursive = -1;
		
		$learn_objects = $this->User->Learnobjectuser->Learnobject->find('all');
		$ObjectInfo = array();
		if(!empty($learn_objects)){
			foreach($learn_objects as $learn_object){
				$options['conditions'] = array(
					'Learnobjectuser.user_id ' => $user_id ,
					'Learnobjectuser.learn_object_id ' => $learn_object['Learnobject']['id']		
					);
				$count = $this->User->Learnobjectuser->find('count',$options);
				$ObjectInfo[]=array(
									'learn_object_id' => $learn_object['Learnobject']['id'] ,
									'count' => $count ,
									'parent_id' => $learn_object['Learnobject']['parent_id'] ,
									'object_created' => $learn_object['Learnobject']['created'], 
									'user_created' => $user_created
								);	
			}
			if(!empty($ObjectInfo)){
				$this->Session->write('LearnObjectInfo',$ObjectInfo);
			}
			
		}
		
	}
    
 function _get_login_log($id){
    $this->User->Userloglogin->recursive = -1;
    $option['conditions'] = array(
				'Userloglogin.user_id' => $id ,
                'date(Userloglogin.created)' => date('Y-m-d')
			);
			
	$count = $this->User->Userloglogin->find('count',$option);
    
    if($count==0){
         $this->request->data['Userloglogin']['user_id']= $id;			
		 if(!$this->User->Userloglogin->save($this->request->data)){
            /* set log */
				Controller::loadModel('Errorlog');
				$this->Errorlog->get_log('UserlogloginsController','Can not insert in Userloglogin , user_id='.$id);
			/* set log */
         }
    }else
    {
        
         /*$ret= $this->User->Userloglogin->updateAll(
				    array( 'Userloglogin.modified' =>'"'.date('Y-m-d H:i:s').'"' ,'Userloglogin.count_login' =>'Userloglogin.count_login + 1'),   //fields to update
				    array( 'Userloglogin.user_id' => $id , 'date(Userloglogin.created)' => '"'.date('Y-m-d').'"')  //condition
				);*/
        
      
        $ret= $this->User->Userloglogin->query("
            UPDATE user_log_logins as Userloglogin set 
                Userloglogin.modified ='".date('Y-m-d H:i:s')."' ,
                Userloglogin.count_login = Userloglogin.count_login +1
            where  Userloglogin.user_id  = ".$id." 
              and  date(Userloglogin.created) =  '".date('Y-m-d')."'
        "); 
         
        /*if(!$ret){
             
				Controller::loadModel('Errorlog');
				$this->Errorlog->get_log('UserlogloginsController','Can not update in Userloglogin , user_id='.$id);
			 
        } */       
    }      
 }   
    
/**
* 
* 
*/	
	function login()
	{
		$this->render('/Elements/Users/register_login');
	     
	   if ($this->request->is('ajax'))
       {
	   		$response['visible_captcha']=FALSE;
			if($this->Session->read('Login_Attempt'))
			{
				$this->Session->write('Login_Attempt',$this->Session->read('Login_Attempt') + 1);
				if($this->Session->read('Login_Attempt')>=3)
				{
					$response['visible_captcha']=true;
				}
				if($this->Session->read('Login_Attempt')>=4)
				{
					if($_POST['captcha']!=$this->Session->read('captcha'))
					{
						$response['success'] = FALSE;
						$response['message'] = __('incorrect_captcha');
						$response['visible_captcha']=TRUE;
						$this->set('ajaxData', json_encode($response));
						$this->render('/Elements/Users/Ajax/ajax_login', 'ajax');	
						return FALSE;
					}
				}
				
			}
			else $this->Session->write('Login_Attempt',1);
			
			$this->User->recursive = -1;
			$response['success'] = false;
			$response['message'] = null;
			$response['url'] = null;
			
			$this->request->data['User']['email']=$_POST['email'];			
			$this->request->data['User']['password']=$_POST['password'];			
			$this->request->data=Sanitize::clean($this->request->data);
			
			$find_by_username = $this->User->find('first', array(
		        'conditions' => array('user_name' => $this->request->data['User']['email']),
		        'fields' => 'email'
		      )
		    );

			
			if(empty($find_by_username)){
				$email = $this->request->data['User']['email'];
			}else {
				$email = $find_by_username['User']['email'];
				$this->request->data['User']['email']= $email;
			 }
			
			//print_r($email);exit();
			
			$remember_me=$_POST['remember_me'];
			
			$options1['conditions'] = array(
				'User.password' => AuthComponent::password($this->request->data['User']['password']) ,
                'User.email' => $email ,             
				'User.status' => 0
			);
			
			$user_count = $this->User->find('count',$options1); 
			 
			if($user_count>0){
				$response['success'] = FALSE;
				$response['message'] = __('account_not_active_goto_email');
				$this->set('ajaxData', json_encode($response));
				$this->render('/Elements/Users/Ajax/ajax_login', 'ajax');	
				return FALSE;
			}
			
			$response['success'] = $this->Auth->login();
			
			if($response['success'])
			{
				//$this->redirect($this->Auth->redirect());
				$ret= $this->User->updateAll(
				    array( 'User.last_login' =>'"'.date('Y-m-d H:i:s').'"'),   //fields to update
				    array( 'User.email' => $email)  //condition
				);
				
				$response['url']=__SITE_URL;
				if($remember_me==1){
					$this->Cookie->write('User',array('Email' => $email, 'Pass' => AuthComponent::password($this->request->data['User']['password'])) , false, '20 days');
					
				}
			 
			$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.age',
				'User.email',
				'User.image',
				'User.industry_id',
				'User.user_type',
				'User.user_name',
				'User.location',
				'User.details',
				'User.created',	
				'Industry.id',
				'Industry.title_'.$this->Session->read('Config.language').' as title' ,
                '(select count(user_id) from userrelatetags as Userrelatetag  where Userrelatetag.user_id = User.id) as tag_count'
			   );
		   $options['joins'] = array(
			array('table' => 'industries',
				'alias' => 'Industry',
				'type' => 'LEFT',
				'conditions' => array(
				'Industry.id = User.industry_id ',
				)
			) 
			
		   );
			   
			$options['conditions'] = array(
				'User.email' => $email
			);
			$user = $this->User->find('first',$options);
            
            $this->_get_login_log($user['User']['id']); 

			$this->_check_show_learn($user['User']['id'],$user['User']['created']);
            $follow_count = $this->User->Follow->find('count', array('conditions' => array('Follow.from_user_id'=>$user['User']['id'])));
				 
				$this->Session->write('User_Info',array(
				'id' => $user['User']['id'] ,
				'name' => $user['User']['name'] ,
				'sex' => $user['User']['sex'] ,
				'age' => $user['User']['age'],
				'email' => $user['User']['email'],
				'image' => $user['User']['image'],
				'industry_id' => $user['User']['industry_id'],
				'user_type' => $user['User']['user_type'],
				'user_name' => $user['User']['user_name'],
				'location' => $user['User']['location'],
				'industry_name'=>$user['Industry']['title'],
				'details' => $user['User']['details'],
                'follow_count' => $follow_count /*,
				'job_status' => $user['User']['job_status'],
				'degree' => $user['User']['degree'],
				'university_name' => $user['User']['university_name'],
				'job_title' => $user['User']['job_title'],
				'company_name' => $user['User']['company_name']*/
				));
                $this->Session->write('tag_count',$user['0']['tag_count']);
			  
			}
			$response['message'] = $response['success'] ? __('login_sucsess') : __('not_valid_login');

			$this->set('ajaxData', json_encode($response));
			$this->render('/Elements/Users/Ajax/ajax_login', 'ajax');
			 
	   }
	   else
	  { 
	   
	  	if($this->request->is('post'))
	    {

			
			$remember_me=$this->request->data['User']['remember_me'];
			/*$this->request->data['User']['email'] = $this->request->data['User']['login_email'];
			$this->request->data['User']['password'] = $this->request->data['User']['login_password']; */
			 
		   /*if($this->data['User']['captcha']!=$this->Session->read('captcha'))
			{
				$this->Session->setFlash(__('incorrect_captcha', true));
				return FALSE;
			} */
			
			if($this->Auth->login())
    	    {
    	        $this->Cookie->delete('User');
				if($remember_me==1){
					$this->Cookie->write('User',array('Email' => $this->request->data['User']['email'], 'Pass' => AuthComponent::password($this->request->data['User']['password'])) , false, '20 days');
					
				}
				
				$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.age',
				'User.email',
				'User.image',
				'User.user_name',
				'User.location',
				'Industry.id',
				'Industry.title_'.$this->Session->read('Config.language').' as title'
			   );
			   $options['joins'] = array(
				array('table' => 'industries',
					'alias' => 'Industry',
					'type' => 'LEFT',
					'conditions' => array(
					'Industry.id = User.industry_id ',
					)
				) 
			
		   );
			$options['conditions'] = array(
				'User.email' => $this->request->data['User']['email']
			);
			$user = $this->User->find('first',$options);
			 	

				$this->Session->write('User_Info',array(
				'id' => $user['User']['id'] ,
				'name' => $user['User']['name'] ,
				'sex' => $user['User']['sex'] ,
				'age' => $user['User']['age'],
				'email' => $user['User']['email'],
				'image' => $user['User']['image'] ,
				'user_name' => $user['User']['user_name'],
				'location' => $user['User']['location'],
				'industry_name'=>$user['Industry']['title']
				));
				$this->redirect($this->Auth->redirect());
    	    }
    	    else
    	    {
    	        $this->Session->setFlash(__('not_valid_login'));
    	    }
	    }
	   }
	  
	}
	
	function logout()
	{
	    $this->Cookie->delete('User');
		$this->Session->delete('User_Info');
		$this->Session->delete('Login_Attempt');
		$this->redirect($this->Auth->logout());
	}
	
	
	
	function forget_pass()
	{
		$action=$_REQUEST['action'];
		$response['success'] = false;
		$response['message'] = null;
		
		switch($action){
			case 'load_page':
				$this->render('/Elements/Users/Ajax/forget_pass','ajax');
				break;
			case 'send':
			    $fgemail=$_REQUEST['fgemail'];   
			   $result = $this->User->find('count', array('conditions' => array('User.email' => $fgemail)));
			   if($result>=1)
			   {
			   		$pass = $this->Gilace->random_char();
					$savepass=AuthComponent::password($pass);
					
	            	$Email = new CakeEmail();
	
                	$Email->template('forgetpass_sendemail', 'sendemail_layout');
                	$Email->subject(__('send_forget_pass'));
                    try{
            			$Email->emailFormat('html');
            			$Email->to($fgemail);
            			$Email->from(array(__Madaner_Email => __Email_Name));
            			$Email->viewVars(array('pass'=>$pass,'email'=>$fgemail,'name'=>''));
            			$ret=$Email->send();
                        /*$response['success'] = TRUE;
            		    $response['message']=__('send_pass_success');*/
            		} catch (Exception $e) {
            			/*$response['success'] = false;
            			$response['message']=__('cant_send_pass');*/
            		}
	                if($ret)
					 {					 	
						$ret= $this->User->updateAll(
					    array( 'User.password' =>'"'.$savepass.'"'),   //fields to update
					    array( 'User.email' => $fgemail )  //condition
					  );
						
						//$this->request->data['User']['password']=$savepass;
						if($ret)
						{
							$response['success'] =true;
						}
						else  $response['success'] =false;
					 }
					 else  $response['success'] =false;
			   }
			   
		       $response['message'] = $response['success'] ? __('send_pass_successfully') : __('send_pass_not_successfully'); 
		       $this->set('ajaxData', json_encode($response));
			   $this->render('/Elements/Users/Ajax/ajax_result','ajax');
			  break;	
		}
		
	}
	
	
	function _set_user($id)
	{
		$this->User->recursive = -1;
		$this->User->id = $id;
		if(!$this->User->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_user'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->User->findById($id);
	    }
	    
	    $this->set('user', $this->request->data);
	    
	    return $this->request->data;
	}
	
	
	function admin_index()
	{
		$this->User->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['User']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
				'fields'=>array(
					'User.id',
					'User.name',
					'User.user_name',
					'User.email',
					'User.status',
					'User.created',
					'Role.name'
				),
				'joins'=>array(array('table' => 'roles',
					'alias' => 'Role',
					'type' => 'LEFT',
					'conditions' => array(
					'Role.id = User.role_id ',
					)
				 )),
'conditions' => array('User.name LIKE' => ''.$this->request->data['User']['search'].'%' ,'User.role_id <>'=>1),
				'limit' => $limit,
				'order' => array(
					'User.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
			    'fields'=>array(
					'User.id',
					'User.name',
					'User.user_name',
					'User.email',
					'User.status',
					'User.created',
					'Role.name'
				),
				'joins'=>array(array('table' => 'roles',
					'alias' => 'Role',
					'type' => 'LEFT',
					'conditions' => array(
					'Role.id = User.role_id ',
					)
				 )),
				'conditions' => array('User.role_id <> 1 '),
				'limit' => $limit,
				'order' => array(
					'User.id' => 'desc'
				)
			);
		}		
		$users = $this->paginate('User');
		$this->set(compact('users'));
	}
/**
* admin login
* 
*/	
public function admin_login() {
	    
	     $this->set('title_for_layout','پنل مدیریت سایت معدنر');
		if($this->request->is('post'))
		{
			if($this->request->data['User']['login_email']=='' || $this->request->data['User']['login_password']==''){
				$this->Session->setFlash(__('insert_information'),'default', array('class' => 'alert alert-error')); return;
			} 
			
			if($this->request->data['User']['captcha']!=$this->Session->read('captcha'))
			{
				$this->Session->setFlash(__('insert_captcha'),'default', array('class' => 'alert alert-error')); return;
			}
			
			//pr($this->request->data);return;
			
			$email=$this->request->data['User']['login_email'];
			$conditions = array('User.email' => $email);
			$ret=$this->User->find('first',array('fields'=>array('role_id'),'conditions' => $conditions));
			
			if(empty($ret))	{
				$this->Session->setFlash(__('please_enter_valid_username_and_password'),'default', array('class' => 'alert alert-error'));return;
			}
			 
			if($ret['User']['role_id']==2)  
			  {$this->Session->setFlash(__('can_not_login_in_admin'),'default', array('class' => 'alert alert-error')); return;}
	        $this->request->data['User']['email'] = $this->request->data['User']['login_email'];
		    $this->request->data['User']['password'] = $this->request->data['User']['login_password'];
			$this->request->data=Sanitize::clean($this->request->data);
			 
			if ($this->Auth->login()) {
			
				 
				$options['fields'] = array(
					'User.id',
					'User.name',
					'User.email',
					'User.image',
					'User.role_id',
					'User.user_name'
				   );
				   
				$options['conditions'] = array(
					'User.email' => $this->request->data['User']['email']
				);
				$user = $this->User->find('first',$options); 

					 
					$this->Session->write('AdminUser_Info',array(
					'id' => $user['User']['id'] ,
					'name' => $user['User']['name'] ,
					'email' => $user['User']['email'],
					'image' => $user['User']['image'],
					'role_id' => $user['User']['role_id']
					));
				
				
				$this->redirect($this->Auth->redirect());			
		    } else {
		        $this->Session->setFlash(__('please_enter_valid_username_and_password'),'default', array('class' => 'alert alert-error'));
		    }
		
		
		}
		else
			$this->Session->setFlash(__('please_login_with_your_email_and_password'),'default', array('class' => 'alert alert-info'));
		    
		
		
	}		
	
	function admin_logout()
	{
		$this->Session->delete('AdminUser_Info');
		//$this->redirect(_SITE_URL.'admin/users/login');
		$this->redirect($this->Auth->logout());
	}
	
	
/**
* admin_dashboard
* 
*/
	public function admin_dashboard() {
		$this->set('title_for_layout','پنل مدیریت سایت معدنر');
		//$this->set('description_for_layout',$user['User']['details']);
		//$this->set('keywords_for_layout',$user['User']['name']);
	}
	
	



	function admin_add()
	{
		/*
		if($this->request->is('post'))
		{
			$output=$this->_picture();
			if(!$output['error']) $this->request->data['User']['image']=$output['filename'];
			else $this->request->data['User']['image']='';
			 
			$this->User->create();
			if($this->User->save($this->request->data))
			{
				
				$this->Session->setFlash(__('the user has been saved'), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the user could not be saved. Please, try again.'), 'flash_error', array('plugin' => 'alaxos'));
				@unlink(__USER_IMAGE_PATH."/".$output['filename']);
				@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
			}
		}
		
		if($this->Session->read('User.role_id')==1)
		 {
		 	$roles = $this->User->Role->find('list');
		    $this->set(compact('roles'));
		 }
		 else
		 {
		 	$conditions = array('Role.id <>' => 1);
			$roles=$this->User->Role->find('list',array('conditions' => $conditions)); 
			$this->set(compact('roles'));
		 }
		    
		*/
	}

	function admin_edit($id = null)
	{
		$this->User->id = $id;
		$user_id = $id;
		if(!$this->User->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_user'));
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{ 
  			$error=FALSE;
			$result= $this->User->findById($id);
			
			if(trim($_POST['password'])!=trim($_POST['repassword'])){
				$this->Session->setFlash(__('invalid_2_password'));
				$error = TRUE;
			}
		   
		   if(trim($_POST['password'])!=''){
		   	 $this->request->data['User']['password']= $_POST['password'];
		   }
			
		   $data=Sanitize::clean($this->request->data);
		   
		   $options['conditions'] = array(
			'User.user_name' => trim($data['User']['user_name']),
			'User.user_name <> ' =>$result['User']['user_name']
		   );
		   $user_name_count = $this->User->find('count',$options); 
		   if($user_name_count>0){
		   		$this->Session->setFlash(__('repeated_user_name'));
				$error = TRUE;
		   }
		    
		   if($error==FALSE){

			   $file = $data['User']['image'];
			 	  
			   if($file['size']>0)
				 {
					
					$filename=$result['User']['image'];
					@unlink(__USER_IMAGE_PATH."/".$filename);
					@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename); 
						
					$output=$this->_image_picture();
					if(!$output['error']) $this->request->data['User']['image']=$output['filename'];
					else $this->request->data['User']['image']='';
				 }
				 else $this->request->data['User']['image']=$this->request->data['User']['old_image'];
				 
				 $big_file = $data['User']['cover_image'];
				 if($big_file['size']>0)
				 {
					$filename=$result['User']['cover_image'];
					@unlink(__USER_IMAGE_PATH."/".$filename);
					@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename); 
						
					$big_output=$this->_cover_picture();
					if(!$big_output['error']) $this->request->data['User']['cover_image']=$big_output['filename'];
					else $this->request->data['User']['cover_image']='';
				 }
				 else $this->request->data['User']['cover_image']=$this->request->data['User']['old_cover_image'];
				 
				if($this->User->save($this->request->data))
				{
					$datasource = $this->User->Userrelatetag->getDataSource();
					try{
					    $datasource->begin();
							if(!$this->User->Userrelatetag->deleteAll(array('Userrelatetag.user_id'=>$user_id),FALSE))
										throw new Exception(__('the_tag_not_saved'),7);
				                
				                if(isset($_POST['new_tags'])&& !empty($_POST['new_tags'])){
									$tags=$_POST['new_tags'];//explode('#',$this->request->data['Userrelatetag']['tag']);
									$tags=array_filter($tags,'strlen');
									$this->loadModel('Usertag');
									if(!empty($tags)){
										foreach($tags as $tag)
										{										
		                                    $count = $this->Usertag->find('count', array('conditions' => array('Usertag.title' => $tag)));
		                                    if($count>0){
		                                        continue;
		                                    }
		                                    
		                                    $this->request->data['Usertag']['title']= $tag;
											$this->Usertag->create();
											
											if($this->Usertag->save($this->request->data))
											{
												$tag_id[]=$this->Usertag->getLastInsertID();					
											}
				                            else throw new Exception(__('the_tag_not_saved'),5);
										}
									}
									
								}
								$data = array();
								if(isset($this->request->data['Userrelatetag']['usertag_id'])){
									foreach($this->request->data['Userrelatetag']['usertag_id'] as $id)
									{
										$dt=array('Userrelatetag' => array('user_id' => $user_id,'usertag_id'=>$id));
										array_push($data,$dt);
									}
								}
								//pr($tag_id); 
								if(!empty($tag_id))
									{
										foreach($tag_id as $tid)
										{
											$dt=array('Userrelatetag' => array('user_id' => $user_id,'usertag_id'=>$tid));
											array_push($data,$dt);
										}
										
									}
									
								//pr($data);throw new Exception(); 
								
								if(!empty($this->request->data['Userrelatetag']['usertag_id']) || !empty($tag_id))
								{
									$this->User->Userrelatetag->create();
									if(!$this->User->Userrelatetag->saveMany($data))
				                            throw new Exception(__('the_user_tag_not_saved'),6);
								}
						    $datasource->commit();
							$this->Session->setFlash(__('the_user_has_been_saved'), 'admin_success');
							$this->redirect(array('action' => 'index'));
							
						} catch(Exception $e) {
						    $datasource->rollback();
							$this->Session->setFlash($e->getMessage(),'admin_error');
						}								
					
				}
				else
				{
					if($file['size']>0)
				    {
						@unlink(__USER_IMAGE_PATH."/".$output['filename']);
						@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
				 	}
					if($big_file['size']>0)
				    {
						@unlink(__USER_IMAGE_PATH."/".$big_output['filename']);
						@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$big_output['filename']);
				 	}
					$this->Session->setFlash(__('the_user_not_saved'));
				}
			}	
		}
		
		$this->_set_user($id);
		
		$industries = $this->User->Industry->query("
									SELECT 
											Industry.id, 
											Industry.title_".$this->Session->read('Config.language')." as title
									FROM industries AS Industry      
									ORDER BY Industry.title_".$this->Session->read('Config.language')." asc");
		$this->set(compact('industries'));	
		
		$this->User->Role->recursive = -1;
		$options['fields'] = array(
			'Role.id',
			'Role.name'
		);
		$options['conditions'] = array(
			'Role.id <>' => 1		
			);
		$roles = $this->User->Role->find('all',$options);
		$this->set(compact('roles'));
		
		$this->User->Userrelatetag->recursive = -1;
		$options=array();
		$options['fields'] = array(
				'Usertag.title',
				'Usertag.id' 
			   );
	     $options['joins'] = array(
	    		array('table' => 'usertags',
	        		'alias' => 'Usertag',
	        		'type' => 'INNER',
	        		'conditions' => array(
	        		'Usertag.id = Userrelatetag.usertag_id'
	    		)
			)
	    );	      
	    $options['conditions'] = array(
			'Userrelatetag.user_id' => $id		
			);
		$userrelatetags = $this->User->Userrelatetag->find('all',$options);
	    $this->set('userrelatetags', $userrelatetags);
		
		
	}

	
	function admin_delete($id = null)
	{
		$this->User->id = $id;
		if(!$this->User->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_user'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		
		$this->User->Post->recursive = -1;
		$options['conditions'] = array(
			'Post.user_id' => $id
		);
		$post_count = $this->User->Post->find('count',$options); 
	
		if($post_count>0)
		{
			$this->Session->setFlash(__('cant_delete_first_delete_post'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
		$this->User->Blog->recursive = -1;
		$options1['conditions'] = array(
			'Blog.user_id' => $id 
		);
		
		$blog_count = $this->User->Blog->find('count',$options1); 
		
		if($blog_count>0)
		{
			$this->Session->setFlash(__('cant_delete_first_delete_blog'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		} 
		
		$result= $this->User->findById($id);
		if($this->User->delete($id))
		{
			$this->Session->setFlash(__('delete_user_success'), 'admin_success');
			$filename=$result['User']['image'];
			@unlink(__USER_IMAGE_PATH."/".$filename);
			@unlink(__USER_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename);
			$this->redirect(array('action'=>'index'));
		}else
		{
			$this->Session->setFlash(__('delete_user_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
	}
	
	
 function _picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['User']['image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('User');
			$this->Httpupload->setuploaddir(__USER_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=100;
			$this->Httpupload->thumb_height=100; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
}

function pdf_upload_window($pdf=''){
	$this->set('pdf',$pdf);
	$this->render('/Elements/Users/Ajax/pdf_upload_window','ajax');
}

function edit_pdf(){

	$this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	 
	$options['fields'] = array(
		'User.pdf'
    );
	$options['conditions'] = array(
		'User.id '=> $User_Info['id']
	);	   
	$user= $this->User->find('first',$options); 
	 
	$old_pdf=$user['User']['pdf'];
	 
	$output=$this->_upload_pdf();
	if(!$output['error']) $pdf=$output['filename'];
    else 
        {
            $pdf='';
            echo"<script>show_warning_msg('".$output['message']."');</script>";
            return;
        }
	
	$ret= $this->User->updateAll(
	    array( 'User.pdf' => '"'.$pdf.'"' ),   //fields to update
	    array( 'User.id' => $User_Info['id'] )  //condition
	  );
	if($ret)
	{ 
		@unlink(WWW_ROOT."/".__USER_FILE_PATH.$old_pdf);
		
		$options= array();
		$options['fields'] = array(
			'User.pdf'
	    );
		$options['conditions'] = array(
			'User.id '=> $User_Info['id']
		);	   
		$user= $this->User->find('first',$options); 
        echo"<script>show_success_msg('".__('save_pdf_success')."');</script>";
		echo "<script> remove_modal();
			$('#resume_upload_btn .text').text('".__('edit_and_delete_resume')."');
			$('#resume_upload_btn').attr('onclick','popUp(\"".__SITE_URL."users/pdf_upload_window/".$user['User']['pdf']."\")');
			</script>";
	}  
	else echo"<script>show_error_msg('".__('save_pdf_notsuccess')."');</script>";
	 
 }

function delete_pdf(){
    $this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');	 
	$pdf='';
	$ret= $this->User->updateAll(
	    array( 'User.pdf' => '"'.$pdf.'"' ),   //fields to update
	    array( 'User.id' => $User_Info['id'] )  //condition
	  );
	if($ret)
	{
		@unlink(WWW_ROOT."/".__USER_FILE_PATH.$_REQUEST['old_pdf']);
        echo"<script>show_success_msg('".__('delete_pdf_success')."');</script>";
		echo"<script>
					$('#resume_upload_btn .text').text('".__('upload_resume')."');
					$('#resume_upload_btn').attr('onclick','popUp(\"".__SITE_URL."users/pdf_upload_window\")');
		</script>";
        echo"<script> remove_modal();</script>";
	} 
    else   
	 {
	 	echo"<script>show_error_msg('".__('delete_pdf_notsuccess')."');</script>";
	 } 
 }

function _upload_pdf(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['User']['pdf'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_FILE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
            
            $this->Httpupload->setmodel('User');
			$this->Httpupload->setuploaddir(__USER_FILE_PATH);
			$this->Httpupload->setuploadname('pdf');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(2097152);
			$this->Httpupload->allowExt= __UPLOAD_PDF_EXTENSION; 
			$this->Httpupload->create_thumb=false;
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
}

/**
* load and send message in friends
*/

function cover_upload_window(){
	$this->render('/Elements/Users/Ajax/cover_upload_window','ajax');
}

function edit_cover_image(){

	$this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	 
	$options['fields'] = array(
			'User.cover_image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
	$user= $this->User->find('first',$options); 
	 
	$image=$user['User']['cover_image'];
	 
	$output=$this->_cover_picture();
	if(!$output['error']) $cover_image=$output['filename'];
    else 
        {
            $cover_image='';
            echo"<script>show_warning_msg('".$output['message']."');</script>";
            return;
        }
	
	$ret= $this->User->updateAll(
	    array( 'User.cover_image' => '"'.$cover_image.'"' ),   //fields to update
	    array( 'User.id' => $User_Info['id'] )  //condition
	  );
	if($ret)
	{
	 
		@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.$image);
		echo"<script>$('#add_image').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$cover_image."' );</script>";
		echo"<script>$('#cover_image_img').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$cover_image."' );</script>";
        echo"<script>show_success_msg('".__('save_cover_image_success')."');</script>";
		echo "<script> $('#new_image').val('".$cover_image."'); 
			$('#add_image').Jcrop({
		     // aspectRatio: 1,
		      onSelect: updateCoords
		    });
			$('#image_width').show();
    		$('#image_height').show();
			$('#ChangeImage').attr('action', '".__SITE_URL."/users/edit_cover_crop' );
			</script>";
	}  
	else echo"<script>show_error_msg('".__('save_cover_image_notsuccess')."');</script>";
	 
 }

function edit_cover_crop(){
	
 	$User_Info= $this->Session->read('User_Info');
	
	
	$jpeg_quality = 100;

	$src = __USER_IMAGE_PATH.$_POST['new_image'];
	
	$info = getimagesize($src);
	$height=$info[1];
  	$width = $info[0]; 
	$targ_w = $_POST['w'] ; $targ_h = $_POST['h'];
	
	if(!$info){
		echo"<script>show_error_msg('".__('The_file_type_is_not_supported')."');</script>";
        return;
	}

	// we use the the GD library to load the image, using the file extension to choose the right function
	switch($info[2]) {
		case IMAGETYPE_GIF:
			if(!$img_r = imagecreatefromgif($src)){
				echo"<script>show_error_msg('".__('Could_not_open_GIF_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_PNG:
			if(!$img_r = imagecreatefrompng($src)){
				echo"<script>show_error_msg('".__('Could_not_open_PNG_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_JPEG:
			if(!$img_r = imagecreatefromjpeg($src)){
				echo"<script>show_error_msg('".__('Could_not_open_JPG_file')."');</script>";
        		return;
			}
			break;
		default:
			echo"<script>show_error_msg('".__('The_file_type_is_not_supported')."');</script>";
        	return;
			break;
	}
	
	
	if(!$dst_r = ImageCreateTrueColor( $targ_w, $targ_h ))
	{
		echo"<script>show_error_msg('".__('Could_not_create_new_image_from_source_file')."');</script>";
        return;
	}

	if(!imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
		$targ_w,$targ_h,$_POST['w'],$_POST['h']))
	{
		echo"<script>show_error_msg('".__('Could_not_crop_the_image_with_the_provided_coordinates')."');</script>";
        return;
	}
	
	switch($info[2]) {
		case IMAGETYPE_GIF:
			if(!imagegif($dst_r, $src)){
				echo"<script>show_error_msg('".__('Could_not_save_GIF_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_PNG:
			if(!imagepng($dst_r, $src, max(9 - floor($jpeg_quality/10),0))){
				echo"<script>show_error_msg('".__('Could_not_save_PNG_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_JPEG:
			if(!imagejpeg($dst_r, $src, $jpeg_quality)){
				echo"<script>show_error_msg('".__('Could_not_save_JPG_file')."');</script>";
        		return;
			}
			break;
	}
	
	
	echo"<script>$('#cover_image_img').attr('src','' );</script>";
	echo"<script>$('#cover_image_img').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$_POST['new_image']."' );</script>";
    echo"<script>show_success_msg('".__('save_image_success')."');remove_modal();</script>";
    
}

function delete_cover_image(){
    $response['success'] = false;
	$response['message'] = null;
	
    $this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	 
	$options['fields'] = array(
			'User.cover_image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
	$user= $this->User->find('first',$options); 
	 
	$oldimage=$user['User']['cover_image'];
	$cover_image='';
	$ret= $this->User->updateAll(
	    array( 'User.cover_image' => '"'.$cover_image.'"' ),   //fields to update
	    array( 'User.id' => $User_Info['id'] )  //condition
	  );
	if($ret)
	{
		@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.$oldimage);
		
		if($User_Info['sex']==1){
			$cover_image='cover_men.jpg';
		}else $cover_image='cover_women.jpg';
		
		echo"<script>$('#cover_image_img').attr('src','".__SITE_URL.'img/'.$cover_image."' );</script>";
        echo"<script>show_success_msg('".__('delete_cover_image_success')."');</script>";
		//echo"<script>$('.delete_cover_image').remove();</script>";
	} 
    else   
	 {
	 	echo"<script>show_error_msg('".__('delete_cover_image_notsuccess')."');</script>";
		//echo"<script>$('.delete_cover_image').html('".__('delete')."');</script>";
	 } 
 }


function _cover_picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['User']['cover_image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('User');
			$this->Httpupload->setuploaddir(__USER_IMAGE_PATH);
			$this->Httpupload->setuploadname('cover_image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(4194304);
			//$this->Httpupload->setimagemaxsize(1400,400);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
} 


function _upload_user_image(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['User']['image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
            
            $this->Httpupload->setmodel('User');
			$this->Httpupload->setuploaddir(__USER_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(4194304); // 2 mb  , 2*1024*1024
			//$this->Httpupload->setimagemaxsize(350,350);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION; 
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=160;
			$this->Httpupload->thumb_height=160;
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
} 

/**
* 
* 
*/
function image_upload_window(){
	$this->render('/Elements/Users/Ajax/image_upload_window','ajax');
}

function edit_image(){
    $response['success'] = false;
	$response['message'] = null;
	
    $this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	 
	$options['fields'] = array(
			'User.image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
	$user= $this->User->find('first',$options); 
	 
	$oldimage=$user['User']['image'];
	 
	$output=$this->_upload_user_image();
    
	if(!$output['error']) $image=$output['filename'];
	else 
        {
            @unlink(WWW_ROOT."/".__USER_IMAGE_PATH.$image);
			@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$image);
			$image='';
            echo"<script>show_error_msg('".$output['message']."');</script>";
            return;
        }
	
	$ret= $this->User->updateAll(
	    array( 'User.image' => '"'.$image.'"' ),   //fields to update
	    array( 'User.id' => $User_Info['id'] )  //condition
	  );
	if($ret)
	{
	 
		$this->Session->write('User_Info',array(
						'id' => $User_Info['id'] ,
						'name' => $User_Info['name'] ,
						'sex' => $User_Info['sex'] ,
						'age' => $User_Info['age'],
						'email' => $User_Info['email'],
						'image' => $image,
						'industry_id' => $User_Info['industry_id'],
						'user_type' => $User_Info['user_type'] ,
						'user_name' => $User_Info['user_name'],
		                'location' => $User_Info['location'],
						'industry_name'=> $User_Info['industry_name'],
						'details'=>$User_Info['details'],
                        'follow_count' => $User_Info['follow_count']
						));
	
		@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.$oldimage);
		@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$oldimage);
		//echo"<script>$('#image_img').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$image."' );</script>";
        echo"<script>
		$('.profileImage .ax img').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$image."' );
		
		</script>";
        echo"<script>$('#add_image').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$image."' );</script>";
        echo"<script>show_success_msg('".__('save_image_success')."');</script>";
		echo "<script> $('#new_image').val('".$image."'); 
           // $('#remove-pane').remove();
            $('#preview-pane .preview-container').append('<img src=\"".__SITE_URL.__USER_IMAGE_PATH.$image."\" class=\"jcrop-preview\"  />');
            
            
			
            
            
			$('#image_width').show();
    		$('#image_height').show();
			$('#ChangeImage').attr('action', '".__SITE_URL."/users/edit_image_crop' );
			</script>";
	} 
    else   echo"<script>show_error_msg('".__('save_image_notsuccess')."');</script>";
	
	 
 }

function edit_image_crop(){
	
 	$User_Info= $this->Session->read('User_Info');
	
	
	$jpeg_quality = 100;

	$src = __USER_IMAGE_PATH.$_POST['new_image'];
	
	$info = getimagesize($src);
	$height=$info[1];
  	$width = $info[0]; 
	$targ_w = $_POST['w'] ; $targ_h = $_POST['h'];
	
	if(!$info){
		echo"<script>show_error_msg('".__('The_file_type_is_not_supported')."');</script>";
        return;
	}

	// we use the the GD library to load the image, using the file extension to choose the right function
	switch($info[2]) {
		case IMAGETYPE_GIF:
			if(!$img_r = imagecreatefromgif($src)){
				echo"<script>show_error_msg('".__('Could_not_open_GIF_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_PNG:
			if(!$img_r = imagecreatefrompng($src)){
				echo"<script>show_error_msg('".__('Could_not_open_PNG_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_JPEG:
			if(!$img_r = imagecreatefromjpeg($src)){
				echo"<script>show_error_msg('".__('Could_not_open_JPG_file')."');</script>";
        		return;
			}
			break;
		default:
			echo"<script>show_error_msg('".__('The_file_type_is_not_supported')."');</script>";
        	return;
			break;
	}
	
	
	if(!$dst_r = ImageCreateTrueColor( $targ_w, $targ_h ))
	{
		echo"<script>show_error_msg('".__('Could_not_create_new_image_from_source_file')."');</script>";
        return;
	}

	if(!imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
		$targ_w,$targ_h,$_POST['w'],$_POST['h']))
	{
		echo"<script>show_error_msg('".__('Could_not_crop_the_image_with_the_provided_coordinates')."');</script>";
        return;
	}
	
	switch($info[2]) {
		case IMAGETYPE_GIF:
			if(!imagegif($dst_r, $src)){
				echo"<script>show_error_msg('".__('Could_not_save_GIF_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_PNG:
			if(!imagepng($dst_r, $src, max(9 - floor($jpeg_quality/10),0))){
				echo"<script>show_error_msg('".__('Could_not_save_PNG_file')."');</script>";
        		return;
			}
			break;
		case IMAGETYPE_JPEG:
			if(!imagejpeg($dst_r, $src, $jpeg_quality)){
				echo"<script>show_error_msg('".__('Could_not_save_JPG_file')."');</script>";
        		return;
			}
			break;
	}
	
	$r=255; $g=255; $b=255;
	
	if($width > 160){
		try{
			$this->Httpupload->resize($_POST['new_image'],__USER_IMAGE_PATH.$_POST['new_image'], 160,160,__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$_POST['new_image'], $r, $g, $b);
		} catch (Exception $e) {
			echo"<script>show_error_msg('".$e->getMessage()."');</script>";
        	return;
		}
		
	}

	echo"<script>$('.profileImage .ax img').attr('src','' );</script>";
	echo"<script>$('.profileImage .ax img').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$_POST['new_image']."' );</script>";
    echo"<script>show_success_msg('".__('save_image_success')."');remove_modal();</script>";
    
}	
 
 /**
* 
* 
*/
function delete_image(){
    $response['success'] = false;
	$response['message'] = null;
	
    $this->User->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	 
	$options['fields'] = array(
			'User.image'
		   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
	$user= $this->User->find('first',$options); 
	 
	$oldimage=$user['User']['image'];
	$image='';
	$ret= $this->User->updateAll(
	    array( 'User.image' => '"'.$image.'"' ),   //fields to update
	    array( 'User.id' => $User_Info['id'] )  //condition
	  );
	if($ret)
	{
	 
		$this->Session->write('User_Info',array(
						'id' => $User_Info['id'] ,
						'name' => $User_Info['name'] ,
						'sex' => $User_Info['sex'] ,
						'age' => $User_Info['age'],
						'email' => $User_Info['email'],
						'image' => '',
						'industry_id' => $User_Info['industry_id'],
						'user_type' => $User_Info['user_type'] ,
						'user_name' => $User_Info['user_name'],
		                'location' => $User_Info['location'],
						'industry_name'=> $User_Info['industry_name'],
						'details'=>$User_Info['details'],
                        'follow_count' => $User_Info['follow_count']
						));
	
		@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.$oldimage);
		@unlink(WWW_ROOT."/".__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$oldimage);
		
		if($User_Info['sex']==1){
			$image='profile_men.png';
		}else $image='profile_women.png';
		
		//echo"<script>$('#image_img').attr('src','".__SITE_URL.'img/'.$image."' );</script>";
        echo"<script>$('.profileImage .ax img').attr('src','".__SITE_URL.'img/'.$image."' );</script>";
        echo"<script>show_success_msg('".__('delete_image_success')."');</script>";
		//echo"<script>$('.delete_image').remove();</script>";
	} 
    else   
	 {
	 	echo"<script>show_error_msg('".__('delete_image_notsuccess')."');</script>";
		echo"<script>$('.delete_image').html('".__('delete')."');</script>";
	 } 
 }
 
/**
* 
* 
*/
function _image_picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['User']['image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
            
            $this->Httpupload->setmodel('User');
			$this->Httpupload->setuploaddir(__USER_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION; 
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=120;
			$this->Httpupload->thumb_height=120;
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
} 
/**
* 
* 
*/
function follow_box(){
	$User_Info= $this->Session->read('User_Info');	
	$search_result = $this->User->query("
	SELECT 
			User.id, 
			User.name, 
			User.sex,
			User.email,
			User.image, 
			User.user_name,
			User.details,
			 User.location ,
			 User.site ,
			 User.user_type,
             (select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=User.id )as 'count',
			 Industry.title_".$this->Session->read('Config.language')." as title
			 
	 FROM users  as User
       inner join industries as Industry
               on  Industry.id = User.industry_id
       and User.status= 1
	   and User.role_id = 2 
       and User.id <> ".$User_Info['id']."
        order BY (select count(id) from posts as Post where Post.user_id=User.id) desc
        limit 0,20
	   
	");
	$this->set('search_result', $search_result);
	$this->render('/Elements/Users/Ajax/follow_box', 'ajax');											 
}

/**
* /
* 
*/
function industry_box()
	{
		$action=$_POST['action'];
		$response['success'] = false;
		$response['message'] = null;
		$User_Info= $this->Session->read('User_Info');
		switch($action){
			case 'load_page':

                $this->User->Userrelatetag->recursive = -1;
		        $options = array();
        		$options['fields'] = array(
        				'Usertag.id',
        				'Usertag.title'
        			   );
        		   $options['joins'] = array(
        			array('table' => 'usertags',
        				'alias' => 'Usertag',
        				'type' => 'LEFT',
        				'conditions' => array(
        				'Usertag.id = Userrelatetag.usertag_id ',
        				)
        			) 
        			
        		   );
        			   
        		$options['conditions'] = array(
        			'Userrelatetag.user_id' => $User_Info['id']
        		);
        		$options['order'] = array(
        		'Usertag.id'=>'asc'
        		);
        		$tags = $this->User->Userrelatetag->find('all',$options);
        		$this->set('tags',$tags);
                
                
                $industries = $this->User->Industry->query("
									SELECT 
											Industry.id, 
											Industry.title_".$this->Session->read('Config.language')." as title
									FROM industries AS Industry      
									ORDER BY Industry.title_".$this->Session->read('Config.language')." asc");
				$this->set(compact('industries'));
				$this->render('/Elements/Users/Ajax/industry_box','ajax');
				break;
			
			case 'save':
			   
			   
			   $this->User->id = $User_Info['id'];
			   $this->request->data['User']['sex']= $_POST['sex'];
			   $this->request->data['User']['user_type']= 0; //$_POST['user_type'];
			   $this->request->data['User']['industry_id']= $_POST['industry_id'];
			   $this->request->data['User']['details']= $_POST['details'];
			   $this->request->data['User']['location']= $_POST['location'];
			  /* $this->request->data['User']['job_status']=$_POST['job_status'];
			   $this->request->data['User']['degree']=$_POST['degree'];
			   $this->request->data['User']['university_name']=$_POST['university_name'];
			   $this->request->data['User']['job_title']=$_POST['job_title'];
			   $this->request->data['User']['company_name']=$_POST['company_name'];*/
			   $this->request->data=Sanitize::clean($this->request->data);			
			
				if($this->User->save($this->request->data))
				{
					

                    $this->User->Industry->recursive = -1;
					$options['fields'] = array(
						'Industry.id',
						'Industry.title_'.$this->Session->read('Config.language').' as title'
					   );
					$options['conditions'] = array(
						'Industry.id '=> $this->request->data['User']['industry_id']
					);	   
					$industry_info= $this->User->Industry->find('first',$options);
                    
                    
                    if(!$this->User->Userrelatetag->deleteAll(array('Userrelatetag.user_id'=>$User_Info['id']),FALSE))
								throw new Exception(__('the_tag_not_saved'),7);
		                
		                if(isset($_POST['tags'])&& !empty($_POST['tags'])){
							$tags=explode(',',$_POST['tags']);
							//$tags=array_filter($tags,'strlen');
							$this->loadModel('Usertag');
							if(!empty($tags)){
								foreach($tags as $tag)
								{
									
                                    $count = $this->Usertag->find('count', array('conditions' => array('Usertag.title' => $tag)));
                                    if($count>0){
                                        continue;
                                    }
                                    
                                    $this->request->data['Usertag']['title']= $tag;
									$this->Usertag->create();
									
									if($this->Usertag->save($this->request->data))
									{
										$tag_id[]=$this->Usertag->getLastInsertID();					
									}
		                            else throw new Exception(__('the_tag_not_saved'),5);
								}
							}
							
						}
						$data = array();
						if(isset($this->request->data['Userrelatetag']['usertag_id'])){
							foreach($this->request->data['Userrelatetag']['usertag_id'] as $id)
							{
								$dt=array('Userrelatetag' => array('user_id' => $User_Info['id'],'usertag_id'=>$id));
								array_push($data,$dt);
							}
						}
						//pr($tag_id); 
						if(!empty($tag_id))
							{
								foreach($tag_id as $tid)
								{
									$dt=array('Userrelatetag' => array('user_id' => $User_Info['id'],'usertag_id'=>$tid));
									array_push($data,$dt);
								}
								
							}
							
						//pr($data);throw new Exception(); 
                       // print_r($data);exit();
						
						if(!empty($this->request->data['Userrelatetag']['usertag_id']) || !empty($tag_id))
						{
							$this->User->Userrelatetag->create();
							if(!$this->User->Userrelatetag->saveMany($data))
		                            throw new Exception(__('the_user_tag_not_saved'),6);
						}
                        
                        $count = $this->User->Userrelatetag->find('count', array('conditions' => array('Userrelatetag.user_id' => $User_Info['id'])));
					    $this->Session->write('tag_count',$count);
					$this->Session->write('User_Info',array(
					'id' => $User_Info['id'] ,
					'name' => $User_Info['name'] ,
					'sex' => $this->request->data['User']['sex'] ,
					'age' => $User_Info['age'],
					'email' => $User_Info['email'],
					'image' => $User_Info['image'],
					'industry_id' => $this->request->data['User']['industry_id'],
					'details' => $this->request->data['User']['details'] ,
					'location' => $this->request->data['User']['location'] ,
					'user_name' => $User_Info['user_name'],
					'industry_name'=>$industry_info['Industry']['title'],
					'user_type'=>0,
                    'follow_count' => $User_Info['follow_count']
					/*'job_status' => $this->request->data['User']['job_status'],
					'degree' => $this->request->data['User']['degree'],
					'university_name' => $this->request->data['User']['university_name'],
					'job_title' => $this->request->data['User']['job_title'],
					'company_name' => $this->request->data['User']['company_name']*/
					
					));
					$response['success'] = TRUE;
				}
					else $response['success']= FALSE;
			   $response['message'] = $response['success'] ? __('save_info_successfully') : __('save_info_not_successfully');
		       $this->set('ajaxData', json_encode($response));
			   $this->render('/Elements/Users/Ajax/ajax_result','ajax');
				break;
		}
		
		
		
		
	}


function search_suggest(){
	$this->User->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$search_type = $_REQUEST['search_type'];
	
	if($search_type==1){
		$users = $this->User->query("
			SELECT 
					distinct(User.id) ,
					User.name,
					User.user_name,
					User.image,
					User.email,
					User.sex
			from users as User
				left join userrelatetags as Userrelatetag	
			    	   on Userrelatetag.user_id = User.id
			    left join usertags as Usertag       
			            on 	Usertag.id = Userrelatetag.usertag_id
			
			where User.role_id = 2
			  and User.status = 1
			  and (
			    (User.name like '".$search_word."%' or User.name like '% ".$search_word."%') or
				(User.user_name like '".$search_word."%' or User.user_name like '% ".$search_word."%') or
				((User.email like '".$search_word."%' or User.email like '% ".$search_word."%') and User.search_with_email=1) or
				(Usertag.title like '".$search_word."%' or Usertag.title like '% ".$search_word."%')
				)
			  limit 0,5	
					
					");
					
					
		$this->set(compact('users'));				
	}
	
	if($search_type==2){
		$posts = $this->User->query("
			SELECT 
					Post.id,
					left(Post.body,30) as body,
					User.id ,
					User.name,
					User.user_name,
					User.image,
					User.sex
			from posts as Post
				inner join users as User 
						on  User.id=Post.user_id
			where User.role_id = 2
			  and User.status = 1
			  and (
			    (Post.body like '%".$search_word."%') 
				)
			  limit 0,5	
					
					");
		$this->set(compact('posts'));			
	}
    
    if($search_type==3){
		$post_tags = $this->User->query("
			SELECT 
					Post.id,
					left(Post.body,30) as body,
					User.id ,
					User.name,
					User.user_name,
					User.image,
					User.sex
			from posts as Post
				inner join users as User 
						on  User.id=Post.user_id
                inner join postrelatetags as Postrelatetag 
			 		on Postrelatetag.post_id=Post.id
			    inner join posttags as Posttag 
			 		on Postrelatetag.posttag_id=Posttag.id        
			where User.role_id = 2
			  and User.status = 1
			  and (
			    (Posttag.title like '%".$search_word."%') 
				)
			  limit 0,5	
					
					");
		$this->set(compact('post_tags'));			
	}
    
    
    
	$this->set('search_type',$search_type);
	
	
	
	 /* 
		$options['conditions'] = array(
			"OR"=>array(
			   'User.name LIKE'=> "$search_word%" ,
			   'User.name LIKE'=> "% $search_word%" ,
			   'User.user_name LIKE'=> "% $search_word%",
			   'User.user_name LIKE'=> "%$search_word%"
			),
			"User.role_id"=>2 ,
			"User.status"=>1 
		);*/
	
		$this->render('/Elements/Users/Ajax/search_suggest','ajax');
}

function user_count()
{
	$this->User->recursive = -1;
	$options['conditions'] = array(
		'User.status' => 1
	);
	return  $this->User->find('count',$options); 
}


function admin_user_export()
{
	$this->User->recursive = -1;
	$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.created',
				'User.email',
				'User.industry_id',
				'User.user_name',
				'User.user_type',
				'User.role_id',
				'User.location',
				'User.site',
				'User.status',
				'User.register_key',
				'Industry.id',
				'Industry.title_'.$this->Session->read('Config.language').' as title',
				'Role.name'
				
			   );
	$options['joins'] = array(
				array('table' => 'industries',
					'alias' => 'Industry',
					'type' => 'LEFT',
					'conditions' => array(
					'Industry.id = User.industry_id ',
					)
				) ,
				array('table' => 'roles',
					'alias' => 'Role',
					'type' => 'LEFT',
					'conditions' => array(
					'Role.id = User.role_id ',
					)
				) 
				
			);	
					   
	$users = $this->User->find('all',$options); 
	$this->set(compact('users'));
}

function get_notofication_list()
{ 
   
   /*(SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												 User.user_type ,
												 Follownotification.type ,
												 Follownotification.follow_id as notification_id ,
												 Follownotification.created ,
												 '0' as notification_type 
										FROM users AS User 
											inner JOIN follow_notifications AS Follownotification 
												ON (Follownotification.to_user_id = User.id  )        
 										WHERE Follownotification.from_user_id =".$User_Info['id']."
 										ORDER BY Follownotification.created desc )   
										
										union all */
										
    
	/*$notifications = $this->User->query("
										
										select * from ( 
										
										
										(SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												 User.user_type ,
												 Postnotification.type ,
												 Postnotification.post_id as notification_id ,
												 Postnotification.created ,
												 '1' as notification_type ,
												 left(Post.body,35) as  notification_body
										FROM users AS User 
											inner JOIN post_notifications AS Postnotification 
												ON (Postnotification.from_user_id = User.id  )
											inner join posts as Post
											   on Post.id = Postnotification.post_id		        
 										WHERE Postnotification.to_user_id =".$User_Info['id']."
 										ORDER BY Postnotification.created desc ) 
										
										union all 
										
										(SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image,
												User.user_name,
												 User.user_type , 
												 Sharepostnotification.type ,
												 Post.id as notification_id ,
												 Sharepostnotification.created ,
												 '2' as notification_type ,
												 left(Post.body,35) as  notification_body
										FROM users AS User 
											inner JOIN sharepost_notifications AS Sharepostnotification 
												ON (Sharepostnotification.from_user_id = User.id  ) 
											inner join shareposts as Sharepost	       
											   on Sharepost.id=	Sharepostnotification.sharepost_id
											inner join posts as Post
											   on 	Post.id = Sharepost.post_id     
 										WHERE Sharepostnotification.to_user_id =".$User_Info['id']."
 										ORDER BY Sharepostnotification.created desc ) 
										
										
										union all 
										
										(SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												 User.user_type ,
												 LikeunlikeNotification.type ,
												 Post.id as notification_id ,
												 LikeunlikeNotification.created ,
												 '3' as notification_type ,
												 left(Post.body,35) as  notification_body
										FROM users AS User 
											inner JOIN likeunlike_notifications AS LikeunlikeNotification 
												ON (LikeunlikeNotification.from_user_id = User.id  ) 
												
											inner join likeunlikes as Likeunlike	       
											   on Likeunlike.id=LikeunlikeNotification.likeunlike_id
											inner join posts as Post
											   on 	Post.id = Likeunlike.post_id   
											   	       
 										WHERE LikeunlikeNotification.to_user_id =".$User_Info['id']."
 										ORDER BY LikeunlikeNotification.created desc )
										
										union all 
										
										(SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												 User.user_type ,
												 Follownotification.type ,
												 Follownotification.follow_id as notification_id ,
												 Follownotification.created ,
												 '4' as notification_type ,
												 '' as  notification_body
										FROM users AS User 
											inner JOIN follow_notifications AS Follownotification 
												ON (Follownotification.from_user_id = User.id  )  
											   	       
 										WHERE Follownotification.to_user_id =".$User_Info['id']."
 										ORDER BY Follownotification.created desc )  
										
										
										) UserNotification order by  created desc   
										
										 
										
										
										");*/
	 if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	 }else $first = 0;
	 $end=10;									
										
     $User_Info= $this->Session->read('User_Info');
	 $notifications = $this->User->query("
	 	select * from vmnotifications as UserNotification 
		where UserNotification.to_user_id  = ".$User_Info['id']."
		 limit ".$first." , ".$end." 
	 ");
	 
	 
     $this->set(array(
		'notifications' => $notifications,
		'_serialize' => array('notifications')
	  ));
	
	$this->render('/Elements/Users/Ajax/refresh_notification', 'ajax');
 }  

/**
 * 
 * 
*/
 function profile_follow_payee(){
 	
	$this->User->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	$id =$_REQUEST['id'];
	$User_Info= $this->Session->read('User_Info');
	if($User_Info['id']){
			$follow_query="(select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=Follow.to_user_id )as 'count' ,";
		}else $follow_query='';
	
	$response = $this->User->query("
										SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												User.details,
												 User.location ,
												 User.site ,
												 User.user_type,
												".$follow_query."
												Industry.title_".$this->Session->read('Config.language')." as title
										FROM users AS User 
											inner JOIN follows AS Follow 
												ON (Follow.to_user_id = User.id  )   
											left join industries Industry
											          on  User.industry_id = Industry.id	     
 										WHERE Follow.from_user_id =".$id."
 										  AND User.status = 1 
										  AND  User.id <> ".$id."
										 
 										ORDER BY Follow.created  desc
										LIMIT $first , $end
										");
		
	$this->set('users', $response);
	
	$this->render('/Elements/Users/Ajax/profile_result', 'ajax');	
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_profile_follow_payee($user_id){
 	
	$this->User->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	$id =$_REQUEST['id'];
	 
	if($user_id){
			$follow_query="(select count(*) from follows where from_user_id=".$user_id." and to_user_id=Follow.to_user_id )as 'count' ,";
		}else $follow_query='';
	
	$response = $this->User->query("
										SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												User.details,
												 User.location ,
												 User.site ,
												 User.user_type,
												".$follow_query."
												Industry.title_".$this->Session->read('Config.language')." as title
										FROM users AS User 
											inner JOIN follows AS Follow 
												ON (Follow.to_user_id = User.id  )   
											left join industries Industry
											          on  User.industry_id = Industry.id	     
 										WHERE Follow.from_user_id =".$id."
 										  AND User.status = 1 
										  AND  User.id <> ".$id."
										 
 										ORDER BY Follow.created  desc
										LIMIT $first , $end
										");
		
	
	$this->set(array(
		'users' => $response,
		'_serialize' => array('users')
		));	
	
 }
 
 /**
 * 
 * 
*/
 function profile_chaser(){
 	
	$this->User->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	$id =$_REQUEST['id'];
	$User_Info= $this->Session->read('User_Info');
	if($User_Info['id']){
			$follow_query="(select count(*) from follows where from_user_id=".$User_Info['id']." and to_user_id=Follow.from_user_id )as 'count' ,";
		}else $follow_query='';
	
	$response = $this->User->query("
										SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												User.details,
												 User.location ,
												 User.site ,
												 User.user_type,
												".$follow_query."
												Industry.title_".$this->Session->read('Config.language')." as title
										FROM users AS User 
											inner JOIN follows AS Follow 
												ON (Follow.from_user_id = User.id  )  
											left join industries Industry
											          on  User.industry_id = Industry.id	   
 										WHERE Follow.to_user_id =".$id."
 										  AND User.status = 1 
										  AND  User.id <> ".$id."
										 
 										ORDER BY Follow.created  desc
										LIMIT $first , $end
										");
		
	$this->set('users', $response);
	
	$this->render('/Elements/Users/Ajax/profile_result', 'ajax');	
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_profile_chaser($user_id){
 	
	$this->User->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	$id =$_REQUEST['id'];
	 
	if($user_id){
			$follow_query="(select count(*) from follows where from_user_id=".$user_id." and to_user_id=Follow.from_user_id )as 'count' ,";
		}else $follow_query='';
	
	$response = $this->User->query("
										SELECT 
												User.id, 
												User.name, 
												User.sex,
												User.email,
												User.image, 
												User.user_name,
												User.details,
												 User.location ,
												 User.site ,
												 User.user_type,
												".$follow_query."
												Industry.title_".$this->Session->read('Config.language')." as title
										FROM users AS User 
											inner JOIN follows AS Follow 
												ON (Follow.from_user_id = User.id  )  
											left join industries Industry
											          on  User.industry_id = Industry.id	   
 										WHERE Follow.to_user_id =".$id."
 										  AND User.status = 1 
										  AND  User.id <> ".$id."
										 
 										ORDER BY Follow.created  desc
										LIMIT $first , $end
										");
		
	 
	$this->set(array(
		'users' => $response,
		'_serialize' => array('users')
		));	
	
 }
 
function disable_account(){ 	
  
  
  
  $this->User->recursive = -1;
  $this->User->Post->recursive = -1;
  
  $this->set('title_for_layout',__('disable_account'));
  $this->set('description_for_layout',__('disable_account'));
  $this->set('keywords_for_layout',__('disable_account'));
  
  $User_Info= $this->Session->read('User_Info');	
  
  if($this->request->is('post') || $this->request->is('put'))
	{
		
		try{
			
			if($this->User->deleteAll(array('User.id' => $User_Info['id']), false)){
				if($this->User->Post->deleteAll(array('Post.user_id' => $User_Info['id']), false)){
					$this->logout();
					//$this->redirect(array('controller' => 'pages','action' => 'display'));
				}
			}
			
			
		$response['success'] = TRUE;
		} catch (Exception $e) {
			$response['success'] = FALSE;
		}
	}
	
	$options['fields'] = array(
		'User.id',
		'User.name',
		'User.sex',
		'User.user_name',
		'User.cover_image',
		'User.image'
	   );
	$options['conditions'] = array(
	'User.id '=> $User_Info['id']
	);	   
	$user= $this->User->find('first',$options);
	$this->set(compact('user'));
  
}
 
/* news letter */
function get_last_login_users(){
	$this->User->recursive = -1;
	
    $this->autoRender = false; 
    
	/*$users = $this->User->query("
				SELECT 
						User.id, 
						User.name, 
						User.sex,
						User.email, 
						User.user_name,
						User.image ,
						User.last_login ,
						User.created 
				FROM `users` AS `User` 
			WHERE `User`.`id` = 26
			limit 0,1
	");*/
	
	$users = $this->User->query("
				SELECT 
						User.id, 
						User.name, 
						User.sex,
						User.email, 
						User.user_name,
						User.image ,
						User.last_login ,
						User.created 
				FROM users AS User 
			WHERE ( SELECT DATEDIFF(now(),User.last_login) )>14
			   or User.last_login = '0000-00-00 00:00:00'	
             and  User.status=1 
			LIMIT 0 , 1
	");
	
	
	
	$hot_post_tags=$this->hot_post_tags();
		
	if(!empty($users)){
		foreach($users as $user)
		{
			
			$hot_posts=$this->hot_posts($user['User']['id']);
			$max_follower = $this->max_follower($user['User']['id']);
			$week_fllower=$this->week_fllower($user['User']['id'],$user['User']['last_login']);
			$new_message_count=$this->new_message_count($user['User']['id'],$user['User']['last_login']);
			$new_notification = $this->new_notification($user['User']['id'],$user['User']['last_login']);
            $week_fllower_count = $this->week_fllower_count($user['User']['id'],$user['User']['last_login']);
			 
			try{
				$Email = new CakeEmail();
                $Email->reset();  
				$Email->template('last_login_template', 'newsletter_layout');
				$Email->subject(__('private_madaner_newsletter'));
				$Email->emailFormat('html');
				$Email->to($user['User']['email']);
				$Email->from(array(__Madaner_Email => __Email_Name));
				$Email->viewVars(array('name'=>$user['User']['name'],'email'=>$user['User']['email'],'user_name'=>$user['User']['user_name'],'image'=>$user['User']['image'],'hot_posts'=>$hot_posts,'max_followers'=>$max_follower,'hot_post_tags'=>$hot_post_tags,'week_fllowers'=>$week_fllower,'new_message_count'=>$new_message_count,'sex'=>$user['User']['sex'],'notification_count'=>$new_notification,'week_fllower_count'=>$week_fllower_count));
				$Email->send();
                
                
                /* set log */
    			Controller::loadModel('Errorlog');
    			$this->Errorlog->get_log('UsersController','Last login Send email ='.$user['User']['email']);
		        /* set log */
				
			}catch(Exception $e){
				/* set log */
					Controller::loadModel('Errorlog');
					$this->Errorlog->get_log('UsersControllers',$e->getMessage());
				/* set log */
				 
			}
			
			$ret= $this->User->updateAll(
				    array( 'User.last_login' =>'"'.date('Y-m-d H:i:s').'"'),
				    array( 'User.id' => $user['User']['id'] )  //condition
		   		);	
				
		}
	}
	
}

/**
* 
* @param undefined $user_id
* 
*/
public function hot_posts($user_id){
	$this->User->Post->recursive = -1;
	
	$posts = $this->User->Post->query("
        SELECT  	
		    ParentPost.parent_id,
		    User.name ,
		    User.id ,
		    User.image,
		    User.user_name ,
			User.sex,
		    Post.id,
		    Post.url,
		    Post.image,
			Post.created,
			Post.body,
		    count(*)

		FROM posts as ParentPost
			inner join posts as Post
		        	on ParentPost.parent_id = Post.id 
		        inner join users as User
		        	on User.id = Post.user_id
				   and User.id <> ".$user_id."	
		                    
		where  ParentPost.parent_id <> 0
		    group by ParentPost.parent_id
		     
		ORDER BY count(*)  DESC
		limit 0 , 3
    ");
    return $posts;  
}

public function max_follower($user_id){	
	$this->User->Follow->recursive = -1;
	$users=$this->User->Follow->query("
	SELECT 
				User.id, 
				User.name, 
				User.sex,
				User.email, 
				User.user_name,
				User.image ,
				User.sex,
				User.location ,
				User.site ,
				User.details,
				Industry.title_".$this->Session->read('Config.language')." as title
	FROM follows as Follow 
	   inner join users as User 
		       on User.id = Follow.to_user_id
			  and User.id <> ".$user_id." 
	   inner join industries as Industry	 
	   	       on Industry.id = User.industry_id 	   	
	group by Follow.to_user_id
	ORDER BY count( Follow.to_user_id ) DESC
	limit 0 ,4 ");
	return $users;		
}
public function hot_post_tags(){
	App::import('Model','Postrelatetag');
	$Postrelatetag= new Postrelatetag();
	$Postrelatetag->recursive = -1;
	
	$tags = $Postrelatetag->query("
			SELECT Posttag.id ,
		       Posttag.title 	

			FROM postrelatetags as Postrelatetag
			    inner join posttags as Posttag
			    	    on 	Postrelatetag.posttag_id = Posttag.id
			group by Postrelatetag.posttag_id
			order by count(*) desc
			limit 0,20
			");
			
	return $tags;			
}
/**
* 
* @param undefined $user_id
* @param undefined $last_login
* 
*/
public function week_fllower($user_id,$last_login){
	$this->User->Follow->recursive = -1;
	$users=$this->User->Follow->query("
	SELECT 
			User.id, 
			User.name, 
			User.sex,
			User.email, 
			User.user_name,
			User.image ,
			User.sex ,
			(select count(Post.id) from posts as Post where Post.user_id= User.id) as post_count ,
			(select count(Follow.id) from follows as Follow where Follow.to_user_id= User.id) as new_follow_count ,
			(select count(Follow.id) from follows as Follow where Follow.from_user_id= User.id) as new_tofollow_count,
			Industry.title_".$this->Session->read('Config.language')." as title ,
			User.location
				
	FROM follows as Follow 
	   inner join users as User 
		   on User.id = Follow.from_user_id	 
	   inner join industries as Industry	 
	   	       on Industry.id = User.industry_id 	     	
	where Follow.to_user_id = ".$user_id."
	  and Follow.created between date('".$last_login."') and date('".date('Y-m-d H:i:s')."')
		ORDER BY  Follow.created   DESC
		limit 0 ,4 ");
	return $users;
	
	 // new_follow_count donbal konande
	 // new_tofollow_count donbal shavande
}

public function week_fllower_count($user_id,$last_login){
	$this->User->Follow->recursive = -1;
	$count=$this->User->Follow->query("
	SELECT 
			count(Follow.id) as follow_count
				
	FROM follows as Follow 	     	
	where Follow.to_user_id = ".$user_id."
	  and Follow.created between date('".$last_login."') and date('".date('Y-m-d H:i:s')."')
	 ");
	return $count[0][0]['follow_count'];
}

/**
* 
* @param undefined $user_id
* @param undefined $last_login
* 
*/
public function new_message_count($user_id,$last_login){
	
	$this->User->Chat->recursive = -1;
	
    $count = $this->User->Chat->query("
	 select * from (	
		select count(Chat.id) as message_count from chats as Chat
			where Chat.to_user_id = ".$user_id." 
			  and Chat.viewd =0
			  and Chat.from_delete=0 
			  and Chat.created BETWEEN date('".$last_login."') and date(now())
		) as Chat		
	");
	
   return $count[0]['Chat']['message_count'];
   //$this->set('count',$count[0]['Chat']['message_count']);
}

function new_notification($user_id,$last_login)
{
	$this->User->recursive = -1;
	$notification_count = $this->User->query(
		"
				select * from(						
					select sum(notification_count) as notification_sum from ( 
					
					
					(
						SELECT 
								 count(Postnotification.post_id) as notification_count ,
								 '1' as notification_type  
								 
						FROM   post_notifications AS Postnotification 		        
						WHERE Postnotification.to_user_id =".$user_id."
						  and Postnotification.created between  date('".$last_login."') and date(now())
				    ) 
					
					union all 
					
					(
						SELECT 
								 count(Sharepostnotification.id) as notification_count ,
								 '2' as notification_type  
						FROM  sharepost_notifications AS Sharepostnotification 
								   
						WHERE Sharepostnotification.to_user_id =".$user_id."
						  and Sharepostnotification.created between date('".$last_login."') and date(now())
				   ) 
							
							
					union all 
							
					(
						SELECT 
								 count(LikeunlikeNotification.id) as notification_count ,
								 '3' as notification_type 
						FROM  likeunlike_notifications AS LikeunlikeNotification 
 
						WHERE LikeunlikeNotification.to_user_id =".$user_id."
						  and LikeunlikeNotification.created between date('".$last_login."') and date(now())
				   )
							
					union all 
					
				  (
						SELECT 
								count(Follownotification.id) as notification_count ,
								 '4' as notification_type  
						FROM  follow_notifications AS Follownotification 
							   	       
						WHERE Follownotification.to_user_id =".$user_id."
						  and Follownotification.created between date('".$last_login."') and date(now())
						  
				  )  
							
							
				) UserNotification 
				
				) UserNotification 
											
		"
	);
		
	return $notification_count[0]['UserNotification']['notification_sum'];
		/*$this->set('count',$notification_count); */							
}




public function favorit_posts(){
	App::import('Model','Post');
	$post= new Post();
	$post->recursive = -1;
	
	$posts = $user->query("
				
		select * from (	
			(SELECT  	Post.id ,
						Post.url,
				        Post.image,
				        ".__('max_like')." as title

					FROM likeunlikes as Likeunlike
						inner join posts as Post
					        	on Likeunlike.post_id = Post.id
					where status = 1
						group by post_id
						order by count(*) desc
					limit 0,4
			)  
			
		union all
		
		(SELECT  	Post.id ,
					Post.url,
			        Post.image,
			        ".__('max_sharing')." as title

					FROM shareposts as Sharepost
						inner join posts as Post
					        	on Sharepost.post_id = Post.id
		 
						group by Sharepost.post_id
						order by count(*) desc
					limit 0,4
		)
        
        union all
		
		(SELECT  	Post.id ,
					Post.url,
			        Post.image,
			        ".__('max_response')." as title

					FROM posts as Post
                    WHERE parent_id <> 0
                        group by parent_id
                        order by count(*)  desc
					limit 0,4
		)
        
        union all
		
		(SELECT Post.id,
                Post.url, 
                Post.image,
                ".__('max_favorite')." AS title
            FROM favorite_posts AS Favoritepost
            INNER JOIN posts AS Post ON Favoritepost.post_id = Post.id
            GROUP BY Favoritepost.post_id
            ORDER BY count( * ) DESC
            LIMIT 0 , 4
		)
		
		
		) as Presentation order by rand() limit 0,4
		
		");
      return $posts;  
}



public function hot_images(){
	App::import('Model','Post');
	$post= new Post();
	$post->recursive = -1;
	
	$posts = $user->query("
				
		select * from (	
			(SELECT  	Post.id ,
						Post.url,
				        Post.image,
				        ".__('max_like')." as title

					FROM likeunlikes as Likeunlike
						inner join posts as Post
					        	on Likeunlike.post_id = Post.id
					where status = 1
                      and Post.image <> ''
						group by post_id
						order by count(*) desc
					limit 0,4
			)  
			
		union all
		
		(SELECT  	Post.id ,
					Post.url,
			        Post.image,
			        ".__('max_sharing')." as title

					FROM shareposts as Sharepost
						inner join posts as Post
					        	on Sharepost.post_id = Post.id
		            where Post.image <> '' 
						group by Sharepost.post_id
						order by count(*) desc
					limit 0,4
		)
        
        union all
		
		(SELECT  	Post.id ,
					Post.url,
			        Post.image,
			        ".__('max_response')." as title

					FROM posts as Post
                    WHERE parent_id <> 0
                      and Post.image <> ''
                        group by parent_id
                        order by count(*)  desc
					limit 0,4
		)
        
        union all
		
		(SELECT Post.id,
                Post.url, 
                Post.image,
                ".__('max_favorite')." AS title
            FROM favorite_posts AS Favoritepost
                INNER JOIN posts AS Post 
                        ON Favoritepost.post_id = Post.id
            where Post.image <> ''            
            GROUP BY Favoritepost.post_id
            ORDER BY count( * ) DESC
            LIMIT 0 , 4
		)
		
		
		) as HotImages order by rand() limit 0,4
		
		");
      return $posts;  
}


 function admin_tag_search()
 {
 	$this->loadModel('Usertag');
    $this->Usertag->recursive = -1;
	
	$search_word =  $_GET["term"] ;
	$options['fields'] = array(
				'Usertag.id',
				'Usertag.title'
			   );
				   
	$options['conditions'] = array(
		 array("OR"=>array(
		 	'Usertag.title LIKE'=> "$search_word%" ,
		 	'Usertag.title LIKE'=> "% $search_word%" 
		 ))  
		   
	);
	
	$options['order'] = array(
			'Usertag.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$usertags = $this->Usertag->find('all',$options);
    $this->set('search_result',$usertags);
    $this->render('/Elements/Users/Ajax/tag_search','ajax'); 											 
 }

public function  ads_count(){
   	   $User_Info= $this->Session->read('User_Info');
       $this->User->recursive = -1;
       $response['success'] = TRUE;
	   $response['message'] = FALSE;
       $response['user_count'] = 0;
       
       $tag = Sanitize::clean($_REQUEST['tag']);
       /*
		   $options['joins'] = array(
			array('table' => 'userrelatetags',
				'alias' => 'Userrelatetag',
				'type' => 'LEFT',
				'conditions' => array(
				'User.id = Userrelatetag.user_id ',
				)
			) 
			
		   );
		   
		$options['conditions'] = array(
			'User.status' => 1 ,
            'User.role_id'=>2,
            'Userrelatetag.usertag_id' => array($tag)
		);*/
       if($tag=='' || $tag==0){
          $sql='
           SELECT count(distinct(User.id)) count
               FROM users as User
            WHERE User.status = 1       
              AND User.role_id = 2 
              and User.id <> '.$User_Info['id'].'      
       '; 
       }else{
           $sql='
           SELECT count(distinct(User.id)) count
               FROM users as User
                   INNER JOIN userrelatetags as Userrelatetag
                           ON Userrelatetag.user_id = User.id
            WHERE User.status = 1       
              AND User.role_id = 2
              AND Userrelatetag.usertag_id IN  ('.$tag.') 
              and User.id <> '.$User_Info['id'].'       
           ';
       } 
       
       
	   $response['user_count'] = $this->User->query($sql);	
      // pr($response['user_count'] );exit(); 
	   $response['user_count'] = $response['user_count'][0][0]['count'] ;	 
       //$response['message'] = $response['success'] ? __('correct_email') : __('exist_email'); 
       $this->set('ajaxData', json_encode($response));
       $this->render('/Elements/Users/Ajax/ajax_result', 'ajax');   
}

function all_tags(){
	$this->set('title_for_layout',__('all_subject'));
	$this->set('description_for_layout',__('all_subject'));
	$this->set('keywords_for_layout',__('all_subject'));
 }

/**
* 
* 
*/
function get_all_tags(){
 	
	$this->User->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=50;
	$User_Info= $this->Session->read('User_Info');
    
			$response = $this->User->query("
									 	 SELECT Usertag.title, 
                                                Usertag.id, 
                                                count( Userrelatetag.user_id ) AS count
                        					FROM usertags AS Usertag
                        						LEFT JOIN userrelatetags AS Userrelatetag
                        							   ON Usertag.id = Userrelatetag.usertag_id
                        					GROUP BY Userrelatetag.usertag_id
                        					ORDER BY count DESC
												    LIMIT $first , $end
										"							
										);		
           $this->set('tags', $response);		
           $this->render('/Elements/Users/Ajax/all_tags', 'ajax');
}	


function add_usertag()
 {
 	$this->User->recursive = -1;
	$response['success'] = FALSE;
	$response['message'] = FALSE; 
    
    $User_Info= $this->Session->read('User_Info');
    $this->request->data['Userrelatetag']['usertag_id'] = Sanitize::clean($_REQUEST['id']);
    $this->request->data['Userrelatetag']['user_id'] = $User_Info['id'];
    $this->User->Userrelatetag->create();
    try{
       if($this->User->Userrelatetag->save($this->request->data)){
           $response['success'] = TRUE; 
       }  
    } catch (Exception $e) {
        $response['success'] = FALSE;
    }
    
    $response['message'] = $response['success'] ? __('the_usertag_added_sucsses') :  __('the_usertag_added_notsucsses');  
     
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Users/Ajax/ajax_result', 'ajax');
 }
 
 function get_user_info($user_id = 0)
 {
 	$User_Info= $this->Session->read('User_Info');
    
	if($user_id==0){
		$user_id = $User_Info['id'];
	}
	
    $this->User->recursive = -1;
    $options['fields'] = array(
    	'User.id',
    	'User.user_name',
    	'User.details',
    	'User.pdf',
    );
	$options['conditions'] = array(
	    'User.id'=> $user_id
	);	   
	$user= $this->User->find('first',$options);  
    
    $this->User->Post->recursive = -1;	
	$post_count = $this->User->Post->find('count', array('conditions' => array('Post.user_id'=>$user_id)));	
	$this->User->Post->Sharepost->recursive = -1;
	$share_post = $this->User->Post->Sharepost->find('count', array('conditions' => array('Sharepost.user_id' => $user_id)));	
	$post_count+=$share_post;
    
    /* donbal shevande */
    $this->User->Follow->recursive = -1;
    $tofollow_count = $this->User->Follow->find('count', array('conditions' => array('Follow.from_user_id'=>$user_id)));
    /* donbal konande */
    $follow_count = $this->User->Follow->find('count', array('conditions' => array('Follow.to_user_id'=>$user_id)));
    
    $this->User->Chat->recursive = -1;
    $new_message_count = $this->User->Chat->find('count', array('conditions' => array('Chat.to_user_id'=>$user_id,'Chat.viewd'=>0,'Chat.from_delete'=>0)));
    
	$favorite_count = $this->User->Post->Favoritepost->find('count', array('conditions' => array('Favoritepost.user_id'=>$user_id)));
    
	/*
    $this->User->Userrelatetag->recursive=-1;
	$options['fields'] = array(
			'Usertag.id',
			'Usertag.title'
		   );
	   $options['joins'] = array(
		array('table' => 'usertags',
			'alias' => 'Usertag',
			'type' => 'LEFT',
			'conditions' => array(
			'Usertag.id = Userrelatetag.usertag_id ',
			)
		) 
		
	   );
	   
	$options['conditions'] = array(
		'Userrelatetag.user_id' => $user_id
	);
	$options['order'] = array(
	'Usertag.id'=>'asc'
	);	
	$tags = $this->User->Userrelatetag->find('all',$options);
    */
    return array('user_info'=>$user,'post_count'=>$post_count,'tofollow_count'=>$tofollow_count,'follow_count'=>$follow_count,'new_message_count'=>$new_message_count,'favorite_count'=>$favorite_count);
    
 }
 
 
 function new_users(){
	$this->User->recursive = -1;

    $this->autoRender = false; 
    $this->loadModel('Userm');
	/*
	$users = $this->User->query("
				SELECT 
						* 
				FROM userms AS Userm
			WHERE  Userm.id = 664 
			LIMIT 0 , 1
	");*/
	
	
	$users = $this->User->query("
				SELECT 
						* 
				FROM userms AS Userm
			WHERE  Userm.inserted = 0 
			LIMIT 0 , 1
	");
	
	if(!empty($users)){
		foreach($users as $user)
		{			
			 
				$register_email=trim($user['Userm']['email']);
				$register_key = md5(rand().$this->Gilace->random_char());
				$password = 12345678; 
				
				$options = array();
				$count = 0;
				$options['conditions'] = array(
					'User.user_name'=> $user['Userm']['user_name']
				);
				$count = $this->User->find('count',$options);
				 
				if($count > 0){
					$ret= $this->Userm->updateAll(
					    array( 'Userm.log' =>'"User Exist in database"','Userm.inserted' =>'2'),
					    array( 'Userm.id' => $user['Userm']['id'] )  //condition
			   		);
					return;
				}
				
				$options = array();
				$count = 0;
				$options['conditions'] = array(
					'User.email'=> $user['Userm']['email']
				);
				$count = $this->User->find('count',$options);
				 
				if($count > 0){
					$ret= $this->Userm->updateAll(
					    array( 'Userm.log' =>'"Email Exist in database"','Userm.inserted' =>'3'),
					    array( 'Userm.id' => $user['Userm']['id'] )  //condition
			   		);
					return;
				}
				
				
				
				try{
					$Email = new CakeEmail();
					$Email->template('newuser_sendemail', 'sendemail_layout');
					$Email->subject(__('welcome_to_madaner'));
					$Email->emailFormat('html');
					$Email->to($register_email);
					$Email->from(array('info@madaner.ir' => 'Madaner'));
					$Email->viewVars(array('name'=>$user['Userm']['name'],'register_key'=>$register_key,'email'=>$register_email,'user_name'=>$user['Userm']['user_name'],'password'=>$password));
					$Email->send();
					
					   $this->request->data['User']['email']= $register_email;
					   $this->request->data['User']['register_key']= $register_key;	
					   $this->request->data['User']['role_id']=2;	// user role
					   $this->request->data['User']['name']=trim($user['Userm']['name']);
					   $this->request->data['User']['user_name']=trim($user['Userm']['user_name']);
					   $this->request->data['User']['password']= $password;
					   if(!$this->User->save($this->request->data)){
					   		$ret= $this->Userm->updateAll(
							    array( 'Userm.log' =>'"Error in Insert user"','Userm.inserted' =>'4'),
							    array( 'Userm.id' => $user['Userm']['id'] )  //condition
					   		);
					   }
					   else{
					   		$ret= $this->Userm->updateAll(
							    array( 'Userm.inserted' =>'1'),
							    array( 'Userm.id' => $user['Userm']['id'] )  //condition
					   		);	
					   }					
					
				} catch (Exception $e) {
					$ret= $this->Userm->updateAll(
					    array( 'Userm.log' =>'"Error in send email"','Userm.inserted' =>'5'),
					    array( 'Userm.id' => $user['Userm']['id'] )  //condition
			   		);
				}
								
		}
	}
	
}


 
/**
* load captcha
* 
*/
function captcha_image(){
    App::import('Vendor', 'captcha/captcha');
    $captcha = new captcha();
    $captcha->show_captcha();
}




		
}

