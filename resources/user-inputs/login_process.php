<?php
require_once "../crud/userCrud.php";

function loginUser($username,$password){
    $success = false;
    $data = array (
        "username"=>$username,
        "userPassword"=>$password,
    );

    $jsonString= json_encode($data);
    $userCRUD = new UserCRUD();
    $result = $userCRUD->getUser($jsonString);

    if($result[0]["userPassword"] == hash('sha1',$password)){
        session_start();
        $_SESSION['userID'] = $result[0]['userID'];
        $_SESSION['username'] = $username;
        $success = true;
        if($result[0]["isAdmin"]==0){
            header("location: ../../public/desk.php");
        }
        elseif ($result[0]["isAdmin"]==1) {
            header("location: ../../public/manage.php");
        }

        exit();
    }
    else{
        header("location: ../../public/login.php?message=".urlencode("Incorrect Login Details")."&success=" .($success ? 'true' : 'false'));
        exit();
    }
}

$username =$_POST["username"];
$password=$_POST["password"];


loginUser($username,$password);


?>