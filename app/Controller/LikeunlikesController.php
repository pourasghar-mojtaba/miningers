<?php
App::uses('AppController', 'Controller');

App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class LikeunlikesController  extends AppController {

    var $name = 'Likeunlikes';

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('app_like','app_unlike');
}

var $paginate = array('User'=>array(
	    'limit' => 15,
	    'order' => array(
	         'User.id' => 'asc'
	     )
	),
	
);

	
function like(){
 	
	$response['success'] = false;
	$response['message'] = null;
	$response['like'] = 0;
	$response['unlike'] = 0;
	
	$User_Info= $this->Session->read('User_Info');
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$User_Info['id'] ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=> 1
	);
	$count = $this->Likeunlike->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeat_like');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/LikeUnLikes/Ajax/ajax_result', 'ajax');
		return;
	}
	
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$User_Info['id'] ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=> -1
	);
	$count = $this->Likeunlike->find('count',$options);
	$info = $this->Likeunlike->find('first',$options);
	if($count>0){
		
		$ret= $this->Likeunlike->updateAll(
	    array( 'Likeunlike.status' => '1' ),   //fields to update
	    array( 'Likeunlike.user_id' =>$User_Info['id'] , 'Likeunlike.post_id' =>$_REQUEST['post_id'] )  //condition
	  );
	  
	  if($ret){
	  	try{
	  	    $this->_add_notification($info['Likeunlike']['id'],$User_Info['id'],$_REQUEST['post_user_id'],1);
			/*$this->request->data['LikeunlikeNotification']['likeunlike_id']= $info['Likeunlike']['id'];
			$this->request->data['LikeunlikeNotification']['from_user_id']= $User_Info['id'];
			$this->request->data['LikeunlikeNotification']['to_user_id']= $_REQUEST['post_user_id'];
			$this->request->data['LikeunlikeNotification']['type']=1;
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Likeunlike->LikeunlikeNotification->save($this->request->data);*/
		} catch (Exception $e) {
			$response['message']=$e->getMessage();
		}
	  	$response['success'] = TRUE;
		$response['like']=1;
		$response['unlike']=-1;
	  }else 
	  {
	  	$response['success'] = FALSE;
		$response['like']=0;
		$response['unlike']=0;
	  }
	  
		
	}
	else
	{
		$this->request->data['Likeunlike']['post_id']= $_REQUEST['post_id'];
		$this->request->data['Likeunlike']['status']=1;
		$this->request->data['Likeunlike']['user_id']=$User_Info['id'];
		$this->Likeunlike->create();
		
		$this->request->data=Sanitize::clean($this->request->data);
	
		try{
			if($this->Likeunlike->save($this->request->data)){
				$likeunlike_id= $this->Likeunlike->getLastInsertID();
				$this->_add_notification($likeunlike_id,$User_Info['id'],$_REQUEST['post_user_id'],1);
				$response['success'] = TRUE;
				$response['like']=1;
				$response['unlike']=0;
			}
			 
			
		} catch (Exception $e) {
			$response['success'] = FALSE;
			$response['like']=0;
			$response['unlike']=0;
		}
	
	}

	  
	$response['message'] = $response['success'] ? '' : __('add_like_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/LikeUnLikes/Ajax/ajax_result', 'ajax');
	
 }
 
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_like($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
	$response['like'] = 0;
	$response['unlike'] = 0;
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$user_id ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=> 1
	);
	$count = $this->Likeunlike->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeat_like');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/LikeUnLikes/Ajax/ajax_result', 'ajax');
		return;
	}
	
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$user_id ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=> -1
	);
	$count = $this->Likeunlike->find('count',$options);
	$info = $this->Likeunlike->find('first',$options);
	if($count>0){
		
		$ret= $this->Likeunlike->updateAll(
	    array( 'Likeunlike.status' => '1' ),   //fields to update
	    array( 'Likeunlike.user_id' =>$user_id , 'Likeunlike.post_id' =>$_REQUEST['post_id'] )  //condition
	  );
	  
	  if($ret){
	  	try{
	  	    $this->_add_notification($info['Likeunlike']['id'],$user_id,$_REQUEST['post_user_id'],1);
		} catch (Exception $e) {
			$response['message']=$e->getMessage();
		}
	  	$response['success'] = TRUE;
		$response['like']=1;
		$response['unlike']=-1;
	  }else 
	  {
	  	$response['success'] = FALSE;
		$response['like']=0;
		$response['unlike']=0;
	  }
	  
		
	}
	else
	{
		$this->request->data['Likeunlike']['post_id']= $_REQUEST['post_id'];
		$this->request->data['Likeunlike']['status']=1;
		$this->request->data['Likeunlike']['user_id']=$user_id;
		$this->Likeunlike->create();
		
		$this->request->data=Sanitize::clean($this->request->data);
	
		try{
			if($this->Likeunlike->save($this->request->data)){
				$likeunlike_id= $this->Likeunlike->getLastInsertID();
				$this->_add_notification($likeunlike_id,$user_id,$_REQUEST['post_user_id'],1);
				$response['success'] = TRUE;
				$response['like']=1;
				$response['unlike']=0;
			}
			 
			
		} catch (Exception $e) {
			$response['success'] = FALSE;
			$response['like']=0;
			$response['unlike']=0;
		}
	
	}

	  
	$response['message'] = $response['success'] ? '' : __('add_like_notsuccess');
	
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 }

/**
* 
* 
*/
function unlike(){
 	
	$response['success'] = false;
	$response['message'] = null;
	$response['like'] = 0;
	$response['unlike'] = 0;
	
	$User_Info= $this->Session->read('User_Info');
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$User_Info['id'] ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=> -1
	);
	$count = $this->Likeunlike->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeat_ullike');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/LikeUnLikes/Ajax/ajax_result', 'ajax');
		return;
	}
	
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$User_Info['id'] ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=>  1
	);
	$count = $this->Likeunlike->find('count',$options);
	$info = $this->Likeunlike->find('first',$options);
	if($count>0){
		
		$ret= $this->Likeunlike->updateAll(
	    array( 'Likeunlike.status' => '-1' ),   //fields to update
	    array( 'Likeunlike.user_id' =>$User_Info['id'] , 'Likeunlike.post_id' =>$_REQUEST['post_id'] )  //condition
	  );
	  
	  if($ret){
	  	$this->_add_notification($info['Likeunlike']['id'],$User_Info['id'],$_REQUEST['post_user_id'],0);
	  	$response['success'] = TRUE;
		$response['like']=-1;
		$response['unlike']=1;
	  }else 
	  {
	  	$response['success'] = FALSE;
		$response['like']=0;
		$response['unlike']=0;
	  }
	  
		
	}
	else
	{
		$this->request->data['Likeunlike']['post_id']= $_REQUEST['post_id'];
		$this->request->data['Likeunlike']['status']=-1;
		$this->request->data['Likeunlike']['user_id']=$User_Info['id'];
		$this->Likeunlike->create();
		
		$this->request->data=Sanitize::clean($this->request->data);
	
		try{
			if($this->Likeunlike->save($this->request->data))
			{
				$response['success'] = TRUE;
				$response['like']=0;
				$response['unlike']=1;
				$likeunlike_id= $this->Likeunlike->getLastInsertID();
				$this->_add_notification($likeunlike_id,$User_Info['id'],$_REQUEST['post_user_id'],0);
			}
			
		} catch (Exception $e) {
			$response['success'] = FALSE;
			$response['like']=0;
			$response['unlike']=0;
		}
	
	}

	  
	$response['message'] = $response['success'] ? '' : __('add_like_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/LikeUnLikes/Ajax/ajax_result', 'ajax');
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_unlike($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
	$response['like'] = 0;
	$response['unlike'] = 0;
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$user_id ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=> -1
	);
	$count = $this->Likeunlike->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeat_ullike');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/LikeUnLikes/Ajax/ajax_result', 'ajax');
		return;
	}
	
	
	$options['conditions'] = array(
		'Likeunlike.user_id'=>$user_id ,
		'Likeunlike.post_id'=> $_REQUEST['post_id'] ,
		'Likeunlike.status'=>  1
	);
	$count = $this->Likeunlike->find('count',$options);
	$info = $this->Likeunlike->find('first',$options);
	if($count>0){
		
		$ret= $this->Likeunlike->updateAll(
	    array( 'Likeunlike.status' => '-1' ),   //fields to update
	    array( 'Likeunlike.user_id' =>$user_id , 'Likeunlike.post_id' =>$_REQUEST['post_id'] )  //condition
	  );
	  
	  if($ret){
	  	$this->_add_notification($info['Likeunlike']['id'],$user_id,$_REQUEST['post_user_id'],0);
	  	$response['success'] = TRUE;
		$response['like']=-1;
		$response['unlike']=1;
	  }else 
	  {
	  	$response['success'] = FALSE;
		$response['like']=0;
		$response['unlike']=0;
	  }
	  
		
	}
	else
	{
		$this->request->data['Likeunlike']['post_id']= $_REQUEST['post_id'];
		$this->request->data['Likeunlike']['status']=-1;
		$this->request->data['Likeunlike']['user_id']=$user_id;
		$this->Likeunlike->create();
		
		$this->request->data=Sanitize::clean($this->request->data);
	
		try{
			if($this->Likeunlike->save($this->request->data))
			{
				$response['success'] = TRUE;
				$response['like']=0;
				$response['unlike']=1;
				$likeunlike_id= $this->Likeunlike->getLastInsertID();
				$this->_add_notification($likeunlike_id,$user_id,$_REQUEST['post_user_id'],0);
			}
			
		} catch (Exception $e) {
			$response['success'] = FALSE;
			$response['like']=0;
			$response['unlike']=0;
		}
	
	}

	  
	$response['message'] = $response['success'] ? '' : __('add_like_notsuccess');
	
	$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 }
	

 function _add_notification($likeUnLike_id,$from_user_id,$to_user_id,$type)
{
	if($from_user_id!=$to_user_id){
		$this->request->data['LikeunlikeNotification']['likeunlike_id']= $likeUnLike_id;
		$this->request->data['LikeunlikeNotification']['from_user_id']= $from_user_id;
		$this->request->data['LikeunlikeNotification']['to_user_id']= $to_user_id;
		$this->request->data['LikeunlikeNotification']['type']=$type;
		$this->request->data=Sanitize::clean($this->request->data);
		$this->Likeunlike->LikeunlikeNotification->save($this->request->data);
	}
}	
	
	
		
}
