<?php
require_once 'sqlFunctions.php';
require_once 'assetSql.php';
require_once 'assetViewEls.php';

function getAssetData($action) {
    global $defaultFormCtrls, $sqlMap;
    
    $link = connect();
    
    $context = array();
    
    // routes = add, update, list
    if ($action === 'add') {
        $context = [
            'title' => 'Add Asset',
            'pageHeading' => 'Add New Asset',
            'cardHeading' => 'Enter asset information',
            'formTarget' => '/commit/commitAsset.php',
            'formCtrls' => $defaultFormCtrls
        ];
        
        foreach ($context['formCtrls'] as $name => &$ctrl) {
            // if it's a select element, qry for options
            // in the future rendering should be handled by template eng
            // SEE: https://gist.github.com/iamkirkbater/970c354aa73302448f647676b83e52f7 for form control macros
            if (isset($sqlMap[$name])) {
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
                $ctrl = sprintf($ctrl, '');
            }
        }
        
    } elseif ($action === 'update') {
        
    } else { // fallback is 'list' view
        $fields = $sqlMap['asset'][$action];
        // join with lookup tables before query
        $link->join('component c', 'a.component = c.compID', 'LEFT');
        $link->join('location L', 'a.location = L.locationID', 'LEFT');
        $link->join('yesNo y', 'a.installStatus = y.yesNoID', 'LEFT');
        $link->join('testStatus t', 'a.testStatus = t.testStatID', 'LEFT');
        $data = $link->get('asset a', null, $fields);

        $context['fieldNames'] = $fields;
        $context['data'] = $data;
        $context['addPath'] = "assets.php/add";
    }
    
    return $context;
}