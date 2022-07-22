<?php
include "../classes/Database.class.php";
include "../classes/Page.class.php";

//if($_SERVER["REQUEST_METHOD"]==="POST"){
    $api_key_value = "tPmAT5Ab3j7F9";
    if(isset($_POST['api_key'])){
        $api_key = $_POST['api_key'];
        if($api_key_value === $api_key){
            $data = [
                ':name' => 'SENSOR1',
                ':location' => 'MIS',
                ':sensor1' => $_POST['smoke1'],
                ':sensor2' => $_POST['smoke2'],
            ];
            try{
                $pdo = Database::connection();
                // $pdo->beginTransaction();
                $sql = 'INSERT INTO gassensor (name, location, sensor1, sensor2, created_at) VALUES(:name, :location, :sensor1, :sensor2, NOW())';
                $stmt = $pdo->prepare($sql);                
                $stmt->execute($data);
            }catch(PDOException $e){
                $pdo->rollback();
                throw $e;
            }catch(Exception $er){
                throw $er;
            }
        }
    }
//}