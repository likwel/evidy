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
            `is_read` tinyint(1) NOT NULL DEFAULT 0,
            `is_show` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }
    public function createTablenotification($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `content` text NOT NULL,
            `sender_id` int(11) NOT NULL,
            `type` varchar(100) NOT NULL,
            `is_read` tinyint(1) NOT NULL DEFAULT 0,
            `is_show` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $conn = $this->getConnection();
        $conn->exec($rqt);
        
    }
    public function createTablecarte($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `product` varchar(255) NOT NULL,
            `user_id` int(11) NOT NULL,
            `price` float NOT NULL,
            `taxe` float NOT NULL DEFAULT 0,
            `quantity` float NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 0,
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
            `is_follower` tinyint(1) NOT NULL DEFAULT 0,
            `is_wait` tinyint(1) NOT NULL DEFAULT 0,
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

    public function getAllFriends($table){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    
    public function isFriend($table, $user_id){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as Nb FROM $table where user_id = '$user_id' ");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["Nb"];
        
        if($result > 0){
            return true;
        }else{
            return false;
        }
    }

    public function addFriend($table,$user_id, $isFollow, $isWait){
        
        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table (user_id, is_follower, is_wait) VALUES (?,?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$user_id, $isFollow, $isWait]);

    }

    public function getAllSponsored($tab){
        
        $conn = $this -> getConnection();

        $stm = $conn->prepare("SELECT * FROM $tab where is_active = 1 order by id DESC");

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getFollowerNumber($table){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as NB FROM $table where is_follower =1");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["NB"];

        return $result;
    }

    public function getSuiviNumber($table){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as NB FROM $table where is_follower = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["NB"];

        return $result;
    }

    public function acceptFriend($table, $user_id, $my_id)
    {
        $conn = $this -> getConnection();

        $sql = "UPDATE $table SET is_wait = 0 WHERE user_id =?";

        $stm = $conn->prepare($sql);

        $tb = "tb_friends_".$user_id;

        $sql2 = "UPDATE $tb SET is_wait = 0 WHERE user_id = ? ";

        $stm2 = $conn->prepare($sql2);

        $stm->bindParam(1, $user_id);

        $stm->execute();

        $stm2->bindParam(1, $my_id);

        $stm2->execute();
    }
    public function deleteFriend($table, $user_id, $my_id)
    {
        $conn = $this -> getConnection();
        $sql = "DELETE FROM $table WHERE user_id=?";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$user_id]);

        $tb = "tb_friends_".$user_id;
        $sql2 = "DELETE FROM $tb WHERE user_id=?";
        $stmt2= $conn->prepare($sql2);
        $stmt2->execute([$my_id]);
    }

    public function getProfil($user_id)
    {
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT profil FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["profil"];

        return $result;
    }
    public function getCouverture($user_id)
    {
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT couverture FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["couverture"];

        return $result;
    }

    public function updateUser($nom, $prenom, $pseudo, $email, $pswd, $pswd2, $user_id)
    {
        if($pswd != $pswd2){
            return false;
        }else{

            $conn = $this -> getConnection();

            $sql = "UPDATE user SET firstname=?, lastname =? , email =? ,pseudo =?,password =? WHERE id =?";

            $stm = $conn->prepare($sql);

            $stm->execute([$nom, $prenom, $pseudo, $email, $pswd, $pswd2, $user_id]);

            return true;
        }
        
    }

}