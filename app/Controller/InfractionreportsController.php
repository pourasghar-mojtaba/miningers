<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class InfractionreportsController extends AppController {

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow(array('register'));  
}
   var $name = 'Infractionreports';
/**
* 
* 
*/
function send_infraction_report()
	{
		$action=$_REQUEST['action'];
		$response['success'] = false;
		$response['message'] = null;
		$User_Info= $this->Session->read('User_Info');
		switch($action){
			case 'load_page':
				$id= $_REQUEST['id'];
				$this->set(array('id'=>$id));
				$this->render('/Elements/InfractionReports/Ajax/send_infraction_report','ajax');
				break;
			case 'send':
			   $to_user_id=$_POST['id'];
			   
			   $count_report = $this->Infractionreport->find('count', array('conditions' => array('Infractionreport.to_user_id' =>$to_user_id,'Infractionreport.from_user_id'=>$User_Info['id'])));
	
				if($count_report>0){
					$response['success'] = FALSE;
					$response['message'] = __('you_send_infraction_report');
					$this->set('ajaxData',  json_encode($response));
					$this->render('/Elements/InfractionReports/Ajax/ajax_result', 'ajax');
					return;
				}
			   
			   $this->request->data['Infractionreport']['from_user_id']= $User_Info['id'];
			   $this->request->data['Infractionreport']['to_user_id']= $to_user_id;
			   $this->request->data['Infractionreport']['report']= $_POST['report'];
			   $this->request->data=Sanitize::clean($this->request->data);
			   $this->Infractionreport->create();
				try{
					$this->Infractionreport->save($this->request->data);
					$response['success'] = TRUE;
				} catch (Exception $e) {
					$response['success'] = FALSE;
				}
			   
		       $response['message'] = $response['success'] ? __('send_infraction_report_successfully') : __('send_infraction_report_not_successfully'); 
		       $this->set('ajaxData', json_encode($response));
			   $this->render('/Elements/InfractionReports/Ajax/ajax_result','ajax');
			  break;	
		}
		
	}
	
	
function app_send_infraction_report($user_id)
{
	$response['success'] = false;
	$response['message'] = null;
	 
   $to_user_id=$_POST['id'];
   
   $count_report = $this->Infractionreport->find('count', array('conditions' => array('Infractionreport.to_user_id' =>$to_user_id,'Infractionreport.from_user_id'=>$user_id)));

	if($count_report>0){
		$response['success'] = FALSE;
		$response['message'] = __('you_send_infraction_report');
		$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
		return;
	}
   
   $this->request->data['Infractionreport']['from_user_id']= $user_id;
   $this->request->data['Infractionreport']['to_user_id']= $to_user_id;
   $this->request->data['Infractionreport']['report']= $_POST['report'];
   $this->request->data=Sanitize::clean($this->request->data);
   $this->Infractionreport->create();
	try{
		$this->Infractionreport->save($this->request->data);
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
		$this->Infractionreport->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		if(isset($this->request->data['Infractionreport']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Infractionreport.report LIKE' => '%'.$this->request->data['Infractionreport']['search'].'%'),
				'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'User.id = `Infractionreport`.to_user_id ',
					)
				   ) , 
				   array('table' => 'users',
					'alias' => 'FUser',
					'type' => 'LEFT',
					'conditions' => array(
					'FUser.id = `Infractionreport`.from_user_id ',
					)
				   )
				),
				'fields'=>array(
				'User.name',
				'FUser.name',
				'Infractionreport.id',
				'Infractionreport.report',
				'Infractionreport.created'
				),
				'limit' => $limit,
				'order' => array(
					'Infractionreport.id' => 'desc'
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
					'User.id = `Infractionreport`.to_user_id ',
					)
				   ), 
				   array('table' => 'users',
					'alias' => 'FUser',
					'type' => 'LEFT',
					'conditions' => array(
					'FUser.id = `Infractionreport`.from_user_id ',
					)
				   )
				),
				'fields'=>array(
				'User.name',
				'FUser.name',
				'Infractionreport.id',
				'Infractionreport.report',
				'Infractionreport.created'
				),
				'limit' => $limit,
				'order' => array(
					'Infractionreport.id' => 'desc'
				)
			);
		}		
		$infraction_reports = $this->paginate('Infractionreport');
		$this->set(compact('infraction_reports'));
	}
  

/**
* 
* @param undefined $id
* 
*/ 
 function admin_edit($id = null)
	{
		$this->Infractionreport->id = $id;
		if(!$this->Infractionreport->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_post'));
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			 $error=FALSE;
			 $result= $this->Infractionreport->findById($id);
			 if($error==FALSE){
			   $data=Sanitize::clean($this->request->data);
			   $file = $data['Infractionreport']['newpost_image'];
			 	  
			   if($file['size']>0)
				 {
					
					$filename=$result['Infractionreport']['image'];
					@unlink(__POST_IMAGE_PATH."/".$filename);
					@unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename); 
						
					$output=$this->_picture();
					if(!$output['error']) $this->request->data['Infractionreport']['image']=$output['filename'];
					else $this->request->data['Infractionreport']['image']='';
				 }
				 else $this->request->data['Infractionreport']['image']=$this->request->data['Infractionreport']['old_image'];
				 
			   if($this->Infractionreport->save($this->request->data))
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
	$this->Infractionreport->recursive = -1;
   
	   if($this->Infractionreport->delete($id)){
		   $this->Session->setFlash(__('the_infraction_report_deleted'), 'admin_success');
		   $this->redirect(array('action' => 'index'));
	   }
	   else $this->Session->setFlash(__('the_infraction_report_not_deleted'),'admin_error');
}
 
 
 
}




