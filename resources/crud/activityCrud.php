<?php
require_once 'database.php';

class ActivityCRUD {

    private $db;

    function __construct() {

        $db_connect_booking = new DBConnect();
        $db_connect_booking->loadData();
        $this->db = $db_connect_booking->connect();

    }

    function connectLocal(){

        $db_connect_booking = new DBConnect();
        $db_connect_booking->loadData();
        $this->db = $db_connect_booking->connect();

    }

    function getAllActivities(){

        // connect to database
        $this->connectLocal();

        // query to select all activities
        $sql = 'SELECT * FROM Activity';

        // fetch result and create an empty array
        $result = $this->db->query($sql);
        $activities = array();

        // fill the return array up
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        //disconnect from database
        $this->db->close();

        // return filled up array
        return $activities;
    }

    function getActivities($jsonParameters){

        // connect to database
        $this->connectLocal();

        // decode parameters passed in json string
        $parameters = json_decode($jsonParameters);

        // set up query template
        $query = "SELECT * FROM Activity";

        // intialise conditions array
        $conditions = [];

        // loop through parameters array and add them to conditions array
        foreach ($parameters as $key => $value) {
            $conditions[] = "$key = '$value'";
        }

        // Combine conditions with AND
        $query .= " WHERE " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the activity record
            $activities = array();
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
            //disconnect from database
            $this->db->close();
            return $activities;
        } else {
            //disconnect from database
            $this->db->close();
            return null;
        }

    }


    public function updateActivity($jsonParameters){

        // connect to database
        $this->connectLocal();

        //decode json string provided
        $parameters = json_decode($jsonParameters, true);

        // if parameters are empty return error statement
        if (empty($parameters)) {
            return "Invalid JSON format. Please provide both 'criteria' and 'updateData'.";
        }

        // split json into select parameters and update parameters
        $select_parameters = $parameters[0];
        $update_parameters = $parameters[1];

        // if those parameters are empty return error statement
        if (empty($select_parameters) || empty($update_parameters)) {
            return "Invalid JSON format. Please provide both 'criteria' and 'updateData'.";
        }

        // Build the base query
        $query = "UPDATE Activity";

        // Process parameters to update
        $updates = [];

        foreach ($update_parameters as $key => $value) {
            $updates[] = "$key = '$value'";
        }

        $query .= " SET " . implode(", ", $updates);

        // Process criteria to identify which users to update
        $conditions = [];

        foreach ($select_parameters as $key => $value) {
            $conditions[] = "$key = '$value'";
        }

        // put anupdate query together
        $query .= " WHERE " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        //disconnect from database
        $this->db->close();

        // error checking
        if ($result) {
            return "Activity record(s) updated successfully.";
        } else {
            return "Error updating activities record(s): " . $this->db->error;
        }



    }

    public function createActivity($jsonParameters){

        // connect to database
        $this->connectLocal();

        // decode json strings to get parameters
        $parameters = json_decode($jsonParameters, true);

        // get parateters from the string
        $activity_name = $parameters["activityName"];
        $activity_description = $parameters["activityDescription"];
        $places_available = $parameters["placesAvailable"];
        $activity_date = $parameters["activityDate"];
        $activityPrice = $parameters["activityPrice"];
        $activityRoom = $parameters["activityRoom"];
        $activityImage = $parameters["activityImage"];


        $query = "INSERT INTO Activity (
            activityName, 
            activityDescription, 
            placesAvailable, 
            activityDate,
            price,
            room,
            image
            ) 
            VALUES (
                '$activity_name', 
                '$activity_description', 
                '$places_available', 
                '$activity_date'
                '$activityPrice',
                '$activityRoom',
                '$activityImage')";
        
        echo $query;
        $result = $this->db->query($query);

        //disconnect from database
        $this->db->close();

        if ($result){
            return 1;
        } else{
            return "Error creating creating new activity: " . $this->db->error;
        }
    }

    public function deleteActivity($jsonParameters){

        // connect to database
        $this->connectLocal();

        //decode json string provided
        $parameters = json_decode($jsonParameters, true);

        // if empty parameters, return 
        if (empty($parameters)) {
            return "No parameters provided.";
        }

        $conditions = [];

        foreach ($parameters as $key => $value) {
            $conditions[] = "$key = '$value'";
        }

        $query = "DELETE FROM Activity";

        // Combine conditions with AND
        $query .= " WHERE " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

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