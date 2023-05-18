<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class PostsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */

    var $helpers = array('Gilace','PersianDate');
	var $components = array('Httpupload','Gilace');
	
public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('view','get_paret_post_id','app_get_parent_post_id','getdelete_postchilds','load_image_link','refresh_profile_post','search_post','search','search_tag','refresh_tag','app_refresh_home','appgetdelete_postchilds','apppost_delete','app_addpost','get_share_post_users','app_postview','app_add_comment','extract_url');
}
	
	
function new_post_data(){		
	
	$this->Post->Categorypost->recursive = -1;
	$options= array();
	$options['fields'] = array(
			'Categorypost.id',
			'Categorypost.title_'.$this->Session->read('Config.language').' as title',		
		   );
	$categoryposts = $this->Post->Categorypost->find('all',$options);
	return $categoryposts; 
}

function post_window(){		
	
	$this->Post->Categorypost->recursive = -1;
	$options= array();
	$options['fields'] = array(
			'Categorypost.id',
			'Categorypost.title_'.$this->Session->read('Config.language').' as title',		
		   );
	$categoryposts = $this->Post->Categorypost->find('all',$options);
	$this->set('categoryposts',$categoryposts);
	
	$this->render('/Elements/Posts/Ajax/post_window','ajax');
}
/**
* 
* @param undefined $id
* 
*/	
function view($post_id=null)	
{   
	$User_Info= $this->Session->read('User_Info');
	
	if(!empty($User_Info)){
		$current_user_id = $User_Info['id'];
	}else $current_user_id = 0;
	/*
	if($current_user_id!=0){
		$ret= $this->Post->Postad->Postadsview->updateAll(
		    array( 'Postadsview.viewed' =>'1'),   //fields to update
		    array( 'Postadsview.user_id' => $current_user_id , 'Postad.post_id' => $post_id )  //condition
		);
	}
	*/
	$this->Post->recursive = -1;
	$options['fields'] = array(
		'Post.parent_id',
		'Post.body'
	   );
			   
	$options['conditions'] = array(
		'Post.id'=>$post_id
	);
	$post = $this->Post->find('first',$options);
	
	$parent_id= $post['Post']['parent_id'];
	if($parent_id==0){
		$select_ids=$post_id.',';
		$parent_ids=$this->_getPostChilds($post_id);
		 
		if(isset($parent_ids) && !empty($parent_ids)){
			foreach($parent_ids as $parent_id)
			{
					$select_ids .= $parent_id['id'].',';
			}	
			$select_ids = substr($select_ids,0,strlen($select_ids)-1);
		}
		elseif(empty($select_ids)) $select_ids= -1;
	}
	else
	{
		$rec=$this->get_paret_post_id($parent_id);
		if(!empty($rec)){
			$parent_id = $rec['id'];
		} 
		$parent_ids=$this->_getPostChilds($parent_id);
	
		$select_ids=$parent_id.',';
		 
		if(isset($parent_ids) && !empty($parent_ids)){
			foreach($parent_ids as $parent_id)
			{
					$select_ids .= $parent_id['id'].',';
			}	
			$select_ids = substr($select_ids,0,strlen($select_ids)-1);
		}
		elseif(empty($select_ids)) $select_ids= -1;   
	}
	
	 //pr($select_ids);exit();
	
	$response=$this->Post->query("call MGINPST1(".$current_user_id.",'".$select_ids."',@po_erroe,@po_step)");
	
	$this->set('posts', $response);
	$this->set('select_ids', $select_ids);
	$this->set('post_id', $post_id);
	$this->set('title_for_layout',substr($post['Post']['body'],0,50));
	
}	

function app_postview($post_id=null,$user_id)	
{   

	if(!empty($user_id)){
		$current_user_id = $user_id;
	}else $current_user_id = 0;
	 
	$this->Post->recursive = -1;
	$options['fields'] = array(
		'Post.parent_id',
		'Post.body'
	   );
			   
	$options['conditions'] = array(
		'Post.id'=>$post_id
	);
	$post = $this->Post->find('first',$options);
	
	$parent_id= $post['Post']['parent_id'];
	if($parent_id==0){
		$select_ids=$post_id.',';
		$parent_ids=$this->_getPostChilds($post_id);
		 
		if(isset($parent_ids) && !empty($parent_ids)){
			foreach($parent_ids as $parent_id)
			{
					$select_ids .= $parent_id['id'].',';
			}	
			$select_ids = substr($select_ids,0,strlen($select_ids)-1);
		}
		elseif(empty($select_ids)) $select_ids= -1;
	}
	else
	{
		$rec=$this->get_paret_post_id($parent_id);
		if(!empty($rec)){
			$parent_id = $rec['id'];
		} 
		$parent_ids=$this->_getPostChilds($parent_id);
	
		$select_ids=$parent_id.',';
		 
		if(isset($parent_ids) && !empty($parent_ids)){
			foreach($parent_ids as $parent_id)
			{
					$select_ids .= $parent_id['id'].',';
			}	
			$select_ids = substr($select_ids,0,strlen($select_ids)-1);
		}
		elseif(empty($select_ids)) $select_ids= -1;   
	}
	
	 //pr($select_ids);exit();
	
	$response=$this->Post->query("call MGINPST1(".$current_user_id.",'".$select_ids."',@po_erroe,@po_step)");
	
	$this->set('posts', $response);
	$this->set('select_ids', $select_ids);
	$this->set('post_id', $post_id);
    $this->set(array(
		'_serialize' => array('posts','select_ids','post_id')
		));
	//$this->set('title_for_layout',substr($post['Post']['body'],0,50));
	
}

function refresh_view($post_id=0){
 			
	$User_Info= $this->Session->read('User_Info');
	
	if(!empty($User_Info)){
		$current_user_id = $User_Info['id'];
	}else $current_user_id = 0;
		
	$this->Post->recursive = -1;
	$options['fields'] = array(
		'Post.parent_id',
		'Post.body'
	   );
			   
	$options['conditions'] = array(
		'Post.id'=>$post_id
	);
	$post = $this->Post->find('first',$options);
	
	$parent_id= $post['Post']['parent_id'];
	if($parent_id==0){
		$select_ids=$post_id;
	}
	else
	{
		$rec=$this->get_paret_post_id($parent_id);
		if(!empty($rec)){
			$parent_id = $rec['id'];
		} 
		$parent_ids=$this->_getPostChilds($parent_id);
	
		$select_ids=$parent_id.',';
		 
		if(isset($parent_ids) && !empty($parent_ids)){
			foreach($parent_ids as $parent_id)
			{
					$select_ids .= $parent_id['id'].',';
			}	
			$select_ids = substr($select_ids,0,strlen($select_ids)-1);
		}
		elseif(empty($select_ids)) $select_ids= -1;   
	}   

	$response=$this->Post->query("call MGINPST1(".$current_user_id.",'".$select_ids."',@po_erroe,@po_step)");
	
	$this->set('posts', $response);
	$this->set('select_ids', $select_ids);
	$this->set('post_id', $post_id);
	
	$this->render('/Elements/Posts/Ajax/refresh_view', 'ajax');		
 }

function view_new_comment($id=null)	
{   
	$User_Info= $this->Session->read('User_Info');
	$response = $this->Post->query("
									 	
select * from ( 

	 (SELECT 
		 Post.id as post_id, 
		 Post.url, 
		 Post.image,
		 Post.body,
		 Post.parent_id,
		 User.id as user_id,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image as user_image,
		 Post.created ,
		 0 as share_user_id ,
		 '' as share_name ,
		 '' as share_user_name ,
		 (select count(*) from `likeunlikes` as Likeunlike where Likeunlike.post_id=Post.id and Likeunlike.status=1 )as like_count , 
		 (select count(*) from `likeunlikes` as Likeunlike where Likeunlike.post_id=Post.id and Likeunlike.status=-1 )as unlike_count , 
		 (select count(*) from `posts`  where parent_id=Post.id )as commnet_count , 
		 (select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id )as share_count , 
		 (select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id and Sharepost.user_id=".$User_Info['id']." )as me_share , 

		 `Industry`.title_".$this->Session->read('Config.language')." as title,
		 left(User.location,30) as location
		 
		 FROM `posts` AS `Post` 
		 inner JOIN `users` AS `User` ON (Post.user_id = `User`.id ) 
		 left join industries Industry
	          on  User.industry_id = Industry.id
		 WHERE  Post.id = ".$id."
		 )       
  ) Post     
  
"
	);
	$this->set('post', $response);
	$this->set('title_for_layout',substr($response[0]['Post']['body'],0,50));
	$this->set('description_for_layout',$response[0]['Post']['body']);
	$this->set('in_paginate',FALSE);
	$this->render('/Elements/Posts/post','ajax'); 
}
function load_image_link(){
	$User_Info= $this->Session->read('User_Info');
	$this->Post->recursive = -1;
	$id=$_REQUEST['id'];	
	$options['fields'] = array(
				'Post.id',
				'Post.url',
				'Post.image'
			   );
	 $options['contain'] = array(
		'User' => array(
		'fields' => array('id', 'name','sex','image','user_name'),
		'conditions' => '',
		'order' => ''
		)
		);
	$options['conditions'] = array(
		'Post.id' => $id
	);
	
	$response = $this->Post->find('first',$options); 
	
	$this->set('post', $response);
	$this->render('/Elements/Posts/Ajax/image_place','ajax');
}

function load_answer(){
	$User_Info= $this->Session->read('User_Info');
	$this->Post->recursive = -1;
	$id=Sanitize::clean($_REQUEST['id']);	
	$options['fields'] = array(
				'Post.id',
				'Post.url',
				'Post.image'
			   );
	 $options['contain'] = array(
		'User' => array(
		'fields' => array('id', 'name','sex','image','user_name'),
		'conditions' => '',
		'order' => ''
		)
		);
	$options['conditions'] = array(
		'Post.id' => $id
	);
	
	$response = $this->Post->find('first',$options); 
	
	$this->set('post', $response);
	$this->set('show_comment',$_REQUEST['show_comment']);
	$this->render('/Elements/Posts/Ajax/answer_place','ajax');
}	
/**
* 
* @param undefined $parent_id
* @param undefined $post_id
* 
*/
public function _getBeforePostChilds($parent_id,$post_id) {
			
	$category_data = array();
	$this->Post->recursive = -1;	
	$query=	$this->Post->find('all',array('fields' => array('id','parent_id'),
		'conditions' => array('parent_id' => $parent_id,
							  'id <' =>	$post_id ,
							  'id <> ' =>	$post_id 
							 ))
	);
 
			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Post']['id'],
					'parent_id'    => $result['Post']['parent_id']
				);

	         $category_data = array_merge($category_data, $this->_getBeforePostChilds($parent_id,$result['Post']['id']));
			}	
	return $category_data;
}
/**
* 
* @param undefined $parent_id
* @param undefined $post_id
* 
*/
public function _getAfterPostChilds($parent_id,$post_id) {
			
	$category_data = array();
	$this->Post->recursive = -1;	
	$query=	$this->Post->find('all',array('fields' => array('id','parent_id'),
		'conditions' => array('parent_id' => $parent_id,
							  'id >' =>	$post_id ,
							  'id <> ' =>	$post_id 
							 ))
	);
 
			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Post']['id'],
					'parent_id'    => $result['Post']['parent_id']
				);

	         $category_data = array_merge($category_data, $this->_getAfterPostChilds($parent_id,$result['Post']['id']));
			}	
	return $category_data;
}

/**
* 
* @param undefined $parent_id
* @param undefined $post_id
* @param undefined $location
* 
*/
function _comment_list($parent_id,$post_id,$location)
{
	$User_Info= $this->Session->read('User_Info');
	$this->Post->recursive = -1;
	
	$parent_ids=$this->_getPostChilds($parent_id);
	
	if($location=='before')
		$select_ids=$parent_id.',';else $select_ids='';
	 
	if(isset($parent_ids) && !empty($parent_ids)){
		foreach($parent_ids as $parent_id)
		{
			if($location=='before'){
				if($parent_id['id']<$post_id)
					$select_ids .= $parent_id['id'].',';
			}
			if($location=='after'){
				if($parent_id['id']>$post_id)
					$select_ids .= $parent_id['id'].',';
			}
			
		}	
		$select_ids = substr($select_ids,0,strlen($select_ids)-1);
	}
	elseif(empty($select_ids)) $select_ids= -1;   
	/*
	if(count(explode(',',$select_ids))==2){
		$select_ids = substr($select_ids,0,strlen($select_ids)-1);
	}*/
	
	$response=$this->Post->query("call MGINPST1(".$User_Info['id'].",'".$select_ids."',@po_erroe,@po_step)");
	 
	return $response;									
}
/**
* 
* @param undefined $parent_id
* 
*/
public function _getPostChilds($parent_id) {			
	$category_data = array();
	$this->Post->recursive = -1;	
	$query=	$this->Post->find('all',array('fields' => array('id','parent_id'),
		'conditions' => array('parent_id' => $parent_id							  	
							 ),
		'order'	=> array('Post.id' => 'desc')				 
		)
	);
 
			foreach ($query as $result) {
				$category_data[] = array(
					'id'    => $result['Post']['id'],
					'parent_id'    => $result['Post']['parent_id']
				);
	         $category_data = array_merge($category_data, $this->_getPostChilds($result['Post']['id']));
			 //pr($result);
			}	
	return $category_data;
}
/**
* 
* @param undefined $id
* 
*/
function _get_parents($id=null)
 {
 	$category_data = array();
	$this->Post->recursive = -1;	
	$query=	$this->Post->query(
	  "	select parent_id,id from  posts as Post where Post.parent_id = ".$id." order by  Post.id  desc"
	 );
			 
			foreach ($query as $result) {
				$category_data []= array(
					'id'    => $result['Post']['id'],
					'parent_id'    => $result['Post']['parent_id']
				);
			 pr($category_data);
	         $category_data = array_merge($category_data, $this->_get_parents($result['Post']['parent_id']));
			}	
	return $category_data;
 }
/**
* 
* @param undefined $id
* 
*/
 function get_paret_post_id($id=null)
 {
 	$category_data = array();
	$this->Post->recursive = -1;	
	$query=	$this->Post->query(
	  "	select parent_id from  posts as Post where Post.id = ".$id." and Post.parent_id <> 0  "
	 );
			foreach ($query as $result) {
				$category_data = array(
					'id'    => $result['Post']['parent_id']
				);

	         $category_data = array_merge($category_data, $this->get_paret_post_id($result['Post']['parent_id']));
			}	
	return $category_data;
 }
 function app_get_parent_post_id($post_id=null)
 {
 	$parent_id = $this->get_paret_post_id($post_id);
    $this->set(array(
		'parent_id' => $parent_id['id'],
		'_serialize' => array('parent_id')
		));
 }
 
/**
* 
* 
*/	
function refresh_comment(){
	$id=$_REQUEST['post_id'];
	$this->set('child_posts', $this->_comment_list($id));
	$this->render('/Elements/Posts/Ajax/post_comments', 'ajax');	
	
}	
 
 function load_inline_posts($location){
	$post_id=$_REQUEST['post_id'];
	$parent_id=$_REQUEST['parent_id'];
	$after = FALSE;
	$User_Info= $this->Session->read('User_Info');
	$this->Post->recursive = -1;

	$rec=$this->get_paret_post_id($parent_id);
	if(!empty($rec)){
		$parent_id = $rec['id'];
	} 			
	$parent_ids=$this->_getPostChilds($parent_id);
	
	if($location=='before')
		$select_ids=$parent_id.',';else $select_ids='';
	 
	if(isset($parent_ids) && !empty($parent_ids)){
		foreach($parent_ids as $parent_id)
		{
			if($location=='before'){
				if($parent_id['id']==$post_id) break;
				$select_ids .= $parent_id['id'].',';				
			}
			if($location=='after'){
				if($after==TRUE)
					$select_ids .= $parent_id['id'].',';
				if($parent_id['id']==$post_id) $after = TRUE;	
			}			
		}	
		$select_ids = substr($select_ids,0,strlen($select_ids)-1);
	}
	elseif(empty($select_ids)) $select_ids= -1;   
	
	//print_r($select_ids);exit();
	
	$response=$this->Post->query("call MGINPST1(".$User_Info['id'].",'".$select_ids."',@po_erroe,@po_step)");
	
	$this->set('posts', $response);
	$this->set('select_ids', $select_ids);
	$this->render('/Elements/Posts/Ajax/post_comments', 'ajax');		
 }	
	
/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

   function admin_index()
	{
		$this->Post->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		if(isset($this->request->data['Post']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Post.body LIKE' => ''.$this->request->data['Post']['search'].'%'),
				'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'LEFT',
					'conditions' => array(
					'User.id = `Post`.user_id ',
					)
				   ) 
				),
				'fields'=>array(
				'User.name',
				'Post.id',
				'Post.body',
				'Post.parent_id',
				'Post.url',
				'Post.image',
				'Post.created'
				),
				'limit' => $limit,
				'order' => array(
					'Post.id' => 'desc'
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
					'User.id = `Post`.user_id ',
					)
				   ) 
				),
				'fields'=>array(
				'User.name',
				'Post.id',
				'Post.body',
				'Post.parent_id',
				'Post.url',
				'Post.image',
				'Post.created'
				),
				'limit' => $limit,
				'order' => array(
					'Post.id' => 'desc'
				)
			);
		}		
		$posts = $this->paginate('Post');
		$this->set(compact('posts'));
	}
  
/**
* 
* 
*/ 
 function admin_add()
	{
				
		if($this->request->is('post') || $this->request->is('put'))
		{
			$User_Info= $this->Session->read('AdminUser_Info');
			$this->request->data['Post']['user_id']= $User_Info['id'];
			$data=Sanitize::clean($this->request->data);
			$file = $data['Post']['newpost_image']; 	  
		    if($file['size']>0)
			 {
				$output=$this->_picture();
				if(!$output['error']) $this->request->data['Post']['image']=$output['filename'];
				else $this->request->data['Post']['image']='';
				
				if($this->Post->save($this->request->data))
				{
					$this->Session->setFlash(__('the_post_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));
					
					/*$post_id= $this->Post->getLastInsertID();
					$tags=$this->Gilace->get_tag($this->request->data['Post']['body'],'#'); 
					$this->Post->Postrelatetag->Posttag->recursive = -1;
					if(isset($tags) && !empty($tags)){
						foreach($tags as $tag)
						{
							
							$options['fields'] = array(
								'Posttag.id',
								'Posttag.title'
							   );
							$options['conditions'] = array(
								'Posttag.title '=> $tag
							);	   
							$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
							if(isset($tag_info) && !empty($tag_info)){
								$tag_id[] = $tag_info['Posttag']['id'];
								continue;
							} 
							
							$this->request->data['Posttag']['title']= $tag;
							$this->Post->Postrelatetag->Posttag->create();
							if($this->Post->Postrelatetag->Posttag->save($this->request->data))
							{
								$tag_id[]=$this->Post->Postrelatetag->Posttag->getLastInsertID();					
							}
						}
						$data = array();
						if(!empty($tag_id))
						{
							foreach($tag_id as $tid)
							{
								$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
								array_push($data,$dt);
							}
						}
							 
						if( isset($tag_id) && !empty($tag_id))
						{
							$this->Post->Postrelatetag->create();
							$this->Post->Postrelatetag->saveMany($data);
						}
					}*/										
					
					
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
	}
 
/**
* 
* @param undefined $id
* 
*/ 
 function admin_edit($id = null)
	{
		$this->Post->recursive = -1;
		
		$this->Post->id = $id;
		if(!$this->Post->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_post'));
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			 $error=FALSE;
			 $result= $this->Post->findById($id);
			 if($error==FALSE){
			   $data=Sanitize::clean($this->request->data);
			   $file = $data['Post']['newpost_image'];
			 	  
			   if($file['size']>0)
				 {
					
					$filename=$result['Post']['image'];
					@unlink(__POST_IMAGE_PATH."/".$filename);
					@unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$filename); 
						
					$output=$this->_picture();
					if(!$output['error']) $this->request->data['Post']['image']=$output['filename'];
					else $this->request->data['Post']['image']='';
				 }
				 else $this->request->data['Post']['image']=$this->request->data['Post']['old_image'];
				 
			   if($this->Post->save($this->request->data))
				{
					$this->Session->setFlash(__('the_post_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));

					/*$post_id= $id;
					$tags=$this->Gilace->get_tag($this->request->data['Post']['body'],'#'); 
					$this->Post->Postrelatetag->Posttag->recursive = -1;
					
					$this->Post->Postrelatetag->deleteAll(array('Postrelatetag.post_id' => $id), false);
					
					if(isset($tags) && !empty($tags)){
						foreach($tags as $tag)
						{
							
							$options['fields'] = array(
								'Posttag.id',
								'Posttag.title'
							   );
							$options['conditions'] = array(
								'Posttag.title '=> $tag
							);	   
							$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
							if(isset($tag_info) && !empty($tag_info)){
								$tag_id[] = $tag_info['Posttag']['id'];
								continue;
							} 
							
							$this->request->data['Posttag']['title']= $tag;
							$this->Post->Postrelatetag->Posttag->create();
							if($this->Post->Postrelatetag->Posttag->save($this->request->data))
							{
								$tag_id[]=$this->Post->Postrelatetag->Posttag->getLastInsertID();					
							}
						}
						$data = array();
						if(!empty($tag_id))
						{
							foreach($tag_id as $tid)
							{
								$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
								array_push($data,$dt);
							}
						}
							 
						if( isset($tag_id) && !empty($tag_id))
						{
							$this->Post->Postrelatetag->create();
							$this->Post->Postrelatetag->saveMany($data);
						}
					}*/
					
					
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
	$this->Post->recursive = -1;
	$options['fields'] = array(
		'Post.id',
		'Post.image'
	   );
			   
	$options['conditions'] = array(
		'Post.id'=>$id
	);
	  $post = $this->Post->find('first',$options);
   
	   if($this->Post->delete($id)){
	   	try{
		   	 @unlink(__POST_IMAGE_PATH."/".$post['Post']['image']);
		     @unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$post['Post']['image']);
		   } catch (Exception $e) {
		   	
		   }
		   $this->Session->setFlash(__('the_post_deleted'), 'admin_success');
	   }
	   else $this->Session->setFlash(__('the_post_not_deleted'),'admin_error');
    $this->redirect(array('action'=>'index'));   
}


/**
* 
* @param undefined $id
* 
*/
function _set_post($id)
	{
		$this->Post->recursive = -1;
		$this->Post->id = $id;
		if(!$this->Post->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_post'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Post->findById($id);
	    }
	    
	    $this->set('post', $this->request->data);
	    
	    return $this->request->data;
	}

  
  /**
  * show link form
  */
 function add_link(){
 	$this->render('/Elements/Posts/Ajax/add_link','ajax');
 }
 
 function add_comment_link(){
 	$this->render('/Elements/Posts/Ajax/add_comment_link','ajax');
 }
 
 
 /**
 * 
 * 
*/
  function add_post(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	
	if(isset($this->request->data['Post']['newpost_link']))
		$this->request->data['Post']['url']=$this->request->data['Post']['newpost_link'];
	else{
		$this->request->data['Post']['url_title'] = "";
		$this->request->data['Post']['url_content'] = "";
		$this->request->data['Post']['url_image'] = "";
	}	
	$this->request->data['Post']['user_id']= $User_Info['id'];
	$this->request->data['Post']['status']= 0;
	$this->request->data['Post']['video']=$this->request->data['Post']['newpost_video'];
    $this->request->data['Post']['body']=  trim(mb_substr($this->request->data['Post']['newpost_input'],0,500));
	
	$body = $this->request->data['Post']['body'];
	
	$output=$this->_picture();
	if(!$output['error']) $this->request->data['Post']['image']=$output['filename'];
	else $this->request->data['Post']['image']='';
	
	$this->request->data=Sanitize::clean($this->request->data); 
	
	if($this->Post->save($this->request->data))
	{
		$post_id= $this->Post->getLastInsertID();
		$this->Post->Allpost->recursive=-1;
		//$long = strtotime(date('Y-m-d H:i:s'));
		$this->request->data = array();			
		$this->request->data['Allpost']['post_id']= $post_id;
		$this->request->data['Allpost']['user_id']= $User_Info['id'];
		$this->request->data['Allpost']['type']=0;
		$this->request->data['Allpost']['created']=date('Y-m-d H:i:s');
		//$this->request->data['Allpost']['insertdt']=date('Ymd',$long).date('Hi',$long);
		//$this->request->data['Allpost']['inserttm']=date('Hi',$long);
		$this->request->data=Sanitize::clean($this->request->data);
        $this->Post->Allpost->create();
		$this->Post->Allpost->save($this->request->data);

		$tags=$this->Gilace->get_tag($body,'#'); 
        
        //print_r($tags);exit();
        
		$this->Post->Postrelatetag->Posttag->recursive = -1;
		if(isset($tags) && !empty($tags)){
			foreach($tags as $tag)
			{
				
				$options['fields'] = array(
					'Posttag.id',
					'Posttag.title'
				   );
				$options['conditions'] = array(
					'Posttag.title '=> $tag
				);	   
				$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
				if(isset($tag_info) && !empty($tag_info)){
					$tag_id[] = $tag_info['Posttag']['id'];
					continue;
				} 
				
				$this->request->data['Posttag']['title']= $tag;
				$this->Post->Postrelatetag->Posttag->create();
				if($this->Post->Postrelatetag->Posttag->save($this->request->data))
				{
					$tag_id[]=$this->Post->Postrelatetag->Posttag->getLastInsertID();					
				}
			}
			$data = array();
			if(!empty($tag_id))
			{
				foreach($tag_id as $tid)
				{
					$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
					array_push($data,$dt);
				}
			}
			 	 
			if( isset($tag_id) && !empty($tag_id))
			{
				$this->Post->Postrelatetag->create();
				$this->Post->Postrelatetag->saveMany($data);
			}
		}
		
		/* add notification */
		$tags = array();
		$tags=$this->Gilace->get_username_tag($body,'@');
        $this->Post->User->recursive = -1;
       // print_r($tags);exit();
		if(!empty($tags)){
			foreach($tags as $tag){
				$options = array();
				$user = array();
				$options['fields'] = array(
				'User.id',
				'User.email',
				'User.name'
			   );
				$options['conditions'] = array(
				'User.user_name'=> "$tag"
				);	   
				$user= $this->Post->User->find('first',$options);
               // print_r($user);
				$this->request->data = array();
				/*$long = strtotime(date('Y-m-d H:i:s'));
				$this->request->data['Postnotification']['post_id']= $post_id;
				$this->request->data['Postnotification']['from_user_id']= $User_Info['id'];
				$this->request->data['Postnotification']['to_user_id']= $user['User']['id'];
				$this->request->data['Postnotification']['type']=2;
				$this->request->data['Follownotification']['insertdt']= date('Ymd',$long);
				$this->request->data['Follownotification']['inserttm']= date('Hi',$long);
				$this->request->data=Sanitize::clean($this->request->data);
                $this->Post->Postnotification->create();
				if($this->Post->Postnotification->save($this->request->data))*/
				$this->loadModel('Notification');
				$this->Notification->recursive = -1;
				$this->request->data['Notification']['notification_id']= $post_id;
				$this->request->data['Notification']['from_user_id']= $User_Info['id'];
				$this->request->data['Notification']['to_user_id']= $user['User']['id'];
				$this->request->data['Notification']['type']= 2;
				$this->request->data['Notification']['notification_type']= 1;
				$this->request->data['Notification']['notification_body']= substr($body,0,35);
				if($this->Notification->save($this->request->data))
				{
					$Email = new CakeEmail();
					$Email->template('mention_sendemail', 'sendemail_layout');
					$Email->subject($User_Info['name']." (@".$User_Info['user_name'].") ".__('used_your_user_name'));
					$Email->emailFormat('html');
					$Email->to($user['User']['email']);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('from_name'=>$User_Info['name'],'from_user_name'=>$User_Info['user_name'],'to_name'=>$user['User']['name'],'text'=>$this->request->data['Post']['body'],'email'=>$user['User']['email'],'name'=>$user['User']['name'],'image'=>$User_Info['image'],'post_id'=>$post_id,'sex'=>$User_Info['sex']));
					$Email->send();
					
					$this->set('ajaxData', '');	 
					$this->render('/Elements/Posts/Ajax/ajax_result','ajax');	
				}
				 
			}
		}
        //exit();
		/* add notification */
	
		
	} 
	
 }
 /**
 * 
 * @param undefined $user_id
 * 
*/
 
 function app_addpost($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
 
	$this->request->data['Post']['newpost_link'] = $_REQUEST['newpost_link'];
	$this->request->data['Post']['body'] = trim($_REQUEST['newpost_body']);
	$this->request->data['Post']['newpost_image'] = trim($_REQUEST['newpost_image']);
	
	$this->request->data=Sanitize::clean($this->request->data);
	$body = $this->request->data['Post']['body'];

	if(isset($this->request->data['Post']['newpost_link']))
		$this->request->data['Post']['url']=$this->request->data['Post']['newpost_link'];
	$this->request->data['Post']['user_id']= $user_id;
	$this->request->data['Post']['status']= 0;
    $this->request->data['Post']['body']=  trim(mb_substr($this->request->data['Post']['body'],0,500));
	
	$output=$this->_picture_content();
	if(!$output['error']) $this->request->data['Post']['image']=$output['filename'];
	else $this->request->data['Post']['image']='';
	//pr($this->request->data);
	if($this->Post->save($this->request->data))
	{
		$response['success'] = TRUE;
	    $response['message'] = __('save_post_successfully');
		
		$post_id= $this->Post->getLastInsertID();
        
        $this->Post->Allpost->recursive=-1;
		$this->request->data = array();			
		$this->request->data['Allpost']['post_id']= $post_id;
		$this->request->data['Allpost']['user_id']= $user_id;
		$this->request->data['Allpost']['type']=0;
		$this->request->data=Sanitize::clean($this->request->data);
        $this->Post->Allpost->create();
		$this->Post->Allpost->save($this->request->data);
        
		$tags=$this->Gilace->get_tag($body,'#'); 
		$this->Post->Postrelatetag->Posttag->recursive = -1;
		if(isset($tags) && !empty($tags)){
			foreach($tags as $tag)
			{
				
				$options['fields'] = array(
					'Posttag.id',
					'Posttag.title'
				   );
				$options['conditions'] = array(
					'Posttag.title '=> $tag
				);	   
				$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
				if(isset($tag_info) && !empty($tag_info)){
					$tag_id[] = $tag_info['Posttag']['id'];
					continue;
				} 
				
				$this->request->data['Posttag']['title']= $tag;
				$this->Post->Postrelatetag->Posttag->create();
				if($this->Post->Postrelatetag->Posttag->save($this->request->data))
				{
					$tag_id[]=$this->Post->Postrelatetag->Posttag->getLastInsertID();					
				}
			}
			$data = array();
			if(!empty($tag_id))
			{
				foreach($tag_id as $tid)
				{
					$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
					array_push($data,$dt);
				}
			}
				 
			if( isset($tag_id) && !empty($tag_id))
			{
				$this->Post->Postrelatetag->create();
				$this->Post->Postrelatetag->saveMany($data);
			}
		}
        
        
        /* add notification */
		$tags = array();
		$tags=$this->Gilace->get_username_tag($body,'@');
        $this->Post->User->recursive = -1;
       // print_r($tags);exit();
		if(!empty($tags)){
			foreach($tags as $tag){
				$options = array();
				$user = array();
				$options['fields'] = array(
				'User.id'
			   );
				$options['conditions'] = array(
				'User.user_name'=> "$tag"
				);	   
				$user= $this->Post->User->find('first',$options);
               // print_r($user);
				$this->request->data = array();
				/*$long = strtotime(date('Y-m-d H:i:s'));
				$this->request->data['Postnotification']['post_id']= $post_id;
				$this->request->data['Postnotification']['from_user_id']= $User_Info['id'];
				$this->request->data['Postnotification']['to_user_id']= $user['User']['id'];
				$this->request->data['Postnotification']['type']=2;
				$this->request->data['Follownotification']['insertdt']= date('Ymd',$long);
				$this->request->data['Follownotification']['inserttm']= date('Hi',$long);
				$this->request->data=Sanitize::clean($this->request->data);
                $this->Post->Postnotification->create();
				$this->Post->Postnotification->save($this->request->data);*/
				
				$this->loadModel('Notification');
				$this->Notification->recursive = -1;
				$this->request->data['Notification']['notification_id']= $post_id;
				$this->request->data['Notification']['from_user_id']= $User_Info['id'];
				$this->request->data['Notification']['to_user_id']= $user['User']['id'];
				$this->request->data['Notification']['type']= 2;
				$this->request->data['Notification']['notification_type']= 1;
				$this->request->data['Notification']['notification_body']= substr($body,0,35);
				$this->Notification->create();
				if($this->Notification->save($this->request->data))
				{
					$Email = new CakeEmail();
					$Email->template('mention_sendemail', 'sendemail_layout');
					$Email->subject($User_Info['name']." (@".$User_Info['user_name'].") ".__('used_your_user_name'));
					$Email->emailFormat('html');
					$Email->to($user['User']['email']);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('from_name'=>$User_Info['name'],'from_user_name'=>$User_Info['user_name'],'to_name'=>$user['User']['name'],'text'=>$this->request->data['Post']['body'],'email'=>$user['User']['email'],'name'=>$user['User']['name'],'image'=>$User_Info['image'],'post_id'=>$post_id,'sex'=>$User_Info['sex']));
					$Email->send();
					
					$this->set('ajaxData', '');	 
					$this->render('/Elements/Posts/Ajax/ajax_result','ajax');	
				}
				 
			}
		}
        //exit();
		/* add notification */
        
        
	} 
	else{
		$response['success'] = false;
	    $response['message'] = __('cant_save_post');
	}
	
	$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 }
 

function _picture_content(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Post']['newpost_image'];
  
  
  
  $file_array = explode(';',$file);
  $ext_arry = explode('/',$file_array[0]);
  $ext=$ext_arry[1];
  
  $img = $file_array[1];
  $img = str_replace('base64,', '', $img);
  $img = str_replace(' ', '+', $img);
  
  	  
      if(!empty($img)){
		  $filename = md5(rand().$_SERVER['REMOTE_ADDR']);
          if(file_exists(__POST_IMAGE_PATH.$filename.'.'.$ext))
             $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
          $filename .= '.'.$ext; 
            
          if(!file_put_contents(__POST_IMAGE_PATH.$filename,base64_decode($img)))  
          {
              
             return array('error'=>true,'filename'=>'','message'=>__('the_image_not_saved')); 
          }   
      }
  
	return array('error'=>false,'filename'=>$filename);
} 
 
 
 function add_comment(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	$main_parent_post_id = $this->request->data['Post']['main_parent_post_id'];
    
	$output=$this->_comment_picture();
	if(!$output['error']) $this->request->data['Post']['image']=$output['filename'];
	else $this->request->data['Post']['image']='';
	if(isset($this->request->data['Post']['newcomment_link']))
		$this->request->data['Post']['url']=$this->request->data['Post']['newcomment_link'];
	else{
		$this->request->data['Post']['url_title'] = "";
		$this->request->data['Post']['url_content'] = "";
		$this->request->data['Post']['url_image'] = "";
	}	
		
	$this->request->data['Post']['user_id']= $User_Info['id'];
	$this->request->data['Post']['status']= 0;
	$this->request->data['Post']['body']=  trim(mb_substr($this->request->data['Post']['newcomment_input'],0,500));
	$body = $this->request->data['Post']['body'];
	$this->request->data=Sanitize::clean($this->request->data);	
	
	if($this->Post->save($this->request->data))
	{
		//print_r($this->request->data);exit();
		$post_id= $this->Post->getLastInsertID();
        $commnet_post_user_id = $this->request->data['Post']['commnet_post_user_id'];
		
        $this->Post->Allpost->recursive=-1;
		$this->request->data = array();		
		//$long = strtotime(date('Y-m-d H:i:s'));	
		$this->request->data['Allpost']['post_id']= $post_id;
		$this->request->data['Allpost']['user_id']= $User_Info['id'];
		$this->request->data['Allpost']['type']=0;
		//$this->request->data['Allpost']['insertdt']=date('Ymd',$long).date('Hi',$long);
		$this->request->data=Sanitize::clean($this->request->data);
        $this->Post->Allpost->create();
		$this->Post->Allpost->save($this->request->data);
		 
		// add notification  
		
		$this->request->data = array();
		if($commnet_post_user_id!=$User_Info['id']){
			/*$long = strtotime(date('Y-m-d H:i:s'));
			$this->request->data['Postnotification']['post_id']= $post_id;
			$this->request->data['Postnotification']['from_user_id']= $User_Info['id'];
			$this->request->data['Postnotification']['to_user_id']= $commnet_post_user_id;
			$this->request->data['Postnotification']['type']=1;
			$this->request->data['Postnotification']['insertdt']= date('Ymd',$long);
			$this->request->data['Postnotification']['inserttm']= date('Hi',$long);
			$this->request->data=Sanitize::clean($this->request->data);
			 
			$this->Post->Postnotification->save($this->request->data);*/
			
			$this->loadModel('Notification');
			$this->Notification->recursive = -1;
			$this->request->data['Notification']['notification_id']= $post_id;
			$this->request->data['Notification']['from_user_id']= $User_Info['id'];
			$this->request->data['Notification']['to_user_id']= $commnet_post_user_id;
			$this->request->data['Notification']['type']= 1;
			$this->request->data['Notification']['notification_type']= 1;
			$this->request->data['Notification']['notification_body']= substr($body,0,35);
			$this->Notification->create();
			$this->Notification->save($this->request->data);
			
			
		}
		
		$this->Post->Postreplaylist->recursive = -1;
		$count=$this->Post->Postreplaylist->find('count', array('conditions' => array('Postreplaylist.post_id' => $main_parent_post_id)));
		if($count>0){
			$options = array();
			$long = strtotime(date('Y-m-d H:i:s'));
			$sql="INSERT INTO notifications 		(
														notification_id,
														from_user_id,
														to_user_id,
														type,
														notification_type,
														notification_body,
														created
														 )
										SELECT          ".$main_parent_post_id.",
														".$User_Info['id'].",
														Postreplaylist.user_id,
														3,
														1,
														'".substr($body,0,35)."',
														now()
											From postreplaylists as Postreplaylist 
											 where Postreplaylist.post_id= ".$main_parent_post_id."	
											   and Postreplaylist.user_id <> ".$User_Info['id']."	
												";
			$ret=$this->Post->Postreplaylist->query($sql);												
		}
		$count = 0; 
		$count=$this->Post->Postreplaylist->find('count', array('conditions' => array('Postreplaylist.post_id' => $main_parent_post_id,'Postreplaylist.user_id'=>$User_Info['id'])));
		
		if($count<=0){
			$this->request->data = array();
			$this->request->data['Postreplaylist']['post_id']= $main_parent_post_id;
			$this->request->data['Postreplaylist']['child_post_id']= $post_id;
			$this->request->data['Postreplaylist']['user_id']= $User_Info['id'];
			$this->request->data=Sanitize::clean($this->request->data);			 
			$this->Post->Postreplaylist->save($this->request->data);
		}
		 
		$tags=$this->Gilace->get_tag($body,'#'); 
		$this->Post->Postrelatetag->Posttag->recursive = -1;
		if(isset($tags) && !empty($tags)){
			foreach($tags as $tag)
			{
				$options = array();
				$options['fields'] = array(
					'Posttag.id',
					'Posttag.title'
				   );
				$options['conditions'] = array(
					'Posttag.title '=> $tag
				);	   
				$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
				if(isset($tag_info) && !empty($tag_info)){
					$tag_id[] = $tag_info['Posttag']['id'];
					continue;
				} 
				$this->request->data = array();
				$this->request->data['Posttag']['title']= $tag;
				$this->Post->Postrelatetag->Posttag->create();
				if($this->Post->Postrelatetag->Posttag->save($this->request->data))
				{
					$tag_id[]=$this->Post->Postrelatetag->Posttag->getLastInsertID();					
				}
			}
			$data = array();
			if(!empty($tag_id))
			{
				foreach($tag_id as $tid)
				{
					$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
					array_push($data,$dt);
				}
			}
				 
			if( isset($tag_id) && !empty($tag_id))
			{
				$this->Post->Postrelatetag->create();
				$this->Post->Postrelatetag->saveMany($data);
			}
		}
        
        
        // add notification 
		$tags = array();
		$tags=$this->Gilace->get_username_tag($body,'@');
        $this->Post->User->recursive = -1;
       // print_r($tags);exit();
       
		if(!empty($tags)){
			foreach($tags as $tag){
				$options = array();
				$user = array();
				$options['fields'] = array(
				'User.id',
				'User.email',
				'User.name'
			   );
				$options['conditions'] = array(
				'User.user_name'=> "$tag"
				);	   
				$user= $this->Post->User->find('first',$options);
               // print_r($user);
				$this->request->data = array();
				/*
				$this->request->data['Postnotification']['post_id']= $post_id;
				$this->request->data['Postnotification']['from_user_id']= $User_Info['id'];
				$this->request->data['Postnotification']['to_user_id']= $user['User']['id'];
				$this->request->data['Postnotification']['type']=2;
				$this->request->data=Sanitize::clean($this->request->data);
                $this->Post->Postnotification->create();
				if($this->Post->Postnotification->save($this->request->data))*/
				$this->loadModel('Notification');
				$this->Notification->recursive = -1;
				$this->request->data['Notification']['notification_id']= $post_id;
				$this->request->data['Notification']['from_user_id']= $User_Info['id'];
				$this->request->data['Notification']['to_user_id']= $user['User']['id'];
				$this->request->data['Notification']['type']= 2;
				$this->request->data['Notification']['notification_type']= 1;
				$this->request->data['Notification']['notification_body']= substr($body,0,35);
				$this->Notification->create();
				if($this->Notification->save($this->request->data))
				{
					$Email = new CakeEmail();
					$Email->template('mention_sendemail', 'sendemail_layout');
					$Email->subject(__('used_your_user_name'));
					$Email->emailFormat('html');
					$Email->to($user['User']['email']);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('from_name'=>$User_Info['name'],'from_user_name'=>$User_Info['user_name'],'to_name'=>$user['User']['name'],'text'=>$this->request->data['Post']['body'],'email'=>$user['User']['email'],'name'=>$user['User']['name'],'image'=>$User_Info['image'],'post_id'=>$post_id,'sex'=>$User_Info['sex']));
					$Email->send();
					
					$this->set('ajaxData', '');	 
					$this->render('/Elements/Posts/Ajax/ajax_result','ajax');	
				}
				 
			}
		}
        
	}
	 
 }
 
 function app_add_comment($user_id,$name,$user_name){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$this->request->data['Post']['newpost_link'] = $_REQUEST['newpost_link'];
	$this->request->data['Post']['body'] = $_REQUEST['newpost_body'];
	$this->request->data['Post']['newpost_image'] = $_REQUEST['newpost_image'];
	$main_parent_post_id = $this->request->data['Post']['main_parent_post_id'];
	$this->request->data['Post']['parent_id'] = $_REQUEST['post_id'];
	$this->request->data['Post']['status']= 0;
	$this->request->data=Sanitize::clean($this->request->data);
	$body = $this->request->data['Post']['body'];

	if(isset($this->request->data['Post']['newpost_link']))
		$this->request->data['Post']['url']=$this->request->data['Post']['newpost_link'];
	$this->request->data['Post']['user_id']= $user_id;
        $this->request->data['Post']['body']=  trim(mb_substr($this->request->data['Post']['body'],0,500));
	
	$output=$this->_picture_content();
	if(!$output['error']) $this->request->data['Post']['image']=$output['filename'];
	else $this->request->data['Post']['image']='';
	
	if($this->Post->save($this->request->data))
	{
		$response['success'] = TRUE;
	        $response['message'] = __('save_post_successfully');
		
		$post_id= $this->Post->getLastInsertID();
                $commnet_post_user_id = $this->request->data['Post']['commnet_post_user_id'];
        
        $this->Post->Allpost->recursive=-1;
		$this->request->data = array();			
		$this->request->data['Allpost']['post_id']= $post_id;
		$this->request->data['Allpost']['user_id']= $user_id;
		$this->request->data['Allpost']['type']=0;
		$this->request->data=Sanitize::clean($this->request->data);
        $this->Post->Allpost->create();
		$this->Post->Allpost->save($this->request->data);
        // add notification  
		
		$this->request->data = array();
		if($commnet_post_user_id!=$user_id){
			
			$this->loadModel('Notification');
			$this->Notification->recursive = -1;
			$this->request->data['Notification']['notification_id']= $post_id;
			$this->request->data['Notification']['from_user_id']= $user_id;
			$this->request->data['Notification']['to_user_id']= $commnet_post_user_id;
			$this->request->data['Notification']['type']= 1;
			$this->request->data['Notification']['notification_type']= 1;
			$this->request->data['Notification']['notification_body']= substr($body,0,35);
			$this->Notification->create();
			$this->Notification->save($this->request->data);
			
			
		}
		
		$this->Post->Postreplaylist->recursive = -1;
		$count=$this->Post->Postreplaylist->find('count', array('conditions' => array('Postreplaylist.post_id' => $main_parent_post_id)));
		if($count>0){
			$options = array();
			$long = strtotime(date('Y-m-d H:i:s'));
			$sql="INSERT INTO notifications 		(
														notification_id,
														from_user_id,
														to_user_id,
														type,
														notification_type,
														notification_body,
														created
														 )
										SELECT          ".$main_parent_post_id.",
														".$user_id.",
														Postreplaylist.user_id,
														3,
														1,
														'".substr($body,0,35)."',
														now()
											From postreplaylists as Postreplaylist 
											 where Postreplaylist.post_id= ".$main_parent_post_id."	
											   and Postreplaylist.user_id <> ".$user_id."	
												";
			$ret=$this->Post->Postreplaylist->query($sql);												
		}
		$count = 0; 
		$count=$this->Post->Postreplaylist->find('count', array('conditions' => array('Postreplaylist.post_id' => $main_parent_post_id,'Postreplaylist.user_id'=>$user_id)));
		
		if($count<=0){
			$this->request->data = array();
			$this->request->data['Postreplaylist']['post_id']= $main_parent_post_id;
			$this->request->data['Postreplaylist']['child_post_id']= $post_id;
			$this->request->data['Postreplaylist']['user_id']= $user_id;
			$this->request->data=Sanitize::clean($this->request->data);			 
			$this->Post->Postreplaylist->save($this->request->data);
		}
		$tags=$this->Gilace->get_tag($body,'#'); 
		$this->Post->Postrelatetag->Posttag->recursive = -1;
		if(isset($tags) && !empty($tags)){
			foreach($tags as $tag)
			{
				$options = array();
				$options['fields'] = array(
					'Posttag.id',
					'Posttag.title'
				   );
				$options['conditions'] = array(
					'Posttag.title '=> $tag
				);	   
				$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
				if(isset($tag_info) && !empty($tag_info)){
					$tag_id[] = $tag_info['Posttag']['id'];
					continue;
				} 
				$this->request->data = array();
				$this->request->data['Posttag']['title']= $tag;
				$this->Post->Postrelatetag->Posttag->create();
				if($this->Post->Postrelatetag->Posttag->save($this->request->data))
				{
					$tag_id[]=$this->Post->Postrelatetag->Posttag->getLastInsertID();					
				}
			}
			$data = array();
			if(!empty($tag_id))
			{
				foreach($tag_id as $tid)
				{
					$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
					array_push($data,$dt);
				}
			}
				 
			if( isset($tag_id) && !empty($tag_id))
			{
				$this->Post->Postrelatetag->create();
				$this->Post->Postrelatetag->saveMany($data);
			}
		}
        
        
        
		$tags = array();
		$tags=$this->Gilace->get_username_tag($body,'@');
        $this->Post->User->recursive = -1;
       // print_r($tags);exit();
		if(!empty($tags)){
			foreach($tags as $tag){
				$options = array();
				$user = array();
				$options['fields'] = array(
				'User.id',
				'User.email',
				'User.name'
			   );
				$options['conditions'] = array(
				'User.user_name'=> "$tag"
				);	   
				$user= $this->Post->User->find('first',$options);
               // print_r($user);
				$this->request->data = array();
				
				$this->loadModel('Notification');
				$this->Notification->recursive = -1;
				$this->request->data['Notification']['notification_id']= $post_id;
				$this->request->data['Notification']['from_user_id']= $user_id;
				$this->request->data['Notification']['to_user_id']= $user['User']['id'];
				$this->request->data['Notification']['type']= 2;
				$this->request->data['Notification']['notification_type']= 1;
				$this->request->data['Notification']['notification_body']= substr($body,0,35);
				$this->Notification->create();
				if($this->Notification->save($this->request->data))
				{
					$Email = new CakeEmail();
					$Email->template('mention_sendemail', 'sendemail_layout');
					$Email->subject($name." (@".$user_name.") ".__('used_your_user_name'));
					$Email->emailFormat('html');
					$Email->to($user['User']['email']);
					$Email->from(array(__Madaner_Email => __Email_Name));
					$Email->viewVars(array('from_name'=>$name,'from_user_name'=>$user_name,'to_name'=>$user['User']['name'],'text'=>$this->request->data['Post']['body'],'email'=>$user['User']['email'],'name'=>$user['User']['name'],'image'=>$User_Info['image'],'post_id'=>$post_id,'sex'=>$User_Info['sex']));
					$Email->send();
					
					$this->set('ajaxData', '');	 
					$this->render('/Elements/Posts/Ajax/ajax_result','ajax');	
				}
				 
			}
		}
         
        
        
	} 
	else{
		$response['success'] = false;
	    $response['message'] = __('cant_save_post');
	}
	
	$this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 }
 
 /**
 * 
 * @param undefined $tag
 * 
*/
 function tags($tag){
	$this->set('tag',$tag);
	$this->set('title_for_layout',$tag);
	$this->set('description_for_layout',$tag);
	$this->set('keywords_for_layout',$tag);
 }
 
  function search_tag(){
	$tag=$this->request->data['User']['search_word'];
    $this->set('tag',$tag);
	$this->set('title_for_layout',$tag);
	$this->set('description_for_layout',$tag);
	$this->set('keywords_for_layout',$tag);
}
 

 /**
 * 
 * @param undefined $tag
 * 
*/
 
 function refresh_tag(){
 	
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	$User_Info= $this->Session->read('User_Info');
	
	$tag = Sanitize::clean($_REQUEST['tag']);
	
	// $response=$this->Post->query("call MGTGPST1(".$first.",".$end.",".$User_Info['id'].",'".$tag."',@po_erroe,@po_step)");
    $response=$this->Post->query("
        select * from (	
        	select 
        		Allpost.user_id  as user_id ,
        		Allpost.type,
        		Allpost.post_id,
        		Allpost.id as allpost_id,
        		Post.parent_id,
        		Post.body ,
        		Post.url ,
        		Post.image ,
				Post.video ,	
        		Post.commnetcount ,
        		Post.sharecount,
        		Post.favoritecount,
        		Post.created,
        		PostUser.name as post_name ,
        		PostUser.user_name as post_user_name ,
        		PostUser.id as post_user_id ,
        		PostUser.sex as post_user_sex ,
        		PostUser.image as post_user_image ,
        		left(PostUser.location,30) as post_user_location,
        		Industry.title_per as title,
        		PP.user_name as parent_user_name ,
        		(select count(*) from `favorite_posts` as FavoritePost where FavoritePost.post_id=Post.id and FavoritePost.user_id=".$User_Info['id']." )as me_favorite ,
        		(select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Allpost.post_id  and Sharepost.user_id=".$User_Info['id']." )as me_share 
				,coalesce(Blog.id,0) as blog_id
				,coalesce(Blog.num_viewed) as blog_num_viewed
        		
        	from all_posts as Allpost
        	inner join users as User 
        			  on Allpost.user_id = User.id	
        	inner join posts as Post
        			 on Allpost.post_id = Post.id
        	left join postrelatetags as Postrelatetag 
        			on Postrelatetag.post_id=Post.id
        	left join posttags as Posttag 
        			on Postrelatetag.posttag_id=Posttag.id
        	inner join users as PostUser
        			 on PostUser.id  = Post.user_id	 	
        	left join industries Industry
        				  on  PostUser.industry_id = Industry.id
        	left join vwparentpost PP 
        					on PP.post_id = Post.parent_id
			left join blogs as Blog
			 				on Blog.id = Post.blog_id				
        			 
        	WHERE  Posttag.title = '".$tag."'

        	order by Allpost.id desc
        	limit ".$first.", ".$end."

        	) as PALL
    ");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');		
} 

function all_tags(){
	$this->set('title_for_layout',__('all_subject'));
	$this->set('description_for_layout',__('all_subject'));
	$this->set('keywords_for_layout',__('all_subject'));
 }

/**
* 
* 
*/
function get_all_tags(){
 	
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=50;
	$User_Info= $this->Session->read('User_Info');
    
			$response = $this->Post->query("
									 	 SELECT Posttag.title, Posttag.id, count( Postrelatetag.post_id ) AS count
                        					FROM posttags AS Posttag
                        						LEFT JOIN postrelatetags AS Postrelatetag 
                        							   ON Posttag.id = Postrelatetag.posttag_id
                        					GROUP BY Postrelatetag.posttag_id
                        					ORDER BY count DESC
												    LIMIT $first , $end
										"							
										);		
           $this->set('tags', $response);		
           $this->render('/Elements/Posts/Ajax/all_tags', 'ajax');
}

       
/**
* 
* 
*/ 
function new_tag(){
	$this->Post->recursive = -1;
	$tags = $this->Post->query("
				  SELECT Posttag.title, Posttag.id, count( Postrelatetag.post_id ) AS count
					FROM posttags AS Posttag
						LEFT JOIN postrelatetags AS Postrelatetag 
							   ON Posttag.id = Postrelatetag.posttag_id
					GROUP BY Postrelatetag.posttag_id
					ORDER BY count DESC
					LIMIT 0 , 10
			       "
			);		

   //$this->set('tags', $response);		
   //$this->render('/Elements/Posts/Ajax/new_tags', 'ajax');		
 return $tags;	
}


 
 /**
 * 
 * 
*/
function refresh_new_home(){
	
	$Site_Info= $this->Session->read('Site_Info');
	$User_Info= $this->Session->read('User_Info');
	
	if($Site_Info['OldController']=='pages'&& $Site_Info['OldAction']=='display'){
		$this->refresh_home();
	}
	elseif($Site_Info['OldController']=='users'&& $Site_Info['OldAction']=='profile'){
		//if($this->request->params['username']==$User_Info['user_name']){
			$this->refresh_profile_post($Site_Info['OldId']);
		//}	
	}
}
 
 
 /**
 * get posts for home page
 */
 function refresh_home(){
 			
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;

	$User_Info= $this->Session->read('User_Info');
	
	if(!empty($_REQUEST['categorypost_id'])){
		$categorypost_id=$_REQUEST['categorypost_id'];
	}else $categorypost_id = 0;
	
	if($categorypost_id==0){
		$categorypost_str = "";
	}else $categorypost_str = " and  Pt.categorypost_id = ".Sanitize::clean($categorypost_id);
	   
	//$response=$this->Post->query("call FGPOSTS2(".$first.",".$end.",".$User_Info['id'].",@po_erroe,@po_step)");
	$response=$this->Post->query("
		select * from (
			select 
					Allpost.id  ,
					Allpost.user_id  ,
					Allpost.type,
					Allpost.post_id,
					Allpost.all_created,
					Post.parent_id,
					cast(Post.body as char character set utf8 ) as body,
					Post.url ,
					Post.url_title ,
					Post.url_content ,
					Post.url_image ,
					Post.image ,
					Post.video ,
					Post.commnetcount ,
					Post.sharecount,
					Post.favoritecount,
					Post.created,
					cast(PostUser.name as char character set utf8 ) as post_name ,
					cast(PostUser.user_name as char character set utf8 ) as post_user_name ,
					PostUser.id as post_user_id ,
					PostUser.sex as post_user_sex ,
					PostUser.image as post_user_image ,
					left(cast(PostUser.location as char character set utf8 ),30) as post_user_location,
					 
					cast(PP.user_name as char character set utf8 ) as parent_user_name ,
					(select count(*) from `favorite_posts` as FavoritePost where FavoritePost.post_id=Allpost.post_id  and FavoritePost.user_id=".$User_Info['id']." )as me_favorite ,
					(select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Allpost.post_id  and Sharepost.user_id=".$User_Info['id']." )as me_share 
					,coalesce(Blog.id,0) as blog_id
					,coalesce(Blog.num_viewed) as blog_num_viewed
					
			from
			(
				select 
					distinct(Allpost.id) ,
					Allpost.post_id ,
					Allpost.type ,
					Allpost.user_id ,
					Allpost.created as all_created

					from all_posts as Allpost
					
					left  join follows as Follow
							 on Follow.to_user_id = Allpost.user_id
							and Follow.to_user_id <> ".$User_Info['id']."
							and Follow.from_user_id = ".$User_Info['id']."
				   
					left  join post_ads_views as Postadsview 
							 on Postadsview.from_user_id = Allpost.user_id
							and Postadsview.from_user_id <> ".$User_Info['id']."
							and Postadsview.status = 1
							and Postadsview.to_user_id = ".$User_Info['id']."  
					
					 Inner Join (
					 	select max(Allps.id) as id , Allps.post_id from all_posts as Allps 
						inner join posts as Pt on Pt.id= Allps.post_id 
						".$categorypost_str." group by Allps.post_id
					 ) a2
							On  Allpost.Id = a2.Id  

					where  Follow.from_user_id = ".$User_Info['id']." or Allpost.user_id = ".$User_Info['id']."  or
							Postadsview.to_user_id = ".$User_Info['id']."  
					   		 


				order by Allpost.id desc
				  limit ".$first." ,".$end."
			) as Allpost
				inner join posts as Post
						 on Allpost.post_id = Post.id
						and Post.status = 0 
				inner join users as PostUser
						 on PostUser.id  = Post.user_id	 	
				left join industries Industry
							  on  PostUser.industry_id = Industry.id
				left join vwparentpost PP 
								on PP.post_id = Post.parent_id
				left join blogs as Blog
			 					on Blog.id = Post.blog_id								
								
		) as PALL ;

	");
	
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
	
 }
 
 function app_refresh_home($user_id){
 			
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	
	$response=$this->Post->query("call FGPOSTS2(".$first.",".$end.",".$user_id.",@po_error,@po_step)");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
 }
 
  function search(){
 	$search_word=$this->request->data['User']['search_word'];
	$this->set('search_word',$search_word);
	$this->set('title_for_layout',$search_word);
	$this->set('description_for_layout',$search_word);
	$this->set('keywords_for_layout',$search_word);
 }
 
 function search_post()
 {    
    $this->Post->recursive = -1;	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	$User_Info= $this->Session->read('User_Info');
	if(!empty($User_Info['id'])){
		$user_id = $User_Info['id'];
	}else $user_id = 0;
	
	$search_word = Sanitize::clean($_REQUEST['search_word']);
	
	$response = $this->Post->query("call MGSHPST1(".$first.",".$end.",".$user_id.",'".$search_word."',@po_erroe,@po_step)");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
	
	/*
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
    

    $User_Info= $this->Session->read('User_Info');
	if(isset($User_Info)){
		$share_sql="(select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id and Sharepost.user_id=".$User_Info['id']." )as me_share ,";
	}
	else $share_sql='';
    
    if(isset($User_Info)){
		$favorite_sql=", (select count(*) from `favorite_posts` as FavoritePost where FavoritePost.post_id=Post.id and FavoritePost.user_id=".$User_Info['id']." )as me_favorite";
	}
	else $favorite_sql='';
     
     if(isset($_REQUEST['search_word']) && ($_REQUEST['search_word']!='undefined')&&$_REQUEST['search_word']!=''){
		$search_word=$_REQUEST['search_word'];
			$response = $this->Post->query("
									 	
										select * from ( 
 
											 (SELECT 
												 Post.id as post_id, 
												 Post.url, 
												 Post.image,
												 Post.body,
												 Post.parent_id,
												 User.id as user_id,
												 User.name,
												 User.sex,
												 User.user_name,
												 User.image as user_image,
												 Post.created ,
												 0 as share_user_id ,
												 '' as share_name ,
												 '' as share_user_name ,
												 (select count(*) from `likeunlikes` as Likeunlike where Likeunlike.post_id=Post.id and Likeunlike.status=1 )as like_count , 
												 (select count(*) from `likeunlikes` as Likeunlike where Likeunlike.post_id=Post.id and Likeunlike.status=-1 )as unlike_count , 
												 (select count(*) from `posts`  where parent_id=Post.id )as commnet_count , 
												 (select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id )as share_count , 
												 ".$share_sql."
												  
												 u1.user_name as parent_user_name,
												 `Industry`.title_".$this->Session->read('Config.language')." as title,
												 left(User.location,30) as location
                                                 ".$favorite_sql."
												 FROM `posts` AS `Post` 
												 
												 inner JOIN `users` AS `User` ON (Post.user_id = `User`.id ) 
												 left join posts p1 on  Post.parent_id = p1.id
												 left join users u1 on  p1.user_id = u1.id
												 
												left join industries Industry
											          on  User.industry_id = Industry.id		
												 
												 WHERE  Post.body like '%".$search_word."%' 
												
												 ORDER BY `Post`.`created` desc 
												    LIMIT $first , $end
												 )
										     
										         
										  ) Post order by  `created` desc    
										  
										  
										
										"
										
										
										);		
					
			$this->set(array(
				'posts' => $response,
				'_serialize' => array('posts')
				));
			
		   $this->render('/Elements/Posts/Ajax/home_paginate', 'ajax');		
		   return;
		}
    $this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
	
	$this->render('/Elements/Posts/Ajax/home_paginate', 'ajax');	  */  
 }
 
 
 
 /**
 * get posts for home page
 */
 function refresh_new_post(){

	$this->Post->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
 
	Controller::loadModel('Userpostview');
	$ret=$this->Userpostview->get_time($User_Info['id']);
	$this->Userpostview->updatetime($User_Info['id']);
	$max_post_id = $ret['Userpostview']['max_post_id'];
	
	if(empty($max_post_id) || $max_post_id ==0){
		$response = array();
	}else
	{
		$response=$this->Post->query("call GNPOSTS1(".$User_Info['id'].",".$max_post_id.",@po_erroe,@po_step)");
	}
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
	
 }
 
 function refresh_postads(){

	$this->Post->recursive = -1;
	$User_Info= $this->Session->read('User_Info');
	
    $response=$this->Post->query("call MGADSPST1(".$User_Info['id'].",@po_error,@po_step)");
    
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
        
    $ret= $this->Post->Postad->Postadsview->updateAll(
	    array( 'Postadsview.viewed' =>'1'),   //fields to update
	    array( 'Postadsview.user_id' => $User_Info['id'] )  //condition
	);
	        
	
	$this->render('/Elements/Posts/Ajax/refresh_ads', 'ajax');	
    
	/*
	$response = $this->Post->query("
									 	
										select * from ( 
 
											 (SELECT 
												 Post.id as post_id, 
												 Post.url, 
												 Post.image,
												 Post.body,
												 Post.parent_id,
												 User.id as user_id,
												 User.name,
												 User.sex,
												 User.user_name,
												 User.image as user_image,
												 Post.created ,
												 0 as share_user_id ,
												 '' as share_name ,
												 '' as share_user_name ,
												 (select count(*) from `likeunlikes` as Likeunlike where Likeunlike.post_id=Post.id and Likeunlike.status=1 )as like_count , 
												 (select count(*) from `likeunlikes` as Likeunlike where Likeunlike.post_id=Post.id and Likeunlike.status=-1 )as unlike_count , 
												 (select count(*) from `posts`  where parent_id=Post.id )as commnet_count , 
												 (select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id )as share_count , 
												 (select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id and Sharepost.user_id=".$User_Info['id']." )as me_share ,
												 (select count(*) from `favorite_posts` as FavoritePost where FavoritePost.post_id=Post.id and FavoritePost.user_id=".$User_Info['id']." )as me_favorite ,  
												 u1.user_name as parent_user_name  
												 												 
												 FROM `posts` AS `Post` 											 
												 inner JOIN `users` AS `User` ON (Post.user_id = `User`.id ) 	
												 left join posts p1 on  Post.parent_id = p1.id
												 left join users u1 on  p1.user_id = u1.id
												 
												 WHERE Post.id  in (
												 	select post_id 
														from post_ads as Postads
															inner join post_ads_views as Postadsview
																	on Postads.id = Postadsview.post_ads_id
																   and Postadsview.user_id = ".$User_Info['id']."
																   and Postads.status=1
																   and Postadsview.viewed=0
												 )
												 
												 
												 ORDER BY `Post`.`created` desc												
												 )
										  ) Post order by  `created` desc    
										"
							);*/
	
	
	//$this->set('posts', $response);
	
	
	
 }
 
/**
* add picture on upload
* 
*/ 
function _picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Post']['newpost_image'];

  $size= getimagesize($file['tmp_name']);
  $height=$size[1];
  $width = $size[0]; 
  $newwidth  = 600;
  $newheight = $newwidth * $height / $width;
  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__POST_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Post');
			$this->Httpupload->setuploaddir(__POST_IMAGE_PATH);
			$this->Httpupload->setuploadname('newpost_image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			/*$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);*/
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			if($width>600){
				$this->Httpupload->create_thumb=true;
				$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
				$this->Httpupload->thumb_width=$newwidth;
				$this->Httpupload->thumb_height=$newheight; 
			}
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
		else return array('error'=>true,'filename'=>'','message'=>'');
		
		if($width>600){
			@unlink(__POST_IMAGE_PATH."/".$filename);
			if(@copy(__POST_IMAGE_PATH.__UPLOAD_THUMB."/".$filename, __POST_IMAGE_PATH."/".$filename))
				@unlink(__POST_IMAGE_PATH.__UPLOAD_THUMB."/".$filename);
		}
		
	return array('error'=>false,'filename'=>$filename);
} 
 
 /**
 * 
 * 
*/
function _comment_picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Post']['newcomment_image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__POST_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Post');
			$this->Httpupload->setuploaddir(__POST_IMAGE_PATH);
			$this->Httpupload->setuploadname('newcomment_image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=100;
			$this->Httpupload->thumb_height=70; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
		else return array('error'=>true,'filename'=>'','message'=>'');
		
	return array('error'=>false,'filename'=>$filename);
} 
 /**
 * 
 * 
*/
public function post_delete() {
       $response['success'] = false;
       $response['message'] = null;
	  
	   $this->Post->recursive = -1;
	   
	   $id=$_REQUEST['post_id'];
	   
	   $options['fields'] = array(
				'Post.id',
				'Post.image'
			   );					   
			$options['conditions'] = array(
				'Post.id'=>$id
			);
	  $post = $this->Post->find('first',$options);
   
	   if($this->Post->delete($id)){
	   	try{

		  
			$this->loadModel('Notification');
	   		$this->Notification->recursive = -1;
			$this->Notification->deleteAll(array('Notification.notification_type' => 1 , 'Notification.notification_id' => $id), false);
			$this->Notification->deleteAll(array('Notification.notification_type' => 2 , 'Notification.notification_id' => $id), false);
			 
			 @unlink(__POST_IMAGE_PATH."/".$post['Post']['image']);
		     @unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$post['Post']['image']);
			 /*$delete_arr=$this->_getdeletePostChilds($id);
			 if(isset($delete_arr)&& !empty($delete_arr)){
			 	foreach ($delete_arr as $did)
				{
					$this->Post->delete($did);
				}
			 }*/
		   } catch (Exception $e) {
		   	
		   }
		   $response['success'] =  TRUE;
	   }
       $response['message'] = $response['success'] ? __('post_deleted_successfully') : __('post_deleted_notsuccessfully');
       $this->set('ajaxData',  json_encode($response));
	   $this->render('/Elements/Posts/Ajax/ajax_result', 'ajax');  
} 

public function apppost_delete() {
       $response['success'] = false;
       $response['message'] = null;
	   
	   $id=$_REQUEST['post_id'];
	   
	   $options['fields'] = array(
				'Post.id',
				'Post.image'
			   );					   
			$options['conditions'] = array(
				'Post.id'=>$id
			);
	  $post = $this->Post->find('first',$options);
   
	   if($this->Post->delete($id)){
	   	try{
		   	 @unlink(__POST_IMAGE_PATH."/".$post['Post']['image']);
		     @unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$post['Post']['image']);
		   } catch (Exception $e) {
		   	
		   }
		   $response['success'] =  TRUE;
	   }
       $response['message'] = $response['success'] ? __('post_deleted_successfully') : __('post_deleted_notsuccessfully');
       $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));	  
}

 
 
  public function getdelete_postchilds($parent_id) {
			
	$category_data = array();
	$this->Post->recursive = -1;	
	$query=	$this->Post->find('all',array('fields' => array('id','parent_id'),'conditions' => array('parent_id' => $parent_id)));
 
			foreach ($query as $result) {
				$category_data[] = $result['Post']['id'];

	         $category_data = array_merge($category_data, $this->getdelete_postchilds($result['Post']['id']));
			}	
	return $category_data;
}

/**
* 
* @param undefined $parent_id
* 
*/
public function appgetdelete_postchilds($parent_id) {		
	$category_data = $this->getdelete_postchilds($parent_id);	
    
    $this->set(array(
		'delete_array' => $category_data,
		'_serialize' => array('delete_array')
		));
}

 
 function madaner_posts()
 {
 	$User_Info= $this->Session->read('User_Info');
	$response = $this->Post->query("

    SELECT 
		 Post.id as post_id, 
		 Post.url, 
		 Post.image as post_image,
		 Post.body,
		 User.id as user_id,
		 User.name,
		 User.sex,
		 User.user_name,
		 User.image as user_image,
		 Post.created  

		 FROM `posts` AS `Post`  
		 inner JOIN `users` AS `User` ON (Post.user_id = `User`.id ) 
		 WHERE User.id = 27
			
		 ORDER BY `Post`.`created` desc
		 limit 0,3
		   "										
										
		);
	return $response;	
 }
 
 function post_count()
 {
 	$this->Post->recursive = -1;
	$response['count'] = 0;
	
	$User_Info= $this->Session->read('User_Info');
	
	$post_count = $this->Post->find('count', array('conditions' => array('Post.user_id'=>$User_Info['id'])));
	
	$this->Post->Sharepost->recursive = -1;
	$share_post = $this->Post->Sharepost->find('count', array('conditions' => array('Sharepost.user_id' => $User_Info['id'])));
	
	$post_count+=$share_post;
	$response['count'] = $post_count;
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Posts/Ajax/ajax_result', 'ajax');
 }
 
/**
* 
* @param undefined $user_id
* 
*/
 function refresh_profile_post($user_id=NULL){
 	
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	$User_Info= $this->Session->read('User_Info');
	if(!empty($User_Info['id'])){
		$current_user_id = $User_Info['id'];
	}else $current_user_id = 0;
	
	$response=$this->Post->query("call MGPRPST2(".$first.",".$end.",".$current_user_id.",".$user_id.",@po_erroe,@po_step)");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
 }
 
 
 function favorite(){
	
	$this->set('title_for_layout',__('my_favorite_post'));
	$this->set('description_for_layout',__('my_favorite_post'));
	$this->set('keywords_for_layout',__('my_favorite_post'));
 }
 
 function refresh_favorite(){	
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	$User_Info= $this->Session->read('User_Info');
	
	$response=$this->Post->query("call MGFVPST1(".$first.",".$end.",".$User_Info['id'].",@po_erroe,@po_step)");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
 }
 
 function discover(){	
	$this->set('title_for_layout',__('discover'));
	$this->set('description_for_layout',__('discover'));
	$this->set('keywords_for_layout',__('discover'));
 }
 
 function refresh_discover(){	
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	
	$User_Info= $this->Session->read('User_Info');
	
	$response=$this->Post->query("call MGDSPST1(".$first.",".$end.",".$User_Info['id'].",@po_error,@po_step)");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
 }
 
 
 function post_image($id=null){
	$this->set('user_id',$id);
	$this->set('title_for_layout',__('user_post_image'));
	$this->set('description_for_layout',__('user_post_image'));
	$this->set('keywords_for_layout',__('user_post_image'));
 }
 
 
  function refresh_post_image(){
 	
	$this->Post->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=10;
	
	$id=Sanitize::clean($_REQUEST['id']);	
	
	$response=$this->Post->query("call MGIMPST1(".$first.",".$end.",".$id.",@po_erroe,@po_step)");
	$this->set(array(
		'posts' => $response,
		'_serialize' => array('posts')
		));
	
	$this->render('/Elements/Posts/Ajax/refresh_home', 'ajax');	
	
	//$this->render('/Elements/Posts/Ajax/post_image_paginate', 'ajax');	
	
 }
 
 
  function extract_url()
 {
 	 
     if(isset($_POST) && count($_POST)){
		
		try{
			extract($_POST);
		} catch (Exception $e) {
			echo 0;
	  }
		
		

		@$html = file_get_contents($link);
		
		if(strlen($html)){
			$doc = new DOMDocument();
			@$doc->loadHTML($html);
			$this->set('doc',$doc);
   			$this->render('/Elements/Posts/Ajax/extract_url','ajax'); 
			 
		}
	}		
												 
 }
 
 
 
 
 
}//  end body
