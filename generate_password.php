<?php
// generate_password.php
$password = "Firefox21986";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Password Hash Generator</h3>";
echo "<p><strong>Original Password:</strong> " . htmlspecialchars($password) . "</p>";
echo "<p><strong>Hashed Password:</strong> " . htmlspecialchars($hashed_password) . "</p>";

// Test verification
if (password_verify($password, $hashed_password)) {
    echo "<p style='color: green;'>✓ Password verification successful!</p>";
} else {
    echo "<p style='color: red;'>✗ Password verification failed!</p>";
}

echo "<hr>";
echo "<h4>SQL Query to insert this member:</h4>";
echo "<code>INSERT INTO users (name, email, password, role) VALUES ('Member Name', 'email@example.com', '" . htmlspecialchars($hashed_password) . "', 'member');</code>";
?>