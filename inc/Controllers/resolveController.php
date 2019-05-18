<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 28-4-19
 * Time: 13:49
 */
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . '/inc/Database/Database.php');
USE PDO;

/**
 * Class resolveController
 * @package mcspam
 */

Class resolveController {
    /**
     * @var Database
     */
    private $mysql, $pdo;

    /**
     * resolveController constructor.
     */
    public function __construct(){
        $this->mysql = new Database();
        $this->pdo = $this->mysql->connect();
    }


    /**
     * @param $address
     * @return |null
     */
    private function parseAddress($address){

        if (!strstr($address, ":")){
            return $address;
        }

        $dat = explode(":", $address);
        $server_hostname = $dat[0];
        $server_port = $dat[1];
        if ($server_port=="25565"){
            return $server_hostname;
        }

        return NULL;
    }


    /**
     * @var $host
     */
    private $host;

    /**
     * @param $address
     * @return string
     */
    public function checkAddress($address) {
        $this->host = stripslashes(htmlspecialchars($address));

        $check_address = $this->parseAddress($address);
        if ($check_address==NULL){
            return $address;
        }

        $result = dns_get_record("_minecraft._tcp.$check_address", DNS_SRV);
        if ($result){
            $lowest_priority = 0;
            $valid_record = false;
            foreach ($result as $record){
                $record_type = $record['type'];
                $record_pri = $record['pri'];
                $record_port = $record['port'];
                $record_target = $record['target'];
                if ($record_type=="SRV" && ($valid_record==false || $record_pri <= $lowest_priority)){
                    $address = $record_target.':'.$record_port;
                    $lowest_priority = $record_pri;
                    $valid_record = true;
                }
            }
            return $address;
        } else {
            return false;
        }

    }

}