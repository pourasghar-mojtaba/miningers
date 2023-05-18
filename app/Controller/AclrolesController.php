<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');


class AclrolesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
 
 

function admin_active_permission(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	
	$this->request->data['Aclrole']['role_id']= $_REQUEST['role_id'];
	$this->request->data['Aclrole']['aclitem_id']=$_REQUEST['aclitem_id'];
	
	$this->request->data=Sanitize::clean($this->request->data);
	
	$this->Aclrole->create();
	try{
		$this->Aclrole->save($this->request->data);
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ?  : __('add_permission_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Aclroles/Ajax/ajax_result', 'ajax');
	
 }
 
 
 function admin_inactive_permission(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	try{
		$this->Aclrole->deleteAll(array('Aclrole.role_id' =>$_REQUEST['role_id'] , 'Aclrole.aclitem_id' => $_REQUEST['aclitem_id']), false);
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ?  : __('delete_permission_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Aclroles/Ajax/ajax_result', 'ajax');
	
 }

 
 
 
 
 
 
 
 
 
}
