<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Users Controller
 *
 * @property User $User
 */
class SiteviewsController extends AppController {

    var $name = 'Siteviews';
	var $helpers = array('Gilace','Excel');

var $paginate = array('Siteview'=>array(
	    'limit' => 1,
	    'order' => array(
	         'Siteview.id' => 'asc'
	     )
	),
	
);


function admin_index()
	{
		$this->Siteview->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		$this->paginate = array('Siteview'=>array(
		    'fields'=>array(
				'Siteview.id ',
				'Siteview.count_view ',
                'date(Siteview.created) as created'					 
			) ,
			//'conditions' => array('date(Siteview.created)'=> '".date('Y-m-d')."'),
			'limit' => $limit,
			'order' => array(
				'Siteview.created' => 'desc'
			)
		));
					
		$siteviews = $this->paginate('Siteview');
		$this->set(compact('siteviews'));
	}

/**
* 
* @param undefined $from_date
* @param undefined $to_date
* 
*/
function admin_alldate_export($from_date='',$to_date='')
{
	$this->Siteview->recursive = -1;
	$options['fields'] = array(
                'Siteview.count_view',
                'date(Siteview.created) as created'
				
			   );
    if($from_date!='' && $to_date!=''){
      $options['conditions'] =array(
      'date(Siteview.created) <=' => $to_date, 'date(Siteview.created) >=' => $from_date
      ) ; 
    }  
    $options['order'] =array('Siteview.created'=>'desc');     	
           
					   
	$siteviews = $this->Siteview->find('all',$options); 
	$this->set(compact('siteviews'));
    $this->set('from_date',$from_date);
    $this->set('to_date',$to_date);
}




		
}

