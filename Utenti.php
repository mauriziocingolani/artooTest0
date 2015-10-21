<?php

require_once 'Persona.php';

class Utenti extends Persona {

    const REGEX_EMAIL = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    public $UtenteID;
    public $Email;
    public $RuoloID;
    public $Abilitato;
    public $Ruolo;
    public $UltimoAccesso;

    public function __construct(array $params) {
        $this->UtenteID = isset($params['UtenteID']) ? (int) $params['UtenteID'] : null;
        $this->Nome = strlen($params['Nome']) > 0 ? $params['Nome'] : null;
        $this->Cognome = strlen($params['Cognome']) > 0 ? $params['Cognome'] : null;
        $this->Email = $params['Email'];
        $this->RuoloID = (int) $params['RuoloID'];
        $this->Abilitato = isset($params['Abilitato']) ? (bool) $params['Abilitato'] : false;
        $this->Ruolo = isset($params['Ruolo']) ? $params['Ruolo'] : null;
        $this->UltimoAccesso = isset($params['Login']) ? $params['Login'] : null;
    }

    public function isValid() {
        if (strlen($this->Email) > 0) :
            if (preg_match(self::REGEX_EMAIL, $this->Email) === 1) :
                return true;
            else :
                return 'Indirizzo email non valido!';
            endif;
        else :
            return 'Devi inserire l\'indirizzo email!';
        endif;
    }

    public function save() {
        $mysqli = self::GetMysqli();
        if ($mysqli->connect_errno > 0)
            return self::GetErroreConnessione ();
        if ($this->UtenteID > 0) :
            $n = "UPDATE utenti " .
                    "SET Nome='{$this->_rimpiazzaApostrofi($this->Nome)}' ," .
                    "Cognome='{$this->_rimpiazzaApostrofi($this->Cognome)}' ," .
                    "Email='$this->Email' ," .
                    "RuoloID=$this->RuoloID," .
                    "Abilitato=" . ($this->Abilitato ? '1 ' : '0 ') .
                    "WHERE UtenteID={$this->UtenteID}";
        else :
            $n = "INSERT INTO utenti (RuoloID,Nome,Cognome,Email,Abilitato) " .
                    "VALUES (" .
                    "{$this->RuoloID}," .
                    "'{$this->_rimpiazzaApostrofi($this->Nome)}'," .
                    "'{$this->_rimpiazzaApostrofi($this->Cognome)}'," .
                    "'{$this->Email}'," .
                    ($this->Abilitato ? '1 ' : '0 ') .
                    ")";
        endif;
        $mysqli->query($n);
        if ($mysqli->errno > 0) :
            return __METHOD__ . ' -> ERRORE SQL: (' . $mysqli->errno . ') ' . $mysqli->error;
        else :
            header('Location: /Utenti/utente.php?utenteid=' . ($this->UtenteID > 0 ? $this->UtenteID : $mysqli->insert_id));
        endif;
    }

    private function _rimpiazzaApostrofi($stringa) {
        return preg_replace('/[\']/iD', "''", $stringa);
    }

    public static function GetAll() {
        $mysqli = OggettoDatabase::GetMysqli();
        if ($mysqli->connect_errno > 0)
            return 'ERRORE connessione MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
        $utenti = $mysqli->query(
                "SELECT utenti.*,Descrizione AS Ruolo,MAX(DataOra) AS Login FROM utenti " .
                "JOIN ruoli USING(RuoloID) " .
                "LEFT JOIN logins USING(UtenteID) " .
                "GROUP BY UtenteID " .
                "ORDER BY UtenteID"
        );
        if ($mysqli->errno > 0) :
            return __METHOD__ . ' -> ERRORE SQL: (' . $mysqli->errno . ') ' . $mysqli->error;
        else :
            $a = array();
            while (true) :
                $utente = $utenti->fetch_assoc();
                if ($utente != null) :
                    $a[] = new Utenti($utente);
                else :
                    break;
                endif;
            endwhile;
            return $a;
        endif;
    }

    public static function CreaUtente($utenteid) {
        $mysqli = @new mysqli('localhost', 'maurizio', 'maurizio', 'utenti');
        if ($mysqli->connect_errno > 0)
            return 'ERRORE connessione MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
        $utente = $mysqli->query(
                "SELECT * FROM utenti WHERE UtenteID=$utenteid"
        );
        if ($mysqli->errno > 0) :
            return __METHOD__ . ' -> ERRORE SQL: (' . $mysqli->errno . ') ' . $mysqli->error;
        else :
            $u = $utente->fetch_assoc();
            if ($u == null) :
                return 'ERRORE: utente inesistente!';
            else :
                return new Utenti($u);
            endif;
        endif;
    }

    public static function Elimina($utenteid) {
        $mysqli = new mysqli('localhost', 'maurizio', 'maurizio', 'utenti');
        if ($mysqli->connect_errno > 0)
            return 'ERRORE connessione MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;

        $res = $mysqli->query(
                "DELETE FROM utenti WHERE UtenteID=$utenteid");
        if ($mysqli->errno > 0) :
            return __METHOD__ . ' -> ERRORE SQL: (' . $mysqli->errno . ') ' . $mysqli->error;
        else :
            return true;
        endif;
    }

}
