<?php
require_once 'database.php';
require_once 'userCrud.php';
require_once 'activityCrud.php';


class BookingCRUD {

    private $db;
    private $userCRUD;
    private $activityCRUD;

    function __construct() {
        $this->userCRUD = new UserCRUD();
        $this->activityCRUD = new ActivityCRUD();
    }

    function connectLocal(){
        $db_connect_booking = new DBConnect();
        $db_connect_booking->loadData();
        $this->db = $db_connect_booking->connect();
    }

    function retriverAllBookings(){

        // connect to database
        $this->connectLocal();

        // query to select all activities
        $sql = 'SELECT * FROM Booking';

        // fetch result and create an empty array
        $result = $this->db->query($sql);
        $bookings = array();

        // fill the return array up
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        //disconnect from database
        $this->db->close();

        // return filled up array
        return $bookings;

    }

    function retrieveUserBookings($jsonParameters){

        // connect to database
        $this->connectLocal();
        
        // decode json string to get user parameters and bookings
        $Parameters = json_decode($jsonParameters, true);
        //the username
        if (!$Parameters){
            return "No parameters provided";
        }
        $userID = $Parameters["userID"];
        $query = "SELECT Activity.activityName, Activity.activityDescription, Activity.activityDate,Activity.image,Activity.room,Activity.price,Activity.activityID, Booking.bookingID
        FROM Activity
        INNER JOIN BookingToActivivy ON Activity.activityID = BookingToActivivy.activityID
        INNER JOIN Booking ON BookingToActivivy.bookingID = Booking.bookingID
        INNER JOIN UserToBooking ON Booking.bookingID = UserToBooking.bookingID
        INNER JOIN Users ON UserToBooking.userID = Users.userID
        WHERE Users.userID = $userID";

        $result = $this->db->query($query);

        if ($result){
            $bookings = array();
            while($row = $result->fetch_assoc()){
                $bookings[] = $row;
            }
        }

        //disconnect from database
        $this->db->close();

        return $bookings;
    }

    function createNewBooking($jsonParameters){

        // connect to database
        $this->connectLocal();

        // decode json parameters
        $jsonParameters = json_decode($jsonParameters, true);

        // declare local variables
        $localUserID = 0;
        $localActivityID = 0;
        $localBookingID = 0;
        $localActivityDate = '';
        $localPlacesAvailable = '';

        // get attributes parts
        $userParameters = $jsonParameters[0];
        $activityParameters = $jsonParameters[1];

        // get user id if not provided
        if (isset($userParameters["userID"])){
            $localUserID = $userParameters["userID"];
        } else{
            $localUserID = $this->userCRUD->getUser(json_encode($userParameters))[0]["userID"];
        }

        // get activity id if not provided
        if (isset($activityParameters["activityID"])){
            $localActivityID = $activityParameters["activityID"];
            $activity = $this->activityCRUD->getActivities(json_encode($activityParameters));
            $localActivityDate = $activity[0]["activityDate"];
        } else{
            $activity = $this->activityCRUD->getActivities(json_encode($activityParameters));
            $localActivityID = $activity[0]["activityID"];
            $localActivityDate = $activity[0]["activityDate"];
        }


        $validationQuery = 'SELECT Users.userID, Booking.bookingID, Activity.activityID
                            FROM Users
                            INNER JOIN UserToBooking ON Users.userID = UserToBooking.userID
                            INNER JOIN Booking ON UserToBooking.bookingID = Booking.bookingID
                            INNER JOIN BookingToActivivy ON Booking.bookingID = BookingToActivivy.bookingID
                            INNER JOIN Activity ON BookingToActivivy.activityID = Activity.activityID
                            WHERE Users.userID = '. $localUserID.' AND Activity.activityID = '.$localActivityID;
        $result = $this->db->query($validationQuery);

        if ($result->fetch_assoc()) {
            // booking already exists
            $success = false;
            echo '<script>alert("You are already booked for this activity!"); window.location.href = document.referrer;</script>';
            exit();
        } else{
            // create a new booking record
            $query = "INSERT INTO Booking (bookingCreated) 
            VALUES (
                '$localActivityDate'
            )";
            $strippedQuery = str_replace(array("\r","\n"), '',$query);
            //echo $query;
            $result = $this->db->query($strippedQuery);

            $localBookingID = $this->db->insert_id;

            if (!$result){
                return "Error creating creating new booking: " . $this->db->error;
            }

            // create a link table record for activity-booking
            $query = "INSERT INTO BookingToActivivy (
            activityID, 
            bookingID
            ) 
            VALUES (
                '$localActivityID',
                '$localBookingID'
            )";

            $this->db->query($query);

            // create a link table record for user-booking
            $query = "INSERT INTO UserToBooking (
            userID, 
            bookingID
            ) 
            VALUES (
                '$localUserID',
                '$localBookingID'
            )";

            $this->db->query($query);

            //disconnect from database
            $this->db->close();

            // decrement placesAvailable attribute of an activity
            $currentPlaces = $this->activityCRUD->getActivities(json_encode(array('activityID'=>$localActivityID)))[0]['placesAvailable'];
            $paramArray = array(
                array("activityID"=>$localActivityID),
                array("placesAvailable"=>$currentPlaces-1)
                );
            $this->activityCRUD->updateActivity(json_encode($paramArray));
        }

    }


    function deleteBookings($jsonParameters /*booking_id*/){


        // connect to database
        $this->connectLocal();

        //decode json string provided
        $parameters = json_decode($jsonParameters, true);

         //decode json string provided
         $parameters = json_decode($jsonParameters, true);
         // if empty parameters, return 
         if (!$parameters) {
             return "No parameters provided.";
         }

        // if empty parameters, return 
        if (!$parameters) {
            return "No parameters provided.";
        }

        $deleteUserBookingQuery = "DELETE FROM UserToBooking";
        $deleteUserBookingQuery .= " WHERE bookingID=".$parameters["bookingID"].' AND userID='.$parameters['userID'];

        $deleteActivityBookingQuery = "DELETE FROM BookingToActivivy";
        $deleteActivityBookingQuery .= " WHERE bookingID=".$parameters["bookingID"].' AND activityID='.$parameters['activityID'];

        $query = "DELETE FROM Booking";
        $query .= " WHERE bookingID=".$parameters["bookingID"];
             
 
        // Execute queries
        $this->db->query($deleteUserBookingQuery);
        $this->db->query($deleteActivityBookingQuery);
        $result = $this->db->query($query);

        // increment placesAvailable attribute of an activity
        $currentPlaces = $this->activityCRUD->getActivities(json_encode(array('activityID'=>$parameters['activityID'])))[0]['placesAvailable'];
        echo $currentPlaces;
        $paramArray = array(
            array("activityID"=>$parameters['activityID']),
            array("placesAvailable"=>$currentPlaces+1)
        );
        $this->activityCRUD->updateActivity(json_encode($paramArray));
        //disconnect from database
        $this->db->close();
 
        if ($result) {
            return "Record(s) deleted successfully.";
        } else {
            return "Error deleting record(s): " . $this->db->error;
        }
 
     }
}
?>