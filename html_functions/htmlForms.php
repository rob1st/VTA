<?php
function returnSelectInput($cnxn, $data) {
    $selectEl = "<{$data['tagName']} name='{$data['name']}' id='{$data['id']}' class='form-control'>";
    $options = "<option value=''></option>";
    // if val @ [query] is a string, use it query db
    // if val @ [query] is a sql query result, use it
    $result = is_string($data['query']) ? $cnxn->query($data['query']) : $data['query'];
    if ($result) {
        while ($row = $result->fetch_row()) {
            $selected = $row[0] == $data['value'] ? ' selected' : ' data-notSelected';
            $options .= "<option value='{$row[0]}'{$selected}>{$row[1]}</option>";
        }
    } elseif ($cnxn->error) {
        $options .= "<option selected>{$cnxn->error}</option>";
    } else $options .= "<option selected>There was a problem with the query</option>";
    $selectEl .= $options;
    $selectEl .= "</select>";
    $result->close();
    return $selectEl;
}

function returnTextInput($cnxn, $data) {
    $inputEl = sprintf($data['element'], $data['value']);
    return $inputEl;
}

function returnDateInput($cnxn, $data) {
    $dateEl = sprintf($data['element'], $data['value']);
    return $dateEl;
}

function returnFileInput($cnxn, $data) {
    return $data['element'];
}

function returnTextarea($cnxn, $data) {
    $textarea = sprintf($data['element'], $data['value']);
    return $textarea;
}

function returnFormCtrl($cnxn, $formCtrl) {
    if ($formCtrl['tagName'] === 'select') {
        return returnSelectInput($cnxn, $formCtrl);
    } elseif ($formCtrl['tagName'] === 'input') {
        if ($formCtrl['type'] === 'text') {
            return returnTextInput($cnxn, $formCtrl);
        } elseif ($formCtrl['type'] === 'date') {
            return returnDateInput($cnxn, $formCtrl);
        } elseif ($formCtrl['type'] === 'file') {
            // $col .= "<h3 style='color: var(--purple)'>type === file</h3>";
            return returnFileInput($cnxn, $formCtrl);
        }
    } elseif ($formCtrl['tagName'] === 'textarea') {
        return returnTextarea($cnxn, $formCtrl);
    }
}

?>