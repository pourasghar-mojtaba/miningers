<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class PostadsController extends AppController {

 var $helpers = array('Gilace','PersianDate');
	
 public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('get_ads_users','get_adspost_info','app_get_adspost_info');
}
/**
* 
* 
*/
function get_ads_users(){
 	
	$this->autoRender = false; 
     
    
    
	App::import('Model','User');
	$user= new User();
	$user->recursive = -1;		
	 
	/* $posts = $user->query("
				SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`User`.`email`, 
						`User`.`user_name`,
						`User`.`image`,
						 Post.id ,
						 Post.url ,
						 Post.image ,
						 Post.body,
						 Post.created,
						 Postadsview.id 
						 		   
				FROM `post_ads_views` AS `Postadsview` 
				    inner join post_ads as Postad 
					        on Postad.id = Postadsview.post_ads_id
					inner join posts as Post 
							on 	Post.id = Postad.post_id
					inner join users as User 
						   on User.id = Post.user_id				
				
			WHERE Post.id = 4117 
			limit 0,1
	");	*/
	
	$posts = $user->query("
				SELECT 
						`User`.`id`, 
						`User`.`name`, 
						`User`.`sex`,
						`TOUser`.`email`, 
						`User`.`user_name`,
                        `User`.`image`,
						 Post.id ,
						 Post.url ,
						 Post.image ,
						 Post.body,
						 Post.created,
						 Postadsview.id 
						 		   
				FROM `post_ads_views` AS `Postadsview` 
				    inner join post_ads as Postad 
					        on Postad.id = Postadsview.post_ads_id
					inner join posts as Post 
							on 	Post.id = Postad.post_id
					inner join users as TOUser 
						   on TOUser.id = Postadsview.user_id		
                    inner join users as User 
						   on User.id = Post.user_id	       		
				
			WHERE `Postad`.`status` = 1 
			  and `Postad`.`ads_type` = 2
			  and  Postadsview.email_viewed = 0 
              order by Postadsview.id asc
			limit 0,1
	");
	
	if(!empty($posts)){
		foreach($posts as $post)
		{
			
			try{
				$Email = new CakeEmail();
		        $Email->reset();
				$Email->template('ads_template', 'ads_layout');
				$Email->subject(__('ads_madaner'));
				$Email->emailFormat('html');
				$Email->to($post['TOUser']['email']);
				$Email->from(array(__Madaner_Email => __Email_Name));
				$Email->viewVars(array('name'=>$post['User']['name'],'user_name'=>$post['User']['user_name'],'user_image'=>$post['User']['image'],'post_date'=>$post['Post']['created'],'body'=>$post['Post']['body'],'url'=>$post['Post']['url'],'post_image'=>$post['Post']['image'],'email'=>$post['TOUser']['email'],'post_id'=>$post['Post']['id'],'sex'=>$post['User']['sex']));
				$Email->send();
				
				/* set log */
		    			Controller::loadModel('Errorlog');
		    			$this->Errorlog->get_log('PostadsController','Send email ='.$post['TOUser']['email'].' post_id='.$post['Post']['id']);
				/* set log */
		     
                 $ret= $this->Postad->Postadsview->updateAll(
    				    array( 'Postadsview.email_viewed' =>'1'),   //fields to update
    				    array( 'Postadsview.id' => $post['Postadsview']['id'] )  //condition
    			);
    			if(!$ret){
    				Controller::loadModel('Errorlog');
    		        $this->Errorlog->get_log('PostadsController','Cant Update Postadsview id='.$post['Postadsview']['id']);
    			}
               
			}catch(Exception $e){
				/* set log */
					Controller::loadModel('Errorlog');
					$this->Errorlog->get_log('PostadsController',$e->getMessage());
				/* set log */
                
                $ret= $this->Postad->Postadsview->updateAll(
				    array( 'Postadsview.email_viewed' =>'2'),   //fields to update
				    array( 'Postadsview.id' => $post['Postadsview']['id'] )  //condition
    			);
    			if(!$ret){
    				Controller::loadModel('Errorlog');
    		        $this->Errorlog->get_log('PostadsController','Cant Update Postadsview id='.$post['Postadsview']['id']);
    			}
                
				 
			}
            
            
			 
			
		}// foreach
	 }// !empty
	
}
 	
/**
* 
* 
*/
function ads_post_form()
{
	$User_Info= $this->Session->read('User_Info');
	$this->Postad->recursive = -1;
	$this->Postad->User->recursive = -1;
	$id= $_REQUEST['id'];
	$user_count = $this->Postad->User->find('count', array('conditions' => array('User.status' => 1 ,'User.role_id'=>2)));
    $this->set(compact('user_count'));
	/*
	$industries_with_count = $this->Postad->query("
									select * from (
                                    SELECT 
											Industry.id, 
											Industry.title_".$this->Session->read('Config.language')." as title, 
											(select count(*) from users as User where  industry_id=Industry.id and User.status= 1 and User.role_id = 2 )as 'count'
									FROM industries AS Industry      
									ORDER BY Industry.title_".$this->Session->read('Config.language')." asc ) Industry");
	$this->set(compact('industries_with_count'));*/
	
	
	Controller::loadModel('Siteinformation');
    $setting=$this->Siteinformation->get_setting();
	$this->set('postads_level1_price',$setting['Siteinformation']['postads_level1_price']);
	$this->set('postads_level2_price',$setting['Siteinformation']['postads_level2_price']);

	
	
	$this->set(array('id'=>$id));
	$this->render('/Elements/Postads/Ajax/ads_post_form','ajax');
}
/**
* 
* @param undefined $sum_price
* @param undefined $orderid
* 
*/
function retry_pay($sum_price,$row_id){
	$response['success'] = false;
	$response['message'] = null;
	$User_Info= $this->Session->read('User_Info');
	try{
		
		$options=array();
		/*
		$options['fields'] = array(
				'MAX(Postad.id) as max_id'
		 );
		$maxid = $this->Postad->find('all',$options); 
		
		$new_orderid=$maxid[0][0]['max_id']+1;
		
		$ret= $this->Postad->updateAll(
		    array( 'Postad.id' =>'"'.$new_orderid.'"'),   //fields to update
		    array( 'Postad.id' => $row_id )  //condition
		  );
		
		if(!$ret){
			throw new Exception();
		}
		else{
			$ret= $this->Postad->Postadsview->updateAll(
			    array( 'Postadsview.post_ads_id' =>'"'.$new_orderid.'"'),   //fields to update
			    array( 'Postadsview.post_ads_id' => $row_id )  //condition
			  );
			if(!$ret){
				throw new Exception();
			}  
			
		}*/
		
	    $random= rand(1000,1000000); 
		$long = strtotime(date('Y-m-d H:i:s'));
		$this->Session->write('Pay_Info',array(
			'title' => __('post_ads') ,
			'sum_price' => $sum_price, 
			'other_price' => 0 ,
			'sum_item' => 1,
			'orderid' => $long,
			'model'=> 'Postad' ,
			'token'=>$random ,
            'row_id'=>$row_id
		));
		$response['token'] = $random;
		$response['success'] = TRUE;
	} catch(Exception $e) {	
		$response['success'] = false;
		$response['message'] = __('system_cant_send_your_request');
	}
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Postads/Ajax/ajax_result', 'ajax');	
}
/**
* 
* 
*/
function send_ads(){
 	
	$action=$_REQUEST['action'];
	
	 
	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	
	if($action=='check_postads'){		
		$post_id=Sanitize::clean($_REQUEST['id']);		
		$options=array();
		$options['fields'] = array(
				'Postad.id',
				'Postad.post_id'
			   );			   
		$options['conditions'] = array(
			'Postad.post_id' => $post_id
		);
		$count = $this->Postad->find('count',$options); 		
		if($count>0){
				$response['success'] = FALSE;
				$response['message'] = __('this_post_has_already_been_promoted');
		}else
				$response['success'] = TRUE;
		 
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/Postads/Ajax/ajax_result', 'ajax');
	}
	
	if($action=='send'){
		
		$datasource = $this->Postad->getDataSource();
		try{
		    $datasource->begin();
			
			$tag = Sanitize::clean($_REQUEST['tag']);
			
			
			$options=array();
			$options['fields'] = array(
					'MAX(Postad.id) as max_id'
			 );
			$maxid = $this->Postad->find('all',$options); 
			
			
			$this->request->data['Postad']['id']= $maxid[0][0]['max_id']+1;
			$this->request->data['Postad']['post_id']= $_REQUEST['id'];
			$this->request->data['Postad']['user_id']= $User_Info['id'];
			$this->request->data['Postad']['select_member']= $_REQUEST['current_member'];
			$this->request->data['Postad']['base_amount']= $_REQUEST['selected_priceVal'];
			$this->request->data['Postad']['ads_type']= $_REQUEST['ads_type'];
			$this->request->data['Postad']['sum_price']= $_REQUEST['total_amount'];
			$this->request->data['Postad']['status']=0;
			
			//print_r($this->request->data);throw new Exception();
			
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Postad->create();
			$result=$this->Postad->save($this->request->data);
			if(!$result){
				$step='Can not insert in Postad';
				throw new Exception(__('the_postad_not_save')); 
			}
            
            $id=$this->Postad->getLastInsertID();
            
			$post_ads_id= $this->Postad->getLastInsertID();
            
            if($tag=='' || $tag==0){
                $long = strtotime(date('Y-m-d H:i:s'));
                $sql="INSERT INTO post_ads_views	(
														post_ads_id,
														post_id,
														from_user_id,
														to_user_id,
														created
														 )
										SELECT          ".$post_ads_id.",
														".Sanitize::clean($_REQUEST['id']).",
														".$User_Info['id'].",
														User.id,
														now()
											From users as User 
											 where User.status= 1 
											   and User.role_id = 2
											   and User.id <> ".$User_Info['id']."	
												";
            }
            else{
                $long = strtotime(date('Y-m-d H:i:s'));
                $sql="INSERT INTO post_ads_views	(
														to_user_id,
														from_user_id,
                                                        post_ads_id,
														post_id,
														created
														 )
										SELECT         distinct(User.id), 
														".$User_Info['id'].",
                                                        ".$post_ads_id.",												
														".Sanitize::clean($_REQUEST['id']).",
														now()
											From users as User 
                                                INNER JOIN userrelatetags as Userrelatetag
                                                       ON Userrelatetag.user_id = User.id
											 where Userrelatetag.usertag_id IN  (".$tag.") 
											   and User.status= 1 
											   and User.role_id = 2
											   and User.id <> ".$User_Info['id']."	
												";
            }
			
			$ret=$this->Postad->query($sql);	
			/*if(!$ret){
				$step='Can not insert in post_ads_views';
				throw new Exception(__('the_postad_not_save')); 
			}*/
			
			$this->loadModel('Allpost');
			$this->Allpost->recursive=-1;
			$long = strtotime(date('Y-m-d H:i:s'));
			$this->request->data = array();			
			$this->request->data['Allpost']['post_id']= Sanitize::clean($_REQUEST['id']);
			$this->request->data['Allpost']['user_id']= $User_Info['id'];
			$this->request->data['Allpost']['type']=2;
			//$this->request->data['Allpost']['insertdt']=date('Ymd',$long).date('Hi',$long);
			$this->request->data=Sanitize::clean($this->request->data);
	        $this->Allpost->create();
			$this->Allpost->save($this->request->data);
			
			
			$datasource->commit();
			$response['success'] = TRUE;
			$response['message']= __('the_first_postad_save_success');
			
			$random= rand(1000,1000000); 
			$long = strtotime(date('Y-m-d H:i:s'));
			$this->Session->write('Pay_Info',array(
				'title' => __('post_ads') ,
				'sum_price' => Sanitize::clean($_REQUEST['total_amount']), 
				'other_price' => 0 ,
				'sum_item' => 1,
				'orderid' => $long,
				'model'=> 'Postad' ,
				'token'=>$random,
				'row_id'=>$id
                
			));
			$response['token'] = $random;
			//$this->redirect(array('action' => 'index'));
		} catch(Exception $e) {
		    $datasource->rollback();
			
			/* set log */
			 Controller::loadModel('Errorlog');
			 $this->Errorlog->get_log('PostadsController',$step.' - '.$sql );
			/* set log */
			
			$response['message']=$e->getMessage();
			$response['success'] = FALSE;
		}
		  
		//$response['message'] = $response['success'] ? '' : __('can_not_follow');
		
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/Postads/Ajax/ajax_result', 'ajax');
	}	
 }
 
function postads_list(){
    
    $this->Postad->recursive = -1;
    
	if(isset($_REQUEST['salereferenceid'])){
		if($this->Session->read('token')==$_REQUEST['token']){
			$ret= $this->Postad->updateAll(
			    array( 'Postad.status' => '1' ),   //fields to update
			    array( 'Postad.id' => $_REQUEST['row_id'] )  //condition
			);
			
			$ret= $this->Postad->Postadsview->updateAll(
			    array( 'Postadsview.status' => '1' ),   //fields to update
			    array( 'Postadsview.post_ads_id' => $_REQUEST['row_id'] )  //condition
			);			
		}
	}
	
	$this->Session->delete('token');
	
	
    $User_Info= $this->Session->read('User_Info');
    $this->set('title_for_layout',__('postads_list'));
	$this->set('description_for_layout',__('postads_list'));
	$this->set('keywords_for_layout',__('postads_list'));
    	   
	$options['fields'] = array(
    	'Postad.id',
    	'Postad.post_id',
    	'Postad.user_id',
    	'Postad.bank_id',
    	'Postad.select_member',
    	'Postad.base_amount',
    	'Postad.sum_price',
    	'Postad.sum_rcvd_amnt',
    	'Postad.refid',
    	'Postad.sale_reference_id',
    	'Postad.sale_order_id',
    	'Postad.bankmessage_id',
    	'Postad.status',
    	'Bankmessag.message'
    );
    
    $options['joins'] = array(
		array('table' => 'bankmessags',
			'alias' => 'Bankmessag',
			'type' => 'LEFT',
			'conditions' => array(
			'Bankmessag.id = Postad.bankmessage_id ',
			'Bankmessag.bank_id = Postad.bank_id '
			)
		) 	
	);
	$options['conditions'] = array(
		'Postad.user_id '=> $User_Info['id']
	);	   
	$options['order'] = array(
		'Postad.id'=>'desc'
	);
	$postads = $this->Postad->find('all',$options);
	$this->set(compact('postads'));
	
	$options = array();
			$this->loadModel('User');
			$this->User->recursive = -1;
			$options['fields'] = array(
				'User.id',
				'User.name',
				'User.sex',
				'User.user_name',
				'User.cover_image',
				'User.cover_x',
				'User.cover_y',
				'User.cover_zoom',
				'User.image'
			   );
			$options['conditions'] = array(
			'User.id '=> $User_Info['id']
			);	   
			$user= $this->User->find('first',$options);
			$this->set(compact('user'));
} 
 
 
 function admin_index()
	{
		$this->Postad->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
		/*		
		if(isset($this->request->data['Postad']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Postad.body LIKE' => ''.$this->request->data['Postad']['search'].'%'),
				'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'User.id = `Postad`.user_id ',
					)
				   ) 
				),
				'fields'=>array(
				'User.name',
				'Postad.id',
				'Postad.body',
				'Postad.parent_id',
				'Postad.url',
				'Postad.image',
				'Postad.created'
				),
				'limit' => $limit,
				'order' => array(
					'Postad.id' => 'desc'
				)
			);
		}
		else*/
		{
			$this->paginate = array(
				'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'User.id = `Postad`.user_id ',
					)
				   ),
				   array('table' => 'bankmessags',
					'alias' => 'Bankmessag',
					'type' => 'LEFT',
					'conditions' => array(
					'Bankmessag.id = `Postad`.bankmessage_id ',
					)
				   )  
				),
				'fields'=>array(
					'User.name',
					'Postad.id',
					'Postad.post_id',
					'Postad.select_member',
					'Postad.base_amount',
					'Postad.sum_price',
					'Postad.refid',
					'Postad.sale_reference_id',
					'Postad.status',
					'Postad.created',
					'Bankmessag.message'
				),
				'limit' => $limit,
				'order' => array(
					'Postad.id' => 'desc'
				)
			);
		}		
		$postads = $this->paginate('Postad');
		$this->set(compact('postads'));
	}


/**
* 
* @param undefined $id
* 
*/	
function admin_edit($id = null)
	{
		$this->Postad->id = $id;
		if(!$this->Postad->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_post'));
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			 $error=FALSE;
			 if($error==FALSE){
			   $data=Sanitize::clean($this->request->data);			 
			   if($this->Postad->save($this->request->data))
				{
					$ret= $this->Postad->Postadsview->updateAll(
					    array( 'Postadsview.status' => '"'.$this->request->data['Postad']['status'].'"' ), //fields to update
					    array( 'Postadsview.post_ads_id' => $id )  //condition
					);
					
					$this->Session->setFlash(__('the_postads_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));
				}
				else
				{
					$this->Session->setFlash(__('the_postads_not_saved'),'admin_error');
				}	
			}	 
		}
		$this->_set_postads($id);
	}
/**
* 
* @param undefined $id
* 
*/	
function _set_postads($id)
	{
		$this->Postad->recursive = -1;
		$this->Postad->id = $id;
		if(!$this->Postad->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_postads'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Postad->findById($id);
	    }
	    
	    $this->set('postads', $this->request->data);
	    
	    return $this->request->data;
	}		
 
 /**
 * 
 * @param undefined $id
 * 
*/
 function admin_delete($id = null)
{
	   if($this->Postad->delete($id)){
		   $this->Session->setFlash(__('the_postads_deleted'), 'admin_success');
	   }
	   else $this->Session->setFlash(__('the_postads_not_deleted'),'admin_error');
	   
	   $this->redirect(array('action' => 'index'));
}

function get_adspost_info($post_id,$user_id){
 	$this->Postad->Postadsview->recursive = -1;
	$options['fields'] = array(
		'User.id as ads_user_id',
		'User.name as ads_name',
		'User.user_name as ads_user_name '
    );
	$options['joins'] = array(
			array('table' => 'users',
				'alias' => 'User',
				'type' => 'INNER',
				'conditions' => array(
					'User.id = Postadsview.from_user_id ',
					'Postadsview.status = 1',
					'Postadsview.post_id = '.$post_id,
					'Postadsview.to_user_id = '.$user_id,
				)
			) 		
		);	 
	$options['order'] = array(
		'Postadsview.id'=>'desc'
	);
	$options['limit'] = 1;	   
	$adsuser= $this->Postad->Postadsview->find('first',$options);	
	return $adsuser;
 }
 /**
 * 
 * @param undefined $post_id
 * @param undefined $user_id
 * 
*/
 function app_get_adspost_info($post_id,$user_id){
 	$this->Postad->Postadsview->recursive = -1;
	$options['fields'] = array(
		'User.id as ads_user_id',
		'User.name as ads_name',
		'User.user_name as ads_user_name '
    );
	$options['joins'] = array(
			array('table' => 'users',
				'alias' => 'User',
				'type' => 'INNER',
				'conditions' => array(
					'User.id = Postadsview.from_user_id ',
					'Postadsview.status = 1',
					'Postadsview.post_id = '.$post_id,
					'Postadsview.to_user_id = '.$user_id,
				)
			) 		
		);	 
	$options['order'] = array(
		'Postadsview.id'=>'desc'
	);
	$options['limit'] = 1;	   
	$adsuser= $this->Postad->Postadsview->find('first',$options);	
	$this->set(array(
		'adsuser' => $adsuser,
		'_serialize' => array('adsuser')
		));
 }
 
 
}
