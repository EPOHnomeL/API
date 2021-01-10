<?php

// Handles Sql queries
class Sql {

    private static $conn;

    // Super private information
    private const CON_DETAILS = array(
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'warehouse_user',
        'password' => '1234',
        'database' => 'warehouse'
    );

    // Connect to Databse server
    private static function connect(){
        self::$conn = mysqli_connect(
            self::CON_DETAILS['host'],
            self::CON_DETAILS['user'],
            self::CON_DETAILS['password'],
            self::CON_DETAILS['database']
        );

        if (self::$conn->connect_error) {
            die("Connection failed: " . self::$conn->connect_error);
            };
    }

    // Run query and return results
    public static function execute($query) {
        self::connect();
        $result = self::$conn -> query($query);  
        if(!$result){
            Response::setResponse('Error in SQL Query');
            return;
        }       
        self::$conn -> close();
        return $result;
    }

    public static function insertIntoStatement($val){
        self::connect();

        if(!$stmt = self::$conn->prepare(
            'INSERT INTO users (Name, Password, Email, Email_Confirmed, Role, Token_Expiry, Last_Login)'.
            ' VALUES (?, ?, ?, ?, ?, ?, ?)')){
                Response::setResponse('Database failure: could not prepare');
                return;
            };       

        $stmt->bind_param("sssisis", 
            $val['Name'],
            $val['Password'],
            $val['Email'],
            $val['Email_Confirmed'],
            $val['Role'],
            $val['Token_Expiry'], 
            $val['Last_Login']);
        $stmt->execute();
    }
}