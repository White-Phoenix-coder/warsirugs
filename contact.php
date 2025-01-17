<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection from the config file
include 'warsirugs@1357admin/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = htmlspecialchars(trim($_POST['Name']));
    $number = htmlspecialchars(trim($_POST['phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Google reCAPTCHA validation
    $recaptchaSecret = '6LdDkrMqAAAAAOrsinuAUEH0IUzTvVne3SPvpRQV'; // Replace with your secret key from Google reCAPTCHA
    $recaptchaVerificationUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptchaVerificationResponse = file_get_contents($recaptchaVerificationUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
    $recaptchaResult = json_decode($recaptchaVerificationResponse);

    // Check if reCAPTCHA was successful
    if (!$recaptchaResult->success) {
        $response = ['status' => 'error', 'message' => 'reCAPTCHA verification failed. Please try again.'];
        echo json_encode($response);
        exit();
    }

    try {
        // Prepare the query for inserting data into the database
        $query = "INSERT INTO contact (Name, phone, email, message) VALUES (:name, :number,  :email,:message)";
    
        // Prepare the statement
        $stmt = $conn->prepare($query);
        
        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':number', $number);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $message);

        
        // Execute the query and check for success
        if ($stmt->execute()) {
            // $response = ['status' => 'success', 'message' => 'Your message has been sent successfully.'];
            // Sanitize and process data
                $Name = $name;
                $Email = $email;
                $Phone = $number;
                $Message = $message;
               

                $to = "office@ajinfotek.in,umersiddiqui62@gmail.com";
                $subject = "Warsirugs";
                $txt = "Name: $Name<br>Email: $Email<br>Phone: $Phone<br>Message: $Message";
                // $txt = "hello";


                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                

                // Send email and insert data
                if (mail($to, $subject, $txt, $headers)) {
                    
                   echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
                    
                    exit;
                } else {
                    $response = ['status' => 'error', 'message' => 'Error sending email.'];
                    exit;
                }
        } else {
            // Get error info from the statement and log it
            $errorInfo = $stmt->errorInfo();
            throw new Exception("Error executing query: " . $errorInfo[2]);
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        $response = ['status' => 'error', 'message' => 'There was an error while submitting your message. Please try again.'];
    }

    // Log the response being sent to the client
    error_log("Response: " . json_encode($response));

    // Return the response as JSON
    echo json_encode($response);
    exit();
}
?>
