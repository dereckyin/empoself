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

    public function get($token) {
        // token only valid for 20 minutes
        // valid after browser close
        //$stmt = $this->db->prepare("SELECT * FROM auth_token WHERE token = :token and created_at > DATE_SUB(NOW(), INTERVAL 20 MINUTE)");
        $stmt = $this->db->prepare("SELECT * FROM auth_token WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_error_count_by_ip($ip) {
        // valid after browser close
        //$stmt = $this->db->prepare("SELECT COUNT(*) as error_count FROM auth_token WHERE ip_address = :ip_address and created_at > DATE_SUB(NOW(), INTERVAL 20 MINUTE)");
        $stmt = $this->db->prepare("SELECT COUNT(*) as error_count FROM auth_token WHERE ip_address = :ip_address");
        $stmt->bindParam(':ip_address', $ip);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_error_count_by_token($token) {
        $stmt = $this->db->prepare("UPDATE auth_token SET error_count = error_count + 1 WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    }

    public function set_verify_code_by_token($token, $verify_code) {
        $stmt = $this->db->prepare("UPDATE auth_token SET verify_code = :verify_code, expires_at = NOW() WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':verify_code', $verify_code);
        $stmt->execute();
    }

    public function get_verify_code_by_token($token) {
        $stmt = $this->db->prepare("SELECT verify_code FROM auth_token WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update_verify_error_count_by_token($token) {
        $stmt = $this->db->prepare("UPDATE auth_token SET verify_error_count = verify_error_count + 1 WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    }

    public function get_verify_error_count_by_token($token) {
        $ret = 0;
        $stmt = $this->db->prepare("SELECT verify_error_count FROM auth_token WHERE token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        // return as number
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ret = $row['verify_error_count'] * 1;
        }
        return $ret;
    }
}
?>