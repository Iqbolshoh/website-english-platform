<?php
// to-signup.php
require_once 'config.php';

$response = array('success' => false, 'message' => '');

// Check if POST data is received
if (isset($_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate the inputs
    if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
        $response['message'] = 'All fields are required.';
    } else {
        // Initialize the Query class
        $query = new Query();

        // Check for existing user
        $existingUser = $query->select('users', 'id', 'WHERE email = ? OR username = ?', [$email, $username], 'ss');
        if (count($existingUser) > 0) {
            $response['message'] = 'Email or Username is already taken.';
        } else {
            // Insert new user
            $hashedPassword = $query->hashPassword($password);
            $userId = $query->insert('users', [
                'fullname' => $fullname,
                'email' => $email,
                'username' => $username,
                'password' => $hashedPassword
            ]);

            if ($userId) {
                $response['success'] = true;
                $response['message'] = 'Registration successful. You can now log in.';
            } else {
                $response['message'] = 'Registration failed. Please try again.';
            }
        }
    }
} else {
    $response['message'] = 'Invalid request.';
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
