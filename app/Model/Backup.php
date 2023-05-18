<?php

class Backup extends AppModel {
	public $name = 'Backup';
	public $useTable = "industries"; 
	
    var $actsAs = array('Containable');

}

?>