<?php
/*
 * Load all files in alaxos/libs folder
 *
 * Note: The path to the lib folder is relative to the current file
 *       If you copy this code into your APP bootstrap.php file, adapt the folder path
 *       Alternatively, you can call the Alaxos plugin bootstrap.php file from within your APP bootstrap.php file like this:
 *
 *       require_once(ROOT . DS . 'plugins' . DS . 'alaxos' . DS . 'config' . DS . 'bootstrap.php');
 *       or
 *       require_once(APP . DS . 'plugins' . DS . 'alaxos' . DS . 'config' . DS . 'bootstrap.php');
 */
App::import('Utility', 'Folder');

$f = new Folder(dirname(__FILE__) . DS . '..' . DS . 'Lib');

$files = $f->read();
foreach($files[1] as $file)
{
	if($file != 'additional_translations.php')
	{
		require_once $f->path . $file;
	}
}

/*
 * Some functions of the Alaxos plugin need to have a locale set. By default, a locale is set below to make them work.
 *
 * But this is up to you to use the DateTool :: set_current_locale() function in your application to modify this default locale.
 *
 * Don't modify it below, but do it for instance in the beforeFilter() function of your AppController.
 * This will prevent to forget to modify it again whenever you make an Alaxos plugin update.
 */
DateTool :: set_current_locale('fr');
?>