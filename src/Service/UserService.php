<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class UserService extends PDOService{

    public function createTablemessage($table){
        $rqt = "CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `user_id` int(11) NOT NULL,
            `content` text NOT NULL,
            `isForMe` tinyint(1) NOT NULL DEFAULT 0,
            `isRead` tinyint(1) NOT NULL DEFAULT 0,
            `isShow` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }
    public function createTablenotification($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `content` text NOT NULL,
            `isRead` tinyint(1) NOT NULL DEFAULT 0,
            `isShow` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $conn = $this->getConnection();
        $conn->exec($rqt);
        
    }
    public function createTablecarte($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `productName` varchar(255) NOT NULL,
            `user_id` int(11) NOT NULL,
            `price` float NOT NULL,
            `taxe` float NOT NULL,
            `quantity` float NOT NULL,
            `isWait` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function createTablepublication($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `productName` varchar(255) NOT NULL,
            `user_id` int(11) NOT NULL,
            `price` float NOT NULL,
            `taxe` float NOT NULL,
            `quantity` float NOT NULL,
            `isWait` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function getFullname($user_id){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT fullname FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["fullname"];

        return $result;

    }

    public function getUsername($user_id){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT username FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["fullname"];

        return $result;

    }
}