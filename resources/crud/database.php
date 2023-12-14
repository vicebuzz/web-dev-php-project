<?php
// TODO: fill db with data example
class DBConnect
{
    public $db;
    private $host;
    private $port;
    private $user;
    private $password;
    private $database;

    function loadData()
    {
        $jsonContent = '{
            "db_info":{
                "host": "db.bucomputing.uk",
                "port": "3306",
                "user": "s5507846",
                "password": "hV3Treohk3hjCJhm7iNYfc7A4dju3huv",
                "dbname": "s5507846"
            }
        }';

        $data = json_decode($jsonContent, true)['db_info'];

        $this->host = $data['host'];
        $this->port = intval($data['port']);
        $this->user = $data['user'];
        $this->password = $data['password'];
        $this->database = $data['dbname'];
    }

    function connect()
    {
        $mysqli = new MySQLi();
        //$mysqli->init();
        if (!$mysqli) {
            echo "<p>Initalising MySQLi failed</p>";
        } else {
            $mysqli->ssl_set(null, null, null, "public_html/sys_tests", null);

            $mysqli->real_connect($this->host, $this->user, $this->password, $this->database, $this->port, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);

            if ($mysqli->connect_errno) { // If connection error
                // Display error message and stop the script. We can't do any database work as there is no connection to use
                echo "<p>Failed to connect to MySQL. " .
                    "Error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error . "</p>";
            } else {
                //echo "<p>Connected to server: " . $mysqli->host_info . "</p>";
            }
        }

        // Check for connection errors
        if ($mysqli->connect_error) {
            echo "Connection failed";
            die("Connection failed: " . $mysqli->connect_error);
        }
        //echo "<br>connection successful<br>";

        $this->db = $mysqli;

        return $mysqli;
    }


}





?>