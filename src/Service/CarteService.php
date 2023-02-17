<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class CarteService extends PDOService{

    public function getNotWait($table_carte){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_carte where status = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function getAll($table_carte){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_carte ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function addOneCard($table_carte, $id, $user_id){

        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table_carte (content, sender_id, type) VALUES (?,?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$content,$sender_id, $type]);

    }

}