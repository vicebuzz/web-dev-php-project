<?php

require_once  "../crud/activityCrud.php";

$activityID = $_POST['activityID'];

deleteActivity($activityID);

function deleteActivity($activityID){
    $activityCrud = new ActivityCRUD();

    $jsonParam = json_encode(array("activityID"=>$activityID));
    $activityCrud->deleteActivity($jsonParam);
    echo '<script>alert("Activity successfully deleted!"); window.location.href = document.referrer;</script>';
    exit();
}
?>