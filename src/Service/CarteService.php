<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class CarteService extends PDOService{

    public function getNotWait($table_carte){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_carte where status = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function getAll($table_carte){
        
        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_carte order by id DESC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function addOneCard($table_carte, $product, $user_id, $quantity){

        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table_carte (product_id, user_id, quantity) VALUES (?,?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$product, $user_id, $quantity]);

    }

    public function updateCard($table_card, $quantity, $id){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_card SET quantity = ? where id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$quantity, $id]);

    }

    public function termineCard($table_card, $status, $id){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_card SET status = ? where id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$status, $id]);

    }
    public function getById($pub_id, $user_id){
        
        $conn = $this -> getConnection();

        $table_pub = "tb_activity_".$user_id;

        $statement = $conn->prepare("SELECT * FROM $table_pub where id=$pub_id and user_id=$user_id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function removeToCarte($table_card, $id){

        $conn = $this -> getConnection();
        $sql = "DELETE FROM $table_card WHERE id=?";
        $stmt= $conn->prepare($sql);
        $stmt->execute([$id]);
    }

}