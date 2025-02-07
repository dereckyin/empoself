<?php

// namespace Emposelft\App;

class User {
    private $db;

    private $table_name = "user";

    public $id;
    public $name;
    public $username;
    public $user_type;
    public $gender;
    public $birthday;
    public $phone;
    public $email;
    public $address;
    public $emergency_contact;
    public $emergency_contact_phone;
    public $referral_source;
    public $referral_source_other;
    public $health_condition;
    public $health_condition_other;
    public $account_status;
    public $profile_photo_url;
    public $active_branch;
    public $id_number;
    public $password_hash;
    public $contact_time;
    public $referrer_name;
    public $fitness_goals;
    public $referrer_id;
    public $height;
    public $weight;
    public $default_invoice_type;
    public $mobile_barcode;
    public $company_tax_id;
    public $company_name;

    public function __construct(Database $database) {
        $this->db = $database->conn;
    }

    function userCanLogin(){
        // query to check if email exists
        $query = "SELECT user.id, `name`, username, password_hash, account_status, user_type, email, phone
                FROM " . $this->table_name . "
                WHERE phone = ? 
                and `status` <> -1
                LIMIT 0,1";
    
        // prepare the query
        $stmt = $this->db->prepare( $query );
    
        // sanitize
        $this->phone=htmlspecialchars(strip_tags($this->phone));
    
        // bind given phone value
        $stmt->bindParam(1, $this->phone);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if phone exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->username = $row['username'];
            $this->password_hash = $row['password_hash'];
            $this->account_status = $row['account_status'];
            $this->user_type = $row['user_type'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            
            // return true because email exists in the database
            return true;
        }
    
        // return false if email does not exist in the database
        return false;
    }

    public function getByNameAndBirthday($name, $birthday) {
        // Prepare the SQL statement using PDO
        $query = "SELECT id, `name`, 
                        gender, 
                        birthday, 
                        phone, 
                        email, 
                        `address`, 
                        emergency_contact, 
                        emergency_contact_phone, 
                        referral_source, 
                        referral_source_other, 
                        health_condition, 
                        health_condition_other
                FROM `user` WHERE `name` = :name AND `birthday` = :birthday and `status` <> -1";

        $stmt = $this->db->prepare($query);
        
        // Bind parameters using PDO's bindParam method
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthday', $birthday);
        
        // Execute the statement
        $stmt->execute();
        
        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    } 


    public function update($data) {
        $sql = "update user 
                set 
                    gender = :gender,
                    phone = :phone,
                    email = :email,
                    address = :address,
                    emergency_contact = :emergency_contact,
                    emergency_contact_phone = :emergency_contact_phone,
                    referral_source = :referral_source,
                    referral_source_other = :referral_source_other,
                    health_condition = :health_condition,
                    health_condition_other = :health_condition_other,
                    account_status = :account_status,
                    profile_photo_url = :profile_photo_url,
                    active_branch = :active_branch,
                    id_number = :id_number,
                    password_hash = :password_hash,
                    contact_time = :contact_time,
                    fitness_goals = :fitness_goals,
                    user_type = '學員',
                    referrer_name = :referrer_name,
                    height = :height,
                    weight = :weight,
                    default_invoice_type = :default_invoice_type,
                    mobile_barcode = :mobile_barcode,
                    company_tax_id = :company_tax_id,
                    company_name = :company_name
                    where
                    `status` <> -1 and
                    name = :name and
                    birthday = :birthday";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);
                    
        // Bind parameters
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':emergency_contact', $data['emergency_contact']);
        $stmt->bindParam(':emergency_contact_phone', $data['emergency_contact_phone']);
        $stmt->bindParam(':referral_source', $data['referral_source']);
        $stmt->bindParam(':referral_source_other', $data['referral_source_other']);
        $stmt->bindParam(':health_condition', $data['health_condition']);
        $stmt->bindParam(':health_condition_other', $data['health_condition_other']);
        $stmt->bindParam(':account_status', $data['account_status']);
        $stmt->bindParam(':profile_photo_url', $data['profile_photo_url']);
        $stmt->bindParam(':active_branch', $data['active_branch']);
        $stmt->bindParam(':id_number', $data['id_number']);
        $stmt->bindParam(':password_hash', $data['password_hash']);
        $stmt->bindParam(':contact_time', $data['contact_time']);
        $stmt->bindParam(':fitness_goals', $data['fitness_goals']);
        $stmt->bindParam(':referrer_name', $data['referrer_name']);
        $stmt->bindParam(':height', $data['height']);
        $stmt->bindParam(':weight', $data['weight']);
        $stmt->bindParam(':default_invoice_type', $data['default_invoice_type']);
        $stmt->bindParam(':mobile_barcode', $data['mobile_barcode']);
        $stmt->bindParam(':company_tax_id', $data['company_tax_id']);
        $stmt->bindParam(':company_name', $data['company_name']);
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':birthday', $data['birthday']);

        // Execute the statement
        if ($stmt->execute()) {
            return "";
        } else {
            $arr = $stmt->errorInfo();
            error_log($arr[2]);
            return $arr[2];
        }
    }


    public function insert($data) {
        $sql = "insert into user 
                set 
                    name = :name,
                    gender = :gender,
                    birthday = :birthday,
                    phone = :phone,
                    email = :email,
                    address = :address,
                    emergency_contact = :emergency_contact,
                    emergency_contact_phone = :emergency_contact_phone,
                    referral_source = :referral_source,
                    referral_source_other = :referral_source_other,
                    health_condition = :health_condition,
                    health_condition_other = :health_condition_other,
                    account_status = :account_status,
                    profile_photo_url = :profile_photo_url,
                    active_branch = :active_branch,
                    id_number = :id_number,
                    password_hash = :password_hash,
                    contact_time = :contact_time,
                    fitness_goals = :fitness_goals,
                    user_type = '學員',
                    referrer_name = :referrer_name,
                    height = :height,
                    weight = :weight,
                    default_invoice_type = :default_invoice_type,
                    mobile_barcode = :mobile_barcode,
                    company_tax_id = :company_tax_id,
                    company_name = :company_name";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);
                    
        // Bind parameters
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':birthday', $data['birthday']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':emergency_contact', $data['emergency_contact']);
        $stmt->bindParam(':emergency_contact_phone', $data['emergency_contact_phone']);
        $stmt->bindParam(':referral_source', $data['referral_source']);
        $stmt->bindParam(':referral_source_other', $data['referral_source_other']);
        $stmt->bindParam(':health_condition', $data['health_condition']);
        $stmt->bindParam(':health_condition_other', $data['health_condition_other']);
        $stmt->bindParam(':account_status', $data['account_status']);
        $stmt->bindParam(':profile_photo_url', $data['profile_photo_url']);
        $stmt->bindParam(':active_branch', $data['active_branch']);
        $stmt->bindParam(':id_number', $data['id_number']);
        $stmt->bindParam(':password_hash', $data['password_hash']);
        $stmt->bindParam(':contact_time', $data['contact_time']);
        $stmt->bindParam(':fitness_goals', $data['fitness_goals']);
        $stmt->bindParam(':referrer_name', $data['referrer_name']);
        $stmt->bindParam(':height', $data['height']);
        $stmt->bindParam(':weight', $data['weight']);
        $stmt->bindParam(':default_invoice_type', $data['default_invoice_type']);
        $stmt->bindParam(':mobile_barcode', $data['mobile_barcode']);
        $stmt->bindParam(':company_tax_id', $data['company_tax_id']);
        $stmt->bindParam(':company_name', $data['company_name']);

        // Execute the statement
        if ($stmt->execute()) {
            return "";
        } else {
            $arr = $stmt->errorInfo();
            error_log($arr[2]);
            return $arr[2];
        }
    }

    
    public function updatePassword ($id, $password_hash) {
        $sql = "update user 
                set 
                    password_hash = :password_hash
                    where
                    id = :id";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);
                    
        // Bind parameters
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':id', $id);

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