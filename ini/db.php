<?php
/**
 * Adatbazis kapcsolat beallitasa
 */

$db_ini_file = "ini/db.ini";
if (file_exists($db_ini_file)) {

    if (!$ini_array = parse_ini_file($db_ini_file, true)) {
        throw new exception('Nem lehet megnyitni ' . $db_ini_file);
    }

    define('DB_SERVER_HOST', ($ini_array['database']['server_host'] ?? 'localhost'));
    define('DB_USER', ($ini_array['database']['user'] ?? ''));
    define('DB_PASSWD', $ini_array['database']['password']);
    define('DB_NAME', ($ini_array['database']['db_name'] ?? ''));
    define('DB_CHARSET', ($ini_array['database']['charset'] ?? 'utf8'));

}else {
    throw new exception('Hianyzik a db ini file');
}
