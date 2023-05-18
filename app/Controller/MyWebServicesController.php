<?php

class MyWebServicesController  extends AppController {
    var $name = 'MyWebServices';
    var $layout = "ajax";

    function index() {
        App::import('Vendor', 'nusoap',array('file'=>'nusoap'.DS.'lib'.DS.'nusoap.php')); 
		$server = new SoapServer(null);
        $server->setObject($this);
        $server->handle();
        exit(0);
    }
    public function addNumbers($a,$b) {
        return $a+$b;
    }
	
	 public function hello() {
        return 'Hello moj';
    }
}

?>