<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class MessageService extends PDOService{

    public function getNotRead(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM message where isRead = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }
    public function getNotShow(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM message where isShow = 0 and isForMe = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function setIsRead($user_id){
        $conn = $this -> getConnection();

        $sql = "UPDATE message SET isRead = 1 where user_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$user_id]);

    }


    public function setIsShow(){
        $conn = $this -> getConnection();

        $sql = "UPDATE message SET isShow = 1 ";
        $statement = $conn->prepare($sql);
        $statement->execute();

    }

    public function getAll(){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM message GROUP BY user_id ORDER BY id DESC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    
    public function getMessageById($user_id){
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM message where user_id = $user_id");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getMessageUser($user_id){

        $my_id = 1;

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM message where receiver_id = $my_id and sender_id = $user_id order by id ASC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    
    public function sendOneMessage($user_id, $content, $isForMe){
        $conn = $this -> getConnection();
        $sql = "INSERT INTO message (user_id, content, isForMe) VALUES (?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$user_id,$content,$isForMe]);
    }

}