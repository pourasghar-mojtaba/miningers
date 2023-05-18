<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class AllpostsController  extends AppController {

    var $name = 'Allposts';

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('insertdata','app_share','app_unshare','get_share_post_users','app_get_share_post_users');
}
	
function insertdata(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$allposts = $this->Allpost->query("
        select * from vwallposts as Allpost
    ");
    
    // pr($allposts);
    
    if(!empty($allposts)){
        foreach($allposts as $allpost)
        {
            //$long = strtotime($allpost['Allpost']['created']);
			$this->request->data['Allpost']['post_id']= $allpost['Allpost']['post_id'];
        	$this->request->data['Allpost']['user_id']=$allpost['Allpost']['user_id'];
        	$this->request->data['Allpost']['type']=$allpost['Allpost']['type'];
        	//$this->request->data['Allpost']['insertdt']=date('Ymd',$long).date('Hi',$long);
        	//$this->request->data['Allpost']['inserttm']=date('Hi',$long);
        	$this->Allpost->create();
        	
        	$this->request->data=Sanitize::clean($this->request->data);

            if($this->Allpost->save($this->request->data)){
               
        	}
        }
    } 
 }

/**
* 
* @param undefined $post_id
* 
*/
 function get_share_post_users($post_id){
 	$User_Info= $this->Session->read('User_Info');
	$this->Allpost->recursive=-1;
	$options['fields'] = array(
			'User.id',
			'User.image',
			'User.user_name',
			'User.name',
			'User.sex',
		   );
	   $options['joins'] = array(
		array('table' => 'users',
			'alias' => 'User',
			'type' => 'LEFT',
			'conditions' => array(
			'User.id = Allpost.user_id ',
			)
		) 
		
	   );
	   
	$options['conditions'] = array(
		'Allpost.post_id' => $post_id ,
		'Allpost.user_id <>' => $User_Info['id'] ,
		'Allpost.type' => 1 ,       
	);
	$options['order'] = array(
		'Allpost.id'=>'desc'
	);	
	$options['limit'] = 4;
	$users = $this->Allpost->find('all',$options);
	return $users;
 }
 
 function app_get_share_post_users($post_id,$user_id){
 	 
	$this->Allpost->recursive=-1;
	$options['fields'] = array(
			'User.id',
			'User.image',
			'User.user_name',
			'User.name',
			'User.sex',
		   );
	   $options['joins'] = array(
		array('table' => 'users',
			'alias' => 'User',
			'type' => 'LEFT',
			'conditions' => array(
			'User.id = Allpost.user_id ',
			)
		) 
		
	   );
	   
	$options['conditions'] = array(
		'Allpost.post_id' => $post_id ,
		'Allpost.user_id <>' => $user_id ,
		'Allpost.type' => 1 ,       
	);
	$options['order'] = array(
		'Allpost.id'=>'desc'
	);	
	$options['limit'] = 4;
	$users = $this->Allpost->find('all',$options);
	 
    $this->set(array(
		'users' => $users,
		'_serialize' => array('users')
		));
 }
 
function share(){
	$response['success'] = false;
	$response['message'] = null;

	$User_Info= $this->Session->read('User_Info');
		
	/* check privacy */
	if($User_Info['id']!=$_REQUEST['post_user_id'])
	{	
		/*Controller::loadModel('Sendemail');
		$ret=$this->Sendemail->check_send_email($_REQUEST['post_user_id'],'onsharing');
		if($ret){
			$this->_send_email($_REQUEST['post_user_id']);
		}*/
		
		Controller::loadModel('Privacy');
		$ret=$this->Privacy->check_privacy(Sanitize::clean($_REQUEST['post_user_id']),$User_Info['id'],'sharing');
		if(!$ret['status']){
			switch($ret['privacy_step']){
				case 1:
					$response['message']=  $ret['name'].' '.__('allow_privacy_for_chaser'); 
					break;
				case 2:
					$response['message']=  $ret['name'].' '.__('allow_privacy_for_follow_payee'); 
					break;	
				case 3:
					$response['message']=  $ret['name'].' '.__('allow_privacy_for_chaser_follow_payee'); 
					break;	
				case 4:
					$response['message']=  $ret['name'].' '.__('not_allow_privacy'); 
					break;	
			}
			$response['success'] = FALSE;
			$this->set('ajaxData',  json_encode($response));
		    $this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
			return;
		}
	}
	/* check privacy */
	
	
	$options['conditions'] = array(
		'Allpost.user_id'=>$User_Info['id'] ,
		'Allpost.post_id'=> Sanitize::clean($_REQUEST['post_id']),
		'Allpost.type'=> 1
	);
	$count = $this->Allpost->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeated_share');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
		return;
	}
	
	$this->request->data['Allpost']['post_id']= $_REQUEST['post_id'];
	$this->request->data['Allpost']['user_id']=$User_Info['id'];
	$this->request->data['Allpost']['type']= 1;
	$this->Allpost->create();
	
	$this->request->data=Sanitize::clean($this->request->data);

	try{
		if($this->Allpost->save($this->request->data)){
			$response['success'] = TRUE;
			$allpost_id= $this->Allpost->getLastInsertID();
			
			$this->Allpost->query("
				update posts as Post set sharecount = sharecount + 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
			
			/* Sharepost */
			$this->request->data = array();
			$this->loadModel('Sharepost');
			$this->request->data['Sharepost']['post_id']= $_REQUEST['post_id'];
			$this->request->data['Sharepost']['user_id']=$User_Info['id'];
			$this->Sharepost->create();
			
			$this->request->data=Sanitize::clean($this->request->data);

			try{
				if($this->Sharepost->save($this->request->data)){
					$response['success'] = TRUE;
					$sharepost_id= $this->Sharepost->getLastInsertID();
					/* add notification */
					if($_REQUEST['post_user_id']!=$User_Info['id']){
						$long = strtotime(date('Y-m-d H:i:s'));
						$this->request->data = array();
						/*$this->request->data['Sharepostnotification']['sharepost_id']= $sharepost_id;
						$this->request->data['Sharepostnotification']['from_user_id']= $User_Info['id'];
						$this->request->data['Sharepostnotification']['to_user_id']= $_REQUEST['post_user_id'];
						$this->request->data['Sharepostnotification']['type']=0;
						$this->request->data['Sharepostnotification']['insertdt']= date('Ymd',$long);
						$this->request->data['Sharepostnotification']['inserttm']= date('Hi',$long);
						$this->request->data=Sanitize::clean($this->request->data);
						$this->Sharepost->Sharepostnotification->save($this->request->data);*/
						
						$this->loadModel('Notification');
						$this->Notification->recursive = -1;
						$this->request->data['Notification']['notification_id']= $_REQUEST['post_id'];
						$this->request->data['Notification']['from_user_id']= $User_Info['id'];
						$this->request->data['Notification']['to_user_id']= $_REQUEST['post_user_id'];
						$this->request->data['Notification']['type']= 0;
						$this->request->data['Notification']['notification_type']= 2;
						$this->request->data=Sanitize::clean($this->request->data);
						$this->Notification->create();
						$this->Notification->save($this->request->data);
					}
				}
			} catch (Exception $e) {
				$response['success'] = FALSE;
			}
			
		}
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	
	$response['message'] = $response['success'] ? __('share_post_success') : __('share_post_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_share($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	/* check privacy */
	if($user_id!=$_REQUEST['post_user_id'])
	{	
		Controller::loadModel('Privacy');
		$ret=$this->Privacy->check_privacy($_REQUEST['post_user_id'],$user_id,'sharing');
		if(!$ret['status']){
			switch($ret['privacy_step']){
				case 1:
					$response['message']=  $ret['name'].' '.__('allow_privacy_for_chaser'); 
					break;
				case 2:
					$response['message']=  $ret['name'].' '.__('allow_privacy_for_follow_payee'); 
					break;	
				case 3:
					$response['message']=  $ret['name'].' '.__('allow_privacy_for_chaser_follow_payee'); 
					break;	
				case 4:
					$response['message']=  $ret['name'].' '.__('not_allow_privacy'); 
					break;	
			}
			$response['success'] = FALSE;
			$this->set('ajaxData',  json_encode($response));
		    $this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
			return;
		}
	}
	/* check privacy */
	
	
	$options['conditions'] = array(
		'Allpost.user_id'=>$user_id ,
		'Allpost.post_id'=> $_REQUEST['post_id'],
		'Allpost.type'=> 1
	);
	$count = $this->Allpost->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeated_share');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
		return;
	}
	
	$this->request->data['Allpost']['post_id']= Sanitize::clean($_REQUEST['post_id']);
	$this->request->data['Allpost']['user_id']=$user_id;
	$this->request->data['Allpost']['type']=1;
	$this->Allpost->create();
	
	$this->request->data=Sanitize::clean($this->request->data);

	try{
		if($this->Allpost->save($this->request->data)){
			$response['success'] = TRUE;
			$allpost_id= $this->Allpost->getLastInsertID();
			
			$this->Allpost->query("
				update posts as Post set sharecount = sharecount + 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
			
			/* Sharepost */
			$this->loadModel('Sharepost');
			$this->request->data['Sharepost']['post_id']= Sanitize::clean($_REQUEST['post_id']);
			$this->request->data['Sharepost']['user_id']=$user_id;
			$this->Sharepost->create();
			
			$this->request->data=Sanitize::clean($this->request->data);

			try{
				if($this->Sharepost->save($this->request->data)){
					$response['success'] = TRUE;
					$sharepost_id= $this->Sharepost->getLastInsertID();
					/* add notification */
					if($_REQUEST['post_user_id']!=$user_id){
						$this->request->data = array();
						$this->loadModel('Notification');
						$this->Notification->recursive = -1;
						$this->request->data['Notification']['notification_id']= Sanitize::clean($_REQUEST['post_id']);
						$this->request->data['Notification']['from_user_id']= $user_id;
						$this->request->data['Notification']['to_user_id']= $_REQUEST['post_user_id'];
						$this->request->data['Notification']['type']= 0;
						$this->request->data['Notification']['notification_type']= 2;
						$this->request->data=Sanitize::clean($this->request->data);
						$this->Notification->create();
						$this->Notification->save($this->request->data);
					}
				}
			} catch (Exception $e) {
				$response['success'] = FALSE;
			}
		}
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	
	$response['message'] = $response['success'] ? __('share_post_success') : __('share_post_notsuccess');
	
	$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
 }

/**
* 
* 
*/
function unshare(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	
	$options['conditions'] = array(
		'Allpost.user_id'=>$User_Info['id'] ,
		'Allpost.post_id'=> $_REQUEST['post_id'] ,
		'Allpost.type'=> 1
	);
	$count = $this->Allpost->find('count',$options);
	
	if($count<=0){
		$response['message']=__('not_find_share');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
		return;
	}

	try{
		$this->Allpost->deleteAll(array('Allpost.post_id' => $_REQUEST['post_id'],'Allpost.user_id'=>$User_Info['id'],'Allpost.type'=> 1), false);
		
		/* Sharepost */
		$this->loadModel('Sharepost');
		$this->Sharepost->deleteAll(array('Sharepost.user_id'=>$User_Info['id'],'Sharepost.post_id' => $_REQUEST['post_id']), false);
		
		$response['success'] = TRUE;
		$this->Allpost->query("
				update posts as Post set sharecount = sharecount - 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	  
	$response['message'] = $response['success'] ? __('unshare_post_success') : __('unshare_post_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
	
 }
 
 function app_unshare($user_id){	
	$response['success'] = false;
	$response['message'] = null;
	
	$options['conditions'] = array(
		'Allpost.user_id'=>$user_id ,
		'Allpost.post_id'=> $_REQUEST['post_id'] ,
		'Allpost.type'=> 1
	);
	$count = $this->Allpost->find('count',$options);
	
	if($count<=0){
		$response['message']=__('not_find_share');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/AllPosts/Ajax/ajax_result', 'ajax');
		return;
	}

	try{
		$this->Allpost->deleteAll(array('Allpost.post_id' => $_REQUEST['post_id'],'Allpost.user_id'=>$user_id,'Allpost.type'=>1), false);
		/* Sharepost */
		$this->loadModel('Sharepost');
		$this->Sharepost->deleteAll(array('Sharepost.user_id'=>$user_id,'Sharepost.post_id' => $_REQUEST['post_id']), false);
		
		$response['success'] = TRUE;
		$this->Allpost->query("
				update posts as Post set sharecount = sharecount - 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	  
	$response['message'] = $response['success'] ? __('unshare_post_success') : __('unshare_post_notsuccess');
	
	$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
 }
 
 
 
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function _send_email($user_id)
 {
 	$User_Info= $this->Session->read('User_Info');
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;
	$options['fields'] = array(
			'User.id',
			'User.name',
			'User.email',
			'User.image',
			'User.user_name'
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
			'User.user_name'
		   );
	$options['conditions'] = array(
	'User.id '=> $User_Info['id']
	);	   
	$from_user_info= $user->find('first',$options);
	
	
	$email = new CakeEmail('smtp');
	$email->from(array(__Madaner_Email => __Email_Name));
	$email->to($to_user_info['User']['email']);
	$email->subject(__('sharing_post'));
	$email->sendAs='html';//html
	$email->template('sharing_sendemail','sendemail_layout');
	$email->viewVars(
		array('from_name'=>$from_user_info['User']['name'],'from_user_name'=>$from_user_info['User']['user_name'],'to_name'=>$to_user_info['User']['name'],'email'=>$to_user_info['User']['email'],'name'=>$to_user_info['User']['name'])
	);
	    if($email->send())
		 {
				return true;
		 }
		 else  return false;
 }
 
 
 
 
		
}

