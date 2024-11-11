<?php
class tokenMan {
    private $token = null;

    public function getToken() {
        
        
        return $this->token;
    }

    public function genToken($token) {
        $this->token = $token;
    }
}