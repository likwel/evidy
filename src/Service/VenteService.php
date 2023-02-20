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


    public function getOneByUser($id, $user_id){

        $conn = $this -> getConnection();

        $table_vente = "tb_activity_".$user_id;

        $statement = $conn->prepare("SELECT * FROM $table_vente where id=$id");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getOneBy($table_vente, $id){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT * FROM $table_vente where id=$id order by id DESC");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getAllByListUser($my_id, $user_id_list){

        $conn = $this -> getConnection();

        $tab_activity = array();

        $rqt = "SELECT * from ";

        $final_rqt ="";

        if(count($user_id_list)>0){
            for($i=0 ; $i < count($user_id_list); $i++){
                if($i != count($user_id_list) -1){
                    $rqt .='tb_activity_' . $user_id_list[$i] . ' union SELECT * FROM ';
                }else{
                    $rqt .='tb_activity_' . $user_id_list[$i] .' ';
                }
            }
            $final_rqt = $rqt." union SELECT * from tb_activity_".$my_id." order by datetime DESC";
        }else{
            $final_rqt =$rqt .'tb_activity_'.$my_id." order by datetime DESC";
        }

       
        

        $statement = $conn->prepare($final_rqt);

        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getPostNumber($table_vente){

        $conn = $this -> getConnection();

        $statement = $conn->prepare("SELECT count(*) as NB FROM $table_vente");

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC)["NB"];

        return $result;
    }

    public function setIsVendu($table_vente, $id){

        $conn = $this -> getConnection();

        $sql = "UPDATE $table_vente SET status = 1 where id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$id]);

    }

}