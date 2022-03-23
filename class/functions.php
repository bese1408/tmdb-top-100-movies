<?php

class functions {
    
    public function __construct() {
        ;
    }
    
    public static function debug_value($value) {
        echo "<pre>", print_r($value), "</pre>";
    }
    
    public static function message(string $error_msg = "") {
        if (empty($error_msg)) {
            $msg = "<h2 style='color:green'>SIKERES MŰVELET!</h2>"
                    . "<h3>A top 100 film adatai itt találhatók, melyek a \"tmdb\" adatbázis \"top movies\" táblájában is megtalálhatók.</h3>";
        } else {
            $msg = "<h2 style='color:red'>SIKERTELEN MŰVELET!</h2><br />"
                    . "<h3>" . $error_msg . "</h3>";
        }
        return $msg;
    }
}
