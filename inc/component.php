<?php
$queries = array(
    'add' => "INSERT component (compName, compDescrip) VALUES (?, ?)",
    'list' => "SELECT * FROM component WHERE compName <> ''"
);