<?php
require_once 'ini/config.php';

$movies = new movies();
$top_100 = $movies->get_top_hundred_data();

$create_db = new db(db_method::CREATE);
$db = new db();
if ($db->create_movies_table()) {
    if ($db->insert_movies($top_100)) {
        echo functions::message();
        // Az egyes filmek adatainak szemleltetese kedveert
        functions::debug_value($top_100);
    } else {
        echo functions::message("Az adatbázis tábla feltöltése sikertelen volt.");
    }
} else {
    echo functions::message("Az adatbázis tábla létrehozása sikertelen volt.");
}


