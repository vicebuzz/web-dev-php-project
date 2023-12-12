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
    echo"<br>1";
    $result = $userCRUD->getUser($jsonString);
    echo"<br>2";
    print_r($result);
    if($result[0]["userPassword"] == hash('sha1',$password)){
        echo"<br> success <br>";
        session_start();
        $_SESSION['userID'] = $result[0]['userID'];
        $_SESSION['username'] = $username;
        print_r($_SESSION);
        $success = true;
        header("location: ../../public/desk.php");
        exit();
    }
    else{
        header("location: ../../public/login.php?message=".urlencode("Incorrect Login Details")."&success=" .($success ? 'true' : 'false'));
        //echo '<script>alert("Invalid password. Please try again")</script>';
        exit();
    }
}

$username =$_POST["username"];
$password=$_POST["password"];


loginUser($username,$password);


?>