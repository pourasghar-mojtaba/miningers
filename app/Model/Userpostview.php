<?php
App::import('Model','Post');

class Userpostview extends AppModel {
	public $name = 'Userpostview';
	public $useTable = "userpostviews"; 
	
	  var $actsAs = array('Containable');
	  
 function get_time($id)
 {
	$options['fields'] = array(
			'Userpostview.user_id',
			'Userpostview.created',
			'Userpostview.max_post_id'
		   );
	$options['conditions'] = array(
	'Userpostview.user_id '=> $id
	);	   
	$ret= $this->find('first',$options);
	return $ret;
 } 
 /*
 function updatetime($id){
	$ret= $this->updateAll(
    array( 'Userpostview.created' =>'"'.date('Y-m-d H-i-s').'"'),   //fields to update
    array( 'Userpostview.user_id' => $id )  //condition
  );
  return $ret;
 }*/
	
	
function updatetime($id){
	
	$Post=new Post();
	$Post->recursive = -1;
	
	$rec = $Post->query("
		select max(Post.id) as max_post_id from posts as Post 
	");
	$max_post_id = $rec[0][0]['max_post_id'];	
	 
	$options['conditions'] = array(
			'Userpostview.user_id ' => $id		
			);
	$count = $this->find('count',$options); 
	if($count<=0)
	{
		$data=array();
        $data['Userpostview']['user_id']= $id;
		$data['Userpostview']['max_post_id']= $max_post_id;
       //pr($data);exit();
		if($this->save($data))
		{
		}
	}
	else
	{
		$ret= $this->updateAll(
	    array( 'Userpostview.created' =>'"'.date('Y-m-d H-i-s').'"',
			   'Userpostview.max_post_id' =>'"'.$max_post_id.'"'),   //fields to update
	    array( 'Userpostview.user_id' => $id )  //condition
	  );
	}
 }
}

?>