<?php
require_once 'database.php';
require_once 'userCrud.php';
require_once 'activityCrud.php';


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

        $username = hash('sha1',$Parameters["username"]);

        $query = "SELECT Activity.activityName, Activity.activityDescription, Activity.activityDate
        FROM Activity
        INNER JOIN BookingToActivivy ON Activity.activityID = BookingToActivivy.activityID
        INNER JOIN Booking ON BookingToActivivy.bookingID = Booking.bookingID
        INNER JOIN UserToBooking ON Booking.bookingID = UserToBooking.bookingID
        INNER JOIN Users ON UserToBooking.userID = Users.userID
        WHERE Users.username = '$username'";

        $result = $this->db->query($query);

        if ($result){
            $bookings = array();
            while($row = $result->fetch_assoc()){
                $bookings[] = $row;
            }
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

        //echo "$localActivityID<br>";

        $validationQuery = 'SELECT Users.userID, Booking.bookingID, Activity.activityID
                            FROM Users
                            INNER JOIN UserToBooking ON Users.userID = UserToBooking.userID
                            INNER JOIN Booking ON UserToBooking.bookingID = Booking.bookingID
                            INNER JOIN BookingToActivivy ON Booking.bookingID = BookingToActivivy.bookingID
                            INNER JOIN Activity ON BookingToActivivy.activityID = Activity.activityID
                            WHERE Users.userID = '. $localUserID.' AND Activity.activityID = '.$localActivityID;
        $result = $this->db->query($validationQuery);

        if ($result) {
            // booking already exists
            $success = false;
            header("location: ../../public/desk.php?message=".urlencode("Already booked for this activity")."&success=" .($success ? 'true' : 'false'));
            exit();
        } else{
            // create a new booking record
            $query = "INSERT INTO Booking (bookingCreated) 
            VALUES (
                '$localActivityDate'
            )";
            echo $strippedQuery = str_replace(array("\r","\n"), '',$query);
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

            // decrement placesAvalaible attribute of an activity
        }




    }


    function deleteBookings($jsonParameters /*booking_id*/){

         //decode json string provided
         $parameters = json_decode($jsonParameters, true);

         // if empty parameters, return 
         if (!$parameters) {
             return "No parameters provided.";
         }

         $deleteUserBookingQuery = "DELETE FROM UserToBooking";
         $deleteUserBookingQuery .= " WHERE bookingID=".$parameters["bookingID"].' AND userID='.$parameters['userID'];

        $deleteActivityBookingQuery = "DELETE FROM BookingToActivivy";
        $deleteActivityBookingQuery .= " WHERE bookingID=".$parameters["bookingID"].' AND activityID='.$parameters['activityID'];

         $query = "DELETE FROM Booking";
         $query .= " WHERE id=".$parameters["bookingID"];
             
 
         // Execute queries
        $this->db->query($deleteUserBookingQuery);
        $this->db->query($deleteActivityBookingQuery);
         $result = $this->db->query($query);
 
         if ($result) {
             return "Record(s) deleted successfully.";
         } else {
             return "Error deleting record(s): " . $this->db->error;
         }
 
     }
}

?>