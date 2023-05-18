<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');


class CommentsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
 
 

function add_comment(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	
	$this->request->data['Comment']['post_id']= $_REQUEST['post_id'];
	$this->request->data['Comment']['body']=$_REQUEST['body'];
	$this->request->data['Comment']['user_id']=$User_Info['id'];
	
	$this->request->data=Sanitize::clean($this->request->data);
	/* check privacy */
	if($User_Info['id']!=$_REQUEST['post_user_id'])
	{
		Controller::loadModel('Privacy');
		$ret=$this->Privacy->check_privacy($_REQUEST['post_user_id'],$User_Info['id'],'commenting');
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
		    $this->render('/Elements/Comments/Ajax/ajax_result', 'ajax');
			return;
		}
	}
	/* check privacy */
	
	$this->Comment->create();
	try{
		$this->Comment->save($this->request->data);
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? __('add_comment_success') : __('add_comment_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Comments/Ajax/ajax_result', 'ajax');
	
 }
 

function refresh_comment(){
 	
	$this->Comment->recursive = -1;
	$id=$_REQUEST['post_id'];
	$User_Info= $this->Session->read('User_Info');
	$options['fields'] = array(
			'Comment.id',
			'Comment.body',
			'User.id',
			'User.name',
			'User.sex' ,
			'User.image'
		   );
		   
	$options['joins'] = array(
		array('table' => 'posts',
			'alias' => 'Post',
			'type' => 'Inner',
			'conditions' => array(
			'Comment.post_id = `Post`.id',
			)),
		array('table' => 'users',
			'alias' => 'User',
			'type' => 'INNER',
			'conditions' => array(
			'Comment.user_id = `User`.id ',
			) 	
			
		)
		);
 			   
	$options['conditions'] = array(	
			'Post.id ' => $id
	);	   
	$options['order'] = array(
		'Comment.id'=>'asc'
	);
	
	
	$response = $this->Comment->find('all',$options); 
	
	$this->set('comments', $response);
	
	$this->render('/Elements/Comments/Ajax/post_comments', 'ajax');	
	
 } 
 
 
 
 
 
 
 
 
 
}
