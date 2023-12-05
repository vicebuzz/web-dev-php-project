<?php
require './resources/database.php';

class ActivityCRUD {

    private $db;

    function __construct() {

        $db_connect = new DBConnect();
        $db_connect->loadData();
        $this->db = $db_connect->connect();
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
        $query = "SELECT * FROM User WHERE";

        // intialise conditions array
        $conditions = [];

        // loop through parameters array and add them to conditions array
        foreach ($parameters as $key => $value) {
            $conditions[] = "$key = '$value'";
        }

        // Combine conditions with AND
        $query .= " " . implode(" AND ", $conditions);

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

    public function getActivitiesByPeriod($jsonParameters) {

        // Decode the JSON string to an associative array
        $parameters = json_decode($jsonParameters, true);

        // Check if startDate and endDate are provided, set default values if not
        $startDate = isset($parameters['startDate']) ? $parameters['startDate'] : $this->getMinActivityDate();
        $endDate = isset($parameters['endDate']) ? $parameters['endDate'] : $this->getMaxActivityDate();

        // Build the query
        $query = "SELECT * FROM Activity WHERE activity_date BETWEEN '$startDate' AND '$endDate'";

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
        $query = "SELECT MIN(activity_date) AS min_date FROM Activity";
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
        $query = "SELECT MAX(activity_date) AS max_date FROM Activity";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['max_date'];
        } else {
            return "Error getting maximum activity date: " . $this->db->error;
        }
    }

    public function createActivity($jsonParameters){

        // decode json string to get records of a new activity
        
    }

    public function updateActivity($jsonParameters){

        // decode json strings to get parameters
        $parameters = json_decode($jsonParameters, true);



    }

    
}
?>