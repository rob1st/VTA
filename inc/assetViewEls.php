<?php
$actions = ['list', 'add', 'update'];

$defaultFormCtrls = [
    'assetTag' => "<label for='assetTag' class='required'>Asset tag</label>
        <input type='text' name='assetTag' maxlength='50' value='%s' class='form-control item-margin-bottom' required>",
    'component' => "<label for='component' class='required'>Component</label>
        <select name='component' class='form-control item-margin-bottom' required>%s</select>",
    'location' => "<label for='location' class='required'>Location</label>
        <select name='location' class='form-control item-margin-bottom' required>%s</select>",
    'room' => "<label for='room' class='required'>Room</label>
        <input type='text' name='room' maxlength='25' value='%s' class='form-control item-margin-bottom' required>",
    'installStat' => "<label for='installStat' class='required'>Installed?</label>
        <select name='installStatus' class='form-control item-margin-bottom' required>%s</select>",
    'testStat' => "<label for='testStat'>Test status</label>
        <select name='testStatus' class='form-control item-margin-bottom'>%s</select>"
];
