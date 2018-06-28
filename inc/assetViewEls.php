<?php
$actions = ['list', 'add', 'update'];

$defaultFormCtrls = [
    'assetTag' => "<label for='assetTag'>Asset tag</label>
        <input type='text' name='assetTag' maxlength='50' value='%s' class='form-control item-margin-bottom'>",
    'component' => "<label for='component'>Component</label>
        <select name='component' class='form-control item-margin-bottom'>%s</select>",
    'location' => "<label for='location'>Location</label>
        <select name='location' class='form-control item-margin-bottom'>%s</select>",
    'room' => "<label for='room'>Room</label>
        <input type='text' name='room' maxlength='25' value='%s' class='form-control item-margin-bottom'>",
    'installStat' => "<label for='installStat'>Install status</label>
        <select name='installStat' class='form-control item-margin-bottom'>%s</select>",
    'testStat' => "<label for='testStat'>Test status</label>
        <select name='testStat' class='form-control item-margin-bottom'>%s</select>"
];
