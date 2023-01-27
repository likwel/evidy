<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class CarteService extends PDOService{

    public function getNotWait(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM carte where isWait = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function getAll(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM carte ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}