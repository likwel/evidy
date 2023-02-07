<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class NotificationService extends PDOService{

    public function getNotRead($table_notif){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_notif where isRead = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }
    public function getNotShow($table_notif){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) FROM $table_notif where isShow = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function getAll($table_notif){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_notif ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}