<?php

class SitemapsController extends AppController {
	

var $name = 'Sitemaps';
var $uses = array('Post', 'Info');
var $helpers = array('Time');
var $components = array('RequestHandler');

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow();
}


function index ()
{    
   
   App::import('Model','Post');
   $post= new Post();
   
   $options['fields'] = array(
					'Post.id',
					'Post.url',
					'Post.image',
					'Post.body',
					'Post.created'
				   );
	 $options['contain'] = array(
		'User' => array(
		'fields' => array('id', 'name','sex','image','user_name'),
		'conditions' => '',
		'order' => ''
		)
		);

	$options['order'] = array(
		'Post.id'=>'desc'
	);
	
	$response = $post->find('all',$options); 
	
	$this->set('posts', $response);


    App::import('Model','Page');
    $page= new Page();
   
   $options1['fields'] = array(
				'Page.id',
				'Page.title_per as title',
				'Page.body_per as body',
				'Page.meta_per as meta' ,
				'Page.keyword_per as keyword',
				'Page.created'
			   );
	$options1['conditions'] = array(
		'Page.status' => 1
	);
	
	$options1['order'] = array(
		'Page.arrangment'=>'asc'
	);
	
	$response1 = $page->find('all',$options1); 
	
	$this->set('pages', $response1);  

     
	 
	//debug logs will destroy xml format, make sure were not in drbug mode
	//Configure::write ('debug', 0);
} 
	
	
}
?>