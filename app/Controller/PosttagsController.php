<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class PosttagsController extends AppController {

   var $name = 'Posttags';	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/

 /**
 * search on tag
 * 
*/
 function search(){
	$this->Posttag->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$options['fields'] = array(
				'Posttag.id',
				'Posttag.title'
			   );
		$options['joins'] = array(
				array('table' => 'postrelatetags',
					'alias' => 'Postrelatetag',
					'type' => 'LEFT',
					'conditions' => array(
					'Posttag.id = `Postrelatetag`.posttag_id ',
					)
				) ,
				
				array('table' => 'posts',
					'alias' => 'Post',
					'type' => 'LEFT',
					'conditions' => array(
					'`Postrelatetag`.post_id = `Post`.id ',
					)
				)
				
			);	
				   
		$options['conditions'] = array(
			   'Posttag.title LIKE'=> "$search_word%" ,
		);
		
		$options['order'] = array(
				'Posttag.id'=>'desc'
			);
		$options['limit'] = 3;
		
		$posts = $this->Posttag->find('all',$options);
		$this->set(compact('posts'));
}
 
 /**
 * 
 * 
*/
  function search_suggest(){
	$this->Posttag->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$options['fields'] = array(
				'Posttag.id',
				'Posttag.title'
			   );
				   
		$options['conditions'] = array(
			   'Posttag.title LIKE'=> "$search_word%" ,
		);
		
		$options['order'] = array(
				'Posttag.id'=>'desc'
			);
		$options['limit'] = 3;
		
		$posttags = $this->Posttag->find('all',$options);
		$this->set(compact('posttags'));
	
		$this->render('/Elements/Posts/Ajax/search_suggest','ajax');
}

 function admin_tag_search()
 {
 	$User_Info= $this->Session->read('User_Info');
	$search_word =  $_GET["term"] ;
	$options['fields'] = array(
				'Posttag.id',
				'Posttag.title'
			   );
				   
	$options['conditions'] = array(
		   'Posttag.title LIKE'=> "$search_word%" ,
	);
	
	$options['order'] = array(
			'Posttag.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$posttags = $this->Posttag->find('all',$options);
    $this->set('search_result',$posttags);
    $this->render('/Elements/Posts/Ajax/tag_search','ajax'); 											 
 }
 
 
 /**
 * 
 */
 
 function get_post_tag($id=null)
 {
 	$this->Posttag->recursive = -1;
		$options['fields'] = array(
				'Posttag.id',
				'Posttag.title'
			   );
		$options['joins'] = array(
				array('table' => 'postrelatetags',
					'alias' => 'Postrelatetag',
					'type' => 'LEFT',
					'conditions' => array(
					'Postrelatetag.posttag_id = Posttag.id ',
					)
				) 
				
			);	
				   
		$options['conditions'] = array(
			"Postrelatetag.post_id"=>$id 
		);
		
		$options['order'] = array(
				'Posttag.id'=>'desc'
			);
		
		$tags = $this->Posttag->find('all',$options);
		//$this->set(compact('tags'));
		return $tags;
 }
 
 
 /**
 * 
 * 
*/
 function last_tag_list(){
 	 $this->Posttag->recursive = -1;
	 $tags = $this->Posttag->query(
	 	"
			select * from (
				select 
					distinct(Posttag.id) ,
					Posttag.title  ,
					(select count(*) from  postrelatetags where posttag_id=Posttag.id) as count
					
			    from posttags as Posttag
				
				order by Posttag.id desc limit 0 , 15
			) as Posttag 
		"
	 );
	
		return $tags; 
 }
 
 
 
 
}
