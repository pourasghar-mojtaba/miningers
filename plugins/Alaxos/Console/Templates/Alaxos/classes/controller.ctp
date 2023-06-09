<?php
/**
 * Controller bake template file
 *
 * Allows templating of Controllers generated from bake.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author Nicolas Rod <nico@alaxos.com> (adaptation for Alaxos plugin)
 */

echo "<?php\n";
?>
/**
 * <?php echo $controllerName; ?> Controller
 *
<?php
if (!$isScaffold) {
	$defaultModel = Inflector::singularize($controllerName);
	echo " * @property {$defaultModel} \${$defaultModel}\n";
	if (!empty($components)) {
		foreach ($components as $component) {
			echo " * @property {$component}Component \${$component}\n";
		}
	}
}
?>
 */
class <?php echo $controllerName; ?>Controller extends <?php echo $plugin; ?>AppController {

	var $name = '<?php echo $controllerName; ?>';
<?php if ($isScaffold): ?>
	var $scaffold;
<?php else: ?>
<?php

/*
 * Always add Form, Alaxos.AlaxosForm and Alaxos.AlaxosHtml helpers
 */
$helpers = is_array($helpers) ? $helpers : array();

if(!in_array('Form', $helpers))
{
    $helpers[] = 'Form';
}
if(!in_array('Alaxos.AlaxosForm', $helpers))
{
    $helpers[] = 'Alaxos.AlaxosForm';
}
if(!in_array('Alaxos.AlaxosHtml', $helpers))
{
    $helpers[] = 'Alaxos.AlaxosHtml';
}

if (count($helpers)):
	echo "\tvar \$helpers = array(";
	for ($i = 0, $len = count($helpers); $i < $len; $i++):
		if ($i != $len - 1):
			echo "'" . Inflector::camelize($helpers[$i]) . "', ";
		else:
			echo "'" . Inflector::camelize($helpers[$i]) . "'";
		endif;
	endfor;
	echo ");\n";
endif;

/*
 * Always add Alaxos.AlaxosFilter component
 */
$components = is_array($components) ? $components : array();

if(!in_array('Alaxos.AlaxosFilter', $components))
{
    $components[] = 'Alaxos.AlaxosFilter';
}
if(!in_array('Alaxos.AlaxosFilter', $components))
{
    $components[] = 'Alaxos.AlaxosPaginator';
}
if (count($components)):
	echo "\tvar \$components = array(";
	for ($i = 0, $len = count($components); $i < $len; $i++):
		if ($i != $len - 1):
			echo "'" . Inflector::camelize($components[$i]) . "', ";
		else:
			echo "'" . Inflector::camelize($components[$i]) . "'";
		endif;
	endfor;
	echo ");\n";
endif;

echo $actions;

endif; ?>

}
<?php echo "?>"; ?>