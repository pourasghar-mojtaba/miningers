<?php
App::uses('AppController', 'Controller');

App::uses('Sanitize', 'Utility');
 
ini_set('soap.wsdl_cache_enabled', 0);
 
class WebservicesController  extends AppController{

    var $components = array('RequestHandler');
	var $helpers = array('Text', 'Xml');
	
	public $name = 'Webservices';
    public $useTable = false;
	public $uses = array();
	public $autoRender = false;
 	public $layout = "ajax";
	
	function process()
	{
		
		$this->layout= null;
		Configure::write('debug',0);
	    Configure::write('Session.start', false);
		
		
		App::import('Vendor', 'nusoap',array('file'=>'nusoap'.DS.'lib'.DS.'nusoap.php')); 
		$server = new soap_server(); 
		
		$endpoint = 'http://localhost/madaner/webservices/process';
	               
	    //initialize WSDL support
	    $server->configureWSDL('helloWorldwsdl', 'urn:helloWorldwsdl', $endpoint);
		
		$server->soap_defencoding='UTF-8';
		$server->decode_utf8 = false;
		
		 $this->RequestHandler->respondAs('xml');
		 $this->layoutPath = '/xml';
		 
		 
		$server->register('helloWorld',                // method name        
	    array('return' => 'xsd:string'),    // output parameters
		    'urn:helloWorldwsdl',                    // namespace
		    'urn:helloWorldwsdl#helloWorld',                // soapaction
		    'rpc',                                // style
		    'encoded',                            // use
		    'Says hello to the caller'            // documentation
	    );
		
		$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
		$server->service($HTTP_RAW_POST_DATA);
		$this->autoRender = false;
		 exit(0);
	}
	
	
	 function helloWorld() {
		  $output = array(
        'output_string' => 'hello',
        'allow' => 1
     );
 
       return new soapval('return', 'HelloInfo', $output, false, 'urn:AnyURN');
	} 
	
 

}

?>