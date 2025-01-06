<?php

// namespace Emposelft\App;

class Consult {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->conn;
    }

    public function insert($data) {
        $sql = "insert into consults 
                set 
                    name = :name,
                    gender = :gender,
                    birthday = :birthday,
                    phone = :phone,
                    email = :email,
                    address = :address,
                    emergency_contact = :emergency_contact,
                    emergency_contact_phone = :emergency_contact_phone,
                    emergency_contact_relation = :emergency_contact_relation,
                    referral_source = :referral_source,
                    health_condition = :health_condition,
                    account_status = :account_status,
                    profile_photo_url = :profile_photo_url,
                    active_branch = :active_branch,
                    id_number = :id_number,
                    password_hash = :password_hash,
                    contact_time = :contact_time,
                    fitness_goals = :fitness_goals,
                    referrer_id = :referrer_id,
                    height = :height,
                    weight = :weight,
                    default_invoice_type = :default_invoice_type,
                    mobile_barcode = :mobile_barcode,
                    company_tax_id = :company_tax_id,
                    company_name = :company_name";
                    
        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':birthday', $data['birthday']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':emergency_contact', $data['emergency_contact']);
        $stmt->bindParam(':emergency_contact_phone', $data['emergency_contact_phone']);
        $stmt->bindParam(':emergency_contact_relation', $data['emergency_contact_relation']);
        $stmt->bindParam(':referral_source', $data['referral_source']);
        $stmt->bindParam(':health_condition', $data['health_condition']);
        $stmt->bindParam(':account_status', $data['account_status']);
        $stmt->bindParam(':profile_photo_url', $data['profile_photo_url']);
        $stmt->bindParam(':active_branch', $data['active_branch']);
        $stmt->bindParam(':id_number', $data['id_number']);
        $stmt->bindParam(':password_hash', $data['password_hash']);
        $stmt->bindParam(':contact_time', $data['contact_time']);
        $stmt->bindParam(':fitness_goals', $data['fitness_goals']);
        $stmt->bindParam(':referrer_id', $data['referrer_id']);
        $stmt->bindParam(':height', $data['height']);
        $stmt->bindParam(':weight', $data['weight']);
        $stmt->bindParam(':default_invoice_type', $data['default_invoice_type']);
        $stmt->bindParam(':mobile_barcode', $data['mobile_barcode']);
        $stmt->bindParam(':company_tax_id', $data['company_tax_id']);
        $stmt->bindParam(':company_name', $data['company_name']
        
        );

        // Execute the statement
        if ($stmt->execute()) {
            return "New record created successfully";
        } else {
            return "Error: " . $stmt->error;
        }
    }
}
?>