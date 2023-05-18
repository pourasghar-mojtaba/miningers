<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class BlogsController extends AppController {

var $helpers = array('Gilace');
var $components = array('Gilace','Httpupload'); 
/**
 * Controller name
 *
	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('view','get_last_blog','get_my_blog');
}


function index()
{

	$this->set('title_for_layout',__('blog_title'));
	//$this->set('description_for_layout',$user['User']['details']);
	//$this->set('keywords_for_layout',$user['User']['name']);
	if ($this->request->is('post')) 
    { 
		$User_Info= $this->Session->read('User_Info');
		$data=Sanitize::clean($this->request->data);
		$file = $data['Blog']['blog_file'];
		if($file['size']>0)
		 {	
			$output=$this->_attach_file();
			if(!$output['error']) $this->request->data['Blog']['file']=$output['filename'];
			else 
			{
				$this->request->data['Blog']['file']='';
				$this->Session->setFlash($output['message'],'error');
				return;
			}
		 }
		 
		    $this->request->data['Blog']['active']= 0;
			$this->request->data['Blog']['user_id']= $User_Info['id'];
			$this->request->data['Blog']['title_'.$this->Session->read('Config.language')]='';
			$this->Blog->create();
			if($this->Blog->save($this->request->data))
			{
				$this->Session->setFlash(__('the_blog_has_been_saved'), 'success');
			}
			else $this->Session->setFlash(__('the_blog_no_saved'), 'error');
		 
	} 
}


function _attach_file(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Blog']['blog_file'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_BLOG_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           -
            $this->Httpupload->setmodel('Blog');
			$this->Httpupload->setuploaddir(__USER_BLOG_PATH);
			$this->Httpupload->setuploadname('blog_file');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_FILE_MAX_SIZE);
			$this->Httpupload->allowExt= __UPLOAD_File_EXTENSION; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
} 

/**
* 
* @param undefined $id
* 
*/
function tag($id=null)
{
	$this->Blog->Blogrelatetag->Blogtag->recursive = -1;
	$tag=$this->Blog->Blogrelatetag->Blogtag->findById($id);
	$this->set('title_for_layout',__('search_blog_with_tag').' '.$tag['Blogtag']['title']);
	$this->set('description_for_layout',__('search_blog_with_tag'));
	$this->set('keywords_for_layout',$tag['Blogtag']['title']);
	$this->set('tag_id',$id); 
}
/**
* 
* 
*/
function search()
{
	$this->set('title_for_layout',__('search'));
	$this->set('description_for_layout',__('search_blog_with_tag'));
	
	if(isset($_POST['year'])) $year = $_POST['year']; else $year = '';
	if(isset($_POST['month'])) $month = $_POST['month']; else $month = '';
	if(isset($_POST['writer'])) $writer = $_POST['writer']; else $writer = '';
	if(isset($_POST['search_text'])) $search_text = $_POST['search_text']; else $search_text = '';
	if(isset($_POST['tag'])) $tag = $_POST['tag']; else $tag = '';
	$this->set('year',$year); 
	$this->set('month',$month);
	$this->set('writer',$writer);
	$this->set('search_text',$search_text);
	$this->set('tag',$tag);
}

function _blog_picture(){
  App::uses('Sanitize', 'Utility');

  $output= array();  
  $data=Sanitize::clean($this->request->data);
  $file = $data['Blog']['image'];
	  
	  if($file['size']>0)
        {
			$ext=$this->Httpupload->get_extension($file['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__USER_BLOG_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Blog');
			$this->Httpupload->setuploaddir(__USER_BLOG_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(4194304);
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=200;
			$this->Httpupload->thumb_height=200; 
			//$this->Httpupload->setimagemaxsize(1400,400);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
	return array('error'=>false,'filename'=>$filename);
} 
/**
* 
* @param undefined $blog_id
* 
*/
function add_blog_save($blog_id=0){

	$User_Info= $this->Session->read('User_Info');
	$this->request->data['Blog']['status']= 0;
	$this->request->data['Blog']['body']= trim($this->request->data['Blog']['body']);
	$this->request->data['Blog']['user_id']= $User_Info['id'];
	$this->request->data=Sanitize::clean($this->request->data);
	$data=Sanitize::clean($this->request->data);
	
	$set_publish = $this->request->data['Blog']['set_publish'];
	$channels=$this->request->data['Blog']['channel'];
	$file = $data['Blog']['image'];
	 
	if($file['size']>0){
        $output=$this->_blog_picture();
    	if(!$output['error']) 
    	{
    		$cover_image=$output['filename'];
    	}	
        else 
        {
            $cover_image='';
            echo"<script>show_warning_msg('".$output['message']."');remove_modal();</script>";
            return;
        }
    }
    else 	$cover_image="";
	$this->request->data['Blog']['image']= $cover_image;
	
	$this->Blog->create();
	try{
		$result=$this->Blog->save($this->request->data);
		 
		if($result){
			$data = array();
			$blog_id= $this->Blog->getLastInsertID();
			if($set_publish==1){
				$this->_publish_blog($blog_id,0);
			}
			//print_r($this->request->data['Blog']['channel']);exit();
			foreach ($channels as $cid)
			{
			    $dt=array('Blogchannel' => array('blog_id' => $blog_id,'channel_id'=>$cid));
				array_push($data,$dt);
			}
			if(!empty($data)){
				//print_r($data);exit();
				$this->Blog->Blogchannel->recursive = -1;
				$this->Blog->Blogchannel->create();
				$this->Blog->Blogchannel->saveMany($data);
			}
		}
		
		echo"<script>
		 show_success_msg('".__('the_blog_has_been_saved')."');
		 refresh_last_blog(0);
		 load_blog(0);
		 load_blog_tab(0);
		 remove_modal();
		 
		 </script>";
		
	} catch (Exception $e) {
		 echo"<script>
		 show_success_msg('".__('the_bloh_could_not_be_saved')."');
		 remove_modal();
		  load_blog_tab(0);
		 </script>";
	}
}

function edit_blog_save($blog_id=0){

	
	$this->Blog->recursive = -1;
	$options= array();
	$options['fields'] = array(
			'Blog.id',
			'Blog.title',		
			'Blog.image',
			'Blog.created'
		   );
	$options['conditions'] = array(
		"Blog.id"=> $blog_id
	);
	   
	$blog = $this->Blog->find('first',$options);
	
	
	$User_Info= $this->Session->read('User_Info');
	$this->request->data['Blog']['status']= 0;
	$this->request->data['Blog']['id']= $blog_id;
	$this->request->data['Blog']['body']= trim($this->request->data['Blog']['body']);
	$this->request->data=Sanitize::clean($this->request->data);
	$data=Sanitize::clean($this->request->data);
	
	//print_r($this->request->data);exit();
	$channels=$this->request->data['Blog']['channel'];
	
	$set_publish = $this->request->data['Blog']['set_publish'];
	
	$file = $data['Blog']['image'];
	 
	if($file['size']>0){
        $output=$this->_blog_picture();
    	if(!$output['error']) 
    	{
    		$cover_image=$output['filename'];
    	}	
        else 
        {
            $cover_image='';
            echo"<script>show_warning_msg('".$output['message']."');remove_modal();</script>";
            return;
        }
    }
    else $cover_image=$blog['Blog']['image'];
	$this->request->data['Blog']['image']= $cover_image;
	
	try{
		$result=$this->Blog->save($this->request->data);
		 
		if($result){
			$data = array();
			$blog_id= $blog['Blog']['id'];
			
			
			$this->Blog->Blogchannel->deleteAll(array('Blogchannel.blog_id'=>$blog_id),FALSE);
				//print_r($this->request->data['Blog']['channel']);exit();
				foreach ($channels as $cid)
				{
				    $dt=array('Blogchannel' => array('blog_id' => $blog_id,'channel_id'=>$cid));
					array_push($data,$dt);
				}
				if(!empty($data)){
					//print_r($data);exit();
					$this->Blog->Blogchannel->recursive = -1;
					$this->Blog->Blogchannel->create();
					$this->Blog->Blogchannel->saveMany($data);
				}				
			$this->Blog->Post->recursive=-1;		
			$ret= $this->Blog->Post->query("update posts set status = 2 where  blog_id = ".$blog_id);
			
			if($set_publish==1){
				$this->_publish_blog($blog_id,1);
			}
			
			/*$ret= $this->Blog->Post->updateAll(
			    array( 'Post.status' =>'2'),   //fields to update
			    array( 'Post.blog_id' => $blog_id )  //condition
			);*/
			
		}
		
		echo"<script>
		 show_success_msg('".__('the_blog_has_been_saved')."');
		 remove_modal();
		 load_blog_tab(".$blog.");
		 </script>";
		
	} catch (Exception $e) {
		 echo"<script>
		 show_success_msg('".__('the_bloh_could_not_be_saved')."');
		 refresh_last_blog(0);
		 remove_modal();
		  load_blog_tab(".$blog.");
		 </script>";
	}
}

public function _publish_blog($blog_id,$type) {     
   $User_Info= $this->Session->read('User_Info');
	
   $this->Blog->recursive = -1;
    $options= array();
	$options['fields'] = array(
			'Blog.id',		
			'Blog.image',
			'Blog.title',
			'Blog.body'
		   );
	$options['conditions'] = array(
		"Blog.id"=> $blog_id
	);   
	$blog = $this->Blog->find('first',$options);
	
	$this->Blog->Post->recursive=-1;
	$options= array();
	$options['fields'] = array(
			'Post.id',		
		   );
	$options['conditions'] = array(
		"Post.blog_id"=> $blog_id
	);   
	$post = $this->Blog->Post->find('first',$options);
	
	$ret= $this->Blog->query("update blogs set status = 1 where id =".$blog_id);		
	
	$this->request->data=array();
	
	if(!empty($post)){
		$this->request->data['Post']['id'] = $post['Post']['id'];
		
	}
	else{
		$this->request->data['Post']['blog_id'] = $blog['Blog']['id'];
		$this->request->data['Post']['user_id']= $User_Info['id'];
		$this->request->data['Post']['url'] = __SITE_URL.'blogs/view/'.$blog['Blog']['id'];
	}
		$this->request->data['Post']['status'] = 0;
		$this->request->data['Post']['url_title'] = $blog['Blog']['title'];
		//$this->request->data['Post']['url_content'] = trim(mb_substr($blog['Blog']['body'],0,100));;
		$this->request->data['Post']['url_image'] = __SITE_URL.__USER_BLOG_PATH."/".$blog['Blog']['image'];		
	
	
		if($this->Blog->Post->save($this->request->data))
		{
			$sql="INSERT INTO notifications 		(
							notification_id,
							from_user_id,
							to_user_id,
							type,
							notification_type,
							notification_body,
							created
							 )
			SELECT          ".$blog_id.",
							".$User_Info['id'].",
							Follow.from_user_id,
							".$type.",
							5,
							'".substr($blog['Blog']['title'],0,35)."',
							now()
				From follows as Follow 
				 where Follow.to_user_id = ".$User_Info['id']."	
					";
			$ret=$this->Blog->query($sql);	
			
			if(empty($post)){
				
				
				/// send email
				
				/*$Email = new CakeEmail();
				$Email->template('mention_sendemail', 'sendemail_layout');
				$Email->subject(__('used_your_user_name'));
				$Email->emailFormat('html');
				$Email->to($user['User']['email']);
				$Email->from(array(__Madaner_Email => __Email_Name));
				$Email->viewVars(array('from_name'=>$User_Info['name'],'from_user_name'=>$User_Info['user_name'],'to_name'=>$user['User']['name'],'text'=>$this->request->data['Post']['body'],'email'=>$user['User']['email'],'name'=>$user['User']['name'],'image'=>$User_Info['image'],'post_id'=>$post_id,'sex'=>$User_Info['sex']));
				$Email->send();*/
				
				
					
				$post_id= $this->Blog->Post->getLastInsertID();
				$this->Blog->Post->Allpost->recursive=-1;
				$this->request->data = array();			
				$this->request->data['Allpost']['post_id']= $post_id;
				$this->request->data['Allpost']['user_id']= $User_Info['id'];
				$this->request->data['Allpost']['type']=0;
				$this->request->data['Allpost']['created']=date('Y-m-d H:i:s');
				$this->request->data=Sanitize::clean($this->request->data);
		        $this->Blog->Post->Allpost->create();
				$this->Blog->Post->Allpost->save($this->request->data);			
			}
		}
		
	}
/**
* 
* @param undefined $blog_id
* 
*/
function add_blog($blog_id=0){
 	
	$User_Info= $this->Session->read('User_Info');
	
	$this->Blog->recursive = -1;
	$options= array();
	$options['fields'] = array(
			'Blog.id',
			'Blog.title',		
			'Blog.image',
			'Blog.image_x',
			'Blog.image_y',
			'Blog.created'
		   );
	$options['conditions'] = array(
		"Blog.user_id"=> $User_Info['id'] 
	);
	
	$options['order'] = array(
			'Blog.id'=>'desc'
		);	   
	$blogs = $this->Blog->find('all',$options);
	
	
	$this->set('blogs',$blogs);
	$this->set('blog_id',$blog_id);
	
	if($blog_id==0){
		$this->set('title_for_layout',__('add_new_blog'));
		$this->set('description_for_layout',__('add_new_blog'));
		$this->set('keywords_for_layout',__('add_new_blog'));
	}else
	{
		$this->set('title_for_layout',__('edit_blog'));
		$this->set('description_for_layout',__('edit_blog'));
		$this->set('keywords_for_layout',__('edit_blog'));
	}
	
 }
 /**
 * 
 * 
*/
 function admin_index()
	{
		//$this->Blog->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		if(isset($this->request->data['Blog']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Blog.title_per LIKE' => ''.$this->request->data['Blog']['search'].'%'),
				'limit' => $limit,
				'order' => array(
					'Blog.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				/*'joins'=>array(
				
				),*/
				'fields'=>array(
				'User.name',
				'Blog.id',
				'Blog.title',
				'Blog.num_viewed',
				'Blog.status',
				'Blog.created',
				'Blog.image'
				),
				'limit' => $limit,
				'order' => array(
					'Blog.id' => 'desc'
				)
			);
		}		
		$blogs = $this->paginate('Blog');
		$this->set(compact('blogs'));
	}

/**
* 
* 
*/
	function admin_add()
	{
		$this->Blog->recursive = -1;
		if($this->request->is('post'))
		{
			$User_Info= $this->Session->read('AdminUser_Info');
			$this->request->data['Blog']['user_id']= $User_Info['id'];
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Blog->create();
			if($this->Blog->save($this->request->data))
			{
				$blog_id= $this->Blog->getLastInsertID();
				 
				if(isset($this->request->data['Blog']['tag'])&& !empty($this->request->data['Blog']['tag'])){
					$tags=explode('#',$this->request->data['Blog']['tag']);
					if(!empty($tags)){
						foreach($tags as $tag)
						{
							$this->request->data['Blogtag']['title']= $tag;
							$this->Blog->Blogrelatetag->Blogtag->create();
							
							if($this->Blog->Blogrelatetag->Blogtag->save($this->request->data))
							{
								$tag_id[]=$this->Blog->Blogrelatetag->Blogtag->getLastInsertID();					
							}
						}
					}
					
				}
				$data = array();
				if(isset($_POST['tag_id_arr'])){
					foreach($_POST['tag_id_arr'] as $id)
					{
						$dt=array('Blogrelatetag' => array('blog_id' => $blog_id,'blogtag_id'=>$id));
						array_push($data,$dt);
					}
				}
				
				if(!empty($tag_id))
					{
						foreach($tag_id as $tid)
						{
							$dt=array('Blogrelatetag' => array('blog_id' => $blog_id,'blogtag_id'=>$tid));
							array_push($data,$dt);
						}
						
					}
				 
				if($_POST['tag_id_arr'] || !empty($tag_id))
				{
					$this->Blog->Blogrelatetag->create();
					$this->Blog->Blogrelatetag->saveMany($data);
				}
				
				
				$this->Session->setFlash(__('the_blog_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_blog_could_not_be_saved'));
			}
		}
		 
		
	}

/**
* 
* @param undefined $id
* 
*/
	function _set_blog($id)
	{
		$this->Blog->recursive = -1;
		$this->Blog->id = $id;
		if(!$this->Blog->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_blog'));
			return;
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Blog->findById($id);
	    }
	    
	    $this->set('blog', $this->request->data);
	    
	    return $this->request->data;
	}

/**
* 
* @param undefined $id
* 
*/
	function admin_edit($id = null)
	{
		$this->Blog->id = $id;
		if(!$this->Blog->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_blog'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			
			$this->Blog->recursive = -1;
			$options= array();
			$options['fields'] = array(
					'Blog.id',
					'Blog.title',		
					'Blog.image',
					'Blog.created'
				   );
			$options['conditions'] = array(
				"Blog.id"=> $id
			);
			   
			$blog = $this->Blog->find('first',$options);
			
			
			$User_Info= $this->Session->read('User_Info');
			//$this->request->data['Blog']['status']= 0;
			$this->request->data['Blog']['id']= $id;
			$this->request->data['Blog']['body']= trim($this->request->data['Blog']['body']);
			$this->request->data=Sanitize::clean($this->request->data);
			$data=Sanitize::clean($this->request->data);
			$channels=$this->request->data['Blog']['channel'];
			
			$set_publish = $this->request->data['Blog']['status'];
	
			$file = $data['Blog']['image'];
			 
			if($file['size']>0){
		        $output=$this->_blog_picture();
		    	if(!$output['error']) 
		    	{
		    		$cover_image=$output['filename'];
		    	}	
		        else 
		        {
		            $cover_image='';
		            echo"<script>show_warning_msg('".$output['message']."');remove_modal();</script>";
		            return;
		        }
		    }
		    else $cover_image=$blog['Blog']['image'];
			$this->request->data['Blog']['image']= $cover_image;
			
			try{
				$result=$this->Blog->save($this->request->data);
				 
				if($result){
					$data = array();
					$blog_id= $blog['Blog']['id'];
					if($set_publish==1){
						//print_r($blog_id);exit();
						$this->_publish_blog($blog_id,1);
					}
					
					$this->Blog->Blogchannel->deleteAll(array('Blogchannel.blog_id'=>$blog_id),FALSE);
						//print_r($this->request->data['Blog']['channel']);exit();
						foreach ($channels as $cid)
						{
						    $dt=array('Blogchannel' => array('blog_id' => $blog_id,'channel_id'=>$cid));
							array_push($data,$dt);
						}
						if(!empty($data)){
							//print_r($data);exit();
							$this->Blog->Blogchannel->recursive = -1;
							$this->Blog->Blogchannel->create();
							$this->Blog->Blogchannel->saveMany($data);
						}				
					$this->Blog->Post->recursive=-1;
					
					$ret= $this->Blog->Post->query("update posts set status = 2 where  blog_id = ".$blog_id);
				
					
				}
				
				$this->Session->setFlash(__('the_blog_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
				
			} catch (Exception $e) {
				 $this->Session->setFlash(__('the_blog_could_not_be_saved'));
			}				 
					
					/*
					$this->Session->setFlash(__('the_blog_has_been_saved'), 'admin_success');
					$this->redirect(array('action' => 'index'));
				
				$this->Session->setFlash(__('the_blog_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_blog_could_not_be_saved'));
			}*/
		}
		
		$this->Blog->Blogchannel->Channel->recursive = -1;
		$options['fields'] = array(
				'Channel.id',
				'Channel.title_'.$this->Session->read('Config.language').' as title',
				
			   );
		$options['joins'] = array(
				array('table' => 'blog_channels',
					'alias' => 'Blogchannel',
					'type' => 'INNER',
					'conditions' => array(
					'Blogchannel.channel_id = Channel.id ',
					)
				) 
				
			);	
				   
		$options['conditions'] = array(
			"Blogchannel.blog_id"=>$id 
		);
		
		$options['order'] = array(
				'Channel.id'=>'desc'
			);	
		$cur_channels = $this->Blog->Blogchannel->Channel->find('all',$options);
		$this->set('cur_channels',$cur_channels);
		
		$this->Blog->Blogchannel->Channel->recursive = -1;
		$options= array();
		$options['fields'] = array(
				'Channel.id',
				'Channel.title_'.$this->Session->read('Config.language').' as title',		
			   );
		$channels = $this->Blog->Blogchannel->Channel->find('all',$options);
		
		
		$this->set('channels',$channels);
		
		$this->_set_blog($id);
		
	}
	
	
	function admin_manager()
	{
		
	}

	function admin_delete($id = null)
	{
		$this->Blog->id = $id;
		if(!$this->Blog->exists())
		{
			//$this->Session->setFlash(__('invalid_id_for_blog'));
			$this->Session->setFlash(__('invalid_id_for_blog'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		$result= $this->Blog->findById($id);
		if($this->Blog->delete($id))
		{
			$this->Blog->Post->recursive=-1;
			$options= array();
			$options['fields'] = array(
					'Post.id',		
				   );
			$options['conditions'] = array(
				"Post.blog_id"=> $id
			);   
			$post = $this->Blog->Post->find('first',$options);
						
			if($this->Blog->Post->deleteAll(array('Post.id' => $post['Post']['id']), false)){
				$options= array();
				$options['fields'] = array(
						'Post.id',		
						'Post.image',		
					   );
				$options['conditions'] = array(
					"Post.parent_id"=> $post['Post']['id']
				);   
				$posts = $this->Blog->Post->find('all',$options);
				if(!empty($posts)){
					foreach($posts as $child_post){
						if($this->Blog->Post->delete($child_post['Post']['id'])){
					   	try{
						   	 @unlink(__POST_IMAGE_PATH."/".$child_post['Post']['image']);
						     @unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$child_post['Post']['image']);
						   } catch (Exception $e) {
						   	
						   }
					    }
					}
				}
			}
			
			
			
			@unlink(__USER_BLOG_PATH."/".$blog['Blog']['image']);
			@unlink(__USER_BLOG_PATH."/".__UPLOAD_THUMB."/".$blog['Blog']['image']);
			
			
			$this->Session->setFlash(__('delete_blog_success'), 'admin_success');

			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('delete_blog_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
	}
 
 /**
 * 
 * 
*/
 function refresh_blog(){
 	
	$this->Blog->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	$options['fields'] = array(
				'User.id',
				'User.name',
				'User.user_name',
				'User.role_id',
				'Blog.id',
				'Blog.title_'.$this->Session->read('Config.language').' as title',
				'Blog.body_'.$this->Session->read('Config.language').' as body',
				'Blog.little_body_'.$this->Session->read('Config.language').' as little_body',
				'Blog.num_viewed',
				'Blog.created'
			   );
	$options['joins'] = array(
			array('table' => 'users',
				'alias' => 'User',
				'type' => 'LEFT',
				'conditions' => array(
				'User.id = `Blog`.user_id ',
				)
			) 
			
		);	
			   
	$options['conditions'] = array(
		"Blog.status"=>1 
	);
	
	$options['order'] = array(
			'Blog.id'=>'desc'
		);
	$options['limit'] = $end;
	$options['offset'] = $first;
	
	$blogs = $this->Blog->find('all',$options);
	$this->set(compact('blogs'));
	$this->render('/Elements/Blogs/Ajax/refresh_blog', 'ajax');
	
 }
 
 /**
 * 
 * @param undefined $id
 * 
*/

 

function refresh_tag(){
 	
	$this->Blog->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	if(isset($_REQUEST['tag_id'])){
		$tag_id= $_REQUEST['tag_id'];
	}	
	
	$User_Info= $this->Session->read('User_Info');
	
	$options['fields'] = array(
				'distinct(Blog.id) as id',
				'User.id',
				'User.name',
				'User.user_name',
				'User.role_id',
				'Blog.title_'.$this->Session->read('Config.language').' as title',
				'Blog.body_'.$this->Session->read('Config.language').' as body',
				'Blog.little_body_'.$this->Session->read('Config.language').' as little_body',
				'Blog.num_viewed',
				'Blog.created'
			   );
	$options['joins'] = array(
			array('table' => 'users',
				'alias' => 'User',
				'type' => 'LEFT',
				'conditions' => array(
				'User.id = `Blog`.user_id ',
				)
			),
			array('table' => 'blogrelatetags',
				'alias' => 'Blogrelatetag',
				'type' => 'LEFT',
				'conditions' => array(
				'Blogrelatetag.blog_id = `Blog`.id ',
				)
			) 
			
		);	
			   
	$options['conditions'] = array(
		"Blog.status"=>1 ,
		"Blogrelatetag.blogtag_id"=>$tag_id
	);
	
	$options['order'] = array(
			'Blog.id'=>'desc'
		);
	$options['limit'] = $end;
	$options['offset'] = $first;
	
	$blogs = $this->Blog->find('all',$options);
	$this->set(compact('blogs'));
	$this->render('/Elements/Blogs/Ajax/refresh_blog', 'ajax');
	
 }
 /**
 * 
 * 
*/
 function add_search(){
 	
	$this->Blog->recursive = -1;
	
	if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	}else $first = 0;
	$end=5;
	
	if(isset($_REQUEST['year'])){
		$year= $_REQUEST['year'];
	}else $year='';
	if(isset($_REQUEST['month'])){
		$month= $_REQUEST['month'];
	}else $month='';
	if(isset($_REQUEST['writer'])){
		$writer= $_REQUEST['writer'];
	}else $writer='';
	if(isset($_REQUEST['search_text'])){
		$search_text= $_REQUEST['search_text'];
	}else $search_text='';
	if(isset($_REQUEST['tag'])){
		$tag= $_REQUEST['tag'];
	}else $tag='';	
	
	$User_Info= $this->Session->read('User_Info');
	
	$options['fields'] = array(
				'DISTINCT (Blog.id)',
				'User.id',
				'User.name',
				'User.user_name',
				'User.role_id',
				'Blog.title_'.$this->Session->read('Config.language').' as title',
				'Blog.body_'.$this->Session->read('Config.language').' as body',
				'Blog.little_body_'.$this->Session->read('Config.language').' as little_body',
				'Blog.num_viewed',
				'Blog.created'
			   );
	$options['joins'] = array(
			array('table' => 'users',
				'alias' => 'User',
				'type' => 'LEFT',
				'conditions' => array(
				'User.id = `Blog`.user_id ',
				)
			),
			array('table' => 'blogrelatetags',
				'alias' => 'Blogrelatetag',
				'type' => 'LEFT',
				'conditions' => array(
				'Blogrelatetag.blog_id = `Blog`.id ',
				)
			),
			array('table' => 'blogtags',
				'alias' => 'Blogtag',
				'type' => 'LEFT',
				'conditions' => array(
				'Blogtag.id = `Blogrelatetag`.blogtag_id ',
				)
			)			
		);	
			   
	$options['conditions'] = array(
		"Blog.status"=>1 ,
		"date(Blog.created) like "=> $year."%" ,
		"User.name like "=> $writer."%" ,
		"Blog.title_".$this->Session->read('Config.language')." like "=> $search_text."%",
		"Blogtag.title like "=> $tag."%" ,
	);
	
	if(isset($month) && (isset($year) && !empty($year) )){
		$options['conditions'] = array(
			"Blog.status"=>1 ,
			"date(Blog.created) like "=> $year.'-'.$month."%" ,
			"User.name like "=> $writer."%" ,
			"Blog.title_".$this->Session->read('Config.language')." like "=> $search_text."%",
			"Blogtag.title like "=> $tag."%" ,
		);
	}
	
	
	$options['order'] = array(
			'Blog.id'=>'desc'
		);
	$options['limit'] = $end;
	$options['offset'] = $first;
	
	$blogs = $this->Blog->find('all',$options);
	$this->set(compact('blogs'));
	$this->render('/Elements/Blogs/Ajax/refresh_blog', 'ajax');
	
 }
 
 
 /**
 * 
 * @param undefined $id
 * 
*/
  function view($id=null){
 	
	$this->Blog->recursive = -1;	
	
	
	$this->Blog->id = $id;
	if(!$this->Blog->exists())
	{
		$this->Session->setFlash(__('not_valid_blog'),'error');
		$this->set('title_for_layout',__('not_valid_blog'));
		return;
	}
	
	$User_Info= $this->Session->read('User_Info');
	
	$ret= $this->Blog->updateAll(
	    array( 'Blog.num_viewed' => 'Blog.num_viewed + 1' ),   //fields to update
	    array( 'Blog.id' => $id )  //condition
	  );
	if(!empty($User_Info)){
		$options['fields'] = array(
				'User.id',
				'User.name',
				'User.user_name',
				'User.image',
				'User.sex',
				'User.headline',
				'Blog.id',
				'Blog.title',
				'Blog.body',
				'Blog.image',
				'Blog.num_viewed',
				'Blog.image_x',
				'Blog.image_y',
				'Blog.image_zoom',
				'Blog.created',
				'Blog.favoritecount',
				'(select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id  )as share_count ',
				'(select count(*) from `blog_comments`  where blog_id=Blog.id  )as replay_count ',
				'(select count(*) from `favorite_blogs` as FavoriteBlog where FavoriteBlog.blog_id=Blog.id  
				and FavoriteBlog.user_id = '.$User_Info['id'].')as me_favorite'
			   );
	}else{
		$options['fields'] = array(
				'User.id',
				'User.name',
				'User.user_name',
				'User.image',
				'User.sex',
				'User.headline',
				'Blog.id',
				'Blog.title',
				'Blog.body',
				'Blog.image',
				'Blog.num_viewed',
				'Blog.image_x',
				'Blog.image_y',
				'Blog.image_zoom',
				'Blog.created',
				'Blog.favoritecount',
				'(select count(*) from `shareposts` as Sharepost where Sharepost.post_id=Post.id  )as share_count ',
				'(select count(*) from `blog_comments`  where blog_id=Blog.id  )as replay_count '
			   );
	}
	
	$options['joins'] = array(
			array('table' => 'users',
				'alias' => 'User',
				'type' => 'INNER',
				'conditions' => array(
				'User.id = `Blog`.user_id ',
				)
			) ,
			array('table' => 'posts',
				'alias' => 'Post',
				'type' => 'INNER',
				'conditions' => array(
				'Blog.id = Post.blog_id ',
				)
			)
			
		);	
			   
	$options['conditions'] = array(
		"Blog.status"=>1 ,
		"Blog.id"=>$id
	);
	
	$blog = $this->Blog->find('first',$options);
	$this->set(compact('blog'));
	
	$this->Blog->User->recursive = -1;
	
	$is_follow = $this->Blog->User->Follow->find('count', array('conditions' => array('Follow.from_user_id' => $User_Info['id'],'Follow.to_user_id'=>$blog['User']['id'])));
		$this->set(compact('is_follow'));
	
	
	
	/*
	
	$this->Blog->Blogrelatetag->Blogtag->recursive = -1;
	$options['fields'] = array(
			'Blogtag.id',
			'Blogtag.title'
		   );
	$options['joins'] = array(
			array('table' => 'blogrelatetags',
				'alias' => 'Blogrelatetag',
				'type' => 'LEFT',
				'conditions' => array(
				'Blogrelatetag.blogtag_id = Blogtag.id ',
				)
			) 
			
		);	
			   
	$options['conditions'] = array(
		"Blogrelatetag.blog_id"=>$id 
	);
	
	$options['order'] = array(
			'Blogtag.id'=>'desc'
		);
	
	$tags = $this->Blog->Blogrelatetag->Blogtag->find('all',$options);
	
	$tag_str='';
	if(!empty($tags)){
		foreach($tags as $tag)
		{
			$tag_str=$tag_str.$tag['Blogtag']['title'].',';
		}
	}*/
	
	$this->set('title_for_layout',$blog['Blog']['title']);
	$this->set('description_for_layout',substr($blog['Blog']['body'],0,50));
	//$this->set('keywords_for_layout',$tag_str);
	
	$this->set('blog_id',$id);
	$this->set('title',$blog['Blog']['title']);
 }
 
function user_count()
{
	$this->Blog->recursive = -1;
	$options['conditions'] = array(
		'Blog.status' => 1
	);
	return  $this->Blog->find('count',$options); 
}

function blog_count()
 {
 	$this->Blog->recursive = -1;
	$response['count'] = 0;
	
	$User_Info= $this->Session->read('User_Info');
	
	$blog_count = $this->Blog->find('count', array('conditions' => array('Blog.user_id'=>$User_Info['id'])));
	$response['count'] = $blog_count;
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/Blogs/Ajax/ajax_result', 'ajax');
 }

 function load_blog_form($id=0){	
	if($id!=0){
			$this->Blog->Blogchannel->Channel->recursive = -1;
			$options['fields'] = array(
					'Channel.id',
					'Channel.title_'.$this->Session->read('Config.language').' as title',
					
				   );
			$options['joins'] = array(
					array('table' => 'blog_channels',
						'alias' => 'Blogchannel',
						'type' => 'INNER',
						'conditions' => array(
						'Blogchannel.channel_id = Channel.id ',
						)
					) 
					
				);	
					   
			$options['conditions'] = array(
				"Blogchannel.blog_id"=>$id 
			);
			
			$options['order'] = array(
					'Channel.id'=>'desc'
				);	
			$cur_channels = $this->Blog->Blogchannel->Channel->find('all',$options);
			$this->set('cur_channels',$cur_channels);
			
			$options= array();
			$options['fields'] = array(
					'Blog.id',
					'Blog.title',		
					'Blog.body',		
					'Blog.image',
					'Blog.image_x',
					'Blog.image_y',
					'Blog.image_zoom',
					'Blog.created'
				   );
			$options['conditions'] = array(
				"Blog.id"=> $id
			);  
			$blog= $this->Blog->find('first',$options);
			$this->set('blog',$blog);
	}
	
	$this->Blog->Blogchannel->Channel->recursive = -1;
	$options= array();
	$options['fields'] = array(
			'Channel.id',
			'Channel.title_'.$this->Session->read('Config.language').' as title',		
		   );
	$channels = $this->Blog->Blogchannel->Channel->find('all',$options);
	
	
	$this->set('channels',$channels);
	
	
	$this->render('/Elements/Blogs/Ajax/blog_form', 'ajax');	
 }
 
 /**
 * 
 * @param undefined $id
 * 
*/
  function load_blog_tab($blog_id=0){	
	$User_Info= $this->Session->read('User_Info');
		$this->Blog->recursive = -1;
		$options= array();
		$options['fields'] = array(
				'Blog.id',
				'LEFT(Blog.title,60) as title',		
				'Blog.image',
				'Blog.created'
			   );
		$options['conditions'] = array(
			"Blog.user_id"=> $User_Info['id'] 
		);
		
		$options['order'] = array(
				'Blog.id'=>'desc'
			);	   
		$blogs = $this->Blog->find('all',$options);
		
		
		$this->set('blogs',$blogs);
	
	$this->set('blog_id',$blog_id);	
	$this->render('/Elements/Blogs/Ajax/blog_tab', 'ajax');	
 }
 
 public function delete_image() {
   $response['success'] = false;
   $response['message'] = null;
   
   $blog_id=Sanitize::clean($_REQUEST['blog_id']);
    
   $this->Blog->recursive = -1;
    $options= array();
	$options['fields'] = array(
			'Blog.id',		
			'Blog.image',
		   );
	$options['conditions'] = array(
		"Blog.id"=> $blog_id
	);   
	$blog = $this->Blog->find('first',$options);
   $this->Blog->recursive = -1;
   
   try{
   		$ret= $this->Blog->query("update blogs set image = '' where id =".$blog_id);			 
		@unlink(__USER_BLOG_PATH."/".$blog['Blog']['image']);
		@unlink(__USER_BLOG_PATH."/".__UPLOAD_THUMB."/".$blog['Blog']['image']);
		
		$response['success'] =  TRUE;
   } catch (Exception $e) {
   		$response['success'] =  FALSE;
   }
   
      
   $response['message'] = $response['success'] ? '' : __('image_not_deleted');
   $this->set('ajaxData',  json_encode($response));
   $this->render('/Elements/Blogs/Ajax/ajax_result', 'ajax');  
} 

/**
* 
* 
*/
 public function delete_blog() {
   $response['success'] = false;
   $response['message'] = null;
   
   $blog_id=Sanitize::clean($_REQUEST['blog_id']);
    
   $this->Blog->recursive = -1;
    $options= array();
	$options['fields'] = array(
			'Blog.id',		
			'Blog.image',
		   );
	$options['conditions'] = array(
		"Blog.id"=> $blog_id
	);   
	$blog = $this->Blog->find('first',$options);
   $this->Blog->recursive = -1;
   
   try{
   		if($this->Blog->delete($blog_id)){
			
			$this->Blog->Post->recursive=-1;
			$options= array();
			$options['fields'] = array(
					'Post.id',		
				   );
			$options['conditions'] = array(
				"Post.blog_id"=> $blog_id
			);   
			$post = $this->Blog->Post->find('first',$options);
						
			if($this->Blog->Post->deleteAll(array('Post.id' => $post['Post']['id']), false)){
				$options= array();
				$options['fields'] = array(
						'Post.id',		
						'Post.image',		
					   );
				$options['conditions'] = array(
					"Post.parent_id"=> $post['Post']['id']
				);   
				$posts = $this->Blog->Post->find('all',$options);
				if(!empty($posts)){
					foreach($posts as $child_post){
						if($this->Blog->Post->delete($child_post['Post']['id'])){
					   	try{
						   	 @unlink(__POST_IMAGE_PATH."/".$child_post['Post']['image']);
						     @unlink(__POST_IMAGE_PATH."/".__UPLOAD_THUMB."/".$child_post['Post']['image']);
						   } catch (Exception $e) {
						   	
						   }
					    }
					}
				}
			}
			
			
			
			@unlink(__USER_BLOG_PATH."/".$blog['Blog']['image']);
			@unlink(__USER_BLOG_PATH."/".__UPLOAD_THUMB."/".$blog['Blog']['image']);
			$response['success'] =  TRUE;
		}				 
   } catch (Exception $e) {
   		$response['success'] =  FALSE;
   }
   
      
   $response['message'] = $response['success'] ? '' : __('blog_not_deleted');
   $this->set('ajaxData',  json_encode($response));
   $this->render('/Elements/Blogs/Ajax/ajax_result', 'ajax');  
}
/**
* 
* 
*/
 public function publish_blog() {
   $response['success'] = false;
   $response['message'] = null;
   
   $blog_id=Sanitize::clean($_REQUEST['blog_id']);
    
   $User_Info= $this->Session->read('User_Info');
	
   $this->Blog->recursive = -1;
    $options= array();
	$options['fields'] = array(
			'Blog.id',		
			'Blog.image',
			'Blog.title',
			'Blog.body'
		   );
	$options['conditions'] = array(
		"Blog.id"=> $blog_id
	);   
	$blog = $this->Blog->find('first',$options);
	
	$this->Blog->Post->recursive=-1;
	$options= array();
	$options['fields'] = array(
			'Post.id',		
		   );
	$options['conditions'] = array(
		"Post.blog_id"=> $blog_id
	);   
	$post = $this->Blog->Post->find('first',$options);
	
	$ret= $this->Blog->query("update blogs set status = 1 where id =".$blog_id);		
	
	$this->request->data=array();
	
	if(!empty($post)){
		$this->request->data['Post']['id'] = $post['Post']['id'];
		$this->request->data['Post']['status'] = 0;
	}
	else{
		$this->request->data['Post']['blog_id'] = $blog['Blog']['id'];
		$this->request->data['Post']['user_id']= $User_Info['id'];
		$this->request->data['Post']['url'] = __SITE_URL.'blogs/view/'.$blog['Blog']['id'];
	}
	
	$this->request->data['Post']['url_title'] = $blog['Blog']['title'];
	$this->request->data['Post']['url_content'] = trim(mb_substr($blog['Blog']['body'],0,100));;
	$this->request->data['Post']['url_image'] = __SITE_URL.__USER_BLOG_PATH."/".$blog['Blog']['image'];		
	
	
		if($this->Blog->Post->save($this->request->data))
		{
			if(empty($post)){
				$post_id= $this->Blog->Post->getLastInsertID();
				$this->Blog->Post->Allpost->recursive=-1;
				$this->request->data = array();			
				$this->request->data['Allpost']['post_id']= $post_id;
				$this->request->data['Allpost']['user_id']= $User_Info['id'];
				$this->request->data['Allpost']['type']=0;
				$this->request->data['Allpost']['created']=date('Y-m-d H:i:s');
				$this->request->data=Sanitize::clean($this->request->data);
		        $this->Blog->Post->Allpost->create();
				$this->Blog->Post->Allpost->save($this->request->data);			
			}
			$response['success'] = TRUE;
		}
	  
   $response['message'] = $response['success'] ? __('blog_preview_save') : __('blog_preview_not_save');
   $this->set('ajaxData',  json_encode($response));
   $this->render('/Elements/Blogs/Ajax/ajax_result', 'ajax');  
}

function get_last_blog()
{ 
	 if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	 }else $first = 0;
	 $end=10;									
										
     $User_Info= $this->Session->read('User_Info');
	 $blogs = $this->Blog->query("
	 	select 
			User.id ,
			User.name ,
			User.sex ,
			User.email ,
			User.image ,
			User.user_name ,
			User.user_type , 
			User.image , 
			User.sex , 
			Blog.id,
			LEFT(Blog.title,60) as title,
			Blog.body,
			Blog.image,
			Blog.created
			from blogs as Blog 
			inner join users as User
					on User.id = Blog.user_id
			where Blog.status = 1 		
			order by Blog.id desc
		 limit ".$first." , ".$end." 
	 ");
	 
	 
     $this->set(array(
		'blogs' => $blogs,
		'_serialize' => array('blogs')
	  ));
	
	$this->render('/Elements/Blogs/Ajax/refresh_last_blog', 'ajax');
 } 
 
function get_my_blog($user_id,$blog_id)
{ 
	 if(isset($_REQUEST['first'])){
		$first= $_REQUEST['first'];
	 }else $first = 0;
	 $end=10;									
										
     $User_Info= $this->Session->read('User_Info');
	 $blogs = $this->Blog->query("
	 	select 
			User.id ,
			User.name ,
			User.sex ,
			User.email ,
			User.image ,
			User.user_name ,
			User.user_type , 
			User.image , 
			User.sex , 
			Blog.id,
			LEFT(Blog.title,60) as title,
			Blog.body,
			Blog.image,
			Blog.created
			from blogs as Blog 
			inner join users as User
					on User.id = Blog.user_id
			where Blog.status = 1 	
			  and user_id =  ".$user_id."	
			  and Blog.id <>  ".$blog_id."	
			order by Blog.id desc
		 limit ".$first." , ".$end." 
	 ");
	 
	 
     $this->set(array(
		'blogs' => $blogs,
		'_serialize' => array('blogs')
	  ));
	
	$this->render('/Elements/Blogs/Ajax/refresh_last_blog', 'ajax');
 }  
 
 
 
}
