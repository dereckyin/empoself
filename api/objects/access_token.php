<?php

// namespace Emposelft\App;

class AccessToken {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->conn;
    }

    public function insert($user_id, $token, $ip, $user_agent, $expiration) {
        $stmt = $this->db->prepare("INSERT INTO auth_token (user_id, token, ip_address, user_agent, expires_at) VALUES (:user_id, :token, :ip_address, :user_agent, :expires_at)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':ip_address', $ip);
        $stmt->bindParam(':user_agent', $user_agent);
        $stmt->bindParam(':expires_at', $expiration);

        // Execute the statement
        if ($stmt->execute()) {
            return "";
        } else {
            $arr = $stmt->errorInfo();
            error_log($arr[2]);
            return $arr[2];
        }
    }
}
?>