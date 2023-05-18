<?php
$created_css_class  = isset($created_css_class)  ? $created_css_class  : 'created_date';
$modified_css_class = isset($modified_css_class) ? $modified_css_class : 'modified_date';

if(!isset($model))
{
    $controller = $this->request->params['controller'];
    $modelClass = Inflector::singularize($controller);
    $modelKey   = Inflector::camelize($modelClass);
    
    if(isset($this->request->data[$modelKey]))
    {
        $model = $this->request->data[$modelKey];
    }
}

if(isset($model) && is_array($model))
{
    $created  = isset($model['created'])  ? $model['created']  : null;
    $modified = isset($model['modified']) ? $model['modified'] : null;
    
    if($created == $modified)
    {
        $modified = null;
    }
    
    $date_str = '';
    
    if(!empty($created) && empty($modified))
    {
        $date_str = __d('alaxos', 'created on %s at %s', '<span class="' . $created_css_class . '">' . DateTool::sql_to_date($created, null, false), DateTool::sql_to_time($created) . '</span>');
    }
    elseif(!empty($created) && !empty($modified))
    {
        $date_str = __d('alaxos', 'created on %s at %s', '<span class="' . $created_css_class . '">' . DateTool::sql_to_date($created, null, false), DateTool::sql_to_time($created) . '</span>') . ',  ' . __d('alaxos', 'last updated on %s at %s', '<span class="' . $modified_css_class . '">' . DateTool::sql_to_date($modified, null, false), DateTool::sql_to_time($modified) . '</span>');
    }
    elseif(empty($created) && !empty($modified))
    {
        $date_str = __d('alaxos', 'last updated on %s at %s', '<span class="' . $modified_css_class . '">' . DateTool::sql_to_date($modified, null, false), DateTool::sql_to_time($modified) . '</span>');
    }
    
    echo $date_str;
}
?>