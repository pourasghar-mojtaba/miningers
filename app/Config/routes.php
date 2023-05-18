<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	
	Router::mapResources('users');
	Router::mapResources('posts');
	Router::mapResources('allposts');
	Router::mapResources('shareposts');
	Router::parseExtensions('xml','json','rss');
	
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
   Router::connect('/admin', array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));
 
   //Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

   Router::connect('/:language/:controller/:action/*',
	                       array(),
	                       array('language' => '[a-z]{3}')); 
  
   Router::connect('/sitemap', array('controller' => 'sitemaps', 'action' => 'index')); 
  
   Router::connect('/:username',
	                       array(
						     'controller'=>'users' ,
							 'action'=>'profile'
						   ),
	                       array(
						     'pass' => array('username') ,
							 'username'=>'[a-zA-Z0-9_.]+'
						   ));  
	
							   
   $subdomain=substr(env("HTTP_HOST"),0,strpos(env("HTTP_HOST"),"."));
   if($subdomain!=$_SERVER['HTTP_HOST']){
   		Router::connect('/',
		array(
		     'controller'=>'blogs' ,
			 'action'=>'index',
		     'pass' => $subdomain 
			 ) 
		   );		
   }						   			   					   

/**
 * 
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
