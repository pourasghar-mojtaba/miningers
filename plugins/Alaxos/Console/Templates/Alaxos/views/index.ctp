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
<div class="<?php echo $pluralVar;?> index">

	<h2><?php echo "<?php echo ___('" . strtolower($pluralHumanName) . "');?>";?></h2>

<?php
	echo "\t<?php\n";
 	echo "\techo \$this->element('toolbar/toolbar', array('add' => true, 'container_class' => 'toolbar_container_list', 'pagination_limit' => true), array('plugin' => 'alaxos'));\n";
 	echo "\t?>\n";

 	echo "\n";

 	echo "\t<?php\n";
    echo "\techo \$this->AlaxosForm->create('{$modelClass}');\n";
    echo "\t?>\n";
    ?>

	<table cellspacing="0" class="administration">

	<tr class="sortHeader">
		<th style="width:5px;"></th>
<?php  foreach ($fields as $field):?>
<?php
		if (!in_array($field, array('id', 'password', 'created_by', 'modified_by', 'updated_by')))
		{
			$isKey = false;
			if (!empty($associations['belongsTo']))
			{
				foreach ($associations['belongsTo'] as $alias => $details)
				{
					if ($field === $details['foreignKey'])
					{
						$isKey = true;
						echo "\t\t<th>";
						echo "<?php echo \$this->Paginator->sort('{$modelClass}.{$field}', __('" . strtolower($alias) . "'));?>";
						echo "</th>\n";
						break;
					}
				}
			}

			if(!$isKey)
			{
			    if(in_array($field, array('created', 'modified', 'updated')))
			    {
			        echo "\t\t<th style=\"width:120px;\">";
			    }
			    else
			    {
			        echo "\t\t<th>";
			    }
				echo "<?php echo \$this->Paginator->sort('{$modelClass}.{$field}', __('{$field}'));?>";
				echo "</th>\n";
			}
		}
?>
<?php endforeach;?>

		<th class="actions">&nbsp;</th>
	</tr>

	<tr class="searchHeader">
		<td style="padding:0px 3px;">
<?php
    		echo "\t\t\t<?php\n";
    		echo "\t\t\techo \$this->AlaxosForm->checkbox('_Tech.selectAll', array('style' => 'margin-bottom:8px;'));\n";
    		echo "\t\t\t?>\n";
?>
		</td>
	<?php
	foreach ($fields as $field)
	{
		if (!in_array($field, array('id', 'password', 'created_by', 'modified_by', 'updated_by')))
		{
			echo "\t\t<td>\n\t\t\t<?php\n";
			echo "\t\t\t\techo \$this->AlaxosForm->filter_field('{$field}');\n";
			echo "\t\t\t?>\n\t\t</td>\n";
		}
	}

//	if (!empty($associations['hasAndBelongsToMany']))
//	{
//		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData)
//		{
//			echo "\t\t<td>\n\t\t\t<?php\n";
//			echo "\t\t\t\techo \$this->AlaxosForm->filter_field('{$assocName}');\n";
//			echo "\t\t\t? >\n\t\t</td>\n";
//		}
//	}
	?>
		<td class="searchHeader" style="width:80px">
    		<div class="submitBar">
    			<?php echo "\t\t<?php echo \$this->AlaxosForm->end(___('search'));?>\n"; ?>
    		</div>
    	</td>
	</tr>

	<?php
	echo "<?php
	\$i = 0;
	foreach (\${$pluralVar} as \${$singularVar}):
		\$class = null;
		if (\$i++ % 2 == 0)
		{
			\$class = ' class=\"row\"';
		}
		else
		{
			\$class = ' class=\"altrow\"';
		}
	?>\n";
	echo "\t<tr<?php echo \$class;?>>\n";

	echo "\t\t<td>\n";
	echo "\t\t<?php\n";
	echo "\t\techo \$this->AlaxosForm->checkBox('{$modelClass}.' . \$i . '.id', array('value' => \${$singularVar}['{$modelClass}']['id'], 'class' => 'model_id'));\n";
	echo "\t\t?>\n";
	echo "\t\t</td>\n";

		foreach ($fields as $field)
		{
			if (!in_array($field, array('id', 'password', 'created_by', 'modified_by', 'updated_by')))
			{
				$isKey = false;
				if (!empty($associations['belongsTo']))
				{
					foreach ($associations['belongsTo'] as $alias => $details)
					{
						if ($field === $details['foreignKey'])
						{
							$isKey = true;
							echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
							break;
						}
					}
				}

				if ($isKey !== true)
				{
				    if(in_array($schema[$field]['type'], array('date', 'datetime')))
				    {
				        echo "\t\t<td>\n\t\t\t<?php echo DateTool :: sql_to_date(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t</td>\n";
				    }
					elseif($schema[$field]['type'] == 'boolean')
			    	{
			    		echo "\t\t<td>\n";
			    		echo "\t\t\t<?php\n";
			    		echo "\t\t\techo \$this->AlaxosHtml->get_yes_no(\${$singularVar}['{$modelClass}']['{$field}']);\n";
						echo "\t\t\t?>\n";
						echo "\t\t</td>\n";
			    	}
				    else
				    {
				        echo "\t\t<td>\n\t\t\t<?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>\n\t\t</td>\n";
				    }
				}
			}
		}

		echo "\t\t<td class=\"actions\">\n\n";
		echo "\t\t\t<?php echo \$this->Html->link(\$this->Html->image('/alaxos/img/toolbar/loupe.png'), array('action' => 'view', \${$singularVar}['{$modelClass}']['id']), array('class' => 'to_detail', 'escape' => false)); ?>\n";
		echo "\t\t\t<?php echo \$this->Html->link(\$this->Html->image('/alaxos/img/toolbar/small_edit.png'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['id']), array('escape' => false)); ?>\n";

		if(isset($displayField) && !empty($displayField))
		{
		    echo "\t\t\t<?php echo \$this->Form->postLink(\$this->Html->image('/alaxos/img/toolbar/small_drop.png'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['id']), array('escape' => false), sprintf(___(\"are you sure you want to delete '%s' ?\"), \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n";
		}
		else
		{
		    echo "\t\t\t<?php echo \$this->Form->postLink(\$this->Html->image('/alaxos/img/toolbar/small_drop.png'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['id']), array('escape' => false), sprintf(___(\"are you sure you want to delete this element ?\"))); ?>\n";
		}

		echo "\n\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
	</table>

	<div class="paging">
	<?php echo "\t<?php echo \$this->Paginator->prev('<< ' . __('previous'), array(), null, array('class'=>'disabled'));?>\n";?>
	 |
	 <?php echo "\t<?php echo \$this->Paginator->numbers(array('modulus' => 5, 'first' => 2, 'last' => 2, 'after' => ' ', 'before' => ' '));?>"?>
	 |
	<?php echo "\t<?php echo \$this->Paginator->next(__('next') . ' >>', array(), null, array('class' => 'disabled'));?>\n";?>
	</div>

	<?php
	echo "<?php\n";
	echo "\tif(\$i > 0)\n";
	echo "\t{\n";
	echo "\t\techo '<div class=\"choose_action\">';\n";
	echo "\t\techo ___d('alaxos', 'action to perform on the selected items');\n";
	echo "\t\techo '&nbsp;';\n";
	echo "\t\techo \$this->AlaxosForm->input_actions_list();\n";
	echo "\t\techo '&nbsp;';\n";
	
	echo "\t\techo \$this->AlaxosForm->hidden('_Tech.actionAllUrl', array('value' => \$this->AlaxosForm->url(array('action' => 'actionAll'))));\n";
	echo "\t\techo \$this->AlaxosForm->button(___d('alaxos', 'go'), array('id' => 'chooseActionFormBtn', 'type' => 'button'));\n";
	
	//echo "\techo \$this->AlaxosForm->end(array('label' =>___d('alaxos', 'go'), 'div' => false));\n";
	echo "\t\techo '</div>';\n";
	echo "\t}\n";
	echo "\t?>\n";
	?>

</div>
