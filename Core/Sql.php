<?php

// Handles Sql queries
class Sql {

    private static $conn;

    // Super private information
    private const CON_DETAILS = array(
        'host' => '127.0.0.1',
        'user' => 'warehouse_user',
        'password' => '1234',
        'database' => 'warehouse'
    );

    // Connect to Databse server
    private static function connect(){
        self::$conn = new mysqli(
            self::CON_DETAILS['host'],
            self::CON_DETAILS['user'],
            self::CON_DETAILS['password'],
            self::CON_DETAILS['database']
        );

        if (self::$conn->connect_error) {
            Response::setResponse("Connection failed: " . self::$conn->connect_error);
        };
    }

    // Run query and return results
    public static function execute($query) {
        self::connect();
        $result = mysqli_query(self::$conn, $query);
        if($result === false){
            self::$conn->close(); 
            return $result;
        }
        if($result->num_rows === 1){
            $val = $result->fetch_assoc();
            self::$conn->close(); 
            return $val;
        }        
        $resultArray = array();
        while($val = $result->fetch_assoc()){
            array_push($resultArray, $val);
        }
        self::$conn->close(); 
        return $resultArray;
    }

    // Run query and return results
    public static function update($query) {
        self::connect();
        $result = mysqli_query(self::$conn, $query);
        // Update statements return either true or 
        return $result;
    }

    public static function sanitise($field){
        self::connect();
        $field = mysqli_real_escape_string(self::$conn, $field);
        self::$conn->close();
        return $field;
    }
}