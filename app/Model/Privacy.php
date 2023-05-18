<?php

class Privacy extends AppModel {
	public $name = 'Privacy';
	public $useTable = "privacies"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   


/**
 * 
 * @param undefined $privacy_user_id
 * @param undefined $user_id
 * @param undefined $privacy_location
 * 
*/
 function check_privacy($privacy_user_id,$user_id,$privacy_location)
 {
 	$count = $this->find('count', array('conditions' => array('Privacy.user_id' => $privacy_user_id)));
	if($count<=0){
		return array('status'=>TRUE);
	}
	else if($count>0){
		$options['fields'] = array(
				'Privacy.id',
				'Privacy.commenting',
				'Privacy.sharing',
				'Privacy.messaging' ,
				'Privacy.send_notification_email'
		 );
				   
		$options['conditions'] = array(
				'Privacy.user_id'=>$privacy_user_id
		);
		$privacy = $this->find('first',$options);
		
		switch($privacy_location){
			case 'commenting':
				    switch($privacy['Privacy']['commenting']){
						case 0:/* everyone */
							return array('status'=>TRUE);
							break;
						case 1:/* chaser */
						    $result= $this->_check_chaser($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>1,'name'=>$name);
							break;
						case 2:/*follow_payee*/
						    $result= $this->_check_follow_payee($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>2,'name'=>$name);
							break;
						case 3:/* chaser and follow_payee*/
							$result= $this->_check_follow_payee_chaser($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>3,'name'=>$name);
							break;
						case 4:/*nobody */	
							$user_info=$this->_get_user_info($privacy_user_id);
							$name=$user_info['User']['name'];
						    return array('status'=>FALSE, 'privacy_step'=>4,'name'=>$name);
							break;				
					}
				break;
			case 'sharing':
					switch($privacy['Privacy']['sharing']){
						case 0:/* everyone */
							return array('status'=>TRUE);
							break;
						case 1:/* chaser */
						    $result= $this->_check_chaser($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>1,'name'=>$name);
							break;
						case 2:/*follow_payee*/
						    $result= $this->_check_follow_payee($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>2,'name'=>$name);
							break;
						case 3:/* chaser and follow_payee*/
							$result= $this->_check_follow_payee_chaser($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>3,'name'=>$name);
							break;
						case 4:/*nobody */
							$user_info=$this->_get_user_info($privacy_user_id);
							$name=$user_info['User']['name'];
						    return array('status'=>FALSE, 'privacy_step'=>4,'name'=>$name);
							break;				
					}
				break;
			case 'messaging':
					switch($privacy['Privacy']['messaging']){
						case 0:/* everyone */
							return array('status'=>TRUE);
							break;
						case 1:/* chaser */
						    $result= $this->_check_chaser($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>1,'name'=>$name);
							break;
						case 2:/*follow_payee*/
						    $result= $this->_check_follow_payee($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>2,'name'=>$name);
							break;
						case 3:/* chaser and follow_payee*/
							$result= $this->_check_follow_payee_chaser($privacy_user_id ,$user_id);
							if(!$result){
								$user_info=$this->_get_user_info($privacy_user_id);
								$name=$user_info['User']['name'];
							}else $name ='';
							return array('status'=>$result , 'privacy_step'=>3,'name'=>$name);
							break;
						case 4:/*nobody */
							$user_info=$this->_get_user_info($privacy_user_id);
							$name=$user_info['User']['name'];
						    return array('status'=>FALSE, 'privacy_step'=>4,'name'=>$name);
							break;				
					}
				break;	
			case 'send_notification_email':
				break;	
		}
		
	}	

 }
 
 /**
 * 
 * @param undefined $privacy_user_id
 * @param undefined $user_id
 * 
*/
 private function _check_chaser($privacy_user_id,$user_id)
 {
 	//Model::loadModel('Follow');
	App::import('Model','Follow');
	$follow= new Follow();
	$count = $follow->find('count', array('conditions' => array('Follow.to_user_id' => $privacy_user_id ,'Follow.from_user_id' => $user_id )));
	if($count<=0){
		return FALSE;
	}
	return TRUE;
 }
 
 /**
 * 
 * @param undefined $privacy_user_id
 * @param undefined $user_id
 * 
*/
 
 private function _check_follow_payee($privacy_user_id,$user_id)
 {
 	//Model::loadModel('Follow');
	App::import('Model','Follow');
	$follow= new Follow();
	$count = $follow->find('count', array('conditions' => array('Follow.from_user_id' => $privacy_user_id ,'Follow.to_user_id' => $user_id )));
	if($count<=0){
		return FALSE;
	}
	return TRUE;
 }
 
 /**
 * 
 * @param undefined $privacy_user_id
 * @param undefined $user_id
 * 
*/
 
 private function _check_follow_payee_chaser($privacy_user_id,$user_id)
 {
 	//Model::loadModel('Follow');
	App::import('Model','Follow');
	$follow= new Follow();
	$count = $follow->query("select count(*) as count
	                                 from `follows` as Follow
									  where (to_user_id=".$privacy_user_id." and from_user_id=".$user_id.")
									     or (from_user_id=".$privacy_user_id." and to_user_id=".$user_id.")
								  ");
		 				  
	//print_r($count[0]); return;
	 
	if($count[0][0]['count']<=0){
		return FALSE;
	}
	return TRUE;
 }
 
 /**
 * 
 * @param undefined $privacy_user_id
 * 
*/
  private function _get_user_info($privacy_user_id)
 {
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;
	$options['fields'] = array(
			'User.id',
			'User.name',
			'User.email',
			'User.image',
			'User.user_name'
		   );
	$options['conditions'] = array(
	'User.id '=> $privacy_user_id
	);	   
	$user_ifo= $user->find('first',$options);

	return $user_ifo;
 }

	
}

?>