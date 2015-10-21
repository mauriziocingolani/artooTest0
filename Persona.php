<?php

require_once 'OggettoDatabase.php';

class Persona extends OggettoDatabase {

    public $Nome;
    public $Cognome;
    
    public function getNomeCognome() {
        return $this->Nome . ' ' . $this->Cognome;
    }

}
