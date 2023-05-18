<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class InfractionreportpostsController extends AppController {


   var $name = 'Infractionreportposts';
   
   public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('app_send_infraction_report_post');
}
   
/**
* 
* 
*/
function send_infraction_report_post()
{
	$action=$_REQUEST['action'];
	$response['success'] = false;
	$response['message'] = null;
	$User_Info= $this->Session->read('User_Info');
	switch($action){
		case 'load_page':
			$id= $_REQUEST['id'];
			$this->set(array('id'=>$id));
			$this->render('/Elements/InfractionReportPosts/Ajax/send_infraction_report_post','ajax');
			break;
		case 'send':
		   $post_id=$_REQUEST['id'];
		   
		   $count_report = $this->Infractionreportpost->find('count', array('conditions' => array('Infractionreportpost.post_id' =>$post_id,'Infractionreportpost.user_id'=>$User_Info['id'])));

			if($count_report>0){
				$response['success'] = FALSE;
				$response['message'] = __('you_send_infraction_report');
				$this->set('ajaxData',  json_encode($response));
				$this->render('/Elements/InfractionReportPosts/Ajax/ajax_result', 'ajax');
				return;
			}
		   
		   $this->request->data['Infractionreportpost']['user_id']= $User_Info['id'];
		   $this->request->data['Infractionreportpost']['post_id']= $post_id;
		   $this->request->data['Infractionreportpost']['report']= $_REQUEST['report'];
		   $this->request->data=Sanitize::clean($this->request->data);
		   $this->Infractionreportpost->create();
			try{
				$this->Infractionreportpost->save($this->request->data);
				$response['success'] = TRUE;
			} catch (Exception $e) {
				$response['success'] = FALSE;
			}
		   
	       $response['message'] = $response['success'] ? __('send_infraction_report_successfully') : __('send_infraction_report_not_successfully'); 
	       $this->set('ajaxData', json_encode($response));
		   $this->render('/Elements/InfractionReportPosts/Ajax/ajax_result','ajax');
		  break;	
	}
	
}
/**
* 
* @param undefined $user_id
* 
*/
function app_send_infraction_report_post($user_id)
{
	$action=$_POST['action'];
	$response['success'] = false;
	$response['message'] = null;

   $post_id=$_POST['id'];
   
   $count_report = $this->Infractionreportpost->find('count', array('conditions' => array('Infractionreportpost.post_id' =>$post_id,'Infractionreportpost.user_id'=>$user_id)));

	if($count_report>0){
		$response['success'] = FALSE;
		$response['message'] = __('you_send_infraction_report');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/InfractionReportPosts/Ajax/ajax_result', 'ajax');
		return;
	}
   
   $this->request->data['Infractionreportpost']['user_id']= $user_id;
   $this->request->data['Infractionreportpost']['post_id']= $post_id;
   $this->request->data['Infractionreportpost']['report']= $_POST['report'];
   $this->request->data=Sanitize::clean($this->request->data);
   $this->Infractionreportpost->create();
	try{
		$this->Infractionreportpost->save($this->request->data);
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
   
   $response['message'] = $response['success'] ? __('send_infraction_report_successfully') : __('send_infraction_report_not_successfully'); 
   
   $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
}


   function admin_index()
	{
		$this->Infractionreportpost->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		if(isset($this->request->data['Infractionreportpost']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Infractionreportpost.report LIKE' => '%'.$this->request->data['Infractionreportpost']['search'].'%'),
				'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'User.id = `Infractionreportpost`.user_id ',
					)
				   ) , 
				   array('table' => 'posts',
					'alias' => 'Post',
					'type' => 'LEFT',
					'conditions' => array(
					'Post.id = `Infractionreportpost`.post_id ',
					)
				   )
				),
				'fields'=>array(
				'User.name',
				'Post.body',
				'Infractionreportpost.id',
				'Infractionreportpost.report',
				'Infractionreportpost.created'
				),
				'limit' => $limit,
				'order' => array(
					'Infractionreportpost.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'User.id = `Infractionreportpost`.user_id ',
					)
				   ), 
				   array('table' => 'posts',
					'alias' => 'Post',
					'type' => 'LEFT',
					'conditions' => array(
					'Post.id = `Infractionreportpost`.post_id ',
					)
				   )
				),
				'fields'=>array(
				'User.name',
				'Post.body',
				'Infractionreportpost.id',
				'Infractionreportpost.report',
				'Infractionreportpost.created'
				),
				'limit' => $limit,
				'order' => array(
					'Infractionreportpost.id' => 'desc'
				)
			);
		}		
		$infraction_reports = $this->paginate('Infractionreportpost');
		$this->set(compact('infraction_reports'));
	}
  

/**
* 
* @param undefined $id
* 
*/ 
 function admin_edit($id = null)
	{
		$this->Infractionreportpost->id = $id;
		if(!$this->Infractionreportpost->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_post'));
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			 $error=FALSE;
			 $result= $this->Infractionreportpost->findById($id);
			 if($error==FALSE){
			   $data=Sanitize::clean($this->request->data);
			   $file = $data['Infractionreportpost']['newpost_image'];
			 	  
			   if($file['size']>0)
				 {
					
					$filename=$result['Infractionreportpost']['image'];
					@unlink(__POST_IMAGE_PATH."/".$filename);
					@unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename); 
						
					$output=$this->_picture();
					if(!$output['error']) $this->request->data['Infractionreportpost']['image']=$output['filename'];
					else $this->request->data['Infractionreportpost']['image']='';
				 }
				 else $this->request->data['Infractionreportpost']['image']=$this->request->data['Infractionreportpost']['old_image'];
				 
			   if($this->Infractionreportpost->save($this->request->data))
				{
					$this->Session->setFlash(__('the_post_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));					
					
				}
				else
				{
					if($file['size']>0)
				    {
						@unlink(__POST_IMAGE_PATH."/".$output['filename']);
						@unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$output['filename']);
				 	}
					$this->Session->setFlash(__('the_post_not_saved'),'admin_error');
				}	
			}	 
		}
		$this->_set_post($id);
	}
/**
* 
* @param undefined $id
* 
*/
function admin_delete($id = null)
{
	$this->Infractionreportpost->recursive = -1;
   
	   if($this->Infractionreportpost->delete($id)){
		   $this->Session->setFlash(__('the_infraction_report_deleted'), 'admin_success');
		   $this->redirect(array('action' => 'index'));
	   }
	   else $this->Session->setFlash(__('the_infraction_report_not_deleted'),'admin_error');
}
 
 
 
}




