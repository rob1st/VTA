<?php
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
            $classList = '';
            if ($field['classList']) {
                $classList = " class='";
                if (is_string($field['classList'])) $classList .= $field['classList']."'";
                elseif (is_array($field['classList'])) $classList .= implode(' ', $field['classList'])."'";
            }
            $curHeading = sprintf($th, $classList, $text);
        }
        $headings .= $curHeading;
    }
    printf($thead, $headings);
}

function populateTable($cnxn, $qry, $lvl) {
    if ($result = $cnxn->query($qry)) {
        if ($result->num_rows) {
            $lvl = is_bool(lvl) ? boolToStr($lvl) : $lvl;
            $table = "
                <table class='table table-striped table-responsive svbx-table'>
                    <thead>
                        <tr class='svbx-tr table-heading'>
                            <th class='svbx-th id-th'>ID</th>
                            <th class='svbx-th loc-th collapse-sm collapse-xs'>Location</th>
                            <th class='svbx-th sev-th collapse-xs'>Severity</th>
                            <th class='svbx-th created-th collapse-md  collapse-sm collapse-xs'>Date Created</th>
                            <th class='svbx-th status-th'>Status</th>
                            <th class='svbx-th system-th collapse-sm collapse-xs'>System Affected</th>
                            <th class='svbx-th descrip-th'>Brief Description</th>
                            <th class='svbx-th collapse-md collapse-sm collapse-xs'>Spec Loc</th>";
                    if ($lvl > 1) {
                        $table .= "
                            <th class='svbx-th updated-th collapse-md collapse-sm collapse-xs'>Last Updated</th>
                            <th class='svbx-th edit-th collapse-sm collapse-xs'>Edit</th>";
                    } $table .= "</tr></thead><tbody>";
                    
                while($row = $result->fetch_array()) {
                    $table .= "
                        <tr class='svbx-tr'>
                            <td class='svbx-td id-td'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
                            <td class='svbx-td loc-td collapse-sm collapse-xs'>{$row[1]}</td>
                            <td class='svbx-td sev-td collapse-xs'>{$row[2]}</td>
                            <td class='svbx-td created-td collapse-md  collapse-sm collapse-xs'>{$row[3]}</td>
                            <td class='svbx-td status-td'>{$row[4]}</td>
                            <td class='svbx-td system-td collapse-sm collapse-xs'>{$row[5]}</td>
                            <td class='svbx-td descrip-td'>".nl2br($row[6])."</td>
                            <td class='svbx-td collapse-md collapse-sm collapse-xs'>{$row[7]}</td>";
                    if ($lvl > 1) {
                       $table .= "
                            <td class='svbx-td updated-td collapse-md  collapse-sm collapse-xs'>{$row[8]}</td>
                            <td class='svbx-td edit-td collapse-sm collapse-xs'>
                                <form action='UpdateDef.php' method='POST' onsubmit=''/>
                                    <button type='submit' name='q' value='".$row[0]."'><i class='typcn typcn-edit'></i></button>
                                </form>
                            </td>";
                    } else $table .= "</tr>";
                }
            $table .= "</tbody></table>";
        } else {
            $table .= "<h4 class='text-secondary text-center'>No results found for your search</h4>";
        }
        $result->close();
    } elseif ($cnxn->error) {
        $table .= "<h4 class='text-danger center-content'>Error: $cnxn->error</h4><p>$qry</p>";
    }
    return $table;
}
?>