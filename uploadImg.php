<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 2rem;
            font-family: Verdana, Geneva, sans-serif;
        }
    </style>
</head>
<body>
<?php
session_start();

$types = [ 'jpg', 'jpeg', 'png', 'gif'];

function returnsTrue($anything) {
    return true;
}

function boolToStr($bool) {
    return ($bool ? 'true' : 'false');
}

function filetypeCheck($type) {
    global $types;
    return (array_search($type, $types) !== false);
}

function mimetypeCheck($mimetype) {
    if (strpos($mimetype, 'image/') !== false) {
        // extract type info from MIME string
        $type = substr($mimetype, strpos($mimetype, '/') + 1);
        return filetypeCheck($type);
    } else return false;
}
// validate image uploaded
if (!$_FILES['error']) {
    if ($filename = basename($_FILES['userImg']['name'])) {
        $folder = '/img_uploads';
        $targetDir = $_SERVER['DOCUMENT_ROOT'].$folder;
        $fileDest = $targetDir.'/'.$filename;
        if ($fileIsImg = mimetypeCheck($_FILES['userImg']['type'])) {
            echo "<h4 style='color: darkMagenta'><i>MIME type:</i> {$fileIsImg}, {$_FILES['userImg']['type']}</h4>";
        } else {
            echo "File did not pass MIME type check: ".$_FILES['userImg']['type'];
            exit;
        } if ($fileIsImg = fileTypeCheck(strtolower(pathinfo($filename, PATHINFO_EXTENSION)))) {
            echo "<h4 style='color: mediumBlue'><i>file extension:</i> $fileIsImg, ".strtolower(pathinfo($filename, PATHINFO_EXTENSION))."</h4>";
        } else {
            echo "File did not pass extension type check: ".strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            exit;
        } if ($uploadImgData = getimagesize($_FILES['userImg']['tmp_name'])) {
            $tmpName = $_FILES['userImg']['tmp_name'];
            $moveTarget = $targetDir.'/'.$tmpName;
            echo "<h4 style='color: forestGreen'><i>file info:</i> {$tmpName}, $filename, {$uploadImgData[3]}, {$uploadImgData['mime']}</h4>";
        } else {
            echo "File ".$tmpName." did not pass getimagesize test";
            exit;
        } if ($fileIsImg = move_uploaded_file($tmpName, $fileDest)) {
            echo "
                <h4 style='color: slateBlue'><i>File moved:</i> {$moveTarget} -> {$fileDest}</h4>
                <img src='{$folder}/{$filename}'>";
        } else {
            echo "Could not move file $moveTarget -> $fileDest";
        }
    }
} else {
    echo "There was an error uploading your file: ".$_FILES['error'];
    exit;
}

echo "<pre style='border-radius: .25rem; padding: 1.25rem; background-color: gainsboro; color: dodgerBlue'>";
echo var_dump($_FILES);
echo "</pre>";
// then transfer it to its permanent home
?>
</body>
</html>