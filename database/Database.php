<?php

namespace database;

class DataBase
{
    private $dbh;

    public function __construct(String $host, String $name, String $user, String $pass, String $port = "3306") {
        try{
            $this->dbh = new \PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass); // Initialisation de la connexion à la base de donnée
        } catch (\PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br>";
            die();
        }
    }

    public function GetConnexion() {
        return $this->dbh;
    }
}
