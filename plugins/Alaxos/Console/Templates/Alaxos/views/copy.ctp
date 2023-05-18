<?php
/**
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
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author Nicolas Rod <nico@alaxos.com> (adaptation for Alaxos plugin)
 */
?>
<div class="<?php echo $pluralVar;?> form">

<?php
	echo "\t<?php echo \$this->AlaxosForm->create('{$modelClass}');?>\n";
?>
	
 	<h2><?php printf("<?php echo ___('%s %s'); ?>", strtolower(Inflector::humanize($action)), strtolower($singularHumanName)); ?></h2>
 	
 	<?php
	echo "<?php\n";
 	echo "\techo \$this->element('toolbar/toolbar', array('list' => true, 'back_to_view_id' => \${$singularVar}['{$modelClass}']['id']), array('plugin' => 'alaxos'));\n";
 	echo "\t?>\n";
 	?>
 	
 	<table border="0" cellpadding="5" cellspacing="0" class="edit">
<?php
	foreach ($fields as $field)
	{
		if ($field == $primaryKey)
		{
			continue;
		}
		elseif (!in_array($field, array('id', 'created', 'modified', 'updated', 'created_by', 'modified_by', 'updated_by')))
		{
?>
	<tr>
		<td>
			<?php echo "<?php echo ___('" . $field . "') ?>\n";?>
		</td>
		<td>:</td>
		<td>
			<?php echo "<?php echo \$this->AlaxosForm->input('{$field}', array('label' => false)); ?>\n"; ?>
		</td>
	</tr>
<?php
		}
	}
	
	if (!empty($associations['hasAndBelongsToMany']))
	{
		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData)
		{
			?>
	<tr>
		<td>
			<?php echo "<?php echo ___('" . $assocName . "') ?>\n";?>
		</td>
		<td>:</td>
		<td>
			<?php echo "<?php echo \$this->AlaxosForm->input('{$assocName}', array('label' => false, 'multiple' => 'checkbox')); ?>"; ?>
		</td>
	</tr>
<?php
		}
	}
?>
	<tr>
 		<td></td>
 		<td></td>
 		<td>
			<?php
			echo "<?php echo \$this->AlaxosForm->end(___d('alaxos', 'copy')); ?>";
			?>
 		</td>
 	</tr>
	</table>

</div>
