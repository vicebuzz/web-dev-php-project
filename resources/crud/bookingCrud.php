<?php
require './resources/database.php';

class BookingCRUD {

    private $db;

    function __construct() {

        $db_connect = new DBConnect();
        $db_connect->loadData();
        $this->db = $db_connect->connect();
    }

    
}
?>