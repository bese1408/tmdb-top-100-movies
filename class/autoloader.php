<?php

class Autoloader {

    /**
     *
     * @param string $className
     * @return boolean
     */
    static public function loader(string $className) {
        
        $filename[] = "class/" . strtolower(str_replace("\\", "/", $className) . ".php");
       
        $match = false;
        foreach ($filename as $value) {
            if (!$match && file_exists($value)) {
                require_once $value;
                if (class_exists($className)) {
                    $match = true;
                }
            }
        }
        
        return $match;
    }

}

spl_autoload_register('Autoloader::loader');
