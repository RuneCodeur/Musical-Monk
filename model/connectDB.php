<?php

class ConnectDB {

    //private $Adress = 'mysql:host=localhost;dbname=musical_monk;charset=utf8;port=3308';
    //private $User = 'root';
    //private $Password = 'root';

    private $Adress = 'mysql:host=mysql-musical-monk.alwaysdata.net;dbname=musical-monk_bd';
    private $User = '245938';
    private $Password = 'Oblivion-666';

    protected function Connection() {
        $bdd = new PDO($this->Adress, $this->User,$this->Password);
        $bdd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $bdd;
    }
}