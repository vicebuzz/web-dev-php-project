<?php
require_once "../crud/bookingCrud.php";
session_start();
$activityID = $_POST['activityID'];
function createBooking($activityID){
    $bookingCRUD = new BookingCRUD();

    $paramArray = array(
        array("userID"=>$_SESSION["userID"]),
        array("activityID"=>$activityID)
    );

    $bookingCRUD->createNewBooking(json_encode($paramArray));
    header("location: ../../public/desk.php#activity-cards-section");
    exit();
}

createBooking($activityID);

?>
