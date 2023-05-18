<?php
App::uses('AppHelper', 'Helper');

/**
* Helper for working with PHPExcel class.
* PHPExcel has to be in the vendors directory.
*/

class ExcelHelper extends AppHelper {


/**
* 
* @param undefined $name
* @param undefined $header
* @param undefined $titles
* @param undefined $data
* 
*/
function user_export($name,$header,$titles,$data)
{
	App::import('Vendor', 'ExcelWriter');
	$excel=new ExcelWriter($name,__('users'));
	if($excel==false)	
	echo $excel->error;
	$myArr=array("");
	/*$myArr=array($header);
	$excel->writeLine($myArr);
	$myArr=array("");
	$excel->writeLine($myArr);*/
	if(!empty($titles)){
		foreach($titles as $title)
			$etitle[]=$title;
	}
	$myArr=$etitle;
	$excel->writeLine($myArr);
	if(!empty($data))
	{
		foreach($data as $res)
		{
			if($res['User']['status']==1){
				$status = __('active');
			}else $status = __('inactive');
			
			if($res['User']['user_type']==1){
				$user_type = __('company');
			}else $user_type = __('people');
			
			if($res['User']['sex']==1){
				$sex = __('man');
			}else $sex = __('woman');
			
			if($res['User']['register_key']!=-1){
				$register_key = __SITE_URL.'users/confirmation_email/'.$res['User']['register_key'];
			}else $register_key = '';
			
			$myArr=array($res['User']['name'],
						 $res['User']['user_name'],
						 $res['User']['email'],
						 $status,
						 $res['User']['created'],
						 $user_type,
						 $sex,
						 $res['Role']['name'],
						 $res['Industry']['title'],
						 $res['User']['location'],
						 $res['User']['site'],
						 $register_key
						 );
			$excel->writeLine($myArr);
		}
	}
	$this->send_to_download($name);
	
}
/**
* 
* @param undefined $name
* @param undefined $header
* @param undefined $titles
* @param undefined $data
* 
*/
function onedate_export($name,$header,$titles,$data)
{
	App::import('Vendor', 'ExcelWriter');
	$excel=new ExcelWriter($name,__('userloglogin_management'));
    
	if($excel==false)	
	echo $excel->error;
	$myArr=array("");
	$myArr=array($header);
	$excel->writeLine($myArr,'red');
	$myArr=array("");
	$excel->writeLine($myArr);
	if(!empty($titles)){
		foreach($titles as $title)
			$etitle[]=$title;
	}
	$myArr=$etitle;
	$excel->writeLine($myArr);
	if(!empty($data))
	{
		foreach($data as $res)
		{	
			if($res['User']['sex']==1){
				$sex = __('man');
			}else $sex = __('woman');			
			$myArr=array($res['User']['name'],
						 $res['User']['user_name'],
						 $res['User']['email'],
						 $res['Userloglogin']['created'],
                         $res['Userloglogin']['modified'],
						 $res['Userloglogin']['count_login']
						 );
			$excel->writeLine($myArr);
		}
	}
	$this->send_to_download($name);
	
}
/**
* 
* @param undefined $name
* @param undefined $header
* @param undefined $titles
* @param undefined $data
* 
*/
function alldate_export($name,$header,$titles,$data)
{
	App::import('Vendor', 'ExcelWriter');
	$excel=new ExcelWriter($name,__('userloglogin_management'));
    
	if($excel==false)	
	echo $excel->error;
	$myArr=array("");
	$myArr=array($header);
	$excel->writeLine($myArr,'red');
	$myArr=array("");
	$excel->writeLine($myArr);
	if(!empty($titles)){
		foreach($titles as $title)
			$etitle[]=$title;
	}
	$myArr=$etitle;
	$excel->writeLine($myArr);
	if(!empty($data))
	{
		foreach($data as $res)
		{	
			if($res['User']['sex']==1){
				$sex = __('man');
			}else $sex = __('woman');			
			$myArr=array($res['User']['name'],
						 $res['User']['user_name'],
						 $res['User']['email'],
						 $res['Userloglogin']['created'],
                         $res['Userloglogin']['modified'],
						 $res['Userloglogin']['count_login']
						 );
			$excel->writeLine($myArr);
		}
	}
	$this->send_to_download($name);
	
}
/**
* 
* @param undefined $name
* @param undefined $header
* @param undefined $titles
* @param undefined $data
* 
*/
function site_alldate_export($name,$header,$titles,$data)
{
	App::import('Vendor', 'ExcelWriter');
	$excel=new ExcelWriter($name,__('siteview_management'));
    
	if($excel==false)	
	echo $excel->error;
	$myArr=array("");
	$myArr=array($header);
	$excel->writeLine($myArr,'red');
	$myArr=array("");
	$excel->writeLine($myArr);
	if(!empty($titles)){
		foreach($titles as $title)
			$etitle[]=$title;
	}
	$myArr=$etitle;
	$excel->writeLine($myArr);
	if(!empty($data))
	{
		foreach($data as $res)
		{				
			$myArr=array(
						  $res['0']['created']
						 ,$res['Siteview']['count_view']
						 );
			$excel->writeLine($myArr);
		}
	}
	$this->send_to_download($name);
	
}

function send_to_download($filename)
{
	  if (class_exists('ZipArchive') && filesize($filename) > 100) {
			$zip = new ZipArchive();
			$zip->open($filename . '.zip', ZIPARCHIVE::CREATE);
			$zip->addFile($filename, $filenameSufix);
			$zip->close();	
			if (file_exists($filename . '.zip') && filesize($filename) > 10) {
				unlink($filename);
			}
			$filename = $filename.'.zip';
		}
		
	 	
	if (file_exists($filename)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename='.basename($filename));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($filename));
	    ob_clean();
	    flush();
	    readfile($filename);
	    exit;
	}
}


}