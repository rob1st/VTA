<?php
require_once 'sql_functions/sqlFunctions.php';
require_once 'sql/assetSql.php';
require_once 'html_components/assetComponents.php';

$routes = ['list', 'add', 'update'];

function getAssetData($route) {
    global $defaultFormCtrls, $updateFormCtrls, $sqlMap, $tableStructure;
    
    $link = connect();
    
    $context = [];
    
    // routes = add, update, list
    if ($route === 'add') {
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
                $tableName = $sqlMap[$name]['tableName'];
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
        
    } elseif ($route === 'view') {
        
    } elseif ($route === 'update') {
        $context = [
            'title' => "Update Asset #",
            'pageHeading' => "Update Asset #",
            'formTarget' => '/commit/updateAsset.php',
            // 'formCtrls' => $updateFormCtrls
        ];
        
        try {
            $id = filter_input(INPUT_GET, 'assetID');
            $link->where('assetID', $id);
            $context['data'] = $result = $link->getOne('asset');
            
            $context['title'] .= $result['assetID'];
            $context['pageHeading'] .= $result['assetID'];
            
            // iterate over form ctrls, filling with values
            // foreach ($context['formCtrls'] as $fieldName => &$formCtrl) {
            //     $formCtrl['value'] = $result[$fieldName];
            //     if ($formCtrl['type'] === 'select') { // query for select values
            //         $tableName = $formCtrl['name'];
            //         $fields = $sqlMap[$tableName]['fields'];
            //         $options = $link->get($tableName, $fields);
                    
            //         // reduce 3-dimensional result array to 2-dimensional in the form of:
            //         // [ '<option's-value-attribute>' => '<option's-visible-value>']
            //         $formCtrl['values'] = array_reduce($options, function($arr, $option) use ($fields) {
            //             $idField = $fields[0];
            //             $nameField = $fields[1];
            //             $key = $option[$idField];
            //             $val = $option[$nameField];
            //             return $arr[$key] = $val;
            //         }, []);
            //     }
            // }
        } catch (Exception $e) {
            $context['pageHeading'] = "Error: {$e->getMessage()}";
            exit;
        }
    } else { // fallback is list view
        $fields = $sqlMap['asset'][$route];
        // join with lookup tables before query
        $link->join('component c', 'a.component = c.compID', 'LEFT');
        $link->join('location L', 'a.location = L.locationID', 'LEFT');
        $link->join('yesNo y', 'a.installStatus = y.yesNoID', 'LEFT');
        $link->join('testStatus t', 'a.testStatus = t.testStatID', 'LEFT');
        $result = $link->get('asset a', null, $fields);
        
        // loop over data, appending it to table fields
        $data = [];
        $i = 0;
        
        $data = array_map(function($asset) use ($tableStructure) {
            $row = $tableStructure;
            $id = $asset['assetID']; // hold onto assetID
            
            $row['edit']['href'] .= $id;
            if(!empty($row['edit']['heading']['collapse']))
                $row['edit']['collapse'] = $row['edit']['heading']['collapse'];
            
            foreach ($asset as $field => $value) {
                $row[$field]['value'] = $value ?: '—';
                if (!empty($row[$field]['href'])) {
                    $row[$field]['href'] .= $value;
                }
                // assign any collapse class on the heading to the td, too
                if (!empty($row[$field]['heading']['collapse'])) {
                    $row[$field]['collapse'] = $row[$field]['heading']['collapse'];
                }
            }
            
            return $row;
        }, $result);
        
        $context['cardHeading'] = 'Click on an asset number to see details';
        $context['data'] = $data;
        $context['tableHeadings'] = array_column($tableStructure, 'heading');
        $context['addPath'] = "assets.php/add";
        $context['info'] = "Click on an asset ID to see details";
    }
    
    $link->disconnect();
    
    return $context;
}

function createContext($data) {
    
}