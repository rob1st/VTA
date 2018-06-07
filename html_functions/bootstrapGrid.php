<?php
require_once 'htmlForms.php';

function isElementArray(array $el) {
    return isset($el['element'])
        || isset($el['tagName'])
        || isset($el['label']);
}

function isFormCtrl(array $el) {
    return $el['tagName'] === 'select'
        || $el['tagName'] === 'input'
        || $el['tagName'] === 'textarea';
}
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
        // $isFormCtrl = $element['tagName'] === 'select'
        //     || $element['tagName'] === 'input'
        //     || $element['tagName'] === 'textarea';
        $content = isFormCtrl($element)
            ? isset($options['inline'])
                ? returnRow([ $element['label'], returnFormCtrl($element) ])
                : $element['label'].returnFormCtrl($element)
            : returnRow($element);
        $col = sprintf($colStr, $wd, $content);
    } elseif (is_string($element)) {
        $col = sprintf($colStr, $wd, $element);
    } else $col = sprintf($colStr,  $wd, '');
    return $col;
}

function returnRow(array $elements, $options = []) {
    $elRow = "<div class='row item-margin-bottom'>%s</div>";
    $colCollection = '';
    $numEls = count($elements);
    
    $purpleStar = "<span style='color: purple'>✳</span>";
    $yellowStar = "<span style='color: goldenRod'>✳</span>";
    
    // if number of elements doesn't divide evenly by 12 take the remainder
    // this number will be added to the last col
    $extraCols = 12 % $numEls;
    $colWd = floor(12 / $numEls);
    // if row is singular and has a specific wd, pass it and its wd to returnCol without looping
    if (count($elements) === 1) {
        // $offset = floor((12 - $options['colWd'])/2);
        $colCollection .= returnCol(array_shift($elements), $colWd, ['offset' => $offset]);
    } else {
        // if you're iterating you'll need a counter
        $i = 1;
        foreach ($elements as $el) {
            $colWd = $i === $numEls ? $colWd + $extraCols : $colWd ;
            $colCollection .= returnCol($el, $colWd, $options);
            $i++;
        }
    }
    return sprintf($elRow, $colCollection);
}
?>