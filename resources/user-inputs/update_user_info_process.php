<?php
require_once "../crud/userCrud.php";

$updateType = $_POST["update-type"];

function getTypeToChange($valueChangeType)
{
    $newEmail = $_POST["email"];
    $newPassword = $_POST["password"];
    $newUsername = $_POST["username"];
    $newPhoneNum = $_POST["phone"];

    if ($valueChangeType == "update-username" && !empty($newUsername)) {
        updateUserInfo($newUsername, "username");
    } elseif ($valueChangeType == "update-password" && !empty($newPassword)) {
        updateUserInfo($newPassword, "password");
    } elseif ($valueChangeType == "update-email" && !empty($newEmail)) {
        updateUserInfo($newEmail, "email");
    } elseif ($valueChangeType == "update-phone" && !empty($newPhoneNum)) {
        updateUserInfo($newPhoneNum, "phoneNumber");
    } else {
        // Empty field detected, send alert back to the page
        echo '<script>alert("Fields cannot be empty"); window.history.back();</script>';
        exit();
    }
}

function updateUserInfo($newUserInput, $flag)
{
    session_start();
    $userCRUD = new UserCRUD();

    $paramArray = array(
        array("userID" => $_SESSION["userID"]),
        array($flag => $newUserInput)
    );
    $jsonArray = json_encode($paramArray);
    $userCRUD->updateUser($jsonArray);
    header("location: ../../public/login.php");
    exit();
}

getTypeToChange($updateType);
?>
