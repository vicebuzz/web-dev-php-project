<?php
require_once './resources/database.php';
require_once './resources/crud/userCrud.php';
require_once './resources/crud/activityCrud.php';


class BookingCRUD {

    private $db;
    private $userCRUD;
    private $activityCRUD;

    function __construct() {

        $db_connect_booking = new DBConnect();
        $db_connect_booking->loadData();
        $this->db = $db_connect_booking->connect();

        $this->userCRUD = new UserCRUD();
        $this->activityCRUD = new ActivityCRUD();
    }

    function retriverAllBookings(){

        // query to select all activities
        $sql = 'SELECT * FROM Booking';

        // fetch result and create an empty array
        $result = $this->db->query($sql);
        $bookings = array();

        // fill the return array up
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        // return filled up array
        return $bookings;

    }

    function retrieveBookings($jsonParameters){

    }
    
    function retrieveUserBookings($jsonParameters){
        
        // decode json string to get user parameters and bookings
        $Parameters = json_decode($jsonParameters, true);
        //the username
        if (!$Parameters){
            return "No parameters provided";
        }

        $username = hash('sha1',$Parameters["user_username"]);

        $query = "SELECT Activity.activity_name, Activity.activity_description, Activity.activity_date
        FROM Activity
        INNER JOIN Activity_Booking ON Activity.id = Activity_Booking.activity_id
        INNER JOIN Booking ON Activity_Booking.booking_id = Booking.id
        INNER JOIN User_Booking ON Booking.id = User_Booking.booking_id
        INNER JOIN User ON User_Booking.user_id = User.id
        WHERE User.username = '$username'";

        $result = $this->db->query($query);

        if ($result){
            $bookings = array();
            while($row = $result->fetch_assoc()){
                $bookings[] = $row;
            }
            echo"query run";
        }


        return $bookings;
    }

    function retrieveBookingsForAnActivity($jsonParameters){

    }

    function createNewBooking($jsonParameters){

        // decode json parameters
        $jsonParameters = json_decode($jsonParameters, true);

        // declare local variables
        $localUserID = 0;
        $localActivityID = 0;
        $localBookingID = 0;
        $localActivityDate = '';
        $localPlacesAvailable = '';

        // get attributes parts
        $userParameters = $jsonParameters["userParameters"];
        $activityParameters = $jsonParameters["activityParameters"];

        // get user id if not provided
        if (isset($userParameters["id"])){
            $localUserID = $userParameters["id"];
        } else{
            $localUserID = $this->userCRUD->getUser(json_encode($userParameters))[0]["id"];
        }

        // get activity id if not provided
        if (isset($activityParameters["id"])){
            $localActivityID = $activityParameters["id"];
            $activity = $this->activityCRUD->getActivities(json_encode($activityParameters));
            $localActivityDate = $activity[0]["activity_date"];
        } else{
            $activity = $this->activityCRUD->getActivities(json_encode($activityParameters));
            $localActivityID = $activity[0]["id"];
            $localActivityDate = $activity[0]["activity_date"];
        }

        echo $localActivityID;

        // create a new booking record
        $query = "INSERT INTO Booking (
            date_booked, 
            number_of_places_booked
            ) 
            VALUES (
                '$localActivityDate',
                1
            )";
        
        $result = $this->db->query($query);

        $localBookingID = $this->db->insert_id;

        if (!$result){
            return "Error creating creating new booking: " . $this->db->error;
        }

        // create a link table record for activity-booking
        $query = "INSERT INTO Activity_Booking (
            activity_id, 
            booking_id
            ) 
            VALUES (
                '$localActivityID',
                '$localBookingID'
            )";
        
        $this->db->query($query);

        // create a link table record for user-booking
        $query = "INSERT INTO User_Booking (
            user_id, 
            booking_id
            ) 
            VALUES (
                '$localUserID',
                '$localBookingID'
            )";
        
        $this->db->query($query);

        // decrement placesAvalaible attribute of an activity

    }


    function deleteBookings($jsonParameters /*booking_id*/){

         //decode json string provided
         $parameters = json_decode($jsonParameters, true);

         // if empty parameters, return 
         if (!$parameters) {
             return "No parameters provided.";
         }
 
         $query = "DELETE FROM Booking WHERE id=";
         $query .= $parameters["id"];
             
 
         // Execute the query
         $result = $this->db->query($query);
 
         if ($result) {
             return "Record(s) deleted successfully.";
         } else {
             return "Error deleting record(s): " . $this->db->error;
         }
 
     }
}

$bookingCRUD = new BookingCRUD();
//$bookingCRUD->createNewBooking('{"userParameters":{"id":7},"activityParameters":{"id":2}}');
print_r($bookingCRUD->retrieveUserBookings('{"user_username": "vikstar"}'));
?>