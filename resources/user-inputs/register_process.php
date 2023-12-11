<?php

require_once "../crud/userCrud.php";



function registerUser($username,$password,$phoneNum,$email){
    $data = array (
      "username"=>$username,
      "user_password"=>$password,
      "phoneNumber"=>$phoneNum,
      "email"=>$email,
      "isAdmin"=>0
    );
    print_r($data);

    //$teststring = '{"username" : "'.$username. '", "user_password" : "'.$password.'", "phoneNumber" : "'.$phoneNum.'", "email" : "'.$email. '", "isAdmin" : 0}';
    //$teststring = '{"username" : "h" "user_password" : "h" "phoneNumber" : "h" "email" : "h" "isAdmin" : 0}';
    //echo "<br>$teststring</br>";

    $jsonString= json_encode($data);

    echo $jsonString;

    $userCRUD = new UserCRUD();
    $result = $userCRUD->createUser($jsonString);

    echo $result;
}

$username =$_POST["username"];
$password=$_POST["password"];
$phoneNum=$_POST["phone"];
$email=$_POST["email"];

print_r($_POST);

echo $username,$password,$phoneNum,$email;
registerUser($username,$password,$phoneNum,$email);
?>