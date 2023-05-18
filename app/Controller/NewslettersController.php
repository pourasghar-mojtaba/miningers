<?php
/**
 * Static content controller.
 *
 * This file will render views from views/newsletters/
 *
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email'); 
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/newsletters-controller.html
 */
class NewslettersController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Newsletters';
	
	var $components = array('GilaceDate'); 
	
	 public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('get_active_users','send_newsletters');
	}

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();



/**
* 
* 
*/
function get_active_users(){
 	
	$this->autoRender = false; 
    
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;
	
	$this->Newsletter->Newslettertemplate->recursive = -1;
	$this->Newsletter->recursive = -1;
		
	 $users = $user->query("
				SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						 User.register_key ,
						 User.created 
				FROM `users` AS `User` 
			WHERE `User`.`id` = 22 
			limit 0,1
	");	
		
	/*$users = $user->query("
			
		Select * from(	
		 (		SELECT 
						User.id, 
						User.name, 
						User.sex,
						User.email, 
						User.user_name,
						User.register_key ,
						User.created 
				FROM users AS User 
			WHERE User.status = 1 
                and User.id not in (select user_id from newsletter_sends as Newslettersend)
			LIMIT 0 , 1
		)
	 union all
	 	(		SELECT 
						User.id, 
						User.name, 
						User.sex,
						User.email, 
						User.user_name,
						User.register_key ,
						User.created 
				FROM users AS User 
					Inner Join newsletter_sends as Newslettersend
						   on  Newslettersend.user_id = User.id
						   and ( SELECT DATEDIFF(now(),Newslettersend.created) )>14	
			WHERE User.status = 1 
			LIMIT 0 , 1
		)	
	  ) as User LIMIT 0 , 1		
	");*/
		/*$presentation_users=$this->presentation_users();
		$hot_post_tags=$this->hot_post_tags();
		$favorit_posts=$this->favorit_posts();
		$hot_posts=$this->hot_posts();
		$hot_images=$this->hot_images();*/
		
		if(!empty($users)){
			foreach($users as $auser)
			{
				$ret=$this->Newsletter->Newslettersend->Sendemail->check_send_email($auser['User']['id'],'onnewsletteremail');	 
	    		if($ret['status']){
					
					if(!$this->check_newsletter_send_email($auser['User']['id'])){
						$newsletter=$this->Newsletter->query("
								SELECT *			 
									FROM newsletters AS Newsletter 
								WHERE Newsletter.status =1
								 ORDER BY Newsletter.id  asc
								 limit 0,1
						");
						$newsletter=$newsletter[0];
						if($newsletter['Newsletter']['id']>0){
							$options['fields'] = array(
								'Newslettertemplate.template_name',
								'Newslettertemplate.title'
							);
							$options['conditions'] = array(
								'Newslettertemplate.id'=> $newsletter['Newsletter']['newsletter_template_id']
							);	   
							$template= $this->Newsletter->Newslettertemplate->find('first',$options);
							
							$this->send_email($template['Newslettertemplate']['template_name'],$auser['User']['email'],$auser['User']['name'],$newsletter['Newsletter']['body'],$newsletter['Newsletter']['id'],$auser['User']['id'],1,$newsletter['Newsletter']['title']);
						}	
		                
					}else
					{
						$newsletter = $this->get_newsletter_send_email($auser['User']['id']);
						$diff_day=$this->GilaceDate->diffdate($newsletter['Newslettersend']['created'],date('Y-m-d H:i:s'));
						if($diff_day>14){
							$new_newsletter=$this->Newsletter->query("
								SELECT *			 
									FROM newsletters AS Newsletter 
								WHERE Newsletter.status =1
								  and Newsletter.id > ".$newsletter['Newslettersend']['newsletter_id']."	
								 ORDER BY Newsletter.id  asc
								 limit 0,1
							");
						    $new_newsletter=$new_newsletter[0];
							if($new_newsletter['Newsletter']['id']>0){
								$options['fields'] = array(
								'Newslettertemplate.template_name',
								'Newslettertemplate.title'
								);
								$options['conditions'] = array(
									'Newslettertemplate.id'=> $new_newsletter['Newsletter']['newsletter_template_id']
								);	   
								$template= $this->Newsletter->Newslettertemplate->find('first',$options);
								
								$this->send_email($template['Newslettertemplate']['template_name'],$auser['User']['email'],$auser['User']['name'],$new_newsletter['Newsletter']['body'],$new_newsletter['Newsletter']['id'],$auser['User']['id'],0,$template['Newslettertemplate']['title']);
							}
								 
						}
					}
					
				  }	
			}
		 }
   

}


function send_newsletters(){
 	
	$this->autoRender = false; 
    ini_set('max_execution_time', 123456789);
	
	
	$this->Newsletter->Newslettertemplate->recursive = -1;
	$this->Newsletter->recursive = -1;
	
	$newsletters = $this->Newsletter->query("			
			SELECT * 
			FROM newsletters AS Newsletter 
		WHERE Newsletter.status = 1 
          AND (select count(*) from newsletter_sends where  newsletter_id = Newsletter.id) < 
		  	  (select count(*) from users where status = 1)
		LIMIT 0 , 1	
	");
	

	if(!empty($newsletters)){
		
		foreach($newsletters as $newsletter){
				
			/* $users = $this->Newsletter->query("
						SELECT 
								`User`.`id`, 
								`User`.`name`, 
								`User`.`sex`,
								`User`.`email`, 
								`User`.`user_name`,
								 User.register_key ,
								 User.created 
						FROM `users` AS `User` 
					WHERE `User`.`id` = 26 
					limit 0,1
			");	*/
			
				
			
			$users = $this->Newsletter->query("
					
					SELECT 
							User.id, 
							User.name, 
							User.sex,
							User.email, 
							User.user_name,
							User.register_key ,
							User.created 
					FROM users AS User 
				WHERE User.status = 1 
		            and User.id not in (select user_id from newsletter_sends as Newslettersend 
											where newsletter_id = ".$newsletter['Newsletter']['id'].")
				LIMIT 0 , 5	
			");
				
			//pr($users);	
				
				
			if(!empty($users)){
				foreach($users as $auser)
				{
					$options['fields'] = array(
						'Newslettertemplate.template_name',
						'Newslettertemplate.title'
					);
					$options['conditions'] = array(
						'Newslettertemplate.id'=> $newsletter['Newsletter']['newsletter_template_id']
					);	   
					$template= $this->Newsletter->Newslettertemplate->find('first',$options);
					$template_name = $template['Newslettertemplate']['template_name'];
					$body = $newsletter['Newsletter']['body'];
					$title = $newsletter['Newsletter']['title'];
					
					$email = $auser['User']['email'];
					$user_id = $auser['User']['id'];
					$name = $auser['User']['name'];
					
					$newsletter_id = $newsletter['Newsletter']['id'];
					
					  $body = str_replace("&lt;", "<", $body);
					  $body = str_replace("&gt;", ">", $body);
					  $body = str_replace("&amp;", "&", $body);
					  $body = str_replace("&nbsp;", " ", $body);
					  $body = str_replace("&quot;", "\"", $body);
					  $body = str_replace("\\n", "", $body);	
					    
					    
					    try{
							$Email = new CakeEmail();
					        $Email->reset();
							$Email->template($template_name, 'newsletter_layout');
							//$Email->subject(__('send_newsletter'));
							$Email->subject($title);
							$Email->emailFormat('html');
							$Email->to($email);
							$Email->from(array(__Madaner_Email => __Email_Name));
							$Email->viewVars(array('name'=>$name,'email'=>$email,'body'=>$body,'title'=>$title));
							$Email->send();
					    	/*	
							Controller::loadModel('Errorlog');
					    	$this->Errorlog->get_log('NewslettersController','Send email ='.$email.' newsletter_id='.$newsletter_id);*/
							$this->insert_newsletter_send($user_id,$newsletter_id,1);
												        	
						}catch(Exception $e){
							Controller::loadModel('Errorlog');
							$this->Errorlog->get_log('NewslettersController',$e->getMessage()); 
							$this->insert_newsletter_send($user_id,$newsletter_id,0);
						}
						
					sleep(5);	
					
				}
			 }
		}
		
		
	}
	
   

}

/**
* 
* @param undefined $template_name
* @param undefined $email
* @param undefined $name
* @param undefined $body
* @param undefined $newsletter_id
* @param undefined $user_id
* @param undefined $isinsert
* 
*/
function send_email($template_name,$email,$name,$body,$newsletter_id,$user_id,$isinsert,$title){
	
  $body = str_replace("&lt;", "<", $body);
  $body = str_replace("&gt;", ">", $body);
  $body = str_replace("&amp;", "&", $body);
  $body = str_replace("&nbsp;", " ", $body);
  $body = str_replace("&quot;", "\"", $body);
  $body = str_replace("\\n", "", $body);	
    
    
    try{
		$Email = new CakeEmail();
        $Email->reset();
		$Email->template($template_name, 'newsletter_layout');
		$Email->subject(__('send_newsletter'));
		$Email->emailFormat('html');
		$Email->to($email);
		$Email->from(array(__Madaner_Email => __Email_Name));
		$Email->viewVars(array('name'=>$name,'email'=>$email,'body'=>$body,'title'=>$title));
		$Email->send();
		
		/* set log */
    			Controller::loadModel('Errorlog');
    			$this->Errorlog->get_log('NewslettersController','Send email ='.$email.' newsletter_id='.$newsletter_id);
		/* set log */
        	
	}catch(Exception $e){
		/* set log */
			Controller::loadModel('Errorlog');
			$this->Errorlog->get_log('NewslettersController',$e->getMessage());
		/* set log */
		 
	}
	if($isinsert){
			$this->insert_newsletter_send($user_id,$newsletter_id);
		}
		else
		{
			$this->update_newsletter_send($user_id,$newsletter_id);
		}
	
}


/**
* 
* @param undefined $user_id
* @param undefined $newsletter_id
* 
*/
 function insert_newsletter_send($user_id,$newsletter_id,$status=0){

		$this->request->data['Newslettersend']['user_id']=$user_id;
		$this->request->data['Newslettersend']['newsletter_id']=$newsletter_id;
		$this->request->data['Newslettersend']['status']=$status;
	 	$this->Newsletter->Newslettersend->create();
		if(!$this->Newsletter->Newslettersend->save($this->request->data))
		{
			Controller::loadModel('Errorlog');
    		$this->Errorlog->get_log('NewslettersController','Cant Save Newslettersend user='.$user_id);
		}
}
/**
* 
* @param undefined $user_id
* @param undefined $newsletter_id
* 
*/
 function update_newsletter_send($user_id,$newsletter_id){

	$ret= $this->Newsletter->Newslettersend->updateAll(
	    array( 'Newslettersend.created' =>'"'.date('Y-m-d H:i:s').'"',
			   'Newslettersend.newsletter_id' =>'"'.$newsletter_id.'"'),   //fields to update
	    array( 'Newslettersend.user_id' => $user_id )  //condition
	);
	if(!$ret){
		Controller::loadModel('Errorlog');
        $this->Errorlog->get_log('NewslettersController','Cant Update Newslettersend user='.$user_id);
	}
}
	

/**
 * 
 * @param undefined $user_id
 * @param undefined $send_type
 * 
*/
  function check_newsletter_send_email($user_id){
 	$this->Newsletter->Newslettersend->recursive=-1;
	$options['conditions'] = array(
		'Newslettersend.user_id'=>$user_id
	);
	$count = $this->Newsletter->Newslettersend->find('count',$options);
	if ($count>0 ) return TRUE; return FALSE;
 }
/**
* 
* @param undefined $user_id
* 
*/ 
 function get_newsletter_send_email($user_id){
 	$this->Newsletter->Newslettersend->recursive=-1;
	$options['fields'] = array(
		'Newslettersend.user_id',
		'Newslettersend.newsletter_id',
		'Newslettersend.created'
   );
	$options['conditions'] = array(
		'Newslettersend.user_id'=> $user_id
	);
	return $this->Newsletter->Newslettersend->find('first',$options);
}
	/**
 * 
 * 
*/
 function admin_index()
	{
		$this->Newsletter->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
		
		if(isset($this->request->data['Newsletter']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Newsletter.title LIKE' => ''.$this->request->data['Newsletter']['search'].'%'),
 				'fields'=>array(
					'Newsletter.id',
					'Newsletter.title',
					'Newsletter.status',
					'Newsletter.created',
					'(select count(*) from newsletter_sends where  newsletter_id = Newsletter.id)  as news_count ',
					'(select count(*) from users where status = 1)  as user_count '
				),
				'limit' => $limit,
				'order' => array(
					'Newsletter.id' => 'desc'
				)
			);
		}
		else
		{				
			$this->paginate = array(
				'fields'=>array(
					'Newsletter.id',
					'Newsletter.title',
					'Newsletter.status',
					'Newsletter.created',
					'(select count(*) from newsletter_sends where  newsletter_id = Newsletter.id)  as news_count ',
					'(select count(*) from users where status = 1)  as user_count '
				),
				'limit' => $limit,
				'order' => array(
					'Newsletter.id' => 'desc'
				)
			);
		}
		
		$newsletters = $this->paginate('Newsletter');
		$this->set(compact('newsletters'));
	}
	
	
  function admin_add(){
  	if($this->request->is('post'))
		{
			$this->request->data=Sanitize::clean($this->request->data); 
			$this->request->data['Newsletter']['status']= 0;
			$this->Newsletter->create();
			if($this->Newsletter->save($this->request->data))
			{
				$this->Session->setFlash(__('the_newsletter_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_newsletter_could_not_be_saved'));
			}
		}
		
		$this->Newsletter->Newslettertemplate->recursive = -1;
		$options['fields'] = array(
			'Newslettertemplate.id',
			'Newslettertemplate.title',
			'Newslettertemplate.image '
		);
		$options['conditions'] = array(
			'Newslettertemplate.status'=>1
		);
		$options['order'] = array(
			'Newslettertemplate.id'=>'asc'
		);
		$templates = $this->Newsletter->Newslettertemplate->find('all',$options);
		$this->set('templates',$templates);
  }	


	function admin_edit($id = null)
	{
		$this->Newsletter->id = $id;
		if(!$this->Newsletter->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_newsletter'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Newsletter->save($this->request->data))
			{
				$email = $this->request->data['Newsletter']['preview_email'];
				$body = $this->request->data['Newsletter']['body'];
				$title = $this->request->data['Newsletter']['title'];
				$name = __('test_name');
								
				if(!empty($email) && $this->request->data['Newsletter']['status']==0){
					$options['fields'] = array(
						'Newslettertemplate.template_name',
						'Newslettertemplate.title'
					);
					$options['conditions'] = array(
						'Newslettertemplate.id'=> $this->request->data['Newsletter']['newsletter_template_id']
					);	   
					$template= $this->Newsletter->Newslettertemplate->find('first',$options);
					
					$body = str_replace("&lt;", "<", $body);
					$body = str_replace("&gt;", ">", $body);
					$body = str_replace("&amp;", "&", $body);
					$body = str_replace("&nbsp;", " ", $body);
					$body = str_replace("&quot;", "\"", $body);
					$body = str_replace("\\n", "", $body);
					
					$Email = new CakeEmail();
			        $Email->reset();
					$Email->template($template['Newslettertemplate']['template_name'], 'newsletter_layout');
					$Email->subject($title);
					$Email->emailFormat('html');
					$Email->to($email);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('name'=>$name,'email'=>$email,'body'=>$body,'title'=>$title));
					$Email->send();
					
				}
				
				
				$this->Session->setFlash(__('the_newsletter_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_newsletter_could_not_be_saved'));
			}
		}
		
		$options['fields'] = array(
			'Newsletter.id',
			'Newsletter.title',
			'Newsletter.newsletter_template_id',
			'Newsletter.preview_email',
			'Newsletter.body',
			'Newsletter.status'
		);
		$options['conditions'] = array(
			'Newsletter.id'=>$id
		);
		$newsletter = $this->Newsletter->find('first',$options);
		$this->set(compact('newsletter'));
		
		$this->Newsletter->Newslettertemplate->recursive = -1;
		$options['fields'] = array(
			'Newslettertemplate.id',
			'Newslettertemplate.title',
			'Newslettertemplate.image '
		);
		$options['conditions'] = array(
			'Newslettertemplate.status'=>1
		);
		$options['order'] = array(
			'Newslettertemplate.id'=>'asc'
		);
		$templates = $this->Newsletter->Newslettertemplate->find('all',$options);
		$this->set('templates',$templates);
		
	}	

function admin_delete($id = null){		
		$this->Newsletter->id = $id;
		if(!$this->Newsletter->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_newsletter'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Newsletter->delete($id))
		{
			$this->Session->setFlash(__('delete_newsletter_success'), 'admin_success');
			$this->redirect(array('action'=>'index'));
		}else
		{
			$this->Session->setFlash(__('delete_newsletter_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
  }
	
	
	
function get_main_newsletters()
{
	$options['fields'] = array(
			'Newsletter.id',
			'Newsletter.parent_id',
			'Newsletter.title_per as title'
		);
	$options['conditions'] = array(
		'Newsletter.status'=>1,
		'Newsletter.parent_id'=>0
	);
	$options['order'] = array(
		'Newsletter.arrangment'=>'asc'
	);
	$newsletters = $this->Newsletter->find('all',$options);
	return $newsletters;
}	
		
	
	
}

