<?php 
abstract class Autoload {
    public static function inclusionAuto($className) {
        require_once(__DIR__ . "/" . str_replace("\\", "/", $className) . ".php");
    }
}

spl_autoload_register(["Autoload", "inclusionAuto"]);
?>