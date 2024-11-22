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
            return $result->fetch_assoc();
        }
    
        return false;
    }

    function login_user($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
    }

    function getBaseURL() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . '/';
    }

    function checkUserSessionIsActive() {
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
            header("Location: admin/dashboard.php");
            exit;
        }
    }

    function logout_user() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location:../index.php");
        exit();
    }

    function validateSubjectData($subject_data) {
        $errors = [];
    
        if (empty($subject_data['subject_code'])) {
            $errors[] = "Subject code is required.";
        } elseif (strlen($subject_data['subject_code']) > 3) {
            $errors[] = "Subject code cannot be longer than 3 characters.";
        }
    
        if (empty($subject_data['subject_name'])) {
            $errors[] = "Subject name is required.";
        } elseif (strlen($subject_data['subject_name']) > 55) {
            $errors[] = "Subject name cannot be longer than 55 characters.";
        }
    
        return $errors;
    }
    
    function checkDuplicateSubjectData($subject_data) {
        $connection = db_connect();
        $query = "SELECT * FROM subjects WHERE subject_code = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $subject_data['subject_code']);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return "Subject code already exists. Please input another code.";
        }
    
        return '';
    }

    function guard() {
        if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
            $baseURL = getBaseURL();
            header("Location: " . $baseURL);
            exit();
        }
    }

    function validateStudentData($student_data) {
        $errors = [];
        if (empty($student_data['student_id'])) {
            $errors[] = "Student ID is required.";
        }
        if (empty($student_data['first_name'])) {
            $errors[] = "First Name is required.";
        }
        if (empty($student_data['last_name'])) {
            $errors[] = "Last Name is required.";
        }
    
        return $errors;
    }
    
    function checkDuplicateStudentData($student_data) {
        $connection = db_connect();
        $query = "SELECT * FROM students WHERE student_id = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $student_data['student_id']);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return "Student ID already exists.";
        }
    
        return '';
    }
    
    
    function generateUniqueIdForStudents() {
        $connection = db_connect();
    
        $query = "SELECT MAX(id) AS max_id FROM students";
        $result = $connection->query($query);
        $row = $result->fetch_assoc();
        $max_id = $row['max_id'];
    
        $connection->close();
    
        return $max_id + 1;
    }
?>