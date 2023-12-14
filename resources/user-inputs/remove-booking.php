<?php
require_once "../crud/bookingCrud.php";
session_start();
$userID = $_SESSION['userID'];
$bookingID = $_POST['bookingID'];
$activityID = $_POST['activityID'];

function deleteBooking($userID, $activityID, $bookingID){
$bookingCrud = new BookingCRUD();
    $paramArray = array(
        "userID"=>$userID,
        "bookingID"=>$bookingID,
        "activityID"=>$activityID
    );
    $jsonArray = json_encode($paramArray);
$bookingCrud->deleteBookings($jsonArray);
    header("location: ../../public/desk.php");
}

deleteBooking($userID,$activityID,$bookingID);
?>