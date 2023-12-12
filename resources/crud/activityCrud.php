<?php
require_once 'database.php';

class ActivityCRUD {

    private $db;

    function __construct() {

        $db_connect_activity = new DBConnect();
        $db_connect_activity->loadData();
        $this->db = $db_connect_activity->connect();
    }

    function getAllActivities(){

        // query to select all activities
        $sql = 'SELECT * FROM Activity';

        // fetch result and create an empty array
        $result = $this->db->query($sql);
        $activities = array();

        // fill the return array up
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }

        // return filled up array
        return $activities;
    }

    function getActivities($jsonParameters){

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
            return $activities;
        } else {
            return null;
        }

    }

    public function searchActivities($searchQuery){
        
        // create query
        $query = "SELECT * FROM Activity WHERE activity_name LIKE %$searchQuery% OR activity_description LIKE %$searchQuery%";

        $result = $this->db->query($query);

        if ($result) {
            // Fetch the activity record
            $activities = array();
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
            return $activities;
        } else {
            return null;
        }

    }

    public function getActivitiesByPeriod($jsonParameters) {

        // Decode the JSON string to an associative array
        $parameters = json_decode($jsonParameters, true);

        // Check if startDate and endDate are provided, set default values if not
        $startDate = isset($parameters['startDate']) ? $parameters['startDate'] : $this->getMinActivityDate();
        $endDate = isset($parameters['endDate']) ? $parameters['endDate'] : $this->getMaxActivityDate();

        // Build the query
        $query = "SELECT * FROM Activity WHERE activityDate BETWEEN '$startDate' AND '$endDate'";

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the user record
            $activities = array();
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
            return $activities;
        } else {
            return null;
        }
    }

    public function getMinActivityDate() {

        // Query to get the minimal activity date
        $query = "SELECT MIN(activityDate) AS min_date FROM Activity";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['min_date'];
        } else {
            return "Error getting minimal activity date: " . $this->db->error;
        }
    }

    public function getMaxActivityDate() {

        // Query to get the maximum activity date
        $query = "SELECT MAX(activityDate) AS max_date FROM Activity";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['max_date'];
        } else {
            return "Error getting maximum activity date: " . $this->db->error;
        }
    }

    public function updateActivity($jsonParameters){

        //decode json string provided
        $parameters = json_decode($jsonParameters, true);

        // if parameters are empty return error statement
        if (empty($parameters)) {
            return "Invalid JSON format. Please provide both 'criteria' and 'updateData'.";
        }

        // split json into select parameters and update parameters
        $select_parameters = $parameters["selectParameters"];
        $update_parameters = $parameters["updateParameters"];

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

        // error checking
        if ($result) {
            return "Activity record(s) updated successfully.";
        } else {
            return "Error updating activities record(s): " . $this->db->error;
        }



    }

    public function createActivity($jsonParameters){

        // decode json strings to get parameters
        $parameters = json_decode($jsonParameters, true);

        // get parateters from the string
        $activity_name = $parameters["activity_name"];
        $activity_description = $parameters["activity_description"];
        $places_available = $parameters["places_available"];
        $activity_date = $parameters["activity_date"];

        $query = "INSERT INTO Activity (
            activityName, 
            activityDescription, 
            placesAvailable, 
            activityDate
            ) 
            VALUES (
                '$activity_name', 
                '$activity_description', 
                '$places_available', 
                '$activity_date')";
        
        
        $result = $this->db->query($query);

        if ($result){
            return 1;
        } else{
            return "Error creating creating new activity: " . $this->db->error;
        }
    }

    public function deleteActivity($jsonParameters){

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

        if ($result) {
            return "Record(s) deleted successfully.";
        } else {
            return "Error deleting record(s): " . $this->db->error;
        }

    }

    
}
#$activityCRUD->updateActivity('{"selectParameters":{"activity_name":"Tennis"},"updateParameters":{"activity_name":"Swimming"}}');
#$activityCRUD->deleteActivity('{"activity_name":"Swimming"}');
#print_r($activityCRUD->getAllActivities())
#echo $activityCRUD->getAllActivities()[4]["id"]
#$activityCRUD->createActivity('{"activity_name":"Tennis","activity_description":"test desc","places_available":30,"activity_date":"2023-12-11 12:00:00"}');
#print_r($activityCRUD->getActivitiesByPeriod('{"startDate":"2023-12-01 12:00:00"}'))

?>