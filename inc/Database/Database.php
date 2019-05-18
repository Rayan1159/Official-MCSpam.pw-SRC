<?php
namespace mcspam;
require($_SERVER['DOCUMENT_ROOT'] . '/' . 'inc/Database/Credentials.php');
USE PDO;

/**
 * @return boolean
 */
if (!isset($_SESSION)) {
    session_start([
        'cookie_lifetime' => 12000
    ]);
} else {
    return true;
}

class Database {
    private $database, $connection, $mysql;
    private $username, $password, $host;

    /**
     * @return bool
     */

    public function getSession() {
        if (!$_SESSION['username']) {
            return false;
        }
        return true;
    }

    /**
     * Database constructor.
     */

    public function __construct() {
        $this->username = Credentials::$credentials['username'];
        $this->password = Credentials::$credentials['password'];
        $this->database = Credentials::$credentials['database'];
        $this->host = Credentials::$credentials['host'];
    }

    /**
     * @return PDO
     */
    public function connect() {
        $this->mysql = "mysql:host=" . $this->host . ";dbname=" . $this->database;
        $this->connection = New PDO($this->mysql, $this->username, $this->password);
        return $this->connection;
    }

    /**
     * Destroy database connection when finished.
     */
    public function __destruct(){
        $this->connection = NULL;
    }

}