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
// Quality Of Life Function that we haven't used but potentially may need in the future
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
        // set up query template
        $query = "SELECT * FROM Users";
        
        // initialise conditions array
        $conditions = [];

        // go through json provided and see what conditions are supplied
        foreach ($parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "userPassword" || $key =="phoneNumber") {
                $escapedInput = mysqli_real_escape_string($this->db, $value);
                $hash_value = hash("sha1", $escapedInput);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $escapedInput = mysqli_real_escape_string($this->db, $value);
                $conditions[] = "$key = '$escapedInput'";
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
                $escapedInput = mysqli_real_escape_string($this->db, $value);
                $hash_value = hash("sha1", $escapedInput);
                $updates[] = "$key = '$hash_value'";
            }
            elseif ($key=="userID"){
                $updates[] = "$key = $value";
            }
            else {
                $escapedInput = mysqli_real_escape_string($this->db, $value);
                $updates[] = "$key = '$escapedInput'";
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
// Quality Of Life Function that we haven't used but potentially may need in the future
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




}
?>