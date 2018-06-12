<?php
use codeguy\Upload;
require 'vendor/autoload.php';

include_once('error_handling/sqlErrors.php');

function uploadAttachment($cnxn, $key, $dir, $assocID) {
    $filetypes = explode(',',
        preg_replace('/\s+/', '', file_get_contents('allowedFormats.csv')));
    $storage = new \Upload\Storage\FileSystem($dir);
    $attachment = new \Upload\File($key, $storage);
    $filename = $attachment->getNameWithExtension();
    $filepath = $dir.'/'.$filename;
    $filesize = $attachment->getSize();
    $fileext = $attachment->getExtension();
    $attachment->addValidations($filetypes);
    $sql = 'INSERT bartdlAttachments (bdaFilepath, bartdlID, uploaded_by, filesize, fileext, filename) VALUES (?, ?, ?, ?, ?, ?)';
    $types = 'siiiss';
    try {
        $attachment->upload();
    } catch (\Exception $e) {
        print "
            <h1>
                <pre style='color: crimson'>"
                    .$e->getMessage()
                ."</pre>
            </h1>";
    }
    try {
        if (!$stmt = $cnxn->prepare($sql)) printSqlErrorAndExit($cnxn, $sql);
        if (!$stmt->bind_param($types,
            $cnxn->escape_string($filepath),
            intval($assocID),
            intval($_SESSION['UserID']),
            intval($filesize),
            $cnxn->escape_string($fileext),
            $cnxn->escape_string($filename))) printSqlErrorAndExit($stmt, $sql);
        if (!$stmt->execute()) printSqlErrorAndExit($stmt, $sql);
        else {
            $stmt->close();
            return $filepath;
        }
    } catch (\Exception $e) {
        print "
            <h1>
                <pre style='color: deepPink'>"
                    .$e->getMessage()
                ."</pre>
            </h1>";
    }
}