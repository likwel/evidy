<?php

namespace App\Service;

use PDO;
use DateTime;
use DateTimeImmutable;
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
            `publication` varchar(255) NOT NULL,
            `confidentiality` text NOT NULL,
            `photos` longtext NULL COMMENT '(DC2Type:json)',
            `isWait` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function createTableactivity($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `product` varchar(255) NOT NULL,
            `description` text NOT NULL,
            `devise` varchar(255) NOT NULL,
            `location` varchar(255) NOT NULL,
            `user_id` int(11) NOT NULL,
            `price` float NOT NULL,
            `notPrice` float NOT NULL,
            `taxe` float NOT NULL DEFAULT 0,
            `quantity` float NOT NULL,
            `photos` longtext NULL COMMENT '(DC2Type:json)',
            `isSale` tinyint(1) NOT NULL DEFAULT 1,
            `isDelivery` tinyint(1) NOT NULL DEFAULT 0,
            `isWait` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function createTablefriends($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `user_id` varchar(255) NOT NULL,
            `isWait` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function getFullname($user_id){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT concat(firstname,' ', lastname) as fullname FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result["fullname"];
        }else{
            return null;
        }

    }

    public function getUsername($user_id){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT username FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["fullname"];

        return $result;

    }

    public function diff4humans($date){
        $now = new DateTime("now");
        $target = new DateTime($date);
        $interval = $now->diff($target);
        $heure = $interval->format("%h");
        $min = $interval->format("%i");
        $sec = $interval->format("%s");
        $day = $interval->format("%d");

        if($day >=1){
            return $day." jr";
        }else if($day < 1 && $heure<24){
            return $heure." hr";
        }else if($heure < 1 && $min < 60 && $sec<60){
            return $min." min";

        }else if($min <1 && $sec<60){
            return $sec." sec";
        }else{
            return " à l'instant";
        }
        //return $interval->format("%h:%i:%s");
    }

}