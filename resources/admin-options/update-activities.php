<?php

require_once  "../crud/activityCrud.php";


$activityID = $_POST['activityID'];


updateActivities($activityID);


function updateActivities($activityID){
    $activityCrud = new ActivityCRUD();
    $activityName = $_POST['edit_activity_name'];
    $activityDate = $_POST['edit_activity_date'];
    $activityDesc = $_POST['edit_activity_description'];
    $activityPrice = $_POST['edit_activity_price'];
    $activityRoom = $_POST['edit_activity_room'];
    $activityImage = $_POST['edit_activity_image'];

    $paramArray = array(
        array("activityID"=>$activityID),
        array("activityName"=>$activityName, "activityDescription"=>$activityDesc,"activityDate"=>$activityDate,
               "price"=>$activityPrice, "room"=>$activityRoom, "image"=>$activityImage)
    );
    $jsonArray=json_encode($paramArray);
    $activityCrud->updateActivity($jsonArray);
    echo '<script>alert("Activity successfully updated!"); window.location.href = document.referrer;</script>';
    exit();
}

?>