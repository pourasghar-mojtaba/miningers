<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class LearnobjectusersController extends AppController {

	 var $name = 'Learnobjectusers';
 /**
 * 
 * @param undefined $user_id
 * @param undefined $learn_object_id
 * 
*/

 function view_learn($user_id,$learn_object_id)
 { 
	
    $this->Learnobjectuser->recursive = -1;
	$options['conditions'] = array(
		'Learnobjectuser.learn_object_id'=>$learn_object_id ,
		'Learnobjectuser.user_id'=>$user
	);
	$count = $this->Learnobjectuser->find('count',$options);
	if($count<=0){
		return TRUE;
	}
	return FALSE;
 }
 /**
 * 
 * 
*/
 function show_learn() {
 	//$this->Learnobjectuser->recursive = -1;
	
	$response['success'] = false;
	$response['message'] = null;
	$response['show_tour'] = TRUE;
	
	$this->request->data['Learnobjectuser']['user_id']=$_REQUEST['user_id'];
	$this->request->data['Learnobjectuser']['learn_object_id']=$_REQUEST['learn_object_id'];
	$this->request->data['Learnobjectuser']['parent_id']=$_REQUEST['parent_id'];
	$this->request->data['Learnobjectuser']['object_created']=$_REQUEST['object_created'];
	$this->request->data['Learnobjectuser']['user_created']=$_REQUEST['user_created'];	
	
	
	$this->request->data=Sanitize::clean($this->request->data);
	
	$this->loadModel('Learnobjectuser');
	
	$this->Learnobjectuser->create();
	if($this->Learnobjectuser->save($this->request->data))
	{
	    $response['success'] =TRUE;
		if($this->request->data['Learnobjectuser']['parent_id']!=0){
			if($this->request->data['Learnobjectuser']['user_created'] >$this->request->data['Learnobjectuser']['object_created'] )
			{
				$options['conditions'] = array(
					'Learnobjectuser.learn_object_id'=>$this->request->data['Learnobjectuser']['parent_id'] ,
					'Learnobjectuser.user_id'=>$this->request->data['Learnobjectuser']['user_id'] 
				);
				$count = $this->Learnobjectuser->find('count',$options);
				if($count>0){
					$response['show_tour']=FALSE;
				}
			}
		}
		 $ObjectInfo = array();
		 $LearnObjectInfos= $this->Session->read('LearnObjectInfo');
         
		if(!empty($LearnObjectInfos)){
		  	foreach ($LearnObjectInfos as $key=>$LearnObjectInfo  )
			{
				if($LearnObjectInfo['learn_object_id']==$this->request->data['Learnobjectuser']['learn_object_id'])
				{
					$ObjectInfo[]=array(
								'learn_object_id' => $LearnObjectInfo['learn_object_id'] ,
								'count' => 1 ,
								'parent_id' => $LearnObjectInfo['parent_id'] ,
								'object_created' => $LearnObjectInfo['created'], 
								'user_created' => $LearnObjectInfo['user_created']
							);				
				}else
				{
					$ObjectInfo[]=array(
									'learn_object_id' => $LearnObjectInfo['learn_object_id'] ,
									'count' => $LearnObjectInfo['count'] ,
									'parent_id' => $LearnObjectInfo['parent_id'] ,
									'object_created' => $LearnObjectInfo['created'], 
									'user_created' => $LearnObjectInfo['user_created']
								);
				}
			}
			if(!empty($ObjectInfo)){
				$this->Session->write('LearnObjectInfo',$ObjectInfo);
			}
		  }
	}
	else
	{
	    $response['success'] =FALSE;
	}
	
	$this->set('ajaxData', json_encode($response));
	$this->render('/Elements/LearnObjectUsers/Ajax/ajax_result', 'ajax');
	
 }
 
 
}
