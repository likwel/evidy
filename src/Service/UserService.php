<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class UserService extends PDOService{

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