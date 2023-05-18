<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class BlogtagsController extends AppController {

   var $name = 'Blogtags';	 
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
	$this->Blogtag->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$options['fields'] = array(
				'Blogtag.id',
				'Blogtag.title'
			   );
		$options['joins'] = array(
				array('table' => 'blogrelatetags',
					'alias' => 'Blogrelatetag',
					'type' => 'LEFT',
					'conditions' => array(
					'Blogtag.id = `Blogrelatetag`.blogtag_id ',
					)
				) ,
				
				array('table' => 'blogs',
					'alias' => 'Blog',
					'type' => 'LEFT',
					'conditions' => array(
					'`Blogrelatetag`.blog_id = `Blog`.id ',
					)
				)
				
			);	
				   
		$options['conditions'] = array(
			   'Blogtag.title LIKE'=> "$search_word%" ,
		);
		
		$options['order'] = array(
				'Blogtag.id'=>'desc'
			);
		$options['limit'] = 3;
		
		$blogs = $this->Blogtag->find('all',$options);
		$this->set(compact('blogs'));
}
 
 /**
 * 
 * 
*/
  function search_suggest(){
	$this->Blogtag->recursive = -1;
	$search_word = $_REQUEST['search_word'];
	$options['fields'] = array(
				'Blogtag.id',
				'Blogtag.title'
			   );
				   
		$options['conditions'] = array(
			   'Blogtag.title LIKE'=> "$search_word%" ,
		);
		
		$options['order'] = array(
				'Blogtag.id'=>'desc'
			);
		$options['limit'] = 3;
		
		$blogtags = $this->Blogtag->find('all',$options);
		$this->set(compact('blogtags'));
	
		$this->render('/Elements/Blogs/Ajax/search_suggest','ajax');
}

 function admin_tag_search()
 {
 	$User_Info= $this->Session->read('User_Info');
	$search_word =  $_GET["term"] ;
	$options['fields'] = array(
				'Blogtag.id',
				'Blogtag.title'
			   );
				   
	$options['conditions'] = array(
		   'Blogtag.title LIKE'=> "$search_word%" ,
	);
	
	$options['order'] = array(
			'Blogtag.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$blogtags = $this->Blogtag->find('all',$options);
    $this->set('search_result',$blogtags);
    $this->render('/Elements/Blogs/Ajax/tag_search','ajax'); 											 
 }
 
 
 /**
 * 
 */
 
 function get_blog_tag($id=null)
 {
 	$this->Blogtag->recursive = -1;
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
		
		$tags = $this->Blogtag->find('all',$options);
		//$this->set(compact('tags'));
		return $tags;
 }
 
 
 
 
 
}
