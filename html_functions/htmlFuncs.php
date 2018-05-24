<?php
function returnHtmlForVal($val, $ifHtml, $elseHtml) {
    return (($val === null || $val === '')
        ? sprintf($elseHtml, $val)
        : sprintf($ifHtml, $val));
}
?>