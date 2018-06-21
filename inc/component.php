<?php
$queries = array(
    'add' => "INSERT component (compName, compDescrip) VALUES (?, ?)",
    'list' => "SELECT *, count(compID) FROM component WHERE compName <> ''"
);