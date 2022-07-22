<?php
include "../classes/Database.class.php";
include "../classes/Page.class.php";

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $api_key_value = "tPmAT5Ab3j7F9";
    if(isset($_POST['api_key'])){
        $api_key = $_POST['api_key'];
        if($api_key_value === $api_key){
            $data = [
                ':name' => $_POST['name'], 
                ':location' => $_POST['loc'], 
                ':light' => $_POST['light'], 
                ':smoke1' => $_POST['smoke1'],
                ':smoke2' => $_POST['smoke2'],
                ':temperature' => $_POST['temp']
            ];
            try{
                $pdo = Database::connection();
                $sql = 'INSERT INTO prtch_sensordata (name, location, light, smoke1, smoke2, temperature, created_at) VALUES(:name, :location, :light, :smoke1, :smoke2, :temperature, NOW())';
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
}