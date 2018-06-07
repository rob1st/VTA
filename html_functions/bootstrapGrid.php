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
    $wd = $wd ? $wd : 12;
    isset($options['offset']) && $wd .= " offset-md-{$options['offset']}";
    if (is_array($element)) {
        // right here I need to test for whether $element is a collection of $elements or a singular $element array
        // if it's a singular element array then I can pass it to the relevant fcn (formCtrl or returnRow)
        // if it's a collection I need to iterate it, passing each element to returnRow
        if (isElementArray($element)) {
            $content = isFormCtrl($element)
                ? isset($options['inline'])
                    ? returnRow([ $element['label'], returnFormCtrl($element) ])
                    : $element['label'].returnFormCtrl($element)
                : "<h4 class='text-yellow'>return html from array for $element</h4>"; // PLACEHOLDER
        } else {
            $content = '';
            foreach ($element as $el) {
                $content .= returnRow($el);
            }
        }
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
        $el = array_shift($elements);
        // check if colWd option is present
        // option on column takes precendence if given
        isset($options['colWd']) && $colWd = $options['colWd'];
        isset($el['options']['colWd']) && $colWd = $el['options']['colWd'];
        $offset = $colWd < 12 ? floor(12 - $colWd)/2 : 0;
        // $offset = floor((12 - $options['colWd'])/2);
        $colCollection .= returnCol($el, $colWd, [ 'offset' => $offset ]);
    } else {
        // if you're iterating you'll need a counter
        $i = 1;
        foreach ($elements as $el) {
            $colWd = $i === $numEls ? $colWd + $extraCols : $colWd ;
            $colCollection .= returnCol($el, $colWd);
            $i++;
        }
    }
    return sprintf($elRow, $colCollection);
}
?>