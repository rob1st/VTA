<?php
/*
** available options at this point are:
** 'inline'
** 'colWd'
** 'offset'
*/
function returnCol($element, $wd, $options = []) {
    if (isset($options['offset'])) {
        $wd .= " offset-md-{$options['offset']}";
    }

    $colStr = "<div class='col-md-$wd'>%s</div>";

    if (isset($options['inline'])) {
        $subCol = "<div class='col-sm-6'>%s</div>";
        $labelCol = sprintf($subCol, $element['label']);
        $ctrlCol = sprintf($subCol, returnFormCtrl($element));
        $subRow = sprintf("<div class='row'>%s%s</div>", $labelCol, $ctrlCol);
        $col = sprintf($colStr, $subRow);
    } else {
        $col = sprintf($colStr, $element['label'].returnFormCtrl($element));
    }
    return $col;
}

function returnRow($elements, $options = []) {
    $elRow = "<div class='row item-margin-bottom'>";
    $numEls = count($elements);
    // if number of elements don't divide evenly by 12 substract out the remainder
    // this number will be added to the last col
    $extraCols = 12 % $numEls;
    $colWd = 12 / ($numEls - $extraCols);
    // if row is singular and has a specific wd, pass it and its wd to returnCol without looping
    if (count($elements) === 1 && isset($options['colWd'])) {
        $offset = floor((12 - $options['colWd'])/2);
        $elRow .= returnCol($elements[0], $options['colWd'], ['offset' => $offset]);
    } else foreach ($elements as $el) {
        $elRow .= returnCol($el, $colWd, $options);
    }
    $elRow .= "</div>";
    return $elRow;
}

/* this fcn takes a collection of arrays of string names of form control elements,
** looks up those form controls by name in a collection that describes their properties
** and passes the named form control data to a fcn to that renders each subarray as a row
*/
// this fcn should take html string elements OR arrays of html attributes
// and pass them to a returnRow fcn, which will in turn pass them to a returnCol fcn
// which will then pass the strings or arrays to appropriate renderHtmlStr or renderHtmlArr fcns
// render fcns should not have to do any querying themselves, but should receive string data from finished queries

function returnRowGroup($group, $elementCollection, $options = []) {
    $rowGroup = '';
    foreach ($group as $row) {
        // iterate over each row replacing string at cur index with content at key = string in formCtrls
        foreach ($row as $i => $str) {
            $row[$i] = $elementCollection[$str];
        }
        $rowGroup .= returnRow($row, $options);
    }
    return $rowGroup;
}
?>