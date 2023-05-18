<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class ChatsController extends AppController {


public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow(array('app_send_message','app_message_box','app_delete_message','app_read_message','app_user_chat_search'));
}
/**
*
*
*/
function send_friend_message()
	{
		$action=$_REQUEST['action'];
		$response['success'] = false;
		$response['message'] = null;
		$User_Info= $this->Session->read('User_Info');
		switch($action){
			case 'load_page':
			    $is_multi = $_REQUEST['is_multi'];
				$name= $_REQUEST['name'];
				$id= $_REQUEST['id'];
				$this->set(array('is_multi'=>$is_multi,'name'=>$name,'id'=>$id));
				$this->render('/Elements/Chats/Ajax/send_friend_message','ajax');
				break;
			case 'send':
			   $ids=explode(',',$_REQUEST['id']);

			   if(count(array_unique($ids))<count($ids)){
			   		$response['success'] = FALSE;
					$response['message'] = __('not_repeated_user_to_send_message');
		      	    $this->set('ajaxData', json_encode($response));
			        $this->render('/Elements/Chats/Ajax/ajax_result','ajax');
					return;
			   }
			   /* check privacy */
					Controller::loadModel('Privacy');
			   /* check privacy */
			   $ids= array_unique($ids);
			   $data=array();
			   if(!empty($ids)){
			   	 foreach($ids as $id)
				 {
					$this->request->data['Chat']['message']= $_POST['message'];
					$this->request->data['Chat']['to_user_id']= $id;
					$this->request->data['Chat']['from_user_id']=$User_Info['id'];
					$this->request->data['Chat']['sent']=date();

					/* check privacy */
					if($User_Info['id']!=$id)
					{
						$ret=$this->Privacy->check_privacy($id,$User_Info['id'],'messaging');

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
						    $this->render('/Elements/Chats/Ajax/ajax_result', 'ajax');
							return;
						}
					}
					/* check privacy */

					array_push($data,$this->request->data);
				 }
			   }
			   $this->request->data=Sanitize::clean($this->request->data);

			   $this->Chat->create();
				try{
					$this->Chat->saveAll($data);
					$response['success'] = TRUE;
				} catch (Exception $e) {
					$response['success'] = FALSE;
				}

		       $response['message'] = $response['success'] ? __('send_message_successfully') : __('send_message_not_successfully');
		       $this->set('ajaxData', json_encode($response));
			   $this->render('/Elements/Chats/Ajax/ajax_result','ajax');
			  break;
		}

	}

function app_send_message($user_id)
{

	$response['success'] = false;
	$response['message'] = null;

   $ids=explode(',',$_REQUEST['id']);

   if(count(array_unique($ids))<count($ids)){
   		$response['success'] = FALSE;
		$response['message'] = __('not_repeated_user_to_send_message');
  	    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
		return;
   }
   /* check privacy */
		Controller::loadModel('Privacy');
   /* check privacy */
   $ids= array_unique($ids);
   $data=array();
   if(!empty($ids)){
   	 foreach($ids as $id)
	 {
		$this->request->data['Chat']['message']= $_POST['message'];
		$this->request->data['Chat']['to_user_id']= $id;
		$this->request->data['Chat']['from_user_id']=$user_id;
		$this->request->data['Chat']['sent']=date();

		/* check privacy */
		if($user_id!=$id)
		{
			$ret=$this->Privacy->check_privacy($id,$user_id,'messaging');

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
				$this->set(array(
				'response' => $response,
				'_serialize' => array('response')
				));
				return;
			}
		}
		/* check privacy */

		array_push($data,$this->request->data);
	 }
   }
   $this->request->data=Sanitize::clean($this->request->data);

   $this->Chat->create();
	try{
		$this->Chat->saveAll($data);
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}

   $response['message'] = $response['success'] ? __('send_message_successfully') : __('send_message_not_successfully');


	$this->set(array(
	'response' => $response,
	'_serialize' => array('response')
	));

}
 function send_one_message()
	{
		$response['success'] = false;
		$response['message'] = null;
		$User_Info= $this->Session->read('User_Info');

		$user_name=$_REQUEST['user_name'];
		$user_name= str_replace('@','',$user_name);

		$this->Chat->User->recursive = -1;
		$options['fields'] = array(
			'User.id',
		   );
		$options['conditions'] = array(
		'User.user_name'=> $user_name
		);
		$user= $this->Chat->User->find('first',$options);

		if(!isset($user['User']['id'])){
			$response['message'] =  __('send_message_not_successfully');
       		$this->set('ajaxData', json_encode($response));
	 		$this->render('/Elements/Chats/Ajax/ajax_result','ajax');
			return;
		}

	    $id=$user['User']['id'];

		$this->request->data['Chat']['message']= $_POST['message'];
		$this->request->data['Chat']['to_user_id']= $id;
		$this->request->data['Chat']['from_user_id']=$User_Info['id'];
		$this->request->data['Chat']['sent']=date();

		/* check privacy */
		Controller::loadModel('Privacy');
		if($User_Info['id']!=$id)
		{
			$ret=$this->Privacy->check_privacy($id,$User_Info['id'],'messaging');

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
			    $this->render('/Elements/Chats/Ajax/ajax_result', 'ajax');
				return;
			}
		}
		/* check privacy */


	   $this->request->data=Sanitize::clean($this->request->data);
	   // pr($this->request->data);exit();
	   $this->Chat->create();
		try{
			$this->Chat->save($this->request->data);
			$response['success'] = TRUE;
		} catch (Exception $e) {
			$response['success'] = FALSE;
		}

       $response['message'] = $response['success'] ? __('send_message_successfully') : __('send_message_not_successfully');
       $this->set('ajaxData', json_encode($response));
	   $this->render('/Elements/Chats/Ajax/ajax_result','ajax');


	}

 function friend_autocomplete()
 {
	$this->render('/Elements/Chats/Ajax/friend_autocomplete','ajax');
 }

  function new_message()
 {
	$this->render('/Elements/Chats/Ajax/new_message','ajax');
 }

 function friend_search()
 {
 	$User_Info= $this->Session->read('User_Info');
	$search_word =  $_GET["term"] ;
	$search_result = $this->Chat->User->query("
					SELECT 
							distinct(`User`.`id`), 
							`User`.`name`, 
							`User`.`sex`,
							`User`.`email`,
							`User`.`image`, 
							`User`.`user_name`   
							 
					 FROM `users` AS `User`
					      inner JOIN `follows` AS `Follow` 
							ON (`Follow`.`from_user_id` = `User`.`id`  and `Follow`.`to_user_id` =".$User_Info['id'].") or
							   (`Follow`.`to_user_id` = `User`.`id`  and `Follow`.`from_user_id` =".$User_Info['id'].")

					where (User.name like '".$search_word."%'
					   Or User.user_name like '".$search_word."%')
					   and User.status= 1
					   and User.role_id = 2  
					   and User.id <> ".$User_Info['id']."
					   
					   limit 0 , 5 
							 ");

	//$response['message'] = $response['success'] ? __('send_message_successfully') : __('send_message_not_successfully');
    $this->set('search_result',$search_result);
    $this->render('/Elements/Chats/Ajax/friend_search','ajax');
 }
 /**
 *
 * @param undefined $user_id
 *
*/
 function app_user_chat_search($user_id)
 {
	$search_word =  $_REQUEST["search_word"] ;
	$search_result = $this->Chat->User->query("
					SELECT 
							distinct(`User`.`id`), 
							`User`.`name`, 
							`User`.`sex`,
							`User`.`email`,
							`User`.`image`, 
							`User`.`user_name`   
							 
					 FROM `users` AS `User`
					      inner JOIN `follows` AS `Follow` 
							ON (`Follow`.`from_user_id` = `User`.`id`  and `Follow`.`to_user_id` =".$user_id.") or
							   (`Follow`.`to_user_id` = `User`.`id`  and `Follow`.`from_user_id` =".$user_id.")

					where (User.name like '".$search_word."%'
					   Or User.user_name like '".$search_word."%')
					   and User.status= 1
					   and User.role_id = 2  
					   and User.id <> ".$user_id."
					   
					   limit 0 , 5 
							 ");

	$this->set(array(
	'response' => $search_result,
	'_serialize' => array('response')
	));
 }

 /**
 *
 *
*/
 function message_box()
{

	$response['success'] = false;
	$response['message'] = null;
	$User_Info= $this->Session->read('User_Info');

	$chats = $this->Chat->User->query("	
					select 	     Chatuser.to_user_id as id ,
								 Chatuser.from_user_id , 
								 Chatuser.new_count as count,
								 User . name , 
								 User . sex ,
								 User . email ,
								 User.image, 
								 User.user_name
						from (
							(
								SELECT from_user_id, to_user_id, count( * ) AS msg_count, 0 AS new_count ,id
								FROM `chats` Chat
								WHERE `from_user_id` = ".$User_Info['id']."
								  and Chat.from_delete = 0	
								GROUP BY to_user_id
								 
							)
							union all
							(
								SELECT  to_user_id as from_user_id,
										from_user_id as to_user_id,
										count( * ) AS msg_count ,
										(select count(*) from chats where 
											viewd = 0 
											and from_user_id = Chat.from_user_id 
											and to_user_id = ".$User_Info['id']." ) as new_count
										,id
								FROM `chats` Chat
								WHERE `to_user_id` =".$User_Info['id']."
								  and Chat.from_delete = 0	
								GROUP BY from_user_id
								 
							)
						) as Chatuser
						inner join users as User
											 on  Chatuser.to_user_id = User.id
					 ORDER BY Chatuser.new_count  DESC");


	$chat_list = array();
	$users = array();

	if(!empty($chats)){
		foreach($chats as $chat){
			if(!in_array($chat['Chatuser']['id'],$users)){
				$chat_list[]['User']=array(
					'id'=> $chat['Chatuser']['id'] ,
					'name'=> $chat['User']['name'] ,
					'sex'=> $chat['User']['sex'] ,
					'email'=> $chat['User']['email'] ,
					'image'=> $chat['User']['image'] ,
					'user_name'=> $chat['User']['user_name'] ,
					'count'=> $chat['Chatuser']['count']
				);
			}
			$users[]= $chat['Chatuser']['id'];
		}
	}
	$this->set(compact('chat_list'));
	/*
	$friends = $this->Chat->User->query("
					SELECT
							distinct(`User`.`id`),
							`User`.`name`,
							`User`.`sex`,
							`User`.`email`,
							`User`.`image`,
							`User`.`user_name`

					 FROM `users` AS `User`
					      inner JOIN `follows` AS `Follow`
							ON (`Follow`.`from_user_id` = `User`.`id`  and `Follow`.`to_user_id` =".$User_Info['id'].") or
							   (`Follow`.`to_user_id` = `User`.`id`  and `Follow`.`from_user_id` =".$User_Info['id'].")

					where  User.status= 1
					   and User.role_id = 2
					   and User.id <> ".$User_Info['id']."
							 ");
	$this->set(compact('friends'));*/
	$this->render('/Elements/Chats/Ajax/message_box','ajax');
 }
 function app_message_box($user_id)
{

	$response['success'] = false;
	$response['message'] = null;


	$chats = $this->Chat->User->query("	
					select 	     Chatuser.to_user_id as id ,
								 Chatuser.from_user_id , 
								 Chatuser.new_count as count,
								 User . name , 
								 User . sex ,
								 User . email ,
								 User.image, 
								 User.user_name
						from (
							(
								SELECT from_user_id, to_user_id, count( * ) AS msg_count, 0 AS new_count ,id
								FROM `chats` Chat
								WHERE `from_user_id` = ".$user_id."
								  and Chat.from_delete = 0	
								GROUP BY to_user_id
								 
							)
							union all
							(
								SELECT  to_user_id as from_user_id,
										from_user_id as to_user_id,
										count( * ) AS msg_count ,
										(select count(*) from chats where 
											viewd = 0 
											and from_user_id = Chat.from_user_id 
											and to_user_id = ".$user_id." ) as new_count
										,id
								FROM `chats` Chat
								WHERE `to_user_id` =".$user_id."
								  and Chat.from_delete = 0	
								GROUP BY from_user_id
								 
							)
						) as Chatuser
						inner join users as User
											 on  Chatuser.to_user_id = User.id
					 ORDER BY Chatuser.new_count  DESC");


	$chat_list = array();
	$users = array();

	if(!empty($chats)){
		foreach($chats as $chat){
			if(!in_array($chat['Chatuser']['id'],$users)){
				$chat_list[]['User']=array(
					'id'=> $chat['Chatuser']['id'] ,
					'name'=> $chat['User']['name'] ,
					'sex'=> $chat['User']['sex'] ,
					'email'=> $chat['User']['email'] ,
					'image'=> $chat['User']['image'] ,
					'user_name'=> $chat['User']['user_name'] ,
					'count'=> $chat['Chatuser']['count']
				);
			}
			$users[]= $chat['Chatuser']['id'];
		}
	}
	//$this->set(compact('chat_list'));

	$this->set(array(
	'response' => $chat_list,
	'_serialize' => array('response')
	));
 }


 function read_message()
 {
    $response['success'] = false;
	$response['message'] = null;

	$id=$_REQUEST['id'];

	$User_Info= $this->Session->read('User_Info');

	$ret= $this->Chat->updateAll(
	    array( 'Chat.viewd' => '1' ),   //fields to update
	    array( 'Chat.from_user_id' =>$id , 'Chat.to_user_id' =>$User_Info['id'] )  //condition
	  );
	// DATE_FORMAT(Chat.created, '%Y/%m/%d')
	$message_list = $this->Chat->User->query("
										SELECT 
						                `User`.`id`, 
						                `User`.`name`, 
						                `User`.`sex`,
						                `User`.`email`,
						                `User`.`image`, 
						                `User`.`user_name` ,
						                Chat.message,
										 Chat.created as sent
						FROM `chats` AS `Chat` 
						        inner JOIN `users` AS `User` 
						                ON (`User`.`id` = `Chat`.`from_user_id`)   
										
						where (Chat.from_user_id = ".$id." and `Chat`.`to_user_id` = ".$User_Info['id'].") 
						   or (Chat.from_user_id = ".$User_Info['id']." and `Chat`.`to_user_id` = ".$id.")
						  and Chat.message <> ''
						  and Chat.from_delete = 0
						 ORDER BY `Chat`.`id` asc
						 ");
	$this->set(compact('message_list'));
	$this->set('user_id',$id);

    $response['message'] = $response['success'] ? __('send_message_successfully') : __('send_message_not_successfully');
    $this->set('ajaxData', json_encode($response));
    $this->render('/Elements/Chats/Ajax/message_list','ajax');
 }

 function app_read_message($user_id)
 {
    $response['success'] = false;
	$response['message'] = null;

	$id=$_REQUEST['id'];

	$ret= $this->Chat->updateAll(
	    array( 'Chat.viewd' => '1' ),   //fields to update
	    array( 'Chat.from_user_id' =>$id , 'Chat.to_user_id' =>$user_id )  //condition
	  );

	$message_list = $this->Chat->User->query("
										SELECT 
						                `User`.`id`, 
						                `User`.`name`, 
						                `User`.`sex`,
						                `User`.`email`,
						                `User`.`image`, 
						                `User`.`user_name` ,
						                Chat.message,
										 Chat.created as sent
						FROM `chats` AS `Chat` 
						        inner JOIN `users` AS `User` 
						                ON (`User`.`id` = `Chat`.`from_user_id`)   
										
						where (Chat.from_user_id = ".$id." and `Chat`.`to_user_id` = ".$user_id.") 
						   or (Chat.from_user_id = ".$user_id." and `Chat`.`to_user_id` = ".$id.")
						  and Chat.message <> ''
						  and Chat.from_delete = 0
						 ORDER BY `Chat`.`id` asc
						 ");

    $response['message'] = $response['success'] ? __('send_message_successfully') : __('send_message_not_successfully');
    $this->set('ajaxData', json_encode($response));

	$this->set(array(
	'response' => $message_list,
	'user_id' => $id,
	'_serialize' => array('response','user_id')
	));
 }

/**
*
*
*/
  function delete_message()
 {

    $response['success'] = false;
	$response['message'] = null;

	$id=$_REQUEST['id'];

	$User_Info= $this->Session->read('User_Info');

	try{
		//$this->Chat->deleteAll(array('Chat.from_user_id' =>$id , 'Chat.to_user_id' => $User_Info['id'] , 'Chat.from_delete' => 1), false);
		$this->Chat->query("
		  		delete from chats   
				where ((from_user_id = ".$id." and to_user_id = ".$User_Info['id'].")
				   or  (to_user_id = ".$id." and from_user_id = ".$User_Info['id']."))
				  and from_delete = 1 
		  ");

		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}


 	try{
		 /*$this->Chat->updateAll(
		    array( 'Chat.from_delete' => '1' ),   //fields to update
		    array( 'Chat.from_user_id' =>$id , 'Chat.to_user_id' =>$User_Info['id'] )  //condition
		  ); */

		  $this->Chat->query("
		  		update chats as Chat set Chat.from_delete = 1 
				where (Chat.from_user_id = ".$id." and Chat.to_user_id = ".$User_Info['id'].")
				   or (Chat.to_user_id = ".$id." and Chat.from_user_id = ".$User_Info['id'].")
		  ");

		$response['success'] = TRUE;
		} catch (Exception $e) {
		$response['success'] = FALSE;
	}


    $response['message'] = $response['success'] ? __('delete_message_success') : __('delete_message_not_success');
    $this->set('ajaxData', json_encode($response));
    $this->render('/Elements/Chats/Ajax/ajax_result','ajax');
 }
 /**
 *
 * @param undefined $user_id
 *
*/
  function app_delete_message($user_id)
 {
    $response['success'] = false;
	$response['message'] = null;

	$id=$_REQUEST['id'];

	try{
		$this->Chat->query("
		  		delete from chats   
				where ((from_user_id = ".$id." and to_user_id = ".$user_id.")
				   or  (to_user_id = ".$id." and from_user_id = ".$user_id."))
				  and from_delete = 1 
		  ");

		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}

 	try{
		  $this->Chat->query("
		  		update chats as Chat set Chat.from_delete = 1 
				where (Chat.from_user_id = ".$id." and Chat.to_user_id = ".$user_id.")
				   or (Chat.to_user_id = ".$id." and Chat.from_user_id = ".$user_id.")
		  ");
		$response['success'] = TRUE;
		} catch (Exception $e) {
		$response['success'] = FALSE;
	}
    $response['message'] = $response['success'] ? __('delete_message_success') : __('delete_message_not_success');
    $this->set(array(
	'response' => $response,
	'_serialize' => array('response')
	));
 }
 /**
 *
 *
*/
 function new_message_info()
 {
 	$this->Chat->recursive = -1;
	$response['count'] = 0;

	$User_Info= $this->Session->read('User_Info');

	$new_message_count = $this->Chat->find('count', array('conditions' => array('Chat.to_user_id'=>$User_Info['id'],'Chat.viewd'=>0,'Chat.from_delete'=>0)));
	//if($new_message_count>0)
		$response['count'] = $new_message_count;
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Chats/Ajax/ajax_result', 'ajax');
 }



}




