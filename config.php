<?PHP
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (strpos($actual_link, '.c9') !== false) {
    
    /*C9 Configuration Settings*/

    define('DB_Host', 'localhost'); /*Database Server*/
    define('DB_Name', 'CDL');  /*Database Name*/
    define('DB_USER', 'root'); /*Database Username*/
    define('DB_PWD', ''); /*Database Password*/
} else {
    
    /*Webserver Configuration Settings*/
    
    define('DB_Host', 'localhost'); /*Database Server*/
    define('DB_Name', 'svbxorg_defdb');  /*Database Name*/
    define('DB_USER', 'svbxorg_defdba'); /*Database Username*/
    define('DB_PWD', 'Berryessa2468!'); /*Database Password*/
    
}
?>