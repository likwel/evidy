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
            `product_id` int(11) NOT NULL,
            `user_id` int(11) NOT NULL,
            `quantity` float NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 0,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function createTablecommentaire($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `user_id` int(11) NOT NULL,
            `activity_id` int(11) NOT NULL,
            `content` text NOT NULL,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function createTablereaction($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `user_id` int(11) NOT NULL,
            `activity_id` int(11) NOT NULL,
            `datetime` datetime NOT NULL DEFAULT current_timestamp()
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $conn = $this->getConnection();
        $conn->exec($rqt);

    }

    public function createTablepartage($table){

        $rqt ="CREATE TABLE ".$table." (
            `id` int(11) AUTO_INCREMENT PRIMARY KEY,
            `user_id` int(11) NOT NULL,
            `activity_id` int(11) NOT NULL,
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
            `localisation` varchar(255) NOT NULL,
            `user_id` int(11) NOT NULL,
            `price` float NOT NULL,
            `notPrice` float NOT NULL,
            `taxe` float NOT NULL DEFAULT 0,
            `quantity` float NOT NULL,
            `photos` longtext NULL COMMENT '(DC2Type:json)',
            `type` varchar(25) NOT NULL,
            `famille` varchar(25) NOT NULL,
            `category` varchar(25) NOT NULL,
            `isDelivery` tinyint(1) NOT NULL DEFAULT 0,
            `status` tinyint(1) NOT NULL DEFAULT 0,
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

    public function getUserById($user_id){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM user where id = $user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getAllFriends($table){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function getNumberFriends($table){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as NB FROM $table where is_wait = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["NB"];

        return $result;

    }

    public function getAllFriendsId($table){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT user.id, profil,firstname,lastname FROM user INNER JOIN $table ON user.id = $table.user_id ");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    
    public function isFriend($table, $user_id){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as Nb FROM $table where user_id = $user_id and is_wait= 0 ");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["Nb"];
        
        return $result;
    }

    public function positionFriend($table, $user_id){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table where user_id = $user_id ");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        return $result;
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
    public function getSumUser()
    {
        $conn = $this -> getConnection(); 

        $statement = $conn->prepare("SELECT count(id) as somme, SUBSTR(datetime, 1, 7) as datetime FROM user group by SUBSTR(datetime, 1, 7)");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function terminatedSponsor($id, $user_id){

        $conn = $this -> getConnection();

        $sql = "UPDATE admin_sponsored SET is_active = 0 where id = ? and user_id =?";
        $statement = $conn->prepare($sql);
        $statement->execute([$id,$user_id]);

    }
    public function addSponsor($user_id, $id){

        $conn = $this -> getConnection();

        $sql = "INSERT into admin_sponsored (user_id, post_id, lasted) VALUES (?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$user_id, $id, 1]);

    }

    public function updateProfil($photo, $id){

        $conn = $this -> getConnection();

        $sql = "UPDATE user SET profil = ? where id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$photo, $id]);

    }

    public function updateCouverture($photo, $id){

        $conn = $this -> getConnection();

        $sql = "UPDATE user SET couverture = ? where id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$photo, $id]);

    }

    public function addJournal($user_id, $content){
        
        $conn = $this -> getConnection();

        $sql = "INSERT INTO admin_journal (user_id, content) VALUES (?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$user_id, $content]);

    }
    
    public function getJournal($tab){
        
        $conn = $this -> getConnection();

        $stm = $conn->prepare("SELECT * FROM $tab order by id DESC");

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }

    public function reagir($table, $id, $user_id){
        
        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table (activity_id, user_id) VALUES (?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$id, $user_id]);

    }

    public function commenter($table, $id, $user_id, $content){
        
        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table (activity_id, user_id, content) VALUES (?,?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$id, $user_id, $content]);

    }

    public function partger($table, $id, $user_id){
        
        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table (activity_id, user_id) VALUES (?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$id, $user_id]);

    }

    public function getCommentaire($tab, $id){
        
        $conn = $this -> getConnection();

        $stm = $conn->prepare("SELECT * FROM $tab where activity_id = $id order by id DESC");

        $stm->execute();

        $result = $stm->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }


}