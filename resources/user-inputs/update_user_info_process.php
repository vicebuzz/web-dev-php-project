<?php
require_once "../crud/userCrud.php";
function updateUserInfo($originalEmail,$originalPassword,$newEmail,$newPassword,$confirmNewPassword)
{
}
    $userCRUD = new UserCRUD();


$originalEmail =$_POST["original-email"];
$originalPassword=$_POST["original-password"];
$newEmail = $_POST["new-email"];
$newPassword = $_POST["new-password"];
$confirmNewPassword = $_POST["confirm-password"];


?>