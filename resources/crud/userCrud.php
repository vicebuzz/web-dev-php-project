<?php
require './resources/database.php';

class UserCRUD {

    private $db;

    function __construct() {

        $db_connect = new DBConnect();
        $db_connect->loadData();
        $this->db = $db_connect->connect();
    }

    function getUsers() {
        $sql = 'SELECT * FROM User';
        $result = $this->db->query($sql);
        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
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
        if (isset($parameters['id'])){
            $conditions[] = "id = " . $parameters['id'];
        }
        if (isset($parameters["username"])){
            $conditions[] = "username = '" . hash('sha1', $parameters["username"] . "'");
        }
        if (isset($parameters['email'])) {
            $conditions[] = "email = '" . hash('sha1', $parameters['email'] . "'");
        }
        if (isset($parameters['user_password'])) {
            $conditions[] = "user_password = '" . hash('sha1', $parameters['user_password'] . "'");
        }
        if (isset($parameters['registration_date'])) {
            $conditions[] = "registration_date = '" . $parameters['registration_date'] . "'";
        }
        if (isset($parameters['subscription_type'])) {
            $conditions[] = "subscription_type = '" . $parameters['subscription_type'] . "'";
        }
        if (isset($parameters['preferred_categories'])) {
            $conditions[] = "preferred_categories = '" . $parameters['preferred_categories'] . "'";
        }

        // Combine conditions with AND
        $query .= " " . implode(" AND ", $conditions);

        // Execute the query
        $result = $this->db->query($query);

        if ($result) {
            // Fetch the user record
            $userRecord = $result->fetch_assoc();
            return $userRecord;
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

        // create and execute a query
        $sql = "INSERT INTO User (
            username, 
            email, 
            user_password, 
            registration_date, 
            subscription_type, 
            preferred_categories) 
            VALUES (
                '$username_hash', 
                '$email_hash', 
                '$user_password_hash', 
                '$registration_date', 
                '$subscription_type', 
                '$preferred_categories')";

        #echo $sql;
        $this->db->query($sql);

    }

    function deleteUser($jsonParameters){

        $parameters = json_decode($jsonParameters, true);

        if (empty($parameters)) {
            return "No parameters provided.";
        }

        $conditions = [];

        if (isset($parameters['id'])){
            $conditions[] = "id = " . $parameters['id'];
        }
        if (isset($parameters["username"])){
            $conditions[] = "username = '" . hash('sha1', $parameters["username"] . "'");
        }
        if (isset($parameters['email'])) {
            $conditions[] = "email = '" . hash('sha1', $parameters['email'] . "'");
        }
        if (isset($parameters['user_password'])) {
            $conditions[] = "user_password = '" . hash('sha1', $parameters['user_password'] . "'");
        }
        if (isset($parameters['registration_date'])) {
            $conditions[] = "registration_date = '" . $parameters['registration_date'] . "'";
        }
        if (isset($parameters['subscription_type'])) {
            $conditions[] = "subscription_type = '" . $parameters['subscription_type'] . "'";
        }
        if (isset($parameters['preferred_categories'])) {
            $conditions[] = "preferred_categories = '" . $parameters['preferred_categories'] . "'";
        }

        $query = "DELETE FROM users WHERE";

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


}

$userCRUD = new UserCRUD();
#$userCRUD->createUser("vikstar", "test@yahoo.com", "test1234", "2023-11-28 12:42:38", "pro", "same");
print_r ($userCRUD->getUsers());
#var_dump($userCRUD->getUser('1'))

#var_dump($userCRUD->getUser('{"username":"vikstar"}'))
?>