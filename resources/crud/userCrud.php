<?php
require_once 'database.php';

class UserCRUD {

    private $db;

    function __construct() {

        $db_connect_users = new DBConnect();
        $db_connect_users->loadData();
        $this->db = $db_connect_users->connect();

    }

    function connectLocal(){

        $db_connect_booking = new DBConnect();
        $db_connect_booking->loadData();
        $this->db = $db_connect_booking->connect();

    }

    function getUsers() {
        // connect to database
        $this->connectLocal();
        // query to select all users records
        $sql = 'SELECT * FROM Users';
        // fetch result and create an empty array
        $result = $this->db->query($sql);
        $users = array();
        // fill the return array up
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        //disconnect from database
        $this->db->close();
        // return filled up array
        return $users;
    }

    function getUser($jsonParameters) {

        // connect to database
        $this->connectLocal();
        //decode json string provided
        $parameters = json_decode($jsonParameters, true);
        print_r($parameters);
        // set up query template
        $query = "SELECT * FROM Users";
        
        // initialise conditions array
        $conditions = [];

        // go through json provided and see what conditions are supplied
        foreach ($parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "userPassword" || $key =="phoneNumber") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        // Combine conditions with AND
        $query .= " WHERE " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the user record
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            //disconnect from database
            $this->db->close();
            return $users;
        } else {
            return null;
        }
    }

    function createUser($jsonParameters){

        // connect to database
        $this->connectLocal();

        $parameters = json_decode($jsonParameters, true);

        // hash username, email and password
        $username_hash = hash('sha1', $parameters["username"]);
        $email_hash = hash('sha1', $parameters["email"]);
        $user_password_hash = hash('sha1', $parameters["user_password"]);
        $phoneNumber_hash = hash('sha1',$parameters["phoneNumber"]);

        // get the rest of parameters
        $registration_date = $parameters["registration_date"];
       // $subscription_type = $parameters["subscription_type"];
       // $preferred_categories = $parameters["preferred_categories"];
        $isAdmin = $parameters["isAdmin"];
        print_r($parameters);
        // create and execute a query
        $sql = "INSERT INTO Users (
            username, 
            email, 
            userPassword, 
            registrationDate, 
            phoneNumber,
            isAdmin) 
            VALUES (
                '$username_hash', 
                '$email_hash', 
                '$user_password_hash', 
                '$registration_date',
                '$phoneNumber_hash',
                0
                )";

        #echo $sql;
        $result = $this->db->query($sql);

        if ($result) {
            $lastInsertedId = $this->db->insert_id;
            //disconnect from database
            $this->db->close();
            return array("userID"=>$lastInsertedId);
        } else {
            //disconnect from database
            $this->db->close();
            return "Error registering a new user " . $this->db->error;
        }

    }

    function updateUser($jsonParameters){

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

        echo  "<br>  oeiwhgoiwheoighwoiehgoiwh";
        echo  "<br>  $select_parameters";
        echo  "<br>  $update_parameters";
        // if those parameters are empty return error statement
        if (empty($select_parameters) || empty($update_parameters)) {
            return "Invalid JSON format. Please provide both 'criteria' and 'updateData'.";
        }

        // Build the base query
        $query = "UPDATE Users";

        // Process parameters to update
        $updates = [];

        foreach ($update_parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "userPassword"|| $key =="phoneNumber") {
                $hash_value = hash("sha1", $value);
                $updates[] = "$key = '$hash_value'";
            }
            elseif ($key=="userID"){
                $updates[] = "$key = $value";
            }
            else {
                $updates[] = "$key = '$value'";
            }
        }

        $query .= " SET " . implode(", ", $updates);

        // Process criteria to identify which users to update
        $conditions = [];

        foreach ($select_parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "userPassword"|| $key =="phoneNumber") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            }
            elseif ($key=="userID"){
                $conditions[] = "$key = $value";
            }
            else {
                $conditions[] = "$key = '$value'";
            }

        }

        // put anupdate query together
        $query .= " WHERE " . implode(" AND ", $conditions);
        echo $query;
        // Execute the query
        $result = $this->db->query($query);

        //disconnect from database
        $this->db->close();

        // error checking
        if ($result) {
            return "User record(s) updated successfully.";
        } else {
            return "Error updating user record(s): " . $this->db->error;
        }



    }

    function deleteUser($jsonParameters){

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
            if ($key == "username" || $key == "email" || $key == "userPassword"|| $key =="phoneNumber") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        $query = "DELETE FROM Users";

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

    function deleteAll(){

        // connect to database
        $this->connectLocal();
        $result = $this->db->query("DELETE FROM Users");
        //disconnect from database
        $this->db->close();

        if ($result) {
            return "Records deleted successfully.";
        } else {
            return "Error deleting record(s): " . $this->db->error;
        }
    }

    function isUserAnAdmin ($jsonParameters){

        // connect to database
        $this->connectLocal();

        $parameters = json_decode($jsonParameters, true);

        if (empty($parameters)) {
            return "No parameters provided";
        }

        $query = "SELECT isAdmin FROM Users";

        $conditions = [];

        foreach ($parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "userPassword"|| $key =="phoneNumber") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        $query .= " WHERE " . implode(" AND ", $conditions);

        $result = $this->db->query($query);

        if ($result) {
            $isadmin = $result->fetch_assoc()["admin"];
            //disconnect from database
            $this->db->close();
            if ($isadmin) {
                return true;
            } else {
                return false;
            }
        } else {
            //disconnect from database
            $this->db->close();
            return null;
        }
    }


    function getUsersPeriodRegistrationDate($jsonParameters){

        // connect to database
        $this->connectLocal();

        // decode json string
        $parameters = json_decode($jsonParameters, true);

        // Check if startDate and endDate are provided, set default values if not
        $startDate = isset($parameters['startDate']) ? $parameters['startDate'] : $this->getMinRegistrationDate();
        $endDate = isset($parameters['endDate']) ? $parameters['endDate'] : $this->getMaxRegistrationDate();

        // Build the query
        $query = "SELECT * FROM Users WHERE registrationDate BETWEEN '$startDate' AND '$endDate'";

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the user record
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            //disconnect from database
            $this->db->close();
            return $users;
        } else {
            //disconnect from database
            $this->db->close();
            return null;
        }
    }

    public function getMinRegistrationDate() {

        // connect to database
        $this->connectLocal();

        // Query to get the minimal subscription date
        $query = "SELECT MIN(registrationDate) AS min_date FROM Users";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            //disconnect from database
            $this->db->close();
            return $row['min_date'];
        } else {
            //disconnect from database
            $this->db->close();
            return "Error getting minimal subscription date: " . $this->db->error;
        }
    }

    public function getMaxRegistrationDate() {

        // connect to database
        $this->connectLocal();

        // Query to get the maximum subscription date
        $query = "SELECT MAX(registrationDate) AS max_date FROM Users";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            //disconnect from database
            $this->db->close();
            return $row['max_date'];
        } else {
            //disconnect from database
            $this->db->close();
            return "Error getting maximum subscription date: " . $this->db->error;
        }
    }
    


}

?>