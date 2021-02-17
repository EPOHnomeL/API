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
        // Create connection object
        self::$conn = new mysqli(
            self::CON_DETAILS['host'],
            self::CON_DETAILS['user'],
            self::CON_DETAILS['password'],
            self::CON_DETAILS['database']
        );

        // Check if error occured
        if (self::$conn->connect_error) {
            Response::setResponse("Connection failed: " . self::$conn->connect_error);
        };
    }

    // Run query and return results
    public static function execute($query) {
        // Connect to databse
        self::connect();
        // Execute query and return result
        $result = mysqli_query(self::$conn, $query);
        // Check if response failed
        if($result === false){
            self::$conn->close(); 
            return $result;
        }
        // Check if response only succeeded and did not return anything
        if($result === true) {
            return $result;
        }
        // Check if there is only 1 row
        if($result->num_rows === 1){
            // Return as associative array
            $val = $result->fetch_assoc();
            // Close connection
            self::$conn->close(); 
            return $val;
        }        
        // Build final array of the databse
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

    // Santise for SQL injection
    public static function sanitise($field){
        // Connect to database
        self::connect();
        // Run escape to escape the SQL syntax
        $field = mysqli_real_escape_string(self::$conn, $field);
        self::$conn->close();
        return $field;
    }

    static function getField($username, $field){
        // Execute the query
        $result = Sql::execute(
            "SELECT $field FROM users WHERE Username = '$username'"
        );
        // return the field as a value
        return $result["$field"];
    }

    static function getUserDetails($username){
        // Execute the query
        $result = Sql::execute(
            "SELECT * FROM users WHERE Username = '$username'"
        );
        // return the query as an assosiative array
        return $result;

    }

    static function inDatabase($value, $field, $database){
        // Execute the query
        $result = Sql::execute(
            "SELECT * FROM $database WHERE $field = '$value'"
        );
        // Check if value is false or not
        if($result === false){
            return false;
        } else {
            return true;
        }

    }
    
}