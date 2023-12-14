<?php
require_once "../crud/activityCrud.php";

function createActivity(){
    $activityCrud=new ActivityCRUD();
    $activityName = $_POST['activity-name'];
    $activityDesc = $_POST['activity-description'];
    $activityDate = $_POST['activity-date'];
    $activityPrice = $_POST['activity-price'];
    $activityRoom = $_POST['activity-room'];
    $activityImage = $_POST['activity-image'];
    echo $activityImage;
    $paramArray= array(
  "activityName" => $activityName,
    "activityDescription" => $activityDesc,
    "placesAvailable"=> 10,
    "activityDate" => $activityDate,
    "activityPrice" => $activityPrice,
    "activityRoom" => $activityRoom,
    "activityImage" => $activityImage
    );
    $jsonparam = json_encode($paramArray);
    $activityCrud ->createActivity($jsonparam);
    echo '<script>alert("New Activity successfully created!"); window.location.href = document.referrer;</script>';
    exit();
}
createActivity();

?>