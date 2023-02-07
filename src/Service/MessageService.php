<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;

class MessageService extends PDOService{


    public function getNotRead($table_msg){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_msg where isRead = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }
    public function getNotShow($table_msg){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_msg where isShow = 0 and isForMe = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function setIsRead($table_msg, $user_id){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_msg SET isRead = 1 where user_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$user_id]);

    }


    public function setIsShow($table_msg){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_msg SET isShow = 1 ";
        $statement = $conn->prepare($sql);
        $statement->execute();

    }

    public function getAll($table_msg){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_msg GROUP BY user_id ORDER BY id DESC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    
    public function getMessageById($table_msg,$user_id){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_msg where user_id = $user_id");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getMessageUser($table_msg, $user_id){

        $my_id = 1;

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_msg where receiver_id = $my_id and sender_id = $user_id order by id ASC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    
    public function sendOneMessage($table_msg, $user_id, $content, $isForMe){
        $conn = $this -> getConnection();
        $sql = "INSERT INTO $table_msg (user_id, content, isForMe) VALUES (?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$user_id,$content,$isForMe]);
    }

}