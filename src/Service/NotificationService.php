<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class NotificationService extends PDOService{

    public function getNotRead($table_notif){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_notif where is_read = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }
    public function getNotShow($table_notif){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_notif where is_show = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function setIsRead($table_notif, $user_id){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_notif SET is_read = 1 where user_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$user_id]);

    }


    public function setIsShow($table_notif){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_notif SET is_show = 1 ";
        $statement = $conn->prepare($sql);
        $statement->execute();

    }

    public function getAll($table_notif){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_notif where is_read = 0 order by id DESC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function sendOneNotification($table_notif, $content,$sender_id, $type){

        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table_notif (content, sender_id, type) VALUES (?,?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$content,$sender_id, $type]);

    }

}