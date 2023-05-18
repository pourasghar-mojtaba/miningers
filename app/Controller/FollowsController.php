<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class FollowsController extends AppController {


public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('app_not_follow','app_follow');
}

/**
* 
* 
*/

	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/

function follow(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
    $to_user_id=$_REQUEST['id'];
	
	if($to_user_id == 27){
		$response['success'] = FALSE;
		$response['message'] =  __('can_not_follow_madaner_news');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
	}
	
	$this->request->data['Follow']['active']= 1;
	$this->request->data['Follow']['from_user_id']= $User_Info['id'];
	$this->request->data['Follow']['to_user_id']= $to_user_id;
	$this->request->data=Sanitize::clean($this->request->data);
	
	$is_block = $this->Follow->BlockUser->find('count', array('conditions' => array('BlockUser.to_user_id' => $User_Info['id'],'BlockUser.from_user_id'=>$to_user_id)));
	
	if($is_block>0){
		$response['success'] = FALSE;
		$response['message'] = __('the_user_block_you');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
		return;
	}
	
	
	$this->Follow->create();
	try{
		$result=$this->Follow->save($this->request->data);
		if($result){
            
            $this->Session->write('User_Info',array(
					'id' => $User_Info['id'] ,
					'name' => $User_Info['name'] ,
					'sex' => $User_Info['sex'] ,
					'age' => $User_Info['age'],
					'email' => $User_Info['email'],
					'image' => $User_Info['image'],
					'industry_id' => $User_Info['industry_id'],
					'user_type' => $User_Info['user_type'] ,
					'user_name' => $User_Info['user_name'],
					'location' => $User_Info['location'],
					'industry_name'=>$User_Info['industry_name'],
                    'details'=>$User_Info['details'],
					'country_id' => $User_Info['country_id'],
                    'follow_count' => $User_Info['follow_count'] + 1
					
					));
            /*$long = strtotime(date('Y-m-d H:i:s'));
			$this->request->data['Follownotification']['follow_id']= $this->Follow->getInsertId();
			$this->request->data['Follownotification']['from_user_id']= $User_Info['id'];
			$this->request->data['Follownotification']['to_user_id']= $to_user_id;
			$this->request->data['Follownotification']['insertdt']= date('Ymd',$long);
			$this->request->data['Follownotification']['inserttm']= date('Hi',$long);
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Follow->Follownotification->save($this->request->data);*/
			$this->loadModel('Notification');
			$this->Notification->recursive = -1;
			$this->request->data['Notification']['notification_id']= $this->Follow->getInsertId();
			$this->request->data['Notification']['from_user_id']= $User_Info['id'];
			$this->request->data['Notification']['to_user_id']= $to_user_id;
			$this->request->data['Notification']['notification_type']= 4;
			$this->Notification->save($this->request->data);
			
		}
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? '' : __('can_not_follow');
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_follow($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
	
    $to_user_id=$_REQUEST['id'];
    if($to_user_id == 27){
		$response['success'] = FALSE;
		$response['message'] =  __('can_not_follow_madaner_news');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
	}	
	$this->request->data['Follow']['active']= 1;
	$this->request->data['Follow']['from_user_id']= $user_id;
	$this->request->data['Follow']['to_user_id']= $to_user_id;
	$this->request->data=Sanitize::clean($this->request->data);	
	$is_block = $this->Follow->BlockUser->find('count', array('conditions' => array('BlockUser.to_user_id' => $user_id,'BlockUser.from_user_id'=>$to_user_id)));
	
	if($is_block>0){
		$response['success'] = FALSE;
		$response['message'] = __('the_user_block_you');
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
		return;
	}
	$this->Follow->create();
	try{
		$result=$this->Follow->save($this->request->data);
		if($result){     
			$this->loadModel('Notification');
			$this->Notification->recursive = -1;
			$this->request->data['Notification']['notification_id']= $this->Follow->getInsertId();
			$this->request->data['Notification']['from_user_id']= $user_id;
			$this->request->data['Notification']['to_user_id']= $to_user_id;
            $this->request->data['Notification']['notification_type']= 4;
			$this->Notification->save($this->request->data);
		}
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}	  
	$response['message'] = $response['success'] ? '' : __('can_not_follow');
	 
    
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 }
 
 function not_follow(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
    $to_user_id=$_REQUEST['id'];
	 
	try{
		/*
		$this->Follow->Sharepost->query("		
			delete from  shareposts 
			where  id in (
                            SELECT cid FROM (
				select 
				   distinct(Sharepost.id) AS cid
				 from shareposts as   Sharepost 
				 inner join follows as Follow
				    on Follow.from_user_id = Sharepost.user_id
				where Follow.to_user_id = ".$to_user_id."
				 and Follow.from_user_id = ".$User_Info['id']."
                                 and Sharepost.post_id in 
                                 (
                                    select id from posts
                                    where user_id = ".$to_user_id."
                                 )
                           ) AS c       
			)
		");*/
		$options['fields'] = array(
			'Follow.id'
		   );					   
			$options['conditions'] = array(
				'Follow.from_user_id' => $User_Info['id'] ,
				'Follow.to_user_id' => $to_user_id
			);
	  	$follow = $this->Follow->find('first',$options);
		
	   $this->Follow->deleteAll(array('Follow.from_user_id' => $User_Info['id'] , 'Follow.to_user_id' => $to_user_id), false);
	   
	   
	  
		$this->loadModel('Notification');
   		$this->Notification->recursive = -1;
		$this->Notification->deleteAll(array('Notification.notification_type' => 4 , 'Notification.notification_id' => $follow['Follow']['id']), false);
		
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
		  
	$response['message'] = $response['success'] ? '' : __('can_not_cancel_follow');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
	
 }
 
 function app_not_follow($user_id){
	$response['success'] = false;
	$response['message'] = null;
    $to_user_id=$_REQUEST['id'];
	try{
	$options['fields'] = array(
			'Follow.id'
		   );					   
			$options['conditions'] = array(
				'Follow.from_user_id' => $user_id,
				'Follow.to_user_id' => $to_user_id
			);
	  	$follow = $this->Follow->find('first',$options);	
		$this->Follow->deleteAll(array('Follow.from_user_id' => $user_id , 'Follow.to_user_id' => $to_user_id), false);	
		$this->loadModel('Notification');
   		$this->Notification->recursive = -1;
		$this->Notification->deleteAll(array('Notification.notification_type' => 4 , 'Notification.notification_id' => $follow['Follow']['id']), false);
		
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}	  
	$response['message'] = $response['success'] ? '' : __('can_not_cancel_follow');
	
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
 }
 
 
 /**
 * 
 * 
*/
  function new_follow_info()
 {
 	$this->Follow->recursive = -1;
	$response['count'] = 0;
	
	$User_Info= $this->Session->read('User_Info');
	
	$new_follow_count = $this->Follow->find('count', array('conditions' => array('Follow.to_user_id'=>$User_Info['id'])));
	//if($new_follow_count>0)
		$response['count'] = $new_follow_count;
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
 }
 
function new_tofollow_info()
 {
 	$this->Follow->recursive = -1;
	$response['count'] = 0;
	
	$User_Info= $this->Session->read('User_Info');
	
	$new_follow_count = $this->Follow->find('count', array('conditions' => array('Follow.from_user_id'=>$User_Info['id'])));
	//if($new_follow_count>0)
		$response['count'] = $new_follow_count;
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Follows/Ajax/ajax_result', 'ajax');
 }
 
 /**
 * 
 * 
*/
 function industry_notification_multi()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$end =6;
 	$response=$this->Follow->query("
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.industry_id = ".$User_Info['industry_id']."
		  and User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.role_id = 2
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
		     order by follow_count desc
			 limit 0,".$end."
		  ");
	$this->set('new_notifications', $response);
	$this->set('first', $end);
	$this->set('type', 1);
	$this->render('/Elements/Follows/Ajax/new_notification_multi', 'ajax');
 }
 /**
 * 
 * 
*/
 function industry_notification_one()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$first=$_REQUEST['first'];
	$sql="
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.industry_id = ".$User_Info['industry_id']."
		  and User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
			 order by follow_count desc
			 limit 5,1
		  ";
 	$response=$this->Follow->query($sql);
	$this->set('new_notification', $response);
	$this->set('first', $first+1);
	$this->set('type', 1);
	$this->render('/Elements/Follows/Ajax/new_notification_one', 'ajax');
 }
 
 
 
 /**
 * 
 * 
*/
 function max_follow_notification_multi()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$end =6;
 	$response=$this->Follow->query("
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
		  and (select count(*) from `follows` where to_user_id = User.id  ) > 0
		     order by follow_count desc
			 limit 0,".$end."
		  ");
	$this->set('new_notifications', $response);
	$this->set('first', $end);
	$this->set('type', 2);
	$this->render('/Elements/Follows/Ajax/new_notification_multi', 'ajax');
 }
 /**
 * 
 * 
*/
 function max_follow_notification_one()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$first=$_REQUEST['first'];
	$sql="
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
		  and (select count(*) from `follows` where to_user_id = User.id  ) > 0
		  order by follow_count desc	 
			 limit 5,1
		  ";
 	$response=$this->Follow->query($sql);
	$this->set('new_notification', $response);
	$this->set('first', $first+1);
	$this->set('type', 2);
	$this->render('/Elements/Follows/Ajax/new_notification_one', 'ajax');
 }
 
  /**
 * 
 * 
*/
 function student_notification_multi()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$end =6;
 	$response=$this->Follow->query("
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 case degree  
		 	  when 1 then '".__('phd')."'	
			  when 2 then '".__('ma')."'	
			  when 3 then '".__('bachelor')."'	
			  when 4 then '".__('diploma')."'	
			  when 5 then '".__('diplom')."'	
		 end as degree_title ,	  
		 university_name ,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.job_status in (1,2)
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
		     order by follow_count desc
			 limit 0,".$end."
		  ");
	$this->set('new_notifications', $response);
	$this->set('first', $end);
	$this->set('type', 3);
	$this->render('/Elements/Follows/Ajax/new_notification_multi', 'ajax');
 }
 /**
 * 
 * 
*/
 function student_notification_one()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$first=$_REQUEST['first'];
	$sql="
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 case degree 
		 	  when 1 then '".__('phd')."'	
			  when 2 then '".__('ma')."'	
			  when 3 then '".__('bachelor')."'	
			  when 4 then '".__('diploma')."'	
			  when 5 then '".__('diplom')."'	
		 end as degree_title ,	  
		 university_name ,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.job_status in (1,2)
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
			 order by follow_count desc
			 limit 5,1
		  ";
 	$response=$this->Follow->query($sql);
	$this->set('new_notification', $response);
	$this->set('first', $first+1);
	$this->set('type', 3);
	$this->render('/Elements/Follows/Ajax/new_notification_one', 'ajax');
 }
 
  /**
 * 
 * 
*/
 function job_notification_multi()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$end =6;
 	$response=$this->Follow->query("
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 User.job_title,
		 User.company_name,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.job_status in (3)
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
		     order by follow_count desc
			 limit 0,".$end."
		  ");
	$this->set('new_notifications', $response);
	$this->set('first', $end);
	$this->set('type', 4);
	$this->render('/Elements/Follows/Ajax/new_notification_multi', 'ajax');
 }
 /**
 * 
 * 
*/
 function job_notification_one()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$first=$_REQUEST['first'];
	$sql="
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 User.job_title,
		 User.company_name,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `follows` where to_user_id = User.id  )as follow_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.job_status in (3)
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	 
			 order by follow_count desc
			 limit 5,1
		  ";
 	$response=$this->Follow->query($sql);
	$this->set('new_notification', $response);
	$this->set('first', $first+1);
	$this->set('type', 4);
	$this->render('/Elements/Follows/Ajax/new_notification_one', 'ajax');
 }
 
 /**
 * 
 * 
*/
 function post_notification_multi()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$end =6;
 	$response=$this->Follow->query("
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `posts` where user_id = User.id  )as post_count  
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )	
		  and (select count(*) from `posts` where user_id = User.id ) > 0 
		     order by post_count desc
			 limit 0,".$end."
		  ");
	$this->set('new_notifications', $response);
	$this->set('first', $end);
	$this->set('type', 6);
	$this->render('/Elements/Follows/Ajax/new_notification_multi', 'ajax');
 }
 /**
 * 
 * 
*/
 function post_notification_one()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$first=$_REQUEST['first'];
	$sql="
		SELECT 
		 User.id ,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image,
		 User.user_type,
		 Industry.id,
		 Industry.title_".$this->Session->read('Config.language')." as title,
		 (select count(*) from `posts` where user_id = User.id  )as post_count 
		 
		 FROM `users` as User	
		 		inner join industries as Industry
						on  Industry.id = User.industry_id
		  
		where User.id <> ".$User_Info['id']."
		  and User.status = 1
		  and User.id not in (
		  	select to_user_id
			FROM `follows`
			WHERE `from_user_id` = ".$User_Info['id']."
		  )
		  and (select count(*) from `posts` where user_id = User.id ) > 0 	 
		  order by post_count desc
			 limit 5,1
		  ";
 	$response=$this->Follow->query($sql);
	$this->set('new_notification', $response);
	$this->set('first', $first+1);
	$this->set('type', 6);
	$this->render('/Elements/Follows/Ajax/new_notification_one', 'ajax');
 }
 
 
 /**
 * 
 * 
*/
 function follow_follower_notification_multi()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$end =6;
 	$response=$this->Follow->query("
		select COUNT(followers) as same_follower_count
		    ,followers as user_id
		    ,name
		    ,user_name
		    ,user_type
		    ,image
		    ,sex
			,Industry.id 
		    ,Industry.title_".$this->Session->read('Config.language')." as title 
			,followers as id
		   from
		   (
		        select 
		            b.to_user_id followers
		           ,User.name 
		           ,User.user_name 
		           ,User.user_type
		           ,User.image
		           ,User.sex
				   ,User.industry_id
				    
		        from follows a
		             inner join follows b
		                     on a.to_user_id = b.from_user_id
		             inner join users as User 
		                     on User.id= b.to_user_id and b.to_user_id <> ".$User_Info['id']."	
		        where
		              b.to_user_id not in (select to_user_id from follows 
		              where from_user_id = ".$User_Info['id'].")             
		          and a.from_user_id = ".$User_Info['id']."
		    ) as User
			inner join industries as Industry
						on  Industry.id = User.industry_id	
		    group by followers
			
			having COUNT(followers) >0
			
			order by same_follower_count desc
			 limit 0,".$end."
		  ");
	$this->set('new_notifications', $response);
	$this->set('first', $end);
	$this->set('type', 5);
	$this->render('/Elements/Follows/Ajax/new_notification_multi', 'ajax');
 }
 /**
 * 
 * 
*/
 function follow_follower_notification_one()
 {
 	$this->Follow->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	$first=$_REQUEST['first'];
	$sql="
		select COUNT(followers) as same_follower_count
		    ,followers as user_id
		    ,name
		    ,user_name
		    ,user_type
		    ,image
		    ,sex
			,Industry.id 
		    ,Industry.title_".$this->Session->read('Config.language')." as title 
			,followers as id
		   from
		   (
		        select 
		            b.to_user_id followers
		           ,User.name 
		           ,User.user_name 
		           ,User.user_type
		           ,User.image
		           ,User.sex
				   ,User.industry_id
				   
		        from follows a
		             inner join follows b
		                     on a.to_user_id = b.from_user_id
		             inner join users as User 
		                     on User.id= b.to_user_id and b.to_user_id <> ".$User_Info['id']."	
		        where
		              b.to_user_id not in (select to_user_id from follows 
		              where from_user_id = ".$User_Info['id'].")             
		          and a.from_user_id = ".$User_Info['id']."
		    ) as User
			inner join industries as Industry
						on  Industry.id = User.industry_id
		    group by followers
			
			having COUNT(followers) >0
			
			order by same_follower_count desc	 
		    limit 5,1
		  ";
 	$response=$this->Follow->query($sql);
	$this->set('new_notification', $response);
	$this->set('first', $first+1);
	$this->set('type', 5);
	$this->render('/Elements/Follows/Ajax/new_notification_one', 'ajax');
 }
 
 
}
