<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author Nicolas Rod <nico@alaxos.com> (adaptation for Alaxos plugin)
 */
?>
<div class="<?php echo $pluralVar;?> view">
	
	<h2><?php echo "<?php echo ___('" . strtolower($singularHumanName) . "');?>";?></h2>
	
	<?php
	echo "<?php\n";
 	echo "\techo \$this->element('toolbar/toolbar', array('add' => true, 'list' => true, 'edit_id' => \${$singularVar}['{$modelClass}']['id'], 'copy_id' => \${$singularVar}['{$modelClass}']['id'], 'delete_id' => \${$singularVar}['{$modelClass}']['id'], 'delete_text' => ___('do you really want to delete this " . strtolower($singularHumanName) . " ?')), array('plugin' => 'alaxos'));\n";
 	echo "\t?>\n";
	?>

	<table border="0" class="view">
<?php
foreach ($fields as $field)
{
    if(in_array($field, array('id', 'password', 'created', 'modified', 'created_by', 'modified_by', 'updated_by')))
    {
        continue;
    }
    
    echo "\t<tr>\n";
    
	$isKey = false;
	if (!empty($associations['belongsTo']))
	{
		foreach ($associations['belongsTo'] as $alias => $details)
		{
			if ($field === $details['foreignKey'])
			{
				$isKey = true;
				
				echo "\t\t<td>\n";
            	echo "\t\t\t<?php echo ___('" . strtolower(Inflector::humanize(Inflector::underscore($alias))) . "'); ?>\n";
            	echo "\t\t</td>\n";
            	
            	echo "\t\t<td>:</td>\n";
            	
            	echo "\t\t<td>\n";
				echo "\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n";
				echo "\t\t</td>\n";
				
				break;
			}
		}
	}
	
	if ($isKey !== true)
	{
	    if($schema[$field]['type'] == 'text')
	    {
	        echo "\t\t<td style=\"vertical-align:top;\">\n";
	        echo "\t\t\t<?php echo ___('" . strtolower(Inflector::humanize($field)) . "'); ?>\n";
	        echo "\t\t</td>\n";
    	
	        echo "\t\t<td style=\"vertical-align:top;\">:</td>\n";
	    }
	    else
	    {
	        echo "\t\t<td>\n";
	        echo "\t\t\t<?php echo ___('" . strtolower(Inflector::humanize($field)) . "'); ?>\n";
	        echo "\t\t</td>\n";
    	
	        echo "\t\t<td>:</td>\n";
	    }
	    
    	echo "\t\t<td>\n";
    	
    	if(in_array($schema[$field]['type'], array('date', 'datetime')))
    	{
    	    echo "\t\t\t<?php echo DateTool :: sql_to_date(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
    	}
    	elseif($schema[$field]['type'] == 'text')
    	{
    	    echo "\t\t\t<?php\n";
			echo "\t\t\techo \$this->AlaxosHtml->format_text(\${$singularVar}['{$modelClass}']['{$field}']);\n";
			echo "\t\t\t?>\n";
    	}
    	elseif($schema[$field]['type'] == 'boolean')
    	{
    	    echo "\t\t\t<?php\n";
			echo "\t\t\techo \$this->AlaxosHtml->get_yes_no(\${$singularVar}['{$modelClass}']['{$field}']);\n";
			echo "\t\t\t?>\n";
    	}
    	else
    	{
    	    echo "\t\t\t<?php echo \${$singularVar}['{$modelClass}']['{$field}']; ?>\n";
    	}
		
		echo "\t\t</td>\n";
	}
	
	echo "\t</tr>\n";
}
?>
	</table>
	
	<div class="created_modified_zone">
	<?php
	echo "<?php\n";
	echo "\techo ucfirst(\$this->element('create_update_dates', array('model' => \${$singularVar}['{$modelClass}']), array('plugin' => 'alaxos')));\n";
	echo "\t?>\n";
	?>
	</div>
	
</div>
