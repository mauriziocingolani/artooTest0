<?php

class Ruoli {

    public $RuoloID;
    public $Descrizione;
    public $Colore;

    public function __construct(array $params) {
        $this->RuoloID = (int) $params['RuoloID'];
        $this->Descrizione = $params['Descrizione'];
        $this->Colore = $params['Colore'];
    }

    public static function GetAll() {
        $mysqli = @new mysqli('localhost', 'maurizio', 'maurizio', 'utenti');
        if ($mysqli->connect_errno > 0)
            return 'ERRORE connessione MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
        $ruoli = $mysqli->query("SELECT * FROM ruoli");
        if ($mysqli->errno > 0) :
            return __METHOD__ . ' -> ERRORE SQL: (' . $mysqli->errno . ') ' . $mysqli->error;
        else :
            $a = array();
            while (true) :
                $ruolo = $ruoli->fetch_assoc();
                if ($ruolo != null) :
                    $a[] = new Ruoli($ruolo);
                else :
                    break;
                endif;
            endwhile;
            return $a;
        endif;
    }

}
