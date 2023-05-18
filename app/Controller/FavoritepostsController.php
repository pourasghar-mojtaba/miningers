<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class FavoritepostsController  extends AppController {

    var $name = 'Favoriteposts';

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('app_favorite','app_unfavorite','insertcount');
}


function insertcount(){
 	$this->autoRender = false; 
    
    ini_set('max_execution_time', 123456789);
    
	$ret= $this->Favoritepost->Post->updateAll(array( 'Post.favoritecount' =>'0') );
	
	$this->Favoritepost->Post->recursive = -1;
	$options['fields'] = array(
				'count(*) as favoritecount',	
				'Post.id'	
			   );
	$options['joins'] = array(
		array('table' => 'favorite_posts',
			'alias' => 'Favoritepost',
			'type' => 'INNER',
			'conditions' => array(
			'Favoritepost.post_id = Post.id ',
			)
		)		
	);
	$options['group']='Post.id'	;
					   
	$favoriteposts = $this->Favoritepost->Post->find('all',$options);
	//pr($favoriteposts); 

    if(!empty($favoriteposts)){
        foreach($favoriteposts as $favoritepost)
        {
             $ret= $this->Favoritepost->Post->updateAll(
				    array( 'Post.favoritecount' =>'"'.$favoritepost[0]['favoritecount'].'"'),   //fields to update
				    array( 'Post.id' => $favoritepost['Post']['id'])  //condition
				);
        }
    } 
 }

	
function favorite(){
 	
	$response['success'] = false;
	$response['message'] = null;

	$User_Info= $this->Session->read('User_Info');
	
	$options['conditions'] = array(
		'Favoritepost.user_id'=>$User_Info['id'] ,
		'Favoritepost.post_id'=> Sanitize::clean($_REQUEST['post_id'])
	);
	$count = $this->Favoritepost->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeated_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoritePosts/Ajax/ajax_result', 'ajax');
		return;
	}
	
	$this->request->data['Favoritepost']['post_id']= $_REQUEST['post_id'];
	$this->request->data['Favoritepost']['user_id']=$User_Info['id'];
	$this->Favoritepost->create();
	
	$this->request->data=Sanitize::clean($this->request->data);

	try{
		if($this->Favoritepost->save($this->request->data)){
			$response['success'] = TRUE;				
			$this->Favoritepost->query("
				update posts as Post set favoritecount = favoritecount + 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");	
		}
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	
	$response['message'] = $response['success'] ? __('favorite_post_success') : __('favorite_post_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/FavoritePosts/Ajax/ajax_result', 'ajax');
	
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
		'Favoritepost.user_id'=>$User_Info['id'] ,
		'Favoritepost.post_id'=> Sanitize::clean($_REQUEST['post_id'])
	);
	$count = $this->Favoritepost->find('count',$options);
	
	if($count<=0){
		$response['message']=__('not_find_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoritePosts/Ajax/ajax_result', 'ajax');
		return;
	}	
	
	try{
		$this->Favoritepost->deleteAll(array('Favoritepost.post_id' => $_REQUEST['post_id'],'Favoritepost.user_id'=>$User_Info['id']), false);
		$this->Favoritepost->query("
				update posts as Post set favoritecount = favoritecount - 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
		$response['success'] = TRUE;
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	  
	$response['message'] = $response['success'] ? __('unfavorite_post_success') : __('unfavorite_post_notsuccess');
	
	$this->set('ajaxData',  json_encode($response));
	$this->render('/Elements/FavoritePosts/Ajax/ajax_result', 'ajax');
	
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
		'Favoritepost.user_id'=> $user_id ,
		'Favoritepost.post_id'=> Sanitize::clean($_REQUEST['post_id'])
	);
	$count = $this->Favoritepost->find('count',$options);
	
	if($count>0){
		$response['message']=__('not_allow_repeated_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoritePosts/Ajax/ajax_result', 'ajax');
		return;
	}
	
	$this->request->data['Favoritepost']['post_id']= $_REQUEST['post_id'];
	$this->request->data['Favoritepost']['user_id']=$user_id;
	$this->Favoritepost->create();
	
	$this->request->data=Sanitize::clean($this->request->data);
	try{
		if($this->Favoritepost->save($this->request->data)){
			$response['success'] = TRUE;
			$this->Favoritepost->query("
				update posts as Post set favoritecount = favoritecount + 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
		}
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}
	$response['message'] = $response['success'] ? __('favorite_post_success') : __('favorite_post_notsuccess');
	 
    
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
		'Favoritepost.user_id'=> $user_id ,
		'Favoritepost.post_id'=> Sanitize::clean($_REQUEST['post_id'])
	);
	$count = $this->Favoritepost->find('count',$options);
	
	if($count<=0){
		$response['message']=__('not_find_favorite');
		$response['success'] = FALSE;
		$this->set('ajaxData',  json_encode($response));
		$this->render('/Elements/FavoritePosts/Ajax/ajax_result', 'ajax');
		return;
	}	
	
	try{
		$this->Favoritepost->deleteAll(array('Favoritepost.post_id' => $_REQUEST['post_id'],'Favoritepost.user_id'=>$user_id), false);
		$response['success'] = TRUE;
		$this->Favoritepost->query("
				update posts as Post set favoritecount = favoritecount - 1 
					where Post.id = ".Sanitize::clean($_REQUEST['post_id'])."
			");
	} catch (Exception $e) {
		$response['success'] = FALSE;
	}	  
	$response['message'] = $response['success'] ? __('unfavorite_post_success') : __('unfavorite_post_notsuccess');
	
    $this->set(array(
		'response' => $response,
		'_serialize' => array('response')
		));
	
 } 
 
 
		
}

