<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');


class BlogcommentsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
 
 public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('refresh_comment');
}

 
 

function add_comment(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	
	$this->request->data['Blogcomment']['comment']= $_REQUEST['commant'];
	$this->request->data['Blogcomment']['blog_id']=$_REQUEST['blog_id'];
	$this->request->data['Blogcomment']['user_id']=$User_Info['id'];
	
	$this->request->data=Sanitize::clean($this->request->data);
	
	$commant= $this->request->data['Blogcomment']['comment'];
	$blog_id= $this->request->data['Blogcomment']['blog_id'];
	
	$this->Blogcomment->create();
	try{
		if($this->Blogcomment->save($this->request->data)){
			
			$this->request->data = array();
			$this->request->data['Post']['blog_id'] = $blog_id;
			$this->request->data['Post']['blogcomment_id'] = $this->Blogcomment->getLastInsertID();
			$this->request->data['Post']['url_content'] = $commant;
			$this->request->data['Post']['url_image'] = '';	
			$this->request->data['Post']['user_id']= $User_Info['id'];	
			$this->request->data['Post']['status']= 0;	
			$this->request->data['Post']['url'] = __SITE_URL.'blogs/view/'.$blog_id;
							
				if($this->Blogcomment->Blog->Post->save($this->request->data))
				{
						$post_id= $this->Blogcomment->Blog->Post->getLastInsertID();
						$this->Blogcomment->Blog->Post->Allpost->recursive=-1;
						$this->request->data = array();			
						$this->request->data['Allpost']['post_id']= $post_id;
						$this->request->data['Allpost']['user_id']= $User_Info['id'];
						$this->request->data['Allpost']['type']=0;
						$this->request->data['Allpost']['created']=date('Y-m-d H:i:s');
						$this->request->data=Sanitize::clean($this->request->data);
				        $this->Blogcomment->Blog->Post->Allpost->create();
						$this->Blogcomment->Blog->Post->Allpost->save($this->request->data);			
						
					$response['success'] = TRUE;
				}
		}
		
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? __('add_comment_success') : __('add_comment_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/BlogComments/Ajax/ajax_result', 'ajax');
	
 }
 

function refresh_comment($blog_id){
 	
	$this->Blogcomment->recursive = -1;
	
	$blog_id= Sanitize::clean($blog_id);
	$User_Info= $this->Session->read('User_Info');
	$options['fields'] = array(
			'Blogcomment.id',
			'Blogcomment.comment',
			'Blogcomment.created',
			'User.id',
			'User.name',
			'User.user_name',
			'User.sex' ,
			'User.image'
		   );
		   
	$options['joins'] = array(
		array('table' => 'users',
			'alias' => 'User',
			'type' => 'INNER',
			'conditions' => array(
			'Blogcomment.user_id = `User`.id ',
			) 				
		)
		);
 			   
	$options['conditions'] = array(	
			'Blogcomment.blog_id ' => $blog_id
	);	   
	$options['order'] = array(
		'Blogcomment.id'=>'desc'
	);
	
	
	$response = $this->Blogcomment->find('all',$options); 
	
	$this->set('comments', $response);
	
	$this->render('/Elements/BlogComments/Ajax/refresh_comment', 'ajax');	
	
 } 
 
public function delete_commentblog() {
   $response['success'] = false;
   $response['message'] = null;
   
   $blogcomment_id=Sanitize::clean($_REQUEST['blogcomment_id']);
    
   $this->Blogcomment->recursive = -1;
   
   try{
   		if($this->Blogcomment->delete($blogcomment_id)){
			$response['success'] =  TRUE;
		}				 
   } catch (Exception $e) {
   		$response['success'] =  FALSE;
   } 
      
   $response['message'] = $response['success'] ? '' : __('blogcomment_not_deleted');
   $this->set('ajaxData',  json_encode($response));
   $this->render('/Elements/BlogComments/Ajax/ajax_result', 'ajax');  
} 
 

}
