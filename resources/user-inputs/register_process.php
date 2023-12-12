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
        $message = "Username already in use";
        $success = false;
        header("location: ../../public/register.php?message=".urlencode("$message")."&success=" .($success ? 'true' : 'false'));
        exit();
    }
    $jsonContentCheck = json_encode(array("email"=>$email));
    $validationResult = $userCRUD->getUser($jsonContentCheck);
    if($validationResult[0]["email"] == hash('sha1',$email)){

        $message = "Email already in use";
        $success = false;
        header("location: ../../public/register.php?message=".urlencode("$message")."&success=" .($success ? 'true' : 'false'));
        exit();
    }
    $jsonContentCheck = json_encode(array("phoneNumber"=>$phoneNum));
    $validationResult = $userCRUD->getUser($jsonContentCheck);
    if($validationResult[0]["phoneNumber"] == hash('sha1',$phoneNum)){

        $message = "Phone number already in use";
        $success = false;
        header("location: ../../public/register.php?message=".urlencode("$message")."&success=" .($success ? 'true' : 'false'));
        exit();
    }



   else{
       $result = $userCRUD->createUser($jsonString);
        session_start();
        $_SESSION['user_id'] = $result["user_id"];
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['phoneNum'] = $phoneNum;
       $message = "Registered successfully";
       $success = true;
       header("location: ../../public/desk.php?message=".urlencode("$message")."&success=" .($success ? 'true' : 'false'));
        exit();
    }
    

}

$username =$_POST["username"];
$password=$_POST["password"];
$phoneNum=$_POST["phone"];
$email=$_POST["email"];

registerUser($username,$password,$phoneNum,$email);
?>