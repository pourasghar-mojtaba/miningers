<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class FeedsController extends ReaderAppController {

  var $name = 'Feeds';
  var $components = array('Reader.Rss','Gilace'); 


/**
* 
* 
*/
public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('readfeed','admin_user_search');
}

/**
* 
* 
*/

function _getfeed(){
	$User_Info= $this->Session->read('User_Info');
	$this->Feed->recursive = -1; 
    $options['fields'] = array(
			'Feed.id',
			'Feed.tag',
			'Feedurl.id',
			'Feedurl.url',
			'Feedurl.rss_count',
			'Feedtime.id',
			'Feedtime.read_time',
 
     );
	 $options['joins'] = array(
		array('table' => 'feedurls',
			'alias' => 'Feedurl',
			'type' => 'LEFT',
			'conditions' => array(
			'Feed.id = Feedurl.user_feed_id ',
			)
		) ,
		array('table' => 'feedtimes',
			'alias' => 'Feedtime',
			'type' => 'LEFT',
			'conditions' => array(
			'Feed.id = Feedtime.user_feed_id ',
			)
		)
		
	);
	$options['limit'] = 1;
	
	$options['conditions'] = array(
		'Feed.user_id'=> $User_Info['id']
	);	   
	$feed = $this->Feed->find('first',$options);
	return $feed;
}

function edit_feed(){
	
	$User_Info= $this->Session->read('User_Info');
	$options=array();
	
	$feed = $this->_getfeed();
	$this->set('feed', $feed);
	if($this->request->is('post') || $this->request->is('put'))
	{
		if(!empty($feed)){
			$this->_edit();
			$feed = $this->_getfeed();
			$this->set('feed', $feed);
		}else
		$this->_add();
		$feed = $this->_getfeed();
		$this->set('feed', $feed);
	}	
	
	$this->set('title_for_layout',__('edit_feed'));
	$this->set('description_for_layout',__('edit_feed'));
	$this->set('keywords_for_layout',__('edit_feed'));	
}

function _add(){
	if($this->request->is('post') || $this->request->is('put'))
	{
        $this->request->data=Sanitize::clean($this->request->data);
		$datasource = $this->Feed->getDataSource();
        $User_Info= $this->Session->read('User_Info');
        try{
            $datasource->begin();
            
             //pr($this->request->data);throw new Exception();
			 $this->request->data['Feed']['user_id'] = $User_Info['id'];
			 $this->request->data['Feed']['status'] = 0;
			 
             if(!$this->Feed->save($this->request->data))
			        throw new Exception(__('the_feed_not_saved'));
             $feed_id= $this->Feed->getLastInsertID();    
                       
             /*  Feed url process  */
			 if(!empty($feed_id)){
					foreach($this->request->data['Feedurl']['url'] as $key => $value)
					{
						if(trim($value)==''){
							unset($this->request->data['Feedurl']['url'][$key]);
							unset($this->request->data['Feedurl']['rss_count'][$key]);
						}
					}
				}
				else throw new Exception(__('the_feedurl_has_not_been_saved'));
				 
				if(!empty($this->request->data['Feedurl']['url'])){
					foreach($this->request->data['Feedurl']['url'] as $key => $value)
					{
						$data[]= array('Feedurl' => array(
									'url' => $value ,
									'rss_count'=> $this->request->data['Feedurl']['rss_count'][$key],
									'arrangment'=> 1,
									'status'=> 0,
									'user_feed_id' => $feed_id
								));
					}
				}	
				//pr($data);throw new Exception();
				
				if(!$this->Feed->Feedurl->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_feedurl_has_not_been_saved'));
			 /*  Feed url process  */	
			 
			  /*  Feed url process  */
			  $data= array();
			 if(!empty($feed_id)){
					foreach($this->request->data['Feedtime']['read_time'] as $key => $value)
					{
						if(trim($value)==''){
							unset($this->request->data['Feedtime']['read_time'][$key]);
						}
					}
				}
				else throw new Exception(__('the_feedtime_has_not_been_saved'));
				 
				if(!empty($this->request->data['Feedtime']['read_time'])){
					foreach($this->request->data['Feedtime']['read_time'] as $key => $value)
					{
						$endtime = strtotime($value) + (5 * 60);
                        $endtime= date('H:i:s', $endtime);
                        $data[]= array('Feedtime' => array(
									'read_time' => $value ,
									'end_time' => $endtime ,
									'user_feed_id' => $feed_id
								));
					}
				}	
				//pr($data);throw new Exception();
				
				if(!$this->Feed->Feedtime->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_feedtime_has_not_been_saved'));
			 /*  Feed url process  */
			 
			 
			 	
			 
            $datasource->commit();
            
            $this->Session->setFlash(__('the_feed_has_been_saved'), 'success');
        } catch(Exception $e) {
            $datasource->rollback();
            $this->Session->setFlash($e->getMessage(),'error');
        }    
	}
}

function _edit(){
	if($this->request->is('post') || $this->request->is('put'))
	{
	   $this->request->data=Sanitize::clean($this->request->data);
	   $feed_id = Sanitize::clean($_POST['feed_id']);
	   $User_Info= $this->Session->read('User_Info');
	   $datasource = $this->Feed->getDataSource();
		try{
		    $datasource->begin();
			
			$ret= $this->Feed->updateAll(
			    array( 'Feed.tag' => '"'.$this->request->data['Feed']['tag'].'"' ,
                       'Feed.status' => '0' ,
                ),  
			    array( 'Feed.id' => $feed_id )  //condition
			);
			
			//pr($this->request->data);throw new Exception();
			/* feed url */
			
		    if(!empty($this->request->data['Feedurl']['id'])){
				foreach($this->request->data['Feedurl']['id'] as $key => $value)
				{
				  $ret= $this->Feed->Feedurl->updateAll(
				    array('Feedurl.url' => '"'.$this->request->data['Feedurl']['url'][$key].'"' ,
					      'Feedurl.rss_count' => '"'.$this->request->data['Feedurl']['rss_count'][$key].'"' ,
						  'Feedurl.status' => '0'
					),   //fields to update
				    array( 'Feedurl.id' => $value )  //condition
				  );	
				  if(!$ret){
				  	throw new Exception(__('the_feeurl_not_saved'));
				  }	
				}
			}

			/* feed url */
			
			/* feed time */
			
		    if(!empty($this->request->data['Feedtime']['id'])){
				foreach($this->request->data['Feedtime']['id'] as $key => $value)
				{
				  
                  $time = $this->request->data['Feedtime']['read_time'][$key];
                  $endtime = strtotime($time) + (5 * 60);
                  $endtime= date('H:i:s', $endtime);
                  $ret= $this->Feed->Feedtime->updateAll(
				    array('Feedtime.read_time' => '"'.$this->request->data['Feedtime']['read_time'][$key].'"' ,
                          'Feedtime.end_time' => '"'.$endtime.'"'
					),   //fields to update
				    array( 'Feedtime.id' => $value )  //condition
				  );	
				  if(!$ret){
				  	throw new Exception(__('the_feedtime_not_saved'));
				  }	
				}
			}
			
			/* feed time */
			
			$datasource->commit();
			$this->Session->setFlash(__('the_feed_has_been_saved'), 'success');
			
		} catch(Exception $e) {
			    $datasource->rollback();
				$this->Session->setFlash($e->getMessage(),'error');
		}
                	 
	}
}

/**
* 
* 
*/
function readfeed(){
         
    $this->Feed->recursive = -1;
    $this->Feed->Feedtime->recursive = -1;
    $this->Feed->Feedpost->recursive = -1;
    $this->loadModel('Post');
	Controller::loadModel('Errorlog');
    $this->Post->recursive = -1;

    //$this->autoRender = false; 
    
    $time=  date('H:i:s', time());
    $endtime = strtotime($time) + (5 * 60);
    $endtime= date('H:i:s', $endtime);
    
    $options['fields'] = array(
				'Feedtime.id',
				'Feedtime.user_feed_id',
				'Feedtime.read_time'
			   );
				   
	$options['conditions'] = array(
		   'Feedtime.read_time <='=> "$time" ,
		   'Feedtime.end_time >='=> "$time" ,   	   
	);
   
    $feedtimes = $this->Feed->Feedtime->find('all',$options);
    
    
    if(!empty($feedtimes)){
        foreach($feedtimes as $feedtime)
        {
            $options = array();
            $options['fields'] = array(
				'Feed.id',
				'Feed.user_id',
				'Feed.tag',
				'Feedurl.url',
				'Feedurl.rss_count'
			);
            
            $options['joins'] = array(
				array('table' => 'feedurls',
					'alias' => 'Feedurl',
					'type' => 'LEFT',
					'conditions' => array(
					'Feed.id = Feedurl.user_feed_id ',
					)
				),
                array('table' => 'feedtimes',
					'alias' => 'Feedtime',
					'type' => 'LEFT',
					'conditions' => array(
					'Feed.id = Feedtime.user_feed_id ',
					)
				) 
				
			);
				   
        	$options['conditions'] = array(
        		   'Feed.status'=> 1 ,
        		   'Feedurl.status'=> 1 , 
                   'Feedtime.read_time <='=> "$time" ,
		           'Feedtime.end_time >='=> "$time" ,   
        	);
			$options['order'] = array(
				'Feedurl.arrangment'=>'asc'
			);	
            $feeds = $this->Feed->find('all',$options);
			 
            if(!empty($feeds)){
                foreach($feeds as $feed)
                {
					try {
                        $newsItems = $this->Rss->readwithtag($feed['Feedurl']['url'],$feed['Feedurl']['rss_count'],$feed['Feed']['tag']);
						
                        if(!empty($newsItems)){
							/*$datasource = $this->Post->getDataSource();
								
							$datasource->begin();*/
                            foreach($newsItems as $newsItem){								
							   try{			   		
            						
									$post_count = 0;
									$post_count = $this->Feed->Feedpost->find('count', array('conditions' => array('Feedpost.title' => $newsItem->title)));
									if($post_count>0){
										continue;
									}
									$this->request->data = array();
									$this->request->data['Post']['url'] = $newsItem->link;
	                                $this->request->data['Post']['user_id'] = $feed['Feed']['user_id'];
	                               
                                    $title=$newsItem->title;
                                    $title=$this->Rss->clear_news($title);
									$title = $this->Rss->create_hashtag($feed['Feed']['tag'],$title);
									                                    
                                    $body=$newsItem->description;
                                    $body=$this->Rss->html2txt($body);
                                    $body=$this->Rss->clear_news($body);
                                    
                                    $this->request->data['Post']['body'] = trim(mb_substr($title.'\n'.$body,0,500));;
	                                $this->request->data['Post']['feed'] = 1;
									$this->request->data['Post']['status']= 0;
									$this->Post->create();
	                                if($this->Post->save($this->request->data))
		                            {
										$post_id= $this->Post->getLastInsertID();
										
										$this->request->data = array();			
										$this->request->data['Allpost']['post_id']= $post_id;
										$this->request->data['Allpost']['user_id']= $feed['Feed']['user_id'];
										$this->request->data['Allpost']['type']=0;
										$this->request->data=Sanitize::clean($this->request->data);
								        $this->Post->Allpost->create();
										$this->Post->Allpost->save($this->request->data);
										
										$this->request->data = array(); 
										$this->request->data['Feedpost']['user_feed_id'] = $feed['Feed']['id'];
										$this->request->data['Feedpost']['post_id'] = $post_id;
										$this->request->data['Feedpost']['url'] = $newsItem->link;
										$this->request->data['Feedpost']['title'] = $this->Rss->html2txt($newsItem->title);
										$this->request->data['Feedpost']['body'] = trim($this->Rss->html2txt(mb_substr($newsItem->description,0,500)));
										$this->Feed->Feedpost->create();
										if(!$this->Feed->Feedpost->save($this->request->data))
										{										
											throw new Exception(__('the_Feedpost_not_been_saved').'feed.id='.$feed['Feed']['id']);
										}
										
										$tags=$this->Gilace->get_tag($title,'#'); 
										$this->Post->Postrelatetag->Posttag->recursive = -1;
										
										if(isset($tags) && !empty($tags)){
											$tag_id = array();
											foreach($tags as $tag)
											{											
												$options= array();
												$options['fields'] = array(
													'Posttag.id',
													'Posttag.title'
												   );
												$options['conditions'] = array(
													'Posttag.title '=> $tag
												);	
												  
												$tag_info= $this->Post->Postrelatetag->Posttag->find('first',$options);
												//pr($tags);throw new Exception(); 
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
												else
												throw new Exception(__('the_tag_not_been_saved').'feed.id='.$feed['Feed']['id'].' title = '.$title);
											}
											
											$data = array();
											
											if(!empty($tag_id))
											{
												foreach($tag_id as $tid)
												{
													$dt = array();
													$dt=array('Postrelatetag' => array('post_id' => $post_id,'posttag_id'=>$tid));
													array_push($data,$dt);
												}
											}
												 
											if( isset($tag_id) && !empty($tag_id))
											{
												$this->Post->Postrelatetag->create();
												if(!$this->Post->Postrelatetag->saveMany($data))
												   throw new Exception(__('the_postrelatetag_not_been_saved').'feed.id='.$feed['Feed']['id'].' title = '.$title);
											}
										}
										
	                                }
									else
									{
										throw new Exception(__('the_Post_has_not_been_saved').'feed.id='.$feed['Feed']['id']);
									}
								 
								 
								 } catch(Exception $e) {
					           	    //$datasource->rollback();
	        						$this->Errorlog->get_log('FeedsControllers',$e->getMessage());
					        	}  
								  
                          }
							/*$datasource->commit();
					         */
							//sleep(50);
                        }
                                               
                    } catch(InternalErrorException $e) {
                        $newsItems = null;
                		$this->Errorlog->get_log('FeedsControllers',$e->getMessage().' , url='.$feed['Feedurl']['url'].' , feed.id='.$feed['Feed']['id']);
                    }
                }
            } 
        }
    }
    
  
    
    
    
 } 
  

function admin_user_search()
 {

    $this->Feed->User->recursive = -1;
	
	$search_word =  $_REQUEST["name"] ;
	$options['fields'] = array(
				'User.id',
				'User.name'
			   );
				   
	$options['conditions'] = array(
		   'User.name LIKE'=> "$search_word%" ,
		   'User.role_id'=> 2 ,
		   'User.status'=> 1 
		   
	);  
	
	$options['order'] = array(
			'User.id'=>'desc'
		);
	$options['limit'] = 15;
	
	$users = $this->Feed->User->find('all',$options);
    $this->set('users',$users);
    $this->render('Reader.Elements/Feeds/Ajax/user_suggest','ajax'); 											 
 }

  
/**
* 
* 
*/
 function admin_index()
	{
		$this->Feed->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['Feed']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('User.name LIKE' => '%'.$this->request->data['Feed']['search'].'%'),
                'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
				    	'Feed.user_id = User.id ',
					)
				   ) 
				),
				'fields'=>array(
    				'Feed.id',
    				'Feed.tag',
    				'Feed.status',
    				'Feed.created',
    				'User.name'
                    
				),
				'limit' => $limit,
				'order' => array(
					'Feed.id' => 'asc'
				)
			);
		}
		else
		{
			$this->paginate = array(
                'joins'=>array(
					array('table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
				    	'Feed.user_id = `User`.id ',
					)
				   ) 
				),
				'fields'=>array(
    				'Feed.id',
    				'Feed.tag',
    				'Feed.status',
    				'Feed.created',
    				'User.name'
				),
				'limit' => $limit,
				'order' => array(
					'Feed.id' => 'asc'
				)
			);
		}		
		$feeds = $this->paginate('Feed');
		$this->set(compact('feeds'));
	}

/**
* 
* 
*/
function admin_add()
{		
	if($this->request->is('post') || $this->request->is('put'))
	{
        $datasource = $this->Feed->getDataSource();
        $User_Info= $this->Session->read('AdminUser_Info');
        try{
            $datasource->begin();
            
             //pr($this->request->data);throw new Exception();

             if(!$this->Feed->save($this->request->data))
			        throw new Exception(__('the_feed_not_saved'));
             $feed_id= $this->Feed->getLastInsertID();    
                       
             /*  Feed url process  */
			 if(!empty($feed_id)){
					foreach($this->request->data['Feedurl']['url'] as $key => $value)
					{
						if(trim($value)==''){
							unset($this->request->data['Feedurl']['url'][$key]);
							unset($this->request->data['Feedurl']['rss_count'][$key]);
							unset($this->request->data['Feedurl']['arrangment'][$key]);
							unset($this->request->data['Feedurl']['status'][$key]);
						}
					}
				}
				else throw new Exception(__('the_feedurl_has_not_been_saved'));
				 
				if(!empty($this->request->data['Feedurl']['url'])){
					foreach($this->request->data['Feedurl']['url'] as $key => $value)
					{
						$data[]= array('Feedurl' => array(
									'url' => $value ,
									'rss_count'=> $this->request->data['Feedurl']['rss_count'][$key],
									'arrangment'=> $this->request->data['Feedurl']['arrangment'][$key],
									'status'=> $this->request->data['Feedurl']['status'][$key],
									'user_feed_id' => $feed_id
								));
					}
				}	
				//pr($data);throw new Exception();
				
				if(!$this->Feed->Feedurl->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_feedurl_has_not_been_saved'));
			 /*  Feed url process  */	
			 
			  /*  Feed url process  */
			  $data= array();
			 if(!empty($feed_id)){
					foreach($this->request->data['Feedtime']['read_time'] as $key => $value)
					{
						if(trim($value)==''){
							unset($this->request->data['Feedtime']['read_time'][$key]);
						}
					}
				}
				else throw new Exception(__('the_feedtime_has_not_been_saved'));
				 
				if(!empty($this->request->data['Feedtime']['read_time'])){
					foreach($this->request->data['Feedtime']['read_time'] as $key => $value)
					{						
                        $endtime = strtotime($value) + (5 * 60);
                        $endtime= date('H:i:s', $endtime);
                        $data[]= array('Feedtime' => array(
									'read_time' => $value ,
									'end_time' => $endtime ,
									'user_feed_id' => $feed_id
								));
					}
				}	
				//pr($data);throw new Exception();
				
				if(!$this->Feed->Feedtime->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_feedtime_has_not_been_saved'));
			 /*  Feed url process  */
			 
			 
			 	
			 
            $datasource->commit();
            
            $this->Session->setFlash(__('the_feed_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));
        } catch(Exception $e) {
            $datasource->rollback();
            $this->Session->setFlash($e->getMessage(),'admin_error');
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
	$this->Feed->id = $id;
	if(!$this->Feed->exists())
	{
		$this->Session->setFlash(__('invalid_id_for_feed'));
		$this->redirect(array('action' => 'index'));
	}
	
	if($this->request->is('post') || $this->request->is('put'))
	{
	   $datasource = $this->Feed->getDataSource();
		try{
		    $datasource->begin();
			
			$ret= $this->Feed->updateAll(
			    array( 'Feed.user_id' => '"'.$this->request->data['Feed']['user_id'].'"',
                       'Feed.tag' => '"'.$this->request->data['Feed']['tag'].'"' ,
                       'Feed.status' => '"'.$this->request->data['Feed']['status'].'"' ,
                ),  
			    array( 'Feed.id' => $id )  //condition
			);
			
			//pr($this->request->data);throw new Exception();
			/* feed url */
			$this->Feed->Feedurl->recursive = -1;
			$options['conditions'] = array(
				'Feedurl.user_feed_id' => $id		
			);
			$feedurls = $this->Feed->Feedurl->find('all',$options);
			if(!empty($feedurls)){
				foreach($feedurls as $feedurl)
				{
					if(!in_array($feedurl['Feedurl']['id'],$this->request->data['Feedurl']['id']))
					{						
						if(!$this->Feed->Feedurl->delete($feedurl['Feedurl']['id']))
							throw new Exception(__('the_feeurl_not_saved'));				
					}
					
				}
			}
			
		    if(!empty($this->request->data['Feedurl']['id'])){
				foreach($this->request->data['Feedurl']['id'] as $key => $value)
				{
				  $ret= $this->Feed->Feedurl->updateAll(
				    array('Feedurl.url' => '"'.$this->request->data['Feedurl']['url'][$key].'"' ,
					      'Feedurl.rss_count' => '"'.$this->request->data['Feedurl']['rss_count'][$key].'"' ,
					      'Feedurl.arrangment' => '"'.$this->request->data['Feedurl']['arrangment'][$key].'"' ,
						  'Feedurl.status' => '"'.$this->request->data['Feedurl']['status'][$key].'"'
					),   //fields to update
				    array( 'Feedurl.id' => $value )  //condition
				  );	
				  if(!$ret){
				  	throw new Exception(__('the_feeurl_not_saved'));
				  }	
				}
			}
			
			
			foreach($this->request->data['Feedurl']['id'] as $key => $value)
			{
				unset($this->request->data['Feedurl']['url'][$key]);
				unset($this->request->data['Feedurl']['rss_count'][$key]);
				unset($this->request->data['Feedurl']['arrangment'][$key]);
				unset($this->request->data['Feedurl']['status'][$key]);
			}
			$data = array();
			if(!empty($this->request->data['Feedurl']['url'])){
				foreach($this->request->data['Feedurl']['url'] as $key => $value)
				{
					$data[]= array('Feedurl' => array(
								'url' => $value ,
								'rss_count'=> $this->request->data['Feedurl']['rss_count'][$key],
								'arrangment'=> $this->request->data['Feedurl']['arrangment'][$key],
								'status'=> $this->request->data['Feedurl']['status'][$key],
								'user_feed_id' => $id
							));
				}
			}
			if(!empty($data)){
				if(!$this->Feed->Feedurl->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_feeurl_not_saved'));	
			}
			/* feed url */
			
			/* feed time */
			$this->Feed->Feedtime->recursive = -1;
			$options['conditions'] = array(
				'Feedtime.user_feed_id' => $id		
			);
			$feedtimes = $this->Feed->Feedtime->find('all',$options);
			if(!empty($feedtimes)){
				foreach($feedtimes as $feedtime)
				{
					if(!in_array($feedtime['Feedtime']['id'],$this->request->data['Feedtime']['id']))
					{						
						if(!$this->Feed->Feedtime->delete($feedtime['Feedtime']['id']))
							throw new Exception(__('the_feedtime_not_saved'));				
					}
					
				}
			}
			
		    if(!empty($this->request->data['Feedtime']['id'])){
				foreach($this->request->data['Feedtime']['id'] as $key => $value)
				{
				  $time = $this->request->data['Feedtime']['read_time'][$key];
                  $endtime = strtotime($time) + (5 * 60);
                  $endtime= date('H:i:s', $endtime);
                  $ret= $this->Feed->Feedtime->updateAll(
				    array('Feedtime.read_time' => '"'.$this->request->data['Feedtime']['read_time'][$key].'"' ,
                          'Feedtime.end_time' => '"'.$endtime.'"' 
					),   //fields to update
				    array( 'Feedtime.id' => $value )  //condition
				  );	
				  if(!$ret){
				  	throw new Exception(__('the_feedtime_not_saved'));
				  }	
				}
			}
			
			
			foreach($this->request->data['Feedtime']['id'] as $key => $value)
			{
				unset($this->request->data['Feedtime']['read_time'][$key]);
			}
			
            $data=array();
            
			if(!empty($this->request->data['Feedtime']['read_time'])){
				foreach($this->request->data['Feedtime']['read_time'] as $key => $value)
				{
					$endtime = strtotime($value) + (5 * 60);
                    $endtime= date('H:i:s', $endtime);
                    
                    $data[]= array('Feedtime' => array(
								'read_time' => $value ,
								'end_time' => $endtime ,
								'user_feed_id' => $id
							));
				}
			}
            
           // pr($data);throw new Exception();
            
			if(!empty($data)){
				if(!$this->Feed->Feedtime->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_feedtime_not_saved'));	
			}
			/* feed time */
			
			$datasource->commit();
			$this->Session->setFlash(__('the_feed_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));
			
		} catch(Exception $e) {
			    $datasource->rollback();
				$this->Session->setFlash($e->getMessage(),'admin_error');
		}
                	 
	}
	
    $options=array();
	$this->Feed->recursive = -1; 
    $options['fields'] = array(
			'Feed.id',
			'Feed.tag',
			'Feed.status',
			'Feed.created',
			'User.name' ,
			'User.id' 
     );
	 $options['joins'] = array(
		array('table' => 'users',
			'alias' => 'User',
			'type' => 'LEFT',
			'conditions' => array(
			'User.id = Feed.user_id ',
			)
		) 
		
	);
	$options['conditions'] = array(
		'Feed.id'=> $id
	);	   
	$feed = $this->Feed->find('first',$options);
	$this->set('feed', $feed);
    
    $options=array();
    $this->Feed->Feedurl->recursive = -1; 
	$options['conditions'] = array(
		'Feedurl.user_feed_id' => $id		
		);
	$feedurls = $this->Feed->Feedurl->find('all',$options);
    $this->set('feedurls', $feedurls);
	
    $options=array();
	$this->Feed->Feedtime->recursive = -1; 
	$options['conditions'] = array(
		'Feedtime.user_feed_id' => $id		
		);
	$feedtimes = $this->Feed->Feedtime->find('all',$options);
    $this->set('feedtimes', $feedtimes);
}

/**
* 
* @param undefined $id
* 
*/
function admin_delete($id = null)
{
	$this->Feed->recursive = -1;
		   
   if($this->Feed->delete($id)){
	   $this->Session->setFlash(__('the_feed_deleted'), 'admin_success');
       $this->redirect(array('action' => 'index'));
   }
   else $this->Session->setFlash(__('the_feed_not_deleted'),'admin_error');
}


/**
* 
* 
*/
function index()
{
	
}



 
}
