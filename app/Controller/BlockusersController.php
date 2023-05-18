<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class BlockusersController extends AppController {

   var $name = 'Blockusers';	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('app_block','app_unblock');
}


function block(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
    $to_user_id=$_REQUEST['id'];
	
	$count_block = $this->Blockuser->find('count', array('conditions' => array('Blockuser.to_user_id' =>$to_user_id,'Blockuser.from_user_id'=>$User_Info['id'])));
	
	if($count_block>0){
		$response['success'] = FALSE;
		$response['message'] = __('block_user_exist');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/BlockUsers/Ajax/ajax_result', 'ajax');
		return;
	}
	
	
	$this->request->data['Blockuser']['from_user_id']= $User_Info['id'];
	$this->request->data['Blockuser']['to_user_id']= $to_user_id;
	
	$this->request->data=Sanitize::clean($this->request->data);
	
	$this->Blockuser->create();
	try{
		$this->Blockuser->save($this->request->data);
		$response['success'] = TRUE;
		
		$this->Blockuser->Follow->deleteAll(array('Follow.from_user_id' => $to_user_id,'Follow.to_user_id'=>$User_Info['id']), false);
		
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? __('block_success') : __('can_not_block');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/BlockUsers/Ajax/ajax_result', 'ajax');
	
 }
 
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_block($user_id){	
	$response['success'] = false;
	$response['message'] = null;
    $to_user_id=$_REQUEST['id'];
	$count_block = $this->Blockuser->find('count', array('conditions' => array('Blockuser.to_user_id' =>$to_user_id,'Blockuser.from_user_id'=>$user_id)));
	
	if($count_block>0){
		$response['success'] = FALSE;
		$response['message'] = __('block_user_exist');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/BlockUsers/Ajax/ajax_result', 'ajax');
		return;
	}	
	$this->request->data['Blockuser']['from_user_id']= $user_id;
	$this->request->data['Blockuser']['to_user_id']= $to_user_id;
	
	$this->request->data=Sanitize::clean($this->request->data);
	
	$this->Blockuser->create();
	try{
		$this->Blockuser->save($this->request->data);
		$response['success'] = TRUE;
		
		$this->Blockuser->Follow->deleteAll(array('Follow.from_user_id' => $to_user_id,'Follow.to_user_id'=>$user_id), false);
		
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? __('block_success') : __('can_not_block');	
	
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));	
 }
 
 
 /**
 * 
 */
 
 function unblock(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
    $to_user_id=$_REQUEST['id'];
	
	$this->request->data['Blockuser']['from_user_id']= $User_Info['id'];
	$this->request->data['Blockuser']['to_user_id']= $to_user_id;
	
	$this->request->data=Sanitize::clean($this->request->data);

	try{
		$this->Blockuser->deleteAll(array('Blockuser.from_user_id' => $User_Info['id'],'Blockuser.to_user_id'=>$to_user_id), false);
		$response['success'] = TRUE;
		
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? __('unblock_success') : __('can_not_unblock');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/BlockUsers/Ajax/ajax_result', 'ajax');
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_unblock($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
	
    $to_user_id=$_REQUEST['id'];
	
	$this->request->data['Blockuser']['from_user_id']= $user_id;
	$this->request->data['Blockuser']['to_user_id']= $to_user_id;
	
	$this->request->data=Sanitize::clean($this->request->data);

	try{
		$this->Blockuser->deleteAll(array('Blockuser.from_user_id' => $user_id,'Blockuser.to_user_id'=>$to_user_id), false);
		$response['success'] = TRUE;
		
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? __('unblock_success') : __('can_not_unblock');
	
	$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
}
