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

    echo '<script>alert("Booking deleted successfully."); window.location.href = document.referrer;</script>';
    exit();
}

deleteBooking($userID,$activityID,$bookingID);
?>