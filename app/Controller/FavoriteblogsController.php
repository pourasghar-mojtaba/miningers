<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class FavoriteblogsController  extends AppController {

    var $name = 'Favoriteblogs';

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('app_favorite','app_unfavorite','insertcount');
}


function insertcount(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$ret= $this->Favoriteblog->Blog->updateAll(array( 'Blog.favoritecount' =>'0') );
	
	$this->Favoriteblog->Blog->recursive = -1;
	$options['fields'] = array(
				'count(*) as favoritecount',	
				'Blog.id'	
			   );
	$options['joins'] = array(
		array('table' => 'favorite_blogs',
			'alias' => 'Favoriteblog',
			'type' => 'INNER',
			'conditions' => array(
			'Favoriteblog.blog_id = Blog.id ',
			)
		)		
	);
	$options['group']='Blog.id'	;
					   
	$favoriteblogs = $this->Favoriteblog->Blog->find('all',$options);
	//pr($favoriteblogs); 

    if(!empty($favoriteblogs)){
        foreach($favoriteblogs as $favoriteblog)
        {
             $ret= $this->Favoriteblog->Blog->updateAll(
				    array( 'Blog.favoritecount' =>'"'.$favoriteblog[0]['favoritecount'].'"'),   //fields to update
				    array( 'Blog.id' => $favoriteblog['Blog']['id'])  //condition
				);
        }
    } 
 }

	
function favorite(){
 	
	$response['success'] = false;
	$response['message'] = null;

	$User_Info= $this->Session->read('User_Info');
	
	$options['conditions'] = array(
		'Favoriteblog.user_id'=>$User_Info['id'] ,
		'Favoriteblog.blog_id'=> Sanitize::clean($_REQUEST['blog_id'])
	);
	$count = $this->Favoriteblog->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeated_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoriteBlogs/Ajax/ajax_result', 'ajax');
		return;
	}
	
	$this->request->data['Favoriteblog']['blog_id']= $_REQUEST['blog_id'];
	$this->request->data['Favoriteblog']['user_id']=$User_Info['id'];
	$this->Favoriteblog->create();
	
	$this->request->data=Sanitize::clean($this->request->data);

	try{
		if($this->Favoriteblog->save($this->request->data)){
			$response['success'] = TRUE;				
			$this->Favoriteblog->query("
				update blogs as Blog set favoritecount = favoritecount + 1 
					where Blog.id = ".Sanitize::clean($_REQUEST['blog_id'])."
			");	
		}
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	
	$response['message'] = $response['success'] ? __('favorite_blog_success') : __('favorite_blog_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/FavoriteBlogs/Ajax/ajax_result', 'ajax');
	
 }
 
 /**
* 
* 
*/
function unfavorite(){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$User_Info= $this->Session->read('User_Info');
	
	$options['conditions'] = array(
		'Favoriteblog.user_id'=>$User_Info['id'] ,
		'Favoriteblog.blog_id'=> Sanitize::clean($_REQUEST['blog_id'])
	);
	$count = $this->Favoriteblog->find('count',$options);
	
	if($count<=0){
		$response['message']=__('not_find_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoriteBlogs/Ajax/ajax_result', 'ajax');
		return;
	}	
	
	try{
		$this->Favoriteblog->deleteAll(array('Favoriteblog.blog_id' => $_REQUEST['blog_id'],'Favoriteblog.user_id'=>$User_Info['id']), false);
		$this->Favoriteblog->query("
				update blogs as Blog set favoritecount = favoritecount - 1 
					where Blog.id = ".Sanitize::clean($_REQUEST['blog_id'])."
			");
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	  
	$response['message'] = $response['success'] ? __('unfavorite_blog_success') : __('unfavorite_blog_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/FavoriteBlogs/Ajax/ajax_result', 'ajax');
	
 }
 
 /**
 * 
 * @param undefined $user_id
 * 
*/
 function app_favorite($user_id){
 	
	$response['success'] = false;
	$response['message'] = null;
	
	$options['conditions'] = array(
		'Favoriteblog.user_id'=> $user_id ,
		'Favoriteblog.blog_id'=> Sanitize::clean($_REQUEST['blog_id'])
	);
	$count = $this->Favoriteblog->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeated_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoriteBlogs/Ajax/ajax_result', 'ajax');
		return;
	}
	
	$this->request->data['Favoriteblog']['blog_id']= $_REQUEST['blog_id'];
	$this->request->data['Favoriteblog']['user_id']=$user_id;
	$this->Favoriteblog->create();
	
	$this->request->data=Sanitize::clean($this->request->data);
	try{
		if($this->Favoriteblog->save($this->request->data)){
			$response['success'] = TRUE;
			$this->Favoriteblog->query("
				update blogs as Blog set favoritecount = favoritecount + 1 
					where Blog.id = ".Sanitize::clean($_REQUEST['blog_id'])."
			");
		}
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	$response['message'] = $response['success'] ? __('favorite_blog_success') : __('favorite_blog_notsuccess');
	 
    
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
 }


 
 /**
 * 
 * @param undefined $user_id
 * 
*/
function app_unfavorite($user_id){
	$response['success'] = false;
	$response['message'] = null;
	
	$options['conditions'] = array(
		'Favoriteblog.user_id'=> $user_id ,
		'Favoriteblog.blog_id'=> Sanitize::clean($_REQUEST['blog_id'])
	);
	$count = $this->Favoriteblog->find('count',$options);
	
	if($count<=0){
		$response['message']=__('not_find_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoriteBlogs/Ajax/ajax_result', 'ajax');
		return;
	}	
	
	try{
		$this->Favoriteblog->deleteAll(array('Favoriteblog.blog_id' => $_REQUEST['blog_id'],'Favoriteblog.user_id'=>$user_id), false);
		$response['success'] = TRUE;
		$this->Favoriteblog->query("
				update blogs as Blog set favoritecount = favoritecount - 1 
					where Blog.id = ".Sanitize::clean($_REQUEST['blog_id'])."
			");
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}	  
	$response['message'] = $response['success'] ? __('unfavorite_blog_success') : __('unfavorite_blog_notsuccess');
	
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 } 
 
 
		
}

