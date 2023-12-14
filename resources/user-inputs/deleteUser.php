<?php

require_once "../crud/userCrud.php";
session_start();

$userCRUD = new UserCRUD();
$userID = $_SESSION['userID'];
$userCRUD->deleteUser(json_encode(array('userID'=>$userID)));
header("location: ../../public/login.php");

?>