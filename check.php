<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
session_start();
$dir = new DirectoryIterator('/home/ubuntu/workspace/');
?>
<?php
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
?>
<ol>
<?php
foreach ($dir as $file) {
    $content = file_get_contents($file->getPathname());
    if (strpos($content, $string) !== false) {
        echo "<li>{$file}</li>";
    }
}
?>
</ol>
</body>
</html>