<?php
require_once 'html_functions/htmlFuncs.php';
/*
EXAMPLE $fields ARRAY:
[
    [
        'auth' => int,
        'heading' => 'heading text',
        'classList' => [ 'array', 'of', 'classes' ] || 'string of classes'
    ]
]
*/
function printTableHeadings($fields, $authLvl) {
    $thead = "<thead><tr>%s</tr></thead>";
    $th = "<th %s>%s</th>";
    $headings = '';
    foreach ($fields as $field) {
        if (is_string($field)) {
            $curHeading = sprintf($th, '', $field);
        } elseif (is_array($field)) {
            if ($field['auth'] && $authLvl < $field['auth']) continue;
            $text = $field['text'];
            $classList = returnClassList($field['classList']);
            $curHeading = sprintf($th, $classList, $text);
        }
        $headings .= $curHeading;
    }
    printf($thead, $headings);
}

function populateTable(&$res, $fields, $authLvl) {
    $tbody = "<tbody>%s</tbody>";
    $tr = "<tr>%s</tr>";
    $td = "<td%s>%s</td>";
    $tableRows = '';
    while($row = $res->fetch_row()) {
        $i = 0;
        $curRow = '';
        // print "<pre>";
        // var_dump($row);
        // print "</pre>";
        foreach ($fields as $field) {
            $datum = $row[$i];
            // print "<p class='text-red'>".gettype($fields[$i])."</p>";
            if (is_string($field)) $curTd = sprintf(is_string($field), $datum);
            elseif (is_array($field)) {
                $curField = $field;
                if ($curField['auth'] && $authLvl < $curField['auth']) continue;
                // should factor this out into sep fcn and test for # %s args
                $innerHtml = $curField['innerHtml'] ? sprintf($curField['innerHtml'], $datum, '%s') : $datum;
                $classList = returnClassList($curField['classList']);
                $curTd = sprintf($td, $classList, $innerHtml);
                // print "<p class='text-yellow'>$field</p>";
            }
            $curRow .= $curTd;
            // print "<p class='text-yellow'>$field</p>";
            $i++;
        }
        $curRow = sprintf($tr, $curRow);
        $tableRows .= $curRow;
        /*
        // $table .= "
        //     <tr class='svbx-tr'>
        //         <td class='svbx-td id-td'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
        //         <td class='svbx-td loc-td collapse-sm collapse-xs'>{$row[1]}</td>
        //         <td class='svbx-td sev-td collapse-xs'>{$row[2]}</td>
        //         <td class='svbx-td created-td collapse-md  collapse-sm collapse-xs'>{$row[3]}</td>
        //         <td class='svbx-td status-td'>{$row[4]}</td>
        //         <td class='svbx-td system-td collapse-sm collapse-xs'>{$row[5]}</td>
        //         <td class='svbx-td descrip-td'>".nl2br($row[6])."</td>
        //         <td class='svbx-td collapse-md collapse-sm collapse-xs'>{$row[7]}</td>";
        // if ($lvl > 1) {
        //   $table .= "
        //         <td class='svbx-td updated-td collapse-md  collapse-sm collapse-xs'>{$row[8]}</td>
        //         <td class='svbx-td edit-td collapse-sm collapse-xs'>
        //             <form action='UpdateDef.php' method='POST' onsubmit=''/>
        //                 <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-edit'></i></button>
        //             </form>
        //         </td>";
        // } else $table .= "</tr>";
        */
    }
    // $res->close();
    printf($tbody, $tableRows);
}
?>