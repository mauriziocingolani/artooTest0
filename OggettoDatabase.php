<?php

class OggettoDatabase {

    private static $_mysqli;
    private static $_unicaIstanza;

    private function __construct() {
        
    }

    public static function GetUnicaIstanza() {
        if (!self::$_unicaIstanza)
            self::$_unicaIstanza = new OggettoDatabase;
        return self::$_unicaIstanza;
    }

    protected static function GetMysqli() {
        if (!self::$_mysqli)
            self::$_mysqli = @new mysqli('localhost', 'maurizio', 'maurizio', 'utenti');
        return self::$_mysqli;
    }

    protected static function GetErroreConnessione() {
        return 'ERRORE connessione MySQL: (' . self::$_mysqli->connect_errno . ') ' . self::$_mysqli->connect_error;
    }

}
