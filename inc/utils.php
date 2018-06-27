<?php
function boolToStr($bool) {
    return ($bool ? 'true' : 'false');
}

function isHtmlStr(string $str) {
    /* '/(?<=<)\w+(?=[^<]*?>)/'
    ** "/(?<=<)\w+(?=[^<]*?>)/"
    ** '/<\s?[^\>]*\/?\s?>/i'
    */
}

function pluralize($singular) {
    if (substr($singular, -1) === 'y' && substr($singular, -2) !== 'ey') {
        return substr($singular, 0, strlen($singular) - 1) . 'ies';
    } elseif (substr($singular, -1) === 's') {
        return $singular . 'es';
    } else return $singular . 's';
}