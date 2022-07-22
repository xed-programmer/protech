<?php

class Database {
    private static $host = "localhost";
    private static $dbname = "oqypbtdm_group5";
    private static $user = "oqypbtdm_group5";
    private static $pass = "qizwi7CP,!3,";

    public static function connection()
    {
        try {
            date_default_timezone_set('Asia/Manila');
            $pdo = new PDO('mysql:host='. self::$host .';dbname='.self::$dbname, self::$user, self::$pass);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}