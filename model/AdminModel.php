<?php
class AdminModel
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->connect();
    }

    // General Settings Methods
    public function getGeneralSettings()
    {
        $stmt = $this->pdo->query("SELECT * FROM general_settings LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateGeneralSettings($data)
    {
        $sql = "UPDATE general_settings SET
                system_name = :system_name,
                application_title = :application_title,
                website_title = :website_title,
                website_url = :website_url,
                website_description = :website_description,
                website_keywords = :website_keywords,
                site_copyright = :site_copyright
                WHERE id = 1";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateLogo($type, $filename)
    {
        // $type must be one of: website_logo, admin_logo, favicon_logo
        $sql = "UPDATE general_settings SET $type = :filename WHERE id = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['filename' => $filename]);
    }

    // Contact Settings Methods

    public function getContactSettings()
    {
        $stmt = $this->pdo->query("SELECT * FROM contact_settings LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateContactSettings($data)
    {
        $sql = "UPDATE contact_settings SET
            contact_number = :contact_number,
            contact_number_alternate = :contact_number_alternate,
            whatsapp_number = :whatsapp_number,
            email_id = :email_id,
            email_id_alternate = :email_id_alternate,
            address_line_1 = :address_line_1,
            address_line_2 = :address_line_2,
            address_line_3 = :address_line_3,
            google_map_iframe = :google_map_iframe,
            facebook_url = :facebook_url,
            instagram_url = :instagram_url,
            youtube_url = :youtube_url,
            linkedin_url = :linkedin_url,
            x_url = :x_url,
            custom_url = :custom_url,
            extra_info_1 = :extra_info_1,
            extra_info_2 = :extra_info_2,
            song_text = :song_text,
            song_image = :song_image
            WHERE id = 1";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // --- Counter Settings Methods ---
    public function getCounterSettings()
    {
        $stmt = $this->pdo->query("SELECT * FROM counter_settings LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCounterSettings($data)
    {
        $sql = "UPDATE counter_settings SET
                  counter_name_1 = :counter_name_1,
                  count_no_1 = :count_no_1,
                  counter_name_2 = :counter_name_2,
                  count_no_2 = :count_no_2,
                  counter_name_3 = :counter_name_3,
                  count_no_3 = :count_no_3,
                  counter_name_4 = :counter_name_4,
                  count_no_4 = :count_no_4
                  WHERE id = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }


    // User Methods
    public function getUserById($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUserPassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET user_password = :password WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['password' => $hashedPassword, 'user_id' => $userId]);
    }

    public function verifyCurrentPassword($userId, $currentPassword)
    {
        $user = $this->getUserById($userId);
        if ($user) {
            return password_verify($currentPassword, $user['user_password']);
        }
        return false;
    }

    // Company Details Methods
    public function getCompanyDetails()
    {
        $stmt = $this->pdo->query("SELECT * FROM company_details LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCompanyDetails($data)
    {
        $sql = "UPDATE company_details SET
                    company_name = :company_name,
                    company_invoice_name = :company_invoice_name,
                    company_address_1 = :company_address_1,
                    company_address_2 = :company_address_2,
                    company_city = :company_city,
                    company_pin_code = :company_pin_code,
                    company_gst_no = :company_gst_no,
                    company_state = :company_state,            -- New field
                    company_state_code = :company_state_code,  -- New field
                    company_contact_no = :company_contact_no,
                    company_bank_name = :company_bank_name,
                    company_bank_account_no = :company_bank_account_no,
                    company_ifsc_code = :company_ifsc_code,
                    company_bank_branch = :company_bank_branch,
                    company_logo_invoice = :company_logo_invoice,
                    company_digital_signature = :company_digital_signature
                WHERE company_id = 1";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateCompanyLogo($filename)
    {
        $sql = "UPDATE company_details SET company_logo_invoice = :filename WHERE company_id = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['filename' => $filename]);
    }

    public function updateCompanyDigitalSignature($filename)
    {
        $sql = "UPDATE company_details SET company_digital_signature = :filename WHERE company_id = 1";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['filename' => $filename]);
    }
}
