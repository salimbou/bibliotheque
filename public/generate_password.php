<?php
/**
 * Password Hash Generator
 * USE THIS FILE TO GENERATE HASHED PASSWORDS FOR ADMIN ACCOUNTS
 * DELETE THIS FILE AFTER USE FOR SECURITY
 */
echo __DIR__;
// Your desired password
$password = 'Reader@123';

// Generate hash
$hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

echo "<h2>Password Hash Generator</h2>";
echo "<p><strong>Original Password:</strong> " . htmlspecialchars($password) . "</p>";
echo "<p><strong>Hashed Password:</strong></p>";
echo "<textarea rows='3' cols='80' onclick='this.select()'>" . $hashedPassword . "</textarea>";
echo "<p><em>Copy this hash and use it in your SQL INSERT statement</em></p>";
echo "<hr>";
echo "<h3>Example SQL:</h3>";
echo "<pre>";
echo "INSERT INTO users (email, password, first_name, last_name, role, status) VALUES
('admin@bibliotheque.com', '" . $hashedPassword . "', 'Admin', 'System', 'admin', 'active');";
echo "</pre>";
echo "<p style='color: red;'><strong>⚠️ DELETE THIS FILE AFTER USE FOR SECURITY!</strong></p>";
?>