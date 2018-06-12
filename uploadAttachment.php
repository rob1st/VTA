<?php
use codeguy\Upload;

include('sql_functions/sqlErrors.php');

function uploadAttachment($cnxn, $key, $dir, $assocID) {
    $filetypes = explode(',',
        preg_replace('/\s+/', '', file_get_contents('allowedFormats.csv')));
    $storage = new \Upload\Storage\FileSystem($dir);
    $attachment = new \Upload\File('bartdlAttachments', $storage);
    $filepath = $dir.'/'.$attachment->getNameWithExtension();
    $attachment->addValidations($filetypes);
    $sql = 'INSERT bartdlAttachments (bdaFilepath, bartdlID, uploaded_by, filesize, fileext, filename) VALUES (?, ?, ?, ?, ?, ?)';
    $types = 'siiiss';
    try {
        $attachment->upload();
        if (!$stmt = $cnxn->prepare($sql)) printSqlErrorAndExit($cnxn, $sql);
        if (!$stmt->bind_param($types,
            $cnxn->escape_string($filepath),
            intval($assocID),
            intval($_SESSION['UserID']),
            intval($attachment->getSize()),
            $cnxn->escape_string($attachment->getExtension()),
            $cnxn->escape_string($attachment->getNameWithExtension()))) printSqlErrorAndExit($stmt, $sql);
        if (!$stmt->execute()) printSqlErrorAndExit($stmt, $sql);
        else {
            $stmt->close();
            return $dir.'/'.$attachment->getNameWithExtension();
        }
    } catch (\Exception $e) {
        print "
            <h1>
                <pre style='color: crimson'>";
                    var_dump($attachment->getErrors());
        print "</pre>";
        return;
    }
}