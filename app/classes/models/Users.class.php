<?php

class User extends Database{

    public function getUserLogin($username, $password){
        $pdo = $this->connection();        
        $sql = "SELECT * FROM users WHERE uidUsers=:user OR emailUsers=:email;";
        $stmt = $pdo->prepare(sql);        
        $stmt->execute([
            ':user'=>$username,
            ':email'=>$username,
        ]);
        $result = $stmt->fetchAll();
        
        else {
            mysqli_stmt_bind_param($stmt,"ss", $username, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if  ($row = mysqli_fetch_assoc($result)){
                $pwdCheck = password_verify($password, $row['pwdUsers']);      
                if(!$pwdCheck){
                    // header ("Location: ../index.php?error=wrongpwd");
                    // exit();
                    return ['error' => 'wrong password'];
                }else{    
                    return [
                        'username' => $row['idUsers'],
                        'userid' => $row['idUsers'],
                    ];
                }
            }                          
            else {
                return ['error' => 'wrong credentials'];
            }
        }
    }
}