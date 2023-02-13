<?php

namespace App\Service;

use PDO;
use App\Service\PDOService;

class VenteService extends PDOService{

    public function publierVente($table_vente, $product, $description, $devise, $location, $user_id, $price, $quantity, $photos, $isDelivery, $isWait){

        $conn = $this -> getConnection();

        $sql = "INSERT INTO $table_vente (product, description, devise, location, user_id, price, quantity, photos, isDelivery, isWait) VALUES (?,?,?,?,?,?,?,?,?,?)";

        $statement = $conn->prepare($sql);

        $statement->execute([$product, $description, $devise, $location, $user_id, $price, $quantity, $photos, $isDelivery, $isWait]);

    }

    public function getNotRead($table_vente){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as nb FROM $table_vente where isRead = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }
    public function getNotShow($table_vente){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) FROM $table_vente where isShow = 0");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["nb"];

        return $result;

    }

    public function getAll($table_vente){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_vente order by id DESC");

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

}