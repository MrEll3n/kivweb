<?php
// Custom Session token manager
class tokenMan {
    private $token = null;
    private $pdo = null;

    // Constructor
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Returns the token
    public function getToken() {
        return $this->token;
    }

    // Generates token
    public function genToken() {
        $this->token = hash('sha256',random_bytes(32));
    }
}