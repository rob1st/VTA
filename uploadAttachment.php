<?php
use codeguy\Upload;

include('sql_functions/sqlErrors.php');

function uploadAttachment($cnxn, $key, $dir, $assocID) {
    $filetypes = explode(',',
        preg_replace('/\s+/', '', file_get_contents('allowedFormats.csv')));
    $storage = new \Upload\Storage\FileSystem($dir);
    $attachment = new \Upload\File('bartdlAttachments', $storage);
    $newFilename = substr($_SESSION['Username'], 0, 6)
        ."_".str_pad($assocID, 11, '0', STR_PAD_LEFT)
        ."_".time();
    $attachment->setName($newFilename);
    $filepath = $dir.'/'.$attachment->getNameWithExtension();
    $attachment->addValidations($filetypes);
    $sql = 'INSERT bartdlAttachments (bdaFilepath, bartdlID) VALUES (?, ?)';
    $types = 'si';
    try {
        $attachment->upload($newFilename);
        if (!$stmt = $cnxn->prepare($sql)) printSqlErrorAndExit($cnxn, $sql);
        if (!$stmt->bind_param($types,
            $cnxn->escape_string($filepath),
            intval($assocID))) printSqlErrorAndExit($stmt, $sql);
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