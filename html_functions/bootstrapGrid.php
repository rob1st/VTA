<?php
/*
** available options at this point are:
** 'inline'
** 'colWd'
** 'offset'
*/
// this function should receive a complete element string unless it's a select element that needs iterating over
function returnCol($element, $wd, $options = []) {
    $colStr = "<div class='col-md-%s'>%s</div>";
    $wd = isset($options['offset']) ? $wd." offset-md-{$options['offset']}" : $wd;
    if (is_array($element)) {
        if (isset($options['inline'])) {
            $subCol = "<div class='col-sm-6'>%s</div>";
            $labelCol = sprintf($subCol, $element['label']);
            $ctrlCol = sprintf($subCol, returnFormCtrl($element));
            $subRow = sprintf("<div class='row'>%s%s</div>", $labelCol, $ctrlCol);
            $col = sprintf($colStr, $wd, $subRow);
        } else {
            $col = sprintf($colStr, $wd, $element['label'].returnFormCtrl($element));
        }
    } elseif (is_string($element)) {
        $col = sprintf($colStr, $wd, $element);
    } else $col = sprintf($colStr,  $wd, '');
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
?>