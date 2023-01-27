<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class NotificationService extends PDOService{

    public function getNotRead(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM notification where isRead = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }
    public function getNotShow(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) FROM notification where isShow = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function getAll(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM notification ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}