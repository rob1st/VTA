<?php
require_once 'sqlFunctions.php';
require_once 'assetSql.php';
require_once 'assetViewEls.php';

function getAssetData($action) {
    $link = connect();
    
    $context = array();
    
    if ($action === 'add') {
        $context['formCtrls'] = $defaultFormCtrls;
        
        foreach ($context['formCtrls'] as $name => &$ctrl) {
            // if it's a select element, qry for options
            // in the future rendering should be handled by template eng
            if (isset($sqlMap[$name]) && strpos($ctrl, 'select') === false) {
                $fields = $sqlMap[$name]['fields'];
                $tableName = $sqlMap[$name]['table'];
                $data = $link->get($tableName, $fields);
                
                $options = [];
                $optFormat = "<option value='%s'>%s</option>";
                foreach ($data as $datum) {
                    $option = sprintf($optFormat, $datum[0], $datum[1]);
                    array_push($options, $option);
                }
                
                $ctrl = sprintf($ctrl,
                    vsprintf($options)
                );
            } else {
                $ctrl = sprintf($ctrl, '');
            }
        }
        
    } elseif ($action === 'update') {
        
    } else { // fallback is 'list' view
        $fields = $sqlMap['asset'][$action];
        $context['data'] = $link->get('asset', $fields);
        $context['addPath'] = "assets.php/add";
    }
    
    return $context;
}