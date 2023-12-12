<?php
require_once "../crud/userCrud.php";

$updateType = $_POST["update-type"];

function getTypeToChange ($valueChangeType){
    $newEmail =$_POST["email"];
    $newPassword=$_POST["password"];
    $newUsername=$_POST["username"];
    $newPhoneNum=$_POST["phone"];

    if ($valueChangeType=="update-username"){
        updateUserInfo($newUsername,"username");
    }
    elseif ($valueChangeType=="update-password"){
        updateUserInfo($newPassword,"password");
    }
    elseif ($valueChangeType=="update-email"){
        updateUserInfo($newEmail, "email");
    }
    elseif ($valueChangeType=="update-phone"){
        updateUserInfo($newPhoneNum, "phoneNumber");
    }
}


function updateUserInfo($newUserInput,$flag)
{
    session_start();
    $userCRUD = new UserCRUD();
    $userID = $_SESSION["userID"];

    $paramArray = array(
      array("userID"=>$_SESSION["userID"]),
      array($flag=>$newUserInput)
    );

    echo $jsonArray = json_encode($paramArray);

    echo $userCRUD->updateUser($jsonArray);



}








getTypeToChange($updateType);

?>