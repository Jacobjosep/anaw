<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "anaw_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get total donations
function getTotalDonations($conn) {
    $sql = "SELECT SUM(amount) as total FROM donations WHERE status = 'confirmed'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'] ? $row['total'] : 0;
}

// Function to get member donations
function getMemberDonations($conn, $member_id) {
    $sql = "SELECT SUM(amount) as total FROM donations WHERE member_id = ? AND status = 'confirmed'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ? $row['total'] : 0;
}

// Function to get active projects count
function getActiveProjects($conn) {
    $sql = "SELECT COUNT(*) as count FROM projects WHERE status = 'active'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to get total members
function getTotalMembers($conn) {
    $sql = "SELECT COUNT(*) as count FROM users WHERE role = 'member' AND status = 'active'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}
?>
