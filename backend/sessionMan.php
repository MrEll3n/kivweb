<?php
// Custom Session token manager
class SessionMan {
    private $pdo = null;
        private $sessionExpireTime = "+ 15 minutes"; // 15 minutes

    // Constructor
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Gives back user_id from database
    // $token: token
    // Returns: user_id / null
    public function getUserId($token) {
        $stmt = $this->pdo->prepare('SELECT user_id FROM SESSIONS WHERE session_token = :session_token');
        $stmt->execute(['session_token' => $token]);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        } else {
            return $result['user_id'];
        }
    }

    // Gives back token from database
    // $user_id: user_id
    // Returns: token / null
    public function getToken($user_id) {
        $stmt = $this->pdo->prepare('SELECT session_token FROM SESSIONS WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch();
        if (!$result) {
            return null;
        } else {
            return $result['session_token'];
        }
    }

    // Generates token with SHA256 algorithm
    public function genToken() {
        return hash('sha256',random_bytes(32));
    }

    // Checks if token exists
    // $token: token
    // Returns: boolean
    public function checkToken($token) {
        $stmt = $this->pdo->prepare('SELECT * FROM SESSIONS WHERE session_token = :session_token');
        $stmt->execute(['session_token' => $token]);
        $result = $stmt->fetch();

        //echo $result['session_token'];
        
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Checks if session exists
    // $token: token
    // Returns: boolean
    public function checkSessionUserId($user_id) {
        $stmt = $this->pdo->prepare('SELECT * FROM SESSIONS WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        $result = $stmt->fetch();


        if ($result && sizeof($result) > 0) {
            //echo "true"; 
            return true;
        } else {
            //echo "false";
            return false;
        }
    }

    // Checks if session exists
    // $token: token
    // Returns: boolean
    public function checkSessionToken($token) {
        $stmt = $this->pdo->prepare('SELECT * FROM SESSIONS WHERE session_token = :session_token');
        $stmt->execute(['session_token' => $token]);
        $result = $stmt->fetch();

        if ($result && sizeof($result) > 0) {
            //echo "true"; 
            return true;
        } else {
            //echo "false";
            return false;
        }
    }

    // Creates session and adds it into database
    // $user_id: user_id
    public function createSession($user_id) {
        if ($this->checkSessionUserId($user_id)) {
            $this->killSessionUserId($user_id);
        }
        
        do {
            $new_token = $this->genToken();
        } while ($this->checkToken($new_token));

        // Create a new DateTime object
        $dateTime = new DateTime();
        // Add 5 minutes
        $dateTime->modify($this->sessionExpireTime);
        // Format the DateTime object for MariaDB
        $formattedDateTime = $dateTime->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare('INSERT INTO SESSIONS (session_token, user_id, expires_at) VALUES (:session_token, :user_id, :expires_at)');
        $stmt->execute(['session_token' => $new_token, 'user_id' => $user_id, 'expires_at' => $formattedDateTime]);
    }

    // Deletes session from database
    // $user_id: user_id
    public function killSessionUserId($user_id) {
        if (!$this->checkSessionUserId($user_id)) {
            return;
        }

        $stmt = $this->pdo->prepare('DELETE FROM SESSIONS WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);

    }

    // Deletes session from database
    // $user_id: user_id
    public function killSessionToken($token) {
        if (!$this->checkSessionToken($token)) {
            return;
        }

        $stmt = $this->pdo->prepare('DELETE FROM SESSIONS WHERE session_token = :session_token');
        $stmt->execute(['session_token' => $token]);

    }

    // Checks if session is valid
    // $token: token
    // Returns: boolean / null (if session does not exist)
    public function isSessionTimeOut($token) {

        $currentDateTime = new DateTimeImmutable('now');
        $formattedCurrentDateTime = $currentDateTime->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare('SELECT * FROM SESSIONS WHERE session_token = :session_token');
        $stmt->execute(['session_token' => $token]);
        $result = $stmt->fetch();

        //echo $token;
        //echo $result['expires_at'];

        $expireDateTime = new DateTimeImmutable($result['expires_at']);
        $formattedExpiredDateTime = $expireDateTime->format('Y-m-d H:i:s');

        //echo "ExpiredDateTime: ".$formattedExpiredDateTime;

        //echo "CurrentDateTime: ".$formattedCurrentDateTime;

        if ($currentDateTime > $expireDateTime) {
            return true;
        } else {
            return false;
            
        }
    }

}