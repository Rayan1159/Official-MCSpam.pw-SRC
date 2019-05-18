<?php
namespace mcspam;
require_once($_SERVER['DOCUMENT_ROOT'] . '/' . '/inc/Database/Database.php');
USE PDO;

/**
 * Class userController
 * @package mcspam
 */
Class userController {

    /**
     * @var
     */
    private $username, $password, $email, $options, $ip;
    private $license, $unit, $days, $plan, $expire;
    private $message, $date, $title, $id;
    private $database, $pdo;
    private $target, $port, $time, $concurrents, $price, $vip, $version;

    /**
     * userController constructor.
     */
    public function __construct(){
        $this->database = New Database();
        $this->pdo = $this->database->connect();
    }

    private function updateAttack($id) {
        $this->username = $_SESSION['username'];
        $this->id = $id;
        $mysql = $this->pdo->prepare('
        
            UPDATE Tests
                SET status = ?
                  WHERE username = ? AND ID = ?
        
        ');
        $mysql->bindValue(1, "Finished", PDO::PARAM_STR);
        $mysql->bindValue(2, $this->username, PDO::PARAM_STR);
        $mysql->bindValue(3, $this->id, PDO::PARAM_INT);
        $mysql->execute();
    }

    public function loadAttackTable() {
        $this->username = stripslashes(htmlspecialchars($_SESSION['username']));
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Tests
              WHERE username = ?
                ORDER BY ID
                  DESC LIMIT 5
        
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->execute();

        while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
            if ($data['end'] > time()) {
                $status = ($data['end'] - time() . " " . "Seconds ");
            } elseif ($data['end'] < time()) {
                self::updateAttack($data['ID']);
                $status = "<button class='btn btn-success col-lg-12'>Attack finished</button>";
            }
            $this->target = $data['target'];
            $this->port = $data['port'];
            $this->time = $data['time'];
            $this->version = $data['version'];

            $html = "<tr><td>{$this->target}</td><td>{$this->port}</td><td>{$this->time}</td><td>{$this->version}</td><td>{$status}</td></tr>";
            echo $html;
        }
    }

    public function deadAttacks() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Tests
              WHERE status = ?
        
        ');
        $mysql->bindValue(1, "Running", PDO::PARAM_STR);
        $mysql->execute();

        while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
            $this->id = $data['ID'];
            $this->string = $data['end'];
            if (time() > $this->string) {
                $mysql = $this->pdo->prepare('
                
                    UPDATE Tests 
                        SET status = ?
                          WHERE ID = ?
                
                ');
                $mysql->bindValue(1, "Finished", PDO::PARAM_STR);
                $mysql->bindValue(2, $this->id, PDO::PARAM_STR);
                $mysql->execute();
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return int
     */
    public function countUserAttacks() {
        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
            
            SELECT * FROM Tests
          
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->execute();
        return $mysql->rowCount();
    }

    /**
     * @return int
     */
    public function countServers() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Servers
        
        ');
        $mysql->execute();
        return $mysql->rowCount();
    }

    /**
     * @return int
     */
    public function countAttacks() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Tests
        
        ');
        $mysql->execute();
        return $mysql->rowCount();
    }

    /**
     * @return int
     */
    public function countPaidUsers() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
              WHERE NOT plan = ?
        
        ');
        $mysql->bindValue(1, "No plan", PDO::PARAM_STR);
        $mysql->execute();
        return $mysql->rowCount();
    }

    /**
     * @return int
     */
    public function countUsers() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
        
        ');
        $mysql->execute();
        return $mysql->rowCount();
    }


    /**
     * @param $message
     * @return bool
     */
    public function postNews($title, $message) {
        $this->username = $_SESSION['username'];
        $this->message = stripslashes(htmlspecialchars($message));
        $this->date = gmdate("Y-m-d", time());
        $this->title = stripslashes(htmlspecialchars($title));

        if (self::hasPermission() === true) {
            $mysql = $this->pdo->prepare('
            
                INSERT INTO News(author, title, message, datePosted)
                  VALUES(?, ?, ?, ?)
            
            ');
            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->bindValue(2, $this->title, PDO::PARAM_STR);
            $mysql->bindValue(3, $this->message, PDO::PARAM_STR);
            $mysql->bindValue(4, $this->date, PDO::PARAM_STR);
            $mysql->execute();

            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function closeTicket($id) {
        $this->id = $id;
        if (is_numeric($id)) {

            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Tickets
                  WHERE ID = ?
            
            ');
            $mysql->bindValue(1, $this->id, PDO::PARAM_INT);
            $mysql->execute();

            if ($mysql->rowCount() > 0) {

                $mysql = $this->pdo->prepare('
                
                    UPDATE Tickets
                        SET status = ?
                          WHERE ID =?
                
                ');
                $mysql->bindValue(1, "Closed", PDO::PARAM_STR);
                $mysql->bindValue(2, $this->id, PDO::PARAM_STR);
                $mysql->execute();
                return true;

            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function isTicketAuthor($id) {
        $this->id = $id;
        $this->username = $_SESSION['username'];
        if ($this->username) {
            $mysql = $this->pdo->prepare('
            
                SELECT Author FROM Tickets
                  WHERE ID = ?
            
            ');
            $mysql->bindValue(1, $this->id, PDO::PARAM_STR);
            $mysql->execute();

            if ($this->username === $mysql->fetchColumn(0)){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Loads tickets
     *
     * @return boolean
     */
    public function loadUserTickets() {
        $this->username = $_SESSION['username'];
        if ($this->username) {

            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Tickets
                  WHERE author = ?
                    ORDER BY status
            
            ');
            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->rowCount() > 0) {
                while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                    $this->id = $data['ID'];
                    $this->message = $data['message'];
                    $this->department = $data['department'];
                    $this->subject = $data['subject'];
                    $this->priority = $data['priority'];
                    $this->status = $data['status'];

                    if ($this->status === "Open") {
                        $status = "<button class='btn btn-success col-lg-12'>Open</button>";
                    }
                    if ($this->status === "Closed"){
                        $status = "<button class='btn btn-danger col-lg-12'>Closed</button>";
                    }


                    $html = "<tr><td>{$this->subject}</td><td>{$this->priority}</td><td>{$this->department}</td><td>{$status}</td><td><button class='btn btn-info col-lg-12' type='button' id='read' data-id='$this->id'>Read</button></td></tr>";
                    echo $html;
                }
            }
            return false;
        }
    }

    private $subject, $department, $priority, $status;
    /**
     * @param $subject
     * @param $department
     * @param $priority
     * @param $message
     * @return bool
     */
    public function createTicket($subject, $department, $priority, $message){
        $this->username = $_SESSION['username'];
        $this->subject = stripslashes(htmlspecialchars($subject));
        $this->department = stripslashes(htmlspecialchars($department));
        $this->priority = stripslashes(htmlspecialchars($priority));
        $this->message = stripslashes(htmlspecialchars($message));

        if ($this->subject && $this->department && $this->priority && $this->message) {
            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Tickets
                  WHERE author = ?
                    AND status = ?
            ');
            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->bindValue(2, "Open", PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->rowCount() < 2) {
                $mysql = $this->pdo->prepare('
                
                    INSERT INTO Tickets(subject, department, priority, message, author)
                      VALUES(?, ?, ?, ?, ?)

                ');
                $mysql->bindValue(1, $this->subject, PDO::PARAM_STR);
                $mysql->bindValue(2, $this->department, PDO::PARAM_STR);
                $mysql->bindValue(3, $this->priority, PDO::PARAM_STR);
                $mysql->bindValue(4, $this->message, PDO::PARAM_STR);
                $mysql->bindValue(5, $this->username, PDO::PARAM_STR);
                $mysql->execute();

                $this->id = self::getID($this->subject);
                self::insertTicketMessage($this->message, $this->id);
                return true;
            }
            return false;
        }
    }

    /**
     * @param $title
     * @return mixed
     */

    public function getID($title) {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Tickets WHERE author = ? AND subject = ?
        
        ');
        $mysql->bindValue(1, $_SESSION['username'], PDO::PARAM_STR);
        $mysql->bindValue(2, $title, PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 0){
            $data = $mysql->fetch(PDO::FETCH_ASSOC);
            return $data['ID'];
        }
    }

    public function isClosed($id) {
        $this->id = $id;
        if($id) {
            $mysql = $this->pdo->prepare('
            
                SELECT status FROM Tickets
                  WHERE ID = ?
            
            ');
            $mysql->bindValue(1, $this->id, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->fetchColumn(0) === "Closed"){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Load all plans
     */
    public function loadPlans() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Plans
        
        ');
        $mysql->execute();

        while($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
            $this->plan = $data['name'];
            $this->days = $data['days'];
            $this->price = $data['price'];
            $this->time = $data['attack_time'];
            $this->concurrents = $data['concurrents'];
            $this->vip = $data['vip'];

            if ($this->vip === "2"){
                $status = "No VIP Included";
            } else {
                $status = "VIP Included";
            }

            $html = "<div class=\"col-lg-3\">
                        <div class=\"card\">
                            <div class=\"card-body\">
                                <div class=\"pricingTable1 text-center\">
                                    <h6 class=\"title1 py-3 m-0\">{$this->plan}</h6>
                                    <p class=\"text-muted p-3 mb-0\">Plans are purchased through selly, specify a real email address. your code is sent there.</p>
                                    <div class=\"text-center py-4\">
                                        <h3 class=\"amount\">{$this->price}
                                            <small class=\"font-12 text-muted\">/month</small>
                                        </h3>
                                    </div>
                                    <ul class=\"list-unstyled pricing-content-2 py-3 border-0\">
                                        <li>Attack time: {$this->time}</li>
                                        <li>Concurrents: {$this->concurrents}</li>
                                        <li>{$status}</li>
                                    </ul>

                                    <a href=\"https://selly.gg/u/Destinct\"
                                       class=\"btn btn-block  btn-success btn-square btn-skew btn-outline-dashed mt-3 py-3 font-18\"><span>Go to selly</span></a>
                                </div><!--end pricingTable-->
                            </div><!--end card-body-->
                        </div> <!--end card-->
                    </div><!--end col-->";
            echo $html;
        }
    }

    public function checkExpired() {
        $this->username = $_SESSION['username'];
    }

    /**
     * Loads all news
     */
    public function loadNews() {
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM News
        
        ');
        $mysql->execute();

        while($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
            $this->username = $data['author'];
            $this->message = $data['message'];
            $this->date = $data['datePosted'];
            $this->title = $data['title'];

            $html = "<li>
                       <p class=\"timeline-date\">{$this->date}</p>
                       <div class=\"timeline-content\">
                           <div class=\"track-info\">
                              <div class=\"text-muted float-right\">
                                  <p class=\"mb-1\">Author: {$this->username}</p>
                              </div>
                              <h5 class=\"mt-0 mb-1\">{$this->title}</h5>
                              <p class=\"mb-0\">{$this->message}</p>
                           </div>
                         </div>
                      </li>";
            echo $html;
        }
    }

    public function createCaptcha() {
        $_SESSION['captcha'] = rand(1, 25000);
        return $_SESSION['captcha'];
    }

    private $action;
    public function logAll($action) {
        $this->action = $action;
        if ($_SERVER['HTTP_CF_CONNECTING_IP']){
            $this->ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        }

        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
        
            INSERT INTO Logs(type, username, ip)
              VALUES(?, ?, ?)
        ');
        $mysql->bindValue(1, $this->action, PDO::PARAM_STR);
        $mysql->bindValue(2, stripslashes(htmlspecialchars($this->username)), PDO::PARAM_STR);
        $mysql->bindValue(3, $this->ip, PDO::PARAM_STR);
        $mysql->execute();
    }


    public function logLogin($username) {
        if ($_SERVER['HTTP_CF_CONNECTING_IP']){
            $this->ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } else {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        }

        $this->username = $username;
        $mysql = $this->pdo->prepare('
        
            INSERT INTO Logs(type, username, ip)
              VALUES(?, ?, ?)
        ');
        $mysql->bindValue(1, "Login", PDO::PARAM_STR);
        $mysql->bindValue(2, stripslashes(htmlspecialchars($username)), PDO::PARAM_STR);
        $mysql->bindValue(3, $this->ip, PDO::PARAM_STR);
        $mysql->execute();
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */

    public function createSession($username, $password) {
        $this->username = $username;
        $this->password = $password;


        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
              WHERE username = ?
        
        ');
        $mysql->bindValue(1, stripslashes(htmlspecialchars($this->username)), PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 0 ){
            $data = $mysql->fetch(PDO::FETCH_ASSOC);
            if (password_verify($this->password, $data['password'])) {
                $_SESSION['username'] = $data['username'];
                self::logLogin($username);
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $username
     * @param $password
     * @param $email
     * @return bool
     */
    public function register($username, $password, $email) {
        $this->options = [ 'memory_cost' => 1<<17, 'time_cost' => '4',  'threads' => 3 ];
        $this->username = stripslashes(htmlspecialchars($username));
        $this->password = password_hash($password, PASSWORD_ARGON2I, $this->options);
        $this->email = stripslashes(htmlspecialchars($email));

        //create database connection
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
              WHERE username = ?
        
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 0) {
            return false;
        } else {

            $mysql = $this->pdo->prepare('
            
                INSERT INTO Users(username, password, email)
                  VALUES(?, ?, ?)
            
            ');
            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->bindValue(2, $this->password, PDO::PARAM_STR);
            $mysql->bindValue(3, $this->email, PDO::PARAM_STR);
            $mysql->execute();

            return true;

        }
    }

    /**
     * @return bool
     */

    public function hasPermission() {
        $this->username = stripslashes(htmlspecialchars($_SESSION['username']));

        if ($this->username) {
            $mysql = $this->pdo->prepare('
            
                SELECT rank FROM Users
                  WHERE username = ?
            
            ');
            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->fetchColumn(0) === "777") {
                return true;
            }
            return false;
        }
    }


    /**
     * @param $plan
     * @param $attack_time
     * @param $concurrents
     * @param $price
     * @param $days
     * @param $vip
     * @return bool
     */
    public function createPlan($plan, $attack_time, $concurrents, $price, $days, $vip) {
        if (self::hasPermission() === true) {
            $this->plan = stripslashes(htmlspecialchars($plan));
            $this->time = $attack_time;
            $this->concurrents = $concurrents;
            $this->price = $price;
            $this->days = $days;
            $this->vip = $vip;

            if ($this->plan && $this->time && $this->concurrents && $this->days && $this->vip) {
                $mysql = $this->pdo->prepare('
                
                
                    INSERT INTO Plans(name, attack_time, concurrents, price, vip, unit, days)
                        VALUES(?, ?, ?, ?, ?, ?, ?)
                
                
                ');
                $mysql->bindValue(1, $this->plan, PDO::PARAM_STR);
                $mysql->bindValue(2, $this->time, PDO::PARAM_INT);
                $mysql->bindValue(3, $this->concurrents, PDO::PARAM_INT);
                $mysql->bindValue(4, $this->price, PDO::PARAM_STR);
                $mysql->bindValue(5, $this->vip, PDO::PARAM_INT);
                $mysql->bindValue(6, "Days", PDO::PARAM_STR);
                $mysql->bindValue(7, $this->days, PDO::PARAM_INT);
                $mysql->execute();
                self::logAll("Created plan $this->plan");
                return true;

            }
        }
    }

    private $string;
    public function hasPlan(){
        $this->username = $_SESSION['username'];
        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Users
              WHERE username = ?
        
        ');
        $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
        $mysql->execute();

        while ($data = $mysql->fetch(PDO::FETCH_ASSOC)){
            $this->string = $data['expire'];
            if (time() > $this->string){
                $mysql = $this->pdo->prepare('
                    
                    UPDATE Users
                        SET plan = ?, expire = ?
                          WHERE username = ?
                ');
                $mysql->bindValue(1, "No plan", PDO::PARAM_STR);
                $mysql->bindValue(2, 0, PDO::PARAM_INT);
                $mysql->bindValue(3, $this->username);
                $mysql->execute();
                return true;
            }
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

    /**
     * @param $id
     * @return bool
     */
    public function deletePlan($id) {
        $this->plan = stripslashes(htmlspecialchars($id));

        if (self::hasPermission() === true) {
            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Plans
                  WHERE ID = ?
            
            ');
            $mysql->bindValue(1, $this->plan, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->rowCount() > 0){
                $mysql = $this->pdo->prepare('
                
                    DELETE FROM Plans
                      WHERE ID = ?
                
                ');
                $mysql->bindValue(1, $this->plan, PDO::PARAM_STR);
                $mysql->execute();
                return true;
            }
            return false;
        }
    }

    /**
     * @param $plan
     * @return bool
     */
    public function createLicense($plan, $amount) {
        if (self::hasPermission() === true) {
            $this->plan = $plan;
            for ($start = 0; $start < $amount; $start++) {
                $this->license = rand(1, 5000000).$plan;
                $mysql = $this->pdo->prepare('
        
                    INSERT INTO Licenses(plan, code)
                      VALUES(?, ?)
                
               ');
                $mysql->bindValue(1, $this->plan, PDO::PARAM_STR);
                $mysql->bindValue(2, $this->license, PDO::PARAM_STR);
                $mysql->execute();
            }
            self::logAll("Created $amount licenses");
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */

    public function deleteLicense($id) {
        if (self::hasPermission() === true) {
            $this->license = $id;

            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Licenses
                  WHERE ID = ?
                
            ');
            $mysql->bindValue(1, $this->license, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->rowCount() > 0) {
                $mysql = $this->pdo->prepare('
                   
                   DELETE FROM Licenses
                      WHERE ID = ?
                
                ');
                $mysql->bindValue(1, $this->license, PDO::PARAM_STR);
                $mysql->execute();
                return true;
            }
            return false;
        }
    }

    public function deleteClaimedLicense($license) {
        $this->license = stripslashes(htmlspecialchars($license));
        if ($this->license) {
            $mysql = $this->pdo->prepare('
            
                DELETE FROM Licenses
                  WHERE code = ?
            
            ');
            $mysql->bindValue(1, $this->license, PDO::PARAM_STR);
            $mysql->execute();
        }
    }

    /**
     * @param $license
     * @return bool
     */
    public function activateMembership($license) {
        $this->username = stripslashes(htmlspecialchars($_SESSION['username']));
        $this->license = stripslashes(htmlspecialchars($license));

        $mysql = $this->pdo->prepare('
        
            SELECT * FROM Licenses
              WHERE code = ?
        
        ');
        $mysql->bindValue(1, $this->license, PDO::PARAM_STR);
        $mysql->execute();

        if ($mysql->rowCount() > 0) {
            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Licenses
                  WHERE code = ?
            
            ');
            $mysql->bindValue(1, $this->license, PDO::PARAM_STR);
            $mysql->execute();

            $data = $mysql->fetch(PDO::FETCH_ASSOC);
            $this->plan = $data['plan'];

            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Plans
                  WHERE name = ?
            
            ');
            $mysql->bindValue(1, $this->plan, PDO::PARAM_STR);
            $mysql->execute();

            while($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                $this->days = $data['days'];
                $this->unit = $data['unit'];
                $this->expire = strtotime("+{$this->days} {$this->unit}");

                $mysql = $this->pdo->prepare('
                
                    UPDATE Users
                        SET plan = ?, expire = ?
                    WHERE username = ?
                
                ');
                $mysql->bindValue(1, $this->plan, PDO::PARAM_STR);
                $mysql->bindValue(2, $this->expire, PDO::PARAM_INT);
                $mysql->bindValue(3, $this->username, PDO::PARAM_STR);
                $mysql->execute();
                self::logAll("Claimed license $license");
                self::deleteClaimedLicense($this->license);
                return true;
            }
            return false;
        }
    }

    public function insertTicketMessage($message ,$id) {
        $this->username = $_SESSION['username'];
        $this->id = $id;
        $this->message = stripslashes(htmlspecialchars($message));

        if ($id && $message) {
            $mysql = $this->pdo->prepare('
            
                INSERT INTO ticket_messages(sender, message, ticket_id)
                  VALUES(?, ?, ?)
                
            ');

            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->bindValue(2, $this->message, PDO::PARAM_STR);
            $mysql->bindValue(3, $this->id, PDO::PARAM_STR);
            $mysql->execute();
            return true;
        } else  {
            return false;
        }
    }

    private $ticket_id;
    public function loadTicketData($id) {
        $this->id = $id;
        if ($id) {
            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Tickets
                  WHERE ID = ?
            
            ');
            $mysql->bindValue(1, $this->id, PDO::PARAM_INT);
            $mysql->execute();

            if ($mysql->rowCount() > 0) {
                while ($data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                    $this->ticket_id = $data['ID'];
                    $this->title = $data['title'];

                    $mysql = $this->pdo->prepare('
                    
                        SELECT * FROM ticket_messages
                          WHERE ticket_id = ?
                    
                    ');
                    $mysql->bindValue(1, $this->ticket_id, PDO::PARAM_INT);
                    $mysql->execute();

                    $html = [];
                    while ($html_data = $mysql->fetch(PDO::FETCH_ASSOC)) {
                        $this->message = $html_data['message'];
                        $this->username = $html_data['sender'];

                        if ($_SESSION['username'] == $this->username){
                            $html = "         <div class=\"media\">                                                        
                                                <div class=\"media-body reverse\">
                                                    <div class=\"chat-msg\">
                                                        <p style='width: 100%; word-wrap: break-word'>{$this->message}</p>
                                                    </div>                       
                                                                                         
                                                </div><!--end media-body--> 
                                            </div><!--end media-->  ";
                        } elseif ($this->username !== $_SESSION['username']) {
                            $html = "         <div class=\"media\">
                                                                        <div class=\"media-img\">
                            <img src=\"../../assets/images/D6KXMpyS_400x400.png\" alt=\"user\" class=\"rounded-circle thumb-md\">
                        </div>                                 
                                                <div class=\"media-body\">
                                                    <div class=\"chat-msg\">
                                                        <p style='width: 100%; word-wrap: break-word'>{$this->message}</p>
                                                    </div>                       
                                                                                         
                                                </div><!--end media-body--> 
                                            </div><!--end media-->  ";
                        }
                        echo $html;

                    }
                }
            } else {
                echo "<script>window.location = 'ticket';</script>";
                exit();
            }
        }
    }

    public function deleteBlacklist($host) {
        $this->ip = $host;
        if ($this->ip) {
            $mysql = $this->pdo->prepare('
            
                SELECT * FROM Blacklists
                  WHERE host = ?
            
            ');
            $mysql->bindValue(1, stripslashes(htmlspecialchars($this->ip)), PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->rowCount() > 0) {
                $mysql = $this->pdo->prepare('
                
                    DELETE FROM Blacklists
                      WHERE host = ?
                
                ');
                $mysql->bindValue(1, $this->ip, PDO::PARAM_STR);
                $mysql->execute();
                return true;
            } else {
                return false;
            }
        }
    }

    public function addBlacklist($host) {
        $this->ip = $host;
        if ($this->ip) {

            $mysql = $this->pdo->prepare('
            
                INSERT INTO Blacklists(host)
                  VALUES(?)
            
            ');
            $mysql->bindValue(1, stripslashes(htmlspecialchars($this->ip)), PDO::PARAM_STR);
            $mysql->execute();
            return true;
        } else {
            return false;
        }
    }

    public function isBanned($username) {
        $this->username = stripslashes(htmlspecialchars($username));
        if ($this->username) {

            $mysql = $this->pdo->prepare('
            
                SELECT status FROM Users
                  WHERE username = ?
            
            ');
            $mysql->bindValue(1, $this->username, PDO::PARAM_STR);
            $mysql->execute();

            if ($mysql->fetchColumn(0) === "banned") {
                return true;
            } else {
                return false;
            }
        }
    }
}
