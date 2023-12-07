<?php
require_once './resources/database.php';

class UserCRUD {

    private $db;

    function __construct() {

        $db_connect_users = new DBConnect();
        $db_connect_users->loadData();
        $this->db = $db_connect_users->connect();
    }

    function getUsers() {
        // query to select all users records
        $sql = 'SELECT * FROM User';
        // fetch result and create an empty array
        $result = $this->db->query($sql);
        $users = array();
        // fill the return array up
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }   
        // return filled up array
        return $users;
    }

    function getUser($jsonParameters) {

        //decode json string provided
        $parameters = json_decode($jsonParameters, true);
        
        // set up query template
        $query = "SELECT * FROM User WHERE";
        
        // initialise conditions array
        $conditions = [];

        // go through json provided and see what conditions are supplied
        foreach ($parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "user_password") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        // Combine conditions with AND
        $query .= " " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the user record
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            return null;
        }
    }

    function createUser($jsonParameters){

        $parameters = json_decode($jsonParameters, true);

        // hash username, email and password
        $username_hash = hash('sha1', $parameters["username"]);
        $email_hash = hash('sha1', $parameters["email"]);
        $user_password_hash = hash('sha1', $parameters["user_password"]);

        // get the rest of parameters
        $registration_date = $parameters["registration_date"];
        $subscription_type = $parameters["subscription_type"];
        $preferred_categories = $parameters["preferred_categories"];
        $isadmin = $parameters["admin"];

        // create and execute a query
        $sql = "INSERT INTO User (
            username, 
            email, 
            user_password, 
            registration_date, 
            subscription_type, 
            preferred_categories,
            admin) 
            VALUES (
                '$username_hash', 
                '$email_hash', 
                '$user_password_hash', 
                '$registration_date', 
                '$subscription_type', 
                '$preferred_categories',
                '$isadmin')";

        #echo $sql;
        $this->db->query($sql);

    }

    function updateUser($jsonParameters){

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
        $query = "UPDATE User";

        // Process parameters to update
        $updates = [];

        foreach ($update_parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "user_password") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        $query .= " SET " . implode(", ", $updates);

        // Process criteria to identify which users to update
        $conditions = [];

        foreach ($select_parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "user_password") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        // put anupdate query together
        $query .= " WHERE " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        // error checking
        if ($result) {
            return "User record(s) updated successfully.";
        } else {
            return "Error updating user record(s): " . $this->db->error;
        }



    }

    function deleteUser($jsonParameters){

        //decode json string provided
        $parameters = json_decode($jsonParameters, true);

        // if empty parameters, return 
        if (empty($parameters)) {
            return "No parameters provided.";
        }

        $conditions = [];

        foreach ($parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "user_password") {
                $hash_value = hash("sha1", $value);
                $conditions[] = "$key = '$hash_value'";
            } else {
                $conditions[] = "$key = '$value'";
            }
        }

        $query = "DELETE FROM User WHERE";

        // Combine conditions with AND
        $query .= " " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            return "Record(s) deleted successfully.";
        } else {
            return "Error deleting record(s): " . $this->db->error;
        }

    }

    function deleteAll(){
        $result = $this->db->query("DELETE FROM User");

        if ($result) {
            return "Records deleted successfully.";
        } else {
            return "Error deleting record(s): " . $this->db->error;
        }
    }

    function isUserAnAdmin ($jsonParameters){

        $parameters = json_decode($jsonParameters, true);

        if (empty($parameters)) {
            return "No parameters provided";
        }

        $query = "SELECT admin FROM User";

        $conditions = [];

        foreach ($parameters as $key => $value) {
            if ($key == "username" || $key == "email" || $key == "user_password") {
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
            if ($isadmin) {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    function getUsersPeriodSubscriptionDate($jsonParameters){

        // decode json string
        $parameters = json_decode($jsonParameters, true);

        // Check if startDate and endDate are provided, set default values if not
        $startDate = isset($parameters['startDate']) ? $parameters['startDate'] : $this->getMinSubscriptionDate();
        $endDate = isset($parameters['endDate']) ? $parameters['endDate'] : $this->getMaxSubscriptionDate();

        // Build the query
        $query = "SELECT * FROM User WHERE subscription_date BETWEEN '$startDate' AND '$endDate'";

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the user record
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            return null;
        }
    }

    public function getMinSubscriptionDate() {

        // Query to get the minimal subscription date
        $query = "SELECT MIN(subscription_date) AS min_date FROM User";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['min_date'];
        } else {
            return "Error getting minimal subscription date: " . $this->db->error;
        }
    }

    public function getMaxSubscriptionDate() {

        // Query to get the maximum subscription date
        $query = "SELECT MAX(subscription_date) AS max_date FROM User";
        $result = $this->db->query($query);

        if ($result) {
            $row = $result->fetch_assoc();
            return $row['max_date'];
        } else {
            return "Error getting maximum subscription date: " . $this->db->error;
        }
    }
    


}

#$userCRUD = new UserCRUD();
#print_r ($userCRUD->getUsers());
#var_dump($userCRUD->getUser('1'))

#$userCRUD->createUser('{"username":"vikstar","email":"vikstar@yahoo.com","user_password":"vikstarcool12345","registration_date":"2023-11-12 13:35:38","subscription_type":"Pro","preferred_categories":"same","admin":1}');

#print_r($userCRUD->getUser('{"username":"vikstar", "user_password":"vikstarcool12345"}'))

#$userCRUD->updateUser('{"selectParameters":{"username":"vikstar"},"updateParameters":{"admin":1}}');
#$userCRUD->deleteUser('{"username":"vikstar"}');
#echo $userCRUD->isUserAnAdmin('{"username":"vikstar"}');


#$userCRUD->deleteAll();
?>