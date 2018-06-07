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

function ifOptionsExtract(array &$el, array &$opts) {
    if (isset($el['options'])) {
        $opts = $opts + $el['options']; // but which one should take precendence??
        unset($el['options']);
    }
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
            // print "<p style='color: fuchsia; font-size: .65rem;'>";
            // var_dump($options);
            // print "</p>";
            
            $content = isFormCtrl($element)
                ? isset($options['inline'])
                    ? returnRow([ $element['label'], returnFormCtrl($element) ])
                    : $element['label'].returnFormCtrl($element)
                : "<h4 class='text-yellow'>return html from array from $element</h4>"; // PLACEHOLDER
        } else {
            $content = '';
            foreach ($element as $el) {
                $content .= returnRow($el, $options);
            }
        }
        $col = sprintf($colStr, $wd, $content);
    } elseif (is_string($element)) {
        $col = sprintf($colStr, $wd, $element);
    } else $col = sprintf($colStr,  $wd, '');
    return $col;
}

function returnRow(array $elements, $options = []) {
    // if options rolled into $elements, extract it
    ifOptionsExtract($elements, $options);
    // print "<p class='text-yellow' style='font-size: .6rem'>";
    // var_dump($options);
    // print "</p>";
    
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
    if ($numEls === 1) {
        $el = array_shift($elements);
        is_array($el) && ifOptionsExtract($el, $options);
        // check if colWd option is present
        // option on column takes precendence if given
        isset($options['colWd']) && $colWd = $options['colWd'];
        isset($el['options']['colWd']) && $colWd = $el['options']['colWd'];
        
        $options['offset'] = isset($options['offset'])
            ? $options['offset']
            : (int) floor(12 - $colWd)/2;
            
        $colCollection .= returnCol($el, $colWd, $options);
    } else {
        // if you're iterating you'll need a counter
        $i = 1;
        foreach ($elements as $el) {
            // write options to a new var so as not to preserve options from prev col
            $colOpts = $options;
            is_array($el) && ifOptionsExtract($el, $colOpts);
            
            // print "<h4 class='text-primary' style='font-size: .5rem'>";
            // var_dump($options);
            // print "</h4>";
            
            $colWd = $i === $numEls ? $colWd + $extraCols : $colWd;
            $colCollection .= returnCol($el, $colWd, $colOpts);
            $i++;
        }
    }
    return sprintf($elRow, $colCollection);
}
?>