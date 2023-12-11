<?php
require_once "../crud/userCrud.php";

function loginUser($username,$password){
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
        header("location: ../../public/desk.php");
        exit();
    }
    else{
        header("location: ../../public/login.php");
        //echo '<script>alert("Invalid password. Please try again")</script>';
        exit();
    }
}

$username =$_POST["username"];
$password=$_POST["password"];


loginUser($username,$password);


?>