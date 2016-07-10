<?php
/**
 * A class file to connect to database
 */
class DB_CONNECT {
    protected $con;
    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }
    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }
    /**
     * Function to connect with database
     */
    function connect() {
        // Connecting to mysql database
        try
        {
            $ini_array = parse_ini_file(".env");
            $var=require_once("config.php");
            $user=$ini_array['USER'];
            $password=$ini_array['PASSWORD'];
            $host=$ini_array['HOST'];
            $dbname=$ini_array['DB'];
            $string='mysql:host='.$host.';dbname='.$dbname.';port=3306;charset=utf8';
            $con = new PDO('mysql:host='.$host.';dbname='.$dbname, $user,$password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $con->exec('SET NAMES "utf8"');
        }
        catch (PDOException $e)
        {
           print_r(json_encode(array('message' => $e->getMessage(), 'success' => false)));
            exit();
        };
        return $con;
    }
    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
        $con=null;
    }
}

?>
