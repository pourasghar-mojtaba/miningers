<?php

App::uses('HtmlHelper', 'View/Helper');

class FckHelper extends Helper
{
    function load($id, $toolbar = 'Default') {
        foreach (explode('/', $id) as $v) {
             $did .= ucfirst($v);
        }

        return <<<FCK_CODE
<script type="text/javascript">
fckLoader_$did = function () {
    var bFCKeditor_$did = new FCKeditor('$did');
    bFCKeditor_$did.BasePath = '/js/';
    bFCKeditor_$did.ToolbarSet = '$toolbar';
    bFCKeditor_$did.ReplaceTextarea();
}
fckLoader_$did();
</script>
FCK_CODE;
    }
} 
?>