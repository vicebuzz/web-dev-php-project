<?php
// TODO: fill db with data example
class DBConnect {
	public $db;
	private $host;
	private $port;
	private $user;
	private $password;
	private $database;

	function loadData() {

		$jsonFilePath = '.../config.json';
        /*
		if (file_exists($jsonFilePath)) {

			$jsonContent = file_get_contents($jsonFilePath);
		
			$data = json_decode($jsonContent, true)['db_info'];

			$this->host = $data['host'];
			$this->port = intval($data['port']);
			$this->user = $data['user'];
			$this->password = $data['password'];
			$this->database = $data['dbname'];
		 
		} else {
			echo "JSON file not found.";
		}*/
        $jsonContent = file_get_contents($jsonFilePath);

        $data = json_decode($jsonContent, true)['db_info'];

        $this->host = $data['host'];
        $this->port = intval($data['port']);
        $this->user = $data['user'];
        $this->password = $data['password'];
        $this->database = $data['dbname'];
	}

	function connect() {

		$mysqli = new mysqli(
			$hostname = $this->host, 
			$username = $this->user, 
			$password = $this->password,
			$database = $this->database,
			$port = $this->port
		);

		// Check for connection errors
		if ($mysqli->connect_error) {
			echo "Connection failed";
			die("Connection failed: " . $mysqli->connect_error);
		}

		$this->db = $mysqli;

		return $mysqli;
	}

	// TODO: error handling
	function close_connection() {
		$this->db->close();
	}

}

// $db_connect = new DBConnect();
// $db_connect->loadData();
// $database = $db_connect->connect();

// $query = "SELECT * FROM User";
// $result = $database->query($query);

// if ($result) {

//     if ($result->num_rows > 0) {

//         while ($row = $result->fetch_assoc()) {
//             echo "ID: " . $row['id'] . "<br>";
//             echo "Name: " . $row['username'] . "<br>";
//             echo "Email: " . $row['email'] . "<br>";
//             echo "<br>";
//         }
//     } else {
//         echo "No results found.";
//     }
// } else {
//     echo "Query failed: " . $mysqli->error;
// }

?>