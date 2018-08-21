<?php
use codeguy\Upload;
use codeguy\Upload\Exception;
require 'vendor/autoload.php';

include_once('error_handling/sqlErrors.php');

$userID = intval($_SESSION['userID']);

function uploadAttachment($cnxn, $key, $dir, int $assocID) {
    $filetypes = explode(',',
        preg_replace('/\s+/', '', file_get_contents('allowedFormats.csv')));
    $storage = new \Upload\Storage\FileSystem($dir);
    $attachment = new \Upload\File($key, $storage);
    $filename = $attachment->getNameWithExtension();
    $filename = $link->escape_string($filename);
    $filepath = $dir . '/' . $filename;
    $filesize = $attachment->getSize();
    $fileext = $attachment->getExtension();
    $attachment->addValidations($filetypes);
    $sql = 'INSERT bartdlAttachments (bdaFilepath, bartdlID, uploaded_by, filesize, fileext, filename) VALUES (?, ?, ?, ?, ?, ?)';
    $types = 'siiiss';
    
    try { // upload file
        $attachment->upload();
    } catch (UploadException $e) {
        throw new UploadException($e);
    }
    
    try { // commit file data to db
        if (!$stmt = $cnxn->prepare($sql)) throw new mysqli_sql_exception($cnxn->error);
        if (!$stmt->bind_param($types,
            $filepath,
            $assocID,
            $userID,
            $filesize,
            $fileext,
            $filename)) throw new mysqli_sql_exception($stmt->error);
        if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        $stmt->close();
        return $filepath;
    } catch (\mysqli_sql_exception $e) {
        $stmt->close();
        throw new mysqli_sql_exception($e);
    } catch (\Exception $e) {
        throw new Exception($e);
    }
}