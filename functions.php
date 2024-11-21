<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function validateLoginCredentials($email, $password) {
        $errors = [];
        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        if (empty($password)) {
            $errors[] = "Password is required.";
        }
        return $errors;
    }

    function renderAlert($messages, $type = 'danger') {
        if (empty($messages)) {
            return '';
        }
        if (!is_array($messages)) {
            $messages = [$messages];
        }
    
        $html = '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
        $html .= '<ul>';
        foreach ($messages as $message) {
            $html .= '<li>' . htmlspecialchars($message) . '</li>';
        }
        $html .= '</ul>';
        $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $html .= '</div>';
    
        return $html;
    }

    function db_connect() {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $database = 'dct-ccs-finals';
    
        $connection = new mysqli($host, $user, $password, $database);
    
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
    
        return $connection;
    }

    function authenticate_user($email, $password) {
        $connection = db_connect();
        $password_hash = md5($password);
    
        $query = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $email, $password_hash);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return user data
        }
    
        return false; // Login failed
    }

    function login_user($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
    }

    function getBaseURL() {
        // Check if HTTPS is enabled
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        // Build the base URL using host and server name
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/'; // Ensure it points to the root
    }

    function checkUserSessionIsActive() {
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
            header("Location: admin/dashboard.php");
            exit;
        }
    }

    function logout_user() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); // Start the session if not already started
        }
        session_destroy(); // Destroy the session
        header("Location:../index.php"); // Redirect to root login page
        exit();
    }

    function validateSubjectData($subject_data) {
        $errors = [];
    
        // Check if subject code is provided and has a valid length
        if (empty($subject_data['subject_code'])) {
            $errors[] = "Subject code is required.";
        } elseif (strlen($subject_data['subject_code']) > 4) { // Limiting subject code length to 4 characters
            $errors[] = "Subject code cannot be longer than 4 characters.";
        }
    
        // Check if subject name is provided and is not too long
        if (empty($subject_data['subject_name'])) {
            $errors[] = "Subject name is required.";
        } elseif (strlen($subject_data['subject_name']) > 100) { // Limiting subject name length to 100 characters
            $errors[] = "Subject name cannot be longer than 100 characters.";
        }
    
        return $errors; // Return the list of errors
    }
    
    // Function to check for duplicate subject data in the database
    function checkDuplicateSubjectData($subject_data) {
        $connection = db_connect();
        $query = "SELECT * FROM subjects WHERE subject_code = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $subject_data['subject_code']);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return "Subject code already exists. Please choose another."; // Return the error message for duplicates
        }
    
        return ''; // No duplicate found
    }
    
    // Function to check for duplicate subject name in the database
    function checkDuplicateSubjectName($subject_name) {
        $connection = db_connect();
        $query = "SELECT * FROM subjects WHERE subject_name = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $subject_name);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return "Subject name already exists. Please choose another."; // Return the error message for duplicates
        }
    
        return ''; // No duplicate found
    }
?>