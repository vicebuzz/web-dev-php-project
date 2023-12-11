<?php

require_once "../crud/userCrud.php";

function registerUser($username,$password,$phoneNum,$email){
    $data = array (
      "username"=>$username,
      "user_password"=>$password,
      "phoneNumber"=>$phoneNum,
      "email"=>$email,
      "isAdmin"=>0,
      "registration_date"=> "2023-12-11 12:00:00"
    );
    $jsonString= json_encode($data);
    $userCRUD = new UserCRUD();

    $jsonContentCheck = json_encode(array("username"=>$username));
    $validationResult = $userCRUD->getUser($jsonContentCheck);

    if($validationResult[0]["username"] == hash('sha1',$username)){
        echo"<br> Account Already Created for that username<br>";

        header("location: ../../public/register.php");
        exit();
    }
    $jsonContentCheck = json_encode(array("email"=>$email));
    $validationResult = $userCRUD->getUser($jsonContentCheck);
    if($validationResult[0]["email"] == hash('sha1',$email)){
        echo"<br> Account Already Created for that email<br>";

        header("location: ../../public/register.php");
        exit();
    }
    $jsonContentCheck = json_encode(array("phoneNumber"=>$phoneNum));
    $validationResult = $userCRUD->getUser($jsonContentCheck);
    if($validationResult[0]["phoneNumber"] == hash('sha1',$phoneNum)){
        echo"<br> Account Already Created for that phone number<br>";

        header("location: ../../public/register.php");
        exit();
    }


    else{
        $result = $userCRUD->createUser($jsonString);
        if($result =="New user registered."){
            header("location: ../../public/desk.php");
            exit();
        }
    }

}

$username =$_POST["username"];
$password=$_POST["password"];
$phoneNum=$_POST["phone"];
$email=$_POST["email"];

registerUser($username,$password,$phoneNum,$email);
?>