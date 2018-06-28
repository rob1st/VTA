<?php
require_once 'sqlFunctions.php';
require_once 'assetSql.php';
require_once 'assetViewEls.php';

function getAssetData($action) {
    global $defaultFormCtrls, $sqlMap;
    
    $link = connect();
    
    $context = array();
    
    if ($action === 'add') {
        $context['title'] = 'Add Asset';
        $context['pageHeading'] = 'Add New Asset';
        $context['formCtrls'] = $defaultFormCtrls;
        
        foreach ($context['formCtrls'] as $name => &$ctrl) {
            // if it's a select element, qry for options
            // in the future rendering should be handled by template eng
            if ($sqlMap[$name]) {
                $fields = $sqlMap[$name]['fields'];
                $tableName = $sqlMap[$name]['table'];
                $data = $link->get($tableName, null, $fields);
                
                $options = [];
                $optFormat = "<option value='%s'>%s</option>";
                foreach ($data as $datum) {
                    $options[] = sprintf($optFormat, $datum[$fields[0]], $datum[$fields[1]]);
                }
                
                $ctrl = sprintf($ctrl,
                    implode('', $options)
                );
            } else {
                $ctrl = sprintf($ctrl, $name);
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