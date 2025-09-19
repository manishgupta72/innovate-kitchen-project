<?php
// File: controllers/CreditScoreController.php
error_reporting(E_ALL);
ini_set('display_errors', 1);



class CreditScoreController
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->connect();
    }
    public function send_otp()
    {
        header('Content-Type: application/json');
        $result = ['success' => false, 'message' => 'Something went wrong'];

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $mobile = trim($input['mobile'] ?? '');

            if (!preg_match('/^[0-9]{10}$/', $mobile)) {
                $result['message'] = 'Invalid mobile number';
                echo json_encode($result);
                return;
            }

            // 🔗 Connect to backend database to check if mobile already exists
            $backendPdo = new PDO("mysql:host=localhost;dbname=scorezyada_backend", "scorezyada", "m1P0j2MUM%Az");
            $backendPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmtCheck = $backendPdo->prepare("SELECT id FROM clients WHERE mobile = ?");
            $stmtCheck->execute([$mobile]);

            if ($stmtCheck->fetch()) {
                $result['message'] = 'This mobile number is already registered.';
                echo json_encode($result);
                return;
            }

            // ✅ Generate OTP
            $otp = rand(100000, 999999);

            // 💾 Save OTP in local `otp` table
            $stmt = $this->pdo->prepare("INSERT INTO otp (mobile, otp, status, created_at, updated_at) VALUES (?, ?, 0, NOW(), NOW())");
            $stmt->execute([$mobile, $otp]);

            // 📤 Send OTP via Mobilogi
            $message = urlencode("Thank you for your request for mobile number verification, Your 6-digit OTP is $otp. IMPETUS ADCON PRIVATE LIMITED");
            $sms_url = "https://vas.mobilogi.com/api.php?username=TJCADV&password=Pass@1234&route=1&sender=IMPADC&mobile[]=$mobile&message[]=$message&template_id=1707174314638297649";

            $ch = curl_init($sms_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $sms_response = curl_exec($ch);
            $curl_error = curl_error($ch);
            curl_close($ch);

            if ($curl_error) {
                $result['message'] = 'OTP saved, but SMS failed: ' . $curl_error;
            } else {
                $result['success'] = true;
                $result['message'] = 'OTP sent successfully.';
            }
        } catch (Throwable $e) {
            $result['message'] = 'Exception: ' . $e->getMessage();
        }

        echo json_encode($result);
    }



    public function verify_otp()
    {
        header('Content-Type: application/json');
        $result = ['success' => false, 'message' => 'Something went wrong'];

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $mobile = trim($input['mobile'] ?? '');
            $inputOtp = trim($input['otp'] ?? '');

            $stmt = $this->pdo->prepare("SELECT * FROM otp WHERE mobile = ? AND status = 0 ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$mobile]);
            $otpRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$otpRecord) {
                $result['message'] = 'OTP not found or already verified';
                echo json_encode($result);
                return;
            }

            if ($otpRecord['otp'] == $inputOtp) {
                $update = $this->pdo->prepare("UPDATE otp SET status = 1 WHERE id = ?");
                $update->execute([$otpRecord['id']]);

                $_SESSION['verified_mobile'] = $mobile;

                $result['success'] = true;
                $result['message'] = 'OTP verified successfully';
            } else {
                $result['message'] = 'Invalid OTP';
            }
        } catch (Throwable $e) {
            $result['message'] = 'Exception: ' . $e->getMessage();
        }

        echo json_encode($result);
    }

    public function submit()
    {
        header('Content-Type: application/json');

        $verifiedMobile = $_SESSION['verified_mobile'] ?? null;

        if (!$verifiedMobile || $verifiedMobile !== $_POST['mobile']) {
            echo json_encode(['success' => false, 'message' => 'Mobile number not verified.']);
            return;
        }

        $data = [
            'first_name'   => $_POST['first_name'],
            'last_name'    => $_POST['last_name'],
            'mobile'       => $_POST['mobile'],
            'pan_number'   => $_POST['pan_number'],
            'dob'          => $_POST['dob'],
            'email_id'     => $_POST['email_id'],
            'pincode'      => $_POST['pincode'],
            'associate_id' => 0,
            'assignto_id'  => 0,
        ];

        try {
            $url = 'https://crm.scorezyada.com/api/front-inquiry';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);

            // Convert to JSON and send as raw JSON
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Requested-With: XMLHttpRequest'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Could not connect to CIBIL-CRM. Error: ' . $curlError
                ]);
                return;
            }

            $crmResponse = json_decode($response, true);

            if ($httpCode === 200 && is_array($crmResponse) && isset($crmResponse['success'])) {
                echo json_encode([
                    'success' => $crmResponse['success'],
                    'message' => $crmResponse['message'] ?? 'No message received.',
                    'response' => $crmResponse
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $crmResponse['message'] ?? 'Unexpected response from CRM.',
                    'raw_response' => $response
                ]);
            }
        } catch (Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }
}
