<?php
require_once "../crud/activityCrud.php";

function createActivity(){
    $activityName = $_POST['activity-name'];
    $activityDesc = $_POST['activity-description'];
    $activityDate = $_POST['activity-date'];
    $activityPrice = $_POST['activity-price'];
    $activityRoom = $_POST['activity-room'];
    $activityImage = $_POST['image-preview'];



}
createActivity();

?>